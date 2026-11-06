<?php
/**
 * Standards: who we work with and the standards we work to, on green.
 * Image and text, then the standards as rule-divided columns.
 *
 * $args['data'] is an ACF group with: kicker, heading, text, image,
 * items (repeater of name, detail).
 *
 * @package mackman-group-acf
 */

$mg_data = isset( $args['data'] ) && is_array( $args['data'] ) ? $args['data'] : array();

if ( empty( $mg_data['heading'] ) ) {
	return;
}

$mg_image = ! empty( $mg_data['image'] ) ? $mg_data['image'] : null;
$mg_items = ! empty( $mg_data['items'] ) ? array_filter(
	$mg_data['items'],
	function ( $item ) {
		return ! empty( $item['name'] );
	}
) : array();
$mg_id    = 'standards-' . wp_unique_id();
?>
<section class="section standards surface-green" aria-labelledby="<?php echo esc_attr( $mg_id ); ?>">
	<div class="container">
		<div class="standards__top<?php echo $mg_image ? ' standards__top--has-image' : ''; ?>">
			<?php if ( $mg_image ) : ?>
				<figure class="standards__media"><?php echo mg_image( $mg_image, 'large', 'standards__img', array( 'sizes' => '(min-width: 60rem) 40vw, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></figure>
			<?php endif; ?>
			<div>
				<?php if ( ! empty( $mg_data['kicker'] ) ) : ?>
					<p class="kicker kicker--ruled"><?php echo esc_html( $mg_data['kicker'] ); ?></p>
				<?php endif; ?>
				<h2 id="<?php echo esc_attr( $mg_id ); ?>"><?php echo esc_html( $mg_data['heading'] ); ?></h2>
				<?php if ( ! empty( $mg_data['text'] ) ) : ?>
					<div class="prose soft"><?php echo wp_kses_post( $mg_data['text'] ); ?></div>
				<?php endif; ?>
			</div>
		</div>

		<?php if ( $mg_items ) : ?>
			<ul class="standards__list">
				<?php foreach ( $mg_items as $mg_item ) : ?>
					<li class="standards__item">
						<span class="standards__name"><?php echo esc_html( $mg_item['name'] ); ?></span>
						<?php if ( ! empty( $mg_item['detail'] ) ) : ?>
							<span class="standards__detail"><?php echo esc_html( $mg_item['detail'] ); ?></span>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>
