<div class="modal fade" id="showGcpMachineModal{{ $machine->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2">
                <i class="bx bx-cloud text-primary fs-4"></i>
                Detalle de máquina GCP
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
                <div class="modal-body text-start">
                    <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold"><i class="bx bx-fingerprint me-1 text-primary"></i>UUID</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ filled($machine->uuid) ? $machine->uuid : 'N/A' }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold"><i class="bx bx-folder me-1 text-primary"></i>Proyecto</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ filled($machine->project_name) ? $machine->project_name : 'N/A' }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold"><i class="bx bx-user me-1 text-primary"></i>Propietario</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ filled($machine->display_owner_full_name) ? $machine->display_owner_full_name : 'N/A' }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold"><i class="bx bx-layer me-1 text-primary"></i>Tipo de aplicacion</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ filled($machine->display_application_type) ? $machine->display_application_type : 'N/A' }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold"><i class="bx bx-grid-alt me-1 text-primary"></i>Aplicacion</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ filled($machine->display_application_name) ? $machine->display_application_name : 'N/A' }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold"><i class="bx bx-data me-1 text-primary"></i>Base de datos</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ filled($machine->display_database_name) ? $machine->display_database_name : 'N/A' }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold"><i class="bx bx-check-circle me-1 text-primary"></i>Estado</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $machine->display_state_label }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold"><i class="bx bx-desktop me-1 text-primary"></i>Nombre máquina</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ filled($machine->machine_name) ? $machine->machine_name : 'N/A' }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold"><i class="bx bx-chip me-1 text-primary"></i>Nombre interno</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ filled($machine->machine_internal_name) ? $machine->machine_internal_name : 'N/A' }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold"><i class="bx bx-cog me-1 text-primary"></i>Sistema operativo</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ filled($machine->operations_system) ? $machine->operations_system : 'N/A' }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold"><i class="bx bx-data me-1 text-primary"></i>Versión kernel</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ filled($machine->kernel_version) ? $machine->kernel_version : 'N/A' }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold"><i class="bx bx-globe me-1 text-primary"></i>Entorno</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ filled($machine->environment) ? $machine->environment : 'N/A' }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold"><i class="bx bx-network-chart me-1 text-primary"></i>IP interna</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ filled($machine->internal_ip) ? $machine->internal_ip : 'N/A' }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold"><i class="bx bx-link me-1 text-primary"></i>Alias IP</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ filled($machine->alias_ip) ? $machine->alias_ip : 'N/A' }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold"><i class="bx bx-link-alt me-1 text-primary"></i>Alias 2 IP</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ filled($machine->alias2_ip) ? $machine->alias2_ip : 'N/A' }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold"><i class="bx bx-link-alt me-1 text-primary"></i>Alias 3 IP</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ filled($machine->alias3_ip) ? $machine->alias3_ip : 'N/A' }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold"><i class="bx bx-memory-card me-1 text-primary"></i>RAM (MB)</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $machine->ram_memory ?? 0 }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold"><i class="bx bx-transfer me-1 text-primary"></i>Swap (MB)</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $machine->swap_memory ?? 0 }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-12 mb-4">
                        <label class="form-label fw-semibold"><i class="bx bx-shield-quarter me-1 text-primary"></i>Último parche de seguridad</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ filled($machine->latest_security_patch) ? $machine->latest_security_patch : 'N/A' }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-12 mb-4">
                        <label class="form-label fw-semibold"><i class="bx bx-list-ul me-1 text-primary"></i>Otras IPs</label>
                        <div class="input-group">
                            <textarea class="form-control" rows="2" disabled>{{ filled($machine->other_ips) ? $machine->other_ips : 'N/A' }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-semibold"><i class="bx bx-user-check me-1 text-primary"></i>Creado por</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $machine->creator->name ?? 'N/A' }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold"><i class="bx bx-calendar-plus me-1 text-primary"></i>Fecha de creacion</label>
                        <input type="text" class="form-control" value="{{ $machine->created_at ? $machine->created_at->format('d/m/Y h:i A') : '—' }}" disabled>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bx bx-x-circle me-1"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>
