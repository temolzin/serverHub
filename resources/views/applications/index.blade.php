@extends('layouts/contentNavbarLayout')

@section('title', 'Aplicaciones')

@if (session('success'))
  <script>
    document.addEventListener('DOMContentLoaded', function() {
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
document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
        icon: 'error',
        title: 'Error en el formulario',
        html: `
            <ul style="text-align:left;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
    });
});
</script>
@endif
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
                <a href="{{ route('export', 'applications') }}" class="btn btn-primary">
                    Exportar Excel
                </a>
            </div>
        </div>
        <div class="card-body">
          <div class="mb-4">
            <input type="text" id="search-application" class="form-control form-control-sm w-50"
              placeholder="Buscar por nombre, servidor, propietario">
          </div>
          <div class="table-responsive text-nowrap" style="overflow-y:hidden;">
            <table class="table align-middle">
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
              <tbody id="applications-table">
                @include('applications.search', ['applications' => $applications])
              </tbody>
            </table>
            @foreach ($applications as $application)
              @include('applications.show', ['application' => $application])
              @include('applications.edit', ['application' => $application])
              @include('applications.delete', ['application' => $application])
            @endforeach
            <div id="applications-pagination">
              @include('applications.pagination', ['applications' => $applications])
            </div>
        </div>
    </div>
    @include('applications.create')
    @endsection

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const searchInput = document.getElementById('search-application');
            const table = document.getElementById('applications-table');
            const pagination = document.getElementById('applications-pagination');

            let timeout = null;

            function fetchApplications(url) {

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        console.error('HTTP error:', response.status);
                        return;
                    }
                    return response.json();
                })
                .then(data => {

                    if (!data) return;

                    table.innerHTML = data.table;
                    pagination.innerHTML = data.pagination;
                })
                .catch(error => {
                    console.error('AJAX Error:', error);
                });
            }
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {

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

            document.addEventListener('click', function(e) {

                const link = e.target.closest('#applications-pagination a');
                if (!link) return;

                e.preventDefault();

                fetchApplications(link.href);
            });

        });

        if (document.querySelector("#ownerSelect")) {
            new TomSelect("#ownerSelect", {
                create: false,
                sortField: {
                field: "text",
                direction: "asc"
                },
                placeholder: "Buscar propietario..."
            });
        }

        if (document.querySelector("#serverSelect")) {
            new TomSelect("#serverSelect", {
                create: false,
                sortField: {
                field: "text",
                direction: "asc"
                },
                placeholder: "Buscar servidor..."
            });
        }

        if (document.querySelector("#gcpMachineSelect")) {
            new TomSelect("#gcpMachineSelect", {
                create: false,
                sortField: {
                field: "text",
                direction: "asc"
                },
                placeholder: "Buscar maquina GCP..."
            });
        }

        document.querySelectorAll('.ownerSelectEdit').forEach(el => {
            new TomSelect(el, {
                create: false,
                sortField: {
                field: "text",
                direction: "asc"
                },
                render: {
                option: (data, escape) =>
                    `<div style="text-align:left;">${escape(data.text)}</div>`,
                item: (data, escape) =>
                    `<div style="text-align:left;">${escape(data.text)}</div>`
                }
            });
        });

        document.querySelectorAll('.serverSelectEdit').forEach(el => {
            new TomSelect(el, {
                create: false,
                sortField: {
                field: "text",
                direction: "asc"
                },
                render: {
                option: (data, escape) =>
                    `<div style="text-align:left;">${escape(data.text)}</div>`,
                item: (data, escape) =>
                    `<div style="text-align:left;">${escape(data.text)}</div>`
                }
            });
        });

        document.querySelectorAll('.gcpMachineSelectEdit').forEach(el => {
            new TomSelect(el, {
                create: false,
                sortField: {
                field: "text",
                direction: "asc"
                },
                render: {
                option: (data, escape) =>
                    `<div style="text-align:left;">${escape(data.text)}</div>`,
                item: (data, escape) =>
                    `<div style="text-align:left;">${escape(data.text)}</div>`
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {

            const createModal = document.getElementById('createApplicationModal');
            const createForm = document.getElementById('createApplicationForm');

            if (createModal) {
                createModal.addEventListener('hidden.bs.modal', function() {
                createForm.reset();

                if (createForm.querySelector('#ownerSelect')?.tomselect) {
                    createForm.querySelector('#ownerSelect').tomselect.clear();
                }

                if (createForm.querySelector('#serverSelect')?.tomselect) {
                    createForm.querySelector('#serverSelect').tomselect.clear();
                }

                if (createForm.querySelector('#gcpMachineSelect')?.tomselect) {
                    createForm.querySelector('#gcpMachineSelect').tomselect.clear(true);
                }
                });
            }
        });
    </script>
@endpush
