@extends('layouts/contentNavbarLayout')

@section('title', 'Maquinas GCP')

@section('content')
    <div class="row">
        <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
            <h5 class="mb-0">Maquinas GCP</h5>
            <div class="ms-auto d-flex gap-2">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createGcpMachineModal">
                <i class="bx bx-plus me-1"></i> Agregar maquina
                </button>
                <a href="{{ route('export', 'gcp-machines') }}" class="btn btn-primary">Exportar Excel</a>
            </div>
            </div>
            <div class="card-body">
            <div>
                <div class="dt-loading">Cargando datos...</div>
                <table id="dt-gcp" class="table align-middle" style="width:100%">
                <thead>
                    <tr>
                    <th>ID</th>
                    <th>UUID</th>
                    <th>Proyecto</th>
                    <th>Maquina</th>
                    <th>Aplicacion</th>
                    <th>Entorno</th>
                    <th>Estado</th>
                    <th>IP interna</th>
                    <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @include('gcp-machines.search', ['gcpMachines' => $gcpMachines])
                </tbody>
                </table>
            </div>
            </div>
        </div>
        </div>
    </div>
    @include('gcp-machines.create')
@endsection

@push('scripts')
    @if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({ icon: 'success', title: 'Listo!', text: @json(session('success')), confirmButtonText: 'Perfecto', timer: 5000, timerProgressBar: true });
        });
    </script>
    @endif
    @if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        var modal = document.getElementById('createGcpMachineModal');
        if (modal) bootstrap.Modal.getOrCreateInstance(modal).show();
        });
    </script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script>
        jQuery(function($) {
        function hydrateBootstrap() {
            document.querySelectorAll('.dropdown-toggle').forEach(el => bootstrap.Dropdown.getOrCreateInstance(el));
        }

        function initSearchableSelects(scope) {
            scope = scope || document;
            if (typeof TomSelect === 'undefined') return;
            scope.querySelectorAll('.gcp-searchable-select').forEach(function(select) {
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

        $('#dt-gcp').DataTable({
            pageLength: 10,
            deferRender: true,
            dom: '<"dt-top d-flex justify-content-between align-items-center gap-3 mb-2"lf>rt<"dt-bottom d-flex justify-content-end align-items-center mt-2"p>',
            order: [
            [0, 'asc']
            ],
            columnDefs: [{
            orderable: false,
            searchable: false,
            targets: -1
            }, {
            visible: false,
            targets: 1
            }],
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

        var createForm = document.getElementById('createGcpForm');
        var cancelBtn = document.getElementById('cancelCreateGcp');
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
