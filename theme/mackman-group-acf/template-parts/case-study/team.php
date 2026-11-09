<?php
/**
 * Who was involved: which parts of the Group worked on the project and
 * what each did. Heading and text beside an optional photo, then a card
 * for each, linking to its page. Hidden when there are no cards.
 *
 * @package mackman-group-acf
 */

$mg_data  = (array) mg_field( 'team' );
$mg_items = ! empty( $mg_data['items'] ) ? array_filter(
	$mg_data['items'],
	function ( $item ) {
		return ! empty( $item['name'] );
	}
) : array();

if ( ! $mg_items ) {
	return;
}

$mg_image = ! empty( $mg_data['image'] ) ? $mg_data['image'] : null;
$mg_cols  = min( max( count( $mg_items ), 2 ), 3 );
?>
<section class="section cs-team" aria-labelledby="cs-team-title">
	<div class="container">
		<div class="cs-team__top<?php echo $mg_image ? ' cs-team__top--has-image' : ''; ?>">
			<div class="section-head">
				<p class="kicker"><?php echo esc_html( ! empty( $mg_data['kicker'] ) ? $mg_data['kicker'] : __( 'Who was involved', 'mackman-group-acf' ) ); ?></p>
				<h2 id="cs-team-title"><?php echo esc_html( ! empty( $mg_data['heading'] ) ? $mg_data['heading'] : __( 'Who was involved', 'mackman-group-acf' ) ); ?></h2>
				<?php if ( ! empty( $mg_data['text'] ) ) : ?>
					<div class="prose soft"><?php echo wp_kses_post( $mg_data['text'] ); ?></div>
				<?php endif; ?>
			</div>
			<?php if ( $mg_image ) : ?>
				<figure class="cs-team__media"><?php echo mg_image( $mg_image, 'large', 'cs-team__img', array( 'sizes' => '(min-width: 60rem) 40vw, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></figure>
			<?php endif; ?>
		</div>

		<div class="card-grid"><div class="grid grid--<?php echo (int) $mg_cols; ?>">
			<?php foreach ( $mg_items as $mg_item ) : ?>
				<?php $mg_has_link = ! empty( $mg_item['link']['url'] ); ?>
				<article class="card<?php echo $mg_has_link ? '' : ' card--static'; ?>">
					<?php if ( ! empty( $mg_item['role'] ) ) : ?>
						<p class="kicker"><?php echo esc_html( $mg_item['role'] ); ?></p>
					<?php endif; ?>
					<h3 class="card__title">
						<?php if ( $mg_has_link ) : ?>
							<a href="<?php echo esc_url( $mg_item['link']['url'] ); ?>"<?php echo ! empty( $mg_item['link']['target'] ) ? ' target="' . esc_attr( $mg_item['link']['target'] ) . '"' : ''; ?>><?php echo esc_html( $mg_item['name'] ); ?></a>
						<?php else : ?>
							<?php echo esc_html( $mg_item['name'] ); ?>
						<?php endif; ?>
					</h3>
					<?php if ( ! empty( $mg_item['text'] ) ) : ?>
						<p class="card__text"><?php echo mg_text( $mg_item['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
					<?php endif; ?>
					<?php if ( $mg_has_link ) : ?>
						<span class="card__more" aria-hidden="true"><?php echo esc_html( ! empty( $mg_item['link']['title'] ) ? $mg_item['link']['title'] : __( 'Find out more', 'mackman-group-acf' ) ); ?><span class="arrow">&rarr;</span></span>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div></div>
	</div>
</section>
