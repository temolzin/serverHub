@extends('layouts/contentNavbarLayout')

@section('title', 'Mi Perfil')

@section('content')
<div class="row">
<div class="col-12 mb-4">
    <div class="card">
        <div class="card-body">
            <h4 class="mb-1"><i class="bx bx-user-circle text-primary me-2"></i>Mi perfil</h4>
            <p class="mb-0 text-muted">Bienvenido, <strong>{{ auth()->user()->name }}</strong></p>
        </div>
    </div>
</div>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div class="col-md-12">
        <div class="card mb-6">
            <div class="card-body pt-4">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="d-flex align-items-center gap-4 mb-4">
                        <img src="{{ auth()->user()->avatar ? asset(auth()->user()->avatar) . '?v=' . time() : asset('assets/img/avatars/1.png') }}" class="rounded" width="100">
                        <div style="max-width: 300px;">
                            <label class="btn btn-outline-primary mb-0">
                                <i class="bx bx-upload me-1"></i> Actualizar imagen
                                <input type="file" name="avatar" hidden>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bx bx-user me-1 text-primary"></i>Nombre
                            </label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bx bx-id-card me-1 text-primary"></i>Apellido
                            </label>
                            <input type="text" name="last_name" class="form-control" value="{{ old('last_name', auth()->user()->last_name) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">
                                <i class="bx bx-envelope me-1 text-primary"></i>Correo
                            </label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}">
                        </div>
                    </div>
                    <button class="btn btn-primary"><i class="bx bx-save me-1"></i> Guardar cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Listo!',
                text: '{{ session('success') }}',
                confirmButtonText: 'Perfecto',
                timer: 5000,
                timerProgressBar: true
            });
        @endif
    });
</script>
@endsection
