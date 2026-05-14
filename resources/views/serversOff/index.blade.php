@extends('layouts/contentNavbarLayout')

@section('title', 'Servidores apagados')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <h5 class="mb-0">Servidores Apagados</h5>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <button class="btn btn-primary text-center" data-bs-toggle="modal" data-bs-target="#createServerOffModal"><i class="bx bx-plus me-1"></i> Agregar servidor apagado</button>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <table class="table align-middle w-100 datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>VM</th>
                                    <th>IP USUARIO</th>
                                    <th>IP MONITOREO</th>
                                    <th>ENTORNO</th>
                                    <th>DATACENTER</th>
                                    <th>HOSTNAME</th>
                                    <th>OTRAS IPS</th>
                                    <th class="text-end no-export">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($servers as $server)
                                    <tr>
                                        <td>{{ $server->id }}</td>
                                        <td>{{ filled($server->vm_according_to_the_vmware) ? $server->vm_according_to_the_vmware : 'N/A' }}</td>
                                        <td>{{ filled($server->ip_user) ? $server->ip_user : 'N/A' }}</td>
                                        <td>{{ filled($server->ip_monitoring) ? $server->ip_monitoring : 'N/A' }}</td>
                                        <td>{{ strtoupper(filled($server->environment) ? $server->environment : 'N/A') }}</td>
                                        <td>{{ filled($server->datacenter) ? $server->datacenter : 'N/A' }}</td>
                                        <td>{{ filled($server->hostname_internal) ? $server->hostname_internal : 'N/A' }}</td>
                                        <td>{{ filled($server->other_ips) ? $server->other_ips : 'N/A' }}</td>
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#showServerOffModal{{ $server->id }}"><i class="bx bx-show me-1"></i> Ver</button>
                                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editServerOffModal{{ $server->id }}"><i class="bx bx-edit-alt me-1"></i> Editar</button>
                                                    <form action="{{ route('servers.power-on', $server) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-success"><i class="bx bx-power-off me-1"></i> Encender</button>
                                                    </form>
                                                    <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteServerOffModal{{ $server->id }}"><i class="bx bx-trash me-1"></i> Eliminar</button>
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

    @foreach ($servers as $server)
        @include('serversOff.show', ['server' => $server])
        @include('serversOff.edit', ['server' => $server, 'databases' => $databases, 'applications' => $applications])
        @include('serversOff.delete', ['server' => $server])
    @endforeach

    @include('serversOff.create')
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Listo!',
                    text: @json(session('success')),
                    confirmButtonText: 'Perfecto',
                    timer: 5000,
                    timerProgressBar: true
                });
            @endif

            @if ($errors->any())
                const createModalEl = document.getElementById('createServerOffModal');
                if (createModalEl) new bootstrap.Modal(createModalEl).show();
            @endif

            function initSearchableSelects(scope = document) {
                if (typeof TomSelect === 'undefined') return;
                scope.querySelectorAll('.server-searchable-select').forEach(select => {
                    if (select.tomselect) return;
                    const tom = new TomSelect(select, {
                        create: false,
                        sortField: { field: 'text', direction: 'asc' },
                        placeholder: select.dataset.placeholder || 'Buscar...'
                    });
                    if (select.closest('[id^="editServerOffModal"]')) {
                        const alignLeft = () => {
                            tom.control.style.textAlign = 'left';
                            tom.control_input.style.textAlign = 'left';
                            tom.dropdown.style.textAlign = 'left';
                            tom.dropdown_content.style.textAlign = 'left';
                            tom.dropdown.querySelectorAll('.option, .optgroup-header').forEach(el => { el.style.textAlign = 'left'; });
                        };
                        alignLeft();
                        tom.on('dropdown_open', alignLeft);
                        tom.on('type', alignLeft);
                    }
                });
            }

            const createForm = document.getElementById('createServerOffForm');
            const cancelCreateBtn = document.getElementById('cancelCreateServerOff');
            if (createForm && cancelCreateBtn) {
                cancelCreateBtn.addEventListener('click', function() {
                    createForm.reset();
                    createForm.querySelectorAll('input, textarea, select').forEach(el => {
                        el.classList.remove('is-invalid');
                        el.setCustomValidity('');
                        if (el.tomselect) {
                            const defaultOption = el.querySelector('option[selected]');
                            el.tomselect.setValue(defaultOption ? defaultOption.value : '', true);
                        }
                    });
                    createForm.querySelectorAll('.text-danger, .invalid-feedback').forEach(el => {
                        el.classList.add('d-none');
                        el.textContent = '';
                    });
                    const submitBtn = createForm.querySelector('button[type="submit"]');
                    if (submitBtn) submitBtn.disabled = false;
                });
            }

            initSearchableSelects(document);
        });
    </script>
@endpush
