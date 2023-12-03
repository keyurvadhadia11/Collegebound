/* Loader */

jQuery(window).on("load", function () {

  jQuery(".loader-wrap").fadeOut(1000);



  jQuery(".mCustomScrollbar").mCustomScrollbar({

    scrollButtons: { enable: true, scrollType: "stepped" },

    keyboard: { scrollType: "stepped" },

    // mouseWheel:{scrollAmount:188,normalizeDelta:true},

    theme: "rounded-dark",

    autoExpandScrollbar: true,

    // snapAmount:188,

    // snapOffset:65

  });

});



function showLoader() {

  jQuery(".loader-wrap").show();

  return false;

}



function stopLoader() {

  jQuery(".loader-wrap").hide();

  return false;

}

// Send OTP MAIL
function sendOtpMail( resend = false)
{
  var first_name = jQuery("input[name=first_name]").val();
  var last_name = jQuery("input[name=last_name]").val();
  var useremail = jQuery("input[name=useremail]").val();

  showLoader();

  jQuery.ajax({
      type: "post",
      dataType: "json",
      url: college_bound_ajax_object.ajax_url,
      data: {
          action: "clg-mail-verify-otp-send",
          first_name: first_name,
          last_name: last_name,
          useremail: useremail,
          resend: resend
      },
      success: function(response)
      {
        stopLoader();

        if( response.success )
        {
          var message_content = '<div class="alert alert-success" role="alert">'+response.message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
          jQuery('#otpVerificationModal').find('#otp-message').html(message_content);

           Swal.fire({
              title: "",
              html:
                response.message,
              icon: "success",
              confirmButtonText: "Okay",
          });

          if( !jQuery('#otpVerificationModal').is(":visible") )
          {
            jQuery('#otpVerificationModal').modal('show');
          }
        } else {
           Swal.fire({
              title: "",
              html: response.message,
              icon: "warning",
              confirmButtonText: "Okay",
          });
        }
      }
  });
}

// Validate OTP MAIL
function validateOtpMail()
{
  var first_name  = jQuery("input[name=first_name]").val();
  var last_name   = jQuery("input[name=last_name]").val();
  var useremail   = jQuery("input[name=useremail]").val();
  var otp_number  = jQuery('#verificationCode').val();

  jQuery.ajax({
      type: "post",
      dataType: "json",
      url: college_bound_ajax_object.ajax_url,
      data: {
          action: "clg-mail-otp-validate",
          first_name: first_name,
          last_name: last_name,
          useremail: useremail,
          otp_number: otp_number
      },
      success: function(response) {
        if( response.success )
        {
          // Email Verifield
          emailVerifield = true;

          jQuery('#otpVerificationModal').modal('hide'); 

          Swal.fire({
              title: "",
              html: response.message,
              icon: "success",
              confirmButtonText: "Okay",
          });

          // Trigger To Next Steps
          jQuery(".nav-signup .active") 
            .parent() 
            .next("li") 
            .find("a") 
            .trigger("click"); 
          jQuery(".nav-signup .active").parent().prev("li").addClass("done");

        } else {
          Swal.fire({
              title: "",
              html: response.message,
              icon: "warning",
              confirmButtonText: "Okay",
          });
        }
      }
  });
}



