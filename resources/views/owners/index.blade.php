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
        <div class="card-body">
          <div class="mb-4">
            <input type="text" id="search-owner" class="form-control form-control-sm w-50"
              placeholder="Buscar por nombre, email o teléfono">
          </div>
          <div class="table-responsive text-nowrap">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Propietario</th>
                  <th>Email</th>
                  <th>Teléfono</th>
                  <th class="text-end">Acciones</th>
                </tr>
              </thead>
              <tbody id="owners-search">
                @include('owners.search', ['owners' => $owners])
              </tbody>
            </table>
            <div id="owners-pagination" class="mt-3">
              {{ $owners->links('pagination::bootstrap-5') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  @include('owners.create')
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const input = document.getElementById('search-owner');
      const table = document.getElementById('owners-search');
      const pagination = document.getElementById('owners-pagination');
      let timeout = null;

      function fetchOwners(url) {
        fetch(url, {
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }
          })
          .then(res => res.json())
          .then(data => {
            table.innerHTML = data.table;
            pagination.innerHTML = data.pagination;
          });
      }
      input.addEventListener('keyup', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
          const value = input.value.trim();
          let url = `{{ route('owners.index') }}`;
          if (value !== '') {
            url += `?search=${encodeURIComponent(value)}`;
          }
          fetchOwners(url);
        }, 300);
      });
      document.addEventListener('click', function(e) {
        const link = e.target.closest('#owners-pagination a');
        if (!link) return;
        e.preventDefault();
        fetchOwners(link.href);
      });
    });
  </script>
@endpush
