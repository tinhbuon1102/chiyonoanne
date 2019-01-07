/*!
 * WooCommerce Variation Gallery v1.1.19 
 * 
 * Author: Emran Ahmed ( emran.bd.08@gmail.com ) 
 * Date: 2019-1-1 16:47:40
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
__webpack_require__(3);
__webpack_require__(4);
__webpack_require__(5);
__webpack_require__(6);
module.exports = __webpack_require__(7);


/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

jQuery(function ($) {
    Promise.resolve().then(function () {
        return __webpack_require__(2);
    }).then(function () {

        // For Single Product

        $('.woo-variation-gallery-wrapper:not(.woo-variation-gallery-product-type-variable)').WooVariationGallery();

        // Ajax and Variation Product
        $(document).on('wc_variation_form', '.variations_form', function () {
            $('.woo-variation-gallery-wrapper').WooVariationGallery();
        });

        // Support for Jetpack's Infinite Scroll,
        $(document.body).on('post-load', function () {
            $('.woo-variation-gallery-wrapper').WooVariationGallery();
        });

        // YITH Quickview
        $(document).on('qv_loader_stop', function () {
            $('.woo-variation-gallery-wrapper').WooVariationGallery();
        });
    });
}); // end of jquery main wrapper

/***/ }),
/* 2 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

// ================================================================
// WooCommerce Variation Gallery
/*global wc_add_to_cart_variation_params, woo_variation_gallery_options */
// ================================================================

var WooVariationGallery = function ($) {

    var Default = {};

    var WooVariationGallery = function () {
        function WooVariationGallery(element, config) {
            _classCallCheck(this, WooVariationGallery);

            // Assign
            this._el = element;
            this._element = $(element);
            this._config = $.extend({}, Default, config);

            this.$product = this._element.closest('.product');
            this.$variations_form = this.$product.find('.variations_form');
            this.$target = this._element.parent();
            this.$slider = $('.woo-variation-gallery-slider', this._element);
            this.$thumbnail = $('.woo-variation-gallery-thumbnail-slider', this._element);
            this.thumbnail_columns = this._element.data('thumbnail_columns');
            this.product_id = this.$variations_form.data('product_id');
            this.is_variation_product = this.$variations_form.length > 0;
            this.initial_load = true;

            // Call
            this.defaultGallery();
            this.initVariationImagePreload();

            this.initEvents();

            if (this.is_variation_product) {
                this.initSlick();
                this.initZoom();
                this.initPhotoswipe();
            }

            if (!this.is_variation_product) {
                this.imagesLoaded();
            }

            this.initVariationGallery();

            this._element.data('woo_variation_gallery', this);
            $(document).trigger('woo_variation_gallery_init', [this]);
        }

        _createClass(WooVariationGallery, [{
            key: 'init',
            value: function init() {
                var _this = this;

                return _.debounce(function () {
                    _this.initSlick();
                    _this.initZoom();
                    _this.initPhotoswipe();
                }, 500);
            }
        }, {
            key: 'dimension',
            value: function dimension() {

                //this._element.css('min-height', '0px');
                //this._element.css('min-width', '0px');

                //return _.debounce(() => {
                //this._element.css('min-height', this.$slider.height() + 'px');
                //this._element.css('min-width', this.$slider.width() + 'px');
                //}, 400);
            }
        }, {
            key: 'initEvents',
            value: function initEvents() {
                // $(window).on('resize', this.dimension());
                this._element.on('woo_variation_gallery_image_loaded', this.init());
            }
        }, {
            key: 'initSlick',
            value: function initSlick() {
                var _this2 = this;

                if (this.$slider.hasClass('slick-initialized')) {
                    this.$slider.slick('unslick');
                }

                this.$slider.off('init');
                this.$slider.off('beforeChange');
                this.$slider.off('afterChange');

                this._element.trigger('woo_variation_gallery_before_init', [this]);

                // Slider

                this.$slider.on('init', function (event) {
                    if (_this2.initial_load) {
                        _this2.initial_load = false;
                        // this._element.css('min-height', this.$slider.height() + 'px');
                    }
                }).on('beforeChange', function (event, slick, currentSlide, nextSlide) {
                    _this2.$thumbnail.find('.wvg-gallery-thumbnail-image').not('.slick-slide').removeClass('current-thumbnail');
                    _this2.$thumbnail.find('.wvg-gallery-thumbnail-image').not('.slick-slide').eq(nextSlide).addClass('current-thumbnail');
                }).on('afterChange', function (event, slick, currentSlide) {
                    _this2.stopVideo(_this2.$slider);
                    _this2.initZoomForTarget(currentSlide);
                }).slick();

                // Thumbnails

                this.$thumbnail.find('.wvg-gallery-thumbnail-image').not('.slick-slide').first().addClass('current-thumbnail');

                this.$thumbnail.find('.wvg-gallery-thumbnail-image').not('.slick-slide').each(function (index, el) {
                    $(el).find('div, img').on('click', function (event) {
                        event.preventDefault();
                        event.stopPropagation();
                        _this2.$slider.slick('slickGoTo', index);
                    });
                });

                _.delay(function () {
                    _this2._element.trigger('woo_variation_gallery_slider_slick_init', [_this2]);
                }, 1);

                _.delay(function () {
                    _this2.removeLoadingClass();
                }, 100);
            }
        }, {
            key: 'initZoomForTarget',
            value: function initZoomForTarget(currentSlide) {

                if (!woo_variation_gallery_options.enable_gallery_zoom) {
                    return;
                }

                var galleryWidth = parseInt(this.$target.width()),
                    zoomEnabled = false,
                    zoomTarget = this.$slider.slick('getSlick').$slides.eq(currentSlide);

                $(zoomTarget).each(function (index, target) {
                    var image = $(target).find('img');

                    if (parseInt(image.data('large_image_width')) > galleryWidth) {
                        zoomEnabled = true;

                        return false;
                    }
                });

                // If zoom not included.
                if (!$().zoom) {
                    return;
                }

                // But only zoom if the img is larger than its container.
                if (zoomEnabled) {
                    var zoom_options = $.extend({
                        touch: false
                    }, wc_single_product_params.zoom_options);

                    if ('ontouchstart' in document.documentElement) {
                        zoom_options.on = 'click';
                    }

                    zoomTarget.trigger('zoom.destroy');
                    zoomTarget.zoom(zoom_options);
                }
            }
        }, {
            key: 'initZoom',
            value: function initZoom() {
                var currentSlide = this.$slider.slick('slickCurrentSlide');
                this.initZoomForTarget(currentSlide);
            }
        }, {
            key: 'initPhotoswipe',
            value: function initPhotoswipe() {
                var _this3 = this;

                if (!woo_variation_gallery_options.enable_gallery_lightbox) {
                    return;
                }

                this._element.off('click', '.woo-variation-gallery-trigger');
                this._element.off('click', '.wvg-gallery-image a');

                this._element.on('click', '.woo-variation-gallery-trigger', function (event) {
                    _this3.openPhotoswipe(event);
                });

                this._element.on('click', '.wvg-gallery-image a', function (event) {
                    _this3.openPhotoswipe(event);
                });
            }
        }, {
            key: 'openPhotoswipe',
            value: function openPhotoswipe(event) {
                var _this4 = this;

                event.preventDefault();

                if (typeof PhotoSwipe === 'undefined') {
                    return false;
                }

                var pswpElement = $('.pswp')[0],
                    items = this.getGalleryItems();

                var options = $.extend({
                    index: this.$slider.slick('slickCurrentSlide')
                }, wc_single_product_params.photoswipe_options);

                // Initializes and opens PhotoSwipe.

                var photoswipe = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);

                // Gallery starts closing
                photoswipe.listen('close', function () {
                    _this4.stopVideo(pswpElement);
                });

                photoswipe.listen('afterChange', function () {
                    _this4.stopVideo(pswpElement);
                });

                photoswipe.init();
            }
        }, {
            key: 'stopVideo',
            value: function stopVideo(element) {
                $(element).find('iframe, video').each(function () {
                    var tag = $(this).prop("tagName").toLowerCase();
                    if (tag === 'iframe') {
                        var src = $(this).attr('src');
                        $(this).attr('src', src);
                    }

                    if (tag === 'video') {
                        $(this)[0].pause();
                    }
                });
            }
        }, {
            key: 'addLoadingClass',
            value: function addLoadingClass() {
                this._element.addClass('loading-gallery');
            }
        }, {
            key: 'removeLoadingClass',
            value: function removeLoadingClass() {
                this._element.removeClass('loading-gallery');
            }
        }, {
            key: 'getGalleryItems',
            value: function getGalleryItems() {
                var $slides = this.$slider.slick('getSlick').$slides,
                    items = [];

                if ($slides.length > 0) {
                    $slides.each(function (i, el) {
                        var img = $(el).find('img, iframe, video');
                        var tag = $(img).prop("tagName").toLowerCase();

                        var src = void 0,
                            item = void 0;
                        switch (tag) {
                            case 'img':
                                var large_image_src = img.attr('data-large_image'),
                                    large_image_w = img.attr('data-large_image_width'),
                                    large_image_h = img.attr('data-large_image_height');
                                item = {
                                    src: large_image_src,
                                    w: large_image_w,
                                    h: large_image_h,
                                    title: img.attr('data-caption') ? img.attr('data-caption') : img.attr('title')
                                };
                                break;
                            case 'iframe':
                                src = img.attr('src');
                                item = {
                                    html: '<iframe class="wvg-lightbox-iframe" src="' + src + '" style="width: 100%; height: 100%; margin: 0;padding: 0; background-color: #000000" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>'
                                };
                                break;
                            case 'video':
                                src = img.attr('src');
                                item = {
                                    html: '<video class="wvg-lightbox-video" controls controlsList="nodownload" src="' + src + '" style="width: 100%; height: 100%; margin: 0;padding: 0; background-color: #000000"></video>'
                                };
                                break;
                        }

                        items.push(item);
                    });
                }
                return items;
            }
        }, {
            key: 'destroySlick',
            value: function destroySlick() {

                this.$slider.html('');
                this.$thumbnail.html('');

                if (this.$slider.hasClass('slick-initialized')) {
                    this.$slider.slick('unslick');
                }

                this._element.trigger('woo_variation_gallery_slick_destroy', [this]);
            }
        }, {
            key: 'defaultGallery',
            value: function defaultGallery() {
                var _this5 = this;

                if (this.is_variation_product) {
                    wp.ajax.send('wvg_get_default_gallery', {
                        data: {
                            product_id: this.product_id
                        },
                        success: function success(data) {
                            _this5._element.data('woo_variation_gallery_default', data);
                            _this5._element.trigger('woo_variation_default_gallery_loaded', _this5);
                        },
                        error: function error(e) {
                            _this5._element.data('woo_variation_gallery_default', []);
                            _this5._element.trigger('woo_variation_default_gallery_loaded', _this5);
                            console.error('Variation Gallery not available on variation id ' + _this5.product_id + '.');
                        }
                    });
                }
            }
        }, {
            key: 'initVariationImagePreload',
            value: function initVariationImagePreload() {
                var _this6 = this;

                //return;
                if (this.is_variation_product) {
                    wp.ajax.send('wvg_get_available_variation_images', {
                        data: {
                            product_id: this.product_id
                        },
                        success: function success(images) {
                            // console.log(data)
                            if (images.length > 1) {
                                _this6.imagePreload(images);
                            }
                            _this6._element.data('woo_variation_gallery_variation_images', images);
                        },
                        error: function error(e) {
                            _this6._element.data('woo_variation_gallery_variation_images', []);
                            console.error('Variation Gallery variations images not available on variation id ' + _this6.product_id + '.');
                        }
                    });
                }
            }
        }, {
            key: 'imagePreload',
            value: function imagePreload(images) {
                for (var i = 0; i < images.length; i++) {
                    try {

                        // Note: this won't work when chrome devtool is open and 'disable cache' is enabled within the network panel
                        /*let _img       = new Image();
                        let _gallery   = new Image();
                        let _full      = new Image();
                        let _thumbnail = new Image();
                         _img.src    = images[i].src;
                        _img.srcset = images[i].srcset;
                         _gallery.src = images[i].gallery_thumbnail_src;
                         _full.src = images[i].full_src;
                         _thumbnail.src = images[i].thumb_src;*/

                        // Append Content
                        var _img_src = images[i].src;
                        var _img_srcset = images[i].srcset;

                        var _gallery_src = images[i].gallery_thumbnail_src;
                        var _full_src = images[i].full_src;
                        var _thumbnail_src = images[i].thumb_src;

                        if (!!_img_srcset) {
                            var _template = '<div style="display: none"><img aria-hidden="true" style="display: none" src="' + _img_src + '" srcset="' + _img_srcset + '" /><img style="display: none" src="' + _gallery_src + '" /><img style="display: none" src="' + _thumbnail_src + '" /><img style="display: none" src="' + _full_src + '" /></div>';
                        } else {
                            var _template2 = '<div style="display: none"><img aria-hidden="true" style="display: none" src="' + _img_src + '" /><img style="display: none" src="' + _gallery_src + '" /><img style="display: none" src="' + _thumbnail_src + '" /><img style="display: none" src="' + _full_src + '" /></div>';
                        }

                        // let template = `<div style="display: none"><img aria-hidden="true" style="display: none" src="${_img_src}" srcset="${_img_srcset}" /><img style="display: none" src="${_gallery_src}" /><img style="display: none" src="${_thumbnail_src}" /><img style="display: none" src="${_full_src}" /></div>`;
                        $('body').append(template);
                    } catch (e) {
                        console.error(e);
                    }
                }
            }
        }, {
            key: 'initVariationGallery',
            value: function initVariationGallery() {
                var _this7 = this;

                // show_variation, found_variation

                this.$variations_form.off('reset_image.wvg');
                this.$variations_form.off('click.wvg');
                this.$variations_form.off('show_variation.wvg');

                if (woo_variation_gallery_options.gallery_reset_on_variation_change) {
                    this.$variations_form.on('reset_image.wvg', function (event) {
                        _this7.addLoadingClass();
                        _this7.galleryReset();
                    });
                } else {
                    this.$variations_form.on('click.wvg', '.reset_variations', function (event) {
                        _this7.addLoadingClass();
                        _this7.galleryReset();
                    });
                }

                this.$variations_form.on('show_variation.wvg', function (event, variation) {
                    _this7.addLoadingClass();
                    _this7.galleryInit(variation.variation_gallery_images);
                });
            }
        }, {
            key: 'galleryReset',
            value: function galleryReset() {
                var _this8 = this;

                var $default_gallery = this._element.data('woo_variation_gallery_default');

                if ($default_gallery && $default_gallery.length > 0) {
                    this.galleryInit($default_gallery);
                } else {
                    _.delay(function () {
                        _this8.removeLoadingClass();
                    }, 100);
                }
            }
        }, {
            key: 'galleryInit',
            value: function galleryInit(images) {
                var _this9 = this;

                var hasGallery = images.length > 1;

                this._element.trigger('before_woo_variation_gallery_init', [this, images]);

                this.destroySlick();

                var slider_inner_html = images.map(function (image) {
                    var template = wp.template('woo-variation-gallery-slider-template');
                    return template(image);
                }).join('');

                var thumbnail_inner_html = images.map(function (image) {
                    var template = wp.template('woo-variation-gallery-thumbnail-template');
                    return template(image);
                }).join('');

                if (hasGallery) {
                    this.$target.addClass('woo-variation-gallery-has-product-thumbnail');
                } else {
                    this.$target.removeClass('woo-variation-gallery-has-product-thumbnail');
                }

                this.$slider.html(slider_inner_html);

                if (hasGallery) {
                    this.$thumbnail.html(thumbnail_inner_html);
                } else {
                    this.$thumbnail.html('');
                }

                //this._element.trigger('woo_variation_gallery_init', [this, images]);

                _.delay(function () {
                    _this9.imagesLoaded();
                }, 1);

                //this._element.trigger('after_woo_variation_gallery_init', [this, images]);
            }
        }, {
            key: 'imagesLoaded',
            value: function imagesLoaded() {
                var _this10 = this;

                // Some Script Add Custom imagesLoaded Function
                if (!$().imagesLoaded.done) {
                    this._element.trigger('woo_variation_gallery_image_loading', [this]);
                    this._element.trigger('woo_variation_gallery_image_loaded', [this]);
                    return;
                }

                this._element.imagesLoaded().progress(function (instance, image) {
                    _this10._element.trigger('woo_variation_gallery_image_loading', [_this10]);
                }).done(function (instance) {
                    _this10._element.trigger('woo_variation_gallery_image_loaded', [_this10]);
                });
            }
        }], [{
            key: '_jQueryInterface',
            value: function _jQueryInterface(config) {
                return this.each(function () {
                    new WooVariationGallery(this, config);
                });
            }
        }]);

        return WooVariationGallery;
    }();

    /**
     * ------------------------------------------------------------------------
     * jQuery
     * ------------------------------------------------------------------------
     */

    $.fn['WooVariationGallery'] = WooVariationGallery._jQueryInterface;
    $.fn['WooVariationGallery'].Constructor = WooVariationGallery;
    $.fn['WooVariationGallery'].noConflict = function () {
        $.fn['WooVariationGallery'] = $.fn['WooVariationGallery'];
        return WooVariationGallery._jQueryInterface;
    };

    return WooVariationGallery;
}(jQuery);

