<div class="modal fade" id="editTypeApplicationModal{{ $type->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center gap-2">
          <i class="bx bx-edit text-primary"></i>
          Editar tipo de aplicación
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('type-applications.update', $type) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body text-start">
          <div class="mb-4">
            <label class="form-label">Tipo de aplicación</label>
            <div class="input-group">
              <span class="input-group-text">
                <i class="bx bx-category text-primary"></i>
              </span>
              <input type="text" name="type_application" class="form-control"
                placeholder="Ej: Web, API, Servicio Windows" maxlength="50" value="{{ $type->type_application }}"
                required>
            </div>
            <small class="text-muted">Máximo 50 caracteres</small>
          </div>
          <div class="mb-4">
            <label class="form-label">Nombre de la aplicación</label>
            <div class="input-group">
              <span class="input-group-text">
                <i class="bx bx-rename text-primary"></i>
              </span>
              <input type="text" name="name_application" class="form-control"
                placeholder="Ej: Sistema de Nómina Corporativo" maxlength="100" value="{{ $type->name_application }}"
                required>
            </div>
            <small class="text-muted">Máximo 100 caracteres</small>
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
