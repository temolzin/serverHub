<div class="modal fade" id="createGcpOffMachineModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-cloud text-primary me-2"></i>Crear máquina GCP apagada</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createGcpOffForm" action="{{ route('gcp-machines-off.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label"><i class="bx bx-user me-1 text-primary"></i>Propietario (obligatorio)</label>
                            <select name="owner_id" class="form-select gcp-off-searchable-select"
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
                            <label class="form-label"><i class="bx bx-folder me-1 text-primary"></i>Nombre del proyecto (obligatorio)</label>
                            <input type="text" name="project_name" class="form-control" placeholder="Ej: proyecto-finanzas-dr" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-layer me-1 text-primary"></i>Tipo de aplicacion</label>
                            <select name="type_application_id" class="form-select gcp-off-searchable-select"
                                data-placeholder="Buscar tipo de aplicacion...">
                                <option value="" selected>Selecciona un tipo de aplicacion</option>
                                @foreach ($typeApplications as $typeApplication)
                                    <option value="{{ $typeApplication->id }}">
                                        {{ $typeApplication->name_application }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-grid-alt me-1 text-primary"></i>Aplicacion</label>
                            <select name="application_id" class="form-select gcp-off-searchable-select"
                                data-placeholder="Buscar aplicacion...">
                                <option value="" selected>Selecciona una aplicacion</option>
                                @foreach ($applications as $application)
                                    <option value="{{ $application->id }}">
                                        {{ $application->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-data me-1 text-primary"></i>Base de datos</label>
                            <select name="database_id" class="form-select gcp-off-searchable-select"
                                data-placeholder="Buscar base de datos...">
                                <option value="" selected>Selecciona una base de datos</option>
                                @foreach ($databases as $database)
                                    <option value="{{ $database->id }}">
                                        {{ $database->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-check-circle me-1 text-primary"></i>Estado (obligatorio)</label>
                            <select name="state" class="form-select" required>
                                <option value="poweredOn">Encendido</option>
                                <option value="poweredOff" selected>Apagado</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-globe me-1 text-primary"></i>Entorno (obligatorio)</label>
                            <input type="text" name="environment" class="form-control" placeholder="Ej: Produccion, QA, Desarrollo" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-desktop me-1 text-primary"></i>Nombre máquina (obligatorio)</label>
                            <input type="text" name="machine_name" class="form-control" placeholder="Ej: vm-gcp-off-01" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-chip me-1 text-primary"></i>Nombre interno (obligatorio)</label>
                            <input type="text" name="machine_internal_name" class="form-control" placeholder="Ej: gcp-int-off-01" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-cog me-1 text-primary"></i>Sistema operativo (obligatorio)</label>
                            <input type="text" name="operations_system" class="form-control" placeholder="Ej: Ubuntu 22.04 LTS" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-network-chart me-1 text-primary"></i>IP interna (obligatorio)</label>
                            <input type="text" name="internal_ip" class="form-control" placeholder="Ej: 10.0.0.15" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-link me-1 text-primary"></i>Alias IP</label>
                            <input type="text" name="alias_ip" class="form-control" placeholder="Ej: 10.0.1.10">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-link-alt me-1 text-primary"></i>Alias 2 IP</label>
                            <input type="text" name="alias2_ip" class="form-control" placeholder="Ej: 10.0.1.11">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-link-alt me-1 text-primary"></i>Alias 3 IP</label>
                            <input type="text" name="alias3_ip" class="form-control" placeholder="Ej: 10.0.1.12">
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="form-label"><i class="bx bx-data me-1 text-primary"></i>Kernel</label>
                            <input type="text" name="kernel_version" class="form-control" placeholder="Ej: 5.15.0-91-generic">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-shield-quarter me-1 text-primary"></i>Ultimo parche</label>
                            <input type="date" name="latest_security_patch" class="form-control">
                        </div>
                        <div class="col-md-3 mb-4">
                            <label class="form-label"><i class="bx bx-memory-card me-1 text-primary"></i>RAM (MB) (obligatorio)</label>
                            <input type="number" name="ram_memory" class="form-control" placeholder="Ej: 8192" required>
                        </div>
                        <div class="col-md-3 mb-4">
                            <label class="form-label"><i class="bx bx-transfer me-1 text-primary"></i>Swap (MB) (obligatorio)</label>
                            <input type="number" name="swap_memory" class="form-control" placeholder="Ej: 2048" required>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="form-label"><i class="bx bx-list-ul me-1 text-primary"></i>Otras IPs</label>
                            <textarea name="other_ips" rows="2" class="form-control" placeholder="IPs adicionales separadas por coma"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="cancelCreateGcpOff" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
