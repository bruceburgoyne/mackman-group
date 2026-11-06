<?php
/**
 * Mackman Group ACF theme bootstrap.
 *
 * @package mackman-group-acf
 */

defined( 'ABSPATH' ) || exit;

define( 'MG_VERSION', '0.1.0' );

require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/acf.php';
require_once get_template_directory() . '/inc/editor.php';
require_once get_template_directory() . '/inc/tracking.php';
require_once get_template_directory() . '/inc/forms.php';

/**
 * Google Fonts stylesheet: EB Garamond (headings) and Lato (body).
 * Before going live, self-host these: loading from Google sends visitor IP
 * addresses to Google (a UK GDPR consideration).
 *
 * @return string
 */
function mg_fonts_url() {
	return 'https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,500;0,700;1,400&family=Lato:ital,wght@0,400;0,700;1,400&display=swap';
}

add_action(
	'after_setup_theme',
	function () {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );

		register_nav_menus(
			array(
				'primary' => __( 'Primary navigation', 'mackman-group-acf' ),
				'footer'  => __( 'Footer links', 'mackman-group-acf' ),
			)
		);
	}
);

add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style( 'mg-fonts', mg_fonts_url(), array(), null );
		wp_enqueue_style( 'mg-tokens', get_template_directory_uri() . '/assets/css/tokens.css', array(), MG_VERSION );
		wp_enqueue_style( 'mg-main', get_template_directory_uri() . '/assets/css/main.css', array( 'mg-fonts', 'mg-tokens' ), MG_VERSION );

		wp_enqueue_script(
			'mg-menu',
			get_template_directory_uri() . '/assets/js/menu.js',
			array(),
			MG_VERSION,
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
	}
);

add_action(
	'wp_head',
	function () {
		echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
		echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
	},
	1
);

// The primary menu is output twice (header and mobile panel). Drop the list
// item IDs from the panel copy so the page has no duplicate IDs.
add_filter(
	'nav_menu_item_id',
	function ( $id, $item, $args ) {
		return ( isset( $args->menu_class ) && 'mobile-panel__list' === $args->menu_class ) ? '' : $id;
	},
	10,
	3
);

// Archive titles without the "Category:" style prefix.
add_filter( 'get_the_archive_title_prefix', '__return_false' );

/**
 * Drop jQuery Migrate on the front end (carried over from the old theme).
 */
add_action(
	'wp_default_scripts',
	function ( $scripts ) {
		if ( is_admin() || empty( $scripts->registered['jquery'] ) ) {
			return;
		}
		$scripts->registered['jquery']->deps = array_diff( $scripts->registered['jquery']->deps, array( 'jquery-migrate' ) );
	}
);
