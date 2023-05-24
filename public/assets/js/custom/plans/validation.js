$(document).ready(function(){
    $("#formPlanAdd").validate();
    $(".btnsubmit").on("click", function(e){
              e.preventDefault();
        if ($("#formPlanAdd").valid()) {
            $.confirm({
                title: "Are you sure?",
                content: `<div class="text-danger">Once you have submitted your plan, you can't edit it later.</div>`,
                buttons: {
                    sayMyName: {
                        text: 'Yes',
                        btnClass: 'btn  btn-success',
                        action: function() {
                             $(this).prop( 'disabled', true );
                            return planFromSubmit();
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

        // set validation
        var settings = $('#formPlanAdd').validate().settings;
        $.extend(settings, {
            errorPlacement: function (error, element) {
                
                if(element.attr('name') == 'annual_sales_from' || element.attr('name') == 'annual_sales_to') {
                    
                    error.appendTo(element.parent().parent());
                }
                else {
                    element.after(error);
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
    
        });
});
function planFromSubmit(){
    var isAjaxCall=false;
    //$("form#formPlanAdd").submit(function(e) { 
      //  e.preventDefault();       
        if (!isAjaxCall) {           
            $('.btnsubmit').prop('disabled', true);
            var formData = new FormData($("#formPlanAdd")[0]);
            isAjaxCall = true;
             var redirect_route = $("#redirect_route").val();
            $.ajax({
                type: 'POST',
                url: $("#formPlanAdd").attr('action'),
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