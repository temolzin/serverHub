<div class="modal fade" id="logModal{{ $audit_log->id }}" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-purple"><i class="bx bx-detail"></i> Detalle del cambio</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-danger-soft fw-bold">Antes</div>
                            <pre class="json-box json-before"> {{ $audit_log->before_pretty }} </pre>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-success-soft fw-bold">Después</div>
                            <pre class="json-box json-after"> {{ $audit_log->after_pretty }} </pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>