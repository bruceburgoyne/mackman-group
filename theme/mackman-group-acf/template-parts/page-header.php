<?php
/**
 * Page header band. Uses the "page_header" group field, falling back to the page title.
 *
 * Optional $args:
 * - post_id: read the fields from this post instead of the current one.
 * - kicker:  small label above the heading.
 * - title:   heading to use instead of the field or page title.
 *
 * @package mackman-group-acf
 */

$mg_post_id = ! empty( $args['post_id'] ) ? (int) $args['post_id'] : false;
$mg_header  = mg_field( 'page_header', $mg_post_id );
$mg_heading = ! empty( $args['title'] ) ? $args['title'] : ( ! empty( $mg_header['heading'] ) ? $mg_header['heading'] : get_the_title( $mg_post_id ? $mg_post_id : null ) );
$mg_kicker  = ! empty( $args['kicker'] ) ? $args['kicker'] : ( ! empty( $mg_header['kicker'] ) ? $mg_header['kicker'] : '' );
?>
<section class="page-header surface-green">
	<div class="container">
		<?php if ( $mg_kicker ) : ?>
			<p class="kicker kicker--ruled"><?php echo esc_html( $mg_kicker ); ?></p>
		<?php endif; ?>
		<h1 class="page-header__title"><?php echo esc_html( $mg_heading ); ?></h1>
		<?php if ( ! empty( $mg_header['intro'] ) ) : ?>
			<p class="lead"><?php echo mg_text( $mg_header['intro'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
		<?php endif; ?>
	</div>
</section>
