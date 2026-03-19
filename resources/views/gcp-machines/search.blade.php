@forelse ($gcpMachines as $machine)
    @php($isOff = $machine->isPoweredOff())
    @php($applicationNames = $machine->applications->pluck('name')->filter()->implode(', '))
    <tr>
        <td>{{ $machine->id }}</td>
        <td>{{ filled($machine->project_name) ? $machine->project_name : 'N/A' }}</td>
        <td>{{ filled($machine->machine_name) ? $machine->machine_name : 'N/A' }}</td>
        <td>{{ strtoupper(filled($machine->environment) ? $machine->environment : 'N/A') }}</td>
        <td>
            <span class="badge {{ $isOff ? 'bg-label-danger' : 'bg-label-success' }}">
                {{ $machine->stateLabel() }}
            </span>
        </td>
        <td>{{ filled($machine->internal_ip) ? $machine->internal_ip : 'N/A' }}</td>
        <td class="text-end">
            <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#showGcpMachineModal{{ $machine->id }}">
                        <i class="bx bx-show me-1"></i> Ver
                    </button>
                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editGcpMachineModal{{ $machine->id }}">
                        <i class="bx bx-edit-alt me-1"></i> Editar
                    </button>
                    <form action="{{ route('gcp-machines.power-off', $machine) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item text-warning">
                            <i class="bx bx-power-off me-1"></i> Apagar
                        </button>
                    </form>
                    <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteGcpMachineModal{{ $machine->id }}">
                        <i class="bx bx-trash me-1"></i> Eliminar
                    </button>
                </div>
            </div>
            @include('gcp-machines.show', ['machine' => $machine])
            @include('gcp-machines.edit', ['machine' => $machine])
            @include('gcp-machines.delete', ['machine' => $machine])
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="8" class="text-center text-muted">No se encontraron registros</td>
    </tr>
@endforelse
