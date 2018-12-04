<?php
    get_header();
    $sidebar = get_theme_mod( 'blog_sidebar', 'right' );
$cat = get_category( get_query_var( 'cat' ) );
$cat_slug = $cat->slug;
?>
<?php //echo $cat_slug; ?>
<?php if (!$cat_slug == 'press'): ?>
<div class="container">
    <div class="row">
        <?php
		 
            switch($sidebar):
            case 'left':
            /*! sidebar in left
            ------------------------------------------------->*/
        ?>
		
        
		<div class="col-md-3">
            <?php get_sidebar(); ?>
        </div>
		

        <main id="main" lass="col-md-9 col-lg-9">
            <?php
            if ( have_posts() ):
                while ( have_posts() ): the_post();
			get_template_part( 'template-parts/content', get_post_format() );
                    
                endwhile;
                zoa_paging();
            else :
                get_template_part( 'template-parts/content', 'none' );
            endif; ?>
        </main>
        <?php
            break;
            case 'right':
            /*! sidebar in right
            ------------------------------------------------->*/
        ?>
        <main id="main" class="col-md-9 col-lg-9">
            <?php
            if ( have_posts() ):
                while ( have_posts() ): the_post();
                    get_template_part( 'template-parts/content', get_post_format() );
                endwhile;
                zoa_paging();
            else :
                get_template_part( 'template-parts/content', 'none' );
            endif; ?>
        </main>

        <?php if (!$cat_slug == 'press'): ?>
		<div class="col-md-3">
            <?php get_sidebar(); ?>
        </div>
		<?php endif; ?>
        <?php
            break;
            case 'full':
            /*! no sidebar
            ------------------------------------------------->*/
        ?>
		
		
        <main id="main" class="col-md-12 col-lg-12">
            <?php
            if ( have_posts() ):
                while ( have_posts() ): the_post();
                    get_template_part( 'template-parts/content', get_post_format() );
                endwhile;
                zoa_paging();
            else :
                get_template_part( 'template-parts/content', 'none' );
            endif; ?>
        </main>
		
        <?php
            break;
            endswitch;
        ?>
    </div>
</div>
<?php else : ?>
<div class="container">
    <div class="row">
		<main id="main" class="col-md-12 col-lg-12">
            <?php
            if ( have_posts() ):
			echo '<div class="row">';
                while ( have_posts() ): the_post();
                get_template_part( 'template-parts/content', 'press_archive' );
                endwhile;
                zoa_paging();
			echo '</div>';
            else :
                get_template_part( 'template-parts/content', 'none' );
            endif; ?>
        </main>
	</div>
</div>
<?php endif; ?>
<?php
    get_footer();