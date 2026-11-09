<?php
/**
 * Case study results: two to four evidenced figures, each with its source,
 * as rule-divided columns. Hidden when there are no figures.
 *
 * @package mackman-group-acf
 */

$mg_data  = (array) mg_field( 'results' );
$mg_items = ! empty( $mg_data['items'] ) ? array_filter(
	$mg_data['items'],
	function ( $item ) {
		return ! empty( $item['figure'] );
	}
) : array();

if ( ! $mg_items ) {
	return;
}
?>
<section class="section cs-results" aria-labelledby="cs-results-title">
	<div class="container">
		<h2 class="kicker" id="cs-results-title"><?php echo esc_html( ! empty( $mg_data['kicker'] ) ? $mg_data['kicker'] : __( 'Results', 'mackman-group-acf' ) ); ?></h2>
		<dl class="stats">
			<?php foreach ( $mg_items as $mg_item ) : ?>
				<div class="stat">
					<dt><?php echo esc_html( $mg_item['label'] ?? '' ); ?></dt>
					<dd><?php echo esc_html( $mg_item['figure'] ); ?></dd>
					<?php if ( ! empty( $mg_item['source'] ) ) : ?>
						<dd class="stat__source">
							<?php
							/* translators: %s: where a figure comes from */
							echo esc_html( sprintf( __( 'Source: %s', 'mackman-group-acf' ), $mg_item['source'] ) );
							?>
						</dd>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</dl>
	</div>
</section>
