<div class="modal fade" id="editOwnerModal{{ $owner->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center gap-2">
          <i class="bx bx-edit text-primary"></i>Editar propietario
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('owners.update', $owner) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="mb-4">
            <label class="form-label fw-semibold">
              <i class="bx bx-user me-1 text-primary"></i>Nombre
            </label>
            <div class="input-group">
              <input type="text" name="name" class="form-control" placeholder="Ej: Carlos" maxlength="20" value="{{ $owner->name }}" required>
            </div>
            <small class="text-muted">Máximo 20 caracteres</small>
          </div>
          <div class="mb-4">
            <label class="form-label fw-semibold">
              <i class="bx bx-id-card me-1 text-primary"></i>Apellido
            </label>
            <div class="input-group">
              <input type="text" name="last_name" class="form-control" placeholder="Ej: Ramírez" maxlength="50" value="{{ $owner->last_name }}" required>
            </div>
            <small class="text-muted">Máximo 50 caracteres</small>
          </div>
          <div class="mb-4">
            <label class="form-label fw-semibold">
              <i class="bx bx-envelope me-1 text-primary"></i>Email
            </label>
            <div class="input-group">
              <input type="email" name="email" class="form-control email-check" placeholder="Ej: carlos.ramirez@empresa.com" value="{{ $owner->email }}" required data-exclude="{{ $owner->id }}" data-error-target="edit-owner-email-error-{{ $owner->id }}">
            </div>
          </div>
          <div id="edit-owner-email-error-{{ $owner->id }}" class="text-danger text-center mb-2 d-none"></div>
          <div class="mb-4">
            <label class="form-label fw-semibold">
              <i class="bx bx-phone me-1 text-primary"></i>Teléfono
            </label>
            <div class="input-group">
              <input type="text" name="number_phone" class="form-control" placeholder="Ej: 5512345678" maxlength="10" pattern="[0-9]*" inputmode="numeric" value="{{ $owner->number_phone }}" required>
            </div>
            <small class="text-muted">Solo números (10 dígitos)</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal"> Cancelar </button>
          <button type="submit" class="btn btn-primary"> <i class="bx bx-save me-1"></i> Actualizar </button>
        </div>
      </form>
    </div>
  </div>
</div>
