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
                  <img src="<?php echo $assetsPath; ?>images/logo-white.svg" class="img-fluid d-block mx-auto" align="CollegeBound" />
               </div>
               <div class="sidebar-profile">
                  <div class="sidebar-image">
                   <?php
                    $dpImageUrl =  $image_path.'/dp.png';
                    $dpImageUrl = getUiAvtarUrl( get_user_meta($userId, 'first_name', true).'+'.get_user_meta($userId, 'last_name', true) );
                    $attachment_id = get_user_meta( $userId, 'profile-pic', true);
                    if( $attachment_id  && file_exists(get_attached_file($attachment_id)) )
                    $dpImage = wp_get_attachment_image_src( $attachment_id, 'thumbnail');
                    if($dpImage)
                        $dpImageUrl = $dpImage[0];
                  ?> 
                     <img src="<?php echo $dpImageUrl;?>" class="img-fluid" alt="<?php echo get_user_meta($userId, 'first_name', true).' '.get_user_meta($userId, 'last_name', true);?>"/>
                  </div>
                  <h5 class="sidebar-username"><?php echo get_user_meta($userId, 'first_name', true).' '.get_user_meta($userId, 'last_name', true);?></h5>
                  <h6 class="sidebar-designation"><?php echo get_user_meta( $userId, 'coachtitle', true);?></h6>
                  <a href="<?php echo wp_logout_url('home');?>" class="btn btn-outline-primary">Logout</a>
               </div>
            </div>
            <div class="sidebar-links">
               <?php
               $dashboardUrl = site_url('admin-dashboard');
                if( current_user_can('coach') ) {
                    $dashboardUrl = site_url('coach-dashboard');
                }
               ?>
               <a href="<?php echo $dashboardUrl; ?>" class="item-link">
                   <i class="icon icon-dashboard"></i>
                   Dashboard
               </a>
               <?php if( current_user_can('eventOperator')) { ?>
               <a href="<?php echo site_url('manage-tournament');?>" class="item-link">
                   <i class="icon icon-events"></i>
                   Create Event
               </a>
               <?php } ?>
               <a href="<?php echo get_permalink(45);?>" class="item-link">
                    <?php if(current_user_can('eventOperator')){ ?>
                    <i class="icon icon-all-event"></i>
                    All Events
                    <?php } else { ?>
                    <i class="icon icon-tournament"></i>
                    Event<br>Packets
                   <?php } ?>
               </a>
               <a href="<?php echo site_url('message');?>" class="item-link">
                   <i class="icon icon-message"></i>
                   Messages
               </a>
               <?php
                  $setting_url = site_url('coach-setting');
                  if( current_user_can('eventOperator') ) {
                    $setting_url = site_url('operator-setting');
                  }
                ?>
               <a href="<?php echo $setting_url;?>" class="item-link">
                   <i class="icon icon-setting"></i>
                   Settings
               </a>
               <?php 
                 $profile_url =  get_permalink(24);
                if( current_user_can('eventOperator')) {
                  $profile_url = site_url('operator-profile');
                }
               ?>
               <a href="<?php echo $profile_url;?>" class="item-link">
                   <i class="icon icon-profile"></i>
                   Profile
               </a>
               <a href="<?php echo site_url();?>/purchase-history/" class="item-link">
                   <i class="icon icon-purchase"></i>
                   Purchase History
               </a>
            </div>
        </div>