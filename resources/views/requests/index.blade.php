@extends('layouts.app')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <h6 class="py-0 breadcrumb-wrapper">
            <div class="row">
                <div class="col align-self-md-center">
                    <span class="text-muted fw-light">
                        <a href="{{ route('home') }}"><i class="mb-1 bx bx-home-heart"></i> </a>
                    </span>
                    <span class="text-muted fw-normal">Requests</span>
                </div>
                <div class="col-auto">
                    <div class="d-flex align-items-center">
                        @include('common.export')
                        @if (auth()->user()->customer_type != config('params.individual_brand'))
                            @if (checkPermission([config('params.admin_role')]))
                                <div class="select2-primary w-px-200 me-2">
                                    <select id="all_companies" nanme="all_companies" class="form-select  form-select-sm">
                                        <option value="">Select Company</option>
                                        @foreach ($company as $data)
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="select2-primary w-px-200 me-2 d-none" id="div_select_customer">
                                    <select id="select_customer" name="select_customer" class="form-select  form-select-sm">
                                    </select>
                                </div>
                            @elseif (checkPermission([config('params.company_role')]))
                                <div class="select2-primary w-px-200 me-2">
                                    <select id="prefilled_company" nanme="prefilled_company"
                                        class="form-select  form-select-sm" disabled>
                                        <option value="{{ auth()->user()->id }}">{{ auth()->user()->name }}</option>
                                    </select>
                                </div>
                                <div class="select2-primary w-px-200 me-2">
                                    <select id="select_company_customer" name="select_company_customer"
                                        class="form-select  form-select-sm">
                                    </select>
                                </div>
                            @endif
                        @endif
                        @if (auth()->user()->customer_type != config('params.individual_brand'))
                            <a href="{{ url()->previous() }}"
                                class="btn btn-sm rounded-pill btn-outline-primary font-weight-bold float-end me-2"><i
                                    class="fa fa-angle-double-left"></i> Back</a>
                        @endif
                        @if (auth()->user()->role == config('params.company_role'))
                            <a href="{{ route('store.index') }}" type="button"
                                class="btn btn-sm rounded-pill btn-outline-primary me-2">
                                <i class="fas fa-duotone fa-store fa-fw"></i> Stores</a>
                        @endif
                        @if (auth()->user()->role == config('params.company_role') &&
                            auth()->user()->customer_type != config('params.individual_brand'))
                            <a href="{{ route('customer.index') }}" type="button"
                                class="btn btn-sm rounded-pill btn-outline-primary me-2">
                                <i class="fas fa-user fa-fw"></i> Brands</a>
                        @endif
                        @include('requests.filter')
                    </div>
                </div>
            </div>
        </h6>
        <div class="row">
            <div class="col-sm-12">
                <!-- Scrollable -->
                <div class="card">
                    <div class="card-datatable text-wrap table-responsive">
                        <table id="dataTable" class="dt-scrollableTable table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Customer</th>
                                    <th>Amazon Order Id</th>
                                    <th>Order Date</th>
                                    <th>Order Status</th>
                                    <th>Request Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!--/ Scrollable -->


            </div>
        </div>
    </div>
@endsection
@push('css')
    <!-- Page css -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <!-- jquery-confirm/ -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endpush
@push('js')
    <!-- Page JS -->
    <script src="{{ asset('assets/vendor/libs/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-responsive/datatables.responsive.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js') }}"></script>
    <!-- jquery-confirm/ -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="{{ asset('assets/js/custom/requests/datatable.js') }}"></script>
    <script>
        var route = "{{ route('request.index') }}";
        var customerRoute = "{{ route('company.fetchCustomerOfCompany') }}";
        var login_user_role = "{{ auth()->user()->role }}";
        var admin_role = "{{ config('params.admin_role') }}";
        var company_role = "{{ config('params.company_role') }}";
        var customer_role = "{{ config('params.customer_role') }}";
        var individual_brand = "{{ config('params.individual_brand') }}";
        var login_customer_type = "{{ auth()->user()->customer_type }}";
    </script>
    <script src="{{ asset('assets/js/custom/export/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/export/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/export/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/export/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/js/custom/export/buttons.html5.min.js') }}"></script>
@endpush
