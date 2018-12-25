<?php 
get_header(); 
global $post,$wp;
$request = explode( '/', $wp->request );
$post_data = get_post($post->post_parent);
$post_slug=$post->post_name;
$ancestors = get_post_ancestors( $post->ID );
$parents_id = end($ancestors);
$parent_slug  = get_post($parents_id)->post_name;
$prev_slug = $request[1];
$lastslug = end($request);
if ( count($request) >= 2 ) {
	$pageClass = 'child-'.$parent_slug;
} else {
	$pageClass = 'parent-'.$parent_slug;
}
$is_sub_myaccount = count($request) > 1 && $request[0] == 'my-account';
?>
<main id="main" class="page-content <?php echo 'page-'.$post_slug.' '.$pageClass.' page_'.$parent_slug.'--'.$prev_slug.' page_'.$parent_slug.'--'.$prev_slug.'--'.$lastslug; ?>">
    <?php
        if( have_posts() ):
            while ( have_posts() ):
                the_post();
	                if (is_page('bespoke')) {
						get_template_part( 'template-parts/content', 'bespoke' );
					} elseif ($post_data->post_name == 'bespoke') {
						get_template_part( 'template-parts/content', 'bespoke_child' );
					} elseif(is_page('reservation-test')) {
						get_template_part( 'template-parts/content', 'booked' );
					} else {
                if( zoa_is_elementor() && zoa_elementor_page( get_the_ID() ) ){
                    /*page build with Elementor*/
                    get_template_part( 'template-parts/content', 'page' );
                }else{
                    /*page without Elementor*/
                    ?>
                    <div class="<?php if( is_cart() || is_checkout() ){ ?>max-width--site gutter-padding--full<?php } elseif(is_account_page()) { ?><?php if($is_sub_myaccount) {?>max-width--site<?php }else{ ?>max-width--large myaccount-dashboard<?php } ?> gutter-padding<?php }else{ ?>container<?php } ?>">
                        <?php
					get_template_part( 'template-parts/content', 'page' );

                            if ( comments_open() || get_comments_number() ) {
                                comments_template();
                            }
                        ?>
                    </div>
                <?php
					}//end bespoke else
                }
           endwhile;
        else:
        ?>
            <div class="container">
                <?php get_template_part( 'template-parts/content', 'none' ); ?>
            </div>
        <?php endif; ?>
</main>

<?php
    get_footer();