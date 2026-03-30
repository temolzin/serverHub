@extends('layouts/contentNavbarLayout')

@section('title', 'Bases de Datos')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <h5 class="mb-0">Bases de Datos</h5>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <button class="btn btn-success text-center" data-bs-toggle="modal" data-bs-target="#createDatabaseModal"><i class="bx bx-plus me-1"></i> Agregar base de datos</button>
                        <a href="{{ route('export', 'databases') }}" class="btn btn-primary text-center">Exportar Excel</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-nowrap">
                        <table class="table align-middle w-100 datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Instancia</th>
                                    <th>Propietario</th>
                                    <th>Puerto</th>
                                    <th>Versión</th>
                                    <th>Estado</th>
                                    <th>Última actualización</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($databases as $database)
                                    <tr>
                                        <td>{{ $database->id }}</td>
                                        <td>{{ $database->name }}</td>
                                        <td>{{ $database->type }}</td>
                                        <td>{{ $database->instance?->server?->hostname_internal ?? 'N/A' }}</td>
                                        <td>{{ optional($database->owner)->name }} {{ optional($database->owner)->last_name }}</td>
                                        <td>{{ $database->port }}</td>
                                        <td>{{ $database->version ?? '—' }}</td>
                                        <td><span class="badge bg-{{ $database->status_color }}">{{ $database->status_label }}</span></td>
                                        <td>{{ $database->last_update ?? '—' }}</td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="javascript:;" data-bs-toggle="modal" data-bs-target="#showDatabaseModal{{ $database->id }}"><i class="bx bx-show me-1"></i> Ver</a>
                                                    <a class="dropdown-item" href="javascript:;" data-bs-toggle="modal" data-bs-target="#editDatabaseModal{{ $database->id }}"><i class="bx bx-edit-alt me-1"></i> Editar</a>
                                                    <a class="dropdown-item text-danger" href="javascript:;" data-bs-toggle="modal" data-bs-target="#deleteDatabaseModal{{ $database->id }}"><i class="bx bx-trash me-1"></i> Eliminar</a>
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
    @foreach ($databases as $database)
        @include('databases.show', ['database' => $database])
        @include('databases.edit', ['database' => $database, 'instances' => $instances, 'owners' => $owners])
        @include('databases.delete', ['database' => $database])
    @endforeach
    @include('databases.create')
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
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

            function initSearchableSelects(scope = document) {
                if (typeof TomSelect === 'undefined') return;
                const selects = scope.querySelectorAll('.server-searchable-select');
                selects.forEach(select => {
                    if (select.tomselect) return;
                    new TomSelect(select, {
                        create: false,
                        sortField: {
                            field: 'text',
                            direction: 'asc'
                        },
                        placeholder: select.dataset.placeholder || 'Buscar...'
                    });
                });
            }
            initSearchableSelects(document);
        });
    </script>
@endpush
