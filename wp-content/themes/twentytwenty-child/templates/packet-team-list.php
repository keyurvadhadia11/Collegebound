<?php
/**
 * Template Name: Packet Team List
 */

session_start();
global $wpdb;

// Is user login, then redirect to dashboard
if ( !is_user_logged_in() && !current_user_can('administrator') ) {
  wp_redirect ( site_url('login') );
  exit;
}

$tournamentId = $_GET['tournament-id'];

$tournamentDetails = $wpdb->get_results("SELECT * FROM `wp_cb_tournament` where `id` = $tournamentId");

if( !isset($tournamentDetails[0]) ){
  wp_die('Tournament Packet Not Found');
}


$limit = 10;  // Number of entries to show in a page. 
// Look for a GET variable page if not found default is 1.      
if (isset($_GET["pn"])) {  
  $pn  = $_GET["pn"];  
}  
else {  
  $pn=1;  
};   

$start_from = ($pn-1) * $limit;

$teamQuery = "SELECT * FROM `wp_cb_team` where `tournament_id` = $tournamentId ";


if( !empty($_REQUEST['search']) ) { 
  $teamQuery .= " AND (`name` LIKE '%".$_REQUEST['search']."%' OR `location` LIKE '%".$_REQUEST['search']."%')";  
}

//$teamQuery .= " LIMIT $start_from, $limit"; 

$teamList = $wpdb->get_results( $teamQuery );

//if( !isset($teamList[0]) ){
//  wp_die('Packet Team Not Found');
//}

