$('#order_date_range').flatpickr({
    mode: "range"
});

$('#requested_date_range').flatpickr({
    mode: "range"
});

$('#searchFilters').on('click', function (e) { 
	var a=$('#select_customer :selected').val();
	if(a=='')
	{
		var a=$('#all_companies :selected').val();
	}
    var param = {};
    param.order_ids = $('#amazon_order_id').val();
    param.order_date_range = $('#order_date_range').val();
    param.requested_date_range = $('#requested_date_range').val();
    param.order_status = JSON.stringify($('#order_status').select2("data"));
    param.request_status = $('#request_status').find(":selected").val();
    param.order_date_range === '' ? delete param.order_date_range : '';
    param.requested_date_range === '' ? delete param.requested_date_range : '';
    param.order_ids === '' ? delete param.order_ids : '';
    param.order_status === '[]' ? delete param.order_status : '';
    param.request_status === 'Request Status' ? delete param.request_status : '';
	param.userid = a;
    itemDataTable(param);
    // $('#clearFilters').click();
    $("#close_canvas").click();
    $.isEmptyObject(param) ? $('#font-filter-applied').hide() : $('#font-filter-applied').css('display','inline-block');
});

$('#clearFilters').on('click', function (e) {
    $('#amazon_order_id').tagsinput('removeAll');
    $('#order_date_range').val('');
    $('#requested_date_range').val('');
    $('#order_status').val('').trigger('change');
    $('#request_status').val('Request Status');
    $('#font-filter-applied').hide();
    itemDataTable();
    $("#close_canvas").click();
});

$("#order_status").select2({
    dropdownParent: $('#offcanvasEnd'),
    ajax: {
        url: order_status_route,
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
            return {
                results: $.map(data, function (item, index) {
                    return {
                        id: index + 1,
                        text: item.order_status,
                    }
                })
            };
        },
        cache: true
    }
});

$('#all_companies').on('change', function () {
    var companyId = this.value;
    if(companyId != ''){
        $("#select_customer").html('');
		
        $.ajax({
            url: customerRoute,
            type: "POST",
            data: {
                companyId: companyId,
                _token: $("meta[name='csrf-token']").attr("content")
            },
            dataType: 'json',
            success: function (res) {
                if (res.length == 0) { 
					$('#div_select_customer').addClass('d-none');
                    $('#select_customer').html('<option value="">Data Not Available</option>');
					
					var param = {};
					param.order_ids = $('#amazon_order_id').val();
					param.order_date_range = $('#order_date_range').val();
                    param.requested_date_range = $('#requested_date_range').val();
					param.order_status = JSON.stringify($('#order_status').select2("data"));
					param.request_status = $('#request_status').find(":selected").val();
					param.order_date_range === '' ? delete param.order_date_range : '';
                    param.requested_date_range === '' ? delete param.requested_date_range : '';
					param.order_ids === '' ? delete param.order_ids : '';
					param.order_status === '[]' ? delete param.order_status : '';
					param.request_status === 'Request Status' ? delete param.request_status : '';
					param.userid = companyId;
					itemDataTable(param);
                }
                else {
					$('#div_select_customer').removeClass('d-none');
                    $('#select_customer').html('<option value="">Select Customer</option>');
                    $.each(res, function (key, value) {
                        $("#select_customer").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            }
        });
    }else{
        $('#div_select_customer').addClass('d-none');
    }
});

$(document).ready(function () {
    var companyId = $('#prefilled_company').val();
    if (companyId != '') {
        $("#select_company_customer").html('');
        $.ajax({
            url: customerRoute,
            type: "POST",
            data: {
                companyId: companyId,
                _token: $("meta[name='csrf-token']").attr("content")
            },
            dataType: 'json',
            success: function (res) { 
                if (res.length == 0) {
                    $('#select_company_customer').html('<option value="">Data Not Available</option>');
                }
                else {
                    $('#select_company_customer').html('<option value="">Select Customer</option>');
                    $.each(res, function (key, value) {
                        $("#select_company_customer").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            }
        });
    }
});