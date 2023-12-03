<?php
/**
 * Template Name: Purchase History
 */
session_start();
global $wpdb;
// Is user login, then redirect to dashboard
if ( !is_user_logged_in() && !current_user_can('administrator') ) {
  wp_redirect ( site_url('login') );
  exit;
}

if( ! (current_user_can('eventOperator') || current_user_can('coach') || current_user_can('administrator')) ) {
   wp_die('You have not allow to access this page');
}

$userId = get_current_user_id();
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

$image_path = get_template_directory_uri().'/assets/images';
get_header('admin');
?>
<!-- COntent -->
<div class="container-fluid">
	<div class="row gutters-10">
		<div class="col-lg-12">

			<div class="message-content">
				<?php if(isset($_SESSION['alert']) && !empty($_SESSION['alert'])) : ?>
					<div class="alert alert-<?php echo $_SESSION['alert']['status']; ?> alert-dismissible">
						<?php echo $_SESSION['alert']['content']; ?>
						<button type="button" class="close" data-dismiss="alert">&times;</button>
					</div>
				<?php unset($_SESSION['alert']); ?>
				<?php endif; ?> 
			</div>

			<div class="section-title d-sm-flex align-items-center justify-content-between">
              <h3 class="title-xs">Purchase History</h3>
            </div>

            <div class="my-3 position-relative"> 
            	<div id="table-all-event_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">

				    <!-- All Event Table Section Start -->
		              <table id="table-all-event" class="table table-striped w-100">
		                <thead>
		                  <tr>
		                  	<th>Operator Name</th>
		                    <th>Event Name</th>
		                    <th>Coach Name</th>
		                    <th>D1</th>
		                    <th>D2</th>
		                    <th>D3</th>
		                    <th>JUCO</th>
		                    <th>NAIA</th>
		                    <th>Type</th>
		                    <th>Total</th>
		                    <th>Date</th>
		                  </tr>
		                </thead>
		                <tbody>
		                  <?php  
		                    foreach ($all_tournaments as $key => $tournament) :

		                    	$coach_first_name = get_user_meta( $tournament->order_user_id, 'first_name', true );
		                    	$coach_last_name = get_user_meta( $tournament->order_user_id, 'last_name', true );
		                    	$coach_name = $coach_first_name ."  ". $coach_last_name;

		                    	$operator_first_name = get_user_meta( $tournament->optr_user_id, 'first_name', true );
		                    	$operator_last_name = get_user_meta( $tournament->optr_user_id, 'last_name', true );

		                    	$operator_name = $operator_first_name ."  ". $operator_last_name;

		                  		?>  
		                  		<tr>

		                  			<td><?php echo $operator_name; ?></td>

			                    	<?php
				                    $view_event = 'javascript:void(0);';
				                    $view_event = add_query_arg('tournament-id', $tournament->evntid, site_url('packet-team-list'));
				                    
				                    $view_buy_page = 'javascript:void(0);';

				                    if(  $tournament->status == 'active' || current_user_can('eventOperator') || current_user_can('administrator') ){
				                        //$view_buy_page = add_query_arg('trnid', base64_encode($tournament->event_id), site_url('tournament-buy'));
				                    }
				                    ?>
		                    		<td>
		                    			<a class="text-shark" href="<?php echo $view_event;?>" style="color: #1f97c7 !important;">
		                    				<?php echo $tournament->name;?>
	                    				</a>
		                    		</td>

		                    		<td><?php echo $coach_name; ?></td>

	                     			<?php
				                        $price = array();

				                        if( isset($tournament->price_array) ){
				                            $price = (array)json_decode($tournament->price_array);
				                        } 
				                        $prices_list = array( 
				                          '0' => 'Division 1',
				                          '1' => 'Division 2',
				                          '2' => 'Division 3',
				                          '3'     => 'Juco',
				                          '4'     => 'Naia',
				                        );
				                        foreach( $prices_list as $price_field => $price_label){
				                            if( isset($price[$price_field]) && $price[$price_field] != 0){
				                                echo '<td>$'.$price[$price_field].'</td>';
				                            } else {
				                              echo '<td>-</td>';
				                            }
				                        }
				                    ?> 
				                    <td>Normal</td>

				                    <td>
				                    	<a class="text-shark" href="<?php echo $view_buy_page;?>" style="color: #1f97c7 !important;">
				                    		$<?php echo $tournament->amount; ?>
			                    		</a>
				                    </td>
				                    <td><?php echo $tournament->created_at; ?></td>
			                  	</tr> 
		                  <?php endforeach; ?>
		                </tbody>
		              </table>
		            <!-- End All Event Table Section -->

				</div>
            </div>

		</div>
	</div>
</div>
<?php get_footer('admin'); ?>