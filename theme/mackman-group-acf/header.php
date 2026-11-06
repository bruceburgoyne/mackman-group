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
