@extends('layouts/contentNavbarLayout')

@section('title', 'Users')

@section('content')
  <h3>Usuarios</h3>
  @if (session()->has('success'))
    <div class="alert alert-success">
      {{ session()->get('success') }}
    </div>
  @endif

  <table class="table">
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Correo</th>
        <th>Permisos</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($users as $user)
        <tr>
          <td>{{ $user->name }}</td>
          <td>{{ $user->email }}</td>
          <td>
            <a href="{{ route('users.permissions.edit', $user) }}" class="btn btn-sm btn-primary">
              Permisos para usuarios
            </a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

@endsection
