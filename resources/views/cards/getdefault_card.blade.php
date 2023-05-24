@if ($value->is_primary == 1)
    <span class="badge rounded-pill bg-success  ">Default</span>
@else
    <span class="badge rounded-pill bg-primary changestatus pointer"
        onclick="changeStatus('{{ $value->uuid }}', {{ config('params.active') }},$(this))"
        data-url="{{ route('cards.changeDefaultCard') }}" data-status="Default" title="click to set Default"
        data-type="card">Set
        Default</span>
@endif
