<?php
/**
 * Template helpers. Each one fails quietly if ACF is not active or a field is empty.
 *
 * @package mackman-group-acf
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get an ACF field value, or null when ACF is not available.
 *
 * @param string          $name    Field name.
 * @param int|string|bool $post_id Post ID, 'option', or false for the current post.
 * @return mixed
 */
function mg_field( $name, $post_id = false ) {
	return function_exists( 'get_field' ) ? get_field( $name, $post_id ) : null;
}

/**
 * Get a value from the Site Settings options page.
 *
 * @param string $name Field name.
 * @return mixed
 */
function mg_option( $name ) {
	return mg_field( $name, 'option' );
}

/**
 * Render an ACF link field (return format: array).
 *
 * @param array|null $link  Link array with url, title and target.
 * @param string     $class CSS class.
 * @param bool       $arrow Add an arrow after the text.
 * @return string
 */
function mg_link( $link, $class = 'button', $arrow = false ) {
	if ( empty( $link['url'] ) ) {
		return '';
	}
	$target = ! empty( $link['target'] ) ? $link['target'] : '_self';
	$rel    = '_blank' === $target ? ' rel="noopener"' : '';
	$title  = ! empty( $link['title'] ) ? $link['title'] : $link['url'];
	$after  = $arrow ? ' <span class="arrow" aria-hidden="true">&rarr;</span>' : '';

	return sprintf(
		'<a class="%s" href="%s" target="%s"%s>%s%s</a>',
		esc_attr( $class ),
		esc_url( $link['url'] ),
		esc_attr( $target ),
		$rel,
		esc_html( $title ),
		$after
	);
}

/**
 * Render an ACF image field (return format: array).
 *
 * @param array|null $image Image array.
 * @param string     $size  Registered image size.
 * @param string     $class CSS class.
 * @param array      $attr  Extra attributes.
 * @return string
 */
function mg_image( $image, $size = 'large', $class = '', $attr = array() ) {
	if ( empty( $image['ID'] ) ) {
		return '';
	}
	return wp_get_attachment_image( $image['ID'], $size, false, array_merge( array( 'class' => $class ), $attr ) );
}

/**
 * Plain text with line breaks preserved.
 *
 * @param string|null $text Text from a textarea field.
 * @return string
 */
function mg_text( $text ) {
	return $text ? nl2br( esc_html( $text ) ) : '';
}

/**
 * URL of a file in the theme's assets folder.
 *
 * @param string $path Path inside assets/.
 * @return string
 */
function mg_asset( $path ) {
	return get_template_directory_uri() . '/assets/' . ltrim( $path, '/' );
}
