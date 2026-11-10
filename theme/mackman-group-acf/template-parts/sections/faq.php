<?php
/**
 * FAQs: label and heading on the left, questions on the right. Each
 * question is a <details> element, so it opens and closes without
 * JavaScript and works with the keyboard and screen readers.
 *
 * $args['data'] is an ACF group with: kicker, heading, items (repeater
 * of question, answer). Hidden when there are no answered questions.
 *
 * @package mackman-group-acf
 */

$mg_data  = isset( $args['data'] ) && is_array( $args['data'] ) ? $args['data'] : array();
$mg_items = ! empty( $mg_data['items'] ) ? array_filter(
	$mg_data['items'],
	function ( $item ) {
		return ! empty( $item['question'] ) && ! empty( $item['answer'] );
	}
) : array();

if ( ! $mg_items ) {
	return;
}

$mg_heading = ! empty( $mg_data['heading'] ) ? $mg_data['heading'] : __( 'Frequently asked questions', 'mackman-group-acf' );
$mg_id      = 'faq-' . wp_unique_id();
?>
<section class="section faq surface-white" aria-labelledby="<?php echo esc_attr( $mg_id ); ?>">
	<div class="container split">
		<div class="text-split__head">
			<?php if ( ! empty( $mg_data['kicker'] ) ) : ?>
				<p class="kicker"><?php echo esc_html( $mg_data['kicker'] ); ?></p>
			<?php endif; ?>
			<h2 id="<?php echo esc_attr( $mg_id ); ?>"><?php echo esc_html( $mg_heading ); ?></h2>
		</div>
		<div class="faq__list">
			<?php foreach ( $mg_items as $mg_item ) : ?>
				<details class="faq__item">
					<summary class="faq__question"><?php echo esc_html( $mg_item['question'] ); ?></summary>
					<div class="faq__answer prose">
						<?php foreach ( mg_paragraphs( $mg_item['answer'] ) as $mg_para ) : ?>
							<p><?php echo mg_text( $mg_para ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
						<?php endforeach; ?>
					</div>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
