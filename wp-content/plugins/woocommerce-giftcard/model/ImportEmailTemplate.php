<?php
/**
 * Created by PhpStorm.
 * User: doanhcn2
 * Date: 10/04/2018
 * Time: 13:25
 */

namespace model;


class ImportEmailTemplate {
	public function __construct()
	{
		add_filter('wp_import_post_data_raw', array(__CLASS__, 'change_domain_image'));
	}

	// change domain in link in the default email template
	public function change_domain_image($post){
		$post['post_content'] = str_replace("http://giftcard.local/wp-content/plugins/woocommerce-giftcard/", GIFTCARD_URL, $post['post_content'] );
		return $post;
	}
}