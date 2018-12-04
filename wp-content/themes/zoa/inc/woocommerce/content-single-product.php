<?php

/*PRODUCT IMAGE*/
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
add_action( 'woocommerce_before_single_product_summary', 'zoa_product_gallery', 20 );
function zoa_product_gallery(){

    /*PRODUCT ATTRIBUTE*/
    global $product;
    $product_id       = $product->get_id();
    $image_id         = $product->get_image_id();
    $image_alt        = zoa_img_alt( $image_id, esc_attr__( 'Product image', 'zoa' ) );

    if( $image_id ){
        $image_small_src    = wp_get_attachment_image_src( $image_id, 'thumbnail' );
        $image_medium_src   = wp_get_attachment_image_src( $image_id, 'woocommerce_single' );
        $image_full_src     = wp_get_attachment_image_src( $image_id, 'full' );
    }else{
        $image_small_src[0] = $image_medium_src[0] = $image_full_src[0] = wc_placeholder_img_src();
    }

    $gallery_id       = $product->get_gallery_image_ids();
    $class_name       = 'pro-single-image';
    $video_url        = '';

    /*LAYOUT*/
    $layout = get_theme_mod( 'shop_gallery_layout', 'vertical' );

    /*ZOOM JS*/
    wp_enqueue_script( 'easyzoom' );
    wp_add_inline_script(
        'easyzoom',
        "document.addEventListener( 'DOMContentLoaded', function(){
            if ( window.matchMedia( '( min-width: 992px )' ).matches ) {
                jQuery( '.ez-zoom' ).easyZoom();
            }
        } );",
        'after'
    );

    /*PRODUCT OPTION*/
    if( function_exists( 'FW' ) ){
        $video_url = fw_get_db_post_option( $product_id, 'video' );
        $p_layout  = fw_get_db_post_option( $product_id, 'layout' );
        if( isset( $p_layout ) && 'default' != $p_layout ){
            $layout = $p_layout;
        }

        wp_enqueue_style( 'lity-style' );
        wp_enqueue_script( 'lity-script' );
    }

    /*SLIDER FOR `vertical` AND `horizontal` LAYOUT*/
    if( ! empty( $gallery_id ) && ( 'vertical' == $layout || 'horizontal' == $layout ) ) {

        $class_name = '';
        $mode       = 'vertical';
        $gutter     = 0;
        $fixedWidth = 0;

        if( 'horizontal' == $layout ){
            $mode       = 'horizontal';
            $gutter     = 10;
            $fixedWidth = 80;
        }

        wp_add_inline_script(
            'tiny-slider',
            "document.addEventListener( 'DOMContentLoaded', function(){
                var image_carousel = tns({
                    loop: false,
                    container: '#gallery-image',
                    navContainer: '#gallery-thumb',
                    items: 1,
                    navAsThumbnails: true,
                    autoHeight: true
                });

                var thumb_carousel = tns({
                    loop: false,
                    container: '#gallery-thumb',
                    gutter: {$gutter},
                    items: 5,
                    mouseDrag: true,
                    nav: false,
                    fixedWidth: {$fixedWidth},
                    controls: false,
                    axis: '{$mode}'
                });

                var _prev = document.querySelector( '[data-controls=\'prev\']' ),
                    _next = document.querySelector( '[data-controls=\'next\']' );

                _prev.addEventListener( 'click', function () {
                    thumb_carousel.goTo( 'prev' );
                });
                _next.addEventListener( 'click', function () {
                    thumb_carousel.goTo( 'next' );
                });

                var reset_slider = function(){
                    image_carousel.goTo( 'first' );
                    thumb_carousel.goTo( 'first' );
                }

                jQuery( document.body ).on( 'found_variation', 'form.variations_form', function( event, variation ){
                    reset_slider();
                });

                jQuery( '.reset_variations' ).on( 'click', function(){
                    reset_slider();
                });
            });",
            'after'
        );
    }

    /*STICKY PRODUCT SUMMARY FOR `list` AND `grid` LAYOUT*/
    if( ! empty( $gallery_id ) && ( 'list' == $layout || 'grid' == $layout ) ) {
        $class_name = '';

        wp_enqueue_script( 'sticky-sidebar' );
        wp_add_inline_script(
            'sticky-sidebar',
            "document.addEventListener( 'DOMContentLoaded', function() {
                var window_width = window.innerWidth;

                function sticky_summary() {
                    if ( window_width < 992 ) {
                        jQuery( '.summary.entry-summary' ).trigger( 'sticky_kit:detach' );
                    } else {
                        jQuery( '.summary.entry-summary' ).stick_in_parent({
                            parent: '.shop-content > .product',
                            offset_top: 0
                        });
                    }
                }

                window.addEventListener( 'load', function() {
                    sticky_summary();
                });

                window.addEventListener( 'resize', function() {
                    sticky_summary();
                });

            } );",
            'after'
        );
    }

    ?>
    <div class="single-product-gallery <?php echo esc_attr( $class_name ); ?>">
        <?php /*MAIN CAROUSEL*/ ?>
        <div class="pro-carousel-image">
            <div id="gallery-image">
                <div class="pro-img-item ez-zoom" data-zoom="<?php echo esc_attr( $image_full_src[0] ); ?>">
                    <img src="<?php echo esc_url( $image_medium_src[0] ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>">
                </div>

                <?php
                    if( ! empty( $gallery_id ) ):
                        foreach( $gallery_id as $key ):
                            $g_full_img_src   = wp_get_attachment_image_src( $key, 'full' );
                            $g_medium_img_src = wp_get_attachment_image_src( $key, 'woocommerce_single' );
                            $g_img_alt        = zoa_img_alt( $key, esc_attr__( 'Product image', 'zoa' ) );
                        ?>
                        <div class="pro-img-item ez-zoom" data-zoom="<?php echo esc_attr( $g_full_img_src[0] ); ?>">
                            <img src="<?php echo esc_url( $g_medium_img_src[0] ); ?>" alt="<?php echo esc_attr( $g_img_alt ); ?>">
                        </div>
                <?php
                        endforeach;
                    endif;
                ?>
            </div>
        </div>

        <?php /*THUMB CAROUSEL*/ ?>
        <?php if( ! empty( $gallery_id ) && ( 'vertical' == $layout || 'horizontal' == $layout ) ): ?>
            <div class="pro-carousel-thumb">
                <div id="gallery-thumb">
                    <div class="pro-thumb">
                        <img src="<?php echo esc_url( $image_small_src[0] ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>">
                    </div>

                    <?php
                        foreach( $gallery_id as $key ):
                            $g_thumb_src = wp_get_attachment_image_src( $key, 'thumbnail' );
                            $g_thumb_alt = zoa_img_alt( $key, esc_attr__( 'Product image', 'zoa' ) );
                        ?>
                        <div class="pro-thumb">
                            <img src="<?php echo esc_url( $g_thumb_src[0] ); ?>" alt="<?php echo esc_attr( $g_thumb_alt ); ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if( ! empty( $video_url ) ){ ?>
            <a class="video-popup-btn zoa-icon-play" data-lity href="<?php echo esc_url( $video_url ); ?>"><?php esc_html_e( 'Video', 'zoa' ); ?></a>
        <?php } ?>

        <?php /* PRODUCT LABEL SINGLE PRODUCT */ ?>
        <?php
            echo zoa_product_label( $product );
        ?>
    </div>
    <?php
}

