<?php
/**
 * Editing experience: ACF for pages, a cut-down block editor for posts.
 *
 * - Pages have no content editor at all. ACF fields are the only CMS controls.
 * - Posts (News articles) use the block editor, but only for writing: a short
 *   list of content blocks, no colour, font size or spacing controls, and
 *   headings limited to H2 to H4. Design stays in the theme. theme.json
 *   switches the design controls off; this file does the rest.
 * - Block styles load on single articles only, so the ACF pages are unchanged.
 *
 * @package mackman-group-acf
 */

defined( 'ABSPATH' ) || exit;

/**
 * Post types that use the block editor. Everything else gets ACF only.
 */
const MG_BLOCK_EDITOR_POST_TYPES = array( 'post' );

add_filter(
	'use_block_editor_for_post_type',
	function ( $use, $post_type ) {
		return in_array( $post_type, MG_BLOCK_EDITOR_POST_TYPES, true );
	},
	100,
	2
);
add_filter( 'use_widgets_block_editor', '__return_false' );

add_action(
	'init',
	function () {
		// Pages: ACF fields only.
		remove_post_type_support( 'page', 'editor' );
	},
	100
);

add_action(
	'after_setup_theme',
	function () {
		remove_theme_support( 'core-block-patterns' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'align-wide' );
		add_editor_style( array( mg_fonts_url(), 'assets/css/tokens.css' ) );
	}
);

/**
 * The blocks writers can use in an article. Rank Math's table of contents
 * and FAQ blocks are kept because existing articles use them.
 *
 * @return string[]
 */
function mg_allowed_blocks() {
	return array(
		'core/paragraph',
		'core/heading',
		'core/list',
		'core/list-item',
		'core/quote',
		'core/pullquote',
		'core/image',
		'core/gallery',
		'core/embed',
		'core/video',
		'core/table',
		'core/separator',
		'core/buttons',
		'core/button',
		'core/footnotes',
		'rank-math/toc-block',
		'rank-math/faq-block',
	);
}

add_filter(
	'allowed_block_types_all',
	function ( $allowed, $context ) {
		if ( ! empty( $context->post ) && in_array( $context->post->post_type, MG_BLOCK_EDITOR_POST_TYPES, true ) ) {
			return mg_allowed_blocks();
		}
		return $allowed;
	},
	10,
	2
);

// The article title is the only H1, so headings in the body start at H2.
add_filter(
	'register_block_type_args',
	function ( $args, $block_type ) {
		if ( 'core/heading' === $block_type && isset( $args['attributes'] ) ) {
			$args['attributes']['levelOptions']['default'] = array( 2, 3, 4 );
		}
		return $args;
	},
	10,
	2
);

// No remote patterns, block directory or Openverse: a quieter, self-contained editor.
add_filter( 'should_load_remote_block_patterns', '__return_false' );
remove_action( 'enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets' );
add_filter(
	'block_editor_settings_all',
	function ( $settings ) {
		$settings['enableOpenverseMediaCategory'] = false;
		return $settings;
	}
);

/**
 * Whether the current front-end view shows block content.
 *
 * @return bool
 */
function mg_is_block_view() {
	return is_singular( MG_BLOCK_EDITOR_POST_TYPES );
}

// Elsewhere (all the ACF pages and the archives), load no block CSS at all.
add_action(
	'wp',
	function () {
		if ( mg_is_block_view() ) {
			return;
		}
		remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
		remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
		remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
	}
);

add_action(
	'wp_enqueue_scripts',
	function () {
		if ( mg_is_block_view() ) {
			wp_dequeue_style( 'classic-theme-styles' );
			return;
		}
		foreach ( array( 'wp-block-library', 'wp-block-library-theme', 'global-styles', 'classic-theme-styles' ) as $handle ) {
			wp_dequeue_style( $handle );
		}
	},
	100
);
