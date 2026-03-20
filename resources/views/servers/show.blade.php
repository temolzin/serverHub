<div class="modal fade" id="showServerModal{{ $server->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2"><i class="bx bx-server text-primary fs-4"></i>Detalle del servidor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold d-block text-start"><i class="bx bx-fingerprint me-1 text-primary"></i>UUID</label>
                        <input type="text" class="form-control" value="{{ filled($server->uuid) ? $server->uuid : 'N/A' }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3"><label class="form-label fw-semibold d-block text-start"><i class="bx bx-user me-1 text-primary"></i>Propietario</label>
                        <input type="text" class="form-control" value="{{ filled($server->display_owner_full_name) ? $server->display_owner_full_name : 'N/A' }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold d-block text-start"><i class="bx bx-layer me-1 text-primary"></i>Tipo de Aplicación</label>
                        <input type="text" class="form-control" value="{{ filled($server->display_application_name) ? $server->display_application_name : 'N/A' }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold d-block text-start"><i class="bx bx-data me-1 text-primary"></i>Aplicaciones asociadas</label>
                        <input type="text" class="form-control" value="{{ filled($server->display_application_names) ? $server->display_application_names : 'N/A' }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold d-block text-start"><i class="bx bx-data me-1 text-primary"></i>Base de datos</label>
                        <input type="text" class="form-control" value="{{ filled($server->display_database_name) ? $server->display_database_name : 'N/A' }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold d-block text-start"><i class="bx bx-server me-1 text-primary"></i>VM (VMware)</label>
                        <input type="text" class="form-control" value="{{ filled($server->vm_according_to_the_vmware) ? $server->vm_according_to_the_vmware : 'N/A' }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold d-block text-start"><i class="bx bx-check-circle me-1 text-primary"></i>Estado</label>
                        <input type="text" class="form-control" value="{{ $server->display_state_label }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold d-block text-start"><i class="bx bx-globe me-1 text-primary"></i>DNS</label>
                        <input type="text" class="form-control" value="{{ filled($server->dns_name) ? $server->dns_name : 'N/A' }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold d-block text-start"><i class="bx bx-network-chart me-1 text-primary"></i>IP primaria</label>
                        <input type="text" class="form-control" value="{{ filled($server->primary_ip_address) ? $server->primary_ip_address : 'N/A' }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3"><label class="form-label fw-semibold d-block text-start"><i class="bx bx-code-alt me-1 text-primary"></i>Entorno</label>
                        <input type="text" class="form-control" value="{{ filled($server->environment) ? $server->environment : 'N/A' }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold d-block text-start"><i class="bx bx-building me-1 text-primary"></i>Datacenter</label>
                        <input type="text" class="form-control" value="{{ filled($server->datacenter) ? $server->datacenter : 'N/A' }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold d-block text-start"><i class="bx bx-chip me-1 text-primary"></i>Sistema operativo</label>
                        <input type="text" class="form-control" value="{{ filled($server->os_according_to_the_vmware) ? $server->os_according_to_the_vmware : 'N/A' }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold d-block text-start"><i class="bx bx-code me-1 text-primary"></i>Versión interna</label>
                        <input type="text" class="form-control" value="{{ filled($server->os_version_internal) ? $server->os_version_internal : 'N/A' }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold d-block text-start"><i class="bx bx-desktop me-1 text-primary"></i>Hostname interno</label>
                        <input type="text" class="form-control" value="{{ filled($server->hostname_internal) ? $server->hostname_internal : 'N/A' }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold d-block text-start"><i class="bx bx-user-pin me-1 text-primary"></i>IP usuario</label>
                        <input type="text" class="form-control" value="{{ filled($server->ip_user) ? $server->ip_user : 'N/A' }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold d-block text-start"><i class="bx bx-line-chart me-1 text-primary"></i>IP monitoreo</label>
                        <input type="text" class="form-control" value="{{ filled($server->ip_monitoring) ? $server->ip_monitoring : 'N/A' }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold d-block text-start"><i class="bx bx-memory-card me-1 text-primary"></i>RAM (MB)</label>
                        <input type="text" class="form-control" value="{{ $server->ram_memory ?? 0 }} MB" disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold d-block text-start"><i class="bx bx-data me-1 text-primary"></i>Swap (MB)</label>
                        <input type="text" class="form-control" value="{{ $server->swap_memory ?? 0 }} MB" disabled>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold d-block text-start"><i class="bx bx-calendar me-1 text-primary"></i>Último parche</label>
                        <input type="text" class="form-control" value="{{ filled($server->latest_security_patch) ? $server->latest_security_patch : 'N/A' }}" disabled>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-semibold d-block text-start"><i class="bx bx-list-ul me-1 text-primary"></i>Otras IPs</label>
                        <textarea class="form-control" rows="2" disabled>{{ filled($server->other_ips) ? $server->other_ips : 'N/A' }}</textarea>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-semibold d-block text-start"><i class="bx bx-comment-detail me-1 text-primary"></i>Comentarios</label>
                        <textarea class="form-control" rows="3" disabled>{{ filled($server->comments) ? $server->comments : 'N/A' }}</textarea>
                    </div>
                    <div class="col-md-6 text-start">
                        <label class="form-label d-block text-start fw-semibold"> <i class="bx bx-user-check me-1 text-primary"></i>Creado por </label>
                        <input type="text" class="form-control" value="{{ $server->creator->name ?? 'N/A' }}" disabled>
                    </div>
                    <div class="col-md-6 mb-3 text-start">
                        <label class="form-label fw-semibold">
                            <i class="bx bx-calendar-plus me-1 text-primary"></i>Fecha de creación
                        </label>
                        <input type="text" class="form-control" value="{{ $server->created_at ? $server->created_at->format('d/m/Y h:i A') : 'N/A' }}" disabled>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bx bx-x-circle me-1"></i> Cerrar</button>
            </div>
        </div>
    </div>
</div>
