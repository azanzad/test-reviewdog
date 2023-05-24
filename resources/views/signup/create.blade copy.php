<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Signup</title>
    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/loader.css') }}"  />
    <link rel="stylesheet" href="{{ asset('assets/css/intlTelInput.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/signup.css') }}"  />
    <link rel="stylesheet" href="{{ asset('assets/css/loader.css') }}"  />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
</head>

<body>
    <div id="overlay" style="display: none;">
        <div class="overlay__inner">
            <div class="overlay__content">

                <img src="http://127.0.0.1:8000/assets/img/blue_loading.gif">
            </div>
        </div>
    </div>
    <!-- Content -->
    <div class="container-fluid p-0 h-100">
        <!-- Register -->
        <div class="row m-0 h-100">
            <div class="col-12 col-md-6 payment-left-bg min-h-100">
                <div class="ms-auto p-5 payment-left-box">

                    <div class="payment-header">
                        <h5 class="text-light-gray">Try {{$plan->name}}</h5>
                        <h2 class="mb-0">30 Days Free</h2>
                        <small class="fw-600">Then ${{$plan->amount}} per month</small>
                    </div>
                    <div class="row mt-4">
                        <div class="col">{{$plan->name}}</div>
                        <div class="col text-end">30 days free</div>
                    </div>
                    <div class="row mt-4">
                        <div class="col text-end text-muted">${{$plan->amount}} / month after</div>
                    </div>
                    <div class="payment-footer mt-4 pb-3">
                        <div class="row border-bottom fw-bold">
                            <div class="col">Subtotal</div>
                            <div class="col text-end">${{$plan->amount}}</div>
                        </div>
                    </div>
                    <div class="add-promotion-btn">
                        {{-- <button class="btn btn-primary">Add promotion code</button> --}}
                        <input type="text" class="form-control" placeholder="Add promotion code" id="promocode_value" />
                        <button type="button" id="apply_btn" class="btn btn-primary">Apply</button>
                    </div>
                    <div class="row border-bottom mt-4 pb-2 text-muted promotion-code-row">
                        <div class="col">Promotion code</div>
                        <div class="col text-end amount">$<span>0.00</span></div>
                    </div>
                    <div class="row border-bottom mt-4 pb-2 text-muted">
                        <div class="col">Tax</div>
                        <div class="col text-end">$0.00</div>
                    </div>
                    <div class="row mt-4">
                        <div class="col">Total after trial</div>
                        <div class="col text-end">$9.99</div>
                    </div>
                    <div class="row border-bottom mt-4 pb-2 fw-bold fs-16px">
                        <div class="col">Total due today</div>
                        <div class="col text-end">$0.00</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <form  action="{{ route('send-otp') }}"  method="post" id="signup_fr">
                    @csrf
                    @method('post')
                    <input type="hidden" name="planid" id="planid" value="{{$plan->uuid}}" />

                    <div class="p-5 payment-right-box">
                        <div class=" payment-header">
                            <h4>Enter payment details</h4>
                        </div>

                        <div class="form-group">
                            <label for="" >Contact Information</label>
                            <div class="input-group">
                                <span class="input-group-text "><i class="bx bx-user fs-5"></i></span>
                                <input type="text" name="name" id="name" class="form-control" placeholder="{{__('message.label.name')}}" >
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group mt-2">
                                <span class="input-group-text "><i class="bx bx-envelope fs-5"></i></span>
                                <input type="email" name="email" id="email" class="form-control" placeholder="email@example.com">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group mt-2">
                                <input type="tel" name="contact_number" id="contact_number" class="form-control contact_number" placeholder="081 234 56789" aria-describedby="basic-icon-default-email2">
                                <input type="hidden" class="country_code" name="country_code"  value="us">
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <label >Card Information</label>
                            <div id="card-element" class="form-control"></div>
                        </div>

                        <div class="form-group mt-4">
                            <label for="card-holder-name">Name of card</label>
                            <input type="text" name="card_holder_name" id="card-holder-name" class="form-control" placeholder="Name of card">
                        </div>

                        <div class="form-group mt-2">
                            <label for="country_id">Contry or region</label>
                            <select name="country_id" id="country_id" class="form-control">
                                <option>Select Country</option>
                                @foreach ($countries as $country)
                                    <option value="{{$country->id}}" >{{$country->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group mt-4">
                            <div class="border p-2 rounded w-100">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" name="confirmationCheckbox" id="confirmationCheckbox">
                                    <label class="form-check-label fw-bold" for="confirmationCheckbox" id="confirmationCheckbox_lbl">
                                        Securely save my information for 1-click checkout
                                    </label>
                                </div>
                                <p class="text-muted mb-0 ms-4">Pay faster on UMA. IO and thousads of sites.</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary d-block btnsubmit">Start trial</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /Register -->
    </div>
    <!-- / Content -->
    <!-- Core JS -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe_key = "{{ config('params.stripe.key') }}";
        var redirect_route = "{{ route('verify-otp','') }}";
        var selectedcountrycodes = null;
        var applyPromotion = "{{ route('apply-promotion-code') }}";
    </script>

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    <script src="{{ asset('assets/js/custom/intlTelInput-jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
    <script src="{{ asset('assets/js/ui-toasts.js') }}"></script>
    <script src="{{ asset('assets/js/custom/common.js') }}"></script>
    <script>

        $("#contact_number").intlTelInput({
            initialCountry: "us",
        });

        $(document).ready(function(){
            $('#iti-0__country-listbox li').on('click', function(){
                var country_code = $(this).data('country-code');
                $('.country_code').val(country_code);
            });

            $('input[type="checkbox"]').click(function(){

                if($(this).prop("checked") == true){
                    $(this).val(1);
                }
                else if($(this).prop("checked") == false){
                    $(this).val('');
                }
            })


        });
    </script>

    {!! JsValidator::formRequest('App\Http\Requests\SignupRequest', '#signup_fr') !!}
    <script src="{{ asset('assets/js/custom/signup/validation.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/custom/signup/signup.js') }}"></script>

</body>

</html>
