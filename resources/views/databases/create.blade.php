<div class="modal fade" id="createDatabaseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="bx bx-data text-primary"></i>Crear base de datos
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('databases.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div  class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-data me-1 text-primary"></i>Nombre
                            </label>
                            <input type="text" name="name" class="form-control" placeholder="Ej: db_produccion" maxlength="50" required>
                        </div>
                        <div  class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-server me-1 text-primary"></i>Instancia
                            </label>
                            <select name="instance_id" class="form-select server-searchable-select" data-placeholder="Buscar instancia...">
                                <option value="" selected>Sin instancia (opcional)</option>
                                @foreach ($instances as $instance)
                                    <option value="{{ $instance->id }}">
                                        {{ $instance->server->hostname_internal }}
                                        {{ $instance->version }}
                                        ({{ $instance->edition }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-user me-1 text-primary"></i>Propietario
                            </label>
                            <select name="owner_id" class="form-select server-searchable-select" data-placeholder="Buscar propietario..." required>
                                <option value="" disabled selected>Selecciona un propietario</option>
                                @foreach ($owners as $owner)
                                    <option value="{{ $owner->id }}">{{ $owner->name }} {{ $owner->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-layer me-1 text-primary"></i>Tipo
                            </label>
                            <select name="type" class="form-select" required>
                                <option value="" disabled selected>Selecciona un tipo</option>
                                <option value="Oracle">Oracle</option>
                                <option value="MSSQL">MSSQL</option>
                                <option value="MySQL">MySQL</option>
                                <option value="DB2">DB2</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-plug me-1 text-primary"></i>Puerto
                            </label>
                            <input type="number" name="port" class="form-control" placeholder="Ej: 3306" min="1" max="65535">
                            <small class="text-muted">Rango permitido: 1 - 65535</small>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-code-alt me-1 text-primary"></i>Versión
                            </label>
                            <input type="text" name="version" class="form-control" placeholder="Ej: 8.0.36" maxlength="20">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-toggle-left me-1 text-primary"></i>Estado
                            </label>
                            <select name="status" class="form-select" required>
                                <option value="" disabled selected>Selecciona un estado</option>
                                <option value="active">Activo</option>
                                <option value="inactive">Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-calendar me-1 text-primary"></i>Última actualización
                            </label>
                            <input type="date" name="last_update" class="form-control">
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-message-square-detail me-1 text-primary"></i>Comentarios
                            </label>
                            <textarea name="comments" class="form-control" rows="3" maxlength="500" placeholder="Información adicional relevante"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Guardar </button>
                </div>
            </form>
        </div>
    </div>
</div>
