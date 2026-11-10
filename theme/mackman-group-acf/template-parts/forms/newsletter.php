<?php
/**
 * Newsletter sign-up: posts straight to Mailchimp, as the embed on the
 * old Newsletter page did, but without Mailchimp's script (which needed
 * jQuery and loaded from a third party on page load). Mailchimp shows its
 * own confirmation page in a new tab.
 *
 * The audience, group and tag IDs are copied from the live embed on
 * 10 November 2026. If the audience changes, copy the new form action and
 * field names from Mailchimp's embedded form code.
 *
 * @package mackman-group-acf
 */

$mg_action = 'https://mackmangroup.us7.list-manage.com/subscribe/post?u=01b22ee0c72b746ed3ec1771a&id=120b2f8f96&f_id=00b2ace4f0';
?>
<form class="newsletter-form" action="<?php echo esc_url( $mg_action ); ?>" method="post" target="_blank">
	<div class="field">
		<label for="mce-EMAIL"><?php esc_html_e( 'Email address (required)', 'mackman-group-acf' ); ?></label>
		<input type="email" name="EMAIL" id="mce-EMAIL" autocomplete="email" required>
	</div>
	<div class="field">
		<label for="mce-FNAME"><?php esc_html_e( 'First name (optional)', 'mackman-group-acf' ); ?></label>
		<input type="text" name="FNAME" id="mce-FNAME" autocomplete="given-name">
	</div>
	<div class="field">
		<label for="mce-MMERGE2"><?php esc_html_e( 'Last name (optional)', 'mackman-group-acf' ); ?></label>
		<input type="text" name="MMERGE2" id="mce-MMERGE2" autocomplete="family-name">
	</div>
	<div class="field field--check">
		<input type="checkbox" name="group[72553][256]" id="mce-optin" value="" required>
		<label for="mce-optin"><?php esc_html_e( 'I confirm that I am happy to periodically receive the Mackman newsletter (required)', 'mackman-group-acf' ); ?></label>
	</div>
	<input type="hidden" name="tags" value="8979749">
	<div class="newsletter-form__trap" aria-hidden="true">
		<input type="text" name="b_01b22ee0c72b746ed3ec1771a_120b2f8f96" tabindex="-1" value="" autocomplete="off">
	</div>
	<p class="field__hint">
		<?php
		printf(
			/* translators: %s: link to the privacy policy */
			esc_html__( 'Your details go to Mailchimp, who send the newsletter for us. See our %s.', 'mackman-group-acf' ),
			'<a href="' . esc_url( get_privacy_policy_url() ) . '">' . esc_html__( 'privacy and cookie policy', 'mackman-group-acf' ) . '</a>'
		);
		?>
	</p>
	<button class="button" type="submit"><?php esc_html_e( 'Subscribe', 'mackman-group-acf' ); ?> <span class="arrow" aria-hidden="true">&rarr;</span></button>
</form>
