<?php
/**
 * Template Name: Operator Setting
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

/*
define('AUTHORIZE_URI', 'https://connect.stripe.com/oauth/authorize');
define('UNAUTHORIZE_URI', 'https://connect.stripe.com/oauth/deauthorize');
define('API_URL', 'https://doctordirectapp.net/doctor/doctor_patient/');
define('TOKEN_URI', 'https://connect.stripe.com/oauth/token');
*/

//update_user_meta($userId, 'stripe_user_id', '');
$stripe_user_id = get_user_meta($userId, 'stripe_user_id', true);

if (isset($_GET['code']) && $_GET['code'] != '') {

  $code = $_GET['code'];
    
  $stripe_publishable_key = get_option( 'admin_stripe_publishable_key');
  $stripe_secret_key = get_option( 'admin_stripe_secret_key');
  $stripe_client_id = get_option( 'admin_stripe_client_id');

  $token_request_body = array(
    'client_secret' => $stripe_secret_key,
    'grant_type' => 'authorization_code',
    'client_id' => $stripe_client_id,
    'code' => $code,
  );

  $req = curl_init("https://connect.stripe.com/oauth/token");
  curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($req, CURLOPT_POST, true);
  curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));
  $respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);

  $response = curl_exec($req);
  $err = curl_error($req);

  curl_close($req);

  $resp = json_decode($response, true);
  //echo '<pre>';print_r($resp);echo '</pre>';

  $stripe_user_id = $resp['stripe_user_id'];
  update_user_meta($userId, 'stripe_user_id', $stripe_user_id);

  $redirect_url = site_url() . "/operator-setting/";
  echo "<script>window.open('".$redirect_url."','_self');</script>";
}

get_header('admin');
?> 
    <!-- COntent -->
      <div class="container-fluid">
        <div class="row gutters-10">
          <div class="col-xl-12">
            <div class="section-title">
              <h3 class="title-xs">Settings</h3>
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
                              <input type="hidden" name="action" value="cb-operator-general-setting">
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

                      <link rel="stylesheet" href="https://stripe.com/assets/compiled/css/sprockets-css-v3/documentation/connect_button-74b18ec77a311a4dac78.min.css">

                      <form autocomplete="off" method="post">
                        <div class="row">
                          <div class="col-lg-12 mb-3">
                            <h5>Connect With Stripe</h5>
                            <p>You need to connect to Super Admin Stripe Account, to receive of the tournament amount from the admin stripe account.</p>
                          </div>
                          
                          <?php 
                          $stripe_client_id = get_option( 'admin_stripe_client_id');
                          ?>
                          <div class="col-lg-12">
                            <div class="card-payment-add row">
                              <?php 
                              if($stripe_user_id == ''){
                                ?>
                                <a href="https://connect.stripe.com/oauth/authorize?response_type=code&client_id=<?php echo $stripe_client_id; ?>&scope=read_write" class="connect-button" style="width: 200px;">
                                  <span>Connect with Stripe</span>
                                </a>
                                <?php
                              } else {
                                ?>
                                <a href="javascript:;" id="disconnect_stripe" class="connect-button" style="width: 200px;">
                                  <span>Disconnect</span>
                                </a>
                                
                                <?php  
                              }
                              ?>
                            </div>
                          </div>
                            
                            <?php /*
                          <div class="col-lg-12">
                            <div class="card-payment-add row">
                              <?php
                                $stripe_publishable_key = get_user_meta( $userId, 'stripe_publishable_key' , true );
                                $stripe_secret_key = get_user_meta( $userId, 'stripe_secret_key' , true );
                              ?>
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

                                <div class="col-lg-12 text-center">
                                  <button type="button" class="btn btn-sm btn-primary" id="save_keys">Save Keys</button>
                                </div>
                            </div>
                          </div>
                          */ ?>
                        </div>
                      </form>
                    </div>
                     
                    <?php
                        $isfaq = wpcb_page_exists_by_slug('page-privacy-policy');
                    ?>
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
<style>
    .ck.ck-editor{
        margin:20px 0;
    }
</style>
<?php
get_footer('admin');

?>