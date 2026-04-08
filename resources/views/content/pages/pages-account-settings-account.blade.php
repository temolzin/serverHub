@extends('layouts/contentNavbarLayout')

@section('title', 'Mi Perfil')

@section('content')
<div class="row">
<div class="col-12 mb-4">
    <div class="card">
        <div class="card-body">
            <h4 class="mb-1"><i class="bx bx-user-circle text-primary me-2"></i>Mi perfil</h4>
            <p class="mb-0 text-muted">
                Bienvenido, <strong>{{ auth()->user()->name . ' ' . auth()->user()->last_name }}</strong>
            </p>
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
                        <img id="avatarPreview" src="{{ auth()->user()->getFirstMediaUrl('avatars') ?: asset('assets/img/avatars/photoDefault.jpeg') }}" class="rounded" width="100">
                        <div style="max-width: 300px;">
                            <label class="btn btn-outline-primary mb-0">
                                <i class="bx bx-upload me-1"></i> Actualizar imagen
                                <input type="file" name="avatar" accept="image/*" hidden>
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
                        <div class="col-md-12 mb-3">
                            <label class="form-label">
                                <i class="bx bx-envelope me-1 text-primary"></i>Correo
                            </label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}">
                        </div>
                    </div>
                    <button class="btn btn-primary"><i class="bx bx-save me-1"></i> Guardar cambios</button>
                    <button type="button" class="btn btn-outline-primary ms-2" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                        <i class="bx bx-lock-alt me-1"></i> Actualizar contraseña
                    </button>
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

        const input = document.querySelector('input[name="avatar"]');
        const preview = document.getElementById('avatarPreview');

        if (input) {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    preview.src = URL.createObjectURL(file);
                }
            });
        }
    });
</script>
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('profile.password.update') }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bx bx-lock-alt me-2 text-primary"></i>
                        Actualizar contraseña
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bx bx-shield-quarter me-1 text-primary"></i>
                            Contraseña actual
                        </label>
                        <div class="input-group">
                            <input type="password" name="current_password" class="form-control" required>
                            <span class="input-group-text" style="cursor:pointer;" onclick="togglePassword(this)">
                                <i class="bx bx-hide"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bx bx-lock-open-alt me-1 text-primary"></i>
                            Nueva contraseña
                        </label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control" required>
                            <span class="input-group-text" style="cursor:pointer;" onclick="togglePassword(this)">
                                <i class="bx bx-hide"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bx bx-check-shield me-1 text-primary"></i>
                            Confirmar contraseña
                        </label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" class="form-control" required>
                            <span class="input-group-text" style="cursor:pointer;" onclick="togglePassword(this)">
                                <i class="bx bx-hide"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i> Cancelar
                    </button>
                    <button class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function togglePassword(el) {
    const input = el.previousElementSibling;
    const icon = el.querySelector('i');

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove('bx-hide');
        icon.classList.add('bx-show');
    } else {
        input.type = "password";
        icon.classList.remove('bx-show');
        icon.classList.add('bx-hide');
    }
}
</script>
@endsection
