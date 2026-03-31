<div class="modal fade" id="createApplicationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <form id="createApplicationForm" method="POST" action="{{ route('applications.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bx bx-layer-plus text-primary me-2"></i>Crear aplicación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bx bx-server me-1 text-primary"></i>Servidor
                            </label>
                            <select name="server_id" id="serverSelect" class="form-select" required oninvalid="this.setCustomValidity('Selecciona un servidor')" oninput="this.setCustomValidity('')">
                                <option value="" disabled selected>Selecciona un servidor</option>
                                @foreach ($servers as $server)
                                    <option value="{{ $server->id }}">{{ $server->hostname_internal }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bx bx-user me-1 text-primary"></i>Propietario
                            </label>
                            <select name="owner_id" id="ownerSelect" class="form-select" required oninvalid="this.setCustomValidity('Selecciona un propietario')" oninput="this.setCustomValidity('')">
                                <option value="" disabled selected>Seleccionar propietario</option>
                                @foreach ($owners as $owner)
                                    <option value="{{ $owner->id }}">{{ $owner->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bx bx-cloud me-1 text-primary"></i>Maquina GCP
                            </label>
                            <select name="gcp_machine_id" id="gcpMachineSelect" class="form-select" required oninvalid="this.setCustomValidity('Selecciona una máquina')" oninput="this.setCustomValidity('')">
                                <option value="" disabled selected>Seleccionar maquina</option>
                                @foreach ($gcpMachines as $machine)
                                    <option value="{{ $machine->id }}">{{ $machine->machine_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bx bx-layer me-1 text-primary"></i>Nombre
                            </label>
                            <input type="text" name="name" class="form-control" maxlength="20" required placeholder="Ej: Sistema de Nómina" oninvalid="this.setCustomValidity('Este campo es obligatorio')" oninput="this.setCustomValidity('')">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bx bx-code me-1 text-primary"></i>Versión
                            </label>
                            <input type="text" name="version" class="form-control" maxlength="20" required placeholder="Ej: 2.3.1" oninvalid="this.setCustomValidity('Este campo es obligatorio')" oninput="this.setCustomValidity('')">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bx bx-pulse me-1 text-primary"></i>Status
                            </label>
                            <select name="status" class="form-select" required oninvalid="this.setCustomValidity('Selecciona un estado')" oninput="this.setCustomValidity('')">
                                <option value="" disabled selected>Selecciona un estado</option>
                                <option value="production">Producción</option>
                                <option value="staging">Staging</option>
                                <option value="development">Development</option>
                                <option value="inactive">Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bx bx-category me-1 text-primary"></i>Tipo
                            </label>
                            <input type="text" name="type" class="form-control" maxlength="20" required placeholder="Ej: Web, API" oninvalid="this.setCustomValidity('Este campo es obligatorio')" oninput="this.setCustomValidity('')">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bx bx-chip me-1 text-primary"></i>Memoria asignada (MB)
                            </label>
                            <input type="number" name="assigned_memory" class="form-control" min="1024" max="32768" required placeholder="Ej: 2048" oninvalid="this.setCustomValidity('Ingresa un valor válido (mínimo 1024 MB)')" oninput="this.setCustomValidity('')">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bx bx-folder me-1 text-primary"></i>Ruta de instalación
                            </label>
                            <input type="text" name="installation_route" class="form-control" maxlength="100" required placeholder="Ej: C:\inetpub\app" oninvalid="this.setCustomValidity('Este campo es obligatorio')" oninput="this.setCustomValidity('')">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="bx bx-user-check me-1 text-primary"></i>Usuario servicio
                            </label>
                            <input type="text" name="user_service" class="form-control" maxlength="20" required placeholder="Ej: svc_app_prod" oninvalid="this.setCustomValidity('Este campo es obligatorio')" oninput="this.setCustomValidity('')">
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="form-label">
                                <i class="bx bx-calendar me-1 text-primary"></i>Último parche
                            </label>
                            <input type="date" name="latest_security_patch" class="form-control" required oninvalid="this.setCustomValidity('Selecciona una fecha')" oninput="this.setCustomValidity('')">
                        </div>
                        <div class="col-12 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-cog me-1 text-primary"></i>Procesos
                            </label>
                            <textarea name="processes" class="form-control" rows="2" placeholder="Ej: app.exe, worker.js, java -jar app.jar" required oninvalid="this.setCustomValidity('Este campo es obligatorio')" oninput="this.setCustomValidity('')"></textarea>
                        </div>
                        <div class="col-12 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-time-five me-1 text-primary"></i>Tareas programadas
                            </label>
                            <textarea name="cron_jobs" class="form-control" rows="2" placeholder="Ej: 0 2 * * * /usr/bin/php artisan schedule:run" required oninvalid="this.setCustomValidity('Este campo es obligatorio')" oninput="this.setCustomValidity('')"></textarea>
                        </div>
                        <div class="col-12 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-message-square-detail me-1 text-primary"></i>Comentarios
                            </label>
                            <textarea name="comments" class="form-control" rows="3" placeholder="Información adicional relevante de la aplicación" required oninvalid="this.setCustomValidity('Este campo es obligatorio')" oninput="this.setCustomValidity('')"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
