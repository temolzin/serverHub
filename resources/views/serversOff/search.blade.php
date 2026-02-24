@forelse ($servers as $server)
  @php($isOff = $server->isPoweredOff())
  <tr>
    <td>{{ $server->id }}</td>
    <td>{{ $server->vm_according_to_the_vmware ?? 'N/A' }}</td>
    <td>{{ $server->dns_name ?? 'N/A' }}</td>
    <td>{{ $server->os_according_to_the_vmware ?? 'N/A' }}</td>
    <td>
      <span class="badge {{ $isOff ? 'bg-label-danger' : 'bg-label-success' }}">
        {{ $isOff ? 'poweredOff' : 'poweredOn' }}
      </span>
    </td>
    <td class="text-end">
      <div class="dropdown">
        <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
          <i class="bx bx-dots-vertical-rounded"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
          <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#showServerOffModal{{ $server->id }}">
            <i class="bx bx-show me-1"></i> Ver
          </a>
          <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editServerOffModal{{ $server->id }}">
            <i class="bx bx-edit-alt me-1"></i> Editar
          </a>
          <form action="{{ route('servers.power-on', $server) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="dropdown-item text-success">
              <i class="bx bx-power-off me-1"></i> Encender
            </button>
          </form>
          <a class="dropdown-item text-danger" data-bs-toggle="modal"
            data-bs-target="#deleteServerOffModal{{ $server->id }}">
            <i class="bx bx-trash me-1"></i> Eliminar
          </a>
        </div>
      </div>
      @include('serversOff.show', ['server' => $server])
      @include('serversOff.edit', ['server' => $server])
      @include('serversOff.delete', ['server' => $server])
    </td>
  </tr>
@empty
  <tr>
    <td colspan="6" class="text-center text-muted">
      No se encontraron servidores apagados
    </td>
  </tr>
@endforelse
