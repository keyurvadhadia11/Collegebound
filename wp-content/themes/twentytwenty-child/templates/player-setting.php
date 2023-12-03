<?php
/**
 * Template Name: Player Setting
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

get_header('player');
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
                              <input type="hidden" name="action" value="cb-player-general-setting">
                              <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            </div>
                          </div>
                        </div>
                      </form>
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
                              <h5>FAQs</h5>

                              <ul class="list-unstyled mCustomScrollbar">
                                <li>
                                  <h6>What happens when I update my email address (or mobile number)?</h6>

                                  <p>Your login email id (or mobile number) changes, likewise. You'll receive all your account related communication on your updated email address (or mobile number).</p>
                                </li>

                                <li>
                                  <h6>What is Lorem Ipsum?</h6>

                                  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                                </li>

                                <li>
                                  <h6>Where can I get some?</h6>

                                  <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>
                                </li>
                              </ul>
                            </div>
                          </div>
                          <div class="tab-pane fade" id="privacypolicy" role="tabpanel">
                            <div class="privacy-content">
                              <h5>Privacy Policy</h5>

                              <ul class="list-unstyled mCustomScrollbar">
                                <li>
                                  <h6>Where does it come from in Privacy Policy</h6>

                                  <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.</p>

                                  <p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>

                                  <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.</p>

                                  <p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
                                </li>
                              </ul>
                            </div>
                          </div>
                          <div class="tab-pane fade" id="termsconditions" role="tabpanel">
                            <div class="privacy-content">
                              <h5>Terms & Conditions</h5>

                              <ul class="list-unstyled mCustomScrollbar">
                                <li>
                                  <h6>Where does it come from in Privacy Policy</h6>

                                  <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.</p>

                                  <p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>

                                  <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.</p>

                                  <p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>
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