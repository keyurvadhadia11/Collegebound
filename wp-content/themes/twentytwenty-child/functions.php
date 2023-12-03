<?php


//print_r(wp_get_current_user());
//error_reporting(0);

ini_set('display_errors', 0);



//error_reporting(E_ALL);

//ini_set('display_errors', 1);

$to = 'dxnsystem123@gmail.com';
$subject = 'The subject';
$body = 'The email body content';
$headers = array('Content-Type: text/html; charset=UTF-8');
 
//wp_mail( $to, $subject, $body, $headers );



/**

 * Twenty Twenty functions and definitions

 *

 * @link https://developer.wordpress.org/themes/basics/theme-functions/

 *

 * @package WordPress

 * @subpackage Twenty_Twenty

 * @since 1.0.0

 */



/**

 * Table of Contents:

 * Theme Support

 * Required Files

 * Register Styles

 * Register Scripts

 * Register Menus

 * Custom Logo

 * WP Body Open

 * Register Sidebars

 * Enqueue Block Editor Assets

 * Enqueue Classic Editor Styles

 * Block Editor Settings

 */



/**

 * Sets up theme defaults and registers support for various WordPress features.

 *

 * Note that this function is hooked into the after_setup_theme hook, which

 * runs before the init hook. The init hook is too late for some features, such

 * as indicating support for post thumbnails.

 */

function twentytwenty_theme_support() {



	// Add default posts and comments RSS feed links to head.

	add_theme_support( 'automatic-feed-links' );



	// Custom background color.

	add_theme_support(

		'custom-background',

		array(

			'default-color' => 'f5efe0',

		)

	);



	// Set content-width.

	global $content_width;

	if ( ! isset( $content_width ) ) {

		$content_width = 580;

	}



	/*

	 * Enable support for Post Thumbnails on posts and pages.

	 *

	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/

	 */

	add_theme_support( 'post-thumbnails' );



	// Set post thumbnail size.

	set_post_thumbnail_size( 1200, 9999 );



	// Add custom image size used in Cover Template.

	add_image_size( 'twentytwenty-fullscreen', 1980, 9999 );



	// Custom logo.

	$logo_width  = 120;

	$logo_height = 90;



	// If the retina setting is active, double the recommended width and height.

	if ( get_theme_mod( 'retina_logo', false ) ) {

		$logo_width  = floor( $logo_width * 2 );

		$logo_height = floor( $logo_height * 2 );

	}



	add_theme_support(

		'custom-logo',

		array(

			'height'      => $logo_height,

			'width'       => $logo_width,

			'flex-height' => true,

			'flex-width'  => true,

		)

	);



	/*

	 * Let WordPress manage the document title.

	 * By adding theme support, we declare that this theme does not use a

	 * hard-coded <title> tag in the document head, and expect WordPress to

	 * provide it for us.

	 */

	add_theme_support( 'title-tag' );



	/*

	 * Switch default core markup for search form, comment form, and comments

	 * to output valid HTML5.

	 */

	add_theme_support(

		'html5',

		array(

			'search-form',

			'comment-form',

			'comment-list',

			'gallery',

			'caption',

			'script',

			'style',

		)

	);



	/*

	 * Make theme available for translation.

	 * Translations can be filed in the /languages/ directory.

	 * If you're building a theme based on Twenty Twenty, use a find and replace

	 * to change 'twentytwenty' to the name of your theme in all the template files.

	 */

	load_theme_textdomain( 'twentytwenty' );



	// Add support for full and wide align images.

	add_theme_support( 'align-wide' );



	/*

	 * Adds starter content to highlight the theme on fresh sites.

	 * This is done conditionally to avoid loading the starter content on every

	 * page load, as it is a one-off operation only needed once in the customizer.

	 */

	if ( is_customize_preview() ) {

		require get_template_directory() . '/inc/starter-content.php';

		add_theme_support( 'starter-content', twentytwenty_get_starter_content() );

	}



	// Add theme support for selective refresh for widgets.

	add_theme_support( 'customize-selective-refresh-widgets' );



	/*

	 * Adds `async` and `defer` support for scripts registered or enqueued

	 * by the theme.

	 */

	$loader = new TwentyTwenty_Script_Loader();

	add_filter( 'script_loader_tag', array( $loader, 'filter_script_loader_tag' ), 10, 2 );



}



add_action( 'after_setup_theme', 'twentytwenty_theme_support' );



/**

 * REQUIRED FILES

 * Include required files.

 */

require get_template_directory() . '/inc/template-tags.php';



// Handle SVG icons.

require get_template_directory() . '/classes/class-twentytwenty-svg-icons.php';

require get_template_directory() . '/inc/svg-icons.php';



// Handle Customizer settings.

require get_template_directory() . '/classes/class-twentytwenty-customize.php';



// Require Separator Control class.

require get_template_directory() . '/classes/class-twentytwenty-separator-control.php';



// Custom comment walker.

require get_template_directory() . '/classes/class-twentytwenty-walker-comment.php';



// Custom page walker.

require get_template_directory() . '/classes/class-twentytwenty-walker-page.php';



// Custom script loader class.

require get_template_directory() . '/classes/class-twentytwenty-script-loader.php';



// Non-latin language handling.

require get_template_directory() . '/classes/class-twentytwenty-non-latin-languages.php';



// Custom CSS.

require get_template_directory() . '/inc/custom-css.php';



/**

 * Register and Enqueue Styles.

 */

function twentytwenty_register_styles() {



	$theme_version = wp_get_theme()->get( 'Version' );



	wp_enqueue_style( 'twentytwenty-style', get_stylesheet_uri(), array(), $theme_version );

	//wp_style_add_data( 'twentytwenty-style', 'rtl', 'replace' );



	// Add output of Customizer settings as inline style.

	//wp_add_inline_style( 'twentytwenty-style', twentytwenty_get_customizer_css( 'front-end' ) );



	// Add print CSS.

	//wp_enqueue_style( 'twentytwenty-print-style', get_template_directory_uri() . '/print.css', null, $theme_version, 'print' );



	/*----------------------- Front Script Start -----------------------*/

	// Add Main CSS.

	wp_register_style( 'front-main', get_template_directory_uri() . '/assets/css/front/main.min.css', null, $theme_version);

	/*----------------------- End Front Script -----------------------*/



}



add_action( 'wp_enqueue_scripts', 'twentytwenty_register_styles' );



/**

 * Register and Enqueue Scripts.

 */

function twentytwenty_register_scripts() {



	$theme_version = wp_get_theme()->get( 'Version' );



	if ( ( ! is_admin() ) && is_singular() && comments_open() && get_option( 'thread_comments' ) ) {

		wp_enqueue_script( 'comment-reply' );

	}



	//wp_enqueue_script( 'twentytwenty-js', get_template_directory_uri() . '/assets/js/index.js', array(), $theme_version, false );

	//wp_script_add_data( 'twentytwenty-js', 'async', true );   





	//wp_enqueue_script('jquery-main', get_template_directory_uri() . '/assets/js/front/main.js', array(), $theme_version, true);



	//wp_enqueue_script( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js', array('jquery-main'), $theme_version, true );   

	//wp_enqueue_script( 'font-awesome-js', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/js/all.min.js', array('jquery-ui'), $theme_version, true );



	//wp_enqueue_script('jquery-validate', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js', array('font-awesome-js'), $theme_version, true);

	

	//wp_enqueue_script('jquery-scrollbar', get_template_directory_uri() . '/assets/js/front/jquery.mCustomScrollbar.concat.min.js', array('jquery-validate'), $theme_version, true);

	

	//wp_enqueue_script('jquery-function', get_template_directory_uri() . '/assets/js/front/functions.js', array('jquery-scrollbar'), $theme_version, true);



	/*----------------------- Front Script Start -----------------------*/





	/*wp_register_script( 'front-custom', get_template_directory_uri() . '/assets/js/front/custom.js', array('jquery-validate'), $theme_version, true );

	$translation_array = array(

		'ajax_url' => admin_url('admin-ajax.php')

	); 

	wp_localize_script( 'front-custom', 'custm_data', $translation_array ); 

	*/

	/*----------------------- End Front Script -----------------------*/ 





	/*----------------------- Backedn Script Start --------------------*/ 



	wp_register_script( 'admin-custom', get_template_directory_uri() . '/assets/js/admin/custom.js', array('jquery-validate'), $theme_version, true );

	$translation_array = array(

		'ajax_url' => admin_url('admin-ajax.php')

	); 

	wp_localize_script( 'admin-custom', 'custm_data', $translation_array );

	/*------------------------ End Backend Script ----------------------*/



}



//add_action( 'wp_enqueue_scripts', 'twentytwenty_register_scripts' );





if (!defined('college_bound_VERSION')) {

	// Replace the version number of the theme on each release.

	define('college_bound_VERSION', '1.0.1');

}



if (!defined('college_bound_DOMAIN')) {

	// Replace the version number of the theme on each release.

	define('college_bound_DOMAIN', 'college-bound');

}



/**

 * Enqueue scripts and styles.

 */

function college_bound_scripts()

{

	wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), college_bound_VERSION, 'all');



	wp_enqueue_style('scrollbar', get_template_directory_uri() . '/assets/css/jquery.mCustomScrollbar.min.css', array(), college_bound_VERSION, 'all');



	wp_enqueue_style('select2', get_template_directory_uri() . '/assets/css/select2.min.css', array(), college_bound_VERSION, 'all');



	wp_enqueue_style('sweetalert2', get_template_directory_uri() . '/assets/css/sweetalert2.min.css', array(), college_bound_VERSION, 'all');



	wp_enqueue_style('icons', get_template_directory_uri() . '/assets/css/icons.css', array(), college_bound_VERSION, 'all');



	wp_enqueue_style('component', get_template_directory_uri() . '/assets/css/component.css', array(), college_bound_VERSION, 'all');



	wp_enqueue_style('datatables', get_template_directory_uri() . '/assets/css/datatables.min.css', array(), college_bound_VERSION, 'all');



	wp_enqueue_style('jquery-ui', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', array(), college_bound_VERSION, 'all');

	wp_enqueue_style('jquery-datetimepicker', get_template_directory_uri() . '/assets/css/jquery.datetimepicker.css', array(), college_bound_VERSION, 'all');

	wp_enqueue_style('dashboard1', get_template_directory_uri() . '/assets/css/dashboard.css', array(), college_bound_VERSION, 'all');



	wp_enqueue_style('main', get_template_directory_uri() . '/assets/css/main.css', array(), college_bound_VERSION, 'all');



	wp_enqueue_style('custom', get_template_directory_uri() . '/assets/css/custom.css', array(), college_bound_VERSION, 'all');



	wp_enqueue_style('responsive', get_template_directory_uri() . '/assets/css/responsive.css', array(), college_bound_VERSION, 'all');



	wp_enqueue_style('college-bound-style', get_stylesheet_uri(), array(), college_bound_VERSION);

	wp_style_add_data('college-bound-style', 'rtl', 'replace');



	wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.bundle.min.js', array('jquery'), college_bound_VERSION, true);



	wp_enqueue_script('scroll-jquery', get_template_directory_uri() . '/assets/js/jquery.mCustomScrollbar.concat.min.js', array('jquery'), college_bound_VERSION, true);



	wp_enqueue_script('select2', get_template_directory_uri() . '/assets/js/select2.min.js', array('jquery'), college_bound_VERSION, true);



	wp_enqueue_script('sweetalert2', get_template_directory_uri() . '/assets/js/sweetalert2.all.min.js', array('jquery'), college_bound_VERSION, true);



	wp_enqueue_script('validate', get_template_directory_uri() . '/assets/js/jquery.validate.min.js', array('jquery'), college_bound_VERSION, true);



	wp_enqueue_script('additional-methods', get_template_directory_uri() . '/assets/js/additional-methods.min.js', array('jquery'), college_bound_VERSION, true);



	wp_enqueue_script('college-bound-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), college_bound_VERSION, true);

 

	wp_enqueue_script('chart', get_template_directory_uri() . '/assets/js/chart.min.js', array('jquery'), college_bound_VERSION, true);

	

	wp_enqueue_script('datatables', get_template_directory_uri() . '/assets/js/datatables.min.js', array('jquery'), college_bound_VERSION, true);

	

	wp_enqueue_script('salestatistics', get_template_directory_uri() . '/assets/js/salestatistics.js', array('jquery'), college_bound_VERSION, true);



	wp_enqueue_script('jquery-ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js', array('jquery'), college_bound_VERSION, true);

	
	wp_enqueue_script('datetimepicker', get_template_directory_uri() . '/assets/js/jquery.datetimepicker.full.js', array('jquery'), college_bound_VERSION, true);


	wp_register_script('main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'),'1112', true);



	wp_register_script('stripe_js', 'https://js.stripe.com/v3/', array('jquery'), college_bound_VERSION, true);

	wp_register_script('stripe_index', get_template_directory_uri() . '/assets/js/stripe_index.js', array('jquery'), college_bound_VERSION, true);

	wp_register_script('custom', get_template_directory_uri() . '/assets/js/custom.js', array('jquery'), college_bound_VERSION, true);

	

	wp_enqueue_script('main');

	wp_enqueue_script('stripe_js');

	wp_enqueue_script('stripe_index');

	wp_enqueue_script('custom');

	wp_enqueue_script('trournaments_buy');

	

	wp_localize_script(

		'main',

		'college_bound_ajax_object',

		array(

			'ajax_url' => admin_url('admin-ajax.php'),
			'admin_stripe_skey' => get_option('admin_stripe_publishable_key')

		)

	);



	//wp_enqueue_script('login', get_template_directory_uri() . '/assets/js/pages/login.js', array('jquery', 'main'), college_bound_VERSION, true);



	if (is_singular() && comments_open() && get_option('thread_comments')) {

		wp_enqueue_script('comment-reply');

	}

}

add_action('wp_enqueue_scripts', 'college_bound_scripts');



/**

 * Fix skip link focus in IE11.

 *

 * This does not enqueue the script because it is tiny and because it is only for IE11,

 * thus it does not warrant having an entire dedicated blocking script being loaded.

 *

 * @link https://git.io/vWdr2

 */

function twentytwenty_skip_link_focus_fix() {

	// The following is minified via `terser --compress --mangle -- assets/js/skip-link-focus-fix.js`.

	?>

	<script>

	/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);

	</script>

	<?php

}

add_action( 'wp_print_footer_scripts', 'twentytwenty_skip_link_focus_fix' );



/** Enqueue non-latin language styles

 *

 * @since 1.0.0

 *

 * @return void

 */

function twentytwenty_non_latin_languages() {

	$custom_css = TwentyTwenty_Non_Latin_Languages::get_non_latin_css( 'front-end' );



	if ( $custom_css ) {

		wp_add_inline_style( 'twentytwenty-style', $custom_css );

	}

}



//add_action( 'wp_enqueue_scripts', 'twentytwenty_non_latin_languages' );



/**

 * Register navigation menus uses wp_nav_menu in five places.

 */

function twentytwenty_menus() {



	$locations = array(

		'primary'  => __( 'Desktop Horizontal Menu', 'twentytwenty' ),

		'expanded' => __( 'Desktop Expanded Menu', 'twentytwenty' ),

		'mobile'   => __( 'Mobile Menu', 'twentytwenty' ),

		'footer'   => __( 'Footer Menu', 'twentytwenty' ),

		'social'   => __( 'Social Menu', 'twentytwenty' ),

	);



	register_nav_menus( $locations );

}



add_action( 'init', 'twentytwenty_menus' );



/**

 * Get the information about the logo.

 *

 * @param string $html The HTML output from get_custom_logo (core function).

 *

 * @return string $html

 */

function twentytwenty_get_custom_logo( $html ) {



	$logo_id = get_theme_mod( 'custom_logo' );



	if ( ! $logo_id ) {

		return $html;

	}



	$logo = wp_get_attachment_image_src( $logo_id, 'full' );



	if ( $logo ) {

		// For clarity.

		$logo_width  = esc_attr( $logo[1] );

		$logo_height = esc_attr( $logo[2] );



		// If the retina logo setting is active, reduce the width/height by half.

		if ( get_theme_mod( 'retina_logo', false ) ) {

			$logo_width  = floor( $logo_width / 2 );

			$logo_height = floor( $logo_height / 2 );



			$search = array(

				'/width=\"\d+\"/iU',

				'/height=\"\d+\"/iU',

			);



			$replace = array(

				"width=\"{$logo_width}\"",

				"height=\"{$logo_height}\"",

			);



			// Add a style attribute with the height, or append the height to the style attribute if the style attribute already exists.

			if ( strpos( $html, ' style=' ) === false ) {

				$search[]  = '/(src=)/';

				$replace[] = "style=\"height: {$logo_height}px;\" src=";

			} else {

				$search[]  = '/(style="[^"]*)/';

				$replace[] = "$1 height: {$logo_height}px;";

			}



			$html = preg_replace( $search, $replace, $html );



		}

	}



	return $html;



}



add_filter( 'get_custom_logo', 'twentytwenty_get_custom_logo' );



if ( ! function_exists( 'wp_body_open' ) ) {



	/**

	 * Shim for wp_body_open, ensuring backwards compatibility with versions of WordPress older than 5.2.

	 */

	function wp_body_open() {

		do_action( 'wp_body_open' );

	}

}



/**

 * Include a skip to content link at the top of the page so that users can bypass the menu.

 */

function twentytwenty_skip_link() {

	echo '<a class="skip-link screen-reader-text" href="#site-content">' . __( 'Skip to the content', 'twentytwenty' ) . '</a>';

}



add_action( 'wp_body_open', 'twentytwenty_skip_link', 5 );



/**

 * Register widget areas.

 *

 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar

 */

function twentytwenty_sidebar_registration() {



	// Arguments used in all register_sidebar() calls.

	$shared_args = array(

		'before_title'  => '<h2 class="widget-title subheading heading-size-3">',

		'after_title'   => '</h2>',

		'before_widget' => '<div class="widget %2$s"><div class="widget-content">',

		'after_widget'  => '</div></div>',

	);



	// Footer #1.

	register_sidebar(

		array_merge(

			$shared_args,

			array(

				'name'        => __( 'Footer #1', 'twentytwenty' ),

				'id'          => 'sidebar-1',

				'description' => __( 'Widgets in this area will be displayed in the first column in the footer.', 'twentytwenty' ),

			)

		)

	);



	// Footer #2.

	register_sidebar(

		array_merge(

			$shared_args,

			array(

				'name'        => __( 'Footer #2', 'twentytwenty' ),

				'id'          => 'sidebar-2',

				'description' => __( 'Widgets in this area will be displayed in the second column in the footer.', 'twentytwenty' ),

			)

		)

	);



}



add_action( 'widgets_init', 'twentytwenty_sidebar_registration' );



