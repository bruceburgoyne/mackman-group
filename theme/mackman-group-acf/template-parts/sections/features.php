<?php
/**
 * Features section: a section heading and a grid of cards. Used for a
 * division's services, the divisions on the home page and accreditations.
 *
 * $args['data'] is an ACF group with: kicker, heading, intro, items
 * (repeater of icon, title, text, link).
 * $args['surface'] optionally sets the section surface (default: none).
 *
 * @package mackman-group-acf
 */

$mg_data  = isset( $args['data'] ) && is_array( $args['data'] ) ? $args['data'] : array();
$mg_items = ! empty( $mg_data['items'] ) ? array_filter(
	$mg_data['items'],
	function ( $item ) {
		return ! empty( $item['title'] );
	}
) : array();

if ( ! $mg_items ) {
	return;
}

$mg_surface = ! empty( $args['surface'] ) ? ' ' . sanitize_html_class( $args['surface'] ) : '';
$mg_grid    = ' grid--' . ( ! empty( $args['columns'] ) ? (int) $args['columns'] : 3 );
$mg_id      = 'features-' . wp_unique_id();
?>
<section class="section features<?php echo esc_attr( $mg_surface ); ?>"<?php echo ! empty( $mg_data['heading'] ) ? ' aria-labelledby="' . esc_attr( $mg_id ) . '"' : ''; ?>>
	<div class="container">
		<?php if ( ! empty( $mg_data['heading'] ) ) : ?>
			<div class="section-head">
				<?php if ( ! empty( $mg_data['kicker'] ) ) : ?>
					<p class="kicker"><?php echo esc_html( $mg_data['kicker'] ); ?></p>
				<?php endif; ?>
				<h2 id="<?php echo esc_attr( $mg_id ); ?>"><?php echo esc_html( $mg_data['heading'] ); ?></h2>
				<?php if ( ! empty( $mg_data['intro'] ) ) : ?>
					<p class="lead"><?php echo mg_text( $mg_data['intro'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="card-grid"><div class="grid<?php echo esc_attr( $mg_grid ); ?>">
			<?php foreach ( $mg_items as $mg_item ) : ?>
				<?php $mg_has_link = ! empty( $mg_item['link']['url'] ); ?>
				<article class="card<?php echo $mg_has_link ? '' : ' card--static'; ?>">
					<?php if ( ! empty( $mg_item['icon'] ) ) : ?>
						<div class="card__icon"><?php echo mg_image( $mg_item['icon'], 'thumbnail', '', array( 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
					<?php endif; ?>
					<h3 class="card__title">
						<?php if ( $mg_has_link ) : ?>
							<a href="<?php echo esc_url( $mg_item['link']['url'] ); ?>"<?php echo ! empty( $mg_item['link']['target'] ) ? ' target="' . esc_attr( $mg_item['link']['target'] ) . '"' : ''; ?>><?php echo esc_html( $mg_item['title'] ); ?></a>
						<?php else : ?>
							<?php echo esc_html( $mg_item['title'] ); ?>
						<?php endif; ?>
					</h3>
					<?php foreach ( mg_paragraphs( $mg_item['text'] ?? '' ) as $mg_para ) : ?>
						<p class="card__text"><?php echo mg_text( $mg_para ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
					<?php endforeach; ?>
					<?php if ( $mg_has_link ) : ?>
						<span class="card__more" aria-hidden="true"><?php echo esc_html( ! empty( $mg_item['link']['title'] ) ? $mg_item['link']['title'] : __( 'Find out more', 'mackman-group-acf' ) ); ?><span class="arrow">&rarr;</span></span>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div></div>
	</div>
</section>
