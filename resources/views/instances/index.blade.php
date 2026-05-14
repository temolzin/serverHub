@extends('layouts/contentNavbarLayout')

@section('title', 'Instancias')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <h5 class="mb-0">Instancias</h5>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <button class="btn btn-success text-center" data-bs-toggle="modal" data-bs-target="#createInstanceModal"><i class="bx bx-plus me-1"></i> Agregar Instancia</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-nowrap">
                        <table class="table align-middle w-100 datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Servidor</th>
                                    <th>Memoria</th>
                                    <th>Versión</th>
                                    <th>Edición</th>
                                    <th class="text-end no-export">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($instances as $instance)
                                    <tr>
                                        <td>{{ $instance->id }}</td>
                                        <td>{{ $instance->server?->hostname_internal ?? 'N/A' }}</td>
                                        <td>{{ $instance->memory }} MB</td>
                                        <td>{{ $instance->version }}</td>
                                        <td>{{ $instance->edition ?? '—' }}</td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="javascript:;" data-bs-toggle="modal" data-bs-target="#showInstanceModal{{ $instance->id }}"><i class="bx bx-show me-1"></i> Ver</a>
                                                    <a class="dropdown-item" href="javascript:;" data-bs-toggle="modal" data-bs-target="#editInstanceModal{{ $instance->id }}"><i class="bx bx-edit-alt me-1"></i>Editar</a>
                                                    <a class="dropdown-item {{ $instance->is_in_use ? 'text-secondary pe-none' : 'text-danger' }}"
                                                        href="javascript:;"
                                                        data-bs-toggle="{{ $instance->is_in_use ? '' : 'modal' }}"
                                                        data-bs-target="{{ $instance->is_in_use ? '' : '#deleteInstanceModal'.$instance->id }}"
                                                        title="{{ $instance->is_in_use ? 'No se puede eliminar porque está en uso' : 'Eliminar instancia' }}">

                                                        <i class="bx {{ $instance->is_in_use ? 'bx-lock-alt' : 'bx-trash' }} me-1"></i>

                                                        {{ $instance->is_in_use ? 'En uso' : 'Eliminar' }}
                                                    </a>
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
    @foreach ($instances as $instance)
        @include('instances.show', ['instance' => $instance])
        @include('instances.edit', ['instance' => $instance, 'servers' => $servers])
        @include('instances.delete', ['instance' => $instance])
    @endforeach
    @include('instances.create')
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
