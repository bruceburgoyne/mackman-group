<?php
/**
 * An article card, for use inside a .card-grid. Call within the loop.
 *
 * Optional $args:
 * - heading: heading level for the title (default h3).
 * - excerpt: show the standfirst (default true).
 *
 * @package mackman-group-acf
 */

$mg_level   = ! empty( $args['heading'] ) ? preg_replace( '/[^h1-6]/', '', $args['heading'] ) : 'h3';
$mg_excerpt = ! isset( $args['excerpt'] ) || $args['excerpt'];
$mg_topic   = mg_topic();
?>
<article class="card news-card">
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="card__media">
			<?php
			the_post_thumbnail(
				'medium_large',
				array(
					'alt'   => '',
					'sizes' => '(min-width: 64rem) 25vw, (min-width: 48rem) 33vw, 100vw',
				)
			);
			?>
		</div>
	<?php endif; ?>
	<p class="kicker news-meta">
		<?php if ( $mg_topic ) : ?>
			<span><?php echo esc_html( $mg_topic->name ); ?></span>
		<?php endif; ?>
		<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
	</p>
	<<?php echo esc_html( $mg_level ); ?> class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></<?php echo esc_html( $mg_level ); ?>>
	<?php if ( $mg_excerpt && has_excerpt() ) : ?>
		<p class="card__text"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
	<?php endif; ?>
	<span class="card__more" aria-hidden="true"><?php esc_html_e( 'Read article', 'mackman-group-acf' ); ?><span class="arrow">&rarr;</span></span>
</article>
