/**
* -----------------------------------------------------------
*
* Castor Studio Framework
* A Lightweight and easy-to-use WordPress Options Framework
*
* The Framework based on some CodeStar Framework. The fields configs desgin also based on CodeStar Framework.
*
* Copyright 2018 CastorStudio <support@castorstudio.com>
*
* -----------------------------------------------------------
*/
;
(function($, window, document, undefined) {
	'use strict';
	$.CSFRAMEWORK = $.CSFRAMEWORK || {};

	// caching selector
	var $csf_body = $('body');
	// caching variables
	var csf_is_rtl = $csf_body.hasClass('rtl');



	$.CSFRAMEWORK_HELPER = {
		FUNCTIONS: {
			string_to_slug: function(str){
				str = str.replace(/^\s+|\s+$/g, ''); // trim
				str = str.toLowerCase();
			
				// remove accents, swap ñ for n, etc
				var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
				var to   = "aaaaeeeeiiiioooouuuunc------";
			
				for (var i=0, l=from.length ; i<l ; i++){
					str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
				}
			
				str = str.replace('.', '-') // replace a dot by a dash 
					.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
					.replace(/\s+/g, '-') // collapse whitespace and replace by a dash
					.replace(/-+/g, '-'); // collapse dashes
			
				return str;
			},
			make_title: function(str) {
				return str.replace(/-/g, " ").replace(/\b[a-z]/g, function () {
					return arguments[0].toUpperCase();
				});
			},
			ucwords: function(str){
				// http://kevin.vanzonneveld.net
				// +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
				// +   improved by: Waldo Malqui Silva
				// +   bugfixed by: Onno Marsman
				// +   improved by: Robin
				// +      input by: James (http://www.james-bell.co.uk/)
				// +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
				// *     example 1: ucwords('kevin van  zonneveld');
				// *     returns 1: 'Kevin Van  Zonneveld'
				// *     example 2: ucwords('HELLO WORLD');
				// *     returns 2: 'HELLO WORLD'
				return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
					return $1.toUpperCase();
				});
			},
		},
        COLOR_PICKER: {
            parse: function ($value) {
                var val = $value.replace(/\s+/g, ''),
                    alpha = ( val.indexOf('rgba') !== -1 ) ? parseFloat(val.replace(/^.*,(.+)\)/, '$1') * 100) : 100,
                    rgba = ( alpha < 100 ) ? true : false;
                return {value: val, alpha: alpha, rgba: rgba};
            }
        },
        CSS_BUILDER: {
            validate: function (val) {
                var s = val;
                if ( $.isNumeric(val) ) {
                    return val + 'px';
                } else if ( val.indexOf('px') > -1 || val.indexOf('%') > -1 || val.indexOf('em') > -1 ) {
                    var checkPx = s.replace("px", "");
                    var checkPct = s.replace("%", "");
                    var checkEm = s.replace("em", "");
                    if ( $.isNumeric(checkPx) || $.isNumeric(checkPct) || $.isNumeric(checkEm) ) {
                        return val;
                    } else {
                        return "0px";
                    }
                } else {
                    return '0px';
                }

            },

            update: {
                border: function ($el) {
                    $el.find('.csf-css-builder-border').css({
                        "border-top-left-radius": $el.find('.csf-border-radius-top-left :input').val(),
                        "border-top-right-radius": $el.find('.csf-border-radius-top-right :input').val(),
                        "border-bottom-right-radius": $el.find('.csf-border-radius-bottom-left :input').val(),
                        "border-bottom-left-radius": $el.find('.csf-border-radius-bottom-right :input').val(),
                        'border-style': $el.find('.csf-element-border-style select').val(),
                        'border-color': $el.find('.csf-element-border-color input.csf-field-color-picker').val(),
                    });

                    $el.find('.csf-css-builder-margin').css({
                        'background-color': $el.find('.csf-element-background-color input.csf-field-color-picker').val(),
                        'color': $el.find('.csf-element-text-color input.csf-field-color-picker').val(),
                    });

                },
                all: function ($el, $type, $main) {
                    var $newVal = $el.val(),
                        $val = $.CSFRAMEWORK_HELPER.CSS_BUILDER.validate($newVal),
                        $is_all = $('.csf-' + $type + '-checkall').hasClass('checked');

                    if ( $is_all === true ) {
                        $main.find('.csf-element.csf-' + $type + ' :input').val($val);
                    } else {
                        $el.val($val);
                    }

                    $.CSFRAMEWORK_HELPER.CSS_BUILDER.update.border($main);

                },
            }
        },
        LIMITER: {
            counter: function (val, countBy) {
                if ( $.trim(val) == '' ) {
                    return 0;
                }

                return countBy ? val.match(/\S+/g).length : val.length;
            },
            subStr: function (val, start, len, subByWord) {
                if ( !subByWord ) {
                    return val.substr(start, len);
                }

                var lastIndexSpace = val.lastIndexOf(' ');
                return val.substr(start, lastIndexSpace);
            }
		},
	};




	// ======================================================
	// CSFRAMEWORK TAB NAVIGATION
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_TAB_NAVIGATION = function() {
		return this.each(function() {
			var $this = $(this),
			$nav = $this.find('.csf-nav'),
			$reset = $this.find('.csf-reset'),
			$expand = $this.find('.csf-expand-all');

			$nav.find('ul:first a').on('click', function(e) {
				e.preventDefault();
				var $el = $(this),
				$next = $el.next(),
				$target = $el.data('section');
				
				if ($next.is('ul')) {
					$next.slideToggle('fast');
					$el.closest('li').toggleClass('csf-tab-active');
				} else {
					// $('#csf-tab-' + $target).fadeIn().siblings().fadeOut();

					var $_tab_target = $('#csf-tab-' + $target);
					$_tab_target.siblings().fadeOut(350).promise().done(function(){
						$_tab_target.fadeIn(350);
					});

					$nav.find('a').removeClass('csf-section-active');
					$el.addClass('csf-section-active');
					$reset.val($target);
					window.location.hash = $target;
				}
			});
			$expand.on('click', function(e) {
				e.preventDefault();
				$this.find('.csf-body').toggleClass('csf-show-all');
				$(this).find('.fa').toggleClass('fa-eye-slash').toggleClass('fa-eye');
			});

			var hash = location.hash.slice(1);
			if (hash){
				$('a[data-section="'+hash+'"]',$nav).trigger('click');
			} else {
				$('a',$nav).first().trigger('click');
			}
		});
	};
	// ======================================================
	

	// ======================================================
	// CSFRAMEWORK TAB NAVIGATION - SCROLL TABS
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_NAV_SCROLL_TABS = function(){
		var _navScrollTo = function(nav,item){
			var $nav 				= $('.csf-nav-wrapper > ul',nav),
				$nav_parent 		= nav.parents('.csf-body'),
				nav_parent_width 	= $nav_parent.outerWidth(),
				nav_width 			= $nav.outerWidth(true);
			
			if (item == 'nav_prev' || item == 'nav_next'){
				var nav_pos	= nav.data('position'),
					new_pos = nav_parent_width - (nav_parent_width * 0.1);

				if (item == 'nav_prev'){
					var new_pos = Math.abs(nav_pos) - new_pos;
				} else if (item == 'nav_next'){
					var new_pos = Math.abs(nav_pos) + new_pos;
				}
			} else {
				var $item 			= item.parents('li'),
					item_width 		= $item.outerWidth(true),
					item_pos		= $item.position().left;
				
				var mid_pos 		= nav_parent_width / 2,
					mid_item_width 	= item_width / 2,
					new_pos 		= item_pos - mid_pos + mid_item_width;
			}
			
			var limit_right 	= nav_width - nav_parent_width;
			
			if (new_pos <= 0){
				new_pos = 0;
			}
			if (new_pos >= limit_right){
				new_pos = nav_width - nav_parent_width;
			}


			var update_pos = function(position){
				nav.data('position',position);
				nav.get(0).style.setProperty('--nav-pos',position+'px');
			};
			update_pos('-'+new_pos);
		};

		return this.each(function() {
			var $this = $(this),
				_item = $('a.csf-section-active',$this),
				_item = (_item.length) ? _item : $('a',$this).eq(0);
			
			if (_item.length){
				_navScrollTo($this,_item);
			}
			
			$this.on('click','a',function(e){
				e.preventDefault();
				var _item = $(this);
				_navScrollTo($this,_item);
			});

			$this.on('click','.csf-nav-button',function(e){
				var type 	= $(this).data('type');

				if (type == 'prev'){
					_navScrollTo($this,'nav_prev');
				} else if (type == 'next'){
					_navScrollTo($this,'nav_next');
				}
			});
		});
	}
	// ======================================================

	
	// ======================================================
	// CSFRAMEWORK DEPENDENCY
	// ------------------------------------------------------
	$.CSFRAMEWORK.DEPENDENCY = function(el, param) {
		// Access to jQuery and DOM versions of element
		var base = this;
		base.$el = $(el);
		base.el = el;
		base.init = function() {
			base.ruleset = $.deps.createRuleset();
			// required for shortcode attrs
			var cfg = {
				show: function(el) {
					el.removeClass('hidden');
					// el.fadeIn(300,function(){
					// 	el.removeClass('hidden');
					// });
				},
				hide: function(el) {
					el.addClass('hidden');
					// el.fadeOut(300,function(){
					// 	el.addClass('hidden');
					// });
				},
				log: false,
				checkTargets: false
			};
			if (param !== undefined) {
				base.depSub();
			} else {
				base.depRoot();
			}
			$.deps.enable(base.$el, base.ruleset, cfg);
		};
		base.depRoot = function() {
			base.$el.each(function() {
				$(this).find('[data-controller]').each(function() {
					var $this = $(this),
					_controller = $this.data('controller').split('|'),
					_condition = $this.data('condition').split('|'),
					_value = $this.data('value').toString().split('|'),
					_rules = base.ruleset;
					$.each(_controller, function(index, element) {
						var value = _value[index] || '',
						condition = _condition[index] || _condition[0];
						_rules = _rules.createRule('[data-depend-id="' + element + '"]', condition, value);
						_rules.include($this);
					});
				});
			});
		};
		base.depSub = function() {
			base.$el.each(function() {
				$(this).find('[data-sub-controller]').each(function() {
					var $this = $(this),
					_controller = $this.data('sub-controller').split('|'),
					_condition = $this.data('sub-condition').split('|'),
					_value = $this.data('sub-value').toString().split('|'),
					_rules = base.ruleset;
					$.each(_controller, function(index, element) {
						var value = _value[index] || '',
						condition = _condition[index] || _condition[0];
						_rules = _rules.createRule('[data-sub-depend-id="' + element + '"]', condition, value);
						_rules.include($this);
					});
				});
			});
		};
		base.init();
	};
	$.fn.CSFRAMEWORK_DEPENDENCY = function(param) {
		return this.each(function() {
			new $.CSFRAMEWORK.DEPENDENCY(this, param);
		});
	};
	// ======================================================
	// ======================================================
	// CSFRAMEWORK CHOSEN
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_CHOSEN = function() {
		return this.each(function() {
			// Added to render only visible fields, not in template groups
			var is_in_group_template = $(this).parents('.csf-group-template');
			if (!is_in_group_template.length){
				$(this).chosen({
					allow_single_deselect: true,
					disable_search_threshold: 15,
					// width: parseFloat($(this).actual('width') + 25) + 'px' // commented to use default input width
					width: 'calc'
				});
			}
		});
	};
	// ======================================================
	// ======================================================
	// CSFRAMEWORK IMAGE SELECTOR
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_IMAGE_SELECTOR = function() {
		return this.each(function() {
			$(this).find('label').on('click', function() {
				$(this).siblings().find('input').prop('checked', false);
			});
		});
	};
	// ======================================================
	// ======================================================
	// CSFRAMEWORK SORTER
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_SORTER = function() {
		return this.each(function() {
			var $this = $(this),
			$enabled = $this.find('.csf-enabled'),
			$disabled = $this.find('.csf-disabled');
			$enabled.sortable({
				connectWith: $disabled,
				placeholder: 'ui-sortable-placeholder',
				update: function(event, ui) {
					var $el = ui.item.find('input');
					if (ui.item.parent().hasClass('csf-enabled')) {
						$el.attr('name', $el.attr('name').replace('disabled', 'enabled'));
					} else {
						$el.attr('name', $el.attr('name').replace('enabled', 'disabled'));
					}
				}
			});
			// avoid conflict
			$disabled.sortable({
				connectWith: $enabled,
				placeholder: 'ui-sortable-placeholder'
			});
		});
	};
	// ======================================================
	// ======================================================
	// CSFRAMEWORK MEDIA UPLOADER / UPLOAD
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_UPLOADER = function() {
		return this.each(function() {
			var $this = $(this),
			$add = $this.find('.csf-add'),
			$input = $this.find('input'),
			wp_media_frame;
			$add.on('click', function(e) {
				e.preventDefault();
				// Check if the `wp.media.gallery` API exists.
				if (typeof wp === 'undefined' || !wp.media || !wp.media.gallery) {
					return;
				}
				// If the media frame already exists, reopen it.
				if (wp_media_frame) {
					wp_media_frame.open();
					return;
				}
				// Create the media frame.
				wp_media_frame = wp.media({
					// Set the title of the modal.
					title: $add.data('frame-title'),
					// Tell the modal to show only images.
					library: {
						type: $add.data('upload-type')
					},
					// Customize the submit button.
					button: {
						// Set the text of the button.
						text: $add.data('insert-title'),
					}
				});
				// When an image is selected, run a callback.
				wp_media_frame.on('select', function() {
					// Grab the selected attachment.
					var attachment = wp_media_frame.state().get('selection').first();
					$input.val(attachment.attributes.url).trigger('change');
				});
				// Finally, open the modal.
				wp_media_frame.open();
			});
		});
	};
	// ======================================================
	// ======================================================
	// CSFRAMEWORK IMAGE UPLOADER
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_IMAGE_UPLOADER = function() {
		return this.each(function() {
			var $this    = $(this),
				$add     = $this.find('.csf-add'),
				$preview = $this.find('.csf-image-preview'),
				$remove  = $this.find('.csf-remove'),
				$input   = $this.find('input'),
				$img     = $this.find('img'),
				wp_media_frame;
			
			$add.on('click', function(e) {
				e.preventDefault();
				// Check if the `wp.media.gallery` API exists.
				if (typeof wp === 'undefined' || !wp.media || !wp.media.gallery) {
					return;
				}
				// If the media frame already exists, reopen it.
				if (wp_media_frame) {
					wp_media_frame.open();
					return;
				}
				// Create the media frame.
				wp_media_frame = wp.media({
					// Set the title of the modal.
					title: $add.data('frame-title'),
					// Tell the modal to show only images.
					library: {
						type: 'image'
					},
					// Customize the submit button.
					button: {
						// Set the text of the button.
						text: $add.data('insert-title'),
					}
				});
				// When an image is selected, run a callback.
				wp_media_frame.on('select', function() {
					var attachment = wp_media_frame.state().get('selection').first().attributes;
					var preview_size = $preview.data('preview-size');
					if (preview_size == 'custom'){
						var thumbnail = attachment.url;	
					} else {
						if (typeof preview_size === 'undefined') {
							preview_size = 'thumbnail';
						}
						var thumbnail = (typeof attachment['sizes'][preview_size] !== 'undefined') ? attachment['sizes'][preview_size]['url'] : attachment.url;
					}
					$preview.removeClass('hidden');
					$remove.removeClass('hidden');
					$img.attr('src', thumbnail);
					$input.val(attachment.id).trigger('change');
				});
				// Finally, open the modal.
				wp_media_frame.open();
			});
			// Remove image
			$remove.on('click', function(e) {
				e.preventDefault();
				$input.val('').trigger('change');
				$preview.addClass('hidden');
				$remove.addClass('hidden');
			});
		});
	};
	// ======================================================
	// ======================================================
	// CSFRAMEWORK IMAGE GALLERY
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_IMAGE_GALLERY = function() {
		return this.each(function() {
			var $this 	= $(this),
				$edit 	= $this.find('.csf-edit'),
				$remove = $this.find('.csf-remove'),
				$list 	= $this.find('ul'),
				$input 	= $this.find('input'),
				$img 	= $this.find('img'),
				wp_media_frame,
				wp_media_click;

			$this.on('click', '.csf-add, .csf-edit', function(e) {
				var $el = $(this),
				what = ($el.hasClass('csf-edit')) ? 'edit' : 'add',
				state = (what === 'edit') ? 'gallery-edit' : 'gallery-library';
				e.preventDefault();
				// Check if the `wp.media.gallery` API exists.
				if (typeof wp === 'undefined' || !wp.media || !wp.media.gallery) {
					return;
				}
				// If the media frame already exists, reopen it.
				//
				// Comentado para forzar que la galeria se actualice cada vez que se abre
				//
				// if (wp_media_frame) {
				// 	wp_media_frame.open();
				// 	wp_media_frame.setState(state);
				// 	return;
				// }
				
				// Create the media frame.
				wp_media_frame = wp.media({
					title: 'Select or Upload Media Of Your Chosen Persuasion',
					button: {
						text: 'Use this media'
					},
					library: {
						type: 'image'
					},
					frame: 'post',
					state: 'gallery',
					multiple: true
				});
				// Open the media frame.
				wp_media_frame.on('open', function() {
					var $input 	= $this.find('input');
					var ids 	= $input.val();
					
					if (ids) {
						var get_array = ids.split(',');
						var library = wp_media_frame.state('gallery-edit').get('library');
						wp_media_frame.setState(state);
						get_array.forEach(function(id) {
							var attachment = wp.media.attachment(id);
							library.add(attachment ? [attachment] : []);
						});
					}
				});
				// When an image is selected, run a callback.
				wp_media_frame.on('update', function() {
					var inner = '';
					var ids = [];
					var images = wp_media_frame.state().get('library');
					images.each(function(attachment) {
						var attributes = attachment.attributes;
						var thumbnail = (typeof attributes.sizes.thumbnail !== 'undefined') ? attributes.sizes.thumbnail.url : attributes.url;
						inner += '<li data-image-id="'+attributes.id+'"><img src="' + thumbnail + '"></li>';
						ids.push(attributes.id);
					});
					$input.val(ids).trigger('change');
					$list.html('').append(inner);
					$remove.removeClass('hidden');
					$edit.removeClass('hidden');
				});
				// Finally, open the modal.
				wp_media_frame.open();
				wp_media_click = what;
			});
			// Remove image
			$remove.on('click', function(e) {
				e.preventDefault();
				$list.html('');
				$input.val('').trigger('change');
				$remove.addClass('hidden');
				$edit.addClass('hidden');
			});
			
			
			
			// Sortable Funcionality
			// -------------------------------------------------------
			$list.sortable({
				helper: 'original',
				cursor: 'move',
				placeholder: 'widget-placeholder',
				stop: function(event, ui) {
					var parent 	= ui.item.parents('.csf-fieldset'),
					input 	= parent.children('input'),
					list 	= parent.children('ul'),
					ids 	= [];
					
					$('li',list).each(function(){
						ids.push($(this).data("imageId"));
					});
					
					// order = order.toString();
					input.val(ids).trigger('change');
				}
			});
			$list.disableSelection();
		});
	};
	// ======================================================
	// ======================================================
	// CSFRAMEWORK TYPOGRAPHY
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_TYPOGRAPHY = function() {
		return this.each(function() {
			var typography 				= $(this),
				family_select 			= typography.find('.csf-typo-family'),
				variants_select 		= typography.find('.csf-typo-variant'),
				typography_type 		= typography.find('.csf-typo-font'),
				typography_size			= typography.find('.csf-typo-size'),
				typography_height 		= typography.find('.csf-typo-height'),
				typography_spacing 		= typography.find('.csf-typo-spacing'),
				typography_align		= typography.find('.csf-typo-align'),
				typography_transform 	= typography.find('.csf-typo-transform'),
				typography_color 		= typography.find('.csf-typo-color');
			
			family_select.on('change', function() {
				var _this = $(this),
				_type = _this.find(':selected').data('type') || 'custom',
				_variants = _this.find(':selected').data('variants');
				if (variants_select.length) {
					variants_select.find('option').remove();
					$.each(_variants.split('|'), function(key, text) {
						variants_select.append('<option value="' + text + '">' + text + '</option>');
					});
					variants_select.find('option[value="regular"]').attr('selected', 'selected').trigger('chosen:updated');
				}
				typography_type.val(_type);
			});
			
			// Typography Advanced Live Preview
			// ---------------------------------------------
			var preview 		= $(".csf-typo-preview",typography),
				previewToggle	= $(".csf-typo-preview-toggle",preview),
				previewId		= $(preview).data("previewId"),
				currentFamily 	= $(this).find('.csf-typo-family').val();
			
			var livePreviewRefresh = function(){
				var preview_weight 		= variants_select.val(),
					preview_size		= typography_size.val(),
					preview_height		= typography_height.val(),
					preview_spacing		= typography_spacing.val(),
					preview_align 		= typography_align.val(),
					preview_transform	= typography_transform.val(),
					preview_color 		= typography_color.val();
				
				var style = {
					"--csf-typo-preview-weight":preview_weight,
					"--csf-typo-preview-size":preview_size+"px",
					"--csf-typo-preview-height":preview_height+"px",
					"--csf-typo-preview-spacing":preview_spacing+"px",
					"--csf-typo-preview-align":preview_align,
					"--csf-typo-preview-transform":preview_transform,
					"--csf-typo-preview-color":preview_color
				};
				setPreviewStyle("#"+$(preview).attr("id"),style);
			}
			
			// Update Preview
			// ------------------------------
			if (preview.length){
				$(preview).css("font-family", currentFamily);
				$('head').append('<link href="http://fonts.googleapis.com/css?family=' + currentFamily +'" class="'+previewId+'" rel="stylesheet" type="text/css" />').load();
				livePreviewRefresh();
			}
			
			family_select.on('change',function(){
				$('head').find("."+previewId).remove();
				var font = $(this).val();
				$(preview).css("font-family", font);
				$('head').append('<link href="http://fonts.googleapis.com/css?family=' + font +'" class="'+previewId+'" rel="stylesheet" type="text/css" />').load();
				livePreviewRefresh();
			});
			
			variants_select.on('change',function(){ livePreviewRefresh(); });
			typography_type.on('change',function(){ livePreviewRefresh(); });
			typography_size.on('change',function(){ livePreviewRefresh(); });
			typography_height.on('change',function(){ livePreviewRefresh(); });
			typography_align.on('change',function(){ livePreviewRefresh(); });
			typography_color.on('change',function(){ livePreviewRefresh(); });
			typography_spacing.on('change',function(){ livePreviewRefresh(); });
			typography_transform.on('change',function(){ livePreviewRefresh(); });
			
			// Toggle Preview BG Style
			// ------------------------------
			$(previewToggle).on("click",function(){
				$(preview).toggleClass("csf-typo-preview-toggle_dark");
			});
			
			
			
			//-----------------------------------------------------------------
			// HELPER FUNCTIONS
			//-----------------------------------------------------------------
			function setPreviewStyle( element, propertyObject ){
				var elem = document.querySelector(element).style;
				for (var property in propertyObject){
					elem.setProperty(property, propertyObject[property]);
				}
			}
			
			function removeStyle( element, propertyObject){
				var elem = document.querySelector(element).style;
				for (var property in propertyObject){
					elem.removeProperty(propertyObject[property]);
				}
			}
		});
	};
	// ======================================================
	
	// ======================================================
	// CSFRAMEWORK GROUP
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_GROUP = function () {
		return this.each( function () {
			var $this = $( this ),
				$elem = $( this );
	
			if ( $this.find( '> .csf-fieldset' ).length > 0 ) {
				$elem = $this.find( '> .csf-fieldset' );
			}
	
			var field_groups = $elem.find( '> .csf-groups' ),
				accordion_group = $elem.find( ' > .csf-accordion' ),
				clone_group = $elem.find( '.csf-group:first' ).clone();
	
			// var $heading = field_groups.find( '> .csf-group > .csf-group-title' );
			var $heading = field_groups.find( '> .csf-group > .csf-group-title-wrapper' );
	
			if ( accordion_group.length ) {
				accordion_group.each( function () {
					$( this ).accordion( {
						// header: '> .csf-group > .csf-group-title',
						header: '> .csf-group > .csf-group-title-wrapper',
						collapsible: true,
						active: false,
						animate: 250,
						heightStyle: 'content',
						icons: {
							'header': 'dashicons dashicons-arrow-right',
							'activeHeader': 'dashicons dashicons-arrow-down'
						},
						beforeActivate: function (event, ui) {
							$( ui.newPanel ).CSFRAMEWORK_DEPENDENCY( 'sub' );
						}
					} );
				} );
			}
	
			field_groups.sortable({
				handle: $heading,
				helper: 'original',
				placeholder: 'widget-placeholder',
				start: function (event, ui) {
					var inside = ui.item.children( '.csf-group-content' );
					if ( inside.css( 'display' ) === 'block' ) {
						inside.hide();
						field_groups.sortable( 'refreshPositions' );
					}
					ui.placeholder.height(ui.item.height());
				},
				stop: function (event, ui) {
					ui.item.children( '.csf-group-title' ).triggerHandler( 'focusout' );
					accordion_group.accordion( {
						active: false
					} );
				}
			});
	
			$elem.find( '> .csf-add-group' ).on( 'click', function (e) {
				e.preventDefault();
				var $is_child = $( this ).attr( 'data-child' );

				var $ex_c = $( this ).attr( 'data-count' );
				var $parent_count = $(this).attr('data-parent-count');
				var $child_count = $( this ).attr( 'data-child-count' );
				if ( $ex_c === undefined ) {
					// $ex_c = $( this ).parent().find( '> .csf-groups > .csf-group' ).length;
					// console.log("> EX:",$ex_c);

					$ex_c = parseInt( $ex_c, 10 ) - 1; // Fix para que el index se incremente solo en 1

					// console.log(">> EX:",$ex_c);

					var $parent = $(this).siblings('.csf-group');
					var $element = $('.csf-fieldset',$parent).children().eq(0);
					var myRegexp = /\[(\d+)\](?!.*\[\d)/;
					var match = myRegexp.exec($element.attr('name'));
					var newIndex = (match) ? parseInt(match[1]) : 0;

					// console.log(match);

					$ex_c = newIndex;
					
					if ($parent_count === undefined){
						// console.log("<<<<< NO EXISTE PARENT COUNT >>>>>>>");
						var $maybe_first = ($(this).siblings('.csf-groups').children('.csf-group').length) ? false : true;

						if ($is_child) {
							// console.log(">>>>> ES CHILD");
							$parent_count = $ex_c;
						} else {
							if ($ex_c === 0 && $maybe_first){
								// console.log(">>>>>>>> ES EL PRIMERO!!!",$ex_c);
								$parent_count = 0;
								$ex_c = -1;
							} else {
								$parent_count = $ex_c + 1;
							}
						}

						// Update Parent Count
						$(this).attr('data-parent-count',$parent_count);
						// console.log(">>> Parent Count Inicial:",$parent_count);
					}

					if ( !$ex_c && $ex_c != 0) {
						// console.log("!!!! NO EXISTE EX_C:",$ex_c);
						$ex_c = -1;
					}
				}


				// console.log(">>>>1. EX:",$ex_c);
				$ex_c = parseInt( $ex_c, 10 ) + 1;
				// console.log(">>>>2. EX:",$ex_c);

	
				
				var $db_id = $( this ).attr( 'data-group-id' );
				if ( $db_id === undefined ) {
					$db_id = '';
				}
				$db_id = $db_id.replace( '[_nonce]', '' );


				if ($is_child){
					if ( $child_count === undefined ) {
						$child_count = 0;
					} else {
						$child_count = parseInt( $child_count, 10 ) + 1;
					}
				}
	
				// console.log("== EX:",$ex_c);
				// console.log("== PARENT COUNT:",$parent_count);

				clone_group.find( 'input, select, textarea' ).each( function () {
					if ( $is_child === 'yes' ) {
						var $sp = this.name.split( '[_nonce]' );
						var $H = '';

						$.each($sp,function($ec, $c){
							if ( $ec !== 0 ) {

								// var parent_name = $sp[0];
								var myRegexp = /\[(\d+)\]/;
								// var match = myRegexp.exec(parent_name);
								// var parent_newIndex = parseInt(match[1]) + 1;
								// console.log("SP:",$sp,"EC:",$ec,"C:",$c,"Parent New Index:",parent_newIndex);
								// console.log(">> H:",$H);
								$c = $c.replace(myRegexp, function () {
									return '[' + $child_count + ']';
								} );
								// console.log(">> NOMBRE:",$c);
								// console.log(">>> H:",$H);
								$c = '[_nonce]' + $c;
							} else {
								var myRegexp = /\[(\d+)\]/;
								$c = $c.replace(myRegexp, function () {
									return '[' + $parent_count + ']';
								} );
							}
							$H += $c;
						});
	
						this.name = $H;

						// console.log("<IS CHILD> ","INDEX: ",$child_count," Nombre:",$H);
	
					} else {
						// this.name = this.name.replace( /\[(\d+)\]/, function (string, id) {
						this.name = this.name.replace(/\[(\d+)\](?!.*\[\d)/, function (string, id) {
							return '[' + $ex_c + ']';
							// return '[' + $parent_count + ']';
						} );
						// console.log("<PARENT> ","INDEX: ",$parent_count);
					}
	
				} );
	
				var cloned = clone_group.clone().removeClass( 'hidden' );
				field_groups.append( cloned );
	
				if ( accordion_group.length ) {
					field_groups.accordion( 'refresh' );
					field_groups.accordion( {
						active: cloned.index()
					} );
				}
	
				field_groups.find( 'input, select, textarea' ).each( function () {
					this.name = this.name.replace( '[_nonce]', '' );
				} );
	
				cloned.CSFRAMEWORK_DEPENDENCY( 'sub' );
				cloned.CSFRAMEWORK_RELOAD_PLUGINS();
				$( this ).attr( 'data-count', $ex_c );
				$( this ).attr( 'data-child-count', $child_count );
				cloned.find( '.csf-field-group' ).CSFRAMEWORK_GROUP();
				cloned.find( '.csf-field-group .csf-add-group' ).attr( 'data-child', 'yes' );
				// console.log("=============================================");
			} );
	
			field_groups.on( 'click', '.csf-remove-group', function (e) {
				e.preventDefault();
				$( this ).closest( '.csf-group' ).remove();
			} );
	
	
		} )
	};
	// ======================================================
	
	
	// ======================================================
	// CSFRAMEWORK RESET CONFIRM
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_CONFIRM = function() {
		return this.each(function() {
			$(this).on('click', function(e) {
				// if (!confirm('Are you sure?')) {
				// 	e.preventDefault();
				// }
				var self = $(this);
				if (!self.data('submit')){
					e.preventDefault();
					$.confirm({
						title: 'Restore options?',
						content: 'Are you sure that you want to continue?',
						theme: 'supervan', // 'material', 'bootstrap'
						buttons: {
							yes: {
								text: 'Continue',
								action: function(){
									self.data('submit',true);
									self.trigger('click');
								}
							},
							no: {
								text: 'Cancel',
								action: function(){
									self.data('submit',false);
								}
							},
						}
					});
				}
			});
		});
	};
	// ======================================================
	// ======================================================
	// CSFRAMEWORK SAVE OPTIONS
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_SAVE = function() {
		return this.each(function() {
			var $this 	= $(this),
				$text 	= $this.data('save'),
				$value 	= $this.val(),
				$ajax 	= $('#csf-save-ajax');

			$(document).on('keydown', function(event) {
				if (event.ctrlKey || event.metaKey) {
					if (String.fromCharCode(event.which).toLowerCase() === 's') {
						event.preventDefault();
						$this.trigger('click');
					}
				}
			});
			$this.on('click', function(e) {
				if ($ajax.length) {

					var saving_dialog = $.dialog({
						lazyOpen: true,
						title: null,
						content: '<div class="csf-spinner"></div> Saving options...',
						theme: 'supervan',
						closeIcon: false,
					});

					saving_dialog.open();

					if (typeof tinyMCE === 'object') {
						tinyMCE.triggerSave();
					}
					// $this.prop('disabled', true).attr('value', $text);
					var serializedOptions = $('#csframework_form').serialize();

					$.post('options.php', serializedOptions).error(function() {
						alert('Error, Please try again.');
					}).success(function() {
						// $this.prop('disabled', false).attr('value', $value);
						// $ajax.hide().fadeIn().delay(450).fadeOut();
					}).complete(function(){
						saving_dialog.close();
					});
					e.preventDefault();
				} else {
					$this.addClass('disabled').attr('value', $text);
				}
			});
		});
	};
	// ======================================================
	// ======================================================
	// CSFRAMEWORK UI DIALOG OVERLAY HELPER
	// ------------------------------------------------------
	if (typeof $.widget !== 'undefined' && typeof $.ui !== 'undefined' && typeof $.ui.dialog !== 'undefined') {
		$.widget('ui.dialog', $.ui.dialog, {
			_createOverlay: function() {
				this._super();
				if (!this.options.modal) {
					return;
				}
				this._on(this.overlay, {
					click: 'close'
				});
			}
		});
	}
	// ======================================================
	// CSFRAMEWORK ICONS MANAGER
	// ------------------------------------------------------
	$.CSFRAMEWORK.ICONS_MANAGER = function() {
		var base = this,
		onload = true,
		$parent;
		base.init = function() {
			$csf_body.on('click', '.csf-icon-add', function(e) {
				e.preventDefault();
				var $this = $(this),
				$dialog = $('#csf-icon-dialog'),
				$load = $dialog.find('.csf-dialog-load'),
				$select = $dialog.find('.csf-dialog-select'),
				$insert = $dialog.find('.csf-dialog-insert'),
				$search = $dialog.find('.csf-icon-search');
				// set parent
				$parent = $this.closest('.csf-icon-select');
				// open dialog
				$dialog.dialog({
					width: 850,
					height: 700,
					modal: true,
					resizable: false,
					closeOnEscape: true,
					position: {
						my: 'center',
						at: 'center',
						of: window
					},
					open: function() {
						// fix scrolling
						$csf_body.addClass('csf-icon-scrolling');
						// fix button for VC
						$('.ui-dialog-titlebar-close').addClass('ui-button');
						// set viewpoint
						$(window).on('resize', function() {
							var height = $(window).height(),
							load_height = Math.floor(height - 237),
							set_height = Math.floor(height - 125);
							$dialog.dialog('option', 'height', set_height).parent().css('max-height', set_height);
							$dialog.css('overflow', 'auto');
							$load.css('height', load_height);
						}).resize();
					},
					close: function() {
						$csf_body.removeClass('csf-icon-scrolling');
					}
				});
				// load icons
				if (onload) {
					$.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {
							action: 'csf-get-icons'
						},
						success: function(content) {
							$load.html(content);
							onload = false;
							$load.on('click', 'a', function(e) {
								e.preventDefault();
								var icon = $(this).data('icon');
								$parent.find('i').removeAttr('class').addClass(icon);
								$parent.find('input').val(icon).trigger('change');
								$parent.find('.csf-icon-preview').removeClass('hidden');
								$parent.find('.csf-icon-remove').removeClass('hidden');
								$dialog.dialog('close');
							});
							$search.keyup(function() {
								var value = $(this).val(),
								$icons = $load.find('a');
								$icons.each(function() {
									var $ico = $(this);
									if ($ico.data('icon').search(new RegExp(value, 'i')) < 0) {
										$ico.hide();
									} else {
										$ico.show();
									}
								});
							});
							$load.find('.csf-icon-tooltip').cstooltip({
								html: true,
								placement: 'top',
								container: 'body'
							});
							$load.accordion({
								collapsible: true,
								icons: {
									header: "dashicons dashicons-plus",
									activeHeader: "dashicons dashicons-minus"
								},
								heightStyle: "content"
							});
						}
					});
				}
			});
			$csf_body.on('click', '.csf-icon-remove', function(e) {
				e.preventDefault();
				var $this = $(this),
				$parent = $this.closest('.csf-icon-select');
				$parent.find('.csf-icon-preview').addClass('hidden');
				$parent.find('input').val('').trigger('change');
				$this.addClass('hidden');
			});
		};
		// run initializer
		base.init();
	};
	// ======================================================


	// ======================================================
	// CSFRAMEWORK IMAGE GALLERY CUSTOM
	// ------------------------------------------------------
	$.CSFRAMEWORK.IMAGE_GALLERY_CUSTOM = function() {
		var base = this,
			onload = true,
			$parent;

		base.init = function() {
			$csf_body.on('click', '.csf-image-add', function(e) {
				e.preventDefault();
				var $this 	= $(this),
					$dialog = $('#csf-image-dialog'),
					$load 	= $dialog.find('.csf-dialog-load'),
					$select = $dialog.find('.csf-dialog-select'),
					$insert = $dialog.find('.csf-dialog-insert'),
					$search = $dialog.find('.csf-image-search');
				
				var $images_path = $this.data('imagesPath');

					// set parent
					$parent = $this.closest('.csf-image-select');

				// open dialog
				$dialog.dialog({
					width: 850,
					height: 700,
					modal: true,
					resizable: false,
					closeOnEscape: true,
					position: {
						my: 'center',
						at: 'center',
						of: window
					},
					open: function() {
						// fix scrolling
						$csf_body.addClass('csf-image-scrolling');
						// fix button for VC
						$('.ui-dialog-titlebar-close').addClass('ui-button');
						// set viewpoint
						$(window).on('resize', function() {
							var height 		= $(window).height(),
								load_height = Math.floor(height - 237),
								set_height 	= Math.floor(height - 125);

							$dialog.dialog('option', 'height', set_height).parent().css('max-height', set_height);
							$dialog.css('overflow', 'auto');
							$load.css('height', load_height);
						}).resize();
					},
					close: function() {
						$csf_body.removeClass('csf-image-scrolling');
					}
				});
				// load images
				if (onload) {
					$.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {
							action: 'csf-get-images',
							path:	$images_path,
						},
						success: function(content) {
							$load.html(content);
							onload = false;
							$load.on('click', 'a', function(e) {
								e.preventDefault();
								var image 			= $(this).data('image'),
									preview_uri 	= $(this).data('imageUri');

								$parent.find('img').attr('src',preview_uri);
								$parent.find('input').val(image).trigger('change');
								// $parent.find('.csf-image-add').addClass('hidden');
								$parent.find('.csf-image-preview').removeClass('hidden');
								$parent.find('.csf-image-remove').removeClass('hidden');
								$dialog.dialog('close');
							});
							$search.keyup(function() {
								var value = $(this).val(),
								$images = $load.find('a');
								$images.each(function() {
									var $ico = $(this);
									if ($ico.data('image').search(new RegExp(value, 'i')) < 0) {
										$ico.hide();
									} else {
										$ico.show();
									}
								});
							});
							$load.find('.csf-image-tooltip').cstooltip({
								html: true,
								placement: 'top',
								container: 'body'
							});
							// $load.accordion({
							// 	collapsible: true,
							// 	images: {
							// 		header: "cli cli-plus",
							// 		activeHeader: "cli cli-minus"
							// 	},
							// 	heightStyle: "content"
							// });
						}
					});
				}
			});
			$csf_body.on('click', '.csf-image-remove', function(e) {
				e.preventDefault();
				var $this 	= $(this),
					$parent = $this.closest('.csf-image-select');

				$parent.find('.csf-image-add').removeClass('hidden');
				$parent.find('.csf-image-preview').addClass('hidden');
				$parent.find('input').val('').trigger('change');
				$this.addClass('hidden');
			});
		};
		// run initializer
		base.init();
	};
	// ======================================================


	// ======================================================
	// CSFRAMEWORK SHORTCODE MANAGER
	// ------------------------------------------------------
	$.CSFRAMEWORK.SHORTCODE_MANAGER = function() {
		var base = this,
		deploy_atts;
		base.init = function() {
			var $dialog = $('#csf-shortcode-dialog'),
			$insert = $dialog.find('.csf-dialog-insert'),
			$shortcodeload = $dialog.find('.csf-dialog-load'),
			$selector = $dialog.find('.csf-dialog-select'),
			shortcode_target = false,
			shortcode_name,
			shortcode_view,
			shortcode_clone,
			$shortcode_button,
			editor_id;
			$csf_body.on('click', '.csf-shortcode', function(e) {
				e.preventDefault();
				// init chosen
				$selector.CSFRAMEWORK_CHOSEN();
				$shortcode_button = $(this);
				shortcode_target = $shortcode_button.hasClass('csf-shortcode-textarea');
				editor_id = $shortcode_button.data('editor-id');
				$dialog.dialog({
					width: 850,
					height: 700,
					modal: true,
					resizable: false,
					closeOnEscape: true,
					position: {
						my: 'center',
						at: 'center',
						of: window
					},
					open: function() {
						// fix scrolling
						$csf_body.addClass('csf-shortcode-scrolling');
						// fix button for VC
						$('.ui-dialog-titlebar-close').addClass('ui-button');
						// set viewpoint
						$(window).on('resize', function() {
							var height = $(window).height(),
							load_height = Math.floor(height - 281),
							set_height = Math.floor(height - 125);
							$dialog.dialog('option', 'height', set_height).parent().css('max-height', set_height);
							$dialog.css('overflow', 'auto');
							$shortcodeload.css('height', load_height);
						}).resize();
					},
					close: function() {
						shortcode_target = false;
						$csf_body.removeClass('csf-shortcode-scrolling');
					}
				});
			});
			$selector.on('change', function() {
				var $elem_this = $(this);
				shortcode_name = $elem_this.val();
				shortcode_view = $elem_this.find(':selected').data('view');
				// check val
				if (shortcode_name.length) {
					$.ajax({
						type: 'POST',
						url: ajaxurl,
						data: {
							action: 'csf-get-shortcode',
							shortcode: shortcode_name
						},
						success: function(content) {
							$shortcodeload.html(content);
							$insert.parent().removeClass('hidden');
							shortcode_clone = $('.csf-shortcode-clone', $dialog).clone();
							$shortcodeload.CSFRAMEWORK_DEPENDENCY();
							$shortcodeload.CSFRAMEWORK_DEPENDENCY('sub');
							$shortcodeload.CSFRAMEWORK_RELOAD_PLUGINS();
						}
					});
				} else {
					$insert.parent().addClass('hidden');
					$shortcodeload.html('');
				}
			});
			$insert.on('click', function(e) {
				e.preventDefault();
				var send_to_shortcode = '',
				ruleAttr = 'data-atts',
				cloneAttr = 'data-clone-atts',
				cloneID = 'data-clone-id';
				switch (shortcode_view) {
					case 'contents':
					$('[' + ruleAttr + ']', '.csf-dialog-load').each(function() {
						var _this = $(this),
						_atts = _this.data('atts');
						send_to_shortcode += '[' + _atts + ']';
						send_to_shortcode += _this.val();
						send_to_shortcode += '[/' + _atts + ']';
					});
					break;
					case 'clone':
					send_to_shortcode += '[' + shortcode_name; // begin: main-shortcode
					// main-shortcode attributes
					$('[' + ruleAttr + ']', '.csf-dialog-load .csf-element:not(.hidden)').each(function() {
						var _this_main = $(this),
						_this_main_atts = _this_main.data('atts');
						console.log(_this_main_atts);
						send_to_shortcode += base.validate_atts(_this_main_atts, _this_main); // validate empty atts
					});
					send_to_shortcode += ']'; // end: main-shortcode attributes
					// multiple-shortcode each
					$('[' + cloneID + ']', '.csf-dialog-load').each(function() {
						var _this_clone = $(this),
						_clone_id = _this_clone.data('clone-id');
						send_to_shortcode += '[' + _clone_id; // begin: multiple-shortcode
						// multiple-shortcode attributes
						$('[' + cloneAttr + ']', _this_clone.find('.csf-element').not('.hidden')).each(function() {
							var _this_multiple = $(this),
							_atts_multiple = _this_multiple.data('clone-atts');
							// is not attr content, add shortcode attribute else write content and close shortcode tag
							if (_atts_multiple !== 'content') {
								send_to_shortcode += base.validate_atts(_atts_multiple, _this_multiple); // validate empty atts
							} else if (_atts_multiple === 'content') {
								send_to_shortcode += ']';
								send_to_shortcode += _this_multiple.val();
								send_to_shortcode += '[/' + _clone_id + '';
							}
						});
						send_to_shortcode += ']'; // end: multiple-shortcode
					});
					send_to_shortcode += '[/' + shortcode_name + ']'; // end: main-shortcode
					break;
					case 'clone_duplicate':
					// multiple-shortcode each
					$('[' + cloneID + ']', '.csf-dialog-load').each(function() {
						var _this_clone = $(this),
						_clone_id = _this_clone.data('clone-id');
						send_to_shortcode += '[' + _clone_id; // begin: multiple-shortcode
						// multiple-shortcode attributes
						$('[' + cloneAttr + ']', _this_clone.find('.csf-element').not('.hidden')).each(function() {
							var _this_multiple = $(this),
							_atts_multiple = _this_multiple.data('clone-atts');
							// is not attr content, add shortcode attribute else write content and close shortcode tag
							if (_atts_multiple !== 'content') {
								send_to_shortcode += base.validate_atts(_atts_multiple, _this_multiple); // validate empty atts
							} else if (_atts_multiple === 'content') {
								send_to_shortcode += ']';
								send_to_shortcode += _this_multiple.val();
								send_to_shortcode += '[/' + _clone_id + '';
							}
						});
						send_to_shortcode += ']'; // end: multiple-shortcode
					});
					break;
					default:
					send_to_shortcode += '[' + shortcode_name;
					$('[' + ruleAttr + ']', '.csf-dialog-load .csf-element:not(.hidden)').each(function() {
						var _this = $(this),
						_atts = _this.data('atts');
						// is not attr content, add shortcode attribute else write content and close shortcode tag
						if (_atts !== 'content') {
							send_to_shortcode += base.validate_atts(_atts, _this); // validate empty atts
						} else if (_atts === 'content') {
							send_to_shortcode += ']';
							send_to_shortcode += _this.val();
							send_to_shortcode += '[/' + shortcode_name + '';
						}
					});
					send_to_shortcode += ']';
					break;
				}
				if (shortcode_target) {
					var $textarea = $shortcode_button.next();
					$textarea.val(base.insertAtChars($textarea, send_to_shortcode)).trigger('change');
				} else {
					base.send_to_editor(send_to_shortcode, editor_id);
				}
				deploy_atts = null;
				$dialog.dialog('close');
			});
			// cloner button
			var cloned = 0;
			$dialog.on('click', '#shortcode-clone-button', function(e) {
				e.preventDefault();
				// clone from cache
				var cloned_el = shortcode_clone.clone().hide();
				cloned_el.find('input:radio').attr('name', '_nonce_' + cloned);
				$('.csf-shortcode-clone:last').after(cloned_el);
				// add - remove effects
				cloned_el.slideDown(100);
				cloned_el.find('.csf-remove-clone').show().on('click', function(e) {
					cloned_el.slideUp(100, function() {
						cloned_el.remove();
					});
					e.preventDefault();
				});
				// reloadPlugins
				cloned_el.CSFRAMEWORK_DEPENDENCY('sub');
				cloned_el.CSFRAMEWORK_RELOAD_PLUGINS();
				cloned++;
			});
		};
		base.validate_atts = function(_atts, _this) {
			var el_value;
			if (_this.data('check') !== undefined && deploy_atts === _atts) {
				return '';
			}
			deploy_atts = _atts;
			if (_this.closest('.pseudo-field').hasClass('hidden') === true) {
				return '';
			}
			if (_this.hasClass('pseudo') === true) {
				return '';
			}
			if (_this.is(':checkbox') || _this.is(':radio')) {
				el_value = _this.is(':checked') ? _this.val() : '';
			} else {
				el_value = _this.val();
			}
			if (_this.data('check') !== undefined) {
				el_value = _this.closest('.csf-element').find('input:checked').map(function() {
					return $(this).val();
				}).get();
			}
			if (el_value !== null && el_value !== undefined && el_value !== '' && el_value.length !== 0) {
				return ' ' + _atts + '="' + el_value + '"';
			}
			return '';
		};
		base.insertAtChars = function(_this, currentValue) {
			var obj = (typeof _this[0].name !== 'undefined') ? _this[0] : _this;
			if (obj.value.length && typeof obj.selectionStart !== 'undefined') {
				obj.focus();
				return obj.value.substring(0, obj.selectionStart) + currentValue + obj.value.substring(obj.selectionEnd, obj.value.length);
			} else {
				obj.focus();
				return currentValue;
			}
		};
		base.send_to_editor = function(html, editor_id) {
			var tinymce_editor;
			if (typeof tinymce !== 'undefined') {
				tinymce_editor = tinymce.get(editor_id);
			}
			if (tinymce_editor && !tinymce_editor.isHidden()) {
				tinymce_editor.execCommand('mceInsertContent', false, html);
			} else {
				var $editor = $('#' + editor_id);
				$editor.val(base.insertAtChars($editor, html)).trigger('change');
			}
		};
		// run initializer
		base.init();
	};
	// ======================================================
	// ======================================================
	// CSFRAMEWORK COLORPICKER
	// ------------------------------------------------------
	if (typeof Color === 'function') {
		// adding alpha support for Automattic Color.js toString function.
		Color.fn.toString = function() {
			// check for alpha
			if (this._alpha < 1) {
				return this.toCSS('rgba', this._alpha).replace(/\s+/g, '');
			}
			var hex = parseInt(this._color, 10).toString(16);
			if (this.error) {
				return '';
			}
			// maybe left pad it
			if (hex.length < 6) {
				for (var i = 6 - hex.length - 1; i >= 0; i--) {
					hex = '0' + hex;
				}
			}
			return '#' + hex;
		};
	}
	$.CSFRAMEWORK.PARSE_COLOR_VALUE = function(val) {
		var value = val.replace(/\s+/g, ''),
		alpha = (value.indexOf('rgba') !== -1) ? parseFloat(value.replace(/^.*,(.+)\)/, '$1') * 100) : 100,
		rgba = (alpha < 100) ? true : false;
		return {
			value: value,
			alpha: alpha,
			rgba: rgba
		};
	};
	$.fn.CSFRAMEWORK_COLORPICKER = function() {
		return this.each(function() {
			var $this = $(this);
			
			// check for user custom color palettes
			var picker_palettes = $this.data('colorpalettes');
			picker_palettes = (picker_palettes) ? picker_palettes.toString().split(",") : false;
			
			// check for rgba enabled/disable
			if ($this.data('rgba') !== false) {
				// parse value
				var picker = $.CSFRAMEWORK.PARSE_COLOR_VALUE($this.val());
				// wpColorPicker core
				$this.wpColorPicker({
					palettes: picker_palettes,
					// wpColorPicker: clear
					clear: function() {
						$this.trigger('keyup');
					},
					// wpColorPicker: change
					change: function(event, ui) {
						var ui_color_value = ui.color.toString();
						// update checkerboard background color
						$this.closest('.wp-picker-container').find('.csf-alpha-slider-offset').css('background-color', ui_color_value);
						$this.val(ui_color_value).trigger('change');
					},
					// wpColorPicker: create
					create: function() {
						// set variables for alpha slider
						var a8cIris = $this.data('a8cIris'),
						$container = $this.closest('.wp-picker-container'),
						// appending alpha wrapper
						$alpha_wrap = $('<div class="csf-alpha-wrap">' + '<div class="csf-alpha-slider"></div>' + '<div class="csf-alpha-slider-offset"></div>' + '<div class="csf-alpha-text"></div>' + '</div>').appendTo($container.find('.wp-picker-holder')),
						$alpha_slider = $alpha_wrap.find('.csf-alpha-slider'),
						$alpha_text = $alpha_wrap.find('.csf-alpha-text'),
						$alpha_offset = $alpha_wrap.find('.csf-alpha-slider-offset');
						// alpha slider
						$alpha_slider.slider({
							// slider: slide
							slide: function(event, ui) {
								var slide_value = parseFloat(ui.value / 100);
								// update iris data alpha && wpColorPicker color option && alpha text
								a8cIris._color._alpha = slide_value;
								$this.wpColorPicker('color', a8cIris._color.toString());
								$alpha_text.text((slide_value < 1 ? slide_value : ''));
							},
							// slider: create
							create: function() {
								var slide_value = parseFloat(picker.alpha / 100),
								alpha_text_value = slide_value < 1 ? slide_value : '';
								// update alpha text && checkerboard background color
								$alpha_text.text(alpha_text_value);
								$alpha_offset.css('background-color', picker.value);
								// wpColorPicker clear for update iris data alpha && alpha text && slider color option
								$container.on('click', '.wp-picker-clear', function() {
									a8cIris._color._alpha = 1;
									$alpha_text.text('');
									$alpha_slider.slider('option', 'value', 100).trigger('slide');
								});
								// wpColorPicker default button for update iris data alpha && alpha text && slider color option
								$container.on('click', '.wp-picker-default', function() {
									var default_picker = $.CSFRAMEWORK.PARSE_COLOR_VALUE($this.data('default-color')),
									default_value = parseFloat(default_picker.alpha / 100),
									default_text = default_value < 1 ? default_value : '';
									a8cIris._color._alpha = default_value;
									$alpha_text.text(default_text);
									$alpha_slider.slider('option', 'value', default_picker.alpha).trigger('slide');
								});
								// show alpha wrapper on click color picker button
								$container.on('click', '.wp-color-result', function() {
									$alpha_wrap.toggle();
								});
								// hide alpha wrapper on click body
								$csf_body.on('click.wpcolorpicker', function() {
									$alpha_wrap.hide();
								});
							},
							// slider: options
							value: picker.alpha,
							step: 1,
							min: 1,
							max: 100
						});
					}
				});
			} else {
				// wpColorPicker default picker
				$this.wpColorPicker({
					palettes: picker_palettes,
					clear: function() {
						$this.trigger('keyup');
					},
					change: function(event, ui) {
						$this.val(ui.color.toString()).trigger('change');
					}
				});
			}
		});
	};
	// ======================================================
	
	// ======================================================
	// Slider field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_SLIDER = function() {
		return this.each( function() {
			var dis 		= $( this ),
			input1 		= $('input[name$="[slider1]"]',dis),
			input2		= $('input[name$="[slider2]"]',dis),
			slider 		= $('.csf-slider > div.csf-slider-wrapper', dis ),
			data 		= $('.csf-slider',dis).data( 'sliderOptions' ),
			step 		= data.step || 1,
			min 		= data.min || 0,
			max 		= data.max || 100,
			round 		= data.round || false,
			tooltip 	= data.tooltip || false,
			handles 	= data.handles || false,
			has_input 	= data.input || false;
			
			var parseInteger = function(value){
				return parseFloat(parseFloat(value).toFixed(2));
			}
			
			var connect 	= (handles) ? [ false , true, false] : [ true , false ];
			var val 		= (handles) ? [ parseInteger(data.slider1) , parseInteger(data.slider2) ] : [ parseInteger(data.slider1) ];
			var tooltips	= (handles) ? ((tooltip) ? [ true, true ] : [ false, false ]) : ((tooltip) ? [ true ] : [ false ]);
			
			var slider = slider.get(0);
			noUiSlider.create(slider, {
				start: val,
				connect: connect,
				tooltips: tooltips,
				step: step,
				range: {
					'min': [  parseInteger( min ) ],
					'max': [ parseInteger( max ) ]
				}
			});
			
			slider.noUiSlider.on('update', function ( values, handle ) {
				var value = (round) ? Math.round(values[handle]) : values[handle];
				(handle ? input2 : input1).val( value );
			});
			
			input1.on("change",function(){
				var val1 = input1.val(),
				val2 = input2.val();
				
				var val 		= (handles) ? [ parseInteger(val1) , parseInteger(val2) ] : [ parseInteger(val1) ];
				updateSliderVal(val);
			});
			input2.on("change",function(){
				var val1 = input1.val(),
				val2 = input2.val();
				
				var val 		= (handles) ? [ parseInteger(val1) , parseInteger(val2) ] : [ parseInteger(val1) ];
				updateSliderVal(val);
			});
			
			function updateSliderVal(value) {
				slider.noUiSlider.updateOptions({
					start: value
				});
			}
		} );
	};
	// ======================================================
	
	
	// ======================================================
	// Easing Editor field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_EASINGEDITOR = function() {
		return this.each(function(){
			var $p1, $p2, $handle, $easingselector, $input, $inputType, $preview, $size, ctx;
			var self 	= this;
			
			ctx 			= $(".csf-easing-editor__bezierCurve",self).get(0).getContext("2d");
			$easingselector = $('.easingSelector',self);
			$input 			= $('input[name$="[easingSelector]"]',self);
			$inputType 		= $('input.easingSelectorType',self);
			$preview 		= $('.csf-easing-editor__preview',self).get(0);
			$size 			= 200;
			$p1				= $(".p1",self);
			$p2				= $(".p2",self);
			
			
			$(document).ready(function(){
				// Toggle Button
				// ------------------------------------------------------
				var btn_toggle 	= $('a.button[name$="[toggleEditor]"]',self),
				btn_icon 	= $("<span />",{ class: "dashicons dashicons-visibility"});
				
				btn_toggle.prepend(btn_icon);
				btn_toggle.on('click',function(){
					$('.csf-easing-editor__graph-outer-wrapper',self).slideToggle({
						start: function(){
							// $easingselector.trigger('change');
							
							var type 	= $('option',$easingselector).last().prop('selected'),
							// value 	= (type) ? getHandles(true) : $easingselector.val();
							value 	= $input.val();
							
							console.log(value);
							console.log($input.val());
							
							// Update Handles Positions
							updateHandles(value);	
							// Render Graph
							renderWrap(ctx);
						}
					});
					$('span',this).toggleClass('dashicons-hidden','dashicons-visibility');
				});
				
				
				// Easing Curve Graph - Dragable handles
				// - Draggable handles
				// - Easing Select box change event to update the graph
				// ------------------------------------------------------
				$(".p1, .p2",self).draggable({ 
					containment: 'parent',
					start: function(){
						setCustomEasing();
					},
					drag: function(event, ui) {
						renderWrap(ctx);
						setDemoValue('drag');
					},
					stop: function(){
						renderWrap(ctx);
						setTransitionFn();
						setDemoValue('drag');
					}
				});
				
				$easingselector.on('change', function(){
					var $this 	= $(this),
					value 	= $this.val();
					
					// Update Handles Positions
					updateHandles(value);
					
					// Render Graph
					renderWrap(ctx);
					setDemoValue();
				});
				
				
				// First Render Easing Curve Graph
				// ------------------------------------------------------
				renderWrap(ctx);
				setTransitionFn();				
				setDemoValue();
			});
			
			
			// HELPER FUNCTIONS
			// --------------------------------------------------------------------
			function setStyle( element, propertyObject ){
				var elem = element.style;
				for (var property in propertyObject){
					elem.setProperty(property, propertyObject[property]);
				}
			}
			function updateHandles(values){
				var values 	= values.split(",");
				
				$p1.css("left", values[0] * $size);
				$p1.css("top", 	(1 - values[1]) * $size);
				$p2.css("left", values[2] * $size);
				$p2.css("top", 	(1 - values[3]) * $size);
			}
			
			function getHandles(string){
				var handles = [],
				p1 		= $p1.position(),
				p2 		= $p2.position();
				
				if($.browser.mozilla) {
					var p1x = adjustValue( (p1.left) / $size);
					var p1y = adjustValue( 1 - (p1.top) / $size);
					var p2x = adjustValue( (p2.left) / $size);
					var p2y = adjustValue( 1 - (p2.top) / $size);
				} else {
					var p1x = adjustValue( (p1.top + 5) / $size);
					var p1y = adjustValue( 1 - (p1.left + 4) / $size);
					var p2x = adjustValue( (p2.top + 5) / $size);
					var p2y = adjustValue( 1 - (p2.left + 4) / $size);
				}
				
				handles.push(p1x);
				handles.push(p1y);
				handles.push(p2x);
				handles.push(p2y);
				
				if (string){
					handles = p1x +","+ p1y +","+ p2x +","+ p2y;
				}
				
				return handles;
			}
			
			function setCustomEasing(){
				$('option',$easingselector).last().prop('selected',true);
				$inputType.val('custom');
			}
			
			function setDemoValue(type) { 
				var value;
				if (type == 'drag') {
					value = getHandles();
					$inputType.val('custom');
				} else {
					value = $easingselector.val();
					$inputType.val('default');
				}
				$input.val(value);
				
				var style = {
					"--easingTypeAnimation":'cubic-bezier('+value+')'
				};
				setStyle($preview,style);
			}
			function setTransitionFn() {
				// console.log('Seteando estilo a la variable box');
			}
			
			// this just removes leading 0 and truncates values
			function adjustValue(val) {	
				val = val.toFixed(2);
				val = val.toString().replace("0.", ".").replace("1.00", "1").replace(".00", "0");
				return val;
			}
			
			function renderWrap(ctx) {
				var p1 = $p1.position(),
				p2 = $p2.position();
				
				render(ctx,
					{
						x: p1.left,
						y: p1.top
					}, 
					{
						x: p2.left,
						y: p2.top
					}
				);
			};
			
			function render(ctx, p1, p2) {
				var ctx = ctx;
				ctx.clearRect(0,0,$size,$size);
				
				ctx.setLineDash([]);
				ctx.beginPath();
				ctx.lineWidth = 3;
				ctx.strokeStyle = "#0073AA";
				ctx.moveTo(0,$size);
				
				// p1 (x,y) p2 (x,y)
				ctx.bezierCurveTo(p1.x,p1.y,p2.x,p2.y,$size,0);				
				ctx.stroke();
				ctx.closePath();
				
				ctx.setLineDash([4, 4]);
				ctx.beginPath();
				ctx.strokeStyle = "#444"; //"#e4e4e4" "#d6d6d6"
				ctx.lineWidth = 1;
				ctx.moveTo(0,$size);
				
				// p1 (x,y)
				ctx.lineTo(p1.x + 0,p1.y + 0);
				ctx.stroke(); 
				
				ctx.moveTo($size,0);
				
				// p2 (x,y)
				ctx.lineTo(p2.x + 0,p2.y + 0);
				ctx.stroke();
				ctx.closePath();
				
				if($.browser.mozilla) {
					$(".p1X", self).html( adjustValue( (p1.x) / $size) );
					$(".p1Y", self).html( adjustValue( 1 - (p1.y) / $size) );
					$(".p2X", self).html( adjustValue( (p2.x) / $size) );
					$(".p2Y", self).html( adjustValue( 1 - (p2.y) / $size) );
				} else {
					$(".p1X", self).html( adjustValue( (p1.x + 5) / $size) );
					$(".p1Y", self).html( adjustValue( 1 - (p1.y + 4) / $size) );
					$(".p2X", self).html( adjustValue( (p2.x + 5) / $size) );
					$(".p2Y", self).html( adjustValue( 1 - (p2.y + 4) / $size) );
				}
				
			}
		});
	};
	// ======================================================
	
	
	// ======================================================
	// CSFRAMEWORK TYPOGRAPHY ADVANCED
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_TYPOGRAPHY_ADVANCED = function() {
		return this.each(function() {
			var typography 				= $(this),
				family_select 			= typography.find('.csf-typo-family'),
				variants_select 		= typography.find('.csf-typo-variant'),
				typography_type 		= typography.find('.csf-typo-font'),
				typography_size			= typography.find('.csf-typo-size'),
				typography_height 		= typography.find('.csf-typo-height'),
				typography_spacing 		= typography.find('.csf-typo-spacing'),
				typography_align		= typography.find('.csf-typo-align'),
				typography_transform 	= typography.find('.csf-typo-transform'),
				typography_color 		= typography.find('.csf-typo-color');
			
			family_select.on('change', function() {
				var _this 		= $(this),
					_selected 	= _this.find(':selected'),
					_type	 	= _this.find(':selected').data('type') || 'custom',
					// _variants 	= _this.find(':selected').data('variants');
					_variants 	= _this.data('variants'),
					_variants 	= _variants[_type][_selected.val()];
				
				if (variants_select.length) {
					variants_select.find('option').remove();
					// $.each(_variants.split('|'), function(key, text) {
					// 	variants_select.append('<option value="' + text + '">' + text + '</option>');
					// });
					$.each(_variants, function(key,text){
						variants_select.append('<option value="' + text + '">' + text + '</option>');
					});

					// Trigger only if is chosen
					// variants_select.find('option[value="regular"]').attr('selected', 'selected').trigger('chosen:updated');
					variants_select.find('option[value="regular"]').attr('selected', 'selected');
				}
				typography_type.val(_type);
			});
			
			// Typography Advanced Live Preview
			// ---------------------------------------------
			var preview 		= $(".csf-typo-preview",typography),
				previewToggle	= $(".csf-typo-preview-toggle",preview),
				previewId		= $(preview).data("previewId"),
				currentFamily 	= $(this).find('.csf-typo-family').val();
			
			var livePreviewRefresh = function(){
				var preview_weight 		= variants_select.val(),
					preview_size		= typography_size.val(),
					preview_height		= typography_height.val(),
					preview_spacing		= typography_spacing.val(),
					preview_align 		= typography_align.val(),
					preview_transform	= typography_transform.val(),
					preview_color 		= typography_color.val();
				
				var style = {
					"--csf-typo-preview-weight":preview_weight,
					"--csf-typo-preview-size":preview_size+"px",
					"--csf-typo-preview-height":preview_height+"px",
					"--csf-typo-preview-spacing":preview_spacing+"px",
					"--csf-typo-preview-align":preview_align,
					"--csf-typo-preview-transform":preview_transform,
					"--csf-typo-preview-color":preview_color
				};
				setPreviewStyle("#"+$(preview).attr("id"),style);
			}
			
			// Update Preview
			// ------------------------------
			if (preview.length){
				$(preview).css("font-family", currentFamily);
				$('head').append('<link href="http://fonts.googleapis.com/css?family=' + currentFamily +'" class="'+previewId+'" rel="stylesheet" type="text/css" />').load();
				livePreviewRefresh();
			}
			
			family_select.on('change',function(){
				$('head').find("."+previewId).remove();
				var font = $(this).val();
				$(preview).css("font-family", font);
				$('head').append('<link href="http://fonts.googleapis.com/css?family=' + font +'" class="'+previewId+'" rel="stylesheet" type="text/css" />').load();
				livePreviewRefresh();
			});
			
			variants_select.on('change',function(){ livePreviewRefresh(); });
			typography_type.on('change',function(){ livePreviewRefresh(); });
			typography_size.on('change',function(){ livePreviewRefresh(); });
			typography_height.on('change',function(){ livePreviewRefresh(); });
			typography_align.on('change',function(){ livePreviewRefresh(); });
			typography_color.on('change',function(){ livePreviewRefresh(); });
			typography_spacing.on('change',function(){ livePreviewRefresh(); });
			typography_transform.on('change',function(){ livePreviewRefresh(); });
			
			// Toggle Preview BG Style
			// ------------------------------
			$(previewToggle).on("click",function(){
				$(preview).toggleClass("csf-typo-preview-toggle_dark");
			});
			
			
			
			//-----------------------------------------------------------------
			// HELPER FUNCTIONS
			//-----------------------------------------------------------------
			function setPreviewStyle( element, propertyObject ){
				var elem = document.querySelector(element).style;
				for (var property in propertyObject){
					elem.setProperty(property, propertyObject[property]);
				}
			}
			
			function removeStyle( element, propertyObject){
				var elem = document.querySelector(element).style;
				for (var property in propertyObject){
					elem.removeProperty(propertyObject[property]);
				}
			}
		});
	};
	// ======================================================
	
	
	// ======================================================
	// Accordion field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_ACCORDION = function() {
		return this.each(function(){
			var self = this;
			
			$(self).accordion({
				header: '.csf-accordion-title',
				collapsible: true,
				active: false,
				animate: 350,
				heightStyle: 'content',
				icons: {
					'header': 'cli cli-arrow-down',
					'activeHeader': 'cli cli-arrow-up'
				},
				beforeActivate: function(event, ui) {
					$(ui.newPanel).CSFRAMEWORK_DEPENDENCY('sub');
				}
			});
		});
	};
	// ======================================================
	
	
	// ======================================================
	// Angle field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_ANGLE = function() {
		return this.each( function() {
			var dis 		= $( this ),
			input 		= $('.csf-anglepicker input',dis),
			anglePicker = $('.csf-anglepicker > div.csf-anglepicker-wrapper > .anglepicker', dis ),
			data 		= $('.csf-anglepicker',dis).data( 'angleOptions' ),
			distance 	= data.distance || 1,
			delay 		= data.delay || 1,
			snap 		= data.snap || 1,
			min 		= data.min || 0,
			shiftSnap 	= data.shiftSnap || 15,
			clockwise 	= data.clockwise || false,
			value 		= data.value || 0;
			
			$(anglePicker).anglepicker({
				start: function(e, ui) {
					
				},
				change: function(e, ui) {
					$(input).val(ui.value);
				},
				stop: function(e, ui) {
					
				},
				distance: 	distance,
				delay: 		delay,
				snap: 		snap,
				min: 		min,
				shiftSnap: 	shiftSnap,
				clockwise: 	clockwise,
				value: 		value,
			});
			
			$(input).on('blur',function(){
				var value = $(input).val();
				$(anglePicker).anglepicker('value',value);
			});
		} );
	};
	// ======================================================
	
	
	// ======================================================
	// Code Editor field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_CODEEDITOR = function() {
		$('.csf-field-code_editor').each(function(index) {
			var $editorContainer = $( this ).find( '.code-editor-container' );
			
			// Get textarea to get/save data
			var $editorTextarea = $editorContainer.prev( 'textarea' );
			
			// Add ID to ace-editor-container
			$editorContainer.attr( 'id', 'aceeditor' + index );
			
			// Get theme and language
			var editorTheme = $editorContainer.data( 'theme' );
			var editorMode = $editorContainer.data( 'mode' );
			
			// Inicialize ACE editor
			var editor = ace.edit( 'aceeditor' + index );
			
			// Set editor settings
			editor.setTheme( 'ace/theme/' + editorTheme );
			editor.getSession().setMode( 'ace/mode/' + editorMode );
			
			editor.setOptions({
				enableBasicAutocompletion: true,
				enableSnippets: true,
				enableLiveAutocompletion: true
			});
			
			// Save data in textarea on ACE editor change
			editor.getSession().on( 'change', function () {
				$editorTextarea.val( editor.getSession().getValue() );
			});
			
			// Get data on load
			editor.getSession().setValue( $editorTextarea.val() );
		});
	};
	// ======================================================
	
	
	// ======================================================
	// Background Field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_BACKGROUND = function() {
		return this.each(function() {
			var $this 			= $(this),
				$add 			= $this.find('.csf-add'),
				$preview 		= $this.find('.csf-image-preview'),
				$remove 		= $this.find('.csf-remove'),
				$input 			= $this.find('input'),
				$input_image 	= $input.first(),
				$img 			= $this.find('img'),
				wp_media_frame;

			$add.on('click', function(e) {
				e.preventDefault();
				// Check if the `wp.media.gallery` API exists.
				if (typeof wp === 'undefined' || !wp.media || !wp.media.gallery) {
					return;
				}
				// If the media frame already exists, reopen it.
				if (wp_media_frame) {
					wp_media_frame.open();
					return;
				}
				// Create the media frame.
				wp_media_frame = wp.media({
					// Set the title of the modal.
					title: $add.data('frame-title'),
					// Tell the modal to show only images.
					library: {
						type: 'image'
					},
					// Customize the submit button.
					button: {
						// Set the text of the button.
						text: $add.data('insert-title'),
					}
				});
				// When an image is selected, run a callback.
				wp_media_frame.on('select', function() {
					var attachment = wp_media_frame.state().get('selection').first().attributes;
					var preview_size = $preview.data('preview-size');
					if (preview_size == 'custom'){
						var thumbnail = attachment.url;	
					} else {
						if (typeof preview_size === 'undefined') {
							preview_size = 'thumbnail';
						}
						var thumbnail = (typeof attachment['sizes'][preview_size] !== 'undefined') ? attachment['sizes'][preview_size]['url'] : attachment.url;
					}
					$preview.removeClass('hidden');
					$remove.removeClass('hidden');
					$img.attr('src', thumbnail);
					$input_image.val(attachment.id).trigger('change');
					console.log($input);
				});
				// Finally, open the modal.
				wp_media_frame.open();
			});
			// Remove image
			$remove.on('click', function(e) {
				e.preventDefault();
				$input_image.val('').trigger('change');
				$preview.addClass('hidden');
				$remove.addClass('hidden');
			});
		});
	};
	// ======================================================
	
	
	// ======================================================
	// Animate.css Field by Castor Studio
	// ------------------------------------------------------
    $.fn.CSFRAMEWORK_ANIMATE_CSS = function(){
        return this.each(function () {
            var $parent = $(this);
            $parent.find("select").on('change', function () {
                var $val = $(this).val();
                var $h3 = $parent.find('.animation-preview h3');
                $h3.removeClass();
                $h3.addClass($val + ' animated ').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                    $(this).removeClass();
                });
            })
        })
	};


	// ======================================================
	// CSS BUILDER Field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_CSS_BUILDER = function () {
        return this.each(function () {
            var $this = $(this);

            $this.find('.csf-css-checkall').on('click', function () {
                $(this).toggleClass('checked');
            });

            $this.find('.csf-element.csf-margin :input').on('change', function () {
                $.CSFRAMEWORK_HELPER.CSS_BUILDER.update.all($(this), 'margin', $this);
            });

            $this.find('.csf-element.csf-padding :input').on('change', function () {
                $.CSFRAMEWORK_HELPER.CSS_BUILDER.update.all($(this), 'padding', $this);
            });

            $this.find('.csf-element.csf-border :input').on('change, blur', function () {
                $.CSFRAMEWORK_HELPER.CSS_BUILDER.update.all($(this), 'border', $this);
            });

            $this.find('.csf-element.csf-border-radius :input').on('change', function () {
                $.CSFRAMEWORK_HELPER.CSS_BUILDER.update.all($(this), 'border-radius', $this);
            });

            $this.find('.csf-element-border-style select').on('change', function () {
                $.CSFRAMEWORK_HELPER.CSS_BUILDER.update.border($this);
            });

            $this.find('.csf-element-border-color input.csf-field-color-picker').on('change', function () {
                $.CSFRAMEWORK_HELPER.CSS_BUILDER.update.border($this);
            });

            $this.find('.csf-element-text-color input.csf-field-color-picker').on('change', function () {
                $.CSFRAMEWORK_HELPER.CSS_BUILDER.update.border($this);
            });

            $this.find('.csf-element-background-color input.csf-field-color-picker').on('change', function () {
                $.CSFRAMEWORK_HELPER.CSS_BUILDER.update.border($this);
            });

        })
	};


	// ======================================================
	// TEXT LIMITER Field by Castor Studio
	// ------------------------------------------------------
    $.fn.CSFRAMEWORK_LIMITER = function () {
        return this.each(function () {
            var $this = $(this),
                $parent = $this.parent(),
                $limiter = $parent.find('> .text-limiter'),
                $counter = $limiter.find('span.counter'),
                $limit = parseInt($limiter.find('span.maximum').html()),
                $countByWord = 'word' == $limiter.data('limit-type');

            var $val = $.CSFRAMEWORK_HELPER.LIMITER.counter($this.val(), $countByWord);
            $counter.html($val);

            $this.on('input', function () {
                var text = $this.val(),
                    length = $.CSFRAMEWORK_HELPER.LIMITER.counter(text, $countByWord);

                if ( length > $limit ) {
                    text = $.CSFRAMEWORK_HELPER.LIMITER.subStr(text, 0, $limit, $countByWord);
                    $this.val(text);
                    $counter.html($limit);
                } else {
                    $counter.html(length);
                }
            });
        })
	};


	// ======================================================
	// CHECKBOX LABELED Field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_CHECKBOX_LABELED = function(){
		return this.each(function () {
			var $this = $(this);
			$this.labelauty();
		});
	};


	// ======================================================
	// CHECKBOX ICHECK Field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_CHECKBOX_ICHECK = function(){
		return this.each(function () {
			var $this = $(this);
			$this.iCheck({
				// handle: 'checkbox,radio',
				checkboxClass: 'csf-checkbox-icheck--checkbox',
				radioClass: 'csf-checkbox-icheck--radio',
			}).on('ifChanged', function(event){
				$this.trigger('change');
			}).on('change',function(){
				$this.iCheck('update');
			});
		});
	};


	// ======================================================
	// WPLinks
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_WPLINKS = function () {
		return this.each( function () {
			$( this ).on( 'click', function (e) {
				e.preventDefault();

				var $this = $( this ),
					$parent = $this.parent(),
					$textarea = $parent.find( '#sample_wplinks' ),
					$link_submit = $( "#wp-link-submit" ),
					$csf_link_submit = $( '<input type="submit" name="csf-link-submit" id="csf_link-submit" class="button-primary" value="' + $link_submit.val() + '">' );
				$link_submit.hide();
				$csf_link_submit.insertBefore( $link_submit );
				var $dialog = !window.wpLink && $.fn.wpdialog && $( "#wp-link" ).length ? {
					$link: !1,
					open: function () {
						this.$link = $( '#wp-link' ).wpdialog( {
							title: wpLinkL10n.title,
							width: 480,
							height: "auto",
							modal: !0,
							dialogClass: "wp-dialog",
							zIndex: 3e5
						} )
					},
					close: function () {
						this.$link.wpdialog( 'close' );
					}
				} : window.wpLink;

				$dialog.open( $textarea.attr( 'id' ) );
				$csf_link_submit.unbind( 'click.csf-wpLink' ).bind( 'click.csf-wpLink', function (e) {
					e.preventDefault(), e.stopImmediatePropagation();

					var $url = $( "#wp-link-url" ).length ? $( "#wp-link-url" ).val() : $( "#url-field" ).val(),
						$title = $( "#wp-link-text" ).length ? $( "#wp-link-text" ).val() : $( "#link-title-field" ).val(),
						$checkbox = $( $( "#wp-link-target" ).length ? "#wp-link-target" : "#link-target-checkbox" ),
						$target = $checkbox[ 0 ].checked ? " _blank" : "";

					$parent.find( 'span.link-title-value' ).html( $title );
					$parent.find( 'span.url-value' ).html( $url );
					$parent.find( 'span.target-value' ).html( $target );

					$parent.find( 'input.csf-url' ).val( $url );
					$parent.find( 'input.csf-title' ).val( $title );
					$parent.find( 'input.csf-target' ).val( $target );

					$dialog.close(),
						$link_submit.show(),
						$csf_link_submit.unbind( "click.csf-wpLink" ),
						$csf_link_submit.remove(),
						$( "#wp-link-cancel" ).unbind( "click.csf-wpLink" ),
						window.wpLink.textarea = "";

					$this.trigger( 'csf-links-updated' );
				} );

				$( "#wp-link-cancel" ).unbind( "click.csf-wpLink" ).bind( "click.csf-wpLink", function (e) {
					e.preventDefault(),
						$dialog.close(),
						$csf_link_submit.unbind( "click.csf-wpLink" ),
						$csf_link_submit.remove(),
						$( "#wp-link-cancel" ).unbind( "click.csf-wpLink" ),
						window.wpLink.textarea = "";

					$this.trigger( 'csf-links-updated' );
				} );
			} )
		} )
	};
	// ======================================================


	// ======================================================
	// Color Theme field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_COLORTHEME = function() {
		return this.each(function(){
			var self = this;
			
			var $parent 			= $('.csf-schemes',self),
				$builder 			= $('.csf-scheme-builder',self),
				$controls 			= $('.csf-schemes-controls',self),
				$schemes_list 		= $('.csf-schemes-list',self),
				$predefined_schemes = $('.csf-color-scheme-predefined_schemes',self),
				$custom_schemes		= $('.csf-color-scheme-custom_schemes',self),
				$input 				= $('.csf-color-scheme-scheme_name',$controls),
				$btn_save 			= $('.csf-color-scheme-save_scheme',$controls),
				$btn_import 		= $('.csf-color-scheme-import_scheme',$controls),
				$import_box			= $('.csf-schemes-import',$controls),
				$btn_import_submit 	= $('.csf-schemes-import_submit',$import_box),
				$btn_export			= $('.csf-color-scheme-export_scheme',$controls),
				field_unique 		= $('.csf-color-scheme-unique',self).val(),
				options_unique 		= $('input[name=option_page]','#csframework_form').val(),
				$preview_template 	= $('.csf-scheme-preview-template',self);

			// Hide Import Box Content
			$import_box.slideUp();
			
			// Select Color Scheme
			$schemes_list.on('click','.csf-schemes-item',function(e){
				e.preventDefault();

				var $self 				= $(this),
					scheme_id 			= $self.data('schemeId'),
					scheme_type 		= $self.data('schemeType'),
					predefined_schemes 	= ($predefined_schemes.val()) ? JSON.parse($predefined_schemes.val()) : null,
					custom_schemes 		= ($custom_schemes.val()) ? JSON.parse($custom_schemes.val()) : null,
					all_schemes 		= {};
				
				all_schemes['predefined'] 	= predefined_schemes;
				all_schemes['custom']		= custom_schemes;

				var current_scheme 			= all_schemes[scheme_type][scheme_id];

				if (current_scheme){
					$self.addClass('csf-loading-scheme');

					var load_scheme = setTimeout(function(){
						// Update Current Scheme ID, val & list border style
						scheme_type = (scheme_type == 'custom') ? 'custom' : 'predefined';
						$('.csf-color-scheme-current_id',$parent).val(scheme_id);
						$('.csf-color-scheme-current_type',$parent).val(scheme_type);
	
						$('.csf-schemes-item-current',$schemes_list).removeClass('csf-schemes-item-current');
						$self.addClass('csf-schemes-item-current');
	
						// Clear all color pickers
						$('.csf-field-color-picker',$builder).val('').parents('.wp-picker-container').children('.wp-color-result').css('background-color','');
						
						$.each(current_scheme.scheme,function(index,value){
							// console.log("Color:",index,value);
							$('.csf-field-color-picker[data-field-name='+index+']').wpColorPicker('color',value);
						});

						$self.removeClass('csf-loading-scheme');
					},100);

				}

			});
			

			// Save New Scheme
			$btn_save.on('click',function(e){
				e.preventDefault();
				var scheme_name = $input.val();

				if (scheme_name){
					if ($btn_save.data('status')) { return false; }

					// Spinner
					var btn_save_text = $btn_save.html();
					$btn_save.data('status','working').attr('disabled','disabled').addClass('csf-button-disabled').html('<div class="csf-spinner"></div>');

					// Get Current Color Scheme
					var colors = $('.csf-scheme-builder .csf-field-color_picker .csf-field-color-picker',self);
					var current_scheme = {};

					$(colors).each(function(){
						var current = $(this),
							color	= current.val(),
							name 	= current.data('fieldName');

						current_scheme[name] = color;
					});

					var new_scheme = {
						name: scheme_name,
						scheme: current_scheme
					};

					// AJAX Call
					var action = 'csf-color-scheme_save';
					var data = {
						nonce: 			csf_framework.nonce, 	// Security nonce
						action: 		action,					// Ajax Action
						// Parameters
						field_unique:	field_unique,
						options_unique:	options_unique,
						scheme:			new_scheme,
					};
		
					$.ajax({
						url: 	csf_framework.ajax_url,
						type: 	'post',
						data: 	data,
						success: function(response){
							if (response.success){
								// Update Custom Schemes
								$custom_schemes.val(response.data.schemes);

								// Add Scheme Preview
								var $preview = $($preview_template.html()).clone();
								var scheme_slug = $.CSFRAMEWORK_HELPER.FUNCTIONS.string_to_slug(scheme_name);
								$preview.attr('data-scheme-id',scheme_slug);
								$('.csf-schemes-item_delete',$preview).attr('data-scheme-id',scheme_slug);
								$('.preview_text',$preview).html($.CSFRAMEWORK_HELPER.FUNCTIONS.make_title(scheme_slug));

								// current_scheme
								var colores = $preview_template.data('schemeColors');
								var color_vars = '';
								$.each(colores,function(index,value){
									index++;
									color_vars += "--color"+index+":"+current_scheme[value]+";";
								});

								$preview.attr('style',color_vars);

								// Add new scheme preview
								$preview.appendTo($schemes_list);
							}
						},
						complete: function(){
							$btn_save.data('status',false).removeAttr('disabled').removeClass('csf-button-disabled').html(btn_save_text);
						}
					});
				}

			});


			// Import Schemes
			$btn_import.on('click',function(e){
				e.preventDefault();
				$import_box.slideToggle();
			});
			// Import Submit Schemes
			$btn_import_submit.on('click',function(e){
				e.preventDefault();

				var $self 				= $(this),
					$import_textarea 	= $('.csf-schemes-import_data',$controls),
					$import_checkbox 	= $('.csf-schemes-import_overwrite',$controls),
					schemes_to_import 	= $import_textarea.val(),
					schemes_overwrite 	= $import_checkbox.prop('checked');

				if (schemes_to_import){
					if ($self.data('status')) { return false; }
	
					// Spinner
					var btn_text = $self.html();
					$self.data('status','working').attr('disabled','disabled').addClass('csf-button-disabled').html('<div class="csf-spinner"></div>');
	
	
					// AJAX Call
					var action = 'csf-color-scheme_import';
					var data = {
						nonce: 			csf_framework.nonce, 	// Security nonce
						action: 		action,					// Ajax Action
						// Parameters
						field_unique:	field_unique,
						options_unique:	options_unique,
						schemes:		schemes_to_import,
						overwrite:		schemes_overwrite,
					};
		
					$.ajax({
						url: 	csf_framework.ajax_url,
						type: 	'post',
						data: 	data,
						success: function(response){
							if (response.success){
								// Update Custom Schemes
								$custom_schemes.val(response.data.schemes);

								var schemes 			= JSON.parse(response.data.schemes),
									$schemes_to_append 	= $(document.createDocumentFragment());

								$.each(schemes,function(index,scheme){
									// Add Scheme Preview
									var $preview 		= $($preview_template.html()).clone(),
										scheme_name 	= $.CSFRAMEWORK_HELPER.FUNCTIONS.make_title(scheme.name),
										scheme_slug 	= index,
										current_scheme 	= scheme.scheme;
										
									$preview.attr('data-scheme-id',scheme_slug);
									$('.csf-schemes-item_delete',$preview).attr('data-scheme-id',scheme_slug);
									$('.preview_text',$preview).html(scheme_name);
	
									// current_scheme
									var colores = $preview_template.data('schemeColors');
									var color_vars = '';
									$.each(colores,function(index,value){
										index++;
										color_vars += "--color"+index+":"+current_scheme[value]+";";
									});
	
									$preview.attr('style',color_vars).appendTo($schemes_to_append);
								});

								// Add new scheme preview
								$('.csf-schemes-item[data-scheme-type=custom]',$schemes_list).remove();
								$schemes_list.append($schemes_to_append);

								// Empty Textarea
								$import_textarea.val('');
								$import_checkbox.prop('checked',false).trigger('change');
							}
						},
						complete: function(){
							$self.data('status',false).removeAttr('disabled').removeClass('csf-button-disabled').html(btn_text);
						}
					});
				}
			});


			// Delete Scheme
			$schemes_list.on('click','.csf-schemes-item_delete',function(e){
				e.preventDefault();
				e.stopPropagation();

				var $self 		= $(this),
					$preview 	= $self.parents('.csf-schemes-item'),
					scheme_id 	= $self.data('schemeId');
				
					
				if (!$self.data('status')){
					$self.data('status','confirm');
					$self.html('<div class="csf-button-inner"><a href="#">Are you sure?</a></div>');
					return false;
				}
				var status = $self.data('status');
				if (status && status != 'confirm') { return false; }

				// Spinner
				$self.data('status','working').addClass('csf-visible').html('<div class="csf-spinner"></div>');

				// AJAX Call
				var action = 'csf-color-scheme_delete';
				var data = {
					nonce: 			csf_framework.nonce, 	// Security nonce
					action: 		action,					// Ajax Action
					// Parameters
					field_unique:	field_unique,
					options_unique:	options_unique,
					scheme: 		scheme_id,
				};

				var state = null;
	
				$.ajax({
					url: 	csf_framework.ajax_url,
					type: 	'post',
					data: 	data,
					success: function(response){
						if (response.success){
							state = 'success';
							// Update Custom Schemes
							$custom_schemes.val(response.data.schemes);

							$self.addClass('csf-visible').html('<i class="cli cli-check"></i>')

							// Remove Scheme Preview
							var setStyle = function( element, propertyObject ){
								var elem = element.style;
								for (var property in propertyObject){
									elem.setProperty(property, propertyObject[property]);
								}
							}
							var style = { '--item-background-color':'rgba(220, 49, 49, 1)'};
							setStyle($preview[0],style);
							$preview.delay(500).queue(function(){
								$(this).addClass('csf-schemes-item-deleted').dequeue().one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(e) {
									$(this).remove();
								});
							});
						}
					},
					complete: function(){
						$self.data('status',false).removeClass('csf-visible');
						if (!state){
							$self.html('<i class="cli cli-trash"></i>');
						}
					}
				});
			}).on('mouseleave','.csf-schemes-item_delete',function(e){
				var $self 		= $(this);

				if ($self.data('status') != 'working'){
					$self.html('<i class="cli cli-trash"></i>').data('status',false);
				}

			});
			

			$('.csf-scheme-section',self).accordion({
				header: '.csf-accordion-title',
				collapsible: true,
				clearStyle: true,
				active: false,
				animate: 350,
				heightStyle: 'content',
				icons: {
					'header': 'cli cli-arrow-down',
					'activeHeader': 'cli cli-arrow-up'
				},
				beforeActivate: function(event, ui) {
					$(ui.newPanel).CSFRAMEWORK_DEPENDENCY('sub');
				}
			});

			$('.csf-field-color-picker').each(function() {
				var self = this;

				$(this).wpColorPicker({
				//   palettes: ['#125', '#459', '#78b', '#ab0', '#de3', '#f0f']
					change: function(event, ui){
						var element = event.target;
						var color = ui.color.toString();
						
						var $target 	= $(event.target),
							field_id 	= $target.data('fieldName'),
							color 		= ui.color.toString();

						
						$(self).parents('.csf-field-color_theme').trigger( "csf-color_theme-update", [ field_id, color ] );
					}
				});
			  });
		});
	};
	// ======================================================


	// ======================================================
	// Layout Builder field by Castor Studio
	// ------------------------------------------------------
	$.fn.CSF_LAYOUTBUILDER = function() {
		return this.each( function() {
			var $self 		= $( this ),
			top 		= $('.layout-section__top',$self),
			left 		= $('.layout-section__left',$self),
			right 		= $('.layout-section__right',$self),
			bottom 		= $('.layout-section__bottom',$self),
			main 		= $('.layout-section__main',$self),
			buttonbar 	= $('.layout-section__buttonbar',$self),
			elements 	= $('.csf-uls-layout__elements',$self);
			
			var containers = [
				top[0],
				left[0],
				right[0],
				bottom[0],
				main[0],
				buttonbar[0],
				elements[0]
			];
			
			var updateLayoutSections = function(el){
				var section_top 		= $('.csf-uls-layout-element',top),
				section_left 		= $('.csf-uls-layout-element',left),
				section_right 		= $('.csf-uls-layout-element',right),
				section_bottom 		= $('.csf-uls-layout-element',bottom),
				section_main 		= $('> .csf-uls-layout-element',main), // only direct child, to avoid buttonbar elements
				section_buttonbar 	= $('.csf-uls-layout-element',buttonbar),
				section_elements	= $('.csf-uls-layout-element',elements);
				
				var getElements = function(section){
					var map = jQuery.map( section, function( n, i ) {
						return $(n).data('layoutElementName');
					});
					var array = Object.values(map);
					return JSON.stringify(array);	
				};
				
				$('input.section__top',$self).val(getElements(section_top));
				$('input.section__left',$self).val(getElements(section_left));
				$('input.section__right',$self).val(getElements(section_right));
				$('input.section__bottom',$self).val(getElements(section_bottom));
				$('input.section__main',$self).val(getElements(section_main));
				$('input.section__buttonbar',$self).val(getElements(section_buttonbar));
				$('input.section__elements',$self).val(getElements(section_elements));
			};
			
			// dragula({
			// 	containers: containers,
			// 	revertOnSpill: true,
			// 	moves: function (el, source, handle) {
			// 		return $(el).is('.csf-uls-layout-element'); 
			// 	},
			// })
			// .on('drop', function(el, target, source, sibling){
			// 	updateLayoutSections();
			// });

			// Ipido Admin Top Navbar Sorting
			Sortable.create(elements[0], { 
				animation: 300,
				group: "csbuilder",
				onEnd: function (evt) {
					updateLayoutSections();
				},
			});
			Sortable.create(main[0], { 
				animation: 300,
				group: "csbuilder",
				onEnd: function (evt) {
					updateLayoutSections();
				},
			});
			
			updateLayoutSections();
		});
	};
	// ======================================================

	
	
	// ======================================================
	// AutoComplete by Codevz
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_AUTOCOMPLETE = function() {
		return this.each( function() {
			var ac = $( this ),
			time = false,
			query = ac.data( 'query' );
			// Keyup input and send ajax
			$( '> input', ac ).on( 'keyup', function() {
				clearTimeout( time );
				var val = $( this ).val(),
				results = $( '.ajax_items', ac );
				if ( val.length < 2 ) {
					results.slideUp();
					$( '.fa-codevz', ac ).removeClass( 'fa-spinner fa-pulse' );
					return;
				}
				$( '.fa-codevz', ac ).addClass( 'fa-spinner fa-pulse' );
				time = setTimeout( function() {
					$.ajax( {
						type: "GET",
						url: ajaxurl,
						data: $.extend( query, { s: val } ),
						success: function( data ) {
							results.html( data ).slideDown();
							$( '.fa-codevz', ac ).removeClass( 'fa-spinner fa-pulse' );
						},
						error: function( xhr, status, error ) {
							results.html( '<div>' + error + '</div>' ).slideDown();
							$( '.fa-codevz', ac ).removeClass( 'fa-spinner fa-pulse' );
							console.log( xhr, status, error );
						}
					} );
				}, 1000 );
			} );
			// Choose item from ajax results
			$( '.ajax_items', ac ).on( 'click', 'div', function() {
				var id = $( this ).data( 'id' ),
				title = $( this ).html();
				if ( $( '.multiple', ac ).length ) {
					var target = 'append';
					var name = query.elm_name + '[]';
				} else {
					var target = 'html';
					var name = query.elm_name;
				}
				$( '> input', ac ).val( '' );
				$( '.ajax_items' ).slideUp();
				if ( $( '#' + id, ac ).length ) {
					return;
				}
				$( '.selected_items', ac )[ target ]( '<div id="' + id + '"><input name="' + name + '" value="' + id + '" /><span> ' + title + '<i class="fa fa-remove"></i></span></div>' );
			} );
			// Remove selected items
			$( '.selected_items', ac ).on( 'click', '.fa-remove', function() {
				$( this ).parent().parent().detach();
			} );
			$( '.csf-autocomplete, .ajax_items' ).on( 'click', function( e ) {
				e.stopPropagation();
			} );
			$( 'body' ).on( 'click', function( e ) {
				$( '.ajax_items' ).slideUp();
			} );
		} );
	};
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	// ======================================================
	// TOOLTIP HELPER
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_TOOLTIP = function() {
		return this.each(function() {
			var placement = (csf_is_rtl) ? 'right' : 'left';
			var placement = (csf_is_rtl) ? placement : (($(this).data('tooltipPlacement')) ? $(this).data('tooltipPlacement') : 'top' );
			$(this).cstooltip({
				html: true,
				placement: placement,
				container: 'body'
			});
		});
	};
	// ======================================================
    // RELOAD WYSIWYG FIELDS
    // ------------------------------------------------------
    $.fn.CSFRAMEWORK_RELOAD_WYSIWYG = function() {
		// var group_wysiwyg           = this.find('.csf-field-wysiwyg');
		// var group_wysiwyg           = this.closest('.csf-group').find('.csf-field-wysiwyg');
		var group_wysiwyg           = this;
		
		if (group_wysiwyg && group_wysiwyg.length){

			group_wysiwyg.each(function(){
	
				if( jQuery(this).find('.mce-tinymce').length <= 0 ) {
					var field_group_i         = jQuery(this).closest('.csf-group').index();
					var field_group_i 		= jQuery(this).closest('.csf-group').children('.csf-group-content').attr('id');
					var default_wysiwyg_id    = jQuery(this).find('textarea').attr('id');
					var group_wysiwyg_id      = default_wysiwyg_id + '_' + field_group_i;
					var tmp_wysiwyg_settings  = tinyMCEPreInit.mceInit[default_wysiwyg_id];

					jQuery(this).find('textarea').attr('id', group_wysiwyg_id);
					jQuery(this).find('.wp-media-buttons .add_media').data('editor', group_wysiwyg_id);
					jQuery(this).find('.wp-editor-tabs .wp-switch-editor').attr('data-wp-editor-id', group_wysiwyg_id);

					tinymce.init(tmp_wysiwyg_settings); 
					tinyMCE.execCommand('mceAddEditor', false, group_wysiwyg_id); 

					// var textareaId = 'widget_content';
					// tinymce.execCommand('mceRemoveEditor',true, textareaId);
					// tinymce.execCommand('mceFocus', true, textareaId );
					// tinymce.execCommand('mceAddEditor',true, textareaId);
	
				}
			});
		}
	};


	// ======================================================
	// ON WIDGET-ADDED RELOAD FRAMEWORK PLUGINS
	// ------------------------------------------------------
	$.CSFRAMEWORK.WIDGET_RELOAD_PLUGINS = function() {
		$(document).on('widget-added widget-updated', function(event, $widget) {
			$widget.CSFRAMEWORK_RELOAD_PLUGINS();
			$widget.CSFRAMEWORK_DEPENDENCY();
		});
	};


	// ======================================================
	// RELOAD FRAMEWORK PLUGINS
	// ------------------------------------------------------
	$.fn.CSFRAMEWORK_RELOAD_PLUGINS = function() {
		return this.each(function() {
			$('.chosen', this).CSFRAMEWORK_CHOSEN();
			$('.csf-field-image-select', this).CSFRAMEWORK_IMAGE_SELECTOR();
			$('.csf-field-image', this).CSFRAMEWORK_IMAGE_UPLOADER();
			$('.csf-field-gallery', this).CSFRAMEWORK_IMAGE_GALLERY();
			$('.csf-field-sorter', this).CSFRAMEWORK_SORTER();
			$('.csf-field-upload', this).CSFRAMEWORK_UPLOADER();
			$('.csf-field-typography', this).CSFRAMEWORK_TYPOGRAPHY();
			$('.csf-field-color-picker', this).CSFRAMEWORK_COLORPICKER();
			$('.csf-help', this).CSFRAMEWORK_TOOLTIP();
			$('> .csf-group-content > .csf-field-wysiwyg', this).CSFRAMEWORK_RELOAD_WYSIWYG();
			
			// ==============================
			// CastorStudio Plugins
			// ------------------------------
			$('.csf-autocomplete', this).CSFRAMEWORK_AUTOCOMPLETE(); 						// by Codevz
			$('.csf-has-tooltip', this).CSFRAMEWORK_TOOLTIP();								// To add tooltip functionality on other fields
			$('.csf-field-slider', this).CSFRAMEWORK_SLIDER();								// Slider field by Castor Studio
			$('.csf-field-easing_editor', this).CSFRAMEWORK_EASINGEDITOR();					// Easing Editor field by Castor Studio
			$('.csf-field-typography_advanced', this).CSFRAMEWORK_TYPOGRAPHY_ADVANCED(); 	// Typography Advanced field by Castor Studio
			$('.csf-field-accordion', this).CSFRAMEWORK_ACCORDION();						// Accordion field by Castor Studio
			$('.csf-field-angle', this).CSFRAMEWORK_ANGLE();								// Angle field by Castor Studio
			$('.csf-field-code_editor', this).CSFRAMEWORK_CODEEDITOR();						// Code Editor field by Castor Studio
			$('.csf-field-background', this).CSFRAMEWORK_BACKGROUND();						// Background Editor field by Castor Studio
			$('.csf-field-animate_css', this).CSFRAMEWORK_ANIMATE_CSS();					// Animate.css field by Castor Studio
			$('.csf-field-css_builder', this).CSFRAMEWORK_CSS_BUILDER();					// CSS Builder by Castor Studio
			$('input[data-limit-element="1"]', this).CSFRAMEWORK_LIMITER();					// Text Input Limiter by Castor Studio
			$('textarea[data-limit-element="1"]', this).CSFRAMEWORK_LIMITER();				// Textarea Limiter by Castor Studio
			$('.csf-field-checkbox .csf-checkbox-labeled').CSFRAMEWORK_CHECKBOX_LABELED(); 	// Labeled Checkboxes
			$('.csf-field-checkbox .csf-checkbox-icheck').CSFRAMEWORK_CHECKBOX_ICHECK(); 	// iCheck Checkboxes
			$('.csf-field-radio .csf-checkbox-icheck').CSFRAMEWORK_CHECKBOX_ICHECK(); 		// iCheck Checkboxes
			$('.csf-wp-link', this).CSFRAMEWORK_WPLINKS();									// WPLinks Field by Castor Studio
			$('.csf-field-color_theme', this).CSFRAMEWORK_COLORTHEME();						// Accordion field by Castor Studio
			$('.csf-field-builder_navbar', this).CSF_LAYOUTBUILDER();						// Layout Builder: Navbar by Castor Studio

		});
	};
	// ======================================================
	// JQUERY DOCUMENT READY
	// ------------------------------------------------------
	$(document).ready(function() {
		$('.csf-framework').CSFRAMEWORK_TAB_NAVIGATION();
		$('.csf-framework .csf-nav').CSFRAMEWORK_NAV_SCROLL_TABS();
		$('.csf-reset-confirm, .csf-import-backup').CSFRAMEWORK_CONFIRM();
		$('.csf-content, .wp-customizer, .widget-content, .csf-taxonomy').CSFRAMEWORK_DEPENDENCY();
		$('.csf-field-group').CSFRAMEWORK_GROUP();
		$('.csf-save').CSFRAMEWORK_SAVE();
		$csf_body.CSFRAMEWORK_RELOAD_PLUGINS();
		$.CSFRAMEWORK.ICONS_MANAGER();
		$.CSFRAMEWORK.IMAGE_GALLERY_CUSTOM();
		$.CSFRAMEWORK.SHORTCODE_MANAGER();
		$.CSFRAMEWORK.WIDGET_RELOAD_PLUGINS();
		$('.csf-field-wysiwyg').CSFRAMEWORK_RELOAD_WYSIWYG();
		
	});
})(jQuery, window, document);