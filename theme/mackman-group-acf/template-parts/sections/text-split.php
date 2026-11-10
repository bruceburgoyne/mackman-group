<?php
/**
 * Text split: label and heading on the left, running text on the right.
 *
 * $args['data'] is an ACF group with: kicker, heading, text, and
 * optionally button and surface (surface-white or surface-mint).
 *
 * @package mackman-group-acf
 */

$mg_data = isset( $args['data'] ) && is_array( $args['data'] ) ? $args['data'] : array();

if ( empty( $mg_data['heading'] ) || empty( $mg_data['text'] ) ) {
	return;
}

$mg_surface = ! empty( $mg_data['surface'] ) ? ' ' . sanitize_html_class( $mg_data['surface'] ) : '';
$mg_id      = 'text-' . wp_unique_id();
?>
<section class="section text-split<?php echo esc_attr( $mg_surface ); ?>" aria-labelledby="<?php echo esc_attr( $mg_id ); ?>">
	<div class="container split">
		<div class="text-split__head">
			<?php if ( ! empty( $mg_data['kicker'] ) ) : ?>
				<p class="kicker"><?php echo esc_html( $mg_data['kicker'] ); ?></p>
			<?php endif; ?>
			<h2 id="<?php echo esc_attr( $mg_id ); ?>"><?php echo esc_html( $mg_data['heading'] ); ?></h2>
		</div>
		<div class="text-split__body">
			<div class="prose"><?php echo wp_kses_post( $mg_data['text'] ); ?></div>
			<?php if ( ! empty( $mg_data['button']['url'] ) ) : ?>
				<div class="button-row"><?php echo mg_link( $mg_data['button'], 'button', true ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
			<?php endif; ?>
		</div>
	</div>
</section>
