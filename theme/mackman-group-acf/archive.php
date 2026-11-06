<?php
/**
 * Topic (category), tag, author and date archives: the same hairline grid
 * as the News page, without the lead article.
 *
 * @package mackman-group-acf
 */

get_header();

$mg_news_id = mg_news_page_id();
get_template_part(
	'template-parts/page-header',
	null,
	array(
		'kicker' => $mg_news_id ? get_the_title( $mg_news_id ) : '',
		'title'  => wp_strip_all_tags( get_the_archive_title() ),
	)
);
get_template_part( 'template-parts/news/topics' );
?>
<section class="section news-archive" aria-label="<?php esc_attr_e( 'Articles', 'mackman-group-acf' ); ?>">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="card-grid"><div class="grid grid--3">
				<?php
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/news/card', null, array( 'heading' => 'h2' ) );
				}
				?>
			</div></div>
			<nav class="pagination" aria-label="<?php esc_attr_e( 'More articles', 'mackman-group-acf' ); ?>">
				<?php
				echo paginate_links( // phpcs:ignore WordPress.Security.EscapeOutput
					array(
						'mid_size'  => 1,
						'prev_text' => __( 'Newer', 'mackman-group-acf' ),
						'next_text' => __( 'Older', 'mackman-group-acf' ),
					)
				);
				?>
			</nav>
		<?php else : ?>
			<p><?php esc_html_e( 'There are no articles on this topic yet.', 'mackman-group-acf' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
if ( $mg_news_id ) {
	get_template_part( 'template-parts/sections/cta', null, array( 'data' => mg_field( 'cta', $mg_news_id ) ) );
}
get_footer();
