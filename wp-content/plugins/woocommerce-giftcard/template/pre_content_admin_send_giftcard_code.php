<?php
$attach_pdf_option = get_option( 'magenest_giftcard_to_pdf', 'yes' );
?>
<table>
    <tr>
        <th><?= __('Giftcard code','GIFTCARD');?></th>
        <td><?= $code ?></td>
    </tr>
    <tr>
        <th><?= __('Giftcard balance','GIFTCARD');?></th>
        <td><?= $balance ?></td>
    </tr>
    <tr>
        <th><?= __('Send To Mail','GIFTCARD');?></th>
        <td><?= $send_to_mail ?></td>
    </tr>
    <tr>
        <th><?= __('Email Content','GIFTCARD');?></th>
        <td><?= $email_content ?></td>
    </tr>
    <tr>
        <th><?= __('Attach Pdf','GIFTCARD');?></th>
        <td><?= $attach_pdf_option ?></td>
    </tr>
    <?php if($attach_pdf_option == "yes"):?>
    <tr>
        <th><?= __('Pdf template name','GIFTCARD');?></th>
        <td><?= $pdf_name ?></td>
    </tr>
    <?php endif;
    ?>
    <tr>
        <th><?= __('Email template','GIFTCARD');?></th>
        <td><?= $email_name ?></td>
    </tr>
    <tr>
        <td>
            <button><?= __('Back','GIFTCARD'); ?></button>
        </td>
        <td>
            <button><?= __('Send','GIFTCARD'); ?></button>
        </td>
    </tr>
</table>

