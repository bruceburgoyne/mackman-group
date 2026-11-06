<?php
/**
 * Single article and other single posts. A plain version for now: the
 * styled article layout comes with the News build.
 *
 * @package mackman-group-acf
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class(); ?>>
		<header class="page-header surface-green">
			<div class="container">
				<p class="kicker kicker--ruled"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
				<h1 class="page-header__title"><?php the_title(); ?></h1>
			</div>
		</header>
		<div class="section">
			<div class="container">
				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="article-image"><?php the_post_thumbnail( 'large', array( 'loading' => 'eager' ) ); ?></figure>
				<?php endif; ?>
				<div class="prose article-body"><?php the_content(); ?></div>
			</div>
		</div>
	</article>
	<?php
endwhile;

get_footer();
