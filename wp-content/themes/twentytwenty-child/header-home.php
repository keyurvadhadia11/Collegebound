<?php

/**

 * Header file for the Twenty Twenty WordPress default theme.

 *

 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials

 *

 * @package WordPress

 * @subpackage Twenty_Twenty

 * @since 1.0.0

 */



?><!doctype html>

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

		/**

	     * Enqueue Admin Scripts Styles

	     */

	    function enqueue_home_scripts()

	    {



	        //wp_dequeue_script('admin-custom');



	        wp_enqueue_style('front-main');



	        wp_enqueue_script('front-main-js');

	        wp_enqueue_script('front-custom');

	    }

	    //add_action( 'wp_enqueue_scripts', 'enqueue_home_scripts' );



		wp_head(); 

		?> 

	</head>



	<body <?php body_class(); ?>>

	    

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