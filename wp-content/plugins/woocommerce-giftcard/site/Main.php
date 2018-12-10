<?php
/**
 * Created by PhpStorm.
 * User: doanhcn2
 * Date: 03/08/2018
 * Time: 13:37
 */

namespace site;


class Main
{
    private static $giftcard_instance;

    /** plugin version number */
    const VERSION = '4.3';

    /** plugin text domain */
    const TEXT_DOMAIN = 'GIFTCARD';

    public function __construct()
    {
        add_action('init', array($this, 'create_post_type'));  // create gift card menu, template email menu
        add_filter( 'plugin_row_meta', array( __CLASS__, 'plugin_row_meta' ), 10, 2 );
        add_action('wp_enqueue_scripts', array($this, 'load_custom_scripts'));
        add_action('init', array($this, 'remove_giftcard_code'));
        add_action('wp_ajax_removeapplygiftcard', array($this, 'remove_giftcard_code'));
        add_action('wp_ajax_nopriv_removeapplygiftcard', array($this, 'remove_giftcard_code'));
        add_action('init', array($this, 'load_class_giftcard'));
        add_filter('page_row_actions', array($this, 'add_sendmail_link'), 10, 2);

        if (is_admin()) {
            add_action('save_post', array($this, 'add_metakey_postmeta'), 10, 2);
            add_action('admin_enqueue_scripts', array($this, 'load_admin_scripts'), 99);
            add_action('admin_menu', array($this, 'admin_menu'), 5);
            add_action('admin_init', array($this, 'hook_emailTemplate'), 1);
            // download ticket for customer
//            add_action('wp_loaded', array($this, 'printPDF'));
            add_action('restrict_manage_posts', array($this, 'add_customer_filter'));
            add_filter('parse_query', array($this, 'wpse45436_posts_filter'));
            add_action('woocommerce_settings_saved', array($this, 'save_admin_setting'), 10);

            new \admin\PdfSettings();
            new \admin\ImportGiftcardController();
            new \admin\EmailTemplateSetting();
            new \admin\MagenestGiftcardAdmin();

        }
        add_action('woocommerce_thankyou', array($this, 'custom_woocommerce_auto_complete_order'), 20);
        /*add menu item ticket*/
        add_action('init', array($this, 'add_endPoint'));
        add_filter('query_var', array($this, 'add_endPoint_query'), 0);
        add_filter('woocommerce_account_menu_items', array($this, 'add_menu_item'), 10, 1);
        add_action('woocommerce_account_mygiftcard_endpoint', array($this, 'manage_giftcard_frontend'));

        add_action('gc_schedule_send_mail', array('model\ScheduleSendMail', 'ScheduleSendMail'));
        add_action( 'admin_post_send_giftcard', array( $this, 'send_giftcard_action' ) );
        add_action( 'admin_post_printpdf', array('admin\MagenestGiftcardAdmin', 'printpdf_action' ) );
        add_action( 'woocommerce_admin_order_totals_after_discount', array('admin\MagenestGiftcardAdmin', 'display_giftcard_on_order' ) );
        add_action( 'admin_notices', array('admin\MagenestGiftcardAdmin', 'admin_notices' ) );
        add_action('wp_ajax_get_info_email', array($this, 'getPreContentEmailAdminSend'));
    }
    /**
     * Show row meta on the plugin screen.
     *
     * @param   mixed $links Plugin Row Meta.
     * @param   mixed $file  Plugin Base file.
     * @return  array
     */
    public static function plugin_row_meta( $links, $file ) {
        if ( GIFTCARD_FILE === $file ) {
            $row_meta = array(
                'docs'    => '<a href="' . esc_url( 'http://www.confluence.izysync.com/display/DOC/Ultimate+Gift+Card+Pro+User+Guide'  ) . '" aria-label="' . esc_attr__( 'View Gift Card documentation', GIFTCARD_TEXT_DOMAIN ) . '" target="_blank">' . esc_html__( 'Docs', GIFTCARD_TEXT_DOMAIN ) . '</a>',
                'support' => '<a href="' . esc_url('http://servicedesk.izysync.com/servicedesk/customer/portal/107' ) . '" aria-label="' . esc_attr__( 'Visit customer support', GIFTCARD_TEXT_DOMAIN ) . '" target="_blank">' . esc_html__( 'Support', GIFTCARD_TEXT_DOMAIN ) . '</a>',
            );

            return array_merge( $links, $row_meta );
        }

        return (array) $links;
    }

