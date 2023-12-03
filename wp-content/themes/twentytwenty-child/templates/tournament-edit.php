<?php
/**
 * Template Name: Tournament Edit
 */
session_start();
global $wpdb;

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
        wp_redirect(site_url('tournament-packets'));  
        die(); 

  } else {
    $_SESSION['alert'] = array('status' => 'danger' , 'content' => "Tournament Not Found" );
    wp_redirect(site_url('tournament-packets'));  
    die(); 
  }  
} 

// If Form Submit
if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) { 
  $error = '';
  global $wpdb;
  
  $tournament_id  = !empty($_POST['tournament-id']) ? $wpdb->escape($_POST['tournament-id']) : '';
  
  $name       = !empty($_POST['tournament_name']) ? $wpdb->escape($_POST['tournament_name']) : '';
  $address    = !empty($_POST['address']) ? $wpdb->escape($_POST['address']) : '';
  $start      = !empty($_POST['start']) ? $wpdb->escape($_POST['start']) : '';
  $end      = !empty($_POST['end']) ? $wpdb->escape($_POST['end']) : '';  
  $price    = !empty($_POST['price']) ? array_filter($_POST['price']) : array();
  $status     = !empty($_POST['status']) ? $wpdb->escape($_POST['status']) : 'in-active'; 

  $streaming_code      = !empty($_POST['streaming_code']) ? $_POST['streaming_code'] : ''; 
  
  // Add Tournament
  if( empty($tournament_id) ){

    if( !empty($name) && !empty($address) && !empty($start) && !empty($end) && !empty($status) ) 
    { 
      /**---------- Upload Tournament Logo Start --------- **/
        $logo_id = '';
        //if ( isset($_FILES['logoImage']) && ! empty($_FILES['logoImage']) ) {
        if ( file_exists($_FILES['logoImage']['tmp_name']) || is_uploaded_file($_FILES['logoImage']['tmp_name']))
        {
          if ( ! function_exists( 'wp_handle_upload' ) ) {
              require_once( ABSPATH . 'wp-admin/includes/image.php' ); 
              require_once( ABSPATH . 'wp-admin/includes/file.php' );  
              require_once( ABSPATH . 'wp-admin/includes/media.php' );
          } 
          // Media Upload File
          $attachment_id = media_handle_upload('logoImage', 1);

          if($attachment_id){
            $logo_id = $attachment_id;
          }
        }
      /**---------- End Upload Tournament Logo --------- **/

      $startDate = DateTime::createFromFormat("m/d/Y" , $start);
      $start = $startDate->format('Y-m-d H:i:s');

      $endDate = DateTime::createFromFormat("m/d/Y" , $end);
      $end = $endDate->format('Y-m-d H:i:s');

      $wpdb->insert( 
                    'wp_cb_tournament', 
                    array( 
                      	'user_id'       	=> get_current_user_id(),
                      	'name'        		=> $name,
                        'address'           => $address,
                        'start'             => $start,
                        'end'               => $end,
                        'logo'              => $logo_id,
                        'price'             => json_encode($price),
                        'streaming_code'	=> $streaming_code,
                        'status'            => $status
                    ), 
                    array( 
                        '%d','%s','%s','%s','%s','%s','%s','%s','%s'
                    ) 
                  );

          if( ! $wpdb->insert_id ) {
            $error = "Somethig will wrong, Please tray again";
          } else {
            if(!empty($_FILES['csv_file']['name']))
        {
          cb_import_csv_file($wpdb->insert_id);
        }
          }
    } else {
      $error = "Please fillup all required fields";
    }

    if( !empty($error) ) {

      session_start();
      $_SESSION['alert'] = array('status' => 'danger' , 'content' => $error );

    } else {

      session_start();
      $_SESSION['alert'] = array('status' => 'success' , 'content' => "Tournament Add Successfully" );
      wp_redirect(site_url('tournament-packets'));
      die();

    }
      
  }
  // Edit Tournament
  else 
  {
      if( !empty($tournament_id) && !empty($name) && !empty($address) && !empty($start) && !empty($end) && !empty($status) ) 
      {   
        /**---------- Upload Tournament Logo Start --------- **/
          $logo_id = '';
          //if ( isset($_FILES['logoImage']) && ! empty($_FILES['logoImage']) ) {
          if ( file_exists($_FILES['logoImage']['tmp_name']) || is_uploaded_file($_FILES['logoImage']['tmp_name']))
          { 
            if ( ! function_exists( 'wp_handle_upload' ) ) {
                require_once( ABSPATH . 'wp-admin/includes/image.php' ); 
                require_once( ABSPATH . 'wp-admin/includes/file.php' );  
                require_once( ABSPATH . 'wp-admin/includes/media.php' );
            } 
            // Media Upload File
            $attachment_id = media_handle_upload('logoImage', 1); 
            
            if($attachment_id){
              $logo_id = $attachment_id;
            }
          }
        /**---------- End Upload Tournament Logo --------- **/
 
        $startDate = DateTime::createFromFormat("m/d/Y" , $start);
        $start = $startDate->format('Y-m-d H:i:s');

        $endDate = DateTime::createFromFormat("m/d/Y" , $end);
        $end = $endDate->format('Y-m-d H:i:s');
      
        if( !empty($logo_id) )
        {
          $update_status = $wpdb->update( 
            $wpdb->prefix.'cb_tournament', 
            array(  
              	'name'    		=> $name,
	            'address'   	=> $address,
	            'start'     	=> $start,
	            'end'       	=> $end,
	            'logo'    		=> $logo_id, 
	            'price'   		=> json_encode($price),
	            'streaming_code'=> $streaming_code,
	            'status'  		=> $status,
	            'update_at' 	=> date('Y-m-d H:i:s')
                  ), 
            array( 'id' => $tournament_id ), 
            array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ), 
            array( '%d' ) 
          ); 
        } else {
          $update_status = $wpdb->update( 
            $wpdb->prefix.'cb_tournament', 
            array( 
              'name'    => $name,
                        'address'   => $address,
                        'start'     => $start,
                        'end'       => $end, 
                        'status'  => $status,
                        'price'   => json_encode($price),
                        'streaming_code'=> $streaming_code,
                        'update_at' => date('Y-m-d H:i:s')
                  ), 
            array( 'id' => $tournament_id ), 
            array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ), 
            array( '%d' ) 
          ); 
        } 

            if( ! $update_status ) {
              $error = "Somethig will wrong, Please tray again";
            } else {

              if(!empty($_FILES['csv_file']['name']))
          {
            // Remove Old Data
            cb_remove_import_data($tournament_id);
            // Insert New Data
            cb_import_csv_file($tournament_id);
          }

            }
      } else {
        $error = 'Please fillup all required fields';
      }

      if( !empty($error) ) {

        session_start();
        $_SESSION['alert'] = array('status' => 'danger' , 'content' => $error);

      } else {

        session_start();
        $_SESSION['alert'] = array('status' => 'success' , 'content' => "Tournament Update Successfully" );
        wp_redirect(site_url('tournament-packets'));  
        die(); 
      }  
  }
  
}

