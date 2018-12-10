<?php
if (!defined('ABSPATH'))
    exit (); // Exit if accessed directlyGIFTCARD_TEXT_DOMAIN
class Magenest_Giftcard_Admin_Metabox
{

    public function __construct(){
        add_action('add_meta_boxes', array($this, 'giftcard_meta_boxes'));
        add_action( 'admin_menu', array($this,'wpdocs_remove_publish_box'));
    }


    public function giftcard_meta_boxes()
    {
        global $post;
        add_meta_box('giftcard_main', __('Gift card', 'GIFTCARD'), array($this, 'giftcard_main'), 'shop_giftcard', 'normal', 'high');
        add_meta_box('giftcard_buy', __('Purchase Information', 'GIFTCARD'), array($this, 'giftcard_buy'), 'shop_giftcard', 'normal', 'high');
        add_meta_box('giftcard_send_friend', __('Send friend', 'GIFTCARD'), array($this, 'giftcard_send_friend'), 'shop_giftcard', 'normal', 'default');
        add_meta_box('giftcard_history', __('GiftCard History', 'GIFTCARD'), array($this, 'giftcard_history'), 'shop_giftcard', 'normal', 'default');
        remove_meta_box('woothemes-settings', 'shop_giftcard', 'normal');
        remove_meta_box('commentstatusdiv', 'shop_giftcard', 'normal');
        remove_meta_box('commentsdiv', 'shop_giftcard', 'normal');
        remove_meta_box('slugdiv', 'shop_giftcard', 'normal');
    }
    function wpdocs_remove_publish_box(){
        remove_meta_box( 'submitdiv', 'shop_giftcard', 'normal' );
    }
    public function giftcard_main()
    {
        global $post;
        $post_id = $post->ID;
        ?>
        <div class="woocommerce_options_panel">
            <style>
                #post-body.columns-2 #postbox-container-1{
                    display: none;
                }
                .woocommerce_options_panel{
                    min-height: auto !important;
                }
                table {
                    table-layout: fixed;
                    width: 50%;
                    border-collapse: collapse;
                }
                th, td {
                    padding: 10px;
                }
                table.magenest_giftcard th {
                    text-align: left;
                }
            </style>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery('#title').attr('readonly',true);
                    jQuery('#title').attr('style','background-color:#ddd;');
                    var giftcard_code = jQuery('#title').val();
                    jQuery('#title').on('change',function () {
                       var change_title = $(this).val();
                       if(change_title != giftcard_code){
                           window.alert('Not permission change');
                       }
                    $(document).on('submit', function() {
                        // do your things
                        return false;
                    });
                    });
                });
            </script>
            <table class="magenest_giftcard giftcard">
                <tr>
                    <th>
                        <?php echo __('Balance Init', 'GIFTCARD') ?>
                    </th>
                    <td>
                        <?php
                        $gc_balance_init = get_post_meta($post_id,'gc_init_balance',true);
                        echo empty($gc_balance_init)?'':wc_price($gc_balance_init);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        <?php echo __('Balance', 'GIFTCARD') ?>
                    </th>
                    <td>
                        <?php
                        $gc_balance = get_post_meta($post_id,'gc_balance',true);
                        echo empty($gc_balance)?'':wc_price($gc_balance);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        <?php echo __('Status', 'GIFTCARD') ?>
                    </th>
                    <td>
                        <?php
                        $gc_status = get_post_meta($post_id,'gc_status',0);
                        $is_sent = get_post_meta($post_id,'gc_is_sent',true);
                        if(!empty($gc_status)){
                            if($gc_status[0] == 1 && $is_sent == 1){
                                echo __('Sent', 'GIFTCARD');
                            } elseif ( $gc_status[0] == 1 && $is_sent == 0){
                                echo __('Activated, pending send', 'GIFTCARD');
                            }elseif($gc_status[0] == 0){
                                echo __('Pending send', 'GIFTCARD');
                            }elseif($gc_status[0] == 2){
                                echo __('Refunded', 'GIFTCARD');
                            }else{
                                echo __('In stock', 'GIFTCARD');
                            }
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        <?php echo __('Expiry date', 'GIFTCARD') ?>
                    </th>
                    <td>
                        <?php
                        $gc_expired_at = get_post_meta($post_id,'gc_expired_at',true);
                        if(!empty($gc_expired_at[0])){
//                            $expired_at = new DateTime($gc_expired_at[0]);
                            echo $gc_expired_at[0];
                        }
                        ?>
                    </td>
                </tr>
            <tr>
            </table>
        <?php
            wp_nonce_field('woocommerce_save_data', 'woocommerce_meta_nonce');
        ?>
        </div>
        <?php
    }

    public function giftcard_buy()
    {
        global $post;
        $post_id = $post->ID;
        ?>
        <div class="woocommerce_options_panel">
            <style>
                table.magenest_purchased th {
                    text-align: left;
                }
            </style>
            <table class="magenest_purchased giftcard">
                <tr>
                    <th>
                        <?php echo __('Order Id') ?>
                    </th>
                    <td>
                        <?php
                        $order_id = get_post_meta($post_id, 'magenest_giftcard_order_id', true);
                        if(!empty($order_id)){
                        ?>
                            <a href="<?= admin_url('post.php?post='.get_post_meta($post_id, 'magenest_giftcard_order_id', true).'&action=edit') ?>">
                                #<?= get_post_meta($post_id, 'magenest_giftcard_order_id', true); ?>
                            </a>
                        <?php
                        }
                        ?>

                    </td>
                </tr>
                <tr>
                    <th>
                        <?php echo __('Product Name', 'GIFTCARD') ?>
                    </th>
                    <td>
                        <?php
                        $gc_product_name = get_post_meta($post_id,'gc_product_name',0);
                        echo empty($gc_product_name)?'':$gc_product_name[0];
                        ?>
                    </td>
                </tr>
            </table>
            <?php
            ?>
        </div>
        <?php
    }

    public function giftcard_send_friend()
    {
        global $thepostid,$post;
        $post_id = $post->ID;
        ?>
        <div class="woocommerce_options_panel">
            <style>
                table.magenest_send_friend th {
                    text-align: left;
                }
            </style>
            <table class="magenest_send_friend giftcard">
                <tr>
                    <th>
                        <?php echo __('To Mail', 'GIFTCARD') ?>
                    </th>
                    <td>
                        <?php
                        $gc_send_to_email = get_post_meta($post_id,'gc_send_to_email',true);
                        echo empty($gc_send_to_email)?'':$gc_send_to_email;
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        <?php echo __('To name', 'GIFTCARD') ?>
                    </th>
                    <td>
                        <?php
                        $gc_send_to_name = get_post_meta($post_id,'gc_send_to_name',0);
                        echo empty($gc_send_to_name)?'':$gc_send_to_name[0];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        <?php echo __('To message', 'GIFTCARD') ?>
                    </th>
                    <td>
                        <?php
                        $gc_message = get_post_meta($post_id,'gc_message',0);
                        echo empty($gc_message)?'':$gc_message[0];
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        <?php echo __('Scheduled send time', 'GIFTCARD') ?>
                    </th>
                    <td>
                        <?php
                        $gc_scheduled_send_time = get_post_meta($post_id,'gc_scheduled_send_time',0);
                        if(!empty($gc_scheduled_send_time[0])){
//                            $scheduled_send_time = new DateTime($gc_scheduled_send_time[0]);
                            echo $gc_scheduled_send_time[0];
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }
    public function giftcard_history(){
        global $thepostid;
        global $wpdb;
        $giftcard_code = get_the_title($thepostid);
        $historyTbl = $wpdb->prefix.'magenest_giftcard_history';
        $sql = "SELECT * FROM ".$historyTbl." WHERE `giftcard_code` = '%s'";
        $posts = $wpdb->get_results($wpdb->prepare($sql, $wpdb->esc_like($giftcard_code)), ARRAY_A);
        if(!empty($posts)) {
    ?>
        <style>
            .magenest_history {
                width: 100%;
            }
            .magenest_history td, .magenest_history th {
                text-align: center;
                padding: 20px;
            }
        </style>
        <table class="magenest_history giftcard">
            <tr>
                <th><?php echo __('Order Id') ?></th>
                <th><?php echo __('Giftcard Code', 'GIFTCARD') ?></th>
                <th><?php echo __('Balance', 'GIFTCARD') ?></th>
                <th><?php echo __('Transaction Amount', 'GIFTCARD') ?></th>
                <th><?php echo __('Comment', 'GIFTCARD') ?></th>
                <th><?php echo __('Create At', 'GIFTCARD') ?></th>
            </tr>
            <?php
            //foreach ($posts as $key => $value):
            foreach ($posts as $post):
            ?>
                <tr>
                    <td>#<?= $post['order_id'] ?></td>
                    <td><?= $post['giftcard_code'] ?></td>
                    <td><?= $post['balance']; ?></td>
                    <td><?= $post['change_balanced']; ?></td>
                    <td><?= $post['log']; ?></td>
                    <td>
                        <?php
                        $date = new DateTime($post['created_at']);
                        echo $date->format('Y-m-d');
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr></tr>
        </table>
        <?php
        }
    }
}

return new Magenest_Giftcard_Admin_Metabox();
