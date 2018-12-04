<?php if ( ! defined( 'ABSPATH' ) ) exit;
$mailbuilder_subject = array();
$mailbuilder_content = array();


$productstable_admin = array(
        "mb-edit-productstable-header[0]"   =>  "<p><strong>" . __( 'Product', 'woocommerce' ) . "</strong></p>",
        "mb-edit-productstable-header-styles[0]"    =>  "{\"border-left-style\":\"none\",\"border-top-style\":\"none\",\"border-right-style\":\"none\",\"border-bottom-style\":\"solid\",\"border-left-color\":\"#000000\",\"border-top-color\":\"#000000\",\"border-right-color\":\"#000000\",\"border-bottom-color\":\"#000000\",\"border-left-width\":\"1px\",\"border-top-width\":\"1px\",\"border-right-width\":\"1px\",\"border-bottom-width\":\"2px\"}",
        "mb-edit-productstable-header[1]"   =>  "",
        "mb-edit-productstable-header-styles[1]"    =>  "{\"border-left-style\":\"none\",\"border-top-style\":\"none\",\"border-right-style\":\"none\",\"border-bottom-style\":\"solid\",\"border-left-color\":\"#000000\",\"border-top-color\":\"#000000\",\"border-right-color\":\"#000000\",\"border-bottom-color\":\"#000000\",\"border-left-width\":\"1px\",\"border-top-width\":\"1px\",\"border-right-width\":\"1px\",\"border-bottom-width\":\"2px\"}",
        "mb-edit-productstable-header[2]"   =>  "<p style=\"text-align: center;\"><strong>" . __( 'Quantity', 'woocommerce' ) . "</strong></p>",
        "mb-edit-productstable-header-styles[2]"    =>  "{\"border-left-style\":\"none\",\"border-top-style\":\"none\",\"border-right-style\":\"none\",\"border-bottom-style\":\"solid\",\"border-left-color\":\"#000000\",\"border-top-color\":\"#000000\",\"border-right-color\":\"#000000\",\"border-bottom-color\":\"#000000\",\"border-left-width\":\"1px\",\"border-top-width\":\"1px\",\"border-right-width\":\"1px\",\"border-bottom-width\":\"2px\",\"width\":\"auto\",\"padding-left\":\"0px\",\"padding-top\":\"0px\",\"padding-right\":\"0px\",\"padding-bottom\":\"0px\"}",
        "mb-edit-productstable-header[3]"   =>  "<p style=\"text-align: right;\"><strong>" . __( 'Price', 'woocommerce' ) . "</strong></p>",
        "mb-edit-productstable-header-styles[3]"    =>  "{\"border-left-style\":\"none\",\"border-top-style\":\"none\",\"border-right-style\":\"none\",\"border-bottom-style\":\"solid\",\"border-left-color\":\"#000000\",\"border-top-color\":\"#000000\",\"border-right-color\":\"#000000\",\"border-bottom-color\":\"#000000\",\"border-left-width\":\"1px\",\"border-top-width\":\"1px\",\"border-right-width\":\"1px\",\"border-bottom-width\":\"2px\"}",
        "mb-edit-productstable-body[0]" =>  "<p>[PHOTO_THUMBNAIL]</p>",
        "mb-edit-productstable-body-styles[0]"  =>  "{\"border-left-style\":\"none\",\"border-top-style\":\"none\",\"border-right-style\":\"none\",\"border-bottom-style\":\"solid\",\"border-left-color\":\"#000000\",\"border-top-color\":\"#000000\",\"border-right-color\":\"#000000\",\"border-bottom-color\":\"#727272\",\"border-left-width\":\"1px\",\"border-top-width\":\"1px\",\"border-right-width\":\"1px\",\"border-bottom-width\":\"1px\",\"width\":\"50px\",\"padding-left\":\"0px\",\"padding-top\":\"1px\",\"padding-right\":\"0px\",\"padding-bottom\":\"0px\"}",
        "mb-edit-productstable-body[1]" =>  "<p><span style=\"font-size: 14px;\"><strong>[PRODUCTNAME]</strong></span></p>\r\n<p><span style=\"font-size: 12px;  color: #808080;\">[ITEMMETA]</span></p>",
        "mb-edit-productstable-body-styles[1]"  =>  "{\"border-left-style\":\"none\",\"border-top-style\":\"none\",\"border-right-style\":\"none\",\"border-bottom-style\":\"solid\",\"border-left-color\":\"#000000\",\"border-top-color\":\"#000000\",\"border-right-color\":\"#000000\",\"border-bottom-color\":\"#727272\",\"border-left-width\":\"1px\",\"border-top-width\":\"1px\",\"border-right-width\":\"1px\",\"border-bottom-width\":\"1px\",\"width\":\"auto\",\"padding-left\":\"10px\",\"padding-top\":\"0px\",\"padding-right\":\"0px\",\"padding-bottom\":\"0px\"}",
        "mb-edit-productstable-body[2]" =>  "<p style=\"text-align: center;\">[QUANTITY]</p>",
        "mb-edit-productstable-body-styles[2]"  =>  "{\"border-left-style\":\"none\",\"border-top-style\":\"none\",\"border-right-style\":\"none\",\"border-bottom-style\":\"solid\",\"border-left-color\":\"#000000\",\"border-top-color\":\"#000000\",\"border-right-color\":\"#000000\",\"border-bottom-color\":\"#727272\",\"border-left-width\":\"1px\",\"border-top-width\":\"1px\",\"border-right-width\":\"1px\",\"border-bottom-width\":\"1px\",\"width\":\"40px\",\"padding-left\":\"0px\",\"padding-top\":\"0px\",\"padding-right\":\"0px\",\"padding-bottom\":\"0px\"}",
        "mb-edit-productstable-body[3]" =>  "<p style=\"text-align: right;\"><strong>[PRICE]</strong></p>",
        "mb-edit-productstable-body-styles[3]"  =>  "{\"border-left-style\":\"none\",\"border-top-style\":\"none\",\"border-right-style\":\"none\",\"border-bottom-style\":\"solid\",\"border-left-color\":\"#000000\",\"border-top-color\":\"#000000\",\"border-right-color\":\"#000000\",\"border-bottom-color\":\"#727272\",\"border-left-width\":\"1px\",\"border-top-width\":\"1px\",\"border-right-width\":\"1px\",\"border-bottom-width\":\"1px\",\"width\":\"70px\",\"padding-left\":\"0px\",\"padding-top\":\"0px\",\"padding-right\":\"0px\",\"padding-bottom\":\"0px\"}",
        "mb-edit-productstable-purchasenote[0]" =>  "<p><span style=\"font-size: 12px;\"><em>[PURCHASE_NOTE]</em></span></p>",
        "mb-edit-productstable-purchasenote-styles[0]"  =>  "{\"border-left-style\":\"none\",\"border-top-style\":\"none\",\"border-right-style\":\"none\",\"border-bottom-style\":\"solid\",\"border-left-color\":\"#000000\",\"border-top-color\":\"#000000\",\"border-right-color\":\"#000000\",\"border-bottom-color\":\"#848484\",\"border-left-width\":\"1px\",\"border-top-width\":\"1px\",\"border-right-width\":\"1px\",\"border-bottom-width\":\"2px\",\"width\":\"auto\",\"padding-left\":\"10px\",\"padding-top\":\"0px\",\"padding-right\":\"0px\",\"padding-bottom\":\"0px\"}",
        "mb-edit-totalstable-body[0]"   =>  "",
        "mb-edit-totalstable-body-styles[0]"    =>  "{\"border-left-style\":\"none\",\"border-top-style\":\"none\",\"border-right-style\":\"none\",\"border-bottom-style\":\"none\",\"border-left-color\":\"#000000\",\"border-top-color\":\"#000000\",\"border-right-color\":\"#000000\",\"border-bottom-color\":\"#707070\",\"border-left-width\":\"1px\",\"border-top-width\":\"1px\",\"border-right-width\":\"1px\",\"border-bottom-width\":\"1px\",\"width\":\"auto\",\"padding-left\":\"0px\",\"padding-top\":\"0px\",\"padding-right\":\"0px\",\"padding-bottom\":\"0px\"}",
        "mb-edit-totalstable-body[1]"   =>  "<p style=\"text-align: right;\"><span style=\"font-size: 14px; \"><span style=\"color: #000000;\">[LABEL]</span></span></p>",
        "mb-edit-totalstable-body-styles[1]"    =>  "{\"border-left-style\":\"none\",\"border-top-style\":\"none\",\"border-right-style\":\"none\",\"border-bottom-style\":\"solid\",\"border-left-color\":\"#000000\",\"border-top-color\":\"#000000\",\"border-right-color\":\"#000000\",\"border-bottom-color\":\"#707070\",\"border-left-width\":\"1px\",\"border-top-width\":\"1px\",\"border-right-width\":\"1px\",\"border-bottom-width\":\"1px\",\"width\":\"140px\",\"padding-left\":\"0px\",\"padding-top\":\"0px\",\"padding-right\":\"0px\",\"padding-bottom\":\"0px\"}",
        "mb-edit-totalstable-body[2]"   =>  "<p style=\"text-align: right;\"><span style=\"font-size: 14px; \"><strong>[VALUE]</strong></span></p>",
        "mb-edit-totalstable-body-styles[2]"    =>  "{\"border-left-style\":\"none\",\"border-top-style\":\"none\",\"border-right-style\":\"none\",\"border-bottom-style\":\"solid\",\"border-left-color\":\"#000000\",\"border-top-color\":\"#000000\",\"border-right-color\":\"#000000\",\"border-bottom-color\":\"#707070\",\"border-left-width\":\"1px\",\"border-top-width\":\"1px\",\"border-right-width\":\"1px\",\"border-bottom-width\":\"1px\",\"width\":\"160px\",\"padding-left\":\"0px\",\"padding-top\":\"0px\",\"padding-right\":\"0px\",\"padding-bottom\":\"0px\"}"
    );




