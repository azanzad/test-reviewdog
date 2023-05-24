@extends('layouts.app')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <h6 class="py-0 breadcrumb-wrapper">
            <span class="text-muted fw-light">
                <a href="{{ route('home') }}"><i class="mb-1 bx bx-home-heart"></i> </a>
            </span>
            <span class="text-muted fw-normal">My Account</span>
        </h6>
        @include('common.flash')
        <div class="row">
            <div class="col-md-12">

                <div class="card mb-4">
                    <h5 class="card-header p-3 border-bottom">Edit Profile </h5>
                    <form class="formsubmit" id="formAccountProfile" enctype="multipart/form-data" onsubmit="return false;"
                        action="{{ route('profile.update', $user->uuid) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- profile image -->
                        <div class="card-body">
                            <input type="hidden" id="" name="uuid" value="{{ $user->uuid }}">
                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                @php
                                    $profile_image = empty($user->profile_image) ? asset('assets/img/default-user.png') : Storage::disk('public')->url(auth()->user()->profile_image);
                                @endphp
                                <img src="{{ $profile_image }}" alt="user-avatar" class="d-block rounded" height="100"
                                    width="100" id="uploadedAvatar" />
                                <div class="button-wrapper">
                                    <label for="upload" class="btn btn-outline-primary me-2 mb-4" tabindex="0">
                                        <span class="d-none d-sm-block">Upload new photo</span>
                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                        <input type="file" id="upload" name="profile_image" class="account-file-input"
                                            hidden accept="image/png, image/jpeg" />
                                        <input type="hidden" id="userProfile" name="userProfile"
                                            value="{{ $user->profile_image ?? '' }}" />
                                    </label>
                                    <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                                        <i class="bx bx-reset d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Remove photo</span>
                                    </button>
                                    @if ($errors->has('profile_image'))
                                        <div class="error">{{ $errors->first('profile_image') }}</div>
                                    @endif
                                    <p class="mb-0 text-muted">Allowed JPG, JPEG or PNG. Max size of 800 KB</p>
                                </div>
                            </div>
                        </div>
                        <hr class="my-0" />

                        <div class="card-body">
                            <!-- basic details -->
                            <input type="hidden" name="id" value="{{ $user->id }}">
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="firstName" class="form-label required">Full Name</label>
                                    <input class="form-control" type="text" id="name" name="name"
                                        placeholder="Full Name" value="{{ auth()->user()->name ?? '' }}" autofocus
                                        required />
                                    @if ($errors->has('name'))
                                        <div class="error">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="email" class="form-label required">E-mail</label>
                                    <input class="form-control" type="email" id="email" name="email"
                                        value="{{ auth()->user()->email ?? '' }}" placeholder="Email" required />
                                    @if ($errors->has('email'))
                                        <div class="error">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label d-block" for="contact_number">Contact Number</label>
                                    <input type="tel" id="contact_number" name="contact_number"
                                        value="{{ auth()->user()->contact_number ?? '' }}"
                                        class="form-control contact_number" maxlength="{{ config('params.max_mobile') }}"
                                        placeholder="Contact Number" />
                                    <input type="hidden" class="country_code" name="country_code">
                                    @if ($errors->has('contact_number'))
                                        <div class="error">{{ $errors->first('contact_number') }}</div>
                                    @endif
                                </div>
                                @if(auth()->user()->role == config('params.company_role'))
                                    <div class="mb-3 col-md-6">
                                        <div class="form-group mb-2">
                                            <label class="form-label required" for="required">Country</label>
                                            <select name="country_id" id="vc" class="form-control">
                                                <option value="">Select Country</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{$country->id}}" {{ (auth()->user()->country_id == $country->id )?'selected':'' }}>{{$country->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <!-- /basic details -->

                        </div>
                        <div class="card-footer border-top pt-0 ">
                            <div class="demo-inline-spacing ">
                                <button type="submit" class="btn rounded-pill btn-outline-primary btnsubmit me-2">Save <i
                                        class="loading-spinner fa fa-lg fa fa-spinner fa-spin"></i>
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
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-profile.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/intlTelInput.css') }}">
@endpush
@push('js')
    <script>
        var selectedcountrycodes = @json([auth()->user()->country_code] ?? null);
    </script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    <script>
        var default_profile_image = "{{ asset('assets/img/default-user.png') }}";
        var old_profile_image = "{{ $profile_image }}";
    </script>
    <script src="{{ asset('assets/js/custom/intlTelInput-jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/profile/profile.js') }}"></script>
    <script src="{{ asset('assets/js/custom/profile/validation.js') }}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\ProfileUpdateRequest', '#formAccountProfile') !!}
@endpush
