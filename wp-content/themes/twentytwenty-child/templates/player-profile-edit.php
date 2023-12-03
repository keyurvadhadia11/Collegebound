<?php
/**
 * Template Name: Player Profile Edit
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

$adminEdit = false;
// Only Admin Can Edit Athlete Profile
if( current_user_can('administrator') && !empty($_GET['user_id']) ) {
  $userData = get_userdata( $_GET['user_id'] );
  if( !empty($userData) ) {
    $userId = $userData->ID;
    $user_info = $userData;
    $adminEdit = true;
  }
}

// Remove Award
if( isset($_REQUEST['action']) && $_REQUEST['action'] == 'award-remove') {

  $award_results = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."cb_athlete_award` where `id`='".$_GET['award-id']."' AND `user_id` = '$userId'");
  if( isset($award_results[0]) ){
      $award_result = $award_results[0];
 
      $wpdb->query( "DELETE FROM `".$wpdb->prefix."cb_athlete_award` where `id` = ".$award_result->id );

       $_SESSION['alert'] = array('status' => 'success' , 'content' => "Award Delete Successfully" );
  } else {
    $_SESSION['alert'] = array('status' => 'danger' , 'content' => "Award Not Found" );
  }
  wp_redirect(site_url('player-profile'));  
  die();   
} 


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
                    $bannerImageUrl =  $image_path.'/banner08.jpg';
                    $banner_attachment_id = get_user_meta( $userId, 'banner-pic', true);
                    if( $banner_attachment_id )
                    $bannerImage = wp_get_attachment_image_src( $banner_attachment_id, $size = 'full');
                    if( $bannerImage && file_exists(get_attached_file($banner_attachment_id)) )
                        $bannerImageUrl = $bannerImage[0];
                 ?>
                <img src="<?php echo $bannerImageUrl;?>" class="img-fluid d-block w-100" /> 
                <a href="javascript:void(0);" class="action-edit banner-pic-edit" <?php echo current_user_can('administrator') ? 'style="display:none"' : '';?>><i class="icon icon-edit"></i></a>
                <input type="file" name="bannerPic" id="banner-pic" accept="image/jpeg, image/png" data-type='image' style="opacity:0;">
              </div>

              <div class="card-profile-content">
                <div class="card-profile-image">
                  <?php
                    $dpImageUrl =  $image_path.'/dp03.png';
                    $dpImageUrl = getUiAvtarUrl( get_user_meta( $userId, 'first_name', true).'+'.get_user_meta( $userId, 'last_name', true) );
                    $attachment_id = get_user_meta( $userId, 'profile-pic', true);
                    if( $attachment_id  && file_exists(get_attached_file($attachment_id)) )
                    $dpImage = wp_get_attachment_image_src( $attachment_id, $size = 'thumbnail'); //var_dump($dpImage);
                    if($dpImage)
                        $dpImageUrl = $dpImage[0];
                  ?>
                  <img src="<?php echo $dpImageUrl; ?>" class="img-fluid" alt="">
                  <a href="javascript:void(0);" class="action-edit profile-pic-edit" <?php echo current_user_can('administrator') ? 'style="display:none"' : '';?>><i class="icon icon-edit"></i></a>
                  <input type="file" name="profilePic" id="profile-pic" accept="image/jpeg, image/png" data-type='image' style="opacity:0;">
                </div>

                <h5><?php echo get_user_meta( $userId, 'first_name', true); ?> <?php echo get_user_meta( $userId, 'last_name', true); ?></h5>

                <p class="text-slate font-weight-bold">No. <?php echo get_user_meta( $userId, 'player_jersey_number', true); ?></p>

                <div class="card-profile-detail">
                  <ul class="list-unstyled">
                    <li>
                      <div class="profile-list-detail">
                        <i class="icon icon-lg-height"></i>
                      </div>
                      <h6 class="text-primary text-underline"><?php echo get_user_meta( $userId, 'height_feet', true );?>'<?php echo get_user_meta( $userId, 'height_inch', true );?>"</h6>
                      <p>Height</p>
                    </li>

                    <li>
                      <?php
                      $position = '';
                      $userPosition = get_user_meta( $userId, 'player_postion', true);
                      if( !empty($userPosition) ) {
                        $position = trim($userPosition,'"');
                      }
                      ?>
                      <div class="profile-list-detail">
                        <i class="icon icon-act icon-<?php echo strtolower(strtok($position, " "));?>"></i>
                      </div>
                      <h6 class="text-slate"><?php echo ucfirst($position); ?></h6>
                      <p>Position</p>
                    </li>

                    <li>
                      <div class="profile-list-detail">
                        <i class="icon icon-grade"></i>
                      </div>
                      <h6 class="text-primary text-underline"><?php echo get_user_meta( $userId, 'player_grade', true );?>-</h6>
                      <p>Graduation Year</p>
                    </li>
                  </ul>
                </div>
              </div>

              <div class="card-profile-form">
                <form class="ajax-form" autocomplete="off" method="post">
                  <div class="row gutters-10">
                    <div class="col-lg-12">
                      <div class="text-center mb-5">
                        <input type="hidden" name="action" value="player-profile-edit">
                        <?php if($adminEdit) : ?>
                          <input type="hidden" name="user_id" value="<?php echo $userId;?>">
                        <?php endif;?>
                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-input">
                        <label class="form-label active">First Name</label>
                        <input type="text" class="form-control" name="first_name" value="<?php echo get_user_meta( $userId, 'first_name', true); ?>" required>
                        <i class="icon icon-user"></i>
                      </div>

                      <div class="form-input">
                        <label class="form-label active">Last Name</label>
                        <input type="text" class="form-control" name="last_name" value="<?php echo get_user_meta( $userId, 'last_name', true); ?>" required>
                        <i class="icon icon-user"></i>
                      </div>

                      <div class="form-input">
                        <label class="form-label active">Email ID</label>
                        <input type="email" class="form-control" name="user_email" value="<?php echo $user_info->user_email;?>" required>
                        <i class="icon icon-mail"></i>
                      </div>

                      <div class="form-input">
                        <label class="form-label active">Height Feet</label>
                        <select class="form-control" name="height_feet">
                          <option value=""></option>
                          <?php 
                          $height_feet = get_user_meta( $userId, 'height_feet', true);

                          for( $feet = 4; $feet <= 6; $feet++){ ?>
                            <?php
                              $selected = '';
                              if( !empty($height_feet) && ($feet == $height_feet) ){
                                $selected = 'selected="selected"';
                              }
                            ?>
                            <option value="<?php echo $feet;?>" <?php echo $selected;?> ><?php echo $feet;?></option>
                          <?php } ?> 
                        </select>
                        <i class="icon icon-height-list"></i>
                      </div>

                      <div class="form-input">
                        <label class="form-label active">Height Inch</label>
                        <select class="form-control" name="height_inch">
                          <option value=""></option>
                          <?php 
                          $height_inch = get_user_meta( $userId, 'height_inch', true);
                          for( $inch = 0; $inch <= 11; $inch++){ ?>
                            <?php
                             $selected = '';
                              if( !empty($height_inch) && ($inch == $height_inch) ){
                                $selected = 'selected="selected"';
                              }
                            ?>
                            <option value="<?php echo $inch;?>" <?php echo $selected;?> ><?php echo $inch;?></option>
                          <?php } ?>
                        </select>
                        <i class="icon icon-height-list"></i>
                      </div>

                      <div class="form-input">
                        <label class="form-label active">GPA</label>
                        <select class="form-control" name="player_gpa" required>
                          <option value=""></option>
                          <?php
                            $player_gpa = get_user_meta( $userId, 'player_gpa', true);

                            $gpaLists = array(
                              "0.0",
                              "1.0",
                              "1.3",
                              "1.7",
                              "2.0",
                              "2.3",
                              "2.7",
                              "3.0",
                              "3.3",
                              "3.7",
                              "4.0",
                            ); 
                          ?>
                          <?php foreach( $gpaLists as $gpakey => $gpa ) { ?>
                            <?php
                             $selected = '';
                              if( !empty($player_gpa) && ($gpa == $player_gpa) ){
                                $selected = 'selected="selected"';
                              }
                            ?>
                            <option value="<?php echo $gpa;?>" <?php echo $selected;?> ><?php echo $gpa;?></option>
                          <?php } ?> 
                        </select>
                        <i class="icon icon-gpa-list"></i>
                      </div>

                      <div class="form-input">
                        <label class="form-label active">SAT</label>
                        <input type="text" class="form-control" name="player_sat" value="<?php echo get_user_meta( $userId, 'player_sat', true); ?>" required>
                        <i class="icon icon-sat-list"></i>
                      </div>

                      <div class="form-input">
                        <label class="form-label active">Jersey Number</label>
                        <input type="number" class="form-control" min="0" name="player_jersey_number" value="<?php echo get_user_meta( $userId, 'player_jersey_number', true); ?>" required>
                        <i class="icon icon-jersey-list"></i>
                      </div>

                    </div>

                    <div class="col-lg-6">
                      <div class="form-input">
                        <label class="form-label active">High School</label>
                        <input type="text" class="form-control" name="universitycollege" value="<?php echo get_user_meta( $userId, 'universitycollege', true);?>" >
                        <i class="icon icon-school-list"></i>
                      </div>

                      <div class="form-input">
                        <label class="form-label active">Hometown</label>
                        <input type="text" class="form-control" name="address" value="<?php echo get_user_meta( $userId, 'address', true); ?>">
                        <i class="icon icon-address"></i>
                      </div>

                      <div class="form-input">
                        <label class="form-label active">Club Program</label>
                        <input type="text" class="form-control" name="coachtitle" value="<?php echo get_user_meta( $userId, 'coachtitle', true); ?>">
                        <i class="icon icon-coach"></i>
                      </div>

                      <div class="form-input">
                        <label class="form-label active">Position</label>
                        <select class="form-control" name="player_postion" required>
                          <option value=""></option>
                          <?php
                            $player_postion = get_user_meta( $userId, 'player_postion', true);

                            $positions = array(
                              "Point Guard",
                              "Shooting Guard",
                              "Small Forward",
                              "Power Forward",
                              "Center",
                            ); 
                          ?>
                          <?php foreach( $positions as $poskey => $position ) { ?>
                            <?php
                             $selected = '';
                              if( !empty($player_postion) && ($position == $player_postion) ){
                                $selected = 'selected="selected"';
                              }
                            ?>
                            <option value="<?php echo $position;?>" <?php echo $selected;?> ><?php echo $position;?></option>
                          <?php } ?> 
                        </select>
                        <i class="icon icon-position-list"></i>
                      </div>

                      <div class="form-input">
                        <label class="form-label active">Graduation Year</label>
                        <input type="text" class="form-control" name="player_grade" value="<?php echo get_user_meta( $userId, 'player_grade', true); ?>">
                        <i class="icon icon-grade-list"></i>
                      </div>

                      <div class="form-input">
                        <label class="form-label active">ACT</label>
                        <input type="text" class="form-control" name="player_act" value="<?php echo get_user_meta( $userId, 'player_act', true); ?>" required>
                        <i class="icon icon-act-list"></i>
                      </div>

                       <div class="form-input">
                        <label class="form-label active">State</label>
                        <select class="form-control" name="player_state" required>
                          <option value=""></option>
                          <?php
                            $player_state = get_user_meta( $userId, 'player_state', true);

                            $states = array(
                                "AL" => "Alabama",
                                "AK" => "Alaska",
                                "AZ" => "Arizona",
                                "AR" => "Arkansas",
                                "CA" => "California",
                                "CO" => "Colorado",
                                "CT" => "Connecticut",
                                "DE" => "Delaware",
                                "FL" => "Florida",
                                "GA" => "Georgia",
                                "HI" => "Hawaii",
                                "ID" => "Idaho",
                                "IL" => "Illinois",
                                "IN" => "Indiana",
                                "IA" => "Iowa",
                                "KS" => "Kansas",
                                "KY" => "Kentucky",
                                "LA" => "Louisiana",
                                "ME" => "Maine",
                                "MD" => "Maryland",
                                "MA" => "Massachusetts",
                                "MI" => "Michigan",
                                "MN" => "Minnesota",
                                "MS" => "Mississippi",
                                "MO" => "Missouri",
                                "MT" => "Montana",
                                "NE" => "Nebraska",
                                "NV" => "Nevada",
                                "NH" => "New Hampshire",
                                "NJ" => "New Jersey",
                                "NM" => "New Mexico",
                                "NY" => "New York",
                                "NC" => "North Carolina",
                                "ND" => "North Dakota",
                                "OH" => "Ohio",
                                "OK" => "Oklahoma",
                                "OR" => "Oregon",
                                "PA" => "Pennsylvania",
                                "RI" => "Rhode Island",
                                "SC" => "South Carolina",
                                "SD" => "South Dakota",
                                "TN" => "Tennessee",
                                "TX" => "Texas",
                                "UT" => "Utah",
                                "VT" => "Vermont",
                                "VA" => "Virginia",
                                "WA" => "Washington",
                                "WV" => "West Virginia",
                                "WI" => "Wisconsin",
                                "WY" => "Wyoming" 
                            ); 
                          ?>
                          <?php foreach( $states as $statekey => $state ) { ?>
                            <?php
                             $selected = '';
                              if( !empty($player_state) && ($player_state == $statekey) ){
                                $selected = 'selected="selected"';
                              }
                            ?>
                            <option value="<?php echo $statekey;?>" <?php echo $selected;?> ><?php echo $state;?></option>
                          <?php } ?> 
                        </select>
                        <i class="icon icon-state-list"></i>
                      </div> 
                    </div> 
                    
                    <?php if( current_user_can('administrator') ): ?>
                    <div class="col-lg-12">
                      <div class="form-input">
                        <label class="form-label">College Bound Evaluation</label>
                        <textarea class="form-control" name="player_evaluation" style="height: 100px;"><?php echo get_user_meta( $userId, 'player_evaluation', true);?></textarea>
                      </div>
                    </div>
                    <?php endif;?>

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

              <!-- Repeat -->
              <div>
                <?php
                $gameSchedules = $wpdb->get_results("SELECT * FROM `wp_cb_player_game_schedule` WHERE `user_id` = $userId AND `game_date` >= '".date('Y-m-d')."' ORDER BY id DESC LIMIT 6");
                 
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
                    <p><?php echo date('d F Y h:i A', strtotime($gameSchedule->game_date
                    ));?></p>
                  </div>

                  <div class="card-game-list-action">
                    <a href="javascript:void(0);" class="text-primary" data-toggle="tooltip" data-placement="right" title="<?php echo date('d F Y h:i A', strtotime($gameSchedule->game_date
                    ));?>" >
                      <i class="icon icon-active-calendar"></i>
                    </a>
                  </div>
                </div>  
                <?php } ?>
              </div>
              <!-- Repeat -->

              <div class="card-game-button <?php echo !empty($gameSchedules) ? 'mt-xl-auto' : '';?>" <?php echo current_user_can('administrator') ? 'style="display:none"' : '';?>>
                <?php if( !empty($gameSchedules)) { ?>
                <a href="<?php echo site_url('player-game-schedule');?>" class="btn btn-sm btn-primary">View All</a>
                <?php } else { ?>
                <a href="javascript:void(0)" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#gameschedule">Add Game</a>
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
                  <h6 class="text-slate"><?php echo get_user_meta( $userId, 'player_gpa', true );?></h6>
                  <p class="text-uppercase">GPA</p>
                </li>

                <li>
                  <div class="profile-list-detail">
                    <i class="icon icon-act"></i>
                  </div>
                  <h6 class="text-slate"><?php echo get_user_meta( $userId, 'player_act', true );?></h6>
                  <p class="text-uppercase">ACT</p>
                </li>

                <li>
                  <div class="profile-list-detail">
                    <i class="icon icon-sat"></i>
                  </div>
                  <h6 class="text-slate"><?php echo get_user_meta( $userId, 'player_sat', true );?></h6>
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
                $athleteAwards = $wpdb->get_results("SELECT * FROM `wp_cb_athlete_award` where `user_id` = $userId"); 
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
                   <?php echo !empty($athleteAward->year) ? '<h6>'.$athleteAward->year.'</h6>' : '';?>
                </div>

                <div class="card-awards-action" <?php echo current_user_can('administrator') ? 'style="display:none"' : '';?> >
                  <a href="<?php echo add_query_arg( array('award-id'=>$athleteAward->id, 'action' => 'award-remove'), get_permalink());?>" onclick="return confirm('Are you want to Delete This Award!');">
                    <i class="icon icon-delete"></i>
                  </a>
                </div>
              </div>
              <?php } ?>
              <div class="text-center" <?php echo current_user_can('administrator') ? 'style="display:none"' : '';?>>
                <a href="javascript:void(0);" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#newAwardModal" >Add New</a>
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
                <div class="col-xl-4 col-lg-6 col-md-6" <?php echo current_user_can('administrator') ? 'style="display:none"' : '';?>>
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

    <!-- New Aard Modal -->
    <div class="modal fade" id="newAwardModal" tabindex="-1" aria-labelledby="playerRatingModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title mx-auto">Add Award And Accolade</h5>
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
                        <input type="text" class="form-control scheduleGameTime" name="gamedate" required>
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