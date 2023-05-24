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
        @if (checkPermission([config('params.admin_role')]))
        <li class="menu-item {{ Request::segment(1) === 'plans' ? 'active' : '' }}">
            <a href="{{ route('plans.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-list-ul"></i>
                <div data-i18n="Subscription Plans">Subscription Plans</div>
            </a>
        </li>
        @endif
        @if (checkPermission([config('params.admin_role')]))
        <li class="menu-item {{ Request::segment(1) === 'company' ? 'active' : '' }} {{ Request::segment(1) === 'customer' ? 'active' : '' }}">
            <a href="{{ route('company.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-building-house"></i>
                <div data-i18n="Customers">Customers</div>
            </a>
        </li>
        @endif

        <li class="menu-item">
            <a href="{{ route('payment_transaction.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-credit-card"></i>
                <div data-i18n="Transactions">Transactions</div>
            </a>
        </li>

    </ul>
</aside>