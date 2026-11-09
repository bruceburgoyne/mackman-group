<?php
/**
 * Hero: the home page opening, on green. Text on the left, aligned to the
 * page container; the image fills the right half to the edge of the screen.
 *
 * $args['data'] is an ACF group with: kicker, heading, heading_emphasis,
 * text, button, video_url, video_label, image.
 *
 * The video link opens YouTube directly without JavaScript. With
 * JavaScript, it plays in a dialog (assets/js/video.js) using the
 * youtube-nocookie domain, and the player only loads when asked for.
 *
 * @package mackman-group-acf
 */

$mg_data     = isset( $args['data'] ) && is_array( $args['data'] ) ? $args['data'] : array();
$mg_image    = ! empty( $mg_data['image'] ) ? $mg_data['image'] : null;
$mg_video_id = ! empty( $mg_data['video_url'] ) ? mg_youtube_id( $mg_data['video_url'] ) : '';
?>
<section class="hero surface-green<?php echo $mg_image ? ' hero--split' : ''; ?>">
	<div class="hero__content">
		<?php if ( ! empty( $mg_data['kicker'] ) ) : ?>
			<p class="kicker kicker--ruled"><?php echo esc_html( $mg_data['kicker'] ); ?></p>
		<?php endif; ?>
		<h1 class="hero__title">
			<?php echo esc_html( ! empty( $mg_data['heading'] ) ? $mg_data['heading'] : get_bloginfo( 'name' ) ); ?>
			<?php if ( ! empty( $mg_data['heading_emphasis'] ) ) : ?>
				<em><?php echo esc_html( $mg_data['heading_emphasis'] ); ?></em>
			<?php endif; ?>
		</h1>
		<?php if ( ! empty( $mg_data['text'] ) ) : ?>
			<p class="hero__text soft"><?php echo mg_text( $mg_data['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
		<?php endif; ?>
		<div class="button-row">
			<?php echo mg_link( $mg_data['button'] ?? null, 'button button--gold', true ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php if ( $mg_video_id ) : ?>
				<a class="text-link video-link" href="<?php echo esc_url( 'https://www.youtube.com/watch?v=' . $mg_video_id ); ?>" data-video-id="<?php echo esc_attr( $mg_video_id ); ?>">
					<span class="video-link__icon" aria-hidden="true"></span>
					<?php echo esc_html( ! empty( $mg_data['video_label'] ) ? $mg_data['video_label'] : __( 'Watch the video', 'mackman-group-acf' ) ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
	<?php if ( $mg_image ) : ?>
		<div class="hero__media">
			<?php
			echo mg_image( // phpcs:ignore WordPress.Security.EscapeOutput
				$mg_image,
				'full',
				'hero__image',
				array(
					'sizes'         => '(min-width: 60rem) 50vw, 100vw',
					'fetchpriority' => 'high',
					'loading'       => 'eager',
				)
			);
			?>
		</div>
	<?php endif; ?>
</section>

<?php
if ( $mg_video_id ) {
	get_template_part( 'template-parts/video-dialog', null, array( 'label' => ! empty( $mg_data['video_label'] ) ? $mg_data['video_label'] : '' ) );
}
