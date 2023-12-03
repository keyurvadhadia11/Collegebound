<?php
/**
 * Template Name: Player Dashboard
 */
global $wpdb;
$userId = get_current_user_id();

// Is user login, then redirect to dashboard
if ( !is_user_logged_in() && !current_user_can('administrator') ) {
  wp_redirect ( site_url('login') );
  exit;
}

// Saved Prospect Counter
$saved_prospects_count = $wpdb->get_var("SELECT COUNT(*) FROM `wp_cb_saved_coach` WHERE `user_id` = $userId");

// Game Schedule Counter
$game_schedule_count = $wpdb->get_var("SELECT COUNT(*) FROM `wp_cb_player_game_schedule` WHERE `user_id` = $userId");

$gallery_image_count = 0;
$gallery_video_count = 0;
$galleryImages = array();
$galleryImagesData = get_user_meta( $userId, 'gallery-pic', true );

if( !empty($galleryImagesData) ) {
    $galleryImages = json_decode($galleryImagesData);
}

foreach ($galleryImages as $imageKey => $attachmentId) { 
    if( wp_attachment_is( 'image', $attachmentId ) ){
      $gallery_image_count++;
    }
    if( wp_attachment_is( 'video', $attachmentId ) ) {
      $gallery_video_count++;
    }
}

