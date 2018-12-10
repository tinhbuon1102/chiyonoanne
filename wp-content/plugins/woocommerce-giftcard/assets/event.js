/**
 * Created by nguyenhang on 31/03/2017.
 */

Array.prototype.max = function() {
    return Math.max.apply(this, this);
};
function saveFormGiftCard() {
    document.getElementById("giftcard-apply-form").submit();
}
function removeapplygiftcard() {

    var giftcard_code = jQuery('#magenest_giftcardcode').val();
    document.getElementById("giftcard-remove-apply-form").submit();
    // var data = {action: 'removeapplygiftcard'};
    // data['removeapplygiftcard'] = giftcard_code;
    // jQuery.post(ajaxurl, data, function (response) {
    //     var obj = JSON.parse(response);
    //     var result = obj.result;
    //     if (obj.type = 'success') {
    //
    //     }
    // });
}
