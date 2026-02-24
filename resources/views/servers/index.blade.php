@extends('layouts/contentNavbarLayout')

@section('title', 'Servers')

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
      const modal = document.getElementById('createServerModal');
      if (modal) {
        bootstrap.Modal.getOrCreateInstance(modal).show();
      }
    });
  </script>
@endif

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Servidores Activos</h5>
          <div class="d-flex gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createServerModal">
              <i class="bx bx-plus me-1"></i> Agregar servidor
            </button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadExcelModal">
              Subir Excel
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="mb-4">
            <input type="text" id="search-server" class="form-control form-control-sm w-50"
              placeholder="Buscar por UUID, aplicacion, hostname o IP">
          </div>
          <div class="table-responsive text-nowrap">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Aplicacion</th>
                  <th>Hostname</th>
                  <th>Entorno</th>
                  <th>IP primaria</th>
                  <th class="text-end">Acciones</th>
                </tr>
              </thead>
              <tbody id="servers-search">
                @include('servers.search', ['servers' => $servers])
              </tbody>
            </table>
            <div id="servers-pagination">
              @include('servers.pagination', ['servers' => $servers])
            </div>
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
        <form action="{{ route('servers.import') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Seleccionar archivo</label>
              <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              Cancelar
            </button>
            <button type="submit" class="btn btn-success">
              Subir Excel
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {

      const input = document.getElementById('search-server');
      const table = document.getElementById('servers-search');
      const pagination = document.getElementById('servers-pagination');
      const baseUrl = `{{ route('servers.index') }}`;
      let timeout = null;

      if (!input || !table || !pagination) return;

      function hydrateBootstrap() {
        document.querySelectorAll('.dropdown-toggle')
          .forEach(el => bootstrap.Dropdown.getOrCreateInstance(el));
      }

      function initSearchableSelects(scope = document) {
        if (typeof TomSelect === 'undefined') return;

        const selects = scope.querySelectorAll('.server-searchable-select');
        selects.forEach(select => {
          if (select.tomselect) return;

          const tom = new TomSelect(select, {
            create: false,
            sortField: {
              field: 'text',
              direction: 'asc'
            },
            placeholder: select.dataset.placeholder || 'Buscar...'
          });

          if (select.closest('[id^="editServerModal"]')) {
            const alignLeft = () => {
              tom.control.style.textAlign = 'left';
              tom.control_input.style.textAlign = 'left';
              tom.dropdown.style.textAlign = 'left';
              tom.dropdown_content.style.textAlign = 'left';
              tom.dropdown
                .querySelectorAll('.option, .optgroup-header')
                .forEach(el => {
                  el.style.textAlign = 'left';
                });
            };

            alignLeft();
            tom.on('dropdown_open', alignLeft);
            tom.on('type', alignLeft);
          }
        });
      }

      function fetchServers(url) {
        fetch(url, {
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }
          })
          .then(res => {
            if (!res.ok) throw new Error('Error en búsqueda');
            return res.json();
          })
          .then(data => {
            table.innerHTML = data.table;
            pagination.innerHTML = data.pagination;
            hydrateBootstrap();
            initSearchableSelects(table);
          })
          .catch(err => {
            console.error(err);
          });
      }

      input.addEventListener('keyup', function() {
        clearTimeout(timeout);
        timeout = setTimeout(function() {
          const value = input.value.trim();
          const url = value ?
            `${baseUrl}?search=${encodeURIComponent(value)}` :
            baseUrl;
          fetchServers(url);
        }, 300);
      });

      document.addEventListener('click', function(e) {
        const link = e.target.closest('#servers-pagination a');
        if (!link) return;

        e.preventDefault();
        fetchServers(link.href);
      });

      const createForm = document.getElementById('createServerForm');
      const cancelCreateBtn = document.getElementById('cancelCreateServer');
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

      initSearchableSelects(document);
    });
  </script>
@endpush
