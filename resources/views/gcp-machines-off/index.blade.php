@extends('layouts/contentNavbarLayout')

@section('title', 'Maquinas GCP apagadas')

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
      const createModalEl = document.getElementById('createGcpOffMachineModal');
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
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Máquinas GCP apagadas</h5>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createGcpOffMachineModal">
            <i class="bx bx-plus me-1"></i> Agregar máquina apagada
          </button>
        </div>
        <div class="card-body">
          <div class="mb-4">
            <input type="text" id="search-gcp-off" class="form-control form-control-sm w-50"
              placeholder="Buscar por UUID, proyecto, maquina, aplicacion o sistema operativo">
          </div>
          <div class="table-responsive text-nowrap">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Proyecto</th>
                  <th>Máquina</th>
                  <th>Aplicación</th>
                  <th>Sistema operativo</th>
                  <th>Estado</th>
                  <th class="text-end">Acciones</th>
                </tr>
              </thead>
              <tbody id="gcp-off-search">
                @include('gcp-machines-off.search', ['gcpMachines' => $gcpMachines])
              </tbody>
            </table>
            <div id="gcp-off-pagination">
              @include('gcp-machines-off.pagination', ['gcpMachines' => $gcpMachines])
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('gcp-machines-off.create')
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const input = document.getElementById('search-gcp-off');
      const table = document.getElementById('gcp-off-search');
      const pagination = document.getElementById('gcp-off-pagination');
      const baseUrl = `{{ route('gcp-machines-off.index') }}`;
      let timeout = null;

      if (!input || !table || !pagination) return;

      function hydrateBootstrap() {
        document.querySelectorAll('.dropdown-toggle')
          .forEach(el => bootstrap.Dropdown.getOrCreateInstance(el));
      }

      function initSearchableSelects(scope = document) {
        if (typeof TomSelect === 'undefined') return;

        const selects = scope.querySelectorAll('.gcp-off-searchable-select');
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

      function fetchGcpOff(url) {
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
          fetchGcpOff(url);
        }, 300);
      });

      document.addEventListener('click', function(e) {
        const link = e.target.closest('#gcp-off-pagination a');
        if (!link) return;

        e.preventDefault();
        fetchGcpOff(link.href);
      });

      const createForm = document.getElementById('createGcpOffForm');
      const cancelCreateBtn = document.getElementById('cancelCreateGcpOff');
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
