<?php
/**
 * Template Name: Coach Search
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

$image_path = get_template_directory_uri().'/assets/images';

get_header('player');
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
                    <input type="text" class="form-control" name="search" value="<?php echo $_GET['search'];?>" placeholder="Search Coach..">
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
                          'role' => 'coach',
                      );

                      /*if( !empty($_GET['searchPlayer']) ){
                          $search_term = $_REQUEST['searchPlayer']; 
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
                            array(
                              'key' => 'address',
                              'value' => esc_attr($search),
                              'compare' => 'LIKE',
                            ),
                        );
                      }

                       // For Player Height
                      if( isset($_GET['player_sat']) && false )
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
                               //'compare' => 'LIKE',
                           );
                        }
                        $query_arg['meta_query'][] = $sat_arg;
                      }

                      //cho '<pre>'; print_r($query_arg);

                      $search_query = new WP_User_Query( $query_arg ); 

                      $searchResults = $search_query->get_results();
                      //echo $search_query->request;

                      $noSearchResults = $search_query->get_total();

                      if($noSearchResults == 0){
                      echo 'No Record Found';
                      } else { 
                      foreach ($searchResults as $key => $coach) { 
                  ?> 
                  <div class="col-xl-6 col-lg-12">
                      <div class="card-graduates card-graduates-contact">
                           <?php
                            $first_name = get_user_meta( $coach->ID, 'first_name', true);
                            $last_name = get_user_meta( $coach->ID, 'last_name', true);
                            $universitycollege = get_user_meta( $coach->ID, 'universitycollege', true);
                            $address = get_user_meta( $coach->id, 'address', true);
                            $profile_view_url = add_query_arg(array('user_id'=>$coach->ID),site_url('coach-profile-view')); 

                            $dpImageUrl = getUiAvtarUrl( $first_name.' '.$last_name );
                             
                            $attachment_id = get_user_meta( $coach->ID, 'profile-pic', true);
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
                              <li><i class="icon icon-sm-address"></i><?php echo $address;?></li>
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
                <button id="coach-save-search" class="btn btn-sm btn-primary">Save Search</button>
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