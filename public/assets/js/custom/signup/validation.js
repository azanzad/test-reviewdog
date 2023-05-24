
// Set your publishable key: remember to change this to your live publishable key in production
var stripe = Stripe(stripe_key);
const appearance = {
    theme: 'stripe',
};

// Pass the appearance object to the Elements instance
const elements = stripe.elements({ stripe_key, appearance });

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
var card = elements.create('card', { hidePostalCode: true, style: style });

// Add an instance of the card Element into the `card-element` <div>.
card.mount('#card-element');


var form = document.getElementById('add_card_fr');
form.addEventListener('submit', function(event) {
    event.preventDefault();
    show_loader(); 
    var card_holdername = document.getElementById('card-holder-name').value;
    
    stripe.createToken(card, { name: card_holdername }).then(function(result) {
            
        if (result.error) {
            
            setTimeout(() => {
                hide_loader();     
            }, 500);
            $('#errorMessageModal #error_message').text(result.error.message);
            $('#errorMessageModal').modal('show'); 
        } else {
            // set validation
            var validate = $('#add_card_fr').validate()
            var length = Object.keys(validate.errorMap).length;
            
            if(length > 0){
                hide_loader(); 
            }else{
                return stripeTokenHandler(result.token);
            }
            
        }
    });
});

function stripeTokenHandler(token) {
    
    // Insert the token ID into the form so it gets submitted to the server
    var form = document.getElementById('add_card_fr');
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    form.appendChild(hiddenInput);

    //add payment type
    var paymentInput = document.createElement('input');
    paymentInput.setAttribute('type', 'hidden');
    paymentInput.setAttribute('name', 'type');
    paymentInput.setAttribute('value', 'payment');
    form.appendChild(paymentInput);

    // Submit the form:
    var formData = new FormData($("#add_card_fr")[0]);
    var action_url = $("#add_card_fr").attr('action');
    
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
            window.location.href = redirect_route;
            
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
}
