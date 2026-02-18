@extends('layouts/contentNavbarLayout')

@section('title', 'Instancias')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Instancias</h5>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createInstanceModal">
            <i class="bx bx-plus me-1"></i> Agregar instancia
          </button>
        </div>
        <div class="card-body">
          <div class="mb-4">
            <input type="text" id="search-instance" class="form-control form-control-sm w-50"
              placeholder="Buscar por versión, edición o servidor">
          </div>
          <div class="table-responsive text-nowrap">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Servidor</th>
                  <th>Memoria (MB)</th>
                  <th>Versión</th>
                  <th>Edición</th>
                  <th class="text-end">Acciones</th>
                </tr>
              </thead>
              <tbody id="instances-search">
                @include('instances.search', ['instances' => $instances, 'servers' => $servers])
              </tbody>
            </table>
            <div id="instances-pagination">
              @include('instances.pagination', ['instances' => $instances])
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @include('instances.create')
  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('search-instance');
        let timeout = null;

        function fetchInstances(url) {
          fetch(url, {
              headers: {
                'X-Requested-With': 'XMLHttpRequest'
              }
            })
            .then(res => res.json())
            .then(data => {
              document.getElementById('instances-search').innerHTML = data.table;
              document.getElementById('instances-pagination').innerHTML = data.pagination;
            });
        }
        input.addEventListener('keyup', function() {
          clearTimeout(timeout);
          timeout = setTimeout(() => {
            let url = `{{ route('instances.index') }}`;
            if (input.value.trim() !== '') {
              url += `?search=${encodeURIComponent(input.value)}`;
            }
            fetchInstances(url);
          }, 300);
        });
        document.addEventListener('click', function(e) {
          const link = e.target.closest('#instances-pagination a');
          if (!link) return;
          e.preventDefault();
          fetchInstances(link.href);
        });
      });
    </script>
  @endpush
@endsection
