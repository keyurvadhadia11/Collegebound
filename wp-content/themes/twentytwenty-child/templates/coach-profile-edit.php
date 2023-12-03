<?php
/**
 * Template Name: Coach Profile Edit
 */ 

global $current_user; // Use global
get_currentuserinfo(); 

$current_page = get_the_id();

$userId = get_current_user_id();
$user_info = get_userdata($userId);  

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
                    $bannerImageUrl =  $image_path.'/banner01.jpg';
                    $banner_attachment_id = get_user_meta( $userId, 'banner-pic', true);
                    if( $banner_attachment_id  && file_exists(get_attached_file($banner_attachment_id)) )
                    $bannerImage = wp_get_attachment_image_src( $banner_attachment_id, $size = 'full');
                    if($bannerImage)
                        $bannerImageUrl = $bannerImage[0];
                 ?>
                <img src="<?php echo $bannerImageUrl;?>" class="img-fluid d-block w-100" />
                <a href="javascript:void(0);" class="action-edit banner-pic-edit"><i class="icon icon-edit"></i></a>
                <input type="file" name="bannerPic" id="banner-pic" accept="image/jpeg, image/png" data-type='image' style="opacity:0;">
              </div>

              <div class="card-profile-content">
                <div class="card-profile-image">
                  <?php
                    $dpImageUrl =  $image_path.'/dp.png';
                    $dpImageUrl = getUiAvtarUrl( get_user_meta( $userId, 'first_name', true).'+'.get_user_meta( $userId, 'last_name', true) );
                    $attachment_id = get_user_meta( $userId, 'profile-pic', true);
                    if( $attachment_id  && file_exists(get_attached_file($attachment_id)) )
                    $dpImage = wp_get_attachment_image_src( $attachment_id, $size = 'thumbnail');
                    if($dpImage)
                        $dpImageUrl = $dpImage[0];
                  ?>
                  <img src="<?php echo $dpImageUrl;?>" class="img-fluid" alt="">
                  <a href="javascript:void(0);" class="action-edit profile-pic-edit"><i class="icon icon-edit"></i></a>
                  <input type="file" name="profilePic" id="profile-pic" accept="image/jpeg, image/png" data-type='image' style="opacity:0;">
                </div>

                <h5><?php echo get_user_meta($userId, 'first_name', true);?> <?php echo get_user_meta($userId, 'last_name', true);?></h5>

                <p class="text-slate mb-3"><?php echo get_user_meta( $userId, 'universitycollege', true);?></p>

                <p><?php echo get_user_meta( $userId, 'coach_description', true);?></p>
              </div>

              <div class="card-profile-form">
                <form class="ajax-form" method="post" autocomplete="off">
                  <div class="row gutters-10">
                    <div class="col-lg-12">
                      <div class="text-center mb-5">
                        <input type="hidden" name="action" value="coach-profile-edit">
                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-input">
                        <label class="form-label active">First Name</label>
                        <input type="text" class="form-control" value="<?php echo get_user_meta($userId, 'first_name', true);?>" name="first_name">
                        <i class="icon icon-user"></i>
                      </div>

                      <div class="form-input">
                        <label class="form-label active">Last Name</label>
                        <input type="text" class="form-control" value="<?php echo get_user_meta($userId, 'last_name', true);?>" name="last_name">
                        <i class="icon icon-user"></i>
                      </div>

                      <div class="form-input">
                        <label class="form-label active">Email ID</label>
                        <input type="email" class="form-control" value="<?php echo $user_info->user_email;?>" name="user_email">
                        <i class="icon icon-mail"></i>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-input">
                        <label class="form-label active">University or College</label>
                        <input type="text" class="form-control" value="<?php echo get_user_meta( $userId, 'universitycollege', true);?>" name="universitycollege">
                        <i class="icon icon-school"></i>
                      </div>

                      <div class="form-input">
                        <label class="form-label active">Hometown</label>
                        <input type="text" class="form-control" value="<?php echo get_user_meta( $userId, 'address', true);?>" name="address">
                        <i class="icon icon-address"></i>
                      </div>

                      <div class="form-input">
                        <label class="form-label active">Coach Title</label>
                        <input type="text" class="form-control" value="<?php echo get_user_meta( $userId, 'coachtitle', true);?>"  name="coachtitle">
                        <i class="icon icon-coach"></i>
                      </div>
                    </div>
                    <div class="col-lg-12 full-sloat">
                      <div class="form-input">
                        <label class="form-label">About Me</label>
                        <textarea class="form-control" name="coach_description" style="height: 100px;"><?php echo get_user_meta( $userId, 'coach_description', true);?></textarea>
                        <i class="icon icon-about icon-top"></i>
                      </div>
                    </div>
                    <div class="col-lg-12 full-sloat">
                      <div class="form-input">
                        <label class="form-label">About University</label>
                        <textarea class="form-control" name="university_description" style="height: 100px;"><?php echo get_user_meta( $userId, 'university_description', true);?></textarea>
                        <i class="icon icon-school icon-top"></i>
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
                 <?php
                    $bannerImageUrl =  $image_path.'/banner01.jpg';
                    $banner_attachment_id = get_user_meta( $userId, 'university-banner-pic', true);
                    if( $banner_attachment_id  && file_exists(get_attached_file($banner_attachment_id)) )
                    $bannerImage = wp_get_attachment_image_src( $banner_attachment_id, $size = 'full');
                    if($bannerImage)
                        $bannerImageUrl = $bannerImage[0];
                 ?> 
                <img src="<?php echo $bannerImageUrl;?>" class="img-fluid d-block w-100" alt="">
                <a href="javascript:void(0);" class="action-edit university-banner-edit"><i class="icon icon-edit" title="University Banner Picture Edit"></i></a>
                <input type="file" name="universityBannerPic" id="university-banner-pic" accept="image/jpeg, image/png" data-type='image' style="opacity:0;">
              </div>

              <div class="card-college-content pb-0">
                <div class="card-college-logo">
                  <?php
                    $dpImageUrl =  $image_path.'/college-logo.png';
                    $dpImageUrl = getUiAvtarUrl( get_user_meta( $userId, 'universitycollege', true));
                    $attachment_id = get_user_meta( $userId, 'university-pic', true);
                    if( $attachment_id  && file_exists(get_attached_file($attachment_id)) )
                    $dpImage = wp_get_attachment_image_src( $attachment_id, $size = 'thumbnail');
                    if($dpImage)
                        $dpImageUrl = $dpImage[0];
                  ?>
                  <img src="<?php echo $dpImageUrl;?>" class="img-fluid" alt="">
                  <a href="javascript:void(0);" class="action-edit university-pic-edit" title="University Picture Edit"><i class="icon icon-edit"></i></a>
                   <input type="file" name="universityPic" id="university-pic" accept="image/jpeg, image/png" data-type='image' style="opacity:0;">
                </div>

                <h4><?php echo get_user_meta( $userId, 'universitycollege', true);?></h4>
                <p><?php echo get_user_meta( $userId, 'university_description', true);?></p>
              </div>
              <div class="pb-5"></div>
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
                <div class="col-xl-4 col-lg-6 col-md-6">
                  <div class="card-gallery-upload">
                    <input type="file" class="custom-upload" id="galleryFile" name="galleryFile" accept="image/jpeg, image/png, video/mp4"/>
                    <div class="card-upload-image">
                      <img id="upload-gallery-pic" src="<?php echo $image_path;?>/image-upload-img.png" class="img-fluid" alt=""/>
                    </div>

                    <a href="javascript:void(0);">Upload New</a>
                  </div>
                </div>
                <?php
                    $galleryImages = array();
                    $galleryImagesData = get_user_meta( $userId, 'gallery-pic', true );

                    if( !empty($galleryImagesData) ) {
                        $galleryImages = json_decode($galleryImagesData);
                    }

                    foreach ($galleryImages as $imageKey => $attachmentId) { 
                        if( wp_attachment_is( 'image', $attachmentId ) ){
                            
                        $image = wp_get_attachment_image_src($attachmentId, 'full', true);
                ?>
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
              </div>
            </div>
          </div>
        </div>
        <!-- End Gallery Content -->
      </div>
<?php get_footer('admin'); ?>