jQuery('.coach_card_detail_save').validate({
    rules: {
        card_name: {
            required: true,
        },
        card_number: {
            required: true,
        },
        card_exp_month: {
            required: true,
        },
        card_exp_year: {
            required: true,
        }
    }
});

/*
jQuery.validator.addMethod('CCExp', function(value, element, params) {
  var minMonth = new Date().getMonth() + 1;
  var minYear = new Date().getFullYear();
  var month = parseInt(jQuery(params.month).val(), 10);
  var year = parseInt(jQuery(params.year).val(), 10);

  return (!month || !year || year > minYear || (year === minYear && month >= minMonth));
}, 'Your Credit Card Expiration date is invalid.');

*/

jQuery(".coach_card_detail_save").on("submit", function(event) { //console.log('Form Submit');
    event.preventDefault();

    var valid = jQuery(this).valid();
    if (!valid) {
        return false;
    }

    //Show Loader
    showLoader();

    var form_this = jQuery(this); // Set selector form_this =  submit form 
    // var form_data = form_this.serialize(); 
    var form_data = new FormData(this);
    //form_this.find('.preloader-wrapper').show();
    var form_submit = form_this.find(':submit'); // Set selector form_submit =  submit form of the submit button 
    form_submit.attr("disabled", "disabled");
    form_submit.parent().css('opacity', 0.2);

    jQuery.ajax({
        url: college_bound_ajax_object.ajax_url,
        method: "POST",
        data: form_data,
        cache: false,
        contentType: false,
        processData: false,
        success: function(response) { //return true;
            jQuery('html,body').scrollTop(0);
            jQuery(".message-content").html("");
            form_submit.removeAttr("disabled");
            form_submit.parent().css('opacity', 1);
            var res_data = JSON.parse(response);
            if (res_data.success) {
                if (res_data.message) {
                    var message_content = "";
                    message_content = '<div class="alert alert-success">' +
                        '<a title="close" aria-label="close" data-dismiss="alert" class="close" href="#">×</a>' +
                        '' + res_data.message +
                        '</div>';
                    jQuery(".message-content").append(message_content);
                }

                if (res_data.redirect_url) {
                    window.location.replace(res_data.redirect_url);
                } else {
                    setTimeout(function() { location.reload(true); }, 4000);
                }

                if (res_data.confirm) {
                    var re_confirm = confirm(res_data.confirm.message);
                    if (re_confirm == true) {
                        window.location.replace(res_data.confirm.redirect_url);
                    }
                }
            } else {

                stopLoader();
                if (res_data.message) {
                    var message_content = "";
                    message_content = '<div class="alert alert-danger">' +
                        '<a title="close" aria-label="close" data-dismiss="alert" class="close" href="#">×</a>' +
                        '' + res_data.message +
                        '</div>';
                    jQuery(".message-content").append(message_content);
                }

                if (res_data.redirect_url) {
                    window.location.replace(res_data.redirect_url);
                } else {
                    if (jQuery('body').hasClass('login-page')) {
                        form_this.trigger("reset");
                    } else {
                        setTimeout(function() { location.reload(true); }, 4000);
                    }
                }
            }
        }
    });
});


jQuery.validator.addMethod('CCExp', function(value, element, params) {
    var minMonth = new Date().getMonth() + 1;
    var minYear = new Date().getFullYear();
    var month = parseInt(jQuery(params.month).val(), 10);
    var year = parseInt(jQuery(params.year).val(), 10);

    return (!month || !year || year > minYear || (year === minYear && month >= minMonth));
}, 'Your Credit Card Expiration date is invalid.');


/*
jQuery(document).ready(function() {
  jQuery( "#save_coach_card" ).bind("click", submitForm);
  jQuery( "#card_exp_month" ).bind("blur", blurCcMonth);
});

var submitForm = function() {
  if(validForm()) {
    jQuery( "form:first" ).submit();
  }
};

var validForm = function() {
  var jQuerycard_num = jQuery( "#card_number" );
  var card_num = jQuerycard_num.val();
  //-- Credit card can only be numeric
  if (!jQuery.isNumeric(card_num)) {
    alert("Please enter valid credit card.");
    jQuerycard_num.select();
    return false;
  }

  var jQueryexp_mon = jQuery( "#card_exp_month" );
  var jQueryexp_year = jQuery( "#card_exp_year" );
  var exp_mon = jQueryexp_mon.val();
  var exp_year = jQueryexp_year.val();
  //-- Month can only be numeric
  if (!jQuery.isNumeric(exp_mon)) {
    alert("Please enter valid expiration month.");
    jQueryexp_mon.select();
    return false;
  }
  //-- Year can only be numeric
  if (!jQuery.isNumeric(exp_year)) {
    alert("Please enter valid expiration year.");
    jQueryexp_year.select();
    return false;
  }
  //-- Year must be in YYYY format
  if(jQuery.trim(exp_year).length < 4) {
    alert("Please enter year in format YYYY.");
    jQueryexp_year.select();
    return false;
  }
  //-- Card expiration must be future month
  var currentYear = (new Date).getFullYear();
  var currentMonth = (new Date).getMonth() + 1;
  if (currentMonth.length < 2) {
    currentMonth = "0" + currentMonth;
  }
  var curYrMn = currentYear.toString() + currentMonth.toString();
  var expYrMn = exp_year + exp_mon;
  if (parseInt(expYrMn, 10) < parseInt(curYrMn, 10)) {
    alert("Please enter a future expiration date.");
    jQueryexp_mon.select();
    return false;
  }

  return true;
};

var blurCcMonth = function() {
  var jQueryexp_mon = jQuery( this );
  exp_mon = jQueryexp_mon.val();
  
  if (jQuery.trim(jQueryexp_mon.val()).length == 0) {
    return;
  }
  else if(jQuery.trim(jQueryexp_mon.val()).length < 2) {
    exp_mon = "0" + jQuery.trim(jQueryexp_mon.val());
    jQueryexp_mon.val(exp_mon);
  }
};
*/


