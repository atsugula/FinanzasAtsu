<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="{{ route('home') }}" target="_blank">
            <img src="{{ asset('img/logo-ct-dark.png') }}" class="navbar-brand-img h-100" alt="{{ __('main_logo') }}">
            <span class="ms-1 font-weight-bold">{{ env('APP_NAME', __('Argon Dashboard 2 by Creative Tim')) }}</span>
        </a>
    </div>
    @php
        $menuItems = config('menu');
    @endphp
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'home' ? 'active' : '' }}" href="{{ route('home') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">{{ __('Dashboard') }}</span>
                </a>
            </li>

            <hr class="horizontal dark mt-0">

            @foreach ($menuItems as $menuItem)
                @if ($menuItem['page'] == 1)
                    <li class="nav-item">
                        <a class="nav-link {{ str_contains(request()->url(), $menuItem['route']) ? 'active' : '' }}" href="{{ route('page', ['page' => $menuItem['route']]) }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="{{ $menuItem['icon'] }} text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __($menuItem['text']) }}</span>
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link {{ Route::currentRouteName() == $menuItem['route'] ? 'active' : '' }}" href="{{ route($menuItem['route']) }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="{{ $menuItem['icon'] }} text-dark text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">{{ __($menuItem['text']) }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</aside>
