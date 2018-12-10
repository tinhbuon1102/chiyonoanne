<?php
if(!empty($data)){
    $image = $data['img_background'];
    if($image == ''){
        $image = get_option('magenestgc_pdf_background');
    }
    $image_link = GIFTCARD_URL.'/assets/'.$image;
    
    $pagewidth = $data['pagewidth'];
    $pageheight = $data['pageheight'];
    $style = $pagewidth."px  ".$pageheight."px;";
    $page_size = $pagewidth. ':' . $pageheight;

    $send_from = $data['send_from'];
    $send_from_x = $data['send_from_x'];
    $send_from_y = $data['send_from_y'];

    $send_to = $data['send_to'];
    $send_to_x = $data['send_to_x'];
    $send_to_y = $data['send_to_y'];

    $balance = $data['balance'];
    $balance_x = $data['balance_x'];
    $balance_y = $data['balance_y'];

    $code = $data['code'];
    $code_x = $data['code_x'];
    $code_y = $data['code_y'];

    $expiry = $data['expriry'];
    $expriry_x = $data['expriry_x'];
    $expriry_y = $data['expriry_y'];

    $message = $data['message'];
    $message_x = $data['message_x'];
    $message_y = $data['message_y'];

    $barcode = GIFTCARD_URL.'/barcode.jpg';
    $barcode_x = $data['barcode_x'];
    $barcode_y = $data['barcode_y'];
}
?>
<style>
    .pdf_template{
        font-size: 10px;
        position: relative;
        width: <?= $pagewidth ?>px;
        height: <?= $pageheight ?>px;
        background-size: <?= $style ?>;
        background-image: url(<?= $image_link ?>);
        background-repeat: no-repeat;
        /*position: fixed;*/
        top: 100px;
        right: 50px;
    }
    .send_from{
        position: absolute;
        left: <?= $send_from_x ?>px;
        bottom: <?= $send_from_y ?>px;
        z-index: 9999;
    }
    .send_to{
        position: absolute;
        left: <?= $send_to_x ?>px;
        bottom: <?= $send_to_y ?>px;
    }
    .balance{
        position: absolute;
        left: <?= $balance_x ?>px;
        bottom: <?= $balance_y ?>px;
    }
    .giftcard_code{
        position: absolute;
        left: <?= $code_x ?>px;
        bottom: <?= $code_y ?>px;
    }
    .expiry_date{
        position: absolute;
        left: <?= $expriry_x ?>px;
        bottom: <?= $expriry_y ?>px;
    }
    .message{
        position: absolute;
        left: <?= $message_x ?>px;
        bottom: <?= $message_y ?>px;
    }
    .barcode{
        position: absolute;
        left: <?= $barcode_x ?>px;
        bottom: <?= $barcode_y ?>px;
    }
    .barcode img{
        width: 100px;
        height: 40px;
    }
</style>
<div class="pdf_template">
    <div class="send_from">
        <?= !empty($send_from_x)?$send_from:''?>
    </div>
    <div class="send_to">
        <?= !empty($send_to_x)?$send_to:''?>
    </div>
    <div class="balance">
        <?= !empty($balance_x)?$balance:''?>
    </div>
    <div class="giftcard_code">
        <?= !empty($code_x)?$code:''?>
    </div>
    <div class="expiry_date">
        <?= !empty($expriry_x)?$expiry:''?>
    </div>
    <div class="message">
        <?= !empty($message_x)?$message:''?>
    </div>
    <div class="barcode">
        <img src="<?= !empty($barcode_x)?$barcode:''?>"/>
    </div>
</div>
