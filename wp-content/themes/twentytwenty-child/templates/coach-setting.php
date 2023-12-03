<?php
/**
 * Template Name: Coach Setting
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

$image_path = get_template_directory_uri().'/assets/images';

$user_meta = get_userdata($userId);
$user_roles = $user_meta->roles;

get_header('admin');

?> 
    <!-- COntent -->
      <div class="container-fluid">
        <div class="row gutters-10">
          <div class="col-xl-12">
            <div class="section-title">
              <h3 class="title-xs">Setting</h3>
            </div>
          </div>

          <div class="col-xl-12">
            <div class="setting-wrap">
              <div class="row">
                <div class="col-xl-3 col-lg-12">
                  <div class="nav nav-pills nav-setting flex-xl-column" id="pills-tab" role="tablist">
                    <a class="nav-link active" data-toggle="pill" href="#generalsetting"><i class="icon icon-config"></i> General Setting</a>
                    <a class="nav-link" data-toggle="pill" href="#paymentsettings"><i class="icon icon-credit-card"></i> Payment Setting</a>
                    <a class="nav-link" data-toggle="pill" href="#dataprivacy"><i class="icon icon-server"></i> Data Privacy</a>
                  </div>
                </div>

                <div class="col-xl-9 col-lg-12">
                  <div class="tab-content tab-setting-content overflow-hidden">
                    <div class="tab-pane fade show active" id="generalsetting">
                      <div class="tab-content-header">
                        <div class="section-title">
                          <h4>General Setting</h4>
                        </div>
                      </div>

                      <form class="ajax-form" method="post" autocomplete="off">
                        <div class="row gutters-10">
                          <div class="col-lg-12">
                            <h5>Change Password</h5>
                          </div>
                          <div class="col-xl-6">
                            <div class="form-group">
                              <div class="form-input">
                                <label class="form-label">Password</label>
                                <input type="password" id="pass1" class="form-control" name="password">
                                <i class="icon icon-lock"></i>
                              </div>
                            </div>
                          </div>
                          <div class="col-xl-6">
                            <div class="form-group">
                              <div class="form-input">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" id="pass2" class="form-control" name="confirmpassword">
                                <i class="icon icon-lock"></i>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="row gutters-10">
                          <div class="col-lg-12">
                            <h5>Change University</h5>
                          </div>
                          <div class="col-lg-12">
                            <div class="form-group">
                              <div class="form-input">
                                <label class="form-label">Change University</label>
                                <input type="text" class="form-control" name="universitycollege" value="<?php echo get_user_meta( $userId, 'universitycollege', true);?>">
                                <i class="icon icon-school"></i>
                              </div>
                            </div>
                          </div>                  
                        </div>

                        <div class="row gutters-10">
                          
                          <div class="col-lg-12">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                              <h5 class="mb-0">Notification</h5>
                              <?php
                                $checked = 'checked="checked"';
                                $general_notification = get_user_meta( $userId, 'general-notification', true);
                                if( $general_notification && ( $general_notification == 'false') ){
                                  $checked = '';
                                }
                              ?>
                              <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="general-notification" name="general-notification" value="true" <?php echo $checked; ?>>
                                <label class="custom-control-label" for="general-notification"></label>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-12">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                              <h5 class="mb-0">Email Notifications</h5>
                              <?php
                                $checked = 'checked="checked"';
                                $email_notification = get_user_meta( $userId, 'email-notification', true);
                                if( $email_notification && ( $email_notification == 'false') ){
                                  $checked = '';
                                }
                              ?>
                              <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="email-notification" name="email-notification" value="true" <?php echo $checked; ?> >
                                <label class="custom-control-label" for="email-notification"></label>
                              </div>
                            </div>
                          </div>

                          <div class="col-lg-12">
                            <div class="text-center">
                              <input type="hidden" name="action" value="cb-coach-general-setting">
                              <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>

                    <div class="tab-pane fade" id="paymentsettings">
                      <div class="tab-content-header">
                        <div class="section-title">
                          <h4>Payment Setting</h4>
                        </div>
                      </div>

                      <?php if (in_array("administrator", $user_roles)) { ?>

                        <form autocomplete="off" method="post">
                          <div class="row">
                            <div class="col-lg-12">
                              <h5>Stripe Keys</h5>
                            </div>
                            

                            <div class="col-lg-12">
                              <div class="card-payment-add row">
                                <?php
                                  $stripe_publishable_key = get_option( 'admin_stripe_publishable_key');
                                  $stripe_secret_key = get_option( 'admin_stripe_secret_key');
                                  $stripe_client_id = get_option( 'admin_stripe_client_id');
                                  $tournament_commission = get_option( 'tournament_commission');
                                ?>
                                  <div class="col-xl-12">
                                    <div class="form-group">
                                      <div class="form-input">
                                        <label class="form-label">Stripe Client ID</label>
                                        <input type="text" class="form-control" id="stripe_client_id" name="stripe_client_id" value="<?php if ( ! empty( $stripe_client_id ) ) { echo $stripe_client_id; }?>">
                                        <input type="hidden" id="userId" name="userId" value="<?php echo $userId; ?>">
                                        <i class="icon icon-lock"></i>
                                      </div>
                                    </div>
                                  </div>

                                  <div class="col-xl-12">
                                    <div class="form-group">
                                      <div class="form-input">
                                        <label class="form-label">Publishable Key</label>
                                        <input type="text" class="form-control" id="publishable_key" name="publishable_key" value="<?php if ( ! empty( $stripe_publishable_key ) ) { echo $stripe_publishable_key; }?>">
                                        <input type="hidden" id="userId" name="userId" value="<?php echo $userId; ?>">
                                        <i class="icon icon-lock"></i>
                                      </div>
                                    </div>
                                  </div>

                                  <div class="col-xl-12">
                                    <div class="form-group">
                                      <div class="form-input">
                                        <label class="form-label">Secret Key</label>
                                        <input type="text" class="form-control" name="secret_key" id="secret_key" value="<?php if ( ! empty( $stripe_secret_key ) ) { echo $stripe_secret_key; }?>">
                                        <i class="icon icon-lock"></i>
                                      </div>
                                    </div>
                                  </div>

                                  <div class="col-xl-12">
                                    <div class="form-group">
                                      <div class="form-input">
                                        <label class="form-label">Tournament Commission (%)</label>
                                        <input type="number" class="form-control" name="tournament_commission" id="tournament_commission" value="<?php if ( ! empty( $tournament_commission ) ) { echo $tournament_commission; }?>">
                                        <i class="icon icon-lock"></i>
                                      </div>
                                    </div>
                                  </div>

                                  <div class="col-lg-12 text-center">
                                    <button type="button" class="btn btn-sm btn-primary" id="save_keys">Save Keys</button>
                                  </div>

                              </div>

                              
                            </div>
                          </div>
                        </form>
                      <?php } else { ?>

                        <div class="row">
                          <div class="col-lg-12">
                            <h5>Manage Saved Cards</h5>
                          </div>

                          <div class="col-lg-12">
                            <?php
                            $userId = get_current_user_id();
                            global $wpdb;
                            $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}cb_credit_card WHERE user_id=%d", $userId));

                            if(!empty($results)){

                              foreach ($results as $key => $card) {

                                $card_last_four_digit = $card->card_last_four_digit;
                                $card_month_expire = $card->card_month_expire;
                                $card_year_expire = $card->card_year_expire;
                                $card_brand = $card->card_brand;
                                $card_id = $card->card_id;
                                $customer_id = $card->stripe_customer;
                                
                                ?>
                                <div class="card-payment-list">
                                <h6>
                                  <span><?php echo $card_brand; ?> Card</span>
                                  <!-- <a class="payment-edit" href="#">Edit</a> -->
                                  <a href="javascript:;" onclick="deleteSaveCard('<?php echo $card_id; ?>', '<?php echo $customer_id; ?>');" class="payment-delete"><i class="icon icon-trash"></i></a>
                                </h6>

                                <div class="card-payment-logo">
                                  <i class="icon icon-credit-card"></i>
                                  <?php /* <img src="<?php echo get_template_directory_uri(); ?>/assets/images/card-visa.png" class="img-fluid" alt=""> */ ?>
                                  <p>**** **** **** <?php echo $card_last_four_digit; ?></p>
                                </div>
                              </div>
                                <?php
                              }
                            }
                            
                            ?>                            

                            <div class="card-payment-save">
                              <h6>Add a new card</h6>

                              <div class="cell example example3 mb-3 pb-5 pt-3" id="example-3">
                                <form method="POST" class="card_save_form" id="card_save_form">
                                  <div id="card_save_loading" style="background-image: url('<?php echo get_template_directory_uri();?>/assets/images/loader.gif');"></div>
                                  <span class="token" style="display: none;"></span>
                                  <div class="error" role="alert">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17"><path class="base" fill="#000" d="M8.5,17 C3.80557963,17 0,13.1944204 0,8.5 C0,3.80557963 3.80557963,0 8.5,0 C13.1944204,0 17,3.80557963 17,8.5 C17,13.1944204 13.1944204,17 8.5,17 Z"></path><path class="glyph" fill="#FFF" d="M8.5,7.29791847 L6.12604076,4.92395924 C5.79409512,4.59201359 5.25590488,4.59201359 4.92395924,4.92395924 C4.59201359,5.25590488 4.59201359,5.79409512 4.92395924,6.12604076 L7.29791847,8.5 L4.92395924,10.8739592 C4.59201359,11.2059049 4.59201359,11.7440951 4.92395924,12.0760408 C5.25590488,12.4079864 5.79409512,12.4079864 6.12604076,12.0760408 L8.5,9.70208153 L10.8739592,12.0760408 C11.2059049,12.4079864 11.7440951,12.4079864 12.0760408,12.0760408 C12.4079864,11.7440951 12.4079864,11.2059049 12.0760408,10.8739592 L9.70208153,8.5 L12.0760408,6.12604076 C12.4079864,5.79409512 12.4079864,5.25590488 12.0760408,4.92395924 C11.7440951,4.59201359 11.2059049,4.59201359 10.8739592,4.92395924 L8.5,7.29791847 L8.5,7.29791847 Z"></path></svg>
                                  
                                    <span class="message"></span>
                                  </div>

                                  <div class="payout-card skin-white">
                                      <div class="row">
                                        <?php /* <div class="col-lg-12">
                                          <img src="<?php echo get_template_directory_uri();?>/assets/images/visa.svg" class="img-fluid w-auto ml-auto" alt="">
                                        </div> */ ?>
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
                                            <div id="example3-card-number" class="form-control"></div>
                                            <i class="icon icon-card-security"></i>
                                          </div>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                          <div class="form-input">
                                            <!-- <label class="form-label">Expire month</label> -->
                                            <div id="example3-card-expiry" class="form-control"></div>
                                            <i class="icon icon-light-calendar"></i>
                                          </div>
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                          <div class="form-input">
                                            <!-- <label class="form-label">Expire year</label> -->
                                            <div id="example3-card-cvc" class="form-control"></div>
                                            <i class="icon icon-cvv"></i>
                                          </div>
                                        </div>
                                      </div>
                                  </div>

                                  <div class="col-lg-12 text-center">
                                    <input type="hidden" name="action" value="cb_coach_carddetails_save_setting">
                                    <button type="submit" id="save_coach_card" class="btn btn-sm btn-primary">Save this card</button>
                                  </div>

                                </form>
                              </div>
                            </div>
                          </div>
                        </div>
                      <?php } ?>
                    </div>
                    <div class="tab-pane fade" id="dataprivacy">
                      <div class="tab-content-header">
                        <div class="section-title">
                          <h4>Data Privacy</h4>
                        </div>
                      </div>

                      <div class="privacy-tab-wrap">
                        <ul class="nav nav-pills nav-privacy justify-content-center" id="dataprivacy-tab" role="tablist">
                          <li class="nav-item" role="presentation">
                            <a class="nav-link active" data-toggle="pill" href="#faqs">FAQs</a>
                          </li>
                          <li class="nav-item" role="presentation">
                            <a class="nav-link" data-toggle="pill" href="#privacypolicy">Privacy Policy</a>
                          </li>
                          <li class="nav-item" role="presentation">
                            <a class="nav-link" data-toggle="pill" href="#termsconditions">Terms & Conditions</a>
                          </li>
                        </ul>

                        <div class="tab-content" id="pills-tabContent">
                          <div class="tab-pane fade show active" id="faqs" role="tabpanel">
                            <div class="privacy-content">
                              <?php
                                  $isfaqs = wpcb_page_exists_by_slug('page-faqs');
                              ?>
                              <h5><?php echo get_the_title($isfaqs); ?></h5>

                              <ul class="list-unstyled mCustomScrollbar">
                                <li>
                                  <?php echo get_post_field('post_content', $isfaqs); ?>
                                </li>
                              </ul>
                            </div>
                          </div>
                          <div class="tab-pane fade" id="privacypolicy" role="tabpanel">
                            <div class="privacy-content">
                              <?php
                                  $isprivacy = wpcb_page_exists_by_slug('page-privacy-policy');
                              ?>
                              
                              <h5><?php echo get_the_title($isprivacy); ?></h5>

                              <ul class="list-unstyled mCustomScrollbar">
                                <li>
                                  <?php echo get_post_field('post_content', $isprivacy); ?>
                                </li>
                              </ul>
                            </div>
                          </div>
                          <div class="tab-pane fade" id="termsconditions" role="tabpanel">
                            <div class="privacy-content">
                              <?php
                                  $istc= wpcb_page_exists_by_slug('page-terms-conditions');
                              ?>
                              <h5><?php echo get_the_title($istc); ?></h5>

                              <ul class="list-unstyled mCustomScrollbar">
                                <li>
                                  <?php echo get_post_field('post_content', $istc); ?>
                                  
                                </li>
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
<?php get_footer('admin'); ?>
