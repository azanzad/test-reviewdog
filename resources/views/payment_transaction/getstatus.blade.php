@if ($value->transaction_status == 'paid')
    <span class="badge rounded-pill bg-success ">{{ ucwords($value->transaction_status) }}</span>
@else
    <span class="badge rounded-pill bg-danger ">{{ ucwords($value->transaction_status) }}</span>
@endif