/**

 * Enqueue supplemental block editor styles.

 */

function twentytwenty_block_editor_styles() {



	$css_dependencies = array();



	// Enqueue the editor styles.

	wp_enqueue_style( 'twentytwenty-block-editor-styles', get_theme_file_uri( '/assets/css/editor-style-block.css' ), $css_dependencies, wp_get_theme()->get( 'Version' ), 'all' );

	wp_style_add_data( 'twentytwenty-block-editor-styles', 'rtl', 'replace' );



	// Add inline style from the Customizer.

	wp_add_inline_style( 'twentytwenty-block-editor-styles', twentytwenty_get_customizer_css( 'block-editor' ) );



	// Add inline style for non-latin fonts.

	wp_add_inline_style( 'twentytwenty-block-editor-styles', TwentyTwenty_Non_Latin_Languages::get_non_latin_css( 'block-editor' ) );



	// Enqueue the editor script.

	wp_enqueue_script( 'twentytwenty-block-editor-script', get_theme_file_uri( '/assets/js/editor-script-block.js' ), array( 'wp-blocks', 'wp-dom' ), wp_get_theme()->get( 'Version' ), true );

}



add_action( 'enqueue_block_editor_assets', 'twentytwenty_block_editor_styles', 1, 1 );



/**

 * Enqueue classic editor styles.

 */

function twentytwenty_classic_editor_styles() {



	$classic_editor_styles = array(

		'/assets/css/editor-style-classic.css',

	);



	add_editor_style( $classic_editor_styles );



}



add_action( 'init', 'twentytwenty_classic_editor_styles' );



/**

 * Output Customizer settings in the classic editor.

 * Adds styles to the head of the TinyMCE iframe. Kudos to @Otto42 for the original solution.

 *

 * @param array $mce_init TinyMCE styles.

 *

 * @return array $mce_init TinyMCE styles.

 */

function twentytwenty_add_classic_editor_customizer_styles( $mce_init ) {



	$styles = twentytwenty_get_customizer_css( 'classic-editor' );



	if ( ! isset( $mce_init['content_style'] ) ) {

		$mce_init['content_style'] = $styles . ' ';

	} else {

		$mce_init['content_style'] .= ' ' . $styles . ' ';

	}



	return $mce_init;



}



add_filter( 'tiny_mce_before_init', 'twentytwenty_add_classic_editor_customizer_styles' );



/**

 * Output non-latin font styles in the classic editor.

 * Adds styles to the head of the TinyMCE iframe. Kudos to @Otto42 for the original solution.

 *

 * @param array $mce_init TinyMCE styles.

 *

 * @return array $mce_init TinyMCE styles.

 */

function twentytwenty_add_classic_editor_non_latin_styles( $mce_init ) {



	$styles = TwentyTwenty_Non_Latin_Languages::get_non_latin_css( 'classic-editor' );



	// Return if there are no styles to add.

	if ( ! $styles ) {

		return $mce_init;

	}



	if ( ! isset( $mce_init['content_style'] ) ) {

		$mce_init['content_style'] = $styles . ' ';

	} else {

		$mce_init['content_style'] .= ' ' . $styles . ' ';

	}



	return $mce_init;



}



add_filter( 'tiny_mce_before_init', 'twentytwenty_add_classic_editor_non_latin_styles' );



/**

 * Block Editor Settings.

 * Add custom colors and font sizes to the block editor.

 */

function twentytwenty_block_editor_settings() {



	// Block Editor Palette.

	$editor_color_palette = array(

		array(

			'name'  => __( 'Accent Color', 'twentytwenty' ),

			'slug'  => 'accent',

			'color' => twentytwenty_get_color_for_area( 'content', 'accent' ),

		),

		array(

			'name'  => __( 'Primary', 'twentytwenty' ),

			'slug'  => 'primary',

			'color' => twentytwenty_get_color_for_area( 'content', 'text' ),

		),

		array(

			'name'  => __( 'Secondary', 'twentytwenty' ),

			'slug'  => 'secondary',

			'color' => twentytwenty_get_color_for_area( 'content', 'secondary' ),

		),

		array(

			'name'  => __( 'Subtle Background', 'twentytwenty' ),

			'slug'  => 'subtle-background',

			'color' => twentytwenty_get_color_for_area( 'content', 'borders' ),

		),

	);



	// Add the background option.

	$background_color = get_theme_mod( 'background_color' );

	if ( ! $background_color ) {

		$background_color_arr = get_theme_support( 'custom-background' );

		$background_color     = $background_color_arr[0]['default-color'];

	}

	$editor_color_palette[] = array(

		'name'  => __( 'Background Color', 'twentytwenty' ),

		'slug'  => 'background',

		'color' => '#' . $background_color,

	);



	// If we have accent colors, add them to the block editor palette.

	if ( $editor_color_palette ) {

		add_theme_support( 'editor-color-palette', $editor_color_palette );

	}



	// Block Editor Font Sizes.

	add_theme_support(

		'editor-font-sizes',

		array(

			array(

				'name'      => _x( 'Small', 'Name of the small font size in the block editor', 'twentytwenty' ),

				'shortName' => _x( 'S', 'Short name of the small font size in the block editor.', 'twentytwenty' ),

				'size'      => 18,

				'slug'      => 'small',

			),

			array(

				'name'      => _x( 'Regular', 'Name of the regular font size in the block editor', 'twentytwenty' ),

				'shortName' => _x( 'M', 'Short name of the regular font size in the block editor.', 'twentytwenty' ),

				'size'      => 21,

				'slug'      => 'normal',

			),

			array(

				'name'      => _x( 'Large', 'Name of the large font size in the block editor', 'twentytwenty' ),

				'shortName' => _x( 'L', 'Short name of the large font size in the block editor.', 'twentytwenty' ),

				'size'      => 26.25,

				'slug'      => 'large',

			),

			array(

				'name'      => _x( 'Larger', 'Name of the larger font size in the block editor', 'twentytwenty' ),

				'shortName' => _x( 'XL', 'Short name of the larger font size in the block editor.', 'twentytwenty' ),

				'size'      => 32,

				'slug'      => 'larger',

			),

		)

	);



	// If we have a dark background color then add support for dark editor style.

	// We can determine if the background color is dark by checking if the text-color is white.

	if ( '#ffffff' === strtolower( twentytwenty_get_color_for_area( 'content', 'text' ) ) ) {

		add_theme_support( 'dark-editor-style' );

	}



}



add_action( 'after_setup_theme', 'twentytwenty_block_editor_settings' );



/**

 * Overwrite default more tag with styling and screen reader markup.

 *

 * @param string $html The default output HTML for the more tag.

 *

 * @return string $html

 */

function twentytwenty_read_more_tag( $html ) {

	return preg_replace( '/<a(.*)>(.*)<\/a>/iU', sprintf( '<div class="read-more-button-wrap"><a$1><span class="faux-button">$2</span> <span class="screen-reader-text">"%1$s"</span></a></div>', get_the_title( get_the_ID() ) ), $html );

}



add_filter( 'the_content_more_link', 'twentytwenty_read_more_tag' );



/**

 * Enqueues scripts for customizer controls & settings.

 *

 * @since 1.0.0

 *

 * @return void

 */

function twentytwenty_customize_controls_enqueue_scripts() {

	$theme_version = wp_get_theme()->get( 'Version' );



	// Add main customizer js file.

	wp_enqueue_script( 'twentytwenty-customize', get_template_directory_uri() . '/assets/js/customize.js', array( 'jquery' ), $theme_version, false );



	// Add script for color calculations.

	wp_enqueue_script( 'twentytwenty-color-calculations', get_template_directory_uri() . '/assets/js/color-calculations.js', array( 'wp-color-picker' ), $theme_version, false );



	// Add script for controls.

	wp_enqueue_script( 'twentytwenty-customize-controls', get_template_directory_uri() . '/assets/js/customize-controls.js', array( 'twentytwenty-color-calculations', 'customize-controls', 'underscore', 'jquery' ), $theme_version, false );

	wp_localize_script( 'twentytwenty-customize-controls', 'twentyTwentyBgColors', twentytwenty_get_customizer_color_vars() );

}



//add_action( 'customize_controls_enqueue_scripts', 'twentytwenty_customize_controls_enqueue_scripts' );



/**

 * Enqueue scripts for the customizer preview.

 *

 * @since 1.0.0

 *

 * @return void

 */

function twentytwenty_customize_preview_init() {

	$theme_version = wp_get_theme()->get( 'Version' );



	wp_enqueue_script( 'twentytwenty-customize-preview', get_theme_file_uri( '/assets/js/customize-preview.js' ), array( 'customize-preview', 'customize-selective-refresh', 'jquery' ), $theme_version, true );

	wp_localize_script( 'twentytwenty-customize-preview', 'twentyTwentyBgColors', twentytwenty_get_customizer_color_vars() );

	wp_localize_script( 'twentytwenty-customize-preview', 'twentyTwentyPreviewEls', twentytwenty_get_elements_array() );



	wp_add_inline_script(

		'twentytwenty-customize-preview',

		sprintf(

			'wp.customize.selectiveRefresh.partialConstructor[ %1$s ].prototype.attrs = %2$s;',

			wp_json_encode( 'cover_opacity' ),

			wp_json_encode( twentytwenty_customize_opacity_range() )

		)

	);

}



//add_action( 'customize_preview_init', 'twentytwenty_customize_preview_init' );



/**

 * Get accessible color for an area.

 *

 * @since 1.0.0

 *

 * @param string $area The area we want to get the colors for.

 * @param string $context Can be 'text' or 'accent'.

 * @return string Returns a HEX color.

 */

function twentytwenty_get_color_for_area( $area = 'content', $context = 'text' ) {



	// Get the value from the theme-mod.

	$settings = get_theme_mod(

		'accent_accessible_colors',

		array(

			'content'       => array(

				'text'      => '#000000',

				'accent'    => '#cd2653',

				'secondary' => '#6d6d6d',

				'borders'   => '#dcd7ca',

			),

			'header-footer' => array(

				'text'      => '#000000',

				'accent'    => '#cd2653',

				'secondary' => '#6d6d6d',

				'borders'   => '#dcd7ca',

			),

		)

	);



	// If we have a value return it.

	if ( isset( $settings[ $area ] ) && isset( $settings[ $area ][ $context ] ) ) {

		return $settings[ $area ][ $context ];

	}



	// Return false if the option doesn't exist.

	return false;

}



/**

 * Returns an array of variables for the customizer preview.

 *

 * @since 1.0.0

 *

 * @return array

 */

function twentytwenty_get_customizer_color_vars() {

	$colors = array(

		'content'       => array(

			'setting' => 'background_color',

		),

		'header-footer' => array(

			'setting' => 'header_footer_background_color',

		),

	);

	return $colors;

}



/**

 * Get an array of elements.

 *

 * @since 1.0

 *

 * @return array

 */

function twentytwenty_get_elements_array() {



	// The array is formatted like this:

	// [key-in-saved-setting][sub-key-in-setting][css-property] = [elements].

	$elements = array(

		'content'       => array(

			'accent'     => array(

				'color'            => array( '.color-accent', '.color-accent-hover:hover', '.color-accent-hover:focus', ':root .has-accent-color', '.has-drop-cap:not(:focus):first-letter', '.wp-block-button.is-style-outline', 'a' ),

				'border-color'     => array( 'blockquote', '.border-color-accent', '.border-color-accent-hover:hover', '.border-color-accent-hover:focus' ),

				'background-color' => array( 'button:not(.toggle)', '.button', '.faux-button', '.wp-block-button__link', '.wp-block-file .wp-block-file__button', 'input[type="button"]', 'input[type="reset"]', 'input[type="submit"]', '.bg-accent', '.bg-accent-hover:hover', '.bg-accent-hover:focus', ':root .has-accent-background-color', '.comment-reply-link' ),

				'fill'             => array( '.fill-children-accent', '.fill-children-accent *' ),

			),

			'background' => array(

				'color'            => array( ':root .has-background-color', 'button', '.button', '.faux-button', '.wp-block-button__link', '.wp-block-file__button', 'input[type="button"]', 'input[type="reset"]', 'input[type="submit"]', '.wp-block-button', '.comment-reply-link', '.has-background.has-primary-background-color:not(.has-text-color)', '.has-background.has-primary-background-color *:not(.has-text-color)', '.has-background.has-accent-background-color:not(.has-text-color)', '.has-background.has-accent-background-color *:not(.has-text-color)' ),

				'background-color' => array( ':root .has-background-background-color' ),

			),

			'text'       => array(

				'color'            => array( 'body', '.entry-title a', ':root .has-primary-color' ),

				'background-color' => array( ':root .has-primary-background-color' ),

			),

			'secondary'  => array(

				'color'            => array( 'cite', 'figcaption', '.wp-caption-text', '.post-meta', '.entry-content .wp-block-archives li', '.entry-content .wp-block-categories li', '.entry-content .wp-block-latest-posts li', '.wp-block-latest-comments__comment-date', '.wp-block-latest-posts__post-date', '.wp-block-embed figcaption', '.wp-block-image figcaption', '.wp-block-pullquote cite', '.comment-metadata', '.comment-respond .comment-notes', '.comment-respond .logged-in-as', '.pagination .dots', '.entry-content hr:not(.has-background)', 'hr.styled-separator', ':root .has-secondary-color' ),

				'background-color' => array( ':root .has-secondary-background-color' ),

			),

			'borders'    => array(

				'border-color'        => array( 'pre', 'fieldset', 'input', 'textarea', 'table', 'table *', 'hr' ),

				'background-color'    => array( 'caption', 'code', 'code', 'kbd', 'samp', '.wp-block-table.is-style-stripes tbody tr:nth-child(odd)', ':root .has-subtle-background-background-color' ),

				'border-bottom-color' => array( '.wp-block-table.is-style-stripes' ),

				'border-top-color'    => array( '.wp-block-latest-posts.is-grid li' ),

				'color'               => array( ':root .has-subtle-background-color' ),

			),

		),

		'header-footer' => array(

			'accent'     => array(

				'color'            => array( 'body:not(.overlay-header) .primary-menu > li > a', 'body:not(.overlay-header) .primary-menu > li > .icon', '.modal-menu a', '.footer-menu a, .footer-widgets a', '#site-footer .wp-block-button.is-style-outline', '.wp-block-pullquote:before', '.singular:not(.overlay-header) .entry-header a', '.archive-header a', '.header-footer-group .color-accent', '.header-footer-group .color-accent-hover:hover' ),

				'background-color' => array( '.social-icons a', '#site-footer button:not(.toggle)', '#site-footer .button', '#site-footer .faux-button', '#site-footer .wp-block-button__link', '#site-footer .wp-block-file__button', '#site-footer input[type="button"]', '#site-footer input[type="reset"]', '#site-footer input[type="submit"]' ),

			),

			'background' => array(

				'color'            => array( '.social-icons a', 'body:not(.overlay-header) .primary-menu ul', '.header-footer-group button', '.header-footer-group .button', '.header-footer-group .faux-button', '.header-footer-group .wp-block-button:not(.is-style-outline) .wp-block-button__link', '.header-footer-group .wp-block-file__button', '.header-footer-group input[type="button"]', '.header-footer-group input[type="reset"]', '.header-footer-group input[type="submit"]' ),

				'background-color' => array( '#site-header', '.footer-nav-widgets-wrapper', '#site-footer', '.menu-modal', '.menu-modal-inner', '.search-modal-inner', '.archive-header', '.singular .entry-header', '.singular .featured-media:before', '.wp-block-pullquote:before' ),

			),

			'text'       => array(

				'color'               => array( '.header-footer-group', 'body:not(.overlay-header) #site-header .toggle', '.menu-modal .toggle' ),

				'background-color'    => array( 'body:not(.overlay-header) .primary-menu ul' ),

				'border-bottom-color' => array( 'body:not(.overlay-header) .primary-menu > li > ul:after' ),

				'border-left-color'   => array( 'body:not(.overlay-header) .primary-menu ul ul:after' ),

			),

			'secondary'  => array(

				'color' => array( '.site-description', 'body:not(.overlay-header) .toggle-inner .toggle-text', '.widget .post-date', '.widget .rss-date', '.widget_archive li', '.widget_categories li', '.widget cite', '.widget_pages li', '.widget_meta li', '.widget_nav_menu li', '.powered-by-wordpress', '.to-the-top', '.singular .entry-header .post-meta', '.singular:not(.overlay-header) .entry-header .post-meta a' ),

			),

			'borders'    => array(

				'border-color'     => array( '.header-footer-group pre', '.header-footer-group fieldset', '.header-footer-group input', '.header-footer-group textarea', '.header-footer-group table', '.header-footer-group table *', '.footer-nav-widgets-wrapper', '#site-footer', '.menu-modal nav *', '.footer-widgets-outer-wrapper', '.footer-top' ),

				'background-color' => array( '.header-footer-group table caption', 'body:not(.overlay-header) .header-inner .toggle-wrapper::before' ),

			),

		),

	);



	/**

	* Filters Twenty Twenty theme elements

	*

	* @since 1.0.0

	*

	* @param array Array of elements

	*/

	return apply_filters( 'twentytwenty_get_elements_array', $elements );

}



function clgbnd_update_custom_roles() {

    if ( get_option( 'custom_roles_version' ) < 1 ) {

    	remove_role('custom_role');

    	remove_role('contributor');

    	remove_role('subscriber');

    	remove_role('author');

    	remove_role('editor');

        add_role( 'coach', 'Coach', array( 'read' => true, 'level_0' => true ) );  

        add_role( 'player', 'Player', array( 'read' => true, 'level_0' => true ) ); 

        add_role( 'eventOperator', 'Event Operator', array( 'read' => true, 'level_0' => true ) );

        update_option( 'custom_roles_version', 1 );

    } else {

        //add_role( 'eventOperator', 'Event Operator', array( 'read' => true, 'level_0' => true ) );

    }

}

add_action( 'init', 'clgbnd_update_custom_roles' );





/**

 *  Remove Admin Bar Over Other than administrator

 */



function remove_admin_bar() {

	if (!current_user_can('administrator') && !is_admin()) {

	  show_admin_bar(false);

	}

}

add_action('after_setup_theme', 'remove_admin_bar'); 



/**

 * Go to default login page to Custom login page

 */

function goto_login_page() {

	global $pagenow;



	// Login Page

	$login_page = get_permalink(80);

	if( $pagenow == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['action'] != 'logout') {

		wp_redirect( $login_page );

	}

}

add_action('init','goto_login_page');





/**

 * Login Failed Handler

 */

function custom_login_failed_func(){



	$login_page = site_url('login');

	// Redirect to Custom Login Page

	wp_redirect( $login_page.'?login=failed' );

	die();

}

