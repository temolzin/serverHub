<div class="modal fade" id="createDatabaseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Crear base de datos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('databases.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-4">
            <label class="form-label">Instancia</label>
            <select name="instance_id" class="form-select" required>
              <option value="" disabled selected>Selecciona una instancia</option>
              @foreach ($instances as $instance)
                <option value="{{ $instance->id }}">
                  {{ $instance->server->hostname_internal }}
                  {{ $instance->version }}
                  ({{ $instance->edition }})
                </option>
              @endforeach
            </select>
          </div>
          <div class="mb-4">
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
          <div class="mb-4">
            <label class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" placeholder="Ej: db_produccion" required>
          </div>
          <div class="mb-4">
            <label class="form-label">Tipo</label>
            <input type="text" name="type" class="form-control" placeholder="Ej: MySQL, PostgreSQL, SQL Server"
              required>
          </div>
          <div class="mb-4">
            <label class="form-label">Puerto</label>
            <input type="number" name="port" class="form-control" placeholder="Ej: 3306">
          </div>
          <div class="mb-4">
            <label class="form-label">Versión</label>
            <input type="text" name="version" class="form-control" placeholder="Ej: 8.0.36">
          </div>
          <div class="mb-4">
            <label class="form-label">Estado</label>
            <input type="text" name="status" class="form-control" placeholder="Ej: Activa, En mantenimiento">
          </div>
          <div class="mb-4">
            <label class="form-label">Última actualización</label>
            <input type="date" name="last_update" class="form-control">
          </div>
          <div class="mb-4">
            <label class="form-label">Comentarios</label>
            <textarea name="comments" class="form-control" placeholder="Información adicional relevante"></textarea>
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
@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const serverSelect = document.getElementById('server-select');
      const instanceSelect = document.getElementById('instance-select');
      const ownerInput = document.getElementById('owner-id');
      if (!serverSelect || !instanceSelect || !ownerInput) return;
      Array.from(instanceSelect.options).forEach(option => {
        if (option.value) option.style.display = 'none';
      });
      serverSelect.addEventListener('change', function() {
        const serverId = this.value;
        const selectedOption = this.options[this.selectedIndex];
        const ownerId = selectedOption?.dataset.owner;
        ownerInput.value = ownerId ?? '';
        Array.from(instanceSelect.options).forEach(option => {
          if (!option.value) return;
          if (option.dataset.server == serverId) {
            option.style.display = 'block';
          } else {
            option.style.display = 'none';
          }
        });
        instanceSelect.value = '';
      });
    });
  </script>
@endpush
