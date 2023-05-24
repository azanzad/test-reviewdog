

<script>

    @if(Session::has('success'))
        displaySuccessMessage("{{ session('success') }}");
    @endif

    @if(Session::has('error'))
        displayErrorMessage("{{ session('error') }}");
    @endif

    @if(Session::has('info'))
        toastr.info("{{ session('info') }}");
    @endif

    @if(Session::has('warning'))
  		toastr.warning("{{ session('warning') }}");
    @endif
</script>
