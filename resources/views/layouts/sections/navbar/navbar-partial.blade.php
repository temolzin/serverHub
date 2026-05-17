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
                    <img src="{{ Auth::user()->getFirstMediaUrl('avatars') ?: asset('assets/img/avatars/photoDefault.jpeg') }}" class="w-px-40 h-20 w-30 rounded-circle" style="object-fit: cover;">
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

    const sections = [
        {
            key: 'servers',
            label: 'Servidores',
            icon: '🖥',
            url: '/servers',
            getSearchValue: item => item.hostname_internal,
            getText: item => item.hostname_internal
        },
        {
            key: 'machines',
            label: 'Máquinas',
            icon: '☁',
            url: '/gcp-machines',
            getSearchValue: item => item.machine_name,
            getText: item => item.machine_name
        },
        {
            key: 'applications',
            label: 'Apps',
            icon: '📦',
            url: '/applications',
            getSearchValue: item => item.name,
            getText: item => item.name
        },
        {
            key: 'databases',
            label: 'DB',
            icon: '🗄',
            url: '/databases',
            getSearchValue: item => item.name,
            getText: item => item.name
        },
        {
            key: 'owners',
            label: 'Propietarios',
            icon: '👤',
            url: '/owners',
            getSearchValue: item => item.name,
            getText: item => `${item.name} ${item.last_name}`
        },
        {
            key: 'users',
            label: 'Usuarios',
            icon: '👤',
            url: '/users',
            getSearchValue: item => item.name,
            getText: item => item.name
        },
        {
            key: 'instances',
            label: 'Instancias',
            icon: '📦',
            url: '/instances',
            getSearchValue: item => item.id,
            getText: item => item.id
        },
        {
            key: 'storages',
            label: 'Storage',
            icon: '💾',
            url: '/storages',
            getSearchValue: item => item.id,
            getText: item => item.id
        },
        {
            key: 'type_applications',
            label: 'Tipos de App',
            icon: '⚙️',
            url: '/type-applications',
            getSearchValue: item => item.id,
            getText: item => item.id
        }
    ];

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

                    let html = '';

                    sections.forEach((section, index) => {
                        const items = data[section.key];

                        if (!items || !items.length) return;

                        html += `<small class="text-muted ${index > 0 ? 'mt-2 d-block' : ''}">
                                    ${section.label}
                                 </small>`;

                        items.forEach(item => {
                            const searchValue = encodeURIComponent(section.getSearchValue(item));
                            const text = section.getText(item);

                            html += `
                            <a href="${section.url}?id=${item.id}"
                            class="d-block p-1 hover-item text-decoration-none text-dark">
                            ${section.icon} ${text}
                            </a>`;
                        });
                    });

                    if (!html) {
                        html = `<div class="text-center text-muted">Sin resultados</div>`;
                    }

                    container.innerHTML = html;
                    resultsBox.classList.remove('d-none');
                })
                .catch(() => {
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
