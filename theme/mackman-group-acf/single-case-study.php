<?php
/**
 * A single case study.
 *
 * A story in a fixed order, so every case study reads the same way. Each
 * section is hidden when its fields are empty.
 *
 * 1. Opening (green): breadcrumb, type of work, H1, overview panel, lead
 *    and summary; then the film or image, and links to the stages
 * 2. Results: evidenced figures
 * 3. The challenge, ending with the question the work had to answer
 * 4. The work: one to four stages of the approach (Understand, Shape,
 *    Deliver, Grow) joined by a gold line
 * 5. Testimonial (mint), shown to visitors only once approved
 * 6. Outcome
 * 7. Who was involved: the parts of the Group and what each did
 * 8. More case studies (can be hidden per case study)
 * 9. Call to action (green), falling back to Site Settings
 *
 * Case studies not yet moved into these fields show their original block
 * content in the article layout (single.php) instead.
 *
 * @package mackman-group-acf
 */

if ( ! mg_case_study_is_built( get_queried_object_id() ) ) {
	require get_template_directory() . '/single.php';
	return;
}

get_header();

while ( have_posts() ) :
	the_post();
	$mg_stages = mg_case_study_stages();
	?>
	<article <?php post_class( 'case-study' ); ?>>
		<?php
		get_template_part( 'template-parts/case-study/opening', null, array( 'stages' => $mg_stages ) );
		get_template_part( 'template-parts/case-study/results' );
		get_template_part( 'template-parts/case-study/challenge' );

		if ( $mg_stages ) {
			echo '<div class="cs-work">';
			foreach ( $mg_stages as $mg_i => $mg_stage ) {
				get_template_part(
					'template-parts/case-study/stage',
					null,
					array(
						'stage' => $mg_stage,
						'index' => $mg_i,
					)
				);
			}
			echo '</div>';
		}

		// The testimonial is only public once approved. Signed-in editors see
		// it beforehand, marked, so they can check the layout.
		$mg_quote = mg_field( 'testimonial' );
		if ( ! empty( $mg_quote['quote'] ) ) {
			if ( ! empty( $mg_quote['approved'] ) ) {
				get_template_part( 'template-parts/sections/quote', null, array( 'data' => $mg_quote ) );
			} elseif ( current_user_can( 'edit_post', get_the_ID() ) ) {
				get_template_part(
					'template-parts/sections/quote',
					null,
					array(
						'data' => $mg_quote,
						'note' => __( 'Awaiting approval: visitors do not see this quote until Approved is ticked in the Testimonial tab.', 'mackman-group-acf' ),
					)
				);
			}
		}

		get_template_part( 'template-parts/case-study/outcome' );
		get_template_part( 'template-parts/case-study/team' );
		?>
	</article>
	<?php
	// "More case studies" can be hidden per case study (End of page tab).
	if ( ! mg_field( 'hide_related' ) ) {
		get_template_part( 'template-parts/case-study/related' );
	}

	$mg_cta = mg_field( 'cta' );
	if ( empty( $mg_cta['heading'] ) ) {
		$mg_cta = mg_option( 'case_study_cta' );
	}
	get_template_part( 'template-parts/sections/cta', null, array( 'data' => $mg_cta ) );
endwhile;

get_footer();
