<?php
/**
 * More case studies: up to three, from the same sector first, topped up
 * with the newest. Hidden when there are no others.
 *
 * @package mackman-group-acf
 */

$mg_current = get_the_ID();
$mg_ids     = array();
$mg_sectors = get_the_terms( $mg_current, 'sector' );

if ( $mg_sectors && ! is_wp_error( $mg_sectors ) ) {
	$mg_ids = get_posts(
		array(
			'post_type'      => 'case-study',
			'fields'         => 'ids',
			'posts_per_page' => 3,
			'post__not_in'   => array( $mg_current ),
			'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery
				array(
					'taxonomy' => 'sector',
					'terms'    => wp_list_pluck( $mg_sectors, 'term_id' ),
				),
			),
		)
	);
}
if ( count( $mg_ids ) < 3 ) {
	$mg_ids = array_merge(
		$mg_ids,
		get_posts(
			array(
				'post_type'      => 'case-study',
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

$mg_list_url = mg_case_studies_url();
?>
<section class="section cs-related surface-white" aria-labelledby="cs-related-title">
	<div class="container">
		<div class="section-head section-head--row">
			<h2 id="cs-related-title"><?php esc_html_e( 'More case studies', 'mackman-group-acf' ); ?></h2>
			<?php if ( $mg_list_url ) : ?>
				<a class="text-link" href="<?php echo esc_url( $mg_list_url ); ?>"><?php esc_html_e( 'All case studies', 'mackman-group-acf' ); ?> <span class="arrow" aria-hidden="true">&rarr;</span></a>
			<?php endif; ?>
		</div>
		<div class="card-grid"><div class="grid grid--3">
			<?php
			foreach ( $mg_ids as $mg_id ) {
				get_template_part( 'template-parts/case-study/card', null, array( 'post_id' => $mg_id ) );
			}
			?>
		</div></div>
	</div>
</section>
