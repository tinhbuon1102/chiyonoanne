jQuery(document).ready(function ($) {
    $('.form__steps').wrap('<div class="form__steps_wrap"></div>');
    $('body').on('change', '#copy_to_billing', function (e) {
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
            $.each(addresFields, function (index, field) {
                if ($('#shipping_' + field).length && $('#shipping_' + field).val())
                {
                    $('#billing_' + field).val($('#shipping_' + field).val());
                    if ($('#billing_' + field).is('select'))
                    {
                        $('#billing_' + field).closest('.justwrap').find('.selectbox__option').each(function () {
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
    //validate Next button
    function check_validate() {
        var showRequiredError = false;
        $('#checkout .is-active').find('.validate-required').each(function (i, field) {
            var v_input = $(field).find('input[type="text"]');
            var v_select = $(field).find('select');
            if (v_input.attr('type') != 'hidden' && (v_input.val() == '' || v_select.val() == '')) {
                showRequiredError = true;
            }
        });
        if (showRequiredError == false) {
            $('#checkout .is-active .js-next').removeClass('ch-disable-add-to-cart');
        } else {
            $('#checkout .is-active .js-next').addClass('ch-disable-add-to-cart');
        }
    }
    $('#checkout').find('.validate-required').each(function (i, field) {
        var v_input = $(field).find('input[type="text"]');
        var v_select = $(field).find('select');
        if (v_input.attr('type') != 'hidden' && (v_input.val() == '' || v_select.val() == '')) {
            $('#checkout .js-next').addClass('ch-disable-add-to-cart');
        }
    });
    $("input[type='text'],select").change(function () {
        event.preventDefault();
        check_validate();
    });

    $("#createaccount").change(function () {
        if (this.checked) {
            if ($("#account_password").val() == '') {
                check_validate();
                $('#checkout .is-active .js-next').addClass('ch-disable-add-to-cart');
            } else {
                $('#checkout .is-active .js-next').removeClass('ch-disable-add-to-cart');
                check_validate();
            }
            $("#account_password").change(function () {
                var showRequiredError = false;
                if ($(this).val() == '') {
                    showRequiredError = true;
                }
                if (showRequiredError == false) {
                    $('#checkout .is-active .js-next').removeClass('ch-disable-add-to-cart');
                    check_validate();
                } else {
                    $('#checkout .is-active .js-next').addClass('ch-disable-add-to-cart');
                }
            });
        } else {
            check_validate();
        }
    });
    //end
});