<div class="modal fade" id="createTypeApplicationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Crear tipo de aplicación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('type-applications.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-4">
            <label class="form-label">Tipo de aplicación</label>
            <input type="text" name="type_application" class="form-control" placeholder="Ej: Web, Mobile, API"
              maxlength="50" required>
            <small class="text-muted">Máximo 50 caracteres</small>
          </div>
          <div class="mb-4">
            <label class="form-label">Nombre de la aplicación</label>
            <input type="text" name="name_application" class="form-control" placeholder="Ej: Sistema de Inventario"
              maxlength="100" required>
            <small class="text-muted">Máximo 100 caracteres</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
            Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            Crear
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
