@extends('layouts/contentNavbarLayout')

@section('title', 'Servers')

@if (session('success'))
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({
        icon: 'success',
        title: '¡Listo!',
        text: '{{ session('success') }}',
        confirmButtonText: 'Perfecto',
        timer: 5000,
        timerProgressBar: true
      })
    })
  </script>
  @if ($errors->any())
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('createServerModal'));
        myModal.show();
      });
    </script>
  @endif
@endif

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Servidores</h5>
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
              placeholder="Buscar por aplicación, hostname o IP">
          </div>
          <div class="table-responsive text-nowrap">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Aplicación</th>
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
            <div id="servers-pagination">
              @include('servers.pagination', ['servers' => $servers])
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @include('servers.create')
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const input = document.getElementById('search-server');
      const table = document.getElementById('servers-search');
      const pagination = document.getElementById('servers-pagination');
      let timeout = null;

      function fetchServers(url) {
        fetch(url, {
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }
          })
          .then(res => res.json())
          .then(data => {
            document.getElementById('servers-search').innerHTML = data.table;
            document.getElementById('servers-pagination').innerHTML = data.pagination;
            // 🔥 REACTIVAR DROPDOWNS
            const dropdowns = document.querySelectorAll('.dropdown-toggle');
            dropdowns.forEach(el => {
              new bootstrap.Dropdown(el);
            });
          });
      }
      input.addEventListener('keyup', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
          const value = input.value.trim();
          let url = `{{ route('servers.index') }}`;
          if (value !== '') {
            url += `?search=${encodeURIComponent(value)}`;
          }
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
    document.addEventListener("DOMContentLoaded", function() {
      new TomSelect("#ownerSelect", {
        create: false,
        sortField: {
          field: "text",
          direction: "asc"
        },
        placeholder: "Buscar propietario..."
      });
      new TomSelect("#typeApplicationSelect", {
        create: false,
        sortField: {
          field: "text",
          direction: "asc"
        },
        placeholder: "Buscar aplicación..."
      });
    });
    document.addEventListener('blur', function(e) {
      if (!e.target.classList.contains('ip-check')) return;
      const input = e.target;
      const ip = input.value.trim();
      const exclude = input.dataset.exclude || '';
      const errorTargetId = input.dataset.errorTarget;
      const errorDiv = document.getElementById(errorTargetId);
      if (!ip) {
        errorDiv.classList.add('d-none');
        input.setCustomValidity('');
        return;
      }
      fetch(`/servers/check-ip?ip=${encodeURIComponent(ip)}&exclude=${exclude}`)
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
