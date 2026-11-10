<?php
/**
 * Home page (the static front page set in Settings > Reading).
 *
 * Sections, in order, each hidden when empty:
 *
 * 1. Hero: label, H1 with an italic gold ending, text, button, video, image
 * 2. Credentials: a short strip of figures (B Corp and memberships)
 * 3. Featured case study: chosen in the editor, details from the case study
 * 4. Capabilities: a numbered card for each division, linking to its page
 * 5. Why one firm: heading and running text, on white
 * 6. Method: numbered steps, on mint
 * 7. Standards: sectors and quality standards, on green
 * 8. Quote
 * 9. FAQs
 * 10. Latest news: the newest articles, automatically
 * 11. Call to action
 *
 * Follows the Trust & Authority pattern in docs/design-system.md:
 * credibility, what we offer, proof, then a clear next step.
 *
 * @package mackman-group-acf
 */

get_header();

get_template_part( 'template-parts/sections/hero', null, array( 'data' => mg_field( 'hero' ) ) );
get_template_part( 'template-parts/sections/credentials', null, array( 'data' => mg_field( 'credentials' ) ) );
get_template_part( 'template-parts/sections/featured-case', null, array( 'data' => mg_field( 'featured_case' ) ) );

// Three capabilities sit in three columns; four or more in two.
$mg_divisions = (array) mg_field( 'divisions' );
get_template_part(
	'template-parts/sections/features',
	null,
	array(
		'data'    => $mg_divisions,
		'columns' => ( ! empty( $mg_divisions['items'] ) && 3 === count( $mg_divisions['items'] ) ) ? 3 : 2,
	)
);
get_template_part( 'template-parts/sections/text-split', null, array( 'data' => array_merge( (array) mg_field( 'about' ), array( 'surface' => 'surface-white' ) ) ) );
get_template_part( 'template-parts/sections/method', null, array( 'data' => mg_field( 'method' ) ) );
get_template_part( 'template-parts/sections/standards', null, array( 'data' => mg_field( 'standards' ) ) );
get_template_part( 'template-parts/sections/quote', null, array( 'data' => mg_field( 'testimonial' ) ) );
get_template_part( 'template-parts/sections/faq', null, array( 'data' => mg_field( 'faqs' ) ) );
get_template_part( 'template-parts/sections/news', null, array( 'data' => mg_field( 'news' ) ) );
get_template_part( 'template-parts/sections/cta', null, array( 'data' => mg_field( 'cta' ) ) );

get_footer();
