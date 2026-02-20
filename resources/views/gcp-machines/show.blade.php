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
              <input type="text" class="form-control" value="{{ $machine->uuid ?? 'N/A' }}" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">Proyecto</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-folder"></i></span>
              <input type="text" class="form-control" value="{{ $machine->project_name }}" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">Propietario</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-user"></i></span>
              <input type="text" class="form-control"
                value="{{ optional($machine->owner)->name }} {{ optional($machine->owner)->last_name }}" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">Nombre máquina</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-desktop"></i></span>
              <input type="text" class="form-control" value="{{ $machine->machine_name }}" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">Nombre interno</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-chip"></i></span>
              <input type="text" class="form-control" value="{{ $machine->machine_internal_name }}" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">Sistema operativo</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-cog"></i></span>
              <input type="text" class="form-control" value="{{ $machine->operations_system }}" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">Versión kernel</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-data"></i></span>
              <input type="text" class="form-control" value="{{ $machine->kernel_version ?? '—' }}" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">Entorno</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-globe"></i></span>
              <input type="text" class="form-control" value="{{ $machine->environment }}" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">IP interna</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-network-chart"></i></span>
              <input type="text" class="form-control" value="{{ $machine->internal_ip }}" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">Alias IP</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-link"></i></span>
              <input type="text" class="form-control" value="{{ $machine->alias_ip ?? '—' }}" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">Alias 2 IP</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-link-alt"></i></span>
              <input type="text" class="form-control" value="{{ $machine->alias2_ip ?? '—' }}" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">Alias 3 IP</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-link-alt"></i></span>
              <input type="text" class="form-control" value="{{ $machine->alias3_ip ?? '—' }}" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">RAM (MB)</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-memory-card"></i></span>
              <input type="text" class="form-control" value="{{ $machine->ram_memory }}" disabled>
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold">Swap (MB)</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-transfer"></i></span>
              <input type="text" class="form-control" value="{{ $machine->swap_memory }}" disabled>
            </div>
          </div>
          <div class="col-md-12 mb-4">
            <label class="form-label fw-semibold">Último parche de seguridad</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-shield-quarter"></i></span>
              <input type="text" class="form-control" value="{{ $machine->latest_security_patch ?? '—' }}"
                disabled>
            </div>
          </div>
          <div class="col-md-12 mb-4">
            <label class="form-label fw-semibold">Otras IPs</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-list-ul"></i></span>
              <textarea class="form-control" rows="2" disabled>{{ $machine->other_ips ?? '—' }}</textarea>
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
