<?php
/**
 * Template Name: Signup Thank You
 */

// Is user login, then redirect to dashboard
if (is_user_logged_in() ) {
  wp_redirect ( site_url('dashboard') );
  exit;
}

get_header('login');
?>
    <div class="section-signin">
      <div class="container container-1230">
          <div class="step-7 row">
              <div class="col-lg-5 mx-auto text-left">
                  <p class="register-link text-left">You’re all set!</p>
                  <span class="mt-2 pt-1 text-left pay-info-text d-block">
                      Thank you for joining CollegeBound.
                      A payment confirmation will be sent to your e-mail. Don’t forget to set up your profile next.
                  </span>
                  
              </div>
          </div>
      </div>
    </div>
<?php get_footer('home'); ?>