

$(document).ready(function(){

    $('#enter_otp').keyup(function(){
        
        if($(this).val().trim() ){
            
            $(':input[type="submit"]').prop('disabled', false);

        }else{
            $(':input[type="submit"]').prop('disabled', true);
        }
    });

    $('#otp_verify_fr').submit(function(event){
        event.preventDefault();
        show_loader();   
        // Submit the form:
        var formData = new FormData($("#otp_verify_fr")[0]);
        var action_url = $("#otp_verify_fr").attr('action');

        $.ajax({
            url: action_url,
            data: formData,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                
                if(data.status){
                    window.location.href = password_route + '?id=' + data.uuid;
                }else{
                    setTimeout(() => {
                        hide_loader();
                        displayErrorMessage(data.message);     
                    }, 500);
                }
            },
            error: function(xhr, err) {

                hide_loader();     
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
    })
})
