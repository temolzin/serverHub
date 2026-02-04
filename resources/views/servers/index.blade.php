@extends('layouts/contentNavbarLayout')

@section('title', 'Servidores')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Servidores</h5>
        </div>
        <div class="table-responsive text-nowrap">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Propietario</th>
                <th>Aplicación</th>
                <th>Estado</th>
                <th>DNS</th>
                <th>IP primaria</th>
                <th>Entorno</th>
                <th>Datacenter</th>
                <th>Sistema operativo (VMware)</th>
                <th>Versión interna</th>
                <th>Hostname interno</th>
                <th>IP usuario</th>
                <th>IP monitoreo</th>
                <th>Otras IPs</th>
                <th>RAM (MB)</th>
                <th>Swap (MB)</th>
                <th>Último parche de seguridad</th>
                <th>Comentarios</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($servers as $server)
                <tr>
                  <td>{{ $server->id }}</td>
                  <td>
                    {{ optional($server->owner)->name }}
                    {{ optional($server->owner)->last_name }}
                  </td>
                  <td>{{ optional($server->typeApplication)->name_application }}</td>
                  <td>
                    @if ($server->state)
                      <span class="badge bg-label-success">Activo</span>
                    @else
                      <span class="badge bg-label-danger">Inactivo</span>
                    @endif
                  </td>
                  <td>{{ $server->dns_name ?? '—' }}</td>
                  <td>{{ $server->primary_ip_address }}</td>
                  <td>
                    <span class="badge bg-label-info">
                      {{ $server->environment }}
                    </span>
                  </td>
                  <td>{{ $server->datacenter }}</td>
                  <td>{{ $server->os_according_to_the_vmware }}</td>
                  <td>{{ $server->os_version_internal }}</td>
                  <td class="text-muted">{{ $server->hostname_internal }}</td>
                  <td>{{ $server->ip_user ?? '—' }}</td>
                  <td>{{ $server->ip_monitoring ?? '—' }}</td>
                  <td class="small">{{ $server->other_ips ?? '—' }}</td>
                  <td>{{ $server->ram_memory }} MB</td>
                  <td>{{ $server->swap_memory }} MB</td>
                  <td>{{ $server->latest_security_patch ?? '—' }}</td>
                  <td>{{ $server->comments ?? '—' }}</td>
                  <td class="text-end">
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="18" class="text-center text-muted">
                    No se encontraron servidores
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
