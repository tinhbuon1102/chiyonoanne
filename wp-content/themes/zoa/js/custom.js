'use strict';
var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

document.addEventListener('DOMContentLoaded', function () {
  var _body = document.body;

  /*! BEFORE LOAD
    ------------------------------------------------->*/
  window.addEventListener('beforeunload', function () {
    if (document.getElementsByClassName('is-page-loading')[0]) {
      var themeContainer = document.getElementById('theme-container');

      if (themeContainer) {
        themeContainer.classList.add('is-loading');
        themeContainer.classList.remove('is-ready');
      }
    }
  });

  /*! PRELOADER `start()`
    ------------------------------------------------->*/
  if (document.getElementsByClassName('is-page-loading')[0]) {
    NProgress.configure({
      template: '<div class="bar" role="bar"></div>',
      parent: '#page-loader',
      showSpinner: true,
      easing: 'ease',
      minimum: 0.3,
      speed: 500
    });

    NProgress.start();
  }

  /*! SIDEBAR MENU
    ------------------------------------------------->*/
  var theme_menu = function () {
    /*TOGGLE SIDEBAR MENU*/
    var container = document.getElementById('theme-container'),
        btn = document.getElementById('menu-toggle-btn'),
        menuContent = document.getElementById('sidebar-menu-content'),
        menuOverlay = document.getElementById('menu-overlay');

    if (!btn) return;

    btn.addEventListener('click', function () {
      container.classList.add('menu-open');
    });

    menuOverlay.addEventListener('click', function () {
      container.classList.remove('menu-open');
    });

    _body.addEventListener('keyup', function (e) {
      if (27 === e.keyCode) {
        container.classList.remove('menu-open');
      }
    });

    /*MENU ACCORDION*/
    jQuery('.theme-sidebar-menu a').on('click', function (e) {
      e.preventDefault();

      var t = jQuery(this),
          s = t.siblings(),
          l = s.length;

      /*GO TO URL IF NOT SUB-MENU*/
      if (!l) {
        window.location.href = t.prop('href');
      }

      if (t.next().hasClass('show')) {
        t.next().removeClass('show');
        t.next().slideUp(200);
      } else {
        t.parent().parent().find('li .sub-menu').removeClass('show');
        t.parent().parent().find('li .sub-menu').slideUp(200);
        t.next().toggleClass('show');
        t.next().slideToggle(200);
      }
    });
  };

  /*! SCROLL TO TOP
    ------------------------------------------------->*/
  var $scrollTop = jQuery('.js-to-top');

  jQuery(window).on('scroll', function () {
    if (jQuery(this).scrollTop() > 200) {
      $scrollTop.addClass('is-visible');
    } else {
      $scrollTop.removeClass('is-visible');
    }
  });

  $scrollTop.on('click', function (e) {
    e.preventDefault();
    jQuery('html, body').animate({ scrollTop: 0 }, 500);
  });

  /*! FLEXIBLE SIDEBAR
    ------------------------------------------------->*/
  var sidebarToggle = document.querySelector('.js-sidebar-toggle');
  var toggleIcon = document.querySelector('.toggle-icon');
  var shopSidebar = document.querySelector('.shop-sidebar');
  var sidebarOverlay = document.querySelector('.sidebar-overlay');
  if (sidebarToggle && sidebarOverlay && toggleIcon) {
    sidebarToggle.addEventListener('click', function (e) {
      e.preventDefault();
      shopSidebar.classList.toggle('is-visible');
      sidebarOverlay.classList.toggle('is-visible');
      document.body.classList.toggle('hide-scrollbar');
      this.classList.toggle('is-active');
      if (toggleIcon.classList.contains('ion-android-options')) {
        toggleIcon.classList.remove('ion-android-options');
        toggleIcon.classList.add('ion-android-close');
      } else {
        toggleIcon.classList.remove('ion-android-close');
        toggleIcon.classList.add('ion-android-options');
      }
    });

    sidebarOverlay.addEventListener('click', function () {
      shopSidebar.classList.remove('is-visible');
      this.classList.remove('is-visible');
      document.body.classList.remove('hide-scrollbar');
      sidebarToggle.classList.remove('is-active');
      toggleIcon.classList.remove('ion-android-close');
      toggleIcon.classList.add('ion-android-options');
    });
  }

  /*! STICKY HEADER
    ------------------------------------------------->*/
  var $stickyHeader = jQuery('.js-sticky-header');
  var $headerDOM = jQuery('#theme-menu-layout');

  if ($stickyHeader.length && $headerDOM) {
    var $headerOffsetTop = $headerDOM.offset().top;
    var $headerHeight = $headerDOM.height();
    var $stickyHeaderOffset = $headerOffsetTop + $headerHeight;

    jQuery(window).on('scroll', function () {
      if (jQuery(this).scrollTop() > $stickyHeaderOffset) {
        $stickyHeader.addClass('is-visible');
      } else {
        $stickyHeader.removeClass('is-visible');
      }
    });
  }

  /*! MEGA MENU
    ------------------------------------------------->*/
  var mega_menu = function () {
    if (window.width < 992) return;

    var menu_layouts = Array.from(document.querySelectorAll('.theme-primary-menu:not(.theme-sidebar-menu)'));
    if (!menu_layouts) return;

    menu_layouts.forEach(function (menu_layout) {
      var mega = menu_layout.getElementsByClassName('menu-item-has-mega-menu'),
          mega_length = mega.length;
      if (!mega) return;

      for (var i = 0; i < mega_length; i++) {
        var mega_row = mega[i].getElementsByClassName('mega-menu-row')[0];
        if (mega_row)
        {
        	var mega_col = mega_row.getElementsByClassName('mega-menu-col'),
        	mega_col_length = mega_col.length,
        	_rect = mega_row.getBoundingClientRect(),
        	_ww = _body.clientWidth,
        	_right = _ww - _rect.right;
        	
        	mega_row.classList.add('mega-menu-col-' + mega_col_length);
        	
        	if (_right < 0) {
        		mega_row.classList.add('mega-menu-in-right');
        	} else {
        		mega_row.classList.remove('mega-menu-in-right');
        	}
        }
      }
    });
  }();

  /*! PRODUCT SWATCH LIST
    ------------------------------------------------->*/
  var swatchList = function () {

    jQuery(_body).on('click', '.p-attr-swatch', function () {
      var img_src = void 0,
          t = jQuery(this),
          src = t.data('src'),
          product = t.closest('.product'),
          img_wrap = product.find('.product-image-wrapper'),
          img = img_wrap.find('img'),
          origin_src = img.data('origin_src');

      img.prop('srcset', '');

      if (t.hasClass('active')) {
        img_src = origin_src;
        t.removeClass('active');
      } else {
        img_src = src;
        t.addClass('active').siblings().removeClass('active');
      }

      if (img.prop('src') == img_src) return;

      img_wrap.addClass('image-is-loading');

      img.prop('src', img_src).one('load', function () {
        return img_wrap.removeClass('image-is-loading');
      });
    });
  }();

  /*! PRODUCT ACTION
    ------------------------------------------------->*/
  var product_action = function () {
    var wc = _body.classList.contains('woocommerce-js'),
        _overlay = document.getElementById('shop-overlay');
    if (!wc) return;

    /*OPEN CART SIDEBAR*/
    var open_cart_sidebar = function open_cart_sidebar() {
      _body.classList.add('cart-sidebar-open');
    };

    /*VAR*/
    var shopping_cart_btns = Array.from(document.querySelectorAll('.js-cart-button')),
        close_cart_sidebar_btn = document.getElementById('close-cart-sidebar');

    /* OPEN CART SIDEBAR BY HEADER BUTTON */
    shopping_cart_btns.forEach(function (shopping_cart_btn) {
      shopping_cart_btn.addEventListener('click', function (e) {
        e.preventDefault();

        if (_body.classList.contains('woocommerce-cart')) return;

        open_cart_sidebar();
        close_cart_sidebar();
      });
    });

    /*CLOSE SIDEBAR ACTION*/
    var close_cart_sidebar = function close_cart_sidebar() {

      /*USE `ESC` KEY*/
      _body.addEventListener('keyup', function (e) {
        if (27 === e.keyCode) {
          _body.classList.remove('cart-sidebar-open');
        }
      });

      /*USE CLOSE BUTTON*/
      close_cart_sidebar_btn.addEventListener('click', function () {
        return _body.classList.remove('cart-sidebar-open');
      });

      /*USE OVERLAY*/
      _overlay.addEventListener('click', function () {
        _body.classList.remove('cart-sidebar-open');
      });
    };

    /*RE-INIT EASY ZOOM*/
    var reinit_easy_zoom = function reinit_easy_zoom() {

      if (window.matchMedia('( max-width: 991px )').matches) {
        return;
      }

      var product_image = jQuery(_body).find('.pro-img-item:eq(0)');

      if (!product_image.length || _body.classList.contains('quick-view-open')) return;

      var easy_zoom = product_image.easyZoom(),
          api = easy_zoom.data('easyZoom');

      api.teardown();
      api._init();
    };

    /*MINUS && PLUS BUTTON FOR QUANTITY INPUT*/
    var quantity = function quantity() {
      var _selector = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'form.woocommerce-cart-form, form.cart';

      var j_quick_view = jQuery(_selector),
          _qty = j_quick_view.find('.quantity');

      if (!_qty.length || jQuery(_qty).hasClass('hidden')) return;

      _qty.prepend('<span class=\'modify-qty\' data-click=\'minus\'></span>').append('<span class=\'modify-qty\' data-click=\'plus\'></span>');

      var _qty_btn = j_quick_view.find('.modify-qty');

      jQuery(_qty_btn).on('click', function () {
        var t = jQuery(this),
            _input = t.parent().find('input'),
            currVal = parseInt(_input.val(), 10),
            max = parseInt(_input.prop('max'));

        if ('minus' === t.attr('data-click') && currVal > 1) {
          _input.val(currVal - 1);
        }

        if ('plus' === t.attr('data-click')) {
          if (currVal >= max) return;
          _input.val(currVal + 1);
        }

        jQuery('[name=\'update_cart\']').prop('disabled', false);
      });
    };
    quantity();

    /*UPDATE SWATCH IMAGE WHEN VARIATION CLICK*/
    var product_variation = function product_variation() {
      var popup = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;

      var _gallery = jQuery(_body).find('.shop-content .single-product-gallery');

      if (true == popup) {
        _gallery = jQuery(_body).find('#shop-quick-view #quick-view-gallery');
      }

      /*PRODUCT IMAGE*/
      var _image = _gallery.find('.pro-img-item:eq(0)'),
          _image_src = _image.find('img').prop('src'),


      /*PRODUCT THEMBNAIL*/
      _thumb = _gallery.find('.pro-thumb:eq(0)'),
          _thumb_src = _thumb.find('img').prop('src'),


      /*EASY ZOOM ATTR*/
      _zoom = _image.data('zoom');

     // reinit_easy_zoom();

      /*event when variation changed=========*/
      jQuery(_body).on('found_variation', 'form.variations_form', function (event, variation) {
        /*get image url form `variation`*/
        var img_url = variation.image.full_src,
            thumb_url = variation.image.thumb_src;

        /*change `src` image*/
        _image.find('img').prop('src', img_url);
        _thumb.find('img').prop('src', thumb_url);
        _image.attr('data-zoom', img_url);

        _image.addClass('image-is-loading');
        _image.find('img').prop('src', img_url).one('load', function () {
          return _image.removeClass('image-is-loading');
        });

        //reinit_easy_zoom();
      });

      /*reset variation========*/
      jQuery('.reset_variations').on('click', function (e) {
        e.preventDefault();

        /*change `src` image*/
        _image.find('img').prop('src', _image_src);
        _thumb.prop('src', _thumb_src);
        _image.attr('data-zoom', _zoom);

//        reinit_easy_zoom();
      });
    };
    product_variation();

    /*AJAX SINGLE ADD TO CART*/
    var single_add_to_cart = function single_add_to_cart() {
      var popup = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;

      if (!_body.classList.contains('ajax-single-add-to-cart')) return;

      var _cart = null,
          _qv_popup = document.getElementById('shop-quick-view'),
          is_single_product = _body.classList.contains('single-product');

      if (!is_single_product) {
        if (!_body.classList.contains('quick-view-open')) return;
        _cart = _qv_popup.getElementsByClassName('cart')[0];
      } else {
        _cart = document.getElementsByClassName('cart')[0];
        if (true == popup) {
          _cart = _qv_popup.getElementsByClassName('cart')[0];
        }
      }

      if (!_cart) return;

      var _input = _cart.getElementsByClassName('qty')[0],
          _max = _input ? parseInt(_input.getAttribute('max')) : 0,
          _btn = _cart.getElementsByClassName('single_add_to_cart_button')[0],
          _in_cart = _cart.getElementsByClassName('in-cart-qty')[0],
          _in_stock = _in_cart.getAttribute('data-in_stock'),
          _out_of_stock = _in_cart.getAttribute('data-out_of_stock'),
          _not_enough = _in_cart.getAttribute('data-not_enough'),
          _valid_qty = _in_cart.getAttribute('data-valid_qty');

      if (!_btn || 'A' == _btn.tagName || _cart.classList.contains('grouped_form') || !_input) return;

      _btn.addEventListener('click', function (e) {
        e.preventDefault();

        var _cart_sidebar = document.getElementsByClassName('cart-sidebar-content')[0],
            _item_count = document.getElementsByClassName('shop-cart-count'),
            _in_cart_qty = parseInt(_in_cart.value),
            single_atc_id = 0,
            _qty = '',
            variation_id = null,
            variations = null,
            items = {},
            xhr = new XMLHttpRequest();

        xhr.open('post', zoa_ajax.url);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');

        if (_cart.classList.contains('variations_form')) {
          single_atc_id = _cart.querySelector('[name="product_id"]').value;
          variation_id = _cart.querySelector('[name="variation_id"]').value;
          _qty = parseInt(_input.value);

          var product_attr = _cart.querySelectorAll('select[name^="attribute"]');
          product_attr.forEach(function (x) {
            var attr_name = x.name,
                attr_value = x.value;

            items[attr_name] = attr_value;
          });
        } else {
          single_atc_id = _cart.querySelector('[name="add-to-cart"]').value;
          _qty = parseInt(_input.value);
        }

        /*ALERT IF NOT VALID QUANTITY*/
        if (_qty < 1 || isNaN(_qty)) {
          alert(_valid_qty);
          return;
        }

        /*CONDITION IF STOCK MANAGER ENABLE*/
        if ('yes' == _in_stock) {
          if (_in_cart_qty == _max) {
            alert(_out_of_stock);
            return;
          }

          if (+_qty + +_in_cart_qty > _max) {
            alert(_not_enough);
            return;
          }
        }

        /*UPDATE in_cart VALUE*/
        _in_cart.value = +_in_cart.value + +_input.value;

        /*OPEN && CLOSE CART SIDEBAR ACTION*/
        event_cart_sidebar_open();
        open_cart_sidebar();
        close_cart_sidebar();

        xhr.addEventListener('readystatechange', function () {
          if (4 === xhr.readyState) {
            var s_data = JSON.parse(xhr.responseText);

            if (200 === s_data.status) {
              /*UPDATE PRODUCT COUNT*/
              for (var c = 0; c < 2; c++) {
                _item_count[c].innerHTML = s_data.item;
              }
              /*APPEND CONTENT*/
              _cart_sidebar.innerHTML = s_data.content;
            }
          }
        });

        xhr.addEventListener('load', function () {
          event_cart_sidebar_close();
        });

        xhr.send('action=single_add_to_cart&nonce=' + zoa_ajax.nonce + '&product_id=' + single_atc_id + '&product_qty=' + _qty + '&variation_id=' + variation_id + '&variations=' + JSON.stringify(items));
      });
    };
    single_add_to_cart();

    /*QUICK VIEW*/
    var quick_view_ajax = function () {

      var qv_btn = document.getElementsByClassName('product-quick-view-btn'),
          qv_count_btn = qv_btn.length,
          qv_box = document.getElementById('shop-quick-view'),
          qv_content = qv_box.getElementsByClassName('quick-view-content')[0],
          qv_close_btn = qv_box.getElementsByClassName('quick-view-close-btn')[0];

      var _loop = function _loop(_i) {
        qv_btn[_i].addEventListener('click', function () {

          var qv_product_id = qv_btn[_i].getAttribute('data-pid'),
              qv_id = qv_box.getAttribute('data-view_id'),
              xhr = new XMLHttpRequest();

          if (qv_product_id === qv_id) {
            _body.classList.add('quick-view-open');
            return;
          }

          qv_content.innerHTML = '';

          _body.classList.add('quick-view-open', 'quick-viewing');

          var quick_view_close = function quick_view_close() {
            _body.classList.remove('quick-view-open');
          };

          _body.addEventListener('keyup', function (e) {
            if (27 === e.keyCode) {
              quick_view_close();
            }
          });

          qv_close_btn.addEventListener('click', function () {
            quick_view_close();
          });

          _overlay.addEventListener('click', function () {
            quick_view_close();
          });

          qv_box.setAttribute('data-view_id', qv_product_id);

          xhr.open('post', zoa_ajax.url);
          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
          xhr.send('action=quick_view&nonce=' + zoa_ajax.nonce + '&product_id=' + qv_product_id);

          xhr.addEventListener('readystatechange', function () {
            if (4 === xhr.readyState) {
				
              var _data = JSON.parse(xhr.responseText);

              if (200 === _data.status) {
                qv_content.innerHTML = _data.content;
              }
            }
          });

          xhr.addEventListener('load', function () {
            _body.classList.remove('quick-viewing');

            quantity('#shop-quick-view .cart');

            /*TINY SLIDER FOR QUICKVIEW*/
            var qv_slider = function () {
              var qv_gallery = document.getElementById('quick-view-gallery');

              if (!qv_gallery || !qv_gallery.classList.contains('quick-view-slider')) return;

              var qv_carousel = tns({
                loop: false,
                container: '#quick-view-gallery',
                items: 1,
                mouseDrag: true
              });

              /*RESET SLIDER*/
              jQuery(document.body).on('found_variation', 'form.variations_form', function (event, variation) {
                qv_carousel.goTo('first');
              });

              jQuery('.reset_variations').on('click', function () {
                qv_carousel.goTo('first');
              });
            }();

            /*WATCH FOR QUICKVIEW*/
            var qv_swatch = function () {
              var var_form = jQuery(_body).find('#shop-quick-view .variations_form');
              if (!var_form.length) return;

              if ((typeof wc_add_to_cart_variation_params === 'undefined' ? 'undefined' : _typeof(wc_add_to_cart_variation_params)) !== undefined) {
                var_form.wc_variation_form();
                var_form.find('.variations select').change();
              }

              if (_typeof(jQuery.fn.tawcvs_variation_swatches_form) !== undefined) {
                var_form.tawcvs_variation_swatches_form();
              }
            }();

            product_variation(true);

            single_add_to_cart(true);
          });

          xhr.addEventListener('error', function () {
            return alert('Sorry, something went wrong. Please refresh this page to try again!');
          });
        });
      };

      for (var _i = 0; _i < qv_count_btn; _i++) {
        _loop(_i);
      }
    }();

    /*UPDATE BODY CLASS WHEN CART SIDEBAR OPEN && CLOSE*/
    var event_cart_sidebar_open = function event_cart_sidebar_open() {
      _body.classList.add('updating-cart');
      _body.classList.remove('cart-updated');
    };
    var event_cart_sidebar_close = function event_cart_sidebar_close() {
      _body.classList.add('cart-updated');
      _body.classList.remove('updating-cart');
    };

    /*GLOBAL*/
    jQuery(_body).on('adding_to_cart', function () {
      open_cart_sidebar();
      event_cart_sidebar_open();
    }).on('added_to_cart', function () {
      close_cart_sidebar();
      event_cart_sidebar_close();
    }).on('click', '.add_to_wishlist', function () {
      /*ADDING TO WISHLIST*/
      this.classList.add('adding-to-wishlist');
    }).on('removed_from_cart', function () {
      /*RUN AFTER REMOVED PRODUCT FROM CART*/
      var run_after_removed_from_cart = function () {
        var _pid = '',
            _cart = document.getElementsByClassName('cart')[0],
            _btn = _cart ? _cart.getElementsByClassName('single_add_to_cart_button')[0] : false,
            _in_cart = _cart ? _cart.getElementsByClassName('in-cart-qty')[0] : false,
            _in_stock = _in_cart ? _in_cart.getAttribute('data-in_stock') : 'no',
            _qty = '',
            xhr = new XMLHttpRequest();

        if (!_cart || !_btn || 'no' == _in_stock) return;

        if (_cart.classList.contains('variations_form')) {
          _pid = _cart.querySelector('input[name="product_id"]').value;
        } else {
          _pid = _btn.value;
        }

        xhr.open('post', zoa_ajax.url);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        xhr.send('action=get_count_product_already_in_cart&product_id=' + _pid);
        xhr.addEventListener('readystatechange', function () {
          if (4 === xhr.readyState) {
            var i_data = JSON.parse(xhr.responseText);
            _in_cart.value = i_data.in_cart;
          }
        });
      }();
    }).on('updated_cart_totals', function () {
      quantity();
    });
  }();

  /**
     * Ajax search form
     */
  (function () {
    var $searchForm = jQuery('.js-search-form');
    var $searchButton = jQuery('.js-search-button');
    var $closeButton = jQuery('.js-close-search-form');
    var $searchField = jQuery('.js-search-field');
    var $loader = jQuery('.zoa-search-form__loader');

    $searchButton.on('click', function () {
      $searchForm.addClass('is-open');
      $searchField.focus();
    });

    $closeButton.on('click', function (e) {
      e.preventDefault();
      $searchForm.removeClass('is-open');
    });

    /* HIT `ESC` KEY TO CLOSE DIALOG SEARCH */
    document.body.addEventListener('keyup', function (e) {
      if (27 === e.keyCode) {
        $searchForm.removeClass('is-open');
      }
    });

    jQuery('#autocomplete').autocomplete({
      serviceUrl: global.url,
      deferRequestBy: 300,
      params: {
        action: 'zoa_ajax_search_handler',
        _ajax_nonce: global.nonce
      },
      appendTo: jQuery(".zoa-search-form__suggestions"),
      onSearchStart: function onSearchStart() {
        $loader.addClass('fa-spin is-active');
      },
      onSearchComplete: function onSearchComplete(query, suggestions) {
        $loader.removeClass('fa-spin is-active');
      },
      formatResult: function formatResult(suggestion, currentValue) {
        var result = '';

        if (suggestion.id === -1) {
          result = suggestion.value;
          return result;
        }

        if (suggestion.thumbnail) {
          result += '<div class="autocomplete-suggestion__thumbnail"><a href="' + suggestion.url + '">' + suggestion.thumbnail + '</a></div>';
        }

        result += '<div class="autocomplete-suggestion__content">';
        result += '<h3 class="autocomplete-suggestion__title"><a href="' + suggestion.url + '">' + suggestion.value + '</a></h3>';
        result += suggestion.price;

        if (suggestion.excerpt) {
          result += '<div class="autocomplete-suggestion__excerpt">' + suggestion.excerpt + '</div>';
        }

        result += '</div>';

        return result;
      }
    });
  })();

  /* LOAD
     ***************************************************/
  window.addEventListener('load', function () {

    /*! PRELOADER `done()`
        ------------------------------------------------->*/
    if (document.getElementsByClassName('is-page-loading')[0]) {
      var themeContainer = document.getElementById('theme-container');

      if (themeContainer) {
        themeContainer.classList.remove('is-loading');
        themeContainer.classList.add('is-ready');
      }
      NProgress.done();
    }
  });
});