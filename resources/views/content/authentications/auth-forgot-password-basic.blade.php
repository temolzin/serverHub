@extends('layouts/blankLayout')

@section('title', 'Olvidaste tu contraseña')

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
                        <h4 class="mb-1">¿Olvidaste tu contraseña? 🔒</h4>
                        <p class="mb-6">
                            Ingresa tu correo electrónico y te enviaremos instrucciones para restablecer tu contraseña
                        </p>
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                {{ $errors->first() }}
                            </div>
                        @endif
                        <form id="formAuthentication" class="mb-6" action="{{ route('password.email') }}" method="POST">
                            @csrf
                            <div class="mb-6">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Ingresa tu correo" value="{{ old('email') }}" required autofocus />
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button class="btn btn-primary d-grid w-100">Enviar enlace de recuperación</button>
                        </form>
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="d-flex justify-content-center">
                                <i class="icon-base bx bx-chevron-left me-1"></i>Regresar al inicio de sesion
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
