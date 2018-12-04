jQuery(document).ready(function($){
	$('.form__steps').wrap('<div class="form__steps_wrap"></div>');
	$('body').on('change', '#copy_to_billing', function(e){
		e.preventDefault();
		var addresFields = [
			'last_name',
			'first_name',
			'last_name_kana',
			'first_name_kana',
			'email',
			'phone',
			'postcode',
			'country',
			'state',
			'city',
			'address_1',
			'address_2',
		];
		
		if ($(this).prop('checked'))
		{
			$.each(addresFields, function(index, field){
				if ($('#shipping_' + field).length && $('#shipping_' + field).val())
				{
					$('#billing_' + field).val($('#shipping_' + field).val());
					if ($('#billing_' + field).is('select'))
					{
						$('#billing_' + field).closest('.justwrap').find('.selectbox__option').each(function(){
							if ($(this).attr('data-value') == $('#billing_' + field).val())
							{
								var field_name = $(this).text();
								$(this).closest('.selectbox').find('.selectbox__label').text(field_name);
							}
						});
					}
				}
			});
		}
	});
});