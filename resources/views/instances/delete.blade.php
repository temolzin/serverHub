<div class="modal fade" id="deleteInstanceModal{{ $instance->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('instances.destroy', $instance) }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-header">
          <h5 class="modal-title text-danger">
            <i class="bx bx-trash me-1"></i>
            Eliminar instancia
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <p>¿Estás seguro de eliminar la instancia?</p>
          <strong>{{ $instance->server->hostname_internal }}</strong>
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
