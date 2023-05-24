@if ($value->status == 1)
    <span class="label label-success label-dot mr-2 "></span>
    <span class="badge rounded-pill bg-success changestatus @if (empty($isused)) pointer @endif"
        @if (empty($isused)) onclick="changeStatus('{{ $value->uuid }}', {{ config('params.in_active') }},$(this))" @endif
        data-url="{{ route('store.changestatus') }}" data-status="InActive"
        @if (empty($isused)) title="click to inactive" @endif>Active</span>
@elseif ($value->status == 2)
    <span class="label label-success label-dot mr-2 "></span>
    <span class="badge rounded-pill bg-warning changestatus pointer"
        onclick="changeStatus('{{ $value->uuid }}', {{ config('params.active') }},$(this))"
        data-url="{{ route('store.changestatus') }}" data-status="Active" title="click to Active">Inactive</span>
@elseif (!empty($value->deleted_at))
    <span class="badge rounded-pill bg-danger">Deleted</span>
@endif
