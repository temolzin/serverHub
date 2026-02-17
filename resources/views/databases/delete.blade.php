<div class="modal fade" id="deleteDatabaseModal{{ $database->id }}" tabindex="-1" aria-hidden="true">

  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger">
          <i class="bx bx-trash me-1"></i>
          Eliminar base de datos
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form action="{{ route('databases.destroy', $database) }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body text-center">
          <p class="mb-1">
            ¿Estás seguro de eliminar la base de datos:
          </p>
          <h6 class="fw-bold text-danger">
            {{ $database->name }}
          </h6>
          <p class="mt-2">
            Servidor:
            <strong>{{ optional($database->server)->hostname_internal }}</strong>
          </p>
          <p class="text-muted mt-2">
            Esta acción no se puede deshacer.
          </p>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
            Cancelar
          </button>
          <button type="submit" class="btn btn-danger">
            Sí, eliminar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