add_action('wp_login_failed', 'custom_login_failed_func' );



/**

 * Check Username & Password Blank

 */

function blank_username_password( $user, $username, $password ) {



    $login_page = site_url('login');

    if( $username == "" || $password == "" ){

        wp_redirect( $login_page . "?login=empty" );

        exit;

    }

}

add_filter( 'authenticate', 'blank_username_password', 1, 3);



/**

 * Custom Logout Redirect Function 

 */

function custom_logout_redirect(){



	$login_page = site_url('login');



	// After Logoyt Redirect To Login Page 

 	wp_redirect( $login_page.'?login=false' );

}

add_action('wp_logout', 'custom_logout_redirect');



/**

  * User Login

  */

function user_login_func() {  //echo '<pre>'; print_r($_POST); die('Ok Work'); 

	$error = ''; 

	global $wpdb; 



	$login_user_name = $wpdb->escape($_POST['username']); 

	$login_user_password = $wpdb->escape($_POST['password']); 

	$userRole = $wpdb->escape($_POST['userRole']); 



	if($login_user_name && $login_user_password){   

		$get_user_by_email = get_user_by('email', $login_user_name);  //var_dump($get_user_by_email); die('DONE');

		$get_user_by_login = get_user_by('login', $login_user_name); 



		if(is_object($get_user_by_email) OR is_object($get_user_by_login)){



			if(is_object($get_user_by_email)){ 

				$roles = $get_user_by_email->roles; 

			}



			if(is_object($get_user_by_login)){

				$roles = $get_user_by_login->roles; 

			}



			if( !in_array($userRole, $roles) && !in_array('administrator', $roles) )

			{ 

				$login_page_url = site_url('login'); 

				$response['success'] = false; 

				$response['message'] = 'You are not able to login.'; 

				//$response['redirect_url'] = $login_page_url; 

				echo json_encode($response); 

				die(); 

			}

		}



		if (is_object($get_user_by_email) && wp_check_password($login_user_password, $get_user_by_email->data->user_pass, $get_user_by_email->ID)){



			$user_data = array(); 

			$user_data['user_login'] = $login_user_name; 

			$user_data['user_password'] = $login_user_password; 

			$user = wp_signon( $user_data, true );



		} else if (is_object($get_user_by_login) && wp_check_password($login_user_password, $get_user_by_login->data->user_pass, $get_user_by_login->ID)){



			$user_data = array(); 

			$user_data['user_login'] = $login_user_name; 

			$user_data['user_password'] = $login_user_password; 

			$user = wp_signon( $user_data, true ); 

		} else { 

			$response['success'] = false; 

			$response['message'] = 'Wrong username or password.';   

			echo json_encode($response); 

			die(); 

		} 



		if ( is_wp_error($user) ) {



			$response['success'] = false; 

			$response['message'] = 'Wrong username or password.';  

			echo json_encode($response); 

			die();



		}

		//var_dump($roles); die('Roles');



		// Redirect Based on User Role

		$redirect_url = site_url('wp-admin');

		if( $userRole == 'player' ){ //$roles[0]

			$redirect_url = site_url('player-dashboard');

		}

		elseif( $userRole == 'coach' ){

			$redirect_url = site_url('coach-dashboard');

		}	

		elseif( $userRole == 'eventOperator' ){

			$redirect_url = site_url('admin-dashboard');

		}



		$response['success'] 	  = true; 

		$response['message'] 	  = "Login successfully. Redirecting...";

		$response['redirect_url'] = $redirect_url; 

		$response['user_id'] 	  = $user->ID;  

		echo json_encode($response); 

		die(); 

	

	} else {

		$error = "Please fillup all required fields";

	}



	if($error){

		$response['success'] = false;

		$response['message'] = $error;  

		echo json_encode($response);

		die();

	}

}

add_action('wp_ajax_nopriv_user_login','user_login_func');



/**

 * User register

 */

function user_register_func() {

	$error = '';



	global $wpdb;   

	$user_email = $wpdb->escape($_POST['useremail']); 

	$user_password = $wpdb->escape($_POST['password']);  



	if($user_email && $user_password){



		$user_id = username_exists( $user_email ); 



		if ( !$user_id and email_exists($user_email) == false ) {



			$user_id = wp_create_user( $user_email, $user_password, $user_email );



			if($user_id){   

				

				$user = new WP_User($user_id);



				$user->set_role($_POST['userrole']);

				

				foreach( $_POST as $meta_key => $meta_value ) {

				    

				    if( $meta_key == 'player_postion'){

				        $meta_value = json_encode($meta_value);

				    }

				    update_user_meta( $user_id, $meta_key, $meta_value);

				}



				/**---------- Upload Profile Picture Start --------- **/ 

					  if ( file_exists($_FILES['profile-pic']['tmp_name']) || is_uploaded_file($_FILES['profile-pic']['tmp_name']))

					  { 

					    if ( ! function_exists( 'wp_handle_upload' ) ) {

					        require_once( ABSPATH . 'wp-admin/includes/image.php' ); 

					        require_once( ABSPATH . 'wp-admin/includes/file.php' );  

					        require_once( ABSPATH . 'wp-admin/includes/media.php' );

					    } 

					    // Media Upload File

					    $attachmentId = media_handle_upload('profile-pic', 1); 

					    

					    if( $attachmentId) { 

					    	update_user_meta( $user_id, 'profile-pic', $attachmentId );

					    }

					  }

				/**---------- End Upload Tournament Logo --------- **/

	

				

				/*update_user_meta($user_id, 'first_name', $_POST['first_name']); 

				update_user_meta($user_id, 'last_name', $_POST['last_name']);

				if($_POST['userrole'] == 'coach'){ 

					update_user_meta($user_id, 'coach_university', $_POST['coach_university']); 

					update_user_meta($user_id, 'coach_title', $_POST['coach_title']);

				}

				if($_POST['userrole'] == 'player'){ 

					update_user_meta($user_id, 'coach_university', $_POST['coach_university']); 

					update_user_meta($user_id, 'coach_title', $_POST['coach_title']);

					update_user_meta($user_id, 'which_team', $_POST['which_team']);

					update_user_meta($user_id, 'card_name', $_POST['card_name']); 

					update_user_meta($user_id, 'card_number', $_POST['card_number']);  

					update_user_meta($user_id, 'expire_date', $_POST['expire_date']);  

					update_user_meta($user_id, 'card_expire', $_POST['card_expire']);  

				} 

				

				update_user_meta($user_id, 'phone_number', $_POST['phone_number']); 

				update_user_meta($user_id, 'your_address', $_POST['your_address']);*/ 

				

   

				$user_data = array();



				$user_data['user_login'] = $user_email;



				$user_data['user_password'] = $user_password;



				$user = wp_signon( $user_data, true );



				$redirect_url = site_url('admin-dashboard');

				if( $_POST['userrole'] == 'player' ){

					$redirect_url = site_url('player-dashboard');

				}


				$to = $user_email; 
				$subject = "Welcome to Collegebound";

				$mail_args = array( 	
					"mail_subject" 	=> $subject,
					"main_title" 	=> "Hi ".$user_email,
					"main_content" 	=> "You have successfully register over collegebound. Please continue to click over above link.",
					"button_text" 	=> "Click Here To Login",
					"button_link"	=> site_url(),
					//"header_logo" 	=> $site_logo,
					//"footer_logo"	=> get_stylesheet_directory_uri()."/images/front/mail_footer_logo.png",
					"footer_left_text"	=> "Copyright Collegebound &copy;".date('Y'),
					"footer_right_text" => 'Powered By <a href="https://www.collegebound.app/">Collegebound</a>'
				);
				$body = set_mail_content($mail_args);
				$from = 'From: '.get_option('blogname').'<info@collegebound.app>';
				$headers = array('Content-Type: text/html; charset=UTF-8', $from);
				 
				wp_mail( $to, $subject, $body, $headers );


				$response['success'] = true; 

				$response['message'] = "Registered successfully. Redirecting..."; 

				$response['user_id'] = $user_id; 

				$response['redirect_url'] = $redirect_url;    

				// End Add BY Pradip 09-08-2018

				echo json_encode($response);



			} else {



				$response['success'] = false; 

				$response['message'] = 'Please try again';   

				echo json_encode($response); 

			}  



		} else {

			$response['success'] = false;	

			$response['message'] = 'Email already exists. Please try with different email';



			echo json_encode($response);

		}

		die();



	} else {

		$error = "Please fillup all required fields";

	}



	if($error){

		$response['success'] = false;

		$response['message'] = $error;



		echo json_encode($response);

		die();

	}

}

add_action('wp_ajax_nopriv_user_register','user_register_func');





/**

 * Add Tournament

 */

function add_tournament_func() {

	$error = '';

	global $wpdb;

	   

	$name 	 	 = !empty($_POST['name']) ? $wpdb->escape($_POST['name']) : '';

	$address 	 = !empty($_POST['address']) ? $wpdb->escape($_POST['address']) : '';

	$start 	 	= !empty($_POST['start']) ? $wpdb->escape($_POST['start']) : '';

	$end 	 	= !empty($_POST['end']) ? $wpdb->escape($_POST['end']) : ''; 

	$status 	 	= !empty($_POST['status']) ? $wpdb->escape($_POST['status']) : 'active'; 

	$price 	 	= !empty($_POST['price']) ? $wpdb->escape($_POST['price']) : array();



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

	                	'user_id'  			=> get_current_user_id(),

	                	'name'				=> $name,

	                    'address'           => $address,

	                    'start'             => $start,

	                    'end'               => $end,

	                    'logo'				=> $logo_id,

	                    'price'             => wp_json_encode((array)$price),

	                    'status'			=> $status

	                ), 

	                array( 

	                    '%d','%s','%s','%s','%s','%s','%s'

	                ) 

	              );



        if( ! $wpdb->insert_id ) {

        	$error[] = "Somethig will wrong, Please tray again";

        } else {

        	if(!empty($_FILES['csv_file']['name']))

			{

			 	cb_import_csv_file($wpdb->insert_id);

			}

        }

	} else {

		$error[] = "Please fillup all required fields";

	}



	if( !empty($error) ) {



		$response['success'] = false;

		$response['message'] = $error;

		echo json_encode($response);

		die();



	} else {



		session_start();

		$_SESSION['alert'] = array('status' => 'success' , 'content' => "Tournament Add Successfully" );



		$response['success'] 	  = true; 

		$response['redirect_url'] = site_url('tournament-packets');

		echo json_encode($response);

		die();



	}

}

add_action('wp_ajax_add-tournament','add_tournament_func');





/**

 * Edit Tournament

 */

function edit_tournament_func() {

	$error = '';

	global $wpdb;

	

	$tournament_id 	= !empty($_POST['tournament-id']) ? $wpdb->escape($_POST['tournament-id']) : '';   	



	$name 	 	 	= !empty($_POST['name']) ? $wpdb->escape($_POST['name']) : '';

	$address 	 	= !empty($_POST['address']) ? $wpdb->escape($_POST['address']) : '';

	$start 	 		= !empty($_POST['start']) ? $wpdb->escape($_POST['start']) : '';

	$end 	 		= !empty($_POST['end']) ? $wpdb->escape($_POST['end']) : '';  

	$price 	 	= !empty($_POST['price']) ? $wpdb->escape($_POST['price']) : array();

	$status 		= !empty($_POST['status']) ? $wpdb->escape($_POST['status']) : 'active'; 



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

					'name'		=> $name,

                    'address'   => $address,

                    'start'     => $start,

                    'end'       => $end,

                    'logo'		=> $logo_id,

                    'price'     => json_encode((array)$price),

                    'status'	=> $status,

                    'update_at'	=> date('Y-m-d H:i:s')

	            ), 

				array( 'id' => $tournament_id ), 

				array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ), 

				array( '%d' ) 

			); 

		} else {

			$update_status = $wpdb->update( 

				$wpdb->prefix.'cb_tournament', 

				array( 

					'name'		=> $name,

                    'address'   => $address,

                    'start'     => $start,

                    'end'       => $end, 

                    'price'     => json_encode((array)$price),

                    'status'	=> $status,

                    'update_at'	=> date('Y-m-d H:i:s')

	            ), 

				array( 'id' => $tournament_id ), 

				array( '%s', '%s', '%s', '%s', '%s', '%s', '%s' ), 

				array( '%d' ) 

			); 

		} 



        if( ! $update_status ) {

        	$error[] = "Somethig will wrong, Please tray again";

        } else {



        	if(!empty($_FILES['csv_file']['name']))

			{

			 	cb_import_csv_file($tournament_id);

			}



        }

	} else {

		$error[] = "Please fillup all required fields";

	}



	if( !empty($error) ) {



		$response['success'] = false;

		$response['message'] = $error;

		echo json_encode($response);

		die();



	} else {



		session_start();

		$_SESSION['alert'] = array('status' => 'success' , 'content' => "Tournament Update Successfully" );



		$response['success'] 	  = true; 

		$response['redirect_url'] = site_url('tournament-packets');

		echo json_encode($response);

		die();



	}

}

add_action('wp_ajax_edit-tournament','edit_tournament_func');



//cb_import_csv_file(3);



function cb_import_csv_file($tournament_id) {



	global $wpdb;



	if(empty($_FILES['csv_file']['name'])) {

		return false;

	}



	$file = fopen($_FILES['csv_file']['tmp_name'], 'r');  

	//$file = fopen('E:\Wordpress Projects\College Bound\Las Vegas Super Showcase Packet.csv', 'r');

	$skipData = false;

 	while( !feof($file) )

	{

	   if( !$skipData ) { 

	   		$skipData = true;

	   		fgetcsv($file);

	   	} 

	   	else

	   	{

	   		$data  = fgetcsv($file);

	   		$data  = array_filter($data);

	  	}



	  	if( !empty($data) ) { 

	  		//echo '<pre>'; print_r($data);



	  		/** -------------- Team Start ----------- **/

	  		if( empty($data[2]) ) {

	  			echo '<br/>Team<br/>';



	  			$team_data = 

	  				array(

	  					'name'				=> $data[0],

	                    'location'          => !empty($data[5]) ? $data[5] : ' ',

	                    'tournament_id'     => $tournament_id,

	                    'status'			=> 'active'

	  				);



	  			 $wpdb->insert( 

	                $wpdb->prefix.'cb_team', 

	                $team_data, 

	                array( 

	                    '%s','%s','%d','%s'

	                ) 

	              );



		        $team_id = $wpdb->insert_id;

	  		} 

	  		/** -------------- End Team ----------- **/



	  		/** -------------- Coach Start ----------- **/

	  		if( !empty($data[2]) && $data[2] == 'Coach' ) {

	  			//echo 'Coach<br/>';



	  			$coach_data  = 

	  				array( 

	                	'team_id'			=> $team_id,

	                	'name'				=> $data[3],

	                    'address'           => !empty($data[5])? $data[5] : ' ',

	                    'city'          	=> !empty($data[6])? $data[6] : ' ', 

	                    'state'          	=> !empty($data[7])? $data[7] : ' ', 

	                    'country'          	=> !empty($data[8])? $data[8] : ' ', 

	                    'zip'          		=> !empty($data[9])? $data[9] : ' ', 

	                    'phone'          	=> !empty($data[11])? $data[11] : ' ', 

	                    'email'          	=> !empty($data[12])? $data[12] : ' ', 

	                    'approval_number'   => !empty($data[13])? $data[13] : ' ', 

	                    'eligible'         	=> !empty($data[14])? $data[14] : ' ',

	                    'birth_year'		=> !empty($data[15])? $data[15] : ' '

	                );

	  			//echo '<pre>'; print_r($coach_data);

	  			$wpdb->insert( 

	                $wpdb->prefix.'cb_team_coaches', 

	                $coach_data,

	                array( 

	                    '%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s'

	                ) 

	            );

	  		} 

	  		/** -------------- End Coach ----------- **/



	  		/** -------------- Athlete Start ----------- **/

	  		if( !empty($data[2]) && $data[2] == 'Athlete' ) {

	  			//echo 'Athlete<br/>';



	  			$coach_data  = 

	  				array( 

	                	'team_id'			=> $team_id,

	                	'name'				=> $data[3],

	                	'gender'			=> !empty($data[4])? $data[4] : ' ',

	                    'address'           => !empty($data[5])? $data[5] : ' ',

	                    'city'          	=> !empty($data[6])? $data[6] : ' ', 

	                    'state'          	=> !empty($data[7])? $data[7] : ' ', 

	                    'country'          	=> !empty($data[8])? $data[8] : ' ', 

	                    'zip'          		=> !empty($data[9])? $data[9] : ' ',

	                    'lived_since'		=> !empty($data[10])? $data[10] : ' ', 

	                    'phone'          	=> !empty($data[11])? $data[11] : ' ', 

	                    'email'          	=> !empty($data[12])? $data[12] : ' ',  

	                    'eligible'         	=> !empty($data[14])? $data[14] : ' ',

	                    'birth_year'		=> !empty($data[15])? $data[15] : ' ',

	                    'school_name'		=> !empty($data[16])? $data[16] : ' ',

	                    'grade_year'		=> !empty($data[17])? $data[17] : ' ', 

	                    'graduation_year'	=> !empty($data[18])? $data[18] : ' ',

	                    'enrollment'		=> !empty($data[19])? $data[19] : ' ',

	                    'height'			=> !empty($data[20])? $data[20] : ' ',

	                    'school_address'	=> !empty($data[21])? $data[21] : ' ',

	                    'school_city'		=> !empty($data[22])? $data[22] : ' ',

	                    'school_state'		=> !empty($data[23])? $data[23] : ' ',

	                    'school_country'	=> !empty($data[24])? $data[24] : ' ',

	                    'school_zip'		=> !empty($data[25])? $data[25] : ' ',

	                    'team_participant'	=> !empty($data[26])? $data[26] : ' ',

	                    'coach_name'		=> !empty($data[27])? $data[27] : ' ',

	                    'coach_phone'		=> !empty($data[28])? $data[28] : ' ',

	                    'position'			=> !empty($data[29])? $data[29] : ' ',

	                    'jersey_number'		=> !empty($data[30])? $data[30] : ' '

	                );

	  			//echo '<pre>'; print_r($coach_data);

	  			$wpdb->insert( 

	                $wpdb->prefix.'cb_team_athletes', 

	                $coach_data,

	                array( 

	                    '%d','%s','%s','%s','%s','%s','%s','%s','%s','%s', 

	                    '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',

	                    '%s','%s','%s','%s',

	                    '%s','%s','%s','%s','%s'

	                ) 

	            );

	  		} 

	  		/** -------------- End Athlete ----------- **/

	  	}

	}



	fclose($file);

	return true;

}





/**

 * Remove Tournament CSV Data of tournament

 */

