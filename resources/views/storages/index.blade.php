@extends('layouts/contentNavbarLayout')

@section('title', 'Almacenamiento')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <h5 class="mb-0">Almacenamiento</h5>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <button class="btn btn-success text-center" data-bs-toggle="modal" data-bs-target="#createStorageModal"><i class="bx bx-plus me-1"></i> Agregar almacenamiento</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-nowrap">
                        <table class="table align-middle w-100 datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre del servidor</th>
                                    <th>IP interna</th>
                                    <th>Entorno</th>
                                    <th>Centro de datos</th>
                                    <th class="text-end no-export">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($storages as $storage)
                                    <tr>
                                        <td>{{ $storage->id }}</td>
                                        <td>{{ $storage->hostname }}</td>
                                        <td>{{ $storage->internal_ip ?? '—' }}</td>
                                        <td>{{ $storage->environment ?? '—' }}</td>
                                        <td>{{ $storage->datacenter ?? '—' }}</td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <button type="button" class="dropdown-item btn-modal" data-url="{{ route('storages.modal', ['storage' => $storage->id, 'type' => 'show']) }}"><i class="bx bx-show me-1"></i> Ver</button>
                                                    <button type="button" class="dropdown-item btn-modal" data-url="{{ route('storages.modal', ['storage' => $storage->id, 'type' => 'edit']) }}"><i class="bx bx-edit-alt me-1"></i> Editar</button>
                                                    <button type="button" class="dropdown-item text-danger btn-modal" data-url="{{ route('storages.modal', ['storage' => $storage->id, 'type' => 'delete']) }}"><i class="bx bx-trash me-1"></i> Eliminar</button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('storages.create')

    <div id="modalContainer"></div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.body.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-modal');
                if (!btn) return;
                
                e.preventDefault();
                const url = btn.dataset.url;
                
                fetch(url)
                    .then(r => {
                        if (!r.ok) throw new Error('Error loading modal');
                        return r.text();
                    })
                    .then(html => {
                        document.getElementById('modalContainer').innerHTML = html;
                        const modalEl = document.getElementById('modalContainer').querySelector('.modal');
                        if (modalEl) {
                            $(modalEl).modal('show');
                            
                            modalEl.addEventListener('hidden.bs.modal', function() {
                                modalEl.remove();
                            });
                        }
                    })
                    .catch(err => console.error(err));
            });

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Listo!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'Perfecto',
                    timer: 5000,
                    timerProgressBar: true
                });
            @endif
            @if ($errors->any())
                let modal = new bootstrap.Modal(document.getElementById('createStorageModal'));
                modal.show();
            @endif
        });
    </script>
@endpush
