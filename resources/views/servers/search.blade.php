@forelse ($servers as $server)
    @php
        $applicationName = optional($server->typeApplication)->name_application;
        $isOff = $server->isPoweredOff();
    @endphp
    <tr>
        <td>{{ $server->id }}</td>
        <td>{{ filled($applicationName) ? $applicationName : 'N/A' }}</td>
        <td>{{ filled($server->hostname_internal) ? $server->hostname_internal : 'N/A' }}</td>
        <td>{{ $server->database?->name ?? 'N/A' }}</td>
        <td>
            {{ strtoupper(filled($server->environment) ? $server->environment : 'N/A') }}
        </td>
        <td>
            {{ strtoupper($server->stateLabel()) }}
        </td>
        <td>{{ filled($server->primary_ip_address) ? $server->primary_ip_address : 'N/A' }}</td>
        <td class="text-end">
            <div class="dropdown">
                <button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#showServerModal{{ $server->id }}"><i class="bx bx-show me-1"></i> Ver</a>
                    <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editServerModal{{ $server->id }}"><i class="bx bx-edit-alt me-1"></i> Editar</a>
                    <form action="{{ route('servers.power-off', $server) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item text-warning"><i class="bx bx-power-off me-1"></i> Apagar</button>
                    </form>
                    <a class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteServerModal{{ $server->id }}"><i class="bx bx-trash me-1"></i> Eliminar</a>
                </div>
            </div>
            @include('servers.show', ['server' => $server])
            @include('servers.edit', ['server' => $server, 'databases' => $databases, 'applications' => $applications])
            @include('servers.delete', ['server' => $server])
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="8" class="text-center text-muted">No se encontraron servidores activos</td>
    </tr>
@endforelse
