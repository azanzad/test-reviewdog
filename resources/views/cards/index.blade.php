@extends('layouts.app')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <h6 class="py-0 breadcrumb-wrapper">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <span class="text-muted fw-light">
                        <a href="{{ route('home') }}"><i class="mb-1 bx bx-home-heart"></i> </a>
                    </span>
                    <span class="text-muted fw-normal">Cards</span>
                </div>
                <div class="col-md-6 col-sm-12 d-flex  justify-content-md-end">
                    <a href="{{ route('profile.current_plan') }}"
                        class="btn btn-sm rounded-pill btn-outline-primary font-weight-bold float-end me-2"><i
                            class="fa fa-angle-double-left"></i> Back</a>
                    <a class="btn btn-sm rounded-pill btn-outline-primary openaddmodal" href="{{ route('cards.create') }}">
                        <i class="fas fa fa-plus-circle me-1"></i> Add Card
                    </a>
                </div>
            </div>
        </h6>
        @include('common.flash')
        <div class="row">
            <div class="col-sm-12">
                <!-- Scrollable -->
                <div class="card">
                    <div class="card-datatable text-nowrap table-responsive">
                        <table id="dataTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Card Type</th>
                                    <th>Cardholder name</th>
                                    <th>Default Card</th>
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
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-fixedheader-bs5/fixedheader.bootstrap5.css') }}" />
@endpush
@push('js')
    <!-- Page JS -->
    <script src="{{ asset('assets/vendor/libs/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-responsive/datatables.responsive.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js') }}"></script>
    <!-- jquery-confirm/ -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <!-- Fixed header -->
    <script src="{{ asset('assets/vendor/libs/datatables-fixedheader-bs5/fixedheader.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/js/custom/card/datatable.js') }}"></script>
    <script>
        var modalroute = "{{ route('cards.create') }}";
        var route = "{{ route('cards.index') }}";
    </script>
@endpush
