@extends('layouts/contentNavbarLayout')

@section('title', 'Propietarios')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <h5 class="mb-0">Propietarios</h5>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <button class="btn btn-success text-center" data-bs-toggle="modal" data-bs-target="#createOwnerModal"><i class="bx bx-plus me-1"></i> Agregar propietario</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-nowrap">
                        <table class="table align-middle w-100 datatable">
                            <thead class="table-light">
                                <tr>
                                <th>ID</th>
                                <th>Propietario</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th class="text-end no-export">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($owners as $owner)
                                    <tr>
                                        <td>{{ $owner->id }}</td>
                                        <td>{{ $owner->name }} {{ $owner->last_name }}</td>
                                        <td>{{ $owner->email }}</td>
                                        <td>{{ $owner->number_phone ?? '—' }}</td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <button type="button" class="dropdown-item btn-modal" data-url="{{ route('owners.modal', ['owner' => $owner->id, 'type' => 'show']) }}">
                                                        <i class="bx bx-show me-1"></i> Ver
                                                    </button>
                                                    <button type="button" class="dropdown-item btn-modal" data-url="{{ route('owners.modal', ['owner' => $owner->id, 'type' => 'edit']) }}">
                                                        <i class="bx bx-edit-alt me-1"></i> Editar
                                                    </button>
                                                    @if(!$owner->is_in_use)
                                                        <button type="button" class="dropdown-item text-danger btn-modal" data-url="{{ route('owners.modal', ['owner' => $owner->id, 'type' => 'delete']) }}">
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
    @include('owners.create')

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
                        const message = data.exists
                            ? 'Ya existe un propietario con ese correo.'
                            : '';
                        errorDiv.textContent = message;
                        errorDiv.classList.toggle('d-none', !data.exists);
                        input.setCustomValidity(message);
                    })
                    .catch(() => {
                        errorDiv.classList.add('d-none');
                        input.setCustomValidity('');
                    });
            }, true);
        });
    </script>
@endpush
