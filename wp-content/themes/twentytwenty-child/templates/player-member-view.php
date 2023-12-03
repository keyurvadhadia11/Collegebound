<?php
/**
 * Template Name: Player Member View
 */ 

global $current_user, $wpdb; // Use global
get_currentuserinfo(); 

$current_page = get_the_id();

// Is user login, then redirect to dashboard
if ( !is_user_logged_in() && !current_user_can('administrator') ) {
  wp_redirect ( site_url('login') );
  exit;
} 

$playerDetails = $wpdb->get_row("SELECT * FROM `wp_cb_team_athletes` WHERE `id` =".$_GET['member_id']);
if( !isset($playerDetails->id) ){
  wp_die('Player Not Found.');
}
//var_dump($playerDetails); die('fefef');

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
                 ?>
                <img src="<?php echo $bannerImageUrl; ?>" class="img-fluid d-block w-100" />
              </div>

              <div class="card-profile-content">
                <div class="card-profile-image">
                  <?php
                    $dpImageUrl = getUiAvtarUrl( $playerDetails->name );
                  ?>
                  <img src="<?php echo $dpImageUrl; ?>" class="img-fluid" alt="">
                </div>

                <h5><?php echo $playerDetails->name; ?></h5>

                <p class="text-slate font-weight-bold">No. <?php echo $playerDetails->jersey_number;?></p>

                <div class="card-profile-detail">
                  <ul class="list-unstyled">
                    <li>
                      <div class="profile-list-detail">
                        <i class="icon icon-lg-height"></i>
                      </div>
                      <?php
                        $height = '';
                        if(!empty($playerDetails->height)){
                          $tempHeightFt = explode('ft', $playerDetails->height);
                          $tempHeightInch = str_replace('in', '', $tempHeightFt[1]);
                          $height = $tempHeightFt[0]."'".$tempHeightInch;
                        }
                      ?>
                      <h6 class="text-slate"><?php echo $height;?>"</h6>
                      <p>Height</p>
                    </li>

                    <li>
                      <?php
                      $position = '';
                      $userPosition = $playerDetails->position;
                      $positions = array(
                        "PG" => "Point Guard",
                        "SG" => "Shooting Guard",
                        "SF" => "Small Forward",
                        "PF" => "Power Forward",
                        "Center"=> "Center",
                      ); 
                      $userPosition = $positions[$userPosition];

                      if( !empty($userPosition) ) {
                        $position = trim($userPosition,'"');
                      }
                      ?>
                      <div class="profile-list-detail">
                        <i class="icon icon-<?php echo strtolower(strtok($position, " "));?>"></i>
                      </div>
                      <h6 class="text-slate"><?php echo ucfirst($position); ?></h6>
                      <p>Position</p>
                    </li>

                    <li>
                      <div class="profile-list-detail">
                        <i class="icon icon-grade"></i>
                      </div>
                      <h6 class="text-slate"><?php echo $playerDetails->graduation_year;?></h6>
                      <p>Graduation Year</p>
                    </li>
                  </ul>
                </div>
              </div>

              <div class="card-profile-form">
                <form autocomplete="off">
                  <div class="row gutters-10">
                    <div class="col-lg-6">
                      <?php $names = explode(" ", $playerDetails->name);?>
                      <div class="form-input">
                        <label class="form-label active inactive">First Name</label>
                        <input type="text" class="form-control" value="<?php echo isset($names[0]) ? $names[0] : ''; ?>" readonly>
                        <i class="icon icon-user"></i>
                      </div>

                      <div class="form-input">
                        <label class="form-label active inactive">Last Name</label>
                        <input type="text" class="form-control" value="<?php echo isset($names[1]) ? $names[1] : ''; ?>" readonly>
                        <i class="icon icon-user"></i>
                      </div>

                      <div class="form-input">
                        <label class="form-label active inactive">Email ID</label>
                        <input type="email" class="form-control" value="<?php echo $playerDetails->email;?>" readonly>
                        <i class="icon icon-mail"></i>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-input">
                        <label class="form-label active inactive">High School</label>
                        <input type="text" class="form-control" value="<?php echo $playerDetails->school_name;?>" readonly>
                        <i class="icon icon-school"></i>
                      </div>

                      <div class="form-input">
                        <label class="form-label active inactive">Hometown</label>
                        <input type="text" class="form-control" value="<?php echo $playerDetails->address; ?>" readonly>
                        <i class="icon icon-address"></i>
                      </div>
                    </div>                    
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="col-xl-4 col-lg-6">
            <div class="card-game d-xl-flex flex-column">
              <div class="card-game-image">
                <img src="<?php echo $image_path; ?>/game-schdule.png" class="img-fluid" alt="">
              </div>

              <h4>Game Schedule</h4>
               <div>
                <?php
                //$gameSchedules = $wpdb->get_results("SELECT * FROM `wp_cb_player_game_schedule` WHERE `user_id` = $userId AND `game_date` >= '".date('Y-m-d')."' ORDER BY id DESC LIMIT 6");
                $gameSchedules = array();
                foreach ($gameSchedules as $gameKey => $gameSchedule) { 
                ?>
                <div class="card-game-list">
                  <div class="card-game-list-image">
                   <?php
                      $ImageUrl =  $image_path.'/teamvs.png'; 
                      if( !empty($gameSchedule->game_image ) ){
                        $dpImage = wp_get_attachment_image_src( $gameSchedule->game_image, $size = 'thumbnail');
                        if($dpImage)
                            $ImageUrl = $dpImage[0];
                      }
                    ?>    
                    <img src="<?php echo $ImageUrl; ?>" class="img-fluid" alt="<?php echo $gameSchedule->your_team;?> vs <?php echo $gameSchedule->opponent_team;?>" />
                  </div>

                  <div class="card-game-list-content">
                    <h5><?php echo $gameSchedule->your_team;?>  VS  <?php echo $gameSchedule->opponent_team;?></h5>
                    <p><?php echo $gameSchedule->game_location;?></p>
                  </div>

                  <div class="card-game-list-action">
                    <a href="javascript:void(0);" class="text-primary" title="<?php echo date('d F Y', strtotime($gameSchedule->game_date
                    ));?>">
                      <i class="icon icon-active-calendar"></i>
                    </a>
                  </div>
                </div>  
                <?php } ?>
              </div> 

              <div class="card-game-button <?php echo !empty($gameSchedules) ? 'mt-xl-auto' : '';?>">
                <?php if( !empty($gameSchedules)) { ?>
                <a href="<?php echo site_url('player-game-schedule');?>" class="btn btn-sm btn-primary">View All</a>
                <?php } else { ?>
                <!--<a href="javascript:void(0)" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#gameschedule">Add Game</a>-->
                <?php } ?>
              </div>  
            </div>
          </div>
        </div>

        <div class="row gutters-10">
          <div class="col-lg-6">
            <div class="section-title">
              <h3 class="title-xs">Academics</h3>
            </div>

            <div class="card-academics">
              <ul class="list-unstyled">
                <li>
                  <div class="profile-list-detail">
                    <i class="icon icon-gpa"></i>
                  </div>
                  <h6 class="text-slate"></h6>
                  <p class="text-uppercase">GPA</p>
                </li>

                <li>
                  <div class="profile-list-detail">
                    <i class="icon icon-act"></i>
                  </div>
                  <h6 class="text-slate"></h6>
                  <p class="text-uppercase">ACT</p>
                </li>

                <li>
                  <div class="profile-list-detail">
                    <i class="icon icon-sat"></i>
                  </div>
                  <h6 class="text-slate"></h6>
                  <p class="text-uppercase">SAT</p>
                </li>
              </ul>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="section-title">
              <h3 class="title-xs">Awards and Accolades</h3>
            </div>

            <?php
                //$athleteAwards = $wpdb->get_results("SELECT * FROM `wp_cb_athlete_award` where `user_id` = $userId");
                $athleteAwards = array();
            ?>
            <div class="card-awards <?php echo empty($athleteAwards) ? 'center-empty' : '';?>"> 
               <?php
                foreach ($athleteAwards as $awardkey => $athleteAward) { 
              ?>
              <div class="card-awards-list">
                <div class="card-awards-image">
                  <i class="icon icon-awards"></i>
                </div>

                <div class="card-awards-content">
                  <p><?php echo $athleteAward->league_name;?></p>
                  <h5><?php echo $athleteAward->star_name;?></h5>
                  <h6><?php echo $athleteAward->team_title;?></h6>
                </div>

                <div class="card-awards-action">
                  <a href="<?php echo add_query_arg( array('award-id'=>$athleteAward->id, 'action' => 'award-remove'), get_permalink());?>" onclick="return confirm('Are you want to Delete This Award!');">
                    <i class="icon icon-delete"></i>
                  </a>
                </div>
              </div>
              <?php } ?>
              <div class="text-center">
                <!--<a href="javascript:void(0);" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#newAwardModal" >Add New</a>-->
              </div>
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
                    /*$galleryImagesData = get_user_meta( $userId, 'gallery-pic', true );

                    if( !empty($galleryImagesData) ) {
                        $galleryImages = json_decode($galleryImagesData);
                    }*/
                    foreach ($galleryImages as $imageKey => $attachmentId) {
                        $isGalleryImage = true;
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

   <!-- New Aard Modal -->
    <div class="modal fade" id="newAwardModal" tabindex="-1" aria-labelledby="playerRatingModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title mx-auto">Add Award</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <i class="icon icon-close"></i>
            </button>
          </div> 
          <div class="modal-body">  
            <form class="ajax-form" autocomplete="off" method="post">
              <div class="form-group">
                <div class="form-input">
                  <label class="form-label">League Name</label>
                  <input name="league-name" class="form-control" value="" required/>
                  <i class="icon icon-team"></i>
                </div>
              </div> 
              <div class="form-group">
                <div class="form-input">
                  <label class="form-label">Star Name</label>
                  <input name="star-name" class="form-control" value="" required/>
                  <i class="icon icon-team"></i>
                </div>
              </div> 
              <div class="form-group">
                <div class="form-input">
                  <label class="form-label">Team Title</label>
                  <input name="team-title" class="form-control" value="" required/>
                  <i class="icon icon-team"></i>
                </div>
              </div>
              <div class="form-group text-center mb-0">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                <span class="py-2 d-inline-block"></span>  
                <input type="hidden" name="action" value="add-award-of-athlete">
                <button type="submit" class="btn btn-sm btn-primary">Save</button>
              </div>
            </form>
          </div>        
        </div>
      </div>
    </div>
    <!-- End New Award Modal-->

    <!-- Modal Add Game Schedule-->
    <div class="modal fade" id="gameschedule" tabindex="-1" aria-labelledby="gamescheduleLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title mx-auto">Add Game Schedule</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <i class="icon icon-close"></i>
            </button>
          </div>
          <div class="modal-body">
            <div class="gameschedule-form">
              <form class="ajax-form" method="post" enctype="multipart/form-data" autocomplete="off">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <div class="custom-file-upload text-center">
                        <input type="file" name="gameScheduleImage">
                        <img src="<?php echo $image_path; ?>/image-upload03.png" class="img-fluid" alt="">
                        <p class="mt-2">Upload Team Logo</p>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <div class="form-input">
                        <label class="form-label">Your Team Name</label>
                        <input type="text" class="form-control" name="yourteam" required>
                        <i class="icon icon-team"></i>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <div class="form-input">
                        <label class="form-label">Opponent Team Name</label>
                        <input type="text" class="form-control" name="opponentteam" required>
                        <i class="icon icon-team"></i>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <div class="form-input">
                        <label class="form-label">Date</label>
                        <input type="text" class="form-control datepicker" name="gamedate" required>
                        <i class="icon icon-sm-calendar"></i>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <div class="form-input">
                        <label class="form-label">Location</label>
                        <input type="text" class="form-control" name="gamelocation" required>
                        <i class="icon icon-map-address"></i>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-12">
                    <div class="form-group text-center mt-4">
                      <input type="hidden" name="action" value="add-game-schedule">
                      <button type="submit" class="btn btn-sm btn-xl btn-primary">Create Game Schedule</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Modal Add Game -->
<?php get_footer('admin'); ?>