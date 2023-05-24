<div class="d-flex justify-content-md-end">
    <a href="javascript:void(0);" type="button" class="btn btn-sm rounded-pill btn-outline-primary me-1"
        data-bs-toggle="offcanvas" data-bs-target="#offcanvasEnd" aria-controls="offcanvasEnd">
        <i class="fa fa-filter"></i>
        <span class="badge badge-dot bg-danger mb-1 " id="font-filter-applied"></span>
    </a>
</div>
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEnd" aria-labelledby="offcanvasEndLabel">
    <div class="offcanvas-header">
        <h5 id="offcanvasEndLabel" class="offcanvas-title">Filters</h5>
        <button type="button" id="close_canvas" class="btn-close text-reset" data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0">

        <div class="mb-3">
            <label for="amazon_order_id" class="form-label">Amazon Order Id</label>
            <input class="form-control" type="text" data-role="tagsinput" id="amazon_order_id"
                name="amazon_order_id">
        </div>
        <div class="mb-3">
            <label for="order_date_range" class="form-label">Order Date</label>
            <input type="text" class="form-control" placeholder="YYYY-MM-DD to YYYY-MM-DD" id="order_date_range" />
        </div>
        <div class="mb-3">
            <label for="order_status" class="form-label">Order Status</label>
            <div class="select2-primary">
                <select id="order_status" name="order_status" class="select2 form-select" multiple>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label for="request_status" class="form-label">Request Status</label>
            <select id="request_status" name="request_status" class="form-select">
                <option>Request Status</option>
                <option value="0">Pending</option>
                <option value="1">Successful</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="requested_date_range" class="form-label">Requested Date</label>
            <input type="text" class="form-control" placeholder="YYYY-MM-DD to YYYY-MM-DD" id="requested_date_range" />
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
    <script>
        var order_status_route = "{{ route('request.select-order-status') }}";
    </script>
    <script src="{{ asset('assets/js/custom/requests/form.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js"></script>
@endpush
