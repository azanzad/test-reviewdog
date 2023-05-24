$('#store_created_date').flatpickr({
    mode: "range"
});

$('#searchFilters').on('click', function (e) {
    var param = {};

    // Get all values
    param.store_name = $('#store_name').val();
    param.customer_name = $('#customer_name').val();
    param.parent_company = $('#parent_company').val();
    param.store_type = $('#store_type').val();
    param.status = $('#status').find(":selected").val();
    param.store_created_date = $('#store_created_date').val();

    // Delete empty values
    param.store_name === '' ? delete param.store_name : '';
    param.customer_name === '' ? delete param.customer_name : '';
    param.parent_company === '' ? delete param.parent_company : '';
    param.store_type === '' ? delete param.store_type : '';
    param.status === '' ? delete param.status : '';
    param.store_created_date === '' ? delete param.store_created_date : '';

    itemDataTable(param);

    $("#close_canvas").click();
    $.isEmptyObject(param) ? $('#font-filter-applied').hide() : $('#font-filter-applied').css('display','inline-block');
});

$('#clearFilters').on('click', function (e) {
    $('#store_name').tagsinput('removeAll');
    $('#customer_name').tagsinput('removeAll');
    $('#parent_company').val('').tagsinput('removeAll');
    $('#store_type').val('').tagsinput('removeAll');
    $('#status').val('');
    $('#store_created_date').val('');
    $('#font-filter-applied').hide();
    itemDataTable();
    $("#close_canvas").click();
});