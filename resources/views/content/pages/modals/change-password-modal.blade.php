<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('profile.password.update') }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bx bx-lock-alt me-2 text-primary"></i>Actualizar contraseña
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bx bx-shield-quarter me-1 text-primary"></i>Contraseña actual
                        </label>
                        <div class="input-group">
                            <input type="password" name="current_password" class="form-control" required>
                            <span class="input-group-text" style="cursor:pointer;" onclick="togglePassword(this)">
                                <i class="bx bx-hide"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bx bx-lock-open-alt me-1 text-primary"></i>Nueva contraseña
                        </label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" required>
                            <span class="input-group-text" style="cursor:pointer;" onclick="togglePassword(this)">
                                <i class="bx bx-hide"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bx bx-check-shield me-1 text-primary"></i>Confirmar contraseña
                        </label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" class="form-control" required>
                            <span class="input-group-text" style="cursor:pointer;" onclick="togglePassword(this)">
                                <i class="bx bx-hide"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i> Cancelar
                    </button>
                    <button class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
