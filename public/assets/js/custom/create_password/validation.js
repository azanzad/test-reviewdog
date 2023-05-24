$(document).ready(function(){
    
    $('body').on('submit', '#formCreatePassword', function (e) {
        e.preventDefault();
        if ($("#formCreatePassword").valid()) {
            $('.btnsubmit').prop('disabled', true);
            $('.loading-spinner').toggleClass('active');
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
                    $('.loading-spinner').toggleClass('active');
                    $('.btnsubmit').prop('disabled', false);
                    if (data.status_code == 200) {
                        displaySuccessMessage(data.message);
                        setTimeout(function () {
                            window.location.href = redirect_route;
                        }, 1500);
                    }else{
                        displayErrorMessage(data.message);
                    }
                },
                error: function (xhr, err) {
                    hide_loader();
                    $('.loading-spinner').toggleClass('active');
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
});
