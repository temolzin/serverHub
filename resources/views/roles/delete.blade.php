<div class="modal fade" id="deleteRoleModal{{ $role->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <form action="{{ route('roles.destroy', $role) }}" method="POST">
        @csrf
        @method('DELETE')

        <div class="modal-header">
          <h5 class="modal-title d-flex align-items-center gap-2 text-danger">
            <i class="bx bx-trash"></i> Eliminar Rol
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body text-center">
          <p class="mb-1">
            ¿Seguro que deseas eliminar el rol
            <strong>{{ $role->name }}</strong>?
          </p>
          <small class="text-muted">
            Esta acción no se puede deshacer.
          </small>
        </div>

        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
            Cancelar
          </button>

          <button type="submit" class="btn btn-danger">
            <i class="bx bx-trash me-1"></i> Eliminar
          </button>
        </div>

      </form>

    </div>
  </div>
</div>
