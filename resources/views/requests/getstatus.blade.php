@if ($value->is_request_sent == 0)
    <span class="badge bg-warning rounded-pill">Pending</span>
    <br>
    <span>{{ \Carbon\Carbon::parse($value->request_sent_date)->format('d/m/Y h:i:s A') }}</span>
@elseif ($value->is_request_sent == 1)
    <span class="badge bg-success rounded-pill">Successful</span>
    <br>
    <span>{{ \Carbon\Carbon::parse($value->request_sent_date)->format('d/m/Y h:i:s A') }}</span>
@elseif ($value->is_request_sent == 2)
    <span class="badge bg-danger rounded-pill">Fail</span>
    <br>
    <span>{{ \Carbon\Carbon::parse($value->request_sent_date)->format('d/m/Y h:i:s A') }}</span>
@elseif ($value->is_request_sent == 3)
    <span class="badge bg-danger rounded-pill">Not Eligible</span>
    <br>
    <span>{{ \Carbon\Carbon::parse($value->request_sent_date)->format('d/m/Y h:i:s A') }}</span>
@endif
