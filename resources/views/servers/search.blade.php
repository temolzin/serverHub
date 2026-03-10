@foreach($servers as $server)
<tr>
<td>{{ $server->id }}</td>
<td>{{ $server->typeApplication->name_application ?? 'N/A' }}</td>
<td>{{ $server->hostname_internal }}</td>
<td>{{ $server->database->name ?? 'N/A' }}</td>
<td>{{ $server->environment }}</td>
<td>{{ $server->primary_ip_address }}</td>
<td class="text-end">

<div class="dropdown">
<button class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
<i class="bx bx-dots-vertical-rounded"></i>
</button>

<div class="dropdown-menu dropdown-menu-end">

<button class="dropdown-item"
data-bs-toggle="modal"
data-bs-target="#editServerModal{{ $server->id }}">
<i class="bx bx-edit-alt me-1"></i> Editar
</button>

<form action="{{ route('servers.power-off',$server->id) }}" method="POST">
@csrf
<button class="dropdown-item text-warning">
<i class="bx bx-power-off me-1"></i> Apagar
</button>
</form>

<form action="{{ route('servers.destroy',$server->id) }}" method="POST">
@csrf
@method('DELETE')
<button class="dropdown-item text-danger">
<i class="bx bx-trash me-1"></i> Eliminar
</button>
</form>

</div>
</div>

</td>
</tr>

@include('servers.edit')

@endforeach
