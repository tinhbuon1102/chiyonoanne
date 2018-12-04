<?php
global $post,$wp;
$url = home_url( $wp->request );
$url_arr = explode('/', $url);
array_pop($url_arr);
$request = explode( '/', $wp->request );
$prev_url = implode('/', $url_arr);
$prev_slug = $request[1];
$lastslug = end($request);
$order_history_url = get_permalink( get_option('woocommerce_myaccount_page_id') ).'orders';
if ( count($request) > 2 ) {
	if ( $prev_slug == 'view-order' ) {
		$back_prevpage = '<a class="cta cta--secondary" href="'.$order_history_url.'">'.__( 'Return to Order History', 'zoa' ).'</a>';
	} else {
		$back_prevpage = '<a class="cta cta--secondary" href="'.$prev_url.'">'.__( 'Back', 'zoa' ).'</a>';
	}
}
$my_acount_title = wpb_woo_my_account_order();

$current_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$dashboard_url = get_permalink( get_option('woocommerce_myaccount_page_id'));
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
wc_print_notices();
$title_parts = zoa_wp_title(array());
?>
<div class="account-row row <?php if($dashboard_url == $current_url){ ?>flex-justify-between<?php } else { ?>gutter-padding flex-justify-center<?php } ?>">
<?php
/**
 * My Account navigation.
 * @since 2.6.0
 */
do_action( 'woocommerce_account_navigation' );
	
?>

<div class="account__content <?php if($dashboard_url == $current_url){ ?>col-12 col-md-7<?php } else { ?>col-lg-7 col-md-8 col-12 offset-md-1<?php } ?>">
	<?php if($dashboard_url != $current_url){ ?>
	<?php if(!$lastslug == 'my-wishlist') { ?>
	<div class="account__heading">
		<h1 class="heading heading--xlarge serif">
			<?php if (empty($my_acount_title[trim(end($request))])) { ?>
			<?php echo $title_parts['title']; ?>
			<?php } else { ?>
			<?php echo $my_acount_title[trim(end($request))];?>
			<?php } ?>
		</h1>
		<?php echo $back_prevpage; ?>
	</div>
	<?php } ?>
	<?php } ?>
	<?php
		/**
		 * My Account content.
		 * @since 2.6.0
		 */
		do_action( 'woocommerce_account_content' );
	?>
</div>
</div>
