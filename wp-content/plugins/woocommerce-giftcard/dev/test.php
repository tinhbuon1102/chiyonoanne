<?php
/**
 * Created by Magenest
 * User: Luu Thanh Thuy
 * Date: 13/01/2016
 * Time: 14:41
 */
function testGenerateGiftcard() {
    $buygc = new Magenest_Giftcard_Buygiftcard();
    $buygc->generateGiftcard(130);
}

function testCronSendMail() {
    global $magenest_giftcard_loaded;
    $magenest_giftcard_loaded->dailyCron();
}