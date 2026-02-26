@php($isPoweredOff = $server->isPoweredOff())

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
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label text-start w-100">
                <i class="bx bx-user me-1 text-primary"></i> Propietario
              </label>
              <select name="owner_id" class="form-select text-start" required>
                @foreach ($owners as $owner)
                  <option value="{{ $owner->id }}"
                    {{ $server->owner_id == $owner->id ? 'selected' : '' }}>
                    {{ $owner->name }} {{ $owner->last_name }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label text-start w-100">
                <i class="bx bx-layer me-1 text-primary"></i> Aplicación
              </label>
              <select name="type_application_id" class="form-select text-start" required>
                @foreach ($typeApplications as $type)
                  <option value="{{ $type->id }}"
                    {{ $server->type_application_id == $type->id ? 'selected' : '' }}>
                    {{ $type->name_application }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label text-start w-100">VM (VMware)</label>
              <input type="text" name="vm_according_to_the_vmware" class="form-control text-start" placeholder="Ej: vm-app-prod-01" value="{{ $server->vm_according_to_the_vmware }}"required>
            </div>
            <div class="col-md-6">
              <label class="form-label text-start w-100">
                <i class="bx bx-check-circle me-1 text-primary"></i> Estado
              </label>
              <select name="state" class="form-select text-start" required>
                <option value="poweredOn" {{ !$isPoweredOff ? 'selected' : '' }}>poweredOn</option>
                <option value="poweredOff" {{ $isPoweredOff ? 'selected' : '' }}>poweredOff</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label text-start w-100">DNS</label>
              <input type="text" name="dns_name" class="form-control text-start" placeholder="Ej: app.empresa.com" value="{{ $server->dns_name }}">
            </div>
            <div class="col-md-6">
              <label class="form-label text-start w-100">
                <i class="bx bx-network-chart me-1 text-primary"></i> IP primaria
              </label>
              <input type="text" name="primary_ip_address" class="form-control text-start" placeholder="Ej: 192.168.1.15" value="{{ $server->primary_ip_address }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label text-start w-100">IP usuario</label>
              <input type="text" name="ip_user" class="form-control text-start" placeholder="Ej: 192.168.1.50" value="{{ $server->ip_user }}">
            </div>
            <div class="col-md-6">
              <label class="form-label text-start w-100">IP monitoreo</label>
              <input type="text" name="ip_monitoring" class="form-control text-start" placeholder="Ej: 192.168.1.60" value="{{ $server->ip_monitoring }}">
            </div>
            <div class="col-md-6">
              <label class="form-label text-start w-100">Entorno</label>
              <input type="text" name="environment" class="form-control text-start" placeholder="Ej: Producción" value="{{ $server->environment }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label text-start w-100">Datacenter</label>
              <input type="text" name="datacenter" class="form-control text-start" placeholder="Ej: DC-MX-01" value="{{ $server->datacenter }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label text-start w-100">Sistema operativo</label>
              <input type="text" name="os_according_to_the_vmware" class="form-control text-start" placeholder="Ej: Windows Server 2019" value="{{ $server->os_according_to_the_vmware }}"required>
            </div>
            <div class="col-md-6">
              <label class="form-label text-start w-100">Versión interna</label>
              <input type="text" name="os_version_internal" class="form-control text-start" placeholder="Ej: 10.0.17763" value="{{ $server->os_version_internal }}"required>
            </div>
            <div class="col-md-6">
              <label class="form-label text-start w-100">Hostname interno</label>
              <input type="text" name="hostname_internal" class="form-control text-start" placeholder="Ej: srv-app-01" value="{{ $server->hostname_internal }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label text-start w-100">RAM (MB)</label>
              <input type="number" name="ram_memory" class="form-control text-start" placeholder="Ej: 8192" value="{{ $server->ram_memory }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label text-start w-100">Swap (MB)</label>
              <input type="number" name="swap_memory" class="form-control text-start" placeholder="Ej: 4096" value="{{ $server->swap_memory }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label text-start w-100">Último parche</label>
              <input type="date" name="latest_security_patch" class="form-control text-start" value="{{ $server->latest_security_patch }}">
            </div>
            <div class="col-12">
              <label class="form-label text-start w-100">Otras IPs</label>
              <textarea name="other_ips" rows="2" class="form-control text-start" placeholder="Ej: 192.168.1.20, 192.168.1.21">{{ $server->other_ips }}</textarea>
            </div>
            <div class="col-12">
              <label class="form-label text-start w-100">Comentarios</label>
              <textarea name="comments" rows="3" class="form-control text-start" placeholder="Información adicional del servidor">{{ $server->comments }}</textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button"
                  class="btn btn-label-secondary"
                  data-bs-dismiss="modal">
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
