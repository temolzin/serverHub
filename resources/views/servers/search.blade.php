@forelse ($servers as $server)
  <tr>
    <td>{{ $server->id }}</td>
    <td>
      {{ optional($server->owner)->name }}
      {{ optional($server->owner)->last_name }}
    </td>
    <td>{{ optional($server->typeApplication)->name_application }}</td>
    <td>{{ $server->vm_according_to_the_vmware }}</td>
    <td>
      @if ($server->state)
        <span class="badge bg-label-success">poweredOn</span>
      @else
        <span class="badge bg-label-danger">poweredOff</span>
      @endif
    </td>
    <td>{{ $server->dns_name ?? '—' }}</td>
    <td>{{ $server->primary_ip_address }}</td>
    <td>
      <span class="badge bg-label-info">
        {{ $server->environment }}
      </span>
    </td>
    <td>{{ $server->datacenter }}</td>
    <td>{{ $server->os_according_to_the_vmware }}</td>
    <td>{{ $server->os_version_internal }}</td>
    <td class="text-muted">{{ $server->hostname_internal }}</td>
    <td>{{ $server->ip_user ?? '—' }}</td>
    <td>{{ $server->ip_monitoring ?? '—' }}</td>
    <td class="small">{{ $server->other_ips ?? '—' }}</td>
    <td>{{ $server->ram_memory }} MB</td>
    <td>{{ $server->swap_memory }} MB</td>
    <td>{{ $server->latest_security_patch ?? '—' }}</td>
    <td>{{ $server->comments ?? '—' }}</td>
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
          <a class="dropdown-item text-danger" data-bs-toggle="modal"
            data-bs-target="#deleteServerModal{{ $server->id }}">
            <i class="bx bx-trash me-1"></i> Eliminar
          </a>
        </div>
      </div>
    </td>
  </tr>
  @include('servers.show', ['server' => $server])
  @include('servers.edit', [
      'server' => $server,
      'owners' => $owners,
      'typeApplications' => $typeApplications,
  ])
  @include('servers.delete', ['server' => $server])
@empty
  <tr>
    <td colspan="19" class="text-center text-muted">
      No se encontraron servidores
    </td>
  </tr>
@endforelse
