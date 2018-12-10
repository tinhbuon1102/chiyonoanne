<?php
/**
 * Created by KeysNT
 * User: magenest4
 * Date: 14/12/2017
 * Time: 16:13
 */
if( !class_exists('Magenest_Giftcard_Myaccount')){
    class Magenest_Giftcard_Myaccount {
        public function __construct(){
            //Something to do ...
        }
        public function manage_customer_giftcard($user_id){
            ob_start();
            $template_path = GIFTCARD_PATH.'template/';
            $default_path = GIFTCARD_PATH.'template/';
            wc_get_template( 'customer_giftcard.php', array(),$template_path,$default_path );
            echo  ob_get_clean();
        }
    }
}
