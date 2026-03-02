<div class="modal fade" id="createUserModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title d-flex align-items-center gap-2">
            <i class="bx bx-user-plus text-primary"></i>
            Crear Usuario
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label">
                  <i class="bx bx-user me-1 text-primary"></i>
                  Nombre
                </label>
                <input type="text" name="name" class="form-control" placeholder="Ejemplo:Juan Perez" required>
              </div>
              <div class="mb-3">
                <label class="form-label">
                  <i class="bx bx-envelope me-1 text-primary"></i>
                  Correo
                </label>
                <input type="email" name="email" class="form-control" placeholder="Ejemplo: correo@empresa.com"
                  required>
              </div>
              <div class="mb-3">
                <label class="form-label">
                  <i class="bx bx-lock me-1 text-primary"></i>
                  Contraseña
                </label>
                <input type="password" name="password" class="form-control" placeholder="Genera algo seguro" required>
              </div>
            </div>
            <div class="col-md-6">
              <h6 class="fw-bold text-primary mb-3">
                <i class="bx bx-lock-alt me-1"></i>
                Permisos
              </h6>
              <div class="row">
                @foreach ($permissions as $group => $groupPermissions)
                  <div class="col-6 mb-3">
                    <small class="fw-bold text-secondary">
                      {{ ucfirst($group) }}
                    </small>
                    @foreach ($groupPermissions as $permission)
                    <div class="form-check">
                        <input  class="form-check-input"  type="checkbox"  name="permissions[]" value="{{ $permission->name }}"> <label class="form-check-label">
                        {{ $permission->description }}
                        </label>
                    </div>
                    @endforeach
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
            Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="bx bx-save me-1"></i>
            Guardar Usuario
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
