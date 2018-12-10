jQuery(document).ready(function ($) {
    var handle = $("#custom-handle");
	$('#text-size').slider({
		create: function () {
			handle.text($(this).slider("value"));
		},
		slide: function (event, ui) {
			handle.text(ui.value);
		}
	});

	// add new text area
	$('.add-text').click(function () {
		var areaOptions = {
			x: Math.floor((Math.random() * 200)),
			y: Math.floor((Math.random() * 200)),
			width: 200,
			height: 40,
			'type_area': 'textArea',
			'textArea': {
				'content': "create",
				'size': 35,
				'color' : "#000000",
				'font' : ''
			}
		};
		// output("Add a new area: " + areaToString(areaOptions))
		$('#pdf_area').find('img').selectAreas('add', areaOptions);
		return false;
	});
    // add new shortcode area
    $('.add-shortcode').click(function () {
        var areaOptions = {
            x: Math.floor((Math.random() * 200)),
            y: Math.floor((Math.random() * 200)),
            width: 250,
            height: 40,
            'type_area': 'textArea',
            'textArea': {
                'content': "{{" + $(this).data('shortcode').toString() + "}}",
                'size': 35,
                'color' : "#000000",
				'font' : 'AnonymousPro-Regular'
            }
        };
        // output("Add a new area: " + areaToString(areaOptions))
        $('#pdf_area').find('img').selectAreas('add', areaOptions);
        return false;
    });

	// add new image area
    $('.set-area-image').attr('style','display:none');
	$('.add-image').click(function () {
        $('.set-area-image').attr('style','display:block');
		var areaOptions = {
			x: Math.floor((Math.random() * 200)),
			y: Math.floor((Math.random() * 200)),
			width: 100,
			height: 100,
			'type_area': 'imageArea',
			imgArea: {attachment_id: 0, src: ""}

		};
		// output("Add a new area: " + areaToString(areaOptions))
		$('#pdf_area').find('img').selectAreas('add', areaOptions);
		return false;
	});

	$('.add-qrcode').click(function () {
		var areaOptions = {
			x: Math.floor((Math.random() * 200)),
			y: Math.floor((Math.random() * 200)),
			width: 100,
			height: 100,
			'type_area': 'qrCode',
			qrCode: {
				src: 'https://chart.googleapis.com/chart?chs=260x260&cht=qr&chl=123123'
			}

		};
		// output("Add a new area: " + areaToString(areaOptions))
		$('#pdf_area').find('img').selectAreas('add', areaOptions);
		return false;
	});

	// text content
	$('#_text_content').keyup(function () {
		var textData = $('#pdf-tool-text').data('text_data');
		var text = $('#_text_content').val();
		textData.textArea.content = text;
		$('#pdf_area').find('img').selectAreas('edit', textData.id, textData);
	});

	// text color
	$('#_text_color').change(function () {
		var textData = $('#pdf-tool-text').data('text_data');
		var color = $('#_text_color').val();
		textData.textArea.color = color;
		$('#pdf_area').find('img').selectAreas('edit', textData.id, textData);
	});

	// text size
	$("#slider-range-min").slider({
		range: "min",
		value: 14,
		min: 1,
		max: 100,
		slide: function (event, ui) {
			var textSize = ui.value;
			var textData = $('#pdf-tool-text').data('text_data');
			textData.textArea.size = textSize;
			$('#pdf_area').find('img').selectAreas('edit', textData.id, textData);
			$("#_text_size").val(ui.value);
		}
	});

    // text font
    $('#_text_font').change(function () {
        var textData = $('#pdf-tool-text').data('text_data');
        var fontPath = $('#_text_font').val();
        var font = $('#_text_font').find('option:selected').text();
        textData.textArea.fontPath = fontPath;
        textData.textArea.font = font;
        $('#pdf_area').find('img').selectAreas('edit', textData.id, textData);
    });

	// save pdf data
	$('#publishing-action').find('#publish').click(function () {
		var coor_area = $('#pdf_area').find('img').selectAreas('areas'); // coordinate depend #pdf_area, change depend screen size
        var coor_image = []; // coordinate depend image size

		var ratio = $('#_pdfwidth').val() / $('#pdf_area').width();

        $.each(coor_area, function (index, value) {
            value.x = Math.round(value.x * ratio);
            value.y = Math.round(value.y * ratio);
            value.height = Math.round(value.height * ratio);
            value.width = Math.round(value.width * ratio);
            if (value.hasOwnProperty('textArea')) {
                value.textArea.size = Math.round(value.textArea.size * ratio);
			}
            
        	coor_image[index] = value;
        });
        $('#pdf-config').find('#pdf_data').val(JSON.stringify(coor_image));
		// return false;
	})
});

