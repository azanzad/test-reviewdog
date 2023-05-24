<!--begin::Action--->
@if (!empty($value->email) && $value->customer_type == 2)
    <a href="javascript:;" title="Send store integration link" class="send_store_link text-info "
        data-url="{{ route('customer.send-store-link') }}" data-id="{{ $value->id }}">
        <i class="fa-solid fa-share-from-square me-2 fs-8"></i>
    </a>
@endif
@if ($value->customer_type == config('params.individual_brand'))
    <a href="{{ route('company.show', [$value->uuid]) }}" class=" text-primary " title="View" onclick="getButtonState()">
        <i class="fa-regular fa-eye me-2 fs-8 " aria-hidden="true"></i>
    </a>
@endif

<a href="{{ route('customer.edit', [$value->uuid]) }}" class=" text-primary " title="edit" onclick="getButtonState()">
    <i class="fa-regular bx bxs-edit me-2  fs-8 " aria-hidden="true"></i>
</a>
<a href="javascript:;" onclick="userDelete('{{ $value->uuid }}', $(this))"
    data-url="{{ route('customer.destroy', [$value->uuid]) }}" title="Delete" class=" text-danger delete-record"
    @if ($value->role == config('params.user_roles.customer')) data-type='customer' @endif>
    <i class="fa-regular fa-trash-can me-2  fs-8 " aria-hidden="true"></i>
</a>

<!--end::Action--->
