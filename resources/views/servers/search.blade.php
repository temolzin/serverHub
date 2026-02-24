@forelse ($servers as $server)
  <tr>
    <td>{{ $server->id }}</td>
    <td>{{ optional($server->typeApplication)->name_application }}</td>
    <td>{{ $server->hostname_internal }}</td>
    <td>
      <span class="badge bg-label-info">
        {{ $server->environment }}
      </span>
    </td>
    <td>{{ $server->primary_ip_address }}</td>
    <td class="text-end">
      <div class="dropdown">
        <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
          <i class="bx bx-dots-vertical-rounded"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
          <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#showServerModal{{ $server->id }}">
            <i class="bx bx-show me-1"></i> Ver
          </a>
          <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editServerModal{{ $server->id }}">
            <i class="bx bx-edit-alt me-1"></i> Editar
          </a>
          <form action="{{ route('servers.power-off', $server) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="dropdown-item text-warning">
              <i class="bx bx-power-off me-1"></i> Apagar
            </button>
          </form>
          <a class="dropdown-item text-danger" data-bs-toggle="modal"
            data-bs-target="#deleteServerModal{{ $server->id }}">
            <i class="bx bx-trash me-1"></i> Eliminar
          </a>
        </div>
      </div>
      @include('servers.show', ['server' => $server])
      @include('servers.edit', ['server' => $server])
      @include('servers.delete', ['server' => $server])
    </td>
  </tr>
@empty
  <tr>
    <td colspan="6" class="text-center text-muted">
      No se encontraron servidores activos
    </td>
  </tr>
@endforelse
