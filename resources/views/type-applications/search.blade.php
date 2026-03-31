@forelse ($typeApplications as $type)
    <tr>
        <td>{{ $type->id }}</td>
        <td>{{ $type->type_label }}</td>
        <td class="fw-medium">{{ $type->name_application }}</td>
        <td class="text-end">
            <div class="dropdown">
                <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#showTypeApplicationModal{{ $type->id }}">
                        <i class="bx bx-show me-1"></i> Ver
                    </a>
                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editTypeApplicationModal{{ $type->id }}">
                        <i class="bx bx-edit-alt me-1"></i> Editar
                    </a>
                    <a class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteTypeApplicationModal{{ $type->id }}">
                        <i class="bx bx-trash me-1"></i> Eliminar
                    </a>
                </div>
            </div>
        </td>
    </tr>
    @include('type-applications.show', ['type' => $type])
    @include('type-applications.edit', ['type' => $type])
    @include('type-applications.delete', ['type' => $type])
@empty
    <tr>
        <td colspan="4" class="text-center text-muted">
            No se encontraron aplicaciones del tipo
        </td>
    </tr>
@endforelse
