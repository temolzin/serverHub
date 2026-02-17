@extends('layouts/contentNavbarLayout')

@section('title', 'Bases de Datos')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Bases de Datos</h5>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDatabaseModal">
            <i class="bx bx-plus me-1"></i> Agregar base de datos
          </button>
        </div>
        <div class="card-body">

          <div class="mb-4">
            <input type="text" id="search-database" class="form-control form-control-sm w-50"
              placeholder="Buscar por nombre, tipo o servidor">
          </div>

          <div class="table-responsive text-nowrap">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre</th>
                  <th>Tipo</th>
                  <th>Servidor</th>
                  <th>Puerto</th>
                  <th>Versión</th>
                  <th>Estado</th>
                  <th>Última actualización</th>
                  <th class="text-end">Acciones</th>
                </tr>
              </thead>

              <tbody id="databases-search">
                @include('databases.search', ['databases' => $databases, 'servers' => $servers])
              </tbody>
            </table>

            <div id="databases-pagination">
              @include('databases.pagination', ['databases' => $databases])
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('databases.create')
  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('search-database');
        let timeout = null;

        function fetchDatabases(url) {
          fetch(url, {
              headers: {
                'X-Requested-With': 'XMLHttpRequest'
              }
            })
            .then(res => res.json())
            .then(data => {
              document.getElementById('databases-search').innerHTML = data.table;
              document.getElementById('databases-pagination').innerHTML = data.pagination;
            });
        }

        input.addEventListener('keyup', function() {
          clearTimeout(timeout);
          timeout = setTimeout(() => {
            let url = `{{ route('databases.index') }}`;
            if (input.value.trim() !== '') {
              url += `?search=${encodeURIComponent(input.value)}`;
            }
            fetchDatabases(url);
          }, 300);
        });

        document.addEventListener('click', function(e) {
          const link = e.target.closest('#databases-pagination a');
          if (!link) return;
          e.preventDefault();
          fetchDatabases(link.href);
        });
      });
    </script>
  @endpush
@endsection
