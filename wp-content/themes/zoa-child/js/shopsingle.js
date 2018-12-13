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
	
	$('#previewBox').hide();
	$('.woocommerce form .form-row .required').focusout(function(e) {
		if($(this).hasClass('inputError')) {
			$('#previewBox').hide();
		} else {
			$('#previewBox').show();
		}
	});
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
});