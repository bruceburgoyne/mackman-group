<?php
/**
 * Latest news: the newest articles as cards, with a link to all news.
 *
 * $args['data'] is an ACF group with: heading, count (default 3).
 * Hidden when the heading is empty or there are no published articles.
 *
 * @package mackman-group-acf
 */

$mg_data = isset( $args['data'] ) && is_array( $args['data'] ) ? $args['data'] : array();

if ( empty( $mg_data['heading'] ) ) {
	return;
}

$mg_query = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => ! empty( $mg_data['count'] ) ? (int) $mg_data['count'] : 3,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

if ( ! $mg_query->have_posts() ) {
	return;
}

$mg_news_page = (int) get_option( 'page_for_posts' );
$mg_id        = 'news-' . wp_unique_id();
?>
<section class="section news" aria-labelledby="<?php echo esc_attr( $mg_id ); ?>">
	<div class="container">
		<div class="section-head section-head--row">
			<h2 id="<?php echo esc_attr( $mg_id ); ?>"><?php echo esc_html( $mg_data['heading'] ); ?></h2>
			<?php if ( $mg_news_page ) : ?>
				<a class="text-link" href="<?php echo esc_url( get_permalink( $mg_news_page ) ); ?>"><?php esc_html_e( 'All news', 'mackman-group-acf' ); ?> <span class="arrow" aria-hidden="true">&rarr;</span></a>
			<?php endif; ?>
		</div>
		<div class="grid grid--3">
			<?php
			while ( $mg_query->have_posts() ) :
				$mg_query->the_post();
				?>
				<article class="card">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="card__media"><?php the_post_thumbnail( 'medium_large', array( 'alt' => '', 'sizes' => '(min-width: 64rem) 25vw, (min-width: 40rem) 50vw, 100vw' ) ); ?></div>
					<?php endif; ?>
					<p class="kicker"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
					<h3 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<span class="card__more" aria-hidden="true"><?php esc_html_e( 'Read more', 'mackman-group-acf' ); ?><span class="arrow">&rarr;</span></span>
				</article>
				<?php
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