$productstable_customer = array(
        "mb-edit-productstable-header[0]"           => "<p><strong>" . __( 'Product', 'woocommerce' ) . "</strong></p>",
        "mb-edit-productstable-header-styles[0]"    => "{\"border-left-style\":\"none\",\"border-top-style\":\"none\",\"border-right-style\":\"none\",\"border-bottom-style\":\"solid\",\"border-left-color\":\"#000000\",\"border-top-color\":\"#000000\",\"border-right-color\":\"#000000\",\"border-bottom-color\":\"#000000\",\"border-left-width\":\"1px\",\"border-top-width\":\"1px\",\"border-right-width\":\"1px\",\"border-bottom-width\":\"2px\"}",
        "mb-edit-productstable-header[1]"           => "",
        "mb-edit-productstable-header-styles[1]"    => "{\"border-left-style\":\"none\",\"border-top-style\":\"none\",\"border-right-style\":\"none\",\"border-bottom-style\":\"solid\",\"border-left-color\":\"#000000\",\"border-top-color\":\"#000000\",\"border-right-color\":\"#000000\",\"border-bottom-color\":\"#000000\",\"border-left-width\":\"1px\",\"border-top-width\":\"1px\",\"border-right-width\":\"1px\",\"border-bottom-width\":\"2px\"}",
        "mb-edit-productstable-header[2]"           => "<p style=\"text-align: center;\"><strong>" . __( 'Quantity', 'woocommerce' ) . "</strong></p>",
        "mb-edit-productstable-header-styles[2]"    => "{\"border-left-style\":\"none\",\"border-top-style\":\"none\",\"border-right-style\":\"none\",\"border-bottom-style\":\"solid\",\"border-left-color\":\"#000000\",\"border-top-color\":\"#000000\",\"border-right-color\":\"#000000\",\"border-bottom-color\":\"#000000\",\"border-left-width\":\"1px\",\"border-top-width\":\"1px\",\"border-right-width\":\"1px\",\"border-bottom-width\":\"2px\",\"width\":\"auto\",\"padding-left\":\"0px\",\"padding-top\":\"0px\",\"padding-right\":\"0px\",\"padding-bottom\":\"0px\"}",
        "mb-edit-productstable-header[3]"           => "<p style=\"text-align: right;\"><strong>" . __( 'Price', 'woocommerce' ) . "</strong></p>",
        "mb-edit-productstable-header-styles[3]"    => "{\"border-left-style\":\"none\",\"border-top-style\":\"none\",\"border-right-style\":\"none\",\"border-bottom-style\":\"solid\",\"border-left-color\":\"#000000\",\"border-top-color\":\"#000000\",\"border-right-color\":\"#000000\",\"border-bottom-color\":\"#000000\",\"border-left-width\":\"1px\",\"border-top-width\":\"1px\",\"border-right-width\":\"1px\",\"border-bottom-width\":\"2px\"}",
        "mb-edit-productstable-body[0]"             => "<p>[PHOTO_THUMBNAIL]</p>",
        "mb-edit-productstable-body-styles[0]"      => "{\"border-left-style\":\"none\",\"border-top-style\":\"none\",\"border-right-style\":\"none\",\"border-bottom-style\":\"solid\",\"border-left-color\":\"#000000\",\"border-top-color\":\"#000000\",\"border-right-color\":\"#000000\",\"border-bottom-color\":\"#727272\",\"border-left-width\":\"1px\",\"border-top-width\":\"1px\",\"border-right-width\":\"1px\",\"border-bottom-width\":\"1px\",\"width\":\"50px\",\"padding-left\":\"0px\",\"padding-top\":\"0px\",\"padding-right\":\"0px\",\"padding-bottom\":\"0px\"}",
        "mb-edit-productstable-body[1]"             => "<p><span style=\"font-size: 14px;\"><strong>[PRODUCTNAME]</strong></span></p>\r\n<p><span style=\"font-size: 12px;  color: #808080;\">[ITEMMETA]</span></p>",
        "mb-edit-productstable-body-styles[1]"      => "{\"border-left-style\":\"none\",\"border-top-style\":\"none\",\"border-right-style\":\"none\",\"border-bottom-style\":\"solid\",\"border-left-color\":\"#000000\",\"border-top-color\":\"#000000\",\"border-right-color\":\"#000000\",\"border-bottom-color\":\"#727272\",\"border-left-width\":\"1px\",\"border-top-width\":\"1px\",\"border-right-width\":\"1px\",\"border-bottom-width\":\"1px\",\"width\":\"auto\",\"padding-left\":\"10px\",\"padding-top\":\"0px\",\"padding-right\":\"0px\",\"padding-bottom\":\"0px\"}",
        "mb-edit-productstable-body[2]"             => "<p style=\"text-align: center;\">[QUANTITY]</p>",
        "mb-edit-productstable-body-styles[2]"      => "{\"border-left-style\":\"none\",\"border-top-style\":\"none\",\"border-right-style\":\"none\",\"border-bottom-style\":\"solid\",\"border-left-color\":\"#000000\",\"border-top-color\":\"#000000\",\"border-right-color\":\"#000000\",\"border-bottom-color\":\"#727272\",\"border-left-width\":\"1px\",\"border-top-width\":\"1px\",\"border-right-width\":\"1px\",\"border-bottom-width\":\"1px\",\"width\":\"40px\",\"padding-left\":\"0px\",\"padding-top\":\"0px\",\"padding-right\":\"0px\",\"padding-bottom\":\"0px\"}",
        "mb-edit-productstable-body[3]"             => "<p style=\"text-align: right;\"><strong>[PRICE]</strong></p>",
        "mb-edit-productstable-body-styles[3]"      => "{\"border-left-style\":\"none\",\"border-top-style\":\"none\",\"border-right-style\":\"none\",\"border-bottom-style\":\"solid\",\"border-left-color\":\"#000000\",\"border-top-color\":\"#000000\",\"border-right-color\":\"#000000\",\"border-bottom-color\":\"#727272\",\"border-left-width\":\"1px\",\"border-top-width\":\"1px\",\"border-right-width\":\"1px\",\"border-bottom-width\":\"1px\",\"width\":\"70px\",\"padding-left\":\"0px\",\"padding-top\":\"0px\",\"padding-right\":\"0px\",\"padding-bottom\":\"0px\"}",
        "mb-edit-totalstable-body[0]"               => "",
        "mb-edit-totalstable-body-styles[0]"        => "{\"border-left-style\":\"none\",\"border-top-style\":\"none\",\"border-right-style\":\"none\",\"border-bottom-style\":\"none\",\"border-left-color\":\"#000000\",\"border-top-color\":\"#000000\",\"border-right-color\":\"#000000\",\"border-bottom-color\":\"#707070\",\"border-left-width\":\"1px\",\"border-top-width\":\"1px\",\"border-right-width\":\"1px\",\"border-bottom-width\":\"1px\",\"width\":\"auto\",\"padding-left\":\"0px\",\"padding-top\":\"0px\",\"padding-right\":\"0px\",\"padding-bottom\":\"0px\"}",
        "mb-edit-totalstable-body[1]"               => "<p style=\"text-align: right;\"><span style=\"font-size: 14px; \"><span style=\"color: #000000;\">[LABEL]</span></span></p>",
        "mb-edit-totalstable-body-styles[1]"        => "{\"border-left-style\":\"none\",\"border-top-style\":\"none\",\"border-right-style\":\"none\",\"border-bottom-style\":\"solid\",\"border-left-color\":\"#000000\",\"border-top-color\":\"#000000\",\"border-right-color\":\"#000000\",\"border-bottom-color\":\"#707070\",\"border-left-width\":\"1px\",\"border-top-width\":\"1px\",\"border-right-width\":\"1px\",\"border-bottom-width\":\"1px\",\"width\":\"140px\",\"padding-left\":\"0px\",\"padding-top\":\"0px\",\"padding-right\":\"0px\",\"padding-bottom\":\"0px\"}",
        "mb-edit-totalstable-body[2]"               => "<p style=\"text-align: right;\"><span style=\"font-size: 14px; \"><strong>[VALUE]</strong></span></p>",
        "mb-edit-totalstable-body-styles[2]"        => "{\"border-left-style\":\"none\",\"border-top-style\":\"none\",\"border-right-style\":\"none\",\"border-bottom-style\":\"solid\",\"border-left-color\":\"#000000\",\"border-top-color\":\"#000000\",\"border-right-color\":\"#000000\",\"border-bottom-color\":\"#707070\",\"border-left-width\":\"1px\",\"border-top-width\":\"1px\",\"border-right-width\":\"1px\",\"border-bottom-width\":\"1px\",\"width\":\"160px\",\"padding-left\":\"0px\",\"padding-top\":\"0px\",\"padding-right\":\"0px\",\"padding-bottom\":\"0px\"}"
    );





