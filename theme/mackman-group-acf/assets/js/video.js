/**
 * Video links: play a YouTube video in a dialog instead of leaving the page.
 *
 * - The link works on its own (it goes to YouTube) if this script or
 *   <dialog> is unavailable.
 * - The player is only added when the visitor asks for it, from the
 *   youtube-nocookie domain, and removed again on close so playback stops.
 * - <dialog> handles focus, Escape and returning focus to the link.
 */
( function () {
	'use strict';

	var dialog = document.querySelector( '.video-dialog' );
	if ( ! dialog || typeof dialog.showModal !== 'function' ) {
		return;
	}
	var frame = dialog.querySelector( '.video-dialog__frame' );

	document.querySelectorAll( '.video-link[data-video-id]' ).forEach( function ( link ) {
		link.addEventListener( 'click', function ( event ) {
			event.preventDefault();
			var iframe = document.createElement( 'iframe' );
			iframe.src = 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent( link.dataset.videoId ) + '?autoplay=1&rel=0';
			iframe.title = link.textContent.trim();
			iframe.allow = 'autoplay; encrypted-media; picture-in-picture; fullscreen';
			iframe.allowFullscreen = true;
			frame.replaceChildren( iframe );
			dialog.setAttribute( 'aria-label', iframe.title );
			dialog.showModal();
		} );
	} );

	dialog.addEventListener( 'close', function () {
		frame.replaceChildren();
	} );

	// A click on the backdrop closes the dialog.
	dialog.addEventListener( 'click', function ( event ) {
		if ( event.target === dialog ) {
			dialog.close();
		}
	} );
}() );
