<div class="modal fade text-start" id="editApplianceModal{{ $server->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2"><i class="bx bx-edit text-primary"></i>Editar apliance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('appliances.update', $server) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="page" value="{{ request('page') }}">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-start w-100"><i class="bx bx-user me-1 text-primary"></i>Propietario</label>
                            <select name="owner_id" class="form-select appliance-searchable-select" required>
                                @foreach ($owners as $owner)
                                    <option value="{{ $owner->id }}" {{ $server->owner_id == $owner->id ? 'selected' : '' }}>
                                        {{ $owner->name }} {{ $owner->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-start w-100"><i class="bx bx-layer me-1 text-primary"></i>Tipo de Aplicación</label>
                            <select name="type_application_id" class="form-select text-start" required>
                                @foreach ($typeApplications as $type)
                                    <option value="{{ $type->id }}" {{ $server->type_application_id == $type->id ? 'selected' : '' }}>{{ $type->name_application }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-start w-100"><i class="bx bx-check-circle me-1 text-primary"></i>Estado</label>
                            <select name="state" class="form-select" required>
                                <option value="poweredOn" {{ !$server->is_powered_off ? 'selected' : '' }}>Encendido</option>
                                <option value="poweredOff" {{ $server->is_powered_off ? 'selected' : '' }}>Apagado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-start w-100"><i class="bx bx-desktop me-1 text-primary"></i>VM (VMware)</label>
                            <input type="text" name="vm_according_to_the_vmware" class="form-control" value="{{ filled($server->vm_according_to_the_vmware) ? $server->vm_according_to_the_vmware : 'N/A' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-start w-100"><i class="bx bx-network-chart me-1 text-primary"></i>IP primaria</label>
                            <input type="text" name="primary_ip_address" class="form-control" value="{{ filled($server->primary_ip_address) ? $server->primary_ip_address : 'N/A' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-start w-100"><i class="bx bx-globe me-1 text-primary"></i>DNS</label>
                            <input type="text" name="dns_name" class="form-control" value="{{ filled($server->dns_name) ? $server->dns_name : 'N/A' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-start w-100"><i class="bx bx-building-house me-1 text-primary"></i>Datacenter</label>
                            <input type="text" name="datacenter" class="form-control" value="{{ filled($server->datacenter) ? $server->datacenter : 'N/A' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-start w-100"><i class="bx bx-desktop me-1 text-primary"></i>Sistema operativo</label>
                            <input type="text" name="os_according_to_the_vmware" class="form-control" value="{{ filled($server->os_according_to_the_vmware) ? $server->os_according_to_the_vmware : 'N/A' }}" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-start w-100"><i class="bx bx-shield-quarter me-1 text-primary"></i>Último parche</label>
                            <input type="date" name="latest_security_patch" class="form-control" value="{{ $server->latest_security_patch }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-start w-100"><i class="bx bx-message-square-detail me-1 text-primary"></i>Comentarios</label>
                            <textarea name="comments" rows="3" class="form-control">{{ filled($server->comments) ? $server->comments : 'N/A' }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
