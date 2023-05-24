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
            <span class="text-muted fw-normal">Add Bulk Store</span>

        </h6>
        <div class="row">
            <div class="col-sm-12">
                <div class="card mb-4">
                    <div class="card-header p-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Add Bulk Store</h5>
                        <a href="{{ url()->previous() }}"
                            class="btn btn-sm rounded-pill btn-outline-primary font-weight-bold float-end"><i
                                class="fa fa-angle-double-left"></i> Back</a>
                    </div>

                    <form id="importExcelForm" class="formsubmit importExcelForm" method="POST"
                        enctype="multipart/form-data" onsubmit="return false;" action="{{ route('store.submitexcel') }}">
                        {{ csrf_field() }}
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="alert alert-warning" role="alert">
                                        <span>
                                            <h6 class="text-warning alert-heading mb-1">Steps to integrate stores in bulk:
                                            </h6>
                                            <ol class="mb-0">
                                                <li>Export your customer list from <a class="text-primary"
                                                        href="@if (auth()->user()->role == config('params.company_role')) {{ route('customer.index') }} @else {{ route('company.index') }} @endif">here</a>
                                                </li>
                                                <li>Fill the data in the sheet for each customer</li>
                                                <li>Upload the file below</li>
                                            </ol>

                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <div class="mb-3">
                                        <label for="formFile" class="form-label required">Upload File</label>
                                        <input class="form-control" type="file" id="formFile" name="file"
                                            accept=".xls,.xlsx">
                                        <div id="defaultFormControlHelp" class="form-text">Allowed file .xls, .xlsx
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer border-top pt-0 ">
                            <div class="demo-inline-spacing ">
                                <button type="submit" class="btn rounded-pill btn-outline-primary btnsubmit me-2">Save
                                    <i class="loading-spinner fa fa-lg fa fa-spinner fa-spin"></i>
                                </button>
                                <a href="{{ route('store.index') }}" class="btn rounded-pill btn-outline-dark">Cancel</a>
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
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\ImportStoreRequest', '#importExcelForm') !!}
    <!-- jquery-confirm/ -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script>
        var redirect_route = "{{ route('store.index') }}";
    </script>
    <script src="{{ asset('assets/js/custom/store/import_form.js') }}"></script>
@endpush
