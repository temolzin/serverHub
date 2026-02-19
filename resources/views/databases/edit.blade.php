<div class="modal fade" id="editDatabaseModal{{ $database->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar base de datos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('databases.update', $database) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="mb-4">
            <label class="form-label">Instance</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-layer"></i>
              </span>
              <select name="instance_id" class="form-select" required>
                @foreach ($instances as $instance)
                  <option value="{{ $instance->id }}" {{ $database->instance_id == $instance->id ? 'selected' : '' }}>
                    {{ $instance->server->hostname_internal }}
                    -
                    {{ $instance->version }}
                    ({{ $instance->edition }})
                  </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Propietario</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-user"></i>
              </span>
              <select name="owner_id" class="form-select" required>
                @foreach ($owners as $owner)
                  <option value="{{ $owner->id }}" {{ $database->owner_id == $owner->id ? 'selected' : '' }}>
                    {{ $owner->name }} {{ $owner->last_name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Nombre</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-data"></i>
              </span>
              <input type="text" name="name" class="form-control" value="{{ $database->name }}" required>
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Tipo</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-layer"></i>
              </span>
              <input type="text" name="type" class="form-control" value="{{ $database->type }}" required>
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Puerto</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-network-chart"></i>
              </span>
              <input type="number" name="port" class="form-control" value="{{ $database->port }}">
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Versión</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-code"></i>
              </span>
              <input type="text" name="version" class="form-control" value="{{ $database->version }}">
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Estado</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-check-circle"></i>
              </span>
              <input type="text" name="status" class="form-control" value="{{ $database->status }}">
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Última actualización</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-calendar"></i>
              </span>
              <input type="date" name="last_update" class="form-control"
                value="{{ \Carbon\Carbon::parse($database->last_update)->format('Y-m-d') }}">
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Comentarios</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-comment-detail"></i>
              </span>
              <textarea name="comments" class="form-control">{{ $database->comments }}</textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
            Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            Actualizar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
