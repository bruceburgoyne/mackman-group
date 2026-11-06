<?php
/**
 * Intro section: the top of a page. Label, the page's H1, introduction,
 * button and an image beside them, on green.
 *
 * $args['data'] is an ACF group with: kicker, heading, text, button, image.
 * The heading falls back to the page title, so the page always has an H1.
 *
 * @package mackman-group-acf
 */

$mg_data    = isset( $args['data'] ) && is_array( $args['data'] ) ? $args['data'] : array();
$mg_heading = ! empty( $mg_data['heading'] ) ? $mg_data['heading'] : get_the_title();
$mg_image   = ! empty( $mg_data['image'] ) ? $mg_data['image'] : null;
?>
<section class="intro surface-green<?php echo $mg_image ? ' intro--has-image' : ''; ?>">
	<div class="container intro__inner">
		<div class="intro__text">
			<?php if ( ! empty( $mg_data['kicker'] ) ) : ?>
				<p class="kicker kicker--ruled"><?php echo esc_html( $mg_data['kicker'] ); ?></p>
			<?php endif; ?>
			<h1 class="intro__title"><?php echo esc_html( $mg_heading ); ?></h1>
			<?php if ( ! empty( $mg_data['text'] ) ) : ?>
				<div class="intro__body soft"><?php echo wp_kses_post( $mg_data['text'] ); ?></div>
			<?php endif; ?>
			<?php if ( ! empty( $mg_data['button']['url'] ) ) : ?>
				<div class="button-row"><?php echo mg_link( $mg_data['button'], 'button button--gold', true ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
			<?php endif; ?>
		</div>
		<?php if ( $mg_image ) : ?>
			<figure class="intro__media">
				<?php
				echo mg_image( // phpcs:ignore WordPress.Security.EscapeOutput
					$mg_image,
					'large',
					'intro__img',
					array(
						'loading'       => 'eager',
						'fetchpriority' => 'high',
						'sizes'         => '(min-width: 60rem) 50vw, 100vw',
					)
				);
				?>
			</figure>
		<?php endif; ?>
	</div>
</section>
