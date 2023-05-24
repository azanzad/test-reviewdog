<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="assets/"
    data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Signup</title>
    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />
    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css"
        data-assets-path="{{ asset('assets') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/css/loader.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/intlTelInput.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/signup.css') }}" />
</head>

<body>
    <div id="overlay" style="display: none;">
        <div class="overlay__inner">
            <div class="overlay__content">
                <img src="{{ URL::to('/assets/img/blue_loading.gif') }}">
            </div>
        </div>
    </div>
    <!-- Content -->
    <div class="container-fluid p-0 h-100">
        <!-- Register -->
        <div class="row m-0 h-100">
            <div class="col-12 col-md-6 payment-left-bg min-h-100">
                <div class="ms-auto p-5 payment-left-box">
                    <a class="mt-3 mb-3 d-inline-block text-primary">
                        <img src="{{ asset('assets/img/logo.svg') }}" style="width: 50px;" />
                    </a>
                    @if ($plan->trial_days > 0)
                        <div class="payment-header">
                            <h5 class="text-light-gray">Try {{ $plan->name }}</h5>
                            <h2 class="mb-0">{{ $plan->trial_days }} {{ $plan->trial_days > 1 ? 'Days' : 'Day' }} Free
                            </h2>
                            @if ($plan->interval > 0)
                                @if ($plan->interval_count == 1)
                                    <small class="fw-600">Then ${{ $plan->amount }} per
                                        {{ config('params.plan_durations.' . $plan->interval) }}</small>
                                @else
                                    <small class="fw-600">Then ${{ $plan->amount }} per {{ $plan->interval_count }}
                                        {{ config('params.plan_durations.' . $plan->interval) }}</small>
                                @endif
                            @endif
                        </div>
                    @else
                        <div class="payment-header">
                            <h5 class="text-light-gray">{{ $plan->name }}</h5>

                            @if ($plan->interval > 0)
                                <small class="fw-600">${{ $plan->amount }} per
                                    {{ $plan->interval_count > 1 ? $plan->interval_count : '' }}
                                    {{ config('params.plan_durations.' . $plan->interval) }}</small>
                            @endif
                        </div>
                    @endif
                    <div class="text-center signup-img"><img src="{{ asset('assets/img/signup-page-imge.png') }}"
                            style="width:100%; max-width:100%" /></div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <form action="{{ route('signup.store') }}" method="post" id="signup_fr">
                    @csrf
                    @method('post')
                    <input type="hidden" name="planid" id="planid" value="{{ $plan->uuid }}" />

                    <div class="p-5 payment-right-box">
                        <div class=" payment-header">
                            <h4>Enter contact details</h4>
                        </div>

                        <div class="form-group mb-2">
                            <label class="form-label">Contact Information</label>
                            <div class="input-group">
                                <span class="input-group-text "><i class="bx bx-user fs-5"></i></span>
                                <input type="text" name="name" id="name" class="form-control"
                                    placeholder="{{ __('message.label.name') }}">
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <div class="input-group mt-2">
                                <span class="input-group-text "><i class="bx bx-envelope fs-5"></i></span>
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="email@example.com">
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <div class="input-group mt-2">
                                <input type="tel" name="contact_number" id="contact_number"
                                    class="form-control contact_number" placeholder="081 234 56789"
                                    aria-describedby="basic-icon-default-email2">
                                <input type="hidden" class="country_code" name="country_code" value="us">
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <div class="input-group mt-2">
                                <span class="input-group-text "><i class="bx bx-key fs-5"></i></span>
                                <input type="password" class="form-control" name="password"
                                    placeholder="Enter pasword">
                                <span class="password-pattern-info">Use 8 or more characters with a mix of upper case,
                                    small, symbol and numbers.</span>
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <div class="input-group mt-2">
                                <span class="input-group-text "><i class="bx bx-key fs-5"></i></span>
                                <input type="password" class="form-control" name="password_confirmation"
                                    placeholder="Confirm pasword">
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <div class="input-group mt-2">
                                <span class="input-group-text"><i class="bx bx-flag fs-5"></i></span>
                                <select name="country_id" id="country_id" class="form-control">
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-3 mt-4">
                            <button type="submit" class="btn btn-primary d-block btnsubmit btn-outline-primary  me-2">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /Register -->
    </div>
    <!-- / Content -->

    <!-- Success Modal -->
    <div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-edit-user">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="text-success">{{trans('message.message.registration_success')}}</h3>
                        <p>{{session('success_modal')}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Core JS -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>

    <script>
        var stripe_key = "{{ config('params.stripe.key') }}";
        var selectedcountrycodes = null;
    </script>

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    <script src="{{ asset('assets/js/custom/intlTelInput-jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
    <script src="{{ asset('assets/js/ui-toasts.js') }}"></script>
    <script src="{{ asset('assets/js/custom/common.js') }}"></script>

    {!! JsValidator::formRequest('App\Http\Requests\SignupRequest', '#signup_fr') !!}
    <script type="text/javascript" src="{{ asset('assets/js/custom/signup/signup.js') }}"></script>

    @include('layouts.message')

    @if(Session::has('success_modal'))
        <script>
            setTimeout(() => {
                $('#editUser').modal('show');
            }, 100);
        </script>
    @endif
</body>

</html>
