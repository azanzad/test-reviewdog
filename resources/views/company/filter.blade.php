<a href="javascript:void(0);" type="button" class="btn btn-sm rounded-pill btn-outline-primary me-1"
    data-bs-toggle="offcanvas" data-bs-target="#offcanvasEnd" aria-controls="offcanvasEnd">
    <i class="fa fa-filter"></i>
    <span class="badge badge-dot bg-danger mb-1 " id="font-filter-applied"></span>
</a>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEnd" aria-labelledby="offcanvasEndLabel">
    <div class="offcanvas-header">
        <h5 id="offcanvasEndLabel" class="offcanvas-title">Filters</h5>
        <button type="button" id="close_canvas" class="btn-close text-reset" data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0">
        <h6 class="card-title">Company Filters</h6>
        <div class="mb-3">
            <label for="company_name" class="form-label">Company Name</label>
            <input class="form-control" type="text" data-role="tagsinput" id="company_name" name="company_name">
        </div>
        <div class="mb-3">
            <label for="company_email" class="form-label">Company Email</label>
            <input class="form-control" type="text" data-role="tagsinput" id="company_email" name="company_email">
        </div>
        @if (checkPermission([config('params.admin_role')]))
            <div class="mb-3">
                <label for="customer_type" class="form-label">Customer Type</label>
                <select name="customer_type" id="customer_type" class="form-select">
                    <option value="">Select Customer Type</option>
                    @foreach (config('params.customer_types') as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        <div class="mb-3">
            <label for="is_trial" class="form-label">Free Trial</label>
            <select name="is_trial" id="is_trial" class="form-select">
                <option value="">Select Trial Availability</option>
                @foreach (config('params.trial_availability') as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <label for="trial_condition" class="form-label">Free Trial Days</label>
        <div class="d-flex mb-3">
            <select name="trial_condition" id="trial_condition" class="form-select">
                @foreach (config('params.price_check_conditions') as $key => $value)
                    <option value="{{ $key }}" @if ($key == 1) selected @endif>
                        {{ $value }}</option>
                @endforeach
            </select>
            <input type="number" name="range_from" id="range_from" class="form-control" placeholder="Trial Days" />
            <input type="number" name="range_to" id="range_to" class="form-control d-none" placeholder="Trial To" />
        </div>
        <div class="mb-3">
            <label for="plan" class="form-label">Plan Name</label>
            <input class="form-control" type="text" data-role="tagsinput" id="plan" name="plan">
        </div>
        <div class="mb-3">
            <input type="checkbox" id="over_sales" name="over_sales" class="form-check-input" value="0"/>
            <label for="over_sales" class="form-label">Over Sales</label>
        </div>

        <label for="over_sales_condition" class="form-label">Sales Amount</label>
        <div class="d-flex mb-3">
            <select name="over_sales_condition" id="over_sales_condition" class="form-select">
                @foreach (config('params.price_check_conditions') as $key => $value)
                    <option value="{{ $key }}" @if ($key == 1) selected @endif>
                        {{ $value }}</option>
                @endforeach
            </select>
            <input type="number" name="over_sales_from" id="over_sales_from" class="form-control" placeholder="From" />
            <input type="number" name="over_sales_to" id="over_sales_to" class="form-control d-none" placeholder="To" />
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select">
                <option value="">Select Status</option>
                @foreach (config('params.status') as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="date_range" class="form-label">Created Date</label>
            <input type="text" class="form-control" placeholder="YYYY-MM-DD to YYYY-MM-DD" id="date_range"
                name="date_range" />
        </div>
        <button type="button" class="btn rounded-pill btn-outline-primary" id="searchFilters">Search</button>
        <button type="button" class="btn rounded-pill btn-outline-dark" id="clearFilters">Clear</button>
    </div>
</div>
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css"
        rel="stylesheet" />
@endpush
@push('js')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/js/custom/company/filter.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js"></script>
@endpush
