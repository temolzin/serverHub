<div class="modal fade" id="createDatabaseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Crear base de datos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form action="{{ route('databases.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-4">
            <label class="form-label">Servidor</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-server"></i></span>
              <select name="server_id" class="form-select" required>
                <option value="">Seleccionar servidor</option>
                @foreach ($servers as $server)
                  <option value="{{ $server->id }}">
                    {{ $server->hostname_internal }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label">Nombre</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-data"></i></span>
              <input type="text" name="name" class="form-control" required>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label">Tipo</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-layer"></i></span>
              <input type="text" name="type" class="form-control" required>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label">Puerto</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-network-chart"></i></span>
              <input type="number" name="port" class="form-control">
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label">Versión</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-code"></i></span>
              <input type="text" name="version" class="form-control">
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label">Estado</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-check-circle"></i></span>
              <input type="text" name="status" class="form-control">
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label">Última actualización</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-calendar"></i></span>
              <input type="date" name="last_update" class="form-control">
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label">Comentarios</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-comment-detail"></i></span>
              <textarea name="comments" class="form-control"></textarea>
            </div>
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