jQuery(document).ready(function() {

    jQuery("#save_keys").click(function(e) {
        e.preventDefault();
        var userId = jQuery("#userId").val();
        var publishable_key = jQuery("#publishable_key").val();
        var secret_key = jQuery("#secret_key").val();
        var client_id = jQuery("#stripe_client_id").val();
        var tournament_commission = jQuery("#tournament_commission").val();

        jQuery.ajax({
            type: "post",
            dataType: "json",
            url: college_bound_ajax_object.ajax_url,
            data: {
                action: "save_stripe_keys",
                userId: userId,
                publishable_key: publishable_key,
                secret_key: secret_key,
                client_id: client_id,
                tournament_commission: tournament_commission
            },
            success: function(msg) {
                if (msg == 1) {
                    window.location.reload()
                }
            }
        })
    });
    
    jQuery("#disconnect_stripe").click(function(e) {
        e.preventDefault();
        var userId = jQuery("#userId").val();
    
        var cnf = confirm("Are you sure you want to disconnect the stripe account?");
        if (cnf == true) {
        
            jQuery.ajax({
                type: "post",
                url: college_bound_ajax_object.ajax_url,
                data: {
                    action: "delete_stripe_user_id",
                },
                success: function(msg) {
                    if (msg == 1) {
                        window.location.reload()
                    }
                }
            });
        }
    });
});


function deleteSaveCard(card_id, customer_id) {

    if (!confirm("Are you sure you want to delete it?")) {
        return false;
    }

    jQuery.ajax({
        type: "post",
        dataType: "json",
        url: college_bound_ajax_object.ajax_url,
        data: {
            action: "delete_save_card",
            card_id: card_id,
            customer_id: customer_id
        },
        success: function(msg) {
            if (msg == 1) {

                Swal.fire({
                    title: "",
                    html: "Card deleted successfully." + "<br/>" + " Page automatically redirect to Dashboard...",
                    icon: "success",
                    confirmButtonText: "Okay",
                });

                setTimeout(function() {
                    location.reload();
                }, 2000);

            }
        }
    });

}



jQuery('.coach_tournament_price').click(function() {
    var total = 0;
    var priceArray = [];
    jQuery(".coach_tournament_price").each(function() {
        if (jQuery(this).is(":checked")) {
            total += Number(jQuery(this).val());
            priceArray.push(jQuery(this).val());
        }
    });

    jQuery('#total_price_amount').html(total);

});
jQuery('.saved_card_buy_tournaments').click(function() {

    var card_id = jQuery(this).attr('card_id');
    var price = jQuery('#total_price_amount').text();
    var event_id = jQuery('#trnid').val();

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

        showLoader();

        jQuery.ajax({
            type: "post",
            dataType: "json",
            url: college_bound_ajax_object.ajax_url,
            data: {
                action: "paymentby_card",
                card_id: card_id,
                price: price,
                event_id: event_id,
                keyArray: keyArray,
                price_array: priceArray
            },
            success: function(data) {

                stopLoader();
                
                if (data.success) {

                    Swal.fire({
                        title: "Success",
                        html: data.message + "<br/>" + " Page automatically reload in 5 second...",
                        icon: "success",
                        confirmButtonText: "Okay",
                    });

                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {

                    Swal.fire({
                        title: "Error",
                        html: data.message + "<br/>" + " Page automatically reload in 5 second...",
                        icon: "error",
                        confirmButtonText: "Okay",
                    });

                    /*setTimeout(function() {
                        location.reload();
                    }, 1000);*/
                }
            }
        });
    } else {
        Swal.fire({
            title: "Warning",
            html: "Please select price.",
            icon: "error",
            confirmButtonText: "Okay",
        });

    }
});