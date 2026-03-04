<div class="modal fade" id="showOwnerModal{{ $owner->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center gap-2">
          <i class="bx bx-user-circle text-primary fs-4"></i>
          Detalle del propietario
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label fw-semibold">
                <i class="bx bx-user me-1 text-primary"></i>
                Nombre
                </label>
                <input type="text" class="form-control" value="{{ $owner->name }}" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">
                <i class="bx bx-id-card me-1 text-primary"></i>
                Apellido
                </label>
                <input type="text" class="form-control" value="{{ $owner->last_name }}" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">
                <i class="bx bx-envelope me-1 text-primary"></i>
                Email
                </label>
                <input type="text" class="form-control" value="{{ $owner->email }}" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">
                <i class="bx bx-phone me-1 text-primary"></i>
                Teléfono
                </label>
                <input type="text" class="form-control" value="{{ $owner->number_phone ?? '—' }}" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">
                <i class="bx bx-user-check me-1 text-primary"></i>
                Creado por
                </label>
                <input type="text" class="form-control" value="{{ $owner->creator->name ?? 'N/A' }}" disabled>
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
