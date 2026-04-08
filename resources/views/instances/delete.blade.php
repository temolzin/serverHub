<div class="modal fade" id="deleteInstanceModal{{ $instance->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar Instancia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro que deseas eliminar la instancia
                <strong>{{ $instance->version }}</strong>
                del servidor
                <strong>{{ $instance->server_hostname }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('instances.destroy', $instance) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
