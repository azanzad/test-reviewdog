@extends('layouts.app')
@section('content')
    @include('common.flash')
    <div class="container-fluid flex-grow-1 container-p-y">
        <h6 class="py-0 breadcrumb-wrapper">
            <span class="text-muted fw-light">
                <a href="{{ route('home') }}"><i class="mb-1 bx bx-home-heart"></i></a>
            </span>
            <span class="text-muted fw-normal">Change Password</span>
        </h6>
        <div class="row">
            <div class="col-md-12">

                <div class="card mb-4">
                    <h5 class="card-header p-3 border-bottom">Change Password </h5>

                    <form id="formChangePassword" class="formsubmit" method="POST" enctype="multipart/form-data"
                        onsubmit="return false;" action="{{ route('update_password') }}">
                        @csrf
                        @method('PUT')


                        <div class="card-body">
                            <!-- basic details -->
                            <input type="hidden" name="uuid" value="{{ auth()->user()->uuid }}">
                            <div class="row">
                                <div class="mb-3 col-md-4 form-password-toggle">
                                    <label for="password" class="form-label required">Current Password</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password" class="form-control" name="password"
                                            placeholder="Enter your password" aria-describedby="password" />
                                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                    @if ($errors->has('password'))
                                        <div class="error">{{ $errors->first('password') }}</div>
                                    @endif
                                </div>
                                <div class="mb-3 col-md-4 form-password-toggle">
                                    <label for="new_password" class="form-label required">New Password</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="new_password" class="form-control" name="new_password"
                                            placeholder="Enter new password" aria-describedby="password" />
                                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                </div>
                                <div class="mb-3 col-md-4 form-password-toggle">
                                    <label class="form-label required" for="confirm-password">Confirm Password</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="confirm-password" class="form-control"
                                            name="password_confirmation" placeholder="Confirm Password"
                                            aria-describedby="password" />
                                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                </div>
                            </div>
                            <!-- /basic details -->

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
    {!! JsValidator::formRequest('App\Http\Requests\ChangePasswordRequest', '#formChangePassword') !!}
    <script src="{{ asset('assets/js/custom/change_password/validation.js') }}"></script>
@endpush
