@php
    use Illuminate\Support\Facades\Route;
    $depth = isset($depth) ? (int) $depth : 1;
@endphp

<ul class="menu-sub">
    @if (isset($menu))
        @foreach ($menu as $submenu)

            @php
                $activeClass = '';
                $active = 'active open';
                $currentRouteName = Route::currentRouteName();
                $hasSubmenu = isset($submenu['submenu']) && is_array($submenu['submenu']) && count($submenu['submenu']) > 0;
                $submenuSlug = $submenu['slug'] ?? null;
                $slugList = is_array($submenuSlug) ? $submenuSlug : (is_string($submenuSlug) ? [$submenuSlug] : []);
                $isExactMatch = is_string($submenuSlug) && $currentRouteName === $submenuSlug;
                $hasPrefixMatch = $hasSubmenu && collect($slugList)->contains(
                    fn ($slug) => is_string($slug) && str_starts_with($currentRouteName, $slug)
                );
                $activeClass = $isExactMatch ? 'active' : ($hasPrefixMatch ? $active : '');
            @endphp

            <li class="menu-item {{$activeClass}}">
                <a href="{{ isset($submenu['url']) ? url($submenu['url']) : 'javascript:void(0)' }}" class="{{ $hasSubmenu ? 'menu-link menu-toggle' : 'menu-link' }}" @if (isset($submenu['target']) and !empty($submenu['target'])) target="_blank" @endif>
                @if (isset($submenu['icon']))
                    <i class="{{ $submenu['icon'] }}"></i>
                @endif
                <div>{{ isset($submenu['name']) ? __($submenu['name']) : '' }}</div>
                @if (isset($submenu['badge']))
                    <div class="badge rounded-pill bg-{{ $submenu['badge'][0] }} text-uppercase ms-auto">{{ $submenu['badge'][1] }}</div>
                @endif
                </a>

                @if ($hasSubmenu && $depth < 2)
                @include('layouts.sections.menu.submenu', ['menu' => $submenu['submenu'], 'depth' => $depth + 1])
                @endif
            </li>
        @endforeach
    @endif
</ul>
