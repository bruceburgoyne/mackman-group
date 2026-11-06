<?php
/**
 * Contact Form 7 and Cloudflare Turnstile, carried over from the old theme.
 *
 * - CF7's script and styles load only on pages that render a form.
 * - Turnstile is held back until a visitor starts using a form.
 *
 * The form shortcode is rendered during the template, before wp_footer, so
 * the flag is set by the time the footer scripts are printed. Scripts are
 * enqueued in the footer for that reason.
 *
 * @package mackman-group-acf
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'wpcf7_load_js', '__return_false' );
add_filter( 'wpcf7_load_css', '__return_false' );

/**
 * Record (or read) whether this page renders a contact form.
 *
 * @param bool $found Pass true to record a form.
 * @return bool
 */
function mg_has_contact_form( $found = false ) {
	static $has_form = false;
	if ( $found ) {
		$has_form = true;
	}
	return $has_form;
}

add_filter(
	'do_shortcode_tag',
	function ( $output, $tag ) {
		if ( in_array( $tag, array( 'contact-form-7', 'contact-form' ), true ) ) {
			mg_has_contact_form( true );
			if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
				wpcf7_enqueue_scripts();
				wpcf7_enqueue_styles();
			}
		}
		return $output;
	},
	10,
	2
);

add_action(
	'wp_print_footer_scripts',
	function () {
		if ( ! mg_has_contact_form() ) {
			wp_dequeue_script( 'cloudflare-turnstile' );
		}
	},
	1
);

/**
 * Record (or read) whether Turnstile was held back on this page.
 *
 * @param bool $delayed Pass true to record it.
 * @return bool
 */
function mg_turnstile_delayed( $delayed = false ) {
	static $has_delayed = false;
	if ( $delayed ) {
		$has_delayed = true;
	}
	return $has_delayed;
}

add_filter(
	'script_loader_tag',
	function ( $tag, $handle ) {
		if ( 'cloudflare-turnstile' !== $handle ) {
			return $tag;
		}

		$script = new WP_HTML_Tag_Processor( $tag );
		while ( $script->next_tag( 'script' ) ) {
			$src = $script->get_attribute( 'src' );
			if ( 'cloudflare-turnstile-js' !== $script->get_attribute( 'id' ) || ! $src ) {
				continue;
			}
			$script->remove_attribute( 'src' );
			$script->set_attribute( 'type', 'text/plain' );
			$script->set_attribute( 'data-mg-delayed-src', $src );
			mg_turnstile_delayed( true );
			break;
		}
		return $script->get_updated_html();
	},
	10,
	2
);

add_action(
	'wp_footer',
	function () {
		if ( ! mg_turnstile_delayed() ) {
			return;
		}
		?>
<script>
( () => {
	const events = [ 'focusin', 'pointerdown', 'keydown', 'input' ];
	const load = ( event ) => {
		if ( ! event.target.closest || ! event.target.closest( 'form' ) ) {
			return;
		}
		events.forEach( ( type ) => document.removeEventListener( type, load, true ) );
		document.querySelectorAll( 'script[data-mg-delayed-src]' ).forEach( ( held ) => {
			const script = document.createElement( 'script' );
			script.src = held.dataset.mgDelayedSrc;
			script.async = true;
			held.replaceWith( script );
		} );
	};
	events.forEach( ( type ) => document.addEventListener( type, load, true ) );
} )();
</script>
		<?php
	},
	100
);