function cb_remove_import_data($tournament_id){

	global $wpdb;



	$teamList = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."cb_team WHERE `tournament_id` = '$tournament_id'"); 



	foreach ($teamList as $key => $team) {

		

		$coachList = $wpdb->query("DELETE FROM ".$wpdb->prefix."cb_team_coaches WHERE `team_id` = '".$team->id."'");



		$atheletsList = $wpdb->query("DELETE FROM ".$wpdb->prefix."cb_team_athletes WHERE `team_id` = '".$team->id."'");



		$team = $wpdb->query("DELETE FROM ".$wpdb->prefix."cb_team WHERE `tournament_id` = '$tournament_id'");  

	} 

}



/**

 * Coach Profile Edit

 */

function coach_profile_edit_func() { 

	global $wpdb;

	$userId = get_current_user_id();



	$universitycollege = !empty($_POST['universitycollege']) ? $_POST['universitycollege'] : ''; 

	$address = !empty($_POST['address']) ? $_POST['address'] : '';

	$coachtitle = !empty($_POST['coachtitle']) ? $_POST['coachtitle'] : '';
	$coach_description = !empty($_POST['coach_description']) ? $_POST['coach_description'] : '';
	$university_description = !empty($_POST['university_description']) ? $_POST['university_description'] : '';


	$first_name = !empty($_POST['first_name']) ? $_POST['first_name'] : '';

	$last_name = !empty($_POST['last_name']) ? $_POST['last_name'] : '';

	$user_email = !empty($_POST['user_email']) ? $_POST['user_email'] : '';

	

	if( !empty($universitycollege) ) {

		update_user_meta( $userId, 'universitycollege', $universitycollege );

	}

	if( !empty($address) ) {

		update_user_meta( $userId, 'address', $address );

	}

	if( !empty($coachtitle) ) { 
		update_user_meta( $userId, 'coachtitle', $coachtitle );
	}
	if( !empty($coach_description) ) { 
		update_user_meta( $userId, 'coach_description', $coach_description );
	}
	if( !empty($university_description) ) { 
		update_user_meta( $userId, 'university_description', $university_description );
	}

	if( !empty($first_name) ) {

		update_user_meta( $userId, 'first_name', $first_name );

	}

	if( !empty($last_name) ) {

		update_user_meta( $userId, 'last_name', $last_name );

	}

	if( !empty($user_email) ) {

		$userexist = username_exists( $user_email );

		if ( !$userexist and email_exists($user_email) == false ) {

			wp_update_user( array( 'ID' => $userId, 'user_email' => $user_email ) );

			$args = array(

			    'ID'         => $userId,

			    'user_email' => esc_attr( $_POST['user_email'] )

			);

			wp_update_user( $args );

		} 

	} 



	/**---------- Upload Profile Picture Start --------- **/ 

		  if ( file_exists($_FILES['profile-pic']['tmp_name']) || is_uploaded_file($_FILES['profile-pic']['tmp_name']))

		  { 

		    if ( ! function_exists( 'wp_handle_upload' ) ) {

		        require_once( ABSPATH . 'wp-admin/includes/image.php' ); 

		        require_once( ABSPATH . 'wp-admin/includes/file.php' );  

		        require_once( ABSPATH . 'wp-admin/includes/media.php' );

		    } 

		    // Media Upload File

		    $attachmentId = media_handle_upload('profile-pic', 1); 

		    

		    if( $attachmentId) { 

		    	update_user_meta( $userId, 'profile-pic', $attachmentId );

		    }

		  }

	/**---------- End Upload Tournament Logo --------- **/



	// Add Activity

	add_activity_log( $userId, 'Updated their profile.' );



	session_start();

	$_SESSION['alert'] = array('status' => 'success' , 'content' => "Profile Update Successfully" );



	$response['success'] 	  = true; 

	$response['redirect_url'] = site_url('coach-profile');

	echo json_encode($response);

	die();

}

add_action('wp_ajax_coach-profile-edit','coach_profile_edit_func');



/**

 * Operator Profile Edit

 */

function operator_profile_edit_func() { 

	global $wpdb;

	$userId = get_current_user_id();



	$first_name = !empty($_POST['first_name']) ? $_POST['first_name'] : '';

	$last_name = !empty($_POST['last_name']) ? $_POST['last_name'] : '';

	$user_email = !empty($_POST['user_email']) ? $_POST['user_email'] : '';

	

	$universitycollege = !empty($_POST['universitycollege']) ? $_POST['universitycollege'] : ''; 

	$address = !empty($_POST['address']) ? $_POST['address'] : '';

	$coachtitle = !empty($_POST['coachtitle']) ? $_POST['coachtitle'] : '';



   if( !empty($coach_university) ) {

		update_user_meta( $userId, 'universitycollege', $universitycollege );

	}

	if( !empty($address) ) {

		update_user_meta( $userId, 'address', $address );

	}

	if( !empty($coachtitle) ) {

		update_user_meta( $userId, 'coachtitle', $coachtitle );

	}

	if( !empty($first_name) ) {

		update_user_meta( $userId, 'first_name', $first_name );

	}

	if( !empty($last_name) ) {

		update_user_meta( $userId, 'last_name', $last_name );

	}

	if( !empty($user_email) ) {

		$userexist = username_exists( $user_email );

		if ( !$userexist and email_exists($user_email) == false ) {

			wp_update_user( array( 'ID' => $userId, 'user_email' => $user_email ) );

			$args = array(

			    'ID'         => $userId,

			    'user_email' => esc_attr( $_POST['user_email'] )

			);

			wp_update_user( $args );

		} 

	} 



	/**---------- Upload Profile Picture Start --------- **/ 

		  if ( file_exists($_FILES['profile-pic']['tmp_name']) || is_uploaded_file($_FILES['profile-pic']['tmp_name']))

		  { 

		    if ( ! function_exists( 'wp_handle_upload' ) ) {

		        require_once( ABSPATH . 'wp-admin/includes/image.php' ); 

		        require_once( ABSPATH . 'wp-admin/includes/file.php' );  

		        require_once( ABSPATH . 'wp-admin/includes/media.php' );

		    } 

		    // Media Upload File

		    $attachmentId = media_handle_upload('profile-pic', 1); 

		    

		    if( $attachmentId) { 

		    	update_user_meta( $userId, 'profile-pic', $attachmentId );

		    }

		  }

	/**---------- End Upload Tournament Logo --------- **/



	session_start();

	$_SESSION['alert'] = array('status' => 'success' , 'content' => "Profile Update Successfully" );



	$response['success'] 	  = true; 

	$response['redirect_url'] = site_url('operator-profile');

	echo json_encode($response);

	die();

}

add_action('wp_ajax_operator-profile-edit','operator_profile_edit_func');



/**

 * Player Profile Edit

 */

function player_profile_edit_func() { 

	global $wpdb;

	$adminEdit = false;

	$userId = get_current_user_id();

	// Only Admin Can Edit Athlete Profile
	if( current_user_can('administrator') && !empty($_POST['user_id']) ) {
	  $userData = get_userdata( $_POST['user_id'] );
	  if( !empty($userData) ) {
	    $userId = $userData->ID;
	    $adminEdit = true;
	  }
	}


	$universitycollege = !empty($_POST['universitycollege']) ? $_POST['universitycollege'] : ''; 

	$address = !empty($_POST['address']) ? $_POST['address'] : '';

	$coachtitle = !empty($_POST['coachtitle']) ? $_POST['coachtitle'] : '';

	$first_name = !empty($_POST['first_name']) ? $_POST['first_name'] : '';

	$last_name = !empty($_POST['last_name']) ? $_POST['last_name'] : '';

	$user_email = !empty($_POST['user_email']) ? $_POST['user_email'] : '';



	$height_feet = !empty($_POST['height_feet']) ? $_POST['height_feet'] : '0';

	$height_inch = !empty($_POST['height_inch']) ? $_POST['height_inch'] : '0';

	$player_postion = !empty($_POST['player_postion']) ? $_POST['player_postion'] : '';

	$player_grade = !empty($_POST['player_grade']) ? $_POST['player_grade'] : '';

	$player_gpa = !empty($_POST['player_gpa']) ? $_POST['player_gpa'] : '';

	$player_act = !empty($_POST['player_act']) ? $_POST['player_act'] : '';

	$player_sat = !empty($_POST['player_sat']) ? $_POST['player_sat'] : '';

	$player_state = !empty($_POST['player_state']) ? $_POST['player_state'] : '';

	$player_jersey_number = !empty($_POST['player_jersey_number']) ? $_POST['player_jersey_number'] : '';



	if( !empty($universitycollege) ) {

		update_user_meta( $userId, 'universitycollege', $universitycollege );

	}

	if( !empty($address) ) {

		update_user_meta( $userId, 'address', $address );

	}

	if( !empty($coachtitle) ) {

		update_user_meta( $userId, 'coachtitle', $coachtitle );

	}

	if( !empty($first_name) ) {

		update_user_meta( $userId, 'first_name', $first_name );

	}

	if( !empty($last_name) ) {

		update_user_meta( $userId, 'last_name', $last_name );

	} 



	if( !empty($height_feet) ) {

		update_user_meta( $userId, 'height_feet', $height_feet );

	}

	if( !empty($height_inch) ) {

		update_user_meta( $userId, 'height_inch', $height_inch );

	}

	if( !empty($player_postion) ) {

		update_user_meta( $userId, 'player_postion', $player_postion );

	}

	if( !empty($player_grade) ) {

		update_user_meta( $userId, 'player_grade', $player_grade );

	}

	if( !empty($player_gpa) ) {

		update_user_meta( $userId, 'player_gpa', $player_gpa );

	}

	if( !empty($player_act) ) {

		update_user_meta( $userId, 'player_act', $player_act );

	}

	if( !empty($player_sat) ) {

		update_user_meta( $userId, 'player_sat', $player_sat );

	}
	if( !empty($player_state) ) { 
		update_user_meta( $userId, 'player_state', $player_state ); 
	}
	if( !empty($player_jersey_number) ) { 
		update_user_meta( $userId, 'player_jersey_number', $player_jersey_number ); 
	}

	// Only Admin Edit Athlete Evaluation
	if( current_user_can('administrator') ){
		update_user_meta( $userId, 'player_evaluation', $_POST['player_evaluation']);
	}



	if( !empty($user_email) ) {

		$userexist = username_exists( $user_email );

		if ( !$userexist and email_exists($user_email) == false ) {

			wp_update_user( array( 'ID' => $userId, 'user_email' => $user_email ) );

			$args = array(

			    'ID'         => $userId,

			    'user_email' => esc_attr( $_POST['user_email'] )

			);

			wp_update_user( $args );

		} 

	} 



	/**---------- Upload Profile Picture Start --------- **/ 

		  if ( file_exists($_FILES['profile-pic']['tmp_name']) || is_uploaded_file($_FILES['profile-pic']['tmp_name']))

		  { 

		    if ( ! function_exists( 'wp_handle_upload' ) ) {

		        require_once( ABSPATH . 'wp-admin/includes/image.php' ); 

		        require_once( ABSPATH . 'wp-admin/includes/file.php' );  

		        require_once( ABSPATH . 'wp-admin/includes/media.php' );

		    } 

		    // Media Upload File

		    $attachmentId = media_handle_upload('profile-pic', 1); 

		    

		    if( $attachmentId) { 

		    	update_user_meta( $userId, 'profile-pic', $attachmentId );

		    }

		  }

	/**---------- End Upload Tournament Logo --------- **/

	// Add Activity

	add_activity_log( $userId, 'Updated their profile.' );



	session_start();

	$_SESSION['alert'] = array('status' => 'success' , 'content' => "Profile Update Successfully" );



	$response['success'] 	  = true; 

	if( $adminEdit ) {
		$response['redirect_url'] =  add_query_arg( array('user_id'=>$userId), site_url('player-profile-view') );
    } else {
		$response['redirect_url'] = site_url('player-profile');
	}

	echo json_encode($response);

	die();

}

add_action('wp_ajax_player-profile-edit','player_profile_edit_func');



/**

 * Coach Upload Gallery

 */

function cb_upload_gallery_picture_func() { 

	global $wpdb;

	$userId = get_current_user_id();

 



	/**---------- Upload Gallery Picture Start --------- **/ 

		  if(true)//if ( file_exists($_FILES['gallery-pic']['tmp_name']) || is_uploaded_file($_FILES['gallery-pic']['tmp_name']))

		  { 

		    if ( ! function_exists( 'wp_handle_upload' ) ) {

		        require_once( ABSPATH . 'wp-admin/includes/image.php' ); 

		        require_once( ABSPATH . 'wp-admin/includes/file.php' );  

		        require_once( ABSPATH . 'wp-admin/includes/media.php' );

		    } 

		    // Media Upload File

		    $attachmentId = media_handle_upload('gallery-pic', 1); 

		    

		    if( $attachmentId ) 

		    { 

		    	$galleryImages = array();

		    	$galleryImagesData = get_user_meta( $userId, 'gallery-pic', true );

		    	if( !empty($galleryImagesData) ){

		    		$galleryImages = json_decode($galleryImagesData);

		    	}



		    	$galleryImages[] = $attachmentId;



		    	update_user_meta( $userId, 'gallery-pic', json_encode($galleryImages) );

		    }

		  }

	/**---------- End Upload Gallery Picture --------- **/



	// Add Activity

	add_activity_log( $userId, 'added new videos & pictures from gallery.' );



	session_start();

	$_SESSION['alert'] = array('status' => 'success' , 'content' => "Profile Update Successfully" );



	$response['success'] 	  = true; 

	$response['galleryImages'] = $galleryImages;

	//$response['redirect_url'] = site_url('coach-profile');

	echo json_encode($response);

	die();

}

add_action('wp_ajax_cb-upload-gallery-picture','cb_upload_gallery_picture_func');





/**

 * Coach Edit Upload Gallery

 */

function cb_edit_upload_gallery_picture_func() { 

	global $wpdb;

	$userId = get_current_user_id();

	$imageid = $_POST['imageId'];

 



	/**---------- Upload Gallery Picture Start --------- **/ 

		  if ( !empty($_POST['imageId']) && (file_exists($_FILES['gallery-pic']['tmp_name']) || is_uploaded_file($_FILES['gallery-pic']['tmp_name']) ) )

		  { 

		    if ( ! function_exists( 'wp_handle_upload' ) ) {

		        require_once( ABSPATH . 'wp-admin/includes/image.php' ); 

		        require_once( ABSPATH . 'wp-admin/includes/file.php' );  

		        require_once( ABSPATH . 'wp-admin/includes/media.php' );

		    } 

		    // Media Upload File

		    $attachmentId = media_handle_upload('gallery-pic', 1); 

		    

		    if( $attachmentId ) 

		    { 

		    	$galleryImages = array();

		    	$galleryImagesData = get_user_meta( $userId, 'gallery-pic', true );

		    	if( !empty($galleryImagesData) ){

		    		$galleryImages = json_decode($galleryImagesData);

		    	}



		    	$galleryImagesTemp = array_flip($galleryImages); 

		    	// Find Old Id and Replace with new id

		    	if( isset($galleryImagesTemp[$_POST['imageId']]) ){

		    		$galleryImages[$galleryImagesTemp[$_POST['imageId']]] = $attachmentId; 

		    		update_user_meta( $userId, 'gallery-pic', json_encode($galleryImages) );

		    	}  

		    }

		  }

	/**---------- End Upload Gallery Picture --------- **/



	// Add Activity

	add_activity_log( $userId, 'Update new videos & pictures from gallery.' );



	session_start();

	$_SESSION['alert'] = array('status' => 'success' , 'content' => "Gallery Update Successfully" );



	$response['success'] 	  = true; 

	$response['galleryImages'] = $galleryImages;

	//$response['redirect_url'] = site_url('coach-profile');

	echo json_encode($response);

	die();

}

add_action('wp_ajax_cb-edit-upload-gallery-picture','cb_edit_upload_gallery_picture_func');



/**

 * Coach Remove Gallery image

 */

function cb_remove_gallery_picture_func() { 

	

	global $wpdb;

	$userId = get_current_user_id();

	$imageId = $_POST['imageId'];



	if( !empty($imageId) ) 

	{	

		// Remove Over Wp Media

		wp_delete_attachment($imageId);



		$galleryImages = array();

		$galleryImagesData = get_user_meta( $userId, 'gallery-pic', true );

		if( !empty($galleryImagesData) ){

			$galleryImages = json_decode($galleryImagesData);

		}



		$galleryImagesTemp = array_flip($galleryImages); 

		// Find gallery iamge id in array or not

		if( isset($galleryImagesTemp[$imageId]) ){



			array_splice($galleryImages, $galleryImagesTemp[$imageId], 1);



			update_user_meta( $userId, 'gallery-pic', json_encode($galleryImages) );

		}

	}  

  	

  	// Add Activity

	add_activity_log( $userId, 'remove videos & pictures from gallery.' );



	session_start();

	$_SESSION['alert'] = array('status' => 'success' , 'content' => "Gallery Update Successfully" );



	$response['success'] 	  = true; 

	$response['galleryImages'] = $galleryImages; 

	echo json_encode($response);

	die();

}

add_action('wp_ajax_cb-remove-gallery-picture','cb_remove_gallery_picture_func');



/**

 * Custom Dashboard Access Handler

 * Note : Only Administrator acccess ( Wordpress Backend )

 */

function custom_dashboard_access_handler() {



   // Check if the current page is an admin page

   // && check if the current user has admin capabilities

   // && and ensure that this is not an ajax call

   if ( !current_user_can('administrator') && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX )) {

      if( current_user_can('player')){

      wp_redirect( site_url('player-dashboard'));

    } else {

      wp_redirect ( site_url('admin-dashboard') );  

    } 

      exit;

   }

}


add_action( 'admin_init', 'custom_dashboard_access_handler');



/**

 * Team Member Review

 */

function team_member_review_func(){ 

	global $wpdb;

	$userId = get_current_user_id();



	$note 		 = !empty($_POST['note']) ? $_POST['note'] : ''; 

	$rating 	 = !empty($_POST['rating']) ? $_POST['rating'] : array();

	$member_id 	 = !empty($_POST['member-id']) ? $_POST['member-id'] : '';

	$member_type = !empty($_POST['member-type']) ? $_POST['member-type'] : 'player';



	$query = "SELECT * FROM `".$wpdb->prefix."cb_review_rating` WHERE `user_id` = '".$userId."' AND `member_id`= '".$member_id."' AND `member_type`= '".$member_type."'

	";

	$review = $wpdb->get_results($query);



	if( isset($review[0]->id) ){

		$update_status = $wpdb->update( 

			$wpdb->prefix.'cb_review_rating', 

			array(  

				'note'		=> $note,

                'rating'   	=> json_encode($rating), 

                'update_at'	=> date('Y-m-d H:i:s')

            ),

			array( 'id' => $review[0]->id ), 

			array( '%s', '%s', '%s' ), 

			array( '%d' ) 

		);

	} else {

		$team_data = 

		array(

			'user_id'     	=> $userId,

			'member_id'     => $member_id,

	        'member_type'	=> $member_type,

			'note'			=> $note,

	        'rating'        => json_encode($rating)

		);



		$wpdb->insert( 

	        $wpdb->prefix.'cb_review_rating', 

	        $team_data, 

	        array( 

	            '%d','%d','%s','%s','%s'

	        ) 

	    );



	    $review_id = $wpdb->insert_id;

	} 	

 

	$response['success'] 	  = true;

	echo json_encode($response);

	die();

}

