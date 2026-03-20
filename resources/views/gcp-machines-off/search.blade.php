@forelse ($gcpMachines as $machine)
    <tr>
        <td>{{ $machine->id }}</td>
        <td>{{ filled($machine->project_name) ? $machine->project_name : 'N/A' }}</td>
        <td>{{ filled($machine->machine_name) ? $machine->machine_name : 'N/A' }}</td>
        <td>{{ filled($machine->display_application_name) ? $machine->display_application_name : 'N/A' }}</td>
        <td>{{ filled($machine->operations_system) ? $machine->operations_system : 'N/A' }}</td>
        <td>
            <span class="badge {{ $machine->is_powered_off ? 'bg-label-danger' : 'bg-label-success' }}"> {{ $machine->display_state_label }}</span>
        </td>
        <td class="text-end">
            <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#showGcpOffMachineModal{{ $machine->id }}">
                        <i class="bx bx-show me-1"></i> Ver
                    </button>
                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editGcpOffMachineModal{{ $machine->id }}">
                        <i class="bx bx-edit-alt me-1"></i> Editar
                    </button>
                    <form action="{{ route('gcp-machines.power-on', $machine) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item text-success"><i class="bx bx-power-off me-1"></i> Encender</button>
                    </form>
                    <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteGcpOffMachineModal{{ $machine->id }}">
                        <i class="bx bx-trash me-1"></i> Eliminar
                    </button>
                </div>
            </div>
            @include('gcp-machines-off.show', ['machine' => $machine])
            @include('gcp-machines-off.edit', ['machine' => $machine])
            @include('gcp-machines-off.delete', ['machine' => $machine])
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="7" class="text-center text-muted">No se encontraron maquinas GCP apagadas</td>
    </tr>
@endforelse
