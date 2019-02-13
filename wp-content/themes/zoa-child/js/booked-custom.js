jQuery(document).ready(function ($) {
    /*if ($('.custom-steps').length) {
     $(this).children('div').wrap('<fieldset class="step"></fieldset>');
     }*/
    $('div.custom-steps').children('div:not(.btn-group)').wrap('<fieldset class="step"></fieldset>');
    $('div.custom-steps > fieldset').each(function (i) {
        $(this).attr('id', 'step' + (i + 1));
    });
    $("#bookedForm .js-next").addClass('ch-hidden').attr('disabled');
    $('.booked-calendarSwitcher select').addClass('input-select justselect').wrap('<div class="selectric-wrapper selectric-input-select selectric-responsive"></div>');
    $("#password-row").css('display', 'none');
    $("#b_password").removeAttr("required");
    $('#is_register').change(function () {
        $("#b_password").val('');
        if (this.checked) {
            $("#password-row").css('display', 'block');
            $("#b_password").attr('required', 'required');
        } else {
            $("#b_password").removeAttr("required");
            $("#password-row").css('display', 'none');
        }
    });
});