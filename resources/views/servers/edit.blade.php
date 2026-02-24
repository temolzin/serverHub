<div class="modal fade" id="editServerModal{{ $server->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center gap-2">
          <i class="bx bx-edit text-primary"></i>
          Editar servidor
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('servers.update', $server) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="page" value="{{ request('page') }}">
        <div class="modal-body text-start">
          <div class="row">
            <div class="col-md-6 mb-4">
              <label class="form-label">Propietario</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="bx bx-user text-primary"></i>
                </span>
                <select name="owner_id" class="form-select" required>
                  @foreach ($owners as $owner)
                    <option value="{{ $owner->id }}" {{ $server->owner_id == $owner->id ? 'selected' : '' }}>
                      {{ $owner->name }} {{ $owner->last_name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Aplicación</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="bx bx-layer text-primary"></i>
                </span>
                <select name="type_application_id" class="form-select" required>
                  @foreach ($typeApplications as $type)
                    <option value="{{ $type->id }}"
                      {{ $server->type_application_id == $type->id ? 'selected' : '' }}>
                      {{ $type->name_application }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">VM (VMware)</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="bx bx-server text-primary"></i>
                </span>
                <input type="text" name="vm_according_to_the_vmware" class="form-control"
                  placeholder="Ej: vm-app-prod-01" value="{{ $server->vm_according_to_the_vmware }}" required>
              </div>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Estado</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="bx bx-check-circle text-primary"></i>
                </span>
                <select name="state" class="form-select">
                  <option value="1" {{ $server->state ? 'selected' : '' }}>poweredOn</option>
                  <option value="0" {{ !$server->state ? 'selected' : '' }}>poweredOff</option>
                </select>
              </div>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">DNS</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="bx bx-globe text-primary"></i>
                </span>
                <input type="text" name="dns_name" class="form-control" placeholder="Ej: app.empresa.com"
                  value="{{ $server->dns_name }}">
              </div>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">IP primaria</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="bx bx-network-chart text-primary"></i>
                </span>
                <input type="text" name="primary_ip_address" class="form-control ip-check"
                  placeholder="Ej: 192.168.1.15" value="{{ $server->primary_ip_address }}"
                  data-exclude="{{ $server->id }}" required>
              </div>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Entorno</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="bx bx-code-alt text-primary"></i>
                </span>
                <input type="text" name="environment" class="form-control" placeholder="Ej: Producción"
                  value="{{ $server->environment }}" required>
              </div>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Datacenter</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="bx bx-building text-primary"></i>
                </span>
                <input type="text" name="datacenter" class="form-control" placeholder="Ej: DC-México-01"
                  value="{{ $server->datacenter }}" required>
              </div>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Sistema operativo</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="bx bx-chip text-primary"></i>
                </span>
                <input type="text" name="os_according_to_the_vmware" class="form-control"
                  placeholder="Ej: Windows Server 2019" value="{{ $server->os_according_to_the_vmware }}" required>
              </div>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Versión interna</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="bx bx-code text-primary"></i>
                </span>
                <input type="text" name="os_version_internal" class="form-control" placeholder="Ej: 10.0.17763"
                  value="{{ $server->os_version_internal }}" required>
              </div>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Hostname interno</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="bx bx-desktop text-primary"></i>
                </span>
                <input type="text" name="hostname_internal" class="form-control" placeholder="Ej: srv-app-01"
                  value="{{ $server->hostname_internal }}" required>
              </div>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">RAM (MB)</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="bx bx-memory-card text-primary"></i>
                </span>
                <input type="number" name="ram_memory" class="form-control" placeholder="Ej: 8192"
                  value="{{ $server->ram_memory }}" required>
              </div>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Swap (MB)</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="bx bx-data text-primary"></i>
                </span>
                <input type="number" name="swap_memory" class="form-control" placeholder="Ej: 4096"
                  value="{{ $server->swap_memory }}" required>
              </div>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Último parche</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="bx bx-calendar text-primary"></i>
                </span>
                <input type="date" name="latest_security_patch" class="form-control"
                  value="{{ $server->latest_security_patch }}">
              </div>
            </div>
            <div class="col-md-12 mb-4">
              <label class="form-label">Otras IPs</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="bx bx-list-ul text-primary"></i>
                </span>
                <textarea name="other_ips" rows="2" class="form-control" placeholder="Ej: 192.168.1.20, 192.168.1.21">{{ $server->other_ips }}</textarea>
              </div>
            </div>
            <div class="col-md-12 mb-4">
              <label class="form-label">Comentarios</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="bx bx-comment text-primary"></i>
                </span>
                <textarea name="comments" rows="3" class="form-control" placeholder="Información adicional del servidor">{{ $server->comments }}</textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
            Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="bx bx-save me-1"></i>
            Actualizar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
