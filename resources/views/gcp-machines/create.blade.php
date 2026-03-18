<div class="modal fade" id="createGcpMachineModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bx bx-cloud text-primary me-2"></i>Crear máquina GCP
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createGcpForm" action="{{ route('gcp-machines.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Propietario (obligatorio)</label>
                            <select name="owner_id"
                                class="form-select gcp-searchable-select"
                                data-placeholder="Buscar propietario..." required>
                                <option value="" disabled selected>Selecciona un propietario</option>
                                @foreach ($owners as $owner)
                                    <option value="{{ $owner->id }}">
                                        {{ $owner->name }} {{ $owner->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Nombre del proyecto (obligatorio)</label>
                            <input type="text" name="project_name" class="form-control"
                                placeholder="Ej: proyecto-finanzas-prod" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Entorno (obligatorio)</label>
                            <input type="text" name="environment" class="form-control"
                                placeholder="Ej: Produccion, QA, Desarrollo" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Estado (obligatorio)</label>
                            <select name="state" class="form-select" required>
                                <option value="poweredOn" selected>Encendido</option>
                                <option value="poweredOff">Apagado</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Nombre máquina (obligatorio)</label>
                            <input type="text" name="machine_name" class="form-control"
                                placeholder="Ej: vm-app-prod-01" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Nombre interno (obligatorio)</label>
                            <input type="text" name="machine_internal_name" class="form-control"
                                placeholder="Ej: app-internal-01" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Sistema operativo (obligatorio)</label>
                            <input type="text" name="operations_system" class="form-control"
                                placeholder="Ej: Ubuntu 22.04 LTS" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">IP interna (obligatorio)</label>
                            <input type="text" name="internal_ip" class="form-control"
                                placeholder="Ej: 10.0.0.15" required>
                            @error('internal_ip')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Alias IP</label>
                            <input type="text" name="alias_ip" class="form-control"
                                placeholder="Ej: 10.0.1.10">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Alias 2 IP</label>
                            <input type="text" name="alias2_ip" class="form-control"
                                placeholder="Ej: 10.0.1.11">
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="form-label">Alias 3 IP</label>
                            <input type="text" name="alias3_ip" class="form-control"
                                placeholder="Ej: 10.0.1.12">
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="form-label">Kernel</label>
                            <input type="text" name="kernel_version" class="form-control"
                                placeholder="Ej: 5.15.0-91-generic">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Ultimo parche</label>
                            <input type="date" name="latest_security_patch" class="form-control">
                        </div>
                        <div class="col-md-3 mb-4">
                            <label class="form-label">RAM (MB) (obligatorio)</label>
                            <input type="number" name="ram_memory" class="form-control"
                                placeholder="Ej: 8192" required>
                        </div>
                        <div class="col-md-3 mb-4">
                            <label class="form-label">Swap (MB) (obligatorio)</label>
                            <input type="number" name="swap_memory" class="form-control"
                                placeholder="Ej: 2048" required>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="form-label">Otras IPs</label>
                            <textarea name="other_ips" rows="2" class="form-control"
                                placeholder="IPs adicionales separadas por coma"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
