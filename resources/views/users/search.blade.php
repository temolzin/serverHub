@forelse ($users as $user)
  <tr>
    <td>{{ $user->name }}</td>
    <td>{{ $user->email }}</td>
    <td>
      @foreach ($user->roles as $role)
        <span class="badge bg-label-primary">
          {{ $role->name }}
        </span>
      @endforeach
    </td>
    <td>
      @if ($user->is_active)
        <span class="badge bg-label-success">Activo</span>
      @else
        <span class="badge bg-label-danger">Inactivo</span>
      @endif
    </td>
    <td class="text-end">
      <div class="dropdown">
        <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
          <i class="bx bx-dots-vertical-rounded"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
          <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
            <i class="bx bx-edit-alt me-1"></i> Editar
          </a>
          <a class="dropdown-item text-danger" data-bs-toggle="modal"
            data-bs-target="#deleteUserModal{{ $user->id }}">
            <i class="bx bx-trash me-1"></i> Eliminar
          </a>
        </div>
      </div>
    </td>
  </tr>
  @include('users.partials.edit', ['user' => $user])
  @include('users.partials.delete', ['user' => $user])
@empty
  <tr>
    <td colspan="5" class="text-center text-muted">
      No hay usuarios registrados
    </td>
  </tr>
@endforelse
