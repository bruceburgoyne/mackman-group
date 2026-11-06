<?php
/**
 * Call to action band, on green.
 *
 * $args['data'] is an ACF group with: kicker, heading, text, button.
 *
 * @package mackman-group-acf
 */

$mg_data = isset( $args['data'] ) && is_array( $args['data'] ) ? $args['data'] : array();

if ( empty( $mg_data['heading'] ) ) {
	return;
}

$mg_id = 'cta-' . wp_unique_id();
?>
<section class="section surface-green cta-band" aria-labelledby="<?php echo esc_attr( $mg_id ); ?>">
	<div class="container cta">
		<div>
			<?php if ( ! empty( $mg_data['kicker'] ) ) : ?>
				<p class="kicker kicker--ruled"><?php echo esc_html( $mg_data['kicker'] ); ?></p>
			<?php endif; ?>
			<h2 class="cta__title" id="<?php echo esc_attr( $mg_id ); ?>"><?php echo esc_html( $mg_data['heading'] ); ?></h2>
			<?php if ( ! empty( $mg_data['text'] ) ) : ?>
				<p class="cta__text"><?php echo mg_text( $mg_data['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
			<?php endif; ?>
		</div>
		<?php echo mg_link( $mg_data['button'] ?? null, 'button button--gold', true ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
</section>
