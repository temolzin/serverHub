@php
  $databaseServerNames = $database->servers->pluck('hostname_internal')->filter()->implode(', ');
@endphp

<div class="modal fade" id="showDatabaseModal{{ $database->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center gap-2">
          <i class="bx bx-data text-primary fs-4"></i>
          Detalle de la base de datos
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        <h5 class="modal-title d-flex align-items-center gap-2"><i class="bx bx-data text-primary fs-4"></i> Detalle de
          la base de datos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold"><i class="bx bx-server me-1 text-primary"></i>Servidor</label>
            <input type="text" class="form-control"
              value="{{ filled($databaseServerNames) ? $databaseServerNames : 'N/A' }}" disabled>
          </div>
          <div class="col-md-6">
            <label class="form-label">Creado por</label>
            <input type="text" class="form-control" value="{{ $database->creator->name ?? 'N/A' }}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold"><i class="bx bx-data me-1 text-primary"></i>Nombre</label>
            <input type="text" class="form-control" value="{{ $database->name }}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold"><i class="bx bx-layer me-1 text-primary"></i>Tipo</label>
            <input type="text" class="form-control" value="{{ $database->type }}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold"><i class="bx bx-network-chart me-1 text-primary"></i>Puerto</label>
            <input type="text" class="form-control" value="{{ $database->port ?? '—' }}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold"><i class="bx bx-code me-1 text-primary"></i>Versión</label>
            <input type="text" class="form-control" value="{{ $database->version ?? '—' }}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold"><i class="bx bx-check-circle me-1 text-primary"></i>Estado</label>
            <input type="text" class="form-control" value="{{ $database->status ?? '—' }}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold"><i class="bx bx-calendar me-1 text-primary"></i>Última
              actualización</label>
            <input type="text" class="form-control" value="{{ $database->last_update ?? '—' }}" disabled>
          </div>
          <div class="col-12 mb-3">
            <label class="form-label fw-semibold"><i
                class="bx bx-comment-detail me-1 text-primary"></i>Comentarios</label>
            <textarea class="form-control" rows="3" disabled>{{ $database->comments ?? '—' }}</textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><i class="bx bx-x-circle me-1"></i>
          Cerrar</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">
              <i class="bx bx-server me-1 text-primary"></i>
              Servidor
            </label>
            <input type="text" class="form-control" value="{{ optional($database->server)->hostname_internal }}"
              disabled>
          </div>
          <div class="col-md-6">
            <label class="form-label">Creado por</label>
            <input type="text" class="form-control" value="{{ $database->creator->name ?? 'N/A' }}" disabled>
          </div>
          <div class="col-md-12 mb-3">
            <label class="form-label fw-semibold">
              <i class="bx bx-calendar-plus me-1 text-primary"></i>
              Fecha de creación
            </label>
            <input type="text" class="form-control"
              value="{{ $database->created_at ? $database->created_at->timezone('America/Mexico_City')->format('d/m/Y h:i A') : '—' }}"
              disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">
              <i class="bx bx-data me-1 text-primary"></i>
              Nombre
            </label>
            <input type="text" class="form-control" value="{{ $database->name }}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">
              <i class="bx bx-layer me-1 text-primary"></i>
              Tipo
            </label>
            <input type="text" class="form-control" value="{{ $database->type }}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">
              <i class="bx bx-network-chart me-1 text-primary"></i>
              Puerto
            </label>
            <input type="text" class="form-control" value="{{ $database->port ?? '—' }}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">
              <i class="bx bx-code me-1 text-primary"></i>
              Versión
            </label>
            <input type="text" class="form-control" value="{{ $database->version ?? '—' }}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">
              <i class="bx bx-check-circle me-1 text-primary"></i>
              Estado
            </label>
            <input type="text" class="form-control" value="{{ $database->status ?? '—' }}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">
              <i class="bx bx-calendar me-1 text-primary"></i>
              Última actualización
            </label>
            <input type="text" class="form-control" value="{{ $database->last_update ?? '—' }}" disabled>
          </div>
          <div class="col-12 mb-3">
            <label class="form-label fw-semibold">
              <i class="bx bx-comment-detail me-1 text-primary"></i>
              Comentarios
            </label>
            <textarea class="form-control" rows="3" disabled>{{ $database->comments ?? '—' }}</textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
          <i class="bx bx-x-circle me-1"></i> Cerrar
        </button>
      </div>
    </div>
  </div>
</div>
