@extends('layouts.app')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <h6 class="py-0 breadcrumb-wrapper">
            <div class="row ">
                <div class="col-md-6 col-sm-6">
                    <span class="text-muted fw-light">
                        <a href="{{ route('home') }}"><i class="mb-1 bx bx-home-heart"></i> </a>
                    </span>
                    <span class="text-muted fw-normal">Subscription Plans</span>
                </div>
                <div class="col-md-6 col-sm-6 d-flex  justify-content-md-end">
                    @include('common.export')
                    <a href="{{ route('plans.create') }}" type="button"
                        class="btn btn-sm rounded-pill btn-outline-primary me-2">
                        <i class="fas fa fa-plus-circle"></i> Add Plan</a>
                    @include('plans.filter')
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
                                    <th>Plan Type</th>
                                    <th>Plan Name</th>
                                    <th>Price</th>
                                    <th>Duration</th>
                                    <th>Assigned</th>
                                    <th>Status</th>
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
    <!-- jquery-confirm/ -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <!-- Fixed header -->
@endpush
@push('js')
    <!-- Page JS -->
    <script src="{{ asset('assets/vendor/libs/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-responsive/datatables.responsive.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js') }}"></script>
    <!-- jquery-confirm/ -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="{{ asset('assets/js/custom/plans/datatable.js') }}"></script>
    <script>
        var route = "{{ route('plans.index') }}";
    </script>
    <script src="{{ asset('assets/js/custom/export/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/export/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/export/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/export/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/js/custom/export/buttons.html5.min.js') }}"></script>
@endpush
