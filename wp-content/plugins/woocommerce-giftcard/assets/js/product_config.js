jQuery(document).ready(function ($) {
    // choose gift card mode
    var create_mode = $('input:radio[name="_gc_mode"]:checked').val();
    if (create_mode == "auto"){
        $('#import_gc').hide();
        $('#gc_expiry_date').show();

        // if create gc auto -> can add price
        $('.gc_preset_price').on('select2:opening select2:closing', function( event ) {
            var $searchfield = $(this).parent().find('.select2-search__field');
            $searchfield.prop('disabled', false);
        });
    }
    if(create_mode == "manual") {
        $('#import_gc').show();
        var post_status = $('#magenest_post_id').val();
        if(post_status == 0){
            $('.magenest_button_import').attr('style','display:none;');
            $('#magenest_help_tip').attr('style','display:inline-block;');
        }

        // if get gc from file import -> can't add price
        $('.gc_preset_price').on('select2:opening select2:closing', function( event ) {
            var $searchfield = $(this).parent().find('.select2-search__field');
            $searchfield.prop('disabled', true);
        });
        $('input:radio[name="_giftcard-price-model"]').each(function(i) {
            if($(this).val() != "selected-price"){
                $(this).closest('span').attr('style','display:none');
                jQuery('#gc_preset_list_price').removeAttr('required');
            }
        });
        // $('input:radio[name="_giftcard-price-model"]').closest('span').attr('style','display:none');
    }
    if(create_mode == "undefined"){
        $('#import_gc').hide();
    }
    $('input:radio[name="_gc_mode"]').change(function () {
        var create_mode = $('input:radio[name="_gc_mode"]:checked').val();
        if (create_mode == "auto"){
            $('#import_gc').hide();
            $('#gc_expiry_date').show();
            $('.gc_preset_price').on('select2:opening select2:closing', function( event ) {
                var $searchfield = $(this).parent().find('.select2-search__field');
                $searchfield.prop('disabled', false);
            });
            $('input:radio[name="_giftcard-price-model"]').each(function(i) {
                $(this).closest('span').attr('style','');
            });
        }  else {
            $('input:radio[name="_giftcard-price-model"]').each(function(i) {
                var check = $(this).val();
                if(check != 'selected-price'){
                    $(this).closest('span').attr('style','display:none');
                    jQuery('#gc_preset_list_price').removeAttr('required');
                }else{
                    $(this).attr('checked','checked');
                    $('.giftcard-price').each(function(i) {
                        $(this).hide();
                    });
                    $('#selected-price-for-giftcard').show();
                }
            }) ;

            $('#import_gc').show();
            var post_status = $('#magenest_post_id').val();
            if(post_status == 0){
                $('.magenest_button_import').attr('style','display:none;');
                $('#magenest_help_tip').attr('style','display:inline-block;');
            }
            $('.gc_preset_price').on('select2:opening select2:closing', function( event ) {
                var $searchfield = $(this).parent().find('.select2-search__field');
                $searchfield.prop('disabled', true);
            });
        }
    });


    $('.gc_preset_price').select2({
        width: '100%',
        placeholder: 'Enter the values'
    }).on('select2:open', function (e) {
        $('.select2-container--open .select2-dropdown--below').css('display', 'none');

    }).on('select2:opening change.select2', function (event) {
        var searchfield = $(this).parent().find('.select2-search__field');
        searchfield.attr({'id': 'add_preset_price', 'type': 'number'});
    }).on('select2:unselect', function (event) {
        var data = event.params.data;
        var gc_preset_price = $('#_giftcard-preset-price').val();
        var list_price = gc_preset_price.length !== 0 ? gc_preset_price.split(';') : [];
        var list_price_after = [];
        $.each(list_price, function (index, value) {
            if (value != data.text){
                list_price_after.push(value);
            }
        });
        $('#_giftcard-preset-price').val(list_price_after.sort().join(';'));

        if ($('.gc_preset_price').find("option[value='" + data.id + "']").length){
            $('.gc_preset_price').find("option[value='" + data.id + "']").remove();
        }
        var valuePrice = $('#gc_preset_list_price').val();
        if (valuePrice !== null){
            var valueString = ""+valuePrice.join(";") + "";
            $('#_giftcard-preset-price').removeAttr('value');
            $('#_giftcard-preset-price').attr('value',valueString);
        } else {
            $('#_giftcard-preset-price').removeAttr('value');
        }

    });

    function addPrice(data){
        var list_price = $('#_giftcard-preset-price').val().length !== 0 ? $('#_giftcard-preset-price').val().split(';') : [];
        // Set the value, creating a new option if necessary
        if ($('.gc_preset_price').find("option[value='" + data.id + "']").length) {
            // $('.gc_preset_price').val(data.id).trigger('change');
        } else {
            if(data.text.indexOf("-") != -1 ){
                alert('Negative numbers are not allowed');
            }else{
                // Create a DOM Option and pre-select by default
                var newOption = new Option(data.text, data.id, true, true);
                // Append it to the select
                $('.gc_preset_price').append(newOption).trigger('change');
                list_price.push(data.text);

                $('#_giftcard-preset-price').attr('value', list_price.sort().join(';'));
            }

        }
    }

    $('#add_preset_price').live('keyup', function (event) {
        if (event.which === 13) {
            var data = {
                id: $(this).val(),
                text: $(this).val()
            };
            addPrice(data);
            $(this).val("");
        }
    });

    $('#add_preset_price').live('blur', function (event) {
        var string_price = $(this).val(); // example: 100;123;145
        if (string_price !== ""){
            var prices = string_price.split(';');
            $.each(prices, function (index, price) {
                addPrice({ id: price, text: price });
            });
            $(this).val("");
        }
    });

    if ($('#_giftcard').is(':checked')) {
        $('#_gc_config').show();
        show_price_giftcard_model();
        show_expiry_giftcard_model();
        $('#_giftcard-expiry-date').datepicker();//_regular_price
        $('#_regular_price').attr('required','required');

    } else {
        $('#_gc_config').hide();
    }


    $('#_giftcard').on('change', function(event) {
        $('#_gc_config').toggle();
        show_price_giftcard_model();
        show_expiry_giftcard_model()
        $('#_giftcard-expiry-date').datepicker();//_regular_price
    });
    $('input:radio[name="_giftcard-price-model"]').change(function() {
        show_price_giftcard_model();
    });

    $('input:radio[name="_giftcard-expiry-model"]').change(function() {
        show_expiry_giftcard_model();
    });
});