    public function save_admin_setting()
    {
        if (isset ($_FILES ['magenestgc_pdf_background']['name']) && $_FILES ['magenestgc_pdf_background']['name']) {
            if (isset ($_FILES ['magenestgc_pdf_background'] ['tmp_name']) && $_FILES ['magenestgc_pdf_background'] ['tmp_name']) {
                $path = GIFTCARD_PATH . 'assets/';

                $target_file = $path . $_FILES ['magenestgc_pdf_background']['name'];
                $fileType = $_FILES ['magenestgc_pdf_background'] ['type'];

                $fp = fopen($_FILES ['magenestgc_pdf_background'] ['tmp_name'], 'r');
                $content = fread($fp, filesize($_FILES ['magenestgc_pdf_background'] ['tmp_name']));
                fclose($fp);
                if (move_uploaded_file($_FILES["magenestgc_pdf_background"]["tmp_name"], $target_file)) {
                    update_option('magenestgc_pdf_background', $_FILES ['magenestgc_pdf_background']['name']);
                    //update_post_meta($post_id, '_background_img',  $_FILES ['_background_img']['name']);
                }
            }
        }
    }

    /*
     * start add memu item in my account page
     */
    function add_endPoint()
    {
        add_rewrite_endpoint('mygiftcard', EP_ROOT | EP_PAGES);
    }

    function add_endPoint_query($var)
    {
        $var[] = 'mygiftcard';

        return $var;
    }

    public function manage_giftcard_frontend()
    {
        $user_id = get_current_user_id();
        if (!class_exists('Magenest_Giftcard_Myaccount')) {
            include_once GIFTCARD_PATH . 'model/magenest-giftcard-myaccount.php';
        }
        $mygiftcard = new \Magenest_Giftcard_Myaccount();
        $mygiftcard->manage_customer_giftcard($user_id);
    }

    public function add_menu_item($items)
    {
//        $items[ 'mygiftcard' ] =  __('Gift Card', 'GIFTCARD');
        $dataItem = [];
        foreach ($items as $key => $item){
            if($key == 'orders'){
                $dataItem['mygiftcard'] = __('Gift Card', 'GIFTCARD');
            }
            $dataItem[$key] = $item;
        }
        return $dataItem;
    }

    // end add menu item
    function add_customer_filter()
    {
        $type = 'shop_giftcard';
        if (isset($_GET['post_type']) && $_GET['post_type'] == $type) {
            $values = array(
                'Admin created' => 'admin',
                'Customer created' => 'buyer',
            );
            ?>
            <select name="admin_filter">
                <option value="">
                    <?php _e('All person created ', 'wose45436'); ?>
                </option>
                <?php
                $current_v = isset($_GET['admin_filter']) ? $_GET['admin_filter'] : '';
                foreach ($values as $label => $value) {
                    printf(
                        '<option value="%s"%s>%s</option>', $value, $value == $current_v ? ' selected="selected"' : '', $label
                    );
                }
                ?>
            </select>
            <?php
        }
    }

    /**
     * if submitted filter by post meta
     *
     * make sure to change uset_id to the actual meta key
     * and POST_TYPE to the name of your custom post type
     * @author Ohad Raz
     *
     * @param  (wp_query object) $query
     *
     * @return Void
     */
    function wpse45436_posts_filter($query)
    {
        global $pagenow;
        $type = 'post';
        if (isset($_GET['post_type'])) {
            $type = $_GET['post_type'];
        }
        if ('shop_giftcard' == $type && is_admin() && $pagenow == 'edit.php' && isset($_GET['admin_filter']) && $_GET['admin_filter'] != '') {
            $query->query_vars['meta_key'] = 'mode';
            //$user_id = $_GET['admin_filter']
            //user_can( $user_id, 'manage_options' )
            $query->query_vars['meta_value'] = $_GET['admin_filter'];
        }
    }

