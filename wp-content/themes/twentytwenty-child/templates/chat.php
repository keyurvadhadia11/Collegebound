<?php
/**
 * Template Name: Chat
 */
global $current_user; // Use global
get_currentuserinfo(); 

$current_page = get_the_id();

$userId = get_current_user_id();

$user_info = get_userdata( $userId );  

// Is user login, then redirect to dashboard
if ( !is_user_logged_in() && !current_user_can('administrator') ) {
  wp_redirect ( site_url('login') );
  exit;
}
$image_path = get_template_directory_uri().'/assets/images';

if( current_user_can('player') ){
  get_header('player');
}else{
  get_header('admin');  
}
?> 
    <!-- COntent -->
     <div class="container-fluid">
        <div class="row gutters-10">
          <div class="col-lg-12">
            <div class="section-title">
              <h3 class="title-xs">Message</h3>
            </div>
          </div>

          <div class="col-lg-12">
            <div class="card-message-wrap">
              <?php
                  echo do_shortcode( "[cometchat-pro widget-id='ace940d6-d604-46d1-bd4b-1e35551e79a0' widget-height='800px' widget-width='100%' widget-version='v2']" );
               ?>
               <style type="text/css">
                 #cometchat, #cometchat__widget, .app__wrapper.css-79elbk  { width: 100% !important; float: left; }
               </style>
            </div>
          </div>
        </div>
      </div>
<?php get_footer('admin'); ?>