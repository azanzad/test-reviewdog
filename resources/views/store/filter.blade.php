<a href="javascript:void(0);" type="button" class="btn btn-sm rounded-pill btn-outline-primary me-1"
    data-bs-toggle="offcanvas" data-bs-target="#offcanvasEnd" aria-controls="offcanvasEnd">
    <i class="fa fa-filter"></i>
    <span class="badge badge-dot bg-danger mb-1 " id="font-filter-applied"></span>
</a>
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEnd" aria-labelledby="offcanvasEndLabel">
    <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasEndLabel" class="offcanvas-title">Filters</h5>
        <button type="button" id="close_canvas" class="btn-close text-reset" data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0">
        <div class="mb-3">
            <label for="store_name" class="form-label">Store Name</label>
            <input class="form-control" type="text" data-role="tagsinput" id="store_name" name="store_name">
        </div>
        <div class="mb-3">
            <label for="customer_name" class="form-label">Customer Name</label>
            <input class="form-control" type="text" data-role="tagsinput" id="customer_name" name="customer_name">
        </div>
        <div class="mb-3">
            <label for="parent_company" class="form-label">Parent Company</label>
            <input class="form-control" type="text" data-role="tagsinput" id="parent_company" name="parent_company">
        </div>
        <div class="mb-3">
            <label for="store_type" class="form-label">Store Type</label>
            <input class="form-control" type="text" data-role="tagsinput" id="store_type" name="store_type">
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
            <label for="store_created_date" class="form-label">Store Created Date</label>
            <input type="text" class="form-control" placeholder="YYYY-MM-DD to YYYY-MM-DD" id="store_created_date"
                name="store_created_date" />
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
    <script src="{{ asset('assets/js/custom/store/filter.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js"></script>
@endpush
