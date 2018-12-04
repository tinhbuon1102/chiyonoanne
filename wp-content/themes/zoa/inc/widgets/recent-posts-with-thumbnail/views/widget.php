<?php

echo wp_kses_post($before_widget );
echo wp_kses_post($title);

$args = array(
    'post_type' => 'post',
    'ignore_sticky_posts' => 1,
    'post_status' => 'publish',
    'posts_per_page' => $number
);

$query = new WP_Query( $args );

if( $query->have_posts() ):
    while($query->have_posts()): $query->the_post(); ?>

    <div class="widget_recent_post_thumbnail_item">
        <a href="<?php the_permalink(); ?>" class="blog-recent-post-thumbnail-img">
            <?php
                if(has_post_thumbnail()){
                    the_post_thumbnail(
                        array(100, 100, true),
                        array( 'alt' => get_the_title())
                    );
                }else{
                    echo '<img class="widget-post-thumbnail-default-img" alt="'. get_the_title() .'" src="'. get_template_directory_uri().'/images/thumbnail-default.jpg' .'">';
                }
            ?>
       </a>
        <div class="blog-recent-post-thumbnail-sumary">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            <span><?php echo zoa_date_format(); ?></span>
        </div>
    </div>

<?php
    endwhile;
    wp_reset_postdata();
endif;

echo wp_kses_post($after_widget);