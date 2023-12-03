<?php
/**
 * Template Name: Tournament Buy
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

if(isset($_GET['trnid']) && $_GET['trnid'] != ''){ } else {
  wp_die('You have not allow to access this page');
}

$trnid = base64_decode($_GET['trnid']);

$userId = get_current_user_id();

$orders_query = "SELECT * FROM ".$wpdb->prefix."cb_orders WHERE event_id = '".$trnid."' AND user_id = '".$userId."' ";
$my_orders = $wpdb->get_results( $orders_query);
$orders_query_key = "SELECT * FROM ".$wpdb->prefix."cb_orders WHERE event_id = '".$trnid."' AND user_id = '".$userId."' order by id desc limit 1";
$my_orders_key = $wpdb->get_row( $orders_query_key);
$mykeyArray = json_decode($my_orders_key->keyArray);

$my_price_array = [];
if(!empty($my_orders)){
  foreach ($my_orders as $key => $orders) {
    
    $priceArray = json_decode($orders->price_array);
    $my_price_array = array_merge($my_price_array, $priceArray);
    
  }
}

$my_price_array = array_unique($my_price_array);

$tournament_query =  "SELECT * FROM ".$wpdb->prefix."cb_tournament WHERE id ='".$trnid."' ";
$all_tournaments = $wpdb->get_results( $tournament_query);

if(empty($all_tournaments)){
  //wp_die('You have not allow to access this page');
}

$event_prices = [];

$image_path = get_template_directory_uri().'/assets/images';
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

            <?php $tournament_head = 'Buy'; ?>
            <div class="section-title d-sm-flex align-items-center justify-content-between">
              <h3 class="title-xs"><?php echo $tournament_head;?> Packet</h3>
            </div>

            
            <div class="coach-Packet tournament_buy_main">
              <div class="d-flex flex-wrap justify-content-between"> 
                <?php  foreach ($all_tournaments as $key => $tournament) { ?>
                  <div class="card-tournament card-tournament-full">
                    <?php 

                    $event_prices = [];
                    if( isset($tournament->price) ){
                        $event_prices = (array)json_decode($tournament->price);
                    }

                    $image_url = $image_path."/war_on_the_wood.jpg";
                    if( !empty($tournament->logo) ){
                        $attachment = wp_get_attachment_image_src( $tournament->logo, 'full');
                        if( $attachment )
                        $image_url = $attachment[0];
                    }

                    $tournament_price = json_decode($tournament->price);
                    $buyed_price_array = json_decode($tournament->price_array);
                    
                    ?> 
                    <div class="card-tournament-image mt-5">
                      <img src="<?php echo $image_url;?>" class="img-fluid" alt="" width="274px" height="242px" >
                    </div> 
                    <div class="card-tournament-full">
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
                      <?php 
                        $teamQuery = "SELECT * FROM `wp_cb_team` where `tournament_id` = $tournament->id LIMIT 20";
                        $teamList = array();
                        $counter = 0;
                        $teamList = $wpdb->get_results( $teamQuery ); 
                        foreach ($teamList as $teamKey => $team) {
                          if( $counter == 0)
                            echo '<ul class="list-unstyled card-tournament-team">';
                          ?>
                            <li><img src="<?php echo $image_path;?>/team0<?php echo (($teamKey%3)+1)?>.png" class="img-fluid" alt="" /></li>
                          <?php  
                          $counter++;
                          if( $counter == 20){
                            echo '</ul>'; 
                            $counter = 0;
                          }
                        }
                        echo '</ul>'; 
                      ?> 

                      <hr/>
                      <p class="text-dark small font-weight-bold my-3">Prices</p>

                      <div class="row pl-2">
                        <?php 
                        if(!empty($tournament_price)){ $i=1;
                          foreach ($tournament_price as $event_name => $price) {

                            $event_name = str_replace('_', ' ', $event_name);
                            $event_name = ucfirst($event_name);
                            
                            ?>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                              <label><strong>$<?php echo $price; ?></strong></label>
                              <br/>
                              <label><?php echo $event_name; ?></label>
                              <br/>
                              <?php 
                              $id = "ctp".$i;
                              
                              if(in_array($id,$mykeyArray)){
                              //if(in_array($price, $my_price_array)){
                                ?>
                                <input id="ctp<?php echo $i; ?>" title="You have already purchase thi price." type="checkbox" checked disabled class="coach_tournament_price" name="coach_tournament_price" value="0">
                                <?php
                              } else {
                                ?>
                                <input id="ctp<?php echo $i; ?>" type="checkbox" class="coach_tournament_price" name="coach_tournament_price" value="<?php echo $price; ?>">
                                <?php
                              }
                              ?>
                              
                              <hr/>
                            </div>
                            <?php $i++;
                          }
                        }
                        ?>
                      </div>

                      <p class="text-dark small font-weight-bold my-3">Total:</p>
                      <div class="row pl-2">
                        <div class="col-md-12">
                          <span id="total_price_amount">0.00</span>
                        </div>
                      </div>
                      
                    </div>
                  </div>

                  <?php
                  /* Event Order - Start */
                  $countKey = count($mykeyArray);
                  $is_buy_button_display = 'yes';
                  /*$my_price_array = array_unique($my_price_array);
                  foreach ($event_prices as $key => $evt_price) {
                    if(in_array($evt_price, $my_price_array)){
                      $is_buy_button_display = 'no';
                    } else {
                      $is_buy_button_display = 'yes';
                      break;
                    }
                  }*/
                  if($countKey >=5 ){
                      $is_buy_button_display = 'no';
                    } else {
                      $is_buy_button_display = 'yes';
                    }
                  /* Event Order - End */

                  if($is_buy_button_display == 'yes'){
                  ?>

                  <div class="card-tournament card-tournament-full">

                    <div class="card-tournament-full">
                        <div class="alert alert-danger" style="display: none;"></div>
                        <div class="alert alert-success" style="display: none;"></div>
                      <div class="col-md-12 mb-4">
                        <h2><strong>Buy Packet</strong></h2>
                        <hr/>
                      </div>

                      <div class="accordion" id="accordion_buy_trournaments">

                        <?php
                        $userId = get_current_user_id();
                        global $wpdb;
                        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}cb_credit_card WHERE user_id=%d", $userId));

                        $c_lp = 1;
                        if(!empty($results)){

                          foreach ($results as $key => $card) {

                            $card_last_four_digit = $card->card_last_four_digit;
                            $card_month_expire = $card->card_month_expire;
                            $card_year_expire = $card->card_year_expire;
                            $card_brand = $card->card_brand;
                            $card_id = $card->card_id;
                            $customer_id = $card->stripe_customer;
                            
                            ?>

                            <div class="card">
                              <div class="card-header">
                                  <div class="custom-control custom-radio">
                                      <input <?php /* if($c_lp == 1) { echo 'checked'; } */ ?> data-toggle="collapse" data-target="#collapse_<?php echo $key; ?>" type="radio" id="card_<?php echo $key; ?>" name="customRadio2" class="custom-control-input" />
                                      <label class="custom-control-label" for="card_<?php echo $key; ?>">
                                        <i class="icon icon-credit-card mr-2"></i>
                                        Saved Card <span class="ml-4">**** **** **** <?php echo $card_last_four_digit; ?></span>
                                      </label>
                                  </div>
                              </div>

                              <div id="collapse_<?php echo $key; ?>" class="collapse <?php /*if($c_lp == 1) { echo 'show'; } */ ?>" data-parent="#accordion_buy_trournaments">
                                  <div class="card-body">
                                      <div class="card-payment-list">
                                        <h6> 
                                          <i class="icon icon-credit-card mr-2"></i><span>Saved Card</span> 
                                          
                                          <i class="icon icon-credit-card mr-2"></i>
                                          <p>**** **** **** <?php echo $card_last_four_digit; ?></p>

                                        </h6>
                                      </div>
                                      <div class="col-lg-12 text-center mt-5">
                                        <input type="hidden" name="is_trn_buy" id="is_trn_buy" value="yes">
                                        <input type="hidden" name="action" value="cb_coach_buy_tournaments">
                                        <input type="hidden" name="trnid" class="trnid" id="trnid"  value="<?php echo $trnid; ?>">
                                        
                                        <button type="button" card_id="<?php echo $card->id; ?>"  class="btn btn-sm btn-primary saved_card_buy_tournaments">Buy Packet</button>
                                      </div>
                                  </div>
                              </div>
                            </div>

                            
                            <?php
                            $c_lp++;
                          }
                        }
                        
                        ?>

                        <div class="card">
                          <div class="card-header">
                              <div class="custom-control custom-radio">
                                  <input checked data-toggle="collapse" data-target="#collapse_<?php echo ($c_lp+1); ?>" type="radio" id="card_<?php echo ($c_lp+1); ?>" name="customRadio2" class="custom-control-input" />
                                  <label class="custom-control-label" for="card_<?php echo ($c_lp+1); ?>">
                                    <i class="icon icon-credit-card mr-2"></i>
                                    Other
                                  </label>
                              </div>
                          </div>

                          <div id="collapse_<?php echo ($c_lp+1); ?>" class="collapse show" data-parent="#accordion_buy_trournaments">
                            <div class="card-body">
                              <div id="payment_loading" style="background-image: url('<?php echo get_template_directory_uri();?>/assets/images/loader.gif'); ">
                              </div>

                              <div class="cell example buytournaments mb-3 pb-5 pt-3" id="example-4">
                                <form method="POST" class="card_save_form" id="card_save_form">
                                  <div id="card_save_loading" style="background-image: url('<?php echo get_template_directory_uri();?>/assets/images/loader.gif');"></div>
                                  <span class="token" style="display: none;"></span>
                                  <div class="error" role="alert">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17"><path class="base" fill="#000" d="M8.5,17 C3.80557963,17 0,13.1944204 0,8.5 C0,3.80557963 3.80557963,0 8.5,0 C13.1944204,0 17,3.80557963 17,8.5 C17,13.1944204 13.1944204,17 8.5,17 Z"></path><path class="glyph" fill="#FFF" d="M8.5,7.29791847 L6.12604076,4.92395924 C5.79409512,4.59201359 5.25590488,4.59201359 4.92395924,4.92395924 C4.59201359,5.25590488 4.59201359,5.79409512 4.92395924,6.12604076 L7.29791847,8.5 L4.92395924,10.8739592 C4.59201359,11.2059049 4.59201359,11.7440951 4.92395924,12.0760408 C5.25590488,12.4079864 5.79409512,12.4079864 6.12604076,12.0760408 L8.5,9.70208153 L10.8739592,12.0760408 C11.2059049,12.4079864 11.7440951,12.4079864 12.0760408,12.0760408 C12.4079864,11.7440951 12.4079864,11.2059049 12.0760408,10.8739592 L9.70208153,8.5 L12.0760408,6.12604076 C12.4079864,5.79409512 12.4079864,5.25590488 12.0760408,4.92395924 C11.7440951,4.59201359 11.2059049,4.59201359 10.8739592,4.92395924 L8.5,7.29791847 L8.5,7.29791847 Z"></path></svg>
                                  
                                    <span class="message"></span>
                                  </div>

                                  <div class="payout-card skin-white">
                                      <div class="row">
                                        
                                        <div class="col-lg-12 mb-3">
                                          <div class="form-input">
                                            <label class="form-label">Cardholder Name</label>
                                            <input type="text" class="form-control" name="cardholdername" id="cardholdername" required>
                                            <i class="icon icon-user"></i>
                                          </div>
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                          <div class="form-input">
                                            <!-- <label class="form-label">Card Number</label> -->
                                            <div id="buytournaments-card-number" class="form-control"></div>
                                            <i class="icon icon-card-security"></i>
                                          </div>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                          <div class="form-input">
                                            <!-- <label class="form-label">Expire month</label> -->
                                            <div id="buytournaments-card-expiry" class="form-control"></div>
                                            <i class="icon icon-light-calendar"></i>
                                          </div>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                          <div class="form-input">
                                            <!-- <label class="form-label">Expire year</label> -->
                                            <div id="buytournaments-card-cvc" class="form-control"></div>
                                            <i class="icon icon-cvv"></i>
                                          </div>
                                        </div>
                                      </div>
                                  </div>

                                  <div class="col-lg-12 text-center mt-5">
                                    <input type="hidden" name="is_trn_buy" id="is_trn_buy" value="yes">
                                    <input type="hidden" name="action" value="cb_coach_buy_tournaments">
                                    <input type="hidden" name="trnid" class="trnid" id="trnid"  value="<?php echo $trnid; ?>">
                                    <button type="submit" id="save_coach_card" class="btn btn-sm btn-primary">Buy Packet</button>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                  <?php } ?>
                <?php } ?>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div> 
<?php get_footer('admin'); ?>
<script type="text/javascript" src="<?php echo get_template_directory_uri();?>/assets/js/stripe_buy_tournaments.js"></script>