<div class="modal fade" id="powerOnServerModal{{ $server->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('servers.power-on', $server) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bx bx-power-off text-success me-2"></i>Motivo de encendido
                    </h5>
                </div>
                <div class="modal-body">
                    <textarea name="motive" class="form-control" required minlength="5" placeholder="Escribe el motivo del encendido..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Cancelar </button>
                    <button type="submit" class="btn btn-success"> Encender </button>
                </div>
            </form>
        </div>
    </div>
</div>