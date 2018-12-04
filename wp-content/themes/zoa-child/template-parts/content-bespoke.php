<div id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
	<section id="bespokeIntro" class="section align_center">
		<div class="row flex-justify-center">
			<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
				<div class="heading-title">
					<h3 class="heading notera">Our Bespoke</h3>
					<p class="sub_heading">Message from our designer</p>
				</div>
				
				<div class="intro_text">
					<?php the_content(); ?>
				</div>
			</div>
		</div>
	</section>
	<section id="bespokeType" class="section no_pad_section align_center two_devided_section full_wide_section">
		<div class="float_title heading-title">
			<!--<p class="sub_heading kings">Choose your</p>-->
			<h3 class="heading notera">Bespoke Menu</h3>
		</div>
		<div class="row flex-justify-center">
			<?php
			$args = array(
    'post_type'      => 'page',
    'posts_per_page' => -1,
    'post_parent'    => $post->ID,
    'order'          => 'ASC',
    'orderby'        => 'menu_order'
 );


$parent = new WP_Query( $args );

if ( $parent->have_posts() ) : ?>
			<?php while ( $parent->have_posts() ) : $parent->the_post(); ?>
			<div class="col-lg-6 col-md-6 col-xs-12 bg_image" style="background-image: url(<?php the_field('bespoke_feature_image'); ?>);">
				<div class="half-mid-contain">
					<div class="heading-title">
						<h4 class="heading"><?php the_title(); ?></h4>
						<p class="heading_subtitle crimson italic"><?php the_field('en_subtitle'); ?></p>
					</div>
					<div class="desc">
						<p><?php the_field('summary_excerpt'); ?></p>
					</div>
					<a href="<?php the_permalink(); ?>" class="btn btn__small btn--inverse">See details</a>
				</div>
			</div>
			<?php endwhile; ?>
			<?php endif; wp_reset_postdata(); ?>
		</div>
	</section>
	<section id="bespokeFaq" class="section">
		<div class="mid-container">
				<div class="heading-title align_center">
					<h3 class="heading notera">Faq</h3>
					<p class="sub_heading">frequency asked questions</p>
				</div>
				<?php if( have_rows('faq') ): ?>
				<div class="faq-wrapper">
					<?php while( have_rows('faq') ): the_row();
					// vars
		$question = get_sub_field('question');
		$answer = get_sub_field('answer');
					?>
					<?php if ($question && $answer ): ?>
					<div class="faq">
						<?php echo '<h3 class="faq__title">'.$question.'</h3>'; ?>
						<?php echo '<p class="faq__paragraph">'.$answer.'</p>'; ?>
					</div>
					<?php endif; ?>
					<?php endwhile; ?>
					
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php get_template_part( 'template-parts/common-section', 'appointment' ); ?>
</div>
