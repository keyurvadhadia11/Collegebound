if ( jQuery().validate && jQuery("#signupForm").is(":visible") ) {
    jQuery("#signupForm").validate({
      rules: {
        username: {
          required: true,
          noSpace: true,
        },
        password: {
          required: true,
          noSpace: true,
        },
      },
      message: {
        username: {
          required: "The Username field is required.",
          noSpace: "The Username field is required.",
        },
        password: {
          required: "The Password field is required.",
          noSpace: "The Password field is required.",
        },
      },
    });
  
    jQuery("input").keypress(function (e) {
      if (e.which == 13) {
        signupFunction();
        return false; //<---- Add this line
      }
    });
 }
 
 function signupFunction() {
      showLoadder();
      if (jQuery("#signupForm").valid()) {
          var formData = {};
          //formData.action = "college_bound_login";
          jQuery("#signupForm")
            .serializeArray()
            .map(function (val, index) {
              formData[val.name] = val.value;
            });
    
          jQuery.ajax({
            url: college_bound_ajax_object.ajax_url,
            type: "post",
            dataType: "json",
            data: formData,
            success: function (response) {
                stopLoader();
                var res_data = response; console.log(res_data);
                if( res_data.message ) {
                    Swal.fire({
                        title: "",
                        html:
                          res_data.message +
                          "<br/>" + " Page automatically redirect to Dashboard...",
                        icon: "success",
                        confirmButtonText: "Okay",
                    });
                }
              
                if( res_data.redirect_url ){
                    jQuery("#loginForm").hide();
                    setTimeout(function () {
                        window.location.href = res_data.redirect_url;
                    }, 1500);
                } else {
                    setTimeout(function(){ location.reload(true); }, 4000);
                }
            },
            error: function (error) {
              stopLoader();
              var errorData = error.responseJSON;
              Swal.fire({
                title: "",
                text: errorData.message,
                icon: "error",
                confirmButtonText: "Okay",
              });
            },
          }); 
      } else {
        stopLoader();
      }
      return false;
    }