/**
 * Created by nguyenhang on 21/06/2017.
 */
(function ($) {
	$(document).ready(function () {
		$('#magenest_giftcard_timespan').on('change',function () {
			var price = ($(this).val())*1;
			if(price >= 0){
                $('.woocommerce-save-button').attr('style','display:block');
			}else{
                $('.woocommerce-save-button').attr('style','display:none');
			}
        });
		$('#magenest_giftcard_code_pattern').on('change',function () {
			var string = $(this).val();
			var pattern1 = /[[N\d]]/g;
			var pattern2 = /[[A\d]]/g;
			var result1 = false,  result2 = false;
			result1 = pattern1.test(string);
			result2 = pattern2.test(string);
			if(result1 || result2){
                $('.woocommerce-save-button').attr('style','display:block');
			}else{
                $('.woocommerce-save-button').attr('style','display:none');
			}
        });
	});

})(jQuery);
