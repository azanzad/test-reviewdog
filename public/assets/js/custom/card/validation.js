// Set your publishable key: remember to change this to your live publishable key in production
var stripe = Stripe(stripe_key);
const appearance = {
    theme: 'stripe',
};

 // Pass the appearance object to the Elements instance
const elements = stripe.elements({stripe_key, appearance});

// Custom styling can be passed to options when creating an Element.
var style = {
    base: {
        // Add your base input styles here. For example:
        fontSize: '16px',
        color: '#a1b0cb',
        lineHeight: '1.429'
    },
};

// Create an instance of the card Element.
var card = elements.create('card', {hidePostalCode: true,style: style});

// Add an instance of the card Element into the `card-element` <div>.
card.mount('#card-element');

// Create a token or display an error when the form is submitted.
var form = document.getElementById('formCompanyCardAdd');
form.addEventListener('submit', function(event) {
    event.preventDefault();
    var type = $('#form_payment_type').val();     
    if(type == ''){
        $('.btnsubmit').prop('disabled', true);
        $('.loading-spinner').toggleClass('active');
        event.preventDefault();
        var card_holdername = document.getElementById('card-holder-name').value;
        stripe.createToken(card,{name: card_holdername}).then(function(result) {
            if (result.error) {
                $('.btnsubmit').prop('disabled', false);
                $('.loading-spinner').toggleClass('active');
                // Inform the customer that there was an error.
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
            // Send the token to your server.
            return stripeTokenHandler(result.token);
            }
        });
    }else{
        if ($("#formCompanyCardAdd").valid()) {
            $.confirm({
                title: "Are you sure you want to make payment?",
                buttons: {
                    sayMyName: {
                        text: 'Yes',
                        btnClass: 'btn  btn-success',
                        action: function() {
                            $('.btnsubmit').prop('disabled', true);
                            $('.loading-spinner').toggleClass('active');
                            event.preventDefault();
                            var card_holdername = document.getElementById('card-holder-name').value;
                            stripe.createToken(card,{name: card_holdername}).then(function(result) {
                                if (result.error) {
                                    $('.btnsubmit').prop('disabled', false);
                                    $('.loading-spinner').toggleClass('active');
                                    // Inform the customer that there was an error.
                                    var errorElement = document.getElementById('card-errors');
                                    errorElement.textContent = result.error.message;
                                } else {
                                // Send the token to your server.
                               return stripeTokenHandler(result.token);
                                }
                            });
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
    }        
    
});

function stripeTokenHandler(token) {
    
    // Insert the token ID into the form so it gets submitted to the server
    var form = document.getElementById('formCompanyCardAdd');
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    form.appendChild(hiddenInput);
 
    // Submit the form:
    var formData = new FormData($("#formCompanyCardAdd")[0]);
    var action_url = $("#formCompanyCardAdd").attr('action');
   
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
        beforeSend: function () {
            show_loader();
        },
        success: function (data) {
            $('.loading-spinner').toggleClass('active');
            $('.btnsubmit').prop('disabled', false);            
             hide_loader();  
            if (data.status_code == 200) {
                displaySuccessMessage(data.message);
                setTimeout(function () {
                    window.location.href = redirect_route;
                }, 1500);
            }
            if (data.status_code == 400) {
                displayErrorMessage(data.message);
            }
        },
        error: function (xhr, err) {
            $('.loading-spinner').toggleClass('active');
             $('.btnsubmit').prop('disabled', false);
             hide_loader();  
            isUserUpdate = false;
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