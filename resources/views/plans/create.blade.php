@extends('layouts.app')
@section('content')
    @include('common.flash')
    <div class="container-fluid flex-grow-1 container-p-y">
        <h6 class="py-0 breadcrumb-wrapper">
            <span class="text-muted fw-light">
                <a href="{{ route('home') }}"><i class="mb-1 bx bx-home-heart"></i> </a>
            </span>
            <span class="text-muted fw-light">
                <a href="{{ route('plans.index') }}">Subscription Plans</a> /
            </span>
            <span class="text-muted fw-normal">Add New Plan</span>

        </h6>
        <div class="row">
            <div class="col-sm-12">
                <div class="card mb-4">
                    <div class="card-header p-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Add New Plan</h5>
                        <a href="{{ url()->previous() }}"
                            class="btn btn-sm rounded-pill btn-outline-primary font-weight-bold float-end"><i
                                class="fa fa-angle-double-left"></i> Back</a>
                    </div>

                    <input type="hidden" id="redirect_route" value="{{ route('plans.index') }}">
                    <form id="formPlanAdd" class="formsubmit" method="POST" enctype="multipart/form-data"
                        onsubmit="return false;" action="{{ route('plans.store') }}">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="redirect_url" value="{{ route('plans.index') }}">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-sm-4 mb-4">
                                    <label for="" class="form-label required">Plan Type</label>
                                    <select name="plan_type" class="form-select" required>
                                        <option value="">Select Plan Type</option>
                                        @foreach (config('params.plan_types') as $key => $value)
                                            <option value="{{ $key }}"
                                                @if (old('plan_type') == $key) selected @endif>{{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4 mb-4">
                                    <label for="" class="form-label required">Plan Name</label>
                                    <input type="text" name="name" class="form-control" id=""
                                        value="{{ old('name') }}" placeholder="Plan Name" required>
                                </div>
                                <div class="col-sm-4 mb-4">
                                    <label for="select2Basic" class="form-label required">Plan Duration</label>
                                    <select id="interval" name="interval" class="select2 form-select"
                                        data-allow-clear="true" required>
                                        <option value="">Select Plan Duration</option>
                                        @foreach (config('params.plan_durations') as $key => $plan_duration)
                                            <option value="{{ $key }}"
                                                @if (old('interval') == $key) selected @endif>{{ $plan_duration }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4 mb-4">
                                    <label for="" class="form-label required">Duration Count</label>
                                    <input type="text" name="interval_count" class="form-control numberOrDecimalOnly"
                                        min="1" id="" value="{{ old('interval_count') }}"
                                        placeholder="No. of day/week/month">
                                    <div id="defaultFormControlHelp" class="form-text">The number of intervals between
                                        subscription billings</div>
                                </div>
                                <div class="col-sm-4 mb-4">
                                    <label for="" class="form-label required">Price($)</label>
                                    <input type="text" min="1" name="amount"
                                        class="form-control numberOrDecimalOnly" id="" value="{{ old('amount') }}"
                                        placeholder="Plan Price" required>
                                </div>

                                <div class="col-sm-4 mb-4">
                                    <label for="" class="form-label required">Status</label>
                                    <select name="status" class="form-select" required>
                                        @foreach (config('params.status') as $key => $value)
                                            <option value="{{ $key }}"
                                                @if (old('status') == $key) selected @endif>{{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4 mb-4">
                                    <label for="" class="form-label">Trial days</label>
                                    <input type="number" min="1" name="trial_days" id="trial_days"
                                        class="form-control numberOrDecimalOnly" value="{{ old('trial_days') }}"
                                        placeholder="Trial Days">
                                </div>
                                <div class="col-sm-4 mb-4">
                                    <label class="form-label required">Annual amazon sales</label>
                                    <div class="d-flex">
                                        <input type="number" name="annual_sales_from" min="0" id="annual_sales_from" class="form-control" placeholder="Sales From">
                                        <input type="number" name="annual_sales_to" min="0" id="annual_sales_to" class="form-control" placeholder="Sales To">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer border-top pt-0 ">
                            <div class="demo-inline-spacing ">
                                <button type="submit" class="btn rounded-pill btn-outline-primary btnsubmit me-2">Save
                                    <i class="loading-spinner fa fa-lg fa fa-spinner fa-spin"></i>
                                </button>
                                <a href="{{ route('plans.index') }}" class="btn rounded-pill btn-outline-dark">Cancel</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\SubscriptionPlanAddRequest', '#formPlanAdd') !!}
    <!-- jquery-confirm/ -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="{{ asset('assets/js/custom/plans/validation.js') }}"></script>
@endpush
