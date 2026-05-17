<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        <i class="bx bx-edit text-primary"></i> Editar Usuario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-12">
                            <div class="mb-2">
                                <label class="form-label">
                                    <i class="bx bx-user me-1 text-primary"></i> Nombre
                                </label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">
                                    <i class="bx bx-id-card me-1 text-primary"></i> Apellido
                                </label>
                                <input type="text" name="last_name" class="form-control"
                                    value="{{ old('last_name', $user->last_name) }}" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">
                                    <i class="bx bx-envelope me-1 text-primary"></i> Correo
                                </label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">
                                    <i class="bx bx-lock me-1 text-primary"></i> Nueva Contraseña
                                </label>
                                <input type="password" name="password" class="form-control" placeholder="********">
                            </div>
                            <div class="mb-2">
                                <label class="form-label fw-semibold">
                                    <i class="bx bx-user-check me-1 text-primary"></i> Rol
                                </label>
                                <select name="role" class="form-select" required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}"
                                            {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Actualizar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>
