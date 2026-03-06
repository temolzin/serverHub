@forelse ($gcpMachines as $machine)
    @php($isOff = $machine->isPoweredOff())
    <tr>
        <td>{{ $machine->id }}</td>
        <td>{{ $machine->uuid ?? '' }}</td>
        <td>{{ filled($machine->project_name) ? $machine->project_name : 'N/A' }}</td>
        <td>{{ filled($machine->machine_name) ? $machine->machine_name : 'N/A' }}</td>
        <td>{{ filled(optional($machine->application)->name) ? optional($machine->application)->name : 'N/A' }}</td>
        <td>{{ filled($machine->operations_system) ? $machine->operations_system : 'N/A' }}</td>
        <td>
        <span class="badge {{ $isOff ? 'bg-label-danger' : 'bg-label-success' }}">
            {{ $isOff ? 'poweredOff' : 'poweredOn' }}
        </span>
        </td>
        <td class="text-end">
        <div class="dropdown">
            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
            <i class="bx bx-dots-vertical-rounded"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
            <button class="dropdown-item" data-bs-toggle="modal"
                data-bs-target="#showGcpOffMachineModal{{ $machine->id }}">
                <i class="bx bx-show me-1"></i> Ver
            </button>
            <button class="dropdown-item" data-bs-toggle="modal"
                data-bs-target="#editGcpOffMachineModal{{ $machine->id }}">
                <i class="bx bx-edit-alt me-1"></i> Editar
            </button>
            <form action="{{ route('gcp-machines.power-on', $machine) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="dropdown-item text-success">
                <i class="bx bx-power-off me-1"></i> Encender
                </button>
            </form>
            <button class="dropdown-item text-danger" data-bs-toggle="modal"
                data-bs-target="#deleteGcpOffMachineModal{{ $machine->id }}">
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
        <td colspan="7" class="text-center text-muted">
        No se encontraron maquinas GCP apagadas
        </td>
    </tr>
@endforelse
