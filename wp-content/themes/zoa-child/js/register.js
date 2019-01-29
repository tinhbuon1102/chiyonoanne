jQuery(function($){
	var validateForm = $("form#wppb-register-user");
	validateForm.find('input, select').each(function(){
		var required = $(this).attr('required');
		if (typeof required !== typeof undefined && required !== false) {
			$(this).addClass('validate[required]');
		}
	});
	
	function populateBirthField()
	{
		$('#birth_year option:eq(0)').text('-----');
		$('#birth_month option:eq(0)').text('-----');
		$('#birth_day option:eq(0)').text('-----');
		
		for (var i=1930; i<=2017; i++){
			$('#birth_year').append('<option value="'+ i +'">'+ i +'</option>');
		}
	
		for (var i=1; i<=12; i++) {
			$('#birth_month').append('<option value="'+ i +'">'+ i +'</option>');
		}

		for (var i=1; i<=31; i++) {
			$('#birth_day').append('<option value="'+ i +'">'+ i +'</option>');
		}
	}
	populateBirthField();
	
	$.fn.autoKana('#first_name', '#first_name_kana');
	$.fn.autoKana('#last_name', '#last_name_kana');
	
	$('body').on('change', '#email', function(e){
		$('#username').val($('#email').val());
	});
	
	$('body').on('click', '#register', function(e){
		e.preventDefault();
		$('#username').val($('#email').val());
		
		var validateForm = $("form#wppb-register-user");
  		validateForm.validationEngine({
  			promptPosition : 'inline',
  			addFailureCssClassToField : "inputError",
  			bindMethod : "live"
  		});
  		var isValid = validateForm.validationEngine('validate');
  		if (isValid) {
  			validateForm.submit();
  		}
	});
})