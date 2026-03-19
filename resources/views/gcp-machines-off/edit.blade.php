@php($isPoweredOff = $machine->isPoweredOff())
<div class="modal fade" id="editGcpOffMachineModal{{ $machine->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2">
                <i class="bx bx-edit text-primary"></i>
                Editar maquina GCP apagada
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('gcp-machines-off.update', $machine) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="page" value="{{ request('page') }}">
                <div class="modal-body text-start">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                        <label class="form-label"><i class="bx bx-user me-1 text-primary"></i>Propietario (obligatorio)</label>
                            <div class="input-group">
                                <select name="owner_id" class="form-select gcp-off-searchable-select" data-placeholder="Buscar propietario..." required>
                                    @foreach ($owners as $owner)
                                        <option value="{{ $owner->id }}" {{ $machine->owner_id == $owner->id ? 'selected' : '' }}>
                                        {{ $owner->name }} {{ $owner->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-folder me-1 text-primary"></i>Nombre del proyecto (obligatorio)</label>
                            <div class="input-group">
                                <input type="text" name="project_name" class="form-control" placeholder="Ej: gcp-prod-finanzas"
                                value="{{ filled($machine->project_name) ? $machine->project_name : 'N/A' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-layer me-1 text-primary"></i>Tipo de aplicacion</label>
                            <div class="input-group">
                                <select name="type_application_id" class="form-select gcp-off-searchable-select"
                                    data-placeholder="Buscar tipo de aplicacion...">
                                    <option value="">Selecciona un tipo de aplicacion</option>
                                    @foreach ($typeApplications as $typeApplication)
                                        <option value="{{ $typeApplication->id }}" {{ (int) $machine->type_application_id === (int) $typeApplication->id ? 'selected' : '' }}>
                                            {{ $typeApplication->name_application }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-grid-alt me-1 text-primary"></i>Aplicacion</label>
                            <div class="input-group">
                                <select name="application_id" class="form-select gcp-off-searchable-select"
                                    data-placeholder="Buscar aplicacion...">
                                    <option value="">Selecciona una aplicacion</option>
                                    @foreach ($applications as $application)
                                        <option value="{{ $application->id }}" {{ (int) $machine->application_id === (int) $application->id ? 'selected' : '' }}>
                                            {{ $application->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-data me-1 text-primary"></i>Base de datos</label>
                            <div class="input-group">
                                <select name="database_id" class="form-select gcp-off-searchable-select"
                                    data-placeholder="Buscar base de datos...">
                                    <option value="">Selecciona una base de datos</option>
                                    @foreach ($databases as $database)
                                        <option value="{{ $database->id }}" {{ (int) $machine->database_id === (int) $database->id ? 'selected' : '' }}>
                                            {{ $database->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-check-circle me-1 text-primary"></i>Estado (obligatorio)</label>
                            <select name="state" class="form-select" required>
                                <option value="poweredOn" {{ !$isPoweredOff ? 'selected' : '' }}>Encendido</option>
                                <option value="poweredOff" {{ $isPoweredOff ? 'selected' : '' }}>Apagado</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-globe me-1 text-primary"></i>Entorno (obligatorio)</label>
                            <div class="input-group">
                                <input type="text" name="environment" class="form-control" placeholder="Ej: Produccion, QA, Desarrollo"
                                value="{{ filled($machine->environment) ? $machine->environment : 'N/A' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-desktop me-1 text-primary"></i>Nombre máquina (obligatorio)</label>
                            <div class="input-group">
                                <input type="text" name="machine_name" class="form-control" placeholder="Ej: vm-app-prod-01"
                                value="{{ filled($machine->machine_name) ? $machine->machine_name : 'N/A' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-chip me-1 text-primary"></i>Nombre interno (obligatorio)</label>
                            <div class="input-group">
                                <input type="text" name="machine_internal_name" class="form-control" placeholder="Ej: srv-app-01"
                                value="{{ filled($machine->machine_internal_name) ? $machine->machine_internal_name : 'N/A' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-cog me-1 text-primary"></i>Sistema operativo (obligatorio)</label>
                            <div class="input-group">
                                <input type="text" name="operations_system" class="form-control" placeholder="Ej: Ubuntu 22.04 LTS"
                                value="{{ filled($machine->operations_system) ? $machine->operations_system : 'N/A' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                        <label class="form-label"><i class="bx bx-network-chart me-1 text-primary"></i>IP interna (obligatorio)</label>
                        <div class="input-group">
                            <input type="text" name="internal_ip" class="form-control" placeholder="Ej: 10.10.10.15"
                            value="{{ filled($machine->internal_ip) ? $machine->internal_ip : 'N/A' }}" required>
                        </div>
                        </div>
                        <div class="col-md-6 mb-4">
                        <label class="form-label"><i class="bx bx-link me-1 text-primary"></i>Alias IP</label>
                        <div class="input-group">
                            <input type="text" name="alias_ip" class="form-control" placeholder="Ej: api.empresa.com"
                            value="{{ filled($machine->alias_ip) ? $machine->alias_ip : 'N/A' }}">
                        </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-link-alt me-1 text-primary"></i>Alias 2 IP</label>
                            <div class="input-group">
                                <input type="text" name="alias2_ip" class="form-control" placeholder="Ej: backend.empresa.com"
                                value="{{ filled($machine->alias2_ip) ? $machine->alias2_ip : 'N/A' }}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-link-alt me-1 text-primary"></i>Alias 3 IP</label>
                            <div class="input-group">
                                <input type="text" name="alias3_ip" class="form-control" placeholder="Ej: admin.empresa.com"
                                value="{{ filled($machine->alias3_ip) ? $machine->alias3_ip : 'N/A' }}">
                            </div>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="form-label"><i class="bx bx-data me-1 text-primary"></i>Kernel</label>
                            <div class="input-group">
                                <input type="text" name="kernel_version" class="form-control" placeholder="Ej: 5.15.0-94-generic"
                                value="{{ filled($machine->kernel_version) ? $machine->kernel_version : 'N/A' }}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-shield-quarter me-1 text-primary"></i>Ultimo parche</label>
                            <div class="input-group">
                                <input type="date" name="latest_security_patch" class="form-control"
                                value="{{ $machine->latest_security_patch }}">
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <label class="form-label"><i class="bx bx-memory-card me-1 text-primary"></i>RAM (MB) (obligatorio)</label>
                            <div class="input-group">
                                <input type="number" name="ram_memory" class="form-control" placeholder="Ej: 4096"
                                value="{{ $machine->ram_memory ?? 0 }}" required>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <label class="form-label"><i class="bx bx-transfer me-1 text-primary"></i>Swap (MB) (obligatorio)</label>
                            <div class="input-group">
                                <input type="number" name="swap_memory" class="form-control" placeholder="Ej: 2048"
                                value="{{ $machine->swap_memory ?? 0 }}" required>
                            </div>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="form-label"><i class="bx bx-list-ul me-1 text-primary"></i>Otras IPs</label>
                            <div class="input-group">
                                <textarea name="other_ips" class="form-control" rows="2" placeholder="Ej: 10.10.10.20, 10.10.10.21">{{ filled($machine->other_ips) ? $machine->other_ips : 'N/A' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i>
                    Actualizar
                </button>
                </div>
            </form>
        </div>
    </div>
</div>
