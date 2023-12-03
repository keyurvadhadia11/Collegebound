<?php 
    global $current_user; // Use global
    get_currentuserinfo(); 

    $current_page = get_the_id();

    $userId = get_current_user_id(); 

    $assetsPath = get_template_directory_uri().'/assets/';
    $image_path = $assetsPath.'images';
?>      

        <!-- Sidebar -->
        <div class="section-sidebar">
          <a href="javascript:void(0);" id="menu-close-toggle" class="menu-close-toggle">
            <i class="icon icon-close"></i>
          </a>
          
          <div class="sidebar-top">
            <div class="sidebar-logo">
              <img src="<?php echo $assetsPath;?>images/logo-white.svg" class="img-fluid d-block mx-auto" align="CollegeBound" />
            </div>

            <div class="sidebar-profile">
              <div class="sidebar-image">
                 <?php
                    $dpImageUrl =  $image_path.'/dp03.png';
                     $dpImageUrl = getUiAvtarUrl( get_user_meta($userId, 'first_name', true).'+'.get_user_meta($userId, 'last_name', true) );
                    $attachment_id = get_user_meta( $userId, 'profile-pic', true);
                    if( $attachment_id  && file_exists(get_attached_file($attachment_id)) )
                    $dpImage = wp_get_attachment_image_src( $attachment_id,'thumbnail' );
                    if( isset($dpImage[0]) )
                        $dpImageUrl = $dpImage[0];
                  ?>
                <img src="<?php echo $dpImageUrl;?>" class="img-fluid" alt="<?php echo get_user_meta($userId, 'first_name', true).' '.get_user_meta($userId, 'last_name', true);?>"/>
              </div>
              <h5 class="sidebar-username"><?php echo get_user_meta($userId, 'first_name', true).' '.get_user_meta($userId, 'last_name', true);?></h5>

              <h6 class="sidebar-teamname"><?php echo get_user_meta($userId, 'universitycollege', true);?></h6>

              <a href="<?php echo wp_logout_url('home');?>" class="btn btn-outline-primary">Logout</a>
            </div>
          </div>
          <div class="sidebar-links">
            <a href="<?php echo site_url('player-dashboard');?>" class="item-link">
              <i class="icon icon-dashboard"></i>
              Dashboard
            </a>
            <a href="<?php echo site_url('message');?>" class="item-link">
              <i class="icon icon-message"></i>
              Messages
            </a>
            <a href="<?php echo site_url('player-game-schedule');?>" class="item-link">
              <i class="icon icon-game"></i>
              Game<br/>Schedule
            </a>
            
            <a href="<?php echo site_url('player-setting');?>" class="item-link">
              <i class="icon icon-setting"></i>
              Settings
            </a>
            <a href="<?php echo site_url('player-profile');?>" class="item-link">
              <i class="icon icon-profile"></i>
              Profile
            </a>
          </div>
        </div>