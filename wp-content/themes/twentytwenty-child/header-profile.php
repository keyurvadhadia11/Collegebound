<!doctype html>

<html lang="en">

    <head>

      <meta charset="utf-8">

      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

      <meta name="description" content="">

      <meta name="author" content="">

      <meta name="generator" content="">

      <title>College Bound - <?php the_title(); ?></title>

      <?php $image_path = get_template_directory_uri().'/assets/images'; ?>

      <link rel="apple-touch-icon" sizes="57x57" href="<?php echo $image_path; ?>/fav/apple-icon-57x57.png">

      <link rel="apple-touch-icon" sizes="60x60" href="<?php echo $image_path; ?>/fav/apple-icon-60x60.png">

      <link rel="apple-touch-icon" sizes="72x72" href="<?php echo $image_path; ?>/fav/apple-icon-72x72.png">

      <link rel="apple-touch-icon" sizes="76x76" href="<?php echo $image_path; ?>/fav/apple-icon-76x76.png">

      <link rel="apple-touch-icon" sizes="114x114" href="<?php echo $image_path; ?>/fav/apple-icon-114x114.png">

      <link rel="apple-touch-icon" sizes="120x120" href="<?php echo $image_path; ?>/fav/apple-icon-120x120.png">

      <link rel="apple-touch-icon" sizes="144x144" href="<?php echo $image_path; ?>/fav/apple-icon-144x144.png">

      <link rel="apple-touch-icon" sizes="152x152" href="<?php echo $image_path; ?>/fav/apple-icon-152x152.png">

      <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $image_path; ?>/fav/apple-icon-180x180.png">

      <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo $image_path; ?>/fav/android-icon-192x192.png">

      <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $image_path; ?>/fav/favicon-32x32.png">

      <link rel="icon" type="image/png" sizes="96x96" href="<?php echo $image_path; ?>/fav/favicon-96x96.png">

      <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $image_path; ?>/fav/favicon-16x16.png">

      <link rel="manifest" href="<?php echo $image_path; ?>/fav/manifest.json">

      <meta name="msapplication-TileColor" content="#46C5F8">

      <meta name="msapplication-TileImage" content="<?php echo $image_path; ?>/fav/ms-icon-144x144.png">

      <meta name="theme-color" content="#46C5F8">

      <link rel="canonical" href="">

      <?php

        function cgbound_enqueue_scripts_bk() 

        { 

            wp_dequeue_script('admin-jquery');

            wp_dequeue_script('main-js');

            wp_dequeue_script('font-awesome-js');

            //wp_enqueue_script('jquery-ui'); 

            wp_dequeue_script('admin-custom');

        }



        //add_action( 'wp_enqueue_scripts', 'cgbound_enqueue_scripts' );    

        ?>

    <?php wp_head(); ?>

    </head>

    <body <?php body_class(); ?> >



       <!-- Loading -->

       <div class="loader-wrap">
				<div class="loader-wrapper">
					<div class="floor"></div>
					<div class="ball">
						<div class="ball-line"></div>
						<div class="ball-line"></div>
						<div class="ball-line"></div>
						<div class="ball-line"></div>
					</div>
				</div>
			</div>



      <?php 

        global $current_user; // Use global

        get_currentuserinfo(); 



        $current_page = get_the_id();



        $userId = get_current_user_id();

      ?>



      <!-- Main Wrapper Start -->

      <div class="main-wrapper">

        <?php get_sidebar('player'); ?>



        <div class="content-wrapper">



            <!-- Top Navigatin Start -->

            <nav class="navbar navbar-expand-lg navbar-profile">

               <button class="btn menu-toggle d-lg-none d-block" id="menu-toggle">

               Menu

               </button>

               <div class="" id="mobileMenu">

                  <ul class="navbar-nav ml-auto flex-row align-items-center">

                     <li class="nav-item">

                        <a class="nav-link nav-search" href="javascript:void(0);">

                        <i class="icon icon-sm-search"></i>

                        </a>

                     </li>

                     <!--<li class="nav-item">

                        <a class="nav-link" href="#">

                        <i class="icon icon-envelop"></i>

                        <span class="total-count">2</span>

                        </a>

                     </li>-->

                     <li class="nav-item dropdown devoq-notification">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon icon-notification"></i>
                        <span class="total-count">5</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                           <span class="dropdown-item">Profile Update</span>
                           <span class="dropdown-item">Password Changed</span>
                        </div>
                     </li>

                     <li class="nav-item nav-dropdown-profile dropdown">

                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                        <span class="d-lg-flex d-none"><?php echo get_user_meta($userId, 'first_name', true).' '.get_user_meta($userId, 'last_name', true);?></span>

                        <?php

                          $dpImageUrl =  $image_path.'/dp.png';

                           $dpImageUrl = getUiAvtarUrl(get_user_meta($userId, 'first_name', true).'+'.get_user_meta($userId, 'last_name', true));

                          $attachment_id = get_user_meta( $userId, 'profile-pic', true);

                          if( $attachment_id && file_exists(get_attached_file($attachment_id))  )

                          $dpImage = wp_get_attachment_image_src( $attachment_id,'thumbnail' );

                          if( isset($dpImage[0]) )

                              $dpImageUrl = $dpImage[0];

                        ?>

                        <img src="<?php echo $dpImageUrl;?>" class="img-fluid" alt="<?php echo get_user_meta($userId, 'first_name', true).' '.get_user_meta($userId, 'last_name', true);?>"/>

                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                           <a class="dropdown-item" href="<?php echo get_permalink(24);?>">My Profile</a>

                           <a class="dropdown-item" href="<?php echo wp_logout_url('home');?>;">Logout</a>

                        </div>

                     </li>

                  </ul>

               </div>

            </nav>

            <!-- End Top Navigation -->

            <!-- Top Search Box Start -->

            <div id="searchbox" class="search-box">

               <button id="searchbox-close" class="close" type="button">

               <i class="icon icon-close"></i>

               </button>

               <div class="search-box-wrap">

                  <form autocomplete="off" action="<?php echo site_url('coach-search');?>">

                     <input type="text" class="form-control" placeholder="Type search..." name="searchPlayer" id="search">

                     <button class="btn btn-primary" type="submit">Search</button>

                  </form>

               </div>

            </div>

            <!-- End Top Box -->