<?php
/**
 * Created by PhpStorm.
 * User: doanhcn2
 * Date: 07/04/2018
 * Time: 13:20
 */
?>

<?php
if (!defined('ABSPATH'))
	exit();
$editor_option = array(
	'wpautop' => true,
	'media_buttons' => true,
	'textarea_rows' => 10,
	'teeny' => false,
	'dfw' => false,
	'tinymce' => true,
	'quicktags' => false,
	'drag_drop_upload' => true,
);
?>

<div class="design_pdf full-width-layout">
	<div class="tool-box left-sidebar ui-widget-content " style="width: 30%; display: inline-block; ">
		<p id="aaaaa"><?=__('Magenest',GIFTCARD_TEXT_DOMAIN)?></p>
	</div>
	<div class="preview right-sidebar ui-state-highlight" style="width: 60%; display: inline-block; min-height: 200px">
        <p><?=__('Drop here',GIFTCARD_TEXT_DOMAIN)?></p>
	</div>
	<div style="clear: both;"></div>

</div>

<table class="form-table">
    <tbody>
    <tr valign="top">
        <th scope="row" class="titledesc">
            <label for="magenest_license_delivery_when"><?=__('Delivery license and send notification email when status of
                order',GIFTCARD_TEXT_DOMAIN)?></label>
        </th>
        <td class="forminp forminp-select">
            <select name="magenest_license_delivery_when" id="magenest_license_delivery_when" style="" class="wc-enhanced-select">
                <option value="completed" <?php echo get_option('magenest_license_delivery_when') == "completed" ? 'selected' : '' ?>><?=__('Completed',GIFTCARD_TEXT_DOMAIN)?></option>
                <option value="processing" <?php echo get_option('magenest_license_delivery_when') == "processing" ? 'selected' : '' ?>><?=__('Processing',GIFTCARD_TEXT_DOMAIN)?></option>
                <option value="on-hold" <?php echo get_option('magenest_license_delivery_when') == "on-hold" ? 'selected' : '' ?>><?=__('On hold',GIFTCARD_TEXT_DOMAIN)?></option>
                <option value="pending" <?php echo get_option('magenest_license_delivery_when') == "pending" ? 'selected' : '' ?>><?=__('Pending',GIFTCARD_TEXT_DOMAIN)?></option>
            </select>
        </td>
    </tr>
    <tr valign="top">
        <th scope="row" class="titledesc">
            <label for="magenest_license_delivery_subject"><?=__('Email subject of delivered pin',GIFTCARD_TEXT_DOMAIN)?></label>
        </th>
        <td class="forminp forminp-textarea">
            <textarea name="magenest_license_delivery_subject" id="magenest_license_delivery_subject" style="" class=""
                      placeholder=""><?php echo get_option('magenest_license_delivery_subject') ?></textarea>
        </td>
    </tr>
    <tr valign="top">
        <th scope="row" class="titledesc">
            <label for="magenest_license_delivery_to_content"><?=__('Email content of delived pins',GIFTCARD_TEXT_DOMAIN)?></label>
        </th>
        <td class="forminp forminp-textarea">
            <?php
            $editor_option['textarea_name'] = 'magenest_license_delivery_to_content';
            wp_editor(get_option('magenest_license_delivery_to_content'), 'magenest_license_delivery_to_content', $editor_option);
            ?>
        </td>
    </tr>

    <tr valign="top">
        <th scope="row" class="titledesc">
            <label for="magenest_license_delivery_bcc_name"><?=__('BCC name',GIFTCARD_TEXT_DOMAIN)?></label>
        </th>
        <td class="forminp forminp-text">
            <input name="magenest_license_delivery_bcc_name" id="magenest_license_delivery_bcc_name" type="text" style="" value="<?php echo get_option('magenest_license_delivery_bcc_name') ?>" class="" placeholder=""></td>
    </tr>
    <tr valign="top">
        <th scope="row" class="titledesc">
            <label for="magenest_license_delivery_bcc_email"><?=__('BCC Email',GIFTCARD_TEXT_DOMAIN)?></label>
        </th>
        <td class="forminp forminp-text">
            <input name="magenest_license_delivery_bcc_email" id="magenest_license_delivery_bcc_email" type="text" style="" value="<?php echo get_option('magenest_license_delivery_bcc_email') ?>" class="" placeholder=""></td>
    </tr>
    </tbody>
</table>

