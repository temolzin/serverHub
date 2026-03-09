@extends('layouts/contentNavbarLayout')

@section('title', 'Servidores apagados')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Servidores Apagados</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createServerOffModal">
                        <i class="bx bx-plus me-1"></i> Agregar servidor apagado
                    </button>
                </div>
                <div class="card-body">
                    <div>
                        <div class="dt-loading">Cargando datos...</div>
                        <table id="dt-servers-off" class="table align-middle" style="width:100%">
                            <thead>
                                <tr>
                                <th>ID</th>
                                <th>UUID</th>
                                <th>VM</th>
                                <th>Nombre DNS</th>
                                <th>SO segun VMware</th>
                                <th>Estado</th>
                                <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @include('serversOff.search', ['servers' => $servers])
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('serversOff.create')
@endsection

@push('scripts')
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Listo!',
                    text: @json(session('success')),
                    confirmButtonText: 'Perfecto',
                    timer: 5000,
                    timerProgressBar: true
                });
            });
        </script>
    @endif
    @if ($errors->any())
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('createServerOffModal');
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

            $('#dt-servers-off').DataTable({
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
        });
    </script>
@endpush
