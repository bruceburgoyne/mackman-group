<?php
/**
 * Text split: label and heading on the left, running text on the right.
 *
 * $args['data'] is an ACF group with: kicker, heading, text.
 *
 * @package mackman-group-acf
 */

$mg_data = isset( $args['data'] ) && is_array( $args['data'] ) ? $args['data'] : array();

if ( empty( $mg_data['heading'] ) || empty( $mg_data['text'] ) ) {
	return;
}

$mg_id = 'text-' . wp_unique_id();
?>
<section class="section text-split" aria-labelledby="<?php echo esc_attr( $mg_id ); ?>">
	<div class="container split">
		<div class="text-split__head">
			<?php if ( ! empty( $mg_data['kicker'] ) ) : ?>
				<p class="kicker"><?php echo esc_html( $mg_data['kicker'] ); ?></p>
			<?php endif; ?>
			<h2 id="<?php echo esc_attr( $mg_id ); ?>"><?php echo esc_html( $mg_data['heading'] ); ?></h2>
		</div>
		<div class="prose text-split__body"><?php echo wp_kses_post( $mg_data['text'] ); ?></div>
	</div>
</section>
