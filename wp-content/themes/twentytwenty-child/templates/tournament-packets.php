<?php
/**
 * Template Name: Tournament Packets
 */

session_start();
global $wpdb;

$userId = get_current_user_id();

// Is user login, then redirect to dashboard
if ( !is_user_logged_in() && !current_user_can('administrator') ) {
  wp_redirect ( site_url('login') );
  exit;
}

if( ! (current_user_can('eventOperator') || current_user_can('coach') || current_user_can('administrator')) ) {
   wp_die('You have not allow to access this page');
}

// Remove Tournament
if( $_REQUEST['action'] == 'remove') {

  $tournament_results = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."cb_tournament` where `id`=".$_GET['tournament-id']);
  if( isset($tournament_results[0]) ){
      $tournament = $tournament_results[0];

       // Remove Old Data
      cb_remove_import_data($tournament->id);

      $wpdb->query( "DELETE FROM `".$wpdb->prefix."cb_tournament` where `id` = ".$tournament->id );

       $_SESSION['alert'] = array('status' => 'success' , 'content' => "Tournament Delete Successfully" );
        wp_redirect(site_url('tournament-packets'));  
        die(); 

  } else {
    $_SESSION['alert'] = array('status' => 'danger' , 'content' => "Tournament Not Found" );
    wp_redirect(site_url('tournament-packets'));  
    die(); 
  }  
} 



if ( current_user_can('administrator') ) { 
  $tournament_query =  "SELECT * FROM `wp_cb_tournament` where 1=1 ";

  if( isset($_GET['eventOperator']) && !empty($_GET['eventOperator']) ){
    $tournament_query .= " AND `user_id` = ".$_GET['eventOperator'];
  } 
} else { 
  if( current_user_can('eventOperator') ) {
    $tournament_query =  "SELECT * FROM `wp_cb_tournament` where `user_id` = $userId ";
  } else {  
    $tournament_query =  "SELECT * FROM `wp_cb_tournament` where 1=1  AND `status` = 'active' ";
  }
}

if( !empty($_REQUEST['search']) ) { 
    $tournament_query .= " AND (`name` LIKE '%".$_REQUEST['search']."%' OR `address` LIKE '%".$_REQUEST['search']."%')";  
} 

if( isset($_GET['tournament']) && !empty($_GET['tournament']) ){
    if( $_GET['tournament'] == 'past'){
        $tournament_query .= " AND `end` < '".date('Y-m-d')." 23:59:59'";
    } 
    elseif( $_GET['tournament'] == 'upcoming'){ 
       $tournament_query .= " AND `end` > '".date('Y-m-d')." 00:00:00'";
    } 
}

$all_tournaments = $wpdb->get_results( $tournament_query);

$image_path = get_template_directory_uri().'/assets/images';
$streaming_code = array();

