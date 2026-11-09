<?php
/**
 * Case study opening, on green: breadcrumb, "Case study" and the type of
 * work, the H1 and the overview panel, then the lead line and summary.
 * Below it, the film or image overlaps the edge of the green, followed by
 * links to each stage of the work.
 *
 * $args['stages'] is the list from mg_case_study_stages().
 *
 * @package mackman-group-acf
 */

$mg_opening = (array) mg_field( 'opening' );
$mg_facts   = (array) mg_field( 'facts' );
$mg_stages  = ! empty( $args['stages'] ) ? $args['stages'] : array();

$mg_heading  = ! empty( $mg_opening['heading'] ) ? $mg_opening['heading'] : get_the_title();
$mg_client   = ! empty( $mg_facts['client'] ) ? $mg_facts['client'] : get_the_title();
$mg_image    = ! empty( $mg_opening['image'] ) ? $mg_opening['image'] : null;
$mg_video_id = ! empty( $mg_opening['video_url'] ) ? mg_youtube_id( $mg_opening['video_url'] ) : '';
$mg_video    = ! empty( $mg_opening['video_label'] ) ? $mg_opening['video_label'] : __( 'Watch the case study film', 'mackman-group-acf' );
$mg_has_media = $mg_image || $mg_video_id;
$mg_list_url = mg_case_studies_url();

// Overview rows; empty ones are left out.
$mg_rows = array_filter(
	array(
		__( 'Sector', 'mackman-group-acf' )   => mg_case_study_terms( 'sector' ),
		__( 'Location', 'mackman-group-acf' ) => mg_case_study_terms( 'location' ),
		__( 'Services', 'mackman-group-acf' ) => $mg_facts['services'] ?? '',
		__( 'When', 'mackman-group-acf' )     => $mg_facts['period'] ?? '',
	)
);
$mg_note = ! empty( $mg_facts['note'] ) ? $mg_facts['note'] : '';
?>
<header class="cs-opening surface-green<?php echo $mg_has_media ? ' cs-opening--has-media' : ''; ?>">
	<div class="container">
		<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'mackman-group-acf' ); ?>">
			<ol>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'mackman-group-acf' ); ?></a></li>
				<li>
					<?php if ( $mg_list_url ) : ?>
						<a href="<?php echo esc_url( $mg_list_url ); ?>"><?php esc_html_e( 'Case studies', 'mackman-group-acf' ); ?></a>
					<?php else : ?>
						<?php esc_html_e( 'Case studies', 'mackman-group-acf' ); ?>
					<?php endif; ?>
				</li>
				<li><span aria-current="page"><?php echo esc_html( $mg_client ); ?></span></li>
			</ol>
		</nav>

		<div class="cs-opening__grid">
			<div class="cs-opening__head">
				<p class="kicker kicker--ruled news-meta">
					<span><?php esc_html_e( 'Case study', 'mackman-group-acf' ); ?></span>
					<?php if ( ! empty( $mg_opening['kicker'] ) ) : ?>
						<span><?php echo esc_html( $mg_opening['kicker'] ); ?></span>
					<?php endif; ?>
				</p>
				<h1 class="cs-opening__title"><?php echo esc_html( $mg_heading ); ?></h1>
			</div>

			<aside class="cs-facts" aria-label="<?php esc_attr_e( 'Overview', 'mackman-group-acf' ); ?>">
				<p class="cs-facts__client">
					<?php if ( ! empty( $mg_facts['logo'] ) ) : ?>
						<?php echo mg_image( $mg_facts['logo'], 'medium', 'cs-facts__logo', array( 'alt' => $mg_client ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<?php else : ?>
						<?php echo esc_html( $mg_client ); ?>
					<?php endif; ?>
				</p>
				<?php if ( $mg_rows || $mg_note ) : ?>
					<dl class="cs-facts__list">
						<?php foreach ( $mg_rows as $mg_term => $mg_value ) : ?>
							<div class="cs-facts__row">
								<dt><?php echo esc_html( $mg_term ); ?></dt>
								<dd><?php echo esc_html( $mg_value ); ?></dd>
							</div>
						<?php endforeach; ?>
						<?php if ( $mg_note ) : ?>
							<div class="cs-facts__row cs-facts__row--note">
								<dt><?php echo esc_html( ! empty( $mg_facts['note_label'] ) ? $mg_facts['note_label'] : __( 'Note', 'mackman-group-acf' ) ); ?></dt>
								<dd><?php echo esc_html( $mg_note ); ?></dd>
							</div>
						<?php endif; ?>
					</dl>
				<?php endif; ?>
			</aside>
		</div>

		<?php if ( ! empty( $mg_opening['lead'] ) || ! empty( $mg_opening['summary'] ) ) : ?>
			<div class="cs-opening__intro">
				<?php if ( ! empty( $mg_opening['lead'] ) ) : ?>
					<p class="cs-opening__lead"><?php echo esc_html( $mg_opening['lead'] ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $mg_opening['summary'] ) ) : ?>
					<div class="prose soft cs-opening__summary"><?php echo wp_kses_post( $mg_opening['summary'] ); ?></div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</header>

<?php if ( $mg_has_media ) : ?>
	<div class="cs-media">
		<div class="container">
			<figure class="cs-media__figure">
				<div class="cs-media__frame<?php echo $mg_image ? '' : ' cs-media__frame--empty'; ?>">
					<?php
					if ( $mg_image ) {
						echo mg_image( // phpcs:ignore WordPress.Security.EscapeOutput
							$mg_image,
							'full',
							'cs-media__img',
							array(
								'sizes'         => '(min-width: 86rem) 80rem, 100vw',
								'loading'       => 'eager',
								'fetchpriority' => 'high',
							)
						);
					}
					?>
					<?php if ( $mg_video_id ) : ?>
						<a class="cs-media__play video-link" href="<?php echo esc_url( 'https://www.youtube.com/watch?v=' . $mg_video_id ); ?>" data-video-id="<?php echo esc_attr( $mg_video_id ); ?>">
							<span class="cs-media__icon" aria-hidden="true"></span>
							<span class="cs-media__label"><?php echo esc_html( $mg_video ); ?></span>
						</a>
					<?php endif; ?>
				</div>
				<?php if ( ! empty( $mg_opening['caption'] ) ) : ?>
					<figcaption><?php echo esc_html( $mg_opening['caption'] ); ?></figcaption>
				<?php endif; ?>
			</figure>
		</div>
	</div>
	<?php
	if ( $mg_video_id ) {
		get_template_part( 'template-parts/video-dialog', null, array( 'label' => $mg_video ) );
	}
	?>
<?php endif; ?>

<?php if ( count( $mg_stages ) > 1 ) : ?>
	<nav class="cs-approach" aria-labelledby="cs-approach-title">
		<div class="container">
			<h2 class="kicker" id="cs-approach-title"><?php esc_html_e( 'The approach', 'mackman-group-acf' ); ?></h2>
			<?php
			echo mg_thread( // phpcs:ignore WordPress.Security.EscapeOutput
				array_map(
					function ( $stage ) {
						return array(
							'label' => $stage['number'] . ' ' . $stage['name'],
							'title' => $stage['heading'],
							'url'   => '#' . $stage['id'],
						);
					},
					$mg_stages
				),
				'thread--links'
			);
			?>
		</div>
	</nav>
<?php endif; ?>
