@extends('layouts/contentNavbarLayout')

@section('title', 'Propietarios')

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
        <h5 class="mb-0">Propietarios</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createOwnerModal">
                <i class="bx bx-plus me-1"></i> Agregar propietario
                </button>
                <a href="{{ route('export', 'owners') }}" class="btn btn-primary">
                Exportar Excel
                </a>
            </div>
        </div>
        <div class="card-body">
          <div class="mb-4">
            <input type="text" id="search-owner" class="form-control form-control-sm w-50"
              placeholder="Buscar por nombre, email o teléfono">
          </div>
          <div class="table-responsive text-nowrap" style="overflow-y: hidden;">
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
            @if ($owners->hasPages())
              <nav aria-label="Page navigation">
                <ul class="pagination justify-content-end">
                  <li class="page-item {{ $owners->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $owners->previousPageUrl() }}">
                      <i class="bx bx-chevron-left"></i>
                    </a>
                  </li>
                  @for ($page = 1; $page <= $owners->lastPage(); $page++)
                    <li class="page-item {{ $page == $owners->currentPage() ? 'active' : '' }}">
                      <a class="page-link" href="{{ $owners->url($page) }}">
                        {{ $page }}
                      </a>
                    </li>
                  @endfor
                  <li class="page-item {{ $owners->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $owners->nextPageUrl() }}">
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
    document.addEventListener('blur', function(e) {
      if (!e.target.classList.contains('email-check')) return;
      const input = e.target;
      const email = input.value.trim();
      const exclude = input.dataset.exclude || '';
      const errorTargetId = input.dataset.errorTarget;
      const errorDiv = document.getElementById(errorTargetId);

      if (!email) {
        errorDiv.classList.add('d-none');
        input.setCustomValidity('');
        return;
      }

      fetch(`/owners/check-email?email=${encodeURIComponent(email)}&exclude=${exclude}`)
        .then(res => res.json())
        .then(data => {
          const message = data.exists ?
            'Ya existe un propietario con ese correo.' :
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
