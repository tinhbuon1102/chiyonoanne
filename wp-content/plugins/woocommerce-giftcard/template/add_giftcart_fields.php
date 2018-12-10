<?php
global $post, $wpdb;
$post_id = $post->ID;


$is_giftcard = get_post_meta( $post_id, '_giftcard', true );
if ( $is_giftcard ) {
	$price_model = get_post_meta( $post_id, '_giftcard-price-model', true );//custom price
}

$presets = get_post_meta( $post_id, '_giftcard-preset-price', true );//120;130;140
$preset  = explode( ';', $presets );
sort($preset);
$giftcard_code_mode = get_post_meta($post_id,'_giftcard_mode', true);
if($giftcard_code_mode == 'manual'){
    $sql = "SELECT `postmeta`.`post_id` as `giftcard_code_id` 
            FROM `".$wpdb->prefix."postmeta` as `postmeta`
            JOIN `".$wpdb->prefix."posts` as `posts` ON `postmeta`.`post_id` = `posts`.`ID`
            WHERE `postmeta`.`meta_key` = 'gc_product_id' 
            AND `postmeta`.`meta_value` = ".$post->ID." 
            AND `posts`.`post_status` = 'publish'";
    $results = $wpdb->get_results($sql, ARRAY_A);
    if(!empty($results)){
        $giftcard_value = [];
        foreach ($results as $result){
            $giftcard_code_id[] = $result['giftcard_code_id'];
            $query = "SELECT * FROM `".$wpdb->prefix."postmeta` WHERE `post_id` = ".$result['giftcard_code_id'];
            $giftcardcode = $wpdb->get_results($query,ARRAY_A);
            $value = get_post_meta($result['giftcard_code_id'],'gc_balance', true);
            foreach ($giftcardcode as $giftcard){
                if($giftcard['meta_key'] == 'gc_status' && ($giftcard['meta_value'] == '-1' || $giftcard['meta_value'] == -1) && !in_array($value,$giftcard_value) && $value != ""){
                    $giftcard_value[] = $value;
                }
            }
        }
        sort($giftcard_value);
        $preset = $giftcard_value;
    }else{
        $preset = [];
    }
}

$currency_symbol = get_woocommerce_currency_symbol();
$expiry_model = get_post_meta($post_id,'_giftcard-expiry-model',true);
if($expiry_model == "expiry-date"){
    $expiry_date = new DateTime(get_post_meta($post_id,'_giftcard-expiry-date',true));
    $now = new \DateTime();
    $format = 'Y-m-d';
    $expiry_date = $expiry_date->format($format);
    $expirydate = strtotime($expiry_date);
    $now = $now->format($format);
    $now = strtotime($now);
    $expiry = false;
    if($expirydate <= $now){
        $expiry = true;
    }
    ?>
    <p>
        <input type="hidden" name="expiry" id="expiry" value="<?= $expiry ?>"/>
        <?php echo __('Giftcard code will expiry date:', 'GIFTCARD')." $expiry_date";?>
    </p>
    <?php
}
$exclude_products = json_decode(get_post_meta($post_id,'_exclude_products', true), true);
$product_exclude = [];
if(($exclude_products != null || $exclude_products != "")&&!empty($exclude_products)){
?>
<div class="gc_span">
    <p><?php echo __('Gift Card codes will not apply to the following products:', GIFTCARD_TEXT_DOMAIN);?></p>
<?php
    foreach ($exclude_products as $exclude_product){
        echo "<a href='".get_permalink($exclude_product)."' title='".get_the_title($exclude_product)."'>".get_the_title($exclude_product)."</a> | ";
        $product_exclude = [
            'name' => get_the_title($exclude_product),
            'link' => get_permalink($exclude_product)
        ];
    }
?>
</div>
<?php
}
?>
<style type="text/css">
    .magenest_label {
        width: 110px;
    }
