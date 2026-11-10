<?php
/**
 * Featured case study: one case study, chosen on the home page, shown as
 * its film (or image) beside its client, title, lead line and results.
 *
 * $args['data'] is an ACF group with: kicker, case_study (post ID),
 * heading, text and button. Everything else comes from the case study.
 * Hidden when no published case study is chosen.
 *
 * @package mackman-group-acf
 */

$mg_data = isset( $args['data'] ) && is_array( $args['data'] ) ? $args['data'] : array();
$mg_cs   = ! empty( $mg_data['case_study'] ) ? (int) $mg_data['case_study'] : 0;

if ( ! $mg_cs || 'publish' !== get_post_status( $mg_cs ) ) {
	return;
}

$mg_opening = (array) mg_field( 'opening', $mg_cs );
$mg_facts   = (array) mg_field( 'facts', $mg_cs );
$mg_results = (array) mg_field( 'results', $mg_cs );

$mg_heading = ! empty( $mg_data['heading'] ) ? $mg_data['heading'] : ( ! empty( $mg_opening['heading'] ) ? $mg_opening['heading'] : get_the_title( $mg_cs ) );
$mg_text    = ! empty( $mg_data['text'] ) ? $mg_data['text'] : ( ! empty( $mg_opening['lead'] ) ? $mg_opening['lead'] : ( has_excerpt( $mg_cs ) ? get_the_excerpt( $mg_cs ) : '' ) );
$mg_client  = ! empty( $mg_facts['client'] ) ? $mg_facts['client'] : '';
$mg_button  = ! empty( $mg_data['button'] ) ? $mg_data['button'] : __( 'Read the case study', 'mackman-group-acf' );
$mg_kicker  = ! empty( $mg_data['kicker'] ) ? $mg_data['kicker'] : __( 'Featured case study', 'mackman-group-acf' );

// Film: the case study's own film, played in the dialog (assets/js/video.js).
$mg_video_id    = ! empty( $mg_opening['video_url'] ) ? mg_youtube_id( $mg_opening['video_url'] ) : '';
$mg_video_label = ! empty( $mg_opening['video_label'] ) ? $mg_opening['video_label'] : __( 'Watch the case study film', 'mackman-group-acf' );

// Image (the film's poster when there is one): the opening image, else the
// featured image. Decorative here: the title says what it is.
$mg_attr  = array(
	'alt'   => '',
	'class' => 'cs-media__img',
	'sizes' => '(min-width: 60rem) 55vw, 100vw',
);
$mg_image = '';
if ( ! empty( $mg_opening['image'] ) ) {
	$mg_image = mg_image( $mg_opening['image'], 'large', 'cs-media__img', $mg_attr );
} elseif ( has_post_thumbnail( $mg_cs ) ) {
	$mg_image = get_the_post_thumbnail( $mg_cs, 'large', $mg_attr );
}
$mg_media = $mg_image || $mg_video_id;

$mg_rows = array_filter(
	array(
		__( 'Sector', 'mackman-group-acf' )   => mg_case_study_terms( 'sector', $mg_cs ),
		__( 'Services', 'mackman-group-acf' ) => $mg_facts['services'] ?? '',
	)
);

// Up to three evidenced figures.
$mg_stats = ! empty( $mg_results['items'] ) ? array_slice(
	array_filter(
		$mg_results['items'],
		function ( $item ) {
			return ! empty( $item['figure'] );
		}
	),
	0,
	3
) : array();

$mg_id = 'featured-case-' . wp_unique_id();
?>
<section class="section featured-case surface-white<?php echo $mg_media ? ' featured-case--has-media' : ''; ?>" aria-labelledby="<?php echo esc_attr( $mg_id ); ?>">
	<div class="container featured-case__grid">
		<?php if ( $mg_media ) : ?>
			<div class="featured-case__media cs-media__frame<?php echo $mg_image ? '' : ' cs-media__frame--empty'; ?>">
				<?php echo $mg_image; // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php if ( $mg_video_id ) : ?>
					<a class="cs-media__play video-link" href="<?php echo esc_url( 'https://www.youtube.com/watch?v=' . $mg_video_id ); ?>" data-video-id="<?php echo esc_attr( $mg_video_id ); ?>">
						<span class="cs-media__icon" aria-hidden="true"></span>
						<span class="cs-media__label"><?php echo esc_html( $mg_video_label ); ?></span>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="featured-case__body">
			<p class="kicker kicker--ruled news-meta">
				<span><?php echo esc_html( $mg_kicker ); ?></span>
				<?php if ( $mg_client ) : ?>
					<span><?php echo esc_html( $mg_client ); ?></span>
				<?php endif; ?>
			</p>
			<h2 class="featured-case__title" id="<?php echo esc_attr( $mg_id ); ?>"><?php echo esc_html( $mg_heading ); ?></h2>
			<?php if ( $mg_text ) : ?>
				<p class="featured-case__lead"><?php echo esc_html( $mg_text ); ?></p>
			<?php endif; ?>

			<?php if ( $mg_rows ) : ?>
				<dl class="featured-case__facts">
					<?php foreach ( $mg_rows as $mg_term => $mg_value ) : ?>
						<div class="cs-facts__row">
							<dt><?php echo esc_html( $mg_term ); ?></dt>
							<dd><?php echo esc_html( $mg_value ); ?></dd>
						</div>
					<?php endforeach; ?>
				</dl>
			<?php endif; ?>

			<?php if ( $mg_stats ) : ?>
				<dl class="stats featured-case__stats">
					<?php foreach ( $mg_stats as $mg_item ) : ?>
						<div class="stat">
							<dt><?php echo esc_html( $mg_item['label'] ?? '' ); ?></dt>
							<dd><?php echo esc_html( $mg_item['figure'] ); ?></dd>
						</div>
					<?php endforeach; ?>
				</dl>
			<?php endif; ?>

			<div class="button-row">
				<a class="button" href="<?php echo esc_url( get_permalink( $mg_cs ) ); ?>">
					<?php echo esc_html( $mg_button ); ?><span class="screen-reader-text">: <?php echo esc_html( get_the_title( $mg_cs ) ); ?></span><span class="arrow" aria-hidden="true">&rarr;</span>
				</a>
			</div>
		</div>
	</div>
</section>
<?php
if ( $mg_video_id ) {
	get_template_part( 'template-parts/video-dialog' );
}