/* harmony default export */ __webpack_exports__["default"] = (WooVariationGallery);

/***/ }),
/* 3 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 4 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 5 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 6 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 7 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiYXNzZXRzL2pzL2Zyb250ZW5kLmpzIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vL3dlYnBhY2svYm9vdHN0cmFwIDMxZDYyZGVkNWEwYWYxY2FiMTkzIiwid2VicGFjazovLy9zcmMvanMvZnJvbnRlbmQuanMiLCJ3ZWJwYWNrOi8vL3NyYy9qcy9Xb29WYXJpYXRpb25HYWxsZXJ5LmpzIiwid2VicGFjazovLy8uL3NyYy9zY3NzL3NsaWNrLnNjc3M/MmMzMyIsIndlYnBhY2s6Ly8vLi9zcmMvc2Nzcy9mcm9udGVuZC5zY3NzPzJjM2EiLCJ3ZWJwYWNrOi8vLy4vc3JjL3Njc3MvdGhlbWUtc3VwcG9ydC5zY3NzIiwid2VicGFjazovLy8uL3NyYy9zY3NzL2JhY2tlbmQuc2Nzcz9iZjUwIiwid2VicGFjazovLy8uL3NyYy9zY3NzL2d3cC1hZG1pbi5zY3NzP2FmZjMiXSwic291cmNlc0NvbnRlbnQiOlsiIFx0Ly8gVGhlIG1vZHVsZSBjYWNoZVxuIFx0dmFyIGluc3RhbGxlZE1vZHVsZXMgPSB7fTtcblxuIFx0Ly8gVGhlIHJlcXVpcmUgZnVuY3Rpb25cbiBcdGZ1bmN0aW9uIF9fd2VicGFja19yZXF1aXJlX18obW9kdWxlSWQpIHtcblxuIFx0XHQvLyBDaGVjayBpZiBtb2R1bGUgaXMgaW4gY2FjaGVcbiBcdFx0aWYoaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0pIHtcbiBcdFx0XHRyZXR1cm4gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0uZXhwb3J0cztcbiBcdFx0fVxuIFx0XHQvLyBDcmVhdGUgYSBuZXcgbW9kdWxlIChhbmQgcHV0IGl0IGludG8gdGhlIGNhY2hlKVxuIFx0XHR2YXIgbW9kdWxlID0gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0gPSB7XG4gXHRcdFx0aTogbW9kdWxlSWQsXG4gXHRcdFx0bDogZmFsc2UsXG4gXHRcdFx0ZXhwb3J0czoge31cbiBcdFx0fTtcblxuIFx0XHQvLyBFeGVjdXRlIHRoZSBtb2R1bGUgZnVuY3Rpb25cbiBcdFx0bW9kdWxlc1ttb2R1bGVJZF0uY2FsbChtb2R1bGUuZXhwb3J0cywgbW9kdWxlLCBtb2R1bGUuZXhwb3J0cywgX193ZWJwYWNrX3JlcXVpcmVfXyk7XG5cbiBcdFx0Ly8gRmxhZyB0aGUgbW9kdWxlIGFzIGxvYWRlZFxuIFx0XHRtb2R1bGUubCA9IHRydWU7XG5cbiBcdFx0Ly8gUmV0dXJuIHRoZSBleHBvcnRzIG9mIHRoZSBtb2R1bGVcbiBcdFx0cmV0dXJuIG1vZHVsZS5leHBvcnRzO1xuIFx0fVxuXG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlcyBvYmplY3QgKF9fd2VicGFja19tb2R1bGVzX18pXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm0gPSBtb2R1bGVzO1xuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZSBjYWNoZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5jID0gaW5zdGFsbGVkTW9kdWxlcztcblxuIFx0Ly8gZGVmaW5lIGdldHRlciBmdW5jdGlvbiBmb3IgaGFybW9ueSBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQgPSBmdW5jdGlvbihleHBvcnRzLCBuYW1lLCBnZXR0ZXIpIHtcbiBcdFx0aWYoIV9fd2VicGFja19yZXF1aXJlX18ubyhleHBvcnRzLCBuYW1lKSkge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBuYW1lLCB7XG4gXHRcdFx0XHRjb25maWd1cmFibGU6IGZhbHNlLFxuIFx0XHRcdFx0ZW51bWVyYWJsZTogdHJ1ZSxcbiBcdFx0XHRcdGdldDogZ2V0dGVyXG4gXHRcdFx0fSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGdldERlZmF1bHRFeHBvcnQgZnVuY3Rpb24gZm9yIGNvbXBhdGliaWxpdHkgd2l0aCBub24taGFybW9ueSBtb2R1bGVzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm4gPSBmdW5jdGlvbihtb2R1bGUpIHtcbiBcdFx0dmFyIGdldHRlciA9IG1vZHVsZSAmJiBtb2R1bGUuX19lc01vZHVsZSA/XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0RGVmYXVsdCgpIHsgcmV0dXJuIG1vZHVsZVsnZGVmYXVsdCddOyB9IDpcbiBcdFx0XHRmdW5jdGlvbiBnZXRNb2R1bGVFeHBvcnRzKCkgeyByZXR1cm4gbW9kdWxlOyB9O1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQoZ2V0dGVyLCAnYScsIGdldHRlcik7XG4gXHRcdHJldHVybiBnZXR0ZXI7XG4gXHR9O1xuXG4gXHQvLyBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGxcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubyA9IGZ1bmN0aW9uKG9iamVjdCwgcHJvcGVydHkpIHsgcmV0dXJuIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChvYmplY3QsIHByb3BlcnR5KTsgfTtcblxuIFx0Ly8gX193ZWJwYWNrX3B1YmxpY19wYXRoX19cbiBcdF9fd2VicGFja19yZXF1aXJlX18ucCA9IFwiXCI7XG5cbiBcdC8vIExvYWQgZW50cnkgbW9kdWxlIGFuZCByZXR1cm4gZXhwb3J0c1xuIFx0cmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18oX193ZWJwYWNrX3JlcXVpcmVfXy5zID0gMCk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gd2VicGFjay9ib290c3RyYXAgMzFkNjJkZWQ1YTBhZjFjYWIxOTMiLCJqUXVlcnkoJCA9PiB7XG4gICAgaW1wb3J0KCcuL1dvb1ZhcmlhdGlvbkdhbGxlcnknKS50aGVuKCgpID0+IHtcblxuICAgICAgICAvLyBGb3IgU2luZ2xlIFByb2R1Y3RcblxuICAgICAgICAkKCcud29vLXZhcmlhdGlvbi1nYWxsZXJ5LXdyYXBwZXI6bm90KC53b28tdmFyaWF0aW9uLWdhbGxlcnktcHJvZHVjdC10eXBlLXZhcmlhYmxlKScpLldvb1ZhcmlhdGlvbkdhbGxlcnkoKTtcblxuICAgICAgICAvLyBBamF4IGFuZCBWYXJpYXRpb24gUHJvZHVjdFxuICAgICAgICAkKGRvY3VtZW50KS5vbignd2NfdmFyaWF0aW9uX2Zvcm0nLCAnLnZhcmlhdGlvbnNfZm9ybScsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICQoJy53b28tdmFyaWF0aW9uLWdhbGxlcnktd3JhcHBlcicpLldvb1ZhcmlhdGlvbkdhbGxlcnkoKTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgLy8gU3VwcG9ydCBmb3IgSmV0cGFjaydzIEluZmluaXRlIFNjcm9sbCxcbiAgICAgICAgJChkb2N1bWVudC5ib2R5KS5vbigncG9zdC1sb2FkJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgJCgnLndvby12YXJpYXRpb24tZ2FsbGVyeS13cmFwcGVyJykuV29vVmFyaWF0aW9uR2FsbGVyeSgpO1xuICAgICAgICB9KTtcblxuICAgICAgICAvLyBZSVRIIFF1aWNrdmlld1xuICAgICAgICAkKGRvY3VtZW50KS5vbigncXZfbG9hZGVyX3N0b3AnLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAkKCcud29vLXZhcmlhdGlvbi1nYWxsZXJ5LXdyYXBwZXInKS5Xb29WYXJpYXRpb25HYWxsZXJ5KCk7XG4gICAgICAgIH0pO1xuICAgIH0pO1xufSk7ICAvLyBlbmQgb2YganF1ZXJ5IG1haW4gd3JhcHBlclxuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIHNyYy9qcy9mcm9udGVuZC5qcyIsIi8vID09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT1cbi8vIFdvb0NvbW1lcmNlIFZhcmlhdGlvbiBHYWxsZXJ5XG4vKmdsb2JhbCB3Y19hZGRfdG9fY2FydF92YXJpYXRpb25fcGFyYW1zLCB3b29fdmFyaWF0aW9uX2dhbGxlcnlfb3B0aW9ucyAqL1xuLy8gPT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PVxuXG5jb25zdCBXb29WYXJpYXRpb25HYWxsZXJ5ID0gKCgkKSA9PiB7XG5cbiAgICBjb25zdCBEZWZhdWx0ID0ge307XG5cbiAgICBjbGFzcyBXb29WYXJpYXRpb25HYWxsZXJ5IHtcblxuICAgICAgICBjb25zdHJ1Y3RvcihlbGVtZW50LCBjb25maWcpIHtcblxuICAgICAgICAgICAgLy8gQXNzaWduXG4gICAgICAgICAgICB0aGlzLl9lbCAgICAgID0gZWxlbWVudDtcbiAgICAgICAgICAgIHRoaXMuX2VsZW1lbnQgPSAkKGVsZW1lbnQpO1xuICAgICAgICAgICAgdGhpcy5fY29uZmlnICA9ICQuZXh0ZW5kKHt9LCBEZWZhdWx0LCBjb25maWcpO1xuXG4gICAgICAgICAgICB0aGlzLiRwcm9kdWN0ICAgICAgICAgICAgID0gdGhpcy5fZWxlbWVudC5jbG9zZXN0KCcucHJvZHVjdCcpO1xuICAgICAgICAgICAgdGhpcy4kdmFyaWF0aW9uc19mb3JtICAgICA9IHRoaXMuJHByb2R1Y3QuZmluZCgnLnZhcmlhdGlvbnNfZm9ybScpO1xuICAgICAgICAgICAgdGhpcy4kdGFyZ2V0ICAgICAgICAgICAgICA9IHRoaXMuX2VsZW1lbnQucGFyZW50KCk7XG4gICAgICAgICAgICB0aGlzLiRzbGlkZXIgICAgICAgICAgICAgID0gJCgnLndvby12YXJpYXRpb24tZ2FsbGVyeS1zbGlkZXInLCB0aGlzLl9lbGVtZW50KTtcbiAgICAgICAgICAgIHRoaXMuJHRodW1ibmFpbCAgICAgICAgICAgPSAkKCcud29vLXZhcmlhdGlvbi1nYWxsZXJ5LXRodW1ibmFpbC1zbGlkZXInLCB0aGlzLl9lbGVtZW50KTtcbiAgICAgICAgICAgIHRoaXMudGh1bWJuYWlsX2NvbHVtbnMgICAgPSB0aGlzLl9lbGVtZW50LmRhdGEoJ3RodW1ibmFpbF9jb2x1bW5zJyk7XG4gICAgICAgICAgICB0aGlzLnByb2R1Y3RfaWQgICAgICAgICAgID0gdGhpcy4kdmFyaWF0aW9uc19mb3JtLmRhdGEoJ3Byb2R1Y3RfaWQnKTtcbiAgICAgICAgICAgIHRoaXMuaXNfdmFyaWF0aW9uX3Byb2R1Y3QgPSAodGhpcy4kdmFyaWF0aW9uc19mb3JtLmxlbmd0aCA+IDApO1xuICAgICAgICAgICAgdGhpcy5pbml0aWFsX2xvYWQgICAgICAgICA9IHRydWU7XG5cbiAgICAgICAgICAgIC8vIENhbGxcbiAgICAgICAgICAgIHRoaXMuZGVmYXVsdEdhbGxlcnkoKTtcbiAgICAgICAgICAgIHRoaXMuaW5pdFZhcmlhdGlvbkltYWdlUHJlbG9hZCgpO1xuXG4gICAgICAgICAgICB0aGlzLmluaXRFdmVudHMoKTtcblxuICAgICAgICAgICAgaWYgKHRoaXMuaXNfdmFyaWF0aW9uX3Byb2R1Y3QpIHtcbiAgICAgICAgICAgICAgICB0aGlzLmluaXRTbGljaygpO1xuICAgICAgICAgICAgICAgIHRoaXMuaW5pdFpvb20oKTtcbiAgICAgICAgICAgICAgICB0aGlzLmluaXRQaG90b3N3aXBlKCk7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGlmICghdGhpcy5pc192YXJpYXRpb25fcHJvZHVjdCkge1xuICAgICAgICAgICAgICAgIHRoaXMuaW1hZ2VzTG9hZGVkKCk7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHRoaXMuaW5pdFZhcmlhdGlvbkdhbGxlcnkoKTtcblxuICAgICAgICAgICAgdGhpcy5fZWxlbWVudC5kYXRhKCd3b29fdmFyaWF0aW9uX2dhbGxlcnknLCB0aGlzKTtcbiAgICAgICAgICAgICQoZG9jdW1lbnQpLnRyaWdnZXIoJ3dvb192YXJpYXRpb25fZ2FsbGVyeV9pbml0JywgW3RoaXNdKTtcbiAgICAgICAgfVxuXG4gICAgICAgIHN0YXRpYyBfalF1ZXJ5SW50ZXJmYWNlKGNvbmZpZykge1xuICAgICAgICAgICAgcmV0dXJuIHRoaXMuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgbmV3IFdvb1ZhcmlhdGlvbkdhbGxlcnkodGhpcywgY29uZmlnKVxuICAgICAgICAgICAgfSlcbiAgICAgICAgfVxuXG4gICAgICAgIGluaXQoKSB7XG4gICAgICAgICAgICByZXR1cm4gXy5kZWJvdW5jZSgoKSA9PiB7XG4gICAgICAgICAgICAgICAgdGhpcy5pbml0U2xpY2soKTtcbiAgICAgICAgICAgICAgICB0aGlzLmluaXRab29tKCk7XG4gICAgICAgICAgICAgICAgdGhpcy5pbml0UGhvdG9zd2lwZSgpO1xuICAgICAgICAgICAgfSwgNTAwKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGRpbWVuc2lvbigpIHtcblxuICAgICAgICAgICAgLy90aGlzLl9lbGVtZW50LmNzcygnbWluLWhlaWdodCcsICcwcHgnKTtcbiAgICAgICAgICAgIC8vdGhpcy5fZWxlbWVudC5jc3MoJ21pbi13aWR0aCcsICcwcHgnKTtcblxuICAgICAgICAgICAgLy9yZXR1cm4gXy5kZWJvdW5jZSgoKSA9PiB7XG4gICAgICAgICAgICAvL3RoaXMuX2VsZW1lbnQuY3NzKCdtaW4taGVpZ2h0JywgdGhpcy4kc2xpZGVyLmhlaWdodCgpICsgJ3B4Jyk7XG4gICAgICAgICAgICAvL3RoaXMuX2VsZW1lbnQuY3NzKCdtaW4td2lkdGgnLCB0aGlzLiRzbGlkZXIud2lkdGgoKSArICdweCcpO1xuICAgICAgICAgICAgLy99LCA0MDApO1xuICAgICAgICB9XG5cbiAgICAgICAgaW5pdEV2ZW50cygpIHtcbiAgICAgICAgICAgIC8vICQod2luZG93KS5vbigncmVzaXplJywgdGhpcy5kaW1lbnNpb24oKSk7XG4gICAgICAgICAgICB0aGlzLl9lbGVtZW50Lm9uKCd3b29fdmFyaWF0aW9uX2dhbGxlcnlfaW1hZ2VfbG9hZGVkJywgdGhpcy5pbml0KCkpO1xuICAgICAgICB9XG5cbiAgICAgICAgaW5pdFNsaWNrKCkge1xuXG4gICAgICAgICAgICBpZiAodGhpcy4kc2xpZGVyLmhhc0NsYXNzKCdzbGljay1pbml0aWFsaXplZCcpKSB7XG4gICAgICAgICAgICAgICAgdGhpcy4kc2xpZGVyLnNsaWNrKCd1bnNsaWNrJyk7XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHRoaXMuJHNsaWRlci5vZmYoJ2luaXQnKTtcbiAgICAgICAgICAgIHRoaXMuJHNsaWRlci5vZmYoJ2JlZm9yZUNoYW5nZScpO1xuICAgICAgICAgICAgdGhpcy4kc2xpZGVyLm9mZignYWZ0ZXJDaGFuZ2UnKTtcblxuICAgICAgICAgICAgdGhpcy5fZWxlbWVudC50cmlnZ2VyKCd3b29fdmFyaWF0aW9uX2dhbGxlcnlfYmVmb3JlX2luaXQnLCBbdGhpc10pO1xuXG4gICAgICAgICAgICAvLyBTbGlkZXJcblxuICAgICAgICAgICAgdGhpcy4kc2xpZGVyXG4gICAgICAgICAgICAgICAgLm9uKCdpbml0JywgKGV2ZW50KSA9PiB7XG4gICAgICAgICAgICAgICAgICAgIGlmICh0aGlzLmluaXRpYWxfbG9hZCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5pbml0aWFsX2xvYWQgPSBmYWxzZTtcbiAgICAgICAgICAgICAgICAgICAgICAgIC8vIHRoaXMuX2VsZW1lbnQuY3NzKCdtaW4taGVpZ2h0JywgdGhpcy4kc2xpZGVyLmhlaWdodCgpICsgJ3B4Jyk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9KVxuICAgICAgICAgICAgICAgIC5vbignYmVmb3JlQ2hhbmdlJywgKGV2ZW50LCBzbGljaywgY3VycmVudFNsaWRlLCBuZXh0U2xpZGUpID0+IHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy4kdGh1bWJuYWlsLmZpbmQoJy53dmctZ2FsbGVyeS10aHVtYm5haWwtaW1hZ2UnKS5ub3QoJy5zbGljay1zbGlkZScpLnJlbW92ZUNsYXNzKCdjdXJyZW50LXRodW1ibmFpbCcpO1xuICAgICAgICAgICAgICAgICAgICB0aGlzLiR0aHVtYm5haWwuZmluZCgnLnd2Zy1nYWxsZXJ5LXRodW1ibmFpbC1pbWFnZScpLm5vdCgnLnNsaWNrLXNsaWRlJykuZXEobmV4dFNsaWRlKS5hZGRDbGFzcygnY3VycmVudC10aHVtYm5haWwnKTtcbiAgICAgICAgICAgICAgICB9KVxuICAgICAgICAgICAgICAgIC5vbignYWZ0ZXJDaGFuZ2UnLCAoZXZlbnQsIHNsaWNrLCBjdXJyZW50U2xpZGUpID0+IHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5zdG9wVmlkZW8odGhpcy4kc2xpZGVyKTtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5pbml0Wm9vbUZvclRhcmdldChjdXJyZW50U2xpZGUpO1xuICAgICAgICAgICAgICAgIH0pXG4gICAgICAgICAgICAgICAgLnNsaWNrKCk7XG5cbiAgICAgICAgICAgIC8vIFRodW1ibmFpbHNcblxuICAgICAgICAgICAgdGhpcy4kdGh1bWJuYWlsLmZpbmQoJy53dmctZ2FsbGVyeS10aHVtYm5haWwtaW1hZ2UnKS5ub3QoJy5zbGljay1zbGlkZScpLmZpcnN0KCkuYWRkQ2xhc3MoJ2N1cnJlbnQtdGh1bWJuYWlsJyk7XG5cbiAgICAgICAgICAgIHRoaXMuJHRodW1ibmFpbC5maW5kKCcud3ZnLWdhbGxlcnktdGh1bWJuYWlsLWltYWdlJykubm90KCcuc2xpY2stc2xpZGUnKS5lYWNoKChpbmRleCwgZWwpID0+IHtcbiAgICAgICAgICAgICAgICAkKGVsKS5maW5kKCdkaXYsIGltZycpLm9uKCdjbGljaycsIChldmVudCkgPT4ge1xuICAgICAgICAgICAgICAgICAgICBldmVudC5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgICAgICAgICBldmVudC5zdG9wUHJvcGFnYXRpb24oKTtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy4kc2xpZGVyLnNsaWNrKCdzbGlja0dvVG8nLCBpbmRleCk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgXy5kZWxheSgoKSA9PiB7XG4gICAgICAgICAgICAgICAgdGhpcy5fZWxlbWVudC50cmlnZ2VyKCd3b29fdmFyaWF0aW9uX2dhbGxlcnlfc2xpZGVyX3NsaWNrX2luaXQnLCBbdGhpc10pO1xuICAgICAgICAgICAgfSwgMSk7XG5cbiAgICAgICAgICAgIF8uZGVsYXkoKCkgPT4ge1xuICAgICAgICAgICAgICAgIHRoaXMucmVtb3ZlTG9hZGluZ0NsYXNzKCk7XG4gICAgICAgICAgICB9LCAxMDApO1xuICAgICAgICB9XG5cbiAgICAgICAgaW5pdFpvb21Gb3JUYXJnZXQoY3VycmVudFNsaWRlKSB7XG5cbiAgICAgICAgICAgIGlmICghd29vX3ZhcmlhdGlvbl9nYWxsZXJ5X29wdGlvbnMuZW5hYmxlX2dhbGxlcnlfem9vbSkge1xuICAgICAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgbGV0IGdhbGxlcnlXaWR0aCA9IHBhcnNlSW50KHRoaXMuJHRhcmdldC53aWR0aCgpKSxcbiAgICAgICAgICAgICAgICB6b29tRW5hYmxlZCAgPSBmYWxzZSxcbiAgICAgICAgICAgICAgICB6b29tVGFyZ2V0ICAgPSB0aGlzLiRzbGlkZXIuc2xpY2soJ2dldFNsaWNrJykuJHNsaWRlcy5lcShjdXJyZW50U2xpZGUpO1xuXG4gICAgICAgICAgICAkKHpvb21UYXJnZXQpLmVhY2goZnVuY3Rpb24gKGluZGV4LCB0YXJnZXQpIHtcbiAgICAgICAgICAgICAgICBsZXQgaW1hZ2UgPSAkKHRhcmdldCkuZmluZCgnaW1nJyk7XG5cbiAgICAgICAgICAgICAgICBpZiAocGFyc2VJbnQoaW1hZ2UuZGF0YSgnbGFyZ2VfaW1hZ2Vfd2lkdGgnKSkgPiBnYWxsZXJ5V2lkdGgpIHtcbiAgICAgICAgICAgICAgICAgICAgem9vbUVuYWJsZWQgPSB0cnVlO1xuXG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgLy8gSWYgem9vbSBub3QgaW5jbHVkZWQuXG4gICAgICAgICAgICBpZiAoISQoKS56b29tKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAvLyBCdXQgb25seSB6b29tIGlmIHRoZSBpbWcgaXMgbGFyZ2VyIHRoYW4gaXRzIGNvbnRhaW5lci5cbiAgICAgICAgICAgIGlmICh6b29tRW5hYmxlZCkge1xuICAgICAgICAgICAgICAgIGxldCB6b29tX29wdGlvbnMgPSAkLmV4dGVuZCh7XG4gICAgICAgICAgICAgICAgICAgIHRvdWNoIDogZmFsc2VcbiAgICAgICAgICAgICAgICB9LCB3Y19zaW5nbGVfcHJvZHVjdF9wYXJhbXMuem9vbV9vcHRpb25zKTtcblxuICAgICAgICAgICAgICAgIGlmICgnb250b3VjaHN0YXJ0JyBpbiBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQpIHtcbiAgICAgICAgICAgICAgICAgICAgem9vbV9vcHRpb25zLm9uID0gJ2NsaWNrJztcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICB6b29tVGFyZ2V0LnRyaWdnZXIoJ3pvb20uZGVzdHJveScpO1xuICAgICAgICAgICAgICAgIHpvb21UYXJnZXQuem9vbSh6b29tX29wdGlvbnMpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG5cbiAgICAgICAgaW5pdFpvb20oKSB7XG4gICAgICAgICAgICBsZXQgY3VycmVudFNsaWRlID0gdGhpcy4kc2xpZGVyLnNsaWNrKCdzbGlja0N1cnJlbnRTbGlkZScpO1xuICAgICAgICAgICAgdGhpcy5pbml0Wm9vbUZvclRhcmdldChjdXJyZW50U2xpZGUpO1xuICAgICAgICB9XG5cbiAgICAgICAgaW5pdFBob3Rvc3dpcGUoKSB7XG5cbiAgICAgICAgICAgIGlmICghd29vX3ZhcmlhdGlvbl9nYWxsZXJ5X29wdGlvbnMuZW5hYmxlX2dhbGxlcnlfbGlnaHRib3gpIHtcbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHRoaXMuX2VsZW1lbnQub2ZmKCdjbGljaycsICcud29vLXZhcmlhdGlvbi1nYWxsZXJ5LXRyaWdnZXInKTtcbiAgICAgICAgICAgIHRoaXMuX2VsZW1lbnQub2ZmKCdjbGljaycsICcud3ZnLWdhbGxlcnktaW1hZ2UgYScpO1xuXG4gICAgICAgICAgICB0aGlzLl9lbGVtZW50Lm9uKCdjbGljaycsICcud29vLXZhcmlhdGlvbi1nYWxsZXJ5LXRyaWdnZXInLCAoZXZlbnQpID0+IHtcbiAgICAgICAgICAgICAgICB0aGlzLm9wZW5QaG90b3N3aXBlKGV2ZW50KVxuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIHRoaXMuX2VsZW1lbnQub24oJ2NsaWNrJywgJy53dmctZ2FsbGVyeS1pbWFnZSBhJywgKGV2ZW50KSA9PiB7XG4gICAgICAgICAgICAgICAgdGhpcy5vcGVuUGhvdG9zd2lwZShldmVudCk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuXG4gICAgICAgIG9wZW5QaG90b3N3aXBlKGV2ZW50KSB7XG5cbiAgICAgICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG5cbiAgICAgICAgICAgIGlmICh0eXBlb2YoUGhvdG9Td2lwZSkgPT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBsZXQgcHN3cEVsZW1lbnQgPSAkKCcucHN3cCcpWzBdLFxuICAgICAgICAgICAgICAgIGl0ZW1zICAgICAgID0gdGhpcy5nZXRHYWxsZXJ5SXRlbXMoKTtcblxuICAgICAgICAgICAgbGV0IG9wdGlvbnMgPSAkLmV4dGVuZCh7XG4gICAgICAgICAgICAgICAgaW5kZXggOiB0aGlzLiRzbGlkZXIuc2xpY2soJ3NsaWNrQ3VycmVudFNsaWRlJylcbiAgICAgICAgICAgIH0sIHdjX3NpbmdsZV9wcm9kdWN0X3BhcmFtcy5waG90b3N3aXBlX29wdGlvbnMpO1xuXG4gICAgICAgICAgICAvLyBJbml0aWFsaXplcyBhbmQgb3BlbnMgUGhvdG9Td2lwZS5cblxuICAgICAgICAgICAgbGV0IHBob3Rvc3dpcGUgPSBuZXcgUGhvdG9Td2lwZShwc3dwRWxlbWVudCwgUGhvdG9Td2lwZVVJX0RlZmF1bHQsIGl0ZW1zLCBvcHRpb25zKTtcblxuICAgICAgICAgICAgLy8gR2FsbGVyeSBzdGFydHMgY2xvc2luZ1xuICAgICAgICAgICAgcGhvdG9zd2lwZS5saXN0ZW4oJ2Nsb3NlJywgKCkgPT4ge1xuICAgICAgICAgICAgICAgIHRoaXMuc3RvcFZpZGVvKHBzd3BFbGVtZW50KTtcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICBwaG90b3N3aXBlLmxpc3RlbignYWZ0ZXJDaGFuZ2UnLCAoKSA9PiB7XG4gICAgICAgICAgICAgICAgdGhpcy5zdG9wVmlkZW8ocHN3cEVsZW1lbnQpO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIHBob3Rvc3dpcGUuaW5pdCgpO1xuICAgICAgICB9XG5cbiAgICAgICAgc3RvcFZpZGVvKGVsZW1lbnQpIHtcbiAgICAgICAgICAgICQoZWxlbWVudCkuZmluZCgnaWZyYW1lLCB2aWRlbycpLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIGxldCB0YWcgPSAkKHRoaXMpLnByb3AoXCJ0YWdOYW1lXCIpLnRvTG93ZXJDYXNlKCk7XG4gICAgICAgICAgICAgICAgaWYgKHRhZyA9PT0gJ2lmcmFtZScpIHtcbiAgICAgICAgICAgICAgICAgICAgbGV0IHNyYyA9ICQodGhpcykuYXR0cignc3JjJyk7XG4gICAgICAgICAgICAgICAgICAgICQodGhpcykuYXR0cignc3JjJywgc3JjKTtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICBpZiAodGFnID09PSAndmlkZW8nKSB7XG4gICAgICAgICAgICAgICAgICAgICQodGhpcylbMF0ucGF1c2UoKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuXG4gICAgICAgIGFkZExvYWRpbmdDbGFzcygpIHtcbiAgICAgICAgICAgIHRoaXMuX2VsZW1lbnQuYWRkQ2xhc3MoJ2xvYWRpbmctZ2FsbGVyeScpO1xuICAgICAgICB9XG5cbiAgICAgICAgcmVtb3ZlTG9hZGluZ0NsYXNzKCkge1xuICAgICAgICAgICAgdGhpcy5fZWxlbWVudC5yZW1vdmVDbGFzcygnbG9hZGluZy1nYWxsZXJ5Jyk7XG4gICAgICAgIH1cblxuICAgICAgICBnZXRHYWxsZXJ5SXRlbXMoKSB7XG4gICAgICAgICAgICBsZXQgJHNsaWRlcyA9IHRoaXMuJHNsaWRlci5zbGljaygnZ2V0U2xpY2snKS4kc2xpZGVzLFxuICAgICAgICAgICAgICAgIGl0ZW1zICAgPSBbXTtcblxuICAgICAgICAgICAgaWYgKCRzbGlkZXMubGVuZ3RoID4gMCkge1xuICAgICAgICAgICAgICAgICRzbGlkZXMuZWFjaChmdW5jdGlvbiAoaSwgZWwpIHtcbiAgICAgICAgICAgICAgICAgICAgbGV0IGltZyA9ICQoZWwpLmZpbmQoJ2ltZywgaWZyYW1lLCB2aWRlbycpO1xuICAgICAgICAgICAgICAgICAgICBsZXQgdGFnID0gJChpbWcpLnByb3AoXCJ0YWdOYW1lXCIpLnRvTG93ZXJDYXNlKCk7XG5cbiAgICAgICAgICAgICAgICAgICAgbGV0IHNyYywgaXRlbTtcbiAgICAgICAgICAgICAgICAgICAgc3dpdGNoICh0YWcpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGNhc2UgJ2ltZyc6XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbGV0IGxhcmdlX2ltYWdlX3NyYyA9IGltZy5hdHRyKCdkYXRhLWxhcmdlX2ltYWdlJyksXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGxhcmdlX2ltYWdlX3cgICA9IGltZy5hdHRyKCdkYXRhLWxhcmdlX2ltYWdlX3dpZHRoJyksXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGxhcmdlX2ltYWdlX2ggICA9IGltZy5hdHRyKCdkYXRhLWxhcmdlX2ltYWdlX2hlaWdodCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGl0ZW0gICAgICAgICAgICAgICAgPSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNyYyAgIDogbGFyZ2VfaW1hZ2Vfc3JjLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB3ICAgICA6IGxhcmdlX2ltYWdlX3csXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGggICAgIDogbGFyZ2VfaW1hZ2VfaCxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdGl0bGUgOiBpbWcuYXR0cignZGF0YS1jYXB0aW9uJykgPyBpbWcuYXR0cignZGF0YS1jYXB0aW9uJykgOiBpbWcuYXR0cigndGl0bGUnKVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH07XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgICAgICAgICAgICBjYXNlICdpZnJhbWUnOlxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNyYyAgPSBpbWcuYXR0cignc3JjJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaXRlbSA9IHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgaHRtbCA6IGA8aWZyYW1lIGNsYXNzPVwid3ZnLWxpZ2h0Ym94LWlmcmFtZVwiIHNyYz1cIiR7c3JjfVwiIHN0eWxlPVwid2lkdGg6IDEwMCU7IGhlaWdodDogMTAwJTsgbWFyZ2luOiAwO3BhZGRpbmc6IDA7IGJhY2tncm91bmQtY29sb3I6ICMwMDAwMDBcIiBmcmFtZWJvcmRlcj1cIjBcIiB3ZWJraXRBbGxvd0Z1bGxTY3JlZW4gbW96YWxsb3dmdWxsc2NyZWVuIGFsbG93RnVsbFNjcmVlbj48L2lmcmFtZT5gXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgICAgICAgICAgICAgY2FzZSAndmlkZW8nOlxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNyYyAgPSBpbWcuYXR0cignc3JjJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaXRlbSA9IHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgaHRtbCA6IGA8dmlkZW8gY2xhc3M9XCJ3dmctbGlnaHRib3gtdmlkZW9cIiBjb250cm9scyBjb250cm9sc0xpc3Q9XCJub2Rvd25sb2FkXCIgc3JjPVwiJHtzcmN9XCIgc3R5bGU9XCJ3aWR0aDogMTAwJTsgaGVpZ2h0OiAxMDAlOyBtYXJnaW46IDA7cGFkZGluZzogMDsgYmFja2dyb3VuZC1jb2xvcjogIzAwMDAwMFwiPjwvdmlkZW8+YFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgIGl0ZW1zLnB1c2goaXRlbSk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICByZXR1cm4gaXRlbXM7XG4gICAgICAgIH1cblxuICAgICAgICBkZXN0cm95U2xpY2soKSB7XG5cbiAgICAgICAgICAgIHRoaXMuJHNsaWRlci5odG1sKCcnKTtcbiAgICAgICAgICAgIHRoaXMuJHRodW1ibmFpbC5odG1sKCcnKTtcblxuICAgICAgICAgICAgaWYgKHRoaXMuJHNsaWRlci5oYXNDbGFzcygnc2xpY2staW5pdGlhbGl6ZWQnKSkge1xuICAgICAgICAgICAgICAgIHRoaXMuJHNsaWRlci5zbGljaygndW5zbGljaycpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB0aGlzLl9lbGVtZW50LnRyaWdnZXIoJ3dvb192YXJpYXRpb25fZ2FsbGVyeV9zbGlja19kZXN0cm95JywgW3RoaXNdKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGRlZmF1bHRHYWxsZXJ5KCkge1xuXG4gICAgICAgICAgICBpZiAodGhpcy5pc192YXJpYXRpb25fcHJvZHVjdCkge1xuICAgICAgICAgICAgICAgIHdwLmFqYXguc2VuZCgnd3ZnX2dldF9kZWZhdWx0X2dhbGxlcnknLCB7XG4gICAgICAgICAgICAgICAgICAgIGRhdGEgICAgOiB7XG4gICAgICAgICAgICAgICAgICAgICAgICBwcm9kdWN0X2lkIDogdGhpcy5wcm9kdWN0X2lkXG4gICAgICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgICAgIHN1Y2Nlc3MgOiAoZGF0YSkgPT4ge1xuICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5fZWxlbWVudC5kYXRhKCd3b29fdmFyaWF0aW9uX2dhbGxlcnlfZGVmYXVsdCcsIGRhdGEpO1xuICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5fZWxlbWVudC50cmlnZ2VyKCd3b29fdmFyaWF0aW9uX2RlZmF1bHRfZ2FsbGVyeV9sb2FkZWQnLCB0aGlzKTtcbiAgICAgICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICAgICAgZXJyb3IgICA6IChlKSA9PiB7XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGlzLl9lbGVtZW50LmRhdGEoJ3dvb192YXJpYXRpb25fZ2FsbGVyeV9kZWZhdWx0JywgW10pO1xuICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5fZWxlbWVudC50cmlnZ2VyKCd3b29fdmFyaWF0aW9uX2RlZmF1bHRfZ2FsbGVyeV9sb2FkZWQnLCB0aGlzKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnNvbGUuZXJyb3IoYFZhcmlhdGlvbiBHYWxsZXJ5IG5vdCBhdmFpbGFibGUgb24gdmFyaWF0aW9uIGlkICR7dGhpcy5wcm9kdWN0X2lkfS5gKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG5cbiAgICAgICAgaW5pdFZhcmlhdGlvbkltYWdlUHJlbG9hZCgpIHtcbiAgICAgICAgICAgIC8vcmV0dXJuO1xuICAgICAgICAgICAgaWYgKHRoaXMuaXNfdmFyaWF0aW9uX3Byb2R1Y3QpIHtcbiAgICAgICAgICAgICAgICB3cC5hamF4LnNlbmQoJ3d2Z19nZXRfYXZhaWxhYmxlX3ZhcmlhdGlvbl9pbWFnZXMnLCB7XG4gICAgICAgICAgICAgICAgICAgIGRhdGEgICAgOiB7XG4gICAgICAgICAgICAgICAgICAgICAgICBwcm9kdWN0X2lkIDogdGhpcy5wcm9kdWN0X2lkXG4gICAgICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgICAgIHN1Y2Nlc3MgOiAoaW1hZ2VzKSA9PiB7XG4gICAgICAgICAgICAgICAgICAgICAgICAvLyBjb25zb2xlLmxvZyhkYXRhKVxuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKGltYWdlcy5sZW5ndGggPiAxKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5pbWFnZVByZWxvYWQoaW1hZ2VzKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMuX2VsZW1lbnQuZGF0YSgnd29vX3ZhcmlhdGlvbl9nYWxsZXJ5X3ZhcmlhdGlvbl9pbWFnZXMnLCBpbWFnZXMpO1xuICAgICAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgICAgICBlcnJvciAgIDogKGUpID0+IHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMuX2VsZW1lbnQuZGF0YSgnd29vX3ZhcmlhdGlvbl9nYWxsZXJ5X3ZhcmlhdGlvbl9pbWFnZXMnLCBbXSk7XG4gICAgICAgICAgICAgICAgICAgICAgICBjb25zb2xlLmVycm9yKGBWYXJpYXRpb24gR2FsbGVyeSB2YXJpYXRpb25zIGltYWdlcyBub3QgYXZhaWxhYmxlIG9uIHZhcmlhdGlvbiBpZCAke3RoaXMucHJvZHVjdF9pZH0uYCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuXG4gICAgICAgIGltYWdlUHJlbG9hZChpbWFnZXMpIHtcbiAgICAgICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgaW1hZ2VzLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgICAgICAgICAgdHJ5IHtcblxuICAgICAgICAgICAgICAgICAgICAvLyBOb3RlOiB0aGlzIHdvbid0IHdvcmsgd2hlbiBjaHJvbWUgZGV2dG9vbCBpcyBvcGVuIGFuZCAnZGlzYWJsZSBjYWNoZScgaXMgZW5hYmxlZCB3aXRoaW4gdGhlIG5ldHdvcmsgcGFuZWxcbiAgICAgICAgICAgICAgICAgICAgLypsZXQgX2ltZyAgICAgICA9IG5ldyBJbWFnZSgpO1xuICAgICAgICAgICAgICAgICAgICBsZXQgX2dhbGxlcnkgICA9IG5ldyBJbWFnZSgpO1xuICAgICAgICAgICAgICAgICAgICBsZXQgX2Z1bGwgICAgICA9IG5ldyBJbWFnZSgpO1xuICAgICAgICAgICAgICAgICAgICBsZXQgX3RodW1ibmFpbCA9IG5ldyBJbWFnZSgpO1xuXG4gICAgICAgICAgICAgICAgICAgIF9pbWcuc3JjICAgID0gaW1hZ2VzW2ldLnNyYztcbiAgICAgICAgICAgICAgICAgICAgX2ltZy5zcmNzZXQgPSBpbWFnZXNbaV0uc3Jjc2V0O1xuXG4gICAgICAgICAgICAgICAgICAgIF9nYWxsZXJ5LnNyYyA9IGltYWdlc1tpXS5nYWxsZXJ5X3RodW1ibmFpbF9zcmM7XG5cbiAgICAgICAgICAgICAgICAgICAgX2Z1bGwuc3JjID0gaW1hZ2VzW2ldLmZ1bGxfc3JjO1xuXG4gICAgICAgICAgICAgICAgICAgIF90aHVtYm5haWwuc3JjID0gaW1hZ2VzW2ldLnRodW1iX3NyYzsqL1xuXG4gICAgICAgICAgICAgICAgICAgIC8vIEFwcGVuZCBDb250ZW50XG4gICAgICAgICAgICAgICAgICAgIGxldCBfaW1nX3NyYyAgICA9IGltYWdlc1tpXS5zcmM7XG4gICAgICAgICAgICAgICAgICAgIGxldCBfaW1nX3NyY3NldCA9IGltYWdlc1tpXS5zcmNzZXQ7XG5cbiAgICAgICAgICAgICAgICAgICAgbGV0IF9nYWxsZXJ5X3NyYyAgID0gaW1hZ2VzW2ldLmdhbGxlcnlfdGh1bWJuYWlsX3NyYztcbiAgICAgICAgICAgICAgICAgICAgbGV0IF9mdWxsX3NyYyAgICAgID0gaW1hZ2VzW2ldLmZ1bGxfc3JjO1xuICAgICAgICAgICAgICAgICAgICBsZXQgX3RodW1ibmFpbF9zcmMgPSBpbWFnZXNbaV0udGh1bWJfc3JjO1xuXG4gICAgICAgICAgICAgICAgICAgIGlmICghIV9pbWdfc3Jjc2V0KSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBsZXQgdGVtcGxhdGUgPSBgPGRpdiBzdHlsZT1cImRpc3BsYXk6IG5vbmVcIj48aW1nIGFyaWEtaGlkZGVuPVwidHJ1ZVwiIHN0eWxlPVwiZGlzcGxheTogbm9uZVwiIHNyYz1cIiR7X2ltZ19zcmN9XCIgc3Jjc2V0PVwiJHtfaW1nX3NyY3NldH1cIiAvPjxpbWcgc3R5bGU9XCJkaXNwbGF5OiBub25lXCIgc3JjPVwiJHtfZ2FsbGVyeV9zcmN9XCIgLz48aW1nIHN0eWxlPVwiZGlzcGxheTogbm9uZVwiIHNyYz1cIiR7X3RodW1ibmFpbF9zcmN9XCIgLz48aW1nIHN0eWxlPVwiZGlzcGxheTogbm9uZVwiIHNyYz1cIiR7X2Z1bGxfc3JjfVwiIC8+PC9kaXY+YDtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGxldCB0ZW1wbGF0ZSA9IGA8ZGl2IHN0eWxlPVwiZGlzcGxheTogbm9uZVwiPjxpbWcgYXJpYS1oaWRkZW49XCJ0cnVlXCIgc3R5bGU9XCJkaXNwbGF5OiBub25lXCIgc3JjPVwiJHtfaW1nX3NyY31cIiAvPjxpbWcgc3R5bGU9XCJkaXNwbGF5OiBub25lXCIgc3JjPVwiJHtfZ2FsbGVyeV9zcmN9XCIgLz48aW1nIHN0eWxlPVwiZGlzcGxheTogbm9uZVwiIHNyYz1cIiR7X3RodW1ibmFpbF9zcmN9XCIgLz48aW1nIHN0eWxlPVwiZGlzcGxheTogbm9uZVwiIHNyYz1cIiR7X2Z1bGxfc3JjfVwiIC8+PC9kaXY+YDtcbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgIC8vIGxldCB0ZW1wbGF0ZSA9IGA8ZGl2IHN0eWxlPVwiZGlzcGxheTogbm9uZVwiPjxpbWcgYXJpYS1oaWRkZW49XCJ0cnVlXCIgc3R5bGU9XCJkaXNwbGF5OiBub25lXCIgc3JjPVwiJHtfaW1nX3NyY31cIiBzcmNzZXQ9XCIke19pbWdfc3Jjc2V0fVwiIC8+PGltZyBzdHlsZT1cImRpc3BsYXk6IG5vbmVcIiBzcmM9XCIke19nYWxsZXJ5X3NyY31cIiAvPjxpbWcgc3R5bGU9XCJkaXNwbGF5OiBub25lXCIgc3JjPVwiJHtfdGh1bWJuYWlsX3NyY31cIiAvPjxpbWcgc3R5bGU9XCJkaXNwbGF5OiBub25lXCIgc3JjPVwiJHtfZnVsbF9zcmN9XCIgLz48L2Rpdj5gO1xuICAgICAgICAgICAgICAgICAgICAkKCdib2R5JykuYXBwZW5kKHRlbXBsYXRlKVxuXG4gICAgICAgICAgICAgICAgfSBjYXRjaCAoZSkge1xuICAgICAgICAgICAgICAgICAgICBjb25zb2xlLmVycm9yKGUpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuXG4gICAgICAgIGluaXRWYXJpYXRpb25HYWxsZXJ5KCkge1xuXG4gICAgICAgICAgICAvLyBzaG93X3ZhcmlhdGlvbiwgZm91bmRfdmFyaWF0aW9uXG5cbiAgICAgICAgICAgIHRoaXMuJHZhcmlhdGlvbnNfZm9ybS5vZmYoJ3Jlc2V0X2ltYWdlLnd2ZycpO1xuICAgICAgICAgICAgdGhpcy4kdmFyaWF0aW9uc19mb3JtLm9mZignY2xpY2sud3ZnJyk7XG4gICAgICAgICAgICB0aGlzLiR2YXJpYXRpb25zX2Zvcm0ub2ZmKCdzaG93X3ZhcmlhdGlvbi53dmcnKTtcblxuICAgICAgICAgICAgaWYgKHdvb192YXJpYXRpb25fZ2FsbGVyeV9vcHRpb25zLmdhbGxlcnlfcmVzZXRfb25fdmFyaWF0aW9uX2NoYW5nZSkge1xuICAgICAgICAgICAgICAgIHRoaXMuJHZhcmlhdGlvbnNfZm9ybS5vbigncmVzZXRfaW1hZ2Uud3ZnJywgKGV2ZW50KSA9PiB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuYWRkTG9hZGluZ0NsYXNzKCk7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuZ2FsbGVyeVJlc2V0KCk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICB0aGlzLiR2YXJpYXRpb25zX2Zvcm0ub24oJ2NsaWNrLnd2ZycsICcucmVzZXRfdmFyaWF0aW9ucycsIChldmVudCkgPT4ge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmFkZExvYWRpbmdDbGFzcygpO1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmdhbGxlcnlSZXNldCgpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB0aGlzLiR2YXJpYXRpb25zX2Zvcm0ub24oJ3Nob3dfdmFyaWF0aW9uLnd2ZycsIChldmVudCwgdmFyaWF0aW9uKSA9PiB7XG4gICAgICAgICAgICAgICAgdGhpcy5hZGRMb2FkaW5nQ2xhc3MoKTtcbiAgICAgICAgICAgICAgICB0aGlzLmdhbGxlcnlJbml0KHZhcmlhdGlvbi52YXJpYXRpb25fZ2FsbGVyeV9pbWFnZXMpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cblxuICAgICAgICBnYWxsZXJ5UmVzZXQoKSB7XG4gICAgICAgICAgICBsZXQgJGRlZmF1bHRfZ2FsbGVyeSA9IHRoaXMuX2VsZW1lbnQuZGF0YSgnd29vX3ZhcmlhdGlvbl9nYWxsZXJ5X2RlZmF1bHQnKTtcblxuICAgICAgICAgICAgaWYgKCRkZWZhdWx0X2dhbGxlcnkgJiYgJGRlZmF1bHRfZ2FsbGVyeS5sZW5ndGggPiAwKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5nYWxsZXJ5SW5pdCgkZGVmYXVsdF9nYWxsZXJ5KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgIF8uZGVsYXkoKCkgPT4ge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLnJlbW92ZUxvYWRpbmdDbGFzcygpO1xuICAgICAgICAgICAgICAgIH0sIDEwMClcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuXG4gICAgICAgIGdhbGxlcnlJbml0KGltYWdlcykge1xuXG4gICAgICAgICAgICBsZXQgaGFzR2FsbGVyeSA9IGltYWdlcy5sZW5ndGggPiAxO1xuXG4gICAgICAgICAgICB0aGlzLl9lbGVtZW50LnRyaWdnZXIoJ2JlZm9yZV93b29fdmFyaWF0aW9uX2dhbGxlcnlfaW5pdCcsIFt0aGlzLCBpbWFnZXNdKTtcblxuICAgICAgICAgICAgdGhpcy5kZXN0cm95U2xpY2soKTtcblxuICAgICAgICAgICAgbGV0IHNsaWRlcl9pbm5lcl9odG1sID0gaW1hZ2VzLm1hcCgoaW1hZ2UpID0+IHtcbiAgICAgICAgICAgICAgICBsZXQgdGVtcGxhdGUgPSB3cC50ZW1wbGF0ZSgnd29vLXZhcmlhdGlvbi1nYWxsZXJ5LXNsaWRlci10ZW1wbGF0ZScpO1xuICAgICAgICAgICAgICAgIHJldHVybiB0ZW1wbGF0ZShpbWFnZSk7XG4gICAgICAgICAgICB9KS5qb2luKCcnKTtcblxuICAgICAgICAgICAgbGV0IHRodW1ibmFpbF9pbm5lcl9odG1sID0gaW1hZ2VzLm1hcCgoaW1hZ2UpID0+IHtcbiAgICAgICAgICAgICAgICBsZXQgdGVtcGxhdGUgPSB3cC50ZW1wbGF0ZSgnd29vLXZhcmlhdGlvbi1nYWxsZXJ5LXRodW1ibmFpbC10ZW1wbGF0ZScpO1xuICAgICAgICAgICAgICAgIHJldHVybiB0ZW1wbGF0ZShpbWFnZSk7XG4gICAgICAgICAgICB9KS5qb2luKCcnKTtcblxuICAgICAgICAgICAgaWYgKGhhc0dhbGxlcnkpIHtcbiAgICAgICAgICAgICAgICB0aGlzLiR0YXJnZXQuYWRkQ2xhc3MoJ3dvby12YXJpYXRpb24tZ2FsbGVyeS1oYXMtcHJvZHVjdC10aHVtYm5haWwnKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgIHRoaXMuJHRhcmdldC5yZW1vdmVDbGFzcygnd29vLXZhcmlhdGlvbi1nYWxsZXJ5LWhhcy1wcm9kdWN0LXRodW1ibmFpbCcpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB0aGlzLiRzbGlkZXIuaHRtbChzbGlkZXJfaW5uZXJfaHRtbCk7XG5cbiAgICAgICAgICAgIGlmIChoYXNHYWxsZXJ5KSB7XG4gICAgICAgICAgICAgICAgdGhpcy4kdGh1bWJuYWlsLmh0bWwodGh1bWJuYWlsX2lubmVyX2h0bWwpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgdGhpcy4kdGh1bWJuYWlsLmh0bWwoJycpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAvL3RoaXMuX2VsZW1lbnQudHJpZ2dlcignd29vX3ZhcmlhdGlvbl9nYWxsZXJ5X2luaXQnLCBbdGhpcywgaW1hZ2VzXSk7XG5cbiAgICAgICAgICAgIF8uZGVsYXkoKCkgPT4ge1xuICAgICAgICAgICAgICAgIHRoaXMuaW1hZ2VzTG9hZGVkKCk7XG4gICAgICAgICAgICB9LCAxKTtcblxuICAgICAgICAgICAgLy90aGlzLl9lbGVtZW50LnRyaWdnZXIoJ2FmdGVyX3dvb192YXJpYXRpb25fZ2FsbGVyeV9pbml0JywgW3RoaXMsIGltYWdlc10pO1xuICAgICAgICB9XG5cbiAgICAgICAgaW1hZ2VzTG9hZGVkKCkge1xuXG4gICAgICAgICAgICAvLyBTb21lIFNjcmlwdCBBZGQgQ3VzdG9tIGltYWdlc0xvYWRlZCBGdW5jdGlvblxuICAgICAgICAgICAgaWYgKCEkKCkuaW1hZ2VzTG9hZGVkLmRvbmUpIHtcbiAgICAgICAgICAgICAgICB0aGlzLl9lbGVtZW50LnRyaWdnZXIoJ3dvb192YXJpYXRpb25fZ2FsbGVyeV9pbWFnZV9sb2FkaW5nJywgW3RoaXNdKTtcbiAgICAgICAgICAgICAgICB0aGlzLl9lbGVtZW50LnRyaWdnZXIoJ3dvb192YXJpYXRpb25fZ2FsbGVyeV9pbWFnZV9sb2FkZWQnLCBbdGhpc10pO1xuICAgICAgICAgICAgICAgIHJldHVybjtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgdGhpcy5fZWxlbWVudC5pbWFnZXNMb2FkZWQoKVxuICAgICAgICAgICAgICAgIC5wcm9ncmVzcygoaW5zdGFuY2UsIGltYWdlKSA9PiB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuX2VsZW1lbnQudHJpZ2dlcignd29vX3ZhcmlhdGlvbl9nYWxsZXJ5X2ltYWdlX2xvYWRpbmcnLCBbdGhpc10pO1xuICAgICAgICAgICAgICAgIH0pXG4gICAgICAgICAgICAgICAgLmRvbmUoKGluc3RhbmNlKSA9PiB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuX2VsZW1lbnQudHJpZ2dlcignd29vX3ZhcmlhdGlvbl9nYWxsZXJ5X2ltYWdlX2xvYWRlZCcsIFt0aGlzXSk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cbiAgICB9XG5cbiAgICAvKipcbiAgICAgKiAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cbiAgICAgKiBqUXVlcnlcbiAgICAgKiAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cbiAgICAgKi9cblxuICAgICQuZm5bJ1dvb1ZhcmlhdGlvbkdhbGxlcnknXSA9IFdvb1ZhcmlhdGlvbkdhbGxlcnkuX2pRdWVyeUludGVyZmFjZTtcbiAgICAkLmZuWydXb29WYXJpYXRpb25HYWxsZXJ5J10uQ29uc3RydWN0b3IgPSBXb29WYXJpYXRpb25HYWxsZXJ5O1xuICAgICQuZm5bJ1dvb1ZhcmlhdGlvbkdhbGxlcnknXS5ub0NvbmZsaWN0ICA9IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgJC5mblsnV29vVmFyaWF0aW9uR2FsbGVyeSddID0gJC5mblsnV29vVmFyaWF0aW9uR2FsbGVyeSddO1xuICAgICAgICByZXR1cm4gV29vVmFyaWF0aW9uR2FsbGVyeS5falF1ZXJ5SW50ZXJmYWNlXG4gICAgfTtcblxuICAgIHJldHVybiBXb29WYXJpYXRpb25HYWxsZXJ5O1xuXG59KShqUXVlcnkpO1xuXG5leHBvcnQgZGVmYXVsdCBXb29WYXJpYXRpb25HYWxsZXJ5XG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIHNyYy9qcy9Xb29WYXJpYXRpb25HYWxsZXJ5LmpzIiwiLy8gcmVtb3ZlZCBieSBleHRyYWN0LXRleHQtd2VicGFjay1wbHVnaW5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3NyYy9zY3NzL3NsaWNrLnNjc3Ncbi8vIG1vZHVsZSBpZCA9IDNcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwiLy8gcmVtb3ZlZCBieSBleHRyYWN0LXRleHQtd2VicGFjay1wbHVnaW5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3NyYy9zY3NzL2Zyb250ZW5kLnNjc3Ncbi8vIG1vZHVsZSBpZCA9IDRcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwiLy8gcmVtb3ZlZCBieSBleHRyYWN0LXRleHQtd2VicGFjay1wbHVnaW5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3NyYy9zY3NzL3RoZW1lLXN1cHBvcnQuc2Nzc1xuLy8gbW9kdWxlIGlkID0gNVxuLy8gbW9kdWxlIGNodW5rcyA9IDAiLCIvLyByZW1vdmVkIGJ5IGV4dHJhY3QtdGV4dC13ZWJwYWNrLXBsdWdpblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vc3JjL3Njc3MvYmFja2VuZC5zY3NzXG4vLyBtb2R1bGUgaWQgPSA2XG4vLyBtb2R1bGUgY2h1bmtzID0gMCIsIi8vIHJlbW92ZWQgYnkgZXh0cmFjdC10ZXh0LXdlYnBhY2stcGx1Z2luXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvc2Nzcy9nd3AtYWRtaW4uc2Nzc1xuLy8gbW9kdWxlIGlkID0gN1xuLy8gbW9kdWxlIGNodW5rcyA9IDAiXSwibWFwcGluZ3MiOiI7Ozs7Ozs7O0FBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7Ozs7Ozs7Ozs7Ozs7Ozs7OztBQzdEQTtBQUNBO0FBQUE7QUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7Ozs7Ozs7Ozs7O0FDdEJBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUhBO0FBTUE7QUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUE1Q0E7QUFBQTtBQUFBO0FBbURBO0FBQ0E7QUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUF6REE7QUFBQTtBQUFBO0FBQ0E7QUE0REE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQXBFQTtBQUFBO0FBQUE7QUF1RUE7QUFDQTtBQUNBO0FBekVBO0FBQUE7QUFBQTtBQTJFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBRUE7QUFDQTtBQUNBO0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBN0hBO0FBQUE7QUFBQTtBQUNBO0FBZ0lBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFBQTtBQUFBO0FBQ0E7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQURBO0FBQ0E7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBcktBO0FBQUE7QUFBQTtBQXdLQTtBQUNBO0FBQ0E7QUExS0E7QUFBQTtBQUFBO0FBNEtBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUE1TEE7QUFBQTtBQUFBO0FBOExBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUFBO0FBQ0E7QUFFQTtBQUNBO0FBREE7QUFDQTtBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQTNOQTtBQUFBO0FBQUE7QUE4TkE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBek9BO0FBQUE7QUFBQTtBQTRPQTtBQUNBO0FBN09BO0FBQUE7QUFBQTtBQWdQQTtBQUNBO0FBalBBO0FBQUE7QUFBQTtBQW9QQTtBQUFBO0FBQ0E7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUFBO0FBQUE7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBSkE7QUFNQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBREE7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBREE7QUFHQTtBQXZCQTtBQUNBO0FBeUJBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUEzUkE7QUFBQTtBQUFBO0FBQ0E7QUE4UkE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBdlNBO0FBQUE7QUFBQTtBQXlTQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFEQTtBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQVpBO0FBY0E7QUFDQTtBQTNUQTtBQUFBO0FBQUE7QUE2VEE7QUFDQTtBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFEQTtBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFkQTtBQWdCQTtBQUNBO0FBalZBO0FBQUE7QUFBQTtBQW9WQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7Ozs7Ozs7O0FBY0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBNVhBO0FBQUE7QUFBQTtBQThYQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQXZaQTtBQUFBO0FBQUE7QUF5WkE7QUFDQTtBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBcGFBO0FBQUE7QUFBQTtBQXNhQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQS9jQTtBQUFBO0FBQUE7QUFpZEE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFFQTtBQUNBO0FBRUE7QUFDQTtBQUNBO0FBamVBO0FBQUE7QUFBQTtBQThDQTtBQUNBO0FBQ0E7QUFDQTtBQWpEQTtBQUNBO0FBREE7QUFBQTtBQUNBO0FBbWVBOzs7Ozs7QUFNQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBRUE7QUFDQTtBQUNBOzs7Ozs7QUMxZkE7Ozs7OztBQ0FBOzs7Ozs7QUNBQTs7Ozs7O0FDQUE7Ozs7OztBQ0FBOzs7QSIsInNvdXJjZVJvb3QiOiIifQ==