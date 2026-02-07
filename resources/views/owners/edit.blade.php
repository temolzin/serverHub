<div class="modal fade" id="editOwnerModal{{ $owner->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar propietario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('owners.update', $owner) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="mb-4">
            <label class="form-label">Nombre</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-user"></i>
              </span>
              <input type="text" name="name" class="form-control" maxlength="20" value="{{ $owner->name }}"
                required>
            </div>
            <small class="text-muted">Máximo 20 caracteres</small>
          </div>
          <div class="mb-4">
            <label class="form-label">Apellido</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-id-card"></i>
              </span>
              <input type="text" name="last_name" class="form-control" maxlength="50" value="{{ $owner->last_name }}"
                required>
            </div>
            <small class="text-muted">Máximo 50 caracteres</small>
          </div>
          <div class="mb-4">
            <label class="form-label">Email</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-envelope"></i>
              </span>
              <input type="email" name="email" class="form-control" value="{{ $owner->email }}" required>
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Teléfono</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-phone"></i>
              </span>
              <input type="text" name="number_phone" class="form-control" maxlength="10" pattern="[0-9]*"
                inputmode="numeric" value="{{ $owner->number_phone }}" required>
            </div>
            <small class="text-muted">Solo números (10 dígitos)</small>
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
