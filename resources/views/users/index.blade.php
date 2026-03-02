@extends('layouts/contentNavbarLayout')

@section('title', 'Usuarios')

@if (session('success'))
<script>
document.addEventListener('DOMContentLoaded', function () {
  Swal.fire({
    icon: 'success',
    title: 'Listo!',
    text: '{{ session('success') }}',
    confirmButtonText: 'OK',
    timer: 4000,
    timerProgressBar: true
  });
});
</script>
@endif
@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Usuarios</h5>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createUserModal">
          <i class="bx bx-plus me-1"></i>
          Agregar Usuario
        </button>
      </div>
      <div class="card-body">
       <form method="GET" action="{{ route('users.index') }}" class="mb-3">
          <div class="row">
            <div class="col-md-6">
              <div class="input-group">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="form-control"
                    placeholder="Buscar por nombre, email"
                >
                <button class="btn btn-primary">
                    Buscar
                </button>
              </div>
            </div>
          </div>
        </form>
        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($users as $user)
              <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td class="text-end">
                  <div class="dropdown">
                    <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                      <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                        <i class="bx bx-edit-alt me-1"></i>
                        Editar
                      </a>
                      <a class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $user->id }}">
                        <i class="bx bx-trash me-1"></i>
                        Eliminar
                      </a>
                    </div>
                  </div>
                </td>
              </tr>
              @include('users.edit', ['user' => $user])
              @include('users.delete', ['user' => $user])
              @empty
              <tr>
                <td colspan="5" class="text-center text-muted">
                  No hay usuarios registrados
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
                @if ($users->hasPages())
          <div class="mt-3 d-flex justify-content-end">
            {{ $users->links() }}
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@include('users.create')
@endsection
