<?php
/**
 * Team: a card for each published team member, with their photo, role and
 * name, linking to their profile. Hairline card grid.
 *
 * $args['data'] is an ACF group with: kicker, heading, intro. With no
 * heading, the section is labelled "Our team" for screen readers.
 *
 * @package mackman-group-acf
 */

$mg_data    = isset( $args['data'] ) && is_array( $args['data'] ) ? $args['data'] : array();
$mg_members = mg_team_members();

if ( ! $mg_members ) {
	return;
}

$mg_id = 'team-' . wp_unique_id();
?>
<section class="section team"<?php echo ! empty( $mg_data['heading'] ) ? ' aria-labelledby="' . esc_attr( $mg_id ) . '"' : ' aria-label="' . esc_attr__( 'Our team', 'mackman-group-acf' ) . '"'; ?>>
	<div class="container">
		<?php if ( ! empty( $mg_data['heading'] ) ) : ?>
			<div class="section-head">
				<?php if ( ! empty( $mg_data['kicker'] ) ) : ?>
					<p class="kicker"><?php echo esc_html( $mg_data['kicker'] ); ?></p>
				<?php endif; ?>
				<h2 id="<?php echo esc_attr( $mg_id ); ?>"><?php echo esc_html( $mg_data['heading'] ); ?></h2>
				<?php if ( ! empty( $mg_data['intro'] ) ) : ?>
					<p class="lead"><?php echo mg_text( $mg_data['intro'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="card-grid"><div class="grid grid--3">
			<?php foreach ( $mg_members as $mg_member ) : ?>
				<?php $mg_person = mg_team_member( $mg_member->ID ); ?>
				<article class="card team-card">
					<?php if ( has_post_thumbnail( $mg_member ) ) : ?>
						<div class="card__media team-card__media">
							<?php
							echo get_the_post_thumbnail(
								$mg_member,
								'medium_large',
								array(
									'alt'   => '',
									'sizes' => '(min-width: 64rem) 25vw, (min-width: 48rem) 50vw, 100vw',
								)
							);
							?>
						</div>
					<?php endif; ?>
					<?php if ( $mg_person['role'] ) : ?>
						<p class="kicker"><?php echo esc_html( $mg_person['role'] ); ?></p>
					<?php endif; ?>
					<h3 class="card__title"><a href="<?php echo esc_url( get_permalink( $mg_member ) ); ?>"><?php echo esc_html( $mg_person['name'] ); ?></a></h3>
					<span class="card__more" aria-hidden="true"><?php esc_html_e( 'View profile', 'mackman-group-acf' ); ?><span class="arrow">&rarr;</span></span>
				</article>
			<?php endforeach; ?>
		</div></div>
	</div>
</section>
