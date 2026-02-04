@extends('layouts/contentNavbarLayout')

@section('title', 'Applications')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Aplicaciones</h5>
        </div>
        <div class="table-responsive text-nowrap">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Propietario</th>
                <th>Servidor</th>
                <th>Nombre</th>
                <th>Versión</th>
                <th>Estado</th>
                <th>Tipo</th>
                <th>Memoria asignada (MB)</th>
                <th>Ruta de instalación</th>
                <th>Usuario de servicio</th>
                <th>Parche de seguridad</th>
                <th>Procesos</th>
                <th>Tareas programadas</th>
                <th>Comentarios</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($applications as $application)
                <tr>
                  <td>{{ $application->id }}</td>
                  <td>
                    {{ optional($application->owner)->name }}
                    {{ optional($application->owner)->last_name }}
                  </td>
                  <td class="fw-medium">
                    {{ optional($application->server)->hostname_internal ?? '—' }}
                  </td>
                  <td>{{ $application->name }}</td>
                  <td>{{ $application->version }}</td>
                  <td>
                    <span class="badge bg-label-info">
                      {{ $application->status }}
                    </span>
                  </td>
                  <td>{{ $application->type }}</td>
                  <td>{{ $application->assigned_memory ?? '—' }}</td>
                  <td class="text-muted">{{ $application->installation_route ?? '—' }}</td>
                  <td>{{ $application->user_service ?? '—' }}</td>
                  <td>{{ $application->latest_security_patch ?? '—' }}</td>
                  <td class="small">{{ $application->processes ?? '—' }}</td>
                  <td class="small">{{ $application->cron_jobs ?? '—' }}</td>
                  <td class="small">{{ $application->comments ?? '—' }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="14" class="text-center text-muted">
                    No se encontraron aplicaciones
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
