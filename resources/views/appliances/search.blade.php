@forelse ($servers as $server)
    <tr>
        <td>{{ $server->id }}</td>
        <td>{{ filled($server->vm_according_to_the_vmware) ? $server->vm_according_to_the_vmware : 'N/A' }}</td>
        <td>{{ filled($server->primary_ip_address) ? $server->primary_ip_address : 'N/A' }}</td>
        <td>{{ filled($server->datacenter) ? $server->datacenter : 'N/A' }}</td>
        <td>{{ filled($server->display_application_name) ? $server->display_application_name : 'N/A' }}</td>
        <td>
            <span class="badge {{ $server->display_state_badge_class }}">
                {{ $server->display_state_label }}
            </span>
        </td>
        <td class="text-end">
            <div class="dropdown">
                <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#showApplianceModal{{ $server->id }}"><i class="bx bx-show me-1"></i> Ver</a>
                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editApplianceModal{{ $server->id }}"><i class="bx bx-edit-alt me-1"></i> Editar</a>
                    <form action="{{ route('appliances.power-off', $server) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item text-warning"><i class="bx bx-power-off me-1"></i> Apagar</button>
                    </form>
                    <a class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteApplianceModal{{ $server->id }}"><i class="bx bx-trash me-1"></i> Eliminar</a>
                </div>
            </div>
            @include('appliances.show', ['server' => $server])
            @include('appliances.edit', ['server' => $server, 'databases' => $databases, 'applications' => $applications])
            @include('appliances.delete', ['server' => $server])
        </td>
    </tr>
@empty
    <tr>
        <td colspan="9" class="text-center text-muted">No se encontraron apliances activos</td>
    </tr>
@endforelse
