@extends('layouts/contentNavbarLayout')

@section('title', 'On-Premise')

@section('content')
    <style>
        .swal-above-modal { z-index: 9999 !important; }
        .swal2-container.swal-above-modal { z-index: 9999 !important; }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <h5 class="mb-0">Servidores Activos</h5>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <button class="btn btn-success text-center" data-bs-toggle="modal" data-bs-target="#createServerModal"><i class="bx bx-plus me-1"></i> Agregar servidor</button>
                        <button class="btn btn-primary text-center" data-bs-toggle="modal" data-bs-target="#uploadExcelModal">Subir Excel</button>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <table class="table align-middle w-100 datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>VM</th>
                                    <th>IP USUARIO</th>
                                    <th>IP MONITOREO</th>
                                    <th>ESTADO</th>
                                    <th>ENTORNO</th>
                                    <th>DATACENTER</th>
                                    <th>HOSTNAME</th>
                                    <th>OTRAS IPS</th>
                                    <th class="text-end no-export">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($servers as $server)
                                    <tr>
                                        <td>{{ $server->id }}</td>
                                        <td>{{ filled($server->vm_according_to_the_vmware) ? $server->vm_according_to_the_vmware : 'N/A' }}</td>
                                        <td>{{ filled($server->ip_user) ? $server->ip_user : 'N/A' }}</td>
                                        <td>{{ filled($server->ip_monitoring) ? $server->ip_monitoring : 'N/A' }}</td>
                                        <td><span class="badge {{ $server->display_state_badge_class }}">{{ $server->display_state_label }}</span></td>
                                        <td>{{ strtoupper(filled($server->environment) ? $server->environment : 'N/A') }}</td>
                                        <td>{{ filled($server->datacenter) ? $server->datacenter : 'N/A' }}</td>
                                        <td>{{ filled($server->hostname_internal) ? $server->hostname_internal : 'N/A' }}</td>
                                        <td>{{ filled($server->other_ips) ? $server->other_ips : 'N/A' }}</td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <button type="button" class="dropdown-item btn-modal" data-url="{{ route('servers.modal', ['server' => $server->id, 'type' => 'show']) }}"><i class="bx bx-show me-1"></i> Ver</button>
                                                    <button type="button" class="dropdown-item btn-modal" data-url="{{ route('servers.modal', ['server' => $server->id, 'type' => 'edit']) }}"><i class="bx bx-edit-alt me-1"></i> Editar</button>
                                                    <button type="button" class="dropdown-item text-warning btn-modal" data-url="{{ route('servers.modal', ['server' => $server->id, 'type' => 'power-off']) }}"><i class="bx bx-power-off me-1"></i> Apagar</button>
                                                    <button type="button" class="dropdown-item text-danger btn-modal" data-url="{{ route('servers.modal', ['server' => $server->id, 'type' => 'delete']) }}"><i class="bx bx-trash me-1"></i> Eliminar</button>
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

    @include('servers.create')

    <div id="modalContainer"></div>

    <div class="modal fade" id="uploadExcelModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Subir archivo Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <form action="{{ route('servers.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Seleccionar archivo (obligatorio)</label>
                            <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ asset('layout/Layout.xlsx') }}" class="btn btn-outline-primary" download>
                            Descargar plantilla
                        </a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Subir Excel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
                const successMessage = @json(session('success'));
                const importSummary = @json(session('import_summary', []));
                const escapeHtml = value => String(value).replace(/[&<>"']/g, char => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
                }[char]));
                const rows = Array.isArray(importSummary) && importSummary.length
                    ? importSummary.map(item => `
                    <li class="border-bottom py-2">
                        <div class="d-flex justify-content-between">
                            <span>${escapeHtml(item.label ?? 'Tabla')}</span>
                            <strong>${Number(item.total) || 0} registros</strong>
                        </div>
                        <div class="d-flex gap-3 small">
                            <span class="text-success">Nuevos: ${Number(item.created) || 0}</span>
                            <span class="text-primary">Actualizados: ${Number(item.updated) || 0}</span>
                        </div>
                    </li>`).join('')
                    : '';
                const swalContent = rows
                    ?   {
                            html: `
                                <div class="text-start mb-3">
                                    <p class="mb-2 fw-semibold">Resumen por tabla:</p>
                                    <ul class="list-unstyled mb-0">${rows}</ul>
                                </div>
                                <p class="mb-0">${escapeHtml(successMessage)}</p>
                            `,
                        }
                    :   {
                            text: successMessage,
                        };

                Swal.fire({
                    icon: 'success',
                    title: 'Listo',
                    confirmButtonText: 'Perfecto',
                    ...swalContent,
                });
            @endif

            @if ($errors->any())
                const createModalEl = document.getElementById('createServerModal');
                if (createModalEl) bootstrap.Modal.getOrCreateInstance(createModalEl).show();
            @endif

            function initSearchableSelects(scope = document) {
                if (typeof TomSelect === 'undefined') return;
                scope.querySelectorAll('.server-searchable-select').forEach(select => {
                    if (select.tomselect) return;
                    const tom = new TomSelect(select, {
                        create: false,
                        sortField: { field: 'text', direction: 'asc' },
                        placeholder: select.dataset.placeholder || 'Buscar...'
                    });
                    if (select.closest('[id^="editServerModal"]')) {
                        const alignLeft = () => {
                            tom.control.style.textAlign = 'left';
                            tom.control_input.style.textAlign = 'left';
                            tom.dropdown.style.textAlign = 'left';
                            tom.dropdown_content.style.textAlign = 'left';
                            tom.dropdown.querySelectorAll('.option, .optgroup-header').forEach(el => { el.style.textAlign = 'left'; });
                        };
                        alignLeft();
                        tom.on('dropdown_open', alignLeft);
                        tom.on('type', alignLeft);
                    }
                });
            }

            let excelUploadProgressInterval = null;
            document.querySelectorAll('form[action="{{ route('servers.import') }}"]').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) submitBtn.disabled = true;
                    if (typeof Swal === 'undefined') { form.submit(); return; }

                    let uploadProgress = 0;
                    Swal.fire({
                        title: 'Subiendo Excel',
                        html: `
                            <p class="mb-3">Procesando archivo, por favor espera...</p>
                            <div class="mb-3">
                                <div class="spinner-border text-primary" role="status" style="width:3rem;height:3rem;">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                            </div>
                            <div class="progress" style="height:10px; border-radius:8px; background:#e9ecef;">
                                <div id="uploadProgressBar" class="progress-bar progress-bar-striped progress-bar-animated"
                                    role="progressbar" style="width:0%; background:#696cff; transition:width 0.3s ease;">
                                </div>
                            </div>
                            <small id="uploadProgressText" class="text-muted mt-1 d-block">0%</small>`,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        customClass: { container: 'swal-above-modal' },
                        didOpen: () => {
                            excelUploadProgressInterval = setInterval(() => {
                                if (uploadProgress < 60)      uploadProgress += Math.random() * 10;
                                else if (uploadProgress < 85) uploadProgress += Math.random() * 4;
                                else if (uploadProgress < 95) uploadProgress += Math.random() * 1;
                                if (uploadProgress > 95) uploadProgress = 95;
                                const bar  = document.getElementById('uploadProgressBar');
                                const text = document.getElementById('uploadProgressText');
                                if (bar)  bar.style.width = uploadProgress + '%';
                                if (text) text.textContent = Math.round(uploadProgress) + '%';
                            }, 350);
                            setTimeout(() => form.submit(), 50);
                        },
                        willClose: () => { clearInterval(excelUploadProgressInterval); excelUploadProgressInterval = null; }
                    });
                });
            });

            const createForm = document.getElementById('createServerForm');
            const cancelCreateBtn = document.getElementById('cancelCreateServer');
            if (createForm && cancelCreateBtn) {
                cancelCreateBtn.addEventListener('click', function() {
                    createForm.reset();
                    createForm.querySelectorAll('input, textarea, select').forEach(el => {
                        el.classList.remove('is-invalid');
                        el.setCustomValidity('');
                        if (el.tomselect) {
                            const defaultOption = el.querySelector('option[selected]');
                            el.tomselect.setValue(defaultOption ? defaultOption.value : '', true);
                        }
                    });
                    createForm.querySelectorAll('.text-danger, .invalid-feedback').forEach(el => {
                        el.classList.add('d-none');
                        el.textContent = '';
                    });
                    const submitBtn = createForm.querySelector('button[type="submit"]');
                    if (submitBtn) submitBtn.disabled = false;
                });
            }

            initSearchableSelects(document);
        });
    </script>
@endpush
