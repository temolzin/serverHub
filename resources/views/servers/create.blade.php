<div class="modal fade" id="createServerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bx bx-server me-2 text-primary"></i>
          Crear servidor
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('servers.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Propietario</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-user"></i>
              </span>
              <input type="text" class="form-control" list="ownersList" placeholder="Buscar propietario..."
                id="ownerSearch" required>
            </div>
            <datalist id="ownersList">
              @foreach ($owners as $owner)
                <option data-id="{{ $owner->id }}" value="{{ $owner->name }} {{ $owner->last_name }}">
                </option>
              @endforeach
            </datalist>
            <input type="hidden" name="owner_id" id="owner_id">
          </div>
          <div class="mb-3">
            <label class="form-label">Aplicación</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-layer"></i>
              </span>
              <input type="text" class="form-control" list="typeApplicationList" placeholder="Buscar aplicación..."
                id="typeAppSearch" required>
            </div>
            <datalist id="typeApplicationList">
              @foreach ($typeApplications as $type)
                <option data-id="{{ $type->id }}" value="{{ $type->name_application }}">
                </option>
              @endforeach
            </datalist>
            <input type="hidden" name="type_application_id" id="type_application_id">
          </div>
          <div class="mb-3">
            <label class="form-label">VM según VMware</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-server"></i>
              </span>
              <input type="text" name="vm_according_to_the_vmware" class="form-control" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Estado</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-power-off"></i>
              </span>
              <select name="state" class="form-control">
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
              </select>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">DNS</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-globe"></i>
              </span>
              <input type="text" name="dns_name" class="form-control">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">IP primaria</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-network-chart"></i>
              </span>
              <input type="text" name="primary_ip_address" class="form-control ip-check"
                data-error-target="server-ip-error" required>
            </div>
            <div id="server-ip-error" class="text-danger mt-1 d-none"></div>
          </div>
          <div class="mb-3">
            <label class="form-label">Entorno</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-code-alt"></i>
              </span>
              <input type="text" name="environment" class="form-control" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Datacenter</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-building"></i>
              </span>
              <input type="text" name="datacenter" class="form-control" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Sistema operativo (VMware)</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-chip"></i>
              </span>
              <input type="text" name="os_according_to_the_vmware" class="form-control" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Versión interna</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-code"></i>
              </span>
              <input type="text" name="os_version_internal" class="form-control" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Hostname interno</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-desktop"></i>
              </span>
              <input type="text" name="hostname_internal" class="form-control" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">IP usuario</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-network-chart"></i>
              </span>
              <input type="text" name="ip_user" class="form-control">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">IP monitoreo</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-line-chart"></i>
              </span>
              <input type="text" name="ip_monitoring" class="form-control">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Otras IPs</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-list-ul"></i>
              </span>
              <textarea name="other_ips" class="form-control"></textarea>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">RAM (MB)</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-memory-card"></i>
              </span>
              <input type="number" name="ram_memory" class="form-control" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Swap (MB)</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-data"></i>
              </span>
              <input type="number" name="swap_memory" class="form-control" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Último parche de seguridad</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-calendar"></i>
              </span>
              <input type="date" name="latest_security_patch" class="form-control">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Comentarios</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-comment-detail"></i>
              </span>
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
