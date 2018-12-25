jQuery(document).ready(function($) {
	/*if ($('.custom-steps').length) {
		$(this).children('div').wrap('<fieldset class="step"></fieldset>');
	}*/
	$('div.custom-steps').children('div:not(.btn-group)').wrap('<fieldset class="step"></fieldset>');
	$('div.custom-steps > fieldset').each(function(i) {
		$(this).attr('id','step' + (i+1));
	});
	$("#bookedForm .js-next").addClass('ch-hidden').attr('disabled');
});