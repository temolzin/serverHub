@extends('layouts/contentNavbarLayout')

@section('title', 'GCP')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <h5 class="mb-0">Máquinas GCP</h5>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <button class="btn btn-success text-center" data-bs-toggle="modal" data-bs-target="#createGcpMachineModal"><i class="bx bx-plus me-1"></i> Agregar máquina</button>
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
                                    <th>Entorno</th>
                                    <th>Estado</th>
                                    <th>IP interna</th>
                                    <th class="text-end no-export">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($gcpMachines as $machine)
                                    <tr>
                                        <td>{{ $machine->id }}</td>
                                        <td>{{ filled($machine->project_name) ? $machine->project_name : 'N/A' }}</td>
                                        <td>{{ filled($machine->machine_name) ? $machine->machine_name : 'N/A' }}</td>
                                        <td>{{ strtoupper(filled($machine->environment) ? $machine->environment : 'N/A') }}</td>
                                        <td><span class="badge {{ $machine->is_powered_off ? 'bg-label-danger' : 'bg-label-success' }}">{{ $machine->display_state_label }}</span></td>
                                        <td>{{ filled($machine->internal_ip) ? $machine->internal_ip : 'N/A' }}</td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <button type="button" class="dropdown-item btn-modal" data-url="{{ route('gcp-machines.modal', ['gcp_machine' => $machine->id, 'type' => 'show']) }}"><i class="bx bx-show me-1"></i> Ver</button>
                                                    <button type="button" class="dropdown-item btn-modal" data-url="{{ route('gcp-machines.modal', ['gcp_machine' => $machine->id, 'type' => 'edit']) }}"><i class="bx bx-edit-alt me-1"></i> Editar</button>
                                                    <button type="button" class="dropdown-item text-warning btn-modal" data-url="{{ route('gcp-machines.modal', ['gcp_machine' => $machine->id, 'type' => 'power-off']) }}"><i class="bx bx-power-off me-1"></i> Apagar</button>
                                                    <button type="button" class="dropdown-item text-danger btn-modal" data-url="{{ route('gcp-machines.modal', ['gcp_machine' => $machine->id, 'type' => 'delete']) }}"><i class="bx bx-trash me-1"></i> Eliminar</button>
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

    @include('gcp-machines.create')

    <div id="modalContainer"></div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.body.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-modal');
                if (!btn) return;
                
                e.preventDefault();
                const url = btn.dataset.url;
                
                fetch(url)
                    .then(r => {
                        if (!r.ok) throw new Error('Error loading modal');
                        return r.text();
                    })
                    .then(html => {
                        document.getElementById('modalContainer').innerHTML = html;
                        const modalEl = document.getElementById('modalContainer').querySelector('.modal');
                        if (modalEl) {
                            $(modalEl).modal('show');
                            
                            if (url.includes('edit')) {
                                initSearchableSelects(modalEl);
                            }
                            
                            modalEl.addEventListener('hidden.bs.modal', function() {
                                modalEl.remove();
                            });
                        }
                    })
                    .catch(err => console.error(err));
            });

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
                const createModalEl = document.getElementById('createGcpMachineModal');
                if (createModalEl) {
                    $(createModalEl).modal('show');
                }
            @endif

            function hydrateBootstrap() {
                document.querySelectorAll('.dropdown-toggle')
                .forEach(el => {
                    if (typeof $ !== 'undefined' && typeof $.fn.dropdown !== 'undefined') {
                        new bootstrap.Dropdown(el);
                    }
                });
            }

            function initSearchableSelects(scope = document) {
                if (typeof TomSelect === 'undefined') return;
                const selects = scope.querySelectorAll('.gcp-searchable-select');
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

            const createForm = document.getElementById('createGcpForm');
            const cancelCreateBtn = document.getElementById('cancelCreateGcp');
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