jQuery(document).ready(function ($) {

    

  $('a.dropdown-item').click(function () {

    window.location = $(this).attr('href');

  });

  

  $('.modal').on('hidden.bs.modal', function(){

      $(this).find('form')[0].reset();

  });

  

  /* Form Input */

  jQuery(".form-control").each(function() {

     if (this.value) {

          jQuery(this).parent().find(".form-label").addClass("active");

     }

     jQuery(this).removeAttr('placeholder');

  });

  

  jQuery(".form-control").on("focusin", function () {

    jQuery(this).parent().find(".form-label").addClass("active");

  });



  jQuery(".form-control").on("focusout", function () {

    var inputThis = this;

    setTimeout(function(){  //console.log(inputThis.value);

        if (!inputThis.value) {

          jQuery(inputThis).parent().find(".form-label").removeClass("active");

        }

    }, 300);

  });



  jQuery('.signup-form').validate({

    rules: {

        password: {

            required: true,

            minlength: 5

        },

        confirmpassword: {

            required: true,

            minlength: 5,

            equalTo: "#pass1"

        }

    }

  });



  jQuery('.ajax-form').validate({

    rules: {

        password: { 

            minlength: 5

        },

        confirmpassword: {

            minlength: 5,

            equalTo: "#pass1"

        }

    }

  });



  /* Form Processbar */

  jQuery(".btn-next").click(function (e) {



    var valid = jQuery("form").valid();

    // Mail Verified Code Start
    if( valid && !emailVerifield )
    {
      e.preventDefault();
      
      sendOtpMail();
      return false;
    }
    // End Mail Verified Code


    if( valid ){

      jQuery(".nav-signup .active")

        .parent()

        .next("li")

        .find("a")

        .trigger("click");

      jQuery(".nav-signup .active").parent().prev("li").addClass("done");

    } else {

      e.preventDefault();

    }

  });



  /* Rating Ball */

  jQuery('.rating-ball-wrap input[type="radio"]').click(function () {

    var theNumber = jQuery(this).attr("id").slice(-1);

    jQuery(this)

      .siblings("label")

      .each(function () {

        var sibNumber = jQuery(this).attr("for").slice(-1);

        if (sibNumber <= theNumber) {

          jQuery(this).addClass("on");

        } else {

          jQuery(this).removeClass("on");

        }

      });

  });



  /* Select 2 Js */

  if (jQuery.fn.select2) {

    jQuery(".select2").select2({

      width: "100%",

      minimumResultsForSearch: -1,

      tags: true,

      closeOnSelect: false,

      placeholder: "Select an option",

    });



    jQuery(".select-division").select2({

      width: "100%",

      minimumResultsForSearch: -1,

      tags: true,

      closeOnSelect: false,

      placeholder: "Select your schools division",

    });



    jQuery(".select2-container").on("click", function () {

      if (jQuery(".select2-container").hasClass("select2-container--open")) {

        jQuery(".card-search-criteria").find("label").addClass("text-primary");

      } else {

        jQuery(".card-search-criteria")

          .find("label")

          .removeClass("text-primary");

      }

    });



    jQuery(".select2-selection__arrow, .dropdown-wrapper").addClass(

      "icon icon-down-arrow"

    );

    jQuery(".select2-selection__arrow b").remove();

  }



  /* Search Box */

  jQuery(".nav-search").click(function () {

    jQuery("#searchbox").show();

  });



  jQuery("#searchbox-close").click(function () {

    jQuery("#searchbox").hide();

  });



  /* Dropdown */

  jQuery(".dropdown-light .dropdown-item").on("click", function (e) {

    e.preventDefault();

    var selText = jQuery(this).text();

    jQuery(".dropdown-light .dropdown-toggle span").text(selText);

  });



  if($.fn.DataTable){

    $('.table').DataTable({

      language: { 

        search: "<i class='icon icon-search'></i>",

        searchPlaceholder: "Search",

        sLengthMenu: "Show _MENU_"

      },

      responsive: true,

      columnDefs: [

      {

        orderable: false,

        targets: -1

      }],

      dom: '<"top"f>rt<"bottom"lp><"clear">',

    });

  }

});



/* Menu Active */

jQuery(function () {

  var url = window.location.pathname,

    urlRegExp = new RegExp(url.replace(/\/$/, "") + "$");

  jQuery(".sidebar-links .item-link").each(function () {

    if (urlRegExp.test(this.href.replace(/\/$/, ""))) {

      jQuery(this).addClass("active");

    }

  });

});



