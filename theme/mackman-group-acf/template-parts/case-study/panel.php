<?php
/**
 * Highlight panel inside a stage, on green: the central idea of that stage.
 * Heading and text on the left; a pull line or diagram on the right; linked
 * items (for example the parts of a framework) across the foot.
 *
 * $args['panel'] is the stage's panel group: heading, text, steps, pull, image.
 *
 * @package mackman-group-acf
 */

$mg_panel = ! empty( $args['panel'] ) ? $args['panel'] : array();
if ( empty( $mg_panel['heading'] ) ) {
	return;
}

$mg_steps = mg_lines( $mg_panel['steps'] ?? '' );
$mg_image = ! empty( $mg_panel['image'] ) ? $mg_panel['image'] : null;
$mg_side  = ! empty( $mg_panel['pull'] ) || $mg_image;
?>
<div class="cs-panel surface-green<?php echo $mg_side ? ' cs-panel--has-side' : ''; ?>">
	<div class="cs-panel__main">
		<h3 class="cs-panel__title"><?php echo esc_html( $mg_panel['heading'] ); ?></h3>
		<?php if ( ! empty( $mg_panel['text'] ) ) : ?>
			<div class="prose soft"><?php echo wp_kses_post( $mg_panel['text'] ); ?></div>
		<?php endif; ?>
	</div>
	<?php if ( $mg_side ) : ?>
		<div class="cs-panel__side">
			<?php if ( $mg_image ) : ?>
				<figure class="cs-panel__diagram"><?php echo mg_image( $mg_image, 'large', 'cs-panel__img' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></figure>
			<?php endif; ?>
			<?php if ( ! empty( $mg_panel['pull'] ) ) : ?>
				<p class="cs-panel__pull"><?php echo esc_html( $mg_panel['pull'] ); ?></p>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<?php if ( $mg_steps ) : ?>
		<div class="cs-panel__steps"><?php echo mg_thread( $mg_steps ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
	<?php endif; ?>
</div>
