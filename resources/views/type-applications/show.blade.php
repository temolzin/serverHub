<div class="modal fade" id="showTypeApplicationModal{{ $type->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center gap-2">
          <i class="bx bx-category text-primary fs-4"></i>
          Detalle del tipo de aplicación
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label fw-semibold">
            <i class="bx bx-category me-1 text-primary"></i>
            Tipo de aplicación
          </label>
          <input type="text" class="form-control" value="{{ $type->type_application }}" disabled>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">
            <i class="bx bx-rename me-1 text-primary"></i>
            Nombre de la aplicación
          </label>
          <input type="text" class="form-control" value="{{ $type->name_application }}" disabled>
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
