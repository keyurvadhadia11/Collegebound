<?php
/**
 * Template Name: Player Game Schedule
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

// Remove Tournament
if( $_REQUEST['action'] == 'remove') {

  $schedule_results = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."cb_player_game_schedule` where `id`=".$_GET['schedule-id']);
  if( isset($schedule_results[0]) ){
      $schedule = $schedule_results[0]; 

      $wpdb->query( "DELETE FROM `".$wpdb->prefix."cb_player_game_schedule` where `id` = ".$schedule->id );

      $_SESSION['alert'] = array('status' => 'success' , 'content' => "Game Schedule Delete Successfully" ); 
  } else {
    $_SESSION['alert'] = array('status' => 'danger' , 'content' => "Game Schedule Not Found" ); 
  } 
  wp_redirect(site_url('player-game-schedule'));  
  die();
}


$image_path = get_template_directory_uri().'/assets/images';

get_header('player');
?> 
      <!-- COntent -->
      <div class="container-fluid">
        <div class="row gutters-10">
          <div class="col-lg-12">
            <div class="section-title d-sm-flex justify-content-between align-items-center">
              <h3 class="title-xs">Game Schedule</h3>

              <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#gameschedule">Add Game</a>
            </div>
          </div>

          <div class="col-lg-12">
            <div class="game-schedule-list">
              <?php
                $gameSchedules = $wpdb->get_results("SELECT * FROM `wp_cb_player_game_schedule` where `user_id` = $userId");
                if( empty($gameSchedules) ) {
                  echo '<div class="row gutters-10"><div class="col col-game-team">No Record</div></div>';
                }
                foreach ($gameSchedules as $gameKey => $gameSchedule) { 
              ?>
              <div class="row gutters-10">
                <div class="col col-game-team">
                  <div class="game-schedule-team">
                    <?php
                      $ImageUrl =  $image_path.'/teamvs.png'; 
                      if( !empty($gameSchedule->game_image ) ){
                        $dpImage = wp_get_attachment_image_src( $gameSchedule->game_image, $size = 'thumbnail');
                        if($dpImage)
                            $ImageUrl = $dpImage[0];
                      }
                    ?>    
                    <img src="<?php echo $ImageUrl; ?>" class="img-fluid" alt="<?php echo $gameSchedule->your_team;?> vs <?php echo $gameSchedule->opponent_team;?>" />
                    <div class="team-name">
                      <p><?php echo $gameSchedule->your_team;?></p>
                      <p class="vs">VS</p>
                      <p><?php echo $gameSchedule->opponent_team;?></p>
                    </div>
                  </div>
                </div>

                <div class="col col-game-place">
                  <div class="game-schedule-place text-center">
                    <i class="icon icon-court"></i>
                    <p><?php echo $gameSchedule->game_location;?></p>
                  </div>
                </div>

                <div class="col col-game-date">
                  <div class="game-schedule-date text-center">
                    <i class="icon icon-md-calendar"></i>
                    <p><?php echo date('d F Y h:i A', strtotime($gameSchedule->game_date));?></p>
                  </div>
                </div>

                <div class="col col-game-time">
                  <div class="game-schedule-time">
                    <?php if( $gameSchedule->game_date == date('Y-m-d')){ ?>
                    <span class="badge badge-success badge-time">&nbsp;</span> Today
                    <?php } elseif( strtotime($gameSchedule->game_date) > strtotime(date('Y-m-d')." 23:59:59") ){ ?>
                    <span class="badge badge-warning badge-time">&nbsp;</span> Upcoming 
                    <?php } else { ?>
                    <span class="badge badge-secondary badge-time">&nbsp;</span> Past 
                    <?php } ?>
                  </div>
                </div> 

                <div class="col col-game-action">
                  <div class="game-schedule-action">
                    <a href="javascript:void(0);" schedule-id="<?php echo $gameSchedule->id;?>" class="action-edit edit-game-schedule"><i class="icon icon-edit"></i></a>
                    <a href="<?php echo add_query_arg( array('schedule-id'=>$gameSchedule->id, 'action' => 'remove'), get_permalink());?>" class="action-delete" onclick="return confirm('Are you want to Delete This Game Schedule!');"><i class="icon icon-delete"></i></a>
                  </div>
                </div>
              </div>

              <?php } ?> 
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

      <!-- Modal Edit Game Schedule-->
      <div class="modal fade" id="edit-gameschedule" tabindex="-1" aria-labelledby="edit-gamescheduleLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title mx-auto">Edit Game Schedule</h5>
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
                        <input type="hidden" name="action" value="edit-game-schedule">
                        <input type="hidden" id="game-schedule-id" name="game-schedule-id" value="">
                        <button type="submit" class="btn btn-sm btn-xl btn-primary">Update Game Schedule</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- End Modal Edit Game -->

<?php get_footer('admin'); ?>