<!--begin::Action--->
<a href="{{ route('plans.show', [$value->uuid]) }}" class=" text-primary " title="View" onclick="getButtonState()">
    <i class="fa-regular fa-eye me-2 fs-8 " aria-hidden="true"></i>
</a>
@if (empty($isused))
    {{-- <a href="{{ route('plans.edit', [$value->uuid]) }}" class=" text-primary " title="Edit">
        <i class="fa-regular bx bxs-edit me-2 fs-8 " aria-hidden="true"></i>
    </a> --}}
    <a href="javascript:;" onclick="userDelete('{{ $value->uuid }}', $(this))"
        data-url="{{ route('plans.destroy', [$value->uuid]) }}" class=" text-danger delete-record" title="Delete">
        <i class="fa-regular fa-trash-can me-2 fs-8 " aria-hidden="true"></i>
    </a>
@endif
<!--end::Action--->
