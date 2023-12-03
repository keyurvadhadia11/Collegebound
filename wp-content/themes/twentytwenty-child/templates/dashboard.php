<?php
/**
 * Template Name: Admin Dashboard
 */

// Is user login, then redirect to dashboard
if ( !is_user_logged_in() && !current_user_can('administrator') ) {
  wp_redirect ( site_url('login') );
  exit;
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
        wp_redirect(site_url('admin-dashboard'));  
        die(); 

  } else {
    $_SESSION['alert'] = array('status' => 'danger' , 'content' => "Tournament Not Found" );
    wp_redirect(site_url('admin-dashboard'));  
    die(); 
  }  
}
// End Remove Tournament

$userId = get_current_user_id();

if ( current_user_can('administrator') ) { 
  $tournament_query =  "SELECT * FROM `wp_cb_tournament` where 1=1 ";

  if( isset($_GET['eventOperator']) && !empty($_GET['eventOperator']) ){
    $tournament_query .= " AND `user_id` = ".$_GET['eventOperator'];
  } 
} else { 
  if( current_user_can('eventOperator') ) {
    $tournament_query =  "SELECT * FROM `wp_cb_tournament` where `user_id` = $userId ";
  } else {  
    $tournament_query =  "SELECT * FROM `wp_cb_tournament` where 1=1 ";
  }
} 
$tournament_query .= " ORDER BY id DESC LIMIT 10;";

//var_dump($tournament_query);

//$upcoming_tournaments = $wpdb->get_results( $tournament_query."LIMIT $start_from, $limit");
$all_tournaments = $wpdb->get_results( $tournament_query);
$thismonth ='';
if( isset( $_GET['m'] ) && !empty($_GET['m']) ){
    $thismonth = $_GET['m'];
}

// Counter for Active & In-Active Events
$active_event_count = 0;
$inactive_event_count = 0;
if( current_user_can('eventOperator') ) {
  $active_event_count = $wpdb->get_var("SELECT COUNT(*) FROM `wp_cb_tournament` WHERE `user_id` = $userId AND `status` = 'active'");  
  $inactive_event_count = $wpdb->get_var("SELECT COUNT(*) FROM `wp_cb_tournament` WHERE `user_id` = $userId AND `status` != 'active'");
} else {
  $active_event_count = $wpdb->get_var("SELECT COUNT(*) FROM `wp_cb_tournament` WHERE `status` = 'active'");  
  $inactive_event_count = $wpdb->get_var("SELECT COUNT(*) FROM `wp_cb_tournament` WHERE `status` != 'active'");
}

$table_name = $wpdb->prefix . "cb_orders";
if(current_user_can('eventOperator')){
	$ph_query =  "
		SELECT o.*, o.user_id as order_user_id, ev.name, ev.user_id as optr_user_id, ev.id as evntid FROM $table_name as o 
		LEFT JOIN ".$wpdb->prefix."cb_tournament AS ev ON ev.id = o.event_id
		WHERE ev.user_id = '".$userId."' GROUP by o.id";

} else if(current_user_can('coach')){

	$ph_query =  "
		SELECT o.*, o.user_id as order_user_id, ev.name, ev.user_id as optr_user_id, ev.id as evntid FROM $table_name as o 
		LEFT JOIN ".$wpdb->prefix."cb_tournament AS ev ON ev.id = o.event_id
		WHERE o.user_id = '".$userId."' ";

} else {
	
	$ph_query =  "
		SELECT o.*, o.user_id as order_user_id, ev.name, ev.user_id as optr_user_id, ev.id as evntid FROM $table_name as o 
		LEFT JOIN ".$wpdb->prefix."cb_tournament AS ev ON ev.id = o.event_id";
}

$ph_query .=  " ORDER BY o.created_at DESC";


$all_tournaments = $wpdb->get_results( $ph_query );
    	
get_header('admin');

