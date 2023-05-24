@extends('layouts.app')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <h6 class="py-0 breadcrumb-wrapper">
            <span class="text-muted fw-light">
                <a href="{{ route('home') }}"><i class="mb-1 bx bx-home-heart"></i> </a>
            </span>
            <span class="text-muted fw-light">
                <a href="{{ route('company.index') }}">Parent Company</a> /
            </span>
            <span class="text-muted fw-normal">Edit Company</span>

        </h6>
        <div class="row">
            <div class="col-sm-12">
                <div class="card mb-4">

                    <div class="card-header p-3 border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Edit Company</h6>
                        <a href="{{ url()->previous() }}"
                            class="btn btn-sm rounded-pill btn-outline-primary font-weight-bold float-end"><i
                                class="fa fa-angle-double-left"></i> Back</a>
                    </div>
                    <form id="formCompanyAdd" class="formsubmit" method="POST" enctype="multipart/form-data"
                        onsubmit="return false;" action="{{ route('company.update', [$data->uuid]) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" class="redirect_route" value="{{ route('company.index') }}">
                        <div class="card-body ">
                            {{-- <h6 class="mb-b fw-normal">1. Company Details</h6> --}}
                            <div class="row">
                                <input type="hidden" class="" name="uuid" value="{{ $data->uuid }}">
                                <div class="col-sm-4 mb-4">
                                    <label for="" class="form-label required">Company Name</label>
                                    <input type="text" name="name" class="form-control" id=""
                                        value="{{ $data->name }}" placeholder="Company Name" required>
                                </div>
                                @if ($errors->has('name'))
                                    <div class="error">{{ $errors->first('name') }}</div>
                                @endif
                                <div class="col-sm-4 mb-4">
                                    <label for="" class="form-label required">Company Email</label>
                                    <input type="hidden" name="email" value="{{ $data->email }}" id="email"
                                        class="form-control " placeholder="Company Email" required>
                                    <input type="email" name="email" value="{{ $data->email }}" disabled id="email"
                                        class="form-control " placeholder="Company Email" required>
                                    @if ($errors->has('email'))
                                        <div class="error">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>
                                <div class="col-sm-4 mb-4">
                                    <label for="" class="form-label ">Company Website</label>
                                    <input type="text" name="website" value="{{ $data->website }}" class="form-control"
                                        id="website" placeholder="Company Website">
                                </div>
                                <div class="col-sm-4 mb-4">
                                    <label for="" class="form-label required">Subscription Plan</label>
                                    <select name="planid" class="form-select">
                                        <option value="">Select Subscription Plan</option>
                                        @foreach ($plans as $key => $plan)
                                            <option value="{{ $plan->uuid }}"
                                                @if ($data->planid == $plan->id) selected @endif>{{ $plan->name }}
                                                ({{ config('params.default_currency_icon') }}{{ $plan->amount }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('planid'))
                                        <div class="error">{{ $errors->first('planid') }}</div>
                                    @endif
                                </div>
                                <div class="col-sm-4 mb-4">
                                    <label for="" class="form-label required">Status</label>
                                    <select name="status" class="form-select" required>
                                        @foreach (config('params.status') as $key => $value)
                                            <option value="{{ $key }}"
                                                @if ($data->status == $key) selected @endif>{{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4 mb-4">
                                    <label for="" class="form-label ">Free trial</label>
                                    <input type="checkbox" name="is_trial" class="is_trial" id=""
                                        @if ($data->is_trial == 1) checked @endif
                                        @if (!empty(isCompanyPayment($data->id))) disabled @endif>
                                    <input type="number" name="trial_days" class="form-control trial_days"
                                        value="{{ $data->trial_days }}" id="" placeholder="Trial days"
                                        @if (!empty(isCompanyPayment($data->id))) disabled @endif
                                        @if ($data->is_trial != 1) style="display: none;" @endif>
                                </div>
                                <div class="col-sm-8 mb-4">
                                    <label for="" class="form-label ">Company Description</label>
                                    <textarea name="company_description" class="form-control" id="" cols="30" rows="2">{{ $data->company_description }}</textarea>

                                </div>


                            </div>

                            <fieldset class="custom-fieldset">
                                <legend>
                                    Contact Details
                                </legend>
                                <div class="contact_div field_wrapper ">

                                    @if (!empty($data->contacts) && count($data->contacts) > 0)
                                        @foreach ($data->contacts as $key => $contact)
                                            <div class="row">
                                                <input type="hidden" name="contact[{{ $key }}][contactid]"
                                                    value="{{ $contact->id }}">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="" class="form-label required">Contact Name
                                                        </label>
                                                        <input type="text"
                                                            name="contact[{{ $key }}][contact_name]"
                                                            class="form-control contact_name " maxlength="100"
                                                            value="{{ $contact->contact_name }}"
                                                            placeholder="Contact Name" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="" class="form-label required">Contact
                                                            Title
                                                        </label>
                                                        <input type="text"
                                                            name="contact[{{ $key }}][contact_title]"
                                                            class="form-control contact_name " maxlength="100"
                                                            value="{{ $contact->contact_title }}"
                                                            placeholder="Contact Title" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="" class="form-label required">Contact
                                                            Email
                                                        </label>
                                                        <input type="email"
                                                            name="contact[{{ $key }}][contact_email]"
                                                            class="form-control contact_email"
                                                            value="{{ $contact->email }}" placeholder="Contact Email"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="" class="form-label ">Contact Number
                                                        </label>
                                                        <input type="tel"
                                                            name="contact[{{ $key }}][contact_number]"
                                                            maxlength="{{ config('params.max_mobile') }}"
                                                            class="form-control contact_number"
                                                            value="{{ $contact->contact_number }}"
                                                            placeholder="Contact No.">
                                                        <input type="hidden" class="country_code"
                                                            name="contact[{{ $key }}][country_code]">
                                                    </div>
                                                </div>
                                                <div class="col-md-1 ">
                                                    <div class="form-group mt-4">
                                                        <label><span class="text-success"> </span> </label>
                                                        @if ($key == 0)
                                                            <a class="add_button_comp pointer "
                                                                title="Add Competencies"><i
                                                                    class="icon-lg fas fa-plus-circle text-success addiconclass"
                                                                    aria-hidden="true"></i></a>
                                                        @else
                                                            <a class="remove_button_comp" title="Remove Contact"><i
                                                                    class="fas fa-minus-circle removeiconclass text-danger"
                                                                    aria-hidden="true"></i></a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="" class="form-label required">Contact Name
                                                    </label>
                                                    <input type="text" name="contact[1][contact_name]"
                                                        class="form-control contact_name " maxlength="100" value=""
                                                        placeholder="Contact Name" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="" class="form-label required">Contact Title
                                                    </label>
                                                    <input type="text" name="contact[1][contact_title]"
                                                        class="form-control contact_name " maxlength="100" value=""
                                                        placeholder="Contact Title" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="" class="form-label required">Contact Email
                                                    </label>
                                                    <input type="email" name="contact[1][contact_email]"
                                                        class="form-control contact_email" value=""
                                                        placeholder="Contact Email" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="" class="form-label ">Contact Number </label>
                                                    <input type="tel" name="contact[1][contact_number]"
                                                        maxlength="{{ config('params.max_mobile') }}"
                                                        class="form-control contact_number" value=""
                                                        placeholder="Contact No.">
                                                    <input type="hidden" class="country_code"
                                                        name="contact[1][country_code]">
                                                </div>
                                            </div>
                                            <div class="col-md-1 ">
                                                <div class="form-group mt-4">
                                                    <label><span class="text-success"> </span> </label>
                                                    <a class="add_button_comp pointer " title="Add Competencies"><i
                                                            class="icon-lg fas fa-plus-circle text-success addiconclass"
                                                            aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </fieldset>
                            <!---email periods--->
                            <div class="mb-3 row">
                                <label for="html5-text-input" class="col-md-2 col-form-label">Email Updates</label>
                                <div class="col-md-10 mt-2">
                                    @foreach (config('params.email_periods') as $key => $email_period)
                                        <input
                                            class="form-check-input  @if ($key == 4) nosend @else email_update @endif"
                                            type="checkbox" name="email_update[]" value="{{ $key }}"
                                            id="defaultCheck1" @if (in_array($key, $email_period_ids)) checked @endif>
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
                                <a href="{{ route('company.index') }}"
                                    class="btn rounded-pill btn-outline-dark">Cancel</a>
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
    <link rel="stylesheet" href="{{ asset('assets/css/intlTelInput.css') }}">
@endpush
@push('js')
    <!-- Page JS -->
    <script>
        var selectedcountrycodes = @json($selectedcountrycodes);
    </script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\CompanyUpdateRequest', '#formCompanyAdd') !!}
    <script src="{{ asset('assets/js/custom/intlTelInput-jquery.min.js') }}"></script>
    <script>
        //used for redirecting after form submit
        var redirect_route = "{{ route('company.index') }}";
        var append_contact_route = "{{ route('company.appendcontact') }}";
    </script>
    <script src="{{ asset('assets/js/custom/company/form.js') }}"></script>
    <script src="{{ asset('assets/js/custom/company/validation.js') }}"></script>
@endpush
