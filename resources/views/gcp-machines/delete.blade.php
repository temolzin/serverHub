<div class="modal fade" id="deleteGcpMachineModal{{ $machine->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger">
          <i class="bx bx-trash me-2"></i>
          Eliminar máquina GCP
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('gcp-machines.destroy', $machine) }}" method="POST">
        @csrf
        @method('DELETE')
        <input type="hidden" name="page" value="{{ request('page') }}">
        <div class="modal-body text-center">
          <p class="mb-2">
            ¿Estás seguro de eliminar la máquina
            <strong>{{ $machine->machine_name }}</strong>?
          </p>
          <p class="text-muted small">
            Proyecto: {{ $machine->project_name }}
          </p>
          <p class="text-muted">
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
