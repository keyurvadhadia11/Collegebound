<?php
/**
 * Template Name: Coach Dashboard
 */
global $wpdb;
$userId = get_current_user_id();
$assetsPath = get_template_directory_uri().'/assets/';
$image_path = $assetsPath.'images';

// Is user login, then redirect to dashboard
if ( !is_user_logged_in() && !current_user_can('administrator') ) {
  wp_redirect ( site_url('login') );
  exit;
}

// Saved Prospect Counter
$saved_prospects_count = $wpdb->get_var("SELECT COUNT(*) FROM `wp_cb_saved_prospects` WHERE `user_id` = $userId");

// Upcoming Event Counter
$tournament_query =  "SELECT * FROM `wp_cb_tournament` where 1=1 AND `status` = 'active' ";   
$tournament_query .= " AND `end` > '".date('Y-m-d')." 00:00:00'"; 
$upcoming_event_counts = $wpdb->get_results( $tournament_query );
$ij =0;

foreach ($upcoming_event_counts as $key => $tournament) {
  $operator_stripe_user_id = get_user_meta( $tournament->user_id, 'stripe_user_id', true );
  if( !empty($operator_stripe_user_id) ){
      $ij++;
  }
}
$upcoming_event_count = $ij;
// All Event Counter
$i =0;
$all_tournaments = $wpdb->get_results("SELECT * FROM `wp_cb_tournament` where 1=1 AND `status` = 'active'");
foreach ($all_tournaments as $key => $tournament) {
  $operator_stripe_user_id = get_user_meta( $tournament->user_id, 'stripe_user_id', true );
  if( !empty($operator_stripe_user_id) ){
      $i++;
  }
}
$all_event_count = $i;
//All team Couter
//$all_team_count = $wpdb->get_var("SELECT COUNT(*) FROM `wp_cb_tournament` JOIN `wp_cb_team` ON `wp_cb_tournament`.`id` = `wp_cb_team`.`tournament_id`");
$all_team_count = 0;
$all_team_array = $wpdb->get_results("SELECT wp_cb_tournament.id, wp_cb_tournament.user_id FROM `wp_cb_tournament` JOIN `wp_cb_team` ON `wp_cb_tournament`.`id` = `wp_cb_team`.`tournament_id`");
foreach ($all_team_array as $key => $team) {
  $operator_stripe_user_id = get_user_meta( $team->user_id, 'stripe_user_id', true );
  if( !empty($operator_stripe_user_id) ){
      $all_team_count++;
  }
}
get_header('admin');
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
                <p>Saved <br/>Prospects</p>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card-dash">
              <div class="card-dash-image">
                <i class="icon icon-xl-tournament"></i>
              </div>

              <div class="card-dash-content">
                <h4><?php echo $upcoming_event_count;?></h4>
                <p>Upcoming <br/>Tournaments</p>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card-dash">
              <div class="card-dash-image">
                <i class="icon icon-teams"></i>
              </div>

              <div class="card-dash-content">
                <h4><?php echo $all_team_count;?></h4>
                <p>Total <br/>Teams</p>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="card-dash">
              <div class="card-dash-image">
                <i class="icon icon-xl-events"></i>
              </div>

              <div class="card-dash-content">
                <h4><?php echo $all_event_count; ?></h4>
                <p>Total <br/>Events</p>
              </div>
            </div>
          </div>
        </div>

        <div class="row gutters-10">
          <div class="col-xl-8">
            <div class="section-title">
              <h3 class="title-xs">Saved Prospects</h3>
            </div>

            <div class="nav-graduates-tabs">
              <ul class="nav nav-pills nav-graduates-pills nav-justified" id="pills-graduates-tab" role="tablist">
                <?php
                  $graduation_year = date('Y')-1;
                  // To View Previous 3 Year Graduation
                  for($i=1; $i<=7; $i++){
                    $graduation_year++;
                ?>
                <li class="nav-item" role="presentation">
                  <a class="nav-link <?php echo ($i==1)? 'active':'';?>" id="pills-graduates<?php echo $graduation_year;?>-tab" data-toggle="pill" href="#pills-graduates<?php echo $graduation_year;?>" role="tab" aria-controls="pills-graduates<?php echo $graduation_year;?>" aria-selected="true"><?php echo $graduation_year;?></a>
                </li>
                <?php } // End For ?>
              </ul>

              <div class="tab-content" id="pills-tabContent">
                 <?php
                  $graduation_year = date('Y')-1;
                  for($i=1; $i<=7; $i++){
                    $graduation_year++;
                ?>
                <div class="tab-pane fade show <?php echo ($i==1)? 'active':'';?>" id="pills-graduates<?php echo $graduation_year;?>" role="tabpanel" aria-labelledby="pills-graduates<?php echo $graduation_year;?>-tab">
                  <div class="row row-equal gutters-10">
                    <?php
                      $positionList = array(
                        "PG" => "Point Guard",
                        "SG" => "Shooting Guard",
                        "SF" => "Small Forward",
                        "PF" => "Power Forward",
                        "C" => "Center",
                      );

                      $query = "SELECT * FROM `wp_cb_saved_prospects` as prospect JOIN `wp_cb_team_athletes` as athelete ON `prospect`.`member_id` = `athelete`.`id` WHERE `prospect`.`member_type`= 'player' AND `athelete`.`graduation_year` = '$graduation_year' AND `prospect`.`user_id` = $userId";
                      //var_dump($query);
                      $save_prospects = $wpdb->get_results($query);

                      foreach ($save_prospects  as $key => $save_prospect ) { 
                    ?>
                    <div class="col-xl-6 col-lg-12">
                      <div class="card-graduates">
                        <div class="card-graduates-image">
                           <?php 
                            $dpImageUrl = getUiAvtarUrl( $save_prospect->name );

                            if( isset($save_prospect->email) )
                            {
                              $player_user = get_user_by( 'email', $save_prospect->email );

                              if( !empty($player_user) )
                              {
                                $attachment_id = get_user_meta( $player_user->ID, 'profile-pic', true);
                                if( $attachment_id  && file_exists(get_attached_file($attachment_id)) )
                                $dpImage = wp_get_attachment_image_src( $attachment_id, 'thumbnail');
                              } 
                            }
                            if($dpImage)
                                $dpImageUrl = $dpImage[0];
                          ?> 
                          <img src="<?php echo $dpImageUrl ; ?>" class="img-fluid" alt="">
                        </div> 
                        <div class="card-graduates-content"> 
                          <?php 
                          /*if( isset($save_prospect->email) ){
                            $player_user = get_user_by( 'email', $save_prospect->email );
                            if( !empty($player_user) ){
                              echo '<a href="'.add_query_arg(array('user_id'=>$player_user->ID),site_url('player-profile-view')).'">'.$save_prospect->name.'</a>';
                            } else {
                              echo $save_prospect->name;
                            }
                          } else {
                            echo $save_prospect->name;
                          }*/
                          $profile_view_url = add_query_arg(array('member_id'=>$save_prospect->member_id),site_url('player-member-view'));
                          if( isset($save_prospect->email) ){
                            $player_user = get_user_by( 'email', $save_prospect->email );
                            if( !empty($player_user) ){
                              $profile_view_url = add_query_arg(array('user_id'=>$player_user->ID),site_url('player-profile-view'));
                            }
                          }
                        ?> 
                          <a href="<?php echo $profile_view_url;?>"><h4><?php echo $save_prospect->name;?></h4></a>
                          </h4>
                          <ul class="list-unstyled">
                            <li><i class="icon icon-sm-forward"></i><?php echo isset($positionList[$save_prospect->position]) ? $positionList[$save_prospect->position] : '';?></li>
                            <li><i class="icon icon-sm-school"></i> <?php echo $save_prospect->school_name;?></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <?php } // End Foreach ?>
                  </div>
                </div>
                <?php } // End For ?>
              </div>
            </div>
          </div>


          <div class="col-xl-4 col-md-6">
            <div class="section-title">
              <h3 class="title-xs">University/College</h3>
            </div>

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
              </div>

              <div class="card-college-content">
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
                </div>

                <h4><?php echo get_user_meta( $userId, 'universitycollege', true);?></h4>
                <p><?php echo get_user_meta( $userId, 'university_description', true);?></p>
              </div>
            </div>
          </div>
        </div>

        <div class="row gutters-10">
          <div class="col-xl-8">
            <div class="section-title d-sm-flex align-items-center justify-content-between">
              <h3 class="title-xs">Upcoming Tournaments</h3>

              <a href="<?php echo site_url('tournament-packets');?>" class="text-primary text-underline">View All</a>
            </div>
            <?php   
              $tournament_query =  "SELECT * FROM `wp_cb_tournament` where 1=1 AND `status` = 'active' ";   
              $tournament_query .= " AND `end` > '".date('Y-m-d')." 00:00:00'"; 
              $upcoming_tournaments = $wpdb->get_results( $tournament_query."LIMIT 2");
            ?>
            <div class="card-tournament-wrap"> 
              <?php
                if( empty($upcoming_tournaments) ){
                  echo 'No Upcoming Tournaments';
                }
                foreach ($upcoming_tournaments as $key => $tournament) {
              ?>
              <div class="card-tournament">
                <div class="card-tournament-image">
                  <?php 
                    $image_url = $image_path."/war_on_the_wood.jpg";
                    if( !empty($tournament->logo) ){
                        $attachment = wp_get_attachment_image_src( $tournament->logo, 'full');
                        if( $attachment )
                        $image_url = $attachment[0];
                    }
                  ?> 
                  <img src="<?php echo $image_url;?>" class="img-fluid" alt="" width="274px" height="242px">
                </div>

                <div class="card-tournament-content">
                  <div class="section-title">
                    <h3 class="title-sm text-black"><?php echo $tournament->name;?></h3>
                  </div>
                  <?php
                        $tournament_date = '';
                        $startDate = new DateTime($tournament->start);
                        $endDate = new DateTime($tournament->end); 
                        $start_d = $startDate->format('d'); 
                        $end_d   = $endDate->format('d');
                        $start_m = $startDate->format('F'); 
                        $end_m   = $endDate->format('');
                        $start_y = $startDate->format('Y'); 
                        $end_y   = $endDate->format('Y');

                        if( $start_y != $end_y ){
                            $tournament_date = $startDate->format('F d, Y').' - '.$endDate->format('F d, Y');
                        }
                        elseif( $start_m != $end_m ){
                            $tournament_date = $start_m.' '.$start_d.' - '.$end_m.' '.$end_d.', '.$start_y;
                        } else {
                             $tournament_date = $start_m.' '.$start_d.'-'.$end_d.', '.$start_y;
                        }
                    ?>
                  <p class="card-tournament-date"><i class="icon icon-calendar"></i> <?php echo $tournament_date;?></p>
                  <p class="card-tournament-date"><i class="icon icon-map-address"></i> <?php echo $tournament->address;?></p>

                  <p class="text-dark small font-weight-bold my-3">Teams</p>

                  <div class="row gutters-12">
                      <?php 
                      $teamQuery = "SELECT * FROM `wp_cb_team` where `tournament_id` = $tournament->id";
                      $teamList = array();
                      $counter = 0;
                      $teamList = $wpdb->get_results( $teamQuery ); 
                      foreach ($teamList as $teamKey => $team) {
                        if( $counter == 0)
                          echo '<ul class="col-sm-6 team-list-li">';
                      ?>
                        <li><?php echo $team->name;?></li>
                      <?php  
                        $counter++;
                        if( $counter == 12){
                          echo '</ul>'; 
                          $counter = 0;
                        }
                      }
                    ?>
                    </div>
                 <!--  <ul class="list-unstyled card-tournament-team">
                    <?php 
                      $teamQuery = "SELECT * FROM `wp_cb_team` where `tournament_id` = $tournament->id LIMIT 5";
                      $teamList = array();
                      $counter = 0;
                      $teamList = $wpdb->get_results( $teamQuery ); 
                      foreach ($teamList as $teamKey => $team) { 
                      ?>
                        <li><img src="<?php echo $image_path;?>/team0<?php echo (($teamKey%3)+1)?>.png" class="img-fluid" alt="" /></li>
                      <?php  
                        $counter++;
                        if( $counter == 8){
                          $counter = 0;
                        }
                      }
                    ?>  -->
                  </ul>
                  <a class="btn btn-sm btn-primary" href="<?php echo add_query_arg('tournament-id', $tournament->id, site_url('packet-team-list'));?>">View Details</a> 
                </div>
              </div>
              <?php } ?>
            </div> 
          </div>

          <div class="col-xl-4">
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
<?php get_footer('admin'); ?>