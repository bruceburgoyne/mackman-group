<?php
/**
 * Site footer. Content comes from Site Settings and the Footer links menu.
 *
 * @package mackman-group-acf
 */

$mg_phone   = mg_option( 'phone' );
$mg_email   = mg_option( 'email' );
$mg_address = mg_option( 'address' );
$mg_social  = mg_option( 'social_links' );
$mg_footer  = mg_option( 'footer_text' );
?>
</main>

<footer class="site-footer surface-green">
	<div class="container">
		<div class="site-footer__grid">
			<div class="site-footer__brand">
				<img class="site-footer__logo" src="<?php echo esc_url( mg_asset( 'images/logo-stacked-reversed.svg' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="217" height="116" loading="lazy">
				<?php if ( $mg_address ) : ?>
					<address class="soft"><?php echo mg_text( $mg_address ); // phpcs:ignore WordPress.Security.EscapeOutput ?></address>
				<?php endif; ?>
			</div>

			<?php if ( $mg_phone || $mg_email ) : ?>
				<div>
					<h2 class="kicker"><?php esc_html_e( 'Contact', 'mackman-group-acf' ); ?></h2>
					<ul class="site-footer__list">
						<?php if ( $mg_phone ) : ?>
							<li><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $mg_phone ) ); ?>"><?php echo esc_html( $mg_phone ); ?></a></li>
						<?php endif; ?>
						<?php if ( $mg_email ) : ?>
							<li><a href="mailto:<?php echo esc_attr( antispambot( $mg_email ) ); ?>"><?php echo esc_html( antispambot( $mg_email ) ); ?></a></li>
						<?php endif; ?>
					</ul>
				</div>
			<?php endif; ?>

			<?php if ( has_nav_menu( 'footer' ) ) : ?>
				<nav aria-labelledby="footer-links-title">
					<h2 class="kicker" id="footer-links-title"><?php esc_html_e( 'Links', 'mackman-group-acf' ); ?></h2>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'container'      => false,
							'menu_class'     => 'site-footer__list',
							'depth'          => 1,
						)
					);
					?>
				</nav>
			<?php endif; ?>

			<?php if ( $mg_social ) : ?>
				<div>
					<h2 class="kicker"><?php esc_html_e( 'Follow us', 'mackman-group-acf' ); ?></h2>
					<ul class="site-footer__list">
						<?php foreach ( $mg_social as $mg_item ) : ?>
							<?php if ( ! empty( $mg_item['url'] ) ) : ?>
								<li><a href="<?php echo esc_url( $mg_item['url'] ); ?>" rel="noopener" target="_blank"><?php echo esc_html( $mg_item['label'] ? $mg_item['label'] : $mg_item['url'] ); ?></a></li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>

		<div class="site-footer__base">
			<?php if ( $mg_footer ) : ?>
				<?php echo mg_text( $mg_footer ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php else : ?>
				&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>
			<?php endif; ?>
		</div>
	</div>
</footer>
</div><!-- .site-wrap -->

<?php wp_footer(); ?>
</body>
</html>
