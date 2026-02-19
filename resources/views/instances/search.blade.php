@forelse ($instances as $instance)
  <tr>
    <td>{{ $instance->id }}</td>
    <td>{{ $instance->server->hostname_internal }}</td>
    <td>{{ $instance->memory }} MB</td>
    <td>{{ $instance->version }}</td>
    <td>{{ $instance->edition ?? '—' }}</td>
    <td class="text-end">
      <div class="dropdown">
        <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
          <i class="bx bx-dots-vertical-rounded"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
          <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editInstanceModal{{ $instance->id }}">
            <i class="bx bx-edit-alt me-1"></i> Editar
          </a>
          <a class="dropdown-item text-danger" data-bs-toggle="modal"
            data-bs-target="#deleteInstanceModal{{ $instance->id }}">
            <i class="bx bx-trash me-1"></i> Eliminar
          </a>
        </div>
      </div>
    </td>
  </tr>
  @include('instances.edit', ['instance' => $instance, 'servers' => $servers])
  @include('instances.delete', ['instance' => $instance])
@empty
  <tr>
    <td colspan="6" class="text-center text-muted">
      No hay instancias registradas
    </td>
  </tr>
@endforelse
