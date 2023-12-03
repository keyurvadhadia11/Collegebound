<?php
/**
 * Template Name: Signup
 */
 
// Is user login, then redirect to dashboard
if (is_user_logged_in() ) {
    if( current_user_can('player')){
       wp_redirect( site_url('player-dashboard') );
    } 
    elseif( current_user_can('coach')){
       wp_redirect( site_url('coach-dashboard') );
    } else {
      wp_redirect ( site_url('admin-dashboard') );  
    } 
    exit;
}

get_header('home');
?>  
   <!-- Content -->
  <div class="section-signup">
    <div class="left-form">
      <a href="<?php echo site_url();?>" class="back-to">
        <i class="icon icon-left-arrow"></i>
      </a>
    </div>
    <div class="right-form">
      <div class="logo-form">
        <p>Get Started With</p> 
        <img src="<?php echo get_template_directory_uri();?>/assets/images/logo.svg" class="img-fluid" alt="College Bound">
      </div>

       <?php
          $role = $_GET['by'];
          $userRole = 'eventOperator';
          if( $role == 'coach')
            $userRole = 'coach';
          elseif( $role == 'player')
            $userRole = 'player'; 
        ?>

    <?php if( $userRole == 'coach') : ?>

      <!-- Coach Signup Content Start -->
      <div class="signup-wrap example3" id="example-3">
        <ul class="nav nav-signup nav-five" id="signup-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="basciinfo-tab" data-toggle="pill" href="#basciinfo" role="tab" aria-controls="basciinfo" aria-selected="true"></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="collegeinfo-tab" data-toggle="pill" href="#collegeinfo" role="tab" aria-controls="collegeinfo" aria-selected="true"></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="accountinfo-tab" data-toggle="pill" href="#accountinfo" role="tab" aria-controls="accountinfo" aria-selected="true"></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="paymentinfo-tab" data-toggle="pill" href="#paymentinfo" role="tab" aria-controls="paymentinfo" aria-selected="true"></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="allset-tab" data-toggle="pill" href="#allset" role="tab" aria-controls="allset" aria-selected="true"></a>
          </li>
        </ul> 
        <!-- Coach Signup Form Start -->
        <form class="signup-form" method="post" autocomplete="off">
          <div class="tab-content" id="signup-tabContent">
            
            <div class="tab-pane fade show active" id="basciinfo" role="tabpanel">
              <div class="text-center mb-4">
                <div class="section-title">
                  <h4 class="title-sm">College Coach Sign Up</h4>
                </div>
                <p class="mt-1">Basic Info</p>
              </div>

              <div class="form-input text-center">
                <div id="profile-preview" class="custom-file">
                  <input type="file" name="profile-pic" id="customFile" accept="image/jpeg, image/png" data-type='image'>                
                  <i id="icon-camera" class="icon icon-camera"></i>
                </div>
              </div>

              <div class="form-input">
                <label class="form-label">First Name</label>
                <input type="text" class="form-control" name="first_name" required/>
                <i class="icon icon-user"></i>
              </div>

              <div class="form-input">
                <label class="form-label">Last Name</label>
                <input type="text" class="form-control" name="last_name" required/>
                <i class="icon icon-user"></i>
              </div>

              <div class="form-input">
                <label class="form-label">Email ID</label>
                <input type="email" class="form-control" name="useremail" required/>
                <i class="icon icon-mail"></i>
              </div>

              <div class="form-input mb-4">
                <a href="javascript:void(0);" class="btn btn-block btn-primary btn-next">Next</a>
              </div>

              <p class="text-slate text-center small">Already have an account?
                <a class="text-primary font-weight-medium" href="<?php echo add_query_arg( 'by', $userRole, site_url('login') ); ?>">Login</a>
              </p>
            </div>

            <div class="tab-pane fade" id="collegeinfo" role="tabpanel">
              <div class="text-center mb-4">
                <div class="section-title">
                  <h4 class="title-sm">College Coach Sign Up</h4>
                </div>
                <p class="mt-1">College Info</p>
              </div>

              <div class="form-input">
                <label class="form-label">What University or College do you coach for?</label>
                <input type="text" class="form-control" name="universitycollege" required/>
                <i class="icon icon-school"></i>
              </div>

              <div class="form-input">
                <label class="form-label">What is Your Coach Title?</label>
                <input type="text" class="form-control" name="coachtitle" required/>
                <i class="icon icon-coach"></i>
              </div>

              <div class="form-input">
                <select class="custom-select select-division" multiple="multiple" required>
                  <option value="naaadivision1">NCAA Division 1</option>
                  <option value="naaadivision2">NCAA Division 2</option>
                  <option value="naaadivision3">NCAA Division 3</option>
                  <option value="juco">JUCO</option>
                  <option value="naia">NAIA</option>
                </select>
                <i class="icon icon-division"></i>
              </div>

              <div class="form-input">
                <label class="text-slate d-block mb-4">Which team?</label>

                <div class="custom-file">
                  <input type="radio" id="teammen" name="player_postion" checked="checked" required>
                  <label class="custom-label" for="teammen">MEN</label>
                  <div class="active">
                    <i class="icon icon-male"></i>
                  </div>

                </div>

                <div class="custom-file">
                  <input type="radio" id="teamwomen" name="player_postion" required>
                  <label class="custom-label" for="teamwomen">WOMEN</label>
                  <div class="active">
                    <i class="icon icon-female"></i>
                  </div>
                </div>              
              </div>

              <div class="form-input mb-4">
                <a href="javascript:void(0);" class="btn btn-block btn-primary btn-next">Next</a>
              </div>
            </div>

            <div class="tab-pane fade" id="accountinfo" role="tabpanel">
              <div class="text-center mb-4">
                <div class="section-title">
                  <h4 class="title-sm">College Coach Sign Up</h4>
                </div>
                <p class="mt-1">Secure Your Account</p>
              </div>

              <div class="form-input">
                <label class="form-label">What is your phone number?</label>
                <input type="text" class="form-control" name="phoneno" maxlength="10" required/>
                <i class="icon icon-call"></i>
              </div>

              <div class="form-input">
                <label class="form-label">What is your address?</label>
                <input type="text" class="form-control" name="address" required/>
                <i class="icon icon-address"></i>
              </div>

              <div class="form-input">
                <label class="form-label">Password</label>
                <input type="password" id="pass1" class="form-control" name="password" required/>
                <i class="icon icon-lock"></i>
              </div>

              <div class="form-input">
                <label class="form-label">Confirm Password</label>
                <input type="password" id="pass2" class="form-control" name="confirmpassword" required/>
                <i class="icon icon-lock"></i>
              </div>

              <div class="form-input">
                <div class="progress">
                  <div class="progress-bar bg-gradient" role="progressbar" style="width: 0%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>              
                </div>

                <p class="text-slate text-right">Password Strength: <span id="pass-strength-result">Default</span></p>
              </div>

              <!-- <div class="form-input mb-4">
                <a href="javascript:void(0);" class="btn btn-block btn-primary btn-next" onclick="return validatePassword()">Next</a>
              </div> -->

              <div class="form-input mb-4">
                <input type="hidden" name="action" value="user_register">
                <input type="hidden" name="userrole" value="coach">
                <button type="submit" onclick="return validatePassword()" class="btn btn-block btn-primary">Next</button>
              </div>

            </div>
        
            <?php /* 
            <div class="tab-pane fade show active" id="paymentinfo" role="tabpanel">
              <div class="text-center mb-3">
                <div class="section-title">
                  <h4 class="title-sm">College Coach Sign Up</h4>
                </div>
                <p class="mt-1">Payment Info</p>
              </div>

              <div class="form-input">
                <p class="text-center">Please enter your credit card information below. Your card will not be charged unless you purchase access to an event packet or subscribe to our monthly recruiting service.</p>
              </div>

              <span class="token" style="display: none;"></span>
              <div class="error" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17"><path class="base" fill="#000" d="M8.5,17 C3.80557963,17 0,13.1944204 0,8.5 C0,3.80557963 3.80557963,0 8.5,0 C13.1944204,0 17,3.80557963 17,8.5 C17,13.1944204 13.1944204,17 8.5,17 Z"></path><path class="glyph" fill="#FFF" d="M8.5,7.29791847 L6.12604076,4.92395924 C5.79409512,4.59201359 5.25590488,4.59201359 4.92395924,4.92395924 C4.59201359,5.25590488 4.59201359,5.79409512 4.92395924,6.12604076 L7.29791847,8.5 L4.92395924,10.8739592 C4.59201359,11.2059049 4.59201359,11.7440951 4.92395924,12.0760408 C5.25590488,12.4079864 5.79409512,12.4079864 6.12604076,12.0760408 L8.5,9.70208153 L10.8739592,12.0760408 C11.2059049,12.4079864 11.7440951,12.4079864 12.0760408,12.0760408 C12.4079864,11.7440951 12.4079864,11.2059049 12.0760408,10.8739592 L9.70208153,8.5 L12.0760408,6.12604076 C12.4079864,5.79409512 12.4079864,5.25590488 12.0760408,4.92395924 C11.7440951,4.59201359 11.2059049,4.59201359 10.8739592,4.92395924 L8.5,7.29791847 L8.5,7.29791847 Z"></path></svg>
              
                <span class="message"></span>
              </div>

              <div class="form-input form-card ">
                <div class="payout-card skin-white">
                  <div class="row">
                    <div class="col-lg-12">
                      <img src="<?php echo get_template_directory_uri();?>/assets/images/visa.svg" class="img-fluid w-auto ml-auto" alt="">
                    </div>
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
              </div>

              <div class="form-input mb-4">
                <input type="hidden" name="action" value="user_register">
                <input type="hidden" name="userrole" value="coach">
                <button type="submit" id="sign_up_button" class="btn btn-block btn-primary">Next</button>
              </div>
            </div>
            */ ?>

            <div class="tab-pane fade" id="allset" role="tabpanel">
              <div class="text-center mb-3">
                <div class="section-title">
                  <h4 class="title-sm">You’re all set!</h4>
                </div>
                <p class="mt-3">Thank you for joining College Bound. Please check your email and confirm your account. Also, don’t forget to update your profile!</p>
              </div>

              <div class="form-input form-allset">
                <img src="<?php echo get_template_directory_uri();?>/assets/images/banner-allset.png" class="img-fluid" alt="You’re all set!">
              </div>

              <div class="form-input mb-4">
                <a href="<?php echo site_url('coach-dashboard');?>" class="btn btn-block btn-primary">Next</a>
              </div>
            </div> 
          </div>
        </form>
        <!-- End Coach Signup Form  -->
      </div>
      <!-- End Coach Signup Content -->
    <?php endif; ?>

    <?php if( $userRole == 'player') : ?>
      <!-- Athelete Signup Content Start -->
      <div class="signup-wrap">
         <ul class="nav nav-signup nav-four" id="signup-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="basciinfo-tab" data-toggle="pill" href="#basciinfo" role="tab" aria-controls="basciinfo" aria-selected="true"></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="collegeinfo-tab" data-toggle="pill" href="#collegeinfo" role="tab" aria-controls="collegeinfo" aria-selected="true"></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="accountinfo-tab" data-toggle="pill" href="#accountinfo" role="tab" aria-controls="accountinfo" aria-selected="true"></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="allset-tab" data-toggle="pill" href="#allset" role="tab" aria-controls="allset" aria-selected="true"></a>
          </li>
        </ul>
        <!-- Athlete Signup Form Start -->
        <form class="signup-form" method="post" autocomplete="off">
          <div class="tab-content" id="signup-tabContent">
            <div class="tab-pane fade show active" id="basciinfo" role="tabpanel">
              <div class="text-center mb-4">
                <div class="section-title">
                  <h4 class="title-sm">Athlete Sign Up</h4>
                </div>
                <p class="mt-1">Basic Info</p>
              </div>

              <div class="form-input text-center">
                <div id="profile-preview" class="custom-file">
                  <input type="file" name="profile-pic" id="customFile" accept="image/jpeg, image/png" data-type='image'>
                  <i id="icon-camera" class="icon icon-camera"></i>
                </div>
              </div>

              <div class="form-input">
                <label class="form-label">First Name</label>
                <input type="text" class="form-control" name="first_name" />
                <i class="icon icon-user"></i>
              </div>

              <div class="form-input">
                <label class="form-label">Last Name</label>
                <input type="text" class="form-control" name="last_name" />
                <i class="icon icon-user"></i>
              </div>

              <div class="form-input">
                <label class="form-label">Email ID</label>
                <input type="email" class="form-control" name="useremail" />
                <i class="icon icon-mail"></i>
              </div>

              <div class="form-input mb-4">
                <a href="javascript:void(0);" class="btn btn-block btn-primary btn-next">Next</a>
              </div>

              <p class="text-slate text-center small">Already have an account?
                <a class="text-primary font-weight-medium" href="<?php echo add_query_arg( 'by', $userRole, site_url('login') ); ?>">Login</a>
              </p>
            </div>

            <div class="tab-pane fade" id="collegeinfo" role="tabpanel">
              <div class="text-center mb-4">
                <div class="section-title">
                  <h4 class="title-sm">Athlete Sign Up</h4>
                </div>
                <p class="mt-1">Athlete Information</p>
              </div>

              <div class="form-input">
                <label class="form-label">what school do you attend?</label>
                <input type="text" class="form-control" name="universitycollege" />
                <i class="icon icon-school"></i>
              </div>

              <div class="form-input">
                <label class="text-slate d-block mb-4">What position do you play?</label>

                <div class="row no-lg-gutters">
                  <div class="col-sm-4 col-6">
                    <div class="custom-play">
                      <input type="radio" id="teammen" name="player_postion" value="Point Guard" checked="checked">
                      <div class="active">
                        <i class="icon icon-position-point"></i>
                      </div>
                      <label class="custom-label" for="teammen">Point Guard</label>
                    </div>
                  </div>

                  <div class="col-sm-4 col-6">
                    <div class="custom-play">
                      <input type="radio" id="teamwomen" name="player_postion" value="Shooting Guard">
                      <div class="active">
                        <i class="icon icon-position-shooting"></i>
                      </div>
                      <label class="custom-label" for="teamwomen">Shooting Guard</label>
                    </div>
                  </div>   

                  <div class="col-sm-4 col-6">
                    <div class="custom-play">
                      <input type="radio" id="teamwomen" name="player_postion" value="Small Forward">
                      <div class="active">
                        <i class="icon icon-position-small"></i>
                      </div>
                      <label class="custom-label" for="teamwomen">Small Forward</label>
                    </div>
                  </div>   

                  <div class="col-sm-4 col-6">
                    <div class="custom-play">
                      <input type="radio" id="teamwomen" name="player_postion" value="Power Forward">
                      <div class="active">
                        <i class="icon icon-position-power"></i>
                      </div>
                      <label class="custom-label" for="teamwomen">Power Forward</label>
                    </div>
                  </div>   

                  <div class="col-sm-4 col-6">
                    <div class="custom-play">
                      <input type="radio" id="teamwomen" name="player_postion" value="Center">
                      <div class="active">
                        <i class="icon icon-position-center"></i>
                      </div>
                      <label class="custom-label" for="teamwomen">Center</label>
                    </div>
                  </div>              
                </div>
              </div>

              <div class="form-input mb-4">
                <a href="javascript:void(0);" class="btn btn-block btn-primary btn-next">Next</a>
              </div>
            </div>

            <div class="tab-pane fade" id="accountinfo" role="tabpanel">
              <div class="text-center mb-2">
                <div class="section-title">
                  <h4 class="title-sm">Athlete Sign Up</h4>
                </div>
                <p class="mt-1">Secure Your Account</p>

                <p class="font-italic text-center mt-3">all information will be used strictly for the purpose of recruiting and will not be sold or given to anyone other than a college coach for the purpose of recruiting.</p>
              </div>

              <div class="form-input">
                <label class="form-label">What is your phone number?</label>
                <input type="text" class="form-control" name="phoneno" maxlength="10" required/>
                <i class="icon icon-call"></i>
              </div>

              <div class="form-input">
                <label class="form-label">What is your address?</label>
                <input type="text" class="form-control" name="address" required/>
                <i class="icon icon-address"></i>
              </div>

              <div class="form-input">
                <label class="form-label">Password</label>
                <input type="password" id="pass1" class="form-control" name="password" required/>
                <i class="icon icon-lock"></i>
              </div>

              <div class="form-input">
                <label class="form-label">Confirm Password</label>
                <input type="password" id="pass2" class="form-control" name="confirmpassword" required/>
                <i class="icon icon-lock"></i>
              </div>

              <div class="form-input">
                <div class="progress">
                  <div class="progress-bar bg-gradient" role="progressbar" style="width: 0%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>              
                </div>

                <p class="text-slate text-right">Password Strength: <span id="pass-strength-result">Default</span></p>
              </div>

              <div class="form-input mb-4">
                <input type="hidden" name="action" value="user_register">
                <input type="hidden" name="userrole" value="player">
                <button type="submit" class="btn btn-block btn-primary" onclick="return validatePassword()">Next</button>
              </div>
            </div>

            <div class="tab-pane fade" id="allset" role="tabpanel">
              <div class="text-center mb-3">
                <div class="section-title">
                  <h4 class="title-sm">You’re all set!</h4>
                </div>
                <p class="mt-3">Thank you for joining CollegeBound. A payment confirmation will be sent to your e-mail.  Don’t forget to set up your profile next.</p>
              </div>

              <div class="form-input form-allset">
                <img src="<?php echo get_template_directory_uri();?>/assets/images/banner-allset.png" class="img-fluid" alt="You’re all set!">
              </div>

              <div class="form-input mb-4">
                <a  href="<?php echo site_url('player-dashboard');?>" class="btn btn-block btn-primary">Next</a>
              </div>
            </div>

          </div>
        </form>
        <!-- End Athele Signup Form -->
      </div>
      <!-- End Athlete Signup Content -->
    <?php endif; ?>

    <?php if( $userRole == 'eventOperator') : ?>
      <!-- Event Operator Signup Content Start -->
      <div class="signup-wrap">
        <ul class="nav nav-signup nav-three" id="signup-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="basciinfo-tab" data-toggle="pill" href="#basciinfo" role="tab" aria-controls="basciinfo" aria-selected="true"></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="accountinfo-tab" data-toggle="pill" href="#accountinfo" role="tab" aria-controls="accountinfo" aria-selected="true"></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="allset-tab" data-toggle="pill" href="#allset" role="tab" aria-controls="allset" aria-selected="true"></a>
          </li>
        </ul>
        <!-- Event Operator Signup Form Start -->
        <form class="signup-form" method="post" autocomplete="off">
          <div class="tab-content" id="signup-tabContent">
            <div class="tab-pane fade show active" id="basciinfo" role="tabpanel">
              <div class="text-center mb-4">
                <div class="section-title">
                  <h4 class="title-sm">Event Operator Sign Up</h4>
                </div>
                <p class="mt-1">Basic Info</p>
              </div>

              <div class="form-input text-center">
                <div id="profile-preview" class="custom-file">
                  <input type="file" name="profile-pic" id="customFile" accept="image/jpeg, image/png" data-type='image'>
                  <i id="icon-camera" class="icon icon-camera"></i>
                </div>
              </div>

              <div class="form-input">
                <label class="form-label">First Name</label>
                <input type="text" class="form-control" name="first_name" required/>
                <i class="icon icon-user"></i>
              </div>

              <div class="form-input">
                <label class="form-label">Last Name</label>
                <input type="text" class="form-control" name="last_name" required/>
                <i class="icon icon-user"></i>
              </div>

              <div class="form-input">
                <label class="form-label">Email ID</label>
                <input type="email" class="form-control" name="useremail" onchange="validateEmail(this);" required/>
                <i class="icon icon-mail"></i>
              </div>

              <div class="form-input mb-4">
                <a href="javascript:void(0);" class="btn btn-block btn-primary btn-next">Next</a>
              </div>

              <p class="text-slate text-center small">Already have an account?
                <a class="text-primary font-weight-medium" href="<?php echo add_query_arg( 'by', $userRole, site_url('login') ); ?>">Login</a>
              </p>
            </div>

            <div class="tab-pane fade" id="accountinfo" role="tabpanel">
              <div class="text-center mb-4">
                <div class="section-title">
                  <h4 class="title-sm">Event Operator Sign Up</h4>
                </div>
                <p class="mt-1">Secure Your Account</p>
              </div>

              <div class="form-input">
                <label class="form-label">What is your phone number?</label>
                <input type="text" class="form-control" name="phoneno" maxlength="10" required/>
                <i class="icon icon-call"></i>
              </div>

              <div class="form-input">
                <label class="form-label">What is your address?</label>
                <input type="text" class="form-control" name="address" required/>
                <i class="icon icon-address"></i>
              </div>

              <div class="form-input">
                <label class="form-label">Password</label>
                <input type="password" id="pass1" class="form-control" name="password" required/>
                <i class="icon icon-lock"></i>
              </div>

              <div class="form-input">
                <label class="form-label">Confirm Password</label>
                <input type="password" id="pass2" class="form-control" name="confirmpassword" required/>
                <i class="icon icon-lock"></i>
              </div>

              <div class="form-input">
                <div class="progress">
                  <div class="progress-bar bg-gradient" role="progressbar" style="width: 0%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>              
                </div>

                <p class="text-slate text-right">Password Strength: <span id="pass-strength-result">Default</span></p>
              </div>

              <div class="form-input mb-4">
                <input type="hidden" name="action" value="user_register">
                <input type="hidden" name="userrole" value="eventOperator">
                <button type="submit" class="btn btn-block btn-primary" onclick="return validatePassword()">Next</button>
              </div>
            </div>

            <div class="tab-pane fade" id="allset" role="tabpanel">
              <div class="text-center mb-3">
                <div class="section-title">
                  <h4 class="title-sm">You’re all set!</h4>
                </div>
                <p class="mt-3">Thank you for joining College Bound. Please check your email and confirm your account. Also, don’t forget to update your profile!</p>
              </div>

              <div class="form-input form-allset">
                <img src="<?php echo get_template_directory_uri();?>/assets/images/banner-allset.png" class="img-fluid" alt="You’re all set!">
              </div>

              <div class="form-input mb-4">
                <a href="<?php echo site_url('admin-dashboard');?>" class="btn btn-block btn-primary">Next</a>
              </div>
            </div>

          </div>
        </form>
        <!-- End Event Operator Signup Form -->
      </div>
      <!-- End Event Operator Signup Content -->
    <?php endif; ?>

    </div>
  </div>

  <!-- OTP Verification Modal Start -->
  <div class="modal fade" id="otpVerificationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Validate OTP (One Time Passcode)</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form>
          <div class="modal-body">
            <div id="otp-message">
              <p>A One Time Password has been sent to abc@xyz.com</p><br/>
              <p>Please enter the OTP in the field below to verify.</p><br/>
            </div>
            <div class="form-input">
                <label class="form-label">Enter Verification Code</label>
                <input type="text" id="verificationCode" class="form-control" maxlength="6" name="verificationCode" required="">
            </div>
          </div>
        </form>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="sendOtpMail(true)">Resend OTP</button>
          <button type="button" class="btn btn-primary" onclick="validateOtpMail()">Validate OTP</button>
        </div>
      </div>
    </div>
  </div>
  <!-- End OTP Verification Modal -->
  
    <script>

      // Email Verified
      var emailVerifield = false;

      jQuery(document).keypress(
        function(event){
          if (event.which == '13') {
            event.preventDefault();
          }
      });

      function validatePassword() {
          var password = document.getElementById("pass1");
          var confirmPassword = document.getElementById("pass2");
          if (password.value != confirmPassword.value) {
              alert("Passwords do not match."); 
              return false;
          } 
          return true;
      }

      function validateEmail(thisemail) 
      {
          return true;
          /*var email  = thisemail.value;
          var re = "/^(([a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}|edu)\b))$/";
          
          if( ! re.test(email) ){
            thisemail.value = '';
            alert("please enter valid .edu email"); 
          }*/ 
      } 

      document.getElementById('customFile').addEventListener('change', readURL, true);
      function readURL(){ 
          var profilePreview = document.getElementById('profile-preview');
          profilePreview.style.background = 'none';
          document.getElementById('icon-camera').style.display = "block";

          // Allowing file type 
          var allowedExtensions =  
                  /(\.jpg|\.jpeg|\.png)$/i; 
            
          if (!allowedExtensions.exec(document.getElementById('customFile').value)) { 
              alert('Invalid file type'); 
              document.getElementById('customFile').value.value = ''; 
              return false; 
          }  

          var file = document.getElementById("customFile").files[0];
          var reader = new FileReader();
          reader.onloadend = function(){ 
              profilePreview.style.background = "url(" + reader.result + ")"; 
              profilePreview.style.backgroundSize = "contain"; 
              profilePreview.style.backgroundRepeat = "no-repeat"; 
              profilePreview.style.backgroundPosition = "center";  

              document.getElementById('icon-camera').style.display = "none";
          }
          if(file){
              reader.readAsDataURL(file);
          }else{
          }
      }


      /*var placeSearch, autocomplete;

      function initAutocomplete() {
        // Create the autocomplete object, restricting the search predictions to
        // geographical location types.
        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById('address'), {types: ['geocode']});

        // Avoid paying for data that you don't need by restricting the set of
        // place fields that are returned to just the address components.
        autocomplete.setFields(['address_component']);

        // When the user selects an address from the drop-down, populate the
        // address fields in the form.
        autocomplete.addListener('place_changed', fillInAddress);
      }

      function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();

        // Get each component of the address from the place details,
        // and then fill-in the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
          var addressType = place.address_components[i].types[0];
          if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            document.getElementById(addressType).value = val;
          }
        }
      }

      // Bias the autocomplete object to the user's geographical location,
      // as supplied by the browser's 'navigator.geolocation' object.
      function geolocate() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            var circle = new google.maps.Circle(
                {center: geolocation, radius: position.coords.accuracy});
            autocomplete.setBounds(circle.getBounds());
          });
        }
      }*/
    </script>
    <!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCYUjauXQc_DkI38BeieHhPgf_RuELpAfI&libraries=places&callback=initAutocomplete"
  async defer></script>-->
  <script type="text/javascript" src="<?php echo get_template_directory_uri();?>/assets/js/zxcvbn.js">
  </script>
  <script type="text/javascript"> 

      function check_pass_strength() {
          var pass1 = jQuery('#pass1').val(), strength; //console.log(pass1);

          jQuery('#pass-strength-result').removeClass('short bad good strong empty');
          if ( ! pass1 || '' ===  pass1.trim() ) {
            jQuery('.progress-bar').css('width','0%');
            jQuery( '#pass-strength-result' ).addClass( 'empty' ).html( 'Unknown' );
            return;
          }

          var passwordStrength = ''; 
          if ( 'undefined' === typeof window.zxcvbn ) {
            // Password strength unknown.
            passwordStrength -1;
          } else {
            strength = zxcvbn( pass1, {} ); 
            //console.log(strength);
            passwordStrength = strength.score;
          }  

          switch ( strength.score ) {
             case -1:
              jQuery( '#pass-strength-result' ).addClass( 'bad' ).text( 'Unknown' );
              jQuery('.progress-bar').css('width','0%');
              break;
            case 0 || 1:
              jQuery( '#pass-strength-result' ).addClass( 'bad' ).text( 'Normal' );
              jQuery('.progress-bar').css('width','40%');
              break;
            case 2:
              jQuery('#pass-strength-result').addClass('bad').text( 'Bad' );
              jQuery('.progress-bar').css('width','60%');
              break;
            case 3:
              jQuery('#pass-strength-result').addClass('good').text( 'Good' );
              jQuery('.progress-bar').css('width','80%');
              break;
            case 4:
              jQuery('#pass-strength-result').addClass('strong').text( 'Strong' );
              jQuery('.progress-bar').css('width','100%');
              break;
            case 5:
              jQuery('#pass-strength-result').addClass('short').text( 'Mismatch' );
              jQuery('.progress-bar').css('width','0%');
              break;
            default:
              jQuery('#pass-strength-result').addClass('short').text( 'Default' );
              jQuery('.progress-bar').css('width','20%');
          }
      }

      jQuery(document).ready( function($) {
        $( '#pass1' ).on( 'keyup', check_pass_strength );
      });
  
  </script> 
<?php get_footer('home'); ?>