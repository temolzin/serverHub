@extends('layouts/contentNavbarLayout')

@section('title', 'Roles')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <h5 class="mb-0">Roles</h5>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <button class="btn btn-success text-center" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                            <i class="bx bx-plus me-1"></i> Agregar Rol
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <table class="table align-middle w-100 datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th class="text-end no-export">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $role->id }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <button type="button" class="dropdown-item btn-modal" data-url="{{ route('roles.modal', ['role' => $role->id, 'type' => 'edit']) }}">
                                                        <i class="bx bx-edit-alt me-1"></i> Editar
                                                    </button>
                                                    @if(!$role->users->count())
                                                        <button type="button" class="dropdown-item text-danger btn-modal" data-url="{{ route('roles.modal', ['role' => $role->id, 'type' => 'delete']) }}">
                                                            <i class="bx bx-trash me-1"></i> Eliminar
                                                        </button>
                                                    @else
                                                        <span class="dropdown-item text-secondary"><i class="bx bx-lock-alt me-1"></i> En uso</span>
                                                    @endif
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
    @include('roles.create')

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
                confirmButtonText: 'Perfecto'
            });
        @endif

        @if (session('swal_error'))
            Swal.fire({
                icon: 'error',
                title: 'No se puede eliminar',
                text: '{{ session('swal_error') }}',
                confirmButtonText: 'Entendido'
            });
        @endif
    });
</script>
@endpush
