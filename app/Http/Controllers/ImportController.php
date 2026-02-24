<?php

namespace App\Http\Controllers;

use App\Models\GcpMachine;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ImportController extends Controller
{
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
        return collect($header)
            ->map(fn($value, $key) => strtolower(trim((string) $value)))
            ->map(function ($value, $key) use ($header) {
                static $used = [];
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
        return $value === ''
            ? $default
            : (in_array($value, Server::POWERED_OFF_VALUES, true) ? 'poweredOff' : 'poweredOn');
    }

    private function normalizeLatestPatch($value): ?string
    {
        if (!$value) return null;

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
        return count($headers) === count($row)
            ? array_combine($headers, $row)
            : null;
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        foreach (Excel::toCollection(null, $request->file('file')) as $rows) {

            if ($rows->isEmpty()) continue;
            $headers = $this->normalizeHeaders($rows->first()->toArray());
            foreach ($rows->skip(1) as $row) {

                $data = $this->buildData($headers, $row->toArray());
                if (!$data) continue;

                $isGcp = collect($headers)
                    ->contains(fn($h) => str_contains($h, 'nombre de maquina'));
                $isGcp
                    ? $this->importGcpRow($data)
                    : $this->importServerRow($data);
            }
        }

        return back()->with('success', 'Excel importado correctamente');
    }

    private function importGcpRow(array $data): void
    {
        $internalIp = collect($data)
            ->first(fn($value, $key) => str_contains(strtolower($key), 'ip interna') && $value);

        if (!$internalIp) return;

        $aliases = collect($data)
            ->filter(fn($v, $k) => preg_match('/^ip alias/i', $k) && $v)
            ->flatMap(fn($v) => preg_split('/\r\n|\r|\n/', $v))
            ->map(fn($ip) => trim($ip))
            ->filter()
            ->values();

        GcpMachine::updateOrCreate(
            ['internal_ip' => trim($internalIp)],
            [
                'project_name' => $data['nombre de proyecto'] ?? 'N/A',
                'environment' => $data['entorno'] ?? 'N/A',
                'machine_name' => $data['nombre de maquina'] ?? 'N/A',
                'machine_internal_name' => $data['nombre de maquina interna'] ?? 'N/A',
                'operations_system' => $data['sistema operativo'] ?? 'N/A',
                'kernel_version' => $data['version de kernel'] ?? null,
                'alias_ip'  => $aliases[0] ?? null,
                'alias2_ip' => $aliases[1] ?? null,
                'alias3_ip' => $aliases[2] ?? null,
                'ram_memory' => (int) collect($data)->first(fn($v, $k) => str_contains($k, 'memoria ram') && $v),
                'swap_memory' => (int) collect($data)->first(fn($v, $k) => str_contains($k, 'memoria swap') && $v),
            ]
        );
    }

    private function importServerRow(array $data): void
    {
        $primaryIp = $this->getValue($data, [
            'primary ip address',
            'primary ip address ',
            'primary ip address.1'
        ]);

        if (!$primaryIp) return;

        $otherIps = collect(['ip2', 'ip3', 'ip4', 'ip5'])
            ->map(fn($key) => $this->getValue($data, [$key]))
            ->filter()
            ->flatMap(fn($v) => preg_split('/\r\n|\r|\n/', $v))
            ->map(fn($ip) => trim($ip))
            ->filter()
            ->unique()
            ->implode(', ');

        $server = Server::withTrashed()->updateOrCreate(
            ['primary_ip_address' => $primaryIp],
            [
                'owner_id' => Auth::id(),
                'type_application_id' => 1,
                'vm_according_to_the_vmware' => $this->getValue($data, ['vm']),
                'state' => $this->normalizeState(
                    $this->getValue($data, ['state', 'powerstate', 'state / powerstate'])
                ),
                'datacenter' => $this->getValue($data, ['datacenter']),
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
                ]),
                'hostname_internal' => $this->getValue($data, [
                    'hostname real',
                    'real hostname'
                ]),
                'ip_user' => $this->getValue($data, ['ip']),
                'ip_monitoring' => $this->getValue($data, ['monitoreo']),
                'dns_name' => $this->getValue($data, ['dns name']),
                'other_ips' => $otherIps,
                'latest_security_patch' => $this->normalizeLatestPatch(
                    $this->getValue($data, ['latest security patch'])
                ),
                'comments' => $this->getValue($data, ['comments', 'comentarios']),
                'ram_memory' => 0,
                'swap_memory' => 0,
            ]
        );

        if ($server->trashed()) {
            $server->restore();
        }
    }

    public function importPoweredOff(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');
        $sheets = Excel::toCollection(null, $file);
        $sheetNames = IOFactory::load($file->getRealPath())->getSheetNames();

        $allowed = ['bajatultitlan', 'tuloff', 'qrooff'];
        $count = 0;

        foreach ($sheets as $i => $rows) {

            $name = strtolower(trim((string) ($sheetNames[$i] ?? '')));
            if (!in_array($name, $allowed, true) || $rows->isEmpty()) continue;

            $headers = $this->normalizeHeaders($rows->first()->toArray());

            foreach ($rows->skip(1) as $row) {

                $data = $this->buildData($headers, $row->toArray());
                if (!$data) continue;

                $vm = $this->getValue($data, ['vm']);
                if (!$vm) continue;

                $primaryIp = $this->getValue($data, ['primary ip address']);

                $payload = [
                    'owner_id' => Auth::id(),
                    'type_application_id' => 1,
                    'vm_according_to_the_vmware' => $vm,
                    'state' => 'poweredOff',
                    'dns_name' => $this->getValue($data, ['dns name']),
                    'primary_ip_address' => $primaryIp ?: null,
                    'datacenter' => $this->getValue($data, ['datacenter']),
                    'environment' => $this->getValue($data, ['environment', 'entorno'], 'N/A'),
                    'hostname_internal' => $this->getValue(
                        $data,
                        ['hostname internal', 'hostname real', 'real hostname'],
                        $vm
                    ),
                    'os_version_internal' => $this->getValue($data, [
                        'os according to the configuration file',
                        'os_version_internal',
                        'real os',
                        'real os internal',
                    ]),
                    'os_according_to_the_vmware' => $this->getValue($data, [
                        'os according to the vmware tools',
                        'os according to the vmware',
                        'os according to the wmware',
                        'os according wmware',
                    ]),
                    'latest_security_patch' => $this->normalizeLatestPatch(
                        $this->getValue($data, ['latest security patch'])
                    ),
                    'comments' => $this->getValue($data, ['comments', 'comentarios']),
                    'ram_memory' => 0,
                    'swap_memory' => 0,
                ];

                $server = $primaryIp
                    ? Server::withTrashed()->where('primary_ip_address', $primaryIp)->first()
                    : Server::withTrashed()
                    ->where('vm_according_to_the_vmware', $vm)
                    ->orderByDesc('id')
                    ->first();
                $server
                    ? $server->fill($payload)->save()
                    : $server = Server::create($payload);

                if ($server->trashed()) {
                    $server->restore();
                }

                $count++;
            }
        }

        return back()->with(
            'success',
            $count === 0
                ? 'No se importaron filas: revisa que existan datos en BajaTultitlan, TulOff y QroOff.'
                : "Excel importado correctamente ({$count} servidores apagados)."
        );
    }
}
