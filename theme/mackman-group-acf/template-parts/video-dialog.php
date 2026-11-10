<?php
/**
 * The dialog that plays a YouTube film when a .video-link is pressed
 * (assets/js/video.js). Output once, on pages that have a video link.
 *
 * Optional $args['label']: the dialog's accessible name. When a film
 * opens, the script renames the dialog after the link that opened it.
 *
 * Safe to request more than once (the home page has a film in the hero
 * and in the featured case study): only the first request outputs it.
 *
 * @package mackman-group-acf
 */

if ( ! empty( $GLOBALS['mg_video_dialog_done'] ) ) {
	return;
}
$GLOBALS['mg_video_dialog_done'] = true;

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
