<div class="modal fade" id="editStorageModal{{ $storage->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="bx bx-edit text-primary"></i>Editar almacenamiento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('storages.update', $storage) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body text-start">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-server me-1 text-primary"></i>Nombre del servidor
                            </label>
                            <div class="input-group">
                                <input type="text" name="hostname" class="form-control" placeholder="Ej: storage-prod-01" value="{{ $storage->hostname }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-network-chart me-1 text-primary"></i>IP de datos
                            </label>
                            <div class="input-group">
                                <input type="text" name="data_ip" class="form-control" placeholder="Ej: 192.168.10.50" value="{{ $storage->data_ip }}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-chip me-1 text-primary"></i>Plataforma
                            </label>
                            <div class="input-group">
                                <input type="text" name="platform" class="form-control" placeholder="Ej: VMware / Físico" value="{{ $storage->platform }}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-desktop me-1 text-primary"></i>Nombre del sistema operativo
                            </label>
                            <div class="input-group">
                                <input type="text" name="os_name" class="form-control" placeholder="Ej: Red Hat Enterprise Linux" value="{{ $storage->os_name }}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-code-block me-1 text-primary"></i>Sistema operativo interno
                            </label>
                            <div class="input-group">
                                <input type="text" name="os_internal" class="form-control" placeholder="Ej: 8.8 (Ootpa)" value="{{ $storage->os_internal }}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-layer me-1 text-primary"></i>Sistema operativo (general)
                            </label>
                            <div class="input-group">
                                <input type="text" name="operations_system" class="form-control" placeholder="Ej: Linux / Windows" value="{{ $storage->operations_system }}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-lan me-1 text-primary"></i>IP interna
                            </label>
                            <div class="input-group">
                                <input type="text" name="internal_ip" class="form-control" placeholder="Ej: 10.10.10.15" value="{{ $storage->internal_ip }}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-globe me-1 text-primary"></i>Entorno
                            </label>
                            <div class="input-group">
                                <input type="text" name="environment" class="form-control" placeholder="Ej: Producción / Desarrollo" value="{{ $storage->environment }}">
                            </div>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-building-house me-1 text-primary"></i>Centro de datos
                            </label>
                            <div class="input-group">
                                <input type="text" name="datacenter" class="form-control" placeholder="Ej: DC-México-01" value="{{ $storage->datacenter }}">
                            </div>
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
