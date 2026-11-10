<?php
/**
 * A team member's profile.
 *
 * 1. Opening (green): breadcrumb, role, name (H1), introduction, LinkedIn
 *    link and photo (the featured image)
 * 2. Their quote (mint)
 * 3. Profile
 * 4. Key areas of expertise (white)
 * 5. Client testimonials
 * 6. Call to action (green), from Site Settings > Case studies
 *
 * Each section is hidden when its field is empty. Profiles not yet moved
 * into the fields show their original block content below the opening.
 *
 * @package mackman-group-acf
 */

get_header();

while ( have_posts() ) :
	the_post();
	$mg_person    = mg_team_member();
	$mg_intro     = mg_field( 'intro' );
	$mg_quote     = mg_field( 'quote' );
	$mg_profile   = mg_field( 'profile' );
	$mg_expertise = mg_lines( mg_field( 'expertise' ) );
	$mg_reviews   = array_filter(
		(array) mg_field( 'testimonials' ),
		function ( $item ) {
			return ! empty( $item['quote'] );
		}
	);
	$mg_linkedin  = mg_field( 'linkedin' );
	$mg_team_page = mg_team_page_id();
	?>
	<article <?php post_class( 'profile' ); ?>>
		<header class="intro surface-green<?php echo has_post_thumbnail() ? ' intro--has-image' : ''; ?>">
			<div class="container intro__inner">
				<div class="intro__text">
					<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'mackman-group-acf' ); ?>">
						<ol>
							<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'mackman-group-acf' ); ?></a></li>
							<?php if ( $mg_team_page ) : ?>
								<li><a href="<?php echo esc_url( get_permalink( $mg_team_page ) ); ?>"><?php echo esc_html( get_the_title( $mg_team_page ) ); ?></a></li>
							<?php endif; ?>
							<li><span aria-current="page"><?php echo esc_html( $mg_person['name'] ); ?></span></li>
						</ol>
					</nav>
					<?php if ( $mg_person['role'] ) : ?>
						<p class="kicker kicker--ruled"><?php echo esc_html( $mg_person['role'] ); ?></p>
					<?php endif; ?>
					<h1 class="intro__title"><?php echo esc_html( $mg_person['name'] ); ?></h1>
					<?php if ( $mg_intro ) : ?>
						<div class="intro__body soft"><p><?php echo mg_text( $mg_intro ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p></div>
					<?php endif; ?>
					<?php if ( $mg_linkedin ) : ?>
						<p>
							<a class="text-link" href="<?php echo esc_url( $mg_linkedin ); ?>">
								<?php
								/* translators: %s: the team member's name */
								echo esc_html( sprintf( __( 'Connect with %s on LinkedIn', 'mackman-group-acf' ), $mg_person['name'] ) );
								?>
								<span class="arrow" aria-hidden="true">&rarr;</span>
							</a>
						</p>
					<?php endif; ?>
				</div>
				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="intro__media profile__photo">
						<?php
						the_post_thumbnail(
							'large',
							array(
								'class'         => 'intro__img',
								'alt'           => '',
								'loading'       => 'eager',
								'fetchpriority' => 'high',
								'sizes'         => '(min-width: 60rem) 40vw, 100vw',
							)
						);
						?>
					</figure>
				<?php endif; ?>
			</div>
		</header>

		<?php
		if ( $mg_quote ) {
			get_template_part(
				'template-parts/sections/quote',
				null,
				array(
					'data' => array(
						'quote' => $mg_quote,
						'name'  => $mg_person['name'],
					),
				)
			);
		}

		if ( $mg_profile ) {
			get_template_part(
				'template-parts/sections/text-split',
				null,
				array(
					'data' => array(
						'heading' => __( 'Profile', 'mackman-group-acf' ),
						'text'    => $mg_profile,
					),
				)
			);
		}
		?>

		<?php if ( $mg_expertise ) : ?>
			<section class="section surface-white" aria-labelledby="profile-expertise">
				<div class="container">
					<h2 id="profile-expertise"><?php esc_html_e( 'Key areas of expertise', 'mackman-group-acf' ); ?></h2>
					<ul class="expertise">
						<?php foreach ( $mg_expertise as $mg_item ) : ?>
							<li><?php echo esc_html( $mg_item ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $mg_reviews ) : ?>
			<section class="section" aria-labelledby="profile-testimonials">
				<div class="container">
					<h2 id="profile-testimonials"><?php esc_html_e( 'Client testimonials', 'mackman-group-acf' ); ?></h2>
					<div class="testimonials">
						<?php foreach ( $mg_reviews as $mg_review ) : ?>
							<figure class="quote">
								<blockquote><p><?php echo esc_html( $mg_review['quote'] ); ?></p></blockquote>
								<?php if ( ! empty( $mg_review['source'] ) ) : ?>
									<figcaption><cite><?php echo esc_html( $mg_review['source'] ); ?></cite></figcaption>
								<?php endif; ?>
							</figure>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( ! $mg_profile && trim( get_the_content() ) ) : ?>
			<div class="section">
				<div class="container article-body"><?php the_content(); ?></div>
			</div>
		<?php endif; ?>
	</article>
	<?php
	get_template_part( 'template-parts/sections/cta', null, array( 'data' => mg_option( 'case_study_cta' ) ) );
endwhile;

get_footer();
