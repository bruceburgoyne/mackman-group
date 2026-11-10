<?php
/**
 * Method: label, heading and text on the left, numbered steps on the
 * right, using the gold thread from the case study template.
 *
 * $args['data'] is an ACF group with: kicker, heading, text, steps
 * (repeater of name, text). Hidden when there are no steps.
 *
 * @package mackman-group-acf
 */

$mg_data  = isset( $args['data'] ) && is_array( $args['data'] ) ? $args['data'] : array();
$mg_steps = ! empty( $mg_data['steps'] ) ? array_values(
	array_filter(
		$mg_data['steps'],
		function ( $step ) {
			return ! empty( $step['name'] );
		}
	)
) : array();

if ( ! $mg_steps || empty( $mg_data['heading'] ) ) {
	return;
}

$mg_items = array();
foreach ( $mg_steps as $mg_i => $mg_step ) {
	$mg_items[] = array(
		'label' => sprintf( '%02d %s', $mg_i + 1, $mg_step['name'] ),
		'title' => ! empty( $mg_step['text'] ) ? $mg_step['text'] : $mg_step['name'],
	);
}

$mg_id = 'method-' . wp_unique_id();
?>
<section class="section text-split method surface-mint" aria-labelledby="<?php echo esc_attr( $mg_id ); ?>">
	<div class="container split">
		<div class="text-split__head">
			<?php if ( ! empty( $mg_data['kicker'] ) ) : ?>
				<p class="kicker"><?php echo esc_html( $mg_data['kicker'] ); ?></p>
			<?php endif; ?>
			<h2 id="<?php echo esc_attr( $mg_id ); ?>"><?php echo esc_html( $mg_data['heading'] ); ?></h2>
			<?php if ( ! empty( $mg_data['text'] ) ) : ?>
				<p class="soft"><?php echo mg_text( $mg_data['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
			<?php endif; ?>
		</div>
		<div class="text-split__body">
			<?php echo mg_thread( $mg_items, 'thread--links' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
</section>
