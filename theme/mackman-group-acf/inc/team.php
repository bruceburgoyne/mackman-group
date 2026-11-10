<?php
/**
 * Team members: the team_members post type is registered by ACF
 * (ACF > Post Types) from acf-json/post_type_659574f4b00ea.json. Profile
 * fields are in acf-json/group_mg_team_member.json.
 *
 * Post titles on the live site read "Name | Role", so the name and role
 * fall back to the two halves of the title until their fields are filled.
 *
 * @package mackman-group-acf
 */

defined( 'ABSPATH' ) || exit;

/**
 * A team member's name and role.
 *
 * @param int|false $post_id Team member, or false for the current post.
 * @return array{name: string, role: string}
 */
function mg_team_member( $post_id = false ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$title   = html_entity_decode( get_the_title( $post_id ), ENT_QUOTES, 'UTF-8' );
	$parts   = array_map( 'trim', explode( '|', $title, 2 ) );
	$name    = mg_field( 'name', $post_id );
	$role    = mg_field( 'role', $post_id );

	return array(
		'name' => $name ? $name : $parts[0],
		'role' => $role ? $role : ( $parts[1] ?? '' ),
	);
}

/**
 * Published team members, in menu order.
 *
 * @return WP_Post[]
 */
function mg_team_members() {
	return get_posts(
		array(
			'post_type'      => 'team_members',
			'post_status'    => 'publish',
			'posts_per_page' => 50,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
			'no_found_rows'  => true,
		)
	);
}

/**
 * The page that lists the team (Our business consultants), for the
 * profile breadcrumb.
 *
 * @return int Page ID, or 0.
 */
function mg_team_page_id() {
	$page = get_page_by_path( 'senior-business-consultants' );
	return $page ? (int) $page->ID : 0;
}

