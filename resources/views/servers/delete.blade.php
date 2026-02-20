<div class="modal fade" id="deleteServerModal{{ $server->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger">
          <i class="bx bx-trash me-1"></i>
          Eliminar servidor
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('servers.destroy', $server) }}" method="POST">
        @csrf
        @method('DELETE')
        <input type="hidden" name="page" value="{{ request('page') }}">
        <div class="modal-body text-center">
          <p class="mb-0">
            ¿Estás seguro de eliminar el servidor con IP
            <strong>{{ $server->primary_ip_address }}</strong>?
          </p>
          <p class="mt-2">
            Hostname:
            <strong>{{ $server->hostname_internal }}</strong>
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
