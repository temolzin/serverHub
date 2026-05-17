<div class="modal fade" id="editDatabaseModal{{ $database->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2"><i class="bx bx-edit text-primary"></i>Editar base de datos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('databases.update', $database) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-layer me-1 text-primary"></i>Instancia</label>
                            <select name="instance_id" class="form-select server-searchable-select" data-placeholder="Buscar instancia...">
                                <option value="" {{ $database->instance_id ? '' : 'selected' }}>Sin instancia (opcional)</option>
                                @foreach ($instances as $instance)
                                    <option value="{{ $instance->id }}" {{ $database->instance_id == $instance->id ? 'selected' : '' }}>
                                        {{ $instance->server_hostname }} - {{ $instance->version }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-user me-1 text-primary"></i>Propietario</label>
                            <select name="owner_id" class="form-select server-searchable-select" data-placeholder="Buscar propietario..." required>
                                <option disabled>Seleccione un propietario</option>
                                @foreach ($owners as $owner)
                                    <option value="{{ $owner->id }}" {{ $database->owner_id == $owner->id ? 'selected' : '' }}>{{ $owner->name }} {{ $owner->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-data me-1 text-primary"></i>Nombre</label>
                            <input type="text" name="name" class="form-control" placeholder="Ej: db_produccion_principal" value="{{ $database->name }}" maxlength="50" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-category me-1 text-primary"></i>Tipo</label>
                            <select name="type" class="form-select" required>
                                <option value="">Selecciona un tipo</option>
                                <option value="Oracle" {{ $database->type == 'Oracle' ? 'selected' : '' }}>Oracle</option>
                                <option value="MSSQL" {{ $database->type == 'MSSQL' ? 'selected' : '' }}> MSSQL</option>
                                <option value="MySQL" {{ $database->type == 'MySQL' ? 'selected' : '' }}>MySQL</option>
                                <option value="DB2" {{ $database->type == 'DB2' ? 'selected' : '' }}>DB2</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-network-chart me-1 text-primary"></i>Puerto</label>
                            <input type="number" name="port" class="form-control" placeholder="Ej: 3306" value="{{ $database->port }}" min="1" max="65535">
                            <small class="text-muted">Rango permitido: 1 - 65535</small>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-code me-1 text-primary"></i>Versión</label>
                            <input type="text" name="version" class="form-control" placeholder="Ej: 8.0.36" value="{{ $database->version }}" maxlength="20">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Estado</label>
                            <select name="status" class="form-select" required>
                                <option value="active" {{ $database->status === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $database->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label"><i class="bx bx-calendar me-1 text-primary"></i>Última actualización</label>
                            <input type="date" name="last_update" class="form-control" value="{{ \Carbon\Carbon::parse($database->last_update)->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="form-label"><i class="bx bx-comment-detail me-1 text-primary"></i>Comentarios</label>
                            <textarea name="comments" class="form-control" rows="3" maxlength="500" placeholder="Información adicional sobre la base de datos">{{ $database->comments }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i>Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
