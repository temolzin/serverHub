<div class="modal fade" id="showStorageModal{{ $storage->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center gap-2">
          <i class="bx bx-hdd text-primary fs-4"></i>
          Detalle del almacenamiento
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">
              <i class="bx bx-server me-1 text-primary"></i>
              Hostname
            </label>
            <input type="text" class="form-control" value="{{ $storage->hostname }}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">
              <i class="bx bx-network-chart me-1 text-primary"></i>
              IP de Datos
            </label>
            <input type="text" class="form-control" value="{{ $storage->data_ip ?? '—' }}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">
              <i class="bx bx-layer me-1 text-primary"></i>
              Plataforma
            </label>
            <input type="text" class="form-control" value="{{ $storage->platform ?? '—' }}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">
              <i class="bx bx-code me-1 text-primary"></i>
              Nombre del Sistema Operativo
            </label>
            <input type="text" class="form-control" value="{{ $storage->os_name ?? '—' }}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">
              <i class="bx bx-chip me-1 text-primary"></i>
              Sistema Operativo Interno
            </label>
            <input type="text" class="form-control" value="{{ $storage->os_internal ?? '—' }}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">
              <i class="bx bx-desktop me-1 text-primary"></i>
              Sistema Operativo (General)
            </label>
            <input type="text" class="form-control" value="{{ $storage->operations_system ?? '—' }}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">
              <i class="bx bx-network-chart me-1 text-primary"></i>
              IP Interna
            </label>
            <input type="text" class="form-control" value="{{ $storage->internal_ip ?? '—' }}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">
              <i class="bx bx-cloud me-1 text-primary"></i>
              Entorno
            </label>
            <input type="text" class="form-control" value="{{ $storage->environment ?? '—' }}" disabled>
          </div>
          <div class="col-12 mb-3">
            <label class="form-label fw-semibold">
              <i class="bx bx-building-house me-1 text-primary"></i>
              Datacenter
            </label>
            <input type="text" class="form-control" value="{{ $storage->datacenter ?? '—' }}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">
              <i class="bx bx-user-check me-1 text-primary"></i>
              Creado por
            </label>
            <input type="text" class="form-control" value="{{ $storage->creator->name ?? 'N/A' }}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">
                <i class="bx bx-calendar-plus me-1 text-primary"></i>
                Fecha de creación
            </label>
            <input type="text" class="form-control"
                value="{{ $storage->created_at ? $storage->created_at->timezone('America/Mexico_City')->format('d/m/Y h:i A') : 'N/A' }}"
                disabled>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
          <i class="bx bx-x-circle me-1"></i> Cerrar
        </button>
      </div>
    </div>
  </div>
</div>
