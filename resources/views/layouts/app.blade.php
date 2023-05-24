<!doctype html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('assets') }}/" data-template="vertical-menu-template">
@include('layouts.head')

<body class="">
    <div id="overlay" style="display: none;">
        <div class="overlay__inner">
            <div class="overlay__content">
                {{-- <span class="spinner"></span> --}}
                <img src="{{ asset('assets/img/blue_loading.gif') }}">
            </div>
        </div>
    </div>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Side Menu -->

            @if (auth()->user()->role == config('params.company_role') && auth()->user()->customer_type == null)
                @include('layouts.company_sidebar')
            @elseif (auth()->user()->role == config('params.company_role') &&
                auth()->user()->customer_type == config('params.individual_brand'))
                @include('layouts.customer_sidebar')
            @else
                @include('layouts.sidebar')
            @endif

            <!-- / Side Menu -->
            <!-- Layout container -->
            <div class="layout-page">
                <!-- Header Navbar -->
                @include('layouts.header')
                <!-- /Header Navbar -->
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    @yield('content')
                    <!-- / Content -->
                    <div class="content-backdrop fade"></div>
                </div>
                <!-- /Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>
        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->
    <!-- Add New Credit Card Modal -->
    <div class="modal fade add_modal" id="add_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header border-bottom" style="padding: 10px 15px;">
                    <h5 class="modal-title">{{ __('message.Large Modal') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modalbody">
                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    @include('layouts.footer')
    @include('layouts.scripts')
    @include('layouts.message');
</body>

</html>
