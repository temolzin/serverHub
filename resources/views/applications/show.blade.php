<div class="modal fade" id="showApplicationModal{{ $application->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2"><i class="bx bx-layer text-primary fs-4"></i>Detalle de la aplicacion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold d-flex align-items-center gap-2"><i class="bx bx-user text-primary"></i>Propietario</label>
                            <input type="text" class="form-control"value="{{ optional($application->owner)->name }} {{ optional($application->owner)->last_name }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold d-flex align-items-center gap-2"><i class="bx bx-server text-primary"></i>Servidor</label>
                            <input type="text" class="form-control" value="{{ optional($application->server)->hostname_internal ?? '-' }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold d-flex align-items-center gap-2"><i class="bx bx-cloud text-primary"></i>Maquina GCP</label>
                            <input type="text" class="form-control" value="{{ optional($application->gcpMachine)->machine_name ?? '-' }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold d-flex align-items-center gap-2"><i class="bx bx-layer text-primary"></i>Nombre</label>
                            <input type="text" class="form-control" value="{{ $application->name }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold d-flex align-items-center gap-2"><i class="bx bx-code-alt text-primary"></i>Version</label>
                            <input type="text" class="form-control" value="{{ $application->version ?? '-' }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold d-flex align-items-center gap-2"><i class="bx bx-check-circle text-primary"></i>Status</label>
                            <input type="text" class="form-control" value="{{ ucfirst($application->status) }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold d-flex align-items-center gap-2"><i class="bx bx-memory-card text-primary"></i>Memoria asignada (MB)</label>
                            <input type="text" class="form-control" value="{{ $application->assigned_memory ? $application->assigned_memory . ' MB' : '-' }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold d-flex align-items-center gap-2"><i class="bx bx-category text-primary"></i>Tipo</label>
                            <input type="text" class="form-control" value="{{ $application->type ?? '-' }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold d-flex align-items-center gap-2"><i class="bx bx-folder text-primary"></i>Ruta instalacion</label>
                            <input type="text" class="form-control" value="{{ $application->installation_route ?? '-' }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold d-flex align-items-center gap-2"><i class="bx bx-calendar text-primary"></i>Ultimo parche</label>
                            <input type="text" class="form-control" value="{{ $application->latest_security_patch ?? '-' }}" disabled>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold d-flex align-items-center gap-2"><i class="bx bx-id-card text-primary"></i>Usuario servicio</label>
                            <input type="text" class="form-control" value="{{ $application->user_service ?? '-' }}" disabled>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold d-flex align-items-center gap-2"><i class="bx bx-list-ul text-primary"></i>Procesos</label>
                            <textarea class="form-control" rows="2" disabled>{{ $application->processes ?? '-' }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold d-flex align-items-center gap-2"><i class="bx bx-time text-primary"></i>Tareas programadas</label>
                            <textarea class="form-control" rows="2" disabled>{{ $application->cron_jobs ?? '-' }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold d-flex align-items-center gap-2"><i class="bx bx-comment-detail text-primary"></i>Comentarios</label>
                            <textarea class="form-control" rows="3" disabled>{{ $application->comments ?? '-' }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="bx bx-user-check me-1 text-primary"></i> Creado por </label>
                            <input type="text" class="form-control" value="{{ $application->creator->full_name ?? 'N/A' }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold d-flex align-items-center gap-2">
                                <i class="bx bx-calendar-plus text-primary"></i>Fecha de creación
                            </label>
                            <input type="text" class="form-control" value="{{ $application->created_at ? $application->created_at->format('Y-m-d H:i') : '-' }}" disabled>
                        </div>
                    </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bx bx-x-circle me-1"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>
