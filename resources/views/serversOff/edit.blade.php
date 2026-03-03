@php($isPoweredOff = $server->isPoweredOff())
<div class="modal fade" id="editServerOffModal{{ $server->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center gap-2">
          <i class="bx bx-edit text-primary"></i>
          Editar servidor apagado
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('servers-off.update', $server) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="page" value="{{ request('page') }}">
        <div class="modal-body text-start">
          <div class="row">
            <div class="col-md-6 mb-4">
              <label class="form-label d-block text-start">
                <i class="bx bx-user me-1 text-primary"></i>
                Propietario (obligatorio)
              </label>
              <select name="owner_id" class="form-select server-searchable-select"
                data-placeholder="Buscar propietario..." required>
                @foreach ($owners as $owner)
                  <option value="{{ $owner->id }}" {{ $server->owner_id == $owner->id ? 'selected' : '' }}>
                    {{ $owner->name }} {{ $owner->last_name }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label d-block text-start">
                <i class="bx bx-layer me-1 text-primary"></i>
                Aplicacion (obligatorio)
              </label>
              <select name="type_application_id" class="form-select server-searchable-select"
                data-placeholder="Buscar aplicacion..." required>
                @foreach ($typeApplications as $type)
                  <option value="{{ $type->id }}" {{ $server->type_application_id == $type->id ? 'selected' : '' }}>
                    {{ $type->name_application }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label d-block text-start">
                <i class="bx bx-server me-1 text-primary"></i>
                VM (VMware) (obligatorio)
              </label>
              <input type="text" name="vm_according_to_the_vmware" class="form-control"
                placeholder="Ej: vm-app-prod-01"
                value="{{ filled($server->vm_according_to_the_vmware) ? $server->vm_according_to_the_vmware : 'N/A' }}"
                required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label d-block text-start">
                <i class="bx bx-check-circle me-1 text-primary"></i>
                Estado (obligatorio)
              </label>
              <select name="state" class="form-select" required>
                <option value="poweredOn" {{ !$isPoweredOff ? 'selected' : '' }}>poweredOn</option>
                <option value="poweredOff" {{ $isPoweredOff ? 'selected' : '' }}>poweredOff</option>
              </select>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label d-block text-start">
                <i class="bx bx-globe me-1 text-primary"></i>
                DNS
              </label>
              <input type="text" name="dns_name" class="form-control" placeholder="Ej: app.empresa.com"
                value="{{ filled($server->dns_name) ? $server->dns_name : 'N/A' }}">
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label d-block text-start">
                <i class="bx bx-network-chart me-1 text-primary"></i>
                IP primaria (opcional)
              </label>
              <input type="text" name="primary_ip_address" class="form-control ip-check"
                placeholder="Ej: 192.168.1.15"
                value="{{ filled($server->primary_ip_address) ? $server->primary_ip_address : 'N/A' }}"
                data-exclude="{{ $server->id }}">
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label d-block text-start">
                <i class="bx bx-user me-1 text-primary"></i>
                IP usuario
              </label>
              <input type="text" name="ip_user" class="form-control"
                value="{{ filled($server->ip_user) ? $server->ip_user : 'N/A' }}">
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label d-block text-start">
                <i class="bx bx-radar me-1 text-primary"></i>
                IP monitoreo
              </label>
              <input type="text" name="ip_monitoring" class="form-control"
                value="{{ filled($server->ip_monitoring) ? $server->ip_monitoring : 'N/A' }}">
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label d-block text-start">
                <i class="bx bx-code-alt me-1 text-primary"></i>
                Entorno (obligatorio)
              </label>
              <input type="text" name="environment" class="form-control" placeholder="Ej: Produccion"
                value="{{ filled($server->environment) ? $server->environment : 'N/A' }}" required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label d-block text-start">
                <i class="bx bx-building me-1 text-primary"></i>
                Datacenter (obligatorio)
              </label>
              <input type="text" name="datacenter" class="form-control" placeholder="Ej: DC-MX-01"
                value="{{ filled($server->datacenter) ? $server->datacenter : 'N/A' }}" required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label d-block text-start">
                <i class="bx bx-chip me-1 text-primary"></i>
                Sistema operativo (obligatorio)
              </label>
              <input type="text" name="os_according_to_the_vmware" class="form-control"
                placeholder="Ej: Windows Server 2019"
                value="{{ filled($server->os_according_to_the_vmware) ? $server->os_according_to_the_vmware : 'N/A' }}"
                required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label d-block text-start">
                <i class="bx bx-code me-1 text-primary"></i>
                Version interna (obligatorio)
              </label>
              <input type="text" name="os_version_internal" class="form-control" placeholder="Ej: 10.0.17763"
                value="{{ filled($server->os_version_internal) ? $server->os_version_internal : 'N/A' }}" required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label d-block text-start">
                <i class="bx bx-desktop me-1 text-primary"></i>
                Hostname interno (obligatorio)
              </label>
              <input type="text" name="hostname_internal" class="form-control" placeholder="Ej: srv-app-01"
                value="{{ filled($server->hostname_internal) ? $server->hostname_internal : 'N/A' }}" required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label d-block text-start">
                <i class="bx bx-memory-card me-1 text-primary"></i>
                RAM (MB) (obligatorio)
              </label>
              <input type="number" name="ram_memory" class="form-control" placeholder="Ej: 8192"
                value="{{ $server->ram_memory ?? 0 }}" required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label d-block text-start">
                <i class="bx bx-data me-1 text-primary"></i>
                Swap (MB) (obligatorio)
              </label>
              <input type="number" name="swap_memory" class="form-control" placeholder="Ej: 4096"
                value="{{ $server->swap_memory ?? 0 }}" required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label d-block text-start">
                <i class="bx bx-calendar me-1 text-primary"></i>
                Ultimo parche
              </label>
              <input type="date" name="latest_security_patch" class="form-control"
                value="{{ $server->latest_security_patch }}">
            </div>
            <div class="col-md-12 mb-4">
              <label class="form-label d-block text-start">
                <i class="bx bx-list-ul me-1 text-primary"></i>
                Otras IPs
              </label>
              <textarea name="other_ips" rows="2" class="form-control"
                placeholder="Ej: 192.168.1.20, 192.168.1.21">{{ filled($server->other_ips) ? $server->other_ips : 'N/A' }}</textarea>
            </div>
            <div class="col-md-12 mb-4">
              <label class="form-label d-block text-start">
                <i class="bx bx-comment-detail me-1 text-primary"></i>
                Comentarios
              </label>
              <textarea name="comments" rows="3" class="form-control"
                placeholder="Informacion adicional del servidor">{{ filled($server->comments) ? $server->comments : 'N/A' }}</textarea>
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
