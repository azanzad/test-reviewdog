@extends('layouts.app')
@section('content')
    @include('common.flash')
    <div class="container-fluid flex-grow-1 container-p-y">
        <h6 class="py-0 breadcrumb-wrapper">
            <span class="text-muted fw-light">
                <a href="{{ route('home') }}"><i class="mb-1 bx bx-home-heart"></i> </a>
            </span>
            <span class="text-muted fw-light">
                <a href="{{ route('store.index') }}">Store</a> /
            </span>
            <span class="text-muted fw-normal">Add New Store</span>

        </h6>
        <div class="row">
            <div class="col-sm-12">
                <div class="card mb-4">
                    <div class="card-header p-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Add New Store</h5>
                        <a href="{{ url()->previous() }}"
                            class="btn btn-sm rounded-pill btn-outline-primary font-weight-bold float-end"><i
                                class="fa fa-angle-double-left"></i> Back</a>
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
                                <form id="formStoreAdd" onSubmit="return false" action="{{ route('store.store') }}">
                                    @csrf
                                    @method('POST')
                                    <input type="hidden" name="id" value="{{ $user ? $user->id : '' }}">
                                    <!-- Choose Store : Start -->
                                    @if ($amazonLoginSuccess == 0)
                                        <div id="choose-store" class="content">
                                            <h6 class="text-center mb-4">Amazon MarketPlace</h6>
                                            <div class="row">

                                                <div style="min-height:250px; display: block;">
                                                    <div class="col-12">
                                                        <img src="illustrations/shopping-girl-light.png"
                                                            class="img-fluid w-100 border rounded-2" alt="shopping girl"
                                                            data-app-light-img="illustrations/shopping-girl-light.png"
                                                            data-app-dark-img="illustrations/shopping-girl-dark.png" />
                                                    </div>

                                                    <div class=" mt-4 mb-3">

                                                        <div class="col-sm-6">
                                                            <label class="form-label required" for="store_type">Amazon
                                                                MarketPlace</label>
                                                            <select name="store_type" class="form-select" id="store_type">
                                                                <option value="">Select Amazon MarketPlace</option>
                                                                @foreach ($store_types as $key => $store_type)
                                                                    <option value="{{ $store_type->store_type }}"
                                                                        @if (old('store_type') == $store_type->store_type) selected @endif>
                                                                        {{ $store_type->store_type }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>
                                                <div class="col-12 d-flex justify-content-end">

                                                    <button type="button"
                                                        class="btn rounded-pill btn-outline-primary btn-next">
                                                        <span class="d-sm-inline-block d-none me-sm-1">Next</span>
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
                                                            onclick="open_new_window();">Seller Central </button>
                                                        <span>and login with your seller account</span>
                                                    </p>
                                                </div>
                                                <hr>
                                                <div class="col-12 d-flex justify-content-between">
                                                    <button type="button"
                                                        class="btn rounded-pill btn-outline-dark btn-prev">
                                                        <i class="bx bx-chevron-left bx-sm ms-sm-n2"></i>
                                                        <span class="d-sm-inline-block d-none">Previous</span>
                                                    </button>
                                                    <button type="button" class="btn rounded-pill btn-outline-primary"
                                                        onclick="open_new_window();">
                                                        <span class="d-sm-inline-block d-none me-sm-1">Next</span>
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
                                                <input type="hidden" value="" name="store_type" id="store_type">
                                                <input type="hidden" name="access_token" id="access_token"
                                                    value="{{ $sellerDetail['access_token'] ?? '' }}">

                                                <div class="col-sm-6">
                                                    <label class="form-label" for="store_name">Store Name</label>
                                                    <input type="text" id="store_name" name="store_name"
                                                        class="form-control" autocomplete="off">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="form-label" for="marketplace_name">Marketplace
                                                        Name</label>
                                                    <input type="text" id="marketplace_name" value=""
                                                        name="marketplace_name" class="form-control" readonly />
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="form-label" for="merchant_id">Seller ID</label>
                                                    <input type="text"
                                                        value="{{ $sellerDetail['selling_partner_id'] ?? '' }}" readonly
                                                        id="merchant_id" name="merchant_id" class="form-control" />
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="form-label" for="mws_auth_token">Refresh
                                                        Token</label>
                                                    <input type="text" id="mws_auth_token"
                                                        value="{{ $sellerDetail['refresh_token'] ?? '' }}"
                                                        name="refresh_token" class="form-control" readonly />
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
    </div>
@endsection
@push('css')
    <!-- Page css -->
    <!-- jquery-confirm/ -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
@endpush
@push('js')
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
@endpush