    function remove_giftcard_code()
    {
        global $woocommerce, $wpdb;
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'removegiftcardcode') {
            $woocommerce->session->__unset('giftcard_discount');
            $woocommerce->session->__unset('giftcard_code');
            wc_add_notice(__('Gift card code removed successfully.', 'GIFTCARD'), 'success');
        }
    }

    function custom_woocommerce_auto_complete_order($order_id)
    {
        if (!$order_id) {
            return;
        }
        $order = wc_get_order($order_id);
        $paymentMethod = $order->get_payment_method();
        // No updated status for orders delivered with Bank wire, Cash on delivery and Cheque payment methods.
        //        if ( $paymentMethod == 'bacs' || $paymentMethod == 'cod' || $paymentMethod == 'cheque' ) {
        //            return;
        //        }
        if ($paymentMethod == 'paypal' || $paymentMethod == 'stripe') {
            $order->update_status('processing');
        }
        //        if($paymentMethod == 'stripe'){
        //            $order->update_status( 'completed' );
        //        }
    }

    public function preview_pdf()
    {
        $data = $_REQUEST['data_preview_pdf'];
        $result = $this->template_giftcard_pdf($data);
        $out['type'] = 'success';
        $out['result'] = $result;
        echo json_encode($out);
        wp_die();
    }

    public function template_giftcard_pdf($data)
    {
        ob_start();
        $template_path = GIFTCARD_PATH . 'template/';
        $default_path = GIFTCARD_PATH . 'template/';
        wc_get_template('preview-giftcard-pdf.php', array('data' => $data), $template_path, $default_path);
        $update = ob_get_clean();

        return $update;
    }

    public function update_order_status($order_status, $order_id)
    {
        $order = new \WC_Order($order_id);
        if ('processing' == $order_status && ('on-hold' == $order->status || 'pending' == $order->status || 'failed' == $order->status)) {
            return 'completed';
        }

        return $order_status;
    }

    public function add_metakey_postmeta($post_id, $post)
    {
        $type = $post->post_type;
        if ($type == 'shop_giftcard') {
            include_once GIFTCARD_PATH . 'admin/giftcard-savemeta.php';
            $metakey = new \Magenest_Giftcard_Savemeta();
            $metakey->updateGiftcard($post_id, $post);
        }
    }

    public function add_sendmail_link($actions, $object)
    {
        if ($object->post_type == 'shop_giftcard') {
            $actions = [];
            $giftcardId = $object->ID;//edit.php?post_type=shop_giftcard&page=giftcard_send_mail
            $is_sent = get_post_meta($giftcardId, 'gc_status', true);
            if ($is_sent == '-1') {

            } else {
                $actions['send_mail'] = "<a class='send-mail' href='" . admin_url("admin-post.php?action=send_giftcard&id={$giftcardId}") . "' id='" .$giftcardId."'>" . __('Resend Mail', 'GIFTCARD') . "</a>";
                $actions['printpdf'] = "<a class='printpdf' href='" . admin_url("admin-post.php?action=printpdf&id={$giftcardId}") . "'>" . __('Print PDF', 'GIFTCARD') . "</a>";
            }

        }

        return $actions;
    }
    public function getPreContentEmailAdminSend(){
        if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'get_info_email' && isset( $_REQUEST['id'] ) ) {
            try{
                $giftcardId       = $_REQUEST['id'];
                $post             = get_post( $giftcardId );
                $code             = $post->post_title;
                $balance   = get_post_meta($giftcardId, 'gc_balance', true );
                $to_email   = get_post_meta($giftcardId, 'gc_send_to_email', true );
                $email_template_id = get_post_meta($giftcardId, 'gc_email_template_id', true );
                if($email_template_id == "63") $email_template_id = 62;
                if($email_template_id){
                    $email_name = get_the_title($email_template_id);
                }else{
                    $email_name = "Default email";
                }
                // get mail
                $email_template = \model\EmailTemplate::getMailTemplate($email_template_id);
                $subject = $email_template['subject'];
                $content = $email_template['content'];
                $pdf_template_id = get_post_meta($giftcardId, 'gc_pdf_template_id', true );
                $pdf_name = get_the_title($pdf_template_id);
                $out = array(
                    'type' => 'success',
                    'code' => $code,
                    'balance' => $balance,
                    'send_to_mail' => $to_email,
                    'email_content' => $content,
                    'pdf_name' => $pdf_name,
                    'email_name' => $email_name
                );
            }catch (\Exception $e){
                $out['type'] = "error";
            }
            echo json_encode($out);
            wp_die();
        }
    }
    /**
     * process send/resend button in giftcard post type
     */
    public function send_giftcard_action()
    {
        if (function_exists('wc_add_notice')){
            $a = true;
        }
        if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'send_giftcard' && isset( $_REQUEST['id'] ) ) {
            $giftcardId       = $_REQUEST['id'];
            $post             = get_post( $giftcardId );
            $code             = $post->post_title;
            $giftcardInstance = new \model\Magenest_Giftcard( $code );
            //$order_id = get_post_meta($post, 'magenest_giftcard_order_id', 0);
            $giftcardInstance->send( $giftcardId );
//            $notices[ 'success' ][] = __('Send email for customer successfully!','GIFTCARD');
//            WC()->session->set( 'wc_notices', $notices );
            wp_redirect( admin_url( 'edit.php?post_type=shop_giftcard' ) );
        } else {
            wp_redirect( admin_url( 'edit.php?post_type=shop_giftcard' ) );
        }
    }

    public function load_admin_scripts()
    {
        global $woocommerce;
        if (is_object($woocommerce)) {
            wp_enqueue_style('woocommerce_admin_styles', $woocommerce->plugin_url() . '/assets/css/admin.css');
        }
        wp_enqueue_script('gcevent', GIFTCARD_URL . '/assets/sent_email_template.js');
        wp_enqueue_style('woocommerce_admin_style', GIFTCARD_URL . '/assets/style.css');
        wp_register_style('gc_product_config', GIFTCARD_URL . '/assets/css/product_config.css');
        wp_enqueue_style('jquery-select-area-css', GIFTCARD_URL . '/lib/jQuerySelectArea/resources/jquery.selectareas.css');
        wp_register_style('gc_style_ui', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');

        wp_enqueue_script("jquery-ui-core");
        wp_enqueue_script("jquery-ui-datepicker");
        wp_enqueue_media();


        wp_enqueue_script('jquery');

        if (!wp_script_is('jquery-ui-dialog', 'queue')) {
            wp_enqueue_script('jquery-ui-dialog');
        }
        if (!wp_script_is('jquery-ui-draggable', 'queue')) {
            wp_enqueue_script('jquery-ui-draggable');
        }
        if (!wp_script_is('jquery-ui-droppable', 'queue')) {
            wp_enqueue_script('jquery-ui-droppable');
        }
        if (!wp_script_is('jquery-ui-resizable', 'queue')) {
            wp_enqueue_script('jquery-ui-resizable');
        }
        if (!wp_script_is('jquery-ui-slider', 'queue')) {
            wp_enqueue_script('jquery-ui-slider');
        }

        wp_register_script('jquery-select-area', GIFTCARD_URL . '/lib/jQuerySelectArea/jquery.selectareas.js');
        wp_register_script('gc_image_background_pdf', GIFTCARD_URL . '/assets/js/choose-image.js');
        wp_register_script('gc_pdf_config', GIFTCARD_URL . '/assets/js/pdf_setup.js');
        wp_register_script('gc_product_conf', GIFTCARD_URL . '/assets/js/product_config.js');


    }

    public function load_custom_scripts($hook)
    {
        wp_enqueue_script('gc', GIFTCARD_URL . '/assets/event.js');
        wp_enqueue_style('magenestgiftcard', GIFTCARD_URL . '/assets/giftcard.css');
        wp_register_style('gc_front_end', GIFTCARD_URL . '/assets/css/gc_front_end.css');

        wp_register_style('datetimepickerstyle',  '//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css');
        wp_register_style('datetimepickerstandlonestyle',  '//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker-standalone.min.css');
        wp_register_style('boostrap',  '//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css');
        wp_register_script("momentjs", '//cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js');
        wp_register_script("datetimepicker", '//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js');

        wp_enqueue_script("jquery-ui");
        wp_enqueue_script("jquery-ui-selectable");
        if (!wp_script_is('jquery-ui-dialog', 'queue')) {
            wp_enqueue_script('jquery-ui-dialog');
        }
        //var_dump(wp_enqueue_script("jquery-ui-datepicker")); die();
        //jquery-ui-datepicker
        wp_enqueue_script('gc-form_validator', GIFTCARD_URL . '/assets/form-validator/jquery.form-validator.min.js');
        wp_register_script('gc-preview-email', GIFTCARD_URL . '/assets/js/preview_email.js');
        wp_register_script('gc-preview-pdf', GIFTCARD_URL . '/assets/js/preview-pdf-settings.js');
        wp_register_style('gc_preview_jquery', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');



    }

    public function create_post_type()
    {
        $show_in_menu = current_user_can('manage_woocommerce') ? 'woocommerce' : true;

        // register giftcard
        register_post_type('shop_giftcard', array(
            'labels' => array(
                'name' => __('Giftcard', 'GIFTCARD'),
                'singular_name' => __('Giftcard', 'GIFTCARD'),
                'menu_name' => _x('Giftcard', 'Admin menu name', 'GIFTCARD'),
                'add_new' => __('Add Giftcard', 'GIFTCARD'),
                'add_new_item' => __('Add Giftcard', 'GIFTCARD'),
                'edit' => __('Edit', 'GIFTCARD'),
                'edit_item' => __('Edit Giftcard', 'GIFTCARD'),
                'new_item' => __('Add Giftcard', 'GIFTCARD'),
                'view' => __('View Giftcard', 'GIFTCARD'),
                'view_item' => __('View Giftcard', 'GIFTCARD'),
                'search_items' => __('Search Giftcard', 'GIFTCARD'),
                'not_found' => __('No Giftcard found', 'GIFTCARD'),
                'not_found_in_trash' => __('No Giftcard found in trash', 'GIFTCARD'),
                'parent' => __('Parent Giftcard', 'GIFTCARD')
            ),
            'public' => true,
            'publicly_queryable' => false,
            'exclude_from_search' => false,
            'has_archive' => true,
            'show_in_menu' => true,
            'hierarchical' => true,
            'supports' => array(
                'title'
            ),
            'capabilities' => array(
                'create_posts' => false,
                //                'edit_posts' => true
            ),
            'map_meta_cap' => true,
        ));

        // register email template
        register_post_type('email_giftcard', array(
            'labels' => array(
                'name' => __('Email template', 'GIFTCARD'),
                'singular_name' => __('email_giftcard', 'GIFTCARD'),
                'menu_name' => _x('Email template', 'Admin menu name', 'GIFTCARD'),
                'add_new' => __('Add Email template', 'GIFTCARD'),
                'add_new_item' => __('Add Email template', 'GIFTCARD'),
                'edit' => __('Edit', 'GIFTCARD'),
                'edit_item' => __('Edit Email template', 'GIFTCARD'),
                'new_item' => false,
                'view' => __('View Email template', 'GIFTCARD'),
                'view_item' => __('View Email template', 'GIFTCARD'),
                'search_items' => __('Search Email template', 'GIFTCARD'),
                'not_found' => __('No Email template found', 'GIFTCARD'),
                'not_found_in_trash' => __('No Email template found in trash', 'GIFTCARD'),
                'parent' => __('Parent Email template', 'GIFTCARD')
            ),
            'public' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'has_archive' => true,
            'show_in_menu' => 'edit.php?post_type=shop_giftcard',
            'hierarchical' => true,
            'supports' => array('title', 'editor', 'thumbnail'),

        ));

    }

    public static function install()
    {
        global $wpdb;
        // get current version to check for upgrade
        $installed_version = get_option('magenest_giftcard_version');
        // install
        if (!$installed_version) {
            if (!function_exists('dbDelta')) {
                include_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            }
            $prefix = $wpdb->prefix;
	        /**
             *  $product_id, $quantity = 1, $variation_id = '', $variation = '', $cart_item_data = array()
             */
            $query = "CREATE TABLE IF NOT EXISTS `{$prefix}magenest_giftcard_history` (
			`id` int(11) unsigned NOT NULL auto_increment,
			`giftcard_id` int(11)NOT NULL,
			`giftcard_code` varchar (255) NOT NULL,
			`balance`  varchar (255) NOT NULL,
			`change_balanced`  varchar (255)  NULL,
			`order_id` int(11)  NULL,
			`log` VARCHAR (255) NULL,
			`amount`  varchar (255) NOT NULL,
			`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;";
            dbDelta($query);
            self::create_pages();
            update_option('magenest_giftcard_version', self::VERSION);
            $installed_version = get_option('magenest_giftcard_version');
            self::insertEmailTemplate();
            self::setContentDefaultEmailTemplate();
        }
        // upgrade if installed version lower than plugin version
        if (-1 === version_compare($installed_version, self::VERSION)) {
            self::upgrade($installed_version);
            self::insertEmailTemplate();
            self::setContentDefaultEmailTemplate();
        }
        new \model\ScheduleSendMail();
    }
    public static function setContentDefaultEmailTemplate(){
        $data['magenest_giftcard_to_content'] = "Dear {{to_name}},<br/>You received a gift card from {{from_name}}!<br/>Balance: $ {{balance}}<br/>Message: {{message}}<br/>Code: {{code}}";
        $data['magenest_giftcard_to_subject'] = "Giftcard";
        update_option('magenest_giftcard_to_subject', wp_kses_post($data['magenest_giftcard_to_subject']));
        update_option('magenest_giftcard_to_content', wp_kses_post($data['magenest_giftcard_to_content']));
    }

    public static function upgrade($installed_version)
    {
        global $wpdb;
        $prefix = $wpdb->prefix;
        $args = array(
            'posts_per_page' => -1,
            'post_type' => 'shop_giftcard'
        );
        $giftcards = get_posts($args);
        foreach ($giftcards as $giftcard) {
            $giftcardId = $giftcard->ID;
            $orderId = get_post_meta($giftcardId, 'magenest_giftcard_order_id', true);
            if (isset($orderId) && $orderId == 0) {
                update_post_meta($giftcardId, 'model', 'admin');
            } elseif(isset($orderId)) {
                update_post_meta($giftcardId, 'model', 'buyer');
            }

            self::update_old_gc_meta($giftcardId);
        }
        if (!function_exists('dbDelta'))
            include_once  (ABSPATH . 'wp-admin/includes/upgrade.php');

        $query = "CREATE TABLE IF NOT EXISTS `{$prefix}magenest_giftcard_history` (
			`id` int(11) unsigned NOT NULL auto_increment,
			`giftcard_id` int(11)NOT NULL,
			`giftcard_code` varchar (255) NOT NULL,
			`balance`  varchar (255) NOT NULL,
			`change_balanced`  varchar (255)  NULL,
			`order_id` int(11)  NULL,
			`log` VARCHAR (255) NULL,
			`amount`  varchar (255) NOT NULL,
			`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;";
        $wpdb->query($query);
        update_option('magenest_giftcard_version', self::VERSION);


    }

    public function update_old_gc_meta($gc_id){
        global $wpdb;
        $wpdb->update($wpdb->prefix.'postmeta', array('meta_key' => 'gc_product_id'), array('post_id' => $gc_id, 'meta_key' => 'product_id'));
        $wpdb->update($wpdb->prefix.'postmeta', array('meta_key' => 'gc_order_item_id'), array('post_id' => $gc_id, 'meta_key' => 'order_item_id'));
        $wpdb->update($wpdb->prefix.'postmeta', array('meta_key' => 'gc_product_name'), array('post_id' => $gc_id, 'meta_key' => 'product_name'));
        $wpdb->update($wpdb->prefix.'postmeta', array('meta_key' => 'gc_user_id'), array('post_id' => $gc_id, 'meta_key' => 'user_id'));
        $wpdb->update($wpdb->prefix.'postmeta', array('meta_key' => 'gc_balance'), array('post_id' => $gc_id, 'meta_key' => 'balance'));
        $wpdb->update($wpdb->prefix.'postmeta', array('meta_key' => 'gc_init_balance'), array('post_id' => $gc_id, 'meta_key' => 'init_balance'));
        $wpdb->update($wpdb->prefix.'postmeta', array('meta_key' => 'gc_send_from_firstname'), array('post_id' => $gc_id, 'meta_key' => 'send_from_firstname'));
        $wpdb->update($wpdb->prefix.'postmeta', array('meta_key' => 'gc_send_from_last_name'), array('post_id' => $gc_id, 'meta_key' => 'send_from_last_name'));
        $wpdb->update($wpdb->prefix.'postmeta', array('meta_key' => 'gc_send_to_name'), array('post_id' => $gc_id, 'meta_key' => 'send_to_name'));
        $wpdb->update($wpdb->prefix.'postmeta', array('meta_key' => 'gc_send_to_email'), array('post_id' => $gc_id, 'meta_key' => 'send_to_email'));
        $wpdb->update($wpdb->prefix.'postmeta', array('meta_key' => 'gc_scheduled_send_time'), array('post_id' => $gc_id, 'meta_key' => 'scheduled_send_time'));
        $wpdb->update($wpdb->prefix.'postmeta', array('meta_key' => 'gc_email_template_id'), array('post_id' => $gc_id, 'meta_key' => 'email_template_id'));
        $wpdb->update($wpdb->prefix.'postmeta', array('meta_key' => 'gc_pdf_template_id'), array('post_id' => $gc_id, 'meta_key' => 'pdf_template_id'));
        $wpdb->update($wpdb->prefix.'postmeta', array('meta_key' => 'gc_is_sent'), array('post_id' => $gc_id, 'meta_key' => 'is_sent'));
        $wpdb->update($wpdb->prefix.'postmeta', array('meta_key' => 'gc_send_via'), array('post_id' => $gc_id, 'meta_key' => 'send_via'));
        $wpdb->update($wpdb->prefix.'postmeta', array('meta_key' => 'gc_extra_info'), array('post_id' => $gc_id, 'meta_key' => 'extra_info'));
        $wpdb->update($wpdb->prefix.'postmeta', array('meta_key' => 'gc_code'), array('post_id' => $gc_id, 'meta_key' => 'code'));
        $wpdb->update($wpdb->prefix.'postmeta', array('meta_key' => 'gc_message'), array('post_id' => $gc_id, 'meta_key' => 'message'));
        $wpdb->update($wpdb->prefix.'postmeta', array('meta_key' => 'gc_status'), array('post_id' => $gc_id, 'meta_key' => 'status'));
    }

    public static function insertEmailTemplate(){
        $path = GIFTCARD_PATH.'assets/EmailTemplate/template.html';
        $post_content = file_get_contents($path, FILE_USE_INCLUDE_PATH);

        $birthdayFile = GIFTCARD_PATH.'assets/EmailTemplate/images/magenest-birthday.png';
        $birthdayUrl = self::uploadImage($birthdayFile);

        $birthday2File = GIFTCARD_PATH.'assets/EmailTemplate/images/magenest-birthday2.png';
        $birthday2Url = self::uploadImage($birthday2File);

        $birthday3File = GIFTCARD_PATH.'assets/EmailTemplate/images/magenest-birthday3.png';
        $birthday3Url = self::uploadImage($birthday3File);

        $thanksgivingFile = GIFTCARD_PATH.'assets/EmailTemplate/images/magenest-thanksgiving.png';
        $thanksgivingUrl = self::uploadImage($thanksgivingFile);

        $thanksgiving2File = GIFTCARD_PATH.'assets/EmailTemplate/images/magenest-thanksgiving2.png';
        $thanksgiving2Url = self::uploadImage($thanksgiving2File);

        $thanksgiving3File = GIFTCARD_PATH.'assets/EmailTemplate/images/magenest-thanksgiving3.png';
        $thanksgiving3Url = self::uploadImage($thanksgiving3File);

        $christmasFile = GIFTCARD_PATH.'assets/EmailTemplate/images/magenest-christmas.png';
        $christmasUrl = self::uploadImage($christmasFile);

        $christmas2File = GIFTCARD_PATH.'assets/EmailTemplate/images/magenest-christmas2.png';
        $christmas2Url = self::uploadImage($christmas2File);

        $christmas3File = GIFTCARD_PATH.'assets/EmailTemplate/images/magenest-christmas3.png';
        $christmas3Url = self::uploadImage($christmas3File);

        $valentineFile = GIFTCARD_PATH.'assets/EmailTemplate/images/magenest-valentine.png';
        $valentineUrl = self::uploadImage($valentineFile);

        $valentine2File = GIFTCARD_PATH.'assets/EmailTemplate/images/magenest-valentine2.png';
        $valentine2Url = self::uploadImage($valentine2File);

        $valentine3File = GIFTCARD_PATH.'assets/EmailTemplate/images/magenest-valentine3.png';
        $valentine3Url = self::uploadImage($valentine3File);

        $logoFile = GIFTCARD_PATH.'assets/EmailTemplate/images/magenest-logo.png';
        $logoUrl = self::uploadImage($logoFile);



        $data_images = [
            'birthday' => [
                'title' => 'Happy Birthday!',
                'banner' => $birthdayUrl,
                'logo' => $logoUrl,
                'image' => $birthday2Url,
                'background' => $birthday3Url,
                'style' => ['#8B6A5A','#D6D7E2','#CFC5BD']
            ],
            'thanksgiving' => [
                'title' => 'Happy Thanksgiving!',
                'banner' => $thanksgivingUrl,
                'logo' => $logoUrl,
                'image' => $thanksgiving2Url,
                'background' => $thanksgiving3Url,
                'style' => ['#EC8B3A','#E5D6C1','#EC8B3A']
            ],
            'christmas' => [
                'title' => 'Merry Christmas!',
                'banner' => $christmasUrl,
                'logo' => $logoUrl,
                'image' => $christmas2Url,
                'background' => $christmas3Url,
                'style' => ['#8B0017','#8A2E28','#51565A']
            ],
            'valentine' => [
                'title' => 'Happy Valentine!',
                'banner' => $valentineUrl,
                'logo' => $logoUrl,
                'image' => $valentine2Url,
                'background' => $valentine3Url,
                'style' => ['#8B0017','#D7D6D1','#CF2E2C']
            ]
        ];
        $posts = [];
        foreach ($data_images as $key => $data){
            $email_content = str_replace('[MAGENEST_TITLE]', $data['title'], $post_content);
            $email_content = str_replace('[MAGENEST_BANNER]', $data['banner'], $email_content);
            $email_content = str_replace('[MAGENEST_STYLE1]', $data['style'][0], $email_content);
            $email_content = str_replace('[MAGENEST_STYLE2]', $data['style'][1], $email_content);
            $email_content = str_replace('[MAGENEST_STYLE3]', $data['style'][2], $email_content);
            $email_content = str_replace('[MAGENEST_IMAGE]', $data['image'], $email_content);
            $email_content = str_replace('[MAGENEST_LOGO]', $data['logo'], $email_content);
            $email_content = str_replace('[MAGENEST_BACKGROUND]', $data['background'], $email_content);

            $posts[] = [
                'post_title' => $data['title'],
                'post_content' => $email_content,
                'comment_status' => 'closed',
                'ping_status'    => 'closed',
                'post_author'    => get_current_user_id(),
                'post_status'    => 'publish',
                'post_type'      => 'email_giftcard'
            ];
        }
        foreach ($posts as $post){
            $post_id = wp_insert_post($post);
//            set_post_thumbnail( $post_id, $attach_id );
        }
    }
    public static function uploadImage($filename){
        $wp_upload_dir = wp_upload_dir();
        $subdir = $wp_upload_dir['subdir'];
        $new_url = ABSPATH . 'wp-content/uploads'.$subdir.'/'.basename( $filename );
        $content = file_get_contents($filename, FILE_USE_INCLUDE_PATH);
        file_put_contents($new_url, $content, FILE_APPEND);
        $path_return = $wp_upload_dir['url'] . '/' . basename($filename);
        return $path_return;
    }
    /**
     * create gift card pages for plugin
     */
    public static function create_pages()
    {
        if (!function_exists('wc_create_page')) {
            include_once dirname(__DIR__) . '/woocommerce/includes/admin/wc-admin-functions.php';
        }
        $pages = array(
            'giftregistry' => array(
                'name' => _x('giftcard', 'Page slug', 'woocommerce'),
                'title' => _x('Gift card', 'Page title', 'woocommerce'),
                'content' => '[magenest_giftcard]'
            )
        );
        foreach ($pages as $key => $page) {
            wc_create_page(esc_sql($page ['name']), 'magenest-giftcard' . $key . '_page_id', $page ['title'], $page ['content'], !empty ($page ['parent']) ? wc_get_page_id($page ['parent']) : '');
        }
    }

    //add menu items
    public function admin_menu()
    {
        global $menu;
        include_once GIFTCARD_PATH . 'admin/MagenestGiftcardAdmin.php';
        include_once GIFTCARD_PATH . 'admin/magenest-giftcard-setting.php';
        include_once GIFTCARD_PATH . 'admin/giftcard-columns.php';
        include_once GIFTCARD_PATH . 'admin/giftcard-metabox.php';
        include_once GIFTCARD_PATH . 'admin/giftcard-savemeta.php';
        add_submenu_page( 'edit.php?post_type=shop_giftcard', __( 'Import Gift Card', GIFTCARD_TEXT_DOMAIN ), __( 'Import Gift Card', GIFTCARD_TEXT_DOMAIN ), 'manage_woocommerce', 'import_giftcard', array( 'admin\ImportGiftcardController', 'import_giftcard_view' ) );
    }

    public function hook_emailTemplate()
    {
        add_action('admin_post_save_setting', array($this, 'save_setting_email_template'), 5);
    }

    public function save_setting_email_template()
    {
        if (isset($_POST['btnSubmit'])) {
            $email_template = isset($_POST['email_template']) ? $_POST['email_template'] : '0';
            update_option('email_template', $email_template, true);
            $logo_company = isset($_POST['logo_company']) ? $_POST['logo_company'] : '';
            update_option('logo_company', $logo_company, true);
            $image_template = isset($_POST['image_template']) ? $_POST['image_template'] : '';
            update_option('image_template', $image_template, true);
            $magenest_giftcard_to_subject = isset($_POST['magenest_giftcard_to_subject']) ? $_POST['magenest_giftcard_to_subject'] : '';
            update_option('magenest_giftcard_to_subject', $magenest_giftcard_to_subject, true);
            $magenest_giftcard_to_content = isset($_POST['magenest_giftcard_to_content']) ? $_POST['magenest_giftcard_to_content'] : '';
            update_option('magenest_giftcard_to_content', $magenest_giftcard_to_content, true);
            $email_footer = isset($_POST['email_footer']) ? $_POST['email_footer'] : '';
            update_option('email_footer', $email_footer, true);
            $url = home_url('/wp-admin/edit.php?post_type=shop_giftcard&page=giftcard_mail_template');
            wp_redirect($url);
        }
    }

    public function load_class_giftcard()
    {
        new \model\EmailTemplate();
        new \model\ImportEmailTemplate();
        new \site\cart\AddGiftCardToCartBLL();
        new \site\order\GCOrderBLL();
        new \site\RedeemGC();
        new \admin\ProductGiftCard();

        include_once GIFTCARD_PATH . 'model/giftcard.php';
//        include_once GIFTCARD_PATH . 'model/observer/product.php';
//        include_once GIFTCARD_PATH . 'model/observer/buy-giftcard.php';
        include_once GIFTCARD_PATH . 'model/observer/apply-giftcard.php';
        include_once GIFTCARD_PATH . 'model/giftcard-applied-form-handler.php';

        add_action('wp_ajax_preview_pdf', array('admin\PdfSettings', 'preview_pdf'));
        add_action('wp_ajax_nopriv_preview_pdf', array('admin\PdfSettings', 'preview_pdf'));

        add_action('wp_ajax_process_file', array('admin\ImportGiftcardController', 'process_file'));
        add_action('wp_ajax_save_gc_import', array('admin\ImportGiftcardController', 'process_before_save_data'));

    }


    public static function getInstance()
    {
        if (!self::$giftcard_instance) {
            self::$giftcard_instance = new Main();
        }
        return self::$giftcard_instance;
    }
}