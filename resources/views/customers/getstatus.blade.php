@if ($value->status == 1)
    <span class="badge rounded-pill bg-success changestatus pointer" data-type='customer'
        onclick="changeStatus('{{ $value->uuid }}', {{ config('params.in_active') }},$(this))"
        data-url="{{ route('customer.changestatus') }}" data-status="InActive" title="click to inactive"
        data-id="{{ $value->id }}">Active</span>
@elseif ($value->status == 2)
    <span class="badge rounded-pill bg-warning changestatus pointer" data-type='customer'
        onclick="changeStatus('{{ $value->uuid }}', {{ config('params.active') }},$(this))"
        data-url="{{ route('customer.changestatus') }}" data-status="Active" title="click to Active">Inactive</span>
@elseif (!empty($value->deleted_at))
    <span class="badge rounded-pill bg-danger">Deleted</span>
@endif
