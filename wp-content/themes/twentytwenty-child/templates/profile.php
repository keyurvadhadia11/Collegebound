<?php
/**
 * Template Name: Coach Profile
 */

// Is user login, then redirect to dashboard
if ( !is_user_logged_in() && !current_user_can('administrator') ) {
  wp_redirect ( site_url('login') );
  exit;
}

get_header('admin');
?>
     
    <div class="content-container coach_view grey-bg">
        <div class="row">
            <div class="col-xl-3 col-lg-6">
                <div class="content-card">
                    <a href="#" class="profile-edit-icon">
                        <img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/edit-icon.png">
                    </a>
                    <div class="coach_img">
                        <img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/Coach-img.png">
                    </div>
                    <div class="coach_dtl">
                        <div class="coach_dec text-center">
                            <h2>COACH <br />QUINTERO</h2>
                            <p class="mt-2 text-left">Etiam sed tincidunt odio. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Enim sodales, consequat velit at, dapibus metus. Suspendisse sit amet pulvinar ipsum. </p>
                            <a href="#">Contact</a>

                            <div class="coach_info text-left">
                                <ul>
                                    <li>
                                        <h3>Michigan State University</h3>
                                        <span>School</span>
                                    </li>
                                    <li>
                                        <h3>East Lansing, MI</h3>
                                        <span>Hometown</span>
                                    </li>
                                    <li>
                                        <h3>Head Coach</h3>
                                        <span>Duty</span>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6">
                <div class="content-card text-center university_Profile">
                    <div class="University_Profile_dtl">
                        <img src="<?php echo get_stylesheet_directory_uri();?>/assets/images/Organization-logo.png">
                        <a href="#" class="view_university_profile mt-2 w-100 d-inline-block">View University Profile</a>
                    </div>
                    <div class="University_info">
                        <ul>
                            <li>
                                <div class="University_info-img">

                                </div>
                                <div class="University_info-dtl">
                                    <h4>Quincy Quintero</h4>
                                    <p>
                                        Michigan State University<br />
                                        Head Women’s Basketball Coach
                                    </p>
                                </div>
                                <div class="University_info-cnt text-center">
                                    <a href="#">Contact</a>
                                </div>
                            </li>
                            <li>
                                <div class="University_info-img">

                                </div>
                                <div class="University_info-dtl">
                                    <h4>Quincy Quintero</h4>
                                    <p>
                                        Michigan State University<br />
                                        Head Women’s Basketball Coach
                                    </p>
                                </div>
                                <div class="University_info-cnt text-center">
                                    <a href="#">Contact</a>
                                </div>
                            </li>
                            <li>
                                <div class="University_info-img">

                                </div>
                                <div class="University_info-dtl">
                                    <h4>Quincy Quintero</h4>
                                    <p>
                                        Michigan State University<br />
                                        Head Women’s Basketball Coach
                                    </p>
                                </div>
                                <div class="University_info-cnt text-center">
                                    <a href="#">Contact</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-12">
                <div class="content-card gallery-block">
                    <div class="content-block-title">
                        <h3>Gallery</h3>
                        <img class="float-right" src="<?php echo get_stylesheet_directory_uri();?>/assets/images/Gallery-download.png" />
                    </div>
                    <div class="content-block-details">
                        <div class="row">
                            <div class="col-xl-4 col-md-6">
                                <div class="gallery-img">
                                    <i class="fas fa-ellipsis-v"></i>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="gallery-img">
                                    <i class="fas fa-ellipsis-v"></i>
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-6">
                                <div class="gallery-img">
                                    <i class="fas fa-ellipsis-v"></i>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="gallery-img">
                                    <i class="fas fa-ellipsis-v"></i>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="gallery-img">
                                    <i class="fas fa-ellipsis-v"></i>
                                </div>
                            </div>
                            <div class="col-xl-2 col-md-6">
                                <div class="gallery-img">
                                    <i class="fas fa-ellipsis-v"></i>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="gallery-img">
                                    <i class="fas fa-ellipsis-v"></i>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6">
                                <div class="gallery-img">
                                    <i class="fas fa-ellipsis-v"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php get_footer('admin'); ?>