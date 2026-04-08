<div class="modal fade" id="createApplianceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-chip text-primary me-2"></i>Crear apliance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createApplianceForm" action="{{ route('appliances.store') }}" method="POST" onsubmit="this.querySelector('button[type=submit]').disabled=true;">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bx-user me-1 text-primary"></i>Propietario (obligatorio)</label>
                            <select name="owner_id" class="form-select appliance-searchable-select" required>
                                <option value="">Seleccionar propietario</option>
                                @foreach ($owners as $owner)
                                    <option value="{{ $owner->id }}">{{ $owner->name }} {{ $owner->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bx bx-layer me-1 text-primary"></i>Tipo de Aplicación (obligatorio)
                            </label>
                            <select name="type_application_id" class="form-select appliance-searchable-select" required>
                                <option value="">Seleccionar aplicación</option>
                                @foreach ($typeApplications as $type)
                                    <option value="{{ $type->id }}">{{ $type->name_application }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bx-check-circle me-1 text-primary"></i>Estado (obligatorio)</label>
                            <select name="state" class="form-select" required>
                                <option value="poweredOn">Encendido</option>
                                <option value="poweredOff">Apagado</option>
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
                            <label class="form-label fw-semibold"><i class="bx bx-globe me-1 text-primary"></i>DNS</label>
                            <input type="text" name="dns_name" class="form-control" placeholder="Ej: apliance.empresa.com" maxlength="50">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bx-server me-1 text-primary"></i>Datacenter (obligatorio)</label>
                            <input type="text" name="datacenter" class="form-control" placeholder="Ej: Tultitlan" maxlength="50" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bx-laptop me-1 text-primary"></i>Sistema operativo (obligatorio)</label>
                            <input type="text" name="os_according_to_the_vmware" class="form-control" placeholder="Ej: CentOS 7" maxlength="50" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label"><i class="bx bx-shield-quarter me-1 text-primary"></i>Último parche</label>
                            <input type="date" name="latest_security_patch" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label"><i class="bx bx-comment-detail me-1 text-primary"></i>Comentarios</label>
                            <textarea name="comments" rows="3" class="form-control" placeholder="Información adicional"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="cancelCreateAppliance" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
