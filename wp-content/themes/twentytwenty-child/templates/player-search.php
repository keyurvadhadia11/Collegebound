<?php
/**
 * Template Name: Player Search
 */ 

global $current_user; // Use global
get_currentuserinfo(); 

$current_page = get_the_id();

$userId = get_current_user_id();  

// Is user login, then redirect to dashboard
if ( !is_user_logged_in() && !current_user_can('administrator') ) {
  wp_redirect ( site_url('login') );
  exit;
}

if( isset($_POST['action']) && $_POST['action'] == 'search-save')
{
    update_user_meta( $userId, 'search_criteria', $_POST['search_criteria']);
}

$positions = 
  array(
    "Point Guard",
    "Shooting Guard",
    "Small Forward",
    "Power Forward",
    "Center",
  ); 

$states = 
  array(
    "AL" => "Alabama",
    "AK" => "Alaska",
    "AZ" => "Arizona",
    "AR" => "Arkansas",
    "CA" => "California",
    "CO" => "Colorado",
    "CT" => "Connecticut",
    "DE" => "Delaware",
    "FL" => "Florida",
    "GA" => "Georgia",
    "HI" => "Hawaii",
    "ID" => "Idaho",
    "IL" => "Illinois",
    "IN" => "Indiana",
    "IA" => "Iowa",
    "KS" => "Kansas",
    "KY" => "Kentucky",
    "LA" => "Louisiana",
    "ME" => "Maine",
    "MD" => "Maryland",
    "MA" => "Massachusetts",
    "MI" => "Michigan",
    "MN" => "Minnesota",
    "MS" => "Mississippi",
    "MO" => "Missouri",
    "MT" => "Montana",
    "NE" => "Nebraska",
    "NV" => "Nevada",
    "NH" => "New Hampshire",
    "NJ" => "New Jersey",
    "NM" => "New Mexico",
    "NY" => "New York",
    "NC" => "North Carolina",
    "ND" => "North Dakota",
    "OH" => "Ohio",
    "OK" => "Oklahoma",
    "OR" => "Oregon",
    "PA" => "Pennsylvania",
    "RI" => "Rhode Island",
    "SC" => "South Carolina",
    "SD" => "South Dakota",
    "TN" => "Tennessee",
    "TX" => "Texas",
    "UT" => "Utah",
    "VT" => "Vermont",
    "VA" => "Virginia",
    "WA" => "Washington",
    "WV" => "West Virginia",
    "WI" => "Wisconsin",
    "WY" => "Wyoming" 
);
$gpaLists = 
  array(
      "0.0",
      "1.0",
      "1.3",
      "1.7",
      "2.0",
      "2.3",
      "2.7",
      "3.0",
      "3.3",
      "3.7",
      "4.0",
  ); 

$image_path = get_template_directory_uri().'/assets/images';

get_header('admin');

