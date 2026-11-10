<?php
/**
 * Template Name: Content page
 *
 * For pages that do not follow the division pattern: About, Values,
 * B Corp, Accreditations, Trust, Contact, Newsletter and the team. The
 * intro (green, with the H1) is always first; editors then add sections
 * in any order from the existing components:
 *
 * - text:    label and heading on the left, running text on the right
 * - media:   text beside an image, with an optional film link
 * - cards:   the hairline card grid (services, accreditations, awards)
 * - quote:   one quotation on mint
 * - team:    every published team member, linking to their profile
 * - form:    the contact form or newsletter sign-up, with contact details
 * - cta:     call to action band on green
 * - closing: image beside text and a button, on white
 *
 * @package mackman-group-acf
 */

get_header();

get_template_part( 'template-parts/sections/intro', null, array( 'data' => mg_field( 'intro' ) ) );

$mg_sections = (array) mg_field( 'sections' );
$mg_video    = '';

foreach ( $mg_sections as $mg_section ) {
	if ( empty( $mg_section['acf_fc_layout'] ) ) {
		continue;
	}

	switch ( $mg_section['acf_fc_layout'] ) {
		case 'text':
			get_template_part( 'template-parts/sections/text-split', null, array( 'data' => $mg_section ) );
			break;

		case 'media':
			get_template_part(
				'template-parts/sections/intro',
				null,
				array(
					'data'    => $mg_section,
					'level'   => 2,
					'surface' => ! empty( $mg_section['surface'] ) ? $mg_section['surface'] : 'surface-base',
				)
			);
			if ( ! $mg_video && ! empty( $mg_section['video_url'] ) && mg_youtube_id( $mg_section['video_url'] ) ) {
				$mg_video = ! empty( $mg_section['video_label'] ) ? $mg_section['video_label'] : __( 'Video', 'mackman-group-acf' );
			}
			break;

		case 'cards':
			get_template_part(
				'template-parts/sections/features',
				null,
				array(
					'data'    => $mg_section,
					'surface' => $mg_section['surface'] ?? '',
					'columns' => $mg_section['columns'] ?? 3,
				)
			);
			break;

		case 'quote':
			get_template_part( 'template-parts/sections/quote', null, array( 'data' => $mg_section ) );
			break;

		case 'team':
			get_template_part( 'template-parts/sections/team', null, array( 'data' => $mg_section ) );
			break;

		case 'form':
			get_template_part( 'template-parts/sections/form', null, array( 'data' => $mg_section ) );
			break;

		case 'cta':
			get_template_part( 'template-parts/sections/cta', null, array( 'data' => $mg_section ) );
			break;

		case 'closing':
			get_template_part( 'template-parts/sections/closing', null, array( 'data' => $mg_section ) );
			break;
	}
}

if ( $mg_video ) {
	get_template_part( 'template-parts/video-dialog', null, array( 'label' => $mg_video ) );
}

get_footer();
