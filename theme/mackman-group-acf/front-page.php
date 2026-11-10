<?php
/**
 * Home page (the static front page set in Settings > Reading).
 *
 * Sections, in order, each hidden when empty:
 *
 * 1. Hero: label, H1 with an italic gold ending, text, button, video, image
 * 2. Featured case study: chosen in the editor, details from the case study
 * 3. Who we are: heading and running text
 * 4. Our brands: a card for each division, linking to its page
 * 5. Standards: sectors and quality standards, on green
 * 6. Quote
 * 7. Latest news: the newest articles, automatically
 * 8. Call to action
 *
 * Follows the Trust & Authority pattern in docs/design-system.md:
 * credibility, what we offer, proof, then a clear next step.
 *
 * @package mackman-group-acf
 */

get_header();

get_template_part( 'template-parts/sections/hero', null, array( 'data' => mg_field( 'hero' ) ) );
get_template_part( 'template-parts/sections/featured-case', null, array( 'data' => mg_field( 'featured_case' ) ) );
get_template_part( 'template-parts/sections/text-split', null, array( 'data' => mg_field( 'about' ) ) );
get_template_part(
	'template-parts/sections/features',
	null,
	array(
		'data'    => mg_field( 'divisions' ),
		'columns' => 2,
	)
);
get_template_part( 'template-parts/sections/standards', null, array( 'data' => mg_field( 'standards' ) ) );
get_template_part( 'template-parts/sections/quote', null, array( 'data' => mg_field( 'testimonial' ) ) );
get_template_part( 'template-parts/sections/news', null, array( 'data' => mg_field( 'news' ) ) );
get_template_part( 'template-parts/sections/cta', null, array( 'data' => mg_field( 'cta' ) ) );

get_footer();
