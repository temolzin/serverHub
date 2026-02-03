@extends('layouts/contentNavbarLayout')

@section('title', 'Type Applications')

@section('content')
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Tipo de aplicaciones</h5>
        </div>
        <div class="table-responsive text-nowrap">
          <table class="table align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Nombre</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($typeApplications as $type)
                <tr>
                  <td>{{ $type->id }}</td>
                  <td>
                    <span class="badge bg-label-primary">
                      {{ $type->type_application }}
                    </span>
                  </td>
                  <td class="fw-medium">
                    {{ $type->name_application }}
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center text-muted">
                    No se encontraron aplicaciones del tipo
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
