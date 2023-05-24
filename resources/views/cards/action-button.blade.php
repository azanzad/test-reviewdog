<!--begin::Action--->
{{-- <a href="{{ route('cards.show', [$value->uuid]) }}" class=" text-primary " title="View">
    <i class="fa-regular fa-eye me-2 fs-8 " aria-hidden="true"></i>
</a> --}}
@if (!$value->is_primary)
    <a href="javascript:;" onclick="userDelete('{{ $value->uuid }}', $(this))"
        data-url="{{ route('cards.destroy', [$value->uuid]) }}" class="text-danger delete-record">
        <i class="fa-regular fa-trash-can me-2 fs-8 " aria-hidden="true"></i>
    </a>
@endif

<!--end::Action--->
