jQuery(document).ready(function($){
	$(window).on('load resize', function(e){
		e.preventDefault();
		//has gallery
		var ImageW = $("#gallery-image-ow").width();//aspect 3:4
		console.log('MainImageW：' + ImageW + 'px');
		console.log('MainImageH：' + (ImageW*4/3) + 'px');
		$('#gallery-image-ow').css('height', (ImageW*4/3) + 'px');
		//has no gallery
		var SImageW = $(".single-gallery-slider.single-gallery-vertical .single-product-gallery.pro-single-image .pro-carousel-image #gallery-image .pro-img-item").width();//aspect 3:4
		$('.single-gallery-slider.single-gallery-vertical .single-product-gallery.pro-single-image .pro-carousel-image #gallery-image .pro-img-item').css('height', (SImageW*4/3) + 'px');
		
	});
	//change class for woocommerce ultimate gift card plugin
	$( 'form.cart .mwb_wgm_added_wrapper p.mwb_wgm_section' ).each( function() {
		$(this).addClass('field-wrapper').removeClass('mwb_wgm_section').wrap('<div class="form-row"></div>');
	});
	$('.mwb_wgm_delivery_via_email > input').each( function() {
		$(this).wrap('<div class="form-row" />').wrap('<div class="field-wrapper" />');
	});
	//add required element for attribute
	var attribute = $('.variations.pdp__attribute--group');
	if (attribute.length > 0) {
		$('.pdp__attribute--group > .pdp__attribute.variations__attribute').each(function() {
			if ($(this).find('.about_size_wraper').length) {
			$(this).find('.info_show_wrap').before('<abbr class="required" title="Required">*</abbr>');
		} else {
			$(this).find('.pdp__attribute__label').append('<abbr class="required" title="Required">*</abbr>');
		}
		});
	}
	//add class if attribute element width is bigger width
	$(window).on('load resize', function() {
		var vairations = $('.variations.pdp__attribute--group');
		if (vairations.length > 0) {
			var variationCon = vairations.width();
			var attList = [];
			$('.pdp__attribute--group > .pdp__attribute.variations__attribute').each(function() {
				attList.push($(this).width());
			});
			var maxAttW = Math.max.apply(null,attList);
			var sumAttW = attList.reduceRight(function(a,b){return a+b;});
			if ( (sumAttW+24) > variationCon ) {
				$('.pdp__attribute--group > .pdp__attribute.variations__attribute + .pdp__attribute.variations__attribute').css('margin-top', '24px');
			} else {
				$('.pdp__attribute--group > .pdp__attribute.variations__attribute + .pdp__attribute.variations__attribute').css('margin-top', '0');
			}
			console.log('attribute max width：' + maxAttW + ' px');
			console.log('attContainer：' + variationCon + ' px');
			console.log('attList sum：' + sumAttW + ' px');
			console.log('attList sum + 24px：' + (sumAttW+24) + ' px');
		}
	});
	
	function isAddToCartValid()
	{
		var validateForm = $("form.cart");
  		validateForm.validationEngine({
  			promptPosition : 'inline',
  			addFailureCssClassToField : "inputError",
  			bindMethod : "live"
  		});
  		var isValid = validateForm.validationEngine('validate');
  		return isValid;
	}
	
	if ($('.mwb_wgm_added_wrapper').length)
	{
		$('#mwb_wgm_from_name').addClass('validate[required] required');
		$('#mwb_wgm_message').addClass('validate[required] required');
		$('#mwb_wgm_to_email').addClass('validate[required,custom[email]] required');
		$('#mwb_wgm_to_ship').addClass('validate[required] required');
		
		var cloneAddCartBtn = $('button[name="add-to-cart"]').clone();
		cloneAddCartBtn.attr('name', 'add-to-cart-clone');
		cloneAddCartBtn.attr('type', 'button');
		
		$('button[name="add-to-cart"]').hide();
		$('button[name="add-to-cart"]').after(cloneAddCartBtn);
		function showHideAddCartBtn(isValid)
		{
			if (isValid)
			{
				var radioval = $('input[name="mwb_wgm_send_giftcard"]:checked').val();
				if(radioval === "Shipping") {
					$('#previewBox').hide();
				}else{
					$('#previewBox').show();
				}
				
				$('button[name="add-to-cart"]').show();
				$('button[name="add-to-cart-clone"]').hide();
			}
			else {
				$('#previewBox').hide();
				$('button[name="add-to-cart"]').hide();
				$('button[name="add-to-cart-clone"]').show();
			}
		}
		$('input[name="mwb_wgm_send_giftcard"]:radio').change( function() {
			//var isValid = $('form.cart .inputError').length ? false : true;
			//showHideAddCartBtn(isValid);
		});
		$('body').on('blur', 'form.cart input, form.cart textarea', function(){
			setTimeout(function(){
				var isValid = false;
				if (!$('form.cart .inputError').length)
				{
					isValid = isAddToCartValid();
				}
				showHideAddCartBtn(isValid);
			}, 100);
		});
		
		$('body').on('click', 'button[name="add-to-cart-clone"]', function(){
			var isValid = isAddToCartValid()
			showHideAddCartBtn(isValid);
		});
	}
	
	$( '.mwb_wgm_delivery_method_wrap > .mwb_wgm_delivery_method > div' ).each( function() {
		$(this).find('input[type="text"]').wrap(function(i) {
			return '<div class="form-row" />';
		});
		$(this).find('.form-row').wrapInner(function(i) {
			return '<div class="field-wrapper" />';
		});
		//$(this).wrapAll('<div class="field-wrapper"></div>').wrapAll('<div class="form-row"></div>');
	});
	//remove cta class from related items
	if($('.c-product-item .yith-wcwl-add-to-wishlist > div > a').hasClass('cta')){
		$('.c-product-item .yith-wcwl-add-to-wishlist > div > a').removeClass('cta');
	}
	var slickH = $('.pro-carousel-image >#gallery-image > .slick-list').height();
	$('.pro-carousel-image >#gallery-image > .slick-list > .slick-track > .slick-slide').css('height', slickH + 'px');
	
	
	$('body').on('click', '.pdp__attribute--group.variations', function(){
		setTimeout(function(){
			disableAddCartButton();
		}, 150);
	});
	
	function disableAddCartButton() {
		$('.add-to-wishlist-button .disable_wishlist_float').remove();
		$('.add-to-wishlist-button').removeClass('disabled');
		
		
		var variationGroup = $('.pdp__attribute--group.variations .variations__attribute').length;
		var numVariationSelected = $('.pdp__attribute--group.variations').find('li.variable-item.selected').length;
		if (numVariationSelected < variationGroup)
		{
			$('.single_add_to_cart_button').addClass('woocommerce-variation-add-to-cart-disabled disabled wc-variation-is-unavailable');
			$('.woocommerce-variation-add-to-cart').addClass('woocommerce-variation-add-to-cart-disabled disabled wc-variation-is-unavailable');
			
			// Disable favorite button
			var overflowWishlist = '<div class="disable_wishlist_float">&nbsp;</div>';
			$('.add-to-wishlist-button').append(overflowWishlist);
			$('.add-to-wishlist-button').addClass('disabled');
		}
	}
	disableAddCartButton();
	
	$('body').on('click', '.ywapo_input_container_labels', function(){
		$('.variations__attribute__value:eq(0)').find('input, select').each(function(){
			$(this).trigger('change');
			return false;
		})
	})
});