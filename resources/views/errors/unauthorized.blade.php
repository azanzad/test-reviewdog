<!DOCTYPE html>

<html lang="en" class="light-style" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('assets') }}/"
    data-template="vertical-menu-template">
@include('layouts.head')

<body>
    <!-- Content -->

    <!-- Not Authorized -->
    <div class="container-xxl container-p-y">
        <div class="misc-wrapper">
            <h1 class="mb-2 mx-2">You are not authorized!</h1>
            <p class="mb-4 mx-2">You don’t have permission to access this page. Go Home!!</p>
            <a href="{{ route('home') }}" class="btn rounded-pill btn-outline-primary">Back to home</a>
            <div class="mt-5">
                <img src="assets/img/illustrations/girl-hacking-site-light.png" alt="page-misc-error-light"
                    width="350" class="img-fluid" data-app-light-img="illustrations/girl-hacking-site-light.png"
                    data-app-dark-img="illustrations/girl-hacking-site-dark.png" />
            </div>
        </div>
    </div>
    <!-- /Not Authorized -->

    <!-- / Content -->
    @include('layouts.scripts')

</body>


</html>