jQuery(window).resize(function () {

  /* Toggle Sidebar */

  if (jQuery(window).width() <= 991) {

    jQuery("#menu-toggle").click(function (e) {

      e.preventDefault();

      jQuery(".main-wrapper").addClass("toggled");

      jQuery("body").addClass("overflow-hidden");

    });

    jQuery("#menu-close-toggle").click(function (e) {

      e.preventDefault();

      jQuery(".main-wrapper").removeClass("toggled");

      jQuery("body").removeClass("overflow-hidden");

    });

  }



  /* Background Grey Before Color */

  var bodyHeight = jQuery("body").innerHeight();

  var body = jQuery("body");

  var bodyAddHeight = 355;

  //body.append('<style>.content-wrapper:after{min-height: ' + bodyHeight + 'px;}</style>');



  /* COllage Card Height */
  jQuery(window).on("load resize",function(e){
    if (jQuery(window).width() >= 1200) {

      var cardCollage = jQuery(".card-college").height();
  
      var minusCard = jQuery(".nav-graduates-pills").height();
  
      jQuery(".nav-graduates-tabs .tab-content").css(
  
        "height",
  
        cardCollage - minusCard
  
      );
  
      var cardCustomize = jQuery(".card-customize-wrap").outerHeight();
  
      jQuery(".nav-graduates-tabs .tab-content").css(
  
        "height", cardCustomize
  
      );
  
    }
  }); 



  // Select2

  if (jQuery().select2) {

    jQuery(".select2").select2({

      placeholder: "Select an option",

      width: "100%",

      allowClear: true

    });

  }



  // jQuery Form Validation

  if (jQuery().validate) {

    jQuery.validator.setDefaults({

      errorClass: "error",

      errorElement: "div",

      errorPlacement: function (error, element) {

        // Add the `invalid-feedback` class to the error element

        error.addClass("d-inline-block invalid-feedback");

        element.parent().addClass('invalid-input');



        if (element.prop("type") === "checkbox") {

          error.insertAfter(element.next("label"));

        } else if (

          element.hasClass("select2") &&

          element.next(".select2-container").length

        ) {

          error.insertAfter(element.next(".select2-container"));

        } else {

          error.insertAfter(element);

        }

      },

      highlight: function (element, errorClass, validClass) {

        jQuery(element).addClass("is-invalid").removeClass("is-valid");

        jQuery(element).parent().removeClass("invalid-input");

      },

      unhighlight: function (element, errorClass, validClass) {

        jQuery(element).addClass("is-valid").removeClass("is-invalid");

        jQuery(element).parent().removeClass("invalid-input");

      },

    });



    jQuery.validator.addMethod(

      "regex",

      function (value, element, regexp) {

        if (regexp.constructor != RegExp) regexp = new RegExp(regexp);

        else if (regexp.global) regexp.lastIndex = 0;

        return this.optional(element) || regexp.test(value);

      },

      "Please check your input."

    );



    jQuery.validator.addMethod(

      "noSpace",

      function (value, element) {

        var txt = jQuery.trim(value);

        return txt != "";

      },

      "No space please and don't leave it empty"

    );



    jQuery.validator.addMethod(

      "alphanumeric",

      function (value, element) {

        if (/^[0-9]+$/.test(value)) {

          return false;

        }



        return this.optional(element) || /^[\w.]+$/i.test(value);

      },

      "Letters, numbers, and underscores only please"

    );

  }



  //Jquery Datepicker

   if( jQuery().datepicker ){

    jQuery( function() {

      jQuery( ".datepicker" ).datepicker();

    } );



    jQuery(function ($) {

        jQuery("#txtFrom").datepicker({

            //numberOfMonths: 2,

            onSelect: function (selected) {

                var dt = new Date(selected);

                dt.setDate(dt.getDate() + 1);

                jQuery("#txtTo").datepicker("option", "minDate", dt);

            }

        });

        jQuery("#txtTo").datepicker({

            //numberOfMonths: 2,

            onSelect: function (selected) {

                var dt = new Date(selected);

                dt.setDate(dt.getDate() - 1);

                jQuery("#txtFrom").datepicker("option", "maxDate", dt);

            }

        });

    });

  }

  if( jQuery().datetimepicker ){
    jQuery( function() {
     jQuery('.scheduleGameTime').datetimepicker({
        format:'d/m/Y h:i A',
        formatDate:'d/m/Y h:i A',
        formatTime:"h:i A",
      });
    });
  }

});



jQuery(window).trigger("resize");





