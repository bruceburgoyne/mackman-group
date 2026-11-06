<?php
/**
 * Site footer. Content comes from Site Settings and two menus (Our brands,
 * Footer links).
 *
 * 1. A white strip of logos: professional bodies, then accreditations and awards.
 * 2. The green footer: logo and address, contact, our brands, useful links.
 * 3. The copyright line (current year, automatically), small print and a
 *    certification logo.
 *
 * @package mackman-group-acf
 */

$mg_phone      = mg_option( 'phone' );
$mg_email      = mg_option( 'email' );
$mg_address    = mg_option( 'address' );
$mg_social     = mg_option( 'social_links' );
$mg_newsletter = mg_option( 'newsletter' );
$mg_footer     = mg_option( 'footer_text' );
$mg_certs      = mg_option( 'certifications' );
$mg_logo_rows  = array(
	array(
		'id'    => 'footer-bodies',
		'title' => __( 'Professional bodies', 'mackman-group-acf' ),
		'items' => mg_option( 'professional_bodies' ),
	),
	array(
		'id'    => 'footer-awards',
		'title' => __( 'Accreditations and awards', 'mackman-group-acf' ),
		'items' => mg_option( 'accreditations' ),
	),
);
$mg_logo_rows  = array_filter(
	$mg_logo_rows,
	function ( $row ) {
		return ! empty( $row['items'] );
	}
);
?>
</main>

<?php if ( $mg_logo_rows ) : ?>
	<section class="logo-strip surface-white" aria-label="<?php esc_attr_e( 'Memberships, accreditations and awards', 'mackman-group-acf' ); ?>">
		<div class="container">
			<?php foreach ( $mg_logo_rows as $mg_row ) : ?>
				<div class="logo-strip__group">
					<h2 class="kicker logo-strip__title" id="<?php echo esc_attr( $mg_row['id'] ); ?>"><?php echo esc_html( $mg_row['title'] ); ?></h2>
					<ul class="logo-strip__list" aria-labelledby="<?php echo esc_attr( $mg_row['id'] ); ?>">
						<?php foreach ( $mg_row['items'] as $mg_item ) : ?>
							<?php
							if ( empty( $mg_item['logo'] ) ) {
								continue;
							}
							$mg_img = mg_image(
								$mg_item['logo'],
								'medium',
								'logo-strip__img',
								array(
									'alt'     => $mg_item['name'] ?? '',
									'loading' => 'lazy',
								)
							);
							?>
							<li class="logo-strip__item">
								<?php if ( ! empty( $mg_item['url'] ) ) : ?>
									<a class="logo-strip__link" href="<?php echo esc_url( $mg_item['url'] ); ?>"><?php echo $mg_img; // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
								<?php else : ?>
									<?php echo $mg_img; // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>
		</div>
	</section>
<?php endif; ?>

<footer class="site-footer surface-green">
	<div class="container">
		<div class="site-footer__grid">
			<div class="site-footer__brand">
				<img class="site-footer__logo" src="<?php echo esc_url( mg_asset( 'images/logo-stacked-reversed.svg' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="217" height="116" loading="lazy">
				<?php if ( $mg_address ) : ?>
					<h2 class="kicker"><?php esc_html_e( 'Head office', 'mackman-group-acf' ); ?></h2>
					<address class="soft"><?php echo mg_text( $mg_address ); // phpcs:ignore WordPress.Security.EscapeOutput ?></address>
				<?php endif; ?>
			</div>

			<?php if ( $mg_phone || $mg_email || ! empty( $mg_newsletter['url'] ) ) : ?>
				<div>
					<h2 class="kicker"><?php esc_html_e( 'Contact', 'mackman-group-acf' ); ?></h2>
					<ul class="site-footer__list">
						<?php if ( $mg_phone ) : ?>
							<li><span class="site-footer__label"><?php esc_html_e( 'Telephone', 'mackman-group-acf' ); ?></span><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $mg_phone ) ); ?>"><?php echo esc_html( $mg_phone ); ?></a></li>
						<?php endif; ?>
						<?php if ( $mg_email ) : ?>
							<li><span class="site-footer__label"><?php esc_html_e( 'Email', 'mackman-group-acf' ); ?></span><a href="mailto:<?php echo esc_attr( antispambot( $mg_email ) ); ?>"><?php echo esc_html( antispambot( $mg_email ) ); ?></a></li>
						<?php endif; ?>
					</ul>
					<?php if ( ! empty( $mg_newsletter['url'] ) ) : ?>
						<div class="site-footer__newsletter"><?php echo mg_link( $mg_newsletter, 'button button--gold', true ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( has_nav_menu( 'brands' ) ) : ?>
				<nav aria-labelledby="footer-brands-title">
					<h2 class="kicker" id="footer-brands-title"><?php esc_html_e( 'Our brands', 'mackman-group-acf' ); ?></h2>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'brands',
							'container'      => false,
							'menu_class'     => 'site-footer__list',
							'depth'          => 1,
						)
					);
					?>
				</nav>
			<?php endif; ?>

			<?php if ( has_nav_menu( 'footer' ) ) : ?>
				<nav aria-labelledby="footer-links-title">
					<h2 class="kicker" id="footer-links-title"><?php esc_html_e( 'Useful links', 'mackman-group-acf' ); ?></h2>
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
								<li><a href="<?php echo esc_url( $mg_item['url'] ); ?>"><?php echo esc_html( $mg_item['label'] ? $mg_item['label'] : $mg_item['url'] ); ?></a></li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>

		<div class="site-footer__base">
			<p class="site-footer__small">
				&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'mackman-group-acf' ); ?>
				<?php if ( $mg_footer ) : ?>
					<br><?php echo mg_text( $mg_footer ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php endif; ?>
			</p>
			<?php if ( $mg_certs ) : ?>
				<ul class="site-footer__certs" aria-label="<?php esc_attr_e( 'Certifications', 'mackman-group-acf' ); ?>">
					<?php foreach ( $mg_certs as $mg_cert ) : ?>
						<?php
						if ( empty( $mg_cert['logo'] ) ) {
							continue;
						}
						$mg_img = mg_image( $mg_cert['logo'], 'medium', 'site-footer__cert', array( 'alt' => $mg_cert['name'] ?? '', 'loading' => 'lazy' ) );
						?>
						<li>
							<?php if ( ! empty( $mg_cert['url'] ) ) : ?>
								<a href="<?php echo esc_url( $mg_cert['url'] ); ?>"><?php echo $mg_img; // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
							<?php else : ?>
								<?php echo $mg_img; // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</footer>
</div><!-- .site-body -->
</div><!-- .site-wrap -->

<?php wp_footer(); ?>
</body>
</html>
