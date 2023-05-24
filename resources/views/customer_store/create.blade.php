<!doctype html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('assets') }}/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />
    {{-- <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" /> --}}

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
    <!-- /Vendor -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />

    <!--- custom css -->
    <link rel="stylesheet" href="{{ asset('assets/css/developer.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/loader.css') }}" />
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-misc.css') }}" />
    <!-- jquery-confirm/ -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />

    <script>
        let assets_path = "{{ asset('assets') }}/";
    </script>
    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

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
    <div class="">
        <div class="">
            <!-- Layout container -->
            <div class="">
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card mb-4">
                                <div
                                    class="card-header p-3 border-bottom d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Integrate Store</h5>

                                </div>

                                <div class="card-body pb-0">

                                    <!-- Create Deal Wizard -->
                                    <div id="wizard-create-deal" class="bs-stepper vertical no-shadow">

                                        <div class="bs-stepper-header">
                                            <div class="step" data-target="#choose-store">
                                                <button type="button" class="step-trigger">
                                                    <span class="bs-stepper-circle">
                                                        1
                                                    </span>
                                                    <span class="bs-stepper-label">
                                                        <span class="bs-stepper-title">Amazon MarketPlace</span>
                                                    </span>
                                                </button>
                                            </div>
                                            <div class="line"></div>
                                            <div class="step" data-target="#login-with-amazon">
                                                <button type="button" class="step-trigger">
                                                    <span class="bs-stepper-circle">
                                                        2
                                                    </span>
                                                    <span class="bs-stepper-label">
                                                        <span class="bs-stepper-title">Visit Seller Central</span>
                                                    </span>
                                                </button>
                                            </div>
                                            <div class="line"></div>
                                            <div class="step @if ($amazonLoginSuccess == 1) active @endif"
                                                data-target="#enter-store-name">
                                                <button type="button" class="step-trigger">
                                                    <span class="bs-stepper-circle">
                                                        3
                                                    </span>
                                                    <span class="bs-stepper-label">
                                                        <span class="bs-stepper-title">Store Detail</span>
                                                    </span>
                                                </button>
                                            </div>

                                        </div>

                                        <div class="bs-stepper-content">
                                            <form id="formStoreAdd" onSubmit="return false"
                                                action="{{ route('store.store') }}">
                                                @csrf
                                                @method('POST')
                                                <input type="hidden" name="id"
                                                    value="{{ $user ? $user->id : '' }}">
                                                <!-- Choose Store : Start -->
                                                @if ($amazonLoginSuccess == 0)
                                                    <div id="choose-store" class="content">
                                                        <h6 class="text-center mb-4">Amazon MarketPlace</h6>
                                                        <div class="row">

                                                            <div style="min-height:250px; display: block;">
                                                                <div class="col-12">
                                                                    <img src="illustrations/shopping-girl-light.png"
                                                                        class="img-fluid w-100 border rounded-2"
                                                                        alt="shopping girl"
                                                                        data-app-light-img="illustrations/shopping-girl-light.png"
                                                                        data-app-dark-img="illustrations/shopping-girl-dark.png" />
                                                                </div>

                                                                <div class=" mt-4 mb-3">

                                                                    <div class="col-sm-6">
                                                                        <label class="form-label required"
                                                                            for="store_type">Amazon
                                                                            MarketPlace</label>
                                                                        <select name="store_type" class="form-select"
                                                                            id="store_type">
                                                                            <option value="">Select Amazon
                                                                                MarketPlace</option>
                                                                            @foreach ($store_types as $key => $store_type)
                                                                                <option
                                                                                    value="{{ $store_type->store_type }}"
                                                                                    @if (old('store_type') == $store_type->store_type) selected @endif>
                                                                                    {{ $store_type->store_type }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @if (!empty($stores) && count($stores) > 0)
                                                                <div class="col-12 ">
                                                                    <label class="form-label ">
                                                                        Your added stores
                                                                    </label>
                                                                    <div class="table-responsive">
                                                                        <table class="table border-top">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="text-truncate">Store
                                                                                        Name </th>
                                                                                    <th class="text-truncate">Store
                                                                                        Type </th>
                                                                                    <th class="text-truncate">Merchant
                                                                                        Id</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($stores as $store)
                                                                                    <tr>
                                                                                        <td class="text-truncate">
                                                                                            {{ $store->store_name }}
                                                                                        </td>
                                                                                        <td class="text-truncate">
                                                                                            {{ $store->store_type }}
                                                                                        </td>
                                                                                        <td class="text-truncate">
                                                                                            {{ $store->storeCredentials ? $store->storeCredentials->merchant_id : '' }}
                                                                                        </td>

                                                                                    </tr>
                                                                                @endforeach


                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            <hr>
                                                            <div class="col-12 d-flex justify-content-end">

                                                                <button type="button"
                                                                    class="btn rounded-pill btn-outline-primary btn-next">
                                                                    <span
                                                                        class="d-sm-inline-block d-none me-sm-1">Next</span>
                                                                    <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Choose Store : End -->

                                                    <!-- login-with-amazon : Start -->
                                                    <div id="login-with-amazon" class="content">
                                                        <h6 class="text-center mb-4">Seller Central </h6>
                                                        <div class="row">

                                                            <div class="d-flex  mt-5" style="min-height: 150px;">
                                                                <p><span>Visit</span>
                                                                    <button type="button" id="btn-store-type "
                                                                        class="btn rounded-pill btn-primary mx-2"
                                                                        onclick="open_new_window();">Seller Central
                                                                    </button>
                                                                    <span>and login with your seller account</span>
                                                                </p>
                                                            </div>
                                                            <hr>
                                                            <div class="col-12 d-flex justify-content-between">
                                                                <button type="button"
                                                                    class="btn rounded-pill btn-outline-dark btn-prev">
                                                                    <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                                                                    <span
                                                                        class="d-sm-inline-block d-none">Previous</span>
                                                                </button>
                                                                <button type="button"
                                                                    class="btn rounded-pill btn-outline-primary"
                                                                    onclick="open_new_window();">
                                                                    <span
                                                                        class="d-sm-inline-block d-none me-sm-1">Next</span>
                                                                    <i class="bx bx-chevron-right bx-sm me-sm-n2"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- login-with-amazon : End -->

                                                    <!-- Enter-store-name : Start -->
                                                @endif

                                                <div id="enter-store-name"
                                                    class="content @if ($amazonLoginSuccess == 1) active @endif"
                                                    style="@if ($amazonLoginSuccess == 1) @else display:none; @endif">
                                                    <h6 class="text-center mb-4">Store Detail</h6>

                                                    <div style="min-height:250px; display: block;">
                                                        <div class="row g-3">
                                                            <input type="hidden" value="" name="store_type"
                                                                id="store_type">
                                                            <input type="hidden" name="access_token"
                                                                id="access_token"
                                                                value="{{ $sellerDetail['access_token'] ?? '' }}">

                                                            <div class="col-sm-6">
                                                                <label class="form-label" for="store_name">Store
                                                                    Name</label>
                                                                <input type="text" id="store_name"
                                                                    name="store_name" class="form-control"
                                                                    autocomplete="off">
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label class="form-label"
                                                                    for="marketplace_name">Marketplace
                                                                    Name</label>
                                                                <input type="text" id="marketplace_name"
                                                                    value="" name="marketplace_name"
                                                                    class="form-control" readonly />
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label class="form-label" for="merchant_id">Seller
                                                                    ID</label>
                                                                <input type="text"
                                                                    value="{{ $sellerDetail['selling_partner_id'] ?? '' }}"
                                                                    readonly id="merchant_id" name="merchant_id"
                                                                    class="form-control" />
                                                            </div>
                                                            <div class="col-sm-6">
                                                                <label class="form-label" for="mws_auth_token">Refresh
                                                                    Token</label>
                                                                <input type="text" id="mws_auth_token"
                                                                    value="{{ $sellerDetail['refresh_token'] ?? '' }}"
                                                                    name="refresh_token" class="form-control"
                                                                    readonly />
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-12 d-flex justify-content-end">

                                                        <button type="submit"
                                                            class="btn rounded-pill btn-outline-primary btnsubmit me-2">Submit</button>
                                                        <a href="{{ route('store.index') }}"
                                                            class="btn rounded-pill btn-outline-dark btn-prev"
                                                            Title="Click to redirect store listing">

                                                            <span class="d-sm-inline-block d-none">Cancel</span>
                                                        </a>
                                                    </div>
                                                    <!-- Enter-store-name : End -->
                                                </div>

                                            </form>
                                        </div>

                                    </div>
                                    <!-- /Create Deal Wizard -->
                                </div>


                            </div>
                        </div>
                    </div>
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
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>

    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
    <script src="{{ asset('assets/js/ui-toasts.js') }}"></script>



    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <!-- Custom JS -->
    <script src="{{ asset('assets/js/custom/common.js') }}"></script>

    <!-- Page JS -->
    <script>
        var redirect_route = "{{ route('store.index') }}"
        var amazonLoginSuccess = "{{ $amazonLoginSuccess }}";
        $(document).ready(function() {

            var store_type = $('#store_type').val();
            var store_type = sessionStorage.getItem("store_type");

            if (amazonLoginSuccess == 1) {
                if (typeof store_type !== 'object') {
                    $("#store_type").val(store_type);
                }
            }
            $("#marketplace_name").val($('#store_type').val());
        });

        function open_new_window() {
            var url = "https://sellercentral.amazon.com/apps/authorize/consent?application_id=" +
                "{{ config('amazon.AWS_APPLICATION_ID') }}" + "&state=abc123&version=beta";

            var store_type_val = $("#store_type").val();
            console.log(store_type_val);
            window.sessionStorage.setItem("store_type", store_type_val);

            var configuration = JSON.parse('<?php echo json_encode($configuration); ?>');

            var url = configuration[store_type_val].seller_central_link;

            var store_type = sessionStorage.getItem("store_type");
            window.location.href = url;
        }
    </script>

    <script src="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>
    <script src="{{ asset('assets/js/wizard-ex-create-deal.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\StoreAddRequest', '#formStoreAdd') !!}
    <!-- jquery-confirm/ -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="{{ asset('assets/js/custom/store/validation.js') }}"></script>

</body>

</html>
