@extends('layouts/contentNavbarLayout')

@section('title', 'Historial de Apagados')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">
                <i class="bx bx-history text-primary me-2"></i>Historial de apagado
            </h4>
        </div>
        <form method="GET" class="p-3">
            <div class="d-flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Buscar por nombre, motivo o usuario...">
                <button class="btn btn-primary"><i class="bx bx-search"></i></button>
            </div>
        </form>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tipo</th>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acción</th>
                        <th>Motivo</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>
                                <span class="fw-semibold text-body">
                                    {{ $log->type_label }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-semibold text-body">
                                    {{ $log->resource_id }}
                                </span>
                            </td>
                            <td>{{ $log->resource_name }}</td>
                            <td>
                                <span class="badge {{ $log->action === 'off' ? 'bg-label-danger' : 'bg-label-success' }}">
                                    {{ $log->action === 'off' ? 'APAGADO' : 'ENCENDIDO' }}
                                </span>
                            </td>
                            <td style="max-width: 250px; white-space: normal;">
                                {{ $log->motive }}
                            </td>
                            <td>
                                {{ $log->user->name ?? 'N/A' }}
                            </td>
                            <td>
                                {{ $log->created_at->format('d/m/Y h:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                No hay historial disponible
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $logs->links() }}
        </div>
    </div>
@endsection
