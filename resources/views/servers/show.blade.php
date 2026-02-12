<div class="modal fade" id="showServerModal{{ $server->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center gap-2">
          <i class="bx bx-show text-primary fs-4"></i>
          Detalle del servidor
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label fw-semibold">
            <i class="bx bx-user me-1 text-primary"></i>
            Propietario
          </label>
          <input type="text" class="form-control"
            value="{{ optional($server->owner)->name }} {{ optional($server->owner)->last_name }}" disabled>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">
            <i class="bx bx-layer me-1 text-primary"></i>
            Aplicación
          </label>
          <input type="text" class="form-control" value="{{ optional($server->typeApplication)->name_application }}"
            disabled>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">
            <i class="bx bx-server me-1 text-primary"></i>
            VM según VMware
          </label>
          <input type="text" class="form-control" value="{{ $server->vm_according_to_the_vmware }}" disabled>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">
            <i class="bx bx-power-off me-1 text-primary"></i>
            Estado
          </label>
          <input type="text" class="form-control" value="{{ $server->state ? 'Activo' : 'Inactivo' }}" disabled>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">
            <i class="bx bx-globe me-1 text-primary"></i>
            DNS
          </label>
          <input type="text" class="form-control" value="{{ $server->dns_name ?? '—' }}" disabled>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">
            <i class="bx bx-network-chart me-1 text-primary"></i>
            IP primaria
          </label>
          <input type="text" class="form-control" value="{{ $server->primary_ip_address }}" disabled>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">
            <i class="bx bx-code-alt me-1 text-primary"></i>
            Entorno
          </label>
          <input type="text" class="form-control" value="{{ $server->environment }}" disabled>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">
            <i class="bx bx-building me-1 text-primary"></i>
            Datacenter
          </label>
          <input type="text" class="form-control" value="{{ $server->datacenter }}" disabled>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">
            <i class="bx bx-chip me-1 text-primary"></i>
            Sistema operativo (VMware)
          </label>
          <input type="text" class="form-control" value="{{ $server->os_according_to_the_vmware }}" disabled>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">
            <i class="bx bx-code me-1 text-primary"></i>
            Versión interna
          </label>
          <input type="text" class="form-control" value="{{ $server->os_version_internal }}" disabled>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">
            <i class="bx bx-desktop me-1 text-primary"></i>
            Hostname interno
          </label>
          <input type="text" class="form-control" value="{{ $server->hostname_internal }}" disabled>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">
            <i class="bx bx-network-chart me-1 text-primary"></i>
            IP usuario
          </label>
          <input type="text" class="form-control" value="{{ $server->ip_user ?? '—' }}" disabled>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">
            <i class="bx bx-line-chart me-1 text-primary"></i>
            IP monitoreo
          </label>
          <input type="text" class="form-control" value="{{ $server->ip_monitoring ?? '—' }}" disabled>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">
            <i class="bx bx-list-ul me-1 text-primary"></i>
            Otras IPs
          </label>
          <textarea class="form-control" disabled>{{ $server->other_ips ?? '—' }}</textarea>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">
            <i class="bx bx-memory-card me-1 text-primary"></i>
            RAM (MB)
          </label>
          <input type="text" class="form-control" value="{{ $server->ram_memory }} MB" disabled>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">
            <i class="bx bx-data me-1 text-primary"></i>
            Swap (MB)
          </label>
          <input type="text" class="form-control" value="{{ $server->swap_memory }} MB" disabled>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">
            <i class="bx bx-calendar me-1 text-primary"></i>
            Último parche de seguridad
          </label>
          <input type="text" class="form-control" value="{{ $server->latest_security_patch ?? '—' }}" disabled>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">
            <i class="bx bx-comment-detail me-1 text-primary"></i>
            Comentarios
          </label>
          <textarea class="form-control" disabled>{{ $server->comments ?? '—' }}</textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
          <i class="bx bx-x-circle me-1"></i>
          Cerrar
        </button>
      </div>
    </div>
  </div>
</div>
