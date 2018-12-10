<?php
if (!defined('ABSPATH')) exit();

global $post;
function magenest_giftcard_show_price_model($value) {
	global $post;
	$product_price_model = get_post_meta($post->ID,'_giftcard-price-model' ,true);
	if ($product_price_model ==$value ) {
		echo "checked";
	}
}

function magenest_giftcard_show_expiry_mode($value){
	global $post;
	$expiry_mode = get_post_meta($post->ID,'_giftcard-expiry-model',true);
	if($expiry_mode == $value){
		echo "checked";
	}
}

function get_email_template(){
	global $post;
	$emais = json_decode(get_post_meta($post->ID,'_giftcard-email_templates',true),true);

	$args = array(
		'post_type' => 'email_giftcard',
		'post_status' => 'publish',
        'posts_per_page' => -1
    );

	$emails_post = get_posts($args);

	foreach ($emails_post as $email){
	    if($emais == null){
            echo '<option value="'.$email->ID.'">'.get_the_title($email->ID).'</option>';
        }else{
            echo '<option value="'.$email->ID.'" ' . ((in_array($email->ID, $emais)) ? 'selected' : '') . ' >'.get_the_title($email->ID).'</option>';
        }
	}
}

function get_pdf_template(){
    global $post;
    $pdfs_assign = json_decode(get_post_meta($post->ID,'_giftcard-pdf_templates',true),true);

    $args = array(
        'post_type' => 'pdf_settings',
        'post_status' => 'publish',
        'posts_per_page' => -1
    );

    $pdfs_template = get_posts($args);

    foreach ($pdfs_template as $pdf_template){
        if($pdfs_assign == null){
            echo '<option value="'.$pdf_template->ID.'">'.get_the_title($pdf_template->ID).'</option>';
        }else {
            echo '<option value="' . $pdf_template->ID . '" ' . ((in_array($pdf_template->ID, $pdfs_assign)) ? 'selected' : '') . ' >' . get_the_title($pdf_template->ID) . '</option>';
        }
    }
}
function get_exclude_product(){
    global $post;
    $exclude_products = json_decode(get_post_meta($post->ID,'_exclude_products', true), true);
    if($exclude_products != null || $exclude_products != ""){
        foreach ($exclude_products as $exclude_product){
            echo '<option value="'.$exclude_product.'" selected>'.get_the_title($exclude_product).'</option>';
        }
    }
}

$custom_price = get_post_meta($post->ID,'_giftcard-price-range' ,true);
$custom_price = explode('-', $custom_price);

