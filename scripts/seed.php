<?php
/**
 * Push content from the content/ folder into WordPress: page fields from
 * content/pages/*.json, case study fields from content/case-studies/*.json,
 * team member fields from content/team/*.json, and Site Settings from
 * content/site-settings.json.
 *
 * Run with WP-CLI from the site's app/public folder (Local's site shell):
 *
 *   wp eval-file ~/Documents/GitHub/mackman-group/scripts/seed.php
 *   wp eval-file ~/Documents/GitHub/mackman-group/scripts/seed.php mackman-research
 *   wp eval-file ~/Documents/GitHub/mackman-group/scripts/seed.php disaster-care-brand-repositioning
 *   wp eval-file ~/Documents/GitHub/mackman-group/scripts/seed.php site-settings
 *
 * With no argument, every page, every case study and Site Settings are
 * pushed. Otherwise the argument is a file name (without .json) in
 * content/pages, content/case-studies or content/team, or "site-settings".
 *
 * Each JSON file has:
 * - path:      the post's path, for example "our-brands/mackman-research".
 *              The post must already exist: this script never creates posts.
 * - post_type: optional, default "page" (case studies use "case-study",
 *              team members "team_members").
 * - template:  the page template file to assign (pages only).
 * - terms:     optional, taxonomy => list of term names. Missing terms are
 *              created; the post's terms in that taxonomy are replaced.
 * - fields:    ACF values by field name. Images are attachment IDs from the
 *              media library. In any text, "{{page:path}}" becomes that page's
 *              permalink and "{{file:ID}}" the URL of that media library file.
 *
 * Safe to run again. Values in the files overwrite those fields in
 * WordPress, so once editing moves to WP Admin, stop pushing that post.
 * The post's original block content (post_content) is never touched.
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
$mg_dirs     = array( $mg_content . '/pages', $mg_content . '/case-studies', $mg_content . '/team' );
$mg_only     = isset( $args[0] ) ? sanitize_file_name( $args[0] ) : '';
$mg_settings = ( '' === $mg_only || 'site-settings' === $mg_only );
$mg_files    = array();
if ( '' === $mg_only ) {
	foreach ( $mg_dirs as $mg_dir ) {
		$mg_files = array_merge( $mg_files, (array) glob( "{$mg_dir}/*.json" ) );
	}
} elseif ( 'site-settings' !== $mg_only ) {
	foreach ( $mg_dirs as $mg_dir ) {
		if ( is_readable( "{$mg_dir}/{$mg_only}.json" ) ) {
			$mg_files[] = "{$mg_dir}/{$mg_only}.json";
		}
	}
	if ( ! $mg_files ) {
		WP_CLI::error( "No content file called {$mg_only}.json." );
	}
}

/**
 * Replace {{page:path}} tokens with permalinks and {{file:ID}} tokens with
 * media library URLs, recursively.
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
	$value = preg_replace_callback(
		'/\{\{file:(\d+)\}\}/',
		function ( $m ) {
			$url = wp_get_attachment_url( (int) $m[1] );
			if ( ! $url ) {
				WP_CLI::warning( "No media library file {$m[1]}; link left as '#'." );
				return '#';
			}
			return $url;
		},
		$value
	);
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
		WP_CLI::error( "{$mg_file} is not valid content JSON: " . json_last_error_msg() );
	}

	$mg_type = ! empty( $mg_def['post_type'] ) ? $mg_def['post_type'] : 'page';
	$mg_page = get_page_by_path( $mg_def['path'], OBJECT, $mg_type );
	if ( ! $mg_page ) {
		WP_CLI::warning( "No {$mg_type} at '{$mg_def['path']}'. Skipped." );
		continue;
	}

	if ( 'page' === $mg_type && ! empty( $mg_def['template'] ) ) {
		update_post_meta( $mg_page->ID, '_wp_page_template', $mg_def['template'] );
	}

	foreach ( (array) ( $mg_def['terms'] ?? array() ) as $mg_tax => $mg_names ) {
		if ( ! taxonomy_exists( $mg_tax ) ) {
			WP_CLI::warning( "No taxonomy '{$mg_tax}'. Terms skipped." );
			continue;
		}
		$mg_term_ids = array();
		foreach ( (array) $mg_names as $mg_name ) {
			$mg_term = term_exists( $mg_name, $mg_tax );
			if ( ! $mg_term ) {
				$mg_term = wp_insert_term( $mg_name, $mg_tax );
				if ( is_wp_error( $mg_term ) ) {
					WP_CLI::warning( "Could not add '{$mg_name}' to {$mg_tax}: " . $mg_term->get_error_message() );
					continue;
				}
				WP_CLI::log( "Added '{$mg_name}' to {$mg_tax}." );
			}
			$mg_term_ids[] = (int) $mg_term['term_id'];
		}
		wp_set_object_terms( $mg_page->ID, $mg_term_ids, $mg_tax );
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
	WP_CLI::success( "Updated {$mg_def['path']} ({$mg_type} {$mg_page->ID})." );
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
