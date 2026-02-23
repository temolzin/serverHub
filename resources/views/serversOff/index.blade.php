@extends('layouts/contentNavbarLayout')

@section('title', 'Servidores apagados')

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
          <div class="d-flex gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createServerOffModal">
              <i class="bx bx-plus me-1"></i> Agregar servidor apagado
            </button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#uploadExcelOffModal">
              Subir Excel
            </button>
          </div>
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
            <div class="modal fade" id="uploadExcelOffModal" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Importar servidores apagados</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <form action="{{ route('servers-off.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                      <p class="text-muted small mb-3">
                        Hojas validas: <strong>BajaTultitlan</strong>, <strong>TulOff</strong> y <strong>QroOff</strong>.
                      </p>
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
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const input = document.getElementById('search-server-off');
      const table = document.getElementById('servers-off-search');
      const pagination = document.getElementById('servers-off-pagination');
      const searchBaseUrl = `{{ route('servers-off.index') }}`;
      let timeout = null;

      if (!input || !table || !pagination) {
        return;
      }

      function hydrateDropdowns() {
        const dropdowns = document.querySelectorAll('.dropdown-toggle');
        dropdowns.forEach(function(el) {
          new bootstrap.Dropdown(el);
        });
      }

      function fetchServers(url) {
        fetch(url, {
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }
          })
          .then(function(res) {
            return res.json();
          })
          .then(function(data) {
            table.innerHTML = data.table;
            pagination.innerHTML = data.pagination;
            hydrateDropdowns();
          });
      }

      input.addEventListener('keyup', function() {
        clearTimeout(timeout);
        timeout = setTimeout(function() {
          const value = input.value.trim();
          const url = value ? `${searchBaseUrl}?search=${encodeURIComponent(value)}` : searchBaseUrl;
          fetchServers(url);
        }, 300);
      });

      document.addEventListener('click', function(e) {
        const link = e.target.closest('#servers-off-pagination a');
        if (!link) {
          return;
        }

        e.preventDefault();
        fetchServers(link.href);
      });
    });
  </script>
@endpush
