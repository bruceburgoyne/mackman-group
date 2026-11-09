<?php
/**
 * One stage of the work (Understand, Shape, Deliver or Grow). A numbered
 * marker on a gold line runs down the left of every stage on wide screens,
 * so the stages read as one connected piece of work.
 *
 * In order, each part hidden when empty: stage name, heading, lead, text,
 * image, points (grid or numbered list), the green highlight panel (before
 * or after the points) and a closing line.
 *
 * $args['stage'] is one row from mg_case_study_stages().
 * $args['index'] is its position, used to alternate the surface (white
 * first, so the first stage stands apart from the challenge above it).
 *
 * @package mackman-group-acf
 */

$mg_stage = ! empty( $args['stage'] ) ? $args['stage'] : array();
if ( empty( $mg_stage['heading'] ) ) {
	return;
}

$mg_index  = isset( $args['index'] ) ? (int) $args['index'] : 0;
$mg_points = ! empty( $mg_stage['points'] ) ? array_values(
	array_filter(
		$mg_stage['points'],
		function ( $point ) {
			return ! empty( $point['title'] );
		}
	)
) : array();
$mg_panel  = ( ! empty( $mg_stage['panel']['heading'] ) ) ? $mg_stage['panel'] : null;
$mg_before = $mg_panel && isset( $mg_panel['position'] ) && 'before' === $mg_panel['position'];
$mg_image  = ! empty( $mg_stage['image'] ) ? $mg_stage['image'] : null;
$mg_id     = $mg_stage['id'];
$mg_cols   = min( max( count( $mg_points ), 2 ), 4 );
?>
<section class="section cs-stage<?php echo $mg_index % 2 ? '' : ' surface-white'; ?>" id="<?php echo esc_attr( $mg_id ); ?>" aria-labelledby="<?php echo esc_attr( $mg_id ); ?>-title">
	<div class="container cs-stage__inner">
		<span class="cs-stage__marker" aria-hidden="true"><?php echo esc_html( $mg_stage['number'] ); ?></span>
		<div class="cs-stage__body">
			<p class="kicker cs-stage__name">
				<span class="screen-reader-text">
					<?php
					/* translators: %s: stage number, for example 01 */
					echo esc_html( sprintf( __( 'Stage %s:', 'mackman-group-acf' ), $mg_stage['number'] ) );
					?>
				</span>
				<?php echo esc_html( $mg_stage['name'] ); ?>
			</p>
			<h2 id="<?php echo esc_attr( $mg_id ); ?>-title" class="cs-stage__title"><?php echo esc_html( $mg_stage['heading'] ); ?></h2>
			<?php if ( ! empty( $mg_stage['lead'] ) ) : ?>
				<p class="lead"><?php echo esc_html( $mg_stage['lead'] ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $mg_stage['text'] ) ) : ?>
				<div class="prose cs-stage__text"><?php echo wp_kses_post( $mg_stage['text'] ); ?></div>
			<?php endif; ?>

			<?php if ( $mg_image ) : ?>
				<figure class="cs-stage__media">
					<?php echo mg_image( $mg_image, 'full', 'cs-stage__img', array( 'sizes' => '(min-width: 86rem) 72rem, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<?php if ( ! empty( $mg_stage['caption'] ) ) : ?>
						<figcaption><?php echo esc_html( $mg_stage['caption'] ); ?></figcaption>
					<?php endif; ?>
				</figure>
			<?php endif; ?>

			<?php
			if ( $mg_before ) {
				get_template_part( 'template-parts/case-study/panel', null, array( 'panel' => $mg_panel ) );
			}
			?>

			<?php if ( $mg_points ) : ?>
				<div class="cs-points">
					<?php if ( ! empty( $mg_stage['points_kicker'] ) ) : ?>
						<p class="kicker"><?php echo esc_html( $mg_stage['points_kicker'] ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $mg_stage['points_intro'] ) ) : ?>
						<p class="lead"><?php echo esc_html( $mg_stage['points_intro'] ); ?></p>
					<?php endif; ?>

					<?php if ( isset( $mg_stage['points_layout'] ) && 'list' === $mg_stage['points_layout'] ) : ?>
						<ol class="cs-list">
							<?php foreach ( $mg_points as $mg_n => $mg_point ) : ?>
								<li class="cs-list__item">
									<span class="cs-list__number" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $mg_n + 1 ) ); ?></span>
									<div class="cs-list__head">
										<?php if ( ! empty( $mg_point['label'] ) ) : ?>
											<p class="kicker"><?php echo esc_html( $mg_point['label'] ); ?></p>
										<?php endif; ?>
										<h3 class="cs-list__title"><?php echo esc_html( $mg_point['title'] ); ?></h3>
									</div>
									<?php if ( ! empty( $mg_point['text'] ) ) : ?>
										<p class="cs-list__text"><?php echo mg_text( $mg_point['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ol>
					<?php else : ?>
						<div class="card-grid"><div class="grid grid--<?php echo (int) $mg_cols; ?>">
							<?php foreach ( $mg_points as $mg_point ) : ?>
								<div class="card card--static cs-point">
									<?php if ( ! empty( $mg_point['label'] ) ) : ?>
										<p class="kicker"><?php echo esc_html( $mg_point['label'] ); ?></p>
									<?php endif; ?>
									<h3 class="card__title"><?php echo esc_html( $mg_point['title'] ); ?></h3>
									<?php if ( ! empty( $mg_point['text'] ) ) : ?>
										<p class="card__text"><?php echo mg_text( $mg_point['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php
			if ( $mg_panel && ! $mg_before ) {
				get_template_part( 'template-parts/case-study/panel', null, array( 'panel' => $mg_panel ) );
			}
			?>

			<?php if ( ! empty( $mg_stage['pull'] ) ) : ?>
				<p class="cs-pull cs-pull--wide"><?php echo esc_html( $mg_stage['pull'] ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>
