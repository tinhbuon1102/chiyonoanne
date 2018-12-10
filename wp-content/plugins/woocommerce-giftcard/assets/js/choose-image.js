jQuery(document).ready(function($) {

    // load pdf data from database
    // variable pdfData, imgSrc are setup in PdfSettings.php
    if (pdfData != undefined && imgSrc != undefined){
        try {
            var coor_area = []; // coordinate depend #pdf_area, change depend screen size
            var coor_image = JSON.parse(pdfData); // coordinate depend image size

            var ratio = $('#_pdfwidth').val() / $('#pdf_area').width();

            $.each(coor_image, function (index, value) {
                value.x = Math.round(value.x / ratio);
                value.y = Math.round(value.y / ratio);
                value.height = Math.round(value.height / ratio);
                value.width = Math.round(value.width / ratio);
                if (value.hasOwnProperty('textArea')) {
                    value.textArea.size = Math.round(value.textArea.size / ratio);
                }
                coor_area[index] = value;
            });
            createArea(imgSrc, coor_area);
        } catch (e) {
            console.log(e);
        }
    }


    // set background image for pdf
    if ($('.set_pdf_images').length > 0) {
        if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
            $(document).on('click', '.set_pdf_images', function(e) {
                e.preventDefault();
                var button = $(this);
                $('#pdf_area').css('height', '');
                wp.media.editor.send.attachment = function(props, attachment) {
                    $('#_background_img').val(attachment.id);
                    createArea(attachment.url,coor_area);
                    $('#_pdfwidth').val( attachment.width );
                    $('#_pdfheight').val( attachment.height );
                };
                wp.media.editor.open(button);

                return false;
            });
        }
    }

    function createArea(imageUrl, pdf_data  ) {
        if (imageUrl){
            $('#pdf_area').css('height', '');
            // $('#pdf_area').css('width', '');
            $('#pdf_area').find('img').selectAreas('destroy');
            $('#pdf_area').find('img').attr('src', imageUrl).css({'width': '100%', 'height': '100%'});
            $('#pdf_area').find('img').selectAreas({
                minSize: [10, 10],
                overlayOpacity: 0,
                onChanged: debugQtyAreas,
                allowSelect: false,
                areas: pdf_data
            });

        }
    }

    function debugQtyAreas (event, id, areas) {
        var area = areas.filter(function (e) {
            return e.id == id;
        });

        if (typeof(area[0]) !== "undefined"){
            if (area[0].type_area == 'textArea'){
                $('#pdf-tool-text').removeClass('closed').data('text_data', area[0]);
                $('#pdf-tool-img').addClass('closed');
                $('#pdf-tool-qrcode').addClass('closed');
                // $('#pdf-tool-text').find('#text_data').data('text_data', area[0]);
                $('#pdf-tool-text').find("#_text_content").val(area[0].textArea.content);
                $('#pdf-tool-text').find("#_text_size").val(area[0].textArea.size);
                $('#pdf-tool-text').find("#_text_color").val(area[0].textArea.color);
                $('#pdf-tool-text').find("#_text_color").val(area[0].textArea.color);
                $('#pdf-tool-text').find("#_text_font").find('option[value=' + area[0].textArea.fontPath +']').attr('selected','selected');
                var size = parseInt(area[0].textArea.size);
                $('#pdf-tool-text').find( "#slider-range-min" ).slider("value", size);
            }
            if (area[0].type_area == 'imageArea'){
                $('#pdf-tool-text').addClass('closed');
                $('#pdf-tool-img').removeClass('closed').data('image_data', area[0]);
                $('#pdf-tool-qrcode').addClass('closed');
            }
            if (area[0].type_area == 'qrCode'){
                $('#pdf-tool-text').addClass('closed');
                $('#pdf-tool-img').addClass('closed');
                $('#pdf-tool-qrcode').removeClass('closed').data('qrcode', area[0]);
            }
        }
    };

    // delete background image
    $('#delete_brimage').on("click", function () {
        $('#pdf_area').css('height', '400px');
        $('#pdf_area').find('img').selectAreas('destroy');
        $('#pdf_area').find('img').attr('src', '').css({'width': '100%', 'height': '100%'});
        $('#_pdfwidth').val(0);
        $('#_pdfheight').val(0);
        return false;
    });

    // set image for image area
    if ($('.set-area-image').length > 0) {
        if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
            $(document).on('click', '.set-area-image', function(e) {
                e.preventDefault();
                var button = $(this);
                wp.media.editor.send.attachment = function(props, attachment) {
                    var imageData = $('#pdf-tool-img').data('image_data');
                    imageData.imgArea.src = attachment.url;
                    imageData.imgArea.attachment_id = attachment.id;
                    $('#pdf_area').find('img').selectAreas('edit', imageData.id, imageData);
                };
                wp.media.editor.open(button);
                return false;
            });
        }
    }


});
