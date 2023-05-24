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
                        <a href="{{ route('company.index') }}">Customers</a> /
                    </span>
                    <span class="text-muted fw-normal">
                        @if($data->role == config('params.company_role') && $data->customer_type == null)
                            Parent Company Details
                        @elseif($data->role == config('params.company_role') && $data->customer_type == config('params.individual_brand'))
                            Individual Customer Details
                        @endif
                    </span>
                </div>

            </div>
        </h6>
        <div class="row">
            <div class="col-sm-12">
                <div class="card mb-4">
                    <div class="card-header p-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            @if($data->role == config('params.company_role') && $data->customer_type == null)
                                Parent Company Details
                            @elseif($data->role == config('params.company_role') && $data->customer_type == config('params.individual_brand'))
                                Individual Customer Details
                            @endif
                        </h5>
                        <div class="float-end">
                            @if(isset($subscription->stripe_status) && $subscription->stripe_status == 'active')
                                <form method="post" id="cancelSubscription_fr" action="{{ route('cancel.subscriptionbyadmin') }}"
                                    onsubmit="return false;">
                                    @csrf
                                    @method('POST')
                                    <input type="hidden" name="subscription_type" id="subscription_type" value="cancel">
                                    <input type="hidden" name="user_uuid" id="user_uuid" value="{{$data->uuid}}" />
                                </form>
                                <button type="button"  class="btn btn-sm rounded-pill btn-outline-danger font-weight-bold " id="cancelSubscription_btn"><i class="fa fa-remove"
                                    aria-hidden="true"></i> Cancel Subscription</button>
                                @endif
                            <!--<a href="{{ route('company.edit', [$data->uuid]) }}"
                                class="btn btn-sm rounded-pill btn-outline-primary font-weight-bold "><i class="fa fa-pen"
                                    aria-hidden="true"></i> edit</a>-->
                            <a href="{{ route('company.index') }}"
                                class="btn btn-sm rounded-pill btn-outline-primary font-weight-bold "><i
                                    class="fa fa-angle-double-left"></i> Back</a>
                        </div>

                    </div>

                    <div class="card-body">

                        <div class="row mt-3 col-md-12">

                            <div class="col-md-4">
                                <ul class="list-unstyled">
                                    <li class="mb-3">
                                        <span class="fw-bold me-2">
                                            @if($data->role == config('params.company_role') && $data->customer_type == null)
                                                Parent Company Name:
                                            @elseif($data->role == config('params.company_role') && $data->customer_type == config('params.individual_brand'))
                                                Individual Customer Name:
                                            @endif
                                        </span>
                                        <span>{{ $data->name }}</span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="fw-bold me-2">Subscription Plan:</span>
                                        <span>${{ $data->plan_price }}/{{ $data->getPlan ? config('params.plan_durations.' . $data->getPlan->interval) : '' }}</span>
                                    </li>

                                    <li class="mb-3">
                                        <span class="fw-bold me-2">
                                            @if($data->role == config('params.company_role') && $data->customer_type == null)
                                                Parent Company Notes:
                                            @elseif($data->role == config('params.company_role') && $data->customer_type == config('params.individual_brand'))
                                                Individual Customer Notes:
                                            @endif
                                        </span>
                                        <span>{{ $data->company_description ?? 'N / A' }}</span>
                                    </li>

                                    <li class="mb-3">
                                        <span class="fw-bold me-2">Bill Date:</span>
                                        <span>{{ $data->next_billing_date != null ? date('d-m-Y h:i a', strtotime($data->next_billing_date)) : ''}}</span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="fw-bold me-2"># Emails: </span>
                                        <span>{{ $data['email_count'] }}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <ul class="list-unstyled">
                                    <li class="mb-3">
                                        <span class="fw-bold me-2">
                                            @if($data->role == config('params.company_role') && $data->customer_type == null)
                                                Parent Company Email:
                                            @elseif($data->role == config('params.company_role') && $data->customer_type == config('params.individual_brand'))
                                                Individual Customer Email:
                                            @endif
                                        </span>
                                        <span>{{ $data->email }}</span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="fw-bold me-2">Free trial:</span>
                                        <span>
                                            @if ($data->is_trial)
                                                <span class="badge rounded-pill bg-success ">{{ $data->trial_days }} Days</span>
                                            @else
                                                <span class="badge rounded-pill bg-warning">No</span>
                                            @endif

                                        </span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="fw-bold me-2">Created Date:</span>
                                        <span>{{ $data->created_at->format('d/m/Y h:i:s A') }}</span>
                                    </li>

                                    <li class="mb-3">
                                        <span class="fw-bold me-2"># Stores:</span>
                                        <span>
                                            {{ $data_['stores'] }}
                                        </span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="fw-bold me-2">Subscription:</span>
                                        @if (isset($subscription->stripe_status) && $subscription->stripe_status == 'active')
                                            <span class="badge rounded-pill bg-success ">Active</span>
                                        @else
                                            <span class="badge rounded-pill bg-danger">Cancel</span>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <ul class="list-unstyled">
                                    <li class="mb-3">
                                        <span class="fw-bold me-2">Website:</span>
                                        <span>{{ $data->website }}</span>
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
                                    <li class="mb-3">
                                        <span class="fw-bold me-2"># Brands:</span>
                                        <span>
                                            @if ($data->role == config('params.company_role') && $data->customer_type == null)
                                                {{ count($data->customers) }}
                                            @elseif($data->role == config('params.company_role') && $data->customer_type == config('params.individual_brand'))
                                                '-'
                                            @endif
                                        </span>
                                    </li>

                                    <li class="mb-3">
                                        <span class="fw-bold me-2"># Requests:</span>
                                        <span>
                                            {{ $data['success_count'] }}
                                        </span>
                                    </li>

                                </ul>
                            </div>
                        </div>
                        <hr>
                        <!--customer list--->
                        <div class="row g-4">
                            <h6 class="mb-0">Contact List</h6>
                            <div class="card border no-show">
                                <div class="card-datatable text-nowrap table-responsive">
                                    <table id="dataTable" class="dt-fixedheader table ">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Contact Name</th>
                                                <th>Title</th>
                                                <th>Email</th>
                                                <th>Contact Number</th>
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
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endpush
@push('js')
    <!-- Page JS -->
    <script src="{{ asset('assets/vendor/libs/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-responsive/datatables.responsive.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js') }}"></script>

    <!-- Fixed header -->
    <script src="{{ asset('assets/vendor/libs/datatables-fixedheader-bs5/fixedheader.bootstrap5.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="{{ asset('assets/js/custom/company/details.js') }}"></script>
    <script>
        var route = "{{ route('company.getAllContacts', [$data->uuid]) }}";
    </script>
@endpush
