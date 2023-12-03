<?php
/**
 * Template Name: Forgot Password
 */

// Is user login, then redirect to dashboard
if (is_user_logged_in() ) {
  wp_redirect ( site_url('dashboard') );
  exit;
}

get_header('home');
?>
    
	 	<!-- Content -->
	  	<div class="section-signup">
		    <div class="left-form">
		      <a href="<?php echo site_url(); ?>" class="back-to">
		        <i class="icon icon-left-arrow"></i>
		      </a>
		    </div>
		    <div class="right-form">
		     	<div class="logo-form">
			        <p>Get Started With</p> 
			        <img src="<?php echo get_template_directory_uri();?>/assets/images/logo.svg" class="img-fluid" alt="College Bound">
		      	</div> 
		      	<div class="box-sign">
			        <div class="message-content">
	                 	<?php
	                    global $wpdb;

						$error = '';
						$success = '';

						// check if we're in reset form
						if( isset( $_POST['action'] ) && 'reset' == $_POST['action'] )
						{
							$email = trim($_POST['user_login']);

							if( empty( $email ) ) {
								$error = 'Enter a e-mail address..';
							} else if( ! is_email( $email )) {
								$error = 'Invalid e-mail address.';
							} else if( ! email_exists($email) ) {
								$error = 'There is no user registered with that email address.';
							} else {

								// lets generate our new password
								$random_password = wp_generate_password( 12, false );

								// Get user data by field and data, other field are ID, slug, slug and login
								$user = get_user_by( 'email', $email );

								$update_user = wp_update_user( array (
										'ID' => $user->ID,
										'user_pass' => $random_password
									)
								);

								// if  update user return true then lets send user an email containing the new password
								if( $update_user ) {


									$first_name = $user->user_firstname;
									$last_name = $user->user_lastname;
									$email = $user->user_email; 

									/*$to = $email; 
									$subject = 'Your new password';
									
									$body = "";
									
									$body .= "Hi \n";
									$body .= $first_name." ".$last_name.", \n";
									$body .= "Thanks for contacting us. Please use your new password to login to your account: ".$random_password."\n";
									$body .="<a href='".site_url()."/login'To Continue Login"; 
										
									$from = 'From: '.get_option('name').'<support@collegebound.app>';
									$headers = array('Content-Type: text/html; charset=UTF-8', $from);  
									
									$mail = wp_mail( $to, $subject, $body, $headers );*/
									
									$to = $email; 
									$subject = "Your new password";

									$mail_args = array( 	
										"mail_subject" 	=> $subject,
										"main_title" 	=> "Hi ".$first_name." ".$last_name,
										"main_content" 	=> "Thanks for contacting us. Please use your new password to login to your account: ".$random_password,
										"button_text" 	=> "Click Here To Login",
										"button_link"	=> site_url().'\login',
										//"header_logo" 	=> $site_logo,
										//"footer_logo"	=> get_stylesheet_directory_uri()."/images/front/mail_footer_logo.png",
										"footer_left_text"	=> "Copyright Collegebound &copy;".date('Y'),
										"footer_right_text" => 'Powered By <a href="https://www.collegebound.app/">Collegebound</a>'
									);
									$body = set_mail_content($mail_args);
									$from = 'From: '.get_option('blogname').'<info@collegebound.app>';
									$headers = array('Content-Type: text/html; charset=UTF-8', $from);
									 
									$mail  = wp_mail( $to, $subject, $body, $headers );


									if( $mail )
										$success = 'Check your email address for you new password.';
									else
										$error = 'Oops something went wrong updaing your account.';
								} else {
									$error = 'Oops something went wrong updaing your account.';
								}

							}
							
							if( ! empty( $error ) ){ ?>
								<script type="text/javascript">
								jQuery(document).ready(function(){
									Swal.fire({
						            title: "",
						            text: "<?php echo $error;?>",
						            icon: "error",
						            confirmButtonText: "Okay",
						          });
								});
								</script>
							<?php }

							if( ! empty( $success ) ) { ?>
								<script type="text/javascript">
								jQuery(document).ready(function(){
									Swal.fire({
						            title: "",
						            text: "<?php echo $success;?>",
						            icon: "success",
						            confirmButtonText: "Okay",
						          });
								});
								</script>
							<?php }
						} 
						?> 
					</div>

					<?php        
					// If user is already logged in.
	                if ( is_user_logged_in() ) : ?> 
	                    <div class="logout"> 
	                        <?php 
	                            echo 'Hello'; 
	                            echo $user_login; 
	                        ?> 
	                        </br> 
	                        <?php echo 'You are already logged in.'; ?>

	                    </div> 
	                    <a id="wp-submit" href="<?php echo wp_logout_url(); ?>" title="Logout">
	                        <?php echo 'Logout'; ?>
	                    </a> 
	                <?php // If user is not logged in.
	                    else: 
	                ?>

		            <?php if( empty( $success ) ) { ?> 
				        <div class="section-title">
				          <h4 class="title-sm text-center">Reset Your Password</h4>
				        </div> 
				        <p class="text-center">Please enter your email address. You will receive a new password via email.</p>

				        <form class="login_form" autocomplete="off" method="post">       
				          <div class="form-input"> 
				          	<label class="form-label">Email</label>
				            <?php $user_login = isset( $_POST['user_login'] ) ? $_POST['user_login'] : ''; ?>
							<input type="text" name="user_login" id="user_login" class="form-control" value="<?php echo $user_login; ?>" required/>
				            <i class="icon icon-mail"></i>
				          </div>

				          <div class="form-input">
				          	<input type="hidden" name="action" value="reset" />
				            <button id="submit" type="submit" class="btn btn-block btn-primary text-capitalize">Get New Password</button>
				          </div>
				        </form>
				     </div>
				     <?php } else {?>
						<div class="login_form">
		            	<div id="loginform" class="text-center"> 
		            		<a class="btn btn-primary btn-block" href="<?php echo site_url().'/login'; ?>">Continue Login</a>
						</div> 
					<?php } ?>
			    <?php endif;?>
				</div>
		    </div>
	  	</div>

	    <script type="text/javascript"> 
	      function validateEmail(thisemail) 
	      {
	          var email  = thisemail.value;
	          var re = /^(([a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}|edu)\b))$/;
	          
	          if( ! re.test(email) ){
	            thisemail.value = '';
	            alert("Email must be end with '.edu' "); 
	          } 
	      } 
	    </script>
<?php get_footer('home'); ?>