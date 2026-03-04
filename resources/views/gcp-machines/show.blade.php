@php($ownerFullName = trim((optional($machine->owner)->name ?? '') . ' ' . (optional($machine->owner)->last_name ?? '')))
@php($applicationName = optional($machine->application)->name)
@php($stateLabel = $machine->isPoweredOff() ? 'poweredOff' : 'poweredOn')
<div class="modal fade" id="showGcpMachineModal{{ $machine->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title d-flex align-items-center gap-2">
          <i class="bx bx-cloud text-primary fs-4"></i>
          Detalle de máquina GCP
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-start">
        <div class="row">
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">UUID</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-fingerprint"></i></span>
              <input type="text" class="form-control" value="{{ filled($machine->uuid) ? $machine->uuid : 'N/A' }}" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">Proyecto</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-folder"></i></span>
              <input type="text" class="form-control" value="{{ filled($machine->project_name) ? $machine->project_name : 'N/A' }}" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">Propietario</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-user"></i></span>
              <input type="text" class="form-control" value="{{ filled($ownerFullName) ? $ownerFullName : 'N/A' }}" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">Aplicación</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-layer"></i></span>
              <input type="text" class="form-control" value="{{ filled($applicationName) ? $applicationName : 'N/A' }}" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">Estado</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-power-off"></i></span>
              <input type="text" class="form-control" value="{{ $stateLabel }}" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">Nombre máquina</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-desktop"></i></span>
              <input type="text" class="form-control" value="{{ filled($machine->machine_name) ? $machine->machine_name : 'N/A' }}" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">Nombre interno</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-chip"></i></span>
              <input type="text" class="form-control" value="{{ filled($machine->machine_internal_name) ? $machine->machine_internal_name : 'N/A' }}" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">Sistema operativo</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-cog"></i></span>
              <input type="text" class="form-control" value="{{ filled($machine->operations_system) ? $machine->operations_system : 'N/A' }}" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">Versión kernel</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-data"></i></span>
              <input type="text" class="form-control" value="{{ filled($machine->kernel_version) ? $machine->kernel_version : 'N/A' }}" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">Entorno</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-globe"></i></span>
              <input type="text" class="form-control" value="{{ filled($machine->environment) ? $machine->environment : 'N/A' }}" disabled>
            </div>
          </div>
          <div class="col-md-12 mb-4">
            <label class="form-label fw-semibold">IP interna</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-network-chart"></i></span>
              <input type="text" class="form-control" value="{{ filled($machine->internal_ip) ? $machine->internal_ip : 'N/A' }}" disabled>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <label class="form-label fw-semibold">Alias IP</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-link"></i></span>
              <input type="text" class="form-control" value="{{ filled($machine->alias_ip) ? $machine->alias_ip : 'N/A' }}" disabled>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <label class="form-label fw-semibold">Alias 2 IP</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-link-alt"></i></span>
              <input type="text" class="form-control" value="{{ filled($machine->alias2_ip) ? $machine->alias2_ip : 'N/A' }}" disabled>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <label class="form-label fw-semibold">Alias 3 IP</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-link-alt"></i></span>
              <input type="text" class="form-control" value="{{ filled($machine->alias3_ip) ? $machine->alias3_ip : 'N/A' }}" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">RAM (MB)</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-memory-card"></i></span>
              <input type="text" class="form-control" value="{{ $machine->ram_memory ?? 0 }}" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">Swap (MB)</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-transfer"></i></span>
              <input type="text" class="form-control" value="{{ $machine->swap_memory ?? 0 }}" disabled>
            </div>
          </div>
          <div class="col-md-12 mb-4">
            <label class="form-label fw-semibold">Último parche de seguridad</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-shield-quarter"></i></span>
              <input type="text" class="form-control" value="{{ filled($machine->latest_security_patch) ? $machine->latest_security_patch : 'N/A' }}" disabled>
            </div>
          </div>
          <div class="col-md-12 mb-4">
            <label class="form-label fw-semibold">Otras IPs</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-list-ul"></i></span>
              <textarea class="form-control" rows="2" disabled>{{ filled($machine->other_ips) ? $machine->other_ips : 'N/A' }}</textarea>
            </div>
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
