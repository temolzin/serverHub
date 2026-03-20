<div class="modal fade" id="editTypeApplicationModal{{ $type->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="bx bx-edit text-primary"></i>Editar tipo de aplicación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('type-applications.update', $type) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body text-start">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="bx bx-category me-1 text-primary"></i>Tipo de aplicación
                        </label>
                        <select name="type_application" class="form-control" required>
                            <option value="">Selecciona un tipo</option>
                            <option value="BD">BD</option>
                            <option value="Aplicacion">Aplicación</option>
                            <option value="Appliance">Aparato</option>
                            <option value="Unknown">Desconocido</option>
                        </select>
                        <small class="text-muted">Selecciona el tipo de aplicación</small>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="bx bx-data me-1 text-primary"></i>Nombre de la aplicación
                        </label>
                        <div class="input-group">
                            <input type="text" name="name_application" class="form-control" placeholder="Ej: Sistema de Nómina Corporativo" maxlength="100" value="{{ $type->name_application }}" required>
                        </div>
                        <small class="text-muted">Máximo 100 caracteres</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal"> Cancelar </button>
                    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Actualizar </button>
                </div>
            </form>
        </div>
    </div>
</div>
