jQuery(document).ready(function ($) {
    jQuery('#preview_pdf').on("click", function () {
        var data = {
            'balance': jQuery('.giftcard_amount').val(),
            'to_name': jQuery('#send_to_name').val(),
            'to_email': jQuery('#send_to_email').val(),
            'message': jQuery('#message').val(),
            'scheduled-send-date': jQuery('#giftcard-schedule-send-date').val(),
            'pdf_template': jQuery('#_choose_pdf').val(),
        };

        var dataSend = {
            action: 'preview_pdf',
            'shortcode': data,
			'pdf_id': jQuery('#_choose_pdf').val()
        }
        var loaderContainer;
        jQuery.ajax({
            method: "GET",
            url: ajax_object.ajax_url,
            data: dataSend,
            dataType: "text",
            beforeSend: function () {
                loaderContainer = $('<span class="ajax-loader" style="margin: 5px;"></span>').insertAfter('a#preview_pdf');
                $('<img/>', {
                    src: '/wp-admin/images/loading.gif',
                    'class': 'loader-image'
                }).appendTo(loaderContainer);
            },

            success: function (response) {
                console.log(response);
                if(response != 0 && response.toString() != '{"message":"You must choose pdf before click button"}'){
                    $('#magenest_noti').attr('style','display:none;');
                    // window.open(response);
                    var content = '<div id="preview_pdf"><embed src="'+ response.toString() +'" width="100%" height="500"></div>';
                    jQuery('#email_preview_box').trigger('preview', content);

                }else{
                    var obj = JSON.parse(response);
                    var string = "<span id='magenest_noti' style='margin: 5px;color: red;'>"+obj.message+"</span>";
                    $(string).insertAfter('a#preview_pdf');
                }
            },
            complete: function () {
                loaderContainer.remove();
            },
            timeout: 5000000
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
        $('.ui-dialog').attr('class','preview_email_template ui-dialog ui-widget ui-widget-content ui-corner-all ui-front ui-dialog-buttons ui-draggable ui-resizable');
    });
});