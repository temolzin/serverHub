@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Route;
@endphp

@if (isset($navbarFull))
    <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
        <a href="{{ url('/') }}" class="app-brand-link gap-2">
            <span class="app-brand-logo demo">@include('_partials.macros')</span>
            <span class="app-brand-text demo menu-text fw-bold text-heading">{{ config('variables.templateName') }}</span>
        </a>
    </div>
@endif
@if (!isset($navbarHideToggle))
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 {{ isset($contentNavbar) ? ' d-xl-none ' : '' }}">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0);">
            <i class="icon-base bx bx-menu icon-md"></i>
        </a>
    </div>
@endif
<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    <div class="navbar-nav align-items-center">
        <div class="nav-item d-flex align-items-center position-relative" style="width:300px;">
            <i class="icon-base bx bx-search icon-md"></i>
            <input type="text" id="globalSearch" class="form-control border-0 shadow-none ps-1 ps-sm-2" placeholder="Buscar..." autocomplete="off">
            <div id="searchResults" class="card shadow position-absolute w-100 mt-2 d-none" style="top:100%; z-index:999;">
                <div class="card-body p-2" id="resultsContainer"></div>
            </div>
        </div>
    </div>
    <ul class="navbar-nav flex-row align-items-center ms-auto">
        @auth
        <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow p-0"
               href="#"
               role="button"
               data-bs-toggle="dropdown"
               data-bs-auto-close="outside"
               aria-expanded="false">
                <div class="avatar avatar-online">
                    <img src="{{ Auth::user()->getFirstMediaUrl('avatars') ?: asset('assets/img/avatars/photoDefault.jpeg') }}" class="w-px-40 h-auto rounded-circle" style="object-fit: cover;">
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="javascript:void(0);">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-online">
                                    <img src="{{ Auth::user()->getFirstMediaUrl('avatars') ?: asset('assets/img/avatars/photoDefault.jpeg') }}" class="w-px-40 h-auto rounded-circle" style="object-fit: cover;">
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                <small class="text-muted">{{ Auth::user()->email }}</small>
                            </div>
                        </div>
                    </a>
                </li>
                <li><div class="dropdown-divider my-1"></div></li>
                <li>
                    <a class="dropdown-item" href="{{ url('/account-settings') }}">
                        <i class="icon-base bx bx-user icon-md me-3"></i>
                        <span>Mi Perfil</span>
                    </a>
                </li>
                <li><div class="dropdown-divider my-1"></div></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="icon-base bx bx-power-off me-3"></i>
                            <span>Cerrar sesion</span>
                        </button>
                    </form>
                </li>
            </ul>
        </li>
        @endauth
        @guest
        <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">Login</a>
        </li>
        @endguest
    </ul>
</div>
<style>
    .hover-item:hover {
        background: #f5f5f9;
        cursor: pointer;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('globalSearch');
    const resultsBox = document.getElementById('searchResults');
    const container = document.getElementById('resultsContainer');

    if (!input) return;

    let timeout = null;

    input.addEventListener('keyup', function () {
        clearTimeout(timeout);

        let query = this.value;

        if (query.length < 2) {
            resultsBox.classList.add('d-none');
            return;
        }

        timeout = setTimeout(() => {

            fetch(`/global-search?q=${query}`)
                .then(res => res.json())
                .then(data => {

                    console.log(data);

                    let html = '';

                    if (data.servers && data.servers.length) {
                        html += `<small class="text-muted">Servidores</small>`;
                        data.servers.forEach(s => {
                            html += `
                                <a href="/servers?search=${s.hostname_internal}"
                                class="d-block p-1 hover-item text-decoration-none text-dark">
                                🖥 ${s.hostname_internal}
                                </a>`;
                        });
                    }

                    if (data.machines && data.machines.length) {
                        html += `<small class="text-muted mt-2 d-block">Máquinas</small>`;
                        data.machines.forEach(m => {
                            html += `
                                <a href="/gcp-machines?search=${m.machine_name}"
                                   class="d-block p-1 hover-item text-decoration-none text-dark">
                                   ☁ ${m.machine_name}
                                </a>`;
                        });
                    }

                    if (data.applications && data.applications.length) {
                        html += `<small class="text-muted mt-2 d-block">Apps</small>`;
                        data.applications.forEach(a => {
                            html += `
                                <a href="/applications?search=${a.name}"
                                   class="d-block p-1 hover-item text-decoration-none text-dark">
                                   📦 ${a.name}
                                </a>`;
                        });
                    }

                    if (data.databases && data.databases.length) {
                        html += `<small class="text-muted mt-2 d-block">DB</small>`;
                        data.databases.forEach(d => {
                            html += `
                                <a href="/databases?search=${d.name}"
                                   class="d-block p-1 hover-item text-decoration-none text-dark">
                                   🗄 ${d.name}
                                </a>`;
                        });
                    }

                    if (data.owners && data.owners.length) {
                        html += `<small class="text-muted mt-2 d-block">Propietarios</small>`;
                        data.owners.forEach(o => {
                            html += `
                                <a href="/owners?search=${o.name}"
                                   class="d-block p-1 hover-item text-decoration-none text-dark">
                                   👤 ${o.name} ${o.last_name}
                                </a>`;
                        });
                    }

                    if (data.users && data.users.length) {
                        html += `<small class="text-muted mt-2 d-block">Usuarios</small>`;
                        data.users.forEach(u => {
                            html += `
                                <a href="/users?search=${u.name}"
                                   class="d-block p-1 hover-item text-decoration-none text-dark">
                                   👤 ${u.name}
                                </a>`;
                        });
                    }

                    if (data.instances && data.instances.length) {
                        html += `<small class="text-muted mt-2 d-block">Instancias</small>`;
                        data.instances.forEach(i => {
                            html += `
                                <a href="/instances?search=${i.id}"
                                   class="d-block p-1 hover-item text-decoration-none text-dark">
                                   📦 ${i.id}
                                </a>`;
                        });
                    }

                    if (data.storages && data.storages.length) {
                        html += `<small class="text-muted mt-2 d-block">Storage</small>`;
                        data.storages.forEach(s => {
                            html += `
                                <a href="/storages?search=${s.id}"
                                   class="d-block p-1 hover-item text-decoration-none text-dark">
                                   💾 ${s.id}
                                </a>`;
                        });
                    }

                    if (data.type_applications && data.type_applications.length) {
                        html += `<small class="text-muted mt-2 d-block">Tipos de App</small>`;
                        data.type_applications.forEach(t => {
                            html += `
                                <a href="/type-applications?search=${t.id}"
                                   class="d-block p-1 hover-item text-decoration-none text-dark">
                                   ⚙️ ${t.id}
                                </a>`;
                        });
                    }

                    if (!html) {
                        html = `<div class="text-center text-muted">Sin resultados</div>`;
                    }

                    container.innerHTML = html;
                    resultsBox.classList.remove('d-none');
                })
                .catch(err => {
                    console.error('Error en búsqueda:', err);
                    resultsBox.classList.add('d-none');
                });

        }, 300);
    });

    document.addEventListener('click', function (e) {
        if (!input.contains(e.target) && !resultsBox.contains(e.target)) {
            resultsBox.classList.add('d-none');
        }
    });
});
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const trigger = document.querySelector('[data-bs-toggle="dropdown"]');
        if (trigger && typeof bootstrap !== 'undefined') {
            new bootstrap.Dropdown(trigger);
        }
    });
</script>
