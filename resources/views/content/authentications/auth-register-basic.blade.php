@extends('layouts/blankLayout')

@section('title', 'Registro')

@section('page-style')
    @vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card px-sm-6 px-0">
                    <div class="card-body">
                        <div class="app-brand justify-content-center mb-6">
                            <a href="{{ url('/') }}" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">@include('_partials.macros')</span>
                                <span class="app-brand-text demo text-heading fw-bold">{{ config('variables.templateName') }}</span>
                            </a>
                        </div>
                        <h4 class="mb-1">La aventura comienza aquí</h4>
                        <p class="mb-6">¡Haz que la gestión de tu aplicación sea fácil y divertida!</p>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form id="formAuthentication" class="mb-6" action="{{ route('register.store') }}" method="POST">
                            @csrf
                            <div class="mb-6">
                                <label for="username" class="form-label">Nombre de usuario</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" placeholder="Ingresa tu nombre de usuario" autofocus required />
                                @error('username')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-6">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Ingresa tu correo electrónico" required />
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-password-toggle">
                                <label class="form-label" for="password">Contraseña</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required />
                                    <span class="input-group-text cursor-pointer">
                                        <i class="icon-base bx bx-hide"></i>
                                    </span>
                                </div>
                                @error('password')
                                    <div class="text-danger mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-password-toggle mb-6">
                                    <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" placeholder="••••••••••" required>
                                        <span class="input-group-text cursor-pointer">
                                            <i class="icon-base bx bx-hide"></i>
                                        </span>
                                    </div>
                                    @error('password_confirmation')
                                        <div class="text-danger mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="my-7">
                                <div class="form-check mb-0">
                                    <input class="form-check-input @error('terms') is-invalid @enderror"
                                        type="checkbox" id="terms-conditions" name="terms" />
                                    <label class="form-check-label" for="terms-conditions">
                                        Estoy de acuerdo con los
                                        <a href="javascript:void(0);">Política y términos de privacidad</a>
                                    </label>
                                    @error('terms')
                                        <div class="text-danger mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary d-grid w-100">Registrarse</button>
                        </form>
                        <p class="text-center">
                            <span>¿Ya tienes una cuenta?</span>
                            <a href="{{ route('login') }}">
                                <span>Iniciar sesión</span>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
