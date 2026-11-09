<?php
/**
 * The dialog that plays a YouTube film when a .video-link is pressed
 * (assets/js/video.js). Output once, on pages that have a video link.
 *
 * Optional $args['label']: the dialog's accessible name.
 *
 * @package mackman-group-acf
 */

$mg_label = ! empty( $args['label'] ) ? $args['label'] : __( 'Video', 'mackman-group-acf' );
?>
<dialog class="video-dialog" aria-label="<?php echo esc_attr( $mg_label ); ?>">
	<form method="dialog" class="video-dialog__bar">
		<button class="video-dialog__close" type="submit">
			<span class="screen-reader-text"><?php esc_html_e( 'Close video', 'mackman-group-acf' ); ?></span>
			<span class="mobile-panel__close-icon" aria-hidden="true"></span>
		</button>
	</form>
	<div class="video-dialog__frame"></div>
</dialog>
