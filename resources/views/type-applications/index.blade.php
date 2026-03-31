@extends('layouts/contentNavbarLayout')

@section('title', 'Tipo de aplicaciones')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <h5 class="mb-0">Tipo de aplicaciones</h5>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <button class="btn btn-success text-center" data-bs-toggle="modal" data-bs-target="#createTypeApplicationModal">
                            <i class="bx bx-plus me-1"></i> Agregar tipo de aplicación
                        </button>
                        <a href="{{ route('export', 'type-applications') }}" class="btn btn-primary text-center">
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
                                    <th>Tipo</th>
                                    <th>Nombre</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($typeApplications as $type)
                                    <tr>
                                        <td>{{ $type->id }}</td>
                                        <td>{{ strtoupper($type->type_application) }}</td>
                                        <td class="fw-medium">{{ $type->name_application }}</td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#showTypeApplicationModal{{ $type->id }}">
                                                        <i class="bx bx-show me-1"></i> Ver
                                                    </button>
                                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editTypeApplicationModal{{ $type->id }}">
                                                        <i class="bx bx-edit-alt me-1"></i> Editar
                                                    </button>
                                                    <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteTypeApplicationModal{{ $type->id }}">
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

    @foreach ($typeApplications as $type)
        @include('type-applications.show', ['type' => $type])
        @include('type-applications.edit', ['type' => $type])
        @include('type-applications.delete', ['type' => $type])
    @endforeach

    @include('type-applications.create')
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
                    timer: 2500,
                    timerProgressBar: true
                });
            @endif
        });
    </script>
@endpush
