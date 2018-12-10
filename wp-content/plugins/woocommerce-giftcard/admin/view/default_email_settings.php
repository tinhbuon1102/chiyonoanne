<?php
/**
 * Created by PhpStorm.
 * User: doanhcn2
 * Date: 10/04/2018
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


<table class="form-table">
    <tbody>
    <tr valign="top">
        <th scope="row" class="titledesc">
            <label for="magenest_giftcard_to_subject"><?=__('Email subject',GIFTCARD_TEXT_DOMAIN)?></label>
        </th>
        <td class="forminp forminp-textarea">
            <textarea name="magenest_giftcard_to_subject" id="magenest_giftcard_to_subject" style="" class=""
                      placeholder=""><?php echo get_option('magenest_giftcard_to_subject') ?></textarea>
        </td>
    </tr>
    <tr valign="top">
        <th scope="row" class="titledesc">
            <label for="magenest_giftcard_to_content"><?=__('Email content',GIFTCARD_TEXT_DOMAIN)?></label>
        </th>
        <td class="forminp forminp-textarea">
            <?php
            $editor_option['textarea_name'] = 'magenest_giftcard_to_content';
            wp_editor(get_option('magenest_giftcard_to_content'), 'magenest_giftcard_to_content', $editor_option);
            ?>
        </td>
    </tr>
    <tr valign="top" class="">
        <th scope="row" class="titledesc"><?=__('Attach pdf gift card',GIFTCARD_TEXT_DOMAIN)?></th>
        <td class="forminp forminp-checkbox">
            <fieldset>
                <legend class="screen-reader-text"><span><?=__('Attach pdf gift card',GIFTCARD_TEXT_DOMAIN)?></span></legend>
                <label for="magenest_giftcard_to_pdf">
                    <input name="magenest_giftcard_to_pdf" id="magenest_giftcard_to_pdf" type="checkbox"
                           value="yes" <?= (get_option('magenest_giftcard_to_pdf', true) == 'yes') ? 'checked="checked"' : ''; ?>>
                </label>
                <p class="description"></p>
            </fieldset>
        </td>
    </tr>
    <tr valign="top" class="">
        <th scope="row" class="titledesc"><?=__('BCC the Gift card to the sender',GIFTCARD_TEXT_DOMAIN)?></th>
        <td class="forminp forminp-checkbox">
            <fieldset>
                <legend class="screen-reader-text"><span><?=__('BCC the Gift card to the sender',GIFTCARD_TEXT_DOMAIN)?></span></legend>
                <label for="giftcard_bcc_sender">
                    <input name="giftcard_bcc_sender" id="giftcard_bcc_sender" type="checkbox" value="yes" <?= (get_option('giftcard_bcc_sender', false) == 'yes') ? 'checked="checked"' : ''; ?>>
                </label>
                <p class="description"></p>
            </fieldset>
        </td>
    </tr>
    </tbody>
</table>