//echo '<pre>'; print_r($_GET);
?>
<!-- COntent -->
    <div class="container-fluid">
        <div class="row gutters-10">
          <div class="col-xl-12">
           
            <form id="coachSearchForm" autocomplete="off">
              <div class="section-title">
                <h3 class="title-xs">Search</h3>
              </div>

              <div class="card-team-list-wrap card-team-search">
               
                  <div class="form-group form-search">
                    <i class="icon icon-search"></i>
                    <input type="text" class="form-control" name="search" value="<?php echo $_GET['search'];?>" placeholder="Search Players..">
                  </div>                
              </div>

              <div class="section-title">
                <h3 class="title-xs">Search Criteria</h3>
              </div>

              <div class="card-search-criteria">
                <div class="row">
                  <div class="col-xl-3 col-lg-6">
                    <div class="form-group">
                      <label>Position</label>

                      <select class="custom-select select2" name="player_postion[]" multiple="multiple">
                        <?php 
                            $player_postion = array();
                            if( !empty($_REQUEST['player_postion']) ){
                              $player_postion = (array)$_REQUEST['player_postion'];
                            }
                          ?>
                          <?php foreach( $positions as $poskey => $position ) { ?>
                            <?php
                             $selected = '';
                              if( in_array( $position, $player_postion) ) {
                                $selected = 'selected="selected"';
                              }
                            ?>
                            <option value="<?php echo $position;?>" <?php echo $selected;?> ><?php echo $position;?></option>
                          <?php } ?> 
                      </select>
                    </div>
                  </div>

                  <div class="col-xl-3 col-lg-6">
                    <div class="form-group">
                      <label>GPA</label>
                      <select  class="custom-select select2"  name="player_gpa[]" multiple="multiple">
                          <option value=""></option>
                          <?php
                            $player_gpa = array();
                            if( !empty($_REQUEST['player_gpa']) ){
                              $player_gpa = (array)$_REQUEST['player_gpa'];
                            } 
                          ?>
                          <?php foreach( $gpaLists as $gpakey => $gpa ) { ?>
                            <?php
                             $selected = '';
                              if( in_array( $gpa, $player_gpa ) ){
                                $selected = 'selected="selected"';
                              }
                            ?>
                            <option value="<?php echo $gpa;?>" <?php echo $selected;?> ><?php echo $gpa;?></option>
                          <?php } ?> 
                        </select>
                    </div>
                  </div>

                  <div class="col-xl-3 col-lg-6">
                    <div class="form-group">
                      <label>State</label>
                      <select class="custom-select select2" name="player_state[]"  multiple="multiple">
                          <option value=""></option>
                          <?php 
                            $player_state = array();
                            if( !empty($_REQUEST['player_state']) ){
                              $player_state = (array)$_REQUEST['player_state'];
                            }
                          ?>
                          <?php foreach( $states as $statekey => $state ) { ?>
                            <?php
                             $selected = '';
                               if( in_array( $statekey, $player_state ) ){
                                $selected = 'selected="selected"';
                              }
                            ?>
                            <option value="<?php echo $statekey;?>" <?php echo $selected;?> ><?php echo $state;?></option>
                          <?php } ?> 
                        </select>
                    </div>
                  </div>

                  <div class="col-xl-3 col-lg-6">
                    <div class="form-group">
                      <label>ACT</label>
                      <select class="custom-select select2" name="player_act[]"  multiple="multiple">
                          <option value=""></option>
                          <?php 
                            $player_act = array();
                            if( !empty($_REQUEST['player_act']) ){
                              $player_act = (array)$_REQUEST['player_act'];
                            }
                          ?>
                          <?php for( $act_no=0; $act_no<37 ; $act_no++ ) { ?>
                            <?php
                             $selected = '';
                               if( in_array( $act_no, $player_act ) ){
                                $selected = 'selected="selected"';
                              }
                            ?>
                            <option value="<?php echo $act_no;?>" <?php echo $selected;?> ><?php echo $act_no;?></option>
                          <?php } ?> 
                        </select>
                    </div>
                  </div>

                </div> 
              </div>
            </form>

            <div class="section-title">
              <h3 class="title-xs">Search Result</h3>
            </div>

            <div class="nav-graduates-tabs">
              <div class="tab-content">
                <div class="row row-equal gutters-10">
                  <?php
                      $query_arg = array(
                          'role' => 'player',
                      );

                      /*if( !empty($_GET['search']) ){
                          $search_term = $_REQUEST['search']; 
                          $query_arg['search']     = '*' . esc_attr( $search_term ) . '*'; 
                          $query_arg['search_columns'] = array( 'user_login', 'user_nicename', 'user_email', 'first_name', 'last_name' );
                      }*/

                      if( !empty($_GET['search']) ){
                        $search = $_GET['search'];
                        $searchArray = explode(" ", trim($search));

                        if( count($searchArray) > 1 ){
                          $firstNameSearch = $searchArray[0];
                          $lastNameSearch = $searchArray[1];
                        } else {
                          $firstNameSearch = trim($search);
                          $lastNameSearch = trim($search);
                        }

                        $query_arg['meta_query'][] = array(
                            'relation' => 'OR',
                            array(
                              'key' => 'first_name',
                              'value' => esc_attr($firstNameSearch),
                              'compare' => 'LIKE',
                            ),
                            array(
                              'key' => 'last_name',
                              'value' => esc_attr($lastNameSearch),
                              'compare' => 'LIKE',
                            ),
                            array(
                              'key' => 'universitycollege',
                              'value' => esc_attr($search),
                              'compare' => 'LIKE',
                            ), 
                        );
                      }
                       
                      // For Player Position
                      if( isset($_GET['player_postion']) )
                      {
                        if( count($_GET['player_postion']) > 1){
                          $position_arg = array(
                              'relation' => 'OR', 
                          );
                        }
                        foreach ((array)$_GET['player_postion'] as $key => $position) {
                            $position_arg[] =  array(
                               'key' => 'player_postion',
                               'value' => $position,
                               'compare' => 'LIKE',
                           );
                        }
                        $query_arg['meta_query'][] = $position_arg;
                      }

                      // For Player GPA
                      if( isset($_GET['player_gpa']) )
                      {
                        if( count($_GET['player_gpa']) > 1){
                          $gpa_arg = array(
                              'relation' => 'OR', 
                          );
                        } 
                        foreach ((array)$_GET['player_gpa'] as $key => $gpa) {
                            $gpa_arg[] =  array(
                               'key' => 'player_gpa',
                               'value' => $gpa,
                               'compare' => 'LIKE',
                           );
                        }
                        $query_arg['meta_query'][] = $gpa_arg;
                      }

                      // For Player State
                      if( isset($_GET['player_state']) )
                      {
                        if( count($_GET['player_state']) > 1){
                          $state_arg = array(
                              'relation' => 'OR', 
                          );
                        }  
                        foreach ((array)$_GET['player_state'] as $key => $state) {
                            $state_arg[] =  array(
                               'key' => 'player_state',
                               'value' => $state,
                               'compare' => 'LIKE',
                           );
                        }
                        $query_arg['meta_query'][] = $state_arg;
                      }

                      // For Player ACT
                      if( isset($_GET['player_act']) )
                      {
                        if( count($_GET['player_act']) > 1){
                          $act_arg = array(
                              'relation' => 'OR', 
                          );
                        }
                        foreach ((array)$_GET['player_act'] as $key => $act) {
                            $act_arg[] =  array(
                               'key' => 'player_act',
                               'value' => $act,
                               'compare' => 'LIKE',
                           );
                        }
                        $query_arg['meta_query'][] = $act_arg;
                      }

                      // For Player SAT
                      if( isset($_GET['player_sat']) )
                      {
                        if( count($_GET['player_sat']) > 1){
                          $sat_arg = array(
                              'relation' => 'OR', 
                          );
                        }
                        foreach ((array)$_GET['player_sat'] as $key => $sat) {
                            $sat_arg[] =  array(
                               'key' => 'player_sat',
                               'value' => $sat,
                               'compare' => 'LIKE',
                           );
                        }
                        $query_arg['meta_query'][] = $sat_arg;
                      }

                       // For Player Height
                      if( isset($_GET['player_sat']) )
                      {
                        if( count($_GET['player_sat']) > 1){
                          $sat_arg = array(
                              'relation' => 'OR', 
                          );
                        }
                        foreach ((array)$_GET['player_sat'] as $key => $sat) {
                            $sat_arg[] =  array(
                               'key' => 'player_sat',
                               'value' => $sat,
                               'compare' => 'LIKE',
                           );
                        }
                        $query_arg['meta_query'][] = $sat_arg;
                      }

                      //cho '<pre>'; print_r($query_arg);

                      $player_query = new WP_User_Query( $query_arg ); 

                      $players = $player_query->get_results();
                      //echo $player_query->request;

                      $noPlayers = $player_query->get_total();

                      if($noPlayers == 0){
                      echo 'No Record Found';
                      } else { 
                      foreach ($players as $key => $player) { 
                  ?> 
                  <div class="col-xl-6 col-lg-12">
                      <div class="card-graduates card-graduates-contact">
                           <?php
                            $first_name = get_user_meta( $player->ID, 'first_name', true);
                            $last_name = get_user_meta( $player->ID, 'last_name', true);
                            $universitycollege = get_user_meta( $player->ID, 'universitycollege', true);
                            $player_postion = get_user_meta( $player->ID, 'player_postion', true);
                            $profile_view_url = add_query_arg(array('user_id'=>$player->ID),site_url('player-profile-view')); 

                            $dpImageUrl = getUiAvtarUrl( $first_name.' '.$last_name );
                             
                            $attachment_id = get_user_meta( $player->ID, 'profile-pic', true);
                            if( $attachment_id  && file_exists(get_attached_file($attachment_id)) )
                            $dpImage = wp_get_attachment_image_src( $attachment_id, 'thumbnail');

                            if($dpImage)
                                $dpImageUrl = $dpImage[0];
                          ?> 
                          <div class="card-graduates-image">
                              <img src="<?php echo $dpImageUrl ; ?>" class="img-fluid" alt="">
                          </div>
                          <div class="card-graduates-content">
                            <h4><?php echo $first_name.' '.$last_name;?></h4>
                            <ul class="list-unstyled">
                                <li><i class="icon icon-sm-forward"></i><?php echo $player_postion;?></li>
                                <li><i class="icon icon-sm-school"></i><?php echo $universitycollege;?></li>
                            </ul>
                          </div> 
                          <div class="card-graduates-action">
                              <a href="<?php echo $profile_view_url;?>" class="btn btn-sm btn-primary">Contact</a>
                          </div>
                      </div>
                  </div> 
                  <?php } } ?>
                </div>
              </div>
            </div>

            <div class="pt-3 text-center">
              <form method="post"> 
                <?php 
                  $search_criteria = get_user_meta( $userId, 'search_criteria', true);
                  if( empty($search_criteria) ){
                    $search_criteria = site_url('coach-search');
                  }
                ?>
                <a href="<?php echo $search_criteria;?>" class="btn btn-sm btn-outline-secondary">Reset Search</a>
                <span class="py-2 d-inline-block"></span>
                <button id="save-search" class="btn btn-sm btn-primary">Save Search</button>
                <input type="hidden" name="action" value="search-save">
                <?php $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
                <input type="hidden" name="search_criteria" value="<?php echo $actual_link;?>">
              </form>
            </div>
          </div>
        </div>
    </div>
    <script type="text/javascript">
      jQuery(document).ready(function($){
        $('#coachSearchForm').on('change', 'input', function(){
          $('#coachSearchForm').submit();
        });
        $('#coachSearchForm').on('change', 'select', function(){
          $('#coachSearchForm').submit();
        });
      })
    </script>
<?php get_footer('admin'); ?>