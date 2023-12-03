<?php
/**
 * Template Name: Packet Team
 */ 
session_start();
global $wpdb;
$userId = get_current_user_id();

// Is user login, then redirect to dashboard
if ( !is_user_logged_in() && !current_user_can('administrator') ) {
  wp_redirect ( site_url('login') );
  exit;
}

$teamId = $_GET['team-id'];

$teamDetail = $wpdb->get_results("SELECT * FROM `wp_cb_team` where `id` = $teamId");

if( !isset($teamDetail[0]) ){
  wp_die('Team Not Found');
}

$tournamentDetails = $wpdb->get_results("SELECT * FROM `wp_cb_tournament` where `id` = ".$teamDetail[0]->tournament_id);

if( !isset($tournamentDetails[0]) ){
  wp_die('Tournament Packet Not Found');
} 

 $positions = array(
              "PG" => "Point Guard",
              "SG" => "Shooting Guard",
              "SF" => "Small Forward",
              "PF" => "Power Forward",
              "Center"=> "Center",
            ); 

$image_path = get_template_directory_uri().'/assets/images';
get_header('admin');
?>  

    <!-- COntent -->
    <div class="container-fluid">
        <div class="row gutters-10">
          <div class="col-xl-12">

            <div class="message-content">
              <?php 
              if(isset($_SESSION['alert']) && !empty($_SESSION['alert'])) : ?>
              <div class="alert alert-<?php echo $_SESSION['alert']['status']; ?> alert-dismissible">
                <?php echo $_SESSION['alert']['content']; ?>
                 <button type="button" class="close" data-dismiss="alert">&times;</button>
              </div>
              <?php unset($_SESSION['alert']); ?>
              <?php endif; ?> 
            </div> 


            <div class="section-title">
              <h3 class="title-xs">Event Packets Details</h3>
            </div>
            
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#"><?php echo $tournamentDetails[0]->name;?></a></li>
              <li class="breadcrumb-item"><a href="<?php echo add_query_arg( 'tournament-id', $teamDetail[0]->tournament_id, site_url('packet-team-list') ); ?>"><?php echo $teamDetail[0]->name;?> Team</a></li>
              <li class="breadcrumb-item active" aria-current="page">Team Detail</li>
            </ol>

            <div class="card-team-list-wrap">
              <!-- Team Banner -->
              <div class="card-team-banner card-team-banner-tabs">
                <div class="card-team-banner-image">
                  <img src="<?php echo $image_path;?>/team07.png" class="img-fluid" alt="">
                </div>

                <div class="card-team-banner-content">
                  <h4><?php echo $teamDetail[0]->name;?></h4> 
                  <div>
                    <div class="card-team-banner-rec">
                      <img src="<?php echo $image_path;?>/image-date.png" class="img-fluid" alt=""> 
                      <div>
                         <?php
                            $tournament_date = '';
                            $startDate = new DateTime($tournamentDetails[0]->start);
                            $endDate = new DateTime($tournamentDetails[0]->end); 
                            $start_d = $startDate->format('d'); 
                            $end_d   = $endDate->format('d');
                            $start_m = $startDate->format('M'); 
                            $end_m   = $endDate->format('M');
                            $start_y = $startDate->format('Y'); 
                            $end_y   = $endDate->format('Y');

                            if( $start_y != $end_y ){
                                $tournament_date = $startDate->format('M d, Y').' - '.$endDate->format('M d, Y');
                            }
                            elseif( $start_m != $end_m ){
                                $tournament_date = $start_m.' '.$start_d.' - '.$end_m.' '.$end_d.', '.$start_y;
                            } else {
                                 $tournament_date = $start_m.' '.$start_d.'-'.$end_d.', '.$start_y;
                            }
                        ?>
                        <p>Dates</p>
                        <h5><?php echo $tournament_date; ?></h5>
                      </div>
                    </div> 
                    <div class="card-team-banner-rec">
                      <img src="<?php echo $image_path;?>/image-location.png" class="img-fluid" alt=""> 
                      <div>
                        <p>Location</p>
                        <h5><?php echo $tournamentDetails[0]->address;?></h5>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Tabs Player & Coaches -->
              <div class="card-team-tabs-wrap">
                <ul class="nav nav-pills nav-graduates-pills nav-justified" id="pills-graduates-tab" role="tablist">
                  <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="tab-nav-player" data-toggle="pill" href="#nav-player" role="tab" aria-controls="pills-nav-player" aria-selected="false">Player</a>
                  </li>
                  <li class="nav-item" role="presentation">
                    <a class="nav-link" id="tab-nav-coaches" data-toggle="pill" href="#nav-coaches" role="tab" aria-controls="pills-nav-coaches" aria-selected="true">Coaches</a>
                  </li>
                </ul>

                <div class="tab-content" id="nav-packets-tabContent">
                    <?php
                      $reviews = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."review_rating` WHERE user_id = ".$userId);
                    ?>
                    <!-- Player Tab Content Start -->
                    <div class="tab-pane fade show active" id="nav-player" role="tabpanel" aria-labelledby="nav-player-tab">
                      <?php
                        $teamList = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."cb_team_athletes` where team_id = ".$teamDetail[0]->id);

                        foreach ($teamList as $teamkey => $team) { 
                        ?>  
                          <div id="member-player-<?php echo $team->id;?>" class="card-team-tabs">
                            <div class="card-team-tabs-image">
                              <?php
                                $dpImageUrl =  $image_path.'/user05.png';
                                 $dpImageUrl = getUiAvtarUrl( $team->name );
                                $userTeam = get_user_by( 'email', $team->email );
                                if( isset($userTeam->ID) )
                                {
                                  $attachment_id = get_user_meta( $userTeam->ID, 'profile-pic', true);
                                  if( $attachment_id )
                                    $dpImage = wp_get_attachment_image_src( $attachment_id, $size = 'thumbnail');
                                  if($dpImage)
                                    $dpImageUrl = $dpImage[0];
                                }
                              ?>
                              <img src="<?php echo $dpImageUrl; ?>" class="img-fluid" alt="">
                            </div> 
                            <div class="card-team-tabs-content">
                              <div class="d-flex justify-content-between">
                                <h6 class="member-name"><?php echo $team->name;?></h6>
                                <div class="card-team-action dropdown">
                                  <?php
                                    $saved_prospects = $wpdb->get_var( "SELECT COUNT(*) FROM ".$wpdb->prefix."cb_saved_prospects WHERE `member_type` = 'player' AND `member_id` = '".$team->id."'" );
                                  ?>
                                  <a class="save-prospect <?php echo ($saved_prospects > 0) ? 'card-action-like' : '';?>" href="javascript:void(0);" member-id="<?php echo $team->id;?>" member-type="player" >
                                    <i class="icon icon-like"></i>
                                  </a>

                                  <a class="card-action-more" class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="icon icon-dots"></i>
                                  </a>
                                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a member-id="<?php echo $team->id;?>" member-type="player" class="rating-to-team dropdown-item" href="javascript:void(0);"><i class="icon icon-note"></i> Add Note</a>
                                    <a member-id="<?php echo $team->id;?>" member-type="player" class="rating-to-team dropdown-item" href="javascript:void(0);"><i class="icon icon-sm-basketball"></i> Give Ratings</a>
                                  </div>
                                </div>
                              </div>

                              <div class="row gutters-10 member-details">
                                <div class="col-xl-6 col-lg-12">
                                  <ul class="list-unstyled">
                                  	<?php echo (isset($team->jersey_number) && !empty($team->jersey_number)) ? '<li>No. '.$team->jersey_number.'</li>' : '';?>
                                    <li><i class="icon icon-sm-address"></i> <?php echo $team->address;?></li>
                                    <li><i class="icon icon-position"></i><?php echo $positions[$team->position];?></li>
                                    <li><i class="icon icon-height"></i> Ht: <?php echo $team->height;?></li>
                                    <li><i class="icon icon-sm-grade"></i> Grad Yr.: <?php echo $team->graduation_year;?></li>
                                  </ul>
                                </div>
                                <div class="col-xl-6 col-lg-12">
                                  <ul class="list-unstyled">
                                    <li><i class="icon icon-light-school"></i> School: <?php echo $team->school_name;?></li>
                                    <li><i class="icon icon-bithday"></i> Birthday: <?php echo $team->birth_year;?></li>
                                    <li><i class="icon icon-email"></i><a href="mailto:<?php echo $team->email;?>"><?php echo $team->email;?></a></li>
                                    <li><i class="icon icon-sm-phone"></i><a href="tel:<?php echo $team->phone;?>"><?php echo $team->phone;?></a></li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                          </div> 
                        <?php } ?> 
                    </div>
                    <!-- End Player Tab Content -->

                    <!-- Coach Tab Content Start -->
                    <div class="tab-pane fade" id="nav-coaches" role="tabpanel" aria-labelledby="nav-coaches-tab">
                      <div class="row gutters-10">
                        <?php
                          $coachList = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."cb_team_coaches` where team_id = ".$teamDetail[0]->id);

                          foreach ($coachList as $coachkey => $coach) { 
                          ?>

                          <div class="col-xl-6">
                            <div id="member-coach-<?php echo $coach->id;?>" class="card-team-tabs <?php echo ($coachkey % 3 == 0) ? 'bg-light-yellow' : 'bg-light-sky';?>">
                              <div class="card-team-tabs-image">
                                <?php
                                $dpImageUrl =  $image_path.'/user05.png';
                                $dpImageUrl = getUiAvtarUrl( $coach->name );
                                $userCoach = get_user_by( 'email', $coach->email );
                                if( isset($userCoach->ID) )
                                {
                                  $attachment_id = get_user_meta( $userCoach->ID, 'profile-pic', true);
                                  if( $attachment_id )
                                    $dpImage = wp_get_attachment_image_src( $attachment_id, $size = 'thumbnail');
                                  if($dpImage)
                                    $dpImageUrl = $dpImage[0];
                                }
                              ?>
                              <img src="<?php echo $dpImageUrl; ?>" class="img-fluid" alt="">
                              </div>

                              <div class="card-team-tabs-content">
                                <div class="d-flex justify-content-between">
                                  <h6 class="member-name"><?php echo $coach->name;?></h6>

                                  <div class="card-team-action dropdown">
                                    <?php
                                      $saved_prospects = $wpdb->get_var( "SELECT COUNT(*) FROM ".$wpdb->prefix."cb_saved_prospects WHERE `member_type` = 'coach' AND `member_id` = '".$coach->id."'" );
                                    ?>
                                    <a class="save-prospect <?php echo ($saved_prospects > 0) ? 'card-action-like' : '';?>" href="javascript:void(0);" member-id="<?php echo $coach->id;?>" member-type="coach">
                                      <i class="icon icon-like"></i>
                                    </a>

                                    <a class="card-action-more" class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="icon icon-dots"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                      <a member-id="<?php echo $coach->id;?>" member-type="coach" class="rating-to-team dropdown-item" href="javascript:void(0);"><i class="icon icon-note"></i> Add Note</a>
                                      <a member-id="<?php echo $coach->id;?>" member-type="coach" class="rating-to-team dropdown-item" href="javascript:void(0);"><i class="icon icon-sm-basketball"></i> Give Ratings</a>
                                    </div>
                                  </div>
                                </div>

                                <div class="row gutters-10 member-details">
                                  <div class="col-lg-12">
                                    <ul class="list-unstyled">
                                      <li><i class="icon icon-sm-address"></i> <?php echo $coach->address;?></li>
                                      <li><i class="icon icon-approved"></i> Approval No.: <?php echo $coach->approval_number;?></li>
                                      <li><i class="icon icon-height"></i> BBCS Approval: Yes</li>
                                      <li><i class="icon icon-email"></i><a href="mailto:<?php echo $coach->email;?>"><?php echo $coach->email;?></a></li>
                                      <li><i class="icon icon-sm-phone"></i><a href="tel:<?php echo $coach->phone;?>"><?php echo $coach->phone;?></a></li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div> 
                      <?php } ?>
                        
                      </div>
                    </div>
                      <!-- End Coach Tab Content -->

                </div>
              </div>
            </div>
          </div>
        </div>
    </div>

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
                    <input type="hidden" name="action" value="team-member-review">
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