/*REMOVE DESCRIPTION HEADING*/
add_filter( 'woocommerce_product_description_heading', '__return_empty_string' );

/*AFTER ADD TO CART BUTTON*/
add_action( 'woocommerce_after_add_to_cart_button', 'additional_simple_add_to_cart', 20 );
function additional_simple_add_to_cart() {

    /*AJAX SINGLE ADD TO CART VALUE===*/
    global $product;
    $pid       = $product->get_id();
    $in_stock  = get_post_meta( $pid, '_manage_stock', true ); // RETURN `yes` || `no`
    $stock_qty = $product->get_stock_quantity(); // RETUNR `INT` VALUE

    /*CHECK PRODUCT IN CART && CHECK QUANTITY IF IT ALREADY IN CART*/
    $in_cart_qty = zoa_product_check_in( $pid, $in_cart = true, $qty_in_cart = false ) ? zoa_product_check_in( $pid, $in_cart = false, $qty_in_cart = true ) : 0;

    /*WARNING TEXT*/
    $not_enough   = esc_html__( 'You cannot add that amount of this product to the cart because there is not enough stock.', 'zoa' );
    $out_of_stock = sprintf( esc_html__( 'You cannot add that amount to the cart - we have %1$s in stock and you already have %1$s in your cart', 'zoa' ), $stock_qty );
    $valid_qty    = esc_html__( 'Please enter a valid quantity for this product', 'zoa' );
    ?>
    <input class="in-cart-qty" type="hidden" value="<?php echo esc_attr( $in_cart_qty ); ?>"
    data-in_stock="<?php echo esc_attr( $in_stock ); ?>"
    data-out_of_stock="<?php echo esc_attr( $out_of_stock ); ?>"
    data-valid_qty="<?php echo esc_attr( $valid_qty ); ?>"
    data-not_enough="<?php echo esc_attr( $not_enough ); ?>">
    <?php

    /*ADD TO WISHLIST BUTTON===*/
    echo class_exists( 'YITH_WCWL' ) ? do_shortcode( '[yith_wcwl_add_to_wishlist]' ) : '';
}

