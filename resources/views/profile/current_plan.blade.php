
@extends('layouts.app')
@section('content')
    @include('common.flash')
    <div class="container-fluid flex-grow-1 container-p-y">
        <h6 class="py-0 breadcrumb-wrapper">
            <div class="row">
                <div class="col align-self-md-center">
                    <span class="text-muted fw-light">
                        <a href="{{ route('home') }}"><i class="mb-1 bx bx-home-heart"></i> </a>
                    </span>
                    <span class="text-muted fw-normal">My Subscription</span>
                </div>
                <div class="col-auto">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('payment_transaction.index') }}"
                            class="btn btn-sm rounded-pill btn-outline-primary font-weight-bold float-end me-2"><i
                                class="fa-regular fa-money-bill-1 fa-fw"></i> Transactions</a>


                        <a href="{{ route('cards.index') }}" type="button"
                            class="btn btn-sm rounded-pill btn-outline-primary me-2">
                            <i class="fa-regular fa-credit-card  fa-fw"></i> Cards</a>


                    </div>
                </div>
            </div>
        </h6>

        <div class="row">
            <div class="col-md-12">

                <div class="card mb-4">
                    <h5 class="card-header p-3 border-bottom">My Subscription </h5>
                    <div class="card-body pt-3 pb-2">
                        <!-- basic details -->
                        <div class="row">
                            @if (empty($default_card))
                                <div class="col-md-12">

                                    <div class="alert alert-warning" role="alert">
                                        <span><i class="menu-icon tf-icons bx bx-credit-card"></i> Add the card details
                                            first and then active your subscription here. </span>

                                        <a href="{{ route('cards.create') }}" type="button"
                                            class="btn btn-sm rounded-pill btn-outline-primary float-right">
                                            Add Card</a>

                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-6 ">
                                <ul class="list-unstyled">
                                    <li class="d-flex align-items-center mb-3">
                                        <i class="fa-sharp fa-solid fa-list fa-fw"></i>
                                        <span class="fw-semibold mx-2">
                                            Plan Name:</span>
                                        <span>{{ $user->getPlan->name ?? '' }}</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-3">
                                        <i class="fa-solid fa-money-bill fa-fw"></i>
                                        <span class="fw-semibold mx-2">Plan amount:</span>
                                        <span>${{ $user->getPlan->amount ?? '' }}</span>
                                    </li>
                                    @if($user->discount_price > 0)
                                        <li class="d-flex align-items-center mb-3">
                                            <i class="fa-solid fa-tags"></i>
                                            <span class="fw-semibold mx-2">Discount:</span>
                                            <span>${{ $user->discount_price}}</span>
                                        </li>
                                        <li class="d-flex align-items-center mb-3">
                                            <i class="fa-solid fa-money-bill fa-fw"></i>
                                            <span class="fw-semibold mx-2">Amount to pay:</span>
                                            <span>${{ $user->getPlan->amount - $user->discount_price}}</span>
                                        </li>
                                    @endif
                                    <li class="d-flex align-items-center mb-3">
                                        <i class="fa-solid fa-money-check fa-fw"></i>
                                        <span class="fw-semibold mx-2">Billing Cycle:</span> <span>Every
                                            {{ $user->getPlan->interval_count ?? '' }}
                                            {{ $user->getPlan ? config('params.plan_durations.' . $user->getPlan->interval) : '' }}</span>
                                    </li>
                                    <li class="d-flex align-items-center mb-3">
                                        <i class="fa-regular fa-credit-card fa-fw"></i>
                                        <span class="fw-semibold mx-2"> Paying from card:</span>
                                        <span>
                                            @if (empty($default_card))
                                                N/A
                                            @else
                                                ***** {{ $default_card->last_number }}
                                            @endif
                                        </span>
                                    </li>
                                    @if (!empty(isCompanyPayment(auth()->user()->id)))
                                        <li class="d-flex align-items-center mb-3">
                                            <i class="fa-solid fa-circle-arrow-right fa-fw"></i>
                                            <span class="fw-semibold mx-2">Status:</span>
                                            @if (!empty($current_subscription->stripe_status) && $current_subscription->stripe_status == 'active')
                                                <span
                                                    class="badge rounded-pill bg-success ">{{ ucwords($current_subscription->stripe_status) }}</span>
                                            @elseif (!empty($current_subscription->stripe_status) && $current_subscription->stripe_status == 'trialing')
                                                <span
                                                    class="badge rounded-pill bg-warning ">{{ ucwords($current_subscription->stripe_status) }}</span>
                                            @elseif (!empty($current_subscription->stripe_status) && $current_subscription->stripe_status == 'canceled')
                                                <span
                                                    class="badge rounded-pill bg-danger">{{ ucwords($current_subscription->stripe_status) }}</span>
                                            @endif

                                        </li>

                                        @if (!empty(auth()->user()->trial_ends_at))
                                            <li class="d-flex align-items-center mb-3">
                                                <i class="fa-sharp fa-solid fa-calendar-days fa-fw"></i>
                                                <span class="fw-semibold mx-2">Trial ends:</span>
                                                <span>{{ date('d M Y h:i a', strtotime(ConvertTimezone(auth()->user()->trial_ends_at))) }}</span>
                                            </li>
                                        @endif
                                        <li class="d-flex align-items-center mb-3">
                                            <i class="fa-sharp fa-solid fa-calendar-days fa-fw"></i>
                                            <span class="fw-semibold mx-2">Next Billing Date:</span>
                                            <span>
                                                @if (!empty(auth()->user()->next_billing_date))
                                                    {{ date('d M Y h:i a', strtotime(ConvertTimezone(auth()->user()->next_billing_date))) }}
                                                @else
                                                    N/A
                                                @endif
                                            </span>
                                        </li>
                                    @endif

                                </ul>


                            </div>
                            <div class="col-md-6 mb-1">

                            </div>
                        </div>
                        <!-- /basic details -->
                    </div>
                    <div class="card-footer border-top pt-0 ">
                        <div class="demo-inline-spacing ">
                            <form method="post" id="formMySubscription" action="{{ route('subscription.store') }}"
                                onsubmit="return false;">
                                @csrf
                                @method('POST')
                                @if (!empty(auth()->user()->subscription_id) && auth()->user()->is_plan_active == 1)
                                    <input type="hidden" name="subscription_type" id="subscription_type" value="cancel">
                                    <button type="submit" class="btn rounded-pill btn-outline-danger btnsubmit me-2">Cancel
                                        Subscription
                                        <i class="loading-spinner fa fa-lg fa fa-spinner fa-spin"></i>
                                    </button>
                                @else
                                    <input type="hidden" id="subscription_type" name="subscription_type" value="active">
                                    <button type="submit" class="btn rounded-pill btn-outline-primary btnsubmit me-2"
                                        title="Make Payment" @if (empty($default_card)) disabled @endif>Active
                                        Subscription
                                        <i class="loading-spinner fa fa-lg fa fa-spinner fa-spin"></i>
                                    </button>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@push('css')
    <!-- jquery-confirm/ -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endpush
@push('js')
    <!-- jquery-confirm/ -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="{{ asset('assets/js/custom/profile/subscription.js') }}"></script>
@endpush