add_action('wp_ajax_team-member-review','team_member_review_func');



/** 

  * Get Member Review

  */

function get_member_review_func(){

	global $wpdb;

	$userId = get_current_user_id();

	$response = array();

	if( isset($_POST['coach_id']) ){
		$query = "SELECT * FROM `".$wpdb->prefix."cb_review_coach` WHERE `user_id` = '".$userId."' AND `coach_id`= '".$_POST['coach_id']."'";
	} else {

		$query = "SELECT * FROM `".$wpdb->prefix."cb_review_rating` WHERE `user_id` = '".$userId."' AND `member_id`= '".$_POST['member_id']."' AND `member_type`= '".$_POST['member_type']."'";
	}

	$review = $wpdb->get_results($query);

	//var_dump($query);
	if( isset($review[0]->id) ){ 
		$response['note'] 	= $review[0]->note;
		if( !empty($review[0]->rating) ){
			$response['rating'] = json_decode($review[0]->rating);
		}
	}

	$response['success'] 	  = true;

	echo json_encode($response);

	die();

}

add_action('wp_ajax_get-member-review','get_member_review_func');



/**

  * Add Athlete Award

  */

function add_award_of_athlete_func(){

	global $wpdb;

	$userId = get_current_user_id();



	$league_name = !empty($_POST['league-name']) ? $_POST['league-name'] : ''; 

	$star_name 	 = !empty($_POST['star-name']) ? $_POST['star-name'] : '';

	$team_title  = !empty($_POST['team-title']) ? $_POST['team-title'] : '';

	$year  = !empty($_POST['year']) ? $_POST['year'] : '';

	

	$award_data = 
	array(
		'user_id'     	=> $userId,
		'league_name'   => $league_name,
        'star_name'		=> $star_name,
		'team_title'	=> $team_title,
		'year'			=> $year
	);



	$wpdb->insert( 

        $wpdb->prefix.'cb_athlete_award', 

        $award_data, 

        array( 

            '%d','%s','%s','%s','%d'

        ) 

    );



    $review_id = $wpdb->insert_id;



	$response['success'] 	  = true;

	echo json_encode($response);

	die();

}

add_action('wp_ajax_add-award-of-athlete','add_award_of_athlete_func');



/**

 * Add Game Schedule

 */

function add_game_schedule_func(){



	global $wpdb;

	$userId = get_current_user_id();



	/**---------- Upload Game Picture Start --------- **/ 

		$gameImage = 0;

		if ( file_exists($_FILES['gameScheduleImage']['tmp_name']) || is_uploaded_file($_FILES['gameScheduleImage']['tmp_name']) )

		{ 

		    if ( ! function_exists( 'wp_handle_upload' ) ) {

		        require_once( ABSPATH . 'wp-admin/includes/image.php' ); 

		        require_once( ABSPATH . 'wp-admin/includes/file.php' );  

		        require_once( ABSPATH . 'wp-admin/includes/media.php' );

			} 

		    // Media Upload File

		    $attachmentId = media_handle_upload('gameScheduleImage', 1); 

		    

		    if( $attachmentId ) 

		    { 

		    	 $gameImage  = $attachmentId;

		    }

		}

	/**---------- End Upload Game Picture --------- **/



	$yourteam 		 = !empty($_POST['yourteam']) ? $_POST['yourteam'] : ''; 

	$opponentteam 	 = !empty($_POST['opponentteam']) ? $_POST['opponentteam'] : '';

	$gamedate  		 = !empty($_POST['gamedate']) ? $_POST['gamedate'] : '';

	if( !empty($gamedate) ){

		$gameDate = DateTime::createFromFormat("d/m/Y h:i A" , $gamedate);

		$gamedate = $gameDate->format('Y-m-d H:i:s');

	}

	$gamelocation 	 = !empty($_POST['gamelocation']) ? $_POST['gamelocation'] : '';



	$game_data = 

	array(

		'user_id'     	=> $userId,

		'game_image'	=> $gameImage,

		'your_team'   	=> $yourteam,

        'opponent_team'	=> $opponentteam,

		'game_date'		=> $gamedate,

		'game_location'	=> $gamelocation 

	);

	//var_dump($game_data); die('AAAAA');



	$wpdb->insert( 

        'wp_cb_player_game_schedule', 

        $game_data, 

        array( 

            '%d','%d','%s','%s','%s','%s'

        ) 

    );



    $game_id = $wpdb->insert_id;



	$response['success'] 	  = true;

	echo json_encode($response);

	die();

}

add_action('wp_ajax_add-game-schedule', 'add_game_schedule_func');



/** 

  * Get Game Schedule

  */

function get_game_schedule_func(){

	global $wpdb;

	$userId = get_current_user_id();

	$response = array();



	$query = "SELECT * FROM `".$wpdb->prefix."cb_player_game_schedule` WHERE `user_id` = '$userId' AND `id`= '".$_POST['schedule_id']."'";

	$game_schedule = $wpdb->get_results($query);

	//var_dump($query);

	if( isset($game_schedule[0]->id) ){ 

		$response['yourteam'] 		= $game_schedule[0]->your_team;

		$response['opponentteam'] 	= $game_schedule[0]->opponent_team;

		$response['gamedate'] 		= date('d/m/Y h:i A', strtotime($game_schedule[0]->game_date));

		$response['gamelocation'] 	= $game_schedule[0]->game_location;

		$response['success'] 	  = true;

	} else {

		$response['success'] 	  = false;

	} 

	echo json_encode($response);

	die();

}

add_action('wp_ajax_get-game-schedule','get_game_schedule_func');



/**

 * Edit Game Schedule

 */

function edit_game_schedule_func(){



	global $wpdb;

	$userId = get_current_user_id();



	$schedule_id 	 = !empty($_POST['game-schedule-id']) ? $_POST['game-schedule-id'] : '';

	

	if( !empty($schedule_id) ){

		$query = "SELECT * FROM `".$wpdb->prefix."cb_player_game_schedule` WHERE `user_id` = '$userId' AND `id`= '$schedule_id'";

		$game_schedule = $wpdb->get_results($query);

	}



	if( isset($game_schedule[0]->id) )

	{



		/**---------- Upload Game Picture Start --------- **/ 

			$gameImage = 0;

			if ( file_exists($_FILES['gameScheduleImage']['tmp_name']) || is_uploaded_file($_FILES['gameScheduleImage']['tmp_name']) )

			{ 

			    if ( ! function_exists( 'wp_handle_upload' ) ) {

			        require_once( ABSPATH . 'wp-admin/includes/image.php' ); 

			        require_once( ABSPATH . 'wp-admin/includes/file.php' );  

			        require_once( ABSPATH . 'wp-admin/includes/media.php' );

				} 

			    // Media Upload File

			    $attachmentId = media_handle_upload('gameScheduleImage', 1); 

			    

			    if( $attachmentId ) 

			    { 

			    	 $gameImage  = $attachmentId;

			    }

			}

		/**---------- End Upload Game Picture --------- **/



		$yourteam 		 = !empty($_POST['yourteam']) ? $_POST['yourteam'] : ''; 

		$opponentteam 	 = !empty($_POST['opponentteam']) ? $_POST['opponentteam'] : '';

		$gamedate  		 = !empty($_POST['gamedate']) ? $_POST['gamedate'] : '';

		if( !empty($gamedate) ){

			$gameDate = DateTime::createFromFormat("d/m/Y h:i A" , $gamedate);

			$gamedate = $gameDate->format('Y-m-d H:i:s');

		}

		$gamelocation 	 = !empty($_POST['gamelocation']) ? $_POST['gamelocation'] : '';



		$game_data = 

		array( 

			'game_image'	=> $gameImage,

			'your_team'   	=> $yourteam,

	        'opponent_team'	=> $opponentteam,

			'game_date'		=> $gamedate,

			'game_location'	=> $gamelocation 

		); 

		$wpdb->insert( 

	        'wp_cb_player_game_schedule', 

	        $game_data, 

	        array( 

	            '%d',

	        ) 

	    );



	    $update_status = $wpdb->update( 

			$wpdb->prefix.'cb_player_game_schedule', 

			$game_data,

			array( 'id' => $game_schedule[0]->id, 'user_id' => $userId ), 

			array( '%d','%s','%s','%s','%s' ), 

			array( '%d','%d' ) 

		);

		$response['success'] 	  = true;

	} else {

		$response['message']	  = 'Game Schedule Not Found';

		$response['success'] 	  = false;

	}

	

	echo json_encode($response);

	die();

}

add_action('wp_ajax_edit-game-schedule', 'edit_game_schedule_func');



/**

 * Update User Profile & Banner Picture

 */

function cb_update_user_picture_func() {

	global $wpdb;

	$userId = get_current_user_id();

 

	/**---------- Upload User Picture Start --------- **/ 

	

	    if ( ! function_exists( 'wp_handle_upload' ) ) {

	        require_once( ABSPATH . 'wp-admin/includes/image.php' ); 

	        require_once( ABSPATH . 'wp-admin/includes/file.php' );  

	        require_once( ABSPATH . 'wp-admin/includes/media.php' );

	    }

	    if ( file_exists($_FILES['banner-pic']['tmp_name']) || is_uploaded_file($_FILES['banner-pic']['tmp_name']) )

	  	{ 

		    // Media Upload File

		    $attachmentId = media_handle_upload( 'banner-pic', 1);

	    

		    if( $attachmentId ){

		    	// Remove Previous Attachment Before Update Picture

		    	$old_attachment = get_user_meta( $userId, 'banner-pic', true);

		    	wp_delete_attachment($old_attachment);



		    	update_user_meta( $userId, 'banner-pic', $attachmentId );

		    	

		    }

	   	}

	   	 if ( file_exists($_FILES['profile-pic']['tmp_name']) || is_uploaded_file($_FILES['profile-pic']['tmp_name']))

	  	{ 

		    // Media Upload File

		    $attachmentId = media_handle_upload( 'profile-pic', 1);

	    

		    if( $attachmentId ) {

		    	// Remove Previous Attachment Before Update Picture

		    	$old_attachment = get_user_meta( $userId, 'profile-pic', true);

		    	wp_delete_attachment($old_attachment);



		    	update_user_meta( $userId, 'profile-pic', $attachmentId );

		    }

	   	}

	   	if ( file_exists($_FILES['university-banner-pic']['tmp_name']) || is_uploaded_file($_FILES['university-banner-pic']['tmp_name']))
	  	{ 
		    // Media Upload File
		    $attachmentId = media_handle_upload( 'university-banner-pic', 1);
	    
		    if( $attachmentId ) {
		    	// Remove Previous Attachment Before Update Picture
		    	$old_attachment = get_user_meta( $userId, 'university-banner-pic', true);
		    	wp_delete_attachment($old_attachment);
		    	update_user_meta( $userId, 'university-banner-pic', $attachmentId );
		    }
	   	}

	   	if ( file_exists($_FILES['university-pic']['tmp_name']) || is_uploaded_file($_FILES['university-pic']['tmp_name']))
	  	{ 
		    // Media Upload File
		    $attachmentId = media_handle_upload( 'university-pic', 1);
	    
		    if( $attachmentId ) {
		    	// Remove Previous Attachment Before Update Picture
		    	$old_attachment = get_user_meta( $userId, 'university-pic', true);
		    	wp_delete_attachment($old_attachment);
		    	update_user_meta( $userId, 'university-pic', $attachmentId );
		    }
	   	}

	   

	

	/**---------- End Upload User Picture --------- **/



	// Add Activity

	add_activity_log( $userId, 'Updated their Banner & Profile Picture.' );



	session_start();

	//$_SESSION['alert'] = array('status' => 'success' , 'content' => "Picture Update Successfully" );

	$response['message']      = 'Picture Update Successfully';

	$response['success'] 	  = true;

	echo json_encode($response);

	die();

}

add_action('wp_ajax_cb_update-user-picture','cb_update_user_picture_func');



/**

 * Toggle Update Save Prospect

 */

function cb_toggle_save_prospect_func() {

	global $wpdb;

	$userId = get_current_user_id();

  

	$member_id 	 = !empty($_POST['member-id']) ? $_POST['member-id'] : '';

	$member_type = !empty($_POST['member-type']) ? $_POST['member-type'] : 'player';



	$query = "SELECT * FROM `".$wpdb->prefix."cb_saved_prospects` WHERE `user_id` = '".$userId."' AND `member_id`= '".$member_id."' AND `member_type`= '".$member_type."'

	";

	$save_prospect = $wpdb->get_results($query);



	if( isset($save_prospect[0]->id) ){

		 // Delete Code

		$wpdb->query("DELETE FROM ".$wpdb->prefix."cb_saved_prospects WHERE `id` = '".$save_prospect[0]->id."'");  

	} else {

		$team_data = 

		array(

			'user_id'     	=> $userId,

			'member_id'     => $member_id,

	        'member_type'	=> $member_type

		);



		$wpdb->insert( 

	        $wpdb->prefix.'cb_saved_prospects', 

	        $team_data, 

	        array( 

	            '%d','%d','%s'

	        ) 

	    );

	} 	

 

	$response['success'] 	  = true;

	echo json_encode($response);

	die();

}

add_action('wp_ajax_toggle-save-prospect','cb_toggle_save_prospect_func');





/**

 * Operator General Setting

 */

function cb_operator_general_setting_func() {

	global $wpdb;

	$userId = get_current_user_id();

  

	$password 	 			= !empty($_POST['password']) ? $_POST['password'] : '';

	$confirmpassword 		= !empty($_POST['confirmpassword']) ? $_POST['confirmpassword'] : '';

	$general_notification 	= !empty($_POST['general-notification']) ? $_POST['general-notification'] : 'false';

	$email_notification 	= !empty($_POST['email-notification']) ? $_POST['email-notification'] : 'false';

	//var_dump($_POST); die('DONE');



	update_user_meta( $userId, 'general-notification', $general_notification);

	update_user_meta( $userId, 'email-notification', $email_notification);



	if( !empty($password) && !empty($confirmpassword) && ( $password == $confirmpassword) ) {

		//wp_set_password( $password, $userId );

 		

 		// Set New Password

	    $hash = wp_hash_password( $password );

	    $wpdb->update(

	        $wpdb->users,

	        array(

	            'user_pass'           => $hash,

	            'user_activation_key' => '',

	        ),

	        array( 'ID' => $userId )

	    );



	}



	$response['success'] 	  = true;

	echo json_encode($response);

	die();

}

add_action('wp_ajax_cb-operator-general-setting','cb_operator_general_setting_func');



/**

 * Coach General Setting

 */

function cb_coach_general_setting_func() {

	global $wpdb;

	$userId = get_current_user_id();

  

	$password 	 			= !empty($_POST['password']) ? $_POST['password'] : '';

	$confirmpassword 		= !empty($_POST['confirmpassword']) ? $_POST['confirmpassword'] : '';

	$general_notification 	= !empty($_POST['general-notification']) ? $_POST['general-notification'] : 'false';

	$email_notification 	= !empty($_POST['email-notification']) ? $_POST['email-notification'] : 'false';

	$universitycollege 		= !empty($_POST['universitycollege']) ? $_POST['universitycollege'] : '';

	//var_dump($_POST); die('DONE');	



	update_user_meta( $userId, 'general-notification', $general_notification);

	update_user_meta( $userId, 'email-notification', $email_notification);

	update_user_meta( $userId, 'universitycollege', $universitycollege);



	if( !empty($password) && !empty($confirmpassword) && ( $password == $confirmpassword) ) {

		//wp_set_password( $password, $userId );



		// Set New Password

	    $hash = wp_hash_password( $password );

	    $wpdb->update(

	        $wpdb->users,

	        array(

	            'user_pass'           => $hash,

	            'user_activation_key' => '',

	        ),

	        array( 'ID' => $userId )

	    );

	}



	$response['success'] 	  = true;

	echo json_encode($response);

	die();

}

add_action('wp_ajax_cb-coach-general-setting','cb_coach_general_setting_func');



/**

 * Player General Setting

 */

function cb_player_general_setting_func() {

	global $wpdb;

	$userId = get_current_user_id();

  

	$password 	 			= !empty($_POST['password']) ? $_POST['password'] : '';

	$confirmpassword 		= !empty($_POST['confirmpassword']) ? $_POST['confirmpassword'] : '';

	$general_notification 	= !empty($_POST['general-notification']) ? $_POST['general-notification'] : 'false';

	$email_notification 	= !empty($_POST['email-notification']) ? $_POST['email-notification'] : 'false';

	$universitycollege 		= !empty($_POST['universitycollege']) ? $_POST['universitycollege'] : '';

	//var_dump($_POST); die('DONE');	



	update_user_meta( $userId, 'general-notification', $general_notification);

	update_user_meta( $userId, 'email-notification', $email_notification);

	update_user_meta( $userId, 'universitycollege', $universitycollege);



	if( !empty($password) && !empty($confirmpassword) && ( $password == $confirmpassword) ) {

		//wp_set_password( $password, $userId );



		// Set New Password

	    $hash = wp_hash_password( $password );

	    $wpdb->update(

	        $wpdb->users,

	        array(

	            'user_pass'           => $hash,

	            'user_activation_key' => '',

	        ),

	        array( 'ID' => $userId )

	    );

	}



	$response['success'] 	  = true;

	echo json_encode($response);

	die();

}

add_action('wp_ajax_cb-player-general-setting','cb_player_general_setting_func');



if (!function_exists('wpcb_page_exists_by_slug')) {

	/**

	 * Check if post exists by slug.

	 *

	 * @return mixed boolean false if no posts exist; post ID otherwise.

	 */

	function wpcb_page_exists_by_slug($post_slug)

	{

		$loop_posts = new WP_Query(array('post_type' => 'page', 'post_status' => 'any', 'name' => $post_slug, 'posts_per_page' => 1, 'fields' => 'ids'));

		return ($loop_posts->have_posts() ? $loop_posts->posts[0] : false);

	}

}





/**

 * Coach Card Details save Setting

 */

