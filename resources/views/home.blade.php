@extends('layouts.app')
@section('content')
    @if (session('login_success'))
        @include('common.flash')
    @endif
    <div class="container-xxl flex-grow-1 container-p-y">
        @if (auth()->user()->role == config('params.company_role'))
            @if (empty(isStoreAdded(auth()->user()->id)))
                <div class="row ">
                    <div class="col-md-12">

                        <div class="alert alert-danger" role="alert">
                            <span><i class="menu-icon tf-icons bx bx-shopping-bag"></i> Integrate store right away and let us
                                take
                                the responsibility of requesting reviews for you. </span>
                            @if (auth()->user()->customer_type == config('params.individual_brand'))
                                <a href="{{ route('store.create') }}" type="button"
                                    class="btn btn-sm rounded-pill btn-outline-primary float-right">
                                    Integrate Store</a>
                            @else
                                <a href="{{ route('store.bulk_store') }}" type="button"
                                    class="btn btn-sm rounded-pill btn-outline-primary float-right">
                                    Integrate Store</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            @if (empty(isCompanyPayment(auth()->user()->id)))
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger" role="alert">
                            <i class="menu-icon tf-icons bx bx-credit-card"></i> Make Payment and start your subcription to
                            automate
                            the request review process.
                            <a href="{{ $payment_route }}" type="button"
                                class="btn btn-sm rounded-pill btn-outline-primary float-right">
                                Make Payment</a>
                        </div>
                    </div>
                </div>
            @endif
        @endif
        <div class="row g-4 mb-4">
            @if (checkPermission([config('params.admin_role')]))
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <a href="{{ route('plans.index') }}" class=" text-primary stretched-link"
                                        title="Redirect to Plan Listing">
                                        Subscription Plans({{ $data['plan']['total_count'] ?? 0 }})
                                    </a>

                                    <div class="d-flex align-items-end mt-2">
                                        <small class="mb-0 me-2">Active </small>
                                        <small class="text-success">({{ $data['plan']['active_count'] ?? 0 }})</small>
                                    </div>
                                    <small>InActive</small>
                                    <small class="text-danger">({{ $data['plan']['inactive_count'] ?? 0 }})</small>
                                </div>
                                <span class="badge bg-label-primary rounded p-2">
                                    <i class="bx bx-list-ul bx-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (checkPermission([config('params.admin_role')]))
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <a href="{{ route('company.index') }}" class=" text-info stretched-link"
                                        title="Redirect to Company Listing">
                                        Parent Company({{ $data['parent_company']['total_count'] ?? 0 }})
                                    </a>
                                    <div class="d-flex align-items-end mt-2">
                                        <small class="mb-0 me-2">Active</small>
                                        <small
                                            class="text-success">({{ $data['parent_company']['active_count'] ?? 0 }})</small>
                                    </div>
                                    <small>InActive</small>
                                    <small
                                        class="text-danger">({{ $data['parent_company']['inactive_count'] ?? 0 }})</small>
                                </div>
                                <span class="badge bg-label-info rounded p-2">
                                    <i class="bx bx-building-house bx-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (auth()->user()->customer_type != config('params.individual_brand'))
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <a href="@if (auth()->user()->role == config('params.admin_role')) {{ route('company.index') }} @else {{ route('customer.index') }} @endif"
                                        class=" text-info stretched-link" title="Redirect to Company Listing">
                                        Customers({{ $data['customer']['total_count'] ?? 0 }})
                                    </a>
                                    <div class="d-flex align-items-end mt-2">
                                        <small class="mb-0 me-2">Active</small>
                                        <small class="text-success">({{ $data['customer']['active_count'] ?? 0 }})</small>
                                    </div>
                                    <small>InActive</small>
                                    <small class="text-danger">({{ $data['customer']['inactive_count'] ?? 0 }})</small>
                                </div>
                                <span class="badge bg-label-info rounded p-2">
                                    <i class="bx bx-building-house bx-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (checkPermission([config('params.admin_role')]))
                <div class="col-sm-6 col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="content-left">
                                    <a href="{{ route('company.index') }}" class=" text-success stretched-link"
                                        title="Redirect to Customer Listing">
                                        Individual Customers ({{ $data['individual_customer']['total_count'] ?? 0 }})
                                    </a>
                                    <div class="d-flex align-items-end mt-2">
                                        <small class="mb-0 me-2">Active</small>
                                        <small
                                            class="text-success">({{ $data['individual_customer']['active_count'] ?? 0 }})</small>
                                    </div>
                                    <small>InActive</small>
                                    <small
                                        class="text-danger">({{ $data['individual_customer']['inactive_count'] ?? 0 }})</small>
                                </div>
                                <span class="badge bg-label-success rounded p-2">
                                    <i class="bx bx-group bx-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <a href="{{ route('store.index') }}" class=" text-primary stretched-link">
                                    Stores({{ $data['store']['total_count'] ?? 0 }})
                                </a>
                                <div class="d-flex align-items-end mt-2">
                                    <small class="mb-0 me-2">Active</small>
                                    <small class="text-success">({{ $data['store']['active_count'] ?? 0 }})</small>
                                </div>
                                <small>InActive</small>
                                <small class="text-danger">({{ $data['store']['inactive_count'] ?? 0 }})</small>
                            </div>
                            <span class="badge bg-label-primary rounded p-2">
                                <i class="bx bx-store-alt bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <a href="{{ route('request.index') }}" class=" text-primary stretched-link">
                                    Successful Requests({{ $data['orders']['success_count'] ?? 0 }})
                                </a>
                                <div class="d-flex align-items-end mt-2">
                                    <small class="mb-0 me-2">&nbsp;</small>
                                    <small class="text-success">&nbsp;</small>

                                </div>

                            </div>
                            <span class="badge bg-label-primary rounded p-2">
                                <i class="bx bx-shopping-bag bx-sm"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@push('css')
@endpush
@push('js')
@endpush
