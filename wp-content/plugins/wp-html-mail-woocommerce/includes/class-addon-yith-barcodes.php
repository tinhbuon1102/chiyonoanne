<?php if ( ! defined( 'ABSPATH' ) ) exit;

class WPHTMLMail_WooCommerce_AddOn_YithBarcode
{

    private static $instance;

    
    public static function instance(){
        if (!isset(self::$instance) && !(self::$instance instanceof WPHTMLMail_WooCommerce_AddOn_YithBarcode)) {
            self::$instance = new WPHTMLMail_WooCommerce_AddOn_YithBarcode();
        }

        return self::$instance;
    }



    public function __construct(){
        
        add_filter( 'haet_mail_placeholder_menu', array( $this, 'add_placeholder' ));
        add_filter( 'haet_mail_order_placeholders', array( $this, 'populate_placeholder' ), 10, 3 );


        add_filter( 'haet_mail_placeholder_menu_products_table', array( $this, 'add_placeholder_products_table' ));
        add_filter( 'haet_mail_order_placeholders_products_table', array( $this, 'populate_placeholder_products_table' ), 10, 4 );
    }


    public function add_placeholder( $placeholder_menu ){
        if( is_array( $placeholder_menu ) ){
            $placeholder_menu[] = array(
                    'text'      => __('YITH Barcode','haet_mail'),
                    'tooltip'   => '[YITH_ORDER_BARCODE]',
                );
        }

        return $placeholder_menu;
    }


    public function populate_placeholder( $order, $wc_order, $settings ){
        // YITH_YWBC_Backend::get_instance()->show_on_emails( $settings['wc_email'] );
        if( !isset( $wc_order ) || !is_a( $wc_order, 'WC_Abstract_Order' ) )
            return $order;
        
        ob_start();
        include( YITH_YWBC_ASSETS_DIR . '/css/ywbc-style.css' );
        $css = ob_get_clean();

        ob_start();
        YITH_YWBC()->show_barcode( $wc_order->get_id(), false, $css );
        $order['yith_order_barcode'] = ob_get_clean();
        return $order;
    }


    public function add_placeholder_products_table( $placeholder_menu ){
        if( is_array( $placeholder_menu ) ){
            $placeholder_menu[] = array(
                    'text'      => __('YITH Barcode','haet_mail'),
                    'tooltip'   => '[YITH_PRODUCT_BARCODE]',
                );
        }

        return $placeholder_menu;
    }


    public function populate_placeholder_products_table( $placeholder_item, $item, $product, $wc_order ){
        $placeholder_item['yith_product_barcode'] = do_shortcode( '[yith_render_barcode id="' . $product->get_id() . '"]' );
        return $placeholder_item;
    }
}



function WPHTMLMail_WooCommerce_AddOn_YithBarcode()
{
    return WPHTMLMail_WooCommerce_AddOn_YithBarcode::instance();
}

WPHTMLMail_WooCommerce_AddOn_YithBarcode();