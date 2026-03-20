<div class="modal fade" id="showInstanceModal{{ $instance->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center gap-2">
          <i class="bx bx-layer text-primary fs-4"></i>Detalle de la instancia
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 mb-3">
            <label class="form-label fw-semibold">
              <i class="bx bx-server me-1 text-primary"></i>Servidor
            </label>
            <input type="text" class="form-control" value="{{ $instance->server->hostname_internal ?? 'N/A' }}" disabled>
          </div>
          <div class="col-md-12 mb-3">
            <label class="form-label fw-semibold">
              <i class="bx bx-memory-card me-1 text-primary"></i>Memoria asignada (MB)
            </label>
            <input type="text" class="form-control" value="{{ $instance->memory ?? 'N/A' }}" disabled>
          </div>
          <div class="col-md-12 mb-3">
            <label class="form-label fw-semibold">
              <i class="bx bx-code-alt me-1 text-primary"></i>Versión
            </label>
            <input type="text" class="form-control" value="{{ $instance->version ?? 'N/A' }}" disabled>
          </div>
          <div class="col-md-12 mb-3">
            <label class="form-label fw-semibold">
              <i class="bx bx-layer me-1 text-primary"></i>Edición
            </label>
            <input type="text" class="form-control" value="{{ $instance->edition ?? 'N/A' }}" disabled>
          </div>
            <div class="col-md-12 mb-3">
            <label class="form-label fw-semibold">
              <i class="bx bx-user-check me-1 text-primary"></i>Creado por
            </label>
            <input type="text" class="form-control" value="{{ $instance->creator->name ?? 'N/A' }}" disabled>
          </div>
          <div class="col-md-12 mb-3">
            <label class="form-label fw-semibold">
                <i class="bx bx-calendar-plus me-1 text-primary"></i>Fecha de creación
            </label>
            <input type="text" class="form-control" value="{{ $instance->created_at ? $instance->created_at->format('d/m/Y h:i A') : 'N/A' }}" disabled>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal"> <i class="bx bx-x-circle me-1"></i> Cerrar</button>
      </div>
    </div>
  </div>
</div>
