@extends('layouts/contentNavbarLayout')

@section('title', 'Servidores apagados')

@if (session('success'))
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({
        icon: 'success',
        title: '\u00a1Listo!',
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
      const createModalEl = document.getElementById('createServerOffModal');
      if (createModalEl) {
        new bootstrap.Modal(createModalEl).show();
      }
    });
  </script>
@endif

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
          <div class="mb-4">
            <input type="text" id="search-server-off" class="form-control form-control-sm w-50"
              placeholder="Buscar por UUID, VM, DNS o sistema operativo">
          </div>
          <div class="table-responsive text-nowrap">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>VM</th>
                  <th>Nombre DNS</th>
                  <th>SO segun VMware</th>
                  <th>Estado</th>
                  <th class="text-end">Acciones</th>
                </tr>
              </thead>
              <tbody id="servers-off-search">
                @include('serversOff.search', ['servers' => $servers])
              </tbody>
            </table>
            <div id="servers-off-pagination">
              @include('serversOff.pagination', ['servers' => $servers])
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('serversOff.create')
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {

      const input = document.getElementById('search-server-off');
      const table = document.getElementById('servers-off-search');
      const pagination = document.getElementById('servers-off-pagination');
      const baseUrl = `{{ route('servers-off.index') }}`;
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

          if (select.closest('[id^="editServerOffModal"]')) {
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
        const link = e.target.closest('#servers-off-pagination a');
        if (!link) return;

        e.preventDefault();
        fetchServers(link.href);
      });

      const createForm = document.getElementById('createServerOffForm');
      const cancelCreateBtn = document.getElementById('cancelCreateServerOff');
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
