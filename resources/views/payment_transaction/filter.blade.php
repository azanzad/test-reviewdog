<a href="javascript:void(0);" type="button" class="btn btn-sm rounded-pill btn-outline-primary me-1"
    data-bs-toggle="offcanvas" data-bs-target="#offcanvasEnd" aria-controls="offcanvasEnd">
    <i class="fa fa-filter "></i>
    <span class="badge badge-dot bg-danger mb-1 " id="font-filter-applied"></span>
</a>
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEnd" aria-labelledby="offcanvasEndLabel">
    <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasEndLabel" class="offcanvas-title">Filters</h5>
        <button type="button" id="close_canvas" class="btn-close text-reset" data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0">
        @if (auth()->user()->role == config('params.admin_role'))
            <div class="mb-3">
                <label for="customer_name" class="form-label">Customer Name</label>
                <input class="form-control" type="text" data-role="tagsinput" id="customer_name"
                    name="customer_name">
            </div>
        @endif
        <div class="mb-3">
            <label for="plan_name" class="form-label">Plan Name</label>
            <input class="form-control" type="text" data-role="tagsinput" id="plan_name" name="plan_name">
        </div>
        <label for="price" class="form-label">Price</label>
        <div class="d-flex mb-3">
            <select name="price_condition" id="price_condition" class="form-select">
                @foreach (config('params.price_check_conditions') as $key => $value)
                    <option value="{{ $key }}" @if ($key == 1) selected @endif>
                        {{ $value }}</option>
                @endforeach
            </select>
            <input type="number" name="price" id="price" class="form-control" placeholder="Price" />
            <input type="number" name="price_to" id="price_to" class="form-control d-none" placeholder="Price To" />
        </div>
        <div class="mb-3">
            <label for="transaction_status" class="form-label">Transaction Status</label>
            <input class="form-control" type="text" data-role="tagsinput" id="transaction_status"
                name="transaction_status">
        </div>
        <div class="mb-3">
            <label for="transaction_date" class="form-label">Transaction Date</label>
            <input type="text" class="form-control" placeholder="YYYY-MM-DD to YYYY-MM-DD" id="transaction_date"
                name="transaction_date" />
        </div>
        <button type="button" class="btn rounded-pill btn-outline-primary" id="searchFilters">Search</button>
        <button type="button" class="btn rounded-pill btn-outline-dark" id="clearFilters">Clear</button>
    </div>
</div>
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css"
        rel="stylesheet" />
@endpush
@push('js')
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/js/custom/payment_transaction/filter.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js"></script>
@endpush
