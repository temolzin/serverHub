<div class="modal fade" id="createServerOffModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2"><i class="bx bx-server text-primary"></i>Crear servidor apagado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createServerOffForm" action="{{ route('servers-off.store') }}" method="POST"
                onsubmit="this.querySelector('button[type=submit]').disabled=true;">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label d-block text-start"><i class="bx bx-user me-1 text-primary"></i>Propietario (obligatorio)</label>
                            <select name="owner_id" class="form-select server-searchable-select" data-placeholder="Buscar propietario..." required>
                                <option value="">Seleccionar propietario</option>
                                    @foreach ($owners as $owner)
                                    <option value="{{ $owner->id }}">
                                        {{ $owner->name }} {{ $owner->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label d-block text-start"><i class="bx bx-data me-1 text-primary"></i>Base de datos</label>
                            <select name="database_id" class="form-select server-searchable-select" data-placeholder="Buscar base de datos...">
                                <option value="">Selecciona una base de datos</option>
                                @foreach ($databases as $database)
                                    <option value="{{ $database->id }}" @selected(old('database_id') == $database->id)>
                                        {{ $database->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label d-block text-start"><i class="bx bx-layer me-1 text-primary"></i> Tipo de Aplicación (obligatorio)</label>
                            <select name="type_application_id" class="form-select server-searchable-select" data-placeholder="Buscar aplicacion..." required>
                                <option value="">Seleccionar el Tipo de aplicación</option>
                                @foreach ($typeApplications as $type)
                                    <option value="{{ $type->id }}">
                                        {{ $type->name_application }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label d-block text-start"><i class="bx bx-grid-alt me-1 text-primary"></i>Aplicacion</label>
                            <select name="application_ids[]" class="form-select server-searchable-select" data-placeholder="Buscar aplicaciones..." multiple>
                                @foreach ($applications as $application)
                                    <option value="{{ $application->id }}" @selected(collect(old('application_ids', []))->contains($application->id))>
                                        {{ $application->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label d-block text-start"><i class="bx bx-server me-1 text-primary"></i>VM (VMware) (obligatorio)</label>
                            <input type="text" name="vm_according_to_the_vmware" class="form-control" placeholder="Ej: vm-app-prod-01" maxlength="100" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label d-block text-start"><i class="bx bx-check-circle me-1 text-primary"></i>Estado (obligatorio)</label>
                            <select name="state" class="form-select" required>
                                <option value="poweredOn">Encendido</option>
                                <option value="poweredOff" selected>Apagado</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label d-block text-start"><i class="bx bx-globe me-1 text-primary"></i>DNS</label>
                            <input type="text" name="dns_name" class="form-control" placeholder="Ej: servidor.empresa.com" maxlength="50">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label d-block text-start"><i class="bx bx-network-chart me-1 text-primary"></i>IP primaria (opcional)</label>
                            <input type="text" name="primary_ip_address" class="form-control ip-check" placeholder="Ej: 192.168.1.10" pattern="(\d{1,3}\.){3}\d{1,3}|[Nn]/[Aa]">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label d-block text-start"><i class="bx bx-user me-1 text-primary"></i>IP usuario</label>
                            <input type="text" name="ip_user" class="form-control" pattern="(\d{1,3}\.){3}\d{1,3}|[Nn]/[Aa]">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label d-block text-start"><i class="bx bx-radar me-1 text-primary"></i>IP monitoreo</label>
                            <input type="text" name="ip_monitoring" class="form-control" pattern="(\d{1,3}\.){3}\d{1,3}|[Nn]/[Aa]">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label d-block text-start"><i class="bx bx-code-alt me-1 text-primary"></i>Entorno (obligatorio)</label>
                            <input type="text" name="environment" class="form-control" placeholder="Ej: Producción, QA" maxlength="50" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label d-block text-start"><i class="bx bx-building me-1 text-primary"></i>Datacenter (obligatorio)</label>
                            <input type="text" name="datacenter" class="form-control" placeholder="Ej: DC-MX-01" maxlength="50" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label d-block text-start"><i class="bx bx-chip me-1 text-primary"></i>Sistema operativo (obligatorio)</label>
                            <input type="text" name="os_according_to_the_vmware" class="form-control" placeholder="Ej: Windows Server 2019" maxlength="50" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label d-block text-start"><i class="bx bx-code me-1 text-primary"></i>Versión interna (obligatorio)</label>
                            <input type="text" name="os_version_internal" class="form-control" placeholder="Ej: 10.0.17763" maxlength="50" required>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="form-label d-block text-start"><i class="bx bx-desktop me-1 text-primary"></i>Hostname interno (obligatorio)</label>
                            <input type="text" name="hostname_internal" class="form-control" placeholder="Ej: srv-prod-01" maxlength="50" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label d-block text-start"><i class="bx bx-memory-card me-1 text-primary"></i>RAM (GB) (obligatorio)</label>
                            <input type="number" name="ram_memory" class="form-control" placeholder="Ej: 8" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label d-block text-start"><i class="bx bx-data me-1 text-primary"></i>Swap (GB) (obligatorio)</label>
                            <input type="number" name="swap_memory" class="form-control" placeholder="Ej: 4" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label d-block text-start"><i class="bx bx-calendar me-1 text-primary"></i>Último parche</label>
                            <input type="date" name="latest_security_patch" class="form-control">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label d-block text-start"><i class="bx bx-calendar-star me-1 text-primary"></i>Fecha de creación de servidor</label>
                            <input type="text" name="creation_date" class="form-control" placeholder="Ej: 2024-10-15 / Octubre 2024">
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="form-label d-block text-start"><i class="bx bx-list-ul me-1 text-primary"></i>Otras IPs</label>
                            <textarea name="other_ips" rows="2" class="form-control" placeholder="IPs adicionales separadas por coma"></textarea>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="form-label d-block text-start"><i class="bx bx-comment-detail me-1 text-primary"></i>Comentarios</label>
                            <textarea name="comments" rows="3" class="form-control" placeholder="Información adicional relevante"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="cancelCreateServerOff" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
