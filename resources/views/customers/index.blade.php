@extends('layouts.app')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <h6 class="py-0 breadcrumb-wrapper">
            <div class="row ">
                <div class="col-md-3 col-sm-12">
                    <span class="text-muted fw-light">
                        <a href="{{ route('home') }}"><i class="mb-1 bx bx-home-heart"></i> </a>
                    </span>
                    <span class="text-muted fw-normal">Customers</span>
                </div>

                <div class="col-md-9 col-sm-12 d-flex  justify-content-md-end">
                    @if (checkPermission([config('params.company_role')]))
                        <a href="{{ route('request.index') }}"
                            class="btn btn-sm rounded-pill btn-outline-primary font-weight-bold float-end me-2"><i
                                class="fa fa-angle-double-left"></i> Back</a>
                        <form class="form-some-up form-block me-2" id="formCustomerExport" role="form"
                            action="{{ route('customer.export') }}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" id="customer_ids" name="customer_ids">
                            <button type="submit" class="btn btn-sm rounded-pill btn-outline-primary btnexport"
                                id="export_records" @if (getSubCustomerCount(auth()->user()->id) == 0) disabled @endif>
                                <i class="fa-solid fa-file-arrow-down"></i>
                                Export for Store Integration <i class="loading-spinner fa fa-lg fa fa-spinner fa-spin"></i>
                            </button>

                        </form>
                    @endif
                    @if (checkPermission([config('params.admin_role')]) ||
                        (auth()->user()->role == config('params.company_role') && auth()->user()->customer_type == null))
                        <a href="javascript:;" type="button"
                            class="send_bulk_store_link btn btn-sm rounded-pill btn-outline-primary me-2"
                            data-url="{{ route('send-bulk-store-link') }}">
                            <i class="fa-solid fa-share-from-square"></i> Send store integration link(s)</a>
                        <a href="{{ $create_route }}" type="button"
                            class="btn btn-sm rounded-pill btn-outline-primary me-2">
                            <i class="fas fa fa-plus-circle"></i> Customer</a>
                    @endif
                    @include('customers.filter')
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
                                    <th></th>
                                    <th>#</th>
                                    <th>Customer Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th># Stores</th>
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
@endsection
@push('css')
    <!-- Page css -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
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
    <script src="{{ asset('assets/vendor/libs/datatables-buttons/datatables-buttons.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jszip/jszip.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pdfmake/pdfmake.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-buttons/buttons.html5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-buttons/buttons.print.js') }}"></script>
    <!-- jquery-confirm/ -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <!-- Fixed header -->
    <script src="{{ asset('assets/vendor/libs/datatables-fixedheader-bs5/fixedheader.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/js/custom/customer/datatable.js') }}"></script>

    <script>
        var route = "{{ route('customer.index') }}";
    </script>
@endpush
