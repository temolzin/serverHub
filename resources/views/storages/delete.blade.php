<div class="modal fade" id="deleteStorageModal{{ $storage->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Eliminar Almacenamiento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        ¿Estás seguro que deseas eliminar el almacenamiento
        <strong>{{ $storage->hostname }}</strong>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
          Cancelar
        </button>
        <form action="{{ route('storages.destroy', $storage) }}" method="POST">
          @csrf
          @method('DELETE')
          <button class="btn btn-danger">
            Eliminar
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
