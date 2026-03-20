<div class="modal fade" id="createStorageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('storages.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center gap-2">
                        <i class="bx bx-hdd text-primary"></i>Crear almacenamiento
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-server me-1 text-primary"></i>Nombre del servidor
                            </label>
                            <input type="text" name="hostname" class="form-control @error('hostname') es invalido @enderror" placeholder="Ej: storage-prod-01" maxlength="50" required>
                            @error('hostname')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-network-chart me-1 text-primary"></i>IP de Datos (Data IP)
                            </label>
                            <input type="text" name="data_ip" class="form-control @error('data_ip') is-invalid @enderror" placeholder="Ej: 10.10.10.5" pattern="^((25[0-5]|2[0-4][0-9]|1?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|1?[0-9][0-9]?)$">
                            @error('data_ip')
                                <div class="invalid-feedback">
                                    La IP no tiene una estructura válida.
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-chip me-1 text-primary"></i>Plataforma
                            </label>
                            <input type="text" name="platform" class="form-control @error('platform') is-invalid @enderror" placeholder="Ej: NetApp, Dell EMC" maxlength="50">
                            @error('platform')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-desktop me-1 text-primary"></i>Nombre del Sistema Operativo
                            </label>
                            <input type="text" name="os_name" class="form-control" placeholder="Ej: ONTAP 9" maxlength="50">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-code-block me-1 text-primary"></i>Sistema Operativo Interno
                            </label>
                            <input type="text" name="os_internal" class="form-control" placeholder="Ej: 9.12.1P3" maxlength="50">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-layer me-1 text-primary"></i>Sistema Operativo (General)
                            </label>
                            <input type="text" name="operations_system" class="form-control" placeholder="Ej: Linux Storage OS" maxlength="50">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-lan me-1 text-primary"></i>IP Interna
                            </label>
                            <input type="text" name="internal_ip" class="form-control @error('internal_ip') is-invalid @enderror" placeholder="Ej: 192.168.100.20" pattern="^((25[0-5]|2[0-4][0-9]|1?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|1?[0-9][0-9]?)$">
                            @error('internal_ip')
                                <div class="invalid-feedback">
                                    La IP no tiene una estructura válida.
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-globe me-1 text-primary"></i>Entorno
                            </label>
                            <input type="text" name="environment" class="form-control" placeholder="Ej: Producción, DR" maxlength="20">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-building-house me-1 text-primary"></i>Centro de datos
                            </label>
                            <input type="text" name="datacenter" class="form-control" placeholder="Ej: DC-Querétaro-01" maxlength="50">
                        </div>
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
