<?php

namespace App\Http\Controllers;

use App\Models\GcpMachine;
use App\Models\Server;
use App\Models\AuditLog;
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
    private const APPLIANCE_SHEETS  = ['tulapliance', 'qroapliance', 'aplianceompremise on', 'aplianceompremise off'];

    private array $processedServerIds = [];

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
    private function hasRealChanges(Server $server): bool
    {
        $normalize = fn($v) => (is_null($v) || $v === '' || $v === 'N/A') ? null : trim((string) $v);
        $realFields = [];

        foreach ($server->getDirty() as $field => $newValue) {
            $oldValue = $server->getOriginal($field);
            $isReal = $field === 'state'
                ? $this->normalizeState((string) $newValue) !== $this->normalizeState((string) $oldValue)
                : $normalize($newValue) !== $normalize($oldValue);
            if ($isReal) $realFields[$field] = ['old' => $oldValue, 'new' => $newValue];
        }

        if (!empty($realFields)) {
            \Illuminate\Support\Facades\Log::debug('hasRealChanges', [
                'vm' => $server->vm_according_to_the_vmware,
                'id' => $server->id,
                'f'  => $realFields,
            ]);
        }

        return !empty($realFields);
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

    private function parseExcelDateLike($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            if (is_numeric($value)) {
                return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d H:i:s');
            }

            $v = trim((string) $value);
            $v = preg_replace('/\ba\.?\s?m\.?\b/i', 'AM', $v);
            $v = preg_replace('/\bp\.?\s?m\.?\b/i', 'PM', $v);

            $formats = ['d/m/Y H:i:s A', 'd/m/Y H:i A', 'Y-m-d H:i:s', 'd/m/Y'];
            foreach ($formats as $fmt) {
                try {
                    $dt = Carbon::createFromFormat($fmt, $v);
                    return $dt->format('Y-m-d H:i:s');
                } catch (\Throwable $e) {
                    // try next
                }
            }

            return Carbon::parse($v)->format('Y-m-d H:i:s');
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

        $this->processedServerIds = [];
        $stats = $this->createStats(['servers_on', 'servers_off', 'gcp_machines', 'appliances_on', 'appliances_off']);
        AuditLog::$suppressed = true;

        try {
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
        } finally {
            AuditLog::$suppressed = false;
        }

        return $this->respondWithImportResult(
            $stats,
            'No se encontraron registros nuevos o actualizados en el Excel.'
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
            ->map(fn($alias) => $this->normalizeImportedAliasIp($alias, (string) $internalIp))
            ->filter()
            ->values();

        $ramRaw  = collect($data)->first(fn($v, $k) => str_contains($k, 'memoria ram') && $v);
        $swapRaw = collect($data)->first(fn($v, $k) => str_contains($k, 'memoria swap') && $v);

        $uuid       = $this->getValue($data, ['uuid']);
        $matchKey   = $uuid ? ['uuid' => $uuid] : ['internal_ip' => trim($internalIp)];

        $gcpPayload = [
            'internal_ip'          => trim($internalIp),
            'project_name'         => $this->getValue($data, ['nombre de proyecto'], 'N/A'),
            'environment'          => $this->getValue($data, ['entorno'], 'N/A'),
            'machine_name'         => $this->getValue($data, ['nombre de maquina'], 'N/A'),
            'machine_internal_name' => $this->getValue($data, ['nombre de maquina interna'], 'N/A'),
            'state'                => $this->normalizeState(
                $this->getValue($data, ['state', 'powerstate', 'state / powerstate'])
            ),
            'operations_system'    => $this->getValue($data, ['sistema operativo'], 'N/A'),
            'creation_date'        => $this->parseExcelDateLike($this->getValue($data, [
                'creation date',
                'server creation date',
                'fecha de creacion',
                'fecha de creación',
                'fecha de creacion de servidor',
                'fecha de creación de servidor',
                'creacion de la maquina',
                'creación de la maquina',
            ])),
            'latest_security_patch' => $this->normalizeLatestPatch(
                $this->getValue($data, ['latest security patch', 'ultimo parche de seguridad', 'último parche de seguridad'])
            ),
            'kernel_version'       => $this->getValue($data, ['version de kernel'], 'N/A'),
            'alias_ip'             => $aliases[0] ?? 'N/A',
            'alias2_ip'            => $aliases[1] ?? 'N/A',
            'alias3_ip'            => $aliases[2] ?? 'N/A',
            'other_ips'            => $this->getValue($data, ['other ips', 'otras ips'], 'N/A'),
            'ram_memory'           => max(0, (int) $ramRaw),
            'swap_memory'          => max(0, (int) $swapRaw),
            'created_by'           => Auth::id(),
        ];

        if ($uuid) {
            $gcpPayload['uuid'] = $uuid;
        }

        $machine = GcpMachine::firstOrCreate($matchKey, $gcpPayload);

        if (!$machine->wasRecentlyCreated) {
            $machine->fill($gcpPayload);
            $machine->isDirty() ? $machine->save() : null;
        }

        return $machine->wasRecentlyCreated ? 'created' : ($machine->wasChanged() ? 'updated' : null);
    }

    private function normalizeImportedAliasIp($value, string $internalIp): ?string
    {
        $alias = trim((string) $value);

        if ($alias === '') {
            return null;
        }

        if (!preg_match('/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})(?:\/(\d{1,2}))?$/', $alias, $matches)) {
            return $alias;
        }

        $firstOctet = (int) $matches[1];
        $octet2 = (int) $matches[2];
        $octet3 = (int) $matches[3];
        $octet4 = (int) $matches[4];
        $mask = $matches[5] ?? null;

        if (
            $firstOctet > 255 ||
            $octet2 > 255 ||
            $octet3 > 255 ||
            $octet4 > 255
        ) {
            return $alias;
        }

        if ($firstOctet === 0 && preg_match('/^(\d{1,3})\./', trim($internalIp), $internalMatches)) {
            $candidate = (int) $internalMatches[1];
            if ($candidate >= 1 && $candidate <= 255) {
                $firstOctet = $candidate;
            }
        }

        if ($mask !== null) {
            if ($mask === '3') {
                $mask = '32';
            } elseif ((int) $mask > 32) {
                return $alias;
            }
        }

        $normalized = sprintf('%d.%d.%d.%d', $firstOctet, $octet2, $octet3, $octet4);

        return $mask !== null ? $normalized . '/' . $mask : $normalized;
    }

    private function importServerRow(array $data, bool $isAppliance = false): ?array
    {
        $primaryIp = $this->getValue($data, [
            'primary ip address',
            'primary ip address ',
            'primary ip address.1',
            'ip primaria',
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

        $ramRaw  = collect($data)->first(fn($v, $k) => str_contains(strtolower($k), 'ram') && $v);
        $swapRaw = collect($data)->first(fn($v, $k) => str_contains(strtolower($k), 'swap') && $v);

        $typeApplicationId = $isAppliance
            ? ApplianceController::getApplianceTypeApplicationId()
            : 1;
        $uuid = $this->getValue($data, ['uuid']);
        $excelData = array_filter([
            'uuid' => $uuid ?: null,
            'vm_according_to_the_vmware' => $vm ?: null,
            'primary_ip_address' => $primaryIp ?: null,
            'state' => $this->normalizeState(
                $this->getValue($data, ['state', 'powerstate', 'state / powerstate'])
            ),
            'datacenter' => $this->getValue($data, ['datacenter']),
            'environment' => $this->getValue($data, ['environment', 'enviroment', 'entorno']),
            'os_according_to_the_vmware' => $this->getValue($data, [
                'os according to the wmware',
                'os according wmware',
                'os according to the vmware tools',
                'os according to the vmware',
                'sistema operativo',
            ]),
            'os_version_internal' => $this->getValue($data, [
                'real os',
                'real os internal',
                'os according to the configuration file',
                'versión interna',
                'version interna',
                'version so interno',
                'versión so interno',
            ]),
            'hostname_internal' => $this->getValue($data, ['hostname real', 'real hostname', 'hostname interno',]),
            'ip_user' => $this->getValue($data, ['ip', 'IP usuario', 'ip usuario']),
            'ip_monitoring' => $this->getValue($data, ['monitoreo', 'iP monitoreo', 'ip monitoreo']),
            'dns_name' => $this->getValue($data, ['dns name', 'dns',]),
            'other_ips' => $otherIps ?: $this->getValue($data, ['other ips', 'otras ips']),
            'latest_security_patch' => $this->normalizeLatestPatch(
                $this->getValue($data, ['latest security patch', 'ultimo parche de seguridad', 'último parche de seguridad'])
            ),
            'creation_date' => $this->parseExcelDateLike($this->getValue($data, [
                'creation date',
                'server creation date',
                'fecha de creacion',
                'fecha de creación',
                'fecha de creacion de servidor',
                'fecha de creación de servidor',
                'creacion de la maquina',
                'creación de la maquina',
            ])),
            'comments' => $this->getValue($data, ['comments', 'comentarios']),
            'ram_memory'  => $ramRaw !== null ? max(0, (int) $ramRaw) : null,
            'swap_memory' => $swapRaw !== null ? max(0, (int) $swapRaw) : null,
        ], fn($v) => $v !== null);
        $createOnly = [
            'owner_id' => null,
            'created_by' => Auth::id(),
            'type_application_id' => $typeApplicationId,
            'vm_according_to_the_vmware' => $vm ?: 'N/A',
            'hostname_internal' => $vm ?: 'N/A',
            'state' => 'poweredOn',
            'datacenter' => 'N/A',
            'environment' => 'N/A',
            'os_according_to_the_vmware' => 'N/A',
            'os_version_internal' => 'N/A',
            'ip_user' => 'N/A',
            'ip_monitoring' => 'N/A',
            'dns_name' => 'N/A',
            'other_ips' => 'N/A',
            'comments' => 'N/A',
            'ram_memory' => 0,
            'swap_memory' => 0,
        ];

        $server = collect([
            fn() => $uuid      ? Server::withTrashed()->where('uuid', $uuid)->where('type_application_id', $typeApplicationId)->first()                     : null,
            fn() => $primaryIp ? Server::withTrashed()->where('primary_ip_address', $primaryIp)->where('type_application_id', $typeApplicationId)->first()  : null,
            fn() => $vm        ? Server::withTrashed()->where('vm_according_to_the_vmware', $vm)->where('type_application_id', $typeApplicationId)->first() : null,
        ])->reduce(fn($found, $finder) => $found ?? $finder());

        if ($server && in_array($server->id, $this->processedServerIds)) {
            return [
                'status' => null,
                'state'  => $excelData['state'] ?? 'poweredOn',
            ];
        }

        $server ??= Server::create($excelData + $createOnly);

        $hasChanges = !$server->wasRecentlyCreated
            && $server->fill($excelData)
            && $this->hasRealChanges($server)
            && $server->save();

        $this->processedServerIds[] = $server->id;

        $server->trashed() && $server->restore();

        return [
            'status' => $server->wasRecentlyCreated ? 'created' : ($hasChanges ? 'updated' : null),
            'state'  => $excelData['state'] ?? 'poweredOn',
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
            'ip primaria',
        ]);

        $uuid = $this->getValue($data, ['uuid']);
        $excelData = array_filter([
            'uuid' => $uuid ?: null,
            'vm_according_to_the_vmware' => $vm ?: null,
            'primary_ip_address' => $primaryIp ?: null,
            'state' => 'poweredOff',
            'datacenter' => $this->getValue($data, ['datacenter']),
            'environment' => $this->getValue($data, ['environment', 'entorno']),
            'creation_date' => $this->parseExcelDateLike($this->getValue($data, [
                'creation date',
                'server creation date',
                'fecha de creacion',
                'fecha de creación',
                'fecha de creacion de servidor',
                'fecha de creación de servidor',
                'creacion de la maquina',
                'creación de la maquina',
            ])),
            'hostname_internal' => $this->getValue($data, ['hostname internal', 'hostname real', 'real hostname']),
            'os_according_to_the_vmware' => $this->getValue($data, [
                'os according to the vmware tools',
                'os according to the vmware',
                'os according to the wmware',
                'os according wmware',
            ]),
            'os_version_internal' => $this->getValue($data, [
                'os according to the configuration file',
                'os_version_internal',
                'real os',
                'real os internal',
                'versión so interno'
            ]),
        ], fn($v) => $v !== null);

        $createOnly = [
            'owner_id' => null,
            'created_by' => Auth::id(),
            'type_application_id' => 1,
            'vm_according_to_the_vmware' => $vm ?: 'N/A',
            'hostname_internal' => $vm ?: 'N/A',
            'state' => 'poweredOff',
            'datacenter' => 'N/A',
            'environment' => 'N/A',
            'os_according_to_the_vmware' => 'N/A',
            'os_version_internal' => 'N/A',
            'ip_user' => 'N/A',
            'ip_monitoring' => 'N/A',
            'dns_name' => 'N/A',
            'other_ips' => 'N/A',
            'comments' => 'N/A',
            'ram_memory' => 0,
            'swap_memory' => 0,
        ];

        $server = collect([
            fn() => $uuid      ? Server::withTrashed()->where('uuid', $uuid)->where('type_application_id', 1)->first()                     : null,
            fn() => $primaryIp ? Server::withTrashed()->where('primary_ip_address', $primaryIp)->where('type_application_id', 1)->first()  : null,
            fn() => $vm        ? Server::withTrashed()->where('vm_according_to_the_vmware', $vm)->where('type_application_id', 1)->first() : null,
        ])->reduce(fn($found, $finder) => $found ?? $finder());

        if ($server && in_array($server->id, $this->processedServerIds)) {
            return null;
        }

        $server ??= Server::create($excelData + $createOnly);

        $hasChanges = !$server->wasRecentlyCreated
            && $server->fill($excelData)
            && $this->hasRealChanges($server)
            && $server->save();

        $this->processedServerIds[] = $server->id;

        $server->trashed() && $server->restore();

        return $server->wasRecentlyCreated ? 'created' : ($hasChanges ? 'updated' : null);
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
