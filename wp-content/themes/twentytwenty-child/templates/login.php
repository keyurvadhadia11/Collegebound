<?php
/**
 * Template Name: Login
 */ 

// Is user login, then redirect to dashboard
if (is_user_logged_in() ) {
    if( current_user_can('player')){
       wp_redirect( site_url('player-dashboard') );
    } 
    elseif( current_user_can('coach')){
       wp_redirect( site_url('coach-dashboard') );
    } else {
      wp_redirect ( site_url('admin-dashboard') );  
    } 
    exit;
}

get_header('home');
?>
      <!-- Content -->
      <div class="section-signup">
        <div class="left-form">
          <a href="<?php echo site_url();?>" class="back-to">
            <i class="icon icon-left-arrow"></i>
          </a>
        </div>
        <div class="right-form">
          <div class="logo-form">
            <p>Get Started With</p>

            <img src="<?php echo get_template_directory_uri();?>/assets/images/logo.svg" class="img-fluid" alt="College Bound">
          </div>

          <div class="box-sign">
            <div class="section-title">
              <h4 class="title-sm text-center">
                <?php
                  $role = $_GET['by'];
                  $userRole = 'eventOperator';
                  if( $role == 'coach')
                    $userRole = 'coach';
                  elseif( $role == 'player')
                    $userRole = 'player'; 

                  if( $userRole == 'coach'){
                    echo 'Login to your College Coach Account';
                  }
                  elseif( $userRole == 'player'){
                    echo 'Login to your Athlete Account';
                  } else {
                    echo 'Login to your Event Operator Account';
                  }
                ?> 
              </h4>
            </div>

            <form id="loginForm" method="post" autocomplete="off">
              <div class="message-content"></div>
              <div class="form-input">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="username" required/>
                <i class="icon icon-mail"></i>
              </div>

              <div class="form-input">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required/>
                <i class="icon icon-lock"></i>
              </div>

              <div class="form-input d-flex justify-content-between small">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="rememberme">
                  <label class="custom-control-label text-slate" for="rememberme">Remember Me</label>
                </div>

                <a class="text-slate" href="<?php echo site_url('forgot-password'); ?>">Forgot Password?</a>
              </div>

              <div class="form-input mb-4">
                <input type="hidden" name="action" value="user_login"> 
                <input type="hidden" name="userRole" value="<?php echo $userRole;?>"> 
                <button type="submit" class="btn btn-block btn-primary">login</button>
              </div>

              <p class="text-slate text-center small">Don't have an account? <a class="text-primary font-weight-medium" href="<?php echo add_query_arg( 'by', $userRole, site_url('signup'));?>">Sign Up</a></p>
            </form>
          </div>
        </div>
      </div> 
<?php get_footer('home'); ?>