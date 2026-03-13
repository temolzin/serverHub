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
            <label class="form-label">Instancia</label>
            <select name="instance_id" class="form-select" required>
              <option value="" disabled selected>Selecciona una instancia</option>
              @foreach ($instances as $instance)
                <option value="{{ $instance->id }}">
                  {{ $instance->server->hostname_internal }}
                  {{ $instance->version }}
                  ({{ $instance->edition }})
                </option>
              @endforeach
            </select>
          </div>
          <div class="mb-4">
            <label class="form-label">Propietario</label>
            <select name="owner_id" class="form-select" required>
              <option value="" disabled selected>Selecciona un propietario</option>
              @foreach ($owners as $owner)
                <option value="{{ $owner->id }}">
                  {{ $owner->name }} {{ $owner->last_name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="mb-4">
            <label class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" placeholder="Ej: db_produccion" maxlength="50"
              required>
          </div>
          <div class="mb-4">
            <label class="form-label">Tipo</label>
            <select name="type" class="form-select" required>
              <option value="" disabled selected>Selecciona un tipo</option>
              <option value="Oracle">Oracle</option>
              <option value="MSSQL">MSSQL</option>
              <option value="MySQL">MySQL</option>
              <option value="DB2">DB2</option>
            </select>
          </div>
          <div class="mb-4">
            <label class="form-label">Puerto</label>
            <input type="number" name="port" class="form-control" placeholder="Ej: 3306" min="1"
              max="65535">
            <small class="text-muted">
              Rango permitido: 1 - 65535
            </small>
          </div>
          <div class="mb-4">
            <label class="form-label">Versión</label>
            <input type="text" name="version" class="form-control" placeholder="Ej: 8.0.36" maxlength="20">
          </div>
          <div class="mb-4">
            <label class="form-label">Estado</label>
            <select name="status" class="form-select" required>
              <option value="" disabled selected>Selecciona un estado</option>
              <option value="active">Activo</option>
              <option value="inactive">Inactivo</option>
            </select>
          </div>
          <div class="mb-4">
            <label class="form-label">Última actualización</label>
            <input type="date" name="last_update" class="form-control">
          </div>
          <div class="mb-4">
            <label class="form-label">Comentarios</label>
            <textarea name="comments" class="form-control" rows="3" maxlength="500"
              placeholder="Información adicional relevante"></textarea>
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
