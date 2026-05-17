@extends('layouts/contentNavbarLayout')

@section('title', 'Aplicaciones')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <h5 class="mb-0">Aplicaciones</h5>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <button class="btn btn-success text-center" data-bs-toggle="modal" data-bs-target="#createApplicationModal"><i class="bx bx-plus me-1"></i> Agregar Aplicación</button>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <table class="table align-middle w-100 datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Servidor</th>
                                    <th>GCP</th>
                                    <th>Propietario</th>
                                    <th>Versión</th>
                                    <th>Estado</th>
                                    <th>Memoria (MB)</th>
                                    <th class="text-end no-export">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($applications as $application)
                                    <tr>
                                        <td>{{ $application->id }}</td>
                                        <td>{{ $application->name }}</td>
                                        <td>{{ $application->server?->hostname_internal ?? '-' }}</td>
                                        <td>{{ $application->gcpMachine?->machine_name ?? '-' }}</td>
                                        <td>{{ $application->owner?->name ?? '-' }} {{ $application->owner?->last_name ?? '' }}</td>
                                        <td>{{ $application->version ?? '-' }}</td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'producción' => 'success',
                                                    'desarrollo' => 'info',
                                                    'inactivo' => 'secondary',
                                                ];
                                            @endphp
                                            <span class="badge bg-label-{{ $statusColors[$application->status] ?? 'secondary' }}"> {{ ucfirst($application->status) }}</span>
                                        </td>
                                        <td>{{ $application->assigned_memory ?? '-' }} MB</td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <button type="button" class="dropdown-item btn-modal" data-url="{{ route('applications.modal', ['application' => $application->id, 'type' => 'show']) }}"><i class="bx bx-show me-1"></i>Ver</button>
                                                    <button type="button" class="dropdown-item btn-modal" data-url="{{ route('applications.modal', ['application' => $application->id, 'type' => 'edit']) }}"><i class="bx bx-edit-alt me-1"></i> Editar</button>
                                                    @if(!$application->is_in_use)
                                                        <button type="button" class="dropdown-item text-danger btn-modal" data-url="{{ route('applications.modal', ['application' => $application->id, 'type' => 'delete']) }}"><i class="bx bx-trash me-1"></i> Eliminar</button>
                                                    @else
                                                        <span class="dropdown-item text-secondary"><i class="bx bx-lock-alt me-1"></i> En uso</span>
                                                    @endif
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
    @include('applications.create')

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
                                initTomSelectModal(modalEl);
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
                    title: '¡Listo!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'Perfecto',
                    timer: 5000,
                    timerProgressBar: true
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Error en el formulario',
                    html: `<ul style="text-align:left;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>`
                });
            @endif

            function initTomSelectModal(scope = document) {
                scope.querySelectorAll('.ownerSelectEdit').forEach(el => {
                    if (!el.tomselect) new TomSelect(el, {create: false});
                });
                scope.querySelectorAll('.serverSelectEdit').forEach(el => {
                    if (!el.tomselect) new TomSelect(el, {create: false});
                });
                scope.querySelectorAll('.gcpMachineSelectEdit').forEach(el => {
                    if (!el.tomselect) new TomSelect(el, {create: false});
                });
            }

            function initTomSelect() {
                if (document.querySelector("#ownerSelect") && !document.querySelector("#ownerSelect").tomselect) {
                    new TomSelect("#ownerSelect", {create: false});
                }
                if (document.querySelector("#serverSelect") && !document.querySelector("#serverSelect").tomselect) {
                    new TomSelect("#serverSelect", {create: false});
                }
                if (document.querySelector("#gcpMachineSelect") && !document.querySelector("#gcpMachineSelect").tomselect) {
                    new TomSelect("#gcpMachineSelect", {create: false});
                }

                document.querySelectorAll('.ownerSelectEdit').forEach(el => {
                    if (!el.tomselect) new TomSelect(el, {create: false});
                });
                document.querySelectorAll('.serverSelectEdit').forEach(el => {
                    if (!el.tomselect) new TomSelect(el, {create: false});
                });
                document.querySelectorAll('.gcpMachineSelectEdit').forEach(el => {
                    if (!el.tomselect) new TomSelect(el, {create: false});
                });
            }
            initTomSelect();

            const createModal = document.getElementById('createApplicationModal');
            const createForm = document.getElementById('createApplicationForm');

            if (createModal && createForm) {
                createModal.addEventListener('hidden.bs.modal', function() {
                    createForm.reset();
                    ['#ownerSelect', '#serverSelect', '#gcpMachineSelect'].forEach(sel => {
                        const el = createForm.querySelector(sel);
                        if (el?.tomselect) el.tomselect.clear();
                    });
                });
            }
        });
    </script>
@endpush
