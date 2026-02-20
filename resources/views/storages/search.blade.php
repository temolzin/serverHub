@forelse ($storages as $storage)
  <tr>
    <td>{{ $storage->id }}</td>
    <td>{{ $storage->hostname }}</td>
    <td>{{ $storage->internal_ip ?? '—' }}</td>
    <td>{{ $storage->environment ?? '—' }}</td>
    <td>{{ $storage->datacenter ?? '—' }}</td>
    <td class="text-end">
      <div class="dropdown">
        <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
          <i class="bx bx-dots-vertical-rounded"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
          <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#showStorageModal{{ $storage->id }}">
            <i class="bx bx-show me-1"></i> Ver
          </a>
          <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editStorageModal{{ $storage->id }}">
            <i class="bx bx-edit-alt me-1"></i> Editar
          </a>
          <a class="dropdown-item text-danger" data-bs-toggle="modal"
            data-bs-target="#deleteStorageModal{{ $storage->id }}">
            <i class="bx bx-trash me-1"></i> Eliminar
          </a>
        </div>
      </div>
    </td>
  </tr>
  @include('storages.show', ['storage' => $storage])
  @include('storages.edit', ['storage' => $storage])
  @include('storages.delete', ['storage' => $storage])
@empty
  <tr>
    <td colspan="6" class="text-center text-muted">
      No hay storages registrados
    </td>
  </tr>
@endforelse
