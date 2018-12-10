<?php
/**
 * Created by PhpStorm.
 * User: doanhcn2
 * Date: 03/04/2018
 * Time: 08:34
 */

namespace model;


class EmailTemplate {
	public function __construct()
	{
		add_action('wp_ajax_nopriv_preview_email_template', array($this, 'ajax_preview'));
		add_action('wp_ajax_preview_email_template', array($this, 'ajax_preview'));
	}

	/**
	 * @param int $order_id
	 *
	 * @return bool
	 */
	public static function  getMailTemplate($email_template_id = 0){
		$email_template = [];
		if ($email_template_id == 0){
			// get default template
			$email_template['subject'] = get_option( 'magenest_giftcard_to_subject' );
			$email_template['content'] = wpautop(get_option( 'magenest_giftcard_to_content' ));
			return $email_template;
		}else{
            $email = get_post($email_template_id);
            if($email == ""){
                $email_template = self::getMailTemplate(0);
                return $email_template;
            }
            $email_template['subject'] = $email->post_title;
            $email_template['content'] = wpautop($email->post_content);
            wp_reset_query();
            return $email_template;
        }
	}

	public function ajax_preview(){
		$post = $_POST['info_email'];

		$replace = [];
		$replace = self::getShortcode($replace, $post);

		$email_template = self::getMailTemplate($post['email_template']);

		$email_template['subject'] = strtr($email_template['subject'], $replace);
		$email_template['content'] = strtr($email_template['content'], $replace);

		wp_die(json_encode($email_template));
	}


	public static function getShortcode($replace, $datas){
		foreach ($datas as $key => $value){
//		    if($value == "") continue;
			$replace['{{'.$key.'}}'] = $value;
		}
		return $replace;
	}
}