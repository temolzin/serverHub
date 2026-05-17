@extends('layouts/contentNavbarLayout')

@section('title', 'Historial de cambios')

@section('content')
    <style>
        .text-purple { color: #696cff; }
        .btn-purple { background: rgba(105,108,255,0.1); color: #696cff; border: none; transition: 0.2s; }
        .btn-purple:hover { background: #696cff; color: #fff; }
        .badge-update { background-color: #ff9f43; color: #fff; }
        .badge-delete { background-color: #ea5455; color: #fff; }
        .json-box { background: #f8f9fa; padding: 15px; margin: 0; font-size: 13px; font-family: "Courier New", monospace; max-height: 500px; overflow: auto; border-radius: 0 0 10px 10px; white-space: pre; }
        .json-before { border-top: 4px solid #dc3545; }
        .json-after { border-top: 4px solid #28c76f; }
        .bg-danger-soft { background: rgba(220,53,69,0.12); color: #dc3545; }
        .bg-success-soft { background: rgba(40,199,111,0.12); color: #28c76f; }
    </style>

    <div class="card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <h5 class="mb-0"><i class="bx bx-history text-purple me-2"></i>Historial de cambios</h5>
        </div>
        <div class="card-body">
            <div>
                <table class="table table-hover align-middle w-100 datatable">
                    <thead class="table-light">
                        <tr>
                            <th>Usuario</th>
                            <th>Módulo</th>
                            <th>Acción</th>
                            <th>Registro</th>
                            <th>Fecha</th>
                            <th class="text-center no-export">Ver</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $log)
                            <tr>
                                <td>
                                    <strong>{{ $log->user->name ?? 'Usuario eliminado' }}</strong><br>
                                    <small class="text-muted">ID: {{ $log->alter_by }}</small>
                                </td>
                                <td><span class="fw-semibold text-uppercase">{{ $log->module_label }}</span></td>
                                <td>
                                    @if ($log->action === 'create')
                                        <span class="badge badge-create">CREACIÓN</span>
                                    @elseif ($log->action === 'update')
                                        <span class="badge badge-update">ACTUALIZACIÓN</span>
                                    @elseif ($log->action === 'delete')
                                        <span class="badge badge-delete">ELIMINACIÓN</span>
                                    @endif
                                </td>
                                <td>#{{ $log->record_id }}</td>
                                <td>
                                    {{ $log->created_at->format('d/m/Y') }}<br>
                                    <small class="text-muted">{{ $log->created_at->format('h:i A') }}</small>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-icon btn-purple btn-modal" data-url="{{ route('audit_logs.modal', ['audit_log' => $log->id, 'type' => 'show']) }}"><i class="bx bx-show"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modalContainer"></div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-modal');
            if (!btn) return;
            
            e.preventDefault();
            const url = btn.dataset.url;
            
            fetch(url)
                .then(r => {
                    if (!r.ok) throw new Error('Error loading modal');
                    return r.text();
                })
                .then(html => {
                    document.getElementById('modalContainer').innerHTML = html;
                    const modalEl = document.getElementById('modalContainer').querySelector('.modal');
                    if (modalEl) {
                        $(modalEl).modal('show');
                        
                        modalEl.addEventListener('hidden.bs.modal', function() {
                            modalEl.remove();
                        });
                    }
                })
                .catch(err => console.error(err));
        });
    });
</script>
@endpush
