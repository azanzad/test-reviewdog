@extends('layouts.app')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <h6 class="py-0 breadcrumb-wrapper">
            <div class="row">
                <div class="col-md-3 col-sm-12">
                    <span class="text-muted fw-light">
                        <a href="{{ route('home') }}"><i class="mb-1 bx bx-home-heart"></i> </a>
                    </span>
                    <span class="text-muted fw-normal">Customers</span>
                </div>
                <div class="col-md-9 col-sm-12 d-flex  justify-content-md-end">
                    @include('common.export')
                    <a href="{{ route('store.index') }}" type="button"
                        class="btn btn-sm rounded-pill btn-outline-primary me-2">
                        <i class="fas fa-duotone fa-store fa-fw"></i> Stores</a>

                    <a href="{{ route('request.index') }}" type="button"
                        class="btn btn-sm rounded-pill btn-outline-primary me-2">
                        <i class="fas fa-regular fa-bag-shopping fa-fw"></i> Requests</a>

                    <a href="{{ route('company.create') }}" type="button"
                        class="btn btn-sm rounded-pill btn-outline-primary me-2">
                        <i class="fas fa fa-plus-circle"></i> Add Parent Company</a>
                    @if (checkPermission([config('params.admin_role')]))
                        <a href="{{ route('customer.create') }}" type="button"
                            class="btn btn-sm rounded-pill btn-outline-primary me-2">
                            <i class="fas fa fa-plus-circle"></i> Add Customer</a>
                    @endif

                    @include('company.filter')
                </div>
            </div>
        </h6>
        <div class="row">
            <div class="col-sm-12">
                <!-- Scrollable -->
                <div class="card">
                    <div class="card-datatable text-nowrap table-responsive">
                        <table id="dataTable" class="dt-fixedheader table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Customer Name</th>
                                    <th>Customer Type</th>
                                    <th>Free Trial</th>
                                    <th>Plan</th>
                                    <th>Actual Sales</th>
                                    <th>Status</th>
                                    <th>Next Bill Date</th>
                                    <th># Brands</th>
                                    <th># Stores</th>
                                    <th># Emails</th>
                                    <th># Requests</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!--/ Scrollable -->


            </div>
        </div>
    </div>
    <!-- Add New Credit Card Modal -->
    <!-- Modal -->
    <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1"></h5>


                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">

                    <div class="d-flex  justify-content-md-end margin_bottom_export_btn">
                        @include('common.export')
                        @if (checkPermission([config('params.admin_role')]) ||
                            (auth()->user()->role == config('params.company_role') && auth()->user()->customer_type == null))
                            <a href="javascript:;" type="button"
                                class="send_bulk_store_link btn btn-sm rounded-pill btn-outline-primary me-2"
                                data-url="{{ route('send-bulk-store-link') }}">
                                <i class="fa-solid fa-share-from-square"></i> Send store integration link(s)</a>
                        @endif
                        <form class="form-some-up form-block" id="formCustomerExport" role="form"
                            action="{{ route('customer.export') }}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" id="customer_ids" name="customer_ids">
                            <input type="hidden" id="companyid" name="companyid">
                            <button type="submit" class="btn btn-sm rounded-pill btn-outline-primary btnexport me-2 "
                                id="export_records">
                                <i class="fa-solid fa-file-arrow-down"></i>
                                Export for Store Integration <i class="loading-spinner fa fa-lg fa fa-spinner fa-spin"></i>
                            </button>
                            @if (checkPermission([config('params.admin_role')]))
                                <a href="javascript:;" type="button"
                                    class="btn btn-sm rounded-pill customer_create_url btn-outline-primary me-5">
                                    <i class="fas fa fa-plus-circle"></i> Add Customer</a>
                            @endif
                        </form>

                    </div>

                    <div class="row ">
                        <div class="card-datatable text-nowrap table-responsive">
                            <table id="customerDataTable" class="dt-fixedheader table table-bordered">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>#</th>
                                        <th>Brand Name</th>
                                        <th>Status</th>
                                        <th># Stores</th>
                                        <th># Emails</th>
                                        <th>Email Cadence</th>
                                        <th>Primary email</th>
                                        <th># Requests</th>
                                        <th>Created At</th>
                                        <th>Action1</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Add New Credit Card Modal -->
@endsection
@push('css')
    <!-- Page css -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <!-- jquery-confirm/ -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <!-- Fixed header -->
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/datatables-fixedheader-bs5/fixedheader.bootstrap5.css') }}" />
@endpush
@push('js')

    <!-- Page JS -->
    <script src="{{ asset('assets/vendor/libs/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-responsive/datatables.responsive.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.js') }}"></script>
    <!-- jquery-confirm/ -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <!-- Fixed header -->
    <script src="{{ asset('assets/vendor/libs/datatables-fixedheader-bs5/fixedheader.bootstrap5.js') }}"></script>
    <script>

        var route = "{{ route('company.index') }}";
        var customer_route = "{{ route('customer.index') }}";
        var customer_create_route = "{{ route('customer.create') }}";
    </script>
    <script src="{{ asset('assets/js/custom/company/datatable.js') }}"></script>
    <script src="{{ asset('assets/js/custom/export/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/export/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/export/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/export/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/js/custom/export/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/customer/store_integrate.js') }}"></script>
@endpush
