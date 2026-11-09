<?php
/**
 * Quote section: a single testimonial on mint.
 *
 * $args['data'] is an ACF group with: quote, name, role.
 * $args['note'] optionally adds a note for editors above the quote.
 *
 * @package mackman-group-acf
 */

$mg_data = isset( $args['data'] ) && is_array( $args['data'] ) ? $args['data'] : array();

if ( empty( $mg_data['quote'] ) ) {
	return;
}
?>
<section class="section surface-mint" aria-label="<?php esc_attr_e( 'Testimonial', 'mackman-group-acf' ); ?>">
	<div class="container">
		<?php if ( ! empty( $args['note'] ) ) : ?>
			<p class="editor-note"><?php echo esc_html( $args['note'] ); ?></p>
		<?php endif; ?>
		<figure class="quote quote--feature">
			<blockquote>
				<p><?php echo esc_html( $mg_data['quote'] ); ?></p>
			</blockquote>
			<?php if ( ! empty( $mg_data['name'] ) || ! empty( $mg_data['role'] ) ) : ?>
				<figcaption>
					<?php if ( ! empty( $mg_data['name'] ) ) : ?>
						<cite><?php echo esc_html( $mg_data['name'] ); ?></cite>
					<?php endif; ?>
					<?php if ( ! empty( $mg_data['role'] ) ) : ?>
						<span class="soft"><?php echo esc_html( $mg_data['role'] ); ?></span>
					<?php endif; ?>
				</figcaption>
			<?php endif; ?>
		</figure>
	</div>
</section>
