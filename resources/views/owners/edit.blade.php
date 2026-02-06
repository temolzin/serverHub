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
          <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Nombre</label>
            <div class="col-sm-9">
              <input type="text" name="name" class="form-control" maxlength="20" value="{{ $owner->name }}"
                required>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Apellido</label>
            <div class="col-sm-9">
              <input type="text" name="last_name" class="form-control" maxlength="50" value="{{ $owner->last_name }}"
                required>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Email</label>
            <div class="col-sm-9">
              <input type="email" name="email" class="form-control" value="{{ $owner->email }}" required>
            </div>
          </div>
          <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Teléfono</label>
            <div class="col-sm-9">
              <input type="text" name="number_phone" class="form-control" maxlength="10"
                value="{{ $owner->number_phone }}">
            </div>
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