get_header('admin');
?>

   <!-- COntent -->
      <div class="container-fluid">
        <div class="row gutters-10">
          <div class="col-lg-12">
             
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

            <?php 
              $tournament_head = 'All';
              if( $_GET['tournament'] == 'upcoming'){
                $tournament_head = 'Upcoming';
              } elseif( $_GET['tournament'] == 'past'){
                $tournament_head = 'Past';
              }
            ?>
            <div class="section-title d-sm-flex align-items-center justify-content-between">
              <h3 class="title-xs"><?php echo $tournament_head;?> Events</h3>
            </div>
            <?php 
                
                $op_stripe_user_id = get_user_meta( $userId, 'stripe_user_id', true );
                if( empty($op_stripe_user_id) && current_user_can('eventOperator') ){
                    ?>
                    <div class="alert alert-warning mt-2">
                        Please add your stripe account details in Setting -> Payment Setting screen to display events to Coaches
                    </div>
                    <?php
                }
            ?>

            <?php if( (current_user_can('administrator') || current_user_can('eventOperator')) ) { ?>
            <div class="my-3 position-relative"> 
              <div class="form-group" style="width:100px; float:right;">
                <div class="dropdown dropdown-light">
                  <button class="btn btn-sm btn-block btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span>
                     <?php
                      $Tournament = 'upcoming';
                      if( !empty($_GET['tournament']) ){
                        $Tournament = $_GET['tournament'];
                      }
                      echo ucfirst($Tournament);
                    ?>
                  </span>
                  </button>
                  <div class="dropdown-menu" style="">
                    <a class="dropdown-item <?php echo ($_GET['tournament'] == 'all')? 'active' : '';?>" href="<?php echo add_query_arg('tournament', 'all', site_url('tournament-packets'));?>">All</a>
                    <a class="dropdown-item <?php echo ($_GET['tournament'] == '' || $_GET['tournament'] == 'upcoming')? 'active' : '';?>" href="<?php echo add_query_arg('tournament', 'upcoming', site_url('tournament-packets'));?>">Upcoming</a>
                    <a class="dropdown-item <?php echo ($_GET['tournament'] == 'past')? 'active' : '';?>" href="<?php echo add_query_arg('tournament', 'past', site_url('tournament-packets'));?>">Past</a>
                  </div>
                </div>
              </div>

              <!-- All Event Table Section Start -->
              <table id="table-all-event" class="table table-striped w-100">
                <thead>
                  <tr>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>D1</th>
                    <th>D2</th>
                    <th>D3</th>
                    <th>JUCO</th>
                    <th>NAIA</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th class="text-right">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php  
                    foreach ($all_tournaments as $key => $tournament) :
                  ?>   
                  <tr>
                    <?php
                    $view_event = 'javascript:void(0);';
                    if(  $tournament->status == 'active' || current_user_can('eventOperator') || current_user_can('administrator') ){
                        $view_event = add_query_arg('tournament-id', $tournament->id, site_url('packet-team-list'));
                    }
                    ?>
                    <td><a class="text-shark" href="<?php echo $view_event;?>"><?php echo $tournament->name;?></a></td>
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
                    <td><?php echo $tournament_date; ?></td>
                     <?php
                        $price = array();
                        if( isset($tournament->price) ){
                            $price = (array)json_decode($tournament->price);
                        } 
                        $prices_list = array( 
                          'division_1' => 'Division 1',
                          'division_2' => 'Division 2',
                          'division_3' => 'Division 3',
                          'juco'     => 'Juco',
                          'naia'     => 'Naia',
                        );
                        foreach( $prices_list as $price_field => $price_label){
                            if( isset($price[$price_field])){
                                echo '<td>$'.$price[$price_field].'</td>';
                            } else {
                              echo '<td>-</td>';
                            }
                        }
                    ?> 
                    <td>Normal</td>
                    <td>
                      <div class="d-flex align-items-center">
                        <?php if(  $tournament->status == 'active' ){ ?>
                        Active <span class="badge badge-success badge-time ml-1">&nbsp;</span>
                        <?php } else { ?>
                        In Active <span class="badge badge-secondary badge-time ml-1">&nbsp;</span>
                        <?php } ?>
                      </div>
                    </td>
                    <td class="text-right">
                      <div class="d-flex align-items-center justify-content-end">

                        <?php if( current_user_can('eventOperator') || current_user_can('administrator') ) {?>
                        <a class="action-edit" href="<?php echo add_query_arg(array('action'=>'edit','id'=>$tournament->id), site_url('manage-tournament'));?>"><i class="icon icon-edit"></i></a>
                        <a href="<?php echo add_query_arg( array('tournament-id'=>$tournament->id, 'action' => 'remove'), get_permalink());?>" class="action-delete" onclick="return confirm('Are you want to Delete This Tournament!');"><i class="icon icon-delete"></i></a>
                        <?php } ?>

                        <?php
                            if( !empty($tournament->streaming_code) ) {
                            $streaming_code[$tournament->id] = stripslashes($tournament->streaming_code);
                        ?>
                             <a href="javascript:void(0);"  tournament-id="<?php echo $tournament->id;?>" class="btn btn-sm btn-outline-primary watch-streaming-btn" style="display: none;">Watch Live Match</a>
                        <?php } ?>

                        <!--
                        <?php if( $tournament->status == 'active') {?>
                            <a href="<?php echo add_query_arg('tournament-id', $tournament->id, site_url('packet'));?>">See Packet</a> 
                        <?php } else { ?>

                        <?php if( current_user_can('coach') && ( $startDate->format('m') > date('m') || $startDate->format('Y') > date('Y') ) ) { ?>
                        <a class="not_available" href="javascript:void(0);">Available in <?php echo ($startDate->format('m') > date('m')) ? $startDate->format('F') : $startDate->format('F, Y');?></a>
                        <?php } else {?>
                        <a class="not_available" href="javascript:void(0);">See Packet</a> 
                        <?php } } ?>-->

                      </div>
                    </td>
                  </tr> 
                  <?php endforeach; ?>
                </tbody>
              </table>
              <!-- End All Event Table Section -->
            </div>  
            <?php } else { ?>
            <div class="coach-events">
              <div class="form-search-box">
                <form autocomplete="off">
                  <div class="row gutters-10">
                    <div class="col-lg-10">
                      <div class="form-group form-search">
                        <i class="icon icon-search"></i>
                        <input type="text" class="form-control bg-white" name="search" placeholder="Search" value="<?php echo $_GET['search'];?>">
                      </div>
                    </div>
                    <div class="col-lg-2">
                      <div class="form-group">
                        <div class="dropdown dropdown-light">
                          <button class="btn btn-sm btn-block btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span>
                             <?php
                              $Tournament = 'all';
                              if( !empty($_GET['tournament']) ){
                                $Tournament = $_GET['tournament'];
                              } 
                              echo ucfirst($Tournament);
                            ?>
                          </span>
                          </button>
                          <div class="dropdown-menu" style="">
                            <a class="dropdown-item <?php echo ($_GET['tournament'] == '' || $_GET['tournament'] == 'all')? 'active' : '';?>" href="<?php echo add_query_arg('tournament', 'all', add_query_arg());?>">All</a>
                            <a class="dropdown-item <?php echo ($_GET['tournament'] == 'upcoming')? 'active' : '';?>" href="<?php echo add_query_arg('tournament', 'upcoming', add_query_arg());?>">Upcoming</a>
                            <a class="dropdown-item <?php echo ($_GET['tournament'] == 'past')? 'active' : '';?>" href="<?php echo add_query_arg('tournament', 'past', add_query_arg());?>">Past</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>    
                </form>
              </div>

              <div class="d-flex flex-wrap justify-content-between"> 
                <?php 
                  foreach ($all_tournaments as $key => $tournament) {
                      $operator_stripe_user_id = get_user_meta( $tournament->user_id, 'stripe_user_id', true );
                      if( !empty($operator_stripe_user_id) ){
                ?>
                <div class="card-tournament card-tournament-full">
                    <?php 
                    $image_url = $image_path."/war_on_the_wood.jpg";
                    $image_url = "https://ui-avatars.com/api/?background=random&format=svg&name=".$tournament->name;
                    if( !empty($tournament->logo) && file_exists(get_attached_file($tournament->logo)) ){
                        $attachment = wp_get_attachment_image_src( $tournament->logo, 'full');
                        if( $attachment )
                        $image_url = $attachment[0];
                    }
                  ?> 
                  <div class="card-tournament-image">
                    <img src="<?php echo $image_url;?>" class="img-fluid" alt="" width="274px" height="242px" >
                  </div> 

                  <div class="card-tournament-content">
                    <div class="section-title">
                      <h3 class="title-sm text-black"><?php echo $tournament->name;?></h3>
                    </div>
                    <?php
                        $tournament_date = '';
                        $startDate = new DateTime($tournament->start);
                        
                        $newstartDate = date("F j, Y", strtotime($tournament->start) );
                        $endDate = new DateTime($tournament->end); 
                        $start_d = $startDate->format('d'); 
                        $end_d   = $endDate->format('d');
                        $start_m = $startDate->format('F'); 
                        $end_m   = $endDate->format('');
                        $start_y = $startDate->format('Y'); 
                        $end_y   = $endDate->format('Y');
                        $tournament_date = $startDate->format('M d, Y').' - '.$endDate->format('M d, Y');
                        /*if( $start_y != $end_y ){
                            $tournament_date = $startDate->format('F d, Y').' - '.$endDate->format('F d, Y');
                        }
                        elseif( $start_m != $end_m ){
                            $tournament_date = $start_m.' '.$start_d.' - '.$end_m.' '.$end_d.', '.$start_y;
                        } else {
                             $tournament_date = $start_m.' '.$start_d.'-'.$end_d.', '.$start_y;
                        }*/
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
                     
                  </div>

                  <?php
                  $event_prices = [];
                  if( isset($tournament->price) ){
                      $event_prices = (array)json_decode($tournament->price);
                  }
                  /* Event Order - Start */
                  $trnid = $tournament->id;
                  $orders_query = "SELECT * FROM ".$wpdb->prefix."cb_orders WHERE event_id = '".$trnid."' AND user_id = '".$userId."' ";
                  $my_orders = $wpdb->get_results( $orders_query);

                  $my_price_array = [];
                  if(!empty($my_orders)){
                    foreach ($my_orders as $key => $orders) {
                      
                      $priceArray = json_decode($orders->price_array);
                      $my_price_array = array_merge($my_price_array, $priceArray);
                      
                    }
                  }

                  $is_buy_button_display = 'yes';
                  $my_price_array = array_unique($my_price_array);
                  foreach ($event_prices as $key => $evt_price) {
                    if(in_array($evt_price, $my_price_array)){
                      $is_buy_button_display = 'no';
                    } else {
                      $is_buy_button_display = 'yes';
                      break;
                    }
                  }
                  /* Event Order - End */
                  ?>
                  
                <?php
                    $date_now = new DateTime();
                        
                    $startDate = new DateTime($tournament->start);
                    $endDate = new DateTime($tournament->end); 
                    $startd = strtotime($tournament->start);
                    $endd = strtotime($tournament->end);
                    
                    $nowd = strtotime("now");
                    $is_event_expire = '';
                    
                    if ($nowd > $endd) {
                       $is_event_expire = 'yes';
                    } else {
                        $is_event_expire = 'no';
                    }
                    
                ?>

                    <div class="card-tournament-button">
                        <?php 
                        if( $startDate->format('Y') > date('Y') && $startDate->format('m') > date('m') ) { ?>
                            <a class="btn btn-sm btn-primary not_available" href="javascript:void(0);">Available in <?php echo ($startDate->format('m') > date('m')) ? $startDate->format('F') : $startDate->format('F, Y');?></a>
                        <?php 
                        } else {
                            if( $tournament->status == 'active') {?>
                                <a class="btn btn-sm btn-primary" href="<?php echo add_query_arg('tournament-id', $tournament->id, site_url('packet-team-list'));?>">See Packet</a> 
                                <div class="py-2"></div>
    
                                <?php //&& $is_event_expire == 'yes'
                                    if(true || $is_event_expire == 'no' ){
                                        if($is_buy_button_display == 'yes' ){ ?>
                                            <a href="<?php echo add_query_arg('trnid', base64_encode($tournament->id), site_url('tournament-buy'));?>" class="btn btn-sm btn-outline-primary">Buy Packet</a>
                                        <?php } else { ?>
                                            <a href="javascript:;" class="btn btn-sm btn-outline-primary">Already Purchased</a>
                                        
                                        <?php } 
                                    }
                                ?>

                                <?php
                                    if( !empty($tournament->streaming_code) ) {
                                    $streaming_code[$tournament->id] = stripslashes($tournament->streaming_code);
                                ?>
                                     <a href="javascript:void(0);"  tournament-id="<?php echo $tournament->id;?>" class="btn btn-sm btn-outline-primary watch-streaming-btn">Watch Live Match</a>
                                <?php } ?>
                            
                            <?php } else { ?> 
                                <a class="btn btn-sm btn-primary not_available" href="javascript:void(0);">Not Available</a> 
                            <?php } ?> 
                        <?php } ?> 
                    </div>
                  
                </div>
                <?php } } ?>
              </div>
            </div>
            <?php } ?>
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