$('#date_range').flatpickr({
    mode: "range"
});

//set over sales checkbox value
$('#over_sales').on('change', function(){
    if( $('#over_sales').is(':checked') ){
        $('#over_sales').val(1);
    }
    else{
        $('#over_sales').val(0)
    }
});
$('#searchFilters').on('click', function (e) {
    $.isNumeric($('#range_from').val()) ? '' : $('#range_from').val('');
    $.isNumeric($('#range_to').val()) ? '' : $('#range_to').val('');

    if(parseFloat($('#range_from').val()) > parseFloat($('#range_to').val())){
        let transferValue = parseFloat($('#range_to').val());
        $('#range_to').val(parseFloat($('#range_from').val()));
        $('#range_from').val(transferValue);
    }

    var param = {};

    // Get all values
    param.company_name = $('#company_name').val();
    param.company_email = $('#company_email').val();
    param.customer_type = $('#customer_type').find(":selected").val();
    param.range_from = $('#range_from').val();
    param.trial_condition = $('#trial_condition').find(":selected").text();
    param.range_to = $('#range_to').val();
    param.plan = $('#plan').val();
    param.status = $('#status').find(":selected").val();
    param.is_trial = $('#is_trial').find(":selected").val();
    param.date_range = $('#date_range').val();
    param.over_sales_amount = $('#over_sales_amount').val();
    param.over_sales =  $('#over_sales').val();
    param.over_sales_condition = $('#over_sales_condition').find(":selected").text();
    param.over_sales_from = $('#over_sales_from').val();
    param.over_sales_to = $('#over_sales_to').val();

    if(param.over_sales_from === ''){
        delete param.over_sales_from;
        delete param.over_sales_condition;
        delete param.over_sales_to;
    }
    if(param.trial_condition != 'Range'){
        delete param.range_to;
    }

    // Delete empty values
    param.company_name === '' ? delete param.company_name : '';
    param.company_email === '' ? delete param.company_email : '';
    param.customer_type === '' ? delete param.customer_type : '';
    if(param.range_from === ''){
        delete param.range_from;
        delete param.trial_condition;
        delete param.range_to;
    }
    if(param.trial_condition != 'Range'){
        delete param.range_to;
    }
    param.plan === '' ? delete param.plan : '';
    param.status === '' ? delete param.status : '';
    param.is_trial === '' ? delete param.is_trial : '';
    param.date_range === '' ? delete param.date_range : '';

    itemDataTable(param);

    $("#close_canvas").click();
    $.isEmptyObject(param) ? $('#font-filter-applied').hide() : $('#font-filter-applied').css('display','inline-block');
});

$('#clearFilters').on('click', function (e) {
    $('#company_name').tagsinput('removeAll');
    $('#company_email').tagsinput('removeAll');
    $('#customer_type').val('');
    $('#range_from').val('');
    $('#trial_condition').val('1').trigger('change');
    $('#range_to').val('');
    $('#plan').val('').tagsinput('removeAll');
    $('#status').val('');
    $('#is_trial').val('');
    $('#date_range').val('');
    $('#font-filter-applied').hide();

    $('#over_sales_condition').val('1').trigger('change');
    $('#over_sales_from').val('');
    $('#over_sales_to').val('');
    $('#over_sales').val(0);
    $('#over_sales').prop('checked', false);


    itemDataTable();
    $("#close_canvas").click();
});

$('#trial_condition').change(function () {
    if($(this).find(":selected").val() == '6'){
        $('#range_from').attr("placeholder", "Trial From");
        $('#range_to').removeClass('d-none');
    }else{
        $('#range_to').addClass('d-none');
        $('#range_from').attr("placeholder", "Trial Days");
    }
});

$('#over_sales_condition').change(function () {
    if($(this).find(":selected").val() == '6'){
        $('#over_sales_to').removeClass('d-none');
    }else{
        $('#over_sales_to').addClass('d-none');
    }
});