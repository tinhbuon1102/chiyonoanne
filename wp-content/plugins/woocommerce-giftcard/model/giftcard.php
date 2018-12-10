<?php

namespace model;
if (!class_exists('Magenest_Giftcard')) {
    class Magenest_Giftcard
    {
        const ICONV_CHARSET = 'UTF-8';
        /** @public int Gift card id. */
        public $id;

        /** @public string Coupon code. */
        public $code;

        /** @public string product_id. */
        public $product_id;

        public $product_name;
        /** @public int  buyer id. */
        public $user_id;

        /** @public float balancee. */
        public $balance;

        /** @public float init_balance. */
        public $init_balance;

        /** @public string send_from_firstname. */
        public $send_from_firstname;

        /** @public string send_from_last_name. */
        public $send_from_last_name;

        /** @public string send_to_name. */
        public $send_to_name;

        /** @public string send_to_email. */
        public $send_to_email;

        /** @public string message. */
        public $message;

        /** @public string scheduled_send_time. */
        public $scheduled_send_time;

        public $email_template_id;
        public $pdf_template_id;

        /** @public string is_sent. */
        public $is_sent;

        /** @public string send_via. */
        public $send_via;

        /** @public string expired_at. */
        public $expired_at;


        /** @public string extra_info. */
        public $extra_info;

        /** @public string status. */
        public $status;

        private $post_type = 'shop_giftcard';
        /** @public string error_message. */
        public $error_message;

        public $giftcard_custom_fields;

        public function __construct($code = '')
        {
            global $wpdb;

            $this->post_type = 'shop_giftcard';
            $giftcard_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = 'shop_giftcard' AND post_status = 'publish'", $code));

            if (!$giftcard_id) {
                return;
            }

            $giftcard = get_post($giftcard_id);
            $this->post_title = apply_filters('magenest_giftcard_code', $giftcard->post_title);

            if (empty($giftcard) || $code !== $this->post_title) {
                return;
            }
            $this->id = $giftcard->ID;
            $this->giftcard_custom_fields = get_post_meta($this->id);
            $this->code = $this->post_title;
            $load_data = array(
                'product_id' => 0,
                'product_name' => '',
                'user_id' => 0,
                'balance' => 0,
                'init_balance' => 0,
                'send_from_firstname' => '',
                'send_from_last_name' => '',
                'send_to_name' => '',
                'send_to_email' => '',
                'scheduled_send_time' => '',
                'email_template_id' => '',
                'pdf_template_id' => '',
                'is_sent' => 0,
                'send_via' => '',
                'extra_info' => '',
                'code' => '',
                'message' => '',
                'status' => 0,
                'expired_at' => '',
            );
            foreach ($load_data as $key => $default) {
                $this->$key = isset($this->giftcard_custom_fields['gc_' . $key][0]) && $this->giftcard_custom_fields['gc_' . $key][0] !== '' ? $this->giftcard_custom_fields['gc_' . $key][0] : $default;
            }
        }

        public function is_valid($code, $productId)
        {
            $this->code = $code;
            global $wpdb;
            $this->post_type = 'shop_giftcard';
            $giftcard_id = $wpdb->get_var($wpdb->prepare(apply_filters('magenest_giftcard_code_query', "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = 'shop_giftcard' AND post_status = 'publish'"), $this->code));
            if (!$giftcard_id) {
                return;
            }

            $giftcard = get_post($giftcard_id);
            $this->post_title = apply_filters('magenest_giftcard_code', $giftcard->post_title);

            if (empty($giftcard) || $code !== $this->post_title) {
                return;
            }

            $this->id = $giftcard->ID;
            $this->giftcard_custom_fields = get_post_meta($this->id);


            $this->code = $this->post_title;
            $load_data = array(
                'product_id' => 0,
                'product_name' => '',
                'user_id' => 0,
                'balance' => 0,
                'init_balance' => 0,
                'send_from_firstname' => '',
                'send_from_last_name' => '',
                'send_to_name' => '',
                'send_to_email' => '',
                'scheduled_send_time' => '',
                'is_sent' => 0,
                'send_via' => '',
                'extra_info' => '',
                'code' => '',
                'message' => '',
                'status' => 0,
                'expired_at' => '',

            );

            foreach ($load_data as $key => $default) {
                $this->$key = isset($this->giftcard_custom_fields['gc_' . $key][0]) && $this->giftcard_custom_fields['gc_' . $key][0] !== '' ? $this->giftcard_custom_fields['gc_' . $key][0] : $default;
            }

            /////////////////////////////////////////

            $valid = true;

            $product_giftcard = get_post_meta($giftcard->ID, 'gc_product_id', true);
            $exclude_product = json_decode(get_post_meta($product_giftcard, '_exclude_products', true), true);
            if ($exclude_product != null || $exclude_product != "") {
                foreach ($productId as $id) {
                    if (in_array($id, $exclude_product)) {
                        $valid = false;
                        $this->error_message = 'Gift Card code is not applicable for this product ' . get_the_title($id);
                    }
                }
            }

            if (!$this->id || $this->id < 1 || $this->id == '') {
                $this->error_message = __('Gift card code is not existed', 'GIFTCARD');
                $valid = false;

            }
            if ($this->status != 1) {
                $this->error_message = __('Gift card code is  not active', 'GIFTCARD');
                $valid = false;
            }
            if ($this->expired_at) {
                ////			$today    = date( 'Y-m-d', current_time('timestamp'));
                //			$expired_at = date( 'Y-m-d', $this->expired_at);
                //			if ( $today > $expired_at ) {
                //				$valid = false;
                //				$this->error_message = __('Gift card code is expired', GIFTCARD_TEXT_DOMAIN) ;
                //			}
                if (current_time('mysql') > $this->expired_at) {
                    $valid = false;
                    $this->error_message = __('Gift card code is expired', 'GIFTCARD');
                }
            }
            if ($this->balance == 0 || $this->balance < 0) {
                $valid = false;
                $this->error_message = __('Gift card balance is zero', 'GIFTCARD');
            }

            return $valid;
        }

        public function send($gc_id)
        {
            $order_id = get_post_meta($gc_id, 'magenest_giftcard_order_id', true);
            if ($order_id != 0) {
                $order = new \WC_Order($order_id);
                $fromEmail = $order->get_billing_email();
                $fromName = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
            } else {
                $fromName = get_option('woocommerce_email_from_name');//woocommerce_email_from_name
                $fromEmail = get_option('woocommerce_email_from_address');
            }


            $this->send_mail_to_recipient($this->send_to_name, $this->send_to_email, $this->message, $this->post_title, $this->balance, $this->expired_at, $fromEmail, $fromName, $this->email_template_id, $this->pdf_template_id);
            $log = [
                'giftcard_id' => $gc_id,
                'giftcard_code' => $this->post_title,
                'balance' => $this->balance,
                'change_balanced' => 0,
                'order_id' => $order_id,
                'log' => 'active giftcard code'
            ];
            $this->InsertRedeemLog($log);
            update_post_meta($gc_id, 'gc_status', 1);
        }

        public function add_balance($amount, $giftcard_code)
        {
            global $wpdb;
            $giftcard = $wpdb->get_var($wpdb->prepare("
				SELECT $wpdb->posts.ID
				FROM $wpdb->posts
				WHERE $wpdb->posts.post_type = 'shop_giftcard'
				AND $wpdb->posts.post_status = 'publish'
				AND $wpdb->posts.post_title = '%s'
				", $giftcard_code));

            if ($giftcard) {

                $oldBalance = get_post_meta($giftcard, 'gc_balance', true);


                $giftcard_balance = (float)$oldBalance + (float)$amount;

                update_post_meta($giftcard, 'gc_balance', $giftcard_balance); // Update balance of Giftcard
            }
        }

        /**
         * analysis pattern of coupon which is defined by admin in setting panel
         *
         * @param string $pattern
         *
         * @return string
         */
        public function generate_code($pattern)
        {
            $gen_arr = array();

            preg_match_all("/\[[AN][.*\d]*\]/", $pattern, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                $delegate = substr($match [0], 1, 1);
                $length = substr($match [0], 2, strlen($match [0]) - 3);
                if ($delegate == 'A') {

                    $gen = $this->generate_string($length);
                } elseif ($delegate == 'N') {

                    $gen = $this->generate_num($length);
                }

                $gen_arr [] = $gen;
            }

            foreach ($gen_arr as $g) {
                $pattern = preg_replace('/\[[AN][.*\d]*\]/', $g, $pattern, 1);
            }

            return $pattern;
        }

        public function generate_string($length)
        {
            if ($length == 0 || $length == null || $length == '') {
                $length = 5;
            }
            $c = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
            $rand = '';
            for ($i = 0; $i < $length; $i++) {
                $rand .= $c [rand() % strlen($c)];
            }

            return $rand;
        }

        /**
         * generate arbitratry string contain number digit
         *
         * @param int $length
         *
         * @return string
         */
        public function generate_num($length)
        {
            if ($length == 0 || $length == null || $length == '') {
                $length = 5;
            }
            $c = "0123456789";
            $rand = '';
            for ($i = 0; $i < $length; $i++) {
                $rand .= $c [rand() % strlen($c)];
            }

            return $rand;
        }

        /**
         * Returns the error_message string
         *
         * @access public
         * @return string
         */

        public function get_error_message()
        {
            return $this->error_message;
        }

        public function generateGiftcard($code = '', $data, $order_id)
        {
            $pattern = get_option('magenest_giftcard_code_pattern');
            if (!$code) {
                $code = $this->generate_code($pattern);
            }
            $post_id = -1;
            $author_id = get_current_user_id();
            $title = $code;

            $gift = get_post_meta($data['gc_product_id'], '_giftcard', true);
            if (isset($gift) && $gift == 'yes') {
                if (!$this->getGiftcardByCode($code)) {

                    $post_id = wp_insert_post(
                        array(
                            'comment_status' => 'closed',
                            'ping_status' => 'closed',
                            'post_author' => $author_id,
                            'post_title' => $title,
                            'post_status' => 'publish',
                            'post_type' => $this->post_type
                        )
                    );
                } else {
                    $post_id = -2;
                }
                $data['gc_code'] = $code;

                if ($post_id > 0) {
                    $this->updateGiftcard($post_id, $data, $data['gc_product_id']); // update gift card meta
                }
            }

            return $post_id;
        }

        public function calculateExpiryDate($timespan)
        {
//			$time              = current_time( 'mysql' );
            $current_date_time = new \DateTime();
            if (!$timespan) {
                return false;
            }
            //$current_date_time->add ( $interval );
            $modify = '+';
            $modify .= floatval($timespan);
            $modify .= ' days';
            $current_date_time->modify($modify);
            $format = 'Y-m-d H:i:s';

            return $current_date_time->format($format);
        }

        public function updateGiftcard($post_id, $load_data, $product_id)
        {
            $gc_expired_at = get_post_meta($post_id, 'gc_expired_at', 0);
            if (empty($gc_expired_at[0]) && !isset($load_data['gc_expired_at'])) {
                $expired_at = '';
                $expiry_mode = get_post_meta($product_id, '_giftcard-expiry-model', true);
                // if auto create GC -> caculate expiry date
                $expired_at = '';
                $expiry_mode = get_post_meta($product_id, '_giftcard-expiry-model', true);
                if (!empty($expiry_mode)) {
                    if ($expiry_mode == 'expiry-date') {
                        $expired_at_product_scope = get_post_meta($product_id, '_giftcard-expiry-date', true);
                        if ($expired_at_product_scope) {
                            $expired_at = $expired_at_product_scope;
                        }
                    } elseif ($expiry_mode == 'expiry-time') {
                        $expired_at_product_scope = get_post_meta($product_id, '_giftcard-expiry-time', true);
                        $gc = new \model\Magenest_Giftcard();
                        $expiry_time = $gc->calculateExpiryDate($expired_at_product_scope);
                        if ($expiry_time) {
                            $expired_at = $expiry_time;
                        }
                    }
                } elseif (get_option('magenest_giftcard_timespan')) {
                    $giftcard = new \model\Magenest_Giftcard();
                    $timespan = get_option('magenest_giftcard_timespan');
                    $expired_at_website_scope = $giftcard->calculateExpiryDate($timespan);
                    if ($expired_at_website_scope) {
                        $expired_at = $expired_at_website_scope;
                    }
                }
                $load_data['gc_expired_at'] = $expired_at;
            }
            $mode = get_post_meta($post_id, 'mode', true);
            if (!$mode || $mode != "admin") {
                update_post_meta($post_id, 'mode', 'buyer');
            }
            foreach ($load_data as $key => $default) {
                $value = isset ($load_data [$key]) && $load_data [$key] != '' ? $load_data [$key] : $default;
                update_post_meta($post_id, $key, $value);
            }
        }

        public static function getGiftcardByCode($code)
        {
            global $wpdb;
            $giftcard_id = $wpdb->get_var($wpdb->prepare(apply_filters('magenest_giftcard_code_query', "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = 'shop_giftcard' AND post_status = 'publish'"), $code));
            if ($giftcard_id) {
                return $giftcard_id;
            } else {
                return false;
            }


        }

        public function extract_email_address($string)
        {
            // $string='<a href="mailto:luuthuy205@gmail.com">luuthuy205@gmail.com</a>';
            if (!is_email($string)) {
                preg_match('/\>(.*)\</', $string, $matches);
                if (isset ($matches [1])) {
                    return $matches [1];
                }
            } else {
                return $string;
            }
        }

        /**
         * @param int $order_id
         */
        public static function strlen($string)
        {
            return iconv_strlen($string, self::ICONV_CHARSET);
        }

        public static function str_split($str, $length = 1, $keepWords = false, $trim = false, $wordSeparatorRegex = '\s')
        {
            $result = array();
            $strlen = self::strlen($str);
            if ((!$strlen) || ($length <= 0)) {
                return $result;
            }
            // trim
            if ($trim) {
                $str = trim(preg_replace('/\s{2,}/siu', ' ', $str));
            }
            // do a usual str_split, but safe for our encoding
            if ((!$keepWords) || ($length < 2)) {
                for ($offset = 0; $offset < $strlen; $offset += $length) {
                    $result[] = substr($str, $offset, $length);
                }
            } // split smartly, keeping words
            else {
                $split = preg_split('/(' . $wordSeparatorRegex . '+)/siu', $str, null, PREG_SPLIT_DELIM_CAPTURE);
                $i = 0;
                $space = '';
                $spaceLen = 0;
                foreach ($split as $key => $part) {
                    if ($trim) {
                        // ignore spaces (even keys)
                        if ($key % 2) {
                            continue;
                        }
                        $space = ' ';
                        $spaceLen = 1;
                    }
                    if (empty($result[$i])) {
                        $currentLength = 0;
                        $result[$i] = '';
                        $space = '';
                        $spaceLen = 0;
                    } else {
                        $currentLength = self::strlen($result[$i]);
                    }
                    $partLength = self::strlen($part);
                    // add part to current last element
                    if (($currentLength + $spaceLen + $partLength) <= $length) {
                        $result[$i] .= $space . $part;
                    } // add part to new element
                    elseif ($partLength <= $length) {
                        $i++;
                        $result[$i] = $part;
                    } // break too long part recursively
                    else {
                        foreach (self::str_split($part, $length, false, $trim, $wordSeparatorRegex) as $subpart) {
                            $i++;
                            $result[$i] = $subpart;
                        }
                    }
                }
            }
            // remove last element, if empty
            if ($count = count($result)) {
                if ($result[$count - 1] === '') {
                    unset($result[$count - 1]);
                }
            }
            // remove first element, if empty
            if (isset($result[0]) && $result[0] === '') {
                array_shift($result);
            }

            return $result;
        }

        public function active_giftcard($order_id, $item_id)
        {
            $order = new \WC_Order($order_id);
            global $wpdb;
            $tbl = $wpdb->prefix . 'postmeta';
            $postTbl = $wpdb->prefix . 'posts';
            $from_name = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
            $from_email = $order->get_billing_email();
            $sql = "SELECT * FROM " . $tbl . " WHERE `meta_key` = 'gc_order_item_id' AND `meta_value` =" . $item_id;
            $results = $wpdb->get_results($sql, ARRAY_A);
            if (!empty($results)) {
                foreach ($results as $row) {
                    $post_id = $row['post_id'];
                    $gc_status = get_post_meta($post_id, 'gc_status', true);
//					if ($gc_status != 0){
//						continue;
//					}
                    //send email to recipient
                    $to_name = get_post_meta($post_id, 'gc_send_to_name', true);
                    $to_email = get_post_meta($post_id, 'gc_send_to_email', true);
                    $to_message = get_post_meta($post_id, 'gc_message', true);
                    $email_template_id = get_post_meta($post_id, 'gc_email_template_id', true);
                    $pdf_template_id = get_post_meta($post_id, 'gc_pdf_template_id', true);
                    $code = get_post_meta($post_id, 'gc_code', true);
                    //echo 'Code is '.$code;
                    $balance = get_post_meta($post_id, 'gc_balance', true);
                    $firstname = get_post_meta($post_id, 'gc_send_from_firstname', true);
                    $lastname = get_post_meta($post_id, 'gc_send_from_last_name', true);

                    $datetime_format = get_option('date_format') . ' ' . get_option('time_format');
                    $expire_at = get_post_meta($post_id, 'gc_expired_at', true);
                    $expire_at = new \DateTime($expire_at);
                    $expire_at = date_i18n($datetime_format, $expire_at->getTimestamp());
                    /* validate the send to email */
                    if (!is_email($to_email)) {
                        $to_email = $this->extract_email_address($to_email);
                    }

                    //for jeeta
                    $scheduled_send_date = get_post_meta($post_id, 'gc_scheduled_send_time', true);
                    $scheduled_send_date = new \DateTime($scheduled_send_date);
                    $scheduled_send_date = $scheduled_send_date->format($datetime_format);
                    setlocale(LC_ALL, "en_US");

                    $current_date_time = new \DateTime();
                    $current_time = $current_date_time->format($datetime_format);

                    $query = 'SELECT * FROM ' . $postTbl . ' WHERE ID=' . $post_id . ' ';
                    $record = $wpdb->get_row($query, ARRAY_A);
                    $is_sent = get_post_meta($post_id, 'gc_is_sent', true);
                    $sent_status = false;
                    if (!$scheduled_send_date && $record['post_type'] == 'shop_giftcard') {//compare the gift card's scheduled send date to current date
                        $sent_status = $this->send_mail_to_recipient($to_name, $to_email, $to_message, $code, $balance, $expire_at, $from_email, $from_name, $email_template_id, $pdf_template_id);
                    } elseif ($scheduled_send_date <= $current_time && $record['post_type'] == 'shop_giftcard') {
                        $sent_status = $this->send_mail_to_recipient($to_name, $to_email, $to_message, $code, $balance, $expire_at, $from_email, $from_name, $email_template_id, $pdf_template_id);
                    }

                    if ($sent_status) {
                        $log = [
                            'giftcard_id' => $post_id,
                            'giftcard_code' => $code,
                            'balance' => $balance,
                            'change_balanced' => 0,
                            'order_id' => $order_id,
                            'log' => 'active giftcard code'
                        ];
                        $this->InsertRedeemLog($log);
                        update_post_meta($post_id, 'gc_is_sent', 1);
                    }else{
                        $log = [
                            'giftcard_id' => $post_id,
                            'giftcard_code' => $code,
                            'balance' => $balance,
                            'change_balanced' => 0,
                            'order_id' => $order_id,
                            'log' => 'Errors send email'
                        ];
                        $this->InsertRedeemLog($log);
                        update_post_meta($post_id, 'gc_is_sent', 0);
                    }
                    update_post_meta($post_id, 'gc_status', 1);
                }
            }
        }

        public function send_mail_to_recipient($to_name, $to_email, $to_message, $code, $balance, $expired_at, $from_email, $from_name, $email_template_id, $pdf_template_id)
        {
            $headers = array();
            $headers [] = "Content-Type: text/html";
            $headers [] = 'From: ' . get_option('woocommerce_email_from_name') . ' <' . get_option('woocommerce_email_from_address') . '>';
            $is_bcc = get_option('giftcard_bcc_sender', false);
            if ($is_bcc == 'yes'){
                $headers [] = 'Bcc: ' . $from_name . ' <' . $from_email . '>';
            }
            $to = $to_name . '<' . $to_email . '>';
            if ($email_template_id == "") $email_template_id = 0;
            // get mail
            $email_template = \model\EmailTemplate::getMailTemplate($email_template_id);
            $subject = $email_template['subject'];
            $content = $email_template['content'];

            $post_id = $this->getGiftcardByCode($code);
            $product_id = get_post_meta($post_id, 'gc_product_id', true);
            $product_name = get_the_title($product_id);
            $product_image = get_the_post_thumbnail($product_id, 'medium');

            $datas = array(
                'from_name' => $from_name,
                'to_name' => $to_name,
                'to_email' => $to_email,
                'message' => $to_message,
                'code' => $code,
                'balance' => $balance,
                'expired_at' => $expired_at,
                'product_image' => $product_image,
                'product_name' => $product_name,
                'store_url' => get_permalink(wc_get_page_id('shop')),
                'store_name' => get_bloginfo('name'),
            );

            $replaces = [];
            $replaces = \model\EmailTemplate::getShortcode($replaces, $datas);

            $body = strtr($content, $replaces);

            $attach_pdf_option = get_option('magenest_giftcard_to_pdf', 'yes');
            if ($attach_pdf_option == 'yes' && $pdf_template_id != "") {
                $attachments = array();
                // get pdf
                $pdf = new \model\PdfCreate();
                $pdf->setShortCodeData($replaces);
                $pdf->createPdf($pdf_template_id);
                $pdfAttachmentId = $pdf->exportPdf();
                $attachments [] = get_attached_file($pdfAttachmentId);
                add_filter('wp_mail_content_type', array($this, 'set_html_content_type'));
                return wp_mail($to, $subject, $body, $headers, $attachments);
            } else {
                add_filter('wp_mail_content_type', array($this, 'set_html_content_type'));
                return wp_mail($to, $subject, $body, $headers);
            }

        }

        public function set_html_content_type()
        {
            return 'text/html';
        }

        public function InsertRedeemLog($data)
        {
            global $wpdb;
            $table = $wpdb->prefix . 'magenest_giftcard_history';
            return $wpdb->insert($table, $data);
        }
    }

    return new Magenest_Giftcard();
}