<?php
/**
 * Fallback template: the News page, archives and search until their own
 * templates are built.
 *
 * @package mackman-group-acf
 */

get_header();

if ( is_home() && get_option( 'page_for_posts' ) ) {
	$mg_title = get_the_title( (int) get_option( 'page_for_posts' ) );
} elseif ( is_search() ) {
	/* translators: %s: search term */
	$mg_title = sprintf( __( 'Search results for "%s"', 'mackman-group-acf' ), get_search_query() );
} else {
	$mg_title = get_the_archive_title();
}

get_template_part( 'template-parts/page-header', null, array( 'title' => wp_strip_all_tags( $mg_title ) ) );
?>
<section class="section">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="grid">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article class="card">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="card__media"><?php the_post_thumbnail( 'medium_large', array( 'alt' => '' ) ); ?></div>
						<?php endif; ?>
						<p class="kicker"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
						<h2 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p class="card__text"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
						<span class="card__more" aria-hidden="true"><?php esc_html_e( 'Read more', 'mackman-group-acf' ); ?><span class="arrow">&rarr;</span></span>
					</article>
				<?php endwhile; ?>
			</div>
			<div class="pagination"><?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?></div>
		<?php else : ?>
			<p><?php esc_html_e( 'Sorry, there is nothing here.', 'mackman-group-acf' ); ?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Return to the home page', 'mackman-group-acf' ); ?></a>.</p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
