@extends('layouts/contentNavbarLayout')

@section('title', 'Almacenamiento')
@if (session('success'))
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({
        icon: 'success',
        title: '¡Listo!',
        text: '{{ session('success') }}',
        confirmButtonText: 'OK',
        timer: 5000,
        timerProgressBar: true
      });
    });
  </script>
@endif
@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Almacenamiento</h5>
          <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createStorageModal">
            <i class="bx bx-plus me-1"></i> Agregar almacenamiento
          </button>
        </div>
        <div class="card-body">
          <div class="mb-4">
            <input type="text" id="search-storage" class="form-control form-control-sm w-50"
              placeholder="Buscar por hostname o datacenter">
          </div>
          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre del servidor</th>
                  <th>IP interna</th>
                  <th>Entorno</th>
                  <th>Centro de datos</th>
                  <th class="text-end">Acciones</th>
                </tr>
              </thead>
              <tbody id="storages-search">
                @include('storages.search', ['storages' => $storages])
              </tbody>
            </table>
            <div id="storages-pagination">
              @include('storages.pagination', ['storages' => $storages])
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @include('storages.create')
@endsection