function show_price_giftcard_model() {
    var pricemodel = jQuery('input:radio[name="_giftcard-price-model"]:checked').val();
    if (typeof pricemodel == 'undefined'){
        jQuery('input:radio[name="_gc_mode"]').each(function (i) {
            if(jQuery(this).val() == 'auto'){
                jQuery(this).attr('checked','checked')
            }
        });
        jQuery('input:radio[name="_giftcard-price-model"]').each(function(i) {
           if(jQuery(this).val() == 'fixed-price'){
               jQuery(this).attr('checked','checked')
           }
        });
    }
    pricemodel = jQuery('input:radio[name="_giftcard-price-model"]:checked').val();
    switch (pricemodel) {
        case 'fixed-price': {
            jQuery('#_regular_price').attr({'required':'required'}).removeAttr('readonly');
            jQuery('.giftcard-price').each(function(i) {
                jQuery(this).hide();
            }) ;
            break;
        }
        case 'selected-price': {
            jQuery('#_regular_price').val("0").attr('readonly', 'readonly');

            jQuery('.giftcard-price').each(function(i) {
                jQuery(this).hide();
            });
            jQuery('#selected-price-for-giftcard').show();
            if(jQuery('input:radio[name="_gc_mode"]:checked').val() == 'auto'){
                jQuery('#gc_preset_list_price').attr('required', 'required');
            }else{
                jQuery('#gc_preset_list_price').removeAttr('required');
            }
            break;
        }
        case 'custom-price' : {
            jQuery('#_regular_price').val("0").attr('readonly', 'readonly');

            jQuery('.giftcard-price').each(function(i) {
                jQuery(this).hide();
            }) ;
            jQuery('.custom-price-model').each(function(i) {
                jQuery(this).show();
            }) ;
            break;
        }
    }
}
function show_expiry_giftcard_model() {
    var expirymodel = jQuery('input:radio[name="_giftcard-expiry-model"]:checked').val();
    switch (expirymodel){
        case 'expiry-date' :{
            jQuery('.expiry_time').each(function (i) {
                jQuery(this).hide();
            });
            jQuery('.expiry_date').each(function (i) {
                jQuery(this).show();
            });
            break;
        }
        case 'expiry-time' :{
            jQuery('.expiry_time').each(function (i) {
                jQuery(this).show();
            });
            jQuery('.expiry_date').each(function (i) {
                jQuery(this).hide();
            });
            break;
        }
    }
}