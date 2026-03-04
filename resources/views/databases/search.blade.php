@forelse ($databases as $database)
  <tr>
    <td>{{ $database->id }}</td>
    <td>{{ $database->name }}</td>
    <td>{{ $database->type }}</td>
    <td>{{ $database->instance?->server?->hostname_internal ?? 'N/A' }}</td>
    <td>{{ optional($database->owner)->name }}{{ optional($database->owner)->last_name }}</td>
    <td>{{ $database->port }}</td>
    <td>{{ $database->version ?? '—' }}</td>
    <td>
    <span class="badge
        {{ strtolower($database->status ?? '') === 'inactive' ? 'bg-label-danger' : 'bg-label-info' }}">
        {{ $database->status ?? '—' }}
    </span>
    </td>
    <td>{{ $database->last_update ?? '—' }}</td>
    <td class="text-end">
      <div class="dropdown">
        <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
          <i class="bx bx-dots-vertical-rounded"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
          <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#showDatabaseModal{{ $database->id }}">
            <i class="bx bx-show me-1"></i> Ver
          </a>
          <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editDatabaseModal{{ $database->id }}">
            <i class="bx bx-edit-alt me-1"></i> Editar
          </a>
          <a class="dropdown-item text-danger" data-bs-toggle="modal"
            data-bs-target="#deleteDatabaseModal{{ $database->id }}">
            <i class="bx bx-trash me-1"></i> Eliminar
          </a>
        </div>
      </div>
    </td>
  </tr>
  @include('databases.show', ['database' => $database])
  @include('databases.edit', ['database' => $database, 'servers' => $servers])
  @include('databases.delete', ['database' => $database])
@empty
  <tr>
    <td colspan="6" class="text-center text-muted">
      No se encontraron bases de datos
    </td>
  </tr>
@endforelse
