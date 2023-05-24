@extends('layouts.app')
@section('content')
    @include('common.flash')
    <div class="container-fluid flex-grow-1 container-p-y">
        <h6 class="py-0 breadcrumb-wrapper">
            <span class="text-muted fw-light">
                <a href="{{ route('home') }}"><i class="mb-1 bx bx-home-heart"></i></a>
            </span>
            <span class="text-muted fw-normal">Settings</span>
        </h6>
        <div class="row">
            <div class="col-md-12">

                <div class="card mb-4">
                    <h5 class="card-header p-3 border-bottom">Settings </h5>

                    <form id="formEmailSetting" class="formsubmit" method="POST" enctype="multipart/form-data"
                        onsubmit="return false;" action="{{ route('settings.store') }}">
                        @csrf
                        @method('POST')
                        <div class="card-body">
                            <div class="mb-3 row">
                                <label for="html5-text-input" class="col-md-2 col-form-label">Timezone</label>
                                <div class="col-md-4 mt-2 ">

                                    <select name="timezone" class="form-control" >
                                        <option>Select Default Timezone</option>
                                        @foreach ($timezones as $timezone)
                                            <?php
                                                if(auth()->user()->timezone == $timezone->timezone)
                                            ?>
                                            <option value="{{$timezone->timezone}}" {{ (auth()->user()->timezone == $timezone->timezone)?'selected':'' }}>{{$timezone->timezone}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="html5-text-input" class="col-md-2 col-form-label">Email Updates</label>
                                <div class="col-md-10 mt-2">
                                    @foreach (config('params.email_periods') as $key => $email_period)
                                        <input
                                            class="form-check-input  @if ($key == 4) nosend @else email_update @endif"
                                            type="checkbox" name="email_update[]" value="{{ $key }}"
                                            @if (in_array($key, $email_period_ids)) checked @endif>
                                        <label class="form-check-label me-2" for="defaultCheck1">
                                            {{ $email_period }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="card-footer border-top pt-0 ">
                            <div class="demo-inline-spacing ">
                                <button type="submit" class="btn rounded-pill btn-outline-primary btnsubmit me-2">Save
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
@endpush
@push('js')
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    <script src="{{ asset('assets/js/custom/profile/email_automation.js') }}"></script>
@endpush
