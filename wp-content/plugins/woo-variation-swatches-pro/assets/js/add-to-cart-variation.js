/*!
 * WooCommerce Variation Swatches Pro v1.0.27 
 * 
 * Author: Emran Ahmed ( emran.bd.08@gmail.com ) 
 * Date: 2018-11-24 18:25:59
 * Released under the GPLv3 license.
 */
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);
__webpack_require__(2);
__webpack_require__(3);
module.exports = __webpack_require__(4);


/***/ }),
/* 1 */
/***/ (function(module, exports) {

/*global wc_add_to_cart_variation_params, woo_variation_swatches_options */
;(function ($, window, document, undefined) {
    /**
     * VariationForm class which handles variation forms and attributes.
     */
    var VariationForm = function VariationForm($form) {
        this.$form = $form;
        this.$attributeFields = $form.find('.variations select');
        this.$singleVariation = $form.find('.single_variation');
        this.$singleVariationWrap = $form.find('.single_variation_wrap');
        this.$resetVariations = $form.find('.reset_variations');
        this.$product = $form.closest('.product');
        this.variationData = $form.data('product_variations');
        this.useAjax = false === this.variationData;
        this.xhr = false;
        this.loading = true;

        // Initial state.
        this.$singleVariationWrap.show();
        this.$form.off('.wc-variation-form');

        // Methods.
        this.getChosenAttributes = this.getChosenAttributes.bind(this);
        this.findMatchingVariations = this.findMatchingVariations.bind(this);
        this.isMatch = this.isMatch.bind(this);
        this.toggleResetLink = this.toggleResetLink.bind(this);

        // Events.
        $form.on('click.wc-variation-form', '.reset_variations', { variationForm: this }, this.onReset);
        $form.on('reload_product_variations', { variationForm: this }, this.onReload);
        $form.on('hide_variation', { variationForm: this }, this.onHide);
        $form.on('show_variation', { variationForm: this }, this.onShow);
        $form.on('click', '.single_add_to_cart_button', { variationForm: this }, this.onAddToCart);
        $form.on('reset_data', { variationForm: this }, this.onResetDisplayedVariation);
        $form.on('reset_image', { variationForm: this }, this.onResetImage);
        $form.on('change.wc-variation-form', '.variations select', { variationForm: this }, this.onChange);
        $form.on('found_variation.wc-variation-form', { variationForm: this }, this.onFoundVariation);
        $form.on('check_variations.wc-variation-form', { variationForm: this }, this.onFindVariation);
        $form.on('update_variation_values.wc-variation-form', { variationForm: this }, this.onUpdateAttributes);

        // WOO VARIATION GALLERY CHANGES
        this.init($form);
    };

    // After Gallery Init
    VariationForm.prototype.afterGalleryInit = function ($form) {
        setTimeout(function () {
            // $form.trigger('check_variations');
            $form.trigger('wc_variation_form');
            $form.loading = false;
        }, 100);
    };

    // Variation form events
    VariationForm.prototype.init = function ($form) {
        var _this = this;

        var product_id = $form.data('product_id');
        if (this.useAjax) {
            wp.ajax.send('wvs_get_available_variations', {
                data: {
                    product_id: product_id
                },
                success: function success(data) {
                    $form.data('product_variations', data);
                    _this.useAjax = false;

                    // Init after gallery.
                    _this.afterGalleryInit($form);
                },
                error: function error(e) {
                    // Init after gallery.
                    _this.afterGalleryInit($form);
                    console.error('Variation not available on variation id ' + product_id + '.');
                }
            });
        } else {
            // Init after gallery.
            this.afterGalleryInit($form);
        }
    };

    /**
     * Reset all fields.
     */
    VariationForm.prototype.onReset = function (event) {
        event.preventDefault();
        event.data.variationForm.$attributeFields.val('').change();
        event.data.variationForm.$form.trigger('reset_data');
    };

    /**
     * Reload variation data from the DOM.
     */
    VariationForm.prototype.onReload = function (event) {
        var form = event.data.variationForm;
        form.variationData = form.$form.data('product_variations');
        form.useAjax = false === form.variationData;
        form.$form.trigger('check_variations');
    };

    /**
     * When a variation is hidden.
     */
    VariationForm.prototype.onHide = function (event) {
        event.preventDefault();
        event.data.variationForm.$form.find('.single_add_to_cart_button').removeClass('wc-variation-is-unavailable').addClass('disabled wc-variation-selection-needed');
        event.data.variationForm.$form.find('.woocommerce-variation-add-to-cart').removeClass('woocommerce-variation-add-to-cart-enabled').addClass('woocommerce-variation-add-to-cart-disabled');
    };

    /**
     * When a variation is shown.
     */
    VariationForm.prototype.onShow = function (event, variation, purchasable) {
        event.preventDefault();
        if (purchasable) {
            event.data.variationForm.$form.find('.single_add_to_cart_button').removeClass('disabled wc-variation-selection-needed wc-variation-is-unavailable');
            event.data.variationForm.$form.find('.woocommerce-variation-add-to-cart').removeClass('woocommerce-variation-add-to-cart-disabled').addClass('woocommerce-variation-add-to-cart-enabled');
        } else {
            event.data.variationForm.$form.find('.single_add_to_cart_button').removeClass('wc-variation-selection-needed').addClass('disabled wc-variation-is-unavailable');
            event.data.variationForm.$form.find('.woocommerce-variation-add-to-cart').removeClass('woocommerce-variation-add-to-cart-enabled').addClass('woocommerce-variation-add-to-cart-disabled');
        }
    };

    /**
     * When the cart button is pressed.
     */
    VariationForm.prototype.onAddToCart = function (event) {
        if ($(this).is('.disabled')) {
            event.preventDefault();

            if ($(this).is('.wc-variation-is-unavailable')) {
                window.alert(wc_add_to_cart_variation_params.i18n_unavailable_text);
            } else if ($(this).is('.wc-variation-selection-needed')) {
                window.alert(wc_add_to_cart_variation_params.i18n_make_a_selection_text);
            }
        }
    };

    /**
     * When displayed variation data is reset.
     */
    VariationForm.prototype.onResetDisplayedVariation = function (event) {
        var form = event.data.variationForm;
        form.$product.find('.product_meta').find('.sku').wc_reset_content();
        form.$product.find('.product_weight').wc_reset_content();
        form.$product.find('.product_dimensions').wc_reset_content();
        form.$form.trigger('reset_image');
        form.$singleVariation.slideUp(200).trigger('hide_variation');
    };

    /**
     * When the product image is reset.
     */
    VariationForm.prototype.onResetImage = function (event) {
        event.data.variationForm.$form.wc_variations_image_update(false);
    };

    /**
     * Looks for matching variations for current selected attributes.
     */
    VariationForm.prototype.onFindVariation = function (event) {
        var form = event.data.variationForm,
            attributes = form.getChosenAttributes(),
            currentAttributes = attributes.data;

        if (attributes.count === attributes.chosenCount) {
            if (form.useAjax) {
                if (form.xhr) {
                    form.xhr.abort();
                }
                form.$form.block({ message: null, overlayCSS: { background: '#fff', opacity: 0.6 } });
                currentAttributes.product_id = parseInt(form.$form.data('product_id'), 10);
                currentAttributes.custom_data = form.$form.data('custom_data');
                form.xhr = $.ajax({
                    url: wc_add_to_cart_variation_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_variation'),
                    type: 'POST',
                    data: currentAttributes,
                    success: function success(variation) {
                        if (variation) {
                            form.$form.trigger('found_variation', [variation]);
                        } else {
                            form.$form.trigger('reset_data');
                            attributes.chosenCount = 0;

                            if (!form.loading) {
                                form.$form.find('.single_variation').after('<p class="wc-no-matching-variations woocommerce-info">' + wc_add_to_cart_variation_params.i18n_no_matching_variations_text + '</p>');
                                form.$form.find('.wc-no-matching-variations').slideDown(200);
                            }
                        }
                    },
                    complete: function complete() {
                        form.$form.unblock();
                    }
                });
            } else {
                form.$form.trigger('update_variation_values');

                var matching_variations = form.findMatchingVariations(form.variationData, currentAttributes),
                    variation = matching_variations.shift();

                if (variation) {
                    form.$form.trigger('found_variation', [variation]);
                } else {
                    form.$form.trigger('reset_data');
                    attributes.chosenCount = 0;

                    if (!form.loading) {
                        form.$form.find('.single_variation').after('<p class="wc-no-matching-variations woocommerce-info">' + wc_add_to_cart_variation_params.i18n_no_matching_variations_text + '</p>');
                        form.$form.find('.wc-no-matching-variations').slideDown(200);
                    }
                }
            }
        } else {
            form.$form.trigger('update_variation_values');

            // WOO VARIATION GALLERY CHANGES
            if (!woo_variation_swatches_options.enable_single_variation_preview) {
                form.$form.trigger('reset_data');
            }
        }

        // Show reset link.
        form.toggleResetLink(attributes.chosenCount > 0);
    };

    /**
     * Triggered when a variation has been found which matches all attributes.
     */
    VariationForm.prototype.onFoundVariation = function (event, variation) {
        var form = event.data.variationForm,
            $sku = form.$product.find('.product_meta').find('.sku'),
            $weight = form.$product.find('.product_weight'),
            $dimensions = form.$product.find('.product_dimensions'),
            $qty = form.$singleVariationWrap.find('.quantity'),
            purchasable = true,
            variation_id = '',
            template = false,
            $template_html = '';

        if (variation.sku) {
            $sku.wc_set_content(variation.sku);
        } else {
            $sku.wc_reset_content();
        }

        if (variation.weight) {
            $weight.wc_set_content(variation.weight_html);
        } else {
            $weight.wc_reset_content();
        }

        if (variation.dimensions) {
            $dimensions.wc_set_content(variation.dimensions_html);
        } else {
            $dimensions.wc_reset_content();
        }

        form.$form.wc_variations_image_update(variation);

        if (!variation.variation_is_visible) {
            template = wp.template('unavailable-variation-template');
        } else {
            template = wp.template('variation-template');
            variation_id = variation.variation_id;
        }

        $template_html = template({
            variation: variation
        });
        $template_html = $template_html.replace('/*<![CDATA[*/', '');
        $template_html = $template_html.replace('/*]]>*/', '');

        form.$singleVariation.html($template_html);
        form.$form.find('input[name="variation_id"], input.variation_id').val(variation.variation_id).change();

        // Hide or show qty input
        if (variation.is_sold_individually === 'yes') {
            $qty.find('input.qty').val('1').attr('min', '1').attr('max', '');
            $qty.hide();
        } else {
            $qty.find('input.qty').attr('min', variation.min_qty).attr('max', variation.max_qty);
            $qty.show();
        }

        // Enable or disable the add to cart button
        if (!variation.is_purchasable || !variation.is_in_stock || !variation.variation_is_visible) {
            purchasable = false;
        }

        // Reveal
        if ($.trim(form.$singleVariation.text())) {
            form.$singleVariation.slideDown(200).trigger('show_variation', [variation, purchasable]);
        } else {
            form.$singleVariation.show().trigger('show_variation', [variation, purchasable]);
        }
    };

    /**
     * Triggered when an attribute field changes.
     */
    VariationForm.prototype.onChange = function (event) {
        var form = event.data.variationForm;

        form.$form.find('input[name="variation_id"], input.variation_id').val('').change();
        form.$form.find('.wc-no-matching-variations').remove();

        if (form.useAjax) {
            form.$form.trigger('check_variations');
        } else {
            form.$form.trigger('woocommerce_variation_select_change');
            form.$form.trigger('check_variations');
            $(this).blur();
        }

        // Custom event for when variation selection has been changed
        form.$form.trigger('woocommerce_variation_has_changed');
    };

    /**
     * Escape quotes in a string.
     * @param {string} string
     * @return {string}
     */
    VariationForm.prototype.addSlashes = function (string) {
        string = string.replace(/'/g, '\\\'');
        string = string.replace(/"/g, '\\\"');
        return string;
    };

    /**
     * Updates attributes in the DOM to show valid values.
     */
    VariationForm.prototype.onUpdateAttributes = function (event) {
        var form = event.data.variationForm,
            attributes = form.getChosenAttributes(),
            currentAttributes = attributes.data;

        if (form.useAjax) {
            return;
        }

        // Loop through selects and disable/enable options based on selections.
        form.$attributeFields.each(function (index, el) {
            var current_attr_select = $(el),
                current_attr_name = current_attr_select.data('attribute_name') || current_attr_select.attr('name'),
                show_option_none = $(el).data('show_option_none'),
                option_gt_filter = ':gt(0)',
                attached_options_count = 0,
                new_attr_select = $('<select/>'),
                selected_attr_val = current_attr_select.val() || '',
                selected_attr_val_valid = true;

            // Reference options set at first.
            if (!current_attr_select.data('attribute_html')) {
                var refSelect = current_attr_select.clone();

                refSelect.find('option').removeAttr('disabled attached').removeAttr('selected');

                current_attr_select.data('attribute_options', refSelect.find('option' + option_gt_filter).get()); // Legacy data attribute.
                current_attr_select.data('attribute_html', refSelect.html());
            }

            new_attr_select.html(current_attr_select.data('attribute_html'));

            // The attribute of this select field should not be taken into account when calculating its matching variations:
            // The constraints of this attribute are shaped by the values of the other attributes.
            var checkAttributes = $.extend(true, {}, currentAttributes);

            checkAttributes[current_attr_name] = '';

            var variations = form.findMatchingVariations(form.variationData, checkAttributes);

            // Loop through variations.
            for (var num in variations) {
                if (typeof variations[num] !== 'undefined') {
                    var variationAttributes = variations[num].attributes;

                    for (var attr_name in variationAttributes) {
                        if (variationAttributes.hasOwnProperty(attr_name)) {
                            var attr_val = variationAttributes[attr_name],
                                variation_active = '';

                            if (attr_name === current_attr_name) {
                                if (variations[num].variation_is_active) {
                                    variation_active = 'enabled';
                                }

                                if (attr_val) {
                                    // Decode entities and add slashes.
                                    attr_val = $('<div/>').html(attr_val).text();

                                    // Attach.
                                    new_attr_select.find('option[value="' + form.addSlashes(attr_val) + '"]').addClass('attached ' + variation_active);
                                } else {
                                    // Attach all apart from placeholder.
                                    new_attr_select.find('option:gt(0)').addClass('attached ' + variation_active);
                                }
                            }
                        }
                    }
                }
            }

            // Count available options.
            attached_options_count = new_attr_select.find('option.attached').length;

            // Check if current selection is in attached options.
            if (selected_attr_val && (attached_options_count === 0 || new_attr_select.find('option.attached.enabled[value="' + form.addSlashes(selected_attr_val) + '"]').length === 0)) {
                selected_attr_val_valid = false;
            }

            // Detach the placeholder if:
            // - Valid options exist.
            // - The current selection is non-empty.
            // - The current selection is valid.
            // - Placeholders are not set to be permanently visible.
            if (attached_options_count > 0 && selected_attr_val && selected_attr_val_valid && 'no' === show_option_none) {
                new_attr_select.find('option:first').remove();
                option_gt_filter = '';
            }

            // Detach unattached.
            new_attr_select.find('option' + option_gt_filter + ':not(.attached)').remove();

            // Finally, copy to DOM and set value.
            current_attr_select.html(new_attr_select.html());
            current_attr_select.find('option' + option_gt_filter + ':not(.enabled)').prop('disabled', true);

            // Choose selected value.
            if (selected_attr_val) {
                // If the previously selected value is no longer available, fall back to the placeholder (it's going to be there).
                if (selected_attr_val_valid) {
                    current_attr_select.val(selected_attr_val);
                } else {
                    current_attr_select.val('').change();
                }
            } else {
                current_attr_select.val(''); // No change event to prevent infinite loop.
            }
        });

        // Custom event for when variations have been updated.
        form.$form.trigger('woocommerce_update_variation_values');
    };

    /**
     * Get chosen attributes from form.
     * @return array
     */
    VariationForm.prototype.getChosenAttributes = function () {
        var data = {};
        var count = 0;
        var chosen = 0;

        this.$attributeFields.each(function () {
            var attribute_name = $(this).data('attribute_name') || $(this).attr('name');
            var value = $(this).val() || '';

            if (value.length > 0) {
                chosen++;
            }

            count++;
            data[attribute_name] = value;
        });

        return {
            'count': count,
            'chosenCount': chosen,
            'data': data
        };
    };

    /**
     * Find matching variations for attributes.
     */
    VariationForm.prototype.findMatchingVariations = function (variations, attributes) {
        var matching = [];
        for (var i = 0; i < variations.length; i++) {
            var variation = variations[i];

            if (this.isMatch(variation.attributes, attributes)) {
                matching.push(variation);
            }
        }
        return matching;
    };

    /**
     * See if attributes match.
     * @return {Boolean}
     */
    VariationForm.prototype.isMatch = function (variation_attributes, attributes) {
        var match = true;
        for (var attr_name in variation_attributes) {
            if (variation_attributes.hasOwnProperty(attr_name)) {
                var val1 = variation_attributes[attr_name];
                var val2 = attributes[attr_name];
                if (val1 !== undefined && val2 !== undefined && val1.length !== 0 && val2.length !== 0 && val1 !== val2) {
                    match = false;
                }
            }
        }
        return match;
    };

    /**
     * Show or hide the reset link.
     */
    VariationForm.prototype.toggleResetLink = function (on) {
        if (on) {
            if (this.$resetVariations.css('visibility') === 'hidden') {
                this.$resetVariations.css('visibility', 'visible').hide().fadeIn();
            }
        } else {
            this.$resetVariations.css('visibility', 'hidden');
        }
    };

    /**
     * Function to call wc_variation_form on jquery selector.
     */
    $.fn.wc_variation_form = function () {
        new VariationForm(this);
        return this;
    };

    /**
     * Stores the default text for an element so it can be reset later
     */
    $.fn.wc_set_content = function (content) {
        if (undefined === this.attr('data-o_content')) {
            this.attr('data-o_content', this.text());
        }
        this.text(content);
    };

    /**
     * Stores the default text for an element so it can be reset later
     */
    $.fn.wc_reset_content = function () {
        if (undefined !== this.attr('data-o_content')) {
            this.text(this.attr('data-o_content'));
        }
    };

    /**
     * Stores a default attribute for an element so it can be reset later
     */
    $.fn.wc_set_variation_attr = function (attr, value) {
        if (undefined === this.attr('data-o_' + attr)) {
            this.attr('data-o_' + attr, !this.attr(attr) ? '' : this.attr(attr));
        }
        if (false === value) {
            this.removeAttr(attr);
        } else {
            this.attr(attr, value);
        }
    };

    /**
     * Reset a default attribute for an element so it can be reset later
     */
    $.fn.wc_reset_variation_attr = function (attr) {
        if (undefined !== this.attr('data-o_' + attr)) {
            this.attr(attr, this.attr('data-o_' + attr));
        }
    };

    /**
     * Reset the slide position if the variation has a different image than the current one
     */
    $.fn.wc_maybe_trigger_slide_position_reset = function (variation) {
        var $form = $(this),
            $product = $form.closest('.product'),
            $product_gallery = $product.find('.images'),
            reset_slide_position = false,
            new_image_id = variation && variation.image_id ? variation.image_id : '';

        if ($form.attr('current-image') !== new_image_id) {
            reset_slide_position = true;
        }

        $form.attr('current-image', new_image_id);

        if (reset_slide_position) {
            $product_gallery.trigger('woocommerce_gallery_reset_slide_position');
        }
    };

    /**
     * Sets product images for the chosen variation
     */
    $.fn.wc_variations_image_update = function (variation) {
        var $form = this,
            $product = $form.closest('.product'),
            $product_gallery = $product.find('.images'),
            $gallery_nav = $product.find('.flex-control-nav'),
            $gallery_img = $gallery_nav.find('li:eq(0) img'),
            $product_img_wrap = $product_gallery.find('.woocommerce-product-gallery__image, .woocommerce-product-gallery__image--placeholder').eq(0),
            $product_img = $product_img_wrap.find('.wp-post-image'),
            $product_link = $product_img_wrap.find('a').eq(0);

        if (variation && variation.image && variation.image.src && variation.image.src.length > 1) {
            // See if the gallery has an image with the same original src as the image we want to switch to.
            var galleryHasImage = $gallery_nav.find('li img[data-o_src="' + variation.image.gallery_thumbnail_src + '"]').length > 0;

            // If the gallery has the image, reset the images. We'll scroll to the correct one.
            if (galleryHasImage) {
                $form.wc_variations_image_reset();
            }

            // See if gallery has a matching image we can slide to.
            var slideToImage = $gallery_nav.find('li img[src="' + variation.image.gallery_thumbnail_src + '"]');

            if (slideToImage.length > 0) {
                slideToImage.trigger('click');
                $form.attr('current-image', variation.image_id);
                window.setTimeout(function () {
                    $(window).trigger('resize');
                    $product_gallery.trigger('woocommerce_gallery_init_zoom');
                }, 20);
                return;
            }

            $product_img.wc_set_variation_attr('src', variation.image.src);
            $product_img.wc_set_variation_attr('height', variation.image.src_h);
            $product_img.wc_set_variation_attr('width', variation.image.src_w);
            $product_img.wc_set_variation_attr('srcset', variation.image.srcset);
            $product_img.wc_set_variation_attr('sizes', variation.image.sizes);
            $product_img.wc_set_variation_attr('title', variation.image.title);
            $product_img.wc_set_variation_attr('alt', variation.image.alt);
            $product_img.wc_set_variation_attr('data-src', variation.image.full_src);
            $product_img.wc_set_variation_attr('data-large_image', variation.image.full_src);
            $product_img.wc_set_variation_attr('data-large_image_width', variation.image.full_src_w);
            $product_img.wc_set_variation_attr('data-large_image_height', variation.image.full_src_h);
            $product_img_wrap.wc_set_variation_attr('data-thumb', variation.image.src);
            $gallery_img.wc_set_variation_attr('src', variation.image.gallery_thumbnail_src);
            $product_link.wc_set_variation_attr('href', variation.image.full_src);
        } else {
            $form.wc_variations_image_reset();
        }

        window.setTimeout(function () {
            $(window).trigger('resize');
            $form.wc_maybe_trigger_slide_position_reset(variation);
            $product_gallery.trigger('woocommerce_gallery_init_zoom');
        }, 20);
    };

    /**
     * Reset main image to defaults.
     */
    $.fn.wc_variations_image_reset = function () {
        var $form = this,
            $product = $form.closest('.product'),
            $product_gallery = $product.find('.images'),
            $gallery_nav = $product.find('.flex-control-nav'),
            $gallery_img = $gallery_nav.find('li:eq(0) img'),
            $product_img_wrap = $product_gallery.find('.woocommerce-product-gallery__image, .woocommerce-product-gallery__image--placeholder').eq(0),
            $product_img = $product_img_wrap.find('.wp-post-image'),
            $product_link = $product_img_wrap.find('a').eq(0);

        $product_img.wc_reset_variation_attr('src');
        $product_img.wc_reset_variation_attr('width');
        $product_img.wc_reset_variation_attr('height');
        $product_img.wc_reset_variation_attr('srcset');
        $product_img.wc_reset_variation_attr('sizes');
        $product_img.wc_reset_variation_attr('title');
        $product_img.wc_reset_variation_attr('alt');
        $product_img.wc_reset_variation_attr('data-src');
        $product_img.wc_reset_variation_attr('data-large_image');
        $product_img.wc_reset_variation_attr('data-large_image_width');
        $product_img.wc_reset_variation_attr('data-large_image_height');
        $product_img_wrap.wc_reset_variation_attr('data-thumb');
        $gallery_img.wc_reset_variation_attr('src');
        $product_link.wc_reset_variation_attr('href');
    };

    $(function () {
        if (typeof wc_add_to_cart_variation_params !== 'undefined') {
            $('.variations_form').each(function () {
                $(this).wc_variation_form();
            });
        }
    });

    /**
     * Matches inline variation objects to chosen attributes
     * @deprecated 2.6.9
     * @type {Object}
     */
    var wc_variation_form_matcher = {
        find_matching_variations: function find_matching_variations(product_variations, settings) {
            var matching = [];
            for (var i = 0; i < product_variations.length; i++) {
                var variation = product_variations[i];

                if (wc_variation_form_matcher.variations_match(variation.attributes, settings)) {
                    matching.push(variation);
                }
            }
            return matching;
        },
        variations_match: function variations_match(attrs1, attrs2) {
            var match = true;
            for (var attr_name in attrs1) {
                if (attrs1.hasOwnProperty(attr_name)) {
                    var val1 = attrs1[attr_name];
                    var val2 = attrs2[attr_name];
                    if (val1 !== undefined && val2 !== undefined && val1.length !== 0 && val2.length !== 0 && val1 !== val2) {
                        match = false;
                    }
                }
            }
            return match;
        }
    };
})(jQuery, window, document);

/***/ }),
/* 2 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 3 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 4 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiYXNzZXRzL2pzL2FkZC10by1jYXJ0LXZhcmlhdGlvbi5qcyIsInNvdXJjZXMiOlsid2VicGFjazovLy93ZWJwYWNrL2Jvb3RzdHJhcCBkYjU0MTk1ZWEyMzI2YWI2NTM3NiIsIndlYnBhY2s6Ly8vc3JjL2pzL2FkZC10by1jYXJ0LXZhcmlhdGlvbi5qcyIsIndlYnBhY2s6Ly8vLi9zcmMvc2Nzcy9mcm9udGVuZC5zY3NzPzQ0NWEiLCJ3ZWJwYWNrOi8vLy4vc3JjL3Njc3MvdGhlbWUtb3ZlcnJpZGUuc2Nzcz8wODdmIiwid2VicGFjazovLy8uL3NyYy9zY3NzL2JhY2tlbmQuc2Nzcz9mYTY3Il0sInNvdXJjZXNDb250ZW50IjpbIiBcdC8vIFRoZSBtb2R1bGUgY2FjaGVcbiBcdHZhciBpbnN0YWxsZWRNb2R1bGVzID0ge307XG5cbiBcdC8vIFRoZSByZXF1aXJlIGZ1bmN0aW9uXG4gXHRmdW5jdGlvbiBfX3dlYnBhY2tfcmVxdWlyZV9fKG1vZHVsZUlkKSB7XG5cbiBcdFx0Ly8gQ2hlY2sgaWYgbW9kdWxlIGlzIGluIGNhY2hlXG4gXHRcdGlmKGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdKSB7XG4gXHRcdFx0cmV0dXJuIGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdLmV4cG9ydHM7XG4gXHRcdH1cbiBcdFx0Ly8gQ3JlYXRlIGEgbmV3IG1vZHVsZSAoYW5kIHB1dCBpdCBpbnRvIHRoZSBjYWNoZSlcbiBcdFx0dmFyIG1vZHVsZSA9IGluc3RhbGxlZE1vZHVsZXNbbW9kdWxlSWRdID0ge1xuIFx0XHRcdGk6IG1vZHVsZUlkLFxuIFx0XHRcdGw6IGZhbHNlLFxuIFx0XHRcdGV4cG9ydHM6IHt9XG4gXHRcdH07XG5cbiBcdFx0Ly8gRXhlY3V0ZSB0aGUgbW9kdWxlIGZ1bmN0aW9uXG4gXHRcdG1vZHVsZXNbbW9kdWxlSWRdLmNhbGwobW9kdWxlLmV4cG9ydHMsIG1vZHVsZSwgbW9kdWxlLmV4cG9ydHMsIF9fd2VicGFja19yZXF1aXJlX18pO1xuXG4gXHRcdC8vIEZsYWcgdGhlIG1vZHVsZSBhcyBsb2FkZWRcbiBcdFx0bW9kdWxlLmwgPSB0cnVlO1xuXG4gXHRcdC8vIFJldHVybiB0aGUgZXhwb3J0cyBvZiB0aGUgbW9kdWxlXG4gXHRcdHJldHVybiBtb2R1bGUuZXhwb3J0cztcbiBcdH1cblxuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZXMgb2JqZWN0IChfX3dlYnBhY2tfbW9kdWxlc19fKVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5tID0gbW9kdWxlcztcblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGUgY2FjaGVcbiBcdF9fd2VicGFja19yZXF1aXJlX18uYyA9IGluc3RhbGxlZE1vZHVsZXM7XG5cbiBcdC8vIGRlZmluZSBnZXR0ZXIgZnVuY3Rpb24gZm9yIGhhcm1vbnkgZXhwb3J0c1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kID0gZnVuY3Rpb24oZXhwb3J0cywgbmFtZSwgZ2V0dGVyKSB7XG4gXHRcdGlmKCFfX3dlYnBhY2tfcmVxdWlyZV9fLm8oZXhwb3J0cywgbmFtZSkpIHtcbiBcdFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgbmFtZSwge1xuIFx0XHRcdFx0Y29uZmlndXJhYmxlOiBmYWxzZSxcbiBcdFx0XHRcdGVudW1lcmFibGU6IHRydWUsXG4gXHRcdFx0XHRnZXQ6IGdldHRlclxuIFx0XHRcdH0pO1xuIFx0XHR9XG4gXHR9O1xuXG4gXHQvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5uID0gZnVuY3Rpb24obW9kdWxlKSB7XG4gXHRcdHZhciBnZXR0ZXIgPSBtb2R1bGUgJiYgbW9kdWxlLl9fZXNNb2R1bGUgP1xuIFx0XHRcdGZ1bmN0aW9uIGdldERlZmF1bHQoKSB7IHJldHVybiBtb2R1bGVbJ2RlZmF1bHQnXTsgfSA6XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0TW9kdWxlRXhwb3J0cygpIHsgcmV0dXJuIG1vZHVsZTsgfTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kKGdldHRlciwgJ2EnLCBnZXR0ZXIpO1xuIFx0XHRyZXR1cm4gZ2V0dGVyO1xuIFx0fTtcblxuIFx0Ly8gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSBmdW5jdGlvbihvYmplY3QsIHByb3BlcnR5KSB7IHJldHVybiBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqZWN0LCBwcm9wZXJ0eSk7IH07XG5cbiBcdC8vIF9fd2VicGFja19wdWJsaWNfcGF0aF9fXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnAgPSBcIlwiO1xuXG4gXHQvLyBMb2FkIGVudHJ5IG1vZHVsZSBhbmQgcmV0dXJuIGV4cG9ydHNcbiBcdHJldHVybiBfX3dlYnBhY2tfcmVxdWlyZV9fKF9fd2VicGFja19yZXF1aXJlX18ucyA9IDApO1xuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIHdlYnBhY2svYm9vdHN0cmFwIGRiNTQxOTVlYTIzMjZhYjY1Mzc2IiwiLypnbG9iYWwgd2NfYWRkX3RvX2NhcnRfdmFyaWF0aW9uX3BhcmFtcywgd29vX3ZhcmlhdGlvbl9zd2F0Y2hlc19vcHRpb25zICovXG47KGZ1bmN0aW9uICgkLCB3aW5kb3csIGRvY3VtZW50LCB1bmRlZmluZWQpIHtcbiAgICAvKipcbiAgICAgKiBWYXJpYXRpb25Gb3JtIGNsYXNzIHdoaWNoIGhhbmRsZXMgdmFyaWF0aW9uIGZvcm1zIGFuZCBhdHRyaWJ1dGVzLlxuICAgICAqL1xuICAgIHZhciBWYXJpYXRpb25Gb3JtID0gZnVuY3Rpb24gKCRmb3JtKSB7XG4gICAgICAgIHRoaXMuJGZvcm0gICAgICAgICAgICAgICAgPSAkZm9ybTtcbiAgICAgICAgdGhpcy4kYXR0cmlidXRlRmllbGRzICAgICA9ICRmb3JtLmZpbmQoJy52YXJpYXRpb25zIHNlbGVjdCcpO1xuICAgICAgICB0aGlzLiRzaW5nbGVWYXJpYXRpb24gICAgID0gJGZvcm0uZmluZCgnLnNpbmdsZV92YXJpYXRpb24nKTtcbiAgICAgICAgdGhpcy4kc2luZ2xlVmFyaWF0aW9uV3JhcCA9ICRmb3JtLmZpbmQoJy5zaW5nbGVfdmFyaWF0aW9uX3dyYXAnKTtcbiAgICAgICAgdGhpcy4kcmVzZXRWYXJpYXRpb25zICAgICA9ICRmb3JtLmZpbmQoJy5yZXNldF92YXJpYXRpb25zJyk7XG4gICAgICAgIHRoaXMuJHByb2R1Y3QgICAgICAgICAgICAgPSAkZm9ybS5jbG9zZXN0KCcucHJvZHVjdCcpO1xuICAgICAgICB0aGlzLnZhcmlhdGlvbkRhdGEgICAgICAgID0gJGZvcm0uZGF0YSgncHJvZHVjdF92YXJpYXRpb25zJyk7XG4gICAgICAgIHRoaXMudXNlQWpheCAgICAgICAgICAgICAgPSBmYWxzZSA9PT0gdGhpcy52YXJpYXRpb25EYXRhO1xuICAgICAgICB0aGlzLnhociAgICAgICAgICAgICAgICAgID0gZmFsc2U7XG4gICAgICAgIHRoaXMubG9hZGluZyAgICAgICAgICAgICAgPSB0cnVlO1xuXG4gICAgICAgIC8vIEluaXRpYWwgc3RhdGUuXG4gICAgICAgIHRoaXMuJHNpbmdsZVZhcmlhdGlvbldyYXAuc2hvdygpO1xuICAgICAgICB0aGlzLiRmb3JtLm9mZignLndjLXZhcmlhdGlvbi1mb3JtJyk7XG5cbiAgICAgICAgLy8gTWV0aG9kcy5cbiAgICAgICAgdGhpcy5nZXRDaG9zZW5BdHRyaWJ1dGVzICAgID0gdGhpcy5nZXRDaG9zZW5BdHRyaWJ1dGVzLmJpbmQodGhpcyk7XG4gICAgICAgIHRoaXMuZmluZE1hdGNoaW5nVmFyaWF0aW9ucyA9IHRoaXMuZmluZE1hdGNoaW5nVmFyaWF0aW9ucy5iaW5kKHRoaXMpO1xuICAgICAgICB0aGlzLmlzTWF0Y2ggICAgICAgICAgICAgICAgPSB0aGlzLmlzTWF0Y2guYmluZCh0aGlzKTtcbiAgICAgICAgdGhpcy50b2dnbGVSZXNldExpbmsgICAgICAgID0gdGhpcy50b2dnbGVSZXNldExpbmsuYmluZCh0aGlzKTtcblxuICAgICAgICAvLyBFdmVudHMuXG4gICAgICAgICRmb3JtLm9uKCdjbGljay53Yy12YXJpYXRpb24tZm9ybScsICcucmVzZXRfdmFyaWF0aW9ucycsIHt2YXJpYXRpb25Gb3JtIDogdGhpc30sIHRoaXMub25SZXNldCk7XG4gICAgICAgICRmb3JtLm9uKCdyZWxvYWRfcHJvZHVjdF92YXJpYXRpb25zJywge3ZhcmlhdGlvbkZvcm0gOiB0aGlzfSwgdGhpcy5vblJlbG9hZCk7XG4gICAgICAgICRmb3JtLm9uKCdoaWRlX3ZhcmlhdGlvbicsIHt2YXJpYXRpb25Gb3JtIDogdGhpc30sIHRoaXMub25IaWRlKTtcbiAgICAgICAgJGZvcm0ub24oJ3Nob3dfdmFyaWF0aW9uJywge3ZhcmlhdGlvbkZvcm0gOiB0aGlzfSwgdGhpcy5vblNob3cpO1xuICAgICAgICAkZm9ybS5vbignY2xpY2snLCAnLnNpbmdsZV9hZGRfdG9fY2FydF9idXR0b24nLCB7dmFyaWF0aW9uRm9ybSA6IHRoaXN9LCB0aGlzLm9uQWRkVG9DYXJ0KTtcbiAgICAgICAgJGZvcm0ub24oJ3Jlc2V0X2RhdGEnLCB7dmFyaWF0aW9uRm9ybSA6IHRoaXN9LCB0aGlzLm9uUmVzZXREaXNwbGF5ZWRWYXJpYXRpb24pO1xuICAgICAgICAkZm9ybS5vbigncmVzZXRfaW1hZ2UnLCB7dmFyaWF0aW9uRm9ybSA6IHRoaXN9LCB0aGlzLm9uUmVzZXRJbWFnZSk7XG4gICAgICAgICRmb3JtLm9uKCdjaGFuZ2Uud2MtdmFyaWF0aW9uLWZvcm0nLCAnLnZhcmlhdGlvbnMgc2VsZWN0Jywge3ZhcmlhdGlvbkZvcm0gOiB0aGlzfSwgdGhpcy5vbkNoYW5nZSk7XG4gICAgICAgICRmb3JtLm9uKCdmb3VuZF92YXJpYXRpb24ud2MtdmFyaWF0aW9uLWZvcm0nLCB7dmFyaWF0aW9uRm9ybSA6IHRoaXN9LCB0aGlzLm9uRm91bmRWYXJpYXRpb24pO1xuICAgICAgICAkZm9ybS5vbignY2hlY2tfdmFyaWF0aW9ucy53Yy12YXJpYXRpb24tZm9ybScsIHt2YXJpYXRpb25Gb3JtIDogdGhpc30sIHRoaXMub25GaW5kVmFyaWF0aW9uKTtcbiAgICAgICAgJGZvcm0ub24oJ3VwZGF0ZV92YXJpYXRpb25fdmFsdWVzLndjLXZhcmlhdGlvbi1mb3JtJywge3ZhcmlhdGlvbkZvcm0gOiB0aGlzfSwgdGhpcy5vblVwZGF0ZUF0dHJpYnV0ZXMpO1xuXG4gICAgICAgIC8vIFdPTyBWQVJJQVRJT04gR0FMTEVSWSBDSEFOR0VTXG4gICAgICAgIHRoaXMuaW5pdCgkZm9ybSlcbiAgICB9O1xuXG4gICAgLy8gQWZ0ZXIgR2FsbGVyeSBJbml0XG4gICAgVmFyaWF0aW9uRm9ybS5wcm90b3R5cGUuYWZ0ZXJHYWxsZXJ5SW5pdCA9IGZ1bmN0aW9uICgkZm9ybSkge1xuICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIC8vICRmb3JtLnRyaWdnZXIoJ2NoZWNrX3ZhcmlhdGlvbnMnKTtcbiAgICAgICAgICAgICRmb3JtLnRyaWdnZXIoJ3djX3ZhcmlhdGlvbl9mb3JtJyk7XG4gICAgICAgICAgICAkZm9ybS5sb2FkaW5nID0gZmFsc2U7XG4gICAgICAgIH0sIDEwMCk7XG4gICAgfTtcblxuICAgIC8vIFZhcmlhdGlvbiBmb3JtIGV2ZW50c1xuICAgIFZhcmlhdGlvbkZvcm0ucHJvdG90eXBlLmluaXQgPSBmdW5jdGlvbiAoJGZvcm0pIHtcblxuICAgICAgICBsZXQgcHJvZHVjdF9pZCA9ICRmb3JtLmRhdGEoJ3Byb2R1Y3RfaWQnKTtcbiAgICAgICAgaWYgKHRoaXMudXNlQWpheCkge1xuICAgICAgICAgICAgd3AuYWpheC5zZW5kKCd3dnNfZ2V0X2F2YWlsYWJsZV92YXJpYXRpb25zJywge1xuICAgICAgICAgICAgICAgIGRhdGEgICAgOiB7XG4gICAgICAgICAgICAgICAgICAgIHByb2R1Y3RfaWRcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIHN1Y2Nlc3MgOiAoZGF0YSkgPT4ge1xuICAgICAgICAgICAgICAgICAgICAkZm9ybS5kYXRhKCdwcm9kdWN0X3ZhcmlhdGlvbnMnLCBkYXRhKTtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy51c2VBamF4ID0gZmFsc2U7XG5cbiAgICAgICAgICAgICAgICAgICAgLy8gSW5pdCBhZnRlciBnYWxsZXJ5LlxuICAgICAgICAgICAgICAgICAgICB0aGlzLmFmdGVyR2FsbGVyeUluaXQoJGZvcm0pO1xuXG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICBlcnJvciAgIDogKGUpID0+IHtcbiAgICAgICAgICAgICAgICAgICAgLy8gSW5pdCBhZnRlciBnYWxsZXJ5LlxuICAgICAgICAgICAgICAgICAgICB0aGlzLmFmdGVyR2FsbGVyeUluaXQoJGZvcm0pO1xuICAgICAgICAgICAgICAgICAgICBjb25zb2xlLmVycm9yKGBWYXJpYXRpb24gbm90IGF2YWlsYWJsZSBvbiB2YXJpYXRpb24gaWQgJHtwcm9kdWN0X2lkfS5gKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgIC8vIEluaXQgYWZ0ZXIgZ2FsbGVyeS5cbiAgICAgICAgICAgIHRoaXMuYWZ0ZXJHYWxsZXJ5SW5pdCgkZm9ybSk7XG4gICAgICAgIH1cbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogUmVzZXQgYWxsIGZpZWxkcy5cbiAgICAgKi9cbiAgICBWYXJpYXRpb25Gb3JtLnByb3RvdHlwZS5vblJlc2V0ID0gZnVuY3Rpb24gKGV2ZW50KSB7XG4gICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgIGV2ZW50LmRhdGEudmFyaWF0aW9uRm9ybS4kYXR0cmlidXRlRmllbGRzLnZhbCgnJykuY2hhbmdlKCk7XG4gICAgICAgIGV2ZW50LmRhdGEudmFyaWF0aW9uRm9ybS4kZm9ybS50cmlnZ2VyKCdyZXNldF9kYXRhJyk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIFJlbG9hZCB2YXJpYXRpb24gZGF0YSBmcm9tIHRoZSBET00uXG4gICAgICovXG4gICAgVmFyaWF0aW9uRm9ybS5wcm90b3R5cGUub25SZWxvYWQgPSBmdW5jdGlvbiAoZXZlbnQpIHtcbiAgICAgICAgdmFyIGZvcm0gICAgICAgICAgID0gZXZlbnQuZGF0YS52YXJpYXRpb25Gb3JtO1xuICAgICAgICBmb3JtLnZhcmlhdGlvbkRhdGEgPSBmb3JtLiRmb3JtLmRhdGEoJ3Byb2R1Y3RfdmFyaWF0aW9ucycpO1xuICAgICAgICBmb3JtLnVzZUFqYXggICAgICAgPSBmYWxzZSA9PT0gZm9ybS52YXJpYXRpb25EYXRhO1xuICAgICAgICBmb3JtLiRmb3JtLnRyaWdnZXIoJ2NoZWNrX3ZhcmlhdGlvbnMnKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogV2hlbiBhIHZhcmlhdGlvbiBpcyBoaWRkZW4uXG4gICAgICovXG4gICAgVmFyaWF0aW9uRm9ybS5wcm90b3R5cGUub25IaWRlID0gZnVuY3Rpb24gKGV2ZW50KSB7XG4gICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgIGV2ZW50LmRhdGEudmFyaWF0aW9uRm9ybS4kZm9ybS5maW5kKCcuc2luZ2xlX2FkZF90b19jYXJ0X2J1dHRvbicpLnJlbW92ZUNsYXNzKCd3Yy12YXJpYXRpb24taXMtdW5hdmFpbGFibGUnKS5hZGRDbGFzcygnZGlzYWJsZWQgd2MtdmFyaWF0aW9uLXNlbGVjdGlvbi1uZWVkZWQnKTtcbiAgICAgICAgZXZlbnQuZGF0YS52YXJpYXRpb25Gb3JtLiRmb3JtLmZpbmQoJy53b29jb21tZXJjZS12YXJpYXRpb24tYWRkLXRvLWNhcnQnKS5yZW1vdmVDbGFzcygnd29vY29tbWVyY2UtdmFyaWF0aW9uLWFkZC10by1jYXJ0LWVuYWJsZWQnKS5hZGRDbGFzcygnd29vY29tbWVyY2UtdmFyaWF0aW9uLWFkZC10by1jYXJ0LWRpc2FibGVkJyk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIFdoZW4gYSB2YXJpYXRpb24gaXMgc2hvd24uXG4gICAgICovXG4gICAgVmFyaWF0aW9uRm9ybS5wcm90b3R5cGUub25TaG93ID0gZnVuY3Rpb24gKGV2ZW50LCB2YXJpYXRpb24sIHB1cmNoYXNhYmxlKSB7XG4gICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG4gICAgICAgIGlmIChwdXJjaGFzYWJsZSkge1xuICAgICAgICAgICAgZXZlbnQuZGF0YS52YXJpYXRpb25Gb3JtLiRmb3JtLmZpbmQoJy5zaW5nbGVfYWRkX3RvX2NhcnRfYnV0dG9uJykucmVtb3ZlQ2xhc3MoJ2Rpc2FibGVkIHdjLXZhcmlhdGlvbi1zZWxlY3Rpb24tbmVlZGVkIHdjLXZhcmlhdGlvbi1pcy11bmF2YWlsYWJsZScpO1xuICAgICAgICAgICAgZXZlbnQuZGF0YS52YXJpYXRpb25Gb3JtLiRmb3JtLmZpbmQoJy53b29jb21tZXJjZS12YXJpYXRpb24tYWRkLXRvLWNhcnQnKS5yZW1vdmVDbGFzcygnd29vY29tbWVyY2UtdmFyaWF0aW9uLWFkZC10by1jYXJ0LWRpc2FibGVkJykuYWRkQ2xhc3MoJ3dvb2NvbW1lcmNlLXZhcmlhdGlvbi1hZGQtdG8tY2FydC1lbmFibGVkJyk7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICBldmVudC5kYXRhLnZhcmlhdGlvbkZvcm0uJGZvcm0uZmluZCgnLnNpbmdsZV9hZGRfdG9fY2FydF9idXR0b24nKS5yZW1vdmVDbGFzcygnd2MtdmFyaWF0aW9uLXNlbGVjdGlvbi1uZWVkZWQnKS5hZGRDbGFzcygnZGlzYWJsZWQgd2MtdmFyaWF0aW9uLWlzLXVuYXZhaWxhYmxlJyk7XG4gICAgICAgICAgICBldmVudC5kYXRhLnZhcmlhdGlvbkZvcm0uJGZvcm0uZmluZCgnLndvb2NvbW1lcmNlLXZhcmlhdGlvbi1hZGQtdG8tY2FydCcpLnJlbW92ZUNsYXNzKCd3b29jb21tZXJjZS12YXJpYXRpb24tYWRkLXRvLWNhcnQtZW5hYmxlZCcpLmFkZENsYXNzKCd3b29jb21tZXJjZS12YXJpYXRpb24tYWRkLXRvLWNhcnQtZGlzYWJsZWQnKTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBXaGVuIHRoZSBjYXJ0IGJ1dHRvbiBpcyBwcmVzc2VkLlxuICAgICAqL1xuICAgIFZhcmlhdGlvbkZvcm0ucHJvdG90eXBlLm9uQWRkVG9DYXJ0ID0gZnVuY3Rpb24gKGV2ZW50KSB7XG4gICAgICAgIGlmICgkKHRoaXMpLmlzKCcuZGlzYWJsZWQnKSkge1xuICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcblxuICAgICAgICAgICAgaWYgKCQodGhpcykuaXMoJy53Yy12YXJpYXRpb24taXMtdW5hdmFpbGFibGUnKSkge1xuICAgICAgICAgICAgICAgIHdpbmRvdy5hbGVydCh3Y19hZGRfdG9fY2FydF92YXJpYXRpb25fcGFyYW1zLmkxOG5fdW5hdmFpbGFibGVfdGV4dCk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIGlmICgkKHRoaXMpLmlzKCcud2MtdmFyaWF0aW9uLXNlbGVjdGlvbi1uZWVkZWQnKSkge1xuICAgICAgICAgICAgICAgIHdpbmRvdy5hbGVydCh3Y19hZGRfdG9fY2FydF92YXJpYXRpb25fcGFyYW1zLmkxOG5fbWFrZV9hX3NlbGVjdGlvbl90ZXh0KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBXaGVuIGRpc3BsYXllZCB2YXJpYXRpb24gZGF0YSBpcyByZXNldC5cbiAgICAgKi9cbiAgICBWYXJpYXRpb25Gb3JtLnByb3RvdHlwZS5vblJlc2V0RGlzcGxheWVkVmFyaWF0aW9uID0gZnVuY3Rpb24gKGV2ZW50KSB7XG4gICAgICAgIHZhciBmb3JtID0gZXZlbnQuZGF0YS52YXJpYXRpb25Gb3JtO1xuICAgICAgICBmb3JtLiRwcm9kdWN0LmZpbmQoJy5wcm9kdWN0X21ldGEnKS5maW5kKCcuc2t1Jykud2NfcmVzZXRfY29udGVudCgpO1xuICAgICAgICBmb3JtLiRwcm9kdWN0LmZpbmQoJy5wcm9kdWN0X3dlaWdodCcpLndjX3Jlc2V0X2NvbnRlbnQoKTtcbiAgICAgICAgZm9ybS4kcHJvZHVjdC5maW5kKCcucHJvZHVjdF9kaW1lbnNpb25zJykud2NfcmVzZXRfY29udGVudCgpO1xuICAgICAgICBmb3JtLiRmb3JtLnRyaWdnZXIoJ3Jlc2V0X2ltYWdlJyk7XG4gICAgICAgIGZvcm0uJHNpbmdsZVZhcmlhdGlvbi5zbGlkZVVwKDIwMCkudHJpZ2dlcignaGlkZV92YXJpYXRpb24nKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogV2hlbiB0aGUgcHJvZHVjdCBpbWFnZSBpcyByZXNldC5cbiAgICAgKi9cbiAgICBWYXJpYXRpb25Gb3JtLnByb3RvdHlwZS5vblJlc2V0SW1hZ2UgPSBmdW5jdGlvbiAoZXZlbnQpIHtcbiAgICAgICAgZXZlbnQuZGF0YS52YXJpYXRpb25Gb3JtLiRmb3JtLndjX3ZhcmlhdGlvbnNfaW1hZ2VfdXBkYXRlKGZhbHNlKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogTG9va3MgZm9yIG1hdGNoaW5nIHZhcmlhdGlvbnMgZm9yIGN1cnJlbnQgc2VsZWN0ZWQgYXR0cmlidXRlcy5cbiAgICAgKi9cbiAgICBWYXJpYXRpb25Gb3JtLnByb3RvdHlwZS5vbkZpbmRWYXJpYXRpb24gPSBmdW5jdGlvbiAoZXZlbnQpIHtcbiAgICAgICAgdmFyIGZvcm0gICAgICAgICAgICAgID0gZXZlbnQuZGF0YS52YXJpYXRpb25Gb3JtLFxuICAgICAgICAgICAgYXR0cmlidXRlcyAgICAgICAgPSBmb3JtLmdldENob3NlbkF0dHJpYnV0ZXMoKSxcbiAgICAgICAgICAgIGN1cnJlbnRBdHRyaWJ1dGVzID0gYXR0cmlidXRlcy5kYXRhO1xuXG4gICAgICAgIGlmIChhdHRyaWJ1dGVzLmNvdW50ID09PSBhdHRyaWJ1dGVzLmNob3NlbkNvdW50KSB7XG4gICAgICAgICAgICBpZiAoZm9ybS51c2VBamF4KSB7XG4gICAgICAgICAgICAgICAgaWYgKGZvcm0ueGhyKSB7XG4gICAgICAgICAgICAgICAgICAgIGZvcm0ueGhyLmFib3J0KCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGZvcm0uJGZvcm0uYmxvY2soe21lc3NhZ2UgOiBudWxsLCBvdmVybGF5Q1NTIDoge2JhY2tncm91bmQgOiAnI2ZmZicsIG9wYWNpdHkgOiAwLjZ9fSk7XG4gICAgICAgICAgICAgICAgY3VycmVudEF0dHJpYnV0ZXMucHJvZHVjdF9pZCAgPSBwYXJzZUludChmb3JtLiRmb3JtLmRhdGEoJ3Byb2R1Y3RfaWQnKSwgMTApO1xuICAgICAgICAgICAgICAgIGN1cnJlbnRBdHRyaWJ1dGVzLmN1c3RvbV9kYXRhID0gZm9ybS4kZm9ybS5kYXRhKCdjdXN0b21fZGF0YScpO1xuICAgICAgICAgICAgICAgIGZvcm0ueGhyICAgICAgICAgICAgICAgICAgICAgID0gJC5hamF4KHtcbiAgICAgICAgICAgICAgICAgICAgdXJsICAgICAgOiB3Y19hZGRfdG9fY2FydF92YXJpYXRpb25fcGFyYW1zLndjX2FqYXhfdXJsLnRvU3RyaW5nKCkucmVwbGFjZSgnJSVlbmRwb2ludCUlJywgJ2dldF92YXJpYXRpb24nKSxcbiAgICAgICAgICAgICAgICAgICAgdHlwZSAgICAgOiAnUE9TVCcsXG4gICAgICAgICAgICAgICAgICAgIGRhdGEgICAgIDogY3VycmVudEF0dHJpYnV0ZXMsXG4gICAgICAgICAgICAgICAgICAgIHN1Y2Nlc3MgIDogZnVuY3Rpb24gKHZhcmlhdGlvbikge1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKHZhcmlhdGlvbikge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGZvcm0uJGZvcm0udHJpZ2dlcignZm91bmRfdmFyaWF0aW9uJywgW3ZhcmlhdGlvbl0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgZm9ybS4kZm9ybS50cmlnZ2VyKCdyZXNldF9kYXRhJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgYXR0cmlidXRlcy5jaG9zZW5Db3VudCA9IDA7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAoIWZvcm0ubG9hZGluZykge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBmb3JtLiRmb3JtLmZpbmQoJy5zaW5nbGVfdmFyaWF0aW9uJykuYWZ0ZXIoJzxwIGNsYXNzPVwid2Mtbm8tbWF0Y2hpbmctdmFyaWF0aW9ucyB3b29jb21tZXJjZS1pbmZvXCI+JyArIHdjX2FkZF90b19jYXJ0X3ZhcmlhdGlvbl9wYXJhbXMuaTE4bl9ub19tYXRjaGluZ192YXJpYXRpb25zX3RleHQgKyAnPC9wPicpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBmb3JtLiRmb3JtLmZpbmQoJy53Yy1uby1tYXRjaGluZy12YXJpYXRpb25zJykuc2xpZGVEb3duKDIwMCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgICAgICBjb21wbGV0ZSA6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGZvcm0uJGZvcm0udW5ibG9jaygpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICBmb3JtLiRmb3JtLnRyaWdnZXIoJ3VwZGF0ZV92YXJpYXRpb25fdmFsdWVzJyk7XG5cbiAgICAgICAgICAgICAgICB2YXIgbWF0Y2hpbmdfdmFyaWF0aW9ucyA9IGZvcm0uZmluZE1hdGNoaW5nVmFyaWF0aW9ucyhmb3JtLnZhcmlhdGlvbkRhdGEsIGN1cnJlbnRBdHRyaWJ1dGVzKSxcbiAgICAgICAgICAgICAgICAgICAgdmFyaWF0aW9uICAgICAgICAgICA9IG1hdGNoaW5nX3ZhcmlhdGlvbnMuc2hpZnQoKTtcblxuICAgICAgICAgICAgICAgIGlmICh2YXJpYXRpb24pIHtcbiAgICAgICAgICAgICAgICAgICAgZm9ybS4kZm9ybS50cmlnZ2VyKCdmb3VuZF92YXJpYXRpb24nLCBbdmFyaWF0aW9uXSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICBmb3JtLiRmb3JtLnRyaWdnZXIoJ3Jlc2V0X2RhdGEnKTtcbiAgICAgICAgICAgICAgICAgICAgYXR0cmlidXRlcy5jaG9zZW5Db3VudCA9IDA7XG5cbiAgICAgICAgICAgICAgICAgICAgaWYgKCFmb3JtLmxvYWRpbmcpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGZvcm0uJGZvcm0uZmluZCgnLnNpbmdsZV92YXJpYXRpb24nKS5hZnRlcignPHAgY2xhc3M9XCJ3Yy1uby1tYXRjaGluZy12YXJpYXRpb25zIHdvb2NvbW1lcmNlLWluZm9cIj4nICsgd2NfYWRkX3RvX2NhcnRfdmFyaWF0aW9uX3BhcmFtcy5pMThuX25vX21hdGNoaW5nX3ZhcmlhdGlvbnNfdGV4dCArICc8L3A+Jyk7XG4gICAgICAgICAgICAgICAgICAgICAgICBmb3JtLiRmb3JtLmZpbmQoJy53Yy1uby1tYXRjaGluZy12YXJpYXRpb25zJykuc2xpZGVEb3duKDIwMCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICBmb3JtLiRmb3JtLnRyaWdnZXIoJ3VwZGF0ZV92YXJpYXRpb25fdmFsdWVzJyk7XG5cbiAgICAgICAgICAgIC8vIFdPTyBWQVJJQVRJT04gR0FMTEVSWSBDSEFOR0VTXG4gICAgICAgICAgICBpZiAoIXdvb192YXJpYXRpb25fc3dhdGNoZXNfb3B0aW9ucy5lbmFibGVfc2luZ2xlX3ZhcmlhdGlvbl9wcmV2aWV3KSB7XG4gICAgICAgICAgICAgICAgZm9ybS4kZm9ybS50cmlnZ2VyKCdyZXNldF9kYXRhJyk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cblxuICAgICAgICAvLyBTaG93IHJlc2V0IGxpbmsuXG4gICAgICAgIGZvcm0udG9nZ2xlUmVzZXRMaW5rKGF0dHJpYnV0ZXMuY2hvc2VuQ291bnQgPiAwKTtcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogVHJpZ2dlcmVkIHdoZW4gYSB2YXJpYXRpb24gaGFzIGJlZW4gZm91bmQgd2hpY2ggbWF0Y2hlcyBhbGwgYXR0cmlidXRlcy5cbiAgICAgKi9cbiAgICBWYXJpYXRpb25Gb3JtLnByb3RvdHlwZS5vbkZvdW5kVmFyaWF0aW9uID0gZnVuY3Rpb24gKGV2ZW50LCB2YXJpYXRpb24pIHtcbiAgICAgICAgdmFyIGZvcm0gICAgICAgICAgID0gZXZlbnQuZGF0YS52YXJpYXRpb25Gb3JtLFxuICAgICAgICAgICAgJHNrdSAgICAgICAgICAgPSBmb3JtLiRwcm9kdWN0LmZpbmQoJy5wcm9kdWN0X21ldGEnKS5maW5kKCcuc2t1JyksXG4gICAgICAgICAgICAkd2VpZ2h0ICAgICAgICA9IGZvcm0uJHByb2R1Y3QuZmluZCgnLnByb2R1Y3Rfd2VpZ2h0JyksXG4gICAgICAgICAgICAkZGltZW5zaW9ucyAgICA9IGZvcm0uJHByb2R1Y3QuZmluZCgnLnByb2R1Y3RfZGltZW5zaW9ucycpLFxuICAgICAgICAgICAgJHF0eSAgICAgICAgICAgPSBmb3JtLiRzaW5nbGVWYXJpYXRpb25XcmFwLmZpbmQoJy5xdWFudGl0eScpLFxuICAgICAgICAgICAgcHVyY2hhc2FibGUgICAgPSB0cnVlLFxuICAgICAgICAgICAgdmFyaWF0aW9uX2lkICAgPSAnJyxcbiAgICAgICAgICAgIHRlbXBsYXRlICAgICAgID0gZmFsc2UsXG4gICAgICAgICAgICAkdGVtcGxhdGVfaHRtbCA9ICcnO1xuXG4gICAgICAgIGlmICh2YXJpYXRpb24uc2t1KSB7XG4gICAgICAgICAgICAkc2t1LndjX3NldF9jb250ZW50KHZhcmlhdGlvbi5za3UpO1xuICAgICAgICB9XG4gICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgJHNrdS53Y19yZXNldF9jb250ZW50KCk7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAodmFyaWF0aW9uLndlaWdodCkge1xuICAgICAgICAgICAgJHdlaWdodC53Y19zZXRfY29udGVudCh2YXJpYXRpb24ud2VpZ2h0X2h0bWwpO1xuICAgICAgICB9XG4gICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgJHdlaWdodC53Y19yZXNldF9jb250ZW50KCk7XG4gICAgICAgIH1cblxuICAgICAgICBpZiAodmFyaWF0aW9uLmRpbWVuc2lvbnMpIHtcbiAgICAgICAgICAgICRkaW1lbnNpb25zLndjX3NldF9jb250ZW50KHZhcmlhdGlvbi5kaW1lbnNpb25zX2h0bWwpO1xuICAgICAgICB9XG4gICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgJGRpbWVuc2lvbnMud2NfcmVzZXRfY29udGVudCgpO1xuICAgICAgICB9XG5cbiAgICAgICAgZm9ybS4kZm9ybS53Y192YXJpYXRpb25zX2ltYWdlX3VwZGF0ZSh2YXJpYXRpb24pO1xuXG4gICAgICAgIGlmICghdmFyaWF0aW9uLnZhcmlhdGlvbl9pc192aXNpYmxlKSB7XG4gICAgICAgICAgICB0ZW1wbGF0ZSA9IHdwLnRlbXBsYXRlKCd1bmF2YWlsYWJsZS12YXJpYXRpb24tdGVtcGxhdGUnKTtcbiAgICAgICAgfVxuICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgIHRlbXBsYXRlICAgICA9IHdwLnRlbXBsYXRlKCd2YXJpYXRpb24tdGVtcGxhdGUnKTtcbiAgICAgICAgICAgIHZhcmlhdGlvbl9pZCA9IHZhcmlhdGlvbi52YXJpYXRpb25faWQ7XG4gICAgICAgIH1cblxuICAgICAgICAkdGVtcGxhdGVfaHRtbCA9IHRlbXBsYXRlKHtcbiAgICAgICAgICAgIHZhcmlhdGlvbiA6IHZhcmlhdGlvblxuICAgICAgICB9KTtcbiAgICAgICAgJHRlbXBsYXRlX2h0bWwgPSAkdGVtcGxhdGVfaHRtbC5yZXBsYWNlKCcvKjwhW0NEQVRBWyovJywgJycpO1xuICAgICAgICAkdGVtcGxhdGVfaHRtbCA9ICR0ZW1wbGF0ZV9odG1sLnJlcGxhY2UoJy8qXV0+Ki8nLCAnJyk7XG5cbiAgICAgICAgZm9ybS4kc2luZ2xlVmFyaWF0aW9uLmh0bWwoJHRlbXBsYXRlX2h0bWwpO1xuICAgICAgICBmb3JtLiRmb3JtLmZpbmQoJ2lucHV0W25hbWU9XCJ2YXJpYXRpb25faWRcIl0sIGlucHV0LnZhcmlhdGlvbl9pZCcpLnZhbCh2YXJpYXRpb24udmFyaWF0aW9uX2lkKS5jaGFuZ2UoKTtcblxuICAgICAgICAvLyBIaWRlIG9yIHNob3cgcXR5IGlucHV0XG4gICAgICAgIGlmICh2YXJpYXRpb24uaXNfc29sZF9pbmRpdmlkdWFsbHkgPT09ICd5ZXMnKSB7XG4gICAgICAgICAgICAkcXR5LmZpbmQoJ2lucHV0LnF0eScpLnZhbCgnMScpLmF0dHIoJ21pbicsICcxJykuYXR0cignbWF4JywgJycpO1xuICAgICAgICAgICAgJHF0eS5oaWRlKCk7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAkcXR5LmZpbmQoJ2lucHV0LnF0eScpLmF0dHIoJ21pbicsIHZhcmlhdGlvbi5taW5fcXR5KS5hdHRyKCdtYXgnLCB2YXJpYXRpb24ubWF4X3F0eSk7XG4gICAgICAgICAgICAkcXR5LnNob3coKTtcbiAgICAgICAgfVxuXG4gICAgICAgIC8vIEVuYWJsZSBvciBkaXNhYmxlIHRoZSBhZGQgdG8gY2FydCBidXR0b25cbiAgICAgICAgaWYgKCF2YXJpYXRpb24uaXNfcHVyY2hhc2FibGUgfHwgIXZhcmlhdGlvbi5pc19pbl9zdG9jayB8fCAhdmFyaWF0aW9uLnZhcmlhdGlvbl9pc192aXNpYmxlKSB7XG4gICAgICAgICAgICBwdXJjaGFzYWJsZSA9IGZhbHNlO1xuICAgICAgICB9XG5cbiAgICAgICAgLy8gUmV2ZWFsXG4gICAgICAgIGlmICgkLnRyaW0oZm9ybS4kc2luZ2xlVmFyaWF0aW9uLnRleHQoKSkpIHtcbiAgICAgICAgICAgIGZvcm0uJHNpbmdsZVZhcmlhdGlvbi5zbGlkZURvd24oMjAwKS50cmlnZ2VyKCdzaG93X3ZhcmlhdGlvbicsIFt2YXJpYXRpb24sIHB1cmNoYXNhYmxlXSk7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICBmb3JtLiRzaW5nbGVWYXJpYXRpb24uc2hvdygpLnRyaWdnZXIoJ3Nob3dfdmFyaWF0aW9uJywgW3ZhcmlhdGlvbiwgcHVyY2hhc2FibGVdKTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBUcmlnZ2VyZWQgd2hlbiBhbiBhdHRyaWJ1dGUgZmllbGQgY2hhbmdlcy5cbiAgICAgKi9cbiAgICBWYXJpYXRpb25Gb3JtLnByb3RvdHlwZS5vbkNoYW5nZSA9IGZ1bmN0aW9uIChldmVudCkge1xuICAgICAgICB2YXIgZm9ybSA9IGV2ZW50LmRhdGEudmFyaWF0aW9uRm9ybTtcblxuICAgICAgICBmb3JtLiRmb3JtLmZpbmQoJ2lucHV0W25hbWU9XCJ2YXJpYXRpb25faWRcIl0sIGlucHV0LnZhcmlhdGlvbl9pZCcpLnZhbCgnJykuY2hhbmdlKCk7XG4gICAgICAgIGZvcm0uJGZvcm0uZmluZCgnLndjLW5vLW1hdGNoaW5nLXZhcmlhdGlvbnMnKS5yZW1vdmUoKTtcblxuICAgICAgICBpZiAoZm9ybS51c2VBamF4KSB7XG4gICAgICAgICAgICBmb3JtLiRmb3JtLnRyaWdnZXIoJ2NoZWNrX3ZhcmlhdGlvbnMnKTtcbiAgICAgICAgfVxuICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgIGZvcm0uJGZvcm0udHJpZ2dlcignd29vY29tbWVyY2VfdmFyaWF0aW9uX3NlbGVjdF9jaGFuZ2UnKTtcbiAgICAgICAgICAgIGZvcm0uJGZvcm0udHJpZ2dlcignY2hlY2tfdmFyaWF0aW9ucycpO1xuICAgICAgICAgICAgJCh0aGlzKS5ibHVyKCk7XG4gICAgICAgIH1cblxuICAgICAgICAvLyBDdXN0b20gZXZlbnQgZm9yIHdoZW4gdmFyaWF0aW9uIHNlbGVjdGlvbiBoYXMgYmVlbiBjaGFuZ2VkXG4gICAgICAgIGZvcm0uJGZvcm0udHJpZ2dlcignd29vY29tbWVyY2VfdmFyaWF0aW9uX2hhc19jaGFuZ2VkJyk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEVzY2FwZSBxdW90ZXMgaW4gYSBzdHJpbmcuXG4gICAgICogQHBhcmFtIHtzdHJpbmd9IHN0cmluZ1xuICAgICAqIEByZXR1cm4ge3N0cmluZ31cbiAgICAgKi9cbiAgICBWYXJpYXRpb25Gb3JtLnByb3RvdHlwZS5hZGRTbGFzaGVzID0gZnVuY3Rpb24gKHN0cmluZykge1xuICAgICAgICBzdHJpbmcgPSBzdHJpbmcucmVwbGFjZSgvJy9nLCAnXFxcXFxcJycpO1xuICAgICAgICBzdHJpbmcgPSBzdHJpbmcucmVwbGFjZSgvXCIvZywgJ1xcXFxcXFwiJyk7XG4gICAgICAgIHJldHVybiBzdHJpbmc7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIFVwZGF0ZXMgYXR0cmlidXRlcyBpbiB0aGUgRE9NIHRvIHNob3cgdmFsaWQgdmFsdWVzLlxuICAgICAqL1xuICAgIFZhcmlhdGlvbkZvcm0ucHJvdG90eXBlLm9uVXBkYXRlQXR0cmlidXRlcyA9IGZ1bmN0aW9uIChldmVudCkge1xuICAgICAgICB2YXIgZm9ybSAgICAgICAgICAgICAgPSBldmVudC5kYXRhLnZhcmlhdGlvbkZvcm0sXG4gICAgICAgICAgICBhdHRyaWJ1dGVzICAgICAgICA9IGZvcm0uZ2V0Q2hvc2VuQXR0cmlidXRlcygpLFxuICAgICAgICAgICAgY3VycmVudEF0dHJpYnV0ZXMgPSBhdHRyaWJ1dGVzLmRhdGE7XG5cbiAgICAgICAgaWYgKGZvcm0udXNlQWpheCkge1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG5cbiAgICAgICAgLy8gTG9vcCB0aHJvdWdoIHNlbGVjdHMgYW5kIGRpc2FibGUvZW5hYmxlIG9wdGlvbnMgYmFzZWQgb24gc2VsZWN0aW9ucy5cbiAgICAgICAgZm9ybS4kYXR0cmlidXRlRmllbGRzLmVhY2goZnVuY3Rpb24gKGluZGV4LCBlbCkge1xuICAgICAgICAgICAgdmFyIGN1cnJlbnRfYXR0cl9zZWxlY3QgICAgID0gJChlbCksXG4gICAgICAgICAgICAgICAgY3VycmVudF9hdHRyX25hbWUgICAgICAgPSBjdXJyZW50X2F0dHJfc2VsZWN0LmRhdGEoJ2F0dHJpYnV0ZV9uYW1lJykgfHwgY3VycmVudF9hdHRyX3NlbGVjdC5hdHRyKCduYW1lJyksXG4gICAgICAgICAgICAgICAgc2hvd19vcHRpb25fbm9uZSAgICAgICAgPSAkKGVsKS5kYXRhKCdzaG93X29wdGlvbl9ub25lJyksXG4gICAgICAgICAgICAgICAgb3B0aW9uX2d0X2ZpbHRlciAgICAgICAgPSAnOmd0KDApJyxcbiAgICAgICAgICAgICAgICBhdHRhY2hlZF9vcHRpb25zX2NvdW50ICA9IDAsXG4gICAgICAgICAgICAgICAgbmV3X2F0dHJfc2VsZWN0ICAgICAgICAgPSAkKCc8c2VsZWN0Lz4nKSxcbiAgICAgICAgICAgICAgICBzZWxlY3RlZF9hdHRyX3ZhbCAgICAgICA9IGN1cnJlbnRfYXR0cl9zZWxlY3QudmFsKCkgfHwgJycsXG4gICAgICAgICAgICAgICAgc2VsZWN0ZWRfYXR0cl92YWxfdmFsaWQgPSB0cnVlO1xuXG4gICAgICAgICAgICAvLyBSZWZlcmVuY2Ugb3B0aW9ucyBzZXQgYXQgZmlyc3QuXG4gICAgICAgICAgICBpZiAoIWN1cnJlbnRfYXR0cl9zZWxlY3QuZGF0YSgnYXR0cmlidXRlX2h0bWwnKSkge1xuICAgICAgICAgICAgICAgIHZhciByZWZTZWxlY3QgPSBjdXJyZW50X2F0dHJfc2VsZWN0LmNsb25lKCk7XG5cbiAgICAgICAgICAgICAgICByZWZTZWxlY3QuZmluZCgnb3B0aW9uJykucmVtb3ZlQXR0cignZGlzYWJsZWQgYXR0YWNoZWQnKS5yZW1vdmVBdHRyKCdzZWxlY3RlZCcpO1xuXG4gICAgICAgICAgICAgICAgY3VycmVudF9hdHRyX3NlbGVjdC5kYXRhKCdhdHRyaWJ1dGVfb3B0aW9ucycsIHJlZlNlbGVjdC5maW5kKCdvcHRpb24nICsgb3B0aW9uX2d0X2ZpbHRlcikuZ2V0KCkpOyAvLyBMZWdhY3kgZGF0YSBhdHRyaWJ1dGUuXG4gICAgICAgICAgICAgICAgY3VycmVudF9hdHRyX3NlbGVjdC5kYXRhKCdhdHRyaWJ1dGVfaHRtbCcsIHJlZlNlbGVjdC5odG1sKCkpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBuZXdfYXR0cl9zZWxlY3QuaHRtbChjdXJyZW50X2F0dHJfc2VsZWN0LmRhdGEoJ2F0dHJpYnV0ZV9odG1sJykpO1xuXG4gICAgICAgICAgICAvLyBUaGUgYXR0cmlidXRlIG9mIHRoaXMgc2VsZWN0IGZpZWxkIHNob3VsZCBub3QgYmUgdGFrZW4gaW50byBhY2NvdW50IHdoZW4gY2FsY3VsYXRpbmcgaXRzIG1hdGNoaW5nIHZhcmlhdGlvbnM6XG4gICAgICAgICAgICAvLyBUaGUgY29uc3RyYWludHMgb2YgdGhpcyBhdHRyaWJ1dGUgYXJlIHNoYXBlZCBieSB0aGUgdmFsdWVzIG9mIHRoZSBvdGhlciBhdHRyaWJ1dGVzLlxuICAgICAgICAgICAgdmFyIGNoZWNrQXR0cmlidXRlcyA9ICQuZXh0ZW5kKHRydWUsIHt9LCBjdXJyZW50QXR0cmlidXRlcyk7XG5cbiAgICAgICAgICAgIGNoZWNrQXR0cmlidXRlc1tjdXJyZW50X2F0dHJfbmFtZV0gPSAnJztcblxuICAgICAgICAgICAgdmFyIHZhcmlhdGlvbnMgPSBmb3JtLmZpbmRNYXRjaGluZ1ZhcmlhdGlvbnMoZm9ybS52YXJpYXRpb25EYXRhLCBjaGVja0F0dHJpYnV0ZXMpO1xuXG4gICAgICAgICAgICAvLyBMb29wIHRocm91Z2ggdmFyaWF0aW9ucy5cbiAgICAgICAgICAgIGZvciAodmFyIG51bSBpbiB2YXJpYXRpb25zKSB7XG4gICAgICAgICAgICAgICAgaWYgKHR5cGVvZih2YXJpYXRpb25zW251bV0pICE9PSAndW5kZWZpbmVkJykge1xuICAgICAgICAgICAgICAgICAgICB2YXIgdmFyaWF0aW9uQXR0cmlidXRlcyA9IHZhcmlhdGlvbnNbbnVtXS5hdHRyaWJ1dGVzO1xuXG4gICAgICAgICAgICAgICAgICAgIGZvciAodmFyIGF0dHJfbmFtZSBpbiB2YXJpYXRpb25BdHRyaWJ1dGVzKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAodmFyaWF0aW9uQXR0cmlidXRlcy5oYXNPd25Qcm9wZXJ0eShhdHRyX25hbWUpKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGF0dHJfdmFsICAgICAgICAgPSB2YXJpYXRpb25BdHRyaWJ1dGVzW2F0dHJfbmFtZV0sXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHZhcmlhdGlvbl9hY3RpdmUgPSAnJztcblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmIChhdHRyX25hbWUgPT09IGN1cnJlbnRfYXR0cl9uYW1lKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmICh2YXJpYXRpb25zW251bV0udmFyaWF0aW9uX2lzX2FjdGl2ZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyaWF0aW9uX2FjdGl2ZSA9ICdlbmFibGVkJztcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmIChhdHRyX3ZhbCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgLy8gRGVjb2RlIGVudGl0aWVzIGFuZCBhZGQgc2xhc2hlcy5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGF0dHJfdmFsID0gJCgnPGRpdi8+JykuaHRtbChhdHRyX3ZhbCkudGV4dCgpO1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvLyBBdHRhY2guXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBuZXdfYXR0cl9zZWxlY3QuZmluZCgnb3B0aW9uW3ZhbHVlPVwiJyArIGZvcm0uYWRkU2xhc2hlcyhhdHRyX3ZhbCkgKyAnXCJdJykuYWRkQ2xhc3MoJ2F0dGFjaGVkICcgKyB2YXJpYXRpb25fYWN0aXZlKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8vIEF0dGFjaCBhbGwgYXBhcnQgZnJvbSBwbGFjZWhvbGRlci5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG5ld19hdHRyX3NlbGVjdC5maW5kKCdvcHRpb246Z3QoMCknKS5hZGRDbGFzcygnYXR0YWNoZWQgJyArIHZhcmlhdGlvbl9hY3RpdmUpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAvLyBDb3VudCBhdmFpbGFibGUgb3B0aW9ucy5cbiAgICAgICAgICAgIGF0dGFjaGVkX29wdGlvbnNfY291bnQgPSBuZXdfYXR0cl9zZWxlY3QuZmluZCgnb3B0aW9uLmF0dGFjaGVkJykubGVuZ3RoO1xuXG4gICAgICAgICAgICAvLyBDaGVjayBpZiBjdXJyZW50IHNlbGVjdGlvbiBpcyBpbiBhdHRhY2hlZCBvcHRpb25zLlxuICAgICAgICAgICAgaWYgKHNlbGVjdGVkX2F0dHJfdmFsICYmIChhdHRhY2hlZF9vcHRpb25zX2NvdW50ID09PSAwIHx8IG5ld19hdHRyX3NlbGVjdC5maW5kKCdvcHRpb24uYXR0YWNoZWQuZW5hYmxlZFt2YWx1ZT1cIicgKyBmb3JtLmFkZFNsYXNoZXMoc2VsZWN0ZWRfYXR0cl92YWwpICsgJ1wiXScpLmxlbmd0aCA9PT0gMCkpIHtcbiAgICAgICAgICAgICAgICBzZWxlY3RlZF9hdHRyX3ZhbF92YWxpZCA9IGZhbHNlO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAvLyBEZXRhY2ggdGhlIHBsYWNlaG9sZGVyIGlmOlxuICAgICAgICAgICAgLy8gLSBWYWxpZCBvcHRpb25zIGV4aXN0LlxuICAgICAgICAgICAgLy8gLSBUaGUgY3VycmVudCBzZWxlY3Rpb24gaXMgbm9uLWVtcHR5LlxuICAgICAgICAgICAgLy8gLSBUaGUgY3VycmVudCBzZWxlY3Rpb24gaXMgdmFsaWQuXG4gICAgICAgICAgICAvLyAtIFBsYWNlaG9sZGVycyBhcmUgbm90IHNldCB0byBiZSBwZXJtYW5lbnRseSB2aXNpYmxlLlxuICAgICAgICAgICAgaWYgKGF0dGFjaGVkX29wdGlvbnNfY291bnQgPiAwICYmIHNlbGVjdGVkX2F0dHJfdmFsICYmIHNlbGVjdGVkX2F0dHJfdmFsX3ZhbGlkICYmICgnbm8nID09PSBzaG93X29wdGlvbl9ub25lKSkge1xuICAgICAgICAgICAgICAgIG5ld19hdHRyX3NlbGVjdC5maW5kKCdvcHRpb246Zmlyc3QnKS5yZW1vdmUoKTtcbiAgICAgICAgICAgICAgICBvcHRpb25fZ3RfZmlsdGVyID0gJyc7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIC8vIERldGFjaCB1bmF0dGFjaGVkLlxuICAgICAgICAgICAgbmV3X2F0dHJfc2VsZWN0LmZpbmQoJ29wdGlvbicgKyBvcHRpb25fZ3RfZmlsdGVyICsgJzpub3QoLmF0dGFjaGVkKScpLnJlbW92ZSgpO1xuXG4gICAgICAgICAgICAvLyBGaW5hbGx5LCBjb3B5IHRvIERPTSBhbmQgc2V0IHZhbHVlLlxuICAgICAgICAgICAgY3VycmVudF9hdHRyX3NlbGVjdC5odG1sKG5ld19hdHRyX3NlbGVjdC5odG1sKCkpO1xuICAgICAgICAgICAgY3VycmVudF9hdHRyX3NlbGVjdC5maW5kKCdvcHRpb24nICsgb3B0aW9uX2d0X2ZpbHRlciArICc6bm90KC5lbmFibGVkKScpLnByb3AoJ2Rpc2FibGVkJywgdHJ1ZSk7XG5cbiAgICAgICAgICAgIC8vIENob29zZSBzZWxlY3RlZCB2YWx1ZS5cbiAgICAgICAgICAgIGlmIChzZWxlY3RlZF9hdHRyX3ZhbCkge1xuICAgICAgICAgICAgICAgIC8vIElmIHRoZSBwcmV2aW91c2x5IHNlbGVjdGVkIHZhbHVlIGlzIG5vIGxvbmdlciBhdmFpbGFibGUsIGZhbGwgYmFjayB0byB0aGUgcGxhY2Vob2xkZXIgKGl0J3MgZ29pbmcgdG8gYmUgdGhlcmUpLlxuICAgICAgICAgICAgICAgIGlmIChzZWxlY3RlZF9hdHRyX3ZhbF92YWxpZCkge1xuICAgICAgICAgICAgICAgICAgICBjdXJyZW50X2F0dHJfc2VsZWN0LnZhbChzZWxlY3RlZF9hdHRyX3ZhbCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICBjdXJyZW50X2F0dHJfc2VsZWN0LnZhbCgnJykuY2hhbmdlKCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgY3VycmVudF9hdHRyX3NlbGVjdC52YWwoJycpOyAvLyBObyBjaGFuZ2UgZXZlbnQgdG8gcHJldmVudCBpbmZpbml0ZSBsb29wLlxuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcblxuICAgICAgICAvLyBDdXN0b20gZXZlbnQgZm9yIHdoZW4gdmFyaWF0aW9ucyBoYXZlIGJlZW4gdXBkYXRlZC5cbiAgICAgICAgZm9ybS4kZm9ybS50cmlnZ2VyKCd3b29jb21tZXJjZV91cGRhdGVfdmFyaWF0aW9uX3ZhbHVlcycpO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBHZXQgY2hvc2VuIGF0dHJpYnV0ZXMgZnJvbSBmb3JtLlxuICAgICAqIEByZXR1cm4gYXJyYXlcbiAgICAgKi9cbiAgICBWYXJpYXRpb25Gb3JtLnByb3RvdHlwZS5nZXRDaG9zZW5BdHRyaWJ1dGVzID0gZnVuY3Rpb24gKCkge1xuICAgICAgICB2YXIgZGF0YSAgID0ge307XG4gICAgICAgIHZhciBjb3VudCAgPSAwO1xuICAgICAgICB2YXIgY2hvc2VuID0gMDtcblxuICAgICAgICB0aGlzLiRhdHRyaWJ1dGVGaWVsZHMuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB2YXIgYXR0cmlidXRlX25hbWUgPSAkKHRoaXMpLmRhdGEoJ2F0dHJpYnV0ZV9uYW1lJykgfHwgJCh0aGlzKS5hdHRyKCduYW1lJyk7XG4gICAgICAgICAgICB2YXIgdmFsdWUgICAgICAgICAgPSAkKHRoaXMpLnZhbCgpIHx8ICcnO1xuXG4gICAgICAgICAgICBpZiAodmFsdWUubGVuZ3RoID4gMCkge1xuICAgICAgICAgICAgICAgIGNob3NlbisrO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBjb3VudCsrO1xuICAgICAgICAgICAgZGF0YVthdHRyaWJ1dGVfbmFtZV0gPSB2YWx1ZTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgcmV0dXJuIHtcbiAgICAgICAgICAgICdjb3VudCcgICAgICAgOiBjb3VudCxcbiAgICAgICAgICAgICdjaG9zZW5Db3VudCcgOiBjaG9zZW4sXG4gICAgICAgICAgICAnZGF0YScgICAgICAgIDogZGF0YVxuICAgICAgICB9O1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBGaW5kIG1hdGNoaW5nIHZhcmlhdGlvbnMgZm9yIGF0dHJpYnV0ZXMuXG4gICAgICovXG4gICAgVmFyaWF0aW9uRm9ybS5wcm90b3R5cGUuZmluZE1hdGNoaW5nVmFyaWF0aW9ucyA9IGZ1bmN0aW9uICh2YXJpYXRpb25zLCBhdHRyaWJ1dGVzKSB7XG4gICAgICAgIHZhciBtYXRjaGluZyA9IFtdO1xuICAgICAgICBmb3IgKHZhciBpID0gMDsgaSA8IHZhcmlhdGlvbnMubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgICAgIHZhciB2YXJpYXRpb24gPSB2YXJpYXRpb25zW2ldO1xuXG4gICAgICAgICAgICBpZiAodGhpcy5pc01hdGNoKHZhcmlhdGlvbi5hdHRyaWJ1dGVzLCBhdHRyaWJ1dGVzKSkge1xuICAgICAgICAgICAgICAgIG1hdGNoaW5nLnB1c2godmFyaWF0aW9uKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gbWF0Y2hpbmc7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIFNlZSBpZiBhdHRyaWJ1dGVzIG1hdGNoLlxuICAgICAqIEByZXR1cm4ge0Jvb2xlYW59XG4gICAgICovXG4gICAgVmFyaWF0aW9uRm9ybS5wcm90b3R5cGUuaXNNYXRjaCA9IGZ1bmN0aW9uICh2YXJpYXRpb25fYXR0cmlidXRlcywgYXR0cmlidXRlcykge1xuICAgICAgICB2YXIgbWF0Y2ggPSB0cnVlO1xuICAgICAgICBmb3IgKHZhciBhdHRyX25hbWUgaW4gdmFyaWF0aW9uX2F0dHJpYnV0ZXMpIHtcbiAgICAgICAgICAgIGlmICh2YXJpYXRpb25fYXR0cmlidXRlcy5oYXNPd25Qcm9wZXJ0eShhdHRyX25hbWUpKSB7XG4gICAgICAgICAgICAgICAgdmFyIHZhbDEgPSB2YXJpYXRpb25fYXR0cmlidXRlc1thdHRyX25hbWVdO1xuICAgICAgICAgICAgICAgIHZhciB2YWwyID0gYXR0cmlidXRlc1thdHRyX25hbWVdO1xuICAgICAgICAgICAgICAgIGlmICh2YWwxICE9PSB1bmRlZmluZWQgJiYgdmFsMiAhPT0gdW5kZWZpbmVkICYmIHZhbDEubGVuZ3RoICE9PSAwICYmIHZhbDIubGVuZ3RoICE9PSAwICYmIHZhbDEgIT09IHZhbDIpIHtcbiAgICAgICAgICAgICAgICAgICAgbWF0Y2ggPSBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgcmV0dXJuIG1hdGNoO1xuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBTaG93IG9yIGhpZGUgdGhlIHJlc2V0IGxpbmsuXG4gICAgICovXG4gICAgVmFyaWF0aW9uRm9ybS5wcm90b3R5cGUudG9nZ2xlUmVzZXRMaW5rID0gZnVuY3Rpb24gKG9uKSB7XG4gICAgICAgIGlmIChvbikge1xuICAgICAgICAgICAgaWYgKHRoaXMuJHJlc2V0VmFyaWF0aW9ucy5jc3MoJ3Zpc2liaWxpdHknKSA9PT0gJ2hpZGRlbicpIHtcbiAgICAgICAgICAgICAgICB0aGlzLiRyZXNldFZhcmlhdGlvbnMuY3NzKCd2aXNpYmlsaXR5JywgJ3Zpc2libGUnKS5oaWRlKCkuZmFkZUluKCk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICB0aGlzLiRyZXNldFZhcmlhdGlvbnMuY3NzKCd2aXNpYmlsaXR5JywgJ2hpZGRlbicpO1xuICAgICAgICB9XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIEZ1bmN0aW9uIHRvIGNhbGwgd2NfdmFyaWF0aW9uX2Zvcm0gb24ganF1ZXJ5IHNlbGVjdG9yLlxuICAgICAqL1xuICAgICQuZm4ud2NfdmFyaWF0aW9uX2Zvcm0gPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgIG5ldyBWYXJpYXRpb25Gb3JtKHRoaXMpO1xuICAgICAgICByZXR1cm4gdGhpcztcbiAgICB9O1xuXG4gICAgLyoqXG4gICAgICogU3RvcmVzIHRoZSBkZWZhdWx0IHRleHQgZm9yIGFuIGVsZW1lbnQgc28gaXQgY2FuIGJlIHJlc2V0IGxhdGVyXG4gICAgICovXG4gICAgJC5mbi53Y19zZXRfY29udGVudCA9IGZ1bmN0aW9uIChjb250ZW50KSB7XG4gICAgICAgIGlmICh1bmRlZmluZWQgPT09IHRoaXMuYXR0cignZGF0YS1vX2NvbnRlbnQnKSkge1xuICAgICAgICAgICAgdGhpcy5hdHRyKCdkYXRhLW9fY29udGVudCcsIHRoaXMudGV4dCgpKTtcbiAgICAgICAgfVxuICAgICAgICB0aGlzLnRleHQoY29udGVudCk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIFN0b3JlcyB0aGUgZGVmYXVsdCB0ZXh0IGZvciBhbiBlbGVtZW50IHNvIGl0IGNhbiBiZSByZXNldCBsYXRlclxuICAgICAqL1xuICAgICQuZm4ud2NfcmVzZXRfY29udGVudCA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgaWYgKHVuZGVmaW5lZCAhPT0gdGhpcy5hdHRyKCdkYXRhLW9fY29udGVudCcpKSB7XG4gICAgICAgICAgICB0aGlzLnRleHQodGhpcy5hdHRyKCdkYXRhLW9fY29udGVudCcpKTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBTdG9yZXMgYSBkZWZhdWx0IGF0dHJpYnV0ZSBmb3IgYW4gZWxlbWVudCBzbyBpdCBjYW4gYmUgcmVzZXQgbGF0ZXJcbiAgICAgKi9cbiAgICAkLmZuLndjX3NldF92YXJpYXRpb25fYXR0ciA9IGZ1bmN0aW9uIChhdHRyLCB2YWx1ZSkge1xuICAgICAgICBpZiAodW5kZWZpbmVkID09PSB0aGlzLmF0dHIoJ2RhdGEtb18nICsgYXR0cikpIHtcbiAgICAgICAgICAgIHRoaXMuYXR0cignZGF0YS1vXycgKyBhdHRyLCAoIXRoaXMuYXR0cihhdHRyKSkgPyAnJyA6IHRoaXMuYXR0cihhdHRyKSk7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKGZhbHNlID09PSB2YWx1ZSkge1xuICAgICAgICAgICAgdGhpcy5yZW1vdmVBdHRyKGF0dHIpO1xuICAgICAgICB9XG4gICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgdGhpcy5hdHRyKGF0dHIsIHZhbHVlKTtcbiAgICAgICAgfVxuICAgIH07XG5cbiAgICAvKipcbiAgICAgKiBSZXNldCBhIGRlZmF1bHQgYXR0cmlidXRlIGZvciBhbiBlbGVtZW50IHNvIGl0IGNhbiBiZSByZXNldCBsYXRlclxuICAgICAqL1xuICAgICQuZm4ud2NfcmVzZXRfdmFyaWF0aW9uX2F0dHIgPSBmdW5jdGlvbiAoYXR0cikge1xuICAgICAgICBpZiAodW5kZWZpbmVkICE9PSB0aGlzLmF0dHIoJ2RhdGEtb18nICsgYXR0cikpIHtcbiAgICAgICAgICAgIHRoaXMuYXR0cihhdHRyLCB0aGlzLmF0dHIoJ2RhdGEtb18nICsgYXR0cikpO1xuICAgICAgICB9XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIFJlc2V0IHRoZSBzbGlkZSBwb3NpdGlvbiBpZiB0aGUgdmFyaWF0aW9uIGhhcyBhIGRpZmZlcmVudCBpbWFnZSB0aGFuIHRoZSBjdXJyZW50IG9uZVxuICAgICAqL1xuICAgICQuZm4ud2NfbWF5YmVfdHJpZ2dlcl9zbGlkZV9wb3NpdGlvbl9yZXNldCA9IGZ1bmN0aW9uICh2YXJpYXRpb24pIHtcbiAgICAgICAgdmFyICRmb3JtICAgICAgICAgICAgICAgID0gJCh0aGlzKSxcbiAgICAgICAgICAgICRwcm9kdWN0ICAgICAgICAgICAgID0gJGZvcm0uY2xvc2VzdCgnLnByb2R1Y3QnKSxcbiAgICAgICAgICAgICRwcm9kdWN0X2dhbGxlcnkgICAgID0gJHByb2R1Y3QuZmluZCgnLmltYWdlcycpLFxuICAgICAgICAgICAgcmVzZXRfc2xpZGVfcG9zaXRpb24gPSBmYWxzZSxcbiAgICAgICAgICAgIG5ld19pbWFnZV9pZCAgICAgICAgID0gKHZhcmlhdGlvbiAmJiB2YXJpYXRpb24uaW1hZ2VfaWQpID8gdmFyaWF0aW9uLmltYWdlX2lkIDogJyc7XG5cbiAgICAgICAgaWYgKCRmb3JtLmF0dHIoJ2N1cnJlbnQtaW1hZ2UnKSAhPT0gbmV3X2ltYWdlX2lkKSB7XG4gICAgICAgICAgICByZXNldF9zbGlkZV9wb3NpdGlvbiA9IHRydWU7XG4gICAgICAgIH1cblxuICAgICAgICAkZm9ybS5hdHRyKCdjdXJyZW50LWltYWdlJywgbmV3X2ltYWdlX2lkKTtcblxuICAgICAgICBpZiAocmVzZXRfc2xpZGVfcG9zaXRpb24pIHtcbiAgICAgICAgICAgICRwcm9kdWN0X2dhbGxlcnkudHJpZ2dlcignd29vY29tbWVyY2VfZ2FsbGVyeV9yZXNldF9zbGlkZV9wb3NpdGlvbicpO1xuICAgICAgICB9XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIFNldHMgcHJvZHVjdCBpbWFnZXMgZm9yIHRoZSBjaG9zZW4gdmFyaWF0aW9uXG4gICAgICovXG4gICAgJC5mbi53Y192YXJpYXRpb25zX2ltYWdlX3VwZGF0ZSA9IGZ1bmN0aW9uICh2YXJpYXRpb24pIHtcbiAgICAgICAgdmFyICRmb3JtICAgICAgICAgICAgID0gdGhpcyxcbiAgICAgICAgICAgICRwcm9kdWN0ICAgICAgICAgID0gJGZvcm0uY2xvc2VzdCgnLnByb2R1Y3QnKSxcbiAgICAgICAgICAgICRwcm9kdWN0X2dhbGxlcnkgID0gJHByb2R1Y3QuZmluZCgnLmltYWdlcycpLFxuICAgICAgICAgICAgJGdhbGxlcnlfbmF2ICAgICAgPSAkcHJvZHVjdC5maW5kKCcuZmxleC1jb250cm9sLW5hdicpLFxuICAgICAgICAgICAgJGdhbGxlcnlfaW1nICAgICAgPSAkZ2FsbGVyeV9uYXYuZmluZCgnbGk6ZXEoMCkgaW1nJyksXG4gICAgICAgICAgICAkcHJvZHVjdF9pbWdfd3JhcCA9ICRwcm9kdWN0X2dhbGxlcnkuZmluZCgnLndvb2NvbW1lcmNlLXByb2R1Y3QtZ2FsbGVyeV9faW1hZ2UsIC53b29jb21tZXJjZS1wcm9kdWN0LWdhbGxlcnlfX2ltYWdlLS1wbGFjZWhvbGRlcicpLmVxKDApLFxuICAgICAgICAgICAgJHByb2R1Y3RfaW1nICAgICAgPSAkcHJvZHVjdF9pbWdfd3JhcC5maW5kKCcud3AtcG9zdC1pbWFnZScpLFxuICAgICAgICAgICAgJHByb2R1Y3RfbGluayAgICAgPSAkcHJvZHVjdF9pbWdfd3JhcC5maW5kKCdhJykuZXEoMCk7XG5cbiAgICAgICAgaWYgKHZhcmlhdGlvbiAmJiB2YXJpYXRpb24uaW1hZ2UgJiYgdmFyaWF0aW9uLmltYWdlLnNyYyAmJiB2YXJpYXRpb24uaW1hZ2Uuc3JjLmxlbmd0aCA+IDEpIHtcbiAgICAgICAgICAgIC8vIFNlZSBpZiB0aGUgZ2FsbGVyeSBoYXMgYW4gaW1hZ2Ugd2l0aCB0aGUgc2FtZSBvcmlnaW5hbCBzcmMgYXMgdGhlIGltYWdlIHdlIHdhbnQgdG8gc3dpdGNoIHRvLlxuICAgICAgICAgICAgdmFyIGdhbGxlcnlIYXNJbWFnZSA9ICRnYWxsZXJ5X25hdi5maW5kKCdsaSBpbWdbZGF0YS1vX3NyYz1cIicgKyB2YXJpYXRpb24uaW1hZ2UuZ2FsbGVyeV90aHVtYm5haWxfc3JjICsgJ1wiXScpLmxlbmd0aCA+IDA7XG5cbiAgICAgICAgICAgIC8vIElmIHRoZSBnYWxsZXJ5IGhhcyB0aGUgaW1hZ2UsIHJlc2V0IHRoZSBpbWFnZXMuIFdlJ2xsIHNjcm9sbCB0byB0aGUgY29ycmVjdCBvbmUuXG4gICAgICAgICAgICBpZiAoZ2FsbGVyeUhhc0ltYWdlKSB7XG4gICAgICAgICAgICAgICAgJGZvcm0ud2NfdmFyaWF0aW9uc19pbWFnZV9yZXNldCgpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAvLyBTZWUgaWYgZ2FsbGVyeSBoYXMgYSBtYXRjaGluZyBpbWFnZSB3ZSBjYW4gc2xpZGUgdG8uXG4gICAgICAgICAgICB2YXIgc2xpZGVUb0ltYWdlID0gJGdhbGxlcnlfbmF2LmZpbmQoJ2xpIGltZ1tzcmM9XCInICsgdmFyaWF0aW9uLmltYWdlLmdhbGxlcnlfdGh1bWJuYWlsX3NyYyArICdcIl0nKTtcblxuICAgICAgICAgICAgaWYgKHNsaWRlVG9JbWFnZS5sZW5ndGggPiAwKSB7XG4gICAgICAgICAgICAgICAgc2xpZGVUb0ltYWdlLnRyaWdnZXIoJ2NsaWNrJyk7XG4gICAgICAgICAgICAgICAgJGZvcm0uYXR0cignY3VycmVudC1pbWFnZScsIHZhcmlhdGlvbi5pbWFnZV9pZCk7XG4gICAgICAgICAgICAgICAgd2luZG93LnNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAkKHdpbmRvdykudHJpZ2dlcigncmVzaXplJyk7XG4gICAgICAgICAgICAgICAgICAgICRwcm9kdWN0X2dhbGxlcnkudHJpZ2dlcignd29vY29tbWVyY2VfZ2FsbGVyeV9pbml0X3pvb20nKTtcbiAgICAgICAgICAgICAgICB9LCAyMCk7XG4gICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAkcHJvZHVjdF9pbWcud2Nfc2V0X3ZhcmlhdGlvbl9hdHRyKCdzcmMnLCB2YXJpYXRpb24uaW1hZ2Uuc3JjKTtcbiAgICAgICAgICAgICRwcm9kdWN0X2ltZy53Y19zZXRfdmFyaWF0aW9uX2F0dHIoJ2hlaWdodCcsIHZhcmlhdGlvbi5pbWFnZS5zcmNfaCk7XG4gICAgICAgICAgICAkcHJvZHVjdF9pbWcud2Nfc2V0X3ZhcmlhdGlvbl9hdHRyKCd3aWR0aCcsIHZhcmlhdGlvbi5pbWFnZS5zcmNfdyk7XG4gICAgICAgICAgICAkcHJvZHVjdF9pbWcud2Nfc2V0X3ZhcmlhdGlvbl9hdHRyKCdzcmNzZXQnLCB2YXJpYXRpb24uaW1hZ2Uuc3Jjc2V0KTtcbiAgICAgICAgICAgICRwcm9kdWN0X2ltZy53Y19zZXRfdmFyaWF0aW9uX2F0dHIoJ3NpemVzJywgdmFyaWF0aW9uLmltYWdlLnNpemVzKTtcbiAgICAgICAgICAgICRwcm9kdWN0X2ltZy53Y19zZXRfdmFyaWF0aW9uX2F0dHIoJ3RpdGxlJywgdmFyaWF0aW9uLmltYWdlLnRpdGxlKTtcbiAgICAgICAgICAgICRwcm9kdWN0X2ltZy53Y19zZXRfdmFyaWF0aW9uX2F0dHIoJ2FsdCcsIHZhcmlhdGlvbi5pbWFnZS5hbHQpO1xuICAgICAgICAgICAgJHByb2R1Y3RfaW1nLndjX3NldF92YXJpYXRpb25fYXR0cignZGF0YS1zcmMnLCB2YXJpYXRpb24uaW1hZ2UuZnVsbF9zcmMpO1xuICAgICAgICAgICAgJHByb2R1Y3RfaW1nLndjX3NldF92YXJpYXRpb25fYXR0cignZGF0YS1sYXJnZV9pbWFnZScsIHZhcmlhdGlvbi5pbWFnZS5mdWxsX3NyYyk7XG4gICAgICAgICAgICAkcHJvZHVjdF9pbWcud2Nfc2V0X3ZhcmlhdGlvbl9hdHRyKCdkYXRhLWxhcmdlX2ltYWdlX3dpZHRoJywgdmFyaWF0aW9uLmltYWdlLmZ1bGxfc3JjX3cpO1xuICAgICAgICAgICAgJHByb2R1Y3RfaW1nLndjX3NldF92YXJpYXRpb25fYXR0cignZGF0YS1sYXJnZV9pbWFnZV9oZWlnaHQnLCB2YXJpYXRpb24uaW1hZ2UuZnVsbF9zcmNfaCk7XG4gICAgICAgICAgICAkcHJvZHVjdF9pbWdfd3JhcC53Y19zZXRfdmFyaWF0aW9uX2F0dHIoJ2RhdGEtdGh1bWInLCB2YXJpYXRpb24uaW1hZ2Uuc3JjKTtcbiAgICAgICAgICAgICRnYWxsZXJ5X2ltZy53Y19zZXRfdmFyaWF0aW9uX2F0dHIoJ3NyYycsIHZhcmlhdGlvbi5pbWFnZS5nYWxsZXJ5X3RodW1ibmFpbF9zcmMpO1xuICAgICAgICAgICAgJHByb2R1Y3RfbGluay53Y19zZXRfdmFyaWF0aW9uX2F0dHIoJ2hyZWYnLCB2YXJpYXRpb24uaW1hZ2UuZnVsbF9zcmMpO1xuICAgICAgICB9XG4gICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgJGZvcm0ud2NfdmFyaWF0aW9uc19pbWFnZV9yZXNldCgpO1xuICAgICAgICB9XG5cbiAgICAgICAgd2luZG93LnNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgJCh3aW5kb3cpLnRyaWdnZXIoJ3Jlc2l6ZScpO1xuICAgICAgICAgICAgJGZvcm0ud2NfbWF5YmVfdHJpZ2dlcl9zbGlkZV9wb3NpdGlvbl9yZXNldCh2YXJpYXRpb24pO1xuICAgICAgICAgICAgJHByb2R1Y3RfZ2FsbGVyeS50cmlnZ2VyKCd3b29jb21tZXJjZV9nYWxsZXJ5X2luaXRfem9vbScpO1xuICAgICAgICB9LCAyMCk7XG4gICAgfTtcblxuICAgIC8qKlxuICAgICAqIFJlc2V0IG1haW4gaW1hZ2UgdG8gZGVmYXVsdHMuXG4gICAgICovXG4gICAgJC5mbi53Y192YXJpYXRpb25zX2ltYWdlX3Jlc2V0ID0gZnVuY3Rpb24gKCkge1xuICAgICAgICB2YXIgJGZvcm0gICAgICAgICAgICAgPSB0aGlzLFxuICAgICAgICAgICAgJHByb2R1Y3QgICAgICAgICAgPSAkZm9ybS5jbG9zZXN0KCcucHJvZHVjdCcpLFxuICAgICAgICAgICAgJHByb2R1Y3RfZ2FsbGVyeSAgPSAkcHJvZHVjdC5maW5kKCcuaW1hZ2VzJyksXG4gICAgICAgICAgICAkZ2FsbGVyeV9uYXYgICAgICA9ICRwcm9kdWN0LmZpbmQoJy5mbGV4LWNvbnRyb2wtbmF2JyksXG4gICAgICAgICAgICAkZ2FsbGVyeV9pbWcgICAgICA9ICRnYWxsZXJ5X25hdi5maW5kKCdsaTplcSgwKSBpbWcnKSxcbiAgICAgICAgICAgICRwcm9kdWN0X2ltZ193cmFwID0gJHByb2R1Y3RfZ2FsbGVyeS5maW5kKCcud29vY29tbWVyY2UtcHJvZHVjdC1nYWxsZXJ5X19pbWFnZSwgLndvb2NvbW1lcmNlLXByb2R1Y3QtZ2FsbGVyeV9faW1hZ2UtLXBsYWNlaG9sZGVyJykuZXEoMCksXG4gICAgICAgICAgICAkcHJvZHVjdF9pbWcgICAgICA9ICRwcm9kdWN0X2ltZ193cmFwLmZpbmQoJy53cC1wb3N0LWltYWdlJyksXG4gICAgICAgICAgICAkcHJvZHVjdF9saW5rICAgICA9ICRwcm9kdWN0X2ltZ193cmFwLmZpbmQoJ2EnKS5lcSgwKTtcblxuICAgICAgICAkcHJvZHVjdF9pbWcud2NfcmVzZXRfdmFyaWF0aW9uX2F0dHIoJ3NyYycpO1xuICAgICAgICAkcHJvZHVjdF9pbWcud2NfcmVzZXRfdmFyaWF0aW9uX2F0dHIoJ3dpZHRoJyk7XG4gICAgICAgICRwcm9kdWN0X2ltZy53Y19yZXNldF92YXJpYXRpb25fYXR0cignaGVpZ2h0Jyk7XG4gICAgICAgICRwcm9kdWN0X2ltZy53Y19yZXNldF92YXJpYXRpb25fYXR0cignc3Jjc2V0Jyk7XG4gICAgICAgICRwcm9kdWN0X2ltZy53Y19yZXNldF92YXJpYXRpb25fYXR0cignc2l6ZXMnKTtcbiAgICAgICAgJHByb2R1Y3RfaW1nLndjX3Jlc2V0X3ZhcmlhdGlvbl9hdHRyKCd0aXRsZScpO1xuICAgICAgICAkcHJvZHVjdF9pbWcud2NfcmVzZXRfdmFyaWF0aW9uX2F0dHIoJ2FsdCcpO1xuICAgICAgICAkcHJvZHVjdF9pbWcud2NfcmVzZXRfdmFyaWF0aW9uX2F0dHIoJ2RhdGEtc3JjJyk7XG4gICAgICAgICRwcm9kdWN0X2ltZy53Y19yZXNldF92YXJpYXRpb25fYXR0cignZGF0YS1sYXJnZV9pbWFnZScpO1xuICAgICAgICAkcHJvZHVjdF9pbWcud2NfcmVzZXRfdmFyaWF0aW9uX2F0dHIoJ2RhdGEtbGFyZ2VfaW1hZ2Vfd2lkdGgnKTtcbiAgICAgICAgJHByb2R1Y3RfaW1nLndjX3Jlc2V0X3ZhcmlhdGlvbl9hdHRyKCdkYXRhLWxhcmdlX2ltYWdlX2hlaWdodCcpO1xuICAgICAgICAkcHJvZHVjdF9pbWdfd3JhcC53Y19yZXNldF92YXJpYXRpb25fYXR0cignZGF0YS10aHVtYicpO1xuICAgICAgICAkZ2FsbGVyeV9pbWcud2NfcmVzZXRfdmFyaWF0aW9uX2F0dHIoJ3NyYycpO1xuICAgICAgICAkcHJvZHVjdF9saW5rLndjX3Jlc2V0X3ZhcmlhdGlvbl9hdHRyKCdocmVmJyk7XG4gICAgfTtcblxuICAgICQoZnVuY3Rpb24gKCkge1xuICAgICAgICBpZiAodHlwZW9mIHdjX2FkZF90b19jYXJ0X3ZhcmlhdGlvbl9wYXJhbXMgIT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICAkKCcudmFyaWF0aW9uc19mb3JtJykuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgJCh0aGlzKS53Y192YXJpYXRpb25fZm9ybSgpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cbiAgICB9KTtcblxuICAgIC8qKlxuICAgICAqIE1hdGNoZXMgaW5saW5lIHZhcmlhdGlvbiBvYmplY3RzIHRvIGNob3NlbiBhdHRyaWJ1dGVzXG4gICAgICogQGRlcHJlY2F0ZWQgMi42LjlcbiAgICAgKiBAdHlwZSB7T2JqZWN0fVxuICAgICAqL1xuICAgIHZhciB3Y192YXJpYXRpb25fZm9ybV9tYXRjaGVyID0ge1xuICAgICAgICBmaW5kX21hdGNoaW5nX3ZhcmlhdGlvbnMgOiBmdW5jdGlvbiAocHJvZHVjdF92YXJpYXRpb25zLCBzZXR0aW5ncykge1xuICAgICAgICAgICAgdmFyIG1hdGNoaW5nID0gW107XG4gICAgICAgICAgICBmb3IgKHZhciBpID0gMDsgaSA8IHByb2R1Y3RfdmFyaWF0aW9ucy5sZW5ndGg7IGkrKykge1xuICAgICAgICAgICAgICAgIHZhciB2YXJpYXRpb24gPSBwcm9kdWN0X3ZhcmlhdGlvbnNbaV07XG5cbiAgICAgICAgICAgICAgICBpZiAod2NfdmFyaWF0aW9uX2Zvcm1fbWF0Y2hlci52YXJpYXRpb25zX21hdGNoKHZhcmlhdGlvbi5hdHRyaWJ1dGVzLCBzZXR0aW5ncykpIHtcbiAgICAgICAgICAgICAgICAgICAgbWF0Y2hpbmcucHVzaCh2YXJpYXRpb24pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIHJldHVybiBtYXRjaGluZztcbiAgICAgICAgfSxcbiAgICAgICAgdmFyaWF0aW9uc19tYXRjaCAgICAgICAgIDogZnVuY3Rpb24gKGF0dHJzMSwgYXR0cnMyKSB7XG4gICAgICAgICAgICB2YXIgbWF0Y2ggPSB0cnVlO1xuICAgICAgICAgICAgZm9yICh2YXIgYXR0cl9uYW1lIGluIGF0dHJzMSkge1xuICAgICAgICAgICAgICAgIGlmIChhdHRyczEuaGFzT3duUHJvcGVydHkoYXR0cl9uYW1lKSkge1xuICAgICAgICAgICAgICAgICAgICB2YXIgdmFsMSA9IGF0dHJzMVthdHRyX25hbWVdO1xuICAgICAgICAgICAgICAgICAgICB2YXIgdmFsMiA9IGF0dHJzMlthdHRyX25hbWVdO1xuICAgICAgICAgICAgICAgICAgICBpZiAodmFsMSAhPT0gdW5kZWZpbmVkICYmIHZhbDIgIT09IHVuZGVmaW5lZCAmJiB2YWwxLmxlbmd0aCAhPT0gMCAmJiB2YWwyLmxlbmd0aCAhPT0gMCAmJiB2YWwxICE9PSB2YWwyKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBtYXRjaCA9IGZhbHNlO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICAgICAgcmV0dXJuIG1hdGNoO1xuICAgICAgICB9XG4gICAgfTtcblxufSkoalF1ZXJ5LCB3aW5kb3csIGRvY3VtZW50KTtcblxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyBzcmMvanMvYWRkLXRvLWNhcnQtdmFyaWF0aW9uLmpzIiwiLy8gcmVtb3ZlZCBieSBleHRyYWN0LXRleHQtd2VicGFjay1wbHVnaW5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3NyYy9zY3NzL2Zyb250ZW5kLnNjc3Ncbi8vIG1vZHVsZSBpZCA9IDJcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwiLy8gcmVtb3ZlZCBieSBleHRyYWN0LXRleHQtd2VicGFjay1wbHVnaW5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3NyYy9zY3NzL3RoZW1lLW92ZXJyaWRlLnNjc3Ncbi8vIG1vZHVsZSBpZCA9IDNcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwiLy8gcmVtb3ZlZCBieSBleHRyYWN0LXRleHQtd2VicGFjay1wbHVnaW5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3NyYy9zY3NzL2JhY2tlbmQuc2Nzc1xuLy8gbW9kdWxlIGlkID0gNFxuLy8gbW9kdWxlIGNodW5rcyA9IDAiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7O0FBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7Ozs7Ozs7Ozs7Ozs7Ozs7QUM3REE7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFEQTtBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQWhCQTtBQWtCQTtBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUFBO0FBQUE7QUFDQTtBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBcEJBO0FBc0JBO0FBRUE7QUFDQTtBQUNBO0FBQUE7QUFDQTtBQUVBO0FBQ0E7QUFDQTtBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQ0E7QUFTQTtBQUNBO0FBQ0E7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBREE7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7Ozs7QUFLQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUFBO0FBQUE7QUFDQTtBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFDQTtBQVFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQUE7QUFDQTtBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBRUE7QUFDQTtBQUNBO0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7O0FBSUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBSEE7QUFLQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7O0FBSUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFDQTtBQUtBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQ0E7QUFRQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUNBO0FBUUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Ozs7O0FBS0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUF4QkE7QUEyQkE7Ozs7OztBQ3B1QkE7Ozs7OztBQ0FBOzs7Ozs7QUNBQTs7O0EiLCJzb3VyY2VSb290IjoiIn0=