@extends('layouts/contentNavbarLayout')

@section('title', 'Aplicaciones')

@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Listo!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK',
                timer: 5000,
                timerProgressBar: true
            });
        });
    </script>
@endif

@if ($errors->any())
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                icon: 'error',
                title: 'Error en el formulario',
                html: `
                    <ul style="text-align:left;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `
            });
        });
    </script>
@endif

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">

            {{-- HEADER --}}
            <div class="card-header d-flex align-items-center">
                <h5 class="mb-0">Aplicaciones</h5>

                <div class="ms-auto d-flex gap-2">
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createApplicationModal">
                        <i class="bx bx-plus me-1"></i> Agregar Aplicación
                    </button>

                    <a href="{{ route('export', 'applications') }}" class="btn btn-primary">
                        Exportar Excel
                    </a>
                </div>
            </div>

            {{-- BODY --}}
            <div class="card-body">

                {{-- SEARCH --}}
                <div class="mb-4">
                    <input
                        type="text"
                        id="search-application"
                        class="form-control form-control-sm w-50"
                        placeholder="Buscar por nombre, servidor, propietario"
                    >
                </div>

                {{-- TABLE --}}
                <div class="table-responsive text-nowrap" style="overflow-y:hidden;">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Servidor</th>
                                <th>GCP</th>
                                <th>Propietario</th>
                                <th>Versión</th>
                                <th>Estado</th>
                                <th>Memoria (MB)</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>

                        <tbody id="applications-table">
                            @include('applications.search', ['applications' => $applications])
                        </tbody>
                    </table>

                    {{-- PAGINATION --}}
                    <div id="applications-pagination">
                        @include('applications.pagination', ['applications' => $applications])
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODALES FUERA DEL TABLE (MEJOR PRÁCTICA) --}}
<div id="modals-container">
    @foreach ($applications as $application)
        @include('applications.show', ['application' => $application])
        @include('applications.edit', ['application' => $application])
        @include('applications.delete', ['application' => $application])
    @endforeach
</div>

@include('applications.create')

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('search-application');
    const table = document.getElementById('applications-table');
    const pagination = document.getElementById('applications-pagination');

    let timeout = null;

    function initTomSelect() {

        // CREATE
        if (document.querySelector("#ownerSelect") && !document.querySelector("#ownerSelect").tomselect) {
            new TomSelect("#ownerSelect", { create: false });
        }

        if (document.querySelector("#serverSelect") && !document.querySelector("#serverSelect").tomselect) {
            new TomSelect("#serverSelect", { create: false });
        }

        if (document.querySelector("#gcpMachineSelect") && !document.querySelector("#gcpMachineSelect").tomselect) {
            new TomSelect("#gcpMachineSelect", { create: false });
        }

        // EDIT
        document.querySelectorAll('.ownerSelectEdit').forEach(el => {
            if (!el.tomselect) new TomSelect(el, { create: false });
        });

        document.querySelectorAll('.serverSelectEdit').forEach(el => {
            if (!el.tomselect) new TomSelect(el, { create: false });
        });

        document.querySelectorAll('.gcpMachineSelectEdit').forEach(el => {
            if (!el.tomselect) new TomSelect(el, { create: false });
        });
    }

    function fetchApplications(url) {

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {

            table.innerHTML = data.table;
            pagination.innerHTML = data.pagination;

            // ⚠️ IMPORTANTE: reinit
            initTomSelect();
        })
        .catch(err => console.error(err));
    }

    // SEARCH
    if (searchInput) {
        searchInput.addEventListener('keyup', function () {

            clearTimeout(timeout);

            timeout = setTimeout(() => {

                let url = `{{ route('applications.index') }}`;

                if (this.value.trim() !== '') {
                    url += `?search=${encodeURIComponent(this.value.trim())}`;
                }

                fetchApplications(url);

            }, 300);
        });
    }

    // PAGINATION
    document.addEventListener('click', function (e) {

        const link = e.target.closest('#applications-pagination a');
        if (!link) return;

        e.preventDefault();
        fetchApplications(link.href);
    });

    // RESET CREATE
    const createModal = document.getElementById('createApplicationModal');
    const createForm = document.getElementById('createApplicationForm');

    if (createModal && createForm) {
        createModal.addEventListener('hidden.bs.modal', function () {
            createForm.reset();

            ['#ownerSelect', '#serverSelect', '#gcpMachineSelect'].forEach(sel => {
                const el = createForm.querySelector(sel);
                if (el?.tomselect) el.tomselect.clear();
            });
        });
    }

    initTomSelect();
});
</script>
@endpush
