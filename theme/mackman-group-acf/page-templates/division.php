<?php
/**
 * Template Name: Division
 *
 * A division page (Mackman, Mackman Research, Red Kite HR, Training and
 * Coaching). Sections, in order, each hidden when empty:
 *
 * 1. Intro: label, H1 (the page title unless set), introduction, image, on green
 * 2. Overview: label, H2, text and image, on white
 * 3. Services: section heading and a grid of service cards
 * 4. Testimonial
 * 5. Closing: image, heading, text and contact button
 *
 * @package mackman-group-acf
 */

get_header();

get_template_part( 'template-parts/sections/intro', null, array( 'data' => mg_field( 'intro' ) ) );
get_template_part(
	'template-parts/sections/intro',
	null,
	array(
		'data'    => mg_field( 'overview' ),
		'level'   => 2,
		'surface' => 'surface-white',
	)
);
get_template_part( 'template-parts/sections/features', null, array( 'data' => mg_field( 'services' ) ) );
get_template_part( 'template-parts/sections/quote', null, array( 'data' => mg_field( 'testimonial' ) ) );
get_template_part( 'template-parts/sections/closing', null, array( 'data' => mg_field( 'closing' ) ) );

get_footer();
