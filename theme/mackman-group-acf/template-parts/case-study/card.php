<?php
/**
 * A case study card, for use inside a .card-grid.
 *
 * Image: the featured image, else the opening image. Label: the type of
 * work. Text: the lead line, else a written excerpt.
 *
 * $args['post_id'] is the case study to show.
 *
 * @package mackman-group-acf
 */

$mg_id = ! empty( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();
if ( ! $mg_id ) {
	return;
}

$mg_opening = (array) mg_field( 'opening', $mg_id );
// Only a written excerpt: one generated from old block content reads badly.
$mg_text    = ! empty( $mg_opening['lead'] ) ? $mg_opening['lead'] : ( has_excerpt( $mg_id ) ? get_the_excerpt( $mg_id ) : '' );
$mg_media   = '';
$mg_attr    = array(
	'alt'   => '',
	'sizes' => '(min-width: 64rem) 25vw, (min-width: 48rem) 33vw, 100vw',
);

if ( has_post_thumbnail( $mg_id ) ) {
	$mg_media = get_the_post_thumbnail( $mg_id, 'medium_large', $mg_attr );
} elseif ( ! empty( $mg_opening['image'] ) ) {
	$mg_media = mg_image( $mg_opening['image'], 'medium_large', '', $mg_attr );
}
?>
<article class="card cs-card">
	<?php if ( $mg_media ) : ?>
		<div class="card__media"><?php echo $mg_media; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
	<?php endif; ?>
	<p class="kicker"><?php echo esc_html( ! empty( $mg_opening['kicker'] ) ? $mg_opening['kicker'] : __( 'Case study', 'mackman-group-acf' ) ); ?></p>
	<h3 class="card__title"><a href="<?php echo esc_url( get_permalink( $mg_id ) ); ?>"><?php echo esc_html( get_the_title( $mg_id ) ); ?></a></h3>
	<?php if ( $mg_text ) : ?>
		<p class="card__text"><?php echo esc_html( wp_trim_words( $mg_text, 22 ) ); ?></p>
	<?php endif; ?>
	<span class="card__more" aria-hidden="true"><?php esc_html_e( 'Read case study', 'mackman-group-acf' ); ?><span class="arrow">&rarr;</span></span>
</article>
