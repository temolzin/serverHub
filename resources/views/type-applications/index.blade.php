@extends('layouts/contentNavbarLayout')

@section('title', 'Tipo de aplicaciones')

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
          <h5 class="mb-0">Tipo de aplicaciones</h5>
          <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createTypeApplicationModal">
            <i class="bx bx-plus me-1"></i> Agregar tipo de aplicación
          </button>
        </div>
        <div class="card-body">
          <div class="mb-4">
            <input type="text" id="search-type-application" class="form-control form-control-sm w-50"
              placeholder="Buscar por tipo o nombre">
          </div>
          <div class="table-responsive text-nowrap" style="overflow-y: hidden;">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Tipo</th>
                  <th>Nombre</th>
                  <th class="text-end">Acciones</th>
                </tr>
              </thead>
              <tbody id="type-applications-search">
                @include('type-applications.search', ['typeApplications' => $typeApplications])
              </tbody>
            </table>
            @if ($typeApplications->hasPages())
              <nav aria-label="Page navigation">
                <ul class="pagination justify-content-end" id="type-applications-pagination">
                  <li class="page-item {{ $typeApplications->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $typeApplications->previousPageUrl() }}">
                      <i class="bx bx-chevron-left"></i>
                    </a>
                  </li>
                  @for ($page = 1; $page <= $typeApplications->lastPage(); $page++)
                    <li class="page-item {{ $page == $typeApplications->currentPage() ? 'active' : '' }}">
                      <a class="page-link" href="{{ $typeApplications->url($page) }}">
                        {{ $page }}
                      </a>
                    </li>
                  @endfor
                  <li class="page-item {{ $typeApplications->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link" href="{{ $typeApplications->nextPageUrl() }}">
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
  @include('type-applications.create')
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const input = document.getElementById('search-type-application');
      const table = document.getElementById('type-applications-search');
      let paginationContainer = document.getElementById('type-applications-pagination-container');
      let timeout = null;

      function fetchTypeApplications(url) {
        fetch(url, {
            headers: {
              'X-Requested-With': 'XMLHttpRequest'
            }
          })
          .then(res => res.json())
          .then(data => {
            table.innerHTML = data.table;
            if (paginationContainer) {
              paginationContainer.innerHTML = data.pagination;
            }
          });
      }
      input.addEventListener('keyup', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
          const value = input.value.trim();
          let url = `{{ route('type-applications.index') }}`;
          if (value !== '') {
            url += `?search=${encodeURIComponent(value)}`;
          }
          fetchTypeApplications(url);
        }, 300);
      });
      paginationContainer.addEventListener('click', function(e) {
        const link = e.target.closest('a.page-link');
        if (link) {
          e.preventDefault();
          fetchTypeApplications(link.href);
        }
      });
    });
  </script>
@endpush