$image_path = get_template_directory_uri().'/assets/images';
$streaming_code = array();
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
              <h3 class="title-xs">Team List</h3>
            </div>

            <!-- Breadcrumb -->
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="javascript:void(0);"><?php echo $tournamentDetails[0]->name;?></a></li>
              <li class="breadcrumb-item active" aria-current="page">Team</li>
            </ol>

            <div class="card-team-list-wrap">
              <!-- Team Banner -->
              <div class="card-team-banner">
                 <?php  
                    $image_url = $image_path."/war_on_the_wood.jpg";  
                    if( !empty($tournamentDetails[0]->logo) ){  
                        $attachment = wp_get_attachment_image_src( $tournamentDetails[0]->logo, 'full');  
                        if( $attachment ) 
                        $image_url = $attachment[0];  
                    } 
                  ?>  
                <div class="card-team-banner-image">  
                  <img src="<?php echo $image_url;?>" class="img-fluid" alt="" width="274px" height="242px" > 
                </div>

                <div class="card-team-banner-content">
                  <h4><?php echo $tournamentDetails[0]->name;?></h4>

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
                            $tournament_date = $startDate->format('M d, Y').' - '.$endDate->format('M d, Y');
                            /*if( $start_y != $end_y ){
                                $tournament_date = $startDate->format('M d, Y').' - '.$endDate->format('M d, Y');
                            }
                            elseif( $start_m != $end_m ){
                                $tournament_date = $start_m.' '.$start_d.' - '.$end_m.' '.$end_d.', '.$start_y;
                            } else {
                                 $tournament_date = $start_m.' '.$start_d.'-'.$end_d.', '.$start_y;
                            }*/
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
                <?php
                    
                    $endd = strtotime($tournamentDetails[0]->end);
                    $nowd = strtotime("now");
                    $is_event_expire = '';
                    
                    if ($nowd > $endd) {
                       $is_event_expire = 'yes';
                    } else {
                        $is_event_expire = 'no';
                    }
                    
                ?>
                <div class="card-tournament-button w-100">
                    <?php
                      if( !empty($tournamentDetails[0]->streaming_code) ) {
                        $streaming_code[$tournamentDetails[0]->id] = stripslashes($tournamentDetails[0]->streaming_code);
                    ?>
                         <a href="javascript:void(0);"  tournament-id="<?php echo $tournamentDetails[0]->id;?>" class="btn btn-sm btn-outline-primary watch-streaming-btn">Watch Live Match</a>
                    <?php } ?>

                    <?php 
                        if( ( true || $is_event_expire == 'no') &&  current_user_can('coach') ){ ?>
                                <a href="<?php echo add_query_arg('trnid', base64_encode($tournamentDetails[0]->id), site_url('tournament-buy'));?>" class="btn btn-sm btn-outline-primary">Buy Packet</a>
                            <?php } /*else { ?>
                                <a href="javascript:;" class="btn btn-sm btn-outline-primary">Already Purchased</a>
                            
                            <?php  
                        }*/
                    ?>
                </div>
              <!-- Team Search -->
              <div class="section-title">
                <h3 class="title-xs">Search</h3>
              </div>

              <form autocomplete="off">
                <div class="form-group form-search">
                  <i class="icon icon-search"></i>
                  <input type="text" class="form-control" name="search" placeholder="Search Team name" value="<?php echo $_REQUEST['search']; ?>">
                  <button class="btn btn-sm btn-primary">Search</button>
                   <input type="hidden" name="tournament-id" value="<?php echo $tournamentId;?>">
                </div>                
              </form>
                <?php 
                    $userId = get_current_user_id();
                    $orders_query_key = "SELECT * FROM ".$wpdb->prefix."cb_orders WHERE event_id = '".$tournamentId."' AND user_id = '".$userId."' order by id desc limit 1";
                    $my_orders_key = $wpdb->get_row( $orders_query_key);
                    
                ?>
                  <!-- Team Listing -->
                  <div class="section-title">
                    <h3 class="title-xs">Teams</h3>
                  </div>
    
                  <div class="card-team-wrap">
                    <div class="row gutters-12">
                       <?php if( !isset($teamList[0]) ) {
                            echo '<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12"><p style="text-align:center; padding: 100px;">No Record Found</span></div>';
                        } else {?>
                       <?php 
                        foreach ($teamList as $teamKey => $team) { 
                        
                        if(current_user_can('coach')){
                            if( !empty($my_orders_key)){
                                $team_detail_url = add_query_arg('team-id', $team->id, site_url('packet-team'));
                            } else {
                                $team_detail_url = 'javascript:;';
                            }
                        } else {
                            $team_detail_url = add_query_arg('team-id', $team->id, site_url('packet-team'));
                        }
                    ?>
                          <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                            <div class="card-team <?php echo ($teamKey % 2 == 0) ? 'bg-light-yellow' : 'bg-light-sky';?>">
                              <a href="<?php echo $team_detail_url; ?>">
                                <h4><?php echo $team->name;?></h4> 
                                <div class="card-team-image">
                                  <img src="<?php echo $image_path;?>/<?php echo ($teamKey % 2 == 0) ? 'team06.png' : 'team05.png'?>" class="img-fluid" />
                                </div>
    
                                <p><?php echo $team->location;?></p>
                              </a>
                            </div>
                          </div>
                        <?php } }?>  
                    </div>
                  </div>
              
            </div>
          </div>
        </div>
      </div>

      <!-- Watch Streaming Modal Start -->
      <div class="modal fade" id="watchStreamingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Watch Live Match</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div id="streaming_container" class="modal-body">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
            </div>
          </div>
        </div>
      </div>
      <!-- End Watch Streaming Modal -->

      <script type="text/javascript">
        var streaming_code = <?php echo json_encode($streaming_code);?>;
        jQuery(document).ready(function(){
          jQuery('.watch-streaming-btn').click(function()
          {
              jQuery('#streaming_container').html('');

              var tournamentId = jQuery(this).attr('tournament-id'); 
              var streaming_contain = streaming_code[tournamentId];

              if( streaming_contain != '')
              {
                jQuery('#streaming_container').html(streaming_contain);
                jQuery('#watchStreamingModal').modal('show');
              }
          });
        });
      </script>
<?php get_footer('admin'); ?>