<?php
/**
 * Form: heading and text, optionally the contact details from Site
 * Settings, beside a form panel on white. The form is the Contact Form 7
 * shortcode given, or the newsletter sign-up (template-parts/forms).
 *
 * $args['data'] is an ACF group with: kicker, heading, text, details,
 * form (contact or newsletter), shortcode.
 *
 * @package mackman-group-acf
 */

$mg_data = isset( $args['data'] ) && is_array( $args['data'] ) ? $args['data'] : array();
$mg_type = ! empty( $mg_data['form'] ) ? $mg_data['form'] : 'contact';

if ( 'contact' === $mg_type && empty( $mg_data['shortcode'] ) ) {
	return;
}

$mg_phone   = ! empty( $mg_data['details'] ) ? mg_option( 'phone' ) : '';
$mg_email   = ! empty( $mg_data['details'] ) ? mg_option( 'email' ) : '';
$mg_address = ! empty( $mg_data['details'] ) ? mg_option( 'address' ) : '';
$mg_id      = 'form-' . wp_unique_id();
$mg_label   = ! empty( $mg_data['heading'] ) ? $mg_data['heading'] : ( 'newsletter' === $mg_type ? __( 'Newsletter sign-up', 'mackman-group-acf' ) : __( 'Contact form', 'mackman-group-acf' ) );
?>
<section class="section form-section" aria-labelledby="<?php echo esc_attr( $mg_id ); ?>">
	<div class="container split form-section__inner">
		<div class="form-section__text">
			<?php if ( ! empty( $mg_data['kicker'] ) ) : ?>
				<p class="kicker"><?php echo esc_html( $mg_data['kicker'] ); ?></p>
			<?php endif; ?>
			<h2 id="<?php echo esc_attr( $mg_id ); ?>"<?php echo empty( $mg_data['heading'] ) ? ' class="screen-reader-text"' : ''; ?>><?php echo esc_html( $mg_label ); ?></h2>
			<?php if ( ! empty( $mg_data['text'] ) ) : ?>
				<div class="prose soft"><?php echo wp_kses_post( $mg_data['text'] ); ?></div>
			<?php endif; ?>

			<?php if ( $mg_address || $mg_phone || $mg_email ) : ?>
				<dl class="contact-details">
					<?php if ( $mg_address ) : ?>
						<div>
							<dt><?php esc_html_e( 'Address', 'mackman-group-acf' ); ?></dt>
							<dd><address><?php echo mg_text( $mg_address ); // phpcs:ignore WordPress.Security.EscapeOutput ?></address></dd>
						</div>
					<?php endif; ?>
					<?php if ( $mg_phone ) : ?>
						<div>
							<dt><?php esc_html_e( 'Telephone', 'mackman-group-acf' ); ?></dt>
							<dd><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $mg_phone ) ); ?>"><?php echo esc_html( $mg_phone ); ?></a></dd>
						</div>
					<?php endif; ?>
					<?php if ( $mg_email ) : ?>
						<div>
							<dt><?php esc_html_e( 'Email', 'mackman-group-acf' ); ?></dt>
							<dd><a href="mailto:<?php echo esc_attr( antispambot( $mg_email ) ); ?>"><?php echo esc_html( antispambot( $mg_email ) ); ?></a></dd>
						</div>
					<?php endif; ?>
				</dl>
			<?php endif; ?>
		</div>

		<div class="form-panel surface-white">
			<?php
			if ( 'newsletter' === $mg_type ) {
				get_template_part( 'template-parts/forms/newsletter' );
			} else {
				echo do_shortcode( wp_kses_post( $mg_data['shortcode'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput
			}
			?>
		</div>
	</div>
</section>