?>
<div id="_gc_config" style="display: none">
    <p class="form-field gc-mode">
        <label for="gc_mode"><?=__('Create Gift Card Mode',GIFTCARD_TEXT_DOMAIN)?></label>
        <span>
            <input type="radio" name="_gc_mode" value="auto" <?= get_post_meta($post->ID, '_giftcard_mode', true) == 'auto' ? 'checked' : '' ?> > <?=__('Auto create',GIFTCARD_TEXT_DOMAIN)?>
        </span>
        <span>
            <input type="radio" name="_gc_mode" value="manual" <?= get_post_meta($post->ID, '_giftcard_mode', true) == 'manual' ? 'checked' : '' ?> > <?=__('Import manual',GIFTCARD_TEXT_DOMAIN)?>
        </span>
    </p>
    <div id="import_gc"  style="display: none">
        <?php
            $post_status = $post->post_status;
            $import = 0;
            if($post_status == 'publish'){
                $import = 1;
            }
        ?>
        <p class="form-field">
            <label for = ""><?=__('Import Gift card for product',GIFTCARD_TEXT_DOMAIN)?></label>
            <span id="magenest_help_tip" style="display: none"><i><b><?=__('Save product before redirect to Import Gift Card page',GIFTCARD_TEXT_DOMAIN)?></b></i></span>
            <input type="hidden" name="post_status" id="magenest_post_id" value="<?= $import ?>"/>
            <a href = "<?= admin_url('edit.php?post_type=shop_giftcard&page=import_giftcard&product_id=' . $post->ID); ?>" class="magenest_button_import button"><?=__('Import Gift Card',GIFTCARD_TEXT_DOMAIN)?></a>
        </p>
    </div>



    <p class="form-field price-model giftcard">
        <label for="_regular_price"><?php echo __('Price model', 'GIFTCARD') ?></label>
        <span><input type="radio" name="_giftcard-price-model" value="fixed-price" <?php echo magenest_giftcard_show_price_model('fixed-price') ?> ><?php echo __('Fixed price', 'GIFTCARD') ?></span>
        <span><input type="radio" name="_giftcard-price-model" value="selected-price" <?php echo magenest_giftcard_show_price_model('selected-price') ?> ><?php echo __('Selected price', 'GIFTCARD') ?> </span>
        <span><input type="radio" name="_giftcard-price-model" value="custom-price" <?php echo magenest_giftcard_show_price_model('custom-price') ?> ><?php echo __('Custom price', 'GIFTCARD') ?></span>
    </p>

    <p id="selected-price-for-giftcard" class="form-field _regular_price_field giftcard selector-price-model giftcard-price" <?php if (get_post_meta($post->ID,'__giftcard-price-model' ,true)!='selected-price') :?> style="display:none"<?php endif;?>>
        <label for="_regular_price"><?php echo __('Price Options', 'GIFTCARD') ?> (<?php echo get_woocommerce_currency_symbol() ?>)</label>
        <input type="text" class="giftcard-label" name="_giftcard-preset-price" id="_giftcard-preset-price" value="<?php echo get_post_meta($post->ID,'_giftcard-preset-price' ,true) ?>" hidden>
        <select multiple="multiple" class="gc_preset_price" id="gc_preset_list_price">
            <?php
            $list_price = explode(';', get_post_meta($post->ID,'_giftcard-preset-price' ,true));
            sort($list_price);
            foreach ($list_price as $value){
                if (empty($value)) continue;
                ?>
                <option value="<?= $value; ?>" selected><?= wc_price($value); ?></option>
                <?php
            }
            ?>
        </select>
    </p>
    <p class="form-field dimensions_field _regular_price_field giftcard custom-price-model giftcard-price" <?php if (get_post_meta($post->ID,'__giftcard-price-model' ,true)!='custom-price') :?> style="display:none"<?php endif;?>>
        <label for="_regular_price"><?php echo __('Price range', 'GIFTCARD') ?>(<?php echo get_woocommerce_currency_symbol() ?>)</label>
        <span class="wrap">
            <input type="number" value="<?= isset($custom_price[0])?$custom_price[0]:'' ?>" class="_giftcard-price-range" name="_giftcard-price-range[min]" id="_giftcard-price-range"
                   placeholder="<?php echo __('Min price', 'GIFTCARD' ) ?>">
            <input type="number" value="<?= isset($custom_price[1])?$custom_price[1]:'' ?>" class="_giftcard-price-range" name="_giftcard-price-range[max]" id="_giftcard-price-range"
                   placeholder="<?php echo __('Max price', 'GIFTCARD') ?>">

        </span>

    </p>
    <span id="gc_expiry_date">
        <p class="form-field price-model giftcard">
            <label for=""><?php echo __('Expiry model', 'GIFTCARD') ?></label>
            <span><input type="radio" name="_giftcard-expiry-model" value="expiry-date" <?php echo magenest_giftcard_show_expiry_mode('expiry-date') ?>><?php echo __('Expiry date', 'GIFTCARD') ?></span>
            <span><input type="radio" name="_giftcard-expiry-model" value="expiry-time" <?php echo magenest_giftcard_show_expiry_mode('expiry-time') ?> ><?php echo __('Expiry time', 'GIFTCARD') ?> </span>
        </p>
        <p class="form-field expiry_date giftcard">
            <label for="_regular_price"><?php echo __('Expiry date', 'GIFTCARD') ?> </label>
            <input type="text" autocomplete="false" autofocus="false" value="<?php echo get_post_meta($post->ID,'_giftcard-expiry-date' ,true) ?>"
                   class="custome-giftcard-checkbox" name="_giftcard-expiry-date" id="_giftcard-expiry-date"
                   placeholder="mm-dd-yyyy">
        </p>
        <p class="form-field expiry_time giftcard">
            <label for="_regular_price"><?php echo __('Expiry time (days)', 'GIFTCARD') ?> </label>
            <input type="number" value="<?php echo get_post_meta($post->ID,'_giftcard-expiry-time' ,true) ?>"
                   min="1"
                   class="custome-giftcard-checkbox" name="_giftcard-expiry-time" id="_giftcard-expiry-time"
                   placeholder="<?php echo __('Serveral days', 'GIFTCARD'); ?>" />
        </p>
    </span>

    <p class="form-field giftcard">
        <label for="_regular_price"><?php echo __('Schedule a gift card', 'GIFTCARD') ?> </label>
        <input type="checkbox" <?php echo get_post_meta($post->ID,'schedule_send_gc_mode' ,true) === 'yes' ? 'checked' : ''; ?> name="schedule_send_gc_mode" /> <?=__('Enable',GIFTCARD_TEXT_DOMAIN)?>
    </p>
    <p class="form-field giftcard">
        <label for="_email_template"><?php echo __('Email Template', 'GIFTCARD') ?> </label>
        <select multiple="multiple" name="_email_templates[]" class="chosen_select" style="width: 100%;">
            <?php
            get_email_template();
            ?>
        </select>
    </p>
    <p class="form-field giftcard">
        <label for="_pdf_template"><?php echo __('Pdf Template', 'GIFTCARD') ?> </label>
        <select multiple="multiple" name="_pdf_templates[]" class="chosen_select" style="width: 100%;">
            <?php
            get_pdf_template();
            ?>
        </select>
    </p>
    <p class="form-field file_name_giftcard giftcard">
        <label for="_pdf_template"><?php echo __('Exclude Products', 'GIFTCARD') ?> </label>
        <select class="wc-product-search" multiple="multiple" style="width: 100%;" id="add_item_id" name="_exclude_products[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce' ); ?>">
            <?php
            get_exclude_product();
            ?>
        </select>
    </p>
    <p class="form-field file_name_giftcard giftcard">
        <label for="file_name_giftcard"><?php echo __('Name of PDF file', 'GIFTCARD') ?></label>
        <input style="width: 100%;" type="text" value="<?php echo get_post_meta($post->ID,'file_name_giftcard' ,true) ?>"
               class="file_name_giftcard" name="file_name_giftcard" id="file_name_giftcard"
               placeholder="<?php echo __('Name of PDF file', 'GIFTCARD') ?>">
    </p>

</div>

