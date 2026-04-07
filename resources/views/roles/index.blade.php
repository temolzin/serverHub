@extends('layouts/contentNavbarLayout')

@section('title', 'Roles')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <h5 class="mb-0">Roles</h5>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <button class="btn btn-success text-center" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                            <i class="bx bx-plus me-1"></i> Agregar Rol
                        </button>
                        <a href="#" class="btn btn-primary text-center">
                            Exportar Excel
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <table class="table align-middle w-100 datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $role->id }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <button class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#editRoleModal{{ $role->id }}">
                                                        <i class="bx bx-edit-alt me-1"></i> Editar
                                                    </button>
                                                    <button class="dropdown-item text-danger" data-bs-toggle="modal"
                                                        data-bs-target="#deleteRoleModal{{ $role->id }}">
                                                        <i class="bx bx-trash me-1"></i> Eliminar
                                                    </button>
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
    @foreach ($roles as $role)
        @include('roles.edit', ['role' => $role])
        @include('roles.delete', ['role' => $role])
    @endforeach
    @include('roles.create')
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