$assetsPath = get_template_directory_uri().'/assets/';
?>
           
            <!-- COntent -->
            <div class="container-fluid">
               <div class="row gutters-10">
                  <div class="col-xl-3 col-lg-6 col-md-6">
                     <div class="card-dash">
                        <div class="card-dash-image">
                           <i class="icon icon-xl-events"></i>
                        </div>
                        <div class="card-dash-content">
                           <h4><?php echo $active_event_count;?></h4>
                           <p>Total Active <br/>Events</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-3 col-lg-6 col-md-6">
                     <div class="card-dash">
                        <div class="card-dash-image">
                           <i class="icon icon-inactive-event"></i>
                        </div>
                        <div class="card-dash-content">
                           <h4><?php echo $inactive_event_count;?></h4>
                           <p>Total Inactive <br/>Events</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-3 col-lg-6 col-md-6">
                     <div class="card-dash">
                        <div class="card-dash-image">
                           <i class="icon icon-purchase-event"></i>
                        </div>
                        <div class="card-dash-content">
                           <h4><?php echo count($all_tournaments); ?></h4>
                           <p>Total Purchased <br/>Events</p>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row gutters-10">
                  <div class="col-xl-12">
                     <div class="section-title">
                        <h3 class="title-xs">Total Sales</h3>
                     </div>
                     <div class="chart-sales">
                        <div class="section-title mb-4">
                            <div class="row px-2">
                                <div class="col-md-6">
                                    <h4 class="title-sm">Sales statistics</h4><?php $month = date('m'); ?>
                                </div>
                                <div class="col-md-6 text-right">
                                    <input type="hidden" id="thismonth" value="<?php echo $thismonth; ?>" />
                           
                                    <a href="<?php echo add_query_arg('m', base64_encode($month), site_url('admin-dashboard'));?>" class="pull-custom-right btn btn-sm btn-outline-primary">This Month</a>
                                    <?php 
                                        if(!empty($thismonth)){ ?>
                                            <a href="<?php echo site_url('admin-dashboard') ;?>" class="pull-custom-right btn btn-sm btn-primary">Reset</a>
                                        <?php }
                                    ?>
                                </div>
                            </div>
                           
                           
                           
                        </div>
                        <div class="w-100">
                           <canvas id="salescanvas" height="300" width="600"></canvas>
                        </div>
                     </div>
                  </div>
                  <?php /*
                  <div class="col-xl-4 col-md-6">
                     <div class="section-title">
                        <h3 class="title-xs">Payout Destination</h3>
                     </div>
                     <div class="payout-card skin-white">
                        <form autocomplete="off">
                           <div class="row">
                              <div class="col-lg-12">
                                 <div class="form-group mb-0 text-right">
                                    <img src="<?php echo $assetsPath; ?>images/visa.svg" class="img-fluid" alt="">
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <div class="form-group mb-4">
                                    <div class="form-input">
                                       <label class="form-label active inactiv">Cardholder Name</label>
                                       <input type="text" class="form-control" name="cardholdername" value="Keyur Vadhadia" readonly="">
                                       <i class="icon icon-user"></i>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-12">
                                 <div class="form-group mb-4">
                                    <div class="form-input">
                                       <label class="form-label active inactiv">Card Number</label>
                                       <input type="password" class="form-control" name="cardnumber" value="1234 4567 7890 1112" readonly="">
                                       <i class="icon icon-card-security"></i>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group mb-4">
                                    <div class="form-input">
                                       <label class="form-label active inactiv">Expire Date</label>
                                       <input type="text" class="form-control" name="expiredate" value="02/25" readonly="">
                                       <i class="icon icon-light-calendar"></i>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="form-group mb-4">
                                    <div class="form-input">
                                       <label class="form-label active inactiv">CVV</label>
                                       <input type="password" class="form-control" name="cvv" value="123" readonly="">
                                       <i class="icon icon-cvv"></i>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </form>
                     </div>
                     <div class="mt-4 text-center">
                        <a href="#" class="btn btn-sm btn-primary">View More</a>
                     </div>
                  </div>
                  */ ?>
               </div>
               <div class="row gutters-10">
                  <div class="col-lg-12">
                     <div class="section-title d-sm-flex align-items-center justify-content-between">
                        <h3 class="title-xs">Recent Events</h3>
                        <a href="<?php echo site_url('tournament-packets');?>" class="btn btn-sm btn-primary" >See All</a>
                     </div>
                     <div class="mt-3">
                        <table id="table-event" class="table table-striped w-100"> 
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
                  </div>
               </div>
            </div>
        
<?php get_footer('admin'); ?>