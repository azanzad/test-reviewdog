@if ($value->is_trial == 0)
    <span class="badge bg-warning rounded-pill">No</span>
@elseif ($value->is_trial == 1)
    <span class="badge bg-success rounded-pill">{{$value->trial_days}} days</span>
@endif