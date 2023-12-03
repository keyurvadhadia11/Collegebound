<?php
/**
 * Template Name: Home
 */
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
      <div class="left-form-inner"></div>
    </div>

    <div class="right-form">
      <div class="logo-form">
        <p>Get Started With</p>

        <img src="<?php echo get_template_directory_uri();?>/assets/images/logo.svg" class="img-fluid" alt="College Bound">
      </div>

      <div class="box-form">
        <p>Welcome to College Bound</p>

        <div class="section-title">
          <h4 class="title-sm">Choose your option!</h4>
        </div>

        <a href="<?php echo add_query_arg( 'by', 'coach', site_url('login') ); ?>" class="btn btn-secondary btn-block">College coach</a>

        <a href="<?php echo add_query_arg( 'by', 'player', site_url('login') ); ?>" class="btn btn-secondary btn-block">Athlete</a>

        <a href="<?php echo add_query_arg( 'by', 'operator', site_url('login') ); ?>" class="btn btn-secondary btn-block">Event Operator</a>
      </div>
    </div>
  </div>
<?php get_footer('home'); ?>