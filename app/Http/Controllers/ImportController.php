<?php

namespace App\Http\Controllers;

use App\Models\GcpMachine;
use App\Models\Server;
use App\Http\Controllers\ApplianceController;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ImportController extends Controller
{
    private const FORCED_OFF_SHEETS = ['bajatultitlan', 'tuloff', 'qrooff'];
    private const APPLIANCE_SHEETS  = ['tulapliance', 'qroapliance'];

    private function getValue(array $data, array $keys, $default = null)
    {
        foreach ($keys as $key) {
            $value = $data[$key] ?? null;
            if ($value !== null && $value !== '') {
                return trim($value);
            }
        }

        return $default;
    }

    private function normalizeHeaders(array $header): array
    {
        $used = [];

        return collect($header)
            ->map(fn($value) => strtolower(trim((string) $value)))
            ->map(function ($value, $key) use (&$used) {
                if (in_array($value, $used, true)) {
                    $value .= '_' . $key;
                }

                $used[] = $value;
                return $value;
            })
            ->toArray();
    }

    private function normalizeState(?string $state, string $default = 'poweredOn'): string
    {
        $value = strtolower(trim((string) $state));

        return match (true) {
            $value === '' => $default,
            in_array($value, Server::POWERED_OFF_VALUES, true) => 'poweredOff',
            default => 'poweredOn'
        };
    }

    private function normalizeLatestPatch($value): ?string
    {
        if (!$value) {
            return null;
        }

        try {
            return is_numeric($value)
                ? ExcelDate::excelToDateTimeObject($value)->format('Y-m-d')
                : Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function buildData(array $headers, array $row): ?array
    {
        return count($headers) !== count($row)
            ? null
            : array_combine($headers, $row);
    }

    private function readWorkbook($file): array
    {
        return [
            Excel::toCollection(null, $file),
            IOFactory::load($file->getRealPath())->getSheetNames(),
        ];
    }

    private function createStats(array $buckets): array
    {
        return collect($buckets)
            ->mapWithKeys(fn($bucket) => [$bucket => ['created' => 0, 'updated' => 0]])
            ->all();
    }

    private function incrementStats(array &$stats, string $bucket, ?string $status): void
    {
        $status && isset($stats[$bucket][$status]) && $stats[$bucket][$status]++;
    }

    private function totalProcessed(array $stats): int
    {
        return collect($stats)->sum(fn($bucket) => array_sum($bucket));
    }

    private function getBucketLabel(string $bucket): string
    {
        return match ($bucket) {
            'servers_on'      => 'Servidores encendidos',
            'servers_off'     => 'Servidores apagados',
            'gcp_machines'    => 'Maquinas GCP',
            'appliances_on'   => 'Apliances encendidos',
            'appliances_off'  => 'Apliances apagados',
            default           => ucwords(str_replace('_', ' ', $bucket)),
        };
    }

    private function isApplianceSheet(?string $sheetName): bool
    {
        return in_array(strtolower(trim((string) $sheetName)), self::APPLIANCE_SHEETS, true);
    }

    private function buildImportSummary(array $stats): array
    {
        $hidden = ['appliances_off'];

        return collect($stats)
            ->filter(fn($_, $bucket) => !in_array($bucket, $hidden, true))
            ->map(function (array $bucketStats, string $bucket) {
                $created = (int) ($bucketStats['created'] ?? 0);
                $updated = (int) ($bucketStats['updated'] ?? 0);

                return [
                    'bucket'  => $bucket,
                    'label'   => $this->getBucketLabel($bucket),
                    'total'   => $created + $updated,
                    'created' => $created,
                    'updated' => $updated,
                ];
            })
            ->values()
            ->all();
    }

    private function respondWithImportResult(
        array $stats,
        string $emptyMessage,
        string $successMessage = 'Excel importado correctamente.'
    ) {
        if ($this->totalProcessed($stats) === 0) {
            return back()->with('success', $emptyMessage);
        }

        return back()
            ->with('success', $successMessage)
            ->with('import_summary', $this->buildImportSummary($stats));
    }

    private function isGcpHeaders(array $headers): bool
    {
        return collect($headers)
            ->contains(fn($header) => str_contains($header, 'nombre de maquina'));
    }

    private function isForcedOffSheet(?string $sheetName): bool
    {
        return in_array(strtolower(trim((string) $sheetName)), self::FORCED_OFF_SHEETS, true);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        [$sheets, $sheetNames] = $this->readWorkbook($request->file('file'));

        $stats = $this->createStats(['servers_on', 'servers_off', 'gcp_machines', 'appliances_on', 'appliances_off']);

        foreach ($sheets as $i => $rows) {

            if ($rows->isEmpty()) {
                continue;
            }

            $sheetName   = $sheetNames[$i] ?? null;
            $headers     = $this->normalizeHeaders($rows->first()->toArray());
            $isForcedOff = $this->isForcedOffSheet($sheetName);
            $isAppliance = $this->isApplianceSheet($sheetName);

            foreach ($rows->skip(1) as $row) {

                $data = $this->buildData($headers, $row->toArray());
                if (!$data) {
                    continue;
                }

                $result = $isForcedOff
                    ? [
                        'bucket' => 'servers_off',
                        'status' => $this->importForcedOffServerRow($data)
                    ]
                    : $this->resolveGeneralRowImport($headers, $data, $isAppliance);

                $this->incrementStats($stats, $result['bucket'], $result['status']);
            }
        }

        return $this->respondWithImportResult(
            $stats,
            'No se importaron registros validos desde el Excel.'
        );
    }

    private function resolveGeneralRowImport(array $headers, array $data, bool $isAppliance = false): array
    {
        if ($this->isGcpHeaders($headers)) {
            return [
                'bucket' => 'gcp_machines',
                'status' => $this->importGcpRow($data),
            ];
        }

        $result = $this->importServerRow($data, $isAppliance);

        if ($isAppliance) {
            return [
                'bucket' => ($result['state'] ?? null) === 'poweredOff'
                    ? 'appliances_off'
                    : 'appliances_on',
                'status' => $result['status'] ?? null,
            ];
        }

        return [
            'bucket' => ($result['state'] ?? null) === 'poweredOff'
                ? 'servers_off'
                : 'servers_on',
            'status' => $result['status'] ?? null,
        ];
    }

    private function importGcpRow(array $data): ?string
    {
        $internalIp = collect($data)
            ->first(fn($value, $key) => str_contains(strtolower($key), 'ip interna') && $value);

        if (!$internalIp) {
            return null;
        }

        $aliases = collect($data)
            ->filter(fn($v, $k) => preg_match('/^ip alias/i', $k) && $v)
            ->flatMap(fn($v) => preg_split('/\r\n|\r|\n/', $v))
            ->map('trim')
            ->filter()
            ->values();

        $ramRaw  = collect($data)->first(fn($v, $k) => str_contains($k, 'memoria ram') && $v);
        $swapRaw = collect($data)->first(fn($v, $k) => str_contains($k, 'memoria swap') && $v);

        $machine = GcpMachine::updateOrCreate(
            ['internal_ip' => trim($internalIp)],
            [
                'project_name' => $this->getValue($data, ['nombre de proyecto'], 'N/A'),
                'environment' => $this->getValue($data, ['entorno'], 'N/A'),
                'machine_name' => $this->getValue($data, ['nombre de maquina'], 'N/A'),
                'machine_internal_name' => $this->getValue($data, ['nombre de maquina interna'], 'N/A'),
                'state' => $this->normalizeState(
                    $this->getValue($data, ['state', 'powerstate', 'state / powerstate'])
                ),
                'operations_system' => $this->getValue($data, ['sistema operativo'], 'N/A'),
                'kernel_version' => $this->getValue($data, ['version de kernel'], 'N/A'),
                'alias_ip' => $aliases[0] ?? 'N/A',
                'alias2_ip' => $aliases[1] ?? 'N/A',
                'alias3_ip' => $aliases[2] ?? 'N/A',
                'ram_memory' => max(0, (int) $ramRaw),
                'swap_memory' => max(0, (int) $swapRaw),
            ]
        );

        return $machine->wasRecentlyCreated ? 'created' : 'updated';
    }

    private function importServerRow(array $data, bool $isAppliance = false): ?array
    {
        $primaryIp = $this->getValue($data, [
            'primary ip address',
            'primary ip address ',
            'primary ip address.1'
        ]);

        $vm = $this->getValue($data, ['vm']);

        if (!$primaryIp && !$vm) {
            return null;
        }

        $otherIps = collect(['ip2', 'ip3', 'ip4', 'ip5'])
            ->map(fn($key) => $this->getValue($data, [$key]))
            ->filter()
            ->flatMap(fn($v) => preg_split('/\r\n|\r|\n/', $v))
            ->map('trim')
            ->filter()
            ->unique()
            ->implode(', ');

        $typeApplicationId = $isAppliance
            ? ApplianceController::getApplianceTypeApplicationId()
            : 1;

        $payload = [
            'owner_id' => Auth::id(),
            'created_by' => Auth::id(),
            'type_application_id' => $typeApplicationId,
            'vm_according_to_the_vmware' => $vm ?: 'N/A',
            'state' => $this->normalizeState(
                $this->getValue($data, ['state', 'powerstate', 'state / powerstate'])
            ),
            'datacenter' => $this->getValue($data, ['datacenter'], 'N/A'),
            'environment' => $this->getValue($data, ['enviroment', 'entorno'], 'N/A'),
            'os_according_to_the_vmware' => $this->getValue($data, [
                'os according to the wmware',
                'os according wmware',
                'os according to the vmware tools',
                'os according to the vmware'
            ], 'N/A'),
            'os_version_internal' => $this->getValue($data, [
                'real os',
                'real os internal',
                'os according to the configuration file'
            ], 'N/A'),
            'hostname_internal' => $this->getValue($data, [
                'hostname real',
                'real hostname'
            ], $vm ?: 'N/A'),
            'ip_user' => $this->getValue($data, ['ip'], 'N/A'),
            'ip_monitoring' => $this->getValue($data, ['monitoreo'], 'N/A'),
            'dns_name' => $this->getValue($data, ['dns name'], 'N/A'),
            'other_ips' => $otherIps !== '' ? $otherIps : 'N/A',
            'latest_security_patch' => $this->normalizeLatestPatch(
                $this->getValue($data, ['latest security patch'])
            ),
            'comments' => $this->getValue($data, ['comments', 'comentarios'], 'N/A'),
            'ram_memory' => 0,
            'swap_memory' => 0,
        ];

        $server = $primaryIp
            ? Server::withTrashed()->updateOrCreate(
                ['primary_ip_address' => $primaryIp],
                $payload
            )
            : Server::updateOrCreate(
                ['vm_according_to_the_vmware' => $vm],
                $payload
            );

        $server->trashed() && $server->restore();

        return [
            'status' => $server->wasRecentlyCreated ? 'created' : 'updated',
            'state'  => $payload['state'],
        ];
    }

    private function importForcedOffServerRow(array $data): ?string
    {
        $vm = $this->getValue($data, ['vm']);

        if (!$vm) {
            return null;
        }

        $primaryIp = $this->getValue($data, [
            'primary ip address',
            'primary ip address ',
            'primary ip address.1',
        ]);

        $payload = [
            'owner_id' => Auth::id(),
            'created_by' => Auth::id(),
            'type_application_id' => 1,
            'vm_according_to_the_vmware' => $vm,
            'state' => 'poweredOff',
            'environment' => $this->getValue($data, ['environment', 'entorno'], 'N/A'),
            'datacenter' => $this->getValue($data, ['datacenter'], 'N/A'),
            'hostname_internal' => $this->getValue(
                $data,
                ['hostname internal', 'hostname real', 'real hostname'],
                $vm
            ),
            'os_according_to_the_vmware' => $this->getValue($data, [
                'os according to the vmware tools',
                'os according to the vmware',
                'os according to the wmware',
                'os according wmware',
            ], 'N/A'),
            'os_version_internal' => $this->getValue($data, [
                'os according to the configuration file',
                'os_version_internal',
                'real os',
                'real os internal',
            ], 'N/A'),
            'ram_memory' => 0,
            'swap_memory' => 0,
        ];

        $server = $primaryIp
            ? Server::withTrashed()->updateOrCreate(
                ['primary_ip_address' => $primaryIp],
                $payload
            )
            : Server::withTrashed()->updateOrCreate(
                ['vm_according_to_the_vmware' => $vm],
                $payload
            );

        $server->trashed() && $server->restore();

        return $server->wasRecentlyCreated ? 'created' : 'updated';
    }

    public function importPoweredOff(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        [$sheets, $sheetNames] = $this->readWorkbook($request->file('file'));
        $stats = $this->createStats(['servers_off']);

        foreach ($sheets as $i => $rows) {
            if ($rows->isEmpty() || !$this->isForcedOffSheet($sheetNames[$i] ?? null)) {
                continue;
            }

            $headers = $this->normalizeHeaders($rows->first()->toArray());

            foreach ($rows->skip(1) as $row) {
                $data = $this->buildData($headers, $row->toArray());
                if (!$data) {
                    continue;
                }

                $this->incrementStats(
                    $stats,
                    'servers_off',
                    $this->importForcedOffServerRow($data)
                );
            }
        }

        return $this->respondWithImportResult(
            $stats,
            'No se importaron filas: revisa que existan datos en BajaTultitlan, TulOff y QroOff.'
        );
    }
}
