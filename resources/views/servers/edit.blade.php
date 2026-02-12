<div class="modal fade" id="editServerModal{{ $server->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bx bx-edit-alt me-2 text-warning"></i>
          Editar servidor
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('servers.update', $server) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Propietario</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-user"></i>
              </span>
              <select name="owner_id" class="form-control" required>
                @foreach ($owners as $owner)
                  <option value="{{ $owner->id }}" {{ $server->owner_id == $owner->id ? 'selected' : '' }}>
                    {{ $owner->name }} {{ $owner->last_name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Aplicación</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-layer"></i>
              </span>
              <select name="type_application_id" class="form-control" required>
                @foreach ($typeApplications as $type)
                  <option value="{{ $type->id }}" {{ $server->type_application_id == $type->id ? 'selected' : '' }}>
                    {{ $type->name_application }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">VM según VMware</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-server"></i>
              </span>
              <input type="text" name="vm_according_to_the_vmware" class="form-control"
                value="{{ $server->vm_according_to_the_vmware }}" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">IP primaria</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-network-chart"></i>
              </span>
              <input type="text" name="primary_ip_address" class="form-control ip-check"
                value="{{ $server->primary_ip_address }}" data-exclude="{{ $server->id }}"
                data-error-target="edit-server-ip-error-{{ $server->id }}" required>
            </div>
            <div id="edit-server-ip-error-{{ $server->id }}" class="text-danger mt-1 d-none"></div>
          </div>
          <div class="mb-3">
            <label class="form-label">Entorno</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-code-alt"></i>
              </span>
              <input type="text" name="environment" class="form-control" value="{{ $server->environment }}"
                required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Datacenter</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-building"></i>
              </span>
              <input type="text" name="datacenter" class="form-control" value="{{ $server->datacenter }}" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Sistema operativo (VMware)</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-chip"></i>
              </span>
              <input type="text" name="os_according_to_the_vmware" class="form-control"
                value="{{ $server->os_according_to_the_vmware }}" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Versión interna</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-code"></i>
              </span>
              <input type="text" name="os_version_internal" class="form-control"
                value="{{ $server->os_version_internal }}" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Hostname interno</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-desktop"></i>
              </span>
              <input type="text" name="hostname_internal" class="form-control"
                value="{{ $server->hostname_internal }}" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">IP usuario</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-network-chart"></i>
              </span>
              <input type="text" name="ip_user" class="form-control" value="{{ $server->ip_user }}">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">IP monitoreo</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-line-chart"></i>
              </span>
              <input type="text" name="ip_monitoring" class="form-control" value="{{ $server->ip_monitoring }}">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Otras IPs</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-list-ul"></i>
              </span>
              <textarea name="other_ips" class="form-control">{{ $server->other_ips }}</textarea>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">RAM (MB)</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-memory-card"></i>
              </span>
              <input type="number" name="ram_memory" class="form-control" value="{{ $server->ram_memory }}"
                required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Swap (MB)</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-data"></i>
              </span>
              <input type="number" name="swap_memory" class="form-control" value="{{ $server->swap_memory }}"
                required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Último parche de seguridad</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-calendar"></i>
              </span>
              <input type="date" name="latest_security_patch" class="form-control"
                value="{{ $server->latest_security_patch }}">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Comentarios</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-comment-detail"></i>
              </span>
              <textarea name="comments" class="form-control">{{ $server->comments }}</textarea>
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
