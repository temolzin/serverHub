@forelse ($owners as $owner)
  <tr>
    <td>{{ $owner->id }}</td>
    <td>{{ $owner->name }} {{ $owner->last_name }}</td>
    <td>{{ $owner->email }}</td>
    <td>{{ $owner->number_phone ?? '—' }}</td>
    <td class="text-end">
      <div class="dropdown">
        <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
          <i class="bx bx-dots-vertical-rounded"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
          <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#showOwnerModal{{ $owner->id }}">
            <i class="bx bx-show me-1"></i> Ver
          </a>
          <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editOwnerModal{{ $owner->id }}">
            <i class="bx bx-edit-alt me-1"></i> Editar
          </a>
          <a class="dropdown-item text-danger" data-bs-toggle="modal"
            data-bs-target="#deleteOwnerModal{{ $owner->id }}">
            <i class="bx bx-trash me-1"></i> Eliminar
          </a>
        </div>
      </div>
    </td>
  </tr>
  @include('owners.show', ['owner' => $owner])
  @include('owners.edit', ['owner' => $owner])
  @include('owners.delete', ['owner' => $owner])
@empty
  <tr>
    <td colspan="5" class="text-center text-muted">
      No se encontraron propietarios
    </td>
  </tr>
@endforelse
