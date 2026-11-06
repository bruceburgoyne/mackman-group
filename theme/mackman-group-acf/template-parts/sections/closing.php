<?php
/**
 * Closing section: the call to action at the end of a page. An optional
 * image beside a heading, text and button, on white.
 *
 * $args['data'] is an ACF group with: heading, text, button, image.
 *
 * @package mackman-group-acf
 */

$mg_data = isset( $args['data'] ) && is_array( $args['data'] ) ? $args['data'] : array();

if ( empty( $mg_data['heading'] ) && empty( $mg_data['text'] ) ) {
	return;
}

$mg_image = ! empty( $mg_data['image'] ) ? $mg_data['image'] : null;
$mg_id    = 'closing-' . wp_unique_id();
?>
<section class="section closing surface-white<?php echo $mg_image ? ' closing--has-image' : ''; ?>"<?php echo ! empty( $mg_data['heading'] ) ? ' aria-labelledby="' . esc_attr( $mg_id ) . '"' : ''; ?>>
	<div class="container closing__inner">
		<?php if ( $mg_image ) : ?>
			<figure class="closing__media"><?php echo mg_image( $mg_image, 'large', 'closing__img', array( 'sizes' => '(min-width: 60rem) 40vw, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></figure>
		<?php endif; ?>
		<div class="closing__text">
			<?php if ( ! empty( $mg_data['heading'] ) ) : ?>
				<h2 id="<?php echo esc_attr( $mg_id ); ?>"><?php echo esc_html( $mg_data['heading'] ); ?></h2>
			<?php endif; ?>
			<?php if ( ! empty( $mg_data['text'] ) ) : ?>
				<div class="prose soft"><?php echo wp_kses_post( $mg_data['text'] ); ?></div>
			<?php endif; ?>
			<?php if ( ! empty( $mg_data['button']['url'] ) ) : ?>
				<div class="button-row"><?php echo mg_link( $mg_data['button'], 'button', true ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
			<?php endif; ?>
		</div>
	</div>
</section>
