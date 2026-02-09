@php
  use Illuminate\Support\Facades\Route;
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  <div class="app-brand demo">
    <a href="{{ url('/') }}" class="app-brand-link">
      <span class="app-brand-logo demo">@include('_partials.macros')</span>
      <span class="app-brand-text demo menu-text fw-bold ms-2">
        {{ config('variables.templateName') }}
      </span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left"></i>
    </a>
  </div>

  <div class="menu-divider mt-0"></div>
  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">

    @foreach ($menuData[0]->menu as $menu)
      {{-- HEADER --}}
      @if (isset($menu->menuHeader))
        <li class="menu-header small text-uppercase">
          <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
        </li>
      @else
        @php
          $activeClass = '';
          $currentRouteName = Route::currentRouteName();

          if ($currentRouteName === $menu->slug) {
              $activeClass = 'active';
          } elseif (isset($menu->submenu)) {
              if (is_array($menu->slug)) {
                  foreach ($menu->slug as $slug) {
                      if (str_starts_with($currentRouteName, $slug)) {
                          $activeClass = 'active open';
                      }
                  }
              } else {
                  if (str_starts_with($currentRouteName, $menu->slug)) {
                      $activeClass = 'active open';
                  }
              }
          }
        @endphp

        <li class="menu-item {{ $activeClass }}">
          <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}"
            class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}">
            @isset($menu->icon)
              <i class="{{ $menu->icon }}"></i>
            @endisset
            <div>{{ __($menu->name ?? '') }}</div>
          </a>

          @isset($menu->submenu)
            @include('layouts.sections.menu.submenu', ['menu' => $menu->submenu])
          @endisset
        </li>

        @if (($menu->name ?? '') === 'Servidores')
          @can('viewServerDatabase')
            <li class="menu-item {{ request()->routeIs('databases.*') ? 'active' : '' }}">
              <a href="{{ route('databases.index') }}" class="menu-link">
                <i class="bx bx-data"></i>
                <div>Bases de datos</div>
              </a>
            </li>
          @endcan
        @endif
      @endif
    @endforeach

  </ul>
</aside>
