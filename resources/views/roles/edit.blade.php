<div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('roles.update', $role) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        <i class="bx bx-edit text-primary"></i> Editar Rol
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="bx bx-tag me-1 text-primary"></i> Nombre del Rol
                        </label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $role->name) }}" required>
                    </div>
                    <h6 class="fw-bold text-primary mb-3 d-flex align-items-center gap-1">
                        <i class="bx bx-lock-alt"></i> Permisos
                    </h6>
                    <div class="row">
                        @foreach ($permissions as $permission)
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm_edit_{{ $permission->id }}_{{ $role->id }}" {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                    <label class="form-check-label small"
                                        for="perm_edit_{{ $permission->id }}_{{ $role->id }}">
                                        {{ $permission->description ?? $permission->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i>Actualizar Rol</button>
                </div>
            </form>
        </div>
    </div>
</div>
