var elements=stripe.elements(),style={base:{fontWeight:400,fontFamily:"Roboto, Open Sans, Segoe UI, sans-serif",fontSize:"13px",lineHeight:"1.4",color:"#555",backgroundColor:"#fff","::placeholder":{color:"#888"}},invalid:{color:"#eb1c26"}},cardElement=elements.create("cardNumber",{style:style});cardElement.mount("#card_number");var exp=elements.create("cardExpiry",{style:style});exp.mount("#card_expiry");var cvc=elements.create("cardCvc",{style:style});cvc.mount("#card_cvc");

// When its ready
cardElement.on('ready', function(event) {
  $('#addPaymentFrm button[type=submit]').fadeIn();
});

// Get payment form element
var forms = document.getElementById('addPaymentFrm');


// Error Function
function stripeError(message){
    $('#paymentResponse').html('<p>'+message+'</p>');
    $('#addPaymentFrm').find('button[type=submit]').prop('disabled', false).find('span').remove();
}

cardElement.addEventListener('change', function(event) {
    if (event.error) {
        stripeError(event.error.message);
    } else {
        $('#paymentResponse').html('<p></p>');
    }
});

// Create a token when the form is submitted.
if (forms) {
    forms.addEventListener('submit', function(e) {
        e.preventDefault();
        createToken();
    });
}

// Create single-use token to charge the user

function createToken() {

            stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
                billing_details: {
                    address:{
                        city:$("input[name=city]").val(),
                        country:$("select[name=country]").val(),
                        line1:$("input[name=line_1]").val(),
                        line2:$("input[name=line_2]").val(),
                        postal_code:$("input[name=postal_zipcode]").val(),
                        state:$("select[name=state_county_province_region]").val(),
                    }
                },
            })
            .then(function(result) {
                console.log(result);
                if (result.error) {
                    // Inform the user if there was an error.
                    stripeError(result.error.message);
                } else {
                    console.log(result);
                    // Send the token to your server.
                    stripeTokenHandler(result.paymentMethod.id);
                }
            });
}

function stripeTokenHandler(token) {
    // Insert the token ID into the form so it gets submitted to the server
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'paymentMethod');
    hiddenInput.setAttribute('value', token);
    forms.appendChild(hiddenInput);
    forms.submit();
}

function processing_show(){
    $('#processing-modal').modal('show');
}

function processing_hide(){
    $('#processing-modal').modal('hide');
}

$("form[name=paypal-thank-form]").submit(function(e){
    e.preventDefault();
});