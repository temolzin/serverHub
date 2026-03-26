@extends('layouts/contentNavbarLayout')

@section('title', 'Apliance - Encendidos')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Apliances Activos</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createApplianceModal">
                            <i class="bx bx-plus me-1"></i> Agregar apliance
                        </button>
                        <a href="{{ route('export', 'appliances') }}" class="btn btn-primary">Exportar Excel</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <input type="text" id="search-appliance" class="form-control form-control-sm w-50"
                            placeholder="Buscar por VM, hostname, IP o DNS">
                    </div>
                    <div class="table-responsive text-nowrap">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>VM</th>
                                    <th>IP Primaria</th>
                                    <th>Datacenter</th>
                                    <th>Tipo de Aplicación</th>
                                    <th>Estado</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="appliances-search">
                                @include('appliances.search', ['servers' => $servers])
                            </tbody>
                        </table>
                        <div id="appliances-pagination">
                            @include('appliances.pagination', ['servers' => $servers])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('appliances.create')
@endsection

@push('scripts')
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Listo',
                    text: @json(session('success')),
                    confirmButtonText: 'Perfecto'
                });
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('createApplianceModal');
                if (modal) bootstrap.Modal.getOrCreateInstance(modal).show();
            });
        </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let excelUploadProgressInterval = null;
            const input = document.getElementById('search-appliance');
            const table = document.getElementById('appliances-search');
            const pagination = document.getElementById('appliances-pagination');
            const baseUrl = `{{ route('appliances.index') }}`;
            let timeout = null;

            if (!input || !table || !pagination) return;

            function hydrateBootstrap() {
                document.querySelectorAll('.dropdown-toggle')
                    .forEach(el => bootstrap.Dropdown.getOrCreateInstance(el));
            }

            function initSearchableSelects(scope = document) {
                if (typeof TomSelect === 'undefined') return;
                scope.querySelectorAll('.appliance-searchable-select').forEach(select => {
                    if (select.tomselect) return;
                    new TomSelect(select, {
                        create: false,
                        sortField: { field: 'text', direction: 'asc' },
                        placeholder: select.dataset.placeholder || 'Buscar...'
                    });
                });
            }

            function fetchAppliances(url) {
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(res => { if (!res.ok) throw new Error('Error'); return res.json(); })
                    .then(data => {
                        table.innerHTML = data.table;
                        pagination.innerHTML = data.pagination;
                        hydrateBootstrap();
                        initSearchableSelects(table);
                    })
                    .catch(err => console.error(err));
            }

            input.addEventListener('keyup', function() {
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    const value = input.value.trim();
                    fetchAppliances(value ? `${baseUrl}?search=${encodeURIComponent(value)}` : baseUrl);
                }, 300);
            });

            document.addEventListener('click', function(e) {
                const link = e.target.closest('#appliances-pagination a');
                if (!link) return;
                e.preventDefault();
                fetchAppliances(link.href);
            });

            const createForm = document.getElementById('createApplianceForm');
            const cancelCreateBtn = document.getElementById('cancelCreateAppliance');
            if (createForm && cancelCreateBtn) {
                cancelCreateBtn.addEventListener('click', function() {
                    createForm.reset();
                    createForm.querySelectorAll('input, textarea, select').forEach(el => {
                        el.classList.remove('is-invalid');
                        if (el.tomselect) {
                            const def = el.querySelector('option[selected]');
                            el.tomselect.setValue(def ? def.value : '', true);
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
