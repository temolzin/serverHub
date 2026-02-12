@extends('layouts/contentNavbarLayout')

@section('title', 'Servidores')

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
@endif

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Servidores</h5>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createServerModal">
            <i class="bx bx-plus me-1"></i> Agregar servidor
          </button>
        </div>
        <div class="card-body">
          <div class="mb-4">
            <input type="text" id="search-server" class="form-control form-control-sm w-50"
              placeholder="Buscar por IP, DNS, Hostname o Propietario">
          </div>
          <div class="table-responsive text-nowrap">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Propietario</th>
                  <th>Aplicación</th>
                  <th>VM (VMware)</th>
                  <th>Estado</th>
                  <th>DNS</th>
                  <th>IP primaria</th>
                  <th>Entorno</th>
                  <th>Datacenter</th>
                  <th>Sistema operativo</th>
                  <th>Versión interna</th>
                  <th>Hostname interno</th>
                  <th>IP usuario</th>
                  <th>IP monitoreo</th>
                  <th>Otras IPs</th>
                  <th>RAM (MB)</th>
                  <th>Swap (MB)</th>
                  <th>Último parche</th>
                  <th>Comentarios</th>
                  <th class="text-end">Acciones</th>
                </tr>
              </thead>
              <tbody id="servers-search">
                @include('servers.search', ['servers' => $servers])
              </tbody>
            </table>
            <div id="servers-pagination">
            </div>
            @if ($servers->hasPages())
              <nav>
                <ul class="pagination justify-content-end">
                  <li class="page-item {{ $servers->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $servers->previousPageUrl() }}">
                      <i class="bx bx-chevron-left"></i>
                    </a>
                  </li>
                  @for ($page = 1; $page <= $servers->lastPage(); $page++)
                    <li class="page-item {{ $page == $servers->currentPage() ? 'active' : '' }}">
                      <a class="page-link" href="{{ $servers->url($page) }}">
                        {{ $page }}
                      </a>
                    </li>
                  @endfor
                  <li class="page-item {{ $servers->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $servers->nextPageUrl() }}">
                      <i class="bx bx-chevron-right"></i>
                    </a>
                  </li>
                </ul>
              </nav>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  @include('servers.create')
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {

      const input = document.getElementById('search-server');
      const table = document.getElementById('servers-search');
      const paginationContainer = document.getElementById('servers-pagination');
      let timeout = null;

      function fetchServers(url) {
        fetch(url, {
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }
          })
          .then(res => res.json())
          .then(data => {
            table.innerHTML = data.table;
            paginationContainer.innerHTML = data.pagination ?? '';
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
        let url = link.href;
        const value = input.value.trim();

        if (value !== '') {
          const separator = url.includes('?') ? '&' : '?';
          url += separator + 'search=' + encodeURIComponent(value);
        }

        fetchServers(url);
      });
    });
  </script>
@endpush
