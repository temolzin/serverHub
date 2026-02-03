@extends('layouts/contentNavbarLayout')

@section('title', 'GCP Machines')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Máquinas de GCP</h5>
        </div>
        <div class="table-responsive text-nowrap">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Propietario</th>
                <th>Proyecto</th>
                <th>Entorno</th>
                <th>Nombre de la máquina</th>
                <th>Nombre interno</th>
                <th>Sistema operativo</th>
                <th>Kernel</th>
                <th>Parche de seguridad</th>
                <th>IP interna</th>
                <th>Alias IP</th>
                <th>Alias 2 IP</th>
                <th>Alias 3 IP</th>
                <th>Otras IP</th>
                <th>RAM (MB)</th>
                <th>Swap (MB)</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($gcpMachines as $machine)
                <tr>
                  <td>{{ $machine->id }}</td>
                  <td>
                    {{ optional($machine->owner)->name }}
                    {{ optional($machine->owner)->last_name }}
                  </td>
                  <td class="fw-medium">
                    {{ $machine->project_name }}
                  </td>
                  <td>
                    <span class="badge bg-label-info">
                      {{ $machine->environment }}
                    </span>
                  </td>
                  <td>{{ $machine->machine_name }}</td>
                  <td class="text-muted">{{ $machine->machine_internal_name }}</td>
                  <td>{{ $machine->operations_system }}</td>
                  <td>{{ $machine->kernel_version ?? '—' }}</td>
                  <td>{{ $machine->latest_security_patch ?? '—' }}</td>
                  <td>{{ $machine->internal_ip ?? '—' }}</td>
                  <td>{{ $machine->alias_ip ?? '—' }}</td>
                  <td>{{ $machine->alias2_ip ?? '—' }}</td>
                  <td>{{ $machine->alias3_ip ?? '—' }}</td>
                  <td class="small">{{ $machine->other_ips ?? '—' }}</td>
                  <td>{{ $machine->ram_memory }} MB</td>
                  <td>{{ $machine->swap_memory }} MB</td>
                  <td class="text-end">
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="17" class="text-center text-muted">
                    No se encontraron máquinas de GCP
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
