<?php
/**
 * Credentials: a short strip of figures (for example B Corp), each with
 * one line beneath, under a small label. Uses the rule-divided figures
 * (.stats) from case study results.
 *
 * $args['data'] is an ACF group with: heading, items (repeater of
 * figure, label). Hidden when there are no figures.
 *
 * @package mackman-group-acf
 */

$mg_data  = isset( $args['data'] ) && is_array( $args['data'] ) ? $args['data'] : array();
$mg_items = ! empty( $mg_data['items'] ) ? array_filter(
	$mg_data['items'],
	function ( $item ) {
		return ! empty( $item['figure'] );
	}
) : array();

if ( ! $mg_items ) {
	return;
}

$mg_id = 'credentials-' . wp_unique_id();
?>
<section class="section section--tight credentials"<?php echo ! empty( $mg_data['heading'] ) ? ' aria-labelledby="' . esc_attr( $mg_id ) . '"' : ' aria-label="' . esc_attr__( 'Credentials', 'mackman-group-acf' ) . '"'; ?>>
	<div class="container">
		<?php if ( ! empty( $mg_data['heading'] ) ) : ?>
			<h2 class="kicker kicker--ruled" id="<?php echo esc_attr( $mg_id ); ?>"><?php echo esc_html( $mg_data['heading'] ); ?></h2>
		<?php endif; ?>
		<dl class="stats">
			<?php foreach ( $mg_items as $mg_item ) : ?>
				<div class="stat">
					<dt><?php echo esc_html( $mg_item['label'] ?? '' ); ?></dt>
					<dd><?php echo esc_html( $mg_item['figure'] ); ?></dd>
				</div>
			<?php endforeach; ?>
		</dl>
	</div>
</section>
