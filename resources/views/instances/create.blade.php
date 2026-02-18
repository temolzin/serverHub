<div class="modal fade" id="createInstanceModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <form action="{{ route('instances.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Crear instancia</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-4">
            <label class="form-label">Servidor</label>
            <select name="server_id" class="form-select" required>
              <option value="">Seleccionar servidor</option>
              @foreach ($servers as $server)
                <option value="{{ $server->id }}">
                  {{ $server->hostname_internal }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="mb-4">
            <label class="form-label">Memoria (MB)</label>
            <input type="number" name="memory" class="form-control" required>
          </div>
          <div class="mb-4">
            <label class="form-label">Versión</label>
            <input type="text" name="version" class="form-control" required>
          </div>
          <div class="mb-4">
            <label class="form-label">Edición</label>
            <input type="text" name="edition" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
            Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
