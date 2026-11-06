<?php
/**
 * A single article.
 *
 * Laid out as an article rather than a page: a light header (topic and
 * date, headline, standfirst, byline), a wide featured image, then the
 * body in a narrow reading column (42rem). After it: the topic and tags,
 * "More news", and the News page's call to action.
 *
 * The body comes from the block editor and is styled by assets/css/article.css.
 *
 * @package mackman-group-acf
 */

get_header();

while ( have_posts() ) :
	the_post();
	$mg_topic   = mg_topic();
	$mg_minutes = mg_reading_time();
	$mg_tags    = get_the_tags();
	$mg_news_id = mg_news_page_id();
	?>
	<article <?php post_class( 'article' ); ?>>
		<header class="article-header">
			<div class="container">
				<div class="article-header__inner">
					<p class="kicker kicker--ruled news-meta">
						<?php if ( $mg_topic ) : ?>
							<a href="<?php echo esc_url( get_category_link( $mg_topic ) ); ?>"><?php echo esc_html( $mg_topic->name ); ?></a>
						<?php elseif ( $mg_news_id ) : ?>
							<a href="<?php echo esc_url( get_permalink( $mg_news_id ) ); ?>"><?php echo esc_html( get_the_title( $mg_news_id ) ); ?></a>
						<?php endif; ?>
					</p>
					<h1 class="article-title"><?php the_title(); ?></h1>
					<?php if ( has_excerpt() ) : ?>
						<p class="article-standfirst"><?php echo esc_html( get_the_excerpt() ); ?></p>
					<?php endif; ?>
					<p class="byline">
						<span><?php esc_html_e( 'By', 'mackman-group-acf' ); ?> <strong><?php the_author(); ?></strong></span>
						<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
						<span>
							<?php
							/* translators: %d: minutes */
							echo esc_html( sprintf( _n( '%d minute read', '%d minute read', $mg_minutes, 'mackman-group-acf' ), $mg_minutes ) );
							?>
						</span>
					</p>
				</div>
			</div>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="article-image container">
				<?php
				the_post_thumbnail(
					'full',
					array(
						'class'         => 'article-image__img',
						'sizes'         => '(min-width: 72rem) 64rem, 100vw',
						'loading'       => 'eager',
						'fetchpriority' => 'high',
					)
				);
				$mg_caption = wp_get_attachment_caption( get_post_thumbnail_id() );
				if ( $mg_caption ) :
					?>
					<figcaption><?php echo esc_html( $mg_caption ); ?></figcaption>
				<?php endif; ?>
			</figure>
		<?php endif; ?>

		<div class="article-body">
			<?php the_content(); ?>
		</div>

		<footer class="article-footer">
			<?php if ( $mg_topic || $mg_tags ) : ?>
				<ul class="article-footer__terms" aria-label="<?php esc_attr_e( 'Topics', 'mackman-group-acf' ); ?>">
					<?php if ( $mg_topic ) : ?>
						<li><a href="<?php echo esc_url( get_category_link( $mg_topic ) ); ?>"><?php echo esc_html( $mg_topic->name ); ?></a></li>
					<?php endif; ?>
					<?php foreach ( (array) $mg_tags as $mg_tag ) : ?>
						<?php if ( $mg_tag instanceof WP_Term ) : ?>
							<li><a href="<?php echo esc_url( get_tag_link( $mg_tag ) ); ?>"><?php echo esc_html( $mg_tag->name ); ?></a></li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<?php if ( $mg_news_id ) : ?>
				<a class="text-link" href="<?php echo esc_url( get_permalink( $mg_news_id ) ); ?>"><span class="arrow" aria-hidden="true">&larr;</span> <?php esc_html_e( 'All news', 'mackman-group-acf' ); ?></a>
			<?php endif; ?>
		</footer>
	</article>
	<?php
	get_template_part( 'template-parts/news/related', null, array( 'topic' => $mg_topic ) );
endwhile;

if ( mg_news_page_id() ) {
	get_template_part( 'template-parts/sections/cta', null, array( 'data' => mg_field( 'cta', mg_news_page_id() ) ) );
}

get_footer();
