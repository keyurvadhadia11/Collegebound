'use strict';

var stripe = Stripe(admin_stripe_pkey);

function registerElementsBuyTournaments(elements, exampleName) {
    var formClass = '.' + exampleName;
    
    var example = document.querySelector(formClass);

    var form = example.querySelector('form');
    var resetButton = example.querySelector('a.reset');
    var error = form.querySelector('.error');
    var errorMessage = error.querySelector('.message');

    function enableInputs() {
        Array.prototype.forEach.call(
            form.querySelectorAll(
                "input[type='text'], input[type='email'], input[type='tel']"
            ),
            function(input) {
                input.removeAttribute('disabled');
            }
        );
    }

    function disableInputs() {
        Array.prototype.forEach.call(
            form.querySelectorAll(
                "input[type='text'], input[type='email'], input[type='tel']"
            ),
            function(input) {
                input.setAttribute('disabled', 'true');
            }
        );
    }

    function triggerBrowserValidation() {
        // The only way to trigger HTML5 form validation UI is to fake a user submit
        // event.
        var submit = document.createElement('input');
        submit.type = 'submit';
        submit.style.display = 'none';
        form.appendChild(submit);
        submit.click();
        submit.remove();
    }

    // Listen for errors from each Element, and show error messages in the UI.
    var savedErrors = {};
    elements.forEach(function(element, idx) {
        element.on('change', function(event) {
            if (event.error) {
                error.classList.add('visible');
                savedErrors[idx] = event.error.message;
                errorMessage.innerText = event.error.message;
            } else {
                savedErrors[idx] = null;

                // Loop over the saved errors and find the first one, if any.
                var nextError = Object.keys(savedErrors)
                    .sort()
                    .reduce(function(maybeFoundError, key) {
                        return maybeFoundError || savedErrors[key];
                    }, null);

                if (nextError) {
                    // Now that they've fixed the current error, show another one.
                    errorMessage.innerText = nextError;
                } else {
                    // The user fixed the last error; no more errors.
                    error.classList.remove('visible');
                    errorMessage.innerText = "";
                }
            }
        });
    });

    // Listen on the form's 'submit' handler...
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Trigger HTML5 validation UI on the form if any of the inputs fail
        // validation.
        var plainInputsValid = true;
        Array.prototype.forEach.call(form.querySelectorAll('input'), function(
            input
        ) {
            if (input.checkValidity && !input.checkValidity()) {
                plainInputsValid = false;
                return;
            }
        });
        if (!plainInputsValid) {
            triggerBrowserValidation();
            return;
        }

        // Show a loading screen...
        example.classList.add('submitting');

        // Disable all inputs.
        disableInputs();

        var price = jQuery('#total_price_amount').text();
    
        if (price <= 0) {
            Swal.fire({
                title: "Warning",
                html: "Please select price.",
                icon: "error",
                confirmButtonText: "Okay",
            });
            return false;
        }

        showLoader();
        /*jQuery("#card_save_loading").show();*/

        // Gather additional customer data we may have collected in our form.
        var name = form.querySelector('#' + exampleName + '-name');
        var address1 = form.querySelector('#' + exampleName + '-address');
        var city = form.querySelector('#' + exampleName + '-city');
        var state = form.querySelector('#' + exampleName + '-state');
        var zip = form.querySelector('#' + exampleName + '-zip');
        var additionalData = {
            name: name ? name.value : undefined,
            address_line1: address1 ? address1.value : undefined,
            address_city: city ? city.value : undefined,
            address_state: state ? state.value : undefined,
            address_zip: zip ? zip.value : undefined,
        };

        // Use Stripe.js to create a token. We only need to pass in one Element
        // from the Element group in order to create a token. We can also pass
        // in the additional customer data we collected in our form.
        stripe.createToken(elements[0], additionalData).then(function(result) {
            // Stop loading!
            example.classList.remove('submitting');

            if (result.token) {
                // If we received a token, show the token ID.
                example.querySelector('.token').innerText = result.token.id;
                example.classList.add('submitted');
                stripeTokenHandler(result.token);
            } else {
                // Otherwise, un-disable inputs.
                enableInputs();
                stopLoader();
                /*jQuery("#card_save_loading").hide();*/
            }
        });
    });

    resetButton.addEventListener('click', function(e) {
        e.preventDefault();
        // Resetting the form (instead of setting the value to `''` for each input)
        // helps us clear webkit autofill styles.
        form.reset();

        // Clear each Element.
        elements.forEach(function(element) {
            element.clear();
        });

        // Reset error state as well.
        error.classList.remove('visible');

        // Resetting the form does not un-disable inputs, so we need to do it separately:
        enableInputs();
        example.classList.remove('submitted');
    });
}

