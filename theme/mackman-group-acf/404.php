<?php
/**
 * Page not found.
 *
 * @package mackman-group-acf
 */

get_header();
get_template_part( 'template-parts/page-header', null, array( 'title' => __( 'Page not found', 'mackman-group-acf' ) ) );
?>
<section class="section">
	<div class="container prose">
		<p class="lead"><?php esc_html_e( 'The page you were looking for has moved or no longer exists.', 'mackman-group-acf' ); ?></p>
		<p><a class="button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go to the home page', 'mackman-group-acf' ); ?> <span class="arrow" aria-hidden="true">&rarr;</span></a></p>
	</div>
</section>
<?php
get_footer();
