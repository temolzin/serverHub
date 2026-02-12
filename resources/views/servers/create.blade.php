<div class="modal fade" id="createServerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Crear servidor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('servers.store') }}" method="POST"
        onsubmit="this.querySelector('button[type=submit]').disabled=true;">
        @csrf
        <div class="modal-body">
          <div class="mb-4">
            <label class="form-label">Propietario</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-user"></i></span>
              <select name="owner_id" id="ownerSelect" class="form-select" required>
                <option value="">Seleccionar propietario</option>
                @foreach ($owners as $owner)
                  <option value="{{ $owner->id }}">
                    {{ $owner->name }} {{ $owner->last_name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Aplicación</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-layer"></i></span>
              <select name="type_application_id" id="typeApplicationSelect" class="form-select" required>
                <option value="">Seleccionar aplicación</option>
                @foreach ($typeApplications as $type)
                  <option value="{{ $type->id }}">
                    {{ $type->name_application }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">VM (VMware)</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-server"></i></span>
              <input type="text" name="vm_according_to_the_vmware" class="form-control" required>
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Estado</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-check-circle"></i></span>
              <select name="state" class="form-control">
                <option value="1">poweredOn</option>
                <option value="0">poweredOff</option>
              </select>
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">DNS</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-globe"></i></span>
              <input type="text" name="dns_name" class="form-control">
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">IP primaria</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-network-chart"></i>
              </span>
              <input type="text" name="primary_ip_address" class="form-control ip-check" placeholder="192.168.1.1"
                required data-error-target="server-ip-error">
            </div>
          </div>
          <div id="server-ip-error" class="text-danger text-center mb-2 d-none"></div>
          <div class="mb-4">
            <label class="form-label">Entorno</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-code-alt"></i></span>
              <input type="text" name="environment" class="form-control" required>
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Datacenter</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-building"></i></span>
              <input type="text" name="datacenter" class="form-control" required>
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Sistema operativo</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-chip"></i></span>
              <input type="text" name="os_according_to_the_vmware" class="form-control" required>
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Versión interna</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-code"></i></span>
              <input type="text" name="os_version_internal" class="form-control" required>
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Hostname interno</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-desktop"></i></span>
              <input type="text" name="hostname_internal" class="form-control" required>
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">IP usuario</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-user-pin"></i></span>
              <input type="text" name="ip_user" class="form-control">
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">IP monitoreo</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-line-chart"></i></span>
              <input type="text" name="ip_monitoring" class="form-control">
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Otras IPs</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-list-ul"></i></span>
              <textarea name="other_ips" class="form-control"></textarea>
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">RAM (MB)</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-memory-card"></i></span>
              <input type="number" name="ram_memory" class="form-control" required>
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Swap (MB)</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-data"></i></span>
              <input type="number" name="swap_memory" class="form-control" required>
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Último parche</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-calendar"></i></span>
              <input type="date" name="latest_security_patch" class="form-control">
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Comentarios</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-comment-detail"></i></span>
              <textarea name="comments" class="form-control"></textarea>
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
