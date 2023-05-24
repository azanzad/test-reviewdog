$(document).ready(function(){
    $("#formMakePayment").validate();
    $(".btnsubmit").on("click", function(e){
              e.preventDefault();
        if ($("#formMakePayment").valid()) {
            $.confirm({
                title: "Are you sure you want to make payment?",
                buttons: {
                    sayMyName: {
                        text: 'Yes',
                        btnClass: 'btn  btn-success',
                        action: function() {
                             $(this).prop( 'disabled', true );
                            return paymentFromSubmit();
                        }
                    },
                    No:  {
                        
                        text: 'No',
                        btnClass: 'btn btn-default',
                        
                    }

                }
            });
            return false;
        }
    });
});
function paymentFromSubmit(){
    var isAjaxCall=false;
    //$("form#formPlanAdd").submit(function(e) { 
      //  e.preventDefault();       
        if (!isAjaxCall) {           
            $('.btnsubmit').prop('disabled', true);
            var formData = new FormData($("#formMakePayment")[0]);
            isAjaxCall = true;
            $.ajax({
                type: 'POST',
                url: $("#formMakePayment").attr('action'),
                headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    show_loader();
                },
                success: function (data) {                    
                    hide_loader(); 
                    isAjaxCall = false;
                    $('.btnsubmit').prop('disabled', false);
                    if(data.status_code == 200){
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
    
}