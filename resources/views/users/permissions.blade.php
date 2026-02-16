@extends('layouts/contentNavbarLayout')

@section('title', 'User Permissions')
@section('content')
  <h3 align="center">Permisos para {{ $user->name }}</h3>
  <form method="POST" align="center" action="{{ route('users.permissions.update', $user) }}">
    @csrf
    @foreach ($permissions->filter(fn($permission) => str_starts_with($permission->name, 'view')) as $permission)
      <div>
        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
          {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}>
        <label>{{ $permission->description }}</label>
      </div>
    @endforeach
    <br>
    <button type="submit" class="btn btn-primary">
      Guardar Permisos
    </button>
  </form>
@endsection
