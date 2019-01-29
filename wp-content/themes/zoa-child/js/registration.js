jQuery(document).ready(function($) {
	$('select#birth_year').parent().addClass('column_3');
	$('select#birth_month').parent().addClass('column_3');
	$('select#birth_date').parent().addClass('column_3');
	var userForm = $('.wppb-user-forms > ul');
	userForm.children('li.column_3').wrapAll( '<ul class="column_wrap"></ul>' );
	$('ul.column_wrap').wrap('<span class="value birth_date_value"></span>');
	$('span.birth_date_value').wrap('<li class="wppb-form-field birth_date"></li>');
	$('li.birth_date').prepend('<label class="form-row__label">Birth Date</div>');
	userForm.children('li').children('label').addClass('form-row__label');
	userForm.children('li').children('input').wrap('<span class="value"></span>');
	$('select.custom_field_select').addClass('input-select justselect');
	$('select.custom_field_select').wrap('<div class="selectric-wrapper selectric-input-select selectric-responsive"></div>');
});