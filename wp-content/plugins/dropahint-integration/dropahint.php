<?php
/*
  Plugin Name: Dropahint Integration
  Description: Small integration plugin to easily setup dropahint app into your woocommerce store.
  Version: 1.9
  Author: Dropahint
  Author URI: http://app.dropahint.us
 */


function dropahint_custom_menu_page(){
    add_menu_page(
        'Dropahint',
        'Dropahint',
        'manage_options',
        'dropahint','dropahint_custom_menu_page_render',
        plugins_url('images/dropahint.png', __FILE__),
        90
    );
}
add_action( 'woocommerce_after_add_to_cart_button', 'dropahint_content_after_addtocart_button' );

function dropahint_content_after_addtocart_button() {
    global $loop;
    if($op=get_option('dropahint_widget')) {
        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $loop->post->ID ), 'single-post-thumbnail' );
        ?>
        <script src="<?php echo $op ?>" async></script>
        <br/>
        <span class="drophint-link" data-product-image="<?=$image[0]?>"></span>
        <?php
    }
}
add_action( 'admin_menu', 'dropahint_custom_menu_page' );

add_action('admin_init','dropahint_logout');

function dropahint_logout(){
    if(isset($_POST['remove_dropahint']) && wp_verify_nonce( $_REQUEST['dropahint'], 'remove_dropahint'))
    {
        delete_option( 'dropahint_widget' );
    }
}



function dropahint_custom_menu_page_render()
{

    $errormsg="";
    $account_select = false;
    $extUrl = "";
    $site_id_exist = get_option('dropahint_widget');

    if(@$_POST['Login'] && @$_POST['Email']  && @$_POST['Password'] && filter_var($_POST['Email'],FILTER_VALIDATE_EMAIL))
    {

        $url ="https://app.dropahint.us/site/login?api";

        $response = wp_remote_post( $url, array(
                'method' => 'POST',
                'sslverify' => false,
                'body' => array('api'=> 'true',
                    'WPURL'=>get_home_url(),
                    'LoginForm[email]' => $_POST['Email'],
                    'LoginForm[password]' => $_POST['Password']
                ),
            )
        );

        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            $error_message="Something went wrong: $error_message";
        } else {

            if ($response['response']['code'] == 200) {
//            $response['body']='dfdfdf';
                $accounts = (array)json_decode($response['body']);

                if(!$accounts || empty($accounts) || !isset($accounts['script'])){

                    $error_message="Invalid username/password.!";

                } else {
                    add_option("dropahint_widget", $accounts['script'],'','yes');
                    add_option("dropahint_hash", $accounts['hash'],'','yes');
                    $site_id_exist=$accounts['script'];
                }

            } else {
                $error_message=$response['response']['message'];
            }

        }

    }



    // var_dump($site_id_exist);die;
    if(empty($site_id_exist))
    {

        ?>

        <div class="wrap" style="display: block">

            <div style="    background: #1D2345;
    padding: 30px;">
                <img src="<?= plugins_url('images/dropahint2.png',__FILE__) ?>"/>

            </div>
            <br/>

            <div class="postbox" style="padding: 5px">
                <?php
                if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )===false ) {
                    echo "This plugin only works with WooCommerce. Please install WooCommerce.";
                } else {?>
                    <div class="handlediv" title="Click to toggle">
                        <br>
                    </div>
                    <h3 class="hndle" style="padding: 5px">
                        <span>Login to your account</span>

                    </h3>

                    <div class="inside">
                        <div class="main">
                            <?php
                            if (@$error_message) {
                                echo "<p style='color: #ff0000'>" . $error_message . "</p>";
                            }
                            ?>

                            <form method="post" action="">
                                <table class="form-table">
                                    <tr valign="top">
                                        <th scope="row">Email</th>
                                        <td><input type="email" name="Email" value="" required="required"/></td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row">Password</th>
                                        <td><input type="password" name="Password" value="" required="required"/></td>
                                    </tr>

                                </table>
                                <h4>The Dropahint app will be integrated once your account is linked up.</h4>
                                <p class="submit">
                                    <input type="submit" name="Login" id="Login" class="button button-primary"
                                           value="login">
                                </p>
                                <span>
                            Don't Have Dropahint Account Please <a target="_blank"
                                                                   href="https://app.dropahint.us/site/register">Click here</a>
                        </span>

                            </form>


                        </div>
                    </div>

                <?php } ?>
            </div>


        </div>

        <?php

    } else {
        ?>
        <div class="wrap">

            <div style="    background: #1D2345;
    padding: 30px;">
                <img src="<?=plugins_url('images/dropahint2.png',__FILE__);?>"/>

            </div>
            <br/>


            <div class="postbox" style="padding: 5px">
                <div class="handlediv" title="Click to toggle">
                    <br>
                </div>


                <div class="inside">
                    <div class="main">

                        <p  style="padding: 5px">
                            <span>Account Integrated</span><br><br/>
                            <span>To start managing hints, cards, emails etc, Launch our dashboard for access all feature including all customization</span>
                        </p>

                        <form action="" method="post">
                            <?php wp_nonce_field( 'remove_dropahint','dropahint' ); ?>
                            <a href="http://app.dropahint.us/" target="_blank" class="button button-primary" >Launch Dashboard</a>
                            <input type="submit" name="remove_dropahint" value="Logout" class="button button-primary">
                        </form>


                    </div>
                </div>
            </div>


        </div>
        <?php
    }

}


function dropahint_products_api(){

    if(isset($_GET['dropahint_product_q']) && isset($_GET['dropahint_product_hash']) && get_option('dropahint_hash') ){
        if($_GET['dropahint_product_hash']==get_option('dropahint_hash')){
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => 50,
                'paged'=>$_GET['dropahint_product_page'],
                's' => $_GET['dropahint_product_q'],
            );
            $prods=[];
            $loop = new WP_Query( $args );

            while ( $loop->have_posts() ) : $loop->the_post();
                global $product;
                $prods[]=[
                    'name'=>@get_the_title(),
                    'image'=>@get_the_post_thumbnail_url( $product->ID, apply_filters( 'single_product_archive_thumbnail_size', 'shop_catalog')),
                    'value'=>@get_permalink()
                ];
            endwhile;

            echo json_encode($prods);die;
        }
    }
}

add_action( 'woocommerce_init', 'dropahint_products_api' );


