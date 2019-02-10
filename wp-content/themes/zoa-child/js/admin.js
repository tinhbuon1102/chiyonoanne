jQuery(function($){
	if ($('#account_birth_year').length)
	{
		$('#last_name_kana').closest('tr').hide();
		$('#first_name_kana').closest('tr').hide();
		$('#birth_year').closest('tr').hide();
		$('#birth_month').closest('tr').hide();
		$('#birth_date').closest('tr').hide();
		
		$('#last_name_kana').val('...');
		$('#first_name_kana').val('...');
		
		$('#birth_year').html($('#account_birth_year').html());
		$('#birth_month').html($('#account_birth_month').html());
		$('#birth_date').html($('#account_birth_day').html());
		
		$('#birth_year').append('<option selected="selected" value="1"></option>');
		$('#birth_month').append('<option selected="selected" value="1"></option>');
		$('#birth_date').append('<option selected="selected" value="1"></option>');
	}
	
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