@forelse ($gcpMachines as $machine)
  <tr>
    <td>{{ $machine->id }}</td>
    <td>{{ $machine->project_name }}</td>
    <td>{{ $machine->machine_name }}</td>
    <td>
      <span class="badge bg-label-primary">
        {{ $machine->environment }}
      </span>
    </td>
    <td>{{ $machine->internal_ip }}</td>
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
          <button class="dropdown-item text-danger" data-bs-toggle="modal"
            data-bs-target="#deleteGcpMachineModal{{ $machine->id }}">
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
    <td colspan="6" class="text-center text-muted">
      No se encontraron registros
    </td>
  </tr>
@endforelse
