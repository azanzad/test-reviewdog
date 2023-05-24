$('#created_date').flatpickr({
    mode: "range"
});

$('#searchFilters').on('click', function (e) {
    var param = {};

    // Get all values
    param.customer_name = $('#customer_name').val();
    param.email = $('#email').val();
    param.parent_company = $('#parent_company').val();
    param.customer_type = $('#customer_type').find(":selected").val();
    param.status = $('#status').find(":selected").val();
    param.created_date = $('#created_date').val();

    // Delete empty values
    param.customer_name === '' ? delete param.customer_name : '';
    param.email === '' ? delete param.email : '';
    param.parent_company === '' ? delete param.parent_company : '';
    param.customer_type === '' ? delete param.customer_type : '';
    param.status === '' ? delete param.status : '';
    param.created_date === '' ? delete param.created_date : '';

    itemDataTable(param);

    $("#close_canvas").click();
    $.isEmptyObject(param) ? $('#font-filter-applied').hide() : $('#font-filter-applied').css('display','inline-block');
});

$('#clearFilters').on('click', function (e) {
    $('#customer_name').tagsinput('removeAll');
    $('#email').tagsinput('removeAll');
    $('#parent_company').val('').tagsinput('removeAll');    
    $('#customer_type').val('');
    $('#status').val('');
    $('#created_date').val('');
    $('#font-filter-applied').hide();
    itemDataTable();
    $("#close_canvas").click();
});