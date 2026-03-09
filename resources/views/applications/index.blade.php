@extends('layouts/contentNavbarLayout')

@section('title', 'Aplicaciones')

@section('content')
    <div class="row">
        <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
            <h5 class="mb-0">Aplicaciones</h5>
            <div class="ms-auto d-flex gap-2">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createApplicationModal">
                <i class="bx bx-plus me-1"></i> Agregar Aplicación
                </button>
                <a href="{{ route('export', 'applications') }}" class="btn btn-primary">Exportar Excel</a>
            </div>
            </div>
            <div class="card-body">
            <div>
                <table id="dt-applications" class="table align-middle" style="width:100%">
                <thead>
                    <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Servidor</th>
                    <th>Propietario</th>
                    <th>Version</th>
                    <th>Estado</th>
                    <th>Memoria (MB)</th>
                    <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @include('applications.search', ['applications' => $applications])
                </tbody>
                </table>
                @foreach ($applications as $application)
                @include('applications.show', ['application' => $application])
                @include('applications.edit', ['application' => $application])
                @include('applications.delete', ['application' => $application])
                @endforeach
            </div>
            </div>
        </div>
        </div>
    </div>
    @include('applications.create')
@endsection

@push('scripts')
    @if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({ icon: 'success', title: 'Listo!', text: @json(session('success')), confirmButtonText: 'Perfecto', timer: 5000, timerProgressBar: true });
        });
    </script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script>
        jQuery(function($) {
            $('#dt-applications').DataTable({
                pageLength: 10, deferRender: true,
                dom: '<"dt-top d-flex justify-content-between align-items-center gap-3 mb-2"lf>rt<"dt-bottom d-flex justify-content-end align-items-center mt-2"p>',
                order: [[0, 'asc']],
                columnDefs: [{ orderable: false, searchable: false, targets: -1 }],
                language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json', paginate: { previous: '&#8249;', next: '&#8250;' }
                }
            });

            if (document.querySelector("#ownerSelect")) {
                new TomSelect("#ownerSelect", { create: false, sortField: { field: "text", direction: "asc" }, placeholder: "Buscar propietario..." });
            }
            if (document.querySelector("#serverSelect")) {
                new TomSelect("#serverSelect", { create: false, sortField: { field: "text", direction: "asc" }, placeholder: "Buscar servidor..." });
            }
            document.querySelectorAll('.ownerSelectEdit').forEach(el => {
                new TomSelect(el, { create: false, sortField: { field: "text", direction: "asc" }, render: { option: (d, e) => `<div style="text-align:left;">${e(d.text)}</div>`, item: (d, e) => `<div style="text-align:left;">${e(d.text)}</div>` }});
            });
            document.querySelectorAll('.serverSelectEdit').forEach(el => {
                new TomSelect(el, { create: false, sortField: { field: "text", direction: "asc" }, render: { option: (d, e) => `<div style="text-align:left;">${e(d.text)}</div>`, item: (d, e) => `<div style="text-align:left;">${e(d.text)}</div>` }});
            });

            const createModal = document.getElementById('createApplicationModal');
            const createForm = document.getElementById('createApplicationForm');
            if (createModal) {
                createModal.addEventListener('hidden.bs.modal', function() {
                    createForm.reset();
                    if (createForm.querySelector('#ownerSelect')?.tomselect) createForm.querySelector('#ownerSelect').tomselect.clear();
                    if (createForm.querySelector('#serverSelect')?.tomselect) createForm.querySelector('#serverSelect').tomselect.clear();
                });
            }
        });
    </script>
@endpush
