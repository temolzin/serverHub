<div class="modal fade" id="deleteServerModal{{ $server->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger">
          <i class="bx bx-trash me-2"></i>
          Eliminar servidor
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal">
        </button>
      </div>
      <form action="{{ route('servers.destroy', $server) }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body text-center">
          <p class="mb-2">
            ¿Estás seguro de eliminar el servidor con IP:
          </p>
          <h6 class="fw-bold text-danger">
            {{ $server->primary_ip_address }}
          </h6>
          <p class="mb-2">
            Del propietario :
          </p>
          <h6 class="fw-bold text-danger">
            {{ optional($server->owner)->name }} {{ optional($server->owner)->last_name }}
          </h6>
          <p class="text-muted mt-3">
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
