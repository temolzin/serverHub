@php($isPoweredOff = $machine->isPoweredOff())

<div class="modal fade" id="editGcpOffMachineModal{{ $machine->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="bx bx-edit text-primary"></i>Editar maquina GCP apagada
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
                            <label class="form-label"><i class="bx bx-user me-1 text-primary"></i>Propietario</label>
                            <select name="owner_id" class="form-select gcp-off-searchable-select">
                                <option value="" {{ is_null($machine->owner_id) ? 'selected' : '' }}>N/A</option>
                                @foreach ($owners as $owner)
                                    <option value="{{ $owner->id }}" {{ $machine->owner_id == $owner->id ? 'selected' : '' }}>
                                        {{ $owner->name }} {{ $owner->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-folder me-1 text-primary"></i>Nombre del proyecto</label>
                            <input type="text" name="project_name" class="form-control" placeholder="Ej: proyecto-finanzas-dr" value="{{ $machine->project_name }}" maxlength="100" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-layer me-1 text-primary"></i>Tipo de aplicacion</label>
                            <select name="type_application_id" class="form-select gcp-off-searchable-select">
                                <option value="">Selecciona un tipo de aplicacion</option>
                                @foreach ($typeApplications as $typeApplication)
                                    <option value="{{ $typeApplication->id }}" {{ (int)$machine->type_application_id === (int)$typeApplication->id ? 'selected' : '' }}>
                                        {{ $typeApplication->name_application }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-grid-alt me-1 text-primary"></i>Aplicacion</label>
                            <select name="application_id" class="form-select gcp-off-searchable-select">
                                <option value="">Selecciona una aplicacion</option>
                                @foreach ($applications as $application)
                                    <option value="{{ $application->id }}" {{ (int)$machine->application_id === (int)$application->id ? 'selected' : '' }}>
                                        {{ $application->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-data me-1 text-primary"></i>Base de datos</label>
                            <select name="database_id" class="form-select gcp-off-searchable-select">
                                <option value="">Selecciona una base de datos</option>
                                @foreach ($databases as $database)
                                    <option value="{{ $database->id }}" {{ (int)$machine->database_id === (int)$database->id ? 'selected' : '' }}>
                                        {{ $database->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-check-circle me-1 text-primary"></i>Estado (obligatorio)</label>
                            <select name="state" class="form-select" required>
                                <option value="poweredOn" {{ !$isPoweredOff ? 'selected' : '' }}>Encendido</option>
                                <option value="poweredOff" {{ $isPoweredOff ? 'selected' : '' }}>Apagado</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-globe me-1 text-primary"></i>Entorno</label>
                            <input type="text" name="environment" class="form-control" placeholder="Ej: Produccion, QA, Desarrollo" maxlength="100" value="{{ $machine->environment }}" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-desktop me-1 text-primary"></i>Nombre máquina</label>
                            <input type="text" name="machine_name" class="form-control" placeholder="Ej: vm-gcp-off-01" maxlength="100" value="{{ $machine->machine_name }}" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-chip me-1 text-primary"></i>Nombre interno</label>
                            <input type="text" name="machine_internal_name" class="form-control" placeholder="Ej: gcp-int-off-01" maxlength="100" value="{{ $machine->machine_internal_name }}" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-cog me-1 text-primary"></i>Sistema operativo</label>
                            <input type="text" name="operations_system" class="form-control" placeholder="Ej: Ubuntu 22.04 LTS" maxlength="100" value="{{ $machine->operations_system }}" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-network-chart me-1 text-primary"></i>IP interna</label>
                            <input type="text" name="internal_ip" class="form-control" placeholder="Ej: 10.0.0.15" pattern="(\d{1,3}\.){3}\d{1,3}|N/A|n/a" value="{{ $machine->internal_ip }}" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-link me-1 text-primary"></i>Alias IP</label>
                            <input type="text" name="alias_ip" class="form-control" pattern="(\d{1,3}\.){3}\d{1,3}|N/A|n/a" value="{{ $machine->alias_ip }}">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-link-alt me-1 text-primary"></i>Alias 2 IP</label>
                            <input type="text" name="alias2_ip" class="form-control" pattern="(\d{1,3}\.){3}\d{1,3}|N/A|n/a" value="{{ $machine->alias2_ip }}">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-link-alt me-1 text-primary"></i>Alias 3 IP</label>
                            <input type="text" name="alias3_ip" class="form-control" pattern="(\d{1,3}\.){3}\d{1,3}|N/A|n/a" value="{{ $machine->alias3_ip }}">
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="form-label"><i class="bx bx-data me-1 text-primary"></i>Kernel</label>
                            <input type="text" name="kernel_version" class="form-control" placeholder="Ej: 5.15.0-91-generic" maxlength="100" value="{{ $machine->kernel_version }}">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-shield-quarter me-1 text-primary"></i>Ultimo parche</label>
                            <input type="date" name="latest_security_patch" class="form-control" value="{{ $machine->latest_security_patch }}">
                        </div>
                        <div class="col-md-3 mb-4">
                            <label class="form-label"><i class="bx bx-memory-card me-1 text-primary"></i>RAM (MB)</label>
                            <input type="number" name="ram_memory" class="form-control" placeholder="Ej: 8192" min="256" max="1048576" value="{{ $machine->ram_memory }}" required>
                            <small class="text-muted">Rango permitido: 256 MB - 1048576 MB</small>
                        </div>
                        <div class="col-md-3 mb-4">
                            <label class="form-label"><i class="bx bx-transfer me-1 text-primary"></i>Swap (MB)</label>
                            <input type="number" name="swap_memory" class="form-control" placeholder="Ej: 2048" min="0" max="1048576" value="{{ $machine->swap_memory }}" required>
                            <small class="text-muted">Rango permitido: 0 MB - 1048576 MB</small>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="form-label"><i class="bx bx-list-ul me-1 text-primary"></i>Otras IPs</label>
                            <textarea name="other_ips" rows="2" class="form-control" placeholder="IPs adicionales separadas por coma">{{ $machine->other_ips }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i>Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
