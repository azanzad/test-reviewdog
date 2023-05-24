@extends('layouts.app')
@section('content')
    @include('common.flash')
    <div class="container-fluid flex-grow-1 container-p-y">
        <h6 class="py-0 breadcrumb-wrapper">
            <span class="text-muted fw-light">
                <a href="{{ route('home') }}"><i class="mb-1 bx bx-home-heart"></i> </a>
            </span>
            <span class="text-muted fw-normal">Payment</span>

        </h6>
        <div class="row">
            <div class="col-sm-12">
                <div class="card mb-4">
                    <div class="card-header p-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Payment</h5>
                        <a href="{{ url()->previous() }}"
                            class="btn btn-sm rounded-pill btn-outline-primary font-weight-bold float-end"><i
                                class="fa fa-angle-double-left"></i> Back</a>
                    </div>
                    <form id="formMakePayment" class="formsubmit" method="POST" enctype="multipart/form-data"
                        onsubmit="return false;" action="{{ route('cards_makepayment') }}">
                        @csrf
                        @method('POST')
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="alert alert-success " role="alert">
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
                            </div>
                            <div class="row">
                                <div class="col-sm-4 mb-4">
                                    <label for="" class="form-label required">Payment Method</label>
                                    <select name="payment_method" class="form-select" required>
                                        <option value="">Select Payment Method</option>
                                        @foreach ($cards as $key => $card)
                                            <option value="{{ $card->id }}"
                                                @if (old('payment_method') == $card->id) selected @endif>{{ $card->brand }}
                                                (****{{ $card->last_number }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer border-top pt-0 ">
                            <div class="demo-inline-spacing ">
                                <button type="submit" class="btn rounded-pill btn-outline-primary btnsubmit me-2">Make
                                    Payment
                                    <i class="loading-spinner fa fa-lg fa fa-spinner fa-spin"></i>
                                </button>
                                <a href="{{ route('home') }}" class="btn rounded-pill btn-outline-dark">Cancel</a>
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
    <!-- jquery-confirm/ -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endpush
@push('js')
    <!-- Page JS -->
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\PaymentRequest', '#formMakePayment') !!}
    <!-- jquery-confirm/ -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script>
        var redirect_route = "{{ route('profile.current_plan') }}";
    </script>
    <script src="{{ asset('assets/js/custom/payment/validation.js') }}"></script>
@endpush
