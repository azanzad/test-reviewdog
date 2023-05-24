@if ($value->status == 1)
    <span class="label label-success label-dot mr-2 "></span>
    <span class="badge rounded-pill bg-success  ">Active</span>
@elseif ($value->status == 2)
    <span class="label label-success label-dot mr-2 "></span>
    <span class="badge rounded-pill bg-warning  ">Inactive</span>
@elseif (!empty($value->deleted_at))
    <span class="badge rounded-pill bg-danger">Deleted</span>
@endif
