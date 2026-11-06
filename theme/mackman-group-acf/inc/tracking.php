<?php
/**
 * Google Tag Manager, carried over unchanged from the old theme.
 *
 * Cloudflare's tag gateway loads the container on every page except 404s,
 * but doesn't push the gtm.js event that triggers rely on. So 404s get the
 * full snippet, and every other page only gets the gtm.js event.
 *
 * @package mackman-group-acf
 */

defined( 'ABSPATH' ) || exit;

const MG_GTM_ID = 'GTM-KW2K7MQ';

add_action(
	'wp_head',
	function () {
		if ( is_404() ) {
			?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo esc_js( MG_GTM_ID ); ?>');</script>
<!-- End Google Tag Manager -->
			<?php
			return;
		}
		?>
<script>window.dataLayer=window.dataLayer||[];window.dataLayer.push({'gtm.start':new Date().getTime(),event:'gtm.js'});</script>
		<?php
	}
);

add_action(
	'wp_body_open',
	function () {
		?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr( MG_GTM_ID ); ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
		<?php
	}
);
