@php
use Illuminate\Support\Facades\Route;

$menu = $menu ?? [];
@endphp
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ url('/') }}" class="app-brand-link">
            <span class="app-brand-logo demo">@include('_partials.macros')</span>
            <span class="app-brand-text demo menu-text fw-bold ms-2">
                {{ config('variables.templateName') }}
            </span>
        </a>
        <a href="javascript:void(0);"
            class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="icon-base bx bx-chevron-left icon-sm d-flex align-items-center justify-content-center"></i>
        </a>
    </div>
    <div class="menu-divider mt-0"></div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        @foreach ($menu as $item)
            @if (isset($item['menuHeader']))
                <li class="menu-header small text-uppercase">
                    <span class="menu-header-text">{{ __($item['menuHeader']) }}</span>
                </li>
            @else
                @php
                    $activeClass = '';
                    $currentRouteName = Route::currentRouteName();
                    if (isset($item['slug'])) {
                        if ($currentRouteName === $item['slug']) {
                            $activeClass = 'active';
                        }
                        elseif (is_array($item['slug'])) {
                            foreach ($item['slug'] as $slug) {
                                if (str_starts_with($currentRouteName, $slug)) {
                                    $activeClass = 'active open';
                                }
                            }
                        }
                        elseif (str_starts_with($currentRouteName, $item['slug'])) {
                            $activeClass = 'active open';
                        }
                    }
                @endphp
                <li class="menu-item {{ $activeClass }}">
                    <a href="{{ isset($item['url']) ? url($item['url']) : 'javascript:void(0);' }}"
                        class="{{ isset($item['submenu']) ? 'menu-link menu-toggle' : 'menu-link' }}"
                        @if(isset($item['target']) && !empty($item['target'])) target="_blank" @endif>
                        @isset($item['icon'])
                            <i class="{{ $item['icon'] }}"></i>
                        @endisset
                        <div>{{ isset($item['name']) ? __($item['name']) : '' }}</div>
                        @isset($item['badge'])
                            <div class="badge rounded-pill bg-{{ $item['badge'][0] }} text-uppercase ms-auto">
                                {{ $item['badge'][1] }}
                            </div>
                        @endisset
                    </a>
                    @isset($item['submenu'])
                        @include('layouts.sections.menu.submenu', ['menu' => $item['submenu'], 'depth' => 1])
                    @endisset
                </li>
            @endif
        @endforeach
    </ul>
</aside>
