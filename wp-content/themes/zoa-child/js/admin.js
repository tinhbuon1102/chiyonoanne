jQuery(function($){
	if ($('#product_attribute_color').length)
	{
		var params = { 
				change: function(e, ui) {
					$('input[name="woof_term_color"]').val( ui.color.toString() );
					$('input[name="woof_term_color"]').closest('.wp-picker-container').find('.wp-color-result').css('background-color', ui.color.toString())
					
				}
		}
		
		$('#product_attribute_color').wpColorPicker( params );
	}

})