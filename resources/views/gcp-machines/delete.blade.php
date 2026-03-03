<div class="modal fade" id="deleteGcpMachineModal{{ $machine->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger">
          <i class="bx bx-trash me-2"></i>
          Eliminar maquina GCP
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('gcp-machines.destroy', $machine) }}" method="POST">
        @csrf
        @method('DELETE')
        <input type="hidden" name="page" value="{{ request('page') }}">
        <div class="modal-body text-center">
          <p class="mb-2">
            Estas seguro de eliminar la maquina
            <strong>{{ filled($machine->machine_name) ? $machine->machine_name : 'N/A' }}</strong>?
          </p>
          <p class="text-muted small">
            Proyecto: {{ filled($machine->project_name) ? $machine->project_name : 'N/A' }}
          </p>
          <p class="text-muted">
            Esta accion no se puede deshacer.
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
            Cancelar
          </button>
          <button type="submit" class="btn btn-danger">
            Si, eliminar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