/*************************************
*   WC_Email_New_Order
* ***********************************/
$mailbuilder_subject['WC_Email_New_Order'] = __( '[{site_title}] New customer order ({order_number}) - {order_date}', 'woocommerce' );

// change placeholder names
$mailbuilder_subject['WC_Email_New_Order'] = str_replace( '[{site_title}]', '[WEBSITE_NAME] - ', $mailbuilder_subject['WC_Email_New_Order'] );
$mailbuilder_subject['WC_Email_New_Order'] = str_replace( '{order_number}', '[ORDER_NUMBER]', $mailbuilder_subject['WC_Email_New_Order'] );
$mailbuilder_subject['WC_Email_New_Order'] = str_replace( '{order_date}', '[ORDER_DATE]', $mailbuilder_subject['WC_Email_New_Order'] );


$mailbuilder_content['WC_Email_New_Order'] = array(
        "mb-1459256221516" => array(
                "id"        =>   "mb-1459256221516",
                "type"      =>   "text",
                "content"   => array(
                    "content"   =>  "<h1>" . __( 'New customer order', 'woocommerce' ) . "</h1><p>".sprintf( __( 'You have received an order from %s. The order is as follows:', 'woocommerce' ), "[BILLING_FULL_NAME]" )."</p>"
                )
            ),
        "mb-1460667961059" => array(
                "id"        =>   "mb-1460667961059",
                "type"      =>   "productstable",
                "content"   =>   $productstable_admin
            ),
        "mb-1462224620039" => array(
                "id"        =>   "mb-1462224620039",
                "type"      =>   "text",
                "content"   =>   array(
                    "content"   =>  
                            "<h3 style=\"text-align: center;\">".__( 'Customer details', 'woocommerce' )."</h3>"
                            ."<p style=\"text-align: center;\">[CUSTOMER_DETAILS]</p>"
                )
            ),
        "mb-1459258484139" => array(
                "id"        =>   "mb-1459258484139",
                "type"      =>   "twocol",
                "content"   => array(
                    "col1"      => "<h2>" . __('Billing address','woocommerce') . "</h2><p>[BILLING_ADDRESS]</p>",
                    "col2"      => "<h2 style=\"text-align: right;\">" . __('Shipping address','woocommerce') . "</h2><p style=\"text-align: right;\">[SHIPPING_ADDRESS]</p>"
                )
            ),
    );


