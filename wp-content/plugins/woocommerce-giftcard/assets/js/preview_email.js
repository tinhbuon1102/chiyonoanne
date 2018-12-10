jQuery(document).ready(function ($) {
    jQuery('#preview_email').on("click", function () {

        var data = {
            'balance': jQuery('.giftcard_amount').val(),
            'to_name': jQuery('#send_to_name').val(),
            'to_email': jQuery('#send_to_email').val(),
            'message': jQuery('#message').val(),
            'scheduled-send-date': jQuery('#giftcard-schedule-send-date').val(),
            'email_template': jQuery('#_choose_email').val()
        };
        console.log(data);
        var dataSend = {
            action: 'preview_email_template',
            'info_email': data,
        }

        var loaderContainer;

        jQuery.ajax({
            method: "POST",
            url: ajax_object.ajax_url,
            data: dataSend,
            dataType: "json",
            beforeSend: function () {
                loaderContainer = $('<span class="ajax-loader" style="margin: 5px;"></span>').insertAfter('a#preview_email');

                $('<img/>', {
                    src: '/wp-admin/images/loading.gif',
                    'class': 'loader-image'
                }).appendTo(loaderContainer);
            },

            success: function (response) {
                jQuery('#email_preview_box').trigger('preview', response.content);
            },
            complete: function () {
                loaderContainer.remove();
            },
            timeout: 5000
        });
        return false;
    });

    // show box preview
    $('#email_preview_box').on('preview', function (event, license_html) {
        $('#email_preview_box').empty();
        $('#email_preview_box').append(license_html);
        $("#email_preview_box").dialog({
            modal: true,
            minWidth: 900,
            buttons: {
                Ok: function () {
                    $(this).dialog("close");
                }
            }
        });
        $('.ui-dialog').attr('class','preview_email_template ui-dialog ui-widget ui-widget-content ui-corner-all ui-front ui-dialog-buttons ui-draggable ui-resizable')
    });
});