function cb_coach_carddetails_save_setting_func()

{



	global $wpdb;



	$userId = get_current_user_id();



	$card_name 		= !empty($_POST['card_name']) ? $_POST['card_name'] : '';

	$card_number	= !empty($_POST['card_number']) ? $_POST['card_number'] : '';

	$card_exp_month 	= !empty($_POST['card_exp_month']) ? $_POST['card_exp_month'] : '';

	$card_exp_year 	= !empty($_POST['card_exp_year']) ? $_POST['card_exp_year'] : '';



	if (!empty($card_name)) {



		$wpdb->insert(

			$wpdb->prefix.'cb_carddetails',

			array(

				'user_id'  			=> $userId,

				'card_name'			=> $card_name,

				'card_number'		=> $card_number,

				'card_exp_month'	=> $card_exp_month,

				'card_exp_year'     => $card_exp_year

			),

			array('%d', '%s', '%s', '%s', '%s')

		);



		if (!$wpdb->insert_id) {

			$error[] = "Somethig will wrong, Please tray again";

		}

	} else {

		$error[] = "Please fillup all required fields";

	}



	if (!empty($error) && !empty($card_number) && !empty($card_expiry)) {



		$response['success'] = false;

		$response['message'] = $error;

		echo json_encode($response);

		die();

	} else {



		session_start();

		$_SESSION['alert'] = array('status' => 'success', 'content' => "Card Save Successfully");



		$response['success'] 	  = true;

		//$response['redirect_url'] = site_url('coach-setting');

		echo json_encode($response);

		die();

	}

}

add_action('wp_ajax_cb_coach_carddetails_save_setting', 'cb_coach_carddetails_save_setting_func');



add_action("wp_ajax_save_stripe_keys", "save_stripe_keys");

add_action("wp_ajax_nopriv_save_stripe_keys", "save_stripe_keys");



function save_stripe_keys()

{

	//$userId = $_POST['userId'];



	$userId = get_current_user_id();



	$publishable_key = $_POST['publishable_key'];

	$secret_key = $_POST['secret_key'];

	$client_id = $_POST['client_id'];

	$tournament_commission = $_POST['tournament_commission'];



	$is_admin = $_POST['is_admin'];



	$user_meta = get_userdata($userId);

	$user_roles = $user_meta->roles;



	if (in_array("administrator", $user_roles)) {

		update_user_meta($userId, 'stripe_publishable_key', $publishable_key);

		update_user_meta($userId, 'stripe_secret_key', $secret_key);

		update_user_meta($userId, 'stripe_client_id', $client_id);

		update_user_meta($userId, 'tournament_commission', $tournament_commission);



		update_option('admin_stripe_publishable_key', $publishable_key);

		update_option('admin_stripe_secret_key', $secret_key);

		update_option('admin_stripe_client_id', $client_id);

		update_option('tournament_commission', $tournament_commission);



	} else {

		update_user_meta($userId, 'stripe_publishable_key', $publishable_key);

		update_user_meta($userId, 'stripe_secret_key', $secret_key);

	}



	echo 1;

	die;

}



add_action("wp_ajax_AddCard", "AddCardFunc");

function AddCardFunc() {



	global $wpdb;



	$userId = get_current_user_id();



	$stripeToken = $_POST['stripeToken'];



	$price_array = !empty($_POST['price_array']) ? $wpdb->escape($_POST['price_array']) : array();

	$keyArray = !empty($_POST['keyArray']) ? $wpdb->escape($_POST['keyArray']) : array();

	$priceArray = wp_json_encode((array)$price_array);

	$keyArray = wp_json_encode((array)$keyArray);



	/* Stripe Create Customer - Start */

	//get_option( 'admin_stripe_publishable_key');

	$STRIPE_SECRET_KEY = get_option('admin_stripe_secret_key');



	$user_meta = get_userdata($userId);



	//$_POST['cardholdername'];

	$cardholdername = get_user_meta($userId, 'first_name', true);

	$email = $user_meta->data->user_email;

	if (isset($_POST['savecard'])) {



		$amount = $_POST['amount'];



		/* Payment to Admin - Full Payment - Start */



		$curl = curl_init();

		

		$amountincents = $amount * 100;



		curl_setopt_array($curl, array(

			CURLOPT_URL => "https://api.stripe.com/v1/charges",

			CURLOPT_RETURNTRANSFER => true,

			CURLOPT_ENCODING => "",

			CURLOPT_MAXREDIRS => 10,

			CURLOPT_TIMEOUT => 30,

			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

			CURLOPT_CUSTOMREQUEST => "POST",

			CURLOPT_POSTFIELDS => "amount=$amountincents&currency=usd&source=$stripeToken",

			CURLOPT_HTTPHEADER => array(

				"authorization: Bearer $STRIPE_SECRET_KEY",

				"cache-control: no-cache",

				"content-type: application/x-www-form-urlencoded",

			),

		));



		$response = curl_exec($curl);

		$err = curl_error($curl);

		curl_close($curl);



		$data = json_decode($response);



		curl_close($curl);



		/* Payment to Admin - Full Payment - End */



		if($err){

			$result['success'] = false;

			$result['message'] = $err;

			echo json_encode($result);

			exit;

		} else if (isset($data->error)) {

			$result['success'] = false;

			$result['message'] = $data->error->message;

			echo json_encode($result);

			exit;

		} else {

		    

			global $wpdb;

			$event_id = $wpdb->escape($_POST['event_id']);

			$trans = transferToOperator($event_id, $amount, $data->id, $priceArray, $keyArray);

			/*$responsetrans  = json_decode($trans);

			if($responsetrans['success']){

    			$response  = json_decode($response);

    			$success = $wpdb->insert($wpdb->prefix . 'cb_orders', array(

    				'user_id' => get_current_user_id(),

    				'transation_id' => $response->id,

    				'event_id' => $event_id,

    				'amount' => $amount,

    				'price_array' => $priceArray

    			));

			}*/



			



			//echo $wpdb->last_query;

			/*$result['success'] = true;

			$result['message'] = 'Payment successfully done.';

			echo json_encode($result);*/

		}

		exit;



	} else {



		$curl = curl_init();



		curl_setopt_array($curl, array(

			CURLOPT_URL => "https://api.stripe.com/v1/customers",

			CURLOPT_RETURNTRANSFER => true,

			CURLOPT_ENCODING => "",

			CURLOPT_MAXREDIRS => 10,

			CURLOPT_TIMEOUT => 0,

			CURLOPT_FOLLOWLOCATION => true,

			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

			CURLOPT_CUSTOMREQUEST => "POST",

			CURLOPT_POSTFIELDS => "email=" . $email . "&name=" . $cardholdername,

			CURLOPT_HTTPHEADER => array(

				"Authorization: Bearer " . $STRIPE_SECRET_KEY,

				"Content-Type: application/x-www-form-urlencoded"

			),

		));



		$cus_response = curl_exec($curl);

		curl_close($curl);



		$customer_data = json_decode($cus_response);

		$customer_stripe_id = $customer_data->id;



		/* Stripe Create Customer - End */



		/* Add Card - Start */

		$curl = curl_init();



		curl_setopt_array($curl, array(

			CURLOPT_URL => "https://api.stripe.com/v1/customers/$customer_stripe_id/sources",

			CURLOPT_RETURNTRANSFER => true,

			CURLOPT_ENCODING => "",

			CURLOPT_MAXREDIRS => 10,

			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

			CURLOPT_CUSTOMREQUEST => "POST",

			CURLOPT_POSTFIELDS => "source=" . $stripeToken,

			CURLOPT_HTTPHEADER => array(

				"Authorization: Bearer " . $STRIPE_SECRET_KEY,

				"cache-control: no-cache",

				"content-type: application/x-www-form-urlencoded"

			),

		));



		$response = curl_exec($curl);

		$err = curl_error($curl);

	

		$data = json_decode($response);



		curl_close($curl);

		if($err){

			$result['success'] = false;

			$result['message'] = $err;

			echo json_encode($result);

			exit;

		} else if (isset($data->error)) {

			$result['success'] = false;

			$result['message'] = $data->error->message;

			echo json_encode($result);

			exit;

		} else {

			if ($data->id) {

				global $wpdb;

				$success = $wpdb->insert($wpdb->prefix.'cb_credit_card', array(

					'stripe_customer' => $data->customer,

					'card_id' => $data->id,

					'card_last_four_digit' => $data->last4,

					'card_month_expire' => $data->exp_month,

					'card_year_expire' => $data->exp_year,

					'card_brand' => $data->brand,

					'user_id' => $userId,

				));

				

				$result['success'] = true;

				$result['message'] = "Card saved successfully.";

				echo json_encode($result);

				exit;

			} else {

				$result['success'] = false;

				$result['message'] = "Card not saved.";

				echo json_encode($result);

				exit;

			}

		}

	}

	/* Add Card - End */

}



function transferToOperator($event_id, $amount, $transaction_id, $priceArray, $keyArray){



	global $wpdb;



	$STRIPE_SECRET_KEY = get_option('admin_stripe_secret_key');



	$admin_tournament_commission = get_option( 'tournament_commission');



	/* Payment to Operator - After reduce Admin commission - Start */

	$tournament_query =  "SELECT * FROM " . $wpdb->prefix . "cb_tournament where id = '".$event_id."' ";

	$tournament_detail = $wpdb->get_row( $tournament_query);

	

	$operator_user_id = $tournament_detail->user_id;



	$operator_stripe_user_id = get_user_meta($operator_user_id, 'stripe_user_id', true);



	$after_reduce_commission = (($amount * $admin_tournament_commission) / 100);

	$operator_amount = ($amount - $after_reduce_commission) * 100;



	$curl = curl_init();



	curl_setopt_array($curl, array(

	  CURLOPT_URL => "https://api.stripe.com/v1/transfers",

	  CURLOPT_RETURNTRANSFER => true,

	  CURLOPT_ENCODING => "",

	  CURLOPT_MAXREDIRS => 10,

	  CURLOPT_TIMEOUT => 30,

	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

	  CURLOPT_CUSTOMREQUEST => "POST",

	  CURLOPT_POSTFIELDS => "amount=$operator_amount&currency=usd&destination=".$operator_stripe_user_id."&transfer_group=ORDER_95",

	  CURLOPT_HTTPHEADER => array(

	    "authorization: Bearer $STRIPE_SECRET_KEY",

	    "cache-control: no-cache",

	    "content-type: application/x-www-form-urlencoded",

	  ),

	));



	$response = curl_exec($curl);

	$err = curl_error($curl);

    $res_data  = json_decode($response);

	curl_close($curl);

    if($err){

		$result['success'] = false;

		$result['message'] = $err;

		

		echo json_encode($result);

		exit;

	} else if (isset($res_data->error)) {

		$result['success'] = false;

		$result['message'] = $res_data->error->message;

		

		echo json_encode($result);

		exit;

	} else {

	    

	    $success = $wpdb->insert($wpdb->prefix . 'cb_orders', array(

			'user_id' => get_current_user_id(),

			'transation_id' => $transaction_id,

			'event_id' => $event_id,

			'amount' => $amount,

			'price_array' => $priceArray,

			'keyArray' => $keyArray

		));

		//echo $wpdb->last_query;

	    $result['success'] = true;

		$result['message'] =  'Payment successfully done.';

		echo json_encode($result);

		exit;

	}

	/*

	if ($err) {

	  echo "cURL Error #:" . $err;

	} else {

	  echo $response;

	}

	*/

	/* Payment to Operator - After reduce Admin commission - Start */

}



add_action("wp_ajax_delete_save_card", "delete_save_card_func");

function delete_save_card_func()

{



	$STRIPE_SECRET_KEY = get_option('admin_stripe_secret_key');

	$userId = get_current_user_id();



	$customer_id = $_POST['customer_id'];

	$card_id = $_POST['card_id'];



	$curl = curl_init();



	curl_setopt_array($curl, array(

		CURLOPT_URL => "https://api.stripe.com/v1/customers/$customer_id/sources/$card_id",

		CURLOPT_RETURNTRANSFER => true,

		CURLOPT_ENCODING => "",

		CURLOPT_MAXREDIRS => 10,

		CURLOPT_TIMEOUT => 30,

		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

		CURLOPT_CUSTOMREQUEST => "DELETE",

		CURLOPT_HTTPHEADER => array(

			"Authorization: Bearer " . $STRIPE_SECRET_KEY,

			"cache-control: no-cache",

			"content-type: application/x-www-form-urlencoded",

		),

	));



	$response = curl_exec($curl);

	$err = curl_error($curl);



	curl_close($curl);





	global $wpdb;

	$delete = $wpdb->delete($wpdb->prefix.'cb_credit_card', array('card_id' => $card_id));

	echo 1;

	exit;

}



add_action('wp_head', 'add_custom_stripe_key');

function add_custom_stripe_key()

{

?>

	<script type="text/javascript">

		var admin_stripe_pkey = '<?php echo get_option("admin_stripe_publishable_key"); ?>';

		var ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";

	</script>

<?php

}



add_action("wp_ajax_paymentby_card", "paymentby_card");

function paymentby_card()

{

	global $wpdb;



	$userId = get_current_user_id();



	$card_id = $wpdb->escape($_POST['card_id']);

	$amount = $wpdb->escape($_POST['price']);

	$amountincents = $amount * 100;

	$event_id = $wpdb->escape($_POST['event_id']);

	$teamList = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "cb_credit_card WHERE `ID` = '$card_id' AND user_id = '".$userId."' ");



	$price_array = !empty($_POST['price_array']) ? $wpdb->escape($_POST['price_array']) : array();

	$keyArray = !empty($_POST['keyArray']) ? $wpdb->escape($_POST['keyArray']) : array();

	$priceArray = wp_json_encode((array)$price_array);

	$keyArray = wp_json_encode((array)$keyArray);

	

	$STRIPE_SECRET_KEY = get_option('admin_stripe_secret_key');

	$curl = curl_init();



	curl_setopt_array($curl, array(

		CURLOPT_URL => "https://api.stripe.com/v1/charges",

		CURLOPT_RETURNTRANSFER => true,

		CURLOPT_ENCODING => "",

		CURLOPT_MAXREDIRS => 10,

		CURLOPT_TIMEOUT => 30,

		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

		CURLOPT_CUSTOMREQUEST => "POST",

		CURLOPT_POSTFIELDS => "amount=$amountincents&currency=usd&source=$teamList->card_id&customer=$teamList->stripe_customer",

		CURLOPT_HTTPHEADER => array(

			"authorization: Bearer $STRIPE_SECRET_KEY",

			"cache-control: no-cache",

			"content-type: application/x-www-form-urlencoded",

		),

	));

	$response = curl_exec($curl);

	$err = curl_error($curl);



	$res_data  = json_decode($response);

	curl_close($curl);

	if($err){

		$result['success'] = false;

		$result['message'] = $err;

		echo json_encode($result);

		exit;

	} else if (isset($res_data->error)) {

		$result['success'] = false;

		$result['message'] = $res_data->error->message;

		echo json_encode($result);

		exit;

	} else {

	    

		

		$trans = transferToOperator($event_id, $amount, $res_data->id, $priceArray, $keyArray);

		/*$responsetrans  = json_decode($trans);

		

		if($responsetrans['success']){

			$response  = json_decode($response);

			$success = $wpdb->insert($wpdb->prefix . 'cb_orders', array(

				'user_id' => get_current_user_id(),

				'transation_id' => $response->id,

				'event_id' => $event_id,

				'amount' => $amount,

				'price_array' => $priceArray

			));

		}*/

		



		/*transferToOperator($event_id, $amount);



		//echo $wpdb->last_query;



		if($success){

			$result['success'] = true;

			$result['message'] = 'Payment successfully done.';

		} else {

			$result['success'] = false;

			$result['message'] = $wpdb->last_error;

		}

		echo json_encode($result);

		exit;*/

	}

}





add_action("wp_ajax_get_sales_chart_data", "get_sales_chart_data");

function get_sales_chart_data() {



	global $wpdb;



	$userId = get_current_user_id();

	$user_meta = get_userdata($userId);

	$user_roles = $user_meta->roles;

	$mnth = $_GET['thismonth'];

	//$mnth = 3;

    if(!empty($mnth)){

        if (in_array("administrator", $user_roles)) {

            $orders_query = "SELECT * FROM ".$wpdb->prefix."cb_orders WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())";

    	} else {

    	    

        	$orders_query =  "

        		SELECT o.*, o.user_id as order_user_id, ev.name, ev.user_id as optr_user_od FROM ".$wpdb->prefix."cb_orders as o 

        		LEFT JOIN ".$wpdb->prefix."cb_tournament AS ev ON ev.id = o.event_id

        		WHERE ev.user_id = '".$userId."' 

        		AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())

        		";

    	}

    	$my_orders = $wpdb->get_results( $orders_query);

    

    	$my_price_array = [];

    	

    	// for each day in the month

        for($i = 1; $i <=  date('t'); $i++)

        {

           // add the date to the dates array

           $dates[] = date('F Y') . " " . str_pad($i, 2, '0', STR_PAD_LEFT);

        }

        

        if(!empty($my_orders)){

    		

    		foreach ($dates as $mk => $mv) {

    

    		  	foreach ($my_orders as $key => $orders) {

    		  		

    		  		$createdAt = date('F Y d', strtotime($orders->created_at));

    		  		$price_array = json_decode($orders->price_array);

    		  		//print_r($price_array);

    	  			$price_sum = array_sum($price_array);

    	  			//echo $mk;echo '<br>';

    	  			

    	  			if($createdAt == $mv){

    					$my_price_array[$mv] += $price_sum;

    	  			} else {

    	  				$my_price_array[$mv] += 0;		

    	  			}

    	  		}	  		

    	  	}

    

    	  	//$months = '"'.implode('", "', $months).'"';

    

    	} else {

    		foreach ($dates as $mk => $mv) {

    			$my_price_array[$mv] = 0;

    	  	}

    	}

    	

    	$price_values = array_values($my_price_array);

      	//$price_values = array_reverse($price_values);

    

      	$months = array_reverse($dates);

        

      	$return_data['months'] = $dates;

      	$return_data['price_values'] = $price_values;



    }

    else{

    	if (in_array("administrator", $user_roles)) {

            $orders_query = "SELECT * FROM ".$wpdb->prefix."cb_orders WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())";

    		//$orders_query = "SELECT * FROM ".$wpdb->prefix."cb_orders WHERE created_at BETWEEN NOW() - INTERVAL 12 MONTH AND NOW();";

    	} else {

    	    $orders_query =  "

    		SELECT o.*, o.user_id as order_user_id, ev.name, ev.user_id as optr_user_od FROM ".$wpdb->prefix."cb_orders as o 

    		LEFT JOIN ".$wpdb->prefix."cb_tournament AS ev ON ev.id = o.event_id

    		WHERE ev.user_id = '".$userId."' 

    		AND created_at BETWEEN NOW() - INTERVAL 12 MONTH AND NOW();

    		";

    	$orders_query =  "

    		SELECT o.*, o.user_id as order_user_id, ev.name, ev.user_id as optr_user_od FROM ".$wpdb->prefix."cb_orders as o 

    		LEFT JOIN ".$wpdb->prefix."cb_tournament AS ev ON ev.id = o.event_id

    		WHERE ev.user_id = '".$userId."' 

    		AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())

    		";

    		

    		//$orders_query = "SELECT * FROM ".$wpdb->prefix."cb_orders WHERE user_id = '".$userId."' AND created_at BETWEEN NOW() - INTERVAL 12 MONTH AND NOW();";

    	}

    

    	$my_orders = $wpdb->get_results( $orders_query);

    

    	$my_price_array = [];

    

    	for ($i = 0; $i < 12; $i++) {

    	    $months[] = date("F Y", strtotime( date( 'Y-m-01' )." -$i months"));

    	}

    

    	if(!empty($my_orders)){

    		

    		foreach ($months as $mk => $mv) {

    

    		  	foreach ($my_orders as $key => $orders) {

    		  		

    		  		$createdAt = date('F Y', strtotime($orders->created_at));

    		  		$price_array = json_decode($orders->price_array);

    	  			$price_sum = array_sum($price_array);

    	  			

    	  			if($createdAt == $mv){

    					$my_price_array[$mv] += $price_sum;

    	  			} else {

    	  				$my_price_array[$mv] += 0;		

    	  			}

    	  		}	  		

    	  	}

    

    	  	//$months = '"'.implode('", "', $months).'"';

    

    	} else {

    		foreach ($months as $mk => $mv) {

    			$my_price_array[$mv] = 0;

    	  	}

    	}

    	

    	$price_values = array_values($my_price_array);

      	$price_values = array_reverse($price_values);

    

      	$months = array_reverse($months);

    

      	$return_data['months'] = $months;

      	$return_data['price_values'] = $price_values;

    }

	



  	echo json_encode($return_data);

	die;

}





