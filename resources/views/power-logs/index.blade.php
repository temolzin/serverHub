@extends('layouts/contentNavbarLayout')

@section('title', 'Historial de Apagados')

@section('content')
    <div class="card">
        <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <h4 class="mb-0">
                <i class="bx bx-history text-primary me-2"></i>Historial de apagado
            </h4>
        </div>
        <div class="card-body">
            <div>
                <table class="table align-middle w-100 datatable">
                    <thead class="table-light">
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
                        @foreach ($logs as $log)
                            <tr>
                                <td>{{ $log->id }}</td>
                                <td><span class="fw-semibold text-body">{{ $log->type_label }}</span></td>
                                <td><span class="fw-semibold text-body">{{ $log->resource_id }}</span></td>
                                <td>{{ $log->resource_name }}</td>
                                <td>
                                    <span class="badge {{ $log->action === 'off' ? 'bg-label-danger' : 'bg-label-success' }}">
                                        {{ $log->action === 'off' ? 'APAGADO' : 'ENCENDIDO' }}
                                    </span>
                                </td>
                                <td style="max-width: 250px; white-space: normal;">{{ $log->motive }}</td>
                                <td>{{ $log->user->name ?? 'N/A' }} {{$log->user->last_name ?? 'N/A' }}</td>
                                <td>{{ $log->created_at->format('d/m/Y h:i A') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
