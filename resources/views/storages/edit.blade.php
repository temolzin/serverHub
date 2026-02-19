<div class="modal fade" id="editStorageModal{{ $storage->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <form action="{{ route('storages.update', $storage) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Editar Almacenamiento</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Hostname *</label>
              <input type="text" name="hostname" value="{{ $storage->hostname }}" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">IP de Datos</label>
              <input type="text" name="data_ip" value="{{ $storage->data_ip }}" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Plataforma</label>
              <input type="text" name="platform" value="{{ $storage->platform }}" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Nombre del Sistema Operativo</label>
              <input type="text" name="os_name" value="{{ $storage->os_name }}" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Sistema Operativo Interno</label>
              <input type="text" name="os_internal" value="{{ $storage->os_internal }}" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Sistema Operativo (General)</label>
              <input type="text" name="operations_system" value="{{ $storage->operations_system }}"
                class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">IP Interna</label>
              <input type="text" name="internal_ip" value="{{ $storage->internal_ip }}" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Entorno</label>
              <input type="text" name="environment" value="{{ $storage->environment }}" class="form-control">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Datacenter</label>
              <input type="text" name="datacenter" value="{{ $storage->datacenter }}" class="form-control">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-label-secondary" data-bs-dismiss="modal">
            Cancelar
          </button>
          <button class="btn btn-primary">
            Actualizar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
