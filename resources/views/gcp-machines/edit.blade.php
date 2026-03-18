@php($isPoweredOff = $machine->isPoweredOff())

<div class="modal fade" id="editGcpMachineModal{{ $machine->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="bx bx-edit text-primary"></i>Editar máquina GCP
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('gcp-machines.update', $machine) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="page" value="{{ request('page') }}">
                <div class="modal-body text-start">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Propietario (obligatorio)</label>
                            <select name="owner_id" class="form-select" required>
                                @foreach ($owners as $owner)
                                    <option value="{{ $owner->id }}"
                                        {{ $machine->owner_id == $owner->id ? 'selected' : '' }}>
                                        {{ $owner->name }} {{ $owner->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Nombre del proyecto</label>
                            <input type="text" name="project_name" class="form-control"
                                value="{{ $machine->project_name }}">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Entorno</label>
                            <input type="text" name="environment" class="form-control"
                                value="{{ $machine->environment }}">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Estado</label>
                            <select name="state" class="form-select">
                                <option value="poweredOn" {{ !$isPoweredOff ? 'selected' : '' }}>Encendido</option>
                                <option value="poweredOff" {{ $isPoweredOff ? 'selected' : '' }}>Apagado</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Nombre máquina</label>
                            <input type="text" name="machine_name" class="form-control"
                                value="{{ $machine->machine_name }}">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Nombre interno</label>
                            <input type="text" name="machine_internal_name" class="form-control"
                                value="{{ $machine->machine_internal_name }}">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Sistema operativo</label>
                            <input type="text" name="operations_system" class="form-control"
                                value="{{ $machine->operations_system }}">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">IP interna</label>
                            <input type="text" name="internal_ip" class="form-control"
                                value="{{ $machine->internal_ip }}">
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label">Alias IP</label>
                            <input type="text" name="alias_ip" class="form-control"
                                value="{{ $machine->alias_ip }}">
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label">Alias 2 IP</label>
                            <input type="text" name="alias2_ip" class="form-control"
                                value="{{ $machine->alias2_ip }}">
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label">Alias 3 IP</label>
                            <input type="text" name="alias3_ip" class="form-control"
                                value="{{ $machine->alias3_ip }}">
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="form-label">Kernel</label>
                            <input type="text" name="kernel_version" class="form-control"
                                value="{{ $machine->kernel_version }}">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Ultimo parche</label>
                            <input type="date" name="latest_security_patch" class="form-control"
                                value="{{ $machine->latest_security_patch }}">
                        </div>
                        <div class="col-md-3 mb-4">
                            <label class="form-label">RAM</label>
                            <input type="number" name="ram_memory" class="form-control"
                                value="{{ $machine->ram_memory }}">
                        </div>
                        <div class="col-md-3 mb-4">
                            <label class="form-label">Swap</label>
                            <input type="number" name="swap_memory" class="form-control"
                                value="{{ $machine->swap_memory }}">
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="form-label">Otras IPs</label>
                            <textarea name="other_ips" class="form-control" rows="2">{{ $machine->other_ips }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
