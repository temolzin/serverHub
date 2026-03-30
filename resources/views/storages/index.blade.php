@extends('layouts/contentNavbarLayout')

@section('title', 'Almacenamiento')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <h5 class="mb-0">Almacenamiento</h5>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <button class="btn btn-success text-center" data-bs-toggle="modal" data-bs-target="#createStorageModal"><i class="bx bx-plus me-1"></i> Agregar almacenamiento</button>
                        <a href="{{ route('export', 'storages') }}" class="btn btn-primary text-center">Exportar Excel</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-nowrap">
                        <table class="table align-middle w-100 datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre del servidor</th>
                                    <th>IP interna</th>
                                    <th>Entorno</th>
                                    <th>Centro de datos</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($storages as $storage)
                                    <tr>
                                        <td>{{ $storage->id }}</td>
                                        <td>{{ $storage->hostname }}</td>
                                        <td>{{ $storage->internal_ip ?? '—' }}</td>
                                        <td>{{ $storage->environment ?? '—' }}</td>
                                        <td>{{ $storage->datacenter ?? '—' }}</td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#showStorageModal{{ $storage->id }}"><i class="bx bx-show me-1"></i> Ver</a>
                                                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editStorageModal{{ $storage->id }}"><i class="bx bx-edit-alt me-1"></i> Editar</a>
                                                    <a class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteStorageModal{{ $storage->id }}"><i class="bx bx-trash me-1"></i> Eliminar</a>
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
    @foreach ($storages as $storage)
        @include('storages.show', ['storage' => $storage])
        @include('storages.edit', ['storage' => $storage])
        @include('storages.delete', ['storage' => $storage])
    @endforeach
    @include('storages.create')
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Listo!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'Perfecto',
                    timer: 5000,
                    timerProgressBar: true
                });
            @endif
        });
    </script>
@endpush
