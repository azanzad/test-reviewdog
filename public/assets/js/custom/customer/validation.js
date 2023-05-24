$(document).ready(function () {  
    /**manage email update checkbox */
    $('body').on('click', '.nosend', function (e) {
       var val = $(this).val();
       if(val == 4){
        $('.email_update').prop('checked', false);
       }
    });
    $('body').on('click', '.email_update', function (e) {       
        $('.nosend').prop('checked', false);       
    });
    
    let clickedButton = '';
    var settings = $('#formCustomerAdd').validate().settings;
    $.extend(settings, {
        errorPlacement: function (error, element) {
            if (element.data('select2')) {
                error.appendTo(element.parent());
            } else if(element.attr('type') == 'file') {
                error.appendTo(element.parent().parent().parent());
            }else {
                element.after(error);
            }
        }
    });
    /****form submit */  
   $('body').on('submit', '#formCustomerAdd', function (e) {
        e.preventDefault();
        if ($("#formCustomerAdd").valid()) {
            $('.btnsubmit').prop('disabled', true);
            $.ajax({
                url: $(this).attr('action'),
                data: new FormData(this),
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    show_loader();
                },
                success: function (data) {
                    hide_loader();  
                    $('.btnsubmit').prop('disabled', false);
                    if (data.status_code == 200) {
                        displaySuccessMessage(data.message);
                        if(clickedButton == 'Save'){
                            setTimeout(function () {
                                window.location.href = redirect_route;
                            }, 1500);
                        }else{
                            // setTimeout(function () {
                            //     window.location.href = customer_create_route;
                            // }, 1500);
                            $('#formCustomerAdd')[0].reset();
                        }
                    }else{
                        displayErrorMessage(data.message);
                    }
                },
                error: function (xhr, err) {
                    hide_loader();
                    isAjaxCall = false;
                    $('.btnsubmit').prop('disabled', false);                      
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
        
    });

    $('.btnsubmit').click(function(event){
        clickedButton = event.target.innerText;
        $('#formCustomerAdd').submit();
    });
});
