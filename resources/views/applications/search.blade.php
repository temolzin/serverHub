@forelse ($applications as $application)
    <tr>
        <td>{{ $application->id }}</td>
        <td>{{ $application->name }}</td>
        <td>{{ $application->server?->hostname_internal ?? '-' }}</td>
        <td>{{ $application->gcpMachine?->machine_name ?? '-' }}</td>
        <td>{{ $application->owner?->name ?? '-' }} {{ $application->owner?->last_name ?? '' }}</td>
        <td>{{ $application->version ?? '-' }}</td>
        <td>
            @php
                $statusColors = [
                    'producción' => 'success',
                    'desarrollo' => 'info',
                    'inactivo' => 'secondary',
                ];
            @endphp
            <span class="badge bg-label-{{ $statusColors[$application->status] ?? 'secondary' }}">
                {{ ucfirst($application->status) }}
            </span>
        </td>
        <td>
            {{ $application->assigned_memory ?? '-' }} MB
        </td>
        <td class="text-end">
            <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#showApplicationModal{{ $application->id }}">
                        <i class="bx bx-show me-1"></i> Ver
                    </button>
                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editApplicationModal{{ $application->id }}">
                        <i class="bx bx-edit-alt me-1"></i> Editar
                    </button>
                    <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteApplicationModal{{ $application->id }}">
                        <i class="bx bx-trash me-1"></i> Eliminar
                    </button>
                </div>
            </div>
        </td>
    </tr>

    @empty
    <tr>
        <td colspan="9" class="text-center text-muted">No se encontraron registros</td>
    </tr>
@endforelse
