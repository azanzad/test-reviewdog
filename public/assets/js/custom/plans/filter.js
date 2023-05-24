$('#plan_date_range').flatpickr({
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
    param.plan_type = $('#plan_type').find(":selected").val();
    param.plan_name = $('#plan_name').val();
    param.price = $('#price').val();
    param.price_operation = $('#price_condition').find(":selected").text();
    param.price_to = $('#price_to').val();
    param.interval_count = $('#interval_count').val();
    param.plan_durations = $('#plan_durations').find(":selected").val();
    param.status = $('#status').find(":selected").val();
    param.plan_date_range = $('#plan_date_range').val();

    // Delete empty values
    param.plan_type === '' ? delete param.plan_type : '';
    param.plan_name === '' ? delete param.plan_name : '';
    if(param.price === ''){
        delete param.price;
        delete param.price_operation;
        delete param.price_to;
    }
    if(param.price_operation != 'Range'){
        delete param.price_to;
    }
    if (param.interval_count === '') {
        delete param.interval_count;
        delete param.plan_durations;
    }
    param.status === '' ? delete param.status : '';
    param.plan_date_range === '' ? delete param.plan_date_range : '';

    itemDataTable(param);

    $("#close_canvas").click();
    $.isEmptyObject(param) ? $('#font-filter-applied').hide() : $('#font-filter-applied').css('display','inline-block');
});

$('#clearFilters').on('click', function (e) {
    $('#plan_type').val('');
    $('#plan_name').tagsinput('removeAll');
    $('#price').val('');
    $('#price_condition').val('1').trigger('change');
    $('#price_to').val('');
    $('#interval_count').val('');
    $('#plan_durations').val('1').trigger('change');
    $('#status').val('');
    $('#plan_date_range').val('');
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