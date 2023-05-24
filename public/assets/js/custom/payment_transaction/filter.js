$('#transaction_date').flatpickr({
    mode: "range"
});

$('#searchFilters').on('click', function (e) {
    $.isNumeric($('#price').val()) ? '' : $('#price').val('');
    $.isNumeric($('#price_to').val()) ? '' : $('#price_to').val('');

    if(parseFloat($('#price').val()) > parseFloat($('#price_to').val())){
        let transferValue = parseFloat($('#price_to').val());
        $('#price_to').val(parseFloat($('#price').val()));
        $('#price').val(transferValue);
    }

    var param = {};

    // Get all values
    param.customer_name = $('#customer_name').val();
    param.plan_name = $('#plan_name').val();
    param.price = $('#price').val();
    param.price_operation = $('#price_condition').find(":selected").text();
    param.price_to = $('#price_to').val();
    param.transaction_status = $('#transaction_status').val();
    param.transaction_date = $('#transaction_date').val();

    // Delete empty values
    param.customer_name === '' ? delete param.customer_name : '';
    param.plan_name === '' ? delete param.plan_name : '';
    if(param.price === ''){
        delete param.price;
        delete param.price_operation;
        delete param.price_to;
    }
    if(param.price_operation != 'Range'){
        delete param.price_to;
    }
    param.transaction_status === '' ? delete param.transaction_status : '';
    param.transaction_date === '' ? delete param.transaction_date : '';

    itemDataTable(param);

    $("#close_canvas").click();
    $.isEmptyObject(param) ? $('#font-filter-applied').hide() : $('#font-filter-applied').css('display','inline-block');
});

$('#clearFilters').on('click', function (e) {
    $('#customer_name').tagsinput('removeAll');
    $('#plan_name').tagsinput('removeAll');
    $('#price').val('');
    $('#price_condition').val('1').trigger('change');
    $('#price_to').val('');
    $('#transaction_status').tagsinput('removeAll');
    $('#transaction_date').val('');
    $('#font-filter-applied').hide();
    itemDataTable();
    $("#close_canvas").click();
});

$('#price_condition').change(function () {
    if($(this).find(":selected").val() == '6'){
        $('#price').attr("placeholder", "Price From");
        $('#price_to').removeClass('d-none');
    }else{
        $('#price_to').addClass('d-none');
        $('#price').attr("placeholder", "Price");
    }
});