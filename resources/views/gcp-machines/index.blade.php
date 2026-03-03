@extends('layouts/contentNavbarLayout')

@section('title', 'Maquinas GCP')

@if (session('success'))
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({
        icon: 'success',
        title: '\u00a1Listo!',
        text: '{{ session('success') }}',
        confirmButtonText: 'Perfecto',
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
        <div class="card-header d-flex align-items-center">
        <h5 class="mb-0">Máquinas GCP</h5>
            <div class="ms-auto d-flex gap-2">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createGcpMachineModal">
                <i class="bx bx-plus me-1"></i> Agregar máquina
                </button>
                <a href="{{ route('export', 'gcp-machines') }}" class="btn btn-primary">
                    Exportar Excel
                </a>
            </div>
        </div>
        <div class="card-body">
          <div class="mb-4">
            <input type="text" id="search-gcp" class="form-control form-control-sm w-50"
              placeholder="Buscar por proyecto, maquina, UUID o IP">
          </div>
          <div class="table-responsive text-nowrap" style="overflow-y: hidden;">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Proyecto</th>
                  <th>M&aacute;quina</th>
                  <th>Entorno</th>
                  <th>IP interna</th>
                  <th class="text-end">Acciones</th>
                </tr>
              </thead>
              <tbody id="gcp-search">
                @include('gcp-machines.search', ['gcpMachines' => $gcpMachines])
              </tbody>
            </table>
            <div id="gcp-pagination">
              @include('gcp-machines.pagination', ['gcpMachines' => $gcpMachines])
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @include('gcp-machines.create')
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const input = document.getElementById('search-gcp');
      const table = document.getElementById('gcp-search');
      const pagination = document.getElementById('gcp-pagination');
      let timeout = null;

      function fetchGcp(url) {
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
          let url = `{{ route('gcp-machines.index') }}`;

          if (value !== '') {
            url += `?search=${encodeURIComponent(value)}`;
          }

          fetchGcp(url);
        }, 300);
      });

      document.addEventListener('click', function(e) {
        const link = e.target.closest('#gcp-pagination a');
        if (!link) return;

        e.preventDefault();
        fetchGcp(link.href);
      });
    });
    document.addEventListener("DOMContentLoaded", function() {
      new TomSelect("#ownerSelect", {
        create: false,
        sortField: {
          field: "text",
          direction: "asc"
        },
        placeholder: "Buscar propietario..."
      });
    });

    document.addEventListener('DOMContentLoaded', function() {
      const cancelBtn = document.getElementById('cancelCreateGcp');
      const form = document.getElementById('createGcpForm');
      const modal = document.getElementById('createGcpMachineModal');

      cancelBtn.addEventListener('click', function() {
        form.reset();
        form.querySelectorAll('input, textarea, select').forEach(el => {
          el.classList.remove('is-invalid');
          el.setCustomValidity('');

          if (el.tomselect) {
            const defaultOption = el.querySelector('option[selected]');
            const defaultValue = defaultOption ? defaultOption.value : '';
            el.tomselect.setValue(defaultValue, true);
          }
        });
        form.querySelectorAll('.text-danger').forEach(el => {
          el.classList.add('d-none');
          el.textContent = '';
        });
      });
    });
  </script>
@endpush