add_action("wp_ajax_delete_stripe_user_id", "delete_stripe_user_id");

function delete_stripe_user_id()

{

    $userId = get_current_user_id();

    $stripe_user_id = get_user_meta($userId, 'stripe_user_id', true);

    $STRIPE_SECRET_KEY = get_option('admin_stripe_secret_key');

    

    

    $client_id = get_option('admin_stripe_client_id');

    $curl = curl_init();

    

    curl_setopt_array($curl, array(

    	CURLOPT_URL => "https://connect.stripe.com/oauth/deauthorize",

    	CURLOPT_RETURNTRANSFER => true,

    	CURLOPT_ENCODING => "",

    	CURLOPT_MAXREDIRS => 10,

    	CURLOPT_TIMEOUT => 30,

    	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

    	CURLOPT_CUSTOMREQUEST => "POST",

    	CURLOPT_POSTFIELDS => "client_id=$client_id&stripe_user_id=$stripe_user_id",

    	CURLOPT_HTTPHEADER => array(

    		"authorization: Bearer $STRIPE_SECRET_KEY",

    		"cache-control: no-cache",

    		"content-type: application/x-www-form-urlencoded",

    	),

    ));

    $response = curl_exec($curl);

    $err = curl_error($curl);

    if( !empty($err) ){

        echo 0;

    }else{

       update_user_meta($userId, 'stripe_user_id', '');

        echo 1; 

    }

    

    die;

}



/**

 * Insert Activity log of client

 */

 function add_activity_log( $user_id, $activity_note ) {

	global $wpdb; 	

   	$wpdb->insert( 

            'wp_cb_activity', 

	            array( 

	            	'user_id'   => $user_id, 

	            	'note' 		=> $activity_note, 

	            	'create_at' => date("Y-m-d H:i:s")

	            ), 

	            array( 

	                '%d', '%s', '%s'

	            ) 

          	);  

 }



/**

 * Insert Activity log of client

 */

function getUiAvtarUrl($name){

	return 'https://ui-avatars.com/api/?background=random&rounded=true&format=svg&name='.$name;

}

/**
 * Insert Activity log of client
 */
add_action("wp_ajax_get-notification", "get_notification_func");
function get_notification_func() {
	global $wpdb; 

	$notification = array();

	$recentActivities = $wpdb->get_results('SELECT * FROM `wp_cb_activity` ORDER BY id DESC LIMIT 5'); // AND `create_at` > '.date('Y-m-d').'00:00:00
             
    foreach ($recentActivities as $activitykey => $activity) {
        $notification[] = '<span class="dropdown-item">'.get_user_meta( $activity->user_id, 'first_name', true).' '.get_user_meta( $activity->user_id, 'last_name', true).' '.$activity->note.'</span>';
    }

    $response['success'] = true;
    $response['totalCount'] = count($notification);
    $response['notification'] = implode(" ",$notification); 
	echo json_encode($response); 
	die();  
}

// Stop Notification of Password Changed
if ( !function_exists( 'wp_password_change_notification' ) ) {
    function wp_password_change_notification() {}
}

/**
 * CLG Mail Verify OTP Send(Mail)
 */
add_action("wp_ajax_nopriv_clg-mail-verify-otp-send", "clg_mail_verify_otp_send_func");
function clg_mail_verify_otp_send_func() {
	global $wpdb; 
	session_start();
	$response['success'] = false;

	$first_name = $wpdb->escape($_POST['first_name']); 
	$last_name 	= $wpdb->escape($_POST['last_name']); 
	$useremail 	= $wpdb->escape($_POST['useremail']);
	$resend = isset($_POST['resend']) ? $_POST['resend'] : 'false';

	$user_id = username_exists( $useremail ); 
	if( !$user_id and email_exists($useremail) == false )
	{	
		// generate OTP
		$otp = rand(100000,999999);

		$_SESSION['clg-mailverify-otp'] 	= $otp;
		$_SESSION['clg-mailverify-email'] 	= $useremail;

		$response['success'] 	= true;
		$response['otp-number'] = $otp;


		$to = $useremail; 
		$subject = "Your Collegebound Email Verification OTP";

		$mail_args = array( 	
			"mail_subject" 	=> $subject,
			"main_title" 	=> "Hi ".$first_name." ".$last_name,
			"main_content" 	=> "Thanks for contacting us. Your OTP is : ".$otp,
			"button_text" 	=> "",
			"button_link"	=> "",
			//"header_logo" 	=> $site_logo,
			//"footer_logo"	=> get_stylesheet_directory_uri()."/images/front/mail_footer_logo.png",
			"footer_left_text"	=> "Copyright Collegebound &copy;".date('Y'),
			"footer_right_text" => 'Powered By <a href="https://www.collegebound.app/">Collegebound</a>', );

		$body = set_mail_content($mail_args);
		//echo $body; die('FFF');
		$from = 'From: '.get_option('blogname').'<info@collegebound.app>';
		$headers = array('Content-Type: text/html; charset=UTF-8', $from);
		 
		wp_mail( $to, $subject, $body, $headers );

		if( $resend  == 'false' )
		{
			$response['message'] 	= 'A One Time Password has been sent to '.$useremail.'<br/>Please enter the OTP in the field below to verify.';
		} else {
			$response['message'] 	= 'Re-Send One Time Password to '.$useremail.'.';
		}

	} else {
		$response['message'] 	= 'Email already exist.';
	}

	echo json_encode($response); 
	die();  
}

/**
 * CLG Mail OTP Validate
 */
add_action("wp_ajax_nopriv_clg-mail-otp-validate", "clg_mail_otp_validate_func");
function clg_mail_otp_validate_func() {
	global $wpdb; 
	session_start();
	$response['success'] = false;

	$first_name = $wpdb->escape($_POST['first_name']); 
	$last_name 	= $wpdb->escape($_POST['last_name']); 
	$useremail 	= $wpdb->escape($_POST['useremail']);
	$otp_number = $wpdb->escape($_POST['otp_number']);

	$user_id = username_exists( $useremail ); 
	if( $user_id || email_exists($useremail) == true ) 
	{	
		$response['message'] = 'Email already exist.';
		echo json_encode($response); 
		die();		 
	}

	if( empty($_SESSION['clg-mailverify-otp']) || empty($_SESSION['clg-mailverify-email']) )
	{
		$response['message'] = 'Please enter OTP.';
		echo json_encode($response); 
		die();
	}

	if( ($_SESSION['clg-mailverify-otp'] != $otp_number) || ($_SESSION['clg-mailverify-email'] != $useremail) )
	{
		$response['message'] = 'OTP is no valid. Please resend OTP.';
		echo json_encode($response); 
		die();
	} 
	else 
	{
		
		// Remove Verified OTP Over session variable
		unset($_SESSION['clg-mailverify-otp']);

		// Set Mail Is Verified Over session variable
		$_SESSION['clg-mailverify-status'] = true;

		$response['success'] = true;
		$response['message'] = 'OTP validate successfully.';
		echo json_encode($response); 
		die();
	}
}

