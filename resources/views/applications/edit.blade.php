<div class="modal fade" id="editApplicationModal{{ $application->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center gap-2">
          <i class="bx bx-edit text-warning fs-4"></i>
          Editar Aplicación
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('applications.update', $application) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold d-flex align-items-center gap-2">
                <i class="bx bx-user text-primary"></i>
                Propietario
              </label>
              <select name="owner_id" class="form-select ownerSelectEdit" required>
                @foreach ($owners as $owner)
                  <option value="{{ $owner->id }}" {{ $application->owner_id == $owner->id ? 'selected' : '' }}>
                    {{ $owner->name }} {{ $owner->last_name }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold d-flex align-items-center gap-2">
                <i class="bx bx-server text-info"></i>
                Servidor
              </label>
              <select name="server_id" class="form-select serverSelectEdit" required>
                @foreach ($servers as $server)
                  <option value="{{ $server->id }}" {{ $application->server_id == $server->id ? 'selected' : '' }}>
                    {{ $server->hostname_internal }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold d-flex align-items-center gap-2">
                <i class="bx bx-layer text-success"></i>
                Nombre
              </label>
              <input type="text" name="name" class="form-control" value="{{ $application->name }}" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold d-flex align-items-center gap-2">
                <i class="bx bx-code-alt text-secondary"></i>
                Versión
              </label>
              <input type="text" name="version" class="form-control" value="{{ $application->version }}">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold d-flex align-items-center gap-2">
                <i class="bx bx-check-circle text-success"></i>
                Status
              </label>
              <select name="status" class="form-select" required>
                <option value="production" {{ $application->status == 'production' ? 'selected' : '' }}>Production
                </option>
                <option value="staging" {{ $application->status == 'staging' ? 'selected' : '' }}>Staging</option>
                <option value="development" {{ $application->status == 'development' ? 'selected' : '' }}>Development
                </option>
                <option value="inactive" {{ $application->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold d-flex align-items-center gap-2">
                <i class="bx bx-memory-card text-warning"></i>
                Memoria asignada (MB)
              </label>
              <input type="number" name="assigned_memory" class="form-control"
                value="{{ $application->assigned_memory }}">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold d-flex align-items-center gap-2">
                <i class="bx bx-category text-info"></i>
                Tipo
              </label>
              <input type="text" name="type" class="form-control" value="{{ $application->type }}">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold d-flex align-items-center gap-2">
                <i class="bx bx-folder text-secondary"></i>
                Ruta instalación
              </label>
              <input type="text" name="installation_route" class="form-control"
                value="{{ $application->installation_route }}">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold d-flex align-items-center gap-2">
                <i class="bx bx-shield text-danger"></i>
                Último parche
              </label>
              <input type="date" name="latest_security_patch" class="form-control"
                value="{{ $application->latest_security_patch }}">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold d-flex align-items-center gap-2">
                <i class="bx bx-id-card text-primary"></i>
                Usuario servicio
              </label>
              <input type="text" name="user_service" class="form-control" value="{{ $application->user_service }}">
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold d-flex align-items-center gap-2">
                <i class="bx bx-list-ul text-primary"></i>
                Procesos
              </label>
              <textarea name="processes" class="form-control" rows="2">{{ $application->processes }}
              </textarea>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold d-flex align-items-center gap-2">
                <i class="bx bx-time text-primary"></i>
                Tareas programadas
              </label>
              <textarea name="cron_jobs" class="form-control" rows="2">{{ $application->cron_jobs }}
              </textarea>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold d-flex align-items-center gap-2">
                <i class="bx bx-comment-detail text-primary"></i>
                Comentarios
              </label>
              <textarea name="comments" class="form-control" rows="2">{{ $application->comments }}
              </textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
            Cancelar
          </button>
          <button type="submit" class="btn btn-warning">
            Actualizar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
