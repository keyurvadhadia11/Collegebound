'use strict';

var stripe = Stripe(college_bound_ajax_object.admin_stripe_skey);

function registerElements(elements, exampleName) {
    var formClass = '.' + exampleName;
    var example = document.querySelector(formClass);

    var form = example.querySelector('form');
    var resetButton = example.querySelector('a.reset');
    var error = form.querySelector('.error');
    var errorMessage = error.querySelector('.message');

    function enableInputs() {
        Array.prototype.forEach.call(
            form.querySelectorAll(
                "input[type='text']"
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
    
        var cardholdername = jQuery("#cardholdername").val();
    
        if(cardholdername == ''){
            jQuery(".card_save_form .error").show();
            jQuery(".card_save_form .error .message").text("Card holder name is required.");
        } else {
            jQuery(".card_save_form .error").hide();
            jQuery(".card_save_form .error .message").text("");
        }
        
        

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

        jQuery("#card_save_loading").show();
        
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
                enableInputs();
                jQuery("#card_save_loading").hide();
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
    if(cardholdername == ''){
        jQuery(".card_save_form .error").show();
        jQuery(".card_save_form .error .message").text("Card holder name is required.");
        return false;
    } else {
        jQuery(".card_save_form .error").hide();
        jQuery(".card_save_form .error .message").text("");
    }
    var formClass = '.example3';
    var example = document.querySelector(formClass);
    var form = example.querySelector('form');

    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('id', 'token-id');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    form.appendChild(hiddenInput);
    var params = {
        action: 'AddCard',
        stripeToken: token.id,
        cardholdername: cardholdername,
    };
    if (!confirm("Are you sure you want to save this card?")) {
        jQuery("#card_save_loading").hide();
        return false;
    }
    jQuery.ajax({
        url: college_bound_ajax_object.ajax_url,
        type: "POST",
        data: params,
        success: function(data) {
            console.log(data);
            jQuery("#card_save_loading").hide();

            Swal.fire({
                title: "",
                html: "Card save successfully." + "<br/>" + " Page automatically redirect to Dashboard...",
                icon: "success",
                confirmButtonText: "Okay",
            });

            setTimeout(function() {
                location.reload();
            }, 2000);
        }
    });
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
    if (jQuery('#example3-card-number').length > 0) {
        cardNumber.mount('#example3-card-number');
    }

    var cardExpiry = elements.create('cardExpiry', {
        style: elementStyles,
        classes: elementClasses,
    });
    if (jQuery('#example3-card-expiry').length > 0) {
        cardExpiry.mount('#example3-card-expiry');
    }

    var cardCvc = elements.create('cardCvc', {
        style: elementStyles,
        classes: elementClasses,
    });
    if (jQuery('#example3-card-cvc').length > 0) {
        cardCvc.mount('#example3-card-cvc');
    }

    if (jQuery('#example3-card-cvc').length > 0) {
        registerElements([cardNumber, cardExpiry, cardCvc], 'example3');
    }
})();