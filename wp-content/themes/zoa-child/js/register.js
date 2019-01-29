jQuery(function($){
	var validateForm = $("form#wppb-register-user");
	validateForm.find('input, select').each(function(){
		var required = $(this).attr('required');
		if (typeof required !== typeof undefined && required !== false) {
			$(this).addClass('validate[required]');
		}
	});
	
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