/*************************************
*   WC_Email_Cancelled_Order
* ***********************************/
$mailbuilder_subject['WC_Email_Cancelled_Order'] = __( '[{site_title}] Cancelled order ({order_number})', 'woocommerce' );

// change placeholder names
$mailbuilder_subject['WC_Email_Cancelled_Order'] = str_replace( '[{site_title}]', '[WEBSITE_NAME] - ', $mailbuilder_subject['WC_Email_Cancelled_Order'] );
$mailbuilder_subject['WC_Email_Cancelled_Order'] = str_replace( '{order_number}', '[ORDER_NUMBER]', $mailbuilder_subject['WC_Email_Cancelled_Order'] );


$mailbuilder_content['WC_Email_Cancelled_Order'] = array(
        "mb-1459256221516" => array(
                "id"        =>   "mb-1459256221516",
                "type"      =>   "text",
                "content"   => array(
                    "content"   =>  "<h1>" 
                                .str_replace(
                                    '9999999',
                                    '[ORDER_NUMBER]', 
                                    __( 'Cancelled order', 'woocommerce' ) 
                                    ."</h1><p>"
                                    .sprintf( __( 'The order #%1$s from %2$s has been cancelled. The order was as follows:', 'woocommerce' ), 9999999, "[BILLING_FULL_NAME]" ) 
                                ) 
                                . "</p>" // use 9999999 as numeric placeholder for sprintf and replace afterwards
                                . "<h2><a class=\"link\" href=\"[EDIT_ORDER_URL]\">"
                                . str_replace(
                                    '9999999',
                                    '[ORDER_NUMBER]',
                                    sprintf( __( 'Order #%s', 'woocommerce'), 9999999 )
                                )
                                ."</a> ([ORDER_DATE])</h2>"
                )
            ),
        "mb-1460667961059" => array(
                "id"        =>   "mb-1460667961059",
                "type"      =>   "productstable",
                "content"   =>   $productstable_admin
            ),
        "mb-1462224620039" => array(
                "id"        =>   "mb-1462224620039",
                "type"      =>   "text",
                "content"   =>   array(
                    "content"   =>  
                            "<h3 style=\"text-align: center;\">".__( 'Customer details', 'woocommerce' )."</h3>"
                            ."<p style=\"text-align: center;\">[CUSTOMER_DETAILS]</p>"
                )
            ),
        "mb-1459258484139" => array(
                "id"        =>   "mb-1459258484139",
                "type"      =>   "twocol",
                "content"   => array(
                    "col1"      => "<h2>" . __('Billing address','woocommerce') . "</h2><p>[BILLING_ADDRESS]</p>",
                    "col2"      => "<h2 style=\"text-align: right;\">" . __('Shipping address','woocommerce') . "</h2><p style=\"text-align: right;\">[SHIPPING_ADDRESS]</p>"
                )
            ),
    );




/*************************************
*   WC_Email_Failed_Order
* ***********************************/
$mailbuilder_subject['WC_Email_Failed_Order'] = __( '[{site_title}] Failed order ({order_number})', 'woocommerce' );

// change placeholder names
$mailbuilder_subject['WC_Email_Failed_Order'] = str_replace( '[{site_title}]', '[WEBSITE_NAME] - ', $mailbuilder_subject['WC_Email_Failed_Order'] );
$mailbuilder_subject['WC_Email_Failed_Order'] = str_replace( '{order_number}', '[ORDER_NUMBER]', $mailbuilder_subject['WC_Email_Failed_Order'] );


$mailbuilder_content['WC_Email_Failed_Order'] = array(
        "mb-1459256221516" => array(
                "id"        =>   "mb-1459256221516",
                "type"      =>   "text",
                "content"   => array(
                    "content"   =>  "<h1>" 
                                .str_replace(
                                    '9999999',
                                    '[ORDER_NUMBER]', 
                                    __( 'Failed order', 'woocommerce' ) 
                                    ."</h1><p>"
                                    .sprintf( __( 'Payment for order #%1$s from %2$s has failed. The order was as follows:', 'woocommerce' ), 9999999, "[BILLING_FULL_NAME]" ) 
                                ) 
                                . "</p>" // use 9999999 as numeric placeholder for sprintf and replace afterwards
                                . "<h2><a class=\"link\" href=\"[EDIT_ORDER_URL]\">"
                                . str_replace(
                                    '9999999',
                                    '[ORDER_NUMBER]',
                                    sprintf( __( 'Order #%s', 'woocommerce'), 9999999 )
                                )
                                ."</a> ([ORDER_DATE])</h2>"
                )
            ),
        "mb-1460667961059" => array(
                "id"        =>   "mb-1460667961059",
                "type"      =>   "productstable",
                "content"   =>   $productstable_admin
            ),
        "mb-1462224620039" => array(
                "id"        =>   "mb-1462224620039",
                "type"      =>   "text",
                "content"   =>   array(
                    "content"   =>  
                            "<h3 style=\"text-align: center;\">".__( 'Customer details', 'woocommerce' )."</h3>"
                            ."<p style=\"text-align: center;\">[CUSTOMER_DETAILS]</p>"
                )
            ),
        "mb-1459258484139" => array(
                "id"        =>   "mb-1459258484139",
                "type"      =>   "twocol",
                "content"   => array(
                    "col1"      => "<h2>" . __('Billing address','woocommerce') . "</h2><p>[BILLING_ADDRESS]</p>",
                    "col2"      => "<h2 style=\"text-align: right;\">" . __('Shipping address','woocommerce') . "</h2><p style=\"text-align: right;\">[SHIPPING_ADDRESS]</p>"
                )
            ),
    );





