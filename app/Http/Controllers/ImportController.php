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
    private const APPLIANCE_SHEETS  = ['tulapliance', 'qroapliance'];
    private ?array $serverHistoricIdentityMap = null;

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
            ->mapWithKeys(fn($bucket) => [$bucket => [
                'created' => 0,
                'updated' => 0,
                'preserved' => 0,
                'unchanged' => 0,
            ]])
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

    private function buildOverallStats(array $stats): array
    {
        return collect($stats)->reduce(function (array $carry, array $bucket) {
            foreach ($carry as $key => $value) {
                $carry[$key] += (int) ($bucket[$key] ?? 0);
            }

            return $carry;
        }, [
            'created' => 0,
            'updated' => 0,
            'preserved' => 0,
            'unchanged' => 0,
        ]);
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

    private function applianceTypeApplicationId(): int
    {
        return ApplianceController::getApplianceTypeApplicationId();
    }

    private function serverImportIdentityPrefix(bool $isAppliance = false): string
    {
        return $isAppliance ? 'appliance_record' : 'server_record';
    }

    private function buildImportSummary(array $stats): array
    {
        $hidden = ['appliances_off'];

        return collect($stats)
            ->filter(fn($_, $bucket) => !in_array($bucket, $hidden, true))
            ->map(function (array $bucketStats, string $bucket) {
                $created = (int) ($bucketStats['created'] ?? 0);
                $updated = $this->reportedUpdatedCount($bucketStats);

                return [
                    'bucket' => $bucket,
                    'label' => $this->getBucketLabel($bucket),
                    'total' => array_sum($bucketStats),
                    'created' => $created,
                    'updated' => $updated,
                    'preserved' => (int) ($bucketStats['preserved'] ?? 0),
                    'unchanged' => (int) ($bucketStats['unchanged'] ?? 0),
                ];
            })
            ->values()
            ->all();
    }

    private function reportedUpdatedCount(array $stats): int
    {
        return (int) ($stats['updated'] ?? 0) + (int) ($stats['preserved'] ?? 0);
    }

    private function buildSuccessMessage(array $stats): string
    {
        $totals = $this->buildOverallStats($stats);

        return sprintf(
            'Excel importado correctamente. Procesados: %d. Actualizados: %d.',
            array_sum($totals),
            $this->reportedUpdatedCount($totals),
        );
    }

    private function respondWithImportResult(
        array $stats,
        string $emptyMessage,
        ?string $successMessage = null
    ) {
        return $this->totalProcessed($stats) === 0
            ? back()->with('success', $emptyMessage)
            : back()
                ->with('success', $successMessage ?? $this->buildSuccessMessage($stats))
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

    private function collectImportCandidates($sheets, array $sheetNames): array
    {
        $candidates = [];
        $order = 0;

        foreach ($sheets as $i => $rows) {
            if ($rows->isEmpty()) {
                continue;
            }

            $sheetName = $sheetNames[$i] ?? null;
            $headers = $this->normalizeHeaders($rows->first()->toArray());
            $isForcedOff = $this->isForcedOffSheet($sheetName);
            $isAppliance = $this->isApplianceSheet($sheetName);

            foreach ($rows->skip(1) as $row) {
                $data = $this->buildData($headers, $row->toArray());

                if (!$data) {
                    continue;
                }

                $candidate = $isForcedOff
                    ? $this->buildForcedOffImportCandidate($data)
                    : $this->buildGeneralImportCandidate($headers, $data, $isAppliance);

                if (!$candidate) {
                    continue;
                }

                $candidate['order'] = $order++;
                $candidate['dedupe_key'] = $this->resolveCandidateDedupeKey($candidate);
                $this->rememberImportCandidate($candidates, $candidate);
            }
        }

        return $this->filterUniqueImportCandidates(array_values($candidates));
    }

    private function buildGeneralImportCandidate(array $headers, array $data, bool $isAppliance = false): ?array
    {
        return $this->isGcpHeaders($headers)
            ? $this->buildGcpImportCandidate($data)
            : $this->buildServerImportCandidate($data, $isAppliance);
    }

    private function buildGcpImportCandidate(array $data): ?array
    {
        $internalIp = collect($data)
            ->first(fn($value, $key) => str_contains(strtolower($key), 'ip interna') && $value);

        if (!$internalIp) {
            return null;
        }

        return [
            'key' => $this->buildPrimaryImportIdentityKey(
                'gcp',
                $this->getValue($data, ['uuid']),
                $this->getValue($data, ['nombre de maquina', 'nombre de maquina interna']),
                trim($internalIp)
            ),
            'aliases' => $this->buildImportIdentityAliases(
                'gcp',
                $this->getValue($data, ['uuid']),
                $this->getValue($data, ['nombre de maquina', 'nombre de maquina interna']),
                trim($internalIp)
            ),
            'bucket' => 'gcp_machines',
            'handler' => 'gcp',
            'data' => $data,
            'priority' => $this->scoreImportCandidate(
                $data,
                false,
                [
                    'ip interna',
                    'nombre de proyecto',
                    'nombre de maquina',
                    'nombre de maquina interna',
                    'sistema operativo',
                ]
            ),
        ];
    }

    private function buildServerImportCandidate(array $data, bool $isAppliance = false): ?array
    {
        $prefix = $this->serverImportIdentityPrefix($isAppliance);
        $primaryIp = $this->getValue($data, [
            'primary ip address',
            'primary ip address ',
            'primary ip address.1',
        ]);
        $vm = $this->getValue($data, ['vm']);
        $hostname = $this->getValue($data, ['hostname real', 'real hostname', 'hostname internal']);
        $userIp = $this->getValue($data, ['ip']);
        $dnsName = $this->getValue($data, ['dns name']);

        if (!$primaryIp && !$vm) {
            return null;
        }

        return [
            'key' => $this->buildPrimaryImportIdentityKey(
                $prefix,
                $this->getValue($data, ['uuid']),
                $vm,
                $primaryIp
            ),
            'aliases' => array_values(array_unique(array_merge(
                $this->buildImportIdentityAliases(
                    $prefix,
                    $this->getValue($data, ['uuid']),
                    $vm,
                    $primaryIp
                ),
                $this->buildImportValueAliases($prefix, 'ip', [$userIp]),
                $this->buildImportValueAliases($prefix, 'host', [$hostname]),
                $this->buildImportValueAliases($prefix, 'dns', [$dnsName])
            ))),
            'handler' => 'server',
            'is_appliance' => $isAppliance,
            'data' => $data,
            'priority' => $this->scoreImportCandidate(
                $data,
                false,
                [
                    'primary ip address',
                    'vm',
                    'hostname real',
                    'real hostname',
                    'datacenter',
                    'enviroment',
                    'entorno',
                ]
            ) + ($isAppliance ? 200 : 0),
        ];
    }

    private function buildForcedOffImportCandidate(array $data): ?array
    {
        $prefix = $this->serverImportIdentityPrefix(false);
        $vm = $this->getValue($data, ['vm']);
        $primaryIp = $this->getValue($data, [
            'primary ip address',
            'primary ip address ',
            'primary ip address.1',
        ]);
        $hostname = $this->getValue($data, ['hostname internal', 'hostname real', 'real hostname']);

        if (!$vm) {
            return null;
        }

        return [
            'key' => $this->buildPrimaryImportIdentityKey(
                $prefix,
                $this->getValue($data, ['uuid']),
                $vm,
                $primaryIp
            ),
            'aliases' => array_values(array_unique(array_merge(
                $this->buildImportIdentityAliases(
                    $prefix,
                    $this->getValue($data, ['uuid']),
                    $vm,
                    $primaryIp
                ),
                $this->buildImportValueAliases($prefix, 'host', [$hostname])
            ))),
            'bucket' => 'servers_off',
            'handler' => 'forced_off',
            'data' => $data,
            'priority' => $this->scoreImportCandidate(
                $data,
                true,
                [
                    'vm',
                    'datacenter',
                    'environment',
                    'entorno',
                ]
            ),
        ];
    }

    private function buildPrimaryImportIdentityKey(string $prefix, ?string $uuid, ?string $vm, ?string $ip): ?string
    {
        return $this->buildImportIdentityAliases($prefix, $uuid, $vm, $ip)[0] ?? null;
    }

    private function buildImportIdentityAliases(string $prefix, ?string $uuid, ?string $vm, ?string $ip): array
    {
        $uuid = trim((string) $uuid);
        $vm = trim((string) $vm);
        $ip = trim((string) $ip);

        return array_values(array_unique(array_filter([
            $uuid !== '' ? $prefix . ':uuid:' . strtolower($uuid) : null,
            $vm !== '' ? $prefix . ':vm:' . strtolower($vm) : null,
            $ip !== '' ? $prefix . ':ip:' . strtolower($ip) : null,
        ])));
    }

    private function buildImportValueAliases(string $prefix, string $kind, array $values): array
    {
        return collect($values)
            ->map(fn($value) => trim((string) $value))
            ->filter()
            ->map(fn($value) => $prefix . ':' . $kind . ':' . strtolower($value))
            ->unique()
            ->values()
            ->all();
    }

    private function scoreImportCandidate(array $data, bool $forcedOff, array $preferredFields): int
    {
        $score = $forcedOff ? 0 : 1000;

        if ($this->getValue($data, ['uuid'])) {
            $score += 500;
        }

        if ($this->getValue($data, [
            'primary ip address',
            'primary ip address ',
            'primary ip address.1',
            'ip interna',
        ])) {
            $score += 300;
        }

        if (!$forcedOff && $this->normalizeState(
            $this->getValue($data, ['state', 'powerstate', 'state / powerstate'])
        ) !== 'poweredOff') {
            $score += 100;
        }

        foreach ($preferredFields as $field) {
            if ($this->getValue($data, [$field])) {
                $score += 10;
            }
        }

        return $score;
    }

    private function rememberImportCandidate(array &$candidates, array $candidate): void
    {
        $key = $candidate['dedupe_key'] ?? $candidate['key'] ?? null;

        if (!$key) {
            return;
        }

        $current = $candidates[$key] ?? null;

        if (!$current || ($candidate['priority'] ?? 0) >= ($current['priority'] ?? 0)) {
            $candidates[$key] = $candidate;
        }
    }

    private function resolveCandidateDedupeKey(array $candidate): ?string
    {
        return match ($candidate['handler'] ?? null) {
            'gcp' => $this->resolveGcpCandidateDedupeKey($candidate),
            'server', 'forced_off' => $this->resolveServerCandidateDedupeKey($candidate),
            default => $candidate['key'] ?? null,
        };
    }

    private function resolveServerCandidateDedupeKey(array $candidate): ?string
    {
        $data = $candidate['data'] ?? [];
        $primaryIp = $this->getValue($data, [
            'primary ip address',
            'primary ip address ',
            'primary ip address.1',
        ]);
        $vm = $this->getValue($data, ['vm']);
        $server = $this->findServerForImport(
            $data,
            $primaryIp,
            $vm,
            (bool) ($candidate['is_appliance'] ?? false)
        );

        return $server->exists
            ? 'server:id:' . $server->getKey()
            : ($candidate['key'] ?? null);
    }

    private function resolveGcpCandidateDedupeKey(array $candidate): ?string
    {
        $data = $candidate['data'] ?? [];
        $internalIp = collect($data)
            ->first(fn($value, $key) => str_contains(strtolower($key), 'ip interna') && $value);

        if (!$internalIp) {
            return $candidate['key'] ?? null;
        }

        $machine = $this->findGcpMachineForImport($data, trim($internalIp));

        return $machine->exists
            ? 'gcp:id:' . $machine->getKey()
            : ($candidate['key'] ?? null);
    }

    private function filterUniqueImportCandidates(array $candidates): array
    {
        usort($candidates, function (array $left, array $right) {
            $priorityComparison = ((int) ($right['priority'] ?? 0)) <=> ((int) ($left['priority'] ?? 0));

            if ($priorityComparison !== 0) {
                return $priorityComparison;
            }

            return ((int) ($right['order'] ?? 0)) <=> ((int) ($left['order'] ?? 0));
        });

        $selected = [];
        $seenAliases = [];

        foreach ($candidates as $candidate) {
            $aliases = $candidate['aliases'] ?? [];

            if (collect($aliases)->contains(fn($alias) => isset($seenAliases[$alias]))) {
                continue;
            }

            $selected[] = $candidate;

            foreach ($aliases as $alias) {
                $seenAliases[$alias] = true;
            }
        }

        usort(
            $selected,
            fn(array $left, array $right) => ((int) ($left['order'] ?? 0)) <=> ((int) ($right['order'] ?? 0))
        );

        return $selected;
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        [$sheets, $sheetNames] = $this->readWorkbook($request->file('file'));

        $stats = $this->createStats(['servers_on', 'servers_off', 'gcp_machines', 'appliances_on', 'appliances_off']);
        $candidates = $this->collectImportCandidates($sheets, $sheetNames);
        AuditLog::$suppressed = true;

        try {
            foreach ($candidates as $candidate) {
                $result = match ($candidate['handler'] ?? null) {
                    'gcp' => [
                        'bucket' => 'gcp_machines',
                        'status' => $this->importGcpRow($candidate['data']),
                    ],
                    'forced_off' => [
                        'bucket' => 'servers_off',
                        'status' => $this->importForcedOffServerRow($candidate['data']),
                    ],
                    default => $this->resolveServerCandidateImportResult($candidate),
                };

                $this->incrementStats($stats, $result['bucket'], $result['status']);
            }
        } finally {
            AuditLog::$suppressed = false;
        }

        return $this->respondWithImportResult(
            $stats,
            'No se importaron registros validos desde el Excel.'
        );
    }

    private function resolveServerCandidateImportResult(array $candidate): array
    {
        $isAppliance = (bool) ($candidate['is_appliance'] ?? false);
        $result      = $this->importServerRow($candidate['data'], $isAppliance);
        $isPoweredOff = ($result['state'] ?? null) === 'poweredOff';

        return [
            'bucket' => match (true) {
                $isAppliance && $isPoweredOff  => 'appliances_off',
                $isAppliance                   => 'appliances_on',
                $isPoweredOff                  => 'servers_off',
                default                        => 'servers_on',
            },
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

        $machine = $this->findGcpMachineForImport($data, trim($internalIp));

        return $this->persistImportedModel(
            $machine,
            [
                'project_name' => $this->getValue($data, ['nombre de proyecto'], 'N/A'),
                'environment' => $this->getValue($data, ['entorno'], 'N/A'),
                'machine_name' => $this->getValue($data, ['nombre de maquina'], 'N/A'),
                'machine_internal_name' => $this->getValue($data, ['nombre de maquina interna'], 'N/A'),
                'state' => $this->normalizeState(
                    $this->getValue($data, ['state', 'powerstate', 'state / powerstate'])
                ),
                'operations_system' => $this->getValue($data, ['sistema operativo'], 'N/A'),
                'internal_ip' => trim($internalIp),
                'kernel_version' => $this->getValue($data, ['version de kernel'], 'N/A'),
                'alias_ip' => $aliases[0] ?? 'N/A',
                'alias2_ip' => $aliases[1] ?? 'N/A',
                'alias3_ip' => $aliases[2] ?? 'N/A',
                'ram_memory' => max(0, (int) $ramRaw),
                'swap_memory' => max(0, (int) $swapRaw),
            ] + array_filter(['uuid' => $this->getValue($data, ['uuid'])])
        );
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

        $server = $this->findServerForImport($data, $primaryIp, $vm, $isAppliance);

        $payload = [
            'type_application_id' => $typeApplicationId,
            'vm_according_to_the_vmware' => $vm ?: 'N/A',
            'state' => $this->normalizeState(
                $this->getValue($data, ['state', 'powerstate', 'state / powerstate'])
            ),
            'primary_ip_address' => $primaryIp,
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

        $payload += array_filter(['uuid' => $this->getValue($data, ['uuid'])]);

        if (!$server->exists) {
            $payload['owner_id'] = null;
            $payload['created_by'] = Auth::id();
        }

        return [
            'status' => $this->persistImportedModel($server, $payload),
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

        $server = $this->findServerForImport($data, $primaryIp, $vm, false);

        $payload = [
            'type_application_id' => 1,
            'vm_according_to_the_vmware' => $vm,
            'state' => 'poweredOff',
            'primary_ip_address' => $primaryIp,
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

        $payload += array_filter(['uuid' => $this->getValue($data, ['uuid'])]);

        if (!$server->exists) {
            $payload['owner_id'] = null;
            $payload['created_by'] = Auth::id();
        }

        return $this->persistImportedModel($server, $payload);
    }

    private function serverImportLookupQuery(bool $isAppliance = false)
    {
        $query           = Server::withTrashed();
        $applianceTypeId = $this->applianceTypeApplicationId();

        return $isAppliance
            ? $query->where('type_application_id', $applianceTypeId)
            : $query->where(fn($q) => $q
                ->where('type_application_id', '!=', $applianceTypeId)
                ->orWhereNull('type_application_id')
            );
    }

    private function normalizeComparableIdentityValue(mixed $value): string
    {
        return trim(strtolower((string) $value));
    }

    private function getServerHistoricIdentityMap(): array
    {
        if ($this->serverHistoricIdentityMap !== null) {
            return $this->serverHistoricIdentityMap;
        }

        $servers = Server::withTrashed()
            ->whereNotNull('manual_override_fields')
            ->get([
                'id',
                'type_application_id',
                'uuid',
                'vm_according_to_the_vmware',
                'primary_ip_address',
                'manual_override_fields',
                'updated_at',
            ])
            ->filter(fn(Server $server) => !empty($server->getManualOverrideFields()))
            ->keyBy('id');

        if ($servers->isEmpty()) {
            return $this->serverHistoricIdentityMap = [];
        }

        $map = [];
        $applianceTypeId = $this->applianceTypeApplicationId();
        $logs = AuditLog::query()
            ->where('module', 'server')
            ->where('action', 'update')
            ->whereIn('record_id', $servers->keys()->all())
            ->orderBy('id')
            ->get(['record_id', 'before_data']);

        foreach ($logs as $log) {
            $server = $servers->get((int) $log->record_id);

            if (!$server) {
                continue;
            }

            $before = json_decode((string) $log->before_data, true);

            if (!is_array($before)) {
                continue;
            }

            $identityFields = collect($server->getManualOverrideFields())
                ->intersect(['uuid', 'vm_according_to_the_vmware', 'primary_ip_address'])
                ->values()
                ->all();

            if (empty($identityFields)) {
                continue;
            }

            $prefix = $this->serverImportIdentityPrefix(
                (int) $server->type_application_id === $applianceTypeId
            );

            foreach ($identityFields as $field) {
                $beforeValue = $before[$field] ?? null;
                $currentValue = $server->getAttribute($field);

                if ($this->normalizeComparableIdentityValue($beforeValue)
                    === $this->normalizeComparableIdentityValue($currentValue)) {
                    continue;
                }

                $kind = match ($field) {
                    'uuid' => 'uuid',
                    'primary_ip_address' => 'ip',
                    default => 'vm',
                };

                foreach ($this->buildImportValueAliases($prefix, $kind, [$beforeValue]) as $alias) {
                    $existing = $map[$alias] ?? null;

                    if (!$existing || $server->updated_at?->greaterThan($existing->updated_at)) {
                        $map[$alias] = $server;
                    }
                }
            }
        }

        return $this->serverHistoricIdentityMap = $map;
    }

    private function reloadFullServerForImport(Server $server): Server
    {
        return Server::withTrashed()->findOrFail($server->getKey());
    }

    private function findServerByHistoricImportIdentity(
        ?string $uuid,
        ?string $primaryIp,
        ?string $vm,
        bool $isAppliance = false
    ): ?Server {
        $prefix = $this->serverImportIdentityPrefix($isAppliance);
        $map = $this->getServerHistoricIdentityMap();

        foreach (array_merge(
            $this->buildImportValueAliases($prefix, 'uuid', [$uuid]),
            $this->buildImportValueAliases($prefix, 'ip', [$primaryIp]),
            $this->buildImportValueAliases($prefix, 'vm', [$vm]),
        ) as $alias) {
            if (isset($map[$alias])) {
                return $this->reloadFullServerForImport($map[$alias]);
            }
        }

        return null;
    }

    private function findServerForImport(
        array $data,
        ?string $primaryIp,
        ?string $vm,
        bool $isAppliance = false
    ): Server {
        $uuid = $this->getValue($data, ['uuid']);
        $query = $this->serverImportLookupQuery($isAppliance);

        if ($uuid) {
            $server = (clone $query)->where('uuid', $uuid)->first();

            if ($server) {
                return $server;
            }
        }

        $historicServer = $this->findServerByHistoricImportIdentity($uuid, $primaryIp, $vm, $isAppliance);

        if ($historicServer) {
            return $historicServer;
        }

        if ($primaryIp) {
            $server = (clone $query)->where('primary_ip_address', $primaryIp)->latest('id')->first();

            if ($server) {
                return $server;
            }
        }

        if ($vm) {
            $server = (clone $query)
                ->where('vm_according_to_the_vmware', $vm)
                ->orderByRaw("CASE WHEN primary_ip_address IS NULL OR primary_ip_address = '' THEN 1 ELSE 0 END")
                ->latest('id')
                ->first();

            if ($server) {
                return $server;
            }
        }

        if ($primaryIp) {
            return new Server(['primary_ip_address' => $primaryIp] + array_filter(['uuid' => $uuid]));
        }

        return new Server(['vm_according_to_the_vmware' => $vm] + array_filter(['uuid' => $uuid]));
    }

    private function findGcpMachineForImport(array $data, string $internalIp): GcpMachine
    {
        $uuid = $this->getValue($data, ['uuid']);

        return ($uuid && GcpMachine::withTrashed()->where('uuid', $uuid)->first())
            ?: (GcpMachine::withTrashed()->where('internal_ip', $internalIp)->first()
            ?: new GcpMachine(['internal_ip' => $internalIp] + array_filter(['uuid' => $uuid])));
    }

    private function isImportValueEmpty(mixed $value): bool
    {
        return $value === null || (is_string($value) && trim($value) === '');
    }

    private function normalizeImportDateForComparison(mixed $value): ?string
    {
        if ($this->isImportValueEmpty($value)) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        if (is_string($value) || is_numeric($value)) {
            try {
                return Carbon::parse((string) $value)->format('Y-m-d');
            } catch (\Throwable $e) {
                return trim((string) $value);
            }
        }

        return trim((string) $value);
    }

    private function importValuesSemanticallyEqual(string $key, mixed $stored, mixed $incoming): bool
    {
        // Comparaciones especiales por tipo de campo
        if ($key === 'state') {
            return $this->normalizeState((string) $stored) === $this->normalizeState((string) $incoming);
        }

        if ($key === 'latest_security_patch') {
            return $this->normalizeImportDateForComparison($stored)
                === $this->normalizeImportDateForComparison($incoming);
        }

        if (in_array($key, ['ram_memory', 'swap_memory'], true)) {
            return (int) ($stored ?? 0) === (int) ($incoming ?? 0);
        }

        // En Excel usamos 'N/A' como "vacío". Si en BD quedó NULL no cuenta como update.
        $storedEmpty   = $this->isImportValueEmpty($stored);
        $incomingEmpty = $this->isImportValueEmpty($incoming);

        return match (true) {
            $storedEmpty && $incomingEmpty                                    => true,
            $storedEmpty && is_string($incoming) && trim($incoming) === 'N/A' => true,
            $incomingEmpty && is_string($stored)  && trim($stored)  === 'N/A' => true,
            is_numeric($stored) && is_numeric($incoming)                      => (int) $stored === (int) $incoming,
            default                                                           => trim((string) $stored) === trim((string) $incoming),
        };
    }

    private function importPayloadMatchesStoredModel($model, array $payload): bool
    {
        foreach ($payload as $key => $incoming) {
            if (!$this->importValuesSemanticallyEqual($key, $model->getAttribute($key), $incoming)) {
                return false;
            }
        }

        return true;
    }

    private function persistImportedModel($model, array $payload): string
    {
        $wasCreated = !$model->exists;
        $wasTrashed = method_exists($model, 'trashed') && $model->trashed();
        $reportedFields = [];

        if (!$wasCreated && method_exists($model, 'protectManualOverridesDuringImport')) {
            $overrideResult = $model->protectManualOverridesDuringImport($payload);
            $payload = $overrideResult['payload'];
            $model->releaseManualOverrideFields($overrideResult['released_fields']);
            $model->markManualOverridesAsReported($overrideResult['reported_fields']);
            $reportedFields = $overrideResult['reported_fields'];
        }

        $dataMatchesStored = !$wasCreated && $this->importPayloadMatchesStoredModel($model, $payload);

        if (!$dataMatchesStored) {
            $model->fill($payload);
        }

        $hasImportedFieldChanges = $wasCreated || !$dataMatchesStored;
        $hasMetadataChanges = $model->isDirty('manual_override_fields')
            || $model->isDirty('pending_manual_override_fields');

        if ($hasImportedFieldChanges || $hasMetadataChanges) {
            $model->save();
        }

        if ($wasTrashed) {
            $model->restore();
        }

        if ($wasCreated) {
            return 'created';
        }

        if ($hasImportedFieldChanges || $wasTrashed) {
            return 'updated';
        }

        return !empty($reportedFields) ? 'preserved' : 'unchanged';
    }

    public function importPoweredOff(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        [$sheets, $sheetNames] = $this->readWorkbook($request->file('file'));
        $stats = $this->createStats(['servers_off']);
        AuditLog::$suppressed = true;

        try {
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
        } finally {
            AuditLog::$suppressed = false;
        }

        return $this->respondWithImportResult(
            $stats,
            'No se importaron filas: revisa que existan datos en BajaTultitlan, TulOff y QroOff.'
        );
    }
}
