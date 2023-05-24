

$(document).ready(function() {
    
    $('input[type="checkbox"]').click(function(){
        
        if($(this).prop("checked") == true){
            $(this).val(1);
        }
        else if($(this).prop("checked") == false){
            $(this).val('');
        }
    });
    
    $('#apply_btn').click(function(){
       
        var promocode = $('#promocode_value').val();
        var plan_amount = $('#subtotal_amount').text();
        
        if(promocode.trim()){
            $.ajax({
                url: applyPromotion,
                data: {promocode : promocode, plan_amount:plan_amount},
                dataType : "json",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    
                    if(data.status){
                        $('#apply_btn').addClass('d-none');
                        $('#cancel_btn').removeClass('d-none');
                        $('#promotion_token').val(data.promotionToken);
                        $('#promocode_value').prop('disabled',true);
                        $('#promotion_message').text(data.message);
                        $('#afterFreeTrial_amount').text(data.total_amount);
                        $('#promotion_amount').text(data.disscount_amount);
                        $('#promotion_message').addClass('text-success');
                        $('#promotion_message').removeClass('text-danger');
                    }else{
                        
                        $('#promocode_value').prop('disabled',false);
                        $('#promotion_message').text(data.message);
                        $('#promotion_message').addClass('text-danger');
                        $('#promotion_message').removeClass('text-success');
                    }
                },
                error: function(xhr, err) {
                    console.log( err)
                    if (typeof xhr.responseJSON.message != "undefined" && xhr.responseJSON.message.length > 0) {
                        if (typeof xhr.responseJSON.errors != "undefined") {
                            
                            commonFormErrorShow(xhr, err);
                        } else {
                            
                            displayErrorMessage(xhr.responseJSON.message);
                        }
                    } else {
                        
                        displayErrorMessage(xhr.responseJSON.errors);
                    }
                }
            });
        }
    })

    $('#cancel_btn').click(function(){
        
        $('#afterFreeTrial_amount').text($('#subtotal_amount').text());
        $('#promotion_amount').text('0.00');
        $('#promocode_value').val('');
        $('#promotion_token').val('');
        $('#promotion_message').text('');
        $('#promocode_value').prop('disabled',false);
        
        $('#cancel_btn').addClass('d-none');
        $('#apply_btn').removeClass('d-none');
    })

    // set validation
    var settings = $('#add_card_fr').validate().settings;
    $.extend(settings, {
        errorPlacement: function (error, element) {
            
            if(element.attr('name') == 'confirmationCheckbox') {
                
                $('#confirmationCheckbox').addClass('checkbox-error');
            }
            else if(element.attr('namecard_holder_name') == 'name') {
                error.appendTo(element.parent().parent());
            }
            else {
                element.after(error);
            }
        },
    });
});

