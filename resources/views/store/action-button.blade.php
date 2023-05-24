<!--begin::Action--->

{{-- <a href="{{ route('plans.edit', [$value->uuid]) }}" class=" text-primary " title="Edit">
        <i class="fa-regular bx bxs-edit me-2 fs-8 " aria-hidden="true"></i>
    </a> --}}
<a href="javascript:;" onclick="userDelete('{{ $value->uuid }}', $(this))"
    data-url="{{ route('store.destroy', [$value->uuid]) }}" class=" text-danger delete-record" title="Delete">
    <i class="fa-regular fa-trash-can fs-8 " aria-hidden="true"></i>
</a>

<!--end::Action--->
