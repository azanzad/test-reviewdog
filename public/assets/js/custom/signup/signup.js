

$(document).ready(function() {

    $("#contact_number").intlTelInput({
        initialCountry: "us",
    });

    // set validation
    var settings = $('#signup_fr').validate().settings;
    $.extend(settings, {
        errorPlacement: function (error, element) {
            
            if(element.attr('name') == 'confirmationCheckbox') {
                
                $('#confirmationCheckbox').addClass('checkbox-error');
            }
            else if(element.attr('type') == 'tel' || element.attr('type') == 'password') {
                
                error.appendTo(element.parent().parent());
            }
            else if(element.attr('name') == 'name' || element.attr('name') == 'email') {
                error.appendTo(element.parent().parent());
            }
            else if(element.attr('name') == 'country_id') {
                
                error.appendTo(element.parent().parent());
            }
            else {
                element.after(error);
            }
        },
        submitHandler: function (form) {
            show_loader();   
            form.submit();
        }

    });

    $('#iti-0__country-listbox li').on('click', function(){
        var country_code = $(this).data('country-code');
        $('.country_code').val(country_code);
    });
    
});

