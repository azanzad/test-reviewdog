<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="container-fluid">
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
            </a>
        </div>

        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">


            <ul class="navbar-nav flex-row align-items-center ms-auto">

                <!-- Style Switcher -->
                <li class="nav-item me-2 me-xl-0">
                    <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
                        <i class="bx bx-sm"></i>
                    </a>
                </li>
                <!--/ Style Switcher -->


                <!-- User -->
                @php
                    $profile_image = empty(auth()->user()->profile_image) ? asset('assets/img/default-user.png') : Storage::disk('public')->url(auth()->user()->profile_image);
                @endphp
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <div class="avatar avatar-online">
                            <img src="{{ $profile_image }}" alt class="rounded-circle" />
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.index') }}">
                                <div class="d-flex">

                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online">
                                            <img src="{{ $profile_image }}" alt class="rounded-circle" />
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="fw-semibold d-block lh-1">{{ auth()->user()->name }}</span>
                                        @if (auth()->user()->customer_type == config('params.individual_brand') &&
                                            auth()->user()->role == config('params.user_roles.company'))
                                            <small>{{ ucfirst(config('params.individual_company')) }}</small>
                                        @else
                                            <small>{{ ucfirst(config('params.get_user_roles.' . auth()->user()->role)) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.index') }}">
                                <i class="bx bx-user me-2"></i>
                                <span class="align-middle">My Profile</span>
                            </a>
                        </li>
                        @if (auth()->user()->role == config('params.user_roles.company'))
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.current_plan') }}">
                                    <i class="bx bx-user me-2"></i>
                                    <span class="align-middle">My Subscription</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('settings.index') }}">
                                    <i class="bx bx-user me-2"></i>
                                    <span class="align-middle">Settings</span>
                                </a>
                            </li>
                        @endif
                        <li>
                            <a class="dropdown-item" href="{{ route('change_password') }}">
                                <i class="bx bx-lock-alt me-1"></i>
                                <span class="align-middle">Change Password</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bx bx-power-off me-2"></i>
                                <span class="align-middle">Log Out</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
                <!--/ User -->
            </ul>
        </div>

        <!-- Search Small Screens -->
        <div class="navbar-search-wrapper search-input-wrapper d-none">
            <input type="text" class="form-control search-input container-fluid border-0" placeholder="Search..."
                aria-label="Search..." />
            <i class="bx bx-x bx-sm search-toggler cursor-pointer"></i>
        </div>
    </div>
</nav>
