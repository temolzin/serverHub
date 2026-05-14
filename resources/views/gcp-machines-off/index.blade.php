@extends('layouts/contentNavbarLayout')

@section('title', 'GCP apagadas')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <h5 class="mb-0">Máquinas GCP apagadas</h5>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <button class="btn btn-primary text-center" data-bs-toggle="modal" data-bs-target="#createGcpOffMachineModal"><i class="bx bx-plus me-1"></i> Agregar máquina apagada</button>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <table class="table align-middle w-100 datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Proyecto</th>
                                    <th>Máquina</th>
                                    <th>Aplicación</th>
                                    <th>Sistema operativo</th>
                                    <th>Estado</th>
                                    <th class="text-end no-export">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($gcpMachines as $machine)
                                    <tr>
                                        <td>{{ $machine->id }}</td>
                                        <td>{{ filled($machine->project_name) ? $machine->project_name : 'N/A' }}</td>
                                        <td>{{ filled($machine->machine_name) ? $machine->machine_name : 'N/A' }}</td>
                                        <td>{{ filled($machine->display_application_name) ? $machine->display_application_name : 'N/A' }}</td>
                                        <td>{{ filled($machine->operations_system) ? $machine->operations_system : 'N/A' }}</td>
                                        <td><span class="badge {{ $machine->is_powered_off ? 'bg-label-danger' : 'bg-label-success' }}"> {{ $machine->display_state_label }}</span></td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#showGcpOffMachineModal{{ $machine->id }}"><i class="bx bx-show me-1"></i> Ver</button>
                                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editGcpOffMachineModal{{ $machine->id }}"><i class="bx bx-edit-alt me-1"></i> Editar</button>
                                                    <form action="{{ route('gcp-machines.power-on', $machine) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-success"><i class="bx bx-power-off me-1"></i> Encender</button>
                                                    </form>
                                                    <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteGcpOffMachineModal{{ $machine->id }}"><i class="bx bx-trash me-1"></i> Eliminar</button>
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

    @foreach ($gcpMachines as $machine)
        @include('gcp-machines-off.show', ['machine' => $machine])
        @include('gcp-machines-off.edit', ['machine' => $machine])
        @include('gcp-machines-off.delete', ['machine' => $machine])
    @endforeach

    @include('gcp-machines-off.create')
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Listo',
                    text: @json(session('success')),
                    confirmButtonText: 'Perfecto',
                    timer: 5000,
                    timerProgressBar: true
                });
            @endif

            @if ($errors->any())
                const createModalEl = document.getElementById('createGcpOffMachineModal');
                if (createModalEl) {
                    bootstrap.Modal.getOrCreateInstance(createModalEl).show();
                }
            @endif

            function hydrateBootstrap() {
                document.querySelectorAll('.dropdown-toggle')
                .forEach(el => bootstrap.Dropdown.getOrCreateInstance(el));
            }

            function initSearchableSelects(scope = document) {
                if (typeof TomSelect === 'undefined') return;

                const selects = scope.querySelectorAll('.gcp-off-searchable-select');
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

            const createForm = document.getElementById('createGcpOffForm');
            const cancelCreateBtn = document.getElementById('cancelCreateGcpOff');
            if (createForm && cancelCreateBtn) {
                cancelCreateBtn.addEventListener('click', function() {
                    createForm.reset();

                    createForm.querySelectorAll('input, textarea, select').forEach(el => {
                        el.classList.remove('is-invalid');
                        el.setCustomValidity('');

                        if (el.tomselect) {
                            const defaultOption = el.querySelector('option[selected]');
                            const defaultValue = defaultOption ? defaultOption.value : '';
                            el.tomselect.setValue(defaultValue, true);
                        }
                    });

                    createForm.querySelectorAll('.text-danger, .invalid-feedback').forEach(el => {
                        el.classList.add('d-none');
                        el.textContent = '';
                    });

                    const submitBtn = createForm.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = false;
                    }
                });
            }

            hydrateBootstrap();
            initSearchableSelects(document);
        });
    </script>
@endpush