jQuery(document).ready(function($) { 



    jQuery(".signup-form").on("submit",function( event ) {  //console.log('Form Submit');

        event.preventDefault();

        

        var valid = jQuery("form").valid();

        if(!valid){

          stopLoader();

          return false;

        } 



      var form_this = jQuery(this); // Set selector form_this =  submit form 

       // var form_data = form_this.serialize(); 

        var form_data = new FormData(this);  

        //form_this.find('.preloader-wrapper').show();

        var form_submit =  form_this.find(':submit'); // Set selector form_submit =  submit form of the submit button 

        form_submit.attr("disabled","disabled"); 

        form_submit.parent().css('opacity',0.2);

        $.ajax({

            url: college_bound_ajax_object.ajax_url,

            method: "POST",

            data: form_data,

            cache:false,

            contentType: false,

            processData: false,

            success: function(response){  //return true;

                

                $('html,body').scrollTop(0);

                $('.preloader-wrapper').hide();

                $(".message-content").html(""); 

                form_submit.removeAttr("disabled"); 

                form_submit.parent().css('opacity',1); 

                var res_data = JSON.parse(response); 

                if(res_data.success){



                    jQuery(".nav-signup .active")

                      .parent()

                      .next("li")

                      .find("a")

                      .trigger("click");

                    jQuery(".nav-signup .active").parent().prev("li").addClass("done");

                    

                    console.log(jQuery("input[name=userrole]").val());

                    

                    if(jQuery("input[name=userrole]").val() == 'coach'){

                        console.log("in if");

                        jQuery("#accountinfo").removeClass("active show");

                        jQuery("#allset").addClass("active show");

                    }

                    

                    Swal.fire({

                        title: "",

                        html: res_data.message +

                          "<br/>" + " Page automatically redirect to Dashboard...",

                        icon: "success",

                        confirmButtonText: "Okay",

                    });

                    

                }else

                {  

                    if( res_data.message ) {

                      var message_content = "";

                      message_content = '<div class="alert alert-danger">'

                                          +'<a title="close" aria-label="close" data-dismiss="alert" class="close" href="#">×</a>'

                                          +''+res_data.message

                                       +'</div>';

                      $(".message-content").append(message_content); 

                    } 



                    Swal.fire({

                        title: "",

                        html: res_data.message,

                        icon: "warning",

                        confirmButtonText: "Okay",

                    });



                    if( res_data.redirect_url ){

                      window.location.replace(res_data.redirect_url);

                    } else {

                      if( jQuery('body').hasClass('login-page') ){

                        form_this.trigger("reset");

                      } else {

                        setTimeout(function(){ location.reload(true); }, 4000);

                      }

                    }



                }

            }

        });

    });





  jQuery("form").validate({ 

        ignore:":not(:visible)",

        errorElement : 'div', 

  });

  

  jQuery('.validate_form').validate({ 

        ignore:":not(:visible)",

        errorElement : 'div',

        rules: {

            csv_file: { 

                extension: "csv"

            }

        }

  });



  jQuery(".ajax-form").on("submit",function( event ) {  //console.log('Form Submit');

        event.preventDefault();

        

        var valid = $(this).valid();

        if(!valid){

          return false;

        }

        //Show Loader

        showLoader();



        var form_this = jQuery(this); // Set selector form_this =  submit form 

       // var form_data = form_this.serialize(); 

        var form_data = new FormData(this);  

        //form_this.find('.preloader-wrapper').show();

        var form_submit =  form_this.find(':submit'); // Set selector form_submit =  submit form of the submit button 

        form_submit.attr("disabled","disabled"); 

        form_submit.parent().css('opacity',0.2);

        $.ajax({

            url: college_bound_ajax_object.ajax_url,

            method: "POST",

            data: form_data,

            cache:false,

            contentType: false,

            processData: false,

            success: function(response){  //return true;

                

                $('html,body').scrollTop(0); 

                $(".message-content").html(""); 

                form_submit.removeAttr("disabled"); 

                form_submit.parent().css('opacity',1); 

                var res_data = JSON.parse(response); 

                if(res_data.success){



                    if( res_data.message ) {

                      var message_content = "";

                      message_content = '<div class="alert alert-success">'

                                          +'<a title="close" aria-label="close" data-dismiss="alert" class="close" href="#">×</a>'

                                          +''+res_data.message

                                       +'</div>';

                      $(".message-content").append(message_content); 

                    }



                  if( res_data.redirect_url ){

                    window.location.replace(res_data.redirect_url);

                  } else {

                    setTimeout(function(){ location.reload(true); }, 4000);

                  }



                   if( res_data.confirm ){

                      var re_confirm = confirm(res_data.confirm.message);

                      if (re_confirm == true) {

                          window.location.replace(res_data.confirm.redirect_url);

                      }  

                    }

                }else

                {  

                    // Stop Loader

                    stopLoader();

                    

                    if( res_data.message ) {

                      var message_content = "";

                      message_content = '<div class="alert alert-danger">'

                                          +'<a title="close" aria-label="close" data-dismiss="alert" class="close" href="#">×</a>'

                                          +''+res_data.message

                                       +'</div>';

                      $(".message-content").append(message_content); 

                    } 



                    if( res_data.redirect_url ){

                      window.location.replace(res_data.redirect_url);

                    } else {

                      if( jQuery('body').hasClass('login-page') ){

                        form_this.trigger("reset");

                      } else {

                        setTimeout(function(){ location.reload(true); }, 4000);

                      }

                    }



                }

            }

        })

  });



    

    /**-------------- Coach Profile Gallery Start -------------------*/



     $('#galleryFile').on('change', function () {



          const file = this.files[0];

          const  fileType = file['type'];

          const validImageTypes = ['image/jpeg', 'image/png', 'video/mp4'];

          if (!validImageTypes.includes(fileType)) {

              alert('Only Image & Mp4 file allow for gallery!');

              return false;

          }

          

          var FileSize = this.files[0].size / 1024 / 1024; // in MiB

          if (FileSize > 40) {

            alert('File size exceeds 40 MiB');

            return false;

          }



          showLoader(); 



          var file_data = $('#galleryFile').prop('files')[0];

          var form_data = new FormData();

          form_data.append('gallery-pic', file_data);

          form_data.append('action', 'cb-upload-gallery-picture');

          $.ajax({

              url: college_bound_ajax_object.ajax_url, // point to server-side controller method

              dataType: 'text', // what to expect back from the server

              cache: false,

              contentType: false,

              processData: false,

              data: form_data,

              type: 'post',

              success: function (response) {

                  location.reload(); 

                  $('#msg').html(response); // display success response from the server

              },

              error: function (response) {

                  stopLoader();

                  $('#msg').html(response); // display error response from the server

              }

          });

      });



    $('.editGalleryImage').click(function(){

      var imagePath = $(this).attr('src');

      var imageId   = $(this).attr('imageId');

      

      $('#editGalleryModal').modal("show");

      $('#editGalleryImagePreview').attr('src', imagePath);

      $('#editGalleryId').val(imageId);

      jQuery('#updateGalleryImageBtn').hide();

    });



    $('#editGalleryImagePreview').click(function(){

        $('#editGalleryImageFile').trigger('click');

    });



    jQuery("#editGalleryImageFile").change(function() {



        const file = this.files[0];

        const  fileType = file['type'];

        const validImageTypes = ['image/jpeg', 'image/png'];

        if (!validImageTypes.includes(fileType)) {

            alert('Only Image file allow for gallery!');

            return false;

        }

        //console.log('edit gallery image');

        var input = this;

        if (input.files && input.files[0]) {

          var reader = new FileReader(); 

          reader.onload = function(e) {

            jQuery('#editGalleryImagePreview').attr('src', e.target.result);

            jQuery('#updateGalleryImageBtn').show();

          } 

          reader.readAsDataURL(input.files[0]);

        }

    });



    $('#updateGalleryImageBtn').click(function(event) 

    { 

          event.preventDefault();



          var file_data = $('#editGalleryImageFile').prop('files')[0];

          var imageId   = $('#editGalleryId').val(); 

          var form_data = new FormData();

          form_data.append('gallery-pic', file_data); 

          form_data.append('imageId', imageId);

          form_data.append('action', 'cb-edit-upload-gallery-picture');

          $.ajax({

              url: college_bound_ajax_object.ajax_url, // point to server-side controller method

              dataType: 'text', // what to expect back from the server

              cache: false,

              contentType: false,

              processData: false,

              data: form_data,

              type: 'post',

              success: function (response) {

                  location.reload();

                  $('#msg').html(response); // display success response from the server

              },

              error: function (response) {

                  $('#msg').html(response); // display error response from the server

              }

          });

    });



    $('.removeGalleryImage').click(function(event) 

    { 

          event.preventDefault();



          if ( !confirm("Are you want to delete this gallery media!") ) {

            return false;

          }

 

          var imageId   = $(this).attr('imageId'); 

          if( imageId == ''){

            return false;

          }



          showLoader();



          var form_data = new FormData(); 

          form_data.append('imageId', imageId);

          form_data.append('action', 'cb-remove-gallery-picture');

          $.ajax({

              url: college_bound_ajax_object.ajax_url, // point to server-side controller method

              dataType: 'text', // what to expect back from the server

              cache: false,

              contentType: false,

              processData: false,

              data: form_data,

              type: 'post',

              success: function (response) {

                  location.reload();

                  $('#msg').html(response); // display success response from the server

              },

              error: function (response) {

                  stopLoader();

                  $('#msg').html(response); // display error response from the server

              }

          });

    });

    /**-------------- End Coach Profile Gallery -------------------*/

});



