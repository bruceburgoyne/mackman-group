/**
 * Drop-down navigation (WAI-ARIA disclosure pattern).
 *
 * Everywhere:
 * - The button beside a parent shows or hides its sub-pages and keeps
 *   aria-expanded up to date. Opening one closes its siblings.
 *
 * Header navigation on wide screens:
 * - Pointing at a parent opens its sub-pages; moving away closes them after
 *   a short pause, so the pointer can cross into the list (WCAG 1.4.13).
 * - Escape closes the open list and returns focus to its button.
 * - Tabbing or clicking away closes it.
 *
 * In the mobile panel the buttons work as an accordion; there is no hover.
 */
( function () {
	'use strict';

	var CLOSE_DELAY = 300;
	var canHover = window.matchMedia( '(hover: hover) and (pointer: fine)' );

	function itemOf( button ) {
		return button.closest( 'li' );
	}

	function setOpen( button, open ) {
		button.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		var item = itemOf( button );
		if ( item ) {
			item.classList.toggle( 'is-open', open );
		}
	}

	function closeSiblings( button ) {
		var item = itemOf( button );
		if ( ! item || ! item.parentElement ) {
			return;
		}
		item.parentElement.querySelectorAll( ':scope > li > .submenu-toggle[aria-expanded="true"]' ).forEach( function ( other ) {
			if ( other !== button ) {
				setOpen( other, false );
			}
		} );
	}

	function open( button ) {
		closeSiblings( button );
		setOpen( button, true );
	}

	function setUp( nav, desktop ) {
		var toggles = nav.querySelectorAll( '.submenu-toggle' );
		if ( ! toggles.length ) {
			return;
		}

		toggles.forEach( function ( button ) {
			var item = itemOf( button );
			var timer = null;
			var openedByHover = false;

			button.addEventListener( 'click', function () {
				var expanded = button.getAttribute( 'aria-expanded' ) === 'true';
				// If hover has just opened it, a click keeps it open rather than closing it.
				if ( expanded && openedByHover ) {
					openedByHover = false;
					return;
				}
				openedByHover = false;
				if ( expanded ) {
					setOpen( button, false );
				} else {
					open( button );
				}
			} );

			if ( ! desktop ) {
				return;
			}

			item.addEventListener( 'mouseenter', function () {
				if ( ! canHover.matches ) {
					return;
				}
				window.clearTimeout( timer );
				if ( button.getAttribute( 'aria-expanded' ) !== 'true' ) {
					openedByHover = true;
					open( button );
				}
			} );

			item.addEventListener( 'mouseleave', function () {
				if ( ! canHover.matches ) {
					return;
				}
				window.clearTimeout( timer );
				timer = window.setTimeout( function () {
					// Stay open while keyboard focus is inside.
					if ( ! item.contains( document.activeElement ) ) {
						openedByHover = false;
						setOpen( button, false );
					}
				}, CLOSE_DELAY );
			} );

			// Tabbing out of the item closes it.
			item.addEventListener( 'focusout', function ( event ) {
				if ( event.relatedTarget && ! item.contains( event.relatedTarget ) ) {
					openedByHover = false;
					setOpen( button, false );
				}
			} );
		} );

		if ( ! desktop ) {
			return;
		}

		// Escape closes the open list and returns focus to its button.
		nav.addEventListener( 'keydown', function ( event ) {
			if ( event.key !== 'Escape' ) {
				return;
			}
			var openButton = nav.querySelector( '.submenu-toggle[aria-expanded="true"]' );
			if ( openButton ) {
				event.preventDefault();
				setOpen( openButton, false );
				openButton.focus();
			}
		} );

		// A click anywhere else closes any open list.
		document.addEventListener( 'click', function ( event ) {
			if ( nav.contains( event.target ) ) {
				return;
			}
			nav.querySelectorAll( '.submenu-toggle[aria-expanded="true"]' ).forEach( function ( button ) {
				setOpen( button, false );
			} );
		} );
	}

	document.querySelectorAll( '.site-nav' ).forEach( function ( nav ) {
		setUp( nav, true );
	} );
	document.querySelectorAll( '.mobile-panel__nav' ).forEach( function ( nav ) {
		setUp( nav, false );
	} );

	// A sub-list holding the current page starts open in the mobile panel.
	document.querySelectorAll( '.mobile-panel__nav .current-menu-ancestor > .submenu-toggle, .mobile-panel__nav .current-menu-parent > .submenu-toggle' ).forEach( function ( button ) {
		setOpen( button, true );
	} );
}() );
