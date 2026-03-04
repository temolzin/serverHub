@extends('layouts/contentNavbarLayout')

@section('title', 'Maquinas GCP')

@if (session('success'))
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({
        icon: 'success',
        title: 'Listo',
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
      const createModalEl = document.getElementById('createGcpMachineModal');
      if (createModalEl) {
        bootstrap.Modal.getOrCreateInstance(createModalEl).show();
      }
    });
  </script>
@endif

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex align-items-center">
          <h5 class="mb-0">Máquinas GCP</h5>
          <div class="ms-auto d-flex gap-2">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createGcpMachineModal">
              <i class="bx bx-plus me-1"></i> Agregar máquina
            </button>
            <a href="{{ route('export', 'gcp-machines') }}" class="btn btn-primary">
              Exportar Excel
            </a>
          </div>
        </div>
        <div class="card-body">
          <div class="mb-4">
            <input type="text" id="search-gcp" class="form-control form-control-sm w-50"
              placeholder="Buscar por proyecto, máquinas, aplicación, UUID o IP">
          </div>
          <div class="table-responsive text-nowrap" style="overflow-y: hidden;">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Proyecto</th>
                  <th>Máquina</th>
                  <th>Aplicación</th>
                  <th>Entorno</th>
                  <th>Estado</th>
                  <th>IP interna</th>
                  <th class="text-end">Acciones</th>
                </tr>
              </thead>
              <tbody id="gcp-search">
                @include('gcp-machines.search', ['gcpMachines' => $gcpMachines])
              </tbody>
            </table>
            <div id="gcp-pagination">
              @include('gcp-machines.pagination', ['gcpMachines' => $gcpMachines])
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('gcp-machines.create')
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const input = document.getElementById('search-gcp');
      const table = document.getElementById('gcp-search');
      const pagination = document.getElementById('gcp-pagination');
      const baseUrl = `{{ route('gcp-machines.index') }}`;
      let timeout = null;

      if (!input || !table || !pagination) return;

      function hydrateBootstrap() {
        document.querySelectorAll('.dropdown-toggle')
          .forEach(el => bootstrap.Dropdown.getOrCreateInstance(el));
      }

      function initSearchableSelects(scope = document) {
        if (typeof TomSelect === 'undefined') return;

        const selects = scope.querySelectorAll('.gcp-searchable-select');
        selects.forEach(select => {
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

      function fetchGcp(url) {
        fetch(url, {
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }
          })
          .then(res => {
            if (!res.ok) throw new Error('Error en busqueda');
            return res.json();
          })
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
          const url = value ? `${baseUrl}?search=${encodeURIComponent(value)}` : baseUrl;
          fetchGcp(url);
        }, 300);
      });

      document.addEventListener('click', function(e) {
        const link = e.target.closest('#gcp-pagination a');
        if (!link) return;

        e.preventDefault();
        fetchGcp(link.href);
      });

      const createForm = document.getElementById('createGcpForm');
      const cancelCreateBtn = document.getElementById('cancelCreateGcp');
      if (createForm && cancelCreateBtn) {
        cancelCreateBtn.addEventListener('click', function() {
          createForm.reset();

          createForm.querySelectorAll('input, textarea, select').forEach(el => {
            el.classList.remove('is-invalid');
            el.setCustomValidity('');

            if (el.tomselect) {
              const defaultOption = el.querySelector('option[selected]');
              const defaultValue = defaultOption ? defaultOption.value : '';
              el.tomselect.setValue(defaultValue, true);
            }
          });

          createForm.querySelectorAll('.text-danger, .invalid-feedback').forEach(el => {
            el.classList.add('d-none');
            el.textContent = '';
          });

          const submitBtn = createForm.querySelector('button[type="submit"]');
          if (submitBtn) {
            submitBtn.disabled = false;
          }
        });
      }

      hydrateBootstrap();
      initSearchableSelects(document);
    });
  </script>
@endpush
