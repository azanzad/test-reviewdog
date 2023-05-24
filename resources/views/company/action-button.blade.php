<!--begin::Action--->
<a href="javascript:" data-bs-toggle="modal" data-bs-target="#basicModal" class="openaddmodal text-primary"
    data-backdrop='static' data-keyboard='false' data-encyptid="{{ base64_encode($value->id) }}"
    data-id="{{ $value->id }}" data-cname="{{ $value->name }}" data-cus_count="{{ getSubCustomerCount($value->id) }}">
    <i class="fa-regular bx bx-group  me-2 fs-8"></i>
</a>
<a href="{{ route('company.show', [$value->uuid]) }}" class=" text-primary " title="View" onclick="getButtonState()">
    <i class="fa-regular fa-eye me-2 fs-8 " aria-hidden="true"></i>
</a>
<a href="{{ route('company.edit', [$value->uuid]) }}" class=" text-primary " onclick="getButtonState()">
    <i class="fa-regular bx bxs-edit me-2 fs-8 " aria-hidden="true"></i>
</a><a href="javascript:;" onclick="userDelete('{{ $value->uuid }}', $(this))"
    data-url="{{ route('company.destroy', [$value->uuid]) }}" class="text-danger delete-record">
    <i class="fa-regular fa-trash-can me-2 fs-8 " aria-hidden="true"></i>
</a>

<!--end::Action--->
