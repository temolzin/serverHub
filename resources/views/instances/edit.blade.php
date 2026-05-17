<div class="modal fade" id="editInstanceModal{{ $instance->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2"><i class="bx bx-edit text-primary"></i>Editar Instancia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('instances.update', $instance) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label"><i class="bx bx-server me-1 text-primary"></i>Servidor</label>
                        <select name="server_id" class="form-select server-searchable-select" data-placeholder="Buscar servidor..." required>
                            <option disabled>Seleccione un servidor</option>
                            @foreach ($servers as $server)
                                <option value="{{ $server->id }}" {{ $instance->server_id == $server->id ? 'selected' : '' }}>{{ $server->hostname_internal }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="bx bx-memory-card me-1 text-primary"></i>Memoria (GB)</label>
                        <input type="number" name="memory" class="form-control" placeholder="Ej: 8" value="{{ $instance->memory }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="bx bx-code me-1 text-primary"></i>Versión</label>
                        <input type="text" name="version" class="form-control" placeholder="Ej: SQL Server 2019" value="{{ $instance->version }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="bx bx-layer me-1 text-primary"></i>Edición</label>
                        <input type="text" name="edition" class="form-control" placeholder="Ej: Enterprise, Standard" value="{{ $instance->edition }}" required>
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