function stripeTokenHandler(token) {

    var cardholdername = jQuery("#cardholdername").val();
    var formClass = '.buytournaments';
    var example = document.querySelector(formClass);
    var form = example.querySelector('form');

    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('id', 'token-id');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);

    var price = jQuery('#total_price_amount').text();
    var event_id = jQuery('.trnid').val();

    if (price > 0) {

        var priceArray = [];
        var keyArray = [];
        jQuery(".coach_tournament_price").each(function() {
            if (jQuery(this).is(":checked")) {
                keyArray.push(jQuery(this).attr("id"));
                priceArray.push(jQuery(this).val());
            }else{
                priceArray.push(0);
            }
        });
        
        form.appendChild(hiddenInput);

        var params = {
            action: 'AddCard',
            stripeToken: token.id,
            cardholdername: cardholdername,
            savecard: false,
            amount: price,
            event_id: event_id,
            keyArray: keyArray,
            price_array: priceArray
        };

        jQuery.ajax({
            url: college_bound_ajax_object.ajax_url,
            type: "POST",
            dataType: "json",
            data: params,
            success: function(data) {
                
                stopLoader();

                //jQuery("#card_save_loading").hide();

                if (data.success) {

                    Swal.fire({
                        title: "Success",
                        html: data.message + "<br/>" + " Page automatically reload in 5 second...",
                        icon: "success",
                        confirmButtonText: "Okay",
                    });

                    setTimeout(function() {
                        location.reload();
                    },500);
                } else {

                    Swal.fire({
                        title: "Error",
                        html: data.message + "<br/>" + " Page automatically reload in 5 second...",
                        icon: "error",
                        confirmButtonText: "Okay",
                    });

                    // setTimeout(function() {
                    //     location.reload();
                    // }, 1000);
                }
            }
        });
    } else {
        Swal.fire({
            title: "",
            html: "Please select price",
            icon: "error",
            confirmButtonText: "Okay",
        });
        setTimeout(function() {
            location.reload();
        }, 2000);

    }
}

(function() {
    var elements = stripe.elements({
        fonts: [{
            cssSrc: 'https://fonts.googleapis.com/css?family=Quicksand',
        }, ],
        // Stripe's examples are localized to specific languages, but if
        // you wish to have Elements automatically detect your user's locale,
        // use `locale: 'auto'` instead.
        locale: window.__exampleLocale,
    });

    var elementStyles = {
        base: {
            color: '#fff',
            fontWeight: 600,
            fontFamily: 'Quicksand, Open Sans, Segoe UI, sans-serif',
            fontSize: '16px',
            fontSmoothing: 'antialiased',

            ':focus': {
                color: '#fff',
            },

            '::placeholder': {
                color: '#fff',
            },

            ':focus::placeholder': {
                color: '#fff',
            },
        },
        invalid: {
            color: '#fff',
            ':focus': {
                color: '#FA755A',
            },
            '::placeholder': {
                color: '#FFCCA5',
            },
        },
    };

    var elementClasses = {
        focus: 'focus',
        empty: 'empty',
        invalid: 'invalid',
    };

    var cardNumber = elements.create('cardNumber', {
        style: elementStyles,
        classes: elementClasses,
    });
    if (jQuery('#buytournaments-card-number').length > 0) {
        cardNumber.mount('#buytournaments-card-number');
    }

    var cardExpiry = elements.create('cardExpiry', {
        style: elementStyles,
        classes: elementClasses,
    });
    if (jQuery('#buytournaments-card-expiry').length > 0) {
        cardExpiry.mount('#buytournaments-card-expiry');
    }

    var cardCvc = elements.create('cardCvc', {
        style: elementStyles,
        classes: elementClasses,
    });
    if (jQuery('#buytournaments-card-cvc').length > 0) {
        cardCvc.mount('#buytournaments-card-cvc');
    }

    if (jQuery('#buytournaments-card-cvc').length > 0) {
        registerElementsBuyTournaments([cardNumber, cardExpiry, cardCvc], 'buytournaments');
    }
})();