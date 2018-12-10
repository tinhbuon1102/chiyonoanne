<?php
    global $wpdb;
    if(isset($_POST['btnSubmitGiftcardcode'])){
        $giftcardcode = isset($_POST['giftcardcode'])?$_POST['giftcardcode']:'';
        if($giftcardcode == ''){
            $notify = "<script type='text/javascript'> alert('".__('You must fill order id or giftcard code',GIFTCARD_TEXT_DOMAIN)."'); </script>";
            echo $notify;
        }else{
            $historyTbl = $wpdb->prefix.'magenest_giftcard_history';
            $sql = "SELECT * FROM ".$historyTbl." WHERE `giftcard_code` = '%s'";
            $posts = $wpdb->get_results($wpdb->prepare($sql, $wpdb->esc_like($giftcardcode)), ARRAY_A);
            if($posts == NULL){
                $notify = "<script type='text/javascript'> alert('".__('Giftcard code in valid',GIFTCARD_TEXT_DOMAIN)."'); </script>";
                echo $notify;
            }
        }
    }
    if(isset($_POST['btnresendmail'])){
        $giftcardId = isset($_POST['giftcardId'])?$_POST['giftcardId']:'';
        if($giftcardId != ''){
            $post = get_post($giftcardId);
            $code =$post->post_title;
            if (!class_exists('Magenest_Giftcard_Myaccount'))
                include_once GIFTCARD_PATH. 'model/giftcard.php';
            $giftcardInstance = new Magenest_Giftcard($code);
            if($giftcardInstance->send($giftcardId)){
                $notify = "<script type='text/javascript'> alert('".__('Resend mail successfully',GIFTCARD_TEXT_DOMAIN ) ."'); </script>";
                echo $notify;
            }
        }
    }
?>
<h2><?=__('GiftCard History',GIFTC-ARD_TEXT_DOMAIN)?></h2>
<div class="woocommerce-info"><?=__('Have a Giftcard code?',GIFTCARD_TEXT_DOMAIN)?> <a href="#" class="showgiftcardcode"><?php echo __('Click here to enter your code', GIFTCARD_TEXT_DOMAIN)?></a></div>
<form style="display: none;" id="form-resend-giftcard" method="post" action="">
    <table>
        <tr>
            <td>
                <?php echo __('Enter your giftcard code', GIFTCARD_TEXT_DOMAIN) ?>
            </td>
        </tr>
        <tr>
            <td>
                <input type="text" name="giftcardcode" id="giftcardcode"/>
            </td>
        </tr>
        <tr>
            <td >
                <input type="submit" name="btnSubmitGiftcardcode" value="<?php echo __('Save', GIFTCARD_TEXT_DOMAIN);?>"/>
            </td>
        </tr>
    </table>
</form>
<script type="text/javascript">
    (function ($) {
        $('.showgiftcardcode').click(function () {
            $('#form-resend-giftcard').attr('style', 'display:block;');
        })
    })(jQuery);
</script>
<?php
    if(!empty($posts)):
?>
        <style>
            .magenest_history td, .magenest_history th {
                text-align: center;
            }
        </style>
        <table class="magenest_history">
            <tr>
                <th><?php echo __('Order Id', GIFTCARD_TEXT_DOMAIN)?></th>
                <th><?php echo __('Giftcard Code', GIFTCARD_TEXT_DOMAIN)?></th>
                <th><?php echo __('Balance', GIFTCARD_TEXT_DOMAIN)?></th>
                <th><?php echo __('Transaction Amount', GIFTCARD_TEXT_DOMAIN)?></th>
                <th><?php echo __('Comment', GIFTCARD_TEXT_DOMAIN)?></th>
                <th><?php echo __('Create At', GIFTCARD_TEXT_DOMAIN)?></th>
            </tr>
<?php
        //foreach ($posts as $key => $value):
        foreach ($posts as $post):
            $post_id = $post['id'];

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
            <?php endforeach;?>
            <tr></tr>
        </table>
<?php
    endif;