/*REMOVE ADDITIONAL INFORMATION HEADING*/
add_filter( 'woocommerce_product_additional_information_heading', 'zoa_remove_additional_information_heading' );
function zoa_remove_additional_information_heading() {
    return '';
}

/*SET COLUMN FOR RELATED || UPSELL PRODUCT*/
add_filter( 'woocommerce_upsell_display_args', 'zoa_column_related' );
add_filter( 'woocommerce_output_related_products_args', 'zoa_column_related' );
function zoa_column_related( $args ) {
    $number = (int) get_theme_mod( 'related_product_item', 5 );
    $column = (int) get_theme_mod( 'related_column', 5 );

    $args['posts_per_page'] = $number;
    $args['columns']        = $column;
    return $args;
}

/*AJAX SINGLE ADD TO CART*/
add_action( 'wp_ajax_single_add_to_cart', 'zoa_single_add_to_cart' );
add_action( 'wp_ajax_nopriv_single_add_to_cart', 'zoa_single_add_to_cart' );
function zoa_single_add_to_cart() {
    $response = array(
        'status'  => 500,
        'message' => esc_html__( 'Something is wrong, please try again later...', 'zoa' ),
        'content' => false,
    );

    if( ! isset( $_POST['product_id'] ) ||
        ! isset( $_POST['product_qty'] ) ||
        ! isset( $_POST['nonce'] ) ||
        ! wp_verify_nonce( $_POST['nonce'], 'zoa_product_nonce' ) ):

        echo json_encode( $response );
        exit();
    endif;

    $product_id        = intval( $_POST['product_id'] );
    $product_qty       = intval( $_POST['product_qty'] );
    $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $product_qty );

    if( isset( $_POST['variation_id'] ) ){
        $variation_id = $_POST['variation_id'];
    }

    if( isset( $_POST['variations'] ) ){
        $variations  = ( array ) json_decode( wp_unslash( $_POST['variations'] ) );
    }

    if ( $variation_id && $passed_validation ) {
        WC()->cart->add_to_cart( $product_id, $product_qty, $variation_id, $variations );
    } else {
        WC()->cart->add_to_cart( $product_id, $product_qty );
    }

    $count = WC()->cart->get_cart_contents_count();

    ob_start();

    $response = array(
        'status' => 200,
        'item'   => $count,
    );

    woocommerce_mini_cart();

    $response['content'] = ob_get_clean();

    echo json_encode( $response );
    exit();
}

/*GET NUMBER CURRENT PRODUCT IN CART*/
add_action( 'wp_ajax_get_count_product_already_in_cart', 'zoa_get_count_product_already_in_cart' );
add_action( 'wp_ajax_nopriv_get_count_product_already_in_cart', 'zoa_get_count_product_already_in_cart' );
function zoa_get_count_product_already_in_cart() {
    $response = array(
        'in_cart' => 0
    );

    if( ! isset( $_POST['product_id'] ) ){
        echo json_encode( $response );
        exit();
    }

    $product_id  = intval( $_POST['product_id'] );
    $in_cart_qty = zoa_product_check_in( $product_id, $in_cart = false, $qty_in_cart = true );

    ob_start();

    $response['in_cart'] = $in_cart_qty;

    ob_get_clean();

    echo json_encode( $response );
    exit();
}

?>