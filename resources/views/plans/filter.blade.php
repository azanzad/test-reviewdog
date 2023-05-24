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
            <label for="plan_type" class="form-label">Plan Type</label>
            <select name="plan_type" id="plan_type" class="form-select">
                <option value="">Select Plan Type</option>
                @foreach (config('params.plan_types') as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
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

        <label for="interval_count" class="form-label">Duration</label>
        <div class="d-flex mb-3">
            <input type="number" name="interval_count" id="interval_count" class="form-control"
                placeholder="Enter duration" />
            <select name="plan_durations" id="plan_durations" class="form-select">
                @foreach (config('params.plan_durations') as $key => $value)
                    <option value="{{ $key }}" @if ($key == 1) selected @endif>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
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
            <label for="plan_date_range" class="form-label">Plan Created Date</label>
            <input type="text" class="form-control" placeholder="YYYY-MM-DD to YYYY-MM-DD" id="plan_date_range"
                name="plan_date_range" />
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
    <script src="{{ asset('assets/js/custom/plans/filter.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js"></script>
@endpush
