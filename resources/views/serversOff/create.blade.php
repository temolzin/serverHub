<div class="modal fade" id="createServerOffModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bx bx-server text-primary me-2"></i>
          Crear servidor apagado
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('servers-off.store') }}" method="POST"
        onsubmit="this.querySelector('button[type=submit]').disabled=true;">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">VM</label>
              <input type="text" name="vm_according_to_the_vmware" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Nombre DNS</label>
              <input type="text" name="dns_name" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Centro de datos</label>
              <input type="text" name="datacenter" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Estado</label>
              <select name="state" class="form-select" required>
                <option value="poweredOff" selected>poweredOff</option>
                <option value="poweredOn">poweredOn</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">SO según configuración (os_version_internal)</label>
              <input type="text" name="os_version_internal" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">SO según VMware</label>
              <input type="text" name="os_according_to_the_vmware" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">IP primaria (opcional)</label>
              <input type="text" name="primary_ip_address" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Último parche de seguridad</label>
              <input type="date" name="latest_security_patch" class="form-control">
            </div>
            <div class="col-12 mb-3">
              <label class="form-label">Comentarios</label>
              <textarea name="comments" rows="3" class="form-control"></textarea>
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
