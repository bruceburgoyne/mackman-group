<?php
/**
 * Site header, plus the mobile menu panel.
 *
 * On smaller screens a burger button opens a panel from the left that pushes
 * the page (.site-wrap) across. See assets/js/menu.js.
 *
 * @package mackman-group-acf
 */

$mg_logo          = mg_option( 'logo' );
$mg_header_button = mg_option( 'header_button' );
$mg_side_logo     = mg_option( 'side_logo' );
$mg_side_phone    = mg_option( 'side_phone' ) ? mg_option( 'phone' ) : '';
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script>document.documentElement.classList.add('has-js');</script>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to content', 'mackman-group-acf' ); ?></a>

<div id="mobile-panel" class="mobile-panel" aria-label="<?php esc_attr_e( 'Menu', 'mackman-group-acf' ); ?>" role="dialog" aria-modal="true" inert>
	<button class="mobile-panel__close" type="button" data-menu-close>
		<span class="screen-reader-text"><?php esc_html_e( 'Close menu', 'mackman-group-acf' ); ?></span>
		<span class="mobile-panel__close-icon" aria-hidden="true"></span>
	</button>
	<nav class="mobile-panel__nav" aria-label="<?php esc_attr_e( 'Mobile', 'mackman-group-acf' ); ?>">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'mobile-panel__list',
				'fallback_cb'    => false,
				'depth'          => 2,
				'walker'         => new MG_Nav_Walker(),
			)
		);
		?>
	</nav>
	<?php if ( ! empty( $mg_header_button['url'] ) ) : ?>
		<div class="mobile-panel__cta"><?php echo mg_link( $mg_header_button, 'button', true ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
	<?php endif; ?>
</div>

<div class="site-wrap">
<?php if ( $mg_side_logo || $mg_side_phone ) : ?>
	<?php
	// Side panel: a green rail down the left on wide screens, a bar across
	// the top on smaller ones. Content from Site Settings > Side panel.
	$mg_side_img = mg_image(
		$mg_side_logo,
		'medium',
		'site-rail__img',
		array(
			'alt'     => (string) mg_option( 'side_logo_name' ),
			'loading' => 'eager',
		)
	);
	$mg_side_url = mg_option( 'side_logo_link' );
	?>
	<aside class="site-rail surface-green" aria-label="<?php esc_attr_e( 'Certification and telephone', 'mackman-group-acf' ); ?>">
		<?php if ( $mg_side_img ) : ?>
			<?php if ( $mg_side_url ) : ?>
				<a class="site-rail__logo" href="<?php echo esc_url( $mg_side_url ); ?>"><?php echo $mg_side_img; // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
			<?php else : ?>
				<span class="site-rail__logo"><?php echo $mg_side_img; // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			<?php endif; ?>
		<?php endif; ?>
		<?php if ( $mg_side_phone ) : ?>
			<a class="site-rail__phone" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $mg_side_phone ) ); ?>">
				<span class="site-rail__label"><?php esc_html_e( 'Telephone', 'mackman-group-acf' ); ?></span>
				<span class="site-rail__number"><?php echo esc_html( $mg_side_phone ); ?></span>
			</a>
		<?php endif; ?>
	</aside>
<?php endif; ?>
<div class="site-body">
<header class="site-header">
	<div class="container site-header__inner">
		<a class="site-header__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php if ( $mg_logo ) : ?>
				<?php echo mg_image( $mg_logo, 'medium', 'site-header__logo', array( 'alt' => get_bloginfo( 'name' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php else : ?>
				<img class="site-header__logo" src="<?php echo esc_url( mg_asset( 'images/logo-lockup.svg' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="271" height="36">
			<?php endif; ?>
		</a>

		<nav class="site-nav" aria-label="<?php esc_attr_e( 'Primary', 'mackman-group-acf' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'site-nav__list',
					'fallback_cb'    => false,
					'depth'          => 2,
					'walker'         => new MG_Nav_Walker(),
				)
			);
			?>
			<?php echo mg_link( $mg_header_button, 'button site-nav__button' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</nav>

		<button class="nav-toggle" type="button" aria-controls="mobile-panel" aria-expanded="false">
			<span class="screen-reader-text"><?php esc_html_e( 'Open menu', 'mackman-group-acf' ); ?></span>
			<span class="nav-toggle__bars" aria-hidden="true"><span></span></span>
		</button>
	</div>
</header>

<main id="main" class="site-main">
