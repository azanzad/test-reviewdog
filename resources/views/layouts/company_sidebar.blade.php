<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('home') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('assets/img/logo.svg') }}" style="width:47px" alt="">
            </span>
            <span class="app-brand-text demo menu-text fw-bold">
                <img src="{{ asset('assets/img/logo.svg') }}" alt="Logo" style="width:47px" class="dark-logo">
                <img src="{{ asset('assets/img/logo-white.svg') }}" alt="Logo" style="width:47px" class="d-none white-logo">
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx menu-toggle-icon d-none d-xl-block fs-4 align-middle"></i>
            <i class="bx bx-x d-block d-xl-none bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-divider mt-0"></div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item {{ Request::segment(1) === 'home' ? 'active' : '' }}">
            <a href="{{ route('home') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-heart"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>


        <li class="menu-item {{ Request::segment(1) === 'request' ? 'active' : '' }}">
            <a href="{{ route('request.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-shopping-bag"></i>
                <div data-i18n="Review Automation">Review Automation</div>
            </a>
        </li>


    </ul>
</aside>