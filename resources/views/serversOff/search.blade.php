@forelse ($servers as $server)
    <tr>
        <td>{{ $server->id }}</td>
        <td>{{ filled($server->vm_according_to_the_vmware) ? $server->vm_according_to_the_vmware : 'N/A' }}</td>
        <td>{{ filled($server->dns_name) ? $server->dns_name : 'N/A' }}</td>
        <td>{{ filled($server->os_according_to_the_vmware) ? $server->os_according_to_the_vmware : 'N/A' }}</td>
        <td>
            <span class="badge {{ $server->display_state_badge_class }}">{{ $server->display_state_label }}</span>
        </td>
        <td class="text-end">
            <div class="dropdown">
                <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#showServerOffModal{{ $server->id }}"><i class="bx bx-show me-1"></i> Ver</a>
                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editServerOffModal{{ $server->id }}"><i class="bx bx-edit-alt me-1"></i> Editar</a>
                    <form action="{{ route('servers.power-on', $server) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item text-success"><i class="bx bx-power-off me-1"></i> Encender</button>
                    </form>
                    <a class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteServerOffModal{{ $server->id }}"><i class="bx bx-trash me-1"></i> Eliminar</a>
                </div>
            </div>
            @include('serversOff.show', ['server' => $server])
            @include('serversOff.edit', ['server' => $server, 'applications' => $applications, 'databases' => $databases])
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
