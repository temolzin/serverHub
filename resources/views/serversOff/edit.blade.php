@php($isPoweredOff = $server->isPoweredOff())

<div class="modal fade" id="editServerOffModal{{ $server->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bx bx-edit text-primary me-2"></i>
          Editar servidor apagado
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('servers-off.update', $server) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="page" value="{{ request('page') }}">
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label w-100 text-start">VM</label>
              <input type="text" name="vm_according_to_the_vmware" class="form-control"
                value="{{ $server->vm_according_to_the_vmware }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label w-100 text-start">Nombre DNS</label>
              <input type="text" name="dns_name" class="form-control" value="{{ $server->dns_name }}">
            </div>
            <div class="col-md-6">
              <label class="form-label w-100 text-start">Centro de datos</label>
              <input type="text" name="datacenter" class="form-control" value="{{ $server->datacenter }}">
            </div>
            <div class="col-md-6">
              <label class="form-label w-100 text-start">Estado</label>
              <select name="state" class="form-select" required>
                <option value="poweredOff" {{ $isPoweredOff ? 'selected' : '' }}>
                  poweredOff
                </option>
                <option value="poweredOn" {{ !$isPoweredOff ? 'selected' : '' }}>
                  poweredOn
                </option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label w-100 text-start">
                SO según configuración (os_version_internal)
              </label>
              <input type="text" name="os_version_internal" class="form-control"
                value="{{ $server->os_version_internal }}">
            </div>
            <div class="col-md-6">
              <label class="form-label w-100 text-start">
                SO según VMware
              </label>
              <input type="text" name="os_according_to_the_vmware" class="form-control"
                value="{{ $server->os_according_to_the_vmware }}">
            </div>
            <div class="col-md-6">
              <label class="form-label w-100 text-start">
                IP primaria (opcional)
              </label>
              <input type="text" name="primary_ip_address" class="form-control"
                value="{{ $server->primary_ip_address }}">
            </div>
            <div class="col-md-6">
              <label class="form-label w-100 text-start">
                Último parche de seguridad
              </label>
              <input type="date" name="latest_security_patch" class="form-control"
                value="{{ $server->latest_security_patch }}">
            </div>
            <div class="col-12">
              <label class="form-label w-100 text-start">
                Comentarios
              </label>
              <textarea name="comments" rows="3" class="form-control">{{ $server->comments }}</textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
            Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            Actualizar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
