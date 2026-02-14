@extends('layouts/contentNavbarLayout')

@section('title', 'Applications')

@if (session('success'))
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        confirmButtonText: 'OK',
        timer: 5000,
        timerProgressBar: true
      });
    });
  </script>
@endif

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Aplicaciones</h5>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createApplicationModal">
            <i class="bx bx-plus me-1"></i>
            Agregar Aplicación
          </button>
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
  </script>
@endpush
