<?php
/**
 * Case study outcome: what changed for the client. Heading and lead, what
 * was delivered as linked items, then the text. Hidden when there is no
 * heading.
 *
 * @package mackman-group-acf
 */

$mg_data = (array) mg_field( 'outcome' );

if ( empty( $mg_data['heading'] ) ) {
	return;
}

$mg_items = mg_lines( $mg_data['items'] ?? '' );
?>
<section class="section cs-outcome surface-white" aria-labelledby="cs-outcome-title">
	<div class="container">
		<div class="section-head">
			<p class="kicker"><?php echo esc_html( ! empty( $mg_data['kicker'] ) ? $mg_data['kicker'] : __( 'The outcome', 'mackman-group-acf' ) ); ?></p>
			<h2 id="cs-outcome-title"><?php echo esc_html( $mg_data['heading'] ); ?></h2>
			<?php if ( ! empty( $mg_data['lead'] ) ) : ?>
				<p class="lead"><?php echo esc_html( $mg_data['lead'] ); ?></p>
			<?php endif; ?>
		</div>
		<?php if ( $mg_items ) : ?>
			<div class="cs-outcome__items"><?php echo mg_thread( $mg_items, '', __( 'What was delivered', 'mackman-group-acf' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
		<?php endif; ?>
		<?php if ( ! empty( $mg_data['text'] ) ) : ?>
			<div class="prose cs-outcome__text"><?php echo wp_kses_post( $mg_data['text'] ); ?></div>
		<?php endif; ?>
	</div>
</section>
