<div class="modal fade" id="createInstanceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('instances.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        <i class="bx bx-server text-primary"></i>Crear instancia
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bx bx-hdd me-1 text-primary"></i>Servidor
                        </label>
                        <select name="server_id" class="form-select" required>
                            <option value="" disabled selected>Selecciona un servidor</option>
                            @foreach ($servers as $server)
                                <option value="{{ $server->id }}">
                                    {{ $server->hostname_internal }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bx bx-memory-card me-1 text-primary"></i>Memoria (MB)
                        </label>
                        <input type="number" name="memory" class="form-control" min="1024" max="32768" placeholder="Ej: 8192" required>
                        <small class="text-muted"> Rango permitido: 1024 MB - 32768 MB </small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bx bx-code-alt me-1 text-primary"></i>Versión
                        </label>
                        <input type="text" name="version" class="form-control" placeholder="Ej: SQL Server 2022" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bx bx-layer me-1 text-primary"></i>Edición
                        </label>
                        <input type="text" name="edition" class="form-control" placeholder="Ej: Standard, Enterprise">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal"> Cancelar </button>
                    <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Guardar </button>
                </div>
            </form>
        </div>
    </div>
</div>
