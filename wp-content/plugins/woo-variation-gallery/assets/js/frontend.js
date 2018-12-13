/*!
 * WooCommerce Variation Gallery v1.1.16 
 * 
 * Author: Emran Ahmed ( emran.bd.08@gmail.com ) 
 * Date: 2018-12-10 22:02:00
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

        $('.woo-variation-gallery-wrapper').WooVariationGallery();

        // Ajax
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

            this.initSlick();
            this.initZoom();
            this.initPhotoswipe();

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

                var galleryWidth = this.$target.width(),
                    zoomEnabled = false,
                    zoomTarget = this.$slider.slick('getSlick').$slides.eq(currentSlide);

                $(zoomTarget).each(function (index, target) {
                    var image = $(target).find('img');

                    if (image.data('large_image_width') > galleryWidth) {
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
                        var _img = new Image();
                        var _gallery = new Image();
                        var _full = new Image();
                        var _thumbnail = new Image();

                        _img.src = images[i].src;
                        _img.srcset = images[i].srcset;

                        _gallery.src = images[i].gallery_thumbnail_src;

                        _full.src = images[i].full_src;

                        _thumbnail.src = images[i].thumb_src;

                        // Append Content
                        /*let _img_src    = images[i].src;
                        let _img_srcset = images[i].srcset;
                         let _gallery_src   = images[i].gallery_thumbnail_src;
                        let _full_src      = images[i].full_src;
                        let _thumbnail_src = images[i].thumb_src;
                         let template = `<div style="display: none"><img aria-hidden="true" style="display: none" src="${_img_src}" srcset="${_img_srcset}" /><img style="display: none" src="${_gallery_src}" /><img style="display: none" src="${_thumbnail_src}" /><img style="display: none" src="${_full_src}" /></div>`;
                        $('body').append(template)*/
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

                this.$variations_form.on('show_variation', function (event, variation) {
                    _this7.addLoadingClass();
                    _this7.galleryInit(variation.variation_gallery_images);
                });

                if (woo_variation_gallery_options.gallery_reset_on_variation_change) {
                    this.$variations_form.on('reset_image', function (event) {
                        _this7.addLoadingClass();
                        _this7.galleryReset();
                    });
                } else {
                    this.$variations_form.on('click.wc-variation-form', '.reset_variations', function (event) {
                        _this7.addLoadingClass();
                        _this7.galleryReset();
                    });
                }
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
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiYXNzZXRzL2pzL2Zyb250ZW5kLmpzIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vL3dlYnBhY2svYm9vdHN0cmFwIDMzNDU4OWU1ZjQ5NjE1ZWRiNTAzIiwid2VicGFjazovLy9zcmMvanMvZnJvbnRlbmQuanMiLCJ3ZWJwYWNrOi8vL3NyYy9qcy9Xb29WYXJpYXRpb25HYWxsZXJ5LmpzIiwid2VicGFjazovLy8uL3NyYy9zY3NzL3NsaWNrLnNjc3M/MmMzMyIsIndlYnBhY2s6Ly8vLi9zcmMvc2Nzcy9mcm9udGVuZC5zY3NzPzJjM2EiLCJ3ZWJwYWNrOi8vLy4vc3JjL3Njc3MvdGhlbWUtc3VwcG9ydC5zY3NzIiwid2VicGFjazovLy8uL3NyYy9zY3NzL2JhY2tlbmQuc2Nzcz9iZjUwIiwid2VicGFjazovLy8uL3NyYy9zY3NzL2d3cC1hZG1pbi5zY3NzP2FmZjMiXSwic291cmNlc0NvbnRlbnQiOlsiIFx0Ly8gVGhlIG1vZHVsZSBjYWNoZVxuIFx0dmFyIGluc3RhbGxlZE1vZHVsZXMgPSB7fTtcblxuIFx0Ly8gVGhlIHJlcXVpcmUgZnVuY3Rpb25cbiBcdGZ1bmN0aW9uIF9fd2VicGFja19yZXF1aXJlX18obW9kdWxlSWQpIHtcblxuIFx0XHQvLyBDaGVjayBpZiBtb2R1bGUgaXMgaW4gY2FjaGVcbiBcdFx0aWYoaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0pIHtcbiBcdFx0XHRyZXR1cm4gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0uZXhwb3J0cztcbiBcdFx0fVxuIFx0XHQvLyBDcmVhdGUgYSBuZXcgbW9kdWxlIChhbmQgcHV0IGl0IGludG8gdGhlIGNhY2hlKVxuIFx0XHR2YXIgbW9kdWxlID0gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0gPSB7XG4gXHRcdFx0aTogbW9kdWxlSWQsXG4gXHRcdFx0bDogZmFsc2UsXG4gXHRcdFx0ZXhwb3J0czoge31cbiBcdFx0fTtcblxuIFx0XHQvLyBFeGVjdXRlIHRoZSBtb2R1bGUgZnVuY3Rpb25cbiBcdFx0bW9kdWxlc1ttb2R1bGVJZF0uY2FsbChtb2R1bGUuZXhwb3J0cywgbW9kdWxlLCBtb2R1bGUuZXhwb3J0cywgX193ZWJwYWNrX3JlcXVpcmVfXyk7XG5cbiBcdFx0Ly8gRmxhZyB0aGUgbW9kdWxlIGFzIGxvYWRlZFxuIFx0XHRtb2R1bGUubCA9IHRydWU7XG5cbiBcdFx0Ly8gUmV0dXJuIHRoZSBleHBvcnRzIG9mIHRoZSBtb2R1bGVcbiBcdFx0cmV0dXJuIG1vZHVsZS5leHBvcnRzO1xuIFx0fVxuXG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlcyBvYmplY3QgKF9fd2VicGFja19tb2R1bGVzX18pXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm0gPSBtb2R1bGVzO1xuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZSBjYWNoZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5jID0gaW5zdGFsbGVkTW9kdWxlcztcblxuIFx0Ly8gZGVmaW5lIGdldHRlciBmdW5jdGlvbiBmb3IgaGFybW9ueSBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQgPSBmdW5jdGlvbihleHBvcnRzLCBuYW1lLCBnZXR0ZXIpIHtcbiBcdFx0aWYoIV9fd2VicGFja19yZXF1aXJlX18ubyhleHBvcnRzLCBuYW1lKSkge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBuYW1lLCB7XG4gXHRcdFx0XHRjb25maWd1cmFibGU6IGZhbHNlLFxuIFx0XHRcdFx0ZW51bWVyYWJsZTogdHJ1ZSxcbiBcdFx0XHRcdGdldDogZ2V0dGVyXG4gXHRcdFx0fSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGdldERlZmF1bHRFeHBvcnQgZnVuY3Rpb24gZm9yIGNvbXBhdGliaWxpdHkgd2l0aCBub24taGFybW9ueSBtb2R1bGVzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm4gPSBmdW5jdGlvbihtb2R1bGUpIHtcbiBcdFx0dmFyIGdldHRlciA9IG1vZHVsZSAmJiBtb2R1bGUuX19lc01vZHVsZSA/XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0RGVmYXVsdCgpIHsgcmV0dXJuIG1vZHVsZVsnZGVmYXVsdCddOyB9IDpcbiBcdFx0XHRmdW5jdGlvbiBnZXRNb2R1bGVFeHBvcnRzKCkgeyByZXR1cm4gbW9kdWxlOyB9O1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQoZ2V0dGVyLCAnYScsIGdldHRlcik7XG4gXHRcdHJldHVybiBnZXR0ZXI7XG4gXHR9O1xuXG4gXHQvLyBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGxcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubyA9IGZ1bmN0aW9uKG9iamVjdCwgcHJvcGVydHkpIHsgcmV0dXJuIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChvYmplY3QsIHByb3BlcnR5KTsgfTtcblxuIFx0Ly8gX193ZWJwYWNrX3B1YmxpY19wYXRoX19cbiBcdF9fd2VicGFja19yZXF1aXJlX18ucCA9IFwiXCI7XG5cbiBcdC8vIExvYWQgZW50cnkgbW9kdWxlIGFuZCByZXR1cm4gZXhwb3J0c1xuIFx0cmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18oX193ZWJwYWNrX3JlcXVpcmVfXy5zID0gMCk7XG5cblxuXG4vLyBXRUJQQUNLIEZPT1RFUiAvL1xuLy8gd2VicGFjay9ib290c3RyYXAgMzM0NTg5ZTVmNDk2MTVlZGI1MDMiLCJqUXVlcnkoJCA9PiB7XG4gICAgaW1wb3J0KCcuL1dvb1ZhcmlhdGlvbkdhbGxlcnknKS50aGVuKCgpID0+IHtcblxuICAgICAgICAkKCcud29vLXZhcmlhdGlvbi1nYWxsZXJ5LXdyYXBwZXInKS5Xb29WYXJpYXRpb25HYWxsZXJ5KCk7XG5cbiAgICAgICAgLy8gQWpheFxuICAgICAgICAkKGRvY3VtZW50KS5vbignd2NfdmFyaWF0aW9uX2Zvcm0nLCAnLnZhcmlhdGlvbnNfZm9ybScsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICQoJy53b28tdmFyaWF0aW9uLWdhbGxlcnktd3JhcHBlcicpLldvb1ZhcmlhdGlvbkdhbGxlcnkoKTtcbiAgICAgICAgfSk7XG5cbiAgICAgICAgLy8gU3VwcG9ydCBmb3IgSmV0cGFjaydzIEluZmluaXRlIFNjcm9sbCxcbiAgICAgICAgJChkb2N1bWVudC5ib2R5KS5vbigncG9zdC1sb2FkJywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgJCgnLndvby12YXJpYXRpb24tZ2FsbGVyeS13cmFwcGVyJykuV29vVmFyaWF0aW9uR2FsbGVyeSgpO1xuICAgICAgICB9KTtcblxuICAgICAgICAvLyBZSVRIIFF1aWNrdmlld1xuICAgICAgICAkKGRvY3VtZW50KS5vbigncXZfbG9hZGVyX3N0b3AnLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAkKCcud29vLXZhcmlhdGlvbi1nYWxsZXJ5LXdyYXBwZXInKS5Xb29WYXJpYXRpb25HYWxsZXJ5KCk7XG4gICAgICAgIH0pO1xuICAgIH0pO1xufSk7ICAvLyBlbmQgb2YganF1ZXJ5IG1haW4gd3JhcHBlclxuXG5cblxuLy8gV0VCUEFDSyBGT09URVIgLy9cbi8vIHNyYy9qcy9mcm9udGVuZC5qcyIsIi8vID09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT1cbi8vIFdvb0NvbW1lcmNlIFZhcmlhdGlvbiBHYWxsZXJ5XG4vKmdsb2JhbCB3Y19hZGRfdG9fY2FydF92YXJpYXRpb25fcGFyYW1zLCB3b29fdmFyaWF0aW9uX2dhbGxlcnlfb3B0aW9ucyAqL1xuLy8gPT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PVxuXG5jb25zdCBXb29WYXJpYXRpb25HYWxsZXJ5ID0gKCgkKSA9PiB7XG5cbiAgICBjb25zdCBEZWZhdWx0ID0ge307XG5cbiAgICBjbGFzcyBXb29WYXJpYXRpb25HYWxsZXJ5IHtcblxuICAgICAgICBjb25zdHJ1Y3RvcihlbGVtZW50LCBjb25maWcpIHtcblxuXG4gICAgICAgICAgICAvLyBBc3NpZ25cbiAgICAgICAgICAgIHRoaXMuX2VsICAgICAgPSBlbGVtZW50O1xuICAgICAgICAgICAgdGhpcy5fZWxlbWVudCA9ICQoZWxlbWVudCk7XG4gICAgICAgICAgICB0aGlzLl9jb25maWcgID0gJC5leHRlbmQoe30sIERlZmF1bHQsIGNvbmZpZyk7XG5cbiAgICAgICAgICAgIHRoaXMuJHByb2R1Y3QgICAgICAgICAgICAgPSB0aGlzLl9lbGVtZW50LmNsb3Nlc3QoJy5wcm9kdWN0Jyk7XG4gICAgICAgICAgICB0aGlzLiR2YXJpYXRpb25zX2Zvcm0gICAgID0gdGhpcy4kcHJvZHVjdC5maW5kKCcudmFyaWF0aW9uc19mb3JtJyk7XG4gICAgICAgICAgICB0aGlzLiR0YXJnZXQgICAgICAgICAgICAgID0gdGhpcy5fZWxlbWVudC5wYXJlbnQoKTtcbiAgICAgICAgICAgIHRoaXMuJHNsaWRlciAgICAgICAgICAgICAgPSAkKCcud29vLXZhcmlhdGlvbi1nYWxsZXJ5LXNsaWRlcicsIHRoaXMuX2VsZW1lbnQpO1xuICAgICAgICAgICAgdGhpcy4kdGh1bWJuYWlsICAgICAgICAgICA9ICQoJy53b28tdmFyaWF0aW9uLWdhbGxlcnktdGh1bWJuYWlsLXNsaWRlcicsIHRoaXMuX2VsZW1lbnQpO1xuICAgICAgICAgICAgdGhpcy50aHVtYm5haWxfY29sdW1ucyAgICA9IHRoaXMuX2VsZW1lbnQuZGF0YSgndGh1bWJuYWlsX2NvbHVtbnMnKTtcbiAgICAgICAgICAgIHRoaXMucHJvZHVjdF9pZCAgICAgICAgICAgPSB0aGlzLiR2YXJpYXRpb25zX2Zvcm0uZGF0YSgncHJvZHVjdF9pZCcpO1xuICAgICAgICAgICAgdGhpcy5pc192YXJpYXRpb25fcHJvZHVjdCA9ICh0aGlzLiR2YXJpYXRpb25zX2Zvcm0ubGVuZ3RoID4gMCk7XG4gICAgICAgICAgICB0aGlzLmluaXRpYWxfbG9hZCAgICAgICAgID0gdHJ1ZTtcblxuICAgICAgICAgICAgLy8gQ2FsbFxuICAgICAgICAgICAgdGhpcy5kZWZhdWx0R2FsbGVyeSgpO1xuICAgICAgICAgICAgdGhpcy5pbml0VmFyaWF0aW9uSW1hZ2VQcmVsb2FkKCk7XG5cbiAgICAgICAgICAgIHRoaXMuaW5pdEV2ZW50cygpO1xuXG4gICAgICAgICAgICB0aGlzLmluaXRTbGljaygpO1xuICAgICAgICAgICAgdGhpcy5pbml0Wm9vbSgpO1xuICAgICAgICAgICAgdGhpcy5pbml0UGhvdG9zd2lwZSgpO1xuXG4gICAgICAgICAgICBpZiAoIXRoaXMuaXNfdmFyaWF0aW9uX3Byb2R1Y3QpIHtcbiAgICAgICAgICAgICAgICB0aGlzLmltYWdlc0xvYWRlZCgpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB0aGlzLmluaXRWYXJpYXRpb25HYWxsZXJ5KCk7XG5cbiAgICAgICAgICAgIHRoaXMuX2VsZW1lbnQuZGF0YSgnd29vX3ZhcmlhdGlvbl9nYWxsZXJ5JywgdGhpcyk7XG4gICAgICAgICAgICAkKGRvY3VtZW50KS50cmlnZ2VyKCd3b29fdmFyaWF0aW9uX2dhbGxlcnlfaW5pdCcsIFt0aGlzXSk7XG4gICAgICAgIH1cblxuICAgICAgICBzdGF0aWMgX2pRdWVyeUludGVyZmFjZShjb25maWcpIHtcbiAgICAgICAgICAgIHJldHVybiB0aGlzLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIG5ldyBXb29WYXJpYXRpb25HYWxsZXJ5KHRoaXMsIGNvbmZpZylcbiAgICAgICAgICAgIH0pXG4gICAgICAgIH1cblxuICAgICAgICBpbml0KCkge1xuICAgICAgICAgICAgcmV0dXJuIF8uZGVib3VuY2UoKCkgPT4ge1xuICAgICAgICAgICAgICAgIHRoaXMuaW5pdFNsaWNrKCk7XG4gICAgICAgICAgICAgICAgdGhpcy5pbml0Wm9vbSgpO1xuICAgICAgICAgICAgICAgIHRoaXMuaW5pdFBob3Rvc3dpcGUoKTtcbiAgICAgICAgICAgIH0sIDUwMCk7XG4gICAgICAgIH1cblxuICAgICAgICBkaW1lbnNpb24oKSB7XG5cbiAgICAgICAgICAgIC8vdGhpcy5fZWxlbWVudC5jc3MoJ21pbi1oZWlnaHQnLCAnMHB4Jyk7XG4gICAgICAgICAgICAvL3RoaXMuX2VsZW1lbnQuY3NzKCdtaW4td2lkdGgnLCAnMHB4Jyk7XG5cbiAgICAgICAgICAgIC8vcmV0dXJuIF8uZGVib3VuY2UoKCkgPT4ge1xuICAgICAgICAgICAgLy90aGlzLl9lbGVtZW50LmNzcygnbWluLWhlaWdodCcsIHRoaXMuJHNsaWRlci5oZWlnaHQoKSArICdweCcpO1xuICAgICAgICAgICAgLy90aGlzLl9lbGVtZW50LmNzcygnbWluLXdpZHRoJywgdGhpcy4kc2xpZGVyLndpZHRoKCkgKyAncHgnKTtcbiAgICAgICAgICAgIC8vfSwgNDAwKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGluaXRFdmVudHMoKSB7XG4gICAgICAgICAgICAvLyAkKHdpbmRvdykub24oJ3Jlc2l6ZScsIHRoaXMuZGltZW5zaW9uKCkpO1xuICAgICAgICAgICAgdGhpcy5fZWxlbWVudC5vbignd29vX3ZhcmlhdGlvbl9nYWxsZXJ5X2ltYWdlX2xvYWRlZCcsIHRoaXMuaW5pdCgpKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGluaXRTbGljaygpIHtcblxuICAgICAgICAgICAgaWYgKHRoaXMuJHNsaWRlci5oYXNDbGFzcygnc2xpY2staW5pdGlhbGl6ZWQnKSkge1xuICAgICAgICAgICAgICAgIHRoaXMuJHNsaWRlci5zbGljaygndW5zbGljaycpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB0aGlzLiRzbGlkZXIub2ZmKCdpbml0Jyk7XG4gICAgICAgICAgICB0aGlzLiRzbGlkZXIub2ZmKCdiZWZvcmVDaGFuZ2UnKTtcbiAgICAgICAgICAgIHRoaXMuJHNsaWRlci5vZmYoJ2FmdGVyQ2hhbmdlJyk7XG5cbiAgICAgICAgICAgIHRoaXMuX2VsZW1lbnQudHJpZ2dlcignd29vX3ZhcmlhdGlvbl9nYWxsZXJ5X2JlZm9yZV9pbml0JywgW3RoaXNdKTtcblxuICAgICAgICAgICAgLy8gU2xpZGVyXG5cbiAgICAgICAgICAgIHRoaXMuJHNsaWRlclxuICAgICAgICAgICAgICAgIC5vbignaW5pdCcsIChldmVudCkgPT4ge1xuICAgICAgICAgICAgICAgICAgICBpZiAodGhpcy5pbml0aWFsX2xvYWQpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMuaW5pdGlhbF9sb2FkID0gZmFsc2U7XG4gICAgICAgICAgICAgICAgICAgICAgICAvLyB0aGlzLl9lbGVtZW50LmNzcygnbWluLWhlaWdodCcsIHRoaXMuJHNsaWRlci5oZWlnaHQoKSArICdweCcpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSlcbiAgICAgICAgICAgICAgICAub24oJ2JlZm9yZUNoYW5nZScsIChldmVudCwgc2xpY2ssIGN1cnJlbnRTbGlkZSwgbmV4dFNsaWRlKSA9PiB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuJHRodW1ibmFpbC5maW5kKCcud3ZnLWdhbGxlcnktdGh1bWJuYWlsLWltYWdlJykubm90KCcuc2xpY2stc2xpZGUnKS5yZW1vdmVDbGFzcygnY3VycmVudC10aHVtYm5haWwnKTtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy4kdGh1bWJuYWlsLmZpbmQoJy53dmctZ2FsbGVyeS10aHVtYm5haWwtaW1hZ2UnKS5ub3QoJy5zbGljay1zbGlkZScpLmVxKG5leHRTbGlkZSkuYWRkQ2xhc3MoJ2N1cnJlbnQtdGh1bWJuYWlsJyk7XG4gICAgICAgICAgICAgICAgfSlcbiAgICAgICAgICAgICAgICAub24oJ2FmdGVyQ2hhbmdlJywgKGV2ZW50LCBzbGljaywgY3VycmVudFNsaWRlKSA9PiB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuc3RvcFZpZGVvKHRoaXMuJHNsaWRlcik7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuaW5pdFpvb21Gb3JUYXJnZXQoY3VycmVudFNsaWRlKTtcbiAgICAgICAgICAgICAgICB9KVxuICAgICAgICAgICAgICAgIC5zbGljaygpO1xuXG4gICAgICAgICAgICAvLyBUaHVtYm5haWxzXG5cbiAgICAgICAgICAgIHRoaXMuJHRodW1ibmFpbC5maW5kKCcud3ZnLWdhbGxlcnktdGh1bWJuYWlsLWltYWdlJykubm90KCcuc2xpY2stc2xpZGUnKS5maXJzdCgpLmFkZENsYXNzKCdjdXJyZW50LXRodW1ibmFpbCcpO1xuXG4gICAgICAgICAgICB0aGlzLiR0aHVtYm5haWwuZmluZCgnLnd2Zy1nYWxsZXJ5LXRodW1ibmFpbC1pbWFnZScpLm5vdCgnLnNsaWNrLXNsaWRlJykuZWFjaCgoaW5kZXgsIGVsKSA9PiB7XG4gICAgICAgICAgICAgICAgJChlbCkuZmluZCgnZGl2LCBpbWcnKS5vbignY2xpY2snLCAoZXZlbnQpID0+IHtcbiAgICAgICAgICAgICAgICAgICAgZXZlbnQucHJldmVudERlZmF1bHQoKTtcbiAgICAgICAgICAgICAgICAgICAgZXZlbnQuc3RvcFByb3BhZ2F0aW9uKCk7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuJHNsaWRlci5zbGljaygnc2xpY2tHb1RvJywgaW5kZXgpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIF8uZGVsYXkoKCkgPT4ge1xuICAgICAgICAgICAgICAgIHRoaXMuX2VsZW1lbnQudHJpZ2dlcignd29vX3ZhcmlhdGlvbl9nYWxsZXJ5X3NsaWRlcl9zbGlja19pbml0JywgW3RoaXNdKTtcbiAgICAgICAgICAgIH0sIDEpO1xuXG4gICAgICAgICAgICBfLmRlbGF5KCgpID0+IHtcbiAgICAgICAgICAgICAgICB0aGlzLnJlbW92ZUxvYWRpbmdDbGFzcygpO1xuICAgICAgICAgICAgfSwgMTAwKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGluaXRab29tRm9yVGFyZ2V0KGN1cnJlbnRTbGlkZSkge1xuXG4gICAgICAgICAgICBpZiAoIXdvb192YXJpYXRpb25fZ2FsbGVyeV9vcHRpb25zLmVuYWJsZV9nYWxsZXJ5X3pvb20pIHtcbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGxldCBnYWxsZXJ5V2lkdGggPSB0aGlzLiR0YXJnZXQud2lkdGgoKSxcbiAgICAgICAgICAgICAgICB6b29tRW5hYmxlZCAgPSBmYWxzZSxcbiAgICAgICAgICAgICAgICB6b29tVGFyZ2V0ICAgPSB0aGlzLiRzbGlkZXIuc2xpY2soJ2dldFNsaWNrJykuJHNsaWRlcy5lcShjdXJyZW50U2xpZGUpO1xuXG4gICAgICAgICAgICAkKHpvb21UYXJnZXQpLmVhY2goZnVuY3Rpb24gKGluZGV4LCB0YXJnZXQpIHtcbiAgICAgICAgICAgICAgICBsZXQgaW1hZ2UgPSAkKHRhcmdldCkuZmluZCgnaW1nJyk7XG5cbiAgICAgICAgICAgICAgICBpZiAoaW1hZ2UuZGF0YSgnbGFyZ2VfaW1hZ2Vfd2lkdGgnKSA+IGdhbGxlcnlXaWR0aCkge1xuICAgICAgICAgICAgICAgICAgICB6b29tRW5hYmxlZCA9IHRydWU7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgLy8gSWYgem9vbSBub3QgaW5jbHVkZWQuXG4gICAgICAgICAgICBpZiAoISQoKS56b29tKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAvLyBCdXQgb25seSB6b29tIGlmIHRoZSBpbWcgaXMgbGFyZ2VyIHRoYW4gaXRzIGNvbnRhaW5lci5cbiAgICAgICAgICAgIGlmICh6b29tRW5hYmxlZCkge1xuICAgICAgICAgICAgICAgIGxldCB6b29tX29wdGlvbnMgPSAkLmV4dGVuZCh7XG4gICAgICAgICAgICAgICAgICAgIHRvdWNoIDogZmFsc2VcbiAgICAgICAgICAgICAgICB9LCB3Y19zaW5nbGVfcHJvZHVjdF9wYXJhbXMuem9vbV9vcHRpb25zKTtcblxuICAgICAgICAgICAgICAgIGlmICgnb250b3VjaHN0YXJ0JyBpbiBkb2N1bWVudC5kb2N1bWVudEVsZW1lbnQpIHtcbiAgICAgICAgICAgICAgICAgICAgem9vbV9vcHRpb25zLm9uID0gJ2NsaWNrJztcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICB6b29tVGFyZ2V0LnRyaWdnZXIoJ3pvb20uZGVzdHJveScpO1xuICAgICAgICAgICAgICAgIHpvb21UYXJnZXQuem9vbSh6b29tX29wdGlvbnMpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG5cbiAgICAgICAgaW5pdFpvb20oKSB7XG4gICAgICAgICAgICBsZXQgY3VycmVudFNsaWRlID0gdGhpcy4kc2xpZGVyLnNsaWNrKCdzbGlja0N1cnJlbnRTbGlkZScpO1xuICAgICAgICAgICAgdGhpcy5pbml0Wm9vbUZvclRhcmdldChjdXJyZW50U2xpZGUpO1xuICAgICAgICB9XG5cbiAgICAgICAgaW5pdFBob3Rvc3dpcGUoKSB7XG5cbiAgICAgICAgICAgIGlmICghd29vX3ZhcmlhdGlvbl9nYWxsZXJ5X29wdGlvbnMuZW5hYmxlX2dhbGxlcnlfbGlnaHRib3gpIHtcbiAgICAgICAgICAgICAgICByZXR1cm47XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIHRoaXMuX2VsZW1lbnQub2ZmKCdjbGljaycsICcud29vLXZhcmlhdGlvbi1nYWxsZXJ5LXRyaWdnZXInKTtcbiAgICAgICAgICAgIHRoaXMuX2VsZW1lbnQub2ZmKCdjbGljaycsICcud3ZnLWdhbGxlcnktaW1hZ2UgYScpO1xuXG4gICAgICAgICAgICB0aGlzLl9lbGVtZW50Lm9uKCdjbGljaycsICcud29vLXZhcmlhdGlvbi1nYWxsZXJ5LXRyaWdnZXInLCAoZXZlbnQpID0+IHtcbiAgICAgICAgICAgICAgICB0aGlzLm9wZW5QaG90b3N3aXBlKGV2ZW50KVxuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIHRoaXMuX2VsZW1lbnQub24oJ2NsaWNrJywgJy53dmctZ2FsbGVyeS1pbWFnZSBhJywgKGV2ZW50KSA9PiB7XG4gICAgICAgICAgICAgICAgdGhpcy5vcGVuUGhvdG9zd2lwZShldmVudCk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuXG4gICAgICAgIG9wZW5QaG90b3N3aXBlKGV2ZW50KSB7XG5cbiAgICAgICAgICAgIGV2ZW50LnByZXZlbnREZWZhdWx0KCk7XG5cbiAgICAgICAgICAgIGlmICh0eXBlb2YoUGhvdG9Td2lwZSkgPT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICBsZXQgcHN3cEVsZW1lbnQgPSAkKCcucHN3cCcpWzBdLFxuICAgICAgICAgICAgICAgIGl0ZW1zICAgICAgID0gdGhpcy5nZXRHYWxsZXJ5SXRlbXMoKTtcblxuICAgICAgICAgICAgbGV0IG9wdGlvbnMgPSAkLmV4dGVuZCh7XG4gICAgICAgICAgICAgICAgaW5kZXggOiB0aGlzLiRzbGlkZXIuc2xpY2soJ3NsaWNrQ3VycmVudFNsaWRlJylcbiAgICAgICAgICAgIH0sIHdjX3NpbmdsZV9wcm9kdWN0X3BhcmFtcy5waG90b3N3aXBlX29wdGlvbnMpO1xuXG4gICAgICAgICAgICAvLyBJbml0aWFsaXplcyBhbmQgb3BlbnMgUGhvdG9Td2lwZS5cblxuICAgICAgICAgICAgbGV0IHBob3Rvc3dpcGUgPSBuZXcgUGhvdG9Td2lwZShwc3dwRWxlbWVudCwgUGhvdG9Td2lwZVVJX0RlZmF1bHQsIGl0ZW1zLCBvcHRpb25zKTtcblxuICAgICAgICAgICAgLy8gR2FsbGVyeSBzdGFydHMgY2xvc2luZ1xuICAgICAgICAgICAgcGhvdG9zd2lwZS5saXN0ZW4oJ2Nsb3NlJywgKCkgPT4ge1xuICAgICAgICAgICAgICAgIHRoaXMuc3RvcFZpZGVvKHBzd3BFbGVtZW50KTtcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICBwaG90b3N3aXBlLmxpc3RlbignYWZ0ZXJDaGFuZ2UnLCAoKSA9PiB7XG4gICAgICAgICAgICAgICAgdGhpcy5zdG9wVmlkZW8ocHN3cEVsZW1lbnQpO1xuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIHBob3Rvc3dpcGUuaW5pdCgpO1xuICAgICAgICB9XG5cbiAgICAgICAgc3RvcFZpZGVvKGVsZW1lbnQpIHtcbiAgICAgICAgICAgICQoZWxlbWVudCkuZmluZCgnaWZyYW1lLCB2aWRlbycpLmVhY2goZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIGxldCB0YWcgPSAkKHRoaXMpLnByb3AoXCJ0YWdOYW1lXCIpLnRvTG93ZXJDYXNlKCk7XG4gICAgICAgICAgICAgICAgaWYgKHRhZyA9PT0gJ2lmcmFtZScpIHtcbiAgICAgICAgICAgICAgICAgICAgbGV0IHNyYyA9ICQodGhpcykuYXR0cignc3JjJyk7XG4gICAgICAgICAgICAgICAgICAgICQodGhpcykuYXR0cignc3JjJywgc3JjKTtcbiAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICBpZiAodGFnID09PSAndmlkZW8nKSB7XG4gICAgICAgICAgICAgICAgICAgICQodGhpcylbMF0ucGF1c2UoKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfVxuXG4gICAgICAgIGFkZExvYWRpbmdDbGFzcygpIHtcbiAgICAgICAgICAgIHRoaXMuX2VsZW1lbnQuYWRkQ2xhc3MoJ2xvYWRpbmctZ2FsbGVyeScpO1xuICAgICAgICB9XG5cbiAgICAgICAgcmVtb3ZlTG9hZGluZ0NsYXNzKCkge1xuICAgICAgICAgICAgdGhpcy5fZWxlbWVudC5yZW1vdmVDbGFzcygnbG9hZGluZy1nYWxsZXJ5Jyk7XG4gICAgICAgIH1cblxuICAgICAgICBnZXRHYWxsZXJ5SXRlbXMoKSB7XG4gICAgICAgICAgICBsZXQgJHNsaWRlcyA9IHRoaXMuJHNsaWRlci5zbGljaygnZ2V0U2xpY2snKS4kc2xpZGVzLFxuICAgICAgICAgICAgICAgIGl0ZW1zICAgPSBbXTtcblxuICAgICAgICAgICAgaWYgKCRzbGlkZXMubGVuZ3RoID4gMCkge1xuICAgICAgICAgICAgICAgICRzbGlkZXMuZWFjaChmdW5jdGlvbiAoaSwgZWwpIHtcbiAgICAgICAgICAgICAgICAgICAgbGV0IGltZyA9ICQoZWwpLmZpbmQoJ2ltZywgaWZyYW1lLCB2aWRlbycpO1xuICAgICAgICAgICAgICAgICAgICBsZXQgdGFnID0gJChpbWcpLnByb3AoXCJ0YWdOYW1lXCIpLnRvTG93ZXJDYXNlKCk7XG5cbiAgICAgICAgICAgICAgICAgICAgbGV0IHNyYywgaXRlbTtcbiAgICAgICAgICAgICAgICAgICAgc3dpdGNoICh0YWcpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGNhc2UgJ2ltZyc6XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbGV0IGxhcmdlX2ltYWdlX3NyYyA9IGltZy5hdHRyKCdkYXRhLWxhcmdlX2ltYWdlJyksXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGxhcmdlX2ltYWdlX3cgICA9IGltZy5hdHRyKCdkYXRhLWxhcmdlX2ltYWdlX3dpZHRoJyksXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGxhcmdlX2ltYWdlX2ggICA9IGltZy5hdHRyKCdkYXRhLWxhcmdlX2ltYWdlX2hlaWdodCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGl0ZW0gICAgICAgICAgICAgICAgPSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNyYyAgIDogbGFyZ2VfaW1hZ2Vfc3JjLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB3ICAgICA6IGxhcmdlX2ltYWdlX3csXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGggICAgIDogbGFyZ2VfaW1hZ2VfaCxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdGl0bGUgOiBpbWcuYXR0cignZGF0YS1jYXB0aW9uJykgPyBpbWcuYXR0cignZGF0YS1jYXB0aW9uJykgOiBpbWcuYXR0cigndGl0bGUnKVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH07XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgYnJlYWs7XG4gICAgICAgICAgICAgICAgICAgICAgICBjYXNlICdpZnJhbWUnOlxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNyYyAgPSBpbWcuYXR0cignc3JjJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaXRlbSA9IHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgaHRtbCA6IGA8aWZyYW1lIGNsYXNzPVwid3ZnLWxpZ2h0Ym94LWlmcmFtZVwiIHNyYz1cIiR7c3JjfVwiIHN0eWxlPVwid2lkdGg6IDEwMCU7IGhlaWdodDogMTAwJTsgbWFyZ2luOiAwO3BhZGRpbmc6IDA7IGJhY2tncm91bmQtY29sb3I6ICMwMDAwMDBcIiBmcmFtZWJvcmRlcj1cIjBcIiB3ZWJraXRBbGxvd0Z1bGxTY3JlZW4gbW96YWxsb3dmdWxsc2NyZWVuIGFsbG93RnVsbFNjcmVlbj48L2lmcmFtZT5gXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGJyZWFrO1xuICAgICAgICAgICAgICAgICAgICAgICAgY2FzZSAndmlkZW8nOlxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHNyYyAgPSBpbWcuYXR0cignc3JjJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaXRlbSA9IHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgaHRtbCA6IGA8dmlkZW8gY2xhc3M9XCJ3dmctbGlnaHRib3gtdmlkZW9cIiBjb250cm9scyBjb250cm9sc0xpc3Q9XCJub2Rvd25sb2FkXCIgc3JjPVwiJHtzcmN9XCIgc3R5bGU9XCJ3aWR0aDogMTAwJTsgaGVpZ2h0OiAxMDAlOyBtYXJnaW46IDA7cGFkZGluZzogMDsgYmFja2dyb3VuZC1jb2xvcjogIzAwMDAwMFwiPjwvdmlkZW8+YFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgIGl0ZW1zLnB1c2goaXRlbSk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICByZXR1cm4gaXRlbXM7XG4gICAgICAgIH1cblxuICAgICAgICBkZXN0cm95U2xpY2soKSB7XG5cbiAgICAgICAgICAgIHRoaXMuJHNsaWRlci5odG1sKCcnKTtcbiAgICAgICAgICAgIHRoaXMuJHRodW1ibmFpbC5odG1sKCcnKTtcblxuICAgICAgICAgICAgaWYgKHRoaXMuJHNsaWRlci5oYXNDbGFzcygnc2xpY2staW5pdGlhbGl6ZWQnKSkge1xuICAgICAgICAgICAgICAgIHRoaXMuJHNsaWRlci5zbGljaygndW5zbGljaycpO1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB0aGlzLl9lbGVtZW50LnRyaWdnZXIoJ3dvb192YXJpYXRpb25fZ2FsbGVyeV9zbGlja19kZXN0cm95JywgW3RoaXNdKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGRlZmF1bHRHYWxsZXJ5KCkge1xuXG4gICAgICAgICAgICBpZiAodGhpcy5pc192YXJpYXRpb25fcHJvZHVjdCkge1xuICAgICAgICAgICAgICAgIHdwLmFqYXguc2VuZCgnd3ZnX2dldF9kZWZhdWx0X2dhbGxlcnknLCB7XG4gICAgICAgICAgICAgICAgICAgIGRhdGEgICAgOiB7XG4gICAgICAgICAgICAgICAgICAgICAgICBwcm9kdWN0X2lkIDogdGhpcy5wcm9kdWN0X2lkXG4gICAgICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgICAgIHN1Y2Nlc3MgOiAoZGF0YSkgPT4ge1xuICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5fZWxlbWVudC5kYXRhKCd3b29fdmFyaWF0aW9uX2dhbGxlcnlfZGVmYXVsdCcsIGRhdGEpO1xuICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5fZWxlbWVudC50cmlnZ2VyKCd3b29fdmFyaWF0aW9uX2RlZmF1bHRfZ2FsbGVyeV9sb2FkZWQnLCB0aGlzKTtcbiAgICAgICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgICAgICAgICAgZXJyb3IgICA6IChlKSA9PiB7XG4gICAgICAgICAgICAgICAgICAgICAgICB0aGlzLl9lbGVtZW50LmRhdGEoJ3dvb192YXJpYXRpb25fZ2FsbGVyeV9kZWZhdWx0JywgW10pO1xuICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5fZWxlbWVudC50cmlnZ2VyKCd3b29fdmFyaWF0aW9uX2RlZmF1bHRfZ2FsbGVyeV9sb2FkZWQnLCB0aGlzKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIGNvbnNvbGUuZXJyb3IoYFZhcmlhdGlvbiBHYWxsZXJ5IG5vdCBhdmFpbGFibGUgb24gdmFyaWF0aW9uIGlkICR7dGhpcy5wcm9kdWN0X2lkfS5gKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG5cbiAgICAgICAgaW5pdFZhcmlhdGlvbkltYWdlUHJlbG9hZCgpIHtcbiAgICAgICAgICAgIC8vcmV0dXJuO1xuICAgICAgICAgICAgaWYgKHRoaXMuaXNfdmFyaWF0aW9uX3Byb2R1Y3QpIHtcbiAgICAgICAgICAgICAgICB3cC5hamF4LnNlbmQoJ3d2Z19nZXRfYXZhaWxhYmxlX3ZhcmlhdGlvbl9pbWFnZXMnLCB7XG4gICAgICAgICAgICAgICAgICAgIGRhdGEgICAgOiB7XG4gICAgICAgICAgICAgICAgICAgICAgICBwcm9kdWN0X2lkIDogdGhpcy5wcm9kdWN0X2lkXG4gICAgICAgICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICAgICAgICAgIHN1Y2Nlc3MgOiAoaW1hZ2VzKSA9PiB7XG4gICAgICAgICAgICAgICAgICAgICAgICAvLyBjb25zb2xlLmxvZyhkYXRhKVxuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKGltYWdlcy5sZW5ndGggPiAxKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5pbWFnZVByZWxvYWQoaW1hZ2VzKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMuX2VsZW1lbnQuZGF0YSgnd29vX3ZhcmlhdGlvbl9nYWxsZXJ5X3ZhcmlhdGlvbl9pbWFnZXMnLCBpbWFnZXMpO1xuICAgICAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgICAgICBlcnJvciAgIDogKGUpID0+IHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRoaXMuX2VsZW1lbnQuZGF0YSgnd29vX3ZhcmlhdGlvbl9nYWxsZXJ5X3ZhcmlhdGlvbl9pbWFnZXMnLCBbXSk7XG4gICAgICAgICAgICAgICAgICAgICAgICBjb25zb2xlLmVycm9yKGBWYXJpYXRpb24gR2FsbGVyeSB2YXJpYXRpb25zIGltYWdlcyBub3QgYXZhaWxhYmxlIG9uIHZhcmlhdGlvbiBpZCAke3RoaXMucHJvZHVjdF9pZH0uYCk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuXG4gICAgICAgIGltYWdlUHJlbG9hZChpbWFnZXMpIHtcbiAgICAgICAgICAgIGZvciAobGV0IGkgPSAwOyBpIDwgaW1hZ2VzLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgICAgICAgICAgdHJ5IHtcblxuICAgICAgICAgICAgICAgICAgICAvLyBOb3RlOiB0aGlzIHdvbid0IHdvcmsgd2hlbiBjaHJvbWUgZGV2dG9vbCBpcyBvcGVuIGFuZCAnZGlzYWJsZSBjYWNoZScgaXMgZW5hYmxlZCB3aXRoaW4gdGhlIG5ldHdvcmsgcGFuZWxcbiAgICAgICAgICAgICAgICAgICAgbGV0IF9pbWcgICAgICAgPSBuZXcgSW1hZ2UoKTtcbiAgICAgICAgICAgICAgICAgICAgbGV0IF9nYWxsZXJ5ICAgPSBuZXcgSW1hZ2UoKTtcbiAgICAgICAgICAgICAgICAgICAgbGV0IF9mdWxsICAgICAgPSBuZXcgSW1hZ2UoKTtcbiAgICAgICAgICAgICAgICAgICAgbGV0IF90aHVtYm5haWwgPSBuZXcgSW1hZ2UoKTtcblxuICAgICAgICAgICAgICAgICAgICBfaW1nLnNyYyAgICA9IGltYWdlc1tpXS5zcmM7XG4gICAgICAgICAgICAgICAgICAgIF9pbWcuc3Jjc2V0ID0gaW1hZ2VzW2ldLnNyY3NldDtcblxuICAgICAgICAgICAgICAgICAgICBfZ2FsbGVyeS5zcmMgPSBpbWFnZXNbaV0uZ2FsbGVyeV90aHVtYm5haWxfc3JjO1xuXG4gICAgICAgICAgICAgICAgICAgIF9mdWxsLnNyYyA9IGltYWdlc1tpXS5mdWxsX3NyYztcblxuICAgICAgICAgICAgICAgICAgICBfdGh1bWJuYWlsLnNyYyA9IGltYWdlc1tpXS50aHVtYl9zcmM7XG5cbiAgICAgICAgICAgICAgICAgICAgLy8gQXBwZW5kIENvbnRlbnRcbiAgICAgICAgICAgICAgICAgICAgLypsZXQgX2ltZ19zcmMgICAgPSBpbWFnZXNbaV0uc3JjO1xuICAgICAgICAgICAgICAgICAgICBsZXQgX2ltZ19zcmNzZXQgPSBpbWFnZXNbaV0uc3Jjc2V0O1xuXG4gICAgICAgICAgICAgICAgICAgIGxldCBfZ2FsbGVyeV9zcmMgICA9IGltYWdlc1tpXS5nYWxsZXJ5X3RodW1ibmFpbF9zcmM7XG4gICAgICAgICAgICAgICAgICAgIGxldCBfZnVsbF9zcmMgICAgICA9IGltYWdlc1tpXS5mdWxsX3NyYztcbiAgICAgICAgICAgICAgICAgICAgbGV0IF90aHVtYm5haWxfc3JjID0gaW1hZ2VzW2ldLnRodW1iX3NyYztcblxuICAgICAgICAgICAgICAgICAgICBsZXQgdGVtcGxhdGUgPSBgPGRpdiBzdHlsZT1cImRpc3BsYXk6IG5vbmVcIj48aW1nIGFyaWEtaGlkZGVuPVwidHJ1ZVwiIHN0eWxlPVwiZGlzcGxheTogbm9uZVwiIHNyYz1cIiR7X2ltZ19zcmN9XCIgc3Jjc2V0PVwiJHtfaW1nX3NyY3NldH1cIiAvPjxpbWcgc3R5bGU9XCJkaXNwbGF5OiBub25lXCIgc3JjPVwiJHtfZ2FsbGVyeV9zcmN9XCIgLz48aW1nIHN0eWxlPVwiZGlzcGxheTogbm9uZVwiIHNyYz1cIiR7X3RodW1ibmFpbF9zcmN9XCIgLz48aW1nIHN0eWxlPVwiZGlzcGxheTogbm9uZVwiIHNyYz1cIiR7X2Z1bGxfc3JjfVwiIC8+PC9kaXY+YDtcbiAgICAgICAgICAgICAgICAgICAgJCgnYm9keScpLmFwcGVuZCh0ZW1wbGF0ZSkqL1xuXG4gICAgICAgICAgICAgICAgfSBjYXRjaCAoZSkge1xuICAgICAgICAgICAgICAgICAgICBjb25zb2xlLmVycm9yKGUpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuXG4gICAgICAgIGluaXRWYXJpYXRpb25HYWxsZXJ5KCkge1xuXG4gICAgICAgICAgICAvLyBzaG93X3ZhcmlhdGlvbiwgZm91bmRfdmFyaWF0aW9uXG5cbiAgICAgICAgICAgIHRoaXMuJHZhcmlhdGlvbnNfZm9ybS5vbignc2hvd192YXJpYXRpb24nLCAoZXZlbnQsIHZhcmlhdGlvbikgPT4ge1xuICAgICAgICAgICAgICAgIHRoaXMuYWRkTG9hZGluZ0NsYXNzKCk7XG4gICAgICAgICAgICAgICAgdGhpcy5nYWxsZXJ5SW5pdCh2YXJpYXRpb24udmFyaWF0aW9uX2dhbGxlcnlfaW1hZ2VzKTtcbiAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICBpZiAod29vX3ZhcmlhdGlvbl9nYWxsZXJ5X29wdGlvbnMuZ2FsbGVyeV9yZXNldF9vbl92YXJpYXRpb25fY2hhbmdlKSB7XG4gICAgICAgICAgICAgICAgdGhpcy4kdmFyaWF0aW9uc19mb3JtLm9uKCdyZXNldF9pbWFnZScsIChldmVudCkgPT4ge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmFkZExvYWRpbmdDbGFzcygpO1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmdhbGxlcnlSZXNldCgpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgdGhpcy4kdmFyaWF0aW9uc19mb3JtLm9uKCdjbGljay53Yy12YXJpYXRpb24tZm9ybScsICcucmVzZXRfdmFyaWF0aW9ucycsIChldmVudCkgPT4ge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmFkZExvYWRpbmdDbGFzcygpO1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmdhbGxlcnlSZXNldCgpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG5cbiAgICAgICAgZ2FsbGVyeVJlc2V0KCkge1xuICAgICAgICAgICAgbGV0ICRkZWZhdWx0X2dhbGxlcnkgPSB0aGlzLl9lbGVtZW50LmRhdGEoJ3dvb192YXJpYXRpb25fZ2FsbGVyeV9kZWZhdWx0Jyk7XG5cbiAgICAgICAgICAgIGlmICgkZGVmYXVsdF9nYWxsZXJ5ICYmICRkZWZhdWx0X2dhbGxlcnkubGVuZ3RoID4gMCkge1xuICAgICAgICAgICAgICAgIHRoaXMuZ2FsbGVyeUluaXQoJGRlZmF1bHRfZ2FsbGVyeSk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICBfLmRlbGF5KCgpID0+IHtcbiAgICAgICAgICAgICAgICAgICAgdGhpcy5yZW1vdmVMb2FkaW5nQ2xhc3MoKTtcbiAgICAgICAgICAgICAgICB9LCAxMDApXG4gICAgICAgICAgICB9XG4gICAgICAgIH1cblxuICAgICAgICBnYWxsZXJ5SW5pdChpbWFnZXMpIHtcblxuICAgICAgICAgICAgbGV0IGhhc0dhbGxlcnkgPSBpbWFnZXMubGVuZ3RoID4gMTtcblxuICAgICAgICAgICAgdGhpcy5fZWxlbWVudC50cmlnZ2VyKCdiZWZvcmVfd29vX3ZhcmlhdGlvbl9nYWxsZXJ5X2luaXQnLCBbdGhpcywgaW1hZ2VzXSk7XG5cbiAgICAgICAgICAgIHRoaXMuZGVzdHJveVNsaWNrKCk7XG5cbiAgICAgICAgICAgIGxldCBzbGlkZXJfaW5uZXJfaHRtbCA9IGltYWdlcy5tYXAoKGltYWdlKSA9PiB7XG4gICAgICAgICAgICAgICAgbGV0IHRlbXBsYXRlID0gd3AudGVtcGxhdGUoJ3dvby12YXJpYXRpb24tZ2FsbGVyeS1zbGlkZXItdGVtcGxhdGUnKTtcbiAgICAgICAgICAgICAgICByZXR1cm4gdGVtcGxhdGUoaW1hZ2UpO1xuICAgICAgICAgICAgfSkuam9pbignJyk7XG5cbiAgICAgICAgICAgIGxldCB0aHVtYm5haWxfaW5uZXJfaHRtbCA9IGltYWdlcy5tYXAoKGltYWdlKSA9PiB7XG4gICAgICAgICAgICAgICAgbGV0IHRlbXBsYXRlID0gd3AudGVtcGxhdGUoJ3dvby12YXJpYXRpb24tZ2FsbGVyeS10aHVtYm5haWwtdGVtcGxhdGUnKTtcbiAgICAgICAgICAgICAgICByZXR1cm4gdGVtcGxhdGUoaW1hZ2UpO1xuICAgICAgICAgICAgfSkuam9pbignJyk7XG5cbiAgICAgICAgICAgIGlmIChoYXNHYWxsZXJ5KSB7XG4gICAgICAgICAgICAgICAgdGhpcy4kdGFyZ2V0LmFkZENsYXNzKCd3b28tdmFyaWF0aW9uLWdhbGxlcnktaGFzLXByb2R1Y3QtdGh1bWJuYWlsJyk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICB0aGlzLiR0YXJnZXQucmVtb3ZlQ2xhc3MoJ3dvby12YXJpYXRpb24tZ2FsbGVyeS1oYXMtcHJvZHVjdC10aHVtYm5haWwnKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgdGhpcy4kc2xpZGVyLmh0bWwoc2xpZGVyX2lubmVyX2h0bWwpO1xuXG4gICAgICAgICAgICBpZiAoaGFzR2FsbGVyeSkge1xuICAgICAgICAgICAgICAgIHRoaXMuJHRodW1ibmFpbC5odG1sKHRodW1ibmFpbF9pbm5lcl9odG1sKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgICAgIHRoaXMuJHRodW1ibmFpbC5odG1sKCcnKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgLy90aGlzLl9lbGVtZW50LnRyaWdnZXIoJ3dvb192YXJpYXRpb25fZ2FsbGVyeV9pbml0JywgW3RoaXMsIGltYWdlc10pO1xuXG4gICAgICAgICAgICBfLmRlbGF5KCgpID0+IHtcbiAgICAgICAgICAgICAgICB0aGlzLmltYWdlc0xvYWRlZCgpO1xuICAgICAgICAgICAgfSwgMSk7XG5cbiAgICAgICAgICAgIC8vdGhpcy5fZWxlbWVudC50cmlnZ2VyKCdhZnRlcl93b29fdmFyaWF0aW9uX2dhbGxlcnlfaW5pdCcsIFt0aGlzLCBpbWFnZXNdKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGltYWdlc0xvYWRlZCgpIHtcbiAgICAgICAgICAgIHRoaXMuX2VsZW1lbnQuaW1hZ2VzTG9hZGVkKClcbiAgICAgICAgICAgICAgICAucHJvZ3Jlc3MoKGluc3RhbmNlLCBpbWFnZSkgPT4ge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLl9lbGVtZW50LnRyaWdnZXIoJ3dvb192YXJpYXRpb25fZ2FsbGVyeV9pbWFnZV9sb2FkaW5nJywgW3RoaXNdKTtcbiAgICAgICAgICAgICAgICB9KVxuICAgICAgICAgICAgICAgIC5kb25lKChpbnN0YW5jZSkgPT4ge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLl9lbGVtZW50LnRyaWdnZXIoJ3dvb192YXJpYXRpb25fZ2FsbGVyeV9pbWFnZV9sb2FkZWQnLCBbdGhpc10pO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXG4gICAgICogalF1ZXJ5XG4gICAgICogLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXG4gICAgICovXG5cbiAgICAkLmZuWydXb29WYXJpYXRpb25HYWxsZXJ5J10gPSBXb29WYXJpYXRpb25HYWxsZXJ5Ll9qUXVlcnlJbnRlcmZhY2U7XG4gICAgJC5mblsnV29vVmFyaWF0aW9uR2FsbGVyeSddLkNvbnN0cnVjdG9yID0gV29vVmFyaWF0aW9uR2FsbGVyeTtcbiAgICAkLmZuWydXb29WYXJpYXRpb25HYWxsZXJ5J10ubm9Db25mbGljdCAgPSBmdW5jdGlvbiAoKSB7XG4gICAgICAgICQuZm5bJ1dvb1ZhcmlhdGlvbkdhbGxlcnknXSA9ICQuZm5bJ1dvb1ZhcmlhdGlvbkdhbGxlcnknXTtcbiAgICAgICAgcmV0dXJuIFdvb1ZhcmlhdGlvbkdhbGxlcnkuX2pRdWVyeUludGVyZmFjZVxuICAgIH07XG5cbiAgICByZXR1cm4gV29vVmFyaWF0aW9uR2FsbGVyeTtcblxufSkoalF1ZXJ5KTtcblxuZXhwb3J0IGRlZmF1bHQgV29vVmFyaWF0aW9uR2FsbGVyeVxuXG5cbi8vIFdFQlBBQ0sgRk9PVEVSIC8vXG4vLyBzcmMvanMvV29vVmFyaWF0aW9uR2FsbGVyeS5qcyIsIi8vIHJlbW92ZWQgYnkgZXh0cmFjdC10ZXh0LXdlYnBhY2stcGx1Z2luXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvc2Nzcy9zbGljay5zY3NzXG4vLyBtb2R1bGUgaWQgPSAzXG4vLyBtb2R1bGUgY2h1bmtzID0gMCIsIi8vIHJlbW92ZWQgYnkgZXh0cmFjdC10ZXh0LXdlYnBhY2stcGx1Z2luXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvc2Nzcy9mcm9udGVuZC5zY3NzXG4vLyBtb2R1bGUgaWQgPSA0XG4vLyBtb2R1bGUgY2h1bmtzID0gMCIsIi8vIHJlbW92ZWQgYnkgZXh0cmFjdC10ZXh0LXdlYnBhY2stcGx1Z2luXG5cblxuLy8vLy8vLy8vLy8vLy8vLy8vXG4vLyBXRUJQQUNLIEZPT1RFUlxuLy8gLi9zcmMvc2Nzcy90aGVtZS1zdXBwb3J0LnNjc3Ncbi8vIG1vZHVsZSBpZCA9IDVcbi8vIG1vZHVsZSBjaHVua3MgPSAwIiwiLy8gcmVtb3ZlZCBieSBleHRyYWN0LXRleHQtd2VicGFjay1wbHVnaW5cblxuXG4vLy8vLy8vLy8vLy8vLy8vLy9cbi8vIFdFQlBBQ0sgRk9PVEVSXG4vLyAuL3NyYy9zY3NzL2JhY2tlbmQuc2Nzc1xuLy8gbW9kdWxlIGlkID0gNlxuLy8gbW9kdWxlIGNodW5rcyA9IDAiLCIvLyByZW1vdmVkIGJ5IGV4dHJhY3QtdGV4dC13ZWJwYWNrLXBsdWdpblxuXG5cbi8vLy8vLy8vLy8vLy8vLy8vL1xuLy8gV0VCUEFDSyBGT09URVJcbi8vIC4vc3JjL3Njc3MvZ3dwLWFkbWluLnNjc3Ncbi8vIG1vZHVsZSBpZCA9IDdcbi8vIG1vZHVsZSBjaHVua3MgPSAwIl0sIm1hcHBpbmdzIjoiOzs7Ozs7OztBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7QUM3REE7QUFDQTtBQUFBO0FBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7Ozs7Ozs7Ozs7O0FDcEJBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUhBO0FBTUE7QUFBQTtBQUNBO0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQTNDQTtBQUFBO0FBQUE7QUFrREE7QUFDQTtBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQXhEQTtBQUFBO0FBQUE7QUFDQTtBQTJEQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBbkVBO0FBQUE7QUFBQTtBQXNFQTtBQUNBO0FBQ0E7QUF4RUE7QUFBQTtBQUFBO0FBMEVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFFQTtBQUNBO0FBQ0E7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUE1SEE7QUFBQTtBQUFBO0FBQ0E7QUErSEE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUFBO0FBQUE7QUFDQTtBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQURBO0FBQ0E7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBbktBO0FBQUE7QUFBQTtBQXNLQTtBQUNBO0FBQ0E7QUF4S0E7QUFBQTtBQUFBO0FBMEtBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUExTEE7QUFBQTtBQUFBO0FBNExBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUFBO0FBQ0E7QUFFQTtBQUNBO0FBREE7QUFDQTtBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQXpOQTtBQUFBO0FBQUE7QUE0TkE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBdk9BO0FBQUE7QUFBQTtBQTBPQTtBQUNBO0FBM09BO0FBQUE7QUFBQTtBQThPQTtBQUNBO0FBL09BO0FBQUE7QUFBQTtBQWtQQTtBQUFBO0FBQ0E7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUFBO0FBQUE7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBSkE7QUFNQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBREE7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBREE7QUFHQTtBQXZCQTtBQUNBO0FBeUJBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUF6UkE7QUFBQTtBQUFBO0FBQ0E7QUE0UkE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBclNBO0FBQUE7QUFBQTtBQXVTQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFEQTtBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQVpBO0FBY0E7QUFDQTtBQXpUQTtBQUFBO0FBQUE7QUEyVEE7QUFDQTtBQUFBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFEQTtBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFkQTtBQWdCQTtBQUNBO0FBL1VBO0FBQUE7QUFBQTtBQWtWQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7Ozs7O0FBVUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQW5YQTtBQUFBO0FBQUE7QUFxWEE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBMVlBO0FBQUE7QUFBQTtBQTRZQTtBQUNBO0FBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUF2WkE7QUFBQTtBQUFBO0FBeVpBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBbGNBO0FBQUE7QUFBQTtBQW9jQTtBQUNBO0FBQUE7QUFFQTtBQUNBO0FBRUE7QUFDQTtBQUNBO0FBNWNBO0FBQUE7QUFBQTtBQTZDQTtBQUNBO0FBQ0E7QUFDQTtBQWhEQTtBQUNBO0FBREE7QUFBQTtBQUNBO0FBOGNBOzs7Ozs7QUFNQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBRUE7QUFDQTtBQUNBOzs7Ozs7QUNyZUE7Ozs7OztBQ0FBOzs7Ozs7QUNBQTs7Ozs7O0FDQUE7Ozs7OztBQ0FBOzs7QSIsInNvdXJjZVJvb3QiOiIifQ==