<div class="modal fade" id="editGcpMachineModal{{ $machine->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          Editar máquina GCP
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('gcp-machines.update', $machine) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body text-start">
          <div class="row">
            <div class="col-md-6 mb-4">
              <label class="form-label">Propietario (obligatorio)</label>
              <select name="owner_id" class="form-select gcp-searchable-select" required>
                @foreach ($owners as $owner)
                  <option value="{{ $owner->id }}" {{ $machine->owner_id == $owner->id ? 'selected' : '' }}>
                    {{ $owner->name }} {{ $owner->last_name }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Nombre del proyecto (obligatorio)</label>
              <input type="text" name="project_name" class="form-control" placeholder="Ej: gcp-prod-finanzas"
                value="{{ filled($machine->project_name) ? $machine->project_name : 'N/A' }}" required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Entorno (obligatorio)</label>
              <input type="text" name="environment" class="form-control" placeholder="Ej: Produccion, QA, Desarrollo"
                value="{{ filled($machine->environment) ? $machine->environment : 'N/A' }}" required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Estado (obligatorio)</label>
              <select name="state" class="form-select" required>
                <option value="poweredOn" {{ !$machine->isPoweredOff() ? 'selected' : '' }}>
                  Encendido
                </option>
                <option value="poweredOff" {{ $machine->isPoweredOff() ? 'selected' : '' }}>
                  Apagado
                </option>
              </select>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Nombre máquina (obligatorio)</label>
              <input type="text" name="machine_name" class="form-control" placeholder="Ej: vm-app-prod-01"
                value="{{ filled($machine->machine_name) ? $machine->machine_name : 'N/A' }}" required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Nombre interno (obligatorio)</label>
              <input type="text" name="machine_internal_name" class="form-control" placeholder="Ej: srv-app-01"
                value="{{ filled($machine->machine_internal_name) ? $machine->machine_internal_name : 'N/A' }}"
                required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Sistema operativo (obligatorio)</label>
              <input type="text" name="operations_system" class="form-control" placeholder="Ej: Ubuntu 22.04 LTS"
                value="{{ filled($machine->operations_system) ? $machine->operations_system : 'N/A' }}" required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">IP interna (obligatorio)</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bx bx-network-chart text-primary"></i></span>
                <input type="text" name="internal_ip" class="form-control ip-check" placeholder="Ej: 10.10.10.15"
                  value="{{ filled($machine->internal_ip) ? $machine->internal_ip : 'N/A' }}" required
                  data-exclude="{{ $machine->id }}" data-error-target="edit-gcp-ip-error-{{ $machine->id }}">
              </div>
            </div>
            <div class="col-md-4 mb-4">
              <label class="form-label">Alias IP</label>
              <input type="text" name="alias_ip" class="form-control" placeholder="Ej: api.empresa.com"
                value="{{ filled($machine->alias_ip) ? $machine->alias_ip : 'N/A' }}">
            </div>
            <div class="col-md-4 mb-4">
              <label class="form-label">Alias 2 IP</label>
              <input type="text" name="alias2_ip" class="form-control" placeholder="Ej: backend.empresa.com"
                value="{{ filled($machine->alias2_ip) ? $machine->alias2_ip : 'N/A' }}">
            </div>
            <div class="col-md-4 mb-4">
              <label class="form-label">Alias 3 IP</label>
              <input type="text" name="alias3_ip" class="form-control" placeholder="Ej: admin.empresa.com"
                value="{{ filled($machine->alias3_ip) ? $machine->alias3_ip : 'N/A' }}">
            </div>
            <div class="col-md-12 mb-4">
              <label class="form-label">Kernel</label>
              <input type="text" name="kernel_version" class="form-control" placeholder="Ej: 5.15.0-94-generic"
                value="{{ filled($machine->kernel_version) ? $machine->kernel_version : 'N/A' }}">
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Ultimo parche</label>
              <input type="date" name="latest_security_patch" class="form-control"
                value="{{ $machine->latest_security_patch }}">
            </div>
            <div class="col-md-3 mb-4">
              <label class="form-label">RAM (MB) (obligatorio)</label>
              <input type="number" name="ram_memory" class="form-control" placeholder="Ej: 4096"
                value="{{ $machine->ram_memory ?? 0 }}" required>
            </div>
            <div class="col-md-3 mb-4">
              <label class="form-label">Swap (MB) (obligatorio)</label>
              <input type="number" name="swap_memory" class="form-control" placeholder="Ej: 2048"
                value="{{ $machine->swap_memory ?? 0 }}" required>
            </div>
            <div class="col-md-12 mb-4">
              <label class="form-label">Otras IPs</label>
              <textarea name="other_ips" class="form-control" rows="2" placeholder="Ej: 10.10.10.20, 10.10.10.21">{{ filled($machine->other_ips) ? $machine->other_ips : 'N/A' }}</textarea>
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