function file_get_contents_curl($url) {
    /*$ch = curl_init();

    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;*/

    return "<!doctype html>
<html xmlns='http://www.w3.org/1999/xhtml' xmlns:v='urn:schemas-microsoft-com:vml' xmlns:o='urn:schemas-microsoft-com:office:office'>
   <head>
      <!-- NAME: EDUCATE -->
      <!--[if gte mso 15]>
      <xml>
         <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
         </o:OfficeDocumentSettings>
      </xml>
      <![endif]-->
      <meta charset='UTF-8'>
      <meta http-equiv='X-UA-Compatible' content='IE=edge'>
      <meta name='viewport' content='width=device-width, initial-scale=1'>
      <title></title>
      <style type='text/css'>
         p{
         margin:10px 0;
         padding:0;
         }
         table{
         border-collapse:collapse;
         }
         h1,h2,h3,h4,h5,h6{
         display:block;
         margin:0;
         padding:0;
         }
         img,a img{
         border:0;
         height:auto;
         outline:none;
         text-decoration:none;
         }
         body,#bodyTable,#bodyCell{
         height:100%;
         margin:0;
         padding:0;
         width:100%;
         }
         .mcnPreviewText{
         display:none !important;
         }
         #outlook a{
         padding:0;
         }
         img{
         -ms-interpolation-mode:bicubic;
         }
         table{
         mso-table-lspace:0pt;
         mso-table-rspace:0pt;
         }
         .ReadMsgBody{
         width:100%;
         }
         .ExternalClass{
         width:100%;
         }
         p,a,li,td,blockquote{
         mso-line-height-rule:exactly;
         }
         a[href^=tel],a[href^=sms]{
         color:inherit;
         cursor:default;
         text-decoration:none;
         }
         p,a,li,td,body,table,blockquote{
         -ms-text-size-adjust:100%;
         -webkit-text-size-adjust:100%;
         }
         .ExternalClass,.ExternalClass p,.ExternalClass td,.ExternalClass div,.ExternalClass span,.ExternalClass font{
         line-height:100%;
         }
         a[x-apple-data-detectors]{
         color:inherit !important;
         text-decoration:none !important;
         font-size:inherit !important;
         font-family:inherit !important;
         font-weight:inherit !important;
         line-height:inherit !important;
         }
         .templateContainer{
         max-width:600px !important;
         }
         a.mcnButton{
         display:block;
         }
         .mcnImage,.mcnRetinaImage{
         vertical-align:bottom;
         }
         .mcnTextContent{
         word-break:break-word;
         }
         .mcnTextContent img{
         height:auto !important;
         }
         .mcnDividerBlock{
         table-layout:fixed !important;
         }
         /*
         @tab Page
         @section Heading 1
         @style heading 1
         */
         h1{
         /*@editable*/color:#222222;
         /*@editable*/font-family:Helvetica;
         /*@editable*/font-size:40px;
         /*@editable*/font-style:normal;
         /*@editable*/font-weight:bold;
         /*@editable*/line-height:150%;
         /*@editable*/letter-spacing:normal;
         /*@editable*/text-align:left;
         }
         /*
         @tab Page
         @section Heading 2
         @style heading 2
         */
         h2{
         /*@editable*/color:#222222;
         /*@editable*/font-family:Helvetica;
         /*@editable*/font-size:28px;
         /*@editable*/font-style:normal;
         /*@editable*/font-weight:bold;
         /*@editable*/line-height:150%;
         /*@editable*/letter-spacing:normal;
         /*@editable*/text-align:left;
         }
         /*
         @tab Page
         @section Heading 3
         @style heading 3
         */
         h3{
         /*@editable*/color:#444444;
         /*@editable*/font-family:Helvetica;
         /*@editable*/font-size:22px;
         /*@editable*/font-style:normal;
         /*@editable*/font-weight:bold;
         /*@editable*/line-height:150%;
         /*@editable*/letter-spacing:normal;
         /*@editable*/text-align:left;
         }
         /*
         @tab Page
         @section Heading 4
         @style heading 4
         */
         h4{
         /*@editable*/color:#999999;
         /*@editable*/font-family:Georgia;
         /*@editable*/font-size:20px;
         /*@editable*/font-style:italic;
         /*@editable*/font-weight:normal;
         /*@editable*/line-height:125%;
         /*@editable*/letter-spacing:normal;
         /*@editable*/text-align:left;
         }
         /*
         @tab Header
         @section Header Container Style
         */
         #templateHeader{
         /*@editable*/background-color:#ffffff;
         /*@editable*/background-image:none;
         /*@editable*/background-repeat:no-repeat;
         /*@editable*/background-position:center;
         /*@editable*/background-size:cover;
         /*@editable*/border-top:0;
         /*@editable*/border-bottom:0;
         /*@editable*/padding-top:25px;
         /*@editable*/padding-bottom:25px;
         }
         /*
         @tab Header
         @section Header Interior Style
         */
         .headerContainer{
         /*@editable*/background-color:transparent;
         /*@editable*/background-image:none;
         /*@editable*/background-repeat:no-repeat;
         /*@editable*/background-position:center;
         /*@editable*/background-size:cover;
         /*@editable*/border-top:0;
         /*@editable*/border-bottom:0;
         /*@editable*/padding-top:0;
         /*@editable*/padding-bottom:0;
         }
         /*
         @tab Header
         @section Header Text
         */
         .headerContainer .mcnTextContent,.headerContainer .mcnTextContent p{
         /*@editable*/color:#808080;
         /*@editable*/font-family:Helvetica;
         /*@editable*/font-size:16px;
         /*@editable*/line-height:150%;
         /*@editable*/text-align:left;
         }
         /*
         @tab Header
         @section Header Link
         */
         .headerContainer .mcnTextContent a,.headerContainer .mcnTextContent p a{
         /*@editable*/color:#00ADD8;
         /*@editable*/font-weight:normal;
         /*@editable*/text-decoration:underline;
         }
         /*
         @tab Body
         @section Body Container Style
         */
         #templateBody{
         /*@editable*/background-color:#FFFFFF;
         /*@editable*/background-image:none;
         /*@editable*/background-repeat:no-repeat;
         /*@editable*/background-position:center;
         /*@editable*/background-size:cover;
         /*@editable*/border-top:0;
         /*@editable*/border-bottom:0;
         /*@editable*/padding-top:27px;
         /*@editable*/padding-bottom:20px;
         }
         /*
         @tab Body
         @section Body Interior Style
         */
         .bodyContainer{
         /*@editable*/background-color:transparent;
         /*@editable*/background-image:none;
         /*@editable*/background-repeat:no-repeat;
         /*@editable*/background-position:center;
         /*@editable*/background-size:cover;
         /*@editable*/border-top:0;
         /*@editable*/border-bottom:0;
         /*@editable*/padding-top:0;
         /*@editable*/padding-bottom:0;
         }
         /*
         @tab Body
         @section Body Text
         */
         .bodyContainer .mcnTextContent,.bodyContainer .mcnTextContent p{
         /*@editable*/color:#808080;
         /*@editable*/font-family:Helvetica;
         /*@editable*/font-size:16px;
         /*@editable*/line-height:150%;
         /*@editable*/text-align:left;
         }
         /*
         @tab Body
         @section Body Link
         */
         .bodyContainer .mcnTextContent a,.bodyContainer .mcnTextContent p a{
         /*@editable*/color:#00ADD8;
         /*@editable*/font-weight:normal;
         /*@editable*/text-decoration:underline;
         }
         /*
         @tab Footer
         @section Footer Style
         */
         #templateFooter{
         /*@editable*/background-color:#333333;
         /*@editable*/background-image:none;
         /*@editable*/background-repeat:no-repeat;
         /*@editable*/background-position:center;
         /*@editable*/background-size:cover;
         /*@editable*/border-top:0;
         /*@editable*/border-bottom:0;
         /*@editable*/padding-top:45px;
         /*@editable*/padding-bottom:63px;
         }
         /*
         @tab Footer
         @section Footer Interior Style
         */
         .footerContainer{
         /*@editable*/background-color:transparent;
         /*@editable*/background-image:none;
         /*@editable*/background-repeat:no-repeat;
         /*@editable*/background-position:center;
         /*@editable*/background-size:cover;
         /*@editable*/border-top:0;
         /*@editable*/border-bottom:0;
         /*@editable*/padding-top:0;
         /*@editable*/padding-bottom:0;
         }
         /*
         @tab Footer
         @section Footer Text
         */
         .footerContainer .mcnTextContent,.footerContainer .mcnTextContent p{
         /*@editable*/color:#FFFFFF;
         /*@editable*/font-family:Helvetica;
         /*@editable*/font-size:12px;
         /*@editable*/line-height:150%;
         /*@editable*/text-align:center;
         }
         /*
         @tab Footer
         @section Footer Link
         */
         .footerContainer .mcnTextContent a,.footerContainer .mcnTextContent p a{
         /*@editable*/color:#FFFFFF;
         /*@editable*/font-weight:normal;
         /*@editable*/text-decoration:underline;
         }
         @media only screen and (min-width:768px){
         .templateContainer{
         width:600px !important;
         }
         }	@media only screen and (max-width: 480px){
         body,table,td,p,a,li,blockquote{
         -webkit-text-size-adjust:none !important;
         }
         }	@media only screen and (max-width: 480px){
         body{
         width:100% !important;
         min-width:100% !important;
         }
         }	@media only screen and (max-width: 480px){
         .mcnRetinaImage{
         max-width:100% !important;
         }
         }	@media only screen and (max-width: 480px){
         .mcnImage{
         width:100% !important;
         }
         }	@media only screen and (max-width: 480px){
         .mcnCartContainer,.mcnCaptionTopContent,.mcnRecContentContainer,.mcnCaptionBottomContent,.mcnTextContentContainer,.mcnBoxedTextContentContainer,.mcnImageGroupContentContainer,.mcnCaptionLeftTextContentContainer,.mcnCaptionRightTextContentContainer,.mcnCaptionLeftImageContentContainer,.mcnCaptionRightImageContentContainer,.mcnImageCardLeftTextContentContainer,.mcnImageCardRightTextContentContainer,.mcnImageCardLeftImageContentContainer,.mcnImageCardRightImageContentContainer{
         max-width:100% !important;
         width:100% !important;
         }
         }	@media only screen and (max-width: 480px){
         .mcnBoxedTextContentContainer{
         min-width:100% !important;
         }
         }	@media only screen and (max-width: 480px){
         .mcnImageGroupContent{
         padding:9px !important;
         }
         }	@media only screen and (max-width: 480px){
         .mcnCaptionLeftContentOuter .mcnTextContent,.mcnCaptionRightContentOuter .mcnTextContent{
         padding-top:9px !important;
         }
         }	@media only screen and (max-width: 480px){
         .mcnImageCardTopImageContent,.mcnCaptionBottomContent:last-child .mcnCaptionBottomImageContent,.mcnCaptionBlockInner .mcnCaptionTopContent:last-child .mcnTextContent{
         padding-top:18px !important;
         }
         }	@media only screen and (max-width: 480px){
         .mcnImageCardBottomImageContent{
         padding-bottom:9px !important;
         }
         }	@media only screen and (max-width: 480px){
         .mcnImageGroupBlockInner{
         padding-top:0 !important;
         padding-bottom:0 !important;
         }
         }	@media only screen and (max-width: 480px){
         .mcnImageGroupBlockOuter{
         padding-top:9px !important;
         padding-bottom:9px !important;
         }
         }	@media only screen and (max-width: 480px){
         .mcnTextContent,.mcnBoxedTextContentColumn{
         padding-right:18px !important;
         padding-left:18px !important;
         }
         }	@media only screen and (max-width: 480px){
         .mcnImageCardLeftImageContent,.mcnImageCardRightImageContent{
         padding-right:18px !important;
         padding-bottom:0 !important;
         padding-left:18px !important;
         }
         }	@media only screen and (max-width: 480px){
         .mcpreview-image-uploader{
         display:none !important;
         width:100% !important;
         }
         }	@media only screen and (max-width: 480px){
         /*
         @tab Mobile Styles
         @section Heading 1
         @tip Make the first-level headings larger in size for better readability on small screens.
         */
         h1{
         /*@editable*/font-size:30px !important;
         /*@editable*/line-height:125% !important;
         }
         }	@media only screen and (max-width: 480px){
         /*
         @tab Mobile Styles
         @section Heading 2
         @tip Make the second-level headings larger in size for better readability on small screens.
         */
         h2{
         /*@editable*/font-size:26px !important;
         /*@editable*/line-height:125% !important;
         }
         }	@media only screen and (max-width: 480px){
         /*
         @tab Mobile Styles
         @section Heading 3
         @tip Make the third-level headings larger in size for better readability on small screens.
         */
         h3{
         /*@editable*/font-size:20px !important;
         /*@editable*/line-height:150% !important;
         }
         }	@media only screen and (max-width: 480px){
         /*
         @tab Mobile Styles
         @section Heading 4
         @tip Make the fourth-level headings larger in size for better readability on small screens.
         */
         h4{
         /*@editable*/font-size:18px !important;
         /*@editable*/line-height:150% !important;
         }
         }	@media only screen and (max-width: 480px){
         /*
         @tab Mobile Styles
         @section Boxed Text
         @tip Make the boxed text larger in size for better readability on small screens. We recommend a font size of at least 16px.
         */
         .mcnBoxedTextContentContainer .mcnTextContent,.mcnBoxedTextContentContainer .mcnTextContent p{
         /*@editable*/font-size:14px !important;
         /*@editable*/line-height:150% !important;
         }
         }	@media only screen and (max-width: 480px){
         /*
         @tab Mobile Styles
         @section Header Text
         @tip Make the header text larger in size for better readability on small screens.
         */
         .headerContainer .mcnTextContent,.headerContainer .mcnTextContent p{
         /*@editable*/font-size:16px !important;
         /*@editable*/line-height:150% !important;
         }
         }	@media only screen and (max-width: 480px){
         /*
         @tab Mobile Styles
         @section Body Text
         @tip Make the body text larger in size for better readability on small screens. We recommend a font size of at least 16px.
         */
         .bodyContainer .mcnTextContent,.bodyContainer .mcnTextContent p{
         /*@editable*/font-size:16px !important;
         /*@editable*/line-height:150% !important;
         }
         }	@media only screen and (max-width: 480px){
         /*
         @tab Mobile Styles
         @section Footer Text
         @tip Make the footer content text larger in size for better readability on small screens.
         */
         .footerContainer .mcnTextContent,.footerContainer .mcnTextContent p{
         /*@editable*/font-size:14px !important;
         /*@editable*/line-height:150% !important;
         }
         }
      </style>
   </head>
   <body> 
      <center>
         <table align='center' border='0' cellpadding='0' cellspacing='0' height='100%' width='100%' id='bodyTable'>
            <tr>
               <td align='center' valign='top' id='bodyCell'>
                  <!-- BEGIN TEMPLATE // -->
                  <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                     <tr>
                        <td align='center' valign='top' id='templateHeader' data-template-container>
                           <!--[if (gte mso 9)|(IE)]>
                           <table align='center' border='0' cellspacing='0' cellpadding='0' width='600' style='width:600px;'>
                              <tr>
                                 <td align='center' valign='top' width='600' style='width:600px;'>
                                    <![endif]-->
                                    <table align='center' border='0' cellpadding='0' cellspacing='0' width='100%' class='templateContainer'>
                                       <tr>
                                          <td valign='top' class='headerContainer'>
                                             <table border='0' cellpadding='0' cellspacing='0' width='100%' class='mcnImageBlock' style='min-width:100%;'>
                                                <tbody class='mcnImageBlockOuter'>
                                                   <tr>
                                                      <td valign='top' style='padding:9px' class='mcnImageBlockInner'>
                                                         <table align='left' width='100%' border='0' cellpadding='0' cellspacing='0' class='mcnImageContentContainer' style='min-width:100%;'>
                                                            <tbody>
                                                               <tr>
                                                                  <td class='mcnImageContent' valign='top' style='padding-right: 9px; padding-left: 9px; padding-top: 0; padding-bottom: 0; text-align:center;'>
                                                                     <img align='center' alt='' src='{{site_logo}}' width='141' style='max-width: 141px; padding-bottom: 0px; vertical-align: bottom; display: inline !important; border-radius: 0%;' class='mcnImage'>
                                                                  </td>
                                                               </tr>
                                                            </tbody>
                                                         </table>
                                                      </td>
                                                   </tr>
                                                </tbody>
                                             </table>
                                          </td>
                                       </tr>
                                    </table>
                                    <!--[if (gte mso 9)|(IE)]>
                                 </td>
                              </tr>
                           </table>
                           <![endif]-->
                        </td>
                     </tr>
                     <tr>
                        <td align='center' valign='top' id='templateBody' data-template-container>
                           <!--[if (gte mso 9)|(IE)]>
                           <table align='center' border='0' cellspacing='0' cellpadding='0' width='600' style='width:600px;'>
                              <tr>
                                 <td align='center' valign='top' width='600' style='width:600px;'>
                                    <![endif]-->
                                    <table align='center' border='0' cellpadding='0' cellspacing='0' width='100%' class='templateContainer'>
                                       <tr>
                                          <td valign='top' class='bodyContainer'>
                                             <table border='0' cellpadding='0' cellspacing='0' width='100%' class='mcnTextBlock' style='min-width:100%;'>
                                                <tbody class='mcnTextBlockOuter'>
                                                   <tr>
                                                      <td valign='top' class='mcnTextBlockInner' style='padding-top:9px;'>
                                                         <!--[if mso]>
                                                         <table align='left' border='0' cellspacing='0' cellpadding='0' width='100%' style='width:100%;'>
                                                            <tr>
                                                               <![endif]-->
                                                               <!--[if mso]>
                                                               <td valign='top' width='600' style='width:600px;'>
                                                                  <![endif]-->
                                                                  <table align='left' border='0' cellpadding='0' cellspacing='0' style='max-width:100%; min-width:100%;' width='100%' class='mcnTextContentContainer'>
                                                                     <tbody>
                                                                        <tr>
                                                                           <td valign='top' class='mcnTextContent' style='padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;'>
                                                                              <h2 style='text-align: center;'>{{main_title}}</h2>
                                                                              <p style='font-size: 18px !important; text-align: center;'>{{main_content}}</p>
                                                                           </td>
                                                                        </tr>
                                                                     </tbody>
                                                                  </table>
                                                                  <!--[if mso]>
                                                               </td>
                                                               <![endif]-->
                                                               <!--[if mso]>
                                                            </tr>
                                                         </table>
                                                         <![endif]-->
                                                      </td>
                                                   </tr>
                                                </tbody>
                                             </table>
                                             <table border='0' cellpadding='0' cellspacing='0' width='100%' class='mcnButtonBlock' style='min-width:100%;{{button_display}}'>
                                                <tbody class='mcnButtonBlockOuter'>
                                                   <tr>
                                                      <td style='padding-top:0; padding-right:18px; padding-bottom:18px; padding-left:18px;' valign='top' align='center' class='mcnButtonBlockInner'>
                                                         <table border='0' cellpadding='0' cellspacing='0' class='mcnButtonContentContainer' style='border-collapse: separate !important;border-radius: 3px;background-color: #00ADD8;'>
                                                            <tbody>
                                                               <tr>
                                                                  <td align='center' valign='middle' class='mcnButtonContent' style='font-family: Helvetica; font-size: 18px; padding: 18px;'>
                                                                     <a class='mcnButton ' title='{{button_text}}' href='{{button_link}}' target='_blank' style='font-weight: bold;letter-spacing: -0.5px;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;'>{{button_text}}</a>
                                                                  </td>
                                                               </tr>
                                                            </tbody>
                                                         </table>
                                                        <p style='padding-top: 40px; font-style: italic; font-size: 16px !important; text-align: center; color:#808080;'>{{extra_notes}}</p>
                                                      </td>
                                                   </tr>
                                                </tbody>
                                             </table>  
                                          </td>
                                       </tr>
                                    </table>
                                    <!--[if (gte mso 9)|(IE)]>
                                 </td>
                              </tr>
                           </table>
                           <![endif]-->
                        </td>
                     </tr>
                     <tr>
                        <td align='center' valign='top' id='templateFooter' data-template-container>
                           <!--[if (gte mso 9)|(IE)]>
                           <table align='center' border='0' cellspacing='0' cellpadding='0' width='600' style='width:600px;'>
                              <tr>
                                 <td align='center' valign='top' width='600' style='width:600px;'>
                                    <![endif]-->
                                    <table align='center' border='0' cellpadding='0' cellspacing='0' width='100%' class='templateContainer'>
                                       <tr>
                                          <td valign='top' class='footerContainer'>
                                             <!-- <p style='font-size: 18px !important; text-align: center; color:#ffffff;'>{{extra_notes}}</p> -->
                                             <table border='0' cellpadding='0' cellspacing='0' width='100%' class='mcnImageBlock' style='min-width:100%;'>
                                                <tbody class='mcnImageBlockOuter'>
                                                   <tr>
                                                      <td valign='top' style='padding:9px' class='mcnImageBlockInner'>
                                                         <table align='left' width='100%' border='0' cellpadding='0' cellspacing='0' class='mcnImageContentContainer' style='min-width:100%;'>
                                                            <tbody>
                                                               <tr>
                                                                  <td class='mcnImageContent' valign='top' style='padding-right: 9px; padding-left: 9px; padding-top: 0; padding-bottom: 0; text-align:center;'>
                                                                     <img align='center' alt='' src='{{footer_logo}}' width='115' style='max-width:115px; padding-bottom: 0; display: inline !important; vertical-align: bottom;' class='mcnImage'>
                                                                  </td>
                                                               </tr>
                                                            </tbody>
                                                         </table>
                                                      </td>
                                                   </tr>
                                                </tbody>
                                             </table>
                                             <table border='0' cellpadding='0' cellspacing='0' width='100%' class='mcnDividerBlock' style='min-width:100%;'>
                                                <tbody class='mcnDividerBlockOuter'>
                                                   <tr>
                                                      <td class='mcnDividerBlockInner' style='min-width:100%; padding:18px;'>
                                                         <table class='mcnDividerContent' border='0' cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%;border-top: 2px solid #505050;'>
                                                            <tbody>
                                                               <tr>
                                                                  <td>
                                                                     <span></span>
                                                                  </td>
                                                               </tr>
                                                            </tbody>
                                                         </table>
                                                         <!--            
                                                            <td class='mcnDividerBlockInner' style='padding: 18px;'>
                                                            <hr class='mcnDividerContent' style='border-bottom-color:none; border-left-color:none; border-right-color:none; border-bottom-width:0; border-left-width:0; border-right-width:0; margin-top:0; margin-right:0; margin-bottom:0; margin-left:0;' />
                                                            -->
                                                      </td>
                                                   </tr>
                                                </tbody>
                                             </table>
                                             <table border='0' cellpadding='0' cellspacing='0' width='100%' class='mcnTextBlock' style='min-width:100%;'>
                                                <tbody class='mcnTextBlockOuter'>
                                                   <tr>
                                                      <td valign='top' class='mcnTextBlockInner' style='padding-top:9px;'>
                                                         <!--[if mso]>
                                                         <table align='left' border='0' cellspacing='0' cellpadding='0' width='100%' style='width:100%;'>
                                                            <tr>
                                                               <![endif]-->
                                                               <!--[if mso]>
                                                               <td valign='top' width='600' style='width:600px;'>
                                                                  <![endif]-->
                                                                  <table align='left' border='0' cellpadding='0' cellspacing='0' style='max-width:100%; min-width:100%;' width='100%' class='mcnTextContentContainer'>
                                                                     <tbody>
                                                                        <tr>
                                                                           <td valign='top' class='mcnTextContent' style='padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;'>
                                                                              <em>{{footer_left_text}} | {{footer_right_text}}</em>
                                                                           </td>
                                                                        </tr>
                                                                     </tbody>
                                                                  </table>
                                                                  <!--[if mso]>
                                                               </td>
                                                               <![endif]-->
                                                               <!--[if mso]>
                                                            </tr>
                                                         </table>
                                                         <![endif]-->
                                                      </td>
                                                   </tr>
                                                </tbody>
                                             </table>
                                          </td>
                                       </tr>
                                    </table>
                                    <!--[if (gte mso 9)|(IE)]>
                                 </td>
                              </tr>
                           </table>
                           <![endif]-->
                        </td>
                     </tr>
                  </table>
                  <!-- // END TEMPLATE -->
               </td>
            </tr>
         </table>
      </center>
   </body>
</html>";
}

function set_mail_content($input){
	$mail_template = file_get_contents_curl( get_template_directory_uri().'/assets/mail-templates/main.html');

	$input['button_display'] = '';

	$input['site_logo'] = get_template_directory_uri().'/images/blank_logo.png';
	$subscriber_logo = get_option('site_logo', false);
    if( $subscriber_logo ){
      	// {{site_log}}
		$input['site_logo'] = $subscriber_logo;
    }         

    if( $input['mail_subject'] == "Assessment Invite" ){
		$input['extra_notes'] = 'Please Note: Your invitation link will expire if unused.';
	} else{
		$input['extra_notes'] = '';
	}   

    foreach ($input as $shortcode => $value) {	
    	if($shortcode == 'button_text' || $shortcode == 'button_link'){
    		if( empty($value) ){  
    			$mail_template = str_ireplace('{{button_display}}', 'display:none;',  $mail_template);
    		}
    	}

    	$mail_template = str_ireplace('{{'.$shortcode.'}}', $value,  $mail_template);
    } 

	return $mail_template; 
}

/**

 * Toggle Update Save Prospect

 */

function cb_toggle_save_coach_func() {
	global $wpdb;
	$userId = get_current_user_id();
  
	$coach_id 	 = !empty($_POST['coach-id']) ? $_POST['coach-id'] : ''; 

	$query = "SELECT * FROM `".$wpdb->prefix."cb_saved_coach` WHERE `user_id` = '".$userId."' AND `coach_id`= '".$coach_id."'";
	$save_prospect = $wpdb->get_results($query);

	if( isset($save_prospect[0]->id) ){
		 // Delete Code
		$wpdb->query("DELETE FROM ".$wpdb->prefix."cb_saved_coach WHERE `id` = '".$save_prospect[0]->id."'");  
	} else {
		$team_data = 
		array(
			'user_id'     => $userId,
			'coach_id'     => $coach_id, 
		);

		$wpdb->insert( 
	        $wpdb->prefix.'cb_saved_coach', 
	        $team_data, 
	        array( 
	            '%d','%d'
	        ) 
	    );
	}
 
	$response['success'] 	  = true;
	echo json_encode($response);
	die();
}
add_action('wp_ajax_toggle-save-coach','cb_toggle_save_coach_func');

/**
 * Coach Review
 */
function coach_review_func(){ 
	global $wpdb;
	$userId = get_current_user_id();

	$note 		 = !empty($_POST['note']) ? $_POST['note'] : ''; 
	$rating 	 = !empty($_POST['rating']) ? $_POST['rating'] : array();
	$coach_id 	 = !empty($_POST['member-id']) ? $_POST['member-id'] : ''; 

	$query = "SELECT * FROM `".$wpdb->prefix."cb_review_coach` WHERE `user_id` ='".$userId."' AND `coach_id`= '".$coach_id."'";
	$review = $wpdb->get_results($query);

	if( isset($review[0]->id) ){
		$update_status = $wpdb->update( 
			$wpdb->prefix.'cb_review_coach', 
			array(  
				'note'		=> $note,
                'rating'   	=> json_encode($rating), 
                'update_at'	=> date('Y-m-d H:i:s')
            ),
			array( 'id' => $review[0]->id ), 
			array( '%s', '%s', '%s' ), 
			array( '%d' ) 
		);
	} else {
		$team_data = 
		array(
			'user_id'     	=> $userId,
			'coach_id'     	=> $coach_id, 
			'note'			=> $note,
	        'rating'        => json_encode($rating)
		);

		$wpdb->insert( 
	        $wpdb->prefix.'cb_review_coach', 
	        $team_data, 
	        array( 
	            '%d','%d','%s','%s'
	        ) 
	    );

	    $review_id = $wpdb->insert_id;
	} 	
 
	$response['success'] 	  = true;
	echo json_encode($response);
	die();
}
add_action('wp_ajax_team-coach-review','coach_review_func');