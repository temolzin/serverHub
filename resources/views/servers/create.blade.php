<div class="modal fade" id="createServerModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bx bx-server text-primary me-2"></i>
          Crear servidor
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('servers.store') }}" method="POST"
        onsubmit="this.querySelector('button[type=submit]').disabled=true;">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-4">
              <label class="form-label">Propietario</label>
              <select name="owner_id" class="form-select" required>
                <option value="" disabled selected>Selecciona un propietario</option>
                @foreach ($owners as $owner)
                  <option value="{{ $owner->id }}">
                    {{ $owner->name }} {{ $owner->last_name }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Aplicación</label>
              <select name="type_application_id" class="form-select" required>
                <option value="" disabled selected>Selecciona una aplicación</option>
                @foreach ($typeApplications as $type)
                  <option value="{{ $type->id }}">
                    {{ $type->name_application }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">VM (VMware)</label>
              <input type="text" name="vm_according_to_the_vmware" class="form-control"
                placeholder="Ej: vm-app-prod-01" required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Estado</label>
              <select name="state" class="form-select">
                <option value="1">poweredOn</option>
                <option value="0">poweredOff</option>
              </select>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">DNS</label>
              <input type="text" name="dns_name" class="form-control" placeholder="Ej: servidor.empresa.com">
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">IP primaria</label>
              <input type="text" name="primary_ip_address" class="form-control ip-check"
                placeholder="Ej: 192.168.1.10" required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Entorno</label>
              <input type="text" name="environment" class="form-control" placeholder="Ej: Producción, QA" required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Datacenter</label>
              <input type="text" name="datacenter" class="form-control" placeholder="Ej: DC-MX-01" required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Sistema operativo</label>
              <input type="text" name="os_according_to_the_vmware" class="form-control"
                placeholder="Ej: Windows Server 2019" required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Versión interna</label>
              <input type="text" name="os_version_internal" class="form-control" placeholder="Ej: 10.0.17763"
                required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Hostname interno</label>
              <input type="text" name="hostname_internal" class="form-control" placeholder="Ej: srv-prod-01"
                required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">RAM (MB)</label>
              <input type="number" name="ram_memory" class="form-control" placeholder="Ej: 16384" required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Swap (MB)</label>
              <input type="number" name="swap_memory" class="form-control" placeholder="Ej: 4096" required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Último parche</label>
              <input type="date" name="latest_security_patch" class="form-control">
            </div>
            <div class="col-md-12 mb-4">
              <label class="form-label">Otras IPs</label>
              <textarea name="other_ips" rows="2" class="form-control" placeholder="IPs adicionales separadas por coma"></textarea>
            </div>
            <div class="col-md-12 mb-4">
              <label class="form-label">Comentarios</label>
              <textarea name="comments" rows="3" class="form-control" placeholder="Información adicional relevante"></textarea>
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
