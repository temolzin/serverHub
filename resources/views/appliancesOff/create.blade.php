<div class="modal fade" id="createApplianceOffModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-chip text-primary me-2"></i>Crear apliance apagado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createApplianceOffForm" action="{{ route('appliances-off.store') }}" method="POST" onsubmit="this.querySelector('button[type=submit]').disabled=true;">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bx-user me-1 text-primary"></i>Propietario (obligatorio)</label>
                            <select name="owner_id" class="form-select appliance-off-searchable-select" required>
                                <option value="">Seleccionar propietario</option>
                                @foreach ($owners as $owner)
                                    <option value="{{ $owner->id }}">{{ $owner->name }} {{ $owner->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bx-data me-1 text-primary"></i>Base de datos</label>
                            <select name="database_id" class="form-select appliance-off-searchable-select">
                                <option value="">Selecciona una base de datos</option>
                                @foreach($databases as $database)
                                    <option value="{{ $database->id }}">{{ $database->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bx-layer me-1 text-primary"></i>Tipo de Aplicación (obligatorio)</label>
                            <select name="type_application_id" class="form-select appliance-off-searchable-select" required>
                                <option value="">Seleccionar aplicación</option>
                                @foreach ($typeApplications as $type)
                                    <option value="{{ $type->id }}">{{ $type->name_application }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bx-grid-alt me-1 text-primary"></i>Aplicaciones</label>
                            <select name="application_ids[]" class="form-select appliance-off-searchable-select" multiple>
                                @foreach ($applications as $application)
                                    <option value="{{ $application->id }}">{{ $application->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label d-block text-start"><i class="bx bx-check-circle me-1 text-primary"></i>Estado (obligatorio)</label>
                            <select name="state" class="form-select" required>
                                <option value="poweredOn">Encendido</option>
                                <option value="poweredOff" selected>Apagado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="bx bx-desktop me-1 text-primary"></i>VM (VMware) (obligatorio)</label>
                            <input type="text" name="vm_according_to_the_vmware" class="form-control" placeholder="Ej: apliance-01" maxlength="50" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bx-network-chart me-1 text-primary"></i>IP primaria</label>
                            <input type="text" name="primary_ip_address" class="form-control" placeholder="Ej: 192.168.1.10" pattern="(\d{1,3}\.){3}\d{1,3}|[Nn]/[Aa]">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bx-globe me-1 text-primary"></i>DNS</label>
                            <input type="text" name="dns_name" class="form-control" placeholder="Ej: apliance.empresa.com" maxlength="50">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bx-user me-1 text-primary"></i>IP usuario</label>
                            <input type="text" name="ip_user" class="form-control" pattern="(\d{1,3}\.){3}\d{1,3}|[Nn]/[Aa]">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bx-radar me-1 text-primary"></i>IP monitoreo</label>
                            <input type="text" name="ip_monitoring" class="form-control" pattern="(\d{1,3}\.){3}\d{1,3}|[Nn]/[Aa]">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bx-cube me-1 text-primary"></i>Entorno (obligatorio)</label>
                            <input type="text" name="environment" class="form-control" placeholder="Ej: Producción, QA" maxlength="50" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bx-server me-1 text-primary"></i>Datacenter (obligatorio)</label>
                            <input type="text" name="datacenter" class="form-control" placeholder="Ej: Tultitlan" maxlength="50" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bx-laptop me-1 text-primary"></i>Sistema operativo (obligatorio)</label>
                            <input type="text" name="os_according_to_the_vmware" class="form-control" placeholder="Ej: CentOS 7" maxlength="50" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bx-code-block me-1 text-primary"></i>Versión interna (obligatorio)</label>
                            <input type="text" name="os_version_internal" class="form-control" placeholder="Ej: 7.9" maxlength="50" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label"><i class="bx bx-network-chart me-1 text-primary"></i>Hostname interno (obligatorio)</label>
                            <input type="text" name="hostname_internal" class="form-control" placeholder="Ej: apl-prod-01" maxlength="50" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bx-memory-card me-1 text-primary"></i>RAM (GB) (obligatorio)</label>
                            <input type="number" name="ram_memory" class="form-control" placeholder="Ej: 16" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bx-transfer me-1 text-primary"></i>Swap (GB) (obligatorio)</label>
                            <input type="number" name="swap_memory" class="form-control" placeholder="Ej: 4" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label"><i class="bx bx-shield-quarter me-1 text-primary"></i>Último parche</label>
                            <input type="date" name="latest_security_patch" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label"><i class="bx bx-calendar-star me-1 text-primary"></i>Fecha de creación de servidor</label>
                            <input type="text" name="creation_date" class="form-control" placeholder="Ej: 2024-10-15 / Octubre 2024">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label"><i class="bx bx-network-chart me-1 text-primary"></i>Otras IPs</label>
                            <textarea name="other_ips" rows="2" class="form-control" placeholder="IPs separadas por coma o N/A"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label"><i class="bx bx-comment-detail me-1 text-primary"></i>Comentarios</label>
                            <textarea name="comments" rows="3" class="form-control" placeholder="Información adicional"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="cancelCreateApplianceOff" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
