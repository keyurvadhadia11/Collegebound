<?php
/**
 * Template Name: Coach Member View
 */ 

global $current_user; // Use global
get_currentuserinfo(); 

$current_page = get_the_id();

// Is user login, then redirect to dashboard
if ( !is_user_logged_in() && !current_user_can('administrator') ) {
  wp_redirect ( site_url('login') );
  exit;
} 

$coachDetails = $wpdb->get_row("SELECT * FROM `wp_cb_team_coaches` WHERE `id` =".$_GET['member_id']);
if( !isset($coachDetails->id) ){
  wp_die('Coach Not Found.');
}
//var_dump($playerDetails); die('fefef');

$image_path = get_template_directory_uri().'/assets/images';

get_header('player');
?> 
    <!-- COntent -->
      <div class="container-fluid">
        <div class="row gutters-10">
          <div class="col-xl-8">
            <div class="card-profile">
              <div class="card-profile-banner">
                <?php
                    $bannerImageUrl =  $image_path.'/banner01.jpg';
                 ?>
                <img src="<?php echo $bannerImageUrl;?>" class="img-fluid d-block w-100" />
              </div>

              <div class="card-profile-content">
                <div class="card-profile-image">
                  <?php
                    $dpImageUrl = getUiAvtarUrl( $coachDetails->name );
                  ?>
                  <img src="<?php echo $dpImageUrl;?>" class="img-fluid" alt="">
                </div>

                <h5><?php echo $coachDetails->name; ?></h5>
                <p>Etiam sed tincidunt odio. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Enim sodales, consequat velit at, dapibus metus. Suspendisse sit amet pulvinar ipsum.</p>
              </div>

              <div class="card-profile-form">
                <form autocomplete="off">
                  <div class="row gutters-10">
                    <?php $names = explode(" ", $coachDetails->name);?>
                    <div class="col-lg-6">
                      <div class="form-input">
                        <label class="form-label active inactive">First Name</label>
                        <input type="text" class="form-control" value="<?php echo isset($names[0]) ? $names[0] : ''; ?>" readonly="">
                        <i class="icon icon-user"></i>
                      </div>

                      <div class="form-input">
                        <label class="form-label active inactive">Last Name</label>
                        <input type="text" class="form-control" value="<?php echo isset($names[1]) ? $names[1] : ''; ?>" readonly="">
                        <i class="icon icon-user"></i>
                      </div>

                      <div class="form-input">
                        <label class="form-label active inactive">Email ID</label>
                        <input type="email" class="form-control" value="<?php echo $coachDetails->email;?>" readonly="">
                        <i class="icon icon-mail"></i>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-input">
                        <label class="form-label active inactive">University or College</label>
                        <input type="text" class="form-control" value="" readonly="">
                        <i class="icon icon-school"></i>
                      </div>

                      <div class="form-input">
                        <label class="form-label active inactive">Hometown</label>
                        <input type="text" class="form-control" value="<?php echo $coachDetails->address; ?>" readonly="">
                        <i class="icon icon-address"></i>
                      </div>

                      <div class="form-input">
                        <label class="form-label active inactive">Coach Title</label>
                        <input type="text" class="form-control" value="" readonly="">
                        <i class="icon icon-coach"></i>
                      </div>
                    </div>                    
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="col-xl-4 col-lg-6">
            <div class="card-college">
              <div class="card-college-image">
                <img src="<?php echo $image_path;?>/banner01.jpg" class="img-fluid d-block w-100" alt="">
              </div>

              <div class="card-college-content pb-0">
                <div class="card-college-logo">
                  <img src="<?php echo $image_path;?>/college-logo.png" class="img-fluid" alt="">
                </div>

                <h4 class="mb-0"></h4>
              </div>

              <!-- Listing -->
              <div class="pb-5"> 
              </div>
              <!-- End Listing -->
            </div>
          </div>
        </div>

        <!-- Gallery Content Start -->
        <div class="row gutters-10">
          <div class="col-lg-12">
            <div class="section-title">
              <h3 class="title-xs">Gallery</h3>
            </div>
          </div>
          <div class="col-lg-12">
            <div class="card-gallery-wrap">
              <div class="row row-equal gutters-12">
                <?php
                    $isGalleryImage = false;
                    $galleryImages = array();
                    //$galleryImagesData = get_user_meta( $userId, 'gallery-pic', true );

                    if( !empty($galleryImagesData) ) {
                        $galleryImages = json_decode($galleryImagesData);
                    }

                    foreach ($galleryImages as $imageKey => $attachmentId) {
                        $isGalleryImage = true; 
                        if( wp_attachment_is( 'image', $attachmentId ) ){
                            
                        $image = wp_get_attachment_image_src($attachmentId, 'full', true); 
                ?>
                <div class="col-xl-4 col-lg-6 col-md-6">
                  <div class="card-gallery">
                    <!--<div class="card-gallery-action dropdown">
                      <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon icon-more"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item removeGalleryImage" href="#" imageId="<?php echo $attachmentId;?>">Delete</a>
                      </div>
                    </div>-->
                    
                    <div class="card-gallery-image">
                      <img src="<?php echo $image[0]; ?>" class="img-fluid" alt="" />
                    </div>
                  </div>
                </div>
                <?php } // Is Image  ?>

                <?php  if( wp_attachment_is( 'video', $attachmentId ) ) { ?>
                <div class="col-xl-4 col-lg-6 col-md-6">
                  <div class="card-gallery">
                    <div class="card-gallery-action dropdown">
                      <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon icon-more"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item removeGalleryImage" href="#" imageId="<?php echo $attachmentId;?>">Delete</a>
                      </div>
                    </div>
                    
                    <div class="card-gallery-image">
                      <video width="100%" height="100%" controls src="<?php echo wp_get_attachment_url($attachmentId);?>" controlsList="nodownload"></video>
                    </div>
                  </div>
                </div>
                <?php } // Is Video?>

                <?php } // End Foreach?>

                <?php if( !$isGalleryImage) : ?>
                <div class="col-xl-4 col-lg-6 col-md-6">
                  <div class="card-gallery-upload">
                    <div class="card-upload-image">
                      <img id="upload-gallery-pic" src="<?php echo $image_path;?>/image-upload-img.png" class="img-fluid" alt=""/>
                    </div>
                  </div>
                </div>
                <?php endif;?>

              </div>
            </div>
          </div>
        </div>
        <!-- End Gallery Content -->
      </div> 
<?php get_footer('admin'); ?>