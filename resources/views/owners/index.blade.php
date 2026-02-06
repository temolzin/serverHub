@extends('layouts/contentNavbarLayout')

@section('title', 'Owners')

@if (session('success'))
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({
        icon: 'success',
        title: '¡Listo!',
        text: '{{ session('success') }}',
        confirmButtonText: 'Perfecto',
        timer: 2500,
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
          <h5 class="mb-0">Propietarios</h5>
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createOwnerModal">
            <i class="bx bx-plus me-1"></i> Agregar propietario
          </button>
        </div>
        <div class="table-responsive text-nowrap">
          <form method="GET" action="{{ route('owners.index') }}" class="row g-3 mb-4">
            <div class="col-md-4">
              <input type="text" id="search-name" class="form-control form-control-sm" placeholder="Buscar por nombre">
            </div>
            <div class="col-md-4">
              <input type="text" id="search-email" class="form-control form-control-sm" placeholder="Buscar por email">
            </div>
            <div class="col-md-3">
              <input type="text" id="search-phone" class="form-control form-control-sm"
                placeholder="Buscar por teléfono">
            </div>
            <div class="col-md-1 d-flex align-items-end">
              <button type="button" class="btn btn-primary">
                <i class="bx bx-search"></i>
              </button>
            </div>
          </form>
          <table class="table align-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Propietario</th>
                <th>Email</th>
                <th>Telefono</th>
                <th class="text-end">Acciones</th>
              </tr>
            </thead>
            <tbody id="owners-search">
              @forelse ($owners as $owner)
                <tr>
                  <td>{{ $owner->id }}</td>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="avatar avatar-sm me-3">
                        <span class="avatar-initial rounded-circle bg-label-primary">
                          {{ strtoupper(substr($owner->name, 0, 1)) }}
                        </span>
                      </div>
                      <div>
                        <span class="fw-medium">
                          {{ $owner->name }} {{ $owner->last_name }}
                        </span>
                      </div>
                    </div>
                  </td>
                  <td>{{ $owner->email }}</td>
                  <td>{{ $owner->number_phone ?? '—' }}</td>
                  <td class="text-end">
                    <div class="dropdown">
                      <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-vertical-rounded"></i>
                      </button>
                      <div class="dropdown-menu dropdown-menu-end">
                        <a href="javascript:void(0);" class="dropdown-item" data-bs-toggle="modal"
                          data-bs-target="#editOwnerModal{{ $owner->id }}">
                          <i class="bx bx-edit-alt me-1"></i> Editar
                        </a>
                        <a href="javascript:void(0);" class="dropdown-item text-danger" data-bs-toggle="modal"
                          data-bs-target="#deleteOwnerModal{{ $owner->id }}">
                          <i class="bx bx-trash me-1"></i> Borrar
                        </a>
                      </div>
                    </div>
                  </td>
                </tr>
                @include('owners.edit', ['owner' => $owner])
                @include('owners.delete', ['owner' => $owner])
              @empty
                <tr>
                  <td colspan="5" class="text-center text-muted">
                    No se encontraron propietarios
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  @include('owners.create')
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const nameInput = document.getElementById('search-name');
      const emailInput = document.getElementById('search-email');
      const phoneInput = document.getElementById('search-phone');

      function searchOwners() {
        const params = new URLSearchParams({
          name: nameInput.value,
          email: emailInput.value,
          phone: phoneInput.value
        });
        fetch(`{{ route('owners.index') }}?${params.toString()}`, {
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }
          })
          .then(res => res.text())
          .then(html => {
            document.getElementById('owners-search').innerHTML = html;
          });
      }
      [nameInput, emailInput, phoneInput].forEach(input => {
        input.addEventListener('keyup', searchOwners);
      });
    });
  </script>
@endpush
