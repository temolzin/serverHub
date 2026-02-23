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

  {{-- Modal Excel --}}
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

    });

    /* -------- IP CHECK -------- */

    document.addEventListener('blur', function(e) {

      if (!e.target.classList.contains('ip-check')) return;

      const input = e.target;
      const ip = input.value.trim();
      const exclude = input.dataset.exclude || '';
      const errorDiv = document.getElementById(input.dataset.errorTarget);
      const url = `{{ route('servers.check-ip') }}`;

      if (!errorDiv) return;

      if (!ip) {
        errorDiv.classList.add('d-none');
        input.setCustomValidity('');
        return;
      }

      fetch(`${url}?ip=${encodeURIComponent(ip)}&exclude=${exclude}`)
        .then(res => res.json())
        .then(data => {
          const message = data.exists ?
            'Ya existe un servidor con esa IP.' :
            '';

          errorDiv.textContent = message;
          errorDiv.classList.toggle('d-none', !data.exists);
          input.setCustomValidity(message);
        })
        .catch(() => {
          errorDiv.classList.add('d-none');
          input.setCustomValidity('');
        });

    }, true);
  </script>
@endpush