/** ---------------- Login Function Start ----------------- */



function checkCsvFile(sender) {

    var validExts = new Array(".csv");

    var fileExt = sender.value;

    fileExt = fileExt.substring(fileExt.lastIndexOf('.'));

    if (validExts.indexOf(fileExt) < 0) {

      alert("Invalid file selected, Only Allow " +

               validExts.toString() + " types of files.");

      sender.value = '';

      return false;

    }

    else return true;

}



if (jQuery().validate && jQuery("#loginForm").is(":visible")) {

  jQuery("#loginForm").validate({

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

      loginFunction();

      return false;

    }

  });

  jQuery("#loginForm").submit(function(e) {

    e.preventDefault();

      loginFunction();

      return false;
  }); 

}



function loginFunction() {

    showLoader();

    if (jQuery("#loginForm").valid()) {

      var formData = {};

      //formData.action = "college_bound_login";

      jQuery("#loginForm")

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

            var res_data = response;

            if( res_data.message ) {

                if( res_data.success ){

                    Swal.fire({

                        title: "",

                        html:

                          res_data.message +

                          "<br/>" + " Page automatically redirect to Dashboard...",

                        icon: "success",

                        confirmButtonText: "Okay",

                    });

                } else {

                      Swal.fire({

                        title: "",

                        html:

                          res_data.message,

                        icon: "error",

                        confirmButtonText: "Okay",

                    });

                }

               

            }

          

            if( res_data.redirect_url ){

                jQuery("#loginForm").hide();

                setTimeout(function () {

                    window.location.href = res_data.redirect_url;

                }, 1500);

            } else {

                setTimeout(function(){ location.reload(true); }, 1000);

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

/** ---------------- End Login Function -----------------*/

jQuery(document).ready(function($){



  /**--------  Profile & Banner Picture Start  ------*/

    

    $('.banner-pic-edit').click(function(){
      $('#banner-pic').trigger('click');
    });


    $('.profile-pic-edit').click(function(){
      $('#profile-pic').trigger('click');
    });

    $('.university-banner-edit').click(function(){
      $('#university-banner-pic').trigger('click');
    });

    $('.university-pic-edit').click(function(){
      $('#university-pic').trigger('click');
    });



    $('#banner-pic').on('change', function () 

    {

        const file = this.files[0];

        const fileType = file['type'];

        const validImageTypes = ['image/jpeg', 'image/png'];

        if (!validImageTypes.includes(fileType)) {

            alert('Only Image file allow!');

            return false;

        }



        showLoader(); 



        var file_data = $('#banner-pic').prop('files')[0];

        var form_data = new FormData(); 

        form_data.append('banner-pic', this.files[0]);

        form_data.append('action', 'cb_update-user-picture'); 

        $.ajax({

            url: college_bound_ajax_object.ajax_url, // point to server-side controller method

            dataType: 'text', // what to expect back from the server

            cache: false,

            contentType: false,

            processData: false,

            data: form_data,

            type: 'post',

            success: function (response) {

                location.reload(); 

                $('#msg').html(response); // display success response from the server

            },

            error: function (response) {

                stopLoader();

                $('#msg').html(response); // display error response from the server

            }

        });

    });

  

    $('#profile-pic').on('change', function () 

    {

        const file = this.files[0];

        const fileType = file['type'];

        const validImageTypes = ['image/jpeg', 'image/png'];

        if (!validImageTypes.includes(fileType)) {

            alert('Only Image file allow!');

            return false;

        }



        showLoader(); 



        var file_data = jQuery("#profile-pic").prop('files')[0];

        var form_data = new FormData(); 

        form_data.append('profile-pic', file_data);

        form_data.append('action', 'cb_update-user-picture');  

        $.ajax({

            url: college_bound_ajax_object.ajax_url, // point to server-side controller method

            dataType: 'text', // what to expect back from the server

            cache: false,

            contentType: false,

            processData: false,

            data: form_data,

            type: 'post',

            success: function (response) {

                location.reload(); 

                $('#msg').html(response); // display success response from the server

            },

            error: function (response) {

                stopLoader();

                $('#msg').html(response); // display error response from the server

            }

        });

    });

    $('#university-banner-pic').on('change', function () 
    {
        const file = this.files[0];
        const fileType = file['type'];
        const validImageTypes = ['image/jpeg', 'image/png'];
        if (!validImageTypes.includes(fileType)) {
            alert('Only Image file allow!');
            return false;
        }

        showLoader(); 

        var file_data = jQuery("#university-banner-pic").prop('files')[0];
        var form_data = new FormData(); 
        form_data.append('university-banner-pic', file_data);
        form_data.append('action', 'cb_update-user-picture');  
        $.ajax({
            url: college_bound_ajax_object.ajax_url, // point to server-side controller method
            dataType: 'text', // what to expect back from the server
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (response) {
                location.reload(); 
                $('#msg').html(response); // display success response from the server
            },
            error: function (response) {
                stopLoader();
                $('#msg').html(response); // display error response from the server
            }
        });
    });

    $('#university-pic').on('change', function () 
    {
        const file = this.files[0];
        const fileType = file['type'];
        const validImageTypes = ['image/jpeg', 'image/png'];
        if (!validImageTypes.includes(fileType)) {
            alert('Only Image file allow!');
            return false;
        }

        showLoader(); 

        var file_data = jQuery("#university-pic").prop('files')[0];
        var form_data = new FormData(); 
        form_data.append('university-pic', file_data);
        form_data.append('action', 'cb_update-user-picture');  
        $.ajax({
            url: college_bound_ajax_object.ajax_url, // point to server-side controller method
            dataType: 'text', // what to expect back from the server
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (response) {
                location.reload(); 
                $('#msg').html(response); // display success response from the server
            },
            error: function (response) {
                stopLoader();
                $('#msg').html(response); // display error response from the server
            }
        });
    });



  /**--------  End Profile & Banner Picture ------*/





  /**--------  Review Ratting Start  ------*/

    $('.rating-to-team').click(function(){

      var member_id   = $(this).attr('member-id');

      var member_type = $(this).attr('member-type');

      var content_id  = 'member-'+member_type+'-'+member_id;

      //console.log('content_id '+content_id);



      var $content = $('#'+content_id);



      var memberPic = $content.find('.card-team-tabs-image').find('img').attr('src');

      //console.log('memberPic '+memberPic);



      var memberName = $content.find('.member-name').text();

      //console.log('memberName '+memberName);



      var memberDetails = $content.find('.member-details').html();

      //console.log('memberDetails '+memberDetails);



      var $playerModal = $('#playerRatingModal').children().children();

      $playerModal.find('.member-id').val(member_id);

      $playerModal.find('.member-type').val (member_type);

      $playerModal.find('#memberPic').attr('src', memberPic);

      $playerModal.find('.member-name').text(memberName);

      $playerModal.find('.member-details').html(memberDetails);

      // Reset Form

      $playerModal.find('.ajax-form').trigger("reset");

      // Reset Ratting

      jQuery('.rating-ball-wrap').find("label")

        .each(function () { 

            jQuery(this).removeClass("on");

      }); 



       $.ajax({

              url: college_bound_ajax_object.ajax_url,

              type: "post",

              dataType: "json",

              data: {'action':'get-member-review', 'member_id':member_id, 'member_type':member_type},

              success: function(response){

                if( response.success ) { //console.log(response.note);

                  $playerModal.find('textarea[name=note]').val(response.note);

                  $.each(response.rating, function( index, value ) {

                    //console.log( index + ": " + value );

                    $('#'+index+value).trigger('click');

                  });

                }

                $('#playerRatingModal').modal(['show']);

              }

        });

    }); 

  /** -------- End Review Rating -------- */



  /** -------- Edit Game Schedule Start --------*/

   $('.edit-game-schedule').click(function(){

      var schedule_id   = $(this).attr('schedule-id');  

      var $scheduleModal = $('#edit-gameschedule').children().children();

      $scheduleModal.find('#game-schedule-id').val(schedule_id); 

      // Reset Form

      $scheduleModal.find('.ajax-form').trigger("reset");

       

      $.ajax({

            url: college_bound_ajax_object.ajax_url,

            type: "post",

            dataType: "json",

            data: {'action':'get-game-schedule', 'schedule_id':schedule_id},

            success: function(response){

              if( response.success ) {

                $scheduleModal.find('input[name=yourteam]').val(response.yourteam);

                $scheduleModal.find('input[name=opponentteam]').val(response.opponentteam);

                $scheduleModal.find('input[name=gamedate]').val(response.gamedate);

                $scheduleModal.find('input[name=gamelocation]').val(response.gamelocation); 

              }

              $('#edit-gameschedule').modal(['show']);

            }

        });

    });

  /** -------- End Edit Game Schedule -------- */



    /** -------- Save Prospect Start -------- */  

  $('.save-prospect').click(function(){

      var a_this = $(this);

      var member_id   = a_this.attr('member-id');

      var member_type = a_this.attr('member-type');



      showLoader();



      $.ajax({

            url: college_bound_ajax_object.ajax_url,

            type: "post",

            dataType: "json",

            data: {'action':'toggle-save-prospect', 'member-id':member_id, 'member-type' : member_type},

            success: function(response){

              if( jQuery('body').hasClass('player-dashboard')){
                location.reload();
              } else {
                stopLoader();
              }

              if( response.success ) {  

                // Toggle Saved Icon

                if(  a_this.hasClass('card-action-like') ){

                  a_this.removeClass('card-action-like');

                } else {

                  a_this.addClass('card-action-like');

                }

              }

            }

        });

    });

  /** -------- End Save Prospect -------- */


  /** ----------------- Top Notification Start --------------*/
    jQuery('.devoq-notification').find('.total-count').text('0');
    jQuery('.devoq-notification').find('.dropdown-menu').html('<span class="dropdown-item">No Notification Found</span>');
    jQuery.ajax({
        url: college_bound_ajax_object.ajax_url,
        type: "post",
        dataType: "json",
        data: {'action':'get-notification'},
        success: function(response){
          if( response.success ) { console.log(response.totalCount); console.log(response.notification);
            jQuery('.devoq-notification').find('.total-count').text(response.totalCount);
            jQuery('.devoq-notification').find('.dropdown-menu').html(response.notification);
          }
        } 
    });
  /** ----------------- End Top Notification  --------------*/


  /** -------- Save Coach Start -------- */  

  $('.save-coach').click(function(){

      var a_this = $(this);
      var coach_id   = a_this.attr('coach-id');
      showLoader();

      $.ajax({
            url: college_bound_ajax_object.ajax_url,
            type: "post",
            dataType: "json",
            data: {'action':'toggle-save-coach', 'coach-id':coach_id },
            success: function(response){
              if( jQuery('body').hasClass('player-dashboard')){
                location.reload();
              } else {
                stopLoader();              }
              if( response.success ) {  
                // Toggle Saved Icon
                if(  a_this.hasClass('card-action-like') ){
                  a_this.removeClass('card-action-like');
                } else {
                  a_this.addClass('card-action-like');
                }
              }
            }
        });
    });

  /** -------- End Save Coach -------- */

    /**--------  Review Ratting Coach Start  ------*/

    $('.rating-to-coach').click(function()
    {
      console.log('fefef');
      var member_id   = $(this).attr('coach-id');
      var member_type = 'coach';
      var content_id  = 'member-'+member_type+'-'+member_id;
      //console.log('content_id '+content_id);

      var $content = $('#'+content_id);

      var memberPic = $content.find('.card-team-tabs-image').find('img').attr('src');
      //console.log('memberPic '+memberPic);

      var memberName = $content.find('.member-name').text();
      //console.log('memberName '+memberName);

      var memberDetails = $content.find('.member-details').html();
      //console.log('memberDetails '+memberDetails);

      var $playerModal = $('#playerRatingModal').children().children();
      $playerModal.find('.member-id').val(member_id);
      $playerModal.find('.member-type').val (member_type);
      $playerModal.find('#memberPic').attr('src', memberPic);
      $playerModal.find('.member-name').text(memberName);
      $playerModal.find('.member-details').html(memberDetails);
      // Reset Form
      $playerModal.find('.ajax-form').trigger("reset");
      // Reset Ratting
      jQuery('.rating-ball-wrap').find("label")
        .each(function () { 
            jQuery(this).removeClass("on");
      }); 

       $.ajax({
              url: college_bound_ajax_object.ajax_url,
              type: "post",
              dataType: "json",
              data: {'action':'get-member-review', 'coach_id':member_id, 'member_type':member_type},
              success: function(response){
                if( response.success ) { //console.log(response.note);
                  $playerModal.find('textarea[name=note]').val(response.note);
                  $.each(response.rating, function( index, value ) {
                    //console.log( index + ": " + value );
                    $('#'+index+value).trigger('click');
                  });
                }
                $('#playerRatingModal').modal(['show']);
              }
        });
    }); 
  /** -------- End Review Rating Coach-------- */


});