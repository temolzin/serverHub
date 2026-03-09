@extends('layouts/contentNavbarLayout')

@section('title', 'Servidores')


@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Servidores Activos</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createServerModal">
                        <i class="bx bx-plus me-1"></i> Agregar servidor
                        </button>
                        <a href="{{ route('export', 'servers') }}" class="btn btn-primary">Exportar Excel</a>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadExcelModal">Subir
                        Excel</button>
                    </div>
                </div>
                <div class="card-body">
                <div>
                    <div class="dt-loading">Cargando datos...</div>
                    <table id="dt-servers" class="table align-middle" style="width:100%">
                        <thead>
                            <tr>
                            <th>ID</th>
                            <th>UUID</th>
                            <th>Aplicacion</th>
                            <th>Hostname</th>
                            <th>Base de datos</th>
                            <th>Entorno</th>
                            <th>IP primaria</th>
                            <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @include('servers.search', ['servers' => $servers])
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
    @include('servers.create')
    <div class="modal fade" id="uploadExcelModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Subir archivo Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('servers.import') }}" method="POST" enctype="multipart/form-data" id="excelUploadForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                        <label class="form-label">Seleccionar archivo</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Subir Excel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var successMessage = @json(session('success'));
                var importSummary = @json(session('import_summary', []));
                var rows = Array.isArray(importSummary) && importSummary.length ? importSummary.map(function(i) {
                return '<li class="d-flex justify-content-between border-bottom py-1"><span>' + (i.label || 'Tabla') +
                    '</span><strong>' + (Number(i.total) || 0) + '</strong></li>';
                }).join('') : '';
                Swal.fire({
                    icon: 'success',
                    title: 'Listo',
                    confirmButtonText: 'Perfecto',
                    ...(rows ? {
                        html: '<div class="text-start mb-3"><p class="mb-2 fw-semibold">Total importado por tabla:</p><ul class="list-unstyled mb-0">' +
                        rows + '</ul></div><p class="mb-0">' + successMessage + '</p>'
                    } : {text: successMessage})
                });
            });
        </script>
    @endif
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = document.getElementById('createServerModal');
                if (modal) bootstrap.Modal.getOrCreateInstance(modal).show();
            });
        </script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script>
        jQuery(function($) {
        function hydrateBootstrap() {
            document.querySelectorAll('.dropdown-toggle').forEach(function(el) {
            bootstrap.Dropdown.getOrCreateInstance(el);
            });
        }

        function initSearchableSelects(scope) {
            scope = scope || document;
            if (typeof TomSelect === 'undefined') return;
            scope.querySelectorAll('.server-searchable-select').forEach(function(select) {
                if (select.tomselect) return;
                var tom = new TomSelect(select, {
                    create: false,
                    sortField: {
                    field: 'text',
                    direction: 'asc'
                    },
                    placeholder: select.dataset.placeholder || 'Buscar...'
                });
                if (select.closest('[id^="editServerModal"]')) {
                    var alignLeft = function() {
                        tom.control.style.textAlign = 'left';
                        tom.control_input.style.textAlign = 'left';
                        tom.dropdown.style.textAlign = 'left';
                        tom.dropdown_content.style.textAlign = 'left';
                        tom.dropdown.querySelectorAll('.option, .optgroup-header').forEach(function(el) {
                            el.style.textAlign = 'left';
                        });
                    };
                    alignLeft();
                    tom.on('dropdown_open', alignLeft);
                    tom.on('type', alignLeft);
                }
            });
        }

        $('#dt-servers').DataTable({
            pageLength: 10,
            deferRender: true,
            processing: false,
            dom: '<"dt-top d-flex justify-content-between align-items-center gap-3 mb-2"lf>rt<"dt-bottom d-flex justify-content-end align-items-center mt-2"p>',
            order: [
            [0, 'asc']
            ],
            columnDefs: [
                {orderable:false, searchable:false, targets:-1},
                {visible:false, targets:1}
            ],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json',
                paginate: {
                    previous: '&#8249;',
                    next: '&#8250;'
                }
            },
            drawCallback: function() {
                hydrateBootstrap();
                initSearchableSelects(this.api().table().body());
            }
        });
        document.querySelectorAll('.dt-loading').forEach(function(el) {
            el.remove();
        });

        hydrateBootstrap();
        initSearchableSelects(document);

        document.getElementById('excelUploadForm').addEventListener('submit', function() {
            var modalEl = this.closest('.modal');
            if (modalEl) bootstrap.Modal.getOrCreateInstance(modalEl).hide();
            var btn = this.querySelector('button[type="submit"]');
            if (btn) btn.disabled = true;
            if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Subiendo Excel',
                html: 'Procesando archivo...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: function(popup) {
                    Swal.showLoading();
                    var wrapper = document.createElement('div');
                    wrapper.style.cssText = 'padding:0 2rem 1.5rem;width:100%';
                    wrapper.innerHTML = '<div style="width:100%;height:6px;background:#e0e0e0;border-radius:3px;overflow:hidden;margin-top:.75rem"><div id="swalProgressBar" style="width:0%;height:100%;background:linear-gradient(90deg,#696cff,#8b8eff);border-radius:3px;transition:width .4s ease"></div></div><div id="swalProgressText" style="margin-top:.4rem;font-size:.75rem;color:#697a8d;text-align:center">0%</div>';
                    popup.appendChild(wrapper);
                    var progress = 0;
                    var bar = document.getElementById('swalProgressBar');
                    var txt = document.getElementById('swalProgressText');
                    var interval = setInterval(function() {
                        if (progress < 70) { progress += Math.random() * 5 + 2; }
                        else if (progress < 90) { progress += Math.random() * 1.5 + 0.3; }
                        else if (progress < 95) { progress += Math.random() * 0.3; }
                        progress = Math.min(progress, 95);
                        if (bar) bar.style.width = progress + '%';
                        if (txt) txt.textContent = Math.round(progress) + '%';
                        if (progress >= 95) clearInterval(interval);
                    }, 500);
                }
            });
        }
        });

        var createForm = document.getElementById('createServerForm');
        var cancelBtn = document.getElementById('cancelCreateServer');
            if (createForm && cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    createForm.reset();
                    createForm.querySelectorAll('input, textarea, select').forEach(function(el) {
                        el.classList.remove('is-invalid');
                        el.setCustomValidity('');
                        if (el.tomselect) {
                        var d = el.querySelector('option[selected]');
                        el.tomselect.setValue(d ? d.value : '', true);
                        }
                    });
                    createForm.querySelectorAll('.text-danger, .invalid-feedback').forEach(function(el) {
                        el.classList.add('d-none');
                        el.textContent = '';
                    });
                    var btn = createForm.querySelector('button[type="submit"]');
                    if (btn) btn.disabled = false;
                });
            }
        });
    </script>
@endpush
