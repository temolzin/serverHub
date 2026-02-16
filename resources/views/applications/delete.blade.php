<div class="modal fade" id="deleteApplicationModal{{ $application->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger">
          <i class="bx bx-trash me-2"></i>
          Eliminar Aplicación
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('applications.destroy', $application) }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-body text-center">
          <i class="bx bx-error-circle text-danger" style="font-size: 55px;"></i>
          <h5 class="mt-3">
            ¿Estás seguro?
          </h5>
          <p class="text-muted mb-1">
            Estás a punto de eliminar la aplicación:
          </p>
          <p class="fw-bold mb-1">
            {{ $application->name }}
          </p>
          <p class="text-muted">
            Servidor: {{ $application->server->hostname_internal ?? '-' }}
          </p>
          <p class="text-danger small mt-3">
            Esta acción no se puede deshacer.
          </p>
        </div>
        <div class="modal-footer justify-content-center">
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
