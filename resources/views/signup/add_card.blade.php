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
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/loader.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/signup.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/loader.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
</head>

<body>
    <div id="overlay" style="display: none">
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
                        <img src="{{asset('assets/img/logo.svg')}}" style="width: 50px;" />
                    </a>
                    @if ($plan->trial_days > 0)
                        <div class="payment-header">
                            <h5 class="text-light-gray">Try {{ $plan->name }}</h5>
                            <h2 class="mb-0">{{$plan->trial_days}} {{($plan->trial_days>1)?'Days':'Day'}} Free </h2>
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
                        {{-- <div class="row mt-4">
                            <div class="col">{{ $plan->name }}</div>
                            <div class="col text-end">{{ $plan->trial_days }} {{ trans('message.label.day_free') }}
                            </div>
                        </div> --}}
                    @else
                        <div class="payment-header">
                            <h5 class="text-light-gray">{{ $plan->name }}</h5>
                            <small class="fw-600">${{ $plan->amount }} per {{ $plan->interval_count }}
                                {{ config('params.plan_durations.' . $plan->interval) }}</small>

                        </div>
                    @endif

                    <div class="payment-footer mt-4 pb-3">
                        <div class="row border-bottom fw-bold">
                            <div class="col">Subtotal</div>
                            <div class="col text-end">$<span
                                    id="subtotal_amount">{{ number_format((float) $plan->amount, 2, '.', '') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="add-promotion-btn">
                        <div class="d-flex align-content-center">
                            <input type="text" class="form-control" placeholder="Add promotion code"
                                id="promocode_value" autocomplete="off" />
                            <div class="ms-2">
                                <button type="button" id="apply_btn" class="btn btn-primary btn-outline-primary  me-2">Apply</button>
                                <button type="button" id="cancel_btn" class="d-none btn btn-danger btn-outline-danger  me-2">Remove</button>
                            </div>
                        </div>
                        <span id="promotion_message"></span>
                    </div>

                    <div class="row mt-4">
                        <div class="col">Promotion code</div>
                        <div class="col text-end amount">$<span id="promotion_amount">0.00</span></div>
                    </div>
                    <div class="row mt-4">
                        <div class="col">Tax</div>
                        <div class="col text-end">$<span>0.00</span></div>
                    </div>
                    @if ($plan->trial_days > 0)
                        <div class="row mt-4">
                            <div class="col">Total after trial</div>
                            <div class="col text-end">$<span
                                    id="afterFreeTrial_amount">{{ number_format((float) $plan->amount, 2, '.', '') }}</span>
                            </div>
                        </div>
                        <div class="row border-bottom mt-4 pb-2 fw-bold fs-16px">
                            <div class="col">Total due today</div>
                            <div class="col text-end">$0.00</div>
                        </div>
                    @else
                        <div class="row border-bottom mt-4 pb-2 fw-bold fs-16px">
                            <div class="col">Total due today</div>
                            <div class="col text-end">$<span
                                    id="afterFreeTrial_amount">{{ number_format((float) $plan->amount, 2, '.', '') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-12 col-md-6">
                <form action="{{ route('store-usercard') }}" method="post" id="add_card_fr">
                    @csrf
                    @method('post')
                    <input type="hidden" name="promotion_token" id="promotion_token" />
                    <div class="p-5 payment-right-box">
                        <div class=" payment-header">
                            <h4>Card Information</h4>
                        </div>

                        <div class="form-group mt-4">
                            <div id="card-element" class="form-control"></div>
                        </div>

                        <div class="form-group mt-4">
                            <label for="card-holder-name">Name of card</label>
                            <input type="text" name="card_holder_name" id="card-holder-name" class="form-control"
                                placeholder="Name of card">
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary d-block btnsubmit btn-outline-primary  me-2">
                                @if ($plan->trial_days > 0)
                                    Start trial
                                @else
                                    Make Payment
                                @endif
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @include('common.messageModal');
    </div>
    <!-- / Content -->
    <!-- Core JS -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe_key = "{{ config('params.stripe.key') }}";
        var redirect_route = "{{ route('home') }}";
        var applyPromotion = "{{ route('apply-promotion-code') }}";
    </script>

    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
    <script src="{{ asset('assets/js/ui-toasts.js') }}"></script>
    <script src="{{ asset('assets/js/custom/common.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\AddCardInfoRequest', '#add_card_fr') !!}
    <script src="{{ asset('assets/js/custom/signup/validation.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/custom/signup/add_card.js') }}"></script>

    @include('layouts.message')
</body>

</html>
