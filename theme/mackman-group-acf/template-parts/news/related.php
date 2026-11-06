<?php
/**
 * "More news" under an article: up to three, from the same topic first,
 * topped up with the newest articles.
 *
 * Optional $args['topic']: the current article's topic (WP_Term).
 *
 * @package mackman-group-acf
 */

$mg_current = get_the_ID();
$mg_ids     = array();

if ( ! empty( $args['topic'] ) ) {
	$mg_ids = get_posts(
		array(
			'fields'         => 'ids',
			'posts_per_page' => 3,
			'cat'            => $args['topic']->term_id,
			'post__not_in'   => array( $mg_current ),
		)
	);
}
if ( count( $mg_ids ) < 3 ) {
	$mg_ids = array_merge(
		$mg_ids,
		get_posts(
			array(
				'fields'         => 'ids',
				'posts_per_page' => 3 - count( $mg_ids ),
				'post__not_in'   => array_merge( array( $mg_current ), $mg_ids ),
			)
		)
	);
}
if ( ! $mg_ids ) {
	return;
}

$mg_related = new WP_Query(
	array(
		'post__in'            => $mg_ids,
		'orderby'             => 'post__in',
		'posts_per_page'      => count( $mg_ids ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);
$mg_news_id = mg_news_page_id();
?>
<section class="section related" aria-labelledby="related-title">
	<div class="container">
		<div class="section-head section-head--row">
			<h2 id="related-title"><?php esc_html_e( 'More news', 'mackman-group-acf' ); ?></h2>
			<?php if ( $mg_news_id ) : ?>
				<a class="text-link" href="<?php echo esc_url( get_permalink( $mg_news_id ) ); ?>"><?php esc_html_e( 'All news', 'mackman-group-acf' ); ?> <span class="arrow" aria-hidden="true">&rarr;</span></a>
			<?php endif; ?>
		</div>
		<div class="card-grid"><div class="grid grid--3">
			<?php
			while ( $mg_related->have_posts() ) {
				$mg_related->the_post();
				get_template_part( 'template-parts/news/card', null, array( 'excerpt' => false ) );
			}
			wp_reset_postdata();
			?>
		</div></div>
	</div>
</section>