/*************************************
*   WC_Email_Customer_Processing_Order
* ***********************************/
$mailbuilder_subject['WC_Email_Customer_Processing_Order'] = __( 'Your {site_title} order receipt from {order_date}', 'woocommerce' );

// change placeholder names
$mailbuilder_subject['WC_Email_Customer_Processing_Order'] = str_replace( '{site_title}', '[WEBSITE_NAME] - ', $mailbuilder_subject['WC_Email_Customer_Processing_Order'] );
$mailbuilder_subject['WC_Email_Customer_Processing_Order'] = str_replace( '{order_date}', '[ORDER_DATE]', $mailbuilder_subject['WC_Email_Customer_Processing_Order'] );


$mailbuilder_content['WC_Email_Customer_Processing_Order'] = array(
        "mb-1459256221516" => array(
                "id"        =>   "mb-1459256221516",
                "type"      =>   "text",
                "content"   => array(
                    "content"   =>  "<h1>" 
                                .__( 'Thank you for your order', 'woocommerce' ) 
                                ."</h1><p>"
                                .__( 'Your order has been received and is now being processed. Your order details are shown below for your reference:', 'woocommerce' )
                                . "</p>" 
                                . "[PAYMENT_INSTRUCTIONS]"
                                . "<h2>"
                                . str_replace(
                                    '9999999',
                                    '[ORDER_NUMBER]',
                                    sprintf( __( 'Order #%s', 'woocommerce'), 9999999 )
                                )
                                ."</h2>"
                )
            ),
        "mb-1460667961059" => array(
                "id"        =>   "mb-1460667961059",
                "type"      =>   "productstable",
                "content"   =>   $productstable_customer
            ),
        "mb-1462224620039" => array(
                "id"        =>   "mb-1462224620039",
                "type"      =>   "text",
                "content"   =>   array(
                    "content"   =>  
                            "<h3 style=\"text-align: center;\">".__( 'Customer details', 'woocommerce' )."</h3>"
                            ."<p style=\"text-align: center;\">[CUSTOMER_DETAILS]</p>"
                )
            ),
        "mb-1459258484139" => array(
                "id"        =>   "mb-1459258484139",
                "type"      =>   "twocol",
                "content"   => array(
                    "col1"      => "<h2>" . __('Billing address','woocommerce') . "</h2><p>[BILLING_ADDRESS]</p>",
                    "col2"      => "<h2 style=\"text-align: right;\">" . __('Shipping address','woocommerce') . "</h2><p style=\"text-align: right;\">[SHIPPING_ADDRESS]</p>"
                )
            ),
    );



/*************************************
*   WC_Email_Customer_On_Hold_Order
* ***********************************/
$mailbuilder_subject['WC_Email_Customer_On_Hold_Order'] = __( 'Your {site_title} order receipt from {order_date}', 'woocommerce' );

// change placeholder names
$mailbuilder_subject['WC_Email_Customer_On_Hold_Order'] = str_replace( '{site_title}', '[WEBSITE_NAME] - ', $mailbuilder_subject['WC_Email_Customer_On_Hold_Order'] );
$mailbuilder_subject['WC_Email_Customer_On_Hold_Order'] = str_replace( '{order_date}', '[ORDER_DATE]', $mailbuilder_subject['WC_Email_Customer_On_Hold_Order'] );


$mailbuilder_content['WC_Email_Customer_On_Hold_Order'] = array(
        "mb-1459256221516" => array(
                "id"        =>   "mb-1459256221516",
                "type"      =>   "text",
                "content"   => array(
                    "content"   =>  "<h1>" 
                                .__( 'Thank you for your order', 'woocommerce' ) 
                                ."</h1><p>"
                                .__( 'Your order is on-hold until we confirm payment has been received. Your order details are shown below for your reference:', 'woocommerce' )
                                . "</p>" 
                                . "[PAYMENT_INSTRUCTIONS]"
                                . "<h2>"
                                . str_replace(
                                    '9999999',
                                    '[ORDER_NUMBER]',
                                    sprintf( __( 'Order #%s', 'woocommerce'), 9999999 )
                                )
                                ."</h2>"
                )
            ),
        "mb-1460667961059" => array(
                "id"        =>   "mb-1460667961059",
                "type"      =>   "productstable",
                "content"   =>   $productstable_customer
            ),
        "mb-1462224620039" => array(
                "id"        =>   "mb-1462224620039",
                "type"      =>   "text",
                "content"   =>   array(
                    "content"   =>  
                            "<h3 style=\"text-align: center;\">".__( 'Customer details', 'woocommerce' )."</h3>"
                            ."<p style=\"text-align: center;\">[CUSTOMER_DETAILS]</p>"
                )
            ),
        "mb-1459258484139" => array(
                "id"        =>   "mb-1459258484139",
                "type"      =>   "twocol",
                "content"   => array(
                    "col1"      => "<h2>" . __('Billing address','woocommerce') . "</h2><p>[BILLING_ADDRESS]</p>",
                    "col2"      => "<h2 style=\"text-align: right;\">" . __('Shipping address','woocommerce') . "</h2><p style=\"text-align: right;\">[SHIPPING_ADDRESS]</p>"
                )
            ),
    );





/*************************************
*   WC_Email_Customer_Completed_Order
* ***********************************/
$mailbuilder_subject['WC_Email_Customer_Completed_Order'] = __( 'Your {site_title} order from {order_date} is complete', 'woocommerce' );

