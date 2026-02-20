@extends('layouts/contentNavbarLayout')

@section('title', 'Instances')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Instancias</h5>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createInstanceModal">
            <i class="bx bx-plus me-1"></i> Agregar Instancia
          </button>
        </div>
        <div class="card-body">
          <div class="mb-4">
            <input type="text" id="search-instance" class="form-control form-control-sm w-50"
              placeholder="Buscar por versión o servidor">
          </div>
          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Servidor</th>
                  <th>Memoria</th>
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
@endsection
