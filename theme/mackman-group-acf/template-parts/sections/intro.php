<?php
/**
 * Intro section: text with an image beside it. Label, heading,
 * introduction and button.
 *
 * By default it is the top of a page: on green, with the page's H1. The
 * heading falls back to the page title, so the page always has an H1.
 *
 * With $args['level'] = 2 it is a section further down the page (for
 * example a division's overview): an H2, on the surface in
 * $args['surface'], hidden when the heading is empty.
 *
 * $args['data'] is an ACF group with: kicker, heading, text, button, image,
 * and optionally video_url and video_label.
 *
 * @package mackman-group-acf
 */

$mg_data    = isset( $args['data'] ) && is_array( $args['data'] ) ? $args['data'] : array();
$mg_level   = isset( $args['level'] ) && 2 === (int) $args['level'] ? 2 : 1;
$mg_surface = ! empty( $args['surface'] ) ? $args['surface'] : 'surface-green';
$mg_image   = ! empty( $mg_data['image'] ) ? $mg_data['image'] : null;
// Optional film (the Content page's text and image section). The page
// template outputs the video dialog.
$mg_video_id = ! empty( $mg_data['video_url'] ) ? mg_youtube_id( $mg_data['video_url'] ) : '';
// Logos and illustrations (PNG or SVG) are shown whole, not cropped like photos.
$mg_is_logo  = ! empty( $mg_image['ID'] ) && in_array( get_post_mime_type( $mg_image['ID'] ), array( 'image/png', 'image/svg+xml' ), true );

if ( 1 === $mg_level ) {
	$mg_heading = ! empty( $mg_data['heading'] ) ? $mg_data['heading'] : get_the_title();
} else {
	$mg_heading = ! empty( $mg_data['heading'] ) ? $mg_data['heading'] : '';
	if ( '' === $mg_heading ) {
		return;
	}
	$mg_id = 'intro-' . wp_unique_id();
}
?>
<section class="intro intro--h<?php echo (int) $mg_level; ?> <?php echo esc_attr( sanitize_html_class( $mg_surface ) ); ?><?php echo $mg_image ? ' intro--has-image' : ''; ?>"<?php echo 2 === $mg_level ? ' aria-labelledby="' . esc_attr( $mg_id ) . '"' : ''; ?>>
	<div class="container intro__inner">
		<div class="intro__text">
			<?php if ( ! empty( $mg_data['kicker'] ) ) : ?>
				<p class="kicker kicker--ruled"><?php echo esc_html( $mg_data['kicker'] ); ?></p>
			<?php endif; ?>
			<?php if ( 1 === $mg_level ) : ?>
				<h1 class="intro__title"><?php echo esc_html( $mg_heading ); ?></h1>
			<?php else : ?>
				<h2 class="intro__title" id="<?php echo esc_attr( $mg_id ); ?>"><?php echo esc_html( $mg_heading ); ?></h2>
			<?php endif; ?>
			<?php if ( ! empty( $mg_data['text'] ) ) : ?>
				<div class="intro__body soft"><?php echo wp_kses_post( $mg_data['text'] ); ?></div>
			<?php endif; ?>
			<?php if ( ! empty( $mg_data['button']['url'] ) || $mg_video_id ) : ?>
				<div class="button-row">
					<?php echo mg_link( $mg_data['button'] ?? null, 1 === $mg_level ? 'button button--gold' : 'button', true ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<?php if ( $mg_video_id ) : ?>
						<a class="text-link video-link" href="<?php echo esc_url( 'https://www.youtube.com/watch?v=' . $mg_video_id ); ?>" data-video-id="<?php echo esc_attr( $mg_video_id ); ?>">
							<span class="video-link__icon" aria-hidden="true"></span>
							<?php echo esc_html( ! empty( $mg_data['video_label'] ) ? $mg_data['video_label'] : __( 'Watch the video', 'mackman-group-acf' ) ); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php if ( $mg_image ) : ?>
			<figure class="intro__media<?php echo $mg_is_logo ? ' intro__media--logo' : ''; ?>">
				<?php
				echo mg_image( // phpcs:ignore WordPress.Security.EscapeOutput
					$mg_image,
					'large',
					'intro__img',
					1 === $mg_level ? array(
						'loading'       => 'eager',
						'fetchpriority' => 'high',
						'sizes'         => '(min-width: 60rem) 50vw, 100vw',
					) : array(
						'sizes' => '(min-width: 60rem) 45vw, 100vw',
					)
				);
				?>
			</figure>
		<?php endif; ?>
	</div>
</section>
