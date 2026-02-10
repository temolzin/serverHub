<div class="modal fade" id="deleteTypeApplicationModal{{ $type->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger">Eliminar tipo de aplicación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('type-applications.destroy', $type) }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body">
          <p class="mb-0">
            ¿Estás seguro de eliminar el tipo de aplicación
            <strong>{{ $type->type_application }} - {{ $type->name_application }}</strong>?
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
