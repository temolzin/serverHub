<div class="modal fade" id="createOwnerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Crear propietario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('owners.store') }}" method="POST"
        onsubmit="this.querySelector('button[type=submit]').disabled=true;">
        @csrf
        <div class="modal-body">
          @if (session('error'))
            <div class="alert alert-danger text-center mb-4">
              {{ session('error') }}
            </div>
          @endif
          <div class="mb-4">
            <label class="form-label">Nombre</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-user"></i>
              </span>
              <input type="text" name="name" class="form-control" placeholder="Juan" maxlength="20" required>
            </div>
            <small class="text-muted">Máximo 20 caracteres</small>
          </div>
          <div class="mb-4">
            <label class="form-label">Apellido</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-id-card"></i>
              </span>
              <input type="text" name="last_name" class="form-control" placeholder="Pérez" maxlength="50" required>
            </div>
            <small class="text-muted">Máximo 50 caracteres</small>
          </div>
          <div class="mb-4">
            <label class="form-label">Email</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-envelope"></i>
              </span>
              <input type="email" name="email" class="form-control" placeholder="correo@empresa.com" required
                id="owner-email-input" onblur="checkOwnerEmailExists(this)">
            </div>
          </div>
          <div id="owner-email-error" class="text-danger text-center mb-2" style="display:none;"></div>
          <div class="mb-4">
            <label class="form-label">Teléfono</label>
            <div class="input-group input-group-merge">
              <span class="input-group-text">
                <i class="bx bx-phone"></i>
              </span>
              <input type="text" name="number_phone" class="form-control" placeholder="5512345678" maxlength="10"
                pattern="[0-9]*" inputmode="numeric" required>
            </div>
            <small class="text-muted">Solo números (10 dígitos)</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal" onclick="this.form.reset();">
            Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            Guardar
          </button>
        </div>
      </form>
      <script>
        function checkOwnerEmailExists(input) {
          const email = input.value.trim();
          const errorDiv = document.getElementById('owner-email-error');
          if (!email) {
            errorDiv.style.display = 'none';
            return;
          }
          fetch(`/owners/check-email?email=${encodeURIComponent(email)}`)
            .then(res => res.json())
            .then(data => {
              if (data.exists) {
                errorDiv.textContent = 'Ya existe un propietario con ese correo.';
                errorDiv.style.display = 'block';
                input.setCustomValidity('Ya existe un propietario con ese correo.');
              } else {
                errorDiv.style.display = 'none';
                input.setCustomValidity('');
              }
            })
            .catch(() => {
              errorDiv.style.display = 'none';
              input.setCustomValidity('');
            });
        }
      </script>
    </div>
  </div>
</div>
