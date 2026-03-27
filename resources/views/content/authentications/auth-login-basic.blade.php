@extends('layouts/blankLayout')

@section('title', 'Inicio de sesion')

@section('page-style')
    @vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card px-sm-6 px-0">
                    <div class="card-body">
                        <div class="app-brand justify-content-center">
                            <a href="{{ url('/') }}" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">@include('_partials.macros')</span>
                                <span class="app-brand-text demo text-heading fw-bold">{{ config('variables.templateName') }}</span>
                            </a>
                        </div>
                        <h4 class="mb-1">Bienvenido a {{ config('variables.templateName') }}! 👋</h4>
                        <p class="mb-6">Inicie sesión en su cuenta y comience la aventura</p>
                        @if ($errors->has('email'))
                            <div class="alert alert-danger">
                                Correo o contraseña incorrectos.
                            </div>
                        @endif
                        <form id="formAuthentication" class="mb-6" action="{{ route('login.post') }}" method="POST">
                            @csrf
                            <div class="mb-6">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Ingresa tu correo electrónico" autofocus required />
                            </div>
                            <div class="mb-6 form-password-toggle">
                                <label class="form-label" for="password">Contraseña</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required />
                                    <span class="input-group-text cursor-pointer">
                                        <i class="icon-base bx bx-hide"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="mb-8">
                                <div class="d-flex justify-content-between">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" id="remember-me" name="remember" />
                                        <label class="form-check-label" for="remember-me"> Recuerdame</label>
                                    </div>
                                    <a href="{{ url('auth/forgot-password-basic') }}">
                                        <span>¿Olvidaste tu contraseña?</span>
                                    </a>
                                </div>
                            </div>
                            <div class="mb-6">
                                <button class="btn btn-primary d-grid w-100" type="submit">Iniciar Sesión</button>
                            </div>
                        </form>
                        <p class="text-center">
                            <span>¿Eres nuevo en nuestra plataforma?</span>
                            <a href="{{ url('auth/register-basic') }}">
                                <span>Crear cuenta</span>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
