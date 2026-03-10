<div class="modal fade" id="createUserModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        <i class="bx bx-user-plus text-primary"></i>
                        Crear Usuario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                <i class="bx bx-user me-1 text-primary"></i> Nombre
                                </label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Ejemplo: Juan Perez" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                <i class="bx bx-envelope me-1 text-primary"></i> Correo
                                </label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="Ejemplo: correo@empresa.com" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                <i class="bx bx-lock me-1 text-primary"></i> Contraseña
                                </label>
                                <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="············" required>
                                <small class="text-muted">La contraseña debe tener al menos 6 caracteres.</small>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="bx bx-lock-alt me-1"></i> Permisos
                            </h6>
                            <div class="row">
                                @foreach ($permissions as $group => $groupPermissions)
                                <div class="col-6 mb-3">
                                    <small class="fw-bold text-secondary text-uppercase">{{ $group }}</small>
                                    @foreach ($groupPermissions as $permission)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]"
                                        value="{{ $permission->name }}"
                                        {{ is_array(old('permissions')) && in_array($permission->name, old('permissions')) ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                        {{ $permission->description }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> Guardar Usuario
                </button>
                </div>
            </form>
        </div>
    </div>
</div>
