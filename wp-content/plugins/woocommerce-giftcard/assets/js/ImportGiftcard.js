jQuery(document).ready(function($) {
    var loaderContainer;
    var navListItems = $('ul.setup-panel li a'),
        allWells = $('.setup-content');

    allWells.hide();
    var product_id = $('#gc_product').val();
    if(product_id <= 0){
        $('#activate-step-3').attr('style','display:none');
    }
    $('#gc_product').on('change', function () {
        var product_id = $(this).val();
        if(product_id > 0){
            $('#activate-step-3').removeAttr('style');
        }else{
            $('#activate-step-3').attr('style','display:none');
        }
    });
    navListItems.click(function(e)
    {
        e.preventDefault();
        var $target = $($(this).attr('href')),
            $item = $(this).closest('li');

        if (!$item.hasClass('disabled')) {
            navListItems.closest('li').removeClass('active');
            $item.addClass('active');
            allWells.hide();
            $target.show();
        }
    });

    $('ul.setup-panel li.active a').trigger('click');

    // DEMO ONLY //
    $('#activate-step-2').on('click', function(e) {
        $('ul.setup-panel li:eq(1)').removeClass('disabled');
        $('ul.setup-panel li a[href="#step-2"]').trigger('click');
        $(this).remove();
    });
    $('#activate-step-3').on('click', function(e) {
        $('ul.setup-panel li:eq(2)').removeClass('disabled');
        $('ul.setup-panel li a[href="#step-3"]').trigger('click');
        // $(this).remove();
    });
    
    $('#fileToUpload').change(function (event) {
        var file = $('#fileToUpload')[0].files[0];
        if (file) {
            var fileSize = 0;
            if (file.size > 1024 * 1024)
                fileSize = (Math.round(file.size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
            else
                fileSize = (Math.round(file.size * 100 / 1024) / 100).toString() + 'KB';
            // support file .csv, .xls, .xlsx
            if (jQuery.inArray(file.type, ['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']) != -1){
                jQuery('#import_button').removeAttr('disabled');
            } else {
                jQuery('#import_button').attr('disabled', 'disabled');
            }
            $('#fileName').append('Name: ' + file.name);
            $('#fileSize').append('Size: ' + fileSize);
            $('#fileType').append('Type: ' + file.type);
        }
    });
    
    $('#import_button').click(function () {
        var form_data = new FormData();
        form_data.append("action", 'process_file');
        form_data.append("security", gc_import.gc_sercurity);
        form_data.append("file_gc", $('#fileToUpload')[0].files[0]);
        jQuery.ajax({
            xhr: function()
            {
                var xhr = new window.XMLHttpRequest();
                //Upload progress
                xhr.upload.addEventListener("progress", function(evt){
                    if (evt.lengthComputable) {
                        var percentComplete = Math.round(evt.loaded * 100 / evt.total);
                        document.getElementById('progressNumber').innerHTML = percentComplete.toString() + '%';
                    }
                    else {
                        document.getElementById('progressNumber').innerHTML = 'unable to compute';
                    }
                }, false);

                return xhr;
            },
            url: gc_import.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: form_data,
            contentType: false,
            processData: false,
            beforeSend: function (XMLHttpRequest) {

            },
            success: function (result) {
                if (result.status != false){
                    $.each(result.col_title, function (i, item) {
                        $("#gc_code, #gc_balance").append($('<option>', {
                            value: i,
                            text : item
                        }));
                    });

                    jQuery('#gc_product, #gc_pdf_template, #gc_email_template').select2({
                        width: '100%'
                    });
                    jQuery('#activate-step-3').data("file_data", result.data);
                    jQuery('ul.setup-panel li:eq(1)').removeClass('disabled');
                    jQuery('ul.setup-panel li a[href="#step-2"]').trigger('click');
                    // jQuery('#activate-step-2').remove();
                }
            },
            error: function () {
                alert("There was an error attempting to upload the file.");
            },
            timeout: 5000
        });
    });
    
    $('#activate-step-3').click(function () {
        var gc_data = $('#activate-step-3').data('file_data');
        var field_mapping = {
            'code': $('#gc_code').val(),
            'balance': $('#gc_balance').val(),
            'status': $('#gc_status').val(),
            'expiry_date': $('#gc_expiry_date').val()
        };

        var product_config = {
            'product_id': $('#gc_product').val(),
            'email_id' : $('#gc_email_template').val(),
            'pdf_id' : $('#gc_pdf_template').val(),
            'pdf_name' : $('#gc_pdf_name').val()
        };

        jQuery.ajax({
            url: gc_import.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                'action': 'save_gc_import',
                'security': gc_import.gc_sercurity,
                'gc_data' : gc_data,
                'field_mapping' : field_mapping,
                'product_conf' : product_config
            },
            beforeSend: function (XMLHttpRequest) {
                loaderContainer = $('<span class="ajax-loader" style="margin: 5px;"></span>').insertAfter('#save_to_database_progress');

                $('<img/>', {
                    src: '/wp-admin/images/loading.gif',
                    'class': 'loader-image'
                }).appendTo(loaderContainer);
            },
            success: function (result) {
				$("#gc_result_save").html("");
                $.each(result.result_save, function (i, item) {
                    console.log(item);
                    var text = 'Row ' + i.toString() + ' save ';
                    var status = (item['status'] == true) ? 'sucessfull' : 'error';
                    var remove = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                    var $notice = $('<div>', {
                        text : text + status.toString() + '. Message: ' + item['message'].toString(),
                        class: (item['status'] == true) ? 'alert alert-success alert-dismissible fade in' : 'alert alert-danger alert-dismissible fade in',
                    }).append(remove);
                    $("#gc_result_save").append($notice);
                });

            },
            complete: function () {
                loaderContainer.remove();
            },
            error: function () {
                console.log("save error");
            },
            timeout: 15000
        });
    })
});