// change placeholder names
$mailbuilder_subject['WC_Email_Customer_Completed_Order'] = str_replace( '{site_title}', '[WEBSITE_NAME] - ', $mailbuilder_subject['WC_Email_Customer_Completed_Order'] );
$mailbuilder_subject['WC_Email_Customer_Completed_Order'] = str_replace( '{order_date}', '[ORDER_DATE]', $mailbuilder_subject['WC_Email_Customer_Completed_Order'] );


$mailbuilder_content['WC_Email_Customer_Completed_Order'] = array(
        "mb-1459256221516" => array(
                "id"        =>   "mb-1459256221516",
                "type"      =>   "text",
                "content"   => array(
                    "content"   =>  "<h1>" 
                                .__( 'Your order is complete', 'woocommerce' ) 
                                ."</h1><p>"
                                .sprintf( __( 'Hi there. Your recent order on %s has been completed. Your order details are shown below for your reference:', 'woocommerce' ), "[WEBSITE_NAME]" )
                                . "</p>" 
                                . "<h2>"
                                . str_replace(
                                    '9999999',
                                    '[ORDER_NUMBER]',
                                    sprintf( __( 'Order #%s', 'woocommerce'), 9999999 )
                                )
                                ."</h2>"
                )
            ),
        "mb-1460667961059" => array(
                "id"        =>   "mb-1460667961059",
                "type"      =>   "productstable",
                "content"   =>   $productstable_customer
            ),
        "mb-1462224620039" => array(
                "id"        =>   "mb-1462224620039",
                "type"      =>   "text",
                "content"   =>   array(
                    "content"   =>  
                            "<h3 style=\"text-align: center;\">".__( 'Customer details', 'woocommerce' )."</h3>"
                            ."<p style=\"text-align: center;\">[CUSTOMER_DETAILS]</p>"
                )
            ),
        "mb-1459258484139" => array(
                "id"        =>   "mb-1459258484139",
                "type"      =>   "twocol",
                "content"   => array(
                    "col1"      => "<h2>" . __('Billing address','woocommerce') . "</h2><p>[BILLING_ADDRESS]</p>",
                    "col2"      => "<h2 style=\"text-align: right;\">" . __('Shipping address','woocommerce') . "</h2><p style=\"text-align: right;\">[SHIPPING_ADDRESS]</p>"
                )
            ),
    );





/*************************************
*   WC_Email_Customer_Invoice
* ***********************************/
$mailbuilder_subject['WC_Email_Customer_Invoice'] = __( 'Invoice for order {order_number}', 'woocommerce' );

// change placeholder names
$mailbuilder_subject['WC_Email_Customer_Invoice'] = str_replace( '{order_number}', '[ORDER_NUMBER]', $mailbuilder_subject['WC_Email_Customer_Invoice'] );
$mailbuilder_subject['WC_Email_Customer_Invoice'] = str_replace( '{order_date}', '[ORDER_DATE]', $mailbuilder_subject['WC_Email_Customer_Invoice'] );


$mailbuilder_content['WC_Email_Customer_Invoice'] = array(
        "mb-1459256221516" => array(
                "id"        =>   "mb-1459256221516",
                "type"      =>   "text",
                "content"   => array(
                    "content"   =>  "<h1>" 
                                .str_replace(
                                    '{order_number}',
                                    '[ORDER_NUMBER]', 
                                    __( 'Invoice for order {order_number}', 'woocommerce' )
                                ) 
                                ."</h1><p>"
                                .sprintf( __( 'An order has been created for you on %1$s. %2$s', 'woocommerce' ), '[WEBSITE_NAME]', '<a href="[PAYMENT_URL]">' . __( 'Pay for this order', 'woocommerce' ) . '</a>' )
                                . "</p>"
                                . "[PAYMENT_INSTRUCTIONS]" 
                                . "<h2>"
                                . str_replace(
                                    '9999999',
                                    '[ORDER_NUMBER]',
                                    sprintf( __( 'Order #%s', 'woocommerce'), 9999999 )
                                )
                                ."</h2>"
                )
            ),
        "mb-1460667961059" => array(
                "id"        =>   "mb-1460667961059",
                "type"      =>   "productstable",
                "content"   =>   $productstable_customer
            ),
        "mb-1462224620039" => array(
                "id"        =>   "mb-1462224620039",
                "type"      =>   "text",
                "content"   =>   array(
                    "content"   =>  
                            "<h3 style=\"text-align: center;\">".__( 'Customer details', 'woocommerce' )."</h3>"
                            ."<p style=\"text-align: center;\">[CUSTOMER_DETAILS]</p>"
                )
            ),
        "mb-1459258484139" => array(
                "id"        =>   "mb-1459258484139",
                "type"      =>   "twocol",
                "content"   => array(
                    "col1"      => "<h2>" . __('Billing address','woocommerce') . "</h2><p>[BILLING_ADDRESS]</p>",
                    "col2"      => "<h2 style=\"text-align: right;\">" . __('Shipping address','woocommerce') . "</h2><p style=\"text-align: right;\">[SHIPPING_ADDRESS]</p>"
                )
            ),
    );





/*************************************
*   WC_Email_Customer_Refunded_Order
* ***********************************/
$mailbuilder_subject['WC_Email_Customer_Refunded_Order'] = __( 'Your {site_title} order from {order_date} has been refunded', 'woocommerce' );

// change placeholder names
$mailbuilder_subject['WC_Email_Customer_Refunded_Order'] = str_replace( '{site_title}', '[WEBSITE_NAME]', $mailbuilder_subject['WC_Email_Customer_Refunded_Order'] );
$mailbuilder_subject['WC_Email_Customer_Refunded_Order'] = str_replace( '{order_date}', '[ORDER_DATE]', $mailbuilder_subject['WC_Email_Customer_Refunded_Order'] );


$mailbuilder_content['WC_Email_Customer_Refunded_Order'] = array(
        "mb-1459256221516" => array(
                "id"        =>   "mb-1459256221516",
                "type"      =>   "text",
                "content"   => array(
                    "content"   =>  "<h1>" 
                                .__( 'Your order has been fully refunded', 'woocommerce' ) 
                                ."</h1>"
                                . "<p>".sprintf( __( 'Hi there. Your order on %s has been refunded.', 'woocommerce' ), '[WEBSITE_NAME]' )."</p>"
                                . "<h2>"
                                . str_replace(
                                    '9999999',
                                    '[ORDER_NUMBER]',
                                    sprintf( __( 'Order #%s', 'woocommerce'), 9999999 )
                                )
                                ."</h2>"
                )
            ),
        "mb-1460667961059" => array(
                "id"        =>   "mb-1460667961059",
                "type"      =>   "productstable",
                "content"   =>   $productstable_customer
            ),
        "mb-1462224620039" => array(
                "id"        =>   "mb-1462224620039",
                "type"      =>   "text",
                "content"   =>   array(
                    "content"   =>  
                            "<h3 style=\"text-align: center;\">".__( 'Customer details', 'woocommerce' )."</h3>"
                            ."<p style=\"text-align: center;\">[CUSTOMER_DETAILS]</p>"
                )
            ),
        "mb-1459258484139" => array(
                "id"        =>   "mb-1459258484139",
                "type"      =>   "twocol",
                "content"   => array(
                    "col1"      => "<h2>" . __('Billing address','woocommerce') . "</h2><p>[BILLING_ADDRESS]</p>",
                    "col2"      => "<h2 style=\"text-align: right;\">" . __('Shipping address','woocommerce') . "</h2><p style=\"text-align: right;\">[SHIPPING_ADDRESS]</p>"
                )
            ),
    );





