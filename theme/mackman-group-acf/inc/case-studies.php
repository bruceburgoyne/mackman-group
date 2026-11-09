<?php
/**
 * Case studies: helpers for single-case-study.php and its parts in
 * template-parts/case-study/.
 *
 * The case-study post type and its Sector and Location taxonomies are
 * registered in the database through ACF (ACF > Post Types), not here.
 * The fields are in acf-json/group_mg_case_study.json.
 *
 * @package mackman-group-acf
 */

defined( 'ABSPATH' ) || exit;

/**
 * The four stages of the Mackman approach, in order.
 *
 * @return array slug => array( number, name )
 */
function mg_case_study_stage_names() {
	return array(
		'understand' => array( '01', __( 'Understand', 'mackman-group-acf' ) ),
		'shape'      => array( '02', __( 'Shape', 'mackman-group-acf' ) ),
		'deliver'    => array( '03', __( 'Deliver', 'mackman-group-acf' ) ),
		'grow'       => array( '04', __( 'Grow', 'mackman-group-acf' ) ),
	);
}

/**
 * A case study's stages, ready to render: empty rows dropped, each with
 * its number, name and an anchor ID for the approach links.
 *
 * @param int|false $post_id Post ID, or false for the current post.
 * @return array[]
 */
function mg_case_study_stages( $post_id = false ) {
	$names  = mg_case_study_stage_names();
	$stages = array();
	$used   = array();

	foreach ( (array) mg_field( 'stages', $post_id ) as $row ) {
		if ( empty( $row['heading'] ) ) {
			continue;
		}
		$slug = ( ! empty( $row['stage'] ) && isset( $names[ $row['stage'] ] ) ) ? $row['stage'] : 'understand';
		$id   = 'stage-' . $slug;
		// Two rows with the same stage still get unique anchors.
		$used[ $id ] = isset( $used[ $id ] ) ? $used[ $id ] + 1 : 1;
		if ( $used[ $id ] > 1 ) {
			$id .= '-' . $used[ $id ];
		}
		$stages[] = array_merge(
			$row,
			array(
				'slug'   => $slug,
				'number' => $names[ $slug ][0],
				'name'   => $names[ $slug ][1],
				'id'     => $id,
			)
		);
	}

	return $stages;
}

/**
 * Whether a case study has been moved into the template's fields yet.
 * Until then it is shown with its original block content.
 *
 * @param int|false $post_id Post ID, or false for the current post.
 * @return bool
 */
function mg_case_study_is_built( $post_id = false ) {
	$challenge = mg_field( 'challenge', $post_id );
	return ! empty( $challenge['heading'] ) || (bool) mg_case_study_stages( $post_id );
}

/**
 * The non-empty lines of a textarea.
 *
 * @param string|null $text Text with one item per line.
 * @return string[]
 */
function mg_lines( $text ) {
	return array_values( array_filter( array_map( 'trim', preg_split( '/\R/', (string) $text ) ), 'strlen' ) );
}

/**
 * Thread: items joined by a gold line with a dot at each, across on wide
 * screens and down on narrow ones. Used for the approach links, a
 * framework's parts and what was delivered.
 *
 * @param array  $items Strings, or arrays with title and optional label and url.
 * @param string $class Extra class for the list.
 * @param string $label Accessible name for the list (optional).
 * @return string
 */
function mg_thread( $items, $class = '', $label = '' ) {
	if ( ! $items ) {
		return '';
	}

	$html = '';
	foreach ( $items as $item ) {
		$item  = is_array( $item ) ? $item : array( 'title' => $item );
		$title = esc_html( $item['title'] );
		if ( ! empty( $item['url'] ) ) {
			$title = sprintf( '<a href="%s">%s</a>', esc_url( $item['url'] ), $title );
		}
		$html .= '<li class="thread__item">';
		if ( ! empty( $item['label'] ) ) {
			$html .= '<span class="thread__label">' . esc_html( $item['label'] ) . '</span>';
		}
		$html .= '<span class="thread__title">' . $title . '</span></li>';
	}

	return sprintf(
		'<ol class="thread%s"%s>%s</ol>',
		$class ? ' ' . esc_attr( $class ) : '',
		$label ? ' aria-label="' . esc_attr( $label ) . '"' : '',
		$html
	);
}

/**
 * The case studies page from Site Settings, if one is set.
 *
 * @return string URL, or an empty string.
 */
function mg_case_studies_url() {
	$url = mg_option( 'case_studies_page' );
	return is_string( $url ) ? $url : '';
}

/**
 * A case study's terms in one taxonomy, as names.
 *
 * @param string    $taxonomy Taxonomy name.
 * @param int|false $post_id  Post ID, or false for the current post.
 * @return string Comma-separated names.
 */
function mg_case_study_terms( $taxonomy, $post_id = false ) {
	$terms = get_the_terms( $post_id ? $post_id : get_the_ID(), $taxonomy );
	if ( ! $terms || is_wp_error( $terms ) ) {
		return '';
	}
	return implode( ', ', wp_list_pluck( $terms, 'name' ) );
}
