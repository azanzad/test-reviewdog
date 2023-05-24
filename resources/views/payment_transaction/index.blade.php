@extends('layouts.app')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <h6 class="py-0 breadcrumb-wrapper">
            <div class="row ">
                <div class="col-md-6 col-sm-6">
                    <span class="text-muted fw-light">
                        <a href="{{ route('home') }}"><i class="mb-1 bx bx-home-heart"></i> </a>
                    </span>
                    <span class="text-muted fw-normal">Transactions</span>
                </div>
                <div class="col-md-6 col-sm-6 d-flex  justify-content-md-end">
                    @include('common.export')
                    @if (auth()->user()->role == config('params.company_role'))
                        <a href="{{ route('profile.current_plan') }}"
                            class="btn btn-sm rounded-pill btn-outline-primary font-weight-bold float-end me-2"><i
                                class="fa fa-angle-double-left"></i> Back</a>
                    @endif

                    @include('payment_transaction.filter')
                </div>
            </div>
        </h6>
        <div class="row">
            <div class="col-sm-12">
                <!-- Scrollable -->
                <div class="card ">
                    <div class="card-datatable text-nowrap table-responsive">
                        <table id="dataTable" class="dt-fixedheader table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Customer Name</th>
                                    <th>Plan Name</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Transaction Date</th>
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

    <!-- Fixed header -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-fixedheader-bs5/fixedheader.bootstrap5.css') }}" />
@endpush
@push('js')
    <!-- Page JS -->
    <script src="{{ asset('assets/vendor/libs/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-responsive/datatables.responsive.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js') }}"></script>
    <!-- Fixed header -->
    <script src="{{ asset('assets/vendor/libs/datatables-fixedheader-bs5/fixedheader.bootstrap5.js') }}"></script>
    <script>
        var route = "{{ route('payment_transaction.index') }}";
        var login_user_role = "{{ auth()->user()->role }}";
        var admin_role = "{{ config('params.admin_role') }}";
        var company_role = "{{ config('params.company_role') }}";
        var customer_role = "{{ config('params.customer_role') }}";
    </script>
    <script src="{{ asset('assets/js/custom/payment_transaction/datatable.js') }}"></script>
    <script src="{{ asset('assets/js/custom/export/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/export/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/export/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/export/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/js/custom/export/buttons.html5.min.js') }}"></script>
@endpush
