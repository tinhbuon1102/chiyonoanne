<?php
/**
 * Created by PhpStorm.
 * User: nguyenhang
 * Date: 21/06/2017
 * Time: 12:58
 */
$content = get_option('magenest_giftcard_to_content');
//$replaces = array(
//    '{{from_name}}' => $from_name,
//    '{{to_name}}' => $to_name,
//    '{{to_email}}' =>$to_email,
//    '{{message}}' => $to_message,
//    '{{code}}' => $code,
//    '{{balance}}' => $balance,
//    '{{expired_at}}' => $expired_at,
//    '{{product_image}}'=> $product_image,
//    '{{store_url}}' => get_permalink ( wc_get_page_id ( 'shop' ) ),
//    '{{store_name}}' => get_bloginfo ( 'name' )
//);
//$content = strtr($content, $replaces);
$links = get_permalink(wc_get_page_id ( 'shop' ));
?>
<table width="100%" bgcolor="#2a2a2a" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
    <tbody>
    <tr>
        <td>
            <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth" bgcolor="#D4EEFF">
                <tbody>
                <tr>
                    <td align="center" height="43" style="font-size:1px; line-height:1px;">&nbsp;</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<!-- Start of logo -->
<table width="100%" bgcolor="#2a2a2a" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="banner">
    <tbody>
    <tr>
        <td>
            <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                <tbody>
                <tr>
                    <td width="100%">
                        <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth" bgcolor="#D4EEFF" >
                            <tbody>
                            <tr>
                                <!-- start of image -->
                                <td align="center" st-image="banner-image">
                                    <div class="imgpop">
                                        <a target="_blank" href="#"><img width="303" border="0" height="279" alt="" border="0" style="display:block; border:none; outline:none; text-decoration:none;" src="http://mygiftcardpro.demo.izysync.com/wp-content/uploads/2017/10/8.png" class="banner"></a>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <!-- end of image -->
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<!-- End of logo -->
<!-- Start of seperator -->
<table width="100%" bgcolor="#2a2a2a" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
    <tbody>
    <tr>
        <td>
            <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth" bgcolor="#D4EEFF">
                <tbody>
                <tr>
                    <td align="center" height="50" style="font-size:1px; line-height:1px;">&nbsp;</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<!-- Start of main -->
<table width="100%" bgcolor="#2a2a2a" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="banner">
    <tbody>
    <tr>
        <td>
            <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth"  bgcolor="#D4EEFF">
                <tbody>
                <tr>
                    <td width="100%">
                        <table width="500" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth" bgcolor="#fff" style="border-radius:5px;">
                            <tbody>
                            <tr>
                                <!-- start of image -->
                                <td align="center" st-image="banner-image">
                                    <table cellspacing="0" cellpadding="0" border="0" align="center" width="445" class="devicewidthinner" >
                                        <tbody>
                                        <tr>
                                            <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                                        </tr>
                                        <tr>

                                            <!-- start of image -->
                                            <td align="left" style="color:#494949;font-size:14px;font-family: Helvetica, arial, sans-serif;">
                                                <?= $content ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td align="center" height="12" style=";">
                                                <a href="<?=$links;?>" style="font-size:12px; line-height:35px; border:none; outline:none; text-decoration:none;color:#fff;background-color: #C7272E;height:35px;display: block;width:200px;text-transform: uppercase;font-family: Helvetica, arial, sans-serif;">
                                                    <?=__("GO TO THE WEBSITE",GIFTCARD_TEXT_DOMAIN)?>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <!-- end of image -->
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<!-- Start of seperator -->
<table width="100%" bgcolor="#2a2a2a" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
    <tbody>
    <tr>
        <td>
            <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth" bgcolor="#D4EEFF">
                <tbody>
                <tr>
                    <td align="center" height="48" style="font-size:1px; line-height:1px;">&nbsp;</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>