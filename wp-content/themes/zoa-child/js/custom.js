jQuery(document).ready(function($){	
	console.info("$jQuery = " + $.fn.jquery);
	$(window).load(function () { //全ての読み込みが完了したら実行
		$('body').removeClass('fade-out');
	});
	$('.header-box .quadmenu-navbar-nav').addClass('theme-primary-menu');
	//$('.banner-ad-promo > .item').hide();
	//$('.banner-ad-promo > .item:first-child').show();
	$(".banner-ad-promo > .item:gt(0)").hide();
	setInterval(function() { 
		$('.banner-ad-promo > .item:first')
			.hide()
			.next()
			.show()
			.end()
			.appendTo('.banner-ad-promo');
	},  6000);
	//remove empty p tag
	$( 'p:not(.keep-p)' ).each( function() {
		var $this = $( this );
		if ( $this.html().replace( /\s|&nbsp;/g, '' ).length === 0 ) {
			$this.remove();
		}
	});
	//rearrange WWOOF filter
	$('.woof_redraw_zone .woof_container > .woof_container_inner > .woof_submit_search_form_container').insertAfter('.woof_redraw_zone > .woof_container.woof_container_size');
	//call link only mobile
	var ua = navigator.userAgent;
    if(ua.indexOf('iPhone') > 0 || ua.indexOf('Android') > 0){
        $('.tel-link').each(function(){
            var str = $(this).text();
            $(this).html($('<a>').attr('href', 'tel:' + str.replace(/-/g, '')).append(str + '</a>'));
        });
    }
	$('.checkbox_label > input[type=checkbox]').change(function() {
		if($(this).is(":checked")) {
			$(this).parent().addClass('checked');
		} else {
			$(this).parent().removeClass('checked');
		}
		
	});
	
	function checkRadioCustom(){
		$('.radio-label').each(function () {
			$(this).find('input[type=radio]').each(function() {
				if($(this).is(':checked')) {
					$(this).trigger('click');
					$(this).trigger('change');
					var optionName = $(this).attr('name');
					$('input[name="'+ optionName +'"]').closest('.radio-label').removeClass('checked');
					$(this).parent().addClass('checked');
				} 
			});
		});
	}
	
	$('.radio-label').find('input[type=radio]').change(function() {
		if($(this).is(':checked')) {
			var optionName = $(this).attr('name');
			$('input[name="'+ optionName +'"]').closest('.radio-label').removeClass('checked');
			$(this).parent().addClass('checked');
		}
	});
	checkRadioCustom();
	
	if($('.banner-ad-promo').length){
		/***********/
		$('.banner-ad-link').on('click',function(e){
			e.preventDefault();
			var inst = $('[data-remodal-id=portfolio_modal]').remodal(); 
			var wraper = $(this).closest('.grid-item');
			var pID = $(this).attr('data-id');

			$('body').LoadingOverlay('show');
			$.ajax({
				type: "post",
				url: gl_ajax_url,
				data: {action: 'get_banner_post', id: pID},
				
			}).success(function(response){
				$('.remodal_wraper').html(response);
				 inst.open();
				
				 $('body').LoadingOverlay('hide');
				 
			});
		});
		 
		/***********/
		$adBar=$('.banner-ad-promo')
		var adNav = $adBar.outerHeight();
		var adNavPos = $adBar.offset().top;		
		$(window).on('load scroll', function() {
			var scrollPos= $(this).scrollTop();
			if ( scrollPos > adNavPos ) {
				//$('.banner-ad-promo').hide();
			}
			else{
				//$('.banner-ad-promo').show();
			}
		});		
	} 
	if ($('.sticky_header').length)
	{
		var $win = $(window),
		$nav = $('.header-box'),
		navHeight = $nav.outerHeight(),
		navPos = $nav.offset().top,
		fixedClass = 'is-scroll';
		
		
		
		$win.on('load scroll', function() {
			var value = $(this).scrollTop();
			if ( value > navPos ) {
				$nav.addClass(fixedClass);
				if ($win.width() > 991) {
					$('.header-scroll-logo').show();					
				}
			} else {
				$nav.removeClass(fixedClass);				
				if ($win.width() > 991) {
					$('.header-scroll-logo').hide();				
				}
			}
		});
	}
	//mega menu
	//disable category parent menu
	if ($('.quadmenu-item-object-mega').length) {
		var $win = $(window);
		$('.quadmenu-row > .quadmenu-item-has-children > div > ul').addClass('close');
		$win.on("load",function() {
			
			if ($win.width() > 991) {
				$('.quadmenu-row > .quadmenu-item-has-children > div > ul > li.no_link > a').click(function (e) {
					e.preventDefault();
					return false; // Do something else in here if required
				});
			} else {
				$('.quadmenu-row > .quadmenu-item-has-children > div > ul > li.no_link.has_link_sm > a').click(function (e) {
					e.preventDefault();
					return true; // Do something else in here if required
				});
			}
			if ($win.width() < 768) {
				$('h2.order--checkout__summary__heading.icon--plus').click(function (e) {
					e.preventDefault();
					if ($(this).next().hasClass('toggle--active')) {
						$(this).removeClass('toggle--active');
						$(this).parent().removeClass('toggle--active');
						$(this).next().removeClass('toggle--active');
					} else {
						$(this).addClass('toggle--active');
						$(this).parent().addClass('toggle--active');
						$(this).next().addClass('toggle--active');
					}
					//$(this).next('.toggle--active').toggleClass('toggle--active');
				});
			}
			if ($win.width() > 991) {
				} else {
					$('.quadmenu-row > .quadmenu-item-has-children > div > ul > li.no_link.has_link_sm').click(function (e) {
				e.preventDefault();
				$(this).toggleClass('icon-back');
				$(this).parent().toggleClass('open-child close').toggleClass('open-current');
				$(this).parent().parent().parent().toggleClass('open-current');
				if ($(this).parent().parent().parent().hasClass('open-current')) {
					var ocH = $(this).parent().parent().height();
					var pbH = $('.banner-ad-promo').innerHeight();
					$(this).parent().css('height', (ocH + pbH) + 'px');
				} else {
					$(this).parent().css('height', 'auto');
				}
				
				$(this).parents('.quadmenu-dropdown-menu').siblings('.quadmenu-dropdown-toggle').toggleClass('close_prvlevel');
				$(this).parent().parent().parent().siblings('li').toggleClass('close_otherchild');
				
			});
			//remove class and change css
			$('#menu-toggle-btn').on('click', function() {
				$('.theme-primary-menu').find('.open-current').removeClass('open-current').addClass('close').css('height', 'auto');
				$('.theme-primary-menu').find('.open-child').removeClass('open-child');
			});
				}
			
			
		});
		
	}
	
	//$('.sub-menu.mega-menu-row > li.no_link > a').addClass('flyout-col__heading heading heading--small');
	
	$('#menu-toggle-btn').on('click', function (e) {
		e.preventDefault();
		var headH = $(".menu-layout-custom .header-container").height();
		var wH = $(window).height() + 110;
		var pbH = $(".banner-ad-promo").innerHeight();
		pbH = $(".sticky_header.is-scroll").length ? 0 : pbH;
		pbH = 0;
		
		$(this).toggleClass('toggle--active');
		$('.menu-layout-custom').toggleClass('toggle--active');
		$('.nav-container').toggleClass('nav--active');
		if ($(this).hasClass('toggle--active')) {
			$('.nav-container').css('height', (wH - headH - pbH) + 'px');
		} else {
			$('.nav-container').css('height', '0');
		}
		$('html').toggleClass('cancel-scroll');
	});
	//add class for select
	$('.form-row select:not(.justselect)').each(function(){
		$(this).addClass('input-select justselect').wrapAll('<div class="selectric-wrapper selectric-input-select selectric-responsive"></div>');
	});
	
	//add class for variable product select
	$('.pdp__attribute--group .pdp__attribute.variations__attribute').each(function(){
		var select = $(this).find('select');
		if (!$(select).hasClass('hide')) {
			$(select).addClass('input-select justselect').wrapAll('<div class="selectric-wrapper selectric-input-select selectric-responsive"></div>');
		}
	});
	
	$('select.orderby').addClass('input-select justselect').wrapAll('<div class="selectric-wrapper selectric-input-select selectric-responsive"></div>');
	//change form field style
	$('.order--checkout--row p.form-row').each(function() {
		//$(this).find('label, .woocommerce-input-wrapper').wrapAll('<div class="field-wrapper"></div>');
		$(this).find('label, .woocommerce-Input').wrapAll('<div class="field-wrapper"></div>');
	});
	//remove cta class from product archive
	$('.related products > ul.products > li.product').each(function(){
		if ($(this).find('.cta-wish')) {
			$('.cta-wish').removeClass('cta');
		}
	});
	
	
	
	//copy shipping address to billing address in checkout page
	$("#copy_to_billing").on("click", function(){
    if (this.checked) {
      $("[name='billing_first_name']").val($("[name='shipping_first_name']").val());
      $("[name='billing_last_name']").val($("[name='shipping_last_name']").val());
      $("[name='billing_address_1']").val($("[name='shipping_address_1']").val());
      $("[name='billing_address_2']").val($("[name='shipping_address_2']").val());
      $("[name='billing_city']").val($("[name='shipping_city']").val());
      $("[name='billing_state']").val($("[name='shipping_state']").val());
      $("[name='billing_zip']").val($("[name='shipping_zip']").val());
      $("[name='billing_country']").val($("[name='shipping_country']").val());
    }
  });
	
	$('body').on('click', '#step-2 .js-next', function(){
		$("[name='shipping_first_name']").val($("[name='billing_first_name']").val());
	      $("[name='shipping_last_name']").val($("[name='billing_last_name']").val());
	      $("[name='shipping_address_1']").val($("[name='billing_address_1']").val());
	      $("[name='shipping_address_2']").val($("[name='billing_address_2']").val());
	      $("[name='shipping_city']").val($("[name='billing_city']").val());
	      $("[name='shipping_state']").val($("[name='billing_state']").val());
	      $("[name='shipping_zip']").val($("[name='billing_zip']").val());
	      $("[name='shipping_country']").val($("[name='billing_country']").val());
	});
	
	/***
	*
	* Product filter clear button *
	*/	
	var productFilterClear=function(){
		$('.woof_container').each(function( index ) {
			var checked=$(this).find('.woof_checkbox_term:checked').length;
			if(checked > 0){
				$(this).find('h4.toggle__name').after('<a href="javascript:void(0);" class="filterClear" >Clear</a>');
			}
		  
		});	
		$('a.filterClear').on('click',function(e){
			e.stopPropagation();
			var chkbox=$(this).parents('div.woof_container_inner').find('.woof_checkbox_term').attr('checked',false);
			var tax=$(this).parents('div.woof_container_inner').find('.woof_checkbox_term').data('tax');
			$(this).parents('div.woof_container_inner').find('.woof_checkbox_term').parent('div').removeClass('checked');	
			woof_current_values[tax]='';
			woof_submit_link(woof_get_submit_link());				
		});	
	};
	
	var customFilterSidebar=function(){
		/*var toggleWrap = $('.shop-sidebar .woof_container_inner');
		var toggleCon = $('.woof_block_html_items');
		var toggleTitle = $('.shop-sidebar .woof_container_inner > h4');
		$(toggleWrap).addClass('toggle-wrap');
		$(toggleCon).addClass('toggle__content');
		$(toggleTitle).addClass('toggle__name').wrap('<div class="toggle__link flex-justify-between"></div>');*/
		$(".woof_sid_widget .woof_redraw_zone > .woof_container > .toggle-wrap > .toggle__link:not(.toggle__link--no-indicator)").on("click", function(e){		
			e.preventDefault();
			if(!$(this).hasClass("toggle--active")) {			
			$(this).addClass("toggle--active");
			$(this).next(".toggle__content").addClass("toggle--active");
			$(this).parent().addClass("toggle--active");
			if ($(window).width() < 992) {
				$('#shop-overlay').addClass("set--active");
			}
		} else if($(this).hasClass("toggle--active")) {
			$(this).removeClass("toggle--active");
			$(this).next(".toggle__content").removeClass("toggle--active");
			$(this).parent().removeClass("toggle--active");
			if ($(window).width() < 992) {
				$('#shop-overlay').addClass("set--active");
			}
		}
		});
	};
	//refiment toggle
	$(document).on("click", function(e){
		if ($(window).width() < 992) {
			if($(e.target).closest('#refinementsBarTrigger').length && !$('#refinementsBarTrigger').hasClass("toggle--active")) {
				$('#refinementsBarTrigger').addClass("toggle--active");
				$('#shop-overlay').addClass("set--active");
				$('#refinementsBarTrigger').next(".WOOF_Widget").addClass("toggle--active");
				$('#refinementsBarTrigger').parent().addClass("toggle--active");
				$('body').css('overflow', 'hidden');
			} else if(!$(e.target).closest('.WOOF_Widget').length || $(e.target).closest('#closeRefinement').length && $('#refinementsBarTrigger').hasClass("toggle--active") || !$(e.target).closest('.woof_sid_widget').length) {
				$('#refinementsBarTrigger').removeClass("toggle--active");
				$('#shop-overlay').removeClass("set--active");
				$('#refinementsBarTrigger').next(".WOOF_Widget").removeClass("toggle--active");
				$('#refinementsBarTrigger').parent().removeClass("toggle--active");
				$('body').css('overflow', 'auto');
			}
		}
	});
	
	customFilterSidebar(); 
	jQuery(document).on("woof_ajax_done", woof_ajax_done_handler);
	function woof_ajax_done_handler(g) {
		g.preventDefault();
		customFilterSidebar();
		productFilterClear();
	}
	
	
	
	
	

$('[data-toggle]').on('click', function(e) {
	e.preventDefault();
	$('[data-toggle-target=' + $(this).data('toggle') + ']').toggleClass('toggle--active');
	$(this).toggleClass('toggle--active');
	$(this).parent().toggleClass('toggle--active');
});
	
	
	$('.fade-anitop, .home ul.products > li, .fade-ani').each(function() {
		$(this).addClass('showing');
	});
	$(window).scroll( function(){
		var $win = $(window);
		
		//fade-in
		$('.fade-anitop, .home ul.products > li').each(function(){
            var elemPos = $(this).offset().top;
            var scroll = $(window).scrollTop();
            var windowHeight = $(window).height();
			if ($win.width() > 991) {
            if (scroll > elemPos - windowHeight + 200){
                $(this).addClass('showing');
            }else{
				$(this).removeClass('showing');
			}
			} else {
				$(this).addClass('showing');
			}
        });
		$('.fade-ani').each( function(i){
			var bottom_of_object = $(this).offset().top + $(this).outerHeight();
			var bottom_of_window = $(window).scrollTop() + $(window).height();
			if ($win.width() > 991) {
			 if( bottom_of_window > bottom_of_object ){
				$(this).addClass('showing');
			}
			else{
				$(this).removeClass('showing');
			}
			} else {
				$(this).addClass('showing');
			}
		});
		
		/* Check the location of each desired element */
        /*$('.home ul.products > li').each( function(i){
            
            var bottom_of_object = $(this).offset().top + $(this).outerHeight();
            var bottom_of_window = $(window).scrollTop() + $(window).height();
            
            if( bottom_of_window > bottom_of_object ){
                
                $(this).addClass('showing');
                    
            }else{
				$(this).removeClass('showing');
			}
            
        }); */
	});
	//option add on
	$(".product-addon-options label:has(input.addon-checkbox)").addClass("check-label");
	/* Checkbox  */
  var checkBoxRow = $('.product-addon-options p.form-row');
  var addClassCheckBox = function($input) {
    if ($input.prop('checked')) {
      $input.parent().addClass('checked');
    } else {
      $input.parent().removeClass('checked');
    }
  };
  checkBoxRow.on('change', 'input', function() {
    addClassCheckBox($(this));
  });
	
	$(window).on('load resize', function() {
		//var productH = $('ul.products li.first').height();
		//$("#home-product-column ul.products li.product_selection").css('height', productH + 'px');
	});
    
	
	$("footer #reg_email").attr("placeholder", "EMAIL ADDRESS");
	$("footer #reg_password").attr("placeholder", "PASSWORD");
	
	if ($('form').find('.name-field-wrapper, .kana-field-wrapper')) {
		var NameInput = $(this).find('.name-field-wrapper').find('input');
		var KanaInput = $(this).find('.kana-field-wrapper').find('input');
		$(NameInput).addClass('name-field');
		$(KanaInput).addClass('kana-field');
	}
  $.fn.autoKana('#billing_first_name', '#billing_first_name_kana', {katakana : true});
  $.fn.autoKana('#billing_last_name', '#billing_last_name_kana', {katakana : true});
  
  $.fn.autoKana('#shipping_first_name', '#shipping_first_name_kana', {katakana : true});
  $.fn.autoKana('#shipping_last_name', '#shipping_last_name_kana', {katakana : true});
  
  $.fn.autoKana('#account_first_name', '#account_first_name_kana', {katakana : true});
  $.fn.autoKana('#account_last_name', '#account_last_name_kana', {katakana : true});
	$('.mw_wp_form').each(function() {
		var $NameCon =$('input#name');
		var $KanaCon =$('input#name-kana');
		$.fn.autoKana($NameCon, $KanaCon, {katakana : true});
	});
	
	
	//auto zip input
	
	$('body').on('change', '#billing_postcode, #shipping_postcode', function(){
		var zip1 = $.trim($(this).val());
	    var zipcode = zip1;
	    var elementChange = $(this);
	    
	    // Remove error message about postcode
	    $('.postcode_fail').remove();

	    $.ajax({
	        type: "post",
	        url: gl_site_url + "dataAddress/api.php",
	        data: JSON.stringify(zipcode),
	        crossDomain: false,
	        dataType : "jsonp",
	        scriptCharset: 'utf-8'
	    }).done(function(data){
	    	var address = [
	    		//{postcode : '#deliver_postcode', state : '#deliver_state', city: '#deliver_city', address1: '#deliver_addr1'},
	    		{postcode : '#billing_postcode', state : '#billing_state', city: '#billing_city', address1: '#billing_address_1'},
	    		{postcode : '#shipping_postcode', state : '#shipping_state', city: '#shipping_city', address1: '#shipping_address_1'},
	    	]
	    	
	        if(false && (data[0] == "" || gl_stateAllowed.indexOf(data[0]) == -1)){
	        	if (data[0] != "" && gl_stateAllowed.indexOf(data[0]) == -1)
	        	{
	        		var alertElement = '<span style="display: block" class="woocommerce-error postcode_fail clear">'+ gl_alertStateNotAllowed +'</span>';
	        		elementChange.parent().append(alertElement);
	        	}
	        	$.each(address, function(index, addressItem){
	        		$(addressItem['postcode']).val('');
	        		$(addressItem['state']).val('');
	        		$(addressItem['city']).val('');
	        		$(addressItem['address1']).val('');
	        	});
	        	
	        } else {
	    		$.each(address, function(index, addressItem){
	        		if ($(addressItem['postcode']).length && ('#'+elementChange.attr('id') == addressItem['postcode']))
	        		{
	        			$(addressItem['state'] + ' option').each(function(){
	                		if($(this).text() == data[0])
	                		{
	                			$(addressItem['state']).val($(this).attr('value'));
	                			$(addressItem['state']).change();
	                		}
	                	});
	                	
	                    $(addressItem['city']).val(data[1] + data[2]);
//	                    var address1 = $(addressItem['address1']).val();
//	                    address1 = address1.replace(data[2], '');
//	                    $(addressItem['address1']).val(data[2] + address1);
	        		}
	        	});
	        }
	    }).fail(function(XMLHttpRequest, textStatus, errorThrown){
	    });
	});
	
	//share tools
	$(".sharing-tools").on("click", function() {
		if ($(this).hasClass("-open")) {
			$(this).removeClass("-open");
		} else {
			$(this).addClass("-open");
		}
	});
	//accordion
	$("body").on("click", '.accordion > li > .acc-toggle', function() {
    if ($(this).hasClass("-open")) {
      $(this).removeClass("-open");
      $(this)
        .siblings(".accordion > li > .acc-inner")
        .slideUp(500);
      $(".accordion > li > .acc-toggle > .acc-icon")
        .removeClass("-close")
        .addClass("-open");
    } else {
      $(".accordion > li > .acc-toggle > .acc-icon")
        .removeClass("-close")
        .addClass("-open");
      $(this)
        .find(".acc-icon")
        .removeClass("-open")
        .addClass("-close");
      $(".accordion > li > .acc-toggle").removeClass("-open");
      $(this).addClass("-open");
      $(".accordion > li > .acc-inner").slideUp(500);
      $(this)
        .siblings(".accordion > li > .acc-inner")
        .slideDown(500);
    }
  });
	
	$('body').on('click', '#book_confirmed', function(e){
		e.preventDefault();
        var postData = $('form#confirmed_booking_form').serialize();
        postData += '&' + $.param({
            action: 'bookingform_schedule_confirmed'
        });
        $.post(gl_ajax_url, postData, function(data, status, xhr){
        	var response = jQuery.parseJSON(data);
        	if (response.success)
    		{
        		location.href = gl_site_url + "reservation-thanks";
    		}
        });
	});
	$('body').on('click', '#book_back', function(){
		location.href = gl_site_url + "reservation";
	});	
	
	$('body').on('click', '#shipping_info_link', function(){
		var inst = $('[data-remodal-id=shipping_info_modal]').remodal();
		inst.open();
	});	
	
	
	
	function createCheckboxHook()
	{
		jQuery(".woof_checkbox_term").on("ifChecked", function(e) {
			keep_expand_filter_shop();
	    });
		
		jQuery(".woof_checkbox_term").on("ifUnchecked", function(e) {
			keep_expand_filter_shop();
	    });

		jQuery(".filterClear").on("click", function(e) {
			keep_expand_filter_shop();
		});
		
	}
	
	function keep_expand_filter_shop(is_onload)
	{
		if (!is_onload)
		{
			var toggle_interval = setInterval(function(){
				if (!$('.woof_redraw_zone').find('.woof_container_inner.toggle--active').length)
				{
					var expanding = [];
					$('.woof_redraw_zone').find('.woof_container').each(function(index, woof_container){
						if ($(this).find('.icheckbox_minimal-aero.checked').length)
						{
							expanding.push(index);
						}
					});
					
					clearInterval(toggle_interval);
					toggle_interval = null;
					setTimeout(function(){
						$.each(expanding, function(index, group_index){
							$('.woof_container:eq('+ group_index +')').find('.toggle__link').click();
						});
						createCheckboxHook();
					}, 100);
				}
			}, 10);
		}
		else{
			if (!$('.woof_redraw_zone').find('.woof_container_inner.toggle--active').length)
			{
				var expanding = [];
				$('.woof_redraw_zone').find('.woof_container').each(function(index, woof_container){
					if ($(this).find('.icheckbox_minimal-aero.checked').length)
					{
						expanding.push(index);
					}
				});
				
				$.each(expanding, function(index, group_index){
					$('.woof_container:eq('+ group_index +')').find('.toggle__link').click();
				});
			}
			createCheckboxHook();
		}
	}
	setTimeout(function(){
		keep_expand_filter_shop(true);
	}, 1000);
	
    
	if ($('.contact-form input[name="ctf-name"]').length)
	{
		$('.contact-form input[name="ctf-name"]').val(gl_user_name);
	}
	$('body').on('click', '.shop-content #gallery-image img', function(e){
		var inst = $('[data-remodal-id=product_image_modal]').remodal();
		var carousel_image = $('.shop-content .pro-carousel-image');
		var carousel_thumb = $('.shop-content .pro-carousel-thumb');
		$('#product_image_modal .single-product-gallery').removeClass('width_50');
		$('#product_image_modal .single-product-gallery').addClass('width_100');
		$('#product_image_modal .single-product-gallery').html(carousel_image);
		$('#product_image_modal .single-product-gallery').append(carousel_thumb);
		$('#product_image_modal .single-product-gallery').css('opacity', 0);
		inst.open();
		
		$('#product_image_modal').height($('#product_image_modal .single-product-gallery').height());
		
		$('.slick-dots').remove();
		$('.slick-next').remove();
		$('.slick-prev').remove();
		
		if ($('#gallery-image.slick-slider').length)
		{
			$('#gallery-image').slick('setPosition');
		}
		
		$('#product_image_modal .single-product-gallery').css('opacity', 1);
	});
	
	$(document).on('closing', '#product_image_modal', function (e) {
		var carousel_image = $('#product_image_modal .pro-carousel-image');
		var carousel_thumb = $('#product_image_modal .pro-carousel-thumb');
		$('#product_image_modal .single-product-gallery').css('opacity', 0);
		$('#product_image_modal .single-product-gallery').removeClass('width_100');
		$('#product_image_modal .single-product-gallery').addClass('width_50');
		
		$('.slick-dots').remove();
		$('.slick-next').remove();
		$('.slick-prev').remove();
		
		if ($('#gallery-image.slick-slider').length)
		{
			$('#gallery-image').slick('setPosition');
		}
		
		$('.shop-content .single-product-gallery').html(carousel_image);
		$('.shop-content .single-product-gallery').append(carousel_thumb);
		
		if ($('#gallery-image.slick-slider').length)
		{
			$('#gallery-image').slick('setPosition');
		}
	}); 

	
	$('body').on('click', '.cancel-appointment-btn', function(e){
		e.preventDefault();
		
		if (!confirm(gl_cancel_appointment_alert)) return '';
		
		appointment_id = $(this).data('id');
		var wraper = $(this).closest('.appointment_item');
		var btn_el = (this);
		jQuery('body').LoadingOverlay('show');
		$.ajax({
	        type: "post",
	        url: gl_ajax_url,
	        data: {appointment_id: appointment_id, action: 'cancel_appointment'},
	        dataType : "json"
	    }).done(function(response){
	    	if (response.success)
	    	{
	    		wraper.removeClass('status-active');
	    		wraper.addClass('status-cancelled');
	    		wraper.find('.booking-status .value').text(response.status);
	    		btn_el.remove();
	    	}
	    	jQuery('body').LoadingOverlay('hide');
	    });
	});
	
	$(document).on('click', '.cancel_order_btn', function(event){
		if (confirm(gl_cancel_order_alert_text))
		{
			jQuery('body').LoadingOverlay('show');
			event.preventDefault();
			var order_id = $(this).data('id');
			var cancel_btn = $(this);
			$.ajax({
				type: "post",
				url: gl_ajax_url,
				data: {order_id: order_id, action: 'customer_cancel_order'},
				dataType : "json"
			}).done(function(response){
				if (response.success)
				{
					cancel_btn.fadeOut(function(){cancel_btn.remove()});
					cancel_btn.closest('.box.order').find('.order-status .value').text(response.status);
				}
				jQuery('body').LoadingOverlay('hide');
			});
		}
	});
	
	$(document).on('click', 'input[name="shipping_delivery_option"]', function(event){
		jQuery('body').LoadingOverlay('show');
		var shipping_delivery_option = $(this).val();
		$.ajax({
			type: "post",
			url: gl_ajax_url,
			data: {shipping_delivery_option: shipping_delivery_option, action: 'select_shipping_delivery_option'},
			dataType : "json"
		}).done(function(response){
			if (response.success)
			{
				// trigger udpate shipping button
				if($('button[name="calc_shipping"]').length)
					$('button[name="calc_shipping"]').trigger('click');
				else if ($('#shipping_postcode').length)
					$('#shipping_postcode').trigger('change');
			}
			setTimeout(function(){
				jQuery('body').LoadingOverlay('hide');
			}, 1000);
		});
	});

	$(document).click(function(event) {
		setTimeout(function(){
			if ($(".quick-view-open").length && !$(event.target).closest("ul.products").length && !$(event.target).closest(".shop-quick-view-container").length) {
				$('#shop-quick-view .quick-view-close-btn').trigger('click');
			}
		}, 50);
	});
	
	function moveSizeChart()
	{
		var tab_length = $('.wc-tabs-wrapper .wc-tabs li').length;
		if (tab_length > 1 || $('.wc-tabs-wrapper .wc-tabs li.custom_tab_tab').length == 0)
		{
			$('.wc-tabs-wrapper').show();
		}
		//$('#size_chart_content').html($('#tab-custom_tab'));
	}
	
	moveSizeChart();
	productFilterClear();
});

