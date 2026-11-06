<?php
/**
 * Topic filter for the News page and topic archives: All, then each topic.
 * The current one is marked with aria-current.
 *
 * @package mackman-group-acf
 */

$mg_topics = mg_news_topics();
if ( count( $mg_topics ) < 2 ) {
	return;
}
$mg_news_id = mg_news_page_id();
$mg_current = is_category() ? get_queried_object_id() : 0;
?>
<nav class="topic-nav" aria-label="<?php esc_attr_e( 'News topics', 'mackman-group-acf' ); ?>">
	<div class="container">
		<ul class="topic-nav__list">
			<?php if ( $mg_news_id ) : ?>
				<li><a href="<?php echo esc_url( get_permalink( $mg_news_id ) ); ?>"<?php echo is_home() ? ' aria-current="page"' : ''; ?>><?php esc_html_e( 'All news', 'mackman-group-acf' ); ?></a></li>
			<?php endif; ?>
			<?php foreach ( $mg_topics as $mg_topic ) : ?>
				<li><a href="<?php echo esc_url( get_category_link( $mg_topic ) ); ?>"<?php echo (int) $mg_topic->term_id === $mg_current ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $mg_topic->name ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
</nav>