</style>

    <?php
    if ( $price_model == 'selected-price' ) {
    ?>
    <div class="gc_span">
        <label> <?php echo __( 'Select value', 'GIFTCARD' ) ?></label>
        <?php if ( ! empty( $preset ) ) { ?>
        <select name="giftcard[amount]" class="giftcard_amount" style="width: 100%">
            <?php
                foreach ( $preset as $op ) {
                    if($op == '' || $op == null) continue;
            ?>
                    <option value="<?php echo $op ?>"> <?php echo wc_price($op) ?> </option>
                <?php }
            }else{
                $update = "UPDATE `".$wpdb->prefix."postmeta` SET `meta_value`='outofstock' WHERE `meta_key`='_stock_status' AND `post_id`='".$post_id."'";
                $wpdb->query($update);
            ?>
              <script type="text/javascript">
                  window.location.reload();
              </script>
            <?php
            } ?>
        </select>
    </div>
    <?php
    } elseif ( $price_model == 'custom-price' ) {
        /*get the price range  */
        $price_range = get_post_meta( $post_id, '_giftcard-price-range', true );//100-140
        $min    = 1;
        $max    = 1000000000000000;
        $prices = explode( '-', $price_range );

        if ( isset( $prices[0] ) ) {
            $min = $prices[0];
        }

        if ( isset( $prices[1] ) ) {
            $max = $prices[1];
        }
    /* */
    ?>
    <div class="gc_span">
        <label> <h2><?php echo __( 'Enter a value', 'GIFTCARD' ) ?></h2></label>
        <input id="magenest-giftcard-define-price-" type="text" data-r='giftcard-amount' name='giftcard[amount]' onmouseout="check(<?= $min ?>, <?= $max ?>)" class="gc_input giftcard_amount"/>
        <span id="check"><?php echo __( 'Please enter the value which is in range ', 'GIFTCARD' ) . $currency_symbol . $min . ' to ' . $currency_symbol . $max ?></span>
    </div>
    <?php
    } elseif ($price_model == 'fixed-price'){
        ?>
        <input type="hidden" class="giftcard_amount" name="giftcard[amount]" value="<?= get_post_meta($post_id, '_regular_price', true); ?>">
        <?php
    }
    ?>


<div class="gc_span">
    <label for="send_friend">
        <h2>
            <?php echo __( "Send to friend", 'GIFTCARD' ) ?>
        </h2>
    </label>

    <table style="table-layout: fixed;">
        <tr>
            <td class="magenest_label">
                <?php echo __( "To Name <span class='required'>*</span>", 'GIFTCARD' ) ?>
            </td>
            <td>
                <input type="text" name="giftcard[send_to_name]" id="send_to_name" class="gc_input" placeholder="<?php echo __( 'Send to', 'GIFTCARD' ) ?>" required ><br/>
            </td>
        </tr>
        <tr>
            <td class="magenest_label">
                <?php echo __( "To Email <span class='required'>*</span>", 'GIFTCARD' ) ?>
            </td>
            <td>
                <input type="text" name="giftcard[send_to_email]" id="send_to_email" style="width: 100%"
                       data-validation="email"
                       data-validation-help="<?php echo __( 'Please enter a valid email address', 'GIFTCARD' ) ?>"
                       placeholder="<?php echo __( 'Recipient email', 'GIFTCARD' ) ?>"
                       id="giftcard_to_email" class="gc_input"
                />
            </td>
        </tr>
        <tr>
            <td class="magenest_label">
                <?php echo __( "Message", 'GIFTCARD' ) ?>
            </td>
            <td>
                <textarea class="gc_input" id="message" name="giftcard[message]" rows="2" placeholder="<?php echo __( 'Message', 'GIFTCARD' ) ?>" maxlength="400"></textarea>
            </td>
        </tr>
    </table>
