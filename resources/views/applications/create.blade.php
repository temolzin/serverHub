<div class="modal fade" id="createApplicationModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bx bx-layer-plus text-primary me-2"></i>
          Crear aplicación
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('applications.store') }}" method="POST" id="createApplicationForm">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-4">
              <label class="form-label">
                <i class="bx bx-user me-1 text-primary"></i>
                Propietario
              </label>
              <select name="owner_id" id="ownerSelect" class="form-select" required>
                <option value="" disabled selected>Selecciona un propietario</option>
                @foreach ($owners as $owner)
                  <option value="{{ $owner->id }}">
                    {{ $owner->name }} {{ $owner->last_name }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">
                <i class="bx bx-server me-1 text-primary"></i>
                Servidor
              </label>
              <select name="server_id" id="serverSelect" class="form-select" required>
                <option value="" disabled selected>Selecciona un servidor</option>
                @foreach ($servers as $server)
                  <option value="{{ $server->id }}">
                    {{ $server->hostname_internal }} - {{ $server->primary_ip_address }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">
                <i class="bx bx-cube me-1 text-primary"></i>
                Nombre de la aplicación
              </label>
              <input type="text" name="name" class="form-control" placeholder="Ej: Sistema de Nómina" required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">
                <i class="bx bx-code me-1 text-primary"></i>
                Versión
              </label>
              <input type="text" name="version" class="form-control" placeholder="Ej: 2.3.1">
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">
                <i class="bx bx-pulse me-1 text-primary"></i>
                Status
              </label>
              <select name="status" class="form-select" required>
                <option value="" disabled selected>Selecciona un estado</option>
                <option value="production">Producción</option>
                <option value="staging">Pruebas</option>
                <option value="development">Desarrollo</option>
                <option value="inactive">Inactivo</option>
              </select>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">
                <i class="bx bx-category me-1 text-primary"></i>
                Tipo
              </label>
              <input type="text" name="type" class="form-control" placeholder="Ej: Web, API, Servicio Windows">
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">
                <i class="bx bx-chip me-1 text-primary"></i>
                Memoria asignada (MB)
              </label>
              <input type="number" name="assigned_memory" class="form-control" placeholder="Ej: 2048">
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">
                <i class="bx bx-folder me-1 text-primary"></i>
                Ruta de instalación
              </label>
              <input type="text" name="installation_route" class="form-control" placeholder="Ej: C:\inetpub\app o /var/www/app">
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">
                <i class="bx bx-user-check me-1 text-primary"></i>
                Usuario servicio
              </label>
              <input type="text" name="user_service" class="form-control" placeholder="Ej: svc_app_prod">
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">
                <i class="bx bx-shield me-1 text-primary"></i>
                Último parche
              </label>
              <input type="date" name="latest_security_patch" class="form-control">
            </div>
            <div class="col-md-12 mb-4">
              <label class="form-label">
                <i class="bx bx-cog me-1 text-primary"></i>
                Procesos
              </label>
              <textarea name="processes" rows="2" class="form-control" placeholder="Ej: app.exe, worker.js, java -jar app.jar"></textarea>
            </div>
            <div class="col-md-12 mb-4">
              <label class="form-label">
                <i class="bx bx-time me-1 text-primary"></i>
                Tareas programadas
              </label>
              <textarea name="cron_jobs" rows="2" class="form-control" placeholder="Ej: 0 2 * * * /usr/bin/php artisan schedule:run">
              </textarea>
            </div>
            <div class="col-md-12 mb-4">
              <label class="form-label">
                <i class="bx bx-comment me-1 text-primary"></i>
                Comentarios
              </label>
              <textarea name="comments" rows="3" class="form-control" placeholder="Información adicional relevante de la aplicación">
              </textarea>
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