$image_path = get_template_directory_uri().'/assets/images';
get_header('player');
?> 
    <!-- COntent -->
      <div class="container-fluid">
        <div class="row gutters-10">
          <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card-dash">
              <div class="card-dash-image">
                <i class="icon icon-prospects"></i>
              </div>

              <div class="card-dash-content">
                <h4><?php echo $saved_prospects_count;?></h4>
                <p>Saved <br/>Coaches</p>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card-dash">
              <div class="card-dash-image">
                <i class="icon icon-xl-game"></i>
              </div>

              <div class="card-dash-content">
                <h4><?php echo $game_schedule_count; ?></h4>
                <p>Game <br/>Schedule </p>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card-dash">
              <div class="card-dash-image">
                <i class="icon icon-video"></i>
              </div>

              <div class="card-dash-content">
                <h4><?php echo $gallery_video_count;?></h4>
                <p>Total <br/>Videos </p>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card-dash">
              <div class="card-dash-image">
                <i class="icon icon-photo"></i>
              </div>

              <div class="card-dash-content">
                <h4><?php echo $gallery_image_count; ?></h4>
                <p>Total <br/>Images</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row gutters-10">
          <div class="col-xl-8">
            <div class="section-title">
              <h3 class="title-xs">Saved College Coaches</h3>
            </div>

            <div class="nav-graduates-tabs">
              <div class="tab-content">
                <div class="row row-equal gutters-10">
                  <?php 
                      $coachCounter = 0;
                      $query = "SELECT * FROM `wp_cb_saved_coach` WHERE `user_id` = $userId";
                      //var_dump($query);
                      $save_prospects = $wpdb->get_results($query);

                      foreach ($save_prospects  as $saveCoachkey => $save_prospect ) {

                      if( empty(get_userdata($save_prospect->coach_id)) ){
                        continue;
                      }

                      $coachCounter++;

                      $coach_data = get_userdata($save_prospect->coach_id);

                      $coach_name = get_user_meta( $coach_data->ID, 'first_name', true).' '.get_user_meta( $coach_data->ID, 'last_name', true);

                    ?>
                    <div class="col-xl-6 col-lg-12">
                      <div class="card-graduates <?php echo ($coachCounter % 3 == 0) ? 'bg-light-yellow' : 'bg-light-sky';?>">
                        <div class="card-graduates-image">
                           <?php 
                            $dpImageUrl = getUiAvtarUrl( $coach_name );
 
                            $attachment_id = get_user_meta( $coach_data->ID, 'profile-pic', true);
                            if( $attachment_id  && file_exists(get_attached_file($attachment_id)) ){
                              $dpImage = wp_get_attachment_image_src( $attachment_id, 'thumbnail');
                            }
                            if($dpImage)
                                $dpImageUrl = $dpImage[0];
                          ?> 
                          <img src="<?php echo $dpImageUrl; ?>" class="img-fluid" alt="">
                        </div> 
                        <div class="card-graduates-content"> 
                          <?php
                             $profile_view_url = add_query_arg(array('user_id'=>$coach_data->ID),site_url('coach-profile-view')); 
                          ?>
                          <a href="<?php echo $profile_view_url;?>"><h4><?php echo $coach_name;?></h4></a>
                          <ul class="list-unstyled ul-dash">
                            <li><i class="icon icon-sm-address"></i> <?php echo get_user_meta( $coach_data->ID, 'address', true);?></li>
                            <li><i class="icon icon-sm-school-list"></i> <?php echo get_user_meta( $coach_data->ID, 'universitycollege', true);?></li> 
                          </ul>
                        </div>
                      </div>
                    </div>
                    <?php } // End Foreach ?>
                </div>
              </div>
            </div>

            <!-- All Coaches Section Start -->
            <div class="section-title">
              <h3 class="title-xs">All Coaches</h3>
            </div>

            <div class="nav-graduates-tabs all_coach_box">
              <div class="tab-content">
                <div class="row">
                 <?php
                   $args = array(
                      'role'    => 'coach',
                      'orderby' => 'user_nicename',
                      'order'   => 'ASC'
                  );
                  $coachList = get_users( $args );

                  foreach ($coachList as $coachkey => $coach) {

                  $coach_name =  get_user_meta( $coach->ID, 'first_name', true).' '.get_user_meta( $coach->ID, 'last_name', true);
                  ?>

                  <div class="col-xl-6">
                    <div id="member-coach-<?php echo $coach->id;?>" class="card-team-tabs <?php echo ($coachkey % 3 == 0) ? 'bg-light-yellow' : 'bg-light-sky';?>">
                      <div class="card-team-tabs-image">
                        <?php 
                        $dpImageUrl = getUiAvtarUrl( $coach_name );

                        $attachment_id = get_user_meta( $coach->ID, 'profile-pic', true);
                        if( $attachment_id ){
                          $dpImage = wp_get_attachment_image_src( $attachment_id, $size = 'thumbnail');
                        }

                        if($dpImage)
                          $dpImageUrl = $dpImage[0];
                      ?>
                      <img src="<?php echo $dpImageUrl; ?>" class="img-fluid" alt="">
                      </div>

                      <div class="card-team-tabs-content">
                        <div class="d-flex justify-content-between">
                           <?php
                             $profile_view_url = add_query_arg(array('user_id'=>$coach->ID),site_url('coach-profile-view')); 
                          ?>
                          <h6 class="member-name"><a href="<?php echo $profile_view_url;?>"><?php echo $coach_name;?></a></h6>

                          <div class="card-team-action dropdown">
                            <?php
                              $saved_prospects = $wpdb->get_var( "SELECT COUNT(*) FROM ".$wpdb->prefix."cb_saved_coach WHERE `user_id` = '$userId' AND `coach_id` = '".$coach->ID."'" );
                            ?>
                            <a class="save-coach <?php echo ($saved_prospects > 0) ? 'card-action-like' : '';?>" href="javascript:void(0);" coach-id="<?php echo $coach->ID;?>" member-type="coach">
                              <i class="icon icon-like"></i>
                            </a>

                            <a class="card-action-more" class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="icon icon-dots"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                              <a coach-id="<?php echo $coach->ID;?>" member-type="coach" class="rating-to-coach dropdown-item" href="javascript:void(0);"><i class="icon icon-note"></i> Add Note</a>
                              <a coach-id="<?php echo $coach->id;?>" member-type="coach" class="rating-to-coach dropdown-item" href="javascript:void(0);"><i class="icon icon-sm-basketball"></i> Give Ratings</a>
                            </div>
                          </div>
                        </div>

                        <div class="row gutters-10 member-details">
                          <div class="col-lg-12">
                            <ul class="list-unstyled ul-dash"> 
                              <li>
                                  <i class="icon icon-email"></i>
                                  <span class="list_dash">
                                      <a href="mailto:<?php echo $coach->user_email;?>"><?php echo $coach->user_email;?></a>
                                  </span>
                              </li>
                              <li>
                                  <i class="icon icon-sm-address"></i>
                                  <span class="list_dash">
                                    <?php echo get_user_meta( $coach->ID, 'address', true);?>
                                  </span>
                              </li>
                              <li>
                                  <i class="icon icon-sm-school-list"></i>
                                  <span class="list_dash">
                                    <?php echo get_user_meta( $coach->ID, 'universitycollege', true);?>
                                  </span>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div> 
              <?php } ?>
                </div>
              </div>
            </div>
            <!-- End All Coaches Section --->
          </div>

          <div class="col-xl-4 col-md-6">
            <div class="section-title">
              <h3 class="title-xs">Customize Your Profile</h3>
            </div>

            <div class="card-customize-wrap">
              <div class="card-customize">
                <a href="javascript:void(0);" id="uploadGallery">
                  <div class="card-customize-image">
                    <img src="<?php echo $image_path;?>/image-upload.png" class="img-fluid" alt=""/>
                  </div> 
                  <div class="card-customize-content text-center">
                    <h4>Upload Videos</h4>
                  </div>
                </a>
                 <input type="file" class="custom-upload" id="galleryFile" name="galleryFile" accept="video/mp4" data-type='image' style="opacity:0; width:0px;"/>
              </div>
              <script type="text/javascript">
              jQuery(document).ready(function(){
                jQuery('#uploadGallery').click(function(){
                  jQuery('#galleryFile').trigger('click');
                })
              })
              </script>

              <div class="card-customize">
                <a href="javascript:void(0);" data-toggle="modal" data-target="#gameschedule">
                  <div class="card-customize-image">
                    <img src="<?php echo $image_path;?>/image-add-games.png" class="img-fluid" alt=""/>
                  </div>

                  <div class="card-customize-content text-center">
                    <h4>Add Games Schedule</h4>
                  </div>
                </a>
              </div>
            </div>

            <div class="section-title">
              <h3 class="title-xs">Recent Activities</h3>
            </div>
            <?php
              $recentActivities = $wpdb->get_results('SELECT * FROM `wp_cb_activity` ORDER BY id DESC LIMIT 10');
            ?>
            <div class="card-activity-wrap">
              <div class="card-activity">
                <ul class="list-unstyled">
                  <?php foreach ($recentActivities as $activitykey => $activity) { ?>
                  <li>
                    <span class="card-activity-circle">
                      <i class="icon icon-bell"></i>
                    </span>
                    <span><?php get_user_meta( $activity->user_id, 'first_name', true);?> <?php echo get_user_meta( $activity->user_id, 'last_name', true);?> <?php echo $activity->note;?>
                  </li> 
                  <?php } ?>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>

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

    <!-- Modal -->
    <div class="modal fade modal-rating" id="playerRatingModal" tabindex="-1" aria-labelledby="playerRatingModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body">

            <div class="modal-rating-wrap">
              <div class="modal-rating-profile">
                <i data-dismiss="modal" class="icon icon-left-sm-arrow d-lg-none d-flex"></i>
                <img id="memberPic" src="images/user07.png" class="img-fluid" alt="">
              </div>

              <div class="modal-rating-content">
                <h4 class="member-name"></h4>

                <div class="row member-details">
                </div>
              </div>

              <div class="modal-rating-form">
                <form class="ajax-form" autocomplete="off" method="post">
                  <div class="form-group">
                    <label class="lead font-weight-bold">Notes</label>
                    <div class="form-textarea">
                      <span class="form-span">Add Note</span>
                      <textarea name="note" class="form-control" placeholder="Add your note here..."></textarea>
                      <i class="icon icon-md-note"></i>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="lead font-weight-bold">Ratings</label>

                    <div class="row">
                      <div class="col-xl-6">
                        <div class="rating-ball-wrap">
                          <p>Shooting</p>
                          <input type="radio" name="rating[shooting]" id="shooting1" value="1">
                          <label for="shooting1"></label>
                          <input type="radio" name="rating[shooting]" id="shooting2" value="2">
                          <label for="shooting2"></label>
                          <input type="radio" name="rating[shooting]" id="shooting3" value="3">
                          <label for="shooting3"></label>
                          <input type="radio" name="rating[shooting]" id="shooting4" value="4">
                          <label for="shooting4"></label>
                          <input type="radio" name="rating[shooting]" id="shooting5" value="5">
                          <label for="shooting5"></label>
                        </div>
                      </div>

                      <div class="col-xl-6">
                        <div class="rating-ball-wrap">
                          <p>Passing</p>
                          <input type="radio" name="rating[passing]" id="passing1" value="1">
                          <label for="passing1"></label>
                          <input type="radio" name="rating[passing]" id="passing2" value="2">
                          <label for="passing2"></label>
                          <input type="radio" name="rating[passing]" id="passing3" value="3">
                          <label for="passing3"></label>
                          <input type="radio" name="rating[passing]" id="passing4" value="4">
                          <label for="passing4"></label>
                          <input type="radio" name="rating[passing]" id="passing5" value="5">
                          <label for="passing5"></label>
                        </div>
                      </div>

                      <div class="col-xl-6">
                        <div class="rating-ball-wrap">
                          <p>Leadership</p>
                          <input type="radio" name="rating[leadership]" id="leadership1" value="1">
                          <label for="leadership1"></label>
                          <input type="radio" name="rating[leadership]" id="leadership2" value="2">
                          <label for="leadership2"></label>
                          <input type="radio" name="rating[leadership]" id="leadership3" value="3">
                          <label for="leadership3"></label>
                          <input type="radio" name="rating[leadership]" id="leadership4" value="4">
                          <label for="leadership4"></label>
                          <input type="radio" name="rating[leadership]" id="leadership5" value="5">
                          <label for="leadership5"></label>
                        </div>
                      </div>

                      <div class="col-xl-6">
                        <div class="rating-ball-wrap">
                          <p>Dribbling</p>
                          <input type="radio" name="rating[dribbling]" id="dribbling1" value="1">
                          <label for="dribbling1"></label>
                          <input type="radio" name="rating[dribbling]" id="dribbling2" value="2">
                          <label for="dribbling2"></label>
                          <input type="radio" name="rating[dribbling]" id="dribbling3" value="3">
                          <label for="dribbling3"></label>
                          <input type="radio" name="rating[dribbling]" id="dribbling4" value="4">
                          <label for="dribbling4"></label>
                          <input type="radio" name="rating[dribbling]" id="dribbling5" value="5">
                          <label for="dribbling5"></label>
                        </div>
                      </div>

                      <div class="col-xl-6">
                        <div class="rating-ball-wrap">
                          <p>Rebounding</p>
                          <input type="radio" name="rating[rebounding]" id="rebounding1" value="1">
                          <label for="rebounding1"></label>
                          <input type="radio" name="rating[rebounding]" id="rebounding2" value="2">
                          <label for="rebounding2"></label>
                          <input type="radio" name="rating[rebounding]" id="rebounding3" value="3">
                          <label for="rebounding3"></label>
                          <input type="radio" name="rating[rebounding]" id="rebounding4" value="4">
                          <label for="rebounding4"></label>
                          <input type="radio" name="rating[rebounding]" id="rebounding5" value="5">
                          <label for="rebounding5"></label>
                        </div>
                      </div>

                      <div class="col-xl-6">
                        <div class="rating-ball-wrap">
                          <p>Attitude</p>
                          <input type="radio" name="rating[attitude]" id="attitude1" value="1">
                          <label for="attitude1"></label>
                          <input type="radio" name="rating[attitude]" id="attitude2" value="2">
                          <label for="attitude2"></label>
                          <input type="radio" name="rating[attitude]" id="attitude3" value="3">
                          <label for="attitude3"></label>
                          <input type="radio" name="rating[attitude]" id="attitude4" value="4">
                          <label for="attitude4"></label>
                          <input type="radio" name="rating[attitude]" id="attitude5" value="5">
                          <label for="attitude5"></label>
                        </div>
                      </div>

                      <div class="col-xl-6">
                        <div class="rating-ball-wrap">
                          <p>Defense</p>
                          <input type="radio" name="rating[defense]" id="defense1" value="1">
                          <label for="defense1"></label>
                          <input type="radio" name="rating[defense]" id="defense2" value="2">
                          <label for="defense2"></label>
                          <input type="radio" name="rating[defense]" id="defense3" value="3">
                          <label for="defense3"></label>
                          <input type="radio" name="rating[defense]" id="defense4" value="4">
                          <label for="defense4"></label>
                          <input type="radio" name="rating[defense]" id="defense5" value="5">
                          <label for="defense5"></label>
                        </div>
                      </div>

                      <div class="col-xl-6">
                        <div class="rating-ball-wrap">
                          <p>Basketball IQ</p>
                          <input type="radio" name="rating[basketballIQ]" id="basketballIQ1" value="1">
                          <label for="basketballIQ1"></label>
                          <input type="radio" name="rating[basketballIQ]" id="basketballIQ2" value="2">
                          <label for="basketballIQ2"></label>
                          <input type="radio" name="rating[basketballIQ]" id="basketballIQ3" value="3">
                          <label for="basketballIQ3"></label>
                          <input type="radio" name="rating[basketballIQ]" id="basketballIQ4" value="4">
                          <label for="basketballIQ4"></label>
                          <input type="radio" name="rating[basketballIQ]" id="basketballIQ5" value="5">
                          <label for="basketballIQ5"></label>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group text-center mb-0">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <span class="py-2 d-inline-block"></span>
                    <input type="hidden" class="member-id" name="member-id" value="">
                    <input type="hidden" class="member-type" name="member-type" value=""> 
                    <input type="hidden" name="action" value="team-coach-review">
                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                  </div>
                </form>
              </div>
            </div>

          </div>        
        </div>
      </div>
    </div>
<?php get_footer('admin'); ?>