</div>
    <?php
    $enable_schedule_send_gc = get_post_meta($post_id, 'schedule_send_gc_mode', true);
    if ($enable_schedule_send_gc == 'yes'){
        ?>
        <div class="gc_span">
            <label for="schedule-send-date"><h2><?php echo __( 'Scheduled send', 'GIFTCARD' ) ?></h2></label>
            <table>
                <tr>
                    <td>
                        <?php echo __( 'Scheduled date', 'GIFTCARD' ) ?>
                    </td>
                    <td>
                        <input type="text" name="giftcard[scheduled-send-date]" class="giftcard-input gc-schedule-send-date gc_input date" id="giftcard-schedule-send-date"/>
                        <span class="tool-tip" data-tip="<?php echo __( " If you select a scheduled send date, the gift card will be send to your friend on the scheduled send date", 'GIFTCARD' ) ?>">&nbsp; </span>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }
    ?>

    <?php
    // get email template
    $emails = json_decode( get_post_meta( $post_id, '_giftcard-email_templates', true ), true );
    if (!empty($emails)){
        ?>
        <div class="gc_span">

            <label for="email template"><h2>Choose Email</h2></label>
            <input type="hidden" name="giftcard[email_template]" id="_choose_email" />
            <ol class="flex-control-nav flex-control-thumbs choose-template" id="choose_email">
                <?php
                foreach ( $emails as $email ) {
//                    $image = !empty(get_the_post_thumbnail_url( $email, 'post-thumbnail' )) ? get_the_post_thumbnail_url( $email, 'post-thumbnail' ) : WC()->plugin_url() . '/assets/images/placeholder.png';
                    $image = get_the_post_thumbnail_url( $email, 'post-thumbnail' );
                    if(empty($image)){
                        $image = get_the_post_thumbnail_url( $post_id, 'post-thumbnail' );
                        if(empty($image)){
                            $image = WC()->plugin_url() . '/assets/images/placeholder.png';
                        }
                    }
                    echo '<li id="' . $email . '"style="background-image: url(' . $image . '); background-size: 130px 100px;"></li>';
                }
                ?>
            </ol>
            <div class="clear" style="clear: both;"></div>
            <br/>
            <a id="preview_email" class="slider-button button"><?=__('Preview Email',GIFTCARD_TEXT_DOMAIN)?></a>
        </div>
        <?php
    }

    ?>



    <?php
    // get email template
    $pdfs = json_decode( get_post_meta( $post_id, '_giftcard-pdf_templates', true ), true );
    if (!empty($pdfs)){
        ?>
        <div class="gc_span">
            <label for="pdf template"><h2><?=__('Choose PDF',GIFTCARD_TEXT_DOMAIN)?></h2></label>
            <input type="hidden" name="giftcard[pdf_template]" id="_choose_pdf" />
            <ol class="flex-control-nav flex-control-thumbs choose-template" id="choose_pdf">
                <?php
                foreach ( $pdfs as $pdf ) {
                    $image = !empty(get_the_post_thumbnail_url( $pdf, 'post-thumbnail' )) ? get_the_post_thumbnail_url( $pdf, 'post-thumbnail' ) : WC()->plugin_url() . '/assets/images/placeholder.png';
                    //			wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
                    echo '<li id="' . $pdf . '" style="background-image: url(' . $image . '); background-size: 130px 100px;"></li>';
                }
                ?>
            </ol>
            <div class="clear" style="clear: both;"></div>
            <br/>
            <a id="preview_pdf" class="slider-button button"><?=__('Preview PDF',GIFTCARD_TEXT_DOMAIN)?></a>
        </div>
        <?php
    }
    include GIFTCARD_PATH . 'template/notices.php';
    ?>



<script type="text/javascript">
    jQuery(document).ready(function () {
        var checkMinMax = jQuery('#magenest-giftcard-define-price-').val();
        console.log(checkMinMax);
        if(checkMinMax != undefined){
            jQuery('.single_add_to_cart_button').css('display', 'none');
        }
        var expiry = jQuery('#expiry').val();
        if(expiry){
            jQuery('.single_add_to_cart_button').css('display', 'none');
        }
        var $price = jQuery('#magenest-giftcard-define-price-').val();
        jQuery('#giftcard-schedule-send-date').datetimepicker({
            useCurrent: false,
            minDate: new Date(),
        });
        jQuery('#magenest-giftcard-selector-price').on('change', function (event) {
            var gcprice = jQuery('#magenest-giftcard-selector-price').val();
            jQuery('#giftcard-amount').val(gcprice);
        });

        jQuery('#choose_email').selectable({
            selected: function (event, ui) {
                jQuery('#_choose_email').val(ui.selected.id);
            }
        });

        jQuery('#choose_pdf').selectable({
            selected: function (event, ui) {
                jQuery('#_choose_pdf').val(ui.selected.id);
            }
        });
    });

    function check($min, $max) {
        console.log('min');
        console.log($max);

        var price = jQuery('#magenest-giftcard-define-price-').val();
        if (price < $min || price > $max) {
            //single_add_to_cart_button
            jQuery('#check').css('color', 'red');
            jQuery('.single_add_to_cart_button').css('display', 'none');
        } else {
            jQuery('#check').css('color', '#6d6d6d');
            jQuery('.single_add_to_cart_button').css('display', 'block');
        }
    }

    function bindprice() {
        jQuery('#magenest-giftcard-selector-price').on('change', function (event) {

            var gcprice = jQuery('#magenest-giftcard-selector-price').val();
            console.log(gcprice);
            jQuery('#giftcard-amount').val(gcprice);
        });
    }

    bindprice();

    jQuery.validate();
</script>
