<?php
/**
 * Template Name: Packet
 */

session_start();
global $wpdb;

// Is user login, then redirect to dashboard
if ( !is_user_logged_in() && !current_user_can('administrator') ) {
  wp_redirect ( site_url('login') );
  exit;
}

$tournamentId = $_GET['tournament-id'];

$tournamentDetails = $wpdb->get_results("SELECT * FROM `wp_cb_tournament` where `id` = $tournamentId");

if( !isset($tournamentDetails[0]) ){
  wp_die('Tournament Packet Not Found');
}

$image_path = get_template_directory_uri().'/assets/images';
get_header('admin');
?>
	<div class="content-container tournament_home upcoming_tournaments grey-bg">
       
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

        <a href="<?php echo site_url('tournament-packets');?>" class="return-back"> « Return to Tournaments</a>
        <div class="tournament_home_title">
            <h1><?php echo $tournamentDetails[0]->name;?></h1>
            <?php
                $tournament_date = '';
                $startDate = new DateTime($tournamentDetails[0]->start);
                $endDate = new DateTime($tournamentDetails[0]->end); 
                $start_d = $startDate->format('d'); 
                $end_d   = $endDate->format('d');
                $start_m = $startDate->format('M'); 
                $end_m   = $endDate->format('M');
                $start_y = $startDate->format('Y'); 
                $end_y   = $endDate->format('Y');

                if( $start_y != $end_y ){
                    $tournament_date = $startDate->format('M d, Y').' - '.$endDate->format('M d, Y');
                }
                elseif( $start_m != $end_m ){
                    $tournament_date = $start_m.' '.$start_d.' - '.$end_m.' '.$end_d.', '.$start_y;
                } else {
                     $tournament_date = $start_m.' '.$start_d.'-'.$end_d.', '.$start_y;
                }
            ?>
            <h2><?php echo $tournament_date; ?></h2>
        </div>
        <div class="row">
            <div class="col-xl-7 new-update">
                <div class="content-card">
                    <div class="content-block-details">
                        <ul class="new-update-list">
                            <li>
                                <a href="<?php echo add_query_arg( 'tournament-id', $tournamentDetails[0]->id,  site_url('packet-team-list') );?>">Teams</a>
                            </li>
                            <li>
                                <a href="">Site Map</a>
                            </li>
                            <li>
                                <a href="">Schedule</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>  
        </div>
  </div>
<?php get_footer('admin'); ?>