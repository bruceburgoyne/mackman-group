<?php
/**
 * The featured lead article at the top of the News page: large image,
 * topic and date, title, standfirst and byline. Call within the loop.
 *
 * @package mackman-group-acf
 */

$mg_topic = mg_topic();
?>
<article class="news-lead">
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="news-lead__media">
			<?php
			the_post_thumbnail(
				'large',
				array(
					'alt'           => '',
					'sizes'         => '(min-width: 60rem) 55vw, 100vw',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
				)
			);
			?>
		</div>
	<?php endif; ?>
	<div class="news-lead__text">
		<p class="kicker news-meta">
			<span><?php esc_html_e( 'Latest', 'mackman-group-acf' ); ?></span>
			<?php if ( $mg_topic ) : ?>
				<span><?php echo esc_html( $mg_topic->name ); ?></span>
			<?php endif; ?>
		</p>
		<h2 class="news-lead__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<?php if ( has_excerpt() ) : ?>
			<p class="news-lead__standfirst"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<?php endif; ?>
		<p class="byline">
			<span><?php the_author(); ?></span>
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
			<span>
				<?php
				/* translators: %d: minutes */
				echo esc_html( sprintf( _n( '%d minute read', '%d minute read', mg_reading_time(), 'mackman-group-acf' ), mg_reading_time() ) );
				?>
			</span>
		</p>
		<span class="card__more" aria-hidden="true"><?php esc_html_e( 'Read article', 'mackman-group-acf' ); ?><span class="arrow">&rarr;</span></span>
	</div>
</article>