$tournament = '';
if( isset($_GET['action']) && $_GET['action'] == 'edit' && !empty($_GET['id']) ) {
    $tournament_results = $wpdb->get_results("SELECT * FROM `wp_cb_tournament` where `id`=".$_GET['id']);
    if( isset($tournament_results[0]) ){
        $tournament = $tournament_results[0];
    }
}

$price = array();
if( isset($tournament->price) ){
    $price = (array)json_decode($tournament->price);
} 

$assetsPath = get_template_directory_uri().'/assets/';
get_header('admin');
?>
     <div class="container-fluid">
        <div class="row gutters-10">
          <div class="col-xl-12">
            <div class="section-title">
              <h3 class="title-xs"><?php echo ( isset($_GET['action']) && $_GET['action'] == 'edit') ? 'Edit' : 'Add';?> Tournament</h3>
            </div>
            <div class="card-profile">
              <div class="row">
                <div class="col-md-12">
                  
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

                  <div class="tournament-add-wrap">
                    <form class="validate_form" autocomplete="off" method="post" enctype="multipart/form-data">
                      <div class="row gutters-10">
                        <div class="col-lg-12">
                          <div class="form-group">
                            <div class="custom-file-upload text-center">
                              <input type="file" name="logoImage" id="logoImage" accept="image/jpg, image/jpeg, image/png" onchange="checkImageFile(this);" />
                              <?php 
                                $image_url = $assetsPath."images/image-upload01.png";
                                if( !empty($tournament->logo) ){
                                    $attachment = wp_get_attachment_image_src( $tournament->logo, 'full');
                                    if( $attachment )
                                    $image_url = $attachment[0];
                                }
                              ?> 
                              <img id="logoImageIMG" src="<?php echo $image_url;?>" class="img-fluid" alt="" width="450px" height="297px" />
                              <p>Upload Tournament Image</p>
                            </div>
                          </div>
                        </div>
                        <script type="text/javascript">
                          function readURL(input) {

                            if (input.files && input.files[0]) {
                              var reader = new FileReader();

                              reader.onload = function(e) {
                                jQuery('#logoImageIMG').attr('src', e.target.result);
                              }

                              reader.readAsDataURL(input.files[0]);
                            }
                          }

                          jQuery("#logoImage").change(function() {
                            readURL(this);
                          });
                        </script>

                        <div class="col-lg-6">
                          <div class="form-input">
                            <label class="form-label">Tournament Name</label>
                            <input type="text" class="form-control" id="tournament_name" name="tournament_name" value="<?php echo isset($tournament->name) ? $tournament->name : '';?>" required>
                            <i class="icon icon-sm-tournament"></i>
                          </div>                          

                          <div class="form-input">
                            <label class="form-label">Tournament Start Date</label>
                            <input type="text" class="form-control" id="txtFrom" name="start" value="<?php echo isset($tournament->start) ? date('m/d/Y', strtotime($tournament->start)) : '';?>" required>
                            <i class="icon icon-date"></i>
                          </div>

                          <div class="form-input">
                            <label class="form-label">Price for D1</label>
                            <input type="number" class="form-control" name="price[division_1]"  value="<?php echo isset($price['division_1']) ? $price['division_1'] : '';?>" required>
                            <i class="icon icon-coin"></i>
                          </div>

                          <div class="form-input">
                            <label class="form-label">Price for D3</label>
                            <input type="number" class="form-control" name="price[division_3]" value="<?php echo isset($price['division_3']) ? $price['division_3'] : '';?>" required>
                            <i class="icon icon-coin"></i>
                          </div>

                          <div class="form-input">
                            <label class="form-label">Price for NAIA</label>
                            <input type="number" class="form-control" name="price[naia]" value="<?php echo isset($price['naia']) ? $price['naia'] : '';?>" required>
                            <i class="icon icon-coin"></i>
                          </div>
                        </div>

                        <div class="col-lg-6">
                          <div class="form-input">
                            <label class="form-label">Tournament Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="<?php echo isset($tournament->address) ? $tournament->address : '';?>" required>
                            <i class="icon icon-address"></i>
                          </div>

                          <div class="form-input">
                            <label class="form-label">Tournament End Date</label>
                            <input type="text" class="form-control" id="txtTo" name="end"  value="<?php echo isset($tournament->end) ? date('m/d/Y', strtotime($tournament->end)) : '';?>" required>
                            <i class="icon icon-date"></i>
                          </div>

                          <div class="form-input">
                            <label class="form-label">Price for D2</label>
                            <input type="number" class="form-control" name="price[division_2]" value="<?php echo isset($price['division_2']) ? $price['division_2'] : '';?>" required>
                            <i class="icon icon-coin"></i>
                          </div>

                          <div class="form-input">
                            <label class="form-label">Price for JUCO</label>
                            <input type="number" class="form-control" name="price[juco]" value="<?php echo isset($price['juco']) ? $price['juco'] : '';?>" required>
                            <i class="icon icon-coin"></i>
                          </div>

                          <div class="form-input">
                            <div class="d-flex justify-content-between align-items-center pt-3">
                              <h5 class="mb-0">Event Status</h5>

                              <div class="custom-control custom-switch">
                                <?php
                                    $status_checked = 'checked="checked"';
                                    if( isset($tournament->status) ){
                                        if( $tournament->status != 'active'){
                                            $status_checked ='';
                                        }
                                    }
                                ?>
                                <input type="checkbox" class="custom-control-input" id="status" name="status" value="active" <?php echo $status_checked;?> >
                                <label class="custom-control-label" for="status"></label>
                              </div>
                            </div>
                          </div>
                        </div>

                         <div class="col-lg-12">
                           <div class="form-input mt-5">
                            <label class="form-label">Live Match Streaming Code (Optional)</label>
                            <textarea class="form-control" name="streaming_code" style="height: 100px;"><?php echo isset($tournament->streaming_code) ? stripslashes($tournament->streaming_code) : '';?></textarea>
                            <i class="icon icon-coin11"></i>
                          </div>
                        </div>

                        <div class="col-lg-12">
                          <div class="form-group mt-5">
                            <div class="custom-file-upload text-center">
                              <input type="file" name="csv_file" id="csv_file"  onchange="checkCsvFile(this);" />
                              <img src="<?php echo $assetsPath;?>images/image-upload02.png" class="img-fluid" alt="" />
                              <p class="mt-2">Upload Tournament CSV</p>
                            </div>
                          </div>
                        </div>

                        <div class="col-lg-12">
                          <div class="form-group text-center">
                            <?php if( isset($_GET['action']) && $_GET['action'] == 'edit' && !empty($tournament)) {?>  
                            <input type="hidden" name="tournament-id" value="<?php echo $tournament->id;?>">
                            <button class="btn btn-sm btn-xl btn-primary">Edit Tournament</button>
                            <!--<input type="hidden" name="action" value="edit-tournament">-->
                            <?php } else { ?>
                            <!--<input type="hidden" name="action" value="add-tournament">-->
                            <button type="submit" class="btn btn-sm btn-xl btn-primary">Create Tournament</button>
                            <?php } ?>  
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
	   
    <!--########################################## -->

    <script type="text/javascript">
       function removeTournament(){
          var r = confirm("Are you want to Delete This Tournament!");
          if (r == true) {
            <?php
              $removeLink  = add_query_arg( array('tournament-id'=>$tournament->id, 'action' => 'remove'), get_permalink());
            ?>
            window.location.replace("<?php echo $removeLink; ?>");
          } 
       }
     </script>
     <script> 
      var placeSearch, autocomplete;

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
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCYUjauXQc_DkI38BeieHhPgf_RuELpAfI&libraries=places&callback=initAutocomplete"
  async defer></script>
<?php get_footer('admin'); ?>