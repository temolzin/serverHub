@extends('layouts/contentNavbarLayout')

@section('title', 'Owners')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Propietarios</h5>
          <button class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Agregar propietario
          </button>
        </div>
        <div class="table-responsive text-nowrap">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Propietario</th>
                <th>Email</th>
                <th>Telefono</th>
                <th class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($owners as $owner)
                <tr>
                  <td>{{ $owner->id }}</td>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="avatar avatar-sm me-3">
                        <span class="avatar-initial rounded-circle bg-label-primary">
                          {{ strtoupper(substr($owner->name, 0, 1)) }}
                        </span>
                      </div>
                      <div>
                        <span class="fw-medium">
                          {{ $owner->name }} {{ $owner->last_name }}
                        </span>
                      </div>
                    </div>
                  </td>
                  <td>{{ $owner->email }}</td>
                  <td>{{ $owner->number_phone ?? '—' }}</td>
                  <td class="text-end">
                    <div class="dropdown">
                      <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-vertical-rounded"></i>
                      </button>
                      <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="#">
                          <i class="bx bx-edit-alt me-1"></i> Editar
                        </a>
                        <a class="dropdown-item text-danger" href="#">
                          <i class="bx bx-trash me-1"></i> Borrar
                        </a>
                      </div>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center text-muted">
                    No se encontraron propietarios
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
