<div class="modal fade" id="editApplicationModal{{ $application->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <form method="POST" action="{{ route('applications.update', $application->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bx bx-edit text-primary me-2"></i>Editar aplicación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Servidor</label>
                            <select name="server_id" class="form-select serverSelectEdit" required>
                                @foreach ($servers as $server)
                                    <option value="{{ $server->id }}"
                                        {{ $application->server_id == $server->id ? 'selected' : '' }}>
                                        {{ $server->hostname_internal }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Propietario</label>
                            <select name="owner_id" class="form-select ownerSelectEdit" required>
                                @foreach ($owners as $owner)
                                    <option value="{{ $owner->id }}"
                                        {{ $application->owner_id == $owner->id ? 'selected' : '' }}>
                                        {{ $owner->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Maquina GCP</label>
                            <select name="gcp_machine_id" class="form-select gcpMachineSelectEdit">
                                <option value="">Seleccionar maquina</option>
                                @foreach ($gcpMachines as $machine)
                                    <option value="{{ $machine->id }}"
                                        {{ $application->gcp_machine_id == $machine->id ? 'selected' : '' }}>
                                        {{ $machine->machine_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ $application->name }}"
                                placeholder="Ej: Sistema de Nómina" required>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label">Versión</label>
                            <input type="text" name="version" class="form-control"
                                value="{{ $application->version }}"
                                placeholder="Ej: 2.3.1">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="production" {{ $application->status == 'production' ? 'selected' : '' }}>Producción</option>
                                <option value="staging" {{ $application->status == 'staging' ? 'selected' : '' }}>Staging</option>
                                <option value="development" {{ $application->status == 'development' ? 'selected' : '' }}>Development</option>
                                <option value="inactive" {{ $application->status == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Memoria</label>
                            <input type="number" name="assigned_memory" class="form-control"
                                value="{{ $application->assigned_memory }}"
                                placeholder="Ej: 2048">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Tipo</label>
                            <input type="text" name="type" class="form-control"
                                value="{{ $application->type }}"
                                placeholder="Ej: Web, API">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Ruta</label>
                            <input type="text" name="installation_route" class="form-control"
                                value="{{ $application->installation_route }}"
                                placeholder="Ej: C:\inetpub\app">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Último parche</label>
                            <input type="date" name="latest_security_patch" class="form-control"
                                value="{{ $application->latest_security_patch }}">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Usuario servicio</label>
                            <input type="text" name="user_service" class="form-control"
                                value="{{ $application->user_service }}"
                                placeholder="Ej: svc_app_prod">
                        </div>
                        <div class="col-12 mb-4">
                            <label class="form-label">Procesos</label>
                            <textarea name="processes" class="form-control" rows="2"
                                placeholder="Ej: app.exe, worker.js">{{ $application->processes }}</textarea>
                        </div>
                        <div class="col-12 mb-4">
                            <label class="form-label">Tareas programadas</label>
                            <textarea name="cron_jobs" class="form-control" rows="2"
                                placeholder="Ej: 0 2 * * *">{{ $application->cron_jobs }}</textarea>
                        </div>
                        <div class="col-12 mb-4">
                            <label class="form-label">Comentarios</label>
                            <textarea name="comments" class="form-control" rows="2"
                                placeholder="Notas adicionales">{{ $application->comments }}</textarea>
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
