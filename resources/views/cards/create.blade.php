@extends('layouts.app')
@section('content')
    @php
        if (!empty($payment_type)) {
            $btn_text = 'Make Payment';
        } else {
            $btn_text = 'save';
        }
        $page_title = 'Add New Card';
        $redirect_route = route('cards.index');
        if (!empty($payment_type)) {
            $page_title = 'Payment';
            $redirect_route = route('profile.current_plan');
        }
    @endphp
    <div class="container-fluid flex-grow-1 container-p-y">
        <h6 class="py-0 breadcrumb-wrapper">
            <span class="text-muted fw-light">
                <a href="{{ route('home') }}"><i class="mb-1 bx bx-home-heart"></i> </a>
            </span>
            @if (empty($payment_type))
                <span class="text-muted fw-light">
                    <a href="{{ route('cards.index') }}">Cards</a> /
                </span>
            @endif
            <span class="text-muted fw-normal">{{ $page_title }}</span>
        </h6>
        <div class="row">
            <div class="col-sm-12">
                <div class="card mb-4">
                    <div class="card-header p-3 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">{{ $page_title }}</h6>
                        <a href="{{ url()->previous() }}"
                            class="btn btn-sm rounded-pill btn-outline-primary font-weight-bold float-end"><i
                                class="fa fa-angle-double-left"></i> Back</a>
                    </div>
                    <form action="{{ route('cards.store') }}" method="post" id="formCompanyCardAdd">
                        @csrf
                        @method('POST')
                        <div class="card-body  ">
                            <input type="hidden" id="form_payment_type" name="type" value="{{ $payment_type }}">
                            <input type="hidden" name="redirect_route" id="redirect_route" value="{{ $redirect_route }}">
                            @if (!empty($payment_type))
                                <div class="row">
                                    <div class="alert alert-success mb-4" role="alert">
                                        <h6 class="alert-heading mb-2 text-success">Your Current Plan is
                                            {{ auth()->user()->getPlan->name ?? '' }}</h6>
                                        <h6 class="alert-heading mb-1 text-success">
                                            ${{ auth()->user()->getPlan->amount ?? '' }}
                                            /
                                            {{ auth()->user()->getPlan->interval_count ?? '' }}
                                            {{ auth()->user()->getPlan ? config('params.plan_durations.' . auth()->user()->getPlan->interval) : '' }}
                                        </h6>
                                        @if (!empty(auth()->user()->is_trial))
                                            <h6 class="alert-heading mb-1 text-success">Trail days is
                                                {{ auth()->user()->trial_days }} days</h6>
                                            </li>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-sm-4 mb-4">
                                    <label for="" class="form-label required">Card-holder Name </label>
                                    <input id="card-holder-name" placeholder="Card-holder Name" name="name"
                                        type="text" class="form-control" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 mb-4">
                                    <div id="card-element" class="form-control">
                                        <!-- A Stripe Element will be inserted here. -->
                                    </div>
                                    <!-- Used to display Element errors. -->
                                    <div id="card-errors" class="error" role="alert"></div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer border-top pt-0 ">
                            <div class="demo-inline-spacing ">
                                <button type="submit" class="btn rounded-pill btn-outline-primary btnsubmit me-2"
                                    id="card-button">{{ $btn_text }}
                                    <i class="loading-spinner fa fa-lg fa fa-spinner fa-spin"></i>
                                </button>
                                <a href="{{ route('cards.index') }}" class="btn rounded-pill btn-outline-dark">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <!-- Page css -->
    <script src="https://js.stripe.com/v3/"></script>
    <!-- jquery-confirm/ -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endpush
@push('js')
    <!-- Page JS -->

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>

    {!! JsValidator::formRequest('App\Http\Requests\CardAddRequest', '#formCompanyCardAdd') !!}
    <!-- jquery-confirm/ -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script>
        var stripe_key = "{{ config('params.stripe.key') }}";
        var redirect_route = $('#redirect_route').val();
    </script>
    <script src="{{ asset('assets/js/custom/card/validation.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/custom/card/form.js') }}"></script> --}}
@endpush
