<div class="modal fade" id="editApplicationModal{{ $application->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center gap-2">
          <i class="bx bx-edit text-primary"></i>
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
              <label class="form-label fw-semibold">
                <i class="bx bx-user me-1 text-primary"></i>
                Propietario
              </label>
              <select name="owner_id" class="form-select ownerSelectEdit" required>
                <option disabled>Seleccione un propietario</option>
                @foreach ($owners as $owner)
                  <option value="{{ $owner->id }}" {{ $application->owner_id == $owner->id ? 'selected' : '' }}>
                    {{ $owner->name }} {{ $owner->last_name }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">
                <i class="bx bx-server me-1 text-primary"></i>
                Servidor
              </label>
              <select name="server_id" class="form-select serverSelectEdit" required>
                <option disabled>Seleccione un servidor</option>
                @foreach ($servers as $server)
                  <option value="{{ $server->id }}" {{ $application->server_id == $server->id ? 'selected' : '' }}>
                    {{ $server->hostname_internal }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">
                <i class="bx bx-layer me-1 text-primary"></i>
                Nombre
              </label>
              <input type="text" name="name" class="form-control" placeholder="Ej: Sistema de Nómina"
                value="{{ $application->name }}" maxlength="20" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">
                <i class="bx bx-code-alt me-1 text-primary"></i>
                Versión
              </label>
              <input type="text" name="version" class="form-control" placeholder="Ej: 2.3.1"
                value="{{ $application->version }}" maxlength="20">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">
                <i class="bx bx-check-circle me-1 text-primary"></i>
                Status
              </label>
              <select name="status" class="form-select" required>
                <option value="">Seleccione un estado</option>
                <option value="production" {{ $application->status == 'production' ? 'selected' : '' }}>
                  Production
                </option>
                <option value="staging" {{ $application->status == 'staging' ? 'selected' : '' }}>
                  Staging
                </option>
                <option value="development" {{ $application->status == 'development' ? 'selected' : '' }}>
                  Development
                </option>
                <option value="inactive" {{ $application->status == 'inactive' ? 'selected' : '' }}>
                  Inactive
                </option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">
                <i class="bx bx-memory-card me-1 text-primary"></i>
                Memoria asignada (MB)
              </label>
              <input type="number" name="assigned_memory" class="form-control" placeholder="Ej: 2048"
                value="{{ $application->assigned_memory }}" min="1024" max="32768" step="1">
              <small class="text-muted">
                Rango permitido: 1024 MB - 32768 MB
              </small>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">
                <i class="bx bx-category me-1 text-primary"></i>
                Tipo
              </label>
              <input type="text" name="type" class="form-control" placeholder="Ej: Web, API, Servicio Windows"
                value="{{ $application->type }}" maxlength="20">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">
                <i class="bx bx-folder me-1 text-primary"></i>
                Ruta instalación
              </label>
              <input type="text" name="installation_route" class="form-control"
                placeholder="Ej: /var/www/app o C:\inetpub\app" value="{{ $application->installation_route }}"
                maxlength="100">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">
                <i class="bx bx-shield me-1 text-primary"></i>
                Último parche
              </label>
              <input type="date" name="latest_security_patch" class="form-control"
                value="{{ $application->latest_security_patch }}">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">
                <i class="bx bx-id-card me-1 text-primary"></i>
                Usuario servicio
              </label>
              <input type="text" name="user_service" class="form-control" placeholder="Ej: svc_app_prod"
                value="{{ $application->user_service }}" maxlength="20">
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">
                <i class="bx bx-list-ul me-1 text-primary"></i>
                Procesos
              </label>
              <textarea name="processes" class="form-control" rows="2" maxlength="500"
                placeholder="Ej: app.exe, worker.js, java -jar app.jar">{{ $application->processes }}</textarea>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">
                <i class="bx bx-time me-1 text-primary"></i>
                Tareas programadas
              </label>
              <textarea name="cron_jobs" class="form-control" rows="2" maxlength="500"
                placeholder="Ej: 0 2 * * * /usr/bin/php artisan schedule:run">{{ $application->cron_jobs }}</textarea>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">
                <i class="bx bx-comment-detail me-1 text-primary"></i>
                Comentarios
              </label>
              <textarea name="comments" class="form-control" rows="2" maxlength="500"
                placeholder="Información adicional relevante de la aplicación">{{ $application->comments }}</textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
            Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="bx bx-save me-1"></i>
            Actualizar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
