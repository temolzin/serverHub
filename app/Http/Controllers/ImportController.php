<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Server;
use App\Models\GcpMachine;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
  private function getValue(array $data, array $possibleKeys, $default = null)
  {
    foreach ($possibleKeys as $key) {
      if (isset($data[$key]) && $data[$key] !== null && $data[$key] !== '') {
        return trim($data[$key]);
      }
    }
    return $default;
  }

  public function import(Request $request)
  {
    $request->validate([
      'file' => 'required|mimes:xlsx'
    ]);
    $sheets = Excel::toCollection(null, $request->file('file'));

    foreach ($sheets as $rows) {

      if ($rows->isEmpty()) {
        continue;
      }

      $header = $rows->first()->toArray();
      $rows = $rows->skip(1);

      foreach ($rows as $row) {
        $row = $row->toArray();

        if (count($header) != count($row)) {
          continue;
        }

        $normalizedHeader = [];
        foreach ($header as $key => $value) {
          $clean = strtolower(trim($value));

          if (in_array($clean, $normalizedHeader)) {
            $clean .= '_' . $key;
          }
          $normalizedHeader[] = $clean;
        }

        $data = array_combine($normalizedHeader, $row);
        $isGcpSheet = collect($normalizedHeader)->contains(function ($value) {
          return str_contains($value, 'nombre de maquina');
        });

        if ($isGcpSheet) {
          $internalIp = null;

          foreach ($data as $key => $value) {
            if (str_contains(strtolower($key), 'ip interna') && !empty($value)) {
              $internalIp = trim($value);
              break;
            }
          }

          if (!$internalIp) {
            continue;
          }
          $aliasList = collect();
          foreach ($data as $key => $value) {
            $normalizedKey = strtolower(trim($key));
            if (preg_match('/^ip alias/', $normalizedKey) && !empty($value)) {

              $values = preg_split('/\r\n|\r|\n/', $value);

              foreach ($values as $ip) {
                $clean = trim($ip);
                if (!empty($clean)) {
                  $aliasList->push($clean);
                }
              }
            }
          }
          $aliasList = $aliasList->values();
          $alias1 = $aliasList[0] ?? null;
          $alias2 = $aliasList[1] ?? null;
          $alias3 = $aliasList[2] ?? null;
          $ramMemory = null;
          $swapMemory = null;

          foreach ($data as $key => $value) {
            $normalizedKey = strtolower(trim($key));

            if (str_contains($normalizedKey, 'memoria ram') && !empty($value)) {
              $ramMemory = (int) $value;
            }

            if (str_contains($normalizedKey, 'memoria swap') && !empty($value)) {
              $swapMemory = (int) $value;
            }
          }

          GcpMachine::updateOrCreate(
            ['internal_ip' => $internalIp],
            [
              'project_name' => $data['nombre de proyecto'] ?? 'N/A',
              'environment' => $data['entorno'] ?? 'N/A',
              'machine_name' => $data['nombre de maquina'] ?? 'N/A',
              'machine_internal_name' => $data['nombre de maquina interna'] ?? 'N/A',
              'operations_system' => $data['sistema operativo'] ?? 'N/A',
              'kernel_version' => $data['version de kernel'] ?? null,
              'alias_ip'  => $alias1,
              'alias2_ip' => $alias2,
              'alias3_ip' => $alias3,
              'ram_memory' => $ramMemory ?? 0,
              'swap_memory' => $swapMemory ?? 0,
            ]
          );
        } else {

          $primaryIp = $this->getValue($data, [
            'primary ip address',
            'primary ip address ',
            'primary ip address.1'
          ]);

          if (!$primaryIp) {
            continue;
          }

          $ips = collect([
            $this->getValue($data, ['ip2']),
            $this->getValue($data, ['ip3']),
            $this->getValue($data, ['ip4']),
            $this->getValue($data, ['ip5']),
          ]);

          $otherIps = $ips
            ->filter()
            ->flatMap(function ($item) {
              return preg_split('/\r\n|\r|\n/', $item);
            })
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
              'state' => $this->getValue($data, ['state', 'powerstate']),
              'datacenter' => $this->getValue($data, ['datacenter']),
              'environment' => $this->getValue($data, ['enviroment', 'entorno'], 'N/A'),
              'os_according_to_the_vmware' => $this->getValue($data, [
                'os according to the wmware',
                'os according wmware'
              ], 'N/A'),
              'os_version_internal' => $this->getValue($data, [
                'real os',
                'real os internal'
              ]),
              'hostname_internal' => $this->getValue($data, [
                'hostname real',
                'real hostname'
              ]),
              'ip_user' => $this->getValue($data, ['ip']),
              'ip_monitoring' => $this->getValue($data, ['monitoreo']),
              'dns_name' => $this->getValue($data, ['dns name']),
              'other_ips' => $otherIps,
              'ram_memory' => 0,
              'swap_memory' => 0,
            ]
          );
          if ($server->trashed()) {
            $server->restore();
          }
        }
      }
    }
    return back()->with('success', 'Excel importado correctamente');
  }
}
