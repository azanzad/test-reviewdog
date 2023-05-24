@extends('layouts.app')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <h6 class="py-0 breadcrumb-wrapper">
            <div class="row col-12">
                <div class="col-md-6 col-sm-12">
                    <span class="text-muted fw-light">
                        <a href="{{ route('home') }}"><i class="mb-1 bx bx-home-heart"></i> </a>
                    </span>
                    <span class="text-muted fw-light">
                        <a href="{{ route('plans.index') }}">Subscription Plans</a> /
                    </span>
                    <span class="text-muted fw-normal">Plan Details</span>
                </div>

            </div>



        </h6>
        <div class="row">
            <div class="col-sm-12">
                <div class="card mb-4">
                    <div class="card-header p-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Plan Details</h5>
                        <a href="{{ route('plans.index') }}"
                            class="btn btn-sm rounded-pill btn-outline-primary font-weight-bold float-end"><i
                                class="fa fa-angle-double-left"></i> Back</a>
                    </div>

                    <div class="card-body">

                        <div class="row mt-3 col-md-12">

                            <div class="col-md-4">
                                <ul class="list-unstyled">
                                    <li class="mb-3">
                                        <span class="fw-bold me-2">Plan Type:</span>
                                        <span>{{ config('params.plan_durations.' . $data->interval) }}</span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="fw-bold me-2">Duration Count:</span>
                                        <span>{{ $data->interval_count ?? 'N/A' }}</span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="fw-bold me-2">Created Date:</span>
                                        <span>{{ $data->created_at->format('d/m/Y h:i:s A') }}</span>
                                    </li>


                                </ul>
                            </div>
                            <div class="col-md-4">
                                <ul class="list-unstyled">
                                    <li class="mb-3">
                                        <span class="fw-bold me-2">Plan Name:</span>
                                        <span>{{ $data->name }}</span>
                                    </li>

                                    <li class="mb-3">
                                        <span class="fw-bold me-2">Price:</span>
                                        <span>${{ $data->amount }}</span>
                                    </li>
                                    @if($data->annual_sales_to > 0)
                                        <li class="mb-3">
                                            <span class="fw-bold me-2">Annual Amazon Sales:</span>
                                            <span>${{ $data->annual_sales_from }} - ${{ $data->annual_sales_to }}</span>
                                        </li>
                                    @endif

                                </ul>
                            </div>
                            <div class="col-md-4">
                                <ul class="list-unstyled">
                                    <li class="mb-3">
                                        <span class="fw-bold me-2">Plan Duration:</span>
                                        <span>{{ config('params.plan_durations.' . $data->interval) }}</span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="fw-bold me-2">Status:</span>
                                        <span>
                                            @if ($data->status == config('params.active'))
                                                <span class="badge rounded-pill bg-success">
                                                    {{ config('params.status.' . $data->status) }}
                                                </span>
                                            @else
                                                <span class="badge rounded-pill bg-warning">
                                                    {{ config('params.status.' . $data->status) }}
                                                </span>
                                            @endif
                                        </span>
                                    </li>
                                    @if($data->trial_days)
                                        <li class="mb-3">
                                            <span class="fw-bold me-2">Trial:</span>
                                            <span>{{ $data->trial_days}} day</span>
                                        </li>
                                    @endif

                                </ul>
                            </div>
                        </div>
                        <hr>
                        <!--customer list--->
                        <div class="row g-4">
                            <h6 class="mb-0">Customers List</h6>
                            <div class="card border no-show">
                                <div class="card-datatable text-nowrap table-responsive">
                                    <table id="dataTable" class="dt-fixedheader table ">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Customer Name</th>
                                                <th>Customer Email</th>
                                                <th>Plan Price</th>
                                                <th>Free Trial</th>
                                                <th>Trial Days</th>
                                                <th>Created At</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>



                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <!-- Page css -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />

    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/datatables-fixedheader-bs5/fixedheader.bootstrap5.css') }}" />
@endpush
@push('js')
    <!-- Page JS -->
    <script src="{{ asset('assets/vendor/libs/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-responsive/datatables.responsive.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js') }}"></script>

    <!-- Fixed header -->
    <script src="{{ asset('assets/vendor/libs/datatables-fixedheader-bs5/fixedheader.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/js/custom/plans/details.js') }}"></script>
    <script>
        var route = "{{ route('plans.getAllCompany', [$data->uuid]) }}";
    </script>
@endpush
