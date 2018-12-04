<?php
$c_header = zoa_page_header_slug();
$cat = get_the_category();
$cat = $cat[0];
$pressinfo = get_field('published_date');
$pressinfo_default = $pressinfo;
					$magName = get_the_title();
/*If WPML is activated if (ICL_LANGUAGE_CODE == "ja")*/
					if (get_locale() == 'ja') {
						if ($pressinfo['month'] == 'January') {
						$pressinfo['month'] = '1月';
						} else if ($pressinfo['month'] == 'February') {
							$pressinfo['month'] = '2月';
						} else if ($pressinfo['month'] == 'March') {
							$pressinfo['month'] = '3月';
						} else if ($pressinfo['month'] == 'April') {
							$pressinfo['month'] = '4月';
						} else if ($pressinfo['month'] == 'May') {
							$pressinfo['month'] = '5月';
						} else if ($pressinfo['month'] == 'June') {
							$pressinfo['month'] = '6月';
						} else if ($pressinfo['month'] == 'July') {
							$pressinfo['month'] = '7月';
						} else if ($pressinfo['month'] == 'August') {
							$pressinfo['month'] = '8月';
						} else if ($pressinfo['month'] == 'September') {
							$pressinfo['month'] = '9月';
						} else if ($pressinfo['month'] == 'October') {
							$pressinfo['month'] = '10月';
						} else if ($pressinfo['month'] == 'November') {
							$pressinfo['month'] = '11月';
						} else if ($pressinfo['month'] == 'December') {
							$pressinfo['month'] = '12月';
						} else if ($pressinfo['month'] == 'Spring/Summer') {
							$pressinfo['month'] = '春夏';
						} else if ($pressinfo['month'] == 'Autumn/Winter') {
							$pressinfo['month'] = '秋冬';
						}
					}
?>
<div class="blog-article press-article single_press" <?php zoa_schema_markup( 'blog' ); ?>>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php zoa_schema_markup( 'blog_list' ); ?>>
		<div itemprop="mainEntityOfPage">
			<div class="blog-article-sum">
				<div class="press-article-header max-width--large gutter-padding--full">
					<div class="row">
						<div class="press__header__section col-xs-12">
							<span class="press__article-hero__cat heading heading--small txt--normal"><?php echo get_cat_name($cat->term_id); ?></span>
							<h1 class="press__article-hero__title heading heading--main serif"><?php the_title(); ?></h1>
							<?php
							echo '<div class="press__article-hero__credit p3">' .$pressinfo_default['month'].'&nbsp;'.$pressinfo_default['year'].'</div>';
							?>
							<div class="press__article-hero__descr p3"><?php printf( '<p>'.__('Published in %1$s %2$s %3$s', 'zoa').'</p>', $magName,$pressinfo['month'], $pressinfo['year']); ?></div>
						</div>
					</div>
				</div>
				
				<?php 

$images = get_field('press_gallery');
$size = 'full';
if( $images ): ?>
    <div class="row flex-justify-center">
        <?php foreach( $images as $image ): ?>
            <div class="col-md-6 col-xs-12">
                <a href="<?php echo $image['url']; ?>" data-rel="lightbox">
                     <?php echo wp_get_attachment_image( $image['ID'], $size ); ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<div class="entry-content" <?php zoa_schema_markup( 'post_content' ); ?>>
					
					<?php
					if ( is_single() ) {
						the_content();
						zoa_wp_link_pages();
					} else {
						the_excerpt();
					}
					?>
				</div>

				<footer class="entry-footer">
					<?php
					if ( is_single() ) :
						zoa_blog_tags();
						?>
						<div class="posts-nav">
							<?php
								$excludeTerm = get_exclude_cat_footer_navigation_press();
								$prev = get_previous_post(false, $excludeTerm);
								$next = get_next_post(false, $excludeTerm);
								$img  = get_template_directory_uri() . '/images/thumbnail-default.jpg';

							if ( ! empty( $prev ) ) :
								$prev_img_id  = get_post_thumbnail_id( $prev->ID );
								if ( ! empty( $prev_img_id ) ) {
									$prev_img = wp_get_attachment_image_url( $prev_img_id, 'thumbnail' );
									$prev_img_alt = zoa_img_alt( $prev_img_id, esc_attr__( 'Previous post thumbnail', 'zoa' ) );
								}
								?>
								<div class="post-nav-item prev-nav">
									<?php if ( ! empty( $prev_img ) ) : ?>
									<a href="<?php echo get_permalink( $prev->ID ); ?>">
										<img src="<?php echo esc_url( $prev_img ); ?>" alt="<?php echo esc_attr( $prev_img_alt ); ?>">
									</a>
									<?php endif; ?>

									<span class="nav-item-cont">
										<span><?php esc_html_e( 'Previous Post', 'zoa' ); ?></span>
										<h2 class="entry-title"><a href="<?php echo get_permalink( $prev->ID ); ?>"><?php echo get_the_title( $prev->ID ); ?></a></h2>
									</span>
								</div>
								<?php
								endif;

							if ( ! empty( $next ) ) :
								$next_img_id  = get_post_thumbnail_id( $next->ID );
								if ( ! empty( $next_img_id ) ) {
									$next_img     = wp_get_attachment_image_url( $next_img_id, 'thumbnail' );
									$next_img_alt = zoa_img_alt( $next_img_id, esc_attr__( 'Next post thumbnail', 'zoa' ) );
								}
								?>
								<div class="post-nav-item next-nav">
									<span class="nav-item-cont">
										<span><?php esc_html_e( 'Next Post', 'zoa' ); ?></span>
										<h2 class="entry-title"><a href="<?php echo get_permalink( $next->ID ); ?>"><?php echo get_the_title( $next->ID ); ?></a></h2>
									</span>

									<?php if ( ! empty( $next_img ) ) : ?>
									<a href="<?php echo get_permalink( $next->ID ); ?>">
										<img src="<?php echo esc_url( $next_img ); ?>" alt="<?php echo esc_attr( $next_img_alt ); ?>">
									</a>
									<?php endif; ?>
								</div>
								<?php endif; ?>
							</div>
					<?php else : ?>
						<a href="<?php the_permalink(); ?>" class="blog-read-more"><?php esc_html_e( 'Read more', 'zoa' ); ?><span class="screen-reader-text"><?php esc_html_e( 'about an interesting article to read', 'zoa' ); ?></span></a>
					<?php endif; ?>
				</footer>
			</div>
		</div>
	</article>
</div>
