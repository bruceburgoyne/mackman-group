<?php
/**
 * Case study challenge: heading and lead, then the text beside an image
 * with an optional pull line, and the question the work had to answer in
 * a green panel. Hidden when there is no heading.
 *
 * @package mackman-group-acf
 */

$mg_data     = (array) mg_field( 'challenge' );
$mg_question = (array) mg_field( 'question' );

if ( empty( $mg_data['heading'] ) ) {
	return;
}

$mg_image = ! empty( $mg_data['image'] ) ? $mg_data['image'] : null;
?>
<section class="section cs-challenge" aria-labelledby="cs-challenge-title">
	<div class="container">
		<div class="section-head">
			<p class="kicker"><?php echo esc_html( ! empty( $mg_data['kicker'] ) ? $mg_data['kicker'] : __( 'The challenge', 'mackman-group-acf' ) ); ?></p>
			<h2 id="cs-challenge-title"><?php echo esc_html( $mg_data['heading'] ); ?></h2>
			<?php if ( ! empty( $mg_data['lead'] ) ) : ?>
				<p class="lead"><?php echo esc_html( $mg_data['lead'] ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $mg_data['text'] ) || ! empty( $mg_data['pull'] ) || $mg_image ) : ?>
			<div class="cs-challenge__body<?php echo $mg_image ? ' cs-challenge__body--has-image' : ''; ?>">
				<?php if ( $mg_image ) : ?>
					<figure class="cs-challenge__media"><?php echo mg_image( $mg_image, 'large', 'cs-challenge__img', array( 'sizes' => '(min-width: 60rem) 40vw, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></figure>
				<?php endif; ?>
				<div>
					<?php if ( ! empty( $mg_data['text'] ) ) : ?>
						<div class="prose"><?php echo wp_kses_post( $mg_data['text'] ); ?></div>
					<?php endif; ?>
					<?php if ( ! empty( $mg_data['pull'] ) ) : ?>
						<p class="cs-pull"><?php echo esc_html( $mg_data['pull'] ); ?></p>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $mg_question['question'] ) ) : ?>
			<div class="cs-question surface-green">
				<?php if ( ! empty( $mg_question['text'] ) ) : ?>
					<div class="prose soft cs-question__text"><?php echo wp_kses_post( $mg_question['text'] ); ?></div>
				<?php endif; ?>
				<div class="cs-question__ask">
					<h3 class="kicker kicker--ruled"><?php esc_html_e( 'The question', 'mackman-group-acf' ); ?></h3>
					<p class="cs-question__question"><?php echo esc_html( $mg_question['question'] ); ?></p>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
