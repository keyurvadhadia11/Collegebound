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

?><!DOCTYPE html>

<html class="no-js" <?php language_attributes(); ?>>

	<head>

		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" >

		<link rel="profile" href="https://gmpg.org/xfn/11">

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
	    add_action( 'wp_enqueue_scripts', 'enqueue_home_scripts' );

		wp_head(); 
		?>
	</head>

	<body <?php body_class(); ?>>
		
	    <header class="header"> 
      		<?php
      		// Set Filter Add Custom Class Start
      			function add_classes_on_li($classes, $item, $args) {
				    if ( 'primary' === $args->theme_location ) { //replace main-menu with your menu
					    $classes[] = "nav-item"; 
					}
				    return $classes;
				}
				add_filter('nav_menu_css_class','add_classes_on_li',1,3);

				function add_link_atts($atts, $item, $args) {
				  if ( 'primary' === $args->theme_location ) { //replace main-menu with your menu
					    $atts['class'] = "nav-link"; 
					}
				  return $atts;
				}
				add_filter( 'nav_menu_link_attributes', 'add_link_atts', 1, 3);
			// End Set Filter Add Custom Class

				if ( has_nav_menu( 'primary' ) || ! has_nav_menu( 'expanded' ) ) {
					?>
						<?php
							$custom_logo_id = get_theme_mod( 'custom_logo' );
							$logoImage = wp_get_attachment_image_src( $custom_logo_id , 'full' ); 
						?>
						<nav class="navbar flex-lg-column navbar-expand-lg navbar-light bg-white p-0">
					        <div class="d-flex align-items-center justify-content-between w-100"><a class="navbar-brand header-logo mx-lg-auto" href="#"><img src="<?php echo $logoImage[0]; ?>"></a>
					          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#outermenu" aria-controls="outermenu" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
					        </div>
					        <div class="collapse navbar-collapse" id="outermenu">
	          					<ul class="navbar-nav">
								<?php
								if ( has_nav_menu( 'primary' ) ) {

									wp_nav_menu(
										array(
											'container'  => '',
											'items_wrap' => '%3$s',
											'theme_location' => 'primary',
										)
									);

								}
								?>

								</ul>

							</div>

						</nav><!-- .primary-menu-wrapper -->

					<?php
				}
			?> 
	    </header>