<?php
/**
 * Template Name: Operator Profile
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

get_header('admin');
?> 
    <!-- COntent -->
      <div class="container-fluid">
        <div class="row gutters-10">
          <div class="col-xl-8">
            <div class="card-profile">
              <div class="card-profile-banner">
                <?php
                    $bannerImageUrl =  $image_path.'/banner08.jpg';
                    $banner_attachment_id = get_user_meta( $userId, 'banner-pic', true);
                    if( $banner_attachment_id && file_exists(get_attached_file($banner_attachment_id)) )
                    $bannerImage = wp_get_attachment_image_src( $banner_attachment_id, $size = 'full');
                    if($bannerImage)
                        $bannerImageUrl = $bannerImage[0];
                 ?>
                <img src="<?php echo $bannerImageUrl;?>" class="img-fluid d-block w-100" />
              </div>

              <div class="card-profile-content">
                <div class="card-profile-image">
                  <?php
                    $dpImageUrl =  $image_path.'/dp.png';
                    $dpImageUrl = getUiAvtarUrl( get_user_meta( $userId, 'first_name', true).'+'.get_user_meta( $userId, 'last_name', true) );
                    $attachment_id = get_user_meta( $userId, 'profile-pic', true);
                    if( $attachment_id && file_exists(get_attached_file($attachment_id)) )
                    $dpImage = wp_get_attachment_image_src( $attachment_id, $size = 'thumbnail');
                    if($dpImage)
                        $dpImageUrl = $dpImage[0];
                  ?>
                  <img src="<?php echo $dpImageUrl;?>" class="img-fluid" alt="">
                </div>

                <h5><?php echo get_user_meta($userId, 'first_name', true);?> <?php echo get_user_meta($userId, 'last_name', true);?></h5>

                <p class="text-slate mb-3"><?php echo get_user_meta( $userId, 'coachtitle', true); ?></p>

                <p>Etiam sed tincidunt odio. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Enim sodales, consequat velit at, dapibus metus. Suspendisse sit amet pulvinar ipsum.</p>
              </div>

              <div class="card-profile-form">
                <form autocomplete="off">
                  <div class="row gutters-10">
                    <div class="col-lg-12">
                      <div class="text-center mb-5">
                        <a href="<?php echo site_url('operator-profile-edit');?>" class="btn btn-sm btn-primary">Edit</a>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-input">
                        <label class="form-label active inactive">First Name</label>
                        <input type="text" class="form-control" value="<?php echo get_user_meta($userId, 'first_name', true);?>" name="firstname" readonly="">
                        <i class="icon icon-user"></i>
                      </div>
                      <div class="form-input">
                        <label class="form-label active inactive">Last Name</label>
                        <input type="text" class="form-control" value="<?php echo get_user_meta($userId, 'last_name', true);?>" name="lastname" readonly="">
                        <i class="icon icon-user"></i>
                      </div>
                      <div class="form-input">
                        <label class="form-label active inactive">Operator Title</label>
                        <input type="text" class="form-control" value="<?php echo $user_info->coachtitle;?>" name="coachtitle" readonly="">
                        <i class="icon icon-mail"></i>
                      </div> 
                    </div>

                    <div class="col-lg-6">
                      <div class="form-input">
                        <label class="form-label active inactive">Email ID</label>
                        <input type="email" class="form-control" value="<?php echo $user_info->user_email;?>" name="email" readonly="">
                        <i class="icon icon-mail"></i>
                      </div>
                      <div class="form-input">
                        <label class="form-label active inactive">Hometown</label>
                        <input type="text" class="form-control" value="<?php echo get_user_meta($userId, 'address', true);?>" name="address" readonly="">
                        <i class="icon icon-address"></i>
                      </div> 
                      
                    </div>                 
                  </div>
                </form>
              </div>
            </div> 
          </div>
          <div class="col-xl-4">
            <div class="card-activity-wrap">
              <div class="card-activity">
                <ul class="list-unstyled">
                  <li>
                    <span class="card-activity-circle">
                      <i class="icon icon-bell"></i>
                    </span>

                    <span>There are <a class="text-primary" href="#">12 new players</a> under your saved search for 2018 graduates.</span>
                  </li>

                  <li>
                    <span class="card-activity-circle">
                      <i class="icon icon-bell"></i>
                    </span>

                    <span>Message request from <a class="text-primary" href="#">Coach Quintero.</a></span>
                  </li>

                  <li>
                    <span class="card-activity-circle">
                      <i class="icon icon-bell"></i>
                    </span>

                    <span><a class="text-primary" href="#">Amanda Jenkins</a> added a new video.</span>
                  </li>

                  <li>
                    <span class="card-activity-circle">
                      <i class="icon icon-bell"></i>
                    </span>

                    <span>Keyur Vadhadia added new videos & pictures </span>
                  </li>

                  <li>
                    <span class="card-activity-circle">
                      <i class="icon icon-bell"></i>
                    </span>

                    <span>Damian Lillard Updated her profile.</span>
                  </li>

                  <li>
                    <span class="card-activity-circle">
                      <i class="icon icon-bell"></i>
                    </span>

                    <span>There are <a class="text-primary" href="#">12 new players</a> under your saved search for 2018 graduates.</span>
                  </li>

                  <li>
                    <span class="card-activity-circle">
                      <i class="icon icon-bell"></i>
                    </span>

                    <span>Message request from <a class="text-primary" href="#">Coach Quintero.</a></span>
                  </li>

                  <li>
                    <span class="card-activity-circle">
                      <i class="icon icon-bell"></i>
                    </span>

                    <span><a class="text-primary" href="#">Amanda Jenkins</a> added a new video.</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div> 
<?php get_footer('admin'); ?>