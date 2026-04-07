<div class="modal fade" id="createUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        <i class="bx bx-user-plus text-primary"></i> Crear Usuario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bx bx-user me-1 text-primary"></i> Nombre
                                </label>
                                <input type="text" name="name" class="form-control" placeholder="Ejemplo: Juan" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bx bx-id-card me-1 text-primary"></i> Apellido
                                </label>
                                <input type="text" name="last_name" class="form-control" placeholder="Ejemplo: Perez" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bx bx-envelope me-1 text-primary"></i> Correo
                                </label>
                                <input type="email" name="email" class="form-control" placeholder="correo@empresa.com" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bx bx-lock me-1 text-primary"></i> Contraseña
                                </label>
                                <input type="password" name="password" class="form-control" placeholder="Genera algo seguro" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 shadow-sm h-100">
                                <label class="form-label fw-semibold">
                                    <i class="bx bx-user-check me-1 text-primary"></i> Rol
                                </label>
                                <select name="role" class="form-select" required>
                                    <option value="">Selecciona un rol</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted mt-2 d-block">
                                    El usuario heredará automáticamente los permisos del rol seleccionado.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> Guardar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
