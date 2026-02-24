@php($stateLabel = $server->isPoweredOff() ? 'poweredOff' : 'poweredOn')
<div class="modal fade" id="showServerOffModal{{ $server->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bx bx-server text-primary me-2"></i>
          Detalle servidor apagado
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label w-100 text-start">UUID</label>
            <input type="text" class="form-control" value="{{ $server->uuid ?? 'N/A' }}" disabled>
          </div>
          <div class="col-md-6">
            <label class="form-label w-100 text-start">VM</label>
            <input type="text" class="form-control" value="{{ $server->vm_according_to_the_vmware ?? 'N/A' }}"
              disabled>
          </div>
          <div class="col-md-6">
            <label class="form-label w-100 text-start">Nombre DNS</label>
            <input type="text" class="form-control" value="{{ $server->dns_name ?? 'N/A' }}" disabled>
          </div>
          <div class="col-md-6">
            <label class="form-label w-100 text-start">Centro de datos</label>
            <input type="text" class="form-control" value="{{ $server->datacenter ?? 'N/A' }}" disabled>
          </div>
          <div class="col-md-6">
            <label class="form-label w-100 text-start">Estado</label>
            <input type="text" class="form-control" value="{{ $stateLabel }}" disabled>
          </div>
          <div class="col-md-6">
            <label class="form-label w-100 text-start">SO según configuración</label>
            <input type="text" class="form-control" value="{{ $server->os_version_internal ?? 'N/A' }}" disabled>
          </div>
          <div class="col-md-6">
            <label class="form-label w-100 text-start">SO según VMware</label>
            <input type="text" class="form-control" value="{{ $server->os_according_to_the_vmware ?? 'N/A' }}"
              disabled>
          </div>
          <div class="col-md-6">
            <label class="form-label w-100 text-start">IP primaria</label>
            <input type="text" class="form-control" value="{{ $server->primary_ip_address ?? 'N/A' }}" disabled>
          </div>
          <div class="col-md-6">
            <label class="form-label w-100 text-start">Último parche de seguridad</label>
            <input type="text" class="form-control" value="{{ $server->latest_security_patch ?? 'N/A' }}" disabled>
          </div>
          <div class="col-12">
            <label class="form-label w-100 text-start">Comentarios</label>
            <textarea class="form-control" rows="3" disabled>{{ $server->comments ?? 'N/A' }}</textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
          Cerrar
        </button>
      </div>
    </div>
  </div>
</div>
