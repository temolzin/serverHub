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
                        @php
                            $baseType = class_basename($log->powerable_type);
                            $type = match ($baseType) {
                                'Server' => 'On-Premise',
                                'GcpMachine' => 'GCP Machine',
                                default => 'N/A',
                            };

                            $name = match ($baseType) {
                                'Server' => optional($log->powerable)->hostname_internal ?? 'N/A',
                                'GcpMachine' => optional($log->powerable)->machine_name ?? 'N/A',
                                default => 'N/A',
                            };

                            $resourceId = optional($log->powerable)->id ?? 'N/A';
                        @endphp
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>
                                <span class="fw-semibold text-body">
                                    {{ $type }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-semibold text-body">
                                    {{ $resourceId }}
                                </span>
                            </td>
                            <td>{{ $name }}</td>
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
