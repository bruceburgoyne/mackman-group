<?php
/**
 * News: helpers, archive paging and article styles.
 *
 * - Topics are categories (the default "Uncategorized" is ignored).
 * - The excerpt is the article's standfirst.
 * - The News page leads with the newest article, then a grid of nine,
 *   so the first page shows ten articles and later pages nine.
 *
 * @package mackman-group-acf
 */

defined( 'ABSPATH' ) || exit;

const MG_NEWS_PER_PAGE = 9;

/**
 * The page set as the Posts page in Settings > Reading (News), or 0.
 *
 * @return int
 */
function mg_news_page_id() {
	return (int) get_option( 'page_for_posts' );
}

/**
 * The article's topic: its first category other than the default.
 *
 * @param int|WP_Post|null $post Post.
 * @return WP_Term|null
 */
function mg_topic( $post = null ) {
	$post    = get_post( $post );
	$default = (int) get_option( 'default_category' );
	if ( ! $post ) {
		return null;
	}
	foreach ( get_the_category( $post->ID ) as $term ) {
		if ( (int) $term->term_id !== $default ) {
			return $term;
		}
	}
	return null;
}

/**
 * Estimated reading time in minutes, at 200 words a minute.
 *
 * @param int|WP_Post|null $post Post.
 * @return int
 */
function mg_reading_time( $post = null ) {
	$words = str_word_count( wp_strip_all_tags( (string) get_post_field( 'post_content', $post ) ) );
	return max( 1, (int) ceil( $words / 200 ) );
}

/**
 * Topics to offer as filters: categories with articles, except the default.
 *
 * @return WP_Term[]
 */
function mg_news_topics() {
	return get_categories(
		array(
			'hide_empty' => true,
			'exclude'    => array( (int) get_option( 'default_category' ) ),
		)
	);
}

/**
 * Whether this is the first page of the News page, where an article leads.
 *
 * @param WP_Query|null $query Query (default: main query).
 * @return bool
 */
function mg_news_has_lead( $query = null ) {
	$query = $query ? $query : $GLOBALS['wp_query'];
	return $query->is_home() && ! $query->is_paged();
}

// Paging: ten on the first News page (one lead plus nine), nine elsewhere.
add_action(
	'pre_get_posts',
	function ( $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}
		if ( $query->is_home() ) {
			$paged = max( 1, (int) $query->get( 'paged' ) );
			if ( 1 === $paged ) {
				$query->set( 'posts_per_page', MG_NEWS_PER_PAGE + 1 );
			} else {
				$query->set( 'posts_per_page', MG_NEWS_PER_PAGE );
				$query->set( 'offset', MG_NEWS_PER_PAGE + 1 + ( $paged - 2 ) * MG_NEWS_PER_PAGE );
			}
		} elseif ( $query->is_category() || $query->is_tag() || $query->is_author() || $query->is_date() ) {
			$query->set( 'posts_per_page', MG_NEWS_PER_PAGE );
		}
	}
);

// Page count for the News page, given the uneven first page.
add_filter(
	'found_posts',
	function ( $found, $query ) {
		if ( is_admin() || ! $query->is_main_query() || ! $query->is_home() ) {
			return $found;
		}
		$pages = 1 + (int) ceil( max( 0, $found - ( MG_NEWS_PER_PAGE + 1 ) ) / MG_NEWS_PER_PAGE );
		// WordPress divides by posts_per_page, so return a total that gives $pages.
		return $query->is_paged() ? $pages * MG_NEWS_PER_PAGE : $pages * ( MG_NEWS_PER_PAGE + 1 );
	},
	10,
	2
);

/**
 * Article styles. One file, two uses: nested inside .article-body on the
 * site (so it never touches other pages), and loaded into the block editor
 * so writers see what readers will see.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		if ( ! is_singular( 'post' ) ) {
			return;
		}
		$css = file_get_contents( get_template_directory() . '/assets/css/article.css' ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		wp_add_inline_style( 'mg-main', '.article-body {' . $css . '}' );
	},
	20
);

add_action(
	'after_setup_theme',
	function () {
		add_editor_style( 'assets/css/article.css' );
	},
	20
);
