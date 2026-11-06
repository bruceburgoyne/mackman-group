<?php
/**
 * Default page template: page header plus a single body text field.
 *
 * Pages without their own template yet (most of them, while the build is
 * in progress) show here with just their header until fields are filled.
 *
 * @package mackman-group-acf
 */

get_header();
get_template_part( 'template-parts/page-header' );

$mg_body = mg_field( 'body' );

if ( $mg_body ) :
	?>
	<section class="section">
		<div class="container prose"><?php echo wp_kses_post( $mg_body ); ?></div>
	</section>
	<?php
endif;

get_footer();