/*************************************
*   WC_Email_Customer_Note
* ***********************************/
$mailbuilder_subject['WC_Email_Customer_Note'] = __( 'Note added to your {site_title} order from {order_date}', 'woocommerce' );

// change placeholder names
$mailbuilder_subject['WC_Email_Customer_Note'] = str_replace( '{site_title}', '[WEBSITE_NAME]', $mailbuilder_subject['WC_Email_Customer_Note'] );
$mailbuilder_subject['WC_Email_Customer_Note'] = str_replace( '{order_date}', '[ORDER_DATE]', $mailbuilder_subject['WC_Email_Customer_Note'] );


$mailbuilder_content['WC_Email_Customer_Note'] = array(
        "mb-1459256221516" => array(
                "id"        =>   "mb-1459256221516",
                "type"      =>   "text",
                "content"   => array(
                    "content"   =>  "<h1>" 
                                .__( 'A note has been added to your order', 'woocommerce' ) 
                                ."</h1>"
                                . "<p>".__( 'Hello, a note has just been added to your order:', 'woocommerce' )."</p>"
                                . "<p>[CUSTOMER_NOTE]</p>"
                                . "<p>".__( 'For your reference, your order details are shown below.', 'woocommerce' )."</p>"
                                . "<h2>"
                                . str_replace(
                                    '9999999',
                                    '[ORDER_NUMBER]',
                                    sprintf( __( 'Order #%s', 'woocommerce'), 9999999 )
                                )
                                ."</h2>"
                )
            ),
        "mb-1460667961059" => array(
                "id"        =>   "mb-1460667961059",
                "type"      =>   "productstable",
                "content"   =>   $productstable_customer
            ),
        "mb-1462224620039" => array(
                "id"        =>   "mb-1462224620039",
                "type"      =>   "text",
                "content"   =>   array(
                    "content"   =>  
                            "<h3 style=\"text-align: center;\">".__( 'Customer details', 'woocommerce' )."</h3>"
                            ."<p style=\"text-align: center;\">[CUSTOMER_DETAILS]</p>"
                )
            ),
        "mb-1459258484139" => array(
                "id"        =>   "mb-1459258484139",
                "type"      =>   "twocol",
                "content"   => array(
                    "col1"      => "<h2>" . __('Billing address','woocommerce') . "</h2><p>[BILLING_ADDRESS]</p>",
                    "col2"      => "<h2 style=\"text-align: right;\">" . __('Shipping address','woocommerce') . "</h2><p style=\"text-align: right;\">[SHIPPING_ADDRESS]</p>"
                )
            ),
    );


/*************************************
*   WC_Email_Customer_Reset_Password
* ***********************************/
$mailbuilder_subject['WC_Email_Customer_Reset_Password'] = __( 'Password Reset for {site_title}', 'woocommerce' );

// change placeholder names
$mailbuilder_subject['WC_Email_Customer_Reset_Password'] = str_replace( '{site_title}', '[WEBSITE_NAME]', $mailbuilder_subject['WC_Email_Customer_Reset_Password'] );


$mailbuilder_content['WC_Email_Customer_Reset_Password'] = array(
        "mb-1459256221516" => array(
                "id"        =>   "mb-1459256221516",
                "type"      =>   "text",
                "content"   => array(
                    "content"   =>  "<h1>" 
                                .__( 'Password Reset Instructions', 'woocommerce' ) 
                                . "</h1>"
                                . "<p>".__( 'Someone requested that the password be reset for the following account:', 'woocommerce' )."</p>"
                                . "<p>".sprintf( __( 'Username: %s', 'woocommerce' ), '[USERNAME]' ).'</p>'
                                . "<p>".__( 'If this was a mistake, just ignore this email and nothing will happen.', 'woocommerce' ).'</p>'
                                . "<p>".__( 'To reset your password, visit the following address:', 'woocommerce' ).'</p>'
                                . "<p><a class=\"link\" href=\"[RESET_PASSWORD_URL]\">"
                                . __( 'Click here to reset your password', 'woocommerce' )
                                . "</a></p><p></p>"
                )
            ),
    );





/*************************************
*   WC_Email_Customer_New_Account
* ***********************************/
$mailbuilder_subject['WC_Email_Customer_New_Account'] = __( 'Your account on {site_title}', 'woocommerce' );

// change placeholder names
$mailbuilder_subject['WC_Email_Customer_New_Account'] = str_replace( '{site_title}', '[WEBSITE_NAME]', $mailbuilder_subject['WC_Email_Customer_New_Account'] );


$mailbuilder_content['WC_Email_Customer_New_Account'] = array(
        "mb-1459256221516" => array(
                "id"        =>   "mb-1459256221516",
                "type"      =>   "text",
                "content"   => array(
                    "content"   =>  "<h1>" 
                                . str_replace( '{site_title}', '[WEBSITE_NAME]', __( 'Welcome to {site_title}', 'woocommerce' ) )
                                . "</h1>"
                                . "<p>".sprintf( __( "Thanks for creating an account on %s. Your username is <strong>%s</strong>.", 'woocommerce' ), '[WEBSITE_NAME]', '[USERNAME]' ).'</p>'
                                . "<p>".sprintf( __( "Your password has been automatically generated: <strong>%s</strong>", 'woocommerce' ), '[NEW_PASSWORD]' ).'</p>'
                                . "<p>".sprintf( __( 'You can access your account area to view your orders and change your password here: %s.', 'woocommerce' ), '[MY_ACCOUNT_URL]' ).'</p>'
                                . "<p></p>"
                )
            ),
    );



