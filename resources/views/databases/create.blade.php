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
            <label class="form-label">Instance</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-layer"></i></span>
              <select name="instance_id" class="form-select" required>
                <option value="">Seleccionar instance</option>
                @foreach ($instances as $instance)
                  <option value="{{ $instance->id }}">
                    {{ $instance->server->hostname_internal }}
                    -
                    {{ $instance->version }}
                    ({{ $instance->edition }})
                  </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Propietario</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-user"></i></span>
              <select name="owner_id" class="form-select" required>
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
            <label class="form-label">Nombre</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-data"></i></span>
              <input type="text" name="name" class="form-control" required>
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Tipo</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-layer"></i></span>
              <input type="text" name="type" class="form-control" required>
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Puerto</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-network-chart"></i></span>
              <input type="number" name="port" class="form-control">
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Versión</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-code"></i></span>
              <input type="text" name="version" class="form-control">
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Estado</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-check-circle"></i></span>
              <input type="text" name="status" class="form-control">
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Última actualización</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text"><i class="bx bx-calendar"></i></span>
              <input type="date" name="last_update" class="form-control">
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
@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const serverSelect = document.getElementById('server-select');
      const instanceSelect = document.getElementById('instance-select');
      const ownerInput = document.getElementById('owner-id');
      if (!serverSelect || !instanceSelect || !ownerInput) return;
      // Ocultar instances al cargar
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
