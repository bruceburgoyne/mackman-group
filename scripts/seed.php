<?php
/**
 * Push content from the content/ folder into WordPress: page fields from
 * content/pages/*.json, and Site Settings from content/site-settings.json.
 *
 * Run with WP-CLI from the site's app/public folder (Local's site shell):
 *
 *   wp eval-file ~/Documents/GitHub/mackman-group/scripts/seed.php
 *   wp eval-file ~/Documents/GitHub/mackman-group/scripts/seed.php mackman-research
 *   wp eval-file ~/Documents/GitHub/mackman-group/scripts/seed.php site-settings
 *
 * With no argument, every page and Site Settings are pushed. Otherwise the
 * argument is a file name in content/pages (without .json) to push one page,
 * or "site-settings" to push Site Settings only.
 *
 * Each JSON file has:
 * - path:     the page's path, for example "our-brands/mackman-research".
 *             The page must already exist: this script never creates pages.
 * - template: the page template file to assign.
 * - fields:   ACF values by field name. Images are attachment IDs from the
 *             media library. In link URLs, "{{page:path}}" becomes that page's
 *             permalink.
 *
 * Safe to run again. Values in the files overwrite those fields in
 * WordPress, so once editing moves to WP Admin, stop pushing that page.
 * The page's original block content (post_content) is never touched.
 *
 * @package mackman-group-acf
 */

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	exit;
}

if ( ! function_exists( 'acf_get_field_groups' ) ) {
	WP_CLI::error( 'ACF Pro is not active.' );
}

$mg_content  = dirname( __DIR__ ) . '/content';
$mg_dir      = $mg_content . '/pages';
$mg_only     = isset( $args[0] ) ? sanitize_file_name( $args[0] ) : '';
$mg_settings = ( '' === $mg_only || 'site-settings' === $mg_only );
if ( 'site-settings' === $mg_only ) {
	$mg_files = array();
} else {
	$mg_files = $mg_only ? array( "{$mg_dir}/{$mg_only}.json" ) : glob( "{$mg_dir}/*.json" );
}

/**
 * Replace {{page:path}} tokens with permalinks, recursively.
 *
 * @param mixed $value Field value.
 * @return mixed
 */
function mg_seed_resolve( $value ) {
	if ( is_array( $value ) ) {
		return array_map( 'mg_seed_resolve', $value );
	}
	if ( ! is_string( $value ) ) {
		return $value;
	}
	return preg_replace_callback(
		'/\{\{page:([a-z0-9\-\/]+)\}\}/',
		function ( $m ) {
			$page = get_page_by_path( $m[1] );
			if ( ! $page ) {
				WP_CLI::warning( "Link to unknown page '{$m[1]}' left as '#'." );
				return '#';
			}
			return get_permalink( $page );
		},
		$value
	);
}

/**
 * Top-level field names mapped to keys, for the groups shown on a post.
 * Saving by key makes ACF store the reference meta that groups and
 * repeaters need.
 *
 * @param int $post_id Post ID.
 * @return array name => key
 */
function mg_seed_field_map( $post_id ) {
	$map = array();
	foreach ( acf_get_field_groups( array( 'post_id' => $post_id ) ) as $group ) {
		foreach ( acf_get_fields( $group ) as $field ) {
			if ( '' !== $field['name'] ) {
				$map[ $field['name'] ] = $field['key'];
			}
		}
	}
	return $map;
}

foreach ( $mg_files as $mg_file ) {
	if ( ! is_readable( $mg_file ) ) {
		WP_CLI::error( "Cannot read {$mg_file}." );
	}

	$mg_def = json_decode( file_get_contents( $mg_file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	if ( ! is_array( $mg_def ) || empty( $mg_def['path'] ) ) {
		WP_CLI::error( "{$mg_file} is not valid page JSON: " . json_last_error_msg() );
	}

	$mg_page = get_page_by_path( $mg_def['path'] );
	if ( ! $mg_page ) {
		WP_CLI::warning( "No page at '{$mg_def['path']}'. Skipped." );
		continue;
	}

	if ( ! empty( $mg_def['template'] ) ) {
		update_post_meta( $mg_page->ID, '_wp_page_template', $mg_def['template'] );
	}

	$mg_map = mg_seed_field_map( $mg_page->ID );
	foreach ( (array) ( $mg_def['fields'] ?? array() ) as $mg_name => $mg_value ) {
		if ( empty( $mg_map[ $mg_name ] ) ) {
			WP_CLI::warning( "'{$mg_name}' is not a field on '{$mg_def['path']}'. Skipped." );
			continue;
		}
		update_field( $mg_map[ $mg_name ], mg_seed_resolve( $mg_value ), $mg_page->ID );
	}

	clean_post_cache( $mg_page->ID );
	WP_CLI::success( "Updated {$mg_def['path']} (page {$mg_page->ID})." );
}

// Site Settings (the ACF options page).
if ( $mg_settings && is_readable( $mg_content . '/site-settings.json' ) ) {
	$mg_def = json_decode( file_get_contents( $mg_content . '/site-settings.json' ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	if ( ! is_array( $mg_def ) ) {
		WP_CLI::error( 'content/site-settings.json is not valid JSON: ' . json_last_error_msg() );
	}

	$mg_map = array();
	foreach ( acf_get_field_groups( array( 'options_page' => 'site-settings' ) ) as $mg_group ) {
		foreach ( acf_get_fields( $mg_group ) as $mg_field ) {
			if ( '' !== $mg_field['name'] ) {
				$mg_map[ $mg_field['name'] ] = $mg_field['key'];
			}
		}
	}

	foreach ( (array) ( $mg_def['fields'] ?? array() ) as $mg_name => $mg_value ) {
		if ( empty( $mg_map[ $mg_name ] ) ) {
			WP_CLI::warning( "'{$mg_name}' is not a Site Settings field. Skipped." );
			continue;
		}
		update_field( $mg_map[ $mg_name ], mg_seed_resolve( $mg_value ), 'option' );
	}
	WP_CLI::success( 'Updated Site Settings.' );
}
