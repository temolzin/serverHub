@extends('layouts/contentNavbarLayout')

@section('title', 'Usuarios')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <h5 class="mb-0">Usuarios</h5>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <button class="btn btn-success text-center" data-bs-toggle="modal" data-bs-target="#createUserModal"><i class="bx bx-plus me-1"></i> Agregar Usuario</button>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <table class="table align-middle w-100 datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Correo</th>
                                    <th>Rol</th>
                                    <th class="text-end no-export">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->last_name_formatted }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->roles->pluck('name')->first() ?? 'Sin rol' }}</td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}"><i class="bx bx-edit-alt me-1"></i>Editar</button>
                                                    <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $user->id }}"><i class="bx bx-trash me-1"></i>Eliminar</button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($users as $user)
        @include('users.edit', ['user' => $user])
        @include('users.delete', ['user' => $user])
    @endforeach

    @include('users.create')
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                icon: 'success',
                title: '¡Listo!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK',
                timer: 4000,
                timerProgressBar: true
                });
            @endif
        });
    </script>
@endpush