if(is_plugin_active( 'woocommerce-german-market/WooCommerce-German-Market.php' )){
    /*************************************
    *   WooCommerce German Market Footer
    * ***********************************/
    $wgm = array(
            "wgmcontent" => "{\"imprint\":true,\"terms\":true,\"cancellation_policy\":true,\"cancellation_policy_for_digital_goods\":true,\"cancellation_policy_for_digital_goods_acknowlagement\":true,\"delivery\":true,\"payment_methods\":true}",
            "wgmstyle"  =>  "{\"h1-font-family\":\"Arial, Helvetica, sans-serif\",\"h1-font-size\":\"14\",\"h1-font-weight\":\"bold\",\"h1-font-style\":\"italic\",\"h1-text-align\":\"left\",\"h1-color\":\"#888888\",\"h2-font-family\":\"Arial, Helvetica, sans-serif\",\"h2-font-size\":\"13\",\"h2-font-weight\":\"bold\",\"h2-font-style\":\"italic\",\"h2-text-align\":\"left\",\"h2-color\":\"#888888\",\"h3-font-family\":\"Arial, Helvetica, sans-serif\",\"h3-font-size\":\"12\",\"h3-font-weight\":\"bold\",\"h3-font-style\":\"normal\",\"h3-text-align\":\"left\",\"h3-color\":\"#888888\",\"text-font-family\":\"Arial, Helvetica, sans-serif\",\"text-font-size\":\"12\",\"text-font-weight\":\"normal\",\"text-font-style\":\"normal\",\"text-text-align\":\"left\",\"text-color\":\"#888888\"}"
        );




    /*************************************
    *   WooCommerce German Market
    *   WGM_Email_Confirm_Order
    * ***********************************/

    $mailbuilder_subject['WGM_Email_Confirm_Order'] = __( 'Your {site_title} order confirmation from {order_date}', 'woocommerce-german-market' );

    // change placeholder names
    $mailbuilder_subject['WGM_Email_Confirm_Order'] = str_replace( '{site_title}', '[WEBSITE_NAME]', $mailbuilder_subject['WGM_Email_Confirm_Order'] );
    $mailbuilder_subject['WGM_Email_Confirm_Order'] = str_replace( '{order_date}', '[ORDER_DATE]', $mailbuilder_subject['WGM_Email_Confirm_Order'] );


    $mailbuilder_content['WGM_Email_Confirm_Order'] = array(
            "mb-1459256221598" => array(
                    "id"        =>   "mb-1459256221598",
                    "type"      =>   "text",
                    "content"   => array(
                        "content"   =>  "<h1>" 
                                    .__( 'Order Confirmation', 'woocommerce-german-market' ) 
                                    ."</h1><p>"
                                    .apply_filters(
                                        'wgm_customer_received_order_email_text',
                                        __( 'With this e-mail we confirm that we have received your order. However, this is not a legally binding offer until payment is received.', 'woocommerce-german-market' )
                                    )
                                    . "</p>" 
                                    . "<h2>"
                                    . str_replace(
                                        '9999999',
                                        '[ORDER_NUMBER]',
                                        sprintf( __( 'Order #%s', 'woocommerce'), 9999999 )
                                    )
                                    ."</h2>"
                    )
                ),
            "mb-1460667961099" => array(
                    "id"        =>   "mb-1460667961099",
                    "type"      =>   "productstable",
                    "content"   =>   $productstable_customer
                ),
            "mb-1462224620048" => array(
                    "id"        =>   "mb-1462224620048",
                    "type"      =>   "text",
                    "content"   =>   array(
                        "content"   =>  
                                "<h3 style=\"text-align: center;\">".__( 'Customer details', 'woocommerce' )."</h3>"
                                ."<p style=\"text-align: center;\">[CUSTOMER_DETAILS]</p>"
                    )
                ),
            "mb-1459258484189" => array(
                    "id"        =>   "mb-1459258484189",
                    "type"      =>   "twocol",
                    "content"   => array(
                        "col1"      => "<h2>" . __('Billing address','woocommerce') . "</h2><p>[BILLING_ADDRESS]</p>",
                        "col2"      => "<h2 style=\"text-align: right;\">" . __('Shipping address','woocommerce') . "</h2><p style=\"text-align: right;\">[SHIPPING_ADDRESS]</p>"
                    )
                ),
        );




    /*************************************
    *   WooCommerce German Market
    *   WGM_Email_Double_Opt_In_Customer_Registration
    * ***********************************/

    $mailbuilder_subject['WGM_Email_Double_Opt_In_Customer_Registration'] = __( 'Activate your account - {site_title}', 'woocommerce-german-market' );

    // change placeholder names
    $mailbuilder_subject['WGM_Email_Double_Opt_In_Customer_Registration'] = str_replace( '{site_title}', '[WEBSITE_NAME]', $mailbuilder_subject['WGM_Email_Double_Opt_In_Customer_Registration'] );



    $mailbuilder_content['WGM_Email_Double_Opt_In_Customer_Registration'] = array(
            "mb-21459256221598" => array(
                    "id"        =>   "mb-21459256221598",
                    "type"      =>   "text",
                    "content"   => array(
                        "content"   =>  "<h1>" 
                                    .str_replace( '{site_title}', '[WEBSITE_NAME]', apply_filters( 'wgm_double_opt_in_activation_email_heading', __( 'Activate your account - {site_title}', 'woocommerce-german-market' ) ) )
                                    ."</h1><p>"
                                    .sprintf( __( 'Thanks for creating a customer account on %s. Your username is %s. Please follow the activation link to activate your account:', 'woocommerce-german-market' ), '[WEBSITE_NAME]', '<strong>[USERNAME]</strong>' )
                                    . "</p>" 
                                    . "<p><a href='[ACTIVATION_URL]'>Click here to Activate Account</a></p>"
                                    . "<p>"
                                    . sprintf( __( 'If you haven\'t created an account on %s please ignore this email.', 'woocommerce-german-market' ), '[WEBSITE_NAME]' )
                                    . "</p>"
                    )
                ),
        );




    // append WGM legal informations to customer emails
    $mailbuilder_content['WC_Email_Customer_Processing_Order']["mb-1466672065455"] = array(
                    "id"        =>   "mb-1466672065455",
                    "type"      =>   "wgm",
                    "content"   =>   $wgm
                );

    $mailbuilder_content['WGM_Email_Confirm_Order']["mb-1466672065455"] = array(
                    "id"        =>   "mb-1466672065455",
                    "type"      =>   "wgm",
                    "content"   =>   $wgm
                );
}
