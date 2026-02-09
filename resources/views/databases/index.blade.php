@extends('layouts/contentNavbarLayout')

@section('title', 'Databases')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Bases de datos</h5>
          <button class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Agregar base de datos
          </button>
        </div>

        <div class="table-responsive text-nowrap">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Servidor</th>
                <th>Puerto</th>
                <th>Versión</th>
                <th>Estado</th>
                <th class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($databases as $database)
                <tr>
                  <td>{{ $database->id }}</td>

                  <td>
                    <div class="fw-medium">
                      {{ $database->name }}
                    </div>
                  </td>

                  <td>{{ strtoupper($database->type) }}</td>

                  <td>
                    {{ $database->server?->dns_name ?? '—' }}
                  </td>

                  <td>{{ $database->port }}</td>

                  <td>{{ $database->version }}</td>

                  <td>
                    <span class="badge bg-label-success">
                      {{ ucfirst($database->status) }}
                    </span>
                  </td>

                  <td class="text-end">
                    <div class="dropdown">
                      <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-vertical-rounded"></i>
                      </button>
                      <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="#">
                          <i class="bx bx-show me-1"></i> Ver
                        </a>
                        <a class="dropdown-item" href="#">
                          <i class="bx bx-edit-alt me-1"></i> Editar
                        </a>
                      </div>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center text-muted">
                    No se encontraron bases de datos
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
