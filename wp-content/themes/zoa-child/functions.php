<?php
define('PAGE_TERM_ID', 2734);
define('PAGE_PRIVACY_ID', 2732);
/**
 * Theme functions file
 */
/* * *Remove Admin Notification except super admin** */
add_action('admin_head', 'get_user_role');

function get_user_role() {
    global $current_user;
    $user_roles = $current_user->roles;
    $user_role = array_shift($user_roles);
    if ($user_role != "administrator") {
        add_action('init', create_function('$a', "remove_action( 'init', 'wp_version_check' );"), 2);
        add_filter('pre_option_update_core', create_function('$a', "return null;"));
    };
}

;
/**
  load custom font for admin

  function my_admin_styles_load() {
  wp_enqueue_style( 'noto-sans', 'https://fonts.googleapis.com/css?family=Noto+Sans+JP:300,400,500', array(), null, 'all' );
  }
  add_action( 'admin_enqueue_scripts', 'my_admin_styles_load' );
 * */

/**
  load custom css for woocommerce-customers-manager
 * */
function load_custom_wp_admin_style() {
    // $hook is string value given add_menu_page function.
    if (!class_exists('WCCM_CustomerDetails')) {
        return;
    }
    wp_register_script('admin_custom_js', get_stylesheet_directory_uri() . '/admin/js/admin-custom.js', array(), false, true);
    if (isset($_REQUEST['page']) && in_array($_REQUEST['page'], array('woocommerce-customers-manager', 'wccm-add-new-customer', 'wccm-discover-customer', 'wccm-bulk-email-customer', 'wccm-import-customers', 'wccm-export-customers', 'wccm-options-page', 'acf-options-email-templates-configurator'))) {
        wp_register_style('wcm_plugin_page_css', get_stylesheet_directory_uri() . '/admin/css/wcm-custom.css');
        wp_enqueue_style('wcm_plugin_page_css');
    }
    wp_enqueue_script('admin_custom_js');
}

add_action('admin_enqueue_scripts', 'load_custom_wp_admin_style');

//ユーザ権限の取得
function getUserLevel() {
    global $current_user;
    get_currentuserinfo();  //ユーザレベルを取得
    $userLevel = array_keys($current_user->caps);
    /*
      管理者：administrator
      編集者：editor
      投稿者：author
      寄稿者：contributor
      購読者：subscriber
     */
    return $userLevel[0];
}

/**
  language switcher
 * */
function language_selector_flags() {
    $userLevel = getUserLevel();
    if (function_exists('icl_object_id')) {
        $languages = icl_get_languages('skip_missing=0&orderby=code');
        if (!empty($languages) && $userLevel == "administrator" && is_user_logged_in()) {
            echo '<div class="lang_flag_switcher">';
            foreach ($languages as $l) {
                if (!$l['active'])
                    echo '<div class="lang_flag"><a href="' . $l['url'] . '">';
                if ($l['active'])
                    echo '<div class="lang_flag active">';
                echo '<img src="' . $l['country_flag_url'] . '" height="12" alt="' . $l['language_code'] . '" width="18" />';
                if ($l['active'])
                    echo '</div>';
                if (!$l['active'])
                    echo '</a></div>';
            }
            echo '</div>';
        }
    }
}

function hide_update_noticee_to_all_but_admin_users() {
    if (!is_super_admin()) {
        remove_all_actions('admin_notices');
    }
}

add_action('admin_head', 'hide_update_noticee_to_all_but_admin_users', 1);

/**
 * Enqueue parent theme styles first
 * Replaces previous method using @import
 * <http://codex.wordpress.org/Child_Themes>
 */
//remove_filter ('wp_mail', 'wpautop');
function elsey_change_cssjs_ver($src) {
    if (strpos($src, '?ver='))
        $src = remove_query_arg('ver', $src);
    $src = add_query_arg(array('ver' => '4.8'), $src);
    return $src;
}

add_filter('style_loader_src', 'elsey_change_cssjs_ver', 1000);
add_filter('script_loader_src', 'elsey_change_cssjs_ver', 1000);

add_action('wp_loaded', 'zoa_wp_loaded');

function zoa_wp_loaded() {
    if (!session_id()) {
        session_start();
    }
}

add_action('wp_enqueue_scripts', 'zoa_enqueue_parent_theme_style', 99);

function zoa_enqueue_parent_theme_style() {
    wp_enqueue_style('zoa-theme-style', get_template_directory_uri() . '/style.css');
    //wp_enqueue_style( 'zoa-child-style', get_stylesheet_directory_uri() . '/style.css', array('zoa-theme-style'));
    wp_enqueue_style('zoa-child-style', get_stylesheet_directory_uri() . '/style.css?201901101426', array('zoa-theme-style'));
    /* wp_enqueue_style( 'zoa-child-style',
      get_stylesheet_directory_uri() . '/style.css',
      array('zoa-theme-style'),
      date('YmdHis',filemtime( get_stylesheet_directory(). '/style.css'))
      ); */
    wp_enqueue_style('ec-style', get_stylesheet_directory_uri() . '/css/woo.css?201901171703', array('zoa-child-style'));
    wp_enqueue_style('loading-style', get_stylesheet_directory_uri() . '/css/loading.css', array('ec-style'));
}

//crop portfolio image
add_theme_support('post-thumbnails');
add_image_size('portfolio', 600, 600, true);

//add custom css for elementor
//change post number for porfolo archive
function change_posts_per_page($query) {
    if (is_admin() || !$query->is_main_query())
        return;
    if ($query->is_archive('portfolio')) { //カスタム投稿タイプを指定
        $query->set('posts_per_page', '-1'); //表示件数を指定
    }
}

add_action('pre_get_posts', 'change_posts_per_page');

//remove basic admi menu
function remove_admin_menus() {
    global $menu;
    unset($menu[75]); // ツール
}

add_action('admin_menu', 'remove_admin_menus');

//remove MW FORM MENU
function remove_menus() {
    if (!current_user_can('level_10')) {
        remove_menu_page('edit.php?post_type=mw-wp-form');
        remove_menu_page('edit.php?post_type=giftcard');
        remove_menu_page('edit.php?post_type=rl_gallery');
        remove_menu_page('edit.php?post_type=elementor_library');
        remove_menu_page('elementor');
        remove_menu_page('wcst-shipping-tracking');
        remove_menu_page('quadmenu_welcome');
        remove_menu_page('duplicator-pro');
        remove_menu_page('edit.php?post_type=birs_appointment');
        remove_menu_page('edit.php?post_type=birs_client');
        //remove_menu_page('');
    }
    remove_menu_page('edit.php?post_type=size_guide');
}

add_action('admin_menu', 'remove_menus', 9999999);

//remove status woo menu
add_action('admin_menu', 'remove_menu_pages', 999);

function remove_menu_pages() {
    //global $current_user;
    //$user_roles = $current_user->roles;
    //$user_role = array_shift($user_roles);
    if (!current_user_can('level_10')) {
        $remove_submenu = remove_submenu_page('woocommerce', 'wc-status');
    }
}

//remove customer manage option menu
add_action('admin_menu', 'remove_wccm_sub_menu_pages', 999);

function remove_wccm_sub_menu_pages() {
    if (!current_user_can('level_10')) {
        remove_submenu_page('woocommerce-customers-manager', 'wccm-options-page');
        remove_submenu_page('wcst-shipping-tracking', 'wcst-shipping-companies');
        remove_submenu_page('quadmenu_pro', 'manage_options');
        remove_submenu_page('woocommerce-customers-manager', 'acf-options-email-templates-configurator');
    }
}

// Rename WooCommerce to Shop
if (!current_user_can('level_10')) {


    add_action('admin_menu', 'rename_woocoomerce', 999);

    function rename_woocoomerce() {
        global $menu;

        // Pinpoint menu item
        $woo = rename_woocommerce('WooCommerce', $menu);

        // Validate
        if (!$woo)
            return;
        $menu[$woo][0] = __('Store Setting', 'zoa');
    }

    function rename_woocommerce($needle, $haystack) {
        foreach ($haystack as $key => $value) {
            $current_key = $key;
            if (
                    $needle === $value
                    OR (
                    is_array($value) && rename_woocommerce($needle, $value) !== false
                    )
            ) {
                return $current_key;
            }
        }
        return false;
    }

}//if (!current_user_can('level_10'))


/* Remove Add to cart option from woo variation swatch pro */
remove_action('wvs_pro_variation_show_archive_variation_after_cart_button', 'wvs_pro_archive_variation_template', 5);
/* function from parent inc/woocomerce/content-product.php */
//remove_action( 'woocommerce_before_shop_loop_item_title', 'zoa_wrap_product_image', 10 );
//add_action( 'woocommerce_before_shop_loop_item_title', 'child_zoa_wrap_product_image', 5 );

/**
 * Add your custom functions below
 */
/**
 * Enqueue scripts and styles.
 */
/* hide adminbar */
add_filter('show_admin_bar', '__return_false');
// Remove the product rating display on product loops
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);

//Add TypeKit Font Set
function chiyono_font_typekit() {
    echo '<script src="https://use.typekit.net/kcx8iwf.js"></script><script>try{Typekit.load({ async: false });}catch(e){}</script>';
}

add_action('wp_head', 'chiyono_font_typekit');

/**
 * Check to make sure the main script has been enqueued and then load the typekit
 * inline script.
 *
 * @todo Replace prefix with your theme or plugin prefix
 */
function prefix_typekit_inline() {
    if (wp_script_is('typekit', 'enqueued')) {
        echo '<script type="text/javascript">try{Typekit.load();}catch(e){}</script>';
    }
}

//add code after opening body tag
function custom_content_after_body_open_tag() {
    ?>
    <div id="as-root"></div><script>(function (e, t, n) {
            var r, i = e.getElementsByTagName(t)[0];
            if (e.getElementById(n))
                return;
            r = e.createElement(t);
            r.id = n;
            r.src = "//button.aftership.com/all.js";
            i.parentNode.insertBefore(r, i)
        })(document, "script", "aftership-jssdk")</script>
    <?php
}

add_action('after_body_open_tag', 'custom_content_after_body_open_tag');

add_action('wp_enqueue_scripts', 'load_theme_scripts', 100); //change woo-variation-swatches-pro add to cart js

function load_theme_scripts() {
    // add-to-cart-variation override
    if (woo_variation_swatches()->get_option('enable_single_variation_preview') || woo_variation_swatches()->get_option('disable_threshold')):
        wp_deregister_script('wc-add-to-cart-variation');
        wp_register_script('wc-add-to-cart-variation', get_stylesheet_directory_uri() . '/woocommerce/js/wvs-add-to-cart-variation.js', array(), '');
        wp_deregister_script('yith_wapo_frontend');
        wp_register_script('yith_wapo_frontend', get_stylesheet_directory_uri() . '/woocommerce/js/yith-wapo-frontend.js', array(), '');
    endif;
}

function custom_styles() {
    wp_enqueue_style('cal-style', get_stylesheet_directory_uri() . '/js/calendar/pignose.calendar.css', array(), '');
    wp_enqueue_style('font-style', get_stylesheet_directory_uri() . '/fonts/font.css', array(), '');
    wp_enqueue_style('gfont-style', 'https://fonts.googleapis.com/css?family=Lato:300,400|Noto+Sans+JP:300,400,500|Crimson+Text:400,400i', array(), '');
    wp_enqueue_style('oicon-style', get_stylesheet_directory_uri() . '/icons/icon-outline.css', array(), '');
    wp_enqueue_style('gicon-style', get_stylesheet_directory_uri() . '/icons/icon-glyph.css', array(), '');
    wp_enqueue_style('wicon-style', get_stylesheet_directory_uri() . '/icons/icon-woo.css', array(), '');
    wp_enqueue_style('bsicon-style', get_stylesheet_directory_uri() . '/icons/icon-bodyshape.css', array(), '');
    wp_enqueue_style('select-style', get_stylesheet_directory_uri() . '/js/selectbox/selectbox.min.css', array(), '');
    wp_enqueue_style('bootstrap-grid', get_stylesheet_directory_uri() . '/css/bootstrap-grid.css', array(), '');
    wp_enqueue_style('remoda_theme', get_stylesheet_directory_uri() . '/js/remodal/remodal-default-theme.css', array(), '');
    wp_enqueue_style('remodal', get_stylesheet_directory_uri() . '/js/remodal/remodal.css', array(), '');
    wp_enqueue_style('menu-style', get_stylesheet_directory_uri() . '/css/menu.css', array(), '');
    wp_enqueue_style('booked-calendar', get_stylesheet_directory_uri() . '/css/booked-calendar.css', array(), '');
}

add_action('wp_enqueue_scripts', 'custom_styles');

function add_scripts() {
    wp_register_style('snow-style', get_stylesheet_directory_uri() . '/css/snowfall.css', array(), '');
    wp_register_script('snow-js', get_stylesheet_directory_uri() . '/js/snowfall.js', array(), false, true);
    wp_register_style('woof-style', get_stylesheet_directory_uri() . '/css/woof.css?201901140848', array(), '');
    wp_register_style('giftcard-style', get_stylesheet_directory_uri() . '/css/giftcard.css', array(), '');
    wp_register_style('slick-style', get_stylesheet_directory_uri() . '/js/slick/slick.css', array(), '');
    wp_register_style('slicktheme-style', get_stylesheet_directory_uri() . '/js/slick/slick-theme.css', array(), '');
    wp_register_style('labelauty-style', get_stylesheet_directory_uri() . '/js/labelauty/jquery-labelauty.css', array(), '');
    wp_register_style('form-style', get_stylesheet_directory_uri() . '/css/form.css', array(), '');
    wp_register_style('contact-style', get_stylesheet_directory_uri() . '/css/contact.css', array(), '');
    wp_register_style('portani-style', get_stylesheet_directory_uri() . '/css/port-animation.css', array(), '');
    wp_register_style('portfolio-style', get_stylesheet_directory_uri() . '/css/portfolio.css', array(), '');
    wp_register_style('validation_engine_css', get_stylesheet_directory_uri() . '/js/validationEngine.jquery.css', array(), '');
    
    wp_register_script('moment-js', get_stylesheet_directory_uri() . '/js/calendar/moment.min.js', array(), false, true);
    //wp_register_script( 'calmain-js', get_stylesheet_directory_uri() . '/js/calendar/main.js');
    wp_register_script('formstep-js', get_stylesheet_directory_uri() . '/js/form-steps.js', array(), false, true);
    $translation_array = array(
        'step3_string' => __('Confirmation', 'zoa')
    );
    wp_localize_script('formstep-js', 'object_name', $translation_array);
    wp_register_script('less-js', 'http://cdnjs.cloudflare.com/ajax/libs/less.js/2.5.1/less.min.js', array(), false, true);
    wp_register_script('checkout-js', get_stylesheet_directory_uri() . '/js/checkout.js', array(), false, true);
    wp_register_script('labelauty-js', get_stylesheet_directory_uri() . '/js/labelauty/jquery-labelauty.js', array(), false, true);
    wp_register_script('reservation-js', get_stylesheet_directory_uri() . '/js/reservation.js', array(), false, true);
    wp_register_script('gp-js', get_stylesheet_directory_uri() . '/js/grid-parallax.js', array(), false, true);
    wp_register_script('rellax-js', get_stylesheet_directory_uri() . '/js/rellax.min.js', array(), false, true);
    wp_register_script('masonry-js', get_stylesheet_directory_uri() . '/js/masonry.pkgd.min.js', array(), false, true);
    wp_register_script('portfolio-js', get_stylesheet_directory_uri() . '/js/portfolio.js', array(), false, true);
    wp_register_script('home-js', get_stylesheet_directory_uri() . '/js/home.js', array(), false, true);
    $get_url = array('siteurl' => get_option('siteurl'));
    wp_localize_script('home-js', 'get_url', $get_url);
    wp_register_script('remodal', get_stylesheet_directory_uri() . '/js/remodal/remodal.js', array(), false, true);
    wp_register_script('slick-js', get_stylesheet_directory_uri() . '/js/slick/slick.js', array(), false, true);
    wp_register_script('shopsingle-js', get_stylesheet_directory_uri() . '/js/shopsingle.js?201901102020', array(), false, true); //single shop
    wp_register_script('popup-js', get_stylesheet_directory_uri() . '/js/popup.js', array(), false, true); //popup tooltip
    wp_register_script('woof-js', get_stylesheet_directory_uri() . '/js/woof.js', array(), false, true);
    wp_register_script('booked-js', get_stylesheet_directory_uri() . '/js/booked-custom.js', array(), false, true);
    wp_register_script('booked-steps', get_stylesheet_directory_uri() . '/js/booked-formsteps.js', array(), false, true);
    wp_register_style('tabs-style', get_stylesheet_directory_uri() . '/css/tabs.css', array(), '');
    wp_register_script('tabs-js', get_stylesheet_directory_uri() . '/js/tabs.js', array(), false, true);
    wp_register_script('ajax-con', get_stylesheet_directory_uri() . '/js/ajax-con.js', array(), false, true);
    wp_register_script('register', get_stylesheet_directory_uri() . '/js/register.js', array(), false, true);
	$translation_array = array(
		'name_label' => __('Name', 'zoa'),
		'kana_label' => __('Kana Name', 'zoa'),
		'dbirth_label' => __('Date of Birth', 'zoa'),
        'year_label' => __('Year', 'zoa'),
		'month_label' => __('Month', 'zoa'),
		'day_label' => __('Day', 'zoa')
    );
	wp_localize_script('register', 'translation', $translation_array);
	wp_register_script('register-js', get_stylesheet_directory_uri() . '/js/registration.js', array(), false, true);
	wp_localize_script('register-js', 'translation', $translation_array);
    wp_enqueue_script('remodal');

    if (is_home() || is_front_page()) {
        wp_enqueue_style('snow-style');
        wp_enqueue_script('gp-js'); //wp_enqueue_style(wp_register_scriptで登録したスタイルの名称)
        wp_enqueue_script('rellax-js');
        wp_enqueue_script('snow-js');
        wp_enqueue_script('home-js');
    } elseif (is_page('reservation')) {
        wp_enqueue_style('labelauty-style');
        wp_enqueue_style('form-style');
        wp_enqueue_script('moment-js');
        wp_enqueue_script('formstep-js');
        wp_enqueue_script('labelauty-js');
        wp_enqueue_script('reservation-js');
    } elseif (is_page('reservation-confirm')) {
        wp_enqueue_style('form-style');
    } elseif (is_page('reservation-form')) {
        wp_enqueue_style('labelauty-style');
        wp_enqueue_style('form-style');
        wp_enqueue_style('tabs-style');
        wp_enqueue_script('labelauty-js');
        wp_enqueue_script('booked-js');
        wp_enqueue_script('booked-steps');
        wp_enqueue_script('tabs-js');
        wp_enqueue_script('ajax-con');
        wp_enqueue_style('woof-style');
        wp_enqueue_script('woof-js');
        wp_enqueue_script('popup-js');
    } elseif (is_page('about')) {
        wp_enqueue_style('portani-style');
        wp_enqueue_style('portfolio-style');
        wp_enqueue_script('masonry-js');
        wp_enqueue_style('slick-style');
        wp_enqueue_style('slicktheme-style');
        wp_enqueue_script('slick-js');
        wp_enqueue_script('portfolio-js');
    } elseif (is_post_type_archive('portfolio')) {
        wp_enqueue_style('portani-style');
        wp_enqueue_style('portfolio-style');
        wp_enqueue_style('slick-style');
        wp_enqueue_style('slicktheme-style');
        wp_enqueue_script('masonry-js');
        wp_enqueue_script('slick-js');
        wp_enqueue_script('portfolio-js');
    } elseif (is_checkout()) {
        wp_enqueue_style('form-style');
        wp_enqueue_script('formstep-js');
        wp_enqueue_script('checkout-js');
    } elseif (is_shop() || is_product_category() || is_tax('series')) {
        wp_enqueue_style('woof-style');
        wp_enqueue_script('woof-js');
        wp_enqueue_script('popup-js');
    } elseif (is_product()) {
        wp_enqueue_style('slick-style');
        wp_enqueue_style('slicktheme-style');
        wp_enqueue_style('giftcard-style');
        wp_enqueue_script('slick-js');
        wp_enqueue_script('popup-js');
        wp_enqueue_script('shopsingle-js');
    } elseif (is_page('register')) {
    	wp_enqueue_script('register');
		wp_enqueue_script('register-js');
    	wp_enqueue_style('validation_engine_css');
		wp_enqueue_style('form-style');
    	
    }
    elseif (is_page('contact') || is_page('contact-confirm') || is_page('press-contact-confirm') || is_page('contact-thanks')) {
        wp_enqueue_style('contact-style');
    }/* elseif (is_singular('post') || is_post_type_archive('post')) {
      //wp_enqueue_script('blog');
      } /*elseif (is_singular('info') || is_post_type_archive('info') ) {
      //wp_enqueue_script('info');
      } */
}

add_action('wp_enqueue_scripts', 'add_scripts');

function zoa_dequeue_script() {
    wp_deregister_script('quadmenu');
    wp_dequeue_script('quadmenu');
}

add_action('wp_print_scripts', 'zoa_dequeue_script', 100);

function custom_scripts() {
    //wp_enqueue_script( 'zoa-custom', get_template_directory_uri() . '/js/custom.js');
    //wp_enqueue_script( 'combodate-js', get_stylesheet_directory_uri() . '/js/combodate.js');

    wp_enqueue_script('autokana-js', get_stylesheet_directory_uri() . '/js/jquery.autoKana.js');
    wp_enqueue_script('validation_engine_js', get_stylesheet_directory_uri() . '/js/jquery.validationEngine.js');
    wp_enqueue_script('validation_engine_ja_js', get_stylesheet_directory_uri() . '/js/jquery.validationEngine-ja.js');
    wp_enqueue_script('selectbox-js', get_stylesheet_directory_uri() . '/js/selectbox/selectbox.js');

    wp_enqueue_script('overlay', get_stylesheet_directory_uri() . '/js/loadingoverlay.js');

    wp_register_script('quadmenu_new', get_stylesheet_directory_uri() . '/js/quadmenu/quadmenu.js', array('hoverIntent'), false, true);
    wp_enqueue_script('quadmenu_new');

// 	wp_enqueue_script( 'custom-parent', get_stylesheet_directory_uri() . '/js/custom-parent.js', array(), null,true );
    wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/js/custom.js?201812140713', array(), false, true);
}

add_action('wp_enqueue_scripts', 'custom_scripts');

//validation script
function validation_scripts() {
    wp_register_script('parsley-js', get_stylesheet_directory_uri() . '/js/parsley.min.js', array(), false, true);
    wp_register_script('parsley-lang-en', get_stylesheet_directory_uri() . '/js/i18n/en.js', array(), false, true);
    wp_register_script('parsley-lang-ja', get_stylesheet_directory_uri() . '/js/i18n/ja.js?201812042322', array(), false, true);
    wp_register_script('parsley-script', get_stylesheet_directory_uri() . '/js/parsley-script.js?201812111441', array('parsley-js'), false, true);
    wp_register_style('validation-style', get_stylesheet_directory_uri() . '/css/validation.css?201812131628');
    if (is_page('contact') || is_checkout() || is_product() || is_page('register')) {
        wp_enqueue_script('parsley-js');
        if (get_locale() == 'ja') {
            wp_enqueue_script('parsley-lang-ja');
        } else {
            wp_enqueue_script('parsley-lang-en');
        }


        if (function_exists('validation_scripts')) {
            if (get_locale() == 'ja') {
                $tag = "window.Parsley.setLocale('ja');";
                wp_add_inline_script('parsley-lang-ja', $tag, 'after');
            } else {
                $tag = "window.Parsley.setLocale('en');";
                wp_add_inline_script('parsley-lang-en', $tag, 'after');
            }
        }
        wp_enqueue_script('parsley-script');
        wp_enqueue_style('validation-style');
    }
}

add_action('wp_enqueue_scripts', 'validation_scripts');

/* * **************
  Booked Form Step Button
 * *************** */

function booked_button() {
    echo '<div class="btn-group">
            <input type="button" class="btn btn--inverse btn--1 btn--prev js-prev" value="' . __('Previous', 'zoa') . '" /> 
            <input type="button" class="btn btn--next js-next" value="' . __('Next', 'zoa') . '" />
            <input type="submit" class="btn btn--2" value="' . __('Book an Appointment', 'booked') . '" />
         </div>';
}

add_action('booked_btn_hook', 'booked_button', 7);
/**
 * Change the strength requirement on the woocommerce password
 *
 * Strength Settings
 * 4 = Strong
 * 3 = Medium (default) 
 * 2 = Also Weak but a little stronger 
 * 1 = Password should be at least Weak
 * 0 = Very Weak / Anything
 */
add_filter('woocommerce_min_password_strength', 'misha_change_password_strength');

function misha_change_password_strength($strength) {
    return 2;
}

add_filter('woocommerce_get_script_data', 'misha_strength_meter_settings', 20, 2);

function misha_strength_meter_settings($params, $handle) {

    if ($handle === 'wc-password-strength-meter') {
        $params = array_merge($params, array(
            'min_password_strength' => 2,
            'i18n_password_error' => __('make it stronger', 'zoa'),
            'i18n_password_hint' => ''
        ));
    }
    return $params;
}

add_action('wp_enqueue_scripts', 'misha_password_messages', 9999);

function misha_password_messages() {

    wp_localize_script('wc-password-strength-meter', 'pwsL10n', array(
        'short' => __('Too short', 'zoa'),
        'bad' => __('Too bad', 'zoa'),
        'good' => __('Better but not enough', 'zoa'),
        'strong' => __('Better', 'zoa'),
        'mismatch' => __('Your passwords do not match, please re-enter them.', 'zoa')
    ));
}

//change added cart message
add_filter('wc_add_to_cart_message_html', 'custom_add_to_cart_message', 10, 2);

function custom_add_to_cart_message($message, $products) {
    $message = sprintf('<span class="added_msg">' . __('Products successfully added to cart!', 'zoa') . '</span><a href="%s" class="cta view_cart_link">' . __('View cart', 'zoa') . '</a>', wc_get_cart_url());

    return $message;
}

//add WPML lang class to body
if (function_exists('icl_object_id')) {
    add_filter('body_class', 'append_language_class');

    function append_language_class($classes) {

        $classes[] = ICL_LANGUAGE_CODE;  //or however you want to name your class based on the language code
        return $classes;
    }

}

//add slug class to body
add_filter('body_class', 'body_class_section');

function body_class_section($classes) {
    global $wpdb, $post;
    if (is_page()) {
        if ($post->post_parent) {
            $parent = end(get_post_ancestors($current_page_id));
        } else {
            $parent = $post->ID;
        }
        $post_data = get_post($parent, ARRAY_A);
        $classes[] = 'parent-' . $post_data['post_name'];
    }
    return $classes;
}

/* Woo Add On plugin replace addon.js file */
add_action('wp_enqueue_scripts', 'my_addon_script');

function my_addon_script() {
    wp_enqueue_script('woocommerce-addons', get_stylesheet_directory_uri() . '/js/addons.js', array('jquery', 'accounting'), '1.0', true);
}

add_action('init', 'zoa_init_session', 1);

function zoa_init_session() {
    if (!session_id()) {
        session_start();
    }
}

add_action('wp', 'wpse163434_init');

function wpse163434_init() {
    //remove_action( 'init', 'wvg_remove_default_template', 200 );
    $position = 22;

    // Avada Theme
    if (class_exists('Avada')) {
        $position = 50;
    }

    // Enfold Theme
    if (defined('AV_FRAMEWORK_VERSION')) {
        $position = 5;
    }
    remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', $position);
}

if (!function_exists('lab_setup')) :

    function lab_setup() {

        register_nav_menus(array(
            'footer_left' => 'Footer Left',
            'footer_right' => 'Footer Right',
        ));
    }

endif;
add_action('after_setup_theme', 'lab_setup');

//change add to cart text
add_filter('woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text');

function woo_custom_cart_button_text() {
    return __('Add to cart', '');
}

// Function to add text shortcode to posts and pages
//auto email text shortcode
function email_process_shortcode() {
    return '<p style="text-align: center;">' . __('Your order has been received and is now being processed.', 'zoa') . '<p style="text-align: center;">' . __("We'll drop another email when your order ships.", "zoa") . '</p><p style="text-align: center;">' . __('Your order details are shown below for your reference:', 'zoa') . '</p>';
}

add_shortcode('order-process-text', 'email_process_shortcode');

function email_onhold_shortcode() {
    return '<p style="text-align: center;">' . __('Your order has been received and is now on hold.', 'zoa') . '<p style="text-align: center;">' . __("We'll drop another email after you purchase for this order.", "zoa") . '</p><p style="text-align: center;">' . __('Your order details are shown below for your reference:', 'zoa') . '</p>';
}

add_shortcode('order-onhold-text', 'email_onhold_shortcode');

//user login shortcode
function check_user($params, $content = null) {
    if (!is_user_logged_in() && strpos($_SERVER['REQUEST_URI'], 'my-account') === false) {
        return $content;
    } else {
        return;
    }
}

add_shortcode('loggedin', 'check_user');

//gallery thumbnail size
add_filter('woocommerce_get_image_size_gallery_thumbnail', function( $size ) {
    return array(
        'width' => 94,
        'height' => 126,
            //'crop'   => 0,
    );
});

/**
 * Get site url for links 
 */
function home_url_shortcode() {
    return get_bloginfo('url');
}

add_shortcode('homeurl', 'home_url_shortcode');

//woo shop page link shortcode
function shoplink_shortcode($atts, $content = null) {
    return '<a href="' . get_permalink(wc_get_page_id('shop')) . '" class="link_underline upper view_all">' . $content . '</a>';
}

add_shortcode('shoplink', 'shoplink_shortcode');

//Appointment Section Shortcode
function appointment_template_shortcode() {
    ob_start();
    get_template_part('./template-parts/section-appointment');

    return ob_get_clean();
}

add_shortcode('section-appointment', 'appointment_template_shortcode');

//Contact Section Shortcode
function contact_template_shortcode() {
    ob_start();
    get_template_part('./template-parts/section-contact');

    return ob_get_clean();
}

add_shortcode('section-contact', 'contact_template_shortcode');

//remove dashboard menu in my account page
add_filter('woocommerce_account_menu_items', 'my_remove_my_account_links');

function my_remove_my_account_links($menu_links) {

    //unset( $menu_links['edit-address'] ); // Addresses


    unset($menu_links['dashboard']); // Dashboard
    //unset( $menu_links['payment-methods'] ); // Payment Methods
    //unset( $menu_links['orders'] ); // Orders
    unset($menu_links['downloads']); // Downloads
    //unset( $menu_links['edit-account'] ); // Account details
    //unset( $menu_links['customer-logout'] ); // Logout

    return $menu_links;
}

//add wishlist
class My_Custom_My_Account_Endpoint {

    /**
     * Custom endpoint name.
     *
     * @var string
     */
    public static $endpoint = 'my-wishlist';

    /**
     * Plugin actions.
     */
    public function __construct() {
        // Actions used to insert a new endpoint in the WordPress.
        add_action('init', array($this, 'add_endpoints'));
        add_filter('query_vars', array($this, 'add_query_vars'), 0);

        // Change the My Accout page title.
        add_filter('the_title', array($this, 'endpoint_title'));

        // Insering your new tab/page into the My Account page.
        add_filter('woocommerce_account_menu_items', array($this, 'new_menu_items'));
        add_action('woocommerce_account_' . self::$endpoint . '_endpoint', array($this, 'endpoint_content'));
    }

    /**
     * Register new endpoint to use inside My Account page.
     *
     * @see https://developer.wordpress.org/reference/functions/add_rewrite_endpoint/
     */
    public function add_endpoints() {
        add_rewrite_endpoint(self::$endpoint, EP_ROOT | EP_PAGES);
    }

    /**
     * Add new query var.
     *
     * @param array $vars
     * @return array
     */
    public function add_query_vars($vars) {
        $vars[] = self::$endpoint;

        return $vars;
    }

    /**
     * Set endpoint title.
     *
     * @param string $title
     * @return string
     */
    public function endpoint_title($title) {
        global $wp_query;

        $is_endpoint = isset($wp_query->query_vars[self::$endpoint]);

        if ($is_endpoint && !is_admin() && is_main_query() && in_the_loop() && is_account_page()) {
            // New page title.
            $title = __('My Wishlist', 'zoa');

            remove_filter('the_title', array($this, 'endpoint_title'));
        }

        return $title;
    }

    /**
     * Insert the new endpoint into the My Account menu.
     *
     * @param array $items
     * @return array
     */
    public function new_menu_items($items) {
        // Remove the logout menu item.
        $logout = $items['customer-logout'];
        unset($items['customer-logout']);

        // Insert your custom endpoint.
        $items[self::$endpoint] = __('My Wishlist', 'zoa');

        // Insert back the logout item.
        $items['customer-logout'] = $logout;

        return $items;
    }

    /**
     * Endpoint HTML content.
     */
    public function endpoint_content() {
        echo do_shortcode('[ti_wishlistsview]');
    }

    /**
     * Plugin install action.
     * Flush rewrite rules to make our custom endpoint available.
     */
    public static function install() {
        flush_rewrite_rules();
    }

}

new My_Custom_My_Account_Endpoint();

// Flush rewrite rules on plugin activation.
register_activation_hook(__FILE__, array('My_Custom_My_Account_Endpoint', 'install'));

//change title for edit address/billing and shipping
/* function wpb_woo_editaddr_endpoint_title( $title, $id ) {
  if ( is_wc_endpoint_url( 'shipping' ) && in_the_loop() && is_account_page() ) { // add your endpoint urls
  $title = "Edit Shipping Address"; // change your entry-title
  }
  elseif ( is_wc_endpoint_url( 'billing' ) && in_the_loop() && is_account_page() ) {
  $title = "Edit Billing Address";
  }
  return $title;
  }
  add_filter( 'the_title', 'wpb_woo_editaddr_endpoint_title', 10, 2 ); */

//change my account menu title
function wpb_woo_my_account_order() {
    $myorder = array(
        'edit-account' => __('My Account Info', 'zoa'),
        'orders' => __('My Orders', 'zoa'),
        'edit-address' => __('My Addresses', 'zoa'),
        'my-wishlist' => __('My Wishlist', 'zoa'),
        'appointment' => __('My Appointments', 'zoa'),
        //'payment-methods'    => __( 'Payment Methods', 'woocommerce' ),
        'customer-logout' => __('Logout', 'woocommerce'),
    );
    return $myorder;
}

add_filter('woocommerce_account_menu_items', 'wpb_woo_my_account_order');

// Remove CSS and/or JS for Select2 used by WooCommerce
add_action('wp_enqueue_scripts', 'wsis_dequeue_stylesandscripts_select2', 100);

function wsis_dequeue_stylesandscripts_select2() {
    if (class_exists('woocommerce')) {
        wp_dequeue_style('selectWoo');
        wp_deregister_style('selectWoo');

        wp_dequeue_script('selectWoo');
        wp_deregister_script('selectWoo');
    }
}

/* TOP BAR */

function shop_topbar_changes() {
    remove_action('woocommerce_before_shop_loop', 'zoa_result_count', 20);
    remove_action('woocommerce_before_shop_loop', 'zoa_catalog_ordering', 30);
}

add_action('init', 'shop_topbar_changes');


add_action('woocommerce_before_shop_loop', 'zoa_child_result_count', 30);

function zoa_child_result_count() {
    ?>
    <div class="shop-top-bar display--mid-up">
        <?php
        woocommerce_result_count();
        woocommerce_catalog_ordering();
        ?>
    </div>
    <?php
}

//change related product columns
/* SET COLUMN FOR RELATED || UPSELL PRODUCT */
add_filter('woocommerce_output_related_products_args', 'zoa_child_column_related', 9999);

function zoa_child_column_related($args) {
    $number = (int) get_theme_mod('related_product_item', 4);
    $column = (int) get_theme_mod('related_column', 4);

    $args['posts_per_page'] = $number;
    $args['columns'] = $column;
    return $args;
}

//change side mini cart
/* PRODUCT ACTION */
if (!function_exists('zoa_product_action')) :

    function zoa_product_action() {
        global $woocommerce;
        $total = $woocommerce->cart->cart_contents_count;
        ?>
        <div id="shop-quick-view" data-view_id='0'>
            <div class="shop-quick-view-container">
                <div class="quickview__dialog"><button class="quick-view-close-btn ion-ios-close-empty"></button>
                    <div class="quick-view-content"></div></div>
            </div>
        </div>

        <div id="shop-cart-sidebar">
            <div class="cart-sidebar-wrap">
                <div class="cart-sidebar-head">
                    <h4 class="cart-sidebar-title"><?php esc_html_e('Shopping cart', 'zoa'); ?></h4>
                    <span class="shop-cart-count"><?php echo esc_attr($total); ?></span>
                    <button id="close-cart-sidebar" class="ion-android-close"></button>
                </div>
                <div class="cart-sidebar-content">
                    <?php woocommerce_mini_cart(); ?>
                </div><!--/cart-sidebar-content-->
            </div><!--/cart-sidebar-wrap-->
        </div>

        <div id="shop-overlay"></div>
        <?php
    }

endif;

//change shop container
/* CONTENT WRAPPER */

function product_category_filter_changes() {
    remove_action('woocommerce_before_main_content', 'zoa_shop_open_tag', 5);
    remove_action('woocommerce_after_main_content', 'zoa_shop_close_tag', 5);
}

add_action('wp_head', 'product_category_filter_changes');


add_action('woocommerce_before_main_content', 'zoa_child_shop_open_tag', 1);

function zoa_child_shop_open_tag() {
    $shop_sidebar = !is_active_sidebar('shop-widget') ? 'full' : get_theme_mod('shop_sidebar', 'full');
    $shop_class = '';
    $shop_content_class = '';

    $shop_class .= is_product() ? 'pdp' : 'with-' . $shop_sidebar . '-sidebar';
    $shop_content_class .= is_product() ? 'shop-content' : 'product-grid-container col-12 col-lg-9';
    if (get_theme_mod('flexible_sidebar')) {
        $shop_class .= ' has-flexible-sidebar';
    }
    ?>
    <div class="row results-container max-width--site gutter-padding <?php echo esc_attr($shop_class); ?>">
        <?php
        if (!is_singular('product')) :
            do_action('woocommerce_sidebar');
        endif;
        ?>

        <?php
// 	if ( ! is_singular( 'product' ) ) :
// 	echo '<div class="refinement__sorts col-6">';
// 		woocommerce_catalog_ordering();
// 	echo '</div>';
// 	endif;
        ?>

        <div class="<?php echo esc_attr($shop_content_class); ?>">
            <?php
            if (get_theme_mod('flexible_sidebar') && 'full' !== $shop_sidebar && !is_product()) :
                ?>
                <div class="sidebar-overlay"></div>
                <a href="#" class="sidebar-toggle js-sidebar-toggle">
                    <span class="screen-reader-text"><?php esc_html_e('Toggle Shop Sidebar', 'zoa'); ?></span>
                    <i class="ion-android-options toggle-icon"></i>
                </a>
                <?php
            endif;
        }

        add_action('woocommerce_after_main_content', 'zoa_child_shop_close_tag', 60);

        function zoa_child_shop_close_tag() {
            ?>
        </div>


        <?php
    }

//change thank you page title
//remove attribute name from cart item title
    add_filter('woocommerce_product_variation_title_include_attributes', '__return_false');
//add view full details in quick view
    add_action('woocommerce_after_add_to_cart_button', 'quick_additional_button');

    function quick_additional_button() {
        global $post;
        if ($post->post_type == 'product' && $_REQUEST['action'] == 'quick_view') {
            echo '<div class="vf_row"><a class="cta cta--secondary" href="' . get_permalink($post->ID) . '">' . __('View Full Details', 'zoa') . '</a></div>';
        }
    }

    /* REMOVE EMPTY CART ACTION */

    function remove_cart_actions_parent_theme() {
        remove_action('woocommerce_cart_actions', 'zoa_clear_cart_url');
    }

    ;
    add_action('init', 'remove_cart_actions_parent_theme');

// change incl tax total array for format from class-wc-order.php
    add_filter('woocommerce_get_formatted_order_total', 'woo_rename_tax_inc_format', 10, 4);

    function woo_rename_tax_inc_format($formatted_total, $order_class, $tax_display, $display_refunded) {
        $formatted_total = wc_price($order_class->get_total(), array('currency' => $order_class->get_currency()));
        $order_total = $order_class->get_total();
        $total_refunded = $order_class->get_total_refunded();
        $tax_string = '';

        // Tax for inclusive prices.
        if (wc_tax_enabled() && 'incl' === $tax_display) {
            $tax_string_array = array();
            $tax_totals = $order_class->get_tax_totals();

            if ('itemized' === get_option('woocommerce_tax_total_display')) {
                foreach ($tax_totals as $code => $tax) {
                    $tax_amount = ( $total_refunded && $display_refunded ) ? wc_price(WC_Tax::round($tax->amount - $order_class->get_total_tax_refunded_by_rate_id($tax->rate_id)), array('currency' => $order_class->get_currency())) : $tax->formatted_amount;
                    $tax_string_array[] = sprintf('%s %s', $tax_amount, $tax->label);
                }
            } elseif (!empty($tax_totals)) {
                $tax_amount = ( $total_refunded && $display_refunded ) ? $order_class->get_total_tax() - $order_class->get_total_tax_refunded() : $order_class->get_total_tax();
                $tax_string_array[] = sprintf('%s %s', wc_price($tax_amount, array('currency' => $order_class->get_currency())), WC()->countries->tax_or_vat());
            }

            if (!empty($tax_string_array)) {
                /* translators: %s: taxes */
                $tax_string = ' <small class="includes_tax">' . sprintf(__('(incl. tax)', 'woocommerce')) . '</small>';
            }
        }

        if ($total_refunded && $display_refunded) {
            $formatted_total = '<del>' . strip_tags($formatted_total) . '</del> <ins>' . wc_price($order_total - $total_refunded, array('currency' => $order_class->get_currency())) . $tax_string . '</ins>';
        } else {
            $formatted_total .= $tax_string;
        }
        return $formatted_total;
    }

//Change Incl tax array
    add_filter('woocommerce_cart_totals_order_total_html', 'custom_cart_totals_order_total_html', 10, 1);

    function custom_cart_totals_order_total_html($value) {
        $value = WC()->cart->get_total();

        // If prices are tax inclusive, show taxes here.
        if (wc_tax_enabled() && WC()->cart->display_prices_including_tax()) {
            //$tax_string_array = array();
            $tax_string_array = array();
            $cart_tax_totals = WC()->cart->get_tax_totals();

            if (get_option('woocommerce_tax_total_display') === 'itemized') {
                foreach ($cart_tax_totals as $code => $tax) {
                    $tax_string_array[] = sprintf('%s %s', $tax->formatted_amount, $tax->label);
                }
            } elseif (!empty($cart_tax_totals)) {
                $tax_string_array[] = sprintf('%s %s', wc_price(WC()->cart->get_taxes_total(true, true)), WC()->countries->tax_or_vat());
            }

            /* if ( ! empty( $tax_string_array ) ) {
              $taxable_address = WC()->customer->get_taxable_address();

              $estimated_text = WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ? sprintf( ' ' . __( 'estimated for %s', 'woocommerce' ), WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] ) : '';

              $value .= '<small class="includes_tax test">' . sprintf( __( '(includes %s)', 'woocommerce' ), implode( ', ', $tax_string_array ) . $estimated_text ) . '</small>';
              } */
        }

        // Always return $value
        return $value;
    }

//override function wc_cart_totals_shipping_method_label
    add_filter('woocommerce_cart_shipping_method_full_label', 'custom_cart_totals_shipping_method_label', 10, 2);

//add_filter( 'woocommerce_shipping_package_name', 'custom_cart_totals_shipping_method_label', 10, 2 );
    function custom_cart_totals_shipping_method_label($label, $method) {
        $label = ': ' . $method->get_label();

        if ($method->cost >= 0 && $method->get_method_id() !== 'free_shipping') {
            if (WC()->cart->display_prices_including_tax()) {
                $label .= '</span><span class="value price-amount price-shipping">' . wc_price($method->cost + $method->get_shipping_tax()) . '</span>';
                if ($method->get_shipping_tax() > 0 && !wc_prices_include_tax()) {
                    $label .= ' <small class="tax_label">' . WC()->countries->inc_tax_or_vat() . '</small>';
                }
            } else {
                $label .= '</span>' . wc_price($method->cost) . '';
                if ($method->get_shipping_tax() > 0 && wc_prices_include_tax()) {
                    $label .= ' <small class="tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
                }
            }
        }

        return $label;
    }

//change dashboard content in my account
    add_action('woocommerce_account_dashboard', 'custom_woocommerce_account_dashboard');

    function custom_woocommerce_account_dashboard() {

        // of course you can print dynamic content here, one of the most useful functions here is get_current_user_id()
        echo '<div class="col-img-dash"><img src="' . get_stylesheet_directory_uri() . '/images/my-account.jpg" alt="' . esc_attr(get_bloginfo('name', 'display')) . '"></div>';
    }

// 2. Save field on Customer Created action
    add_action('woocommerce_created_customer', 'save_birthday_register_select_field');

    function save_birthday_register_select_field($customer_id) {
        if (isset($_POST['account_birth'])) {
            update_user_meta($customer_id, 'account_birth', $_POST['account_birth']);
        }
    }

// 3. Display Select Field @ User Profile (admin) and My Account Edit page (front end)
    add_action('show_user_profile', 'add_birthday_to_edit_account_form', 30);
    add_action('edit_user_profile', 'add_birthday_to_edit_account_form', 30);
    add_action('woocommerce_edit_account_form_below_email', 'add_birthday_to_edit_account_form', 10);

    function add_birthday_to_edit_account_form() {
        /* if (empty ($user) ) {
          $user_id = get_current_user_id();
          $user = get_userdata( $user_id );
          } */
        $user_id = $_REQUEST['user_id'] ? $_REQUEST['user_id'] : get_current_user_id();
        $user = get_userdata($user_id);
        $months = array(__('January'), __('February'), __('March'), __('April'), __('May'), __('June'), __('July'), __('August'), __('September'), __('October'), __('November'), __('December'));
        $default = array('day' => '', 'month' => '', 'year' => '',);
        $birth_date = wp_parse_args(get_the_author_meta('account_birth', $user->ID), $default);
        ?>

        <div class="form-row">
            <div class="field-wrapper">
                <label class="form-row__label" for="account_birth_month"><?php _e('Date of Birth', 'zoa'); ?></label>
                <div class="row row-dayofbirth">
                    <div class="col-4">
                        <div class="form-row">
                            <div class="field-wrapper">
                                <div class="selectric-wrapper selectric-input-select selectric-responsive">

                                    <select class="input-select justselect" name="account_birth[year]" id="account_birth_year">
                                        <option value=""><?php _e('Year', 'zoa-child'); ?></option>
                                        <?php
                                        for ($i = 1950; $i <= 2015; $i++) {
                                            printf('<option value="%1$s" %2$s>%1$s</option>', $i, selected($birth_date['year'], $i, false));
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    // Second Field
                    ?>
                    <div class="col-4">
                        <div class="selectric-wrapper selectric-input-select selectric-responsive">

                            <select class="input-select justselect" name="account_birth[month]" id="account_birth_month">
                                <option value=""><?php _e('Month'); ?></option>
                                <?php
                                foreach ($months as $month) {
                                    printf('<option value="%1$s" %2$s>%1$s</option>', $month, selected($birth_date['month'], $month, false));
                                }
                                ?>
                            </select>

                        </div>
                    </div>
                    <?php
                    // Third Field
                    ?>
                    <div class="col-4">
                        <div class="selectric-wrapper selectric-input-select selectric-responsive">

                            <select class="input-select justselect" name="account_birth[day]" id="account_birth_day">
                                <option value=""><?php _e('Day', 'zoa-child'); ?></option>
                                <?php
                                for ($i = 1; $i <= 31; $i++) {
                                    printf('<option value="%1$s" %2$s>%1$s</option>', $i, selected($birth_date['day'], $i, false));
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

// 4. Save User Field When Changed From the Admin/Front End Forms
    add_action('personal_options_update', 'save_birthday_account_details');
    add_action('edit_user_profile_update', 'save_birthday_account_details');
    add_action('woocommerce_save_account_details', 'save_birthday_account_details');

    function save_birthday_account_details($customer_id) {

        if (isset($_POST['account_birth']))
            update_user_meta($customer_id, 'account_birth', $_POST['account_birth']);

        // For Billing email (added related to your comment)
        if (isset($_POST['account_email']))
            update_user_meta($customer_id, 'billing_email', sanitize_text_field($_POST['account_email']));


        $field_group_fields = acf_get_fields(BOOKING_FORM_ID);

        $save_fields = array();
        foreach ($field_group_fields as $field) {
            loop_to_get_sub_field($field, $save_fields);
        }

        foreach ($save_fields as $save_field) {
            if ($save_field['parent_name'] == 'q_04') {
                $post_acf = $_POST['acf'];
                if (isset($post_acf[$save_field['key']]) && $post_acf[$save_field['key']]) {
                    update_user_meta($customer_id, $save_field['name'], $post_acf[$save_field['key']]);
                }
            }
        }
    }

//remove city field from shipping calculator
    add_filter('woocommerce_shipping_calculator_enable_city', '__return_false');
//remove postcode field from shipping calculator
    add_filter('woocommerce_shipping_calculator_enable_postcode', '__return_false');

//move tabs under description in single product page
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
    add_action('woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 60);

//add subtitle for product
    function fp_wc_display_subtitle_in_single_product() {

        echo '<h1 class="product_title entry-title">' . get_the_title() . '</h1>';

        if (function_exists('the_subtitle')):
            if (get_the_subtitle($post_id) != ''):
                echo '<h2 class="subtitle">' . get_the_subtitle() . '</h2>';
            endif;
        endif;
    }

    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
    add_action('woocommerce_single_product_summary', 'fp_wc_display_subtitle_in_single_product', 5);

    function fp_wc_display_subtitle_in_shop_page() {
        if (function_exists('the_subtitle')):
            if (get_the_subtitle($post_id) != ''):
                echo '<h4 class="subtitle">' . get_the_subtitle() . '</h4>';
            endif;
        endif;
    }

    add_action('woocommerce_after_shop_loop_item_title', 'fp_wc_display_subtitle_in_shop_page', 1);

//remove content editor for product edit page
    function remove_product_editor() {
        remove_post_type_support('product', 'editor');
    }

    add_action('init', 'remove_product_editor');


//remove tabs
    add_filter('woocommerce_product_tabs', 'woo_remove_product_tabs', 98);

    function woo_remove_product_tabs($tabs) {

        unset($tabs['description']); // Remove the description tab
        unset($tabs['reviews']); // Remove the reviews tab
        unset($tabs['additional_information']); // Remove the additional information tab

        return $tabs;
    }

//remove meta
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);


    if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

        //wrap div for title and price in product content
        function output_opening_item_wrap() {
            echo '<div class="c-product-item_wrap_info">';
        }

        function ouput_closing_item_wrap() {
            echo '</div><!-- /.c-product-item_wrap_info -->';
        }

        add_action('woocommerce_shop_loop_item_title', 'output_opening_item_wrap', 10);

        //add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

        add_action('woocommerce_after_shop_loop_item', 'ouput_closing_item_wrap', 50);

        /* CHANGE PERMALINK TO LOOP PRODUCT TITLE */

        function child_custom_actions() {
            //remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
            # remove_action( 'woocommerce_shop_loop_item_title', 'zoa_template_loop_product_title', 10 );
        }

        add_action('init', 'child_custom_actions');

        //wrap div for title and price in single product
        function output_opening_div() {
            echo '<div class="prod-info">';
        }

        function ouput_closing_div() {
            echo '</div><!-- /.prod-info -->';
        }

        function ws_opening_div() {
            echo '<div class="ws-row">';
        }

        function ws_closing_div() {
            echo '</div><!-- /.ws-row -->';
        }

        add_action('woocommerce_single_product_summary', 'output_opening_div', 1);

        add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);

        add_action('woocommerce_single_product_summary', 'ouput_closing_div', 10);

        add_filter('the_title', 'zoa_change_product_title_default', 1, 2);

        function zoa_change_product_title_default($title, $id = 0) {
            if (is_cart()) {
                return $title;
            }
            return zoa_change_product_title($title, $id);
        }

        function zoa_change_product_title($title, $id = 0) {
            if (!$id)
                return $title;

            $product = get_post($id);
            if (($product->post_type == 'product') && (!is_admin() || (defined('DOING_AJAX') && DOING_AJAX))) {
                $serie_cat = get_the_terms($product, 'series');
                if (!is_wp_error($serie_cat) && !empty($serie_cat)) {
                    $title = '<div class="mini-product__item mini-product__series heading heading--small">' . $serie_cat[0]->name . '</div>
					<span class="product_title">' . $title . '</span>';
                }
            }
            return $title;
        }

        add_filter('woocommerce_cart_item_name', 'zoa_change_cart_product_title', 1000, 4);

        function zoa_change_cart_product_title($title, $cart_item = '', $cart_item_key = '') {
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
            $product_id = $_product->get_id();
            if ($_product->post_type == 'product_variation') {
                $product_id = $_product->parent_id;
            }
            $title = zoa_change_product_title($title, $product_id);
            return $title;
        }

        add_filter('woocommerce_order_item_name', 'zoa_change_order_product_title', 1000, 4);

        function zoa_change_order_product_title($title, $item = array(), $order = array()) {
            if ((is_admin() && !(defined('DOING_AJAX') && DOING_AJAX))) {
                return $title;
            }
            $_product = $item->get_product();
            $is_visible = $product && $product->is_visible();
            $product_permalink = apply_filters('woocommerce_order_item_permalink', $is_visible ? $product->get_permalink($item) : '', $item, $order);
            $title = $product_permalink ? sprintf('<a href="%s" class="link">%s</a>', $product_permalink, $title) : $title;
            $product_id = $_product->get_id();
            if ($_product->post_type == 'product_variation') {
                $product_id = $_product->parent_id;
            }
            $title = zoa_change_product_title($title, $product_id);
            return $title;
        }

        //change drophint action
        function dropahint_content_after_addtocart_button_child() {
            global $loop;
            if ($op = get_option('dropahint_widget')) {
                $image = wp_get_attachment_image_src(get_post_thumbnail_id($loop->post->ID), 'single-post-thumbnail');
                ?>
                <script src="<?php echo $op ?>" async></script>
                <span class="drophint-link" data-product-image="<?= $image[0] ?>"></span>
                <?php
            }
        }

        //remove drophint action
        function drophint_remove_actions() {
            remove_action('woocommerce_after_add_to_cart_button', 'dropahint_content_after_addtocart_button');
            //remove_action( 'admin_menu', 'dropahint_custom_menu_page' );
        }

        add_action('init', 'drophint_remove_actions');


        //remove shortdescription
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);

        //add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 40 );
        //add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 45 );
        add_action('woocommerce_after_add_to_cart_button', 'ws_opening_div', 20);
        add_action('woocommerce_after_add_to_cart_button', 'show_ti_addwish_button', 30);
        add_action('woocommerce_after_add_to_cart_button', 'dropahint_content_after_addtocart_button_child', 40);
        add_action('woocommerce_after_add_to_cart_button', 'new_zoa_product_sharing', 50);

        function show_ti_addwish_button() {
            global $product;
            $id = $product->get_id();
            $variation_id = get_product($product->variation_id);
            echo '<div class="add-to-wishlist-button">' . do_shortcode('[ti_wishlists_addtowishlist product_id="' . $id . '" variation_id="' . $variation_id . '"]') . '</div>';
        }

        function new_zoa_product_sharing() {
            global $product;
            $id = $product->get_id();
            $url = get_permalink($id);
            $title = get_the_title($id);
            $img_id = $product->get_image_id();
            $img = wp_get_attachment_image_src($img_id, 'full');
            $tags = get_the_terms($id, 'product_tag');
            $tag_list = '';

            if ($tags && !is_wp_error($tags)) {
                $tag_list = implode(', ', wp_list_pluck($tags, 'name'));
            }
            ?>

            <div class="theme-social-icon p-shared">
                <span class="sharing-tools">
                    <span class="sharing-tools__layer-1"><span class="link-icon -no-anim -share cta"><?php esc_html_e('Share', 'zoa'); ?></span></span>
                    <span class="sharing-tools__layer-2">
                        <a
                            href="<?php echo esc_url_raw('//facebook.com/sharer.php?u=' . urlencode($url)); ?>"
                            title="<?php echo esc_attr($title); ?>"
                            target="_blank"
                            >
                        </a>
                        <a
                            href="<?php echo esc_url_raw('//twitter.com/intent/tweet?url=' . urlencode($url) . '&text=' . urlencode($title) . '&hashtags=' . urlencode($tag_list)); ?>"
                            title="<?php echo esc_attr($title); ?>"
                            target="_blank"
                            >
                        </a>
                        <a
                            href="<?php echo esc_url_raw('//pinterest.com/pin/create/button/?url=' . urlencode($url) . '&image_url=' . urlencode($img[0]) . '&description=' . urlencode($title)); ?>"
                            title="<?php echo esc_attr($title); ?>"
                            target="_blank"
                            >
                        </a></span><!--/.sharing-tools-->
                </span>
            </div>

            <?php
        }

        add_action('woocommerce_after_add_to_cart_form', 'ws_closing_div', 51);

        //add accordion tabs
        function output_accordion_tabs() {

            get_template_part('./woocommerce/single-product/accordions');
        }

        add_action('woocommerce_single_product_summary', 'output_accordion_tabs', 50);
    }// end if woocommerce
//Add Delivery Date field in Shipping tab in admin

    add_action('woocommerce_product_options_shipping', 'woo_deliver_date_field');

    function woo_deliver_date_field() {

        $field = array(
            'id' => 'deliver_date',
            'label' => __('Delivery Date', 'woocommerce'),
            'desc_tip' => true,
            'description' => __('Estimated delivery date', 'woocommerce'),
            'data_type' => ''
        );

        woocommerce_wp_text_input($field);
    }

    add_action('woocommerce_process_product_meta', 'save_deliver_date_field');

    function save_deliver_date_field($post_id) {

        $custom_field_value = isset($_POST['deliver_date']) ? $_POST['deliver_date'] : '';

        $product = wc_get_product($post_id);
        $product->update_meta_data('deliver_date', $custom_field_value);
        $product->save();
    }

//remove uncategorized category fomr sidebar
    add_filter('woocommerce_product_categories_widget_args', 'custom_woocommerce_product_subcategories_args');

    function custom_woocommerce_product_subcategories_args($args) {

        $args['exclude'] = get_option('default_product_cat');

        return $args;
    }

//override elementor widget by child theme
    /* ! override PARENT THEME WIDGETS
      -------------------------------------------------> */
    function zoa_widgetsv2() {

        $widgets = glob(get_stylesheet_directory() . '/elementor/widgets/*.php');

        foreach ($widgets as $key) {
            if (file_exists($key)) {
                require_once $key;
            }
        }
    }

    function elementor_ovverides() {
        remove_all_actions('elementor/widgets/widgets_registered');
        add_action('elementor/widgets/widgets_registered', 'zoa_widgetsv2');
    }

    add_action('template_redirect', 'elementor_ovverides');

//exclude press post from blog page
    function exclude_category_home($query) {
        if ($query->is_home) {
            $query->set('cat', '-134');
        }
        return $query;
    }

    add_filter('pre_get_posts', 'exclude_category_home');

    /* ! FOOTER
      -------------------------------------------------> */
    if (!function_exists('zoa_footer')):

        function zoa_footer() {
            $show_footer = zoa_footer_display();
            if (false == $show_footer)
                return;

            $column = get_theme_mod('ft_column', 4);
            $copyright = !empty(get_theme_mod('ft_copyright', '')) ? get_theme_mod('ft_copyright', '') : '&copy; ' . date('Y') . ' <strong>Zoa.</strong> &nbsp; • &nbsp; Privacy Policy &nbsp; • &nbsp; Terms of Use';
            $right_bot_right = get_theme_mod('ft_bot_right', '');

            /* WIDGET */
            if (is_active_sidebar('footer-widget')):
                ?>
                <div class="footer-top">
                    <div class="container">
                        <div class="c-footer_logo">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="c-footer_logo_link"><span class="svg-wrapper"><svg class="svg" width="320" height="116" viewBox="0 0 320 116"><use href="#svg-logo" xlink:href="#svg-logo"/></svg></span></a>
                        </div>
                        <div class="row widget-box footer-col-<?php echo esc_attr($column); ?>">
                            <?php dynamic_sidebar('footer-widget'); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php /* BASE */ ?>
            <div class="footer-bot">
                <div class="container">
                    <div class="footer-logo"></div>
                    <div class="footer-copyright"><?php echo wp_kses_post($copyright); ?></div>
                    <div class="footer-bot-right"><?php echo wp_kses_post($right_bot_right); ?></div>
                </div>
            </div>
            <?php
        }

    endif;
    /**
     * Return the default wishlist url
     *
     * @return string
     */
    /* function my_wishlist_url() {
      if ( ! function_exists( 'tinv_url_wishlist_default' ) ) {


      function tinv_url_wishlist_default() {
      $page = apply_filters( 'wpml_object_id', tinv_get_option( 'page', 'wishlist' ), 'page', true ); // @codingStandardsIgnoreLine WordPress.Variables.GlobalVariables.OverrideProhibited
      if ( empty( $page ) ) {
      return '';
      }
      $link = get_permalink( $page );

      return $link;
      }
      }
      }
      add_action( 'after_setup_theme', 'my_wishlist_url' ); */
    /* ICON HEADER MENU */
    if (!function_exists('zoa_wc_header_action')) :

        function zoa_wc_header_action() {
            global $woocommerce;
            $page_account = get_option('woocommerce_myaccount_page_id');
            $page_logout = wp_logout_url(get_permalink($page_account));

            if ('yes' == get_option('woocommerce_force_ssl_checkout')) {
                $logout_url = str_replace('http:', 'https:', $logout_url);
            }

            $count = $woocommerce->cart->cart_contents_count;
            //$wishlist_url   = get_permalink(get_option('yith_wcwl_wishlist_page_id'));
            $wishlist_url = tinv_url_wishlist_default();
            ?>
            <div class="menu-woo-action">
                <a href="<?php echo get_permalink($page_account); ?>" class="menu-woo-user<?php if (!is_user_logged_in()) : ?> signup_icon<?php else : ?> account_icon<?php endif; ?>">
                    <?php if (!is_user_logged_in()) : ?>
                        <?php esc_html_e('Sign up / Login', 'zoa'); ?>
                    <?php else : ?>
                        <?php esc_html_e('My Account', 'zoa'); ?>
                    <?php endif; ?>
                </a>
                <!--<a href="<?php //echo esc_url( $page_logout );               ?>"><?php //esc_html_e( 'Logout', 'zoa' );               ?></a>-->
            </div>
            <a href="<?php echo wc_get_cart_url(); ?>" id="shopping-cart-btn" class="oecicon oecicon-bag-20 menu-woo-cart js-cart-button"><span
                    class="shop-cart-count"><?php echo esc_html($count); ?></span></a>
            <?php if (function_exists('activation_tinv_wishlist')) { ?><a href="<?php echo esc_url($wishlist_url); ?>" class="oecicon oecicon-heart-2-3 menu-woo-favorite"></a><?php } ?>
            <?php
        }

    endif;

    if (!function_exists('zoa_wc_header_action_mobile')) :

        function zoa_wc_header_action_mobile() {
            global $woocommerce;
            $page_account = get_option('woocommerce_myaccount_page_id');
            $page_logout = wp_logout_url(get_permalink($page_account));

            if ('yes' == get_option('woocommerce_force_ssl_checkout')) {
                $logout_url = str_replace('http:', 'https:', $logout_url);
            }

            $count = $woocommerce->cart->cart_contents_count;
            //$wishlist_url   = get_permalink(get_option('yith_wcwl_wishlist_page_id'));
            $wishlist_url = tinv_url_wishlist_default();
            ?>
            <a href="<?php echo get_permalink($page_account); ?>" id="headerAccountLink" class="header__user__item header__user__link header__user__link--account">
                <?php if (!is_user_logged_in()) : ?>
                    <?php esc_html_e('Sign in / Register', 'zoa'); ?>
                <?php else : ?>
                    <?php esc_html_e('my account', 'zoa'); ?>
                <?php endif; ?>
            </a>
            <?php if (function_exists('activation_tinv_wishlist')) { ?>
                <a href="<?php echo esc_url($wishlist_url); ?>" id="headerWishlistLink" class="header__user__item header__user__link header__user__link--wishlist"><?php esc_html_e('My Wishlist', 'zoa'); ?></a><?php } ?>
            <?php
        }

    endif;

    /* Shortcode for custom post */
    add_shortcode('custom_posts', 'tcb_sc_custom_posts');

    function tcb_sc_custom_posts($atts) {
        global $post;
        $default = array(
            'type' => 'post',
            'post_type' => '',
            'limit' => 10,
            'status' => 'publish'
        );
        $r = shortcode_atts($default, $atts);
        extract($r);

        if (empty($post_type))
            $post_type = $type;

        $post_type_ob = get_post_type_object($post_type);
        if (!$post_type_ob)
            return '<div class="warning"><p>No such post type <em>' . $post_type . '</em> found.</p></div>';

        //$return = '<h3>' . $post_type_ob->name . '</h3>';

        $args = array(
            'post_type' => $post_type,
            'numberposts' => $limit,
            'post_status' => $status,
            'orderby' => 'rand'
        );

        $posts = get_posts($args);
        if (count($posts)):
            if ('portfolio' == $post_type) {
                $return .= '<div class="portfolio-grids grid row">';
                foreach ($posts as $post): setup_postdata($post);
                    $images_series = get_field('images_series', $post->ID);
                    $has_image_series = !empty($images_series) && !empty($images_series[0]['images']);
                    $return .= '<div class="grid-item all-port col-lg-3 col-md-4 col-xs-6"><div class="grid-outer"><a href="' . get_permalink($post->ID) . '" class="pf_link" data-id="' . $post->ID . '" ' . ($has_image_series ? 'data-serie_index="1"' : '') . '>';
                    $return .= '<div class="grid-content"><div class="grid-inner">';
                    $return .= '<div class="pf_item">';
                    //$return .= '<img src="'.get_stylesheet_directory_uri().'/images/pf_sample_thum.jpg" alt="sample" />';
                    if ($has_image_series) :
                        $return .= '<img src="' . $images_series[0]['images'][0]['sizes']['portfolio'] . '" alt="' . get_the_title() . '" />';
                    elseif (has_post_thumbnail()) :
                        $return .= get_the_post_thumbnail($post->ID, 'portfolio');
                    else :
                        $return .= '<img src="' . get_stylesheet_directory_uri() . '/images/pf_sample_thum.jpg" alt="sample" />';
                    endif;
                    $return .= '</div>';
                    $return .= '<div class="pf_caption"><h2 class="pf_title">' . get_the_title() . '</h2><p class="see_more"><span>See details</span></p></div>';
                    $return .= '</div></div>';
                    $return .= '</a></div></div>';
                endforeach;
                wp_reset_postdata();
                $return .= '</div>';
            } else {
                $return .= '<ul class="grid_post">';
                foreach ($posts as $post): setup_postdata($post);
                    $return .= '<li><a href="' . get_permalink($post->ID) . '">' . get_the_title() . '</a></li>';
                endforeach;
                wp_reset_postdata();
                $return .= '</ul>';
            }

        else :
            $return .= '<p>No posts found.</p>';
        endif;

        return $return;
    }

    /* Remove Default Theme Swatch  if Woo Variation Swatches Pro is activated */

    function remove_zoa_swatch_html_filter() {
        if (class_exists('Woo_Variation_Swatches_Pro')):
            // remove the filter
            remove_filter('woocommerce_loop_add_to_cart_link', 'zoa_loop_add_to_cart');
        endif;
    }

    add_filter('init', 'remove_zoa_swatch_html_filter', 20);

// Remove Woo Variation Swatches Pro　Add to cart link in Archive
    /* function remove_wvs_pro_filter() {
      if ( class_exists( 'Woo_Variation_Swatches_Pro' ) ):
      // remove the filter
      remove_filter('woocommerce_loop_add_to_cart_args', 'wvs_pro_loop_add_to_cart_args', 20, 2);
      endif;
      }
      add_filter( 'init', 'remove_wvs_pro_filter', 20 ); */


    /* Make unchecked for ship to differ addr in checkout page */
    add_filter('woocommerce_ship_to_different_address_checked', '__return_false');

// Remove the payment options form from default location
    remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
// Move the payment options
    add_action('woocommerce_checkout_after_customer_details', 'woocommerce_checkout_payment', 20);
    /* PRODUCT LABEL */
    if (!function_exists('zoa_product_label')) {

        /**
         * Display product label
         *
         * @param      $product  The product
         *
         * @return     $label markup
         */
        function zoa_product_label($product) {
            if (!$product) {
                return;
            }

            $label = '';

            // product option
            if (function_exists('FW')) {
                $pid = $product->get_id();
                $label_txt = fw_get_db_post_option($pid, 'label_txt', '');
                $label_color = fw_get_db_post_option($pid, 'label_color', '#fff');
                $label_bg = fw_get_db_post_option($pid, 'label_bg', '#f00');

                if (!empty($label_txt)) {
                    $style = array(
                        'color' => 'color: ' . esc_attr($label_color),
                        'background-color' => 'background-color: ' . esc_attr($label_bg),
                    );

                    $label = '<span class="zoa-product-label" style="' . implode('; ', $style) . '">' . esc_html($label_txt) . '</span>';
                }
            }

            // out of stock label
            if (!$product->is_in_stock()) {
                $label = '<span class="zoa-product-label sold-out-label">' . esc_html__('Sold out', 'zoa') . '</span>';
            }

            return $label;
        }

    }
    /* ! BLOG CATEGORIES
      -------------------------------------------------> */
    if (!function_exists('zoa_blog_categories')):

        function zoa_blog_categories() {
            return get_the_term_list(get_the_ID(), 'category', esc_html_x('', 'In Uncategorized Category', 'zoa'), ', ', null);
        }

    endif;
    /* ! BLOG POST INFO
      -------------------------------------------------> */
    if (!function_exists('zoa_post_info')):

        function zoa_post_info() {
            global $post;
            ?>
            <span class="if-item if-cat"><?php echo zoa_blog_categories(); ?></span>
            <time class="if-item if-date" itemprop="datePublished" datetime="<?php echo get_the_time('c'); ?>"><?php echo zoa_date_format(); ?></time>
            <?php
        }

    endif;

//親ページ判別
    function is_child($slug = "") {
        if (is_singular())://投稿ページのとき（固定ページ含）
            global $post;
            if ($post->post_parent) {//現在のページに親がいる場合
                $post_data = get_post($post->post_parent); //親ページの取得
                if ($slug != "") {//$slugが空じゃないとき
                    if (is_array($slug)) {//$slugが配列のとき
                        for ($i = 0; $i <= count($slug); $i++) {
                            if ($slug[$i] == $post_data->post_name || $slug[$i] == $post_data->ID || $slug[$i] == $post_data->post_title) {//$slugの中のどれかが親ページのスラッグ、ID、投稿タイトルと同じのとき
                                return true;
                            }
                        }
                    } elseif ($slug == $post_data->post_name || $slug == $post_data->ID || $slug == $post_data->post_title) {//$slugが配列ではなく、$slugが親ページのスラッグ、ID、投稿タイトルと同じのとき
                        return true;
                    } else {
                        return false;
                    }
                } else {//親ページは存在するけど$slugが空のとき
                    return true;
                }
            } else {//親ページがいない
                return false;
            }
        endif;
    }

//just check child page nby path
    function is_subpage() {
        global $wp;
        $request = explode('/', $wp->request);
        if (count($request) >= 1) {
            $parentslug = $request[0];
        }
    }

    ;
    /* ! PAGE HEADER
      -------------------------------------------------> */
    if (!function_exists('zoa_page_header')):

        function zoa_page_header() {
            if (is_404())
                return;

            $c_header = zoa_page_header_slug();

            if ('disable' == $c_header)
                return;
            ?>
            <?php
            $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
            global $wp;
            $request = explode('/', $wp->request);
            $is_sub_myaccount = count($request) > 1 && $request[0] == 'my-account';
            ?>
            <?php if (!is_singular('post') && !$is_sub_myaccount && !is_singular('product') && !is_checkout() && !is_page('reservation-thanks')) { ?>
                <div class="<?php if ((!'layout-1' == $c_header)) { ?>breadcrumb_row<?php } else { ?>page-header phd-<?php echo esc_attr($c_header); ?><?php } ?><?php if (has_post_thumbnail() && !is_home() && !is_archive() && !is_woocommerce()) { ?> has-bg js-parallax<?php } ?>" <?php if (has_post_thumbnail() && !is_home() && !is_archive() && !is_woocommerce()) { ?>style="background-image:url(<?php echo $featured_img_url; ?>);"<?php } ?>>
                    <div class="max-width--large gutter-padding--full">
                        <?php /* BREADCRUMBS */ ?>
                        <div id="theme-bread" class="<?php if (is_singular('product') || (!'layout-1' == $c_header )) { ?>display--small-up<?php } else { ?>display--mid-up<?php } ?>">
                            <?php
                            if (function_exists('fw_ext_breadcrumbs')) {
                                fw_ext_breadcrumbs();
                            }
                            ?>
                        </div>
                        <?php
                        /* PAGE TITLE */
                        if (( 'layout-1' == $c_header ) && !is_singular('product')):
                            ?>
                            <?php if (has_post_thumbnail() && !is_home() && !is_archive() && !is_woocommerce()) { ?><div class="bg_title_cover"><div class="container"><?php } ?>
                                    <div id="theme-page-title">
                                        <?php zoa_page_title(); ?>
                                        <?php
                                        if (!is_woocommerce() && get_the_subtitle($post_id) != ''): echo '<p class="page-subtitle">' . get_the_subtitle() . '</p>';
                                        endif;
                                        ?>
                                    </div>
                                    <?php if (get_field('summary')): ?><div class="short_summary"><?php the_field('summary'); ?></div><?php endif; ?>
                                    <?php if (has_post_thumbnail() && !is_home() && !is_archive() && !is_woocommerce()) { ?></div></div><!--/.bg_title_cover---><?php } ?>
                        <?php endif; ?>



                    </div>
                </div>
            <?php } else if (is_singular('product') || is_checkout()) { ?>
                <!--no breadcrumbs-->
            <?php } else { ?>
                <div class="breadcrumb_row">
                    <div class="max-width--large gutter-padding--full">
                        <?php /* PAGE HEADER FOR BLOG POST */ ?>
                        <div id="theme-bread" class="display--small-up">
                            <?php
                            if (function_exists('fw_ext_breadcrumbs')) {
                                fw_ext_breadcrumbs();
                            }
                            ?>
                        </div>
                    </div>
                </div>
            <?php } ?><!--if ( !is_singular( 'post' ) )-->
            <?php
        }

    endif;

//change custom post archive page title
    /* ! PAGE TITLE
      -------------------------------------------------> */
    if (!function_exists('zoa_page_title')):

        function zoa_page_title() {

            /* PAGE TITLE */
            $title = get_the_title();

            /* BLOG TITLE */
            $blog_title = get_theme_mod('blog_title', 'Blog');

            /* SHOP TITLE */
            $shop_title = get_theme_mod('shop_title', 'Shop');
            ?>
            <h1 class="page-title entry-title">
                <?php
                if (is_day()):
                    printf(esc_html__('Daily Archives: %s', 'zoa'), get_the_date());
                elseif (is_month()):
                    printf(esc_html__('Monthly Archives: %s', 'zoa'), get_the_date(esc_html_x('F Y', 'monthly archives date format', 'zoa')));
                elseif (is_home()):
                    echo esc_html($blog_title);
                elseif (is_author()):
                    $author = ( get_query_var('author_name') ) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
                    echo esc_html($author->display_name);
                elseif (is_year()):
                    printf(esc_html__('Yearly Archives: %s', 'zoa'), get_the_date(esc_html_x('Y', 'yearly archives date format', 'zoa')));
                elseif (class_exists('woocommerce') && is_shop()):
                    echo esc_html($shop_title);
                elseif (class_exists('woocommerce') && ( is_product_tag() || is_tag() )):
                    esc_html_e('Tags: ', 'zoa');
                    single_tag_title();
                elseif (is_page() || is_single()):
                    echo!empty($title) ? esc_html($title) : esc_html__('This post has no title', 'zoa');
                elseif (is_tax()):
                    global $wp_query;
                    $term = $wp_query->get_queried_object();
                    $tex_title = $term->name;
                    echo esc_html($tex_title);
                elseif (is_search()):
                    esc_html_e('Search results', 'zoa');
                elseif (is_post_type_archive('portfolio')):
                    esc_html_e('Portfolio', 'zoa');
                elseif (is_category()):
                    echo single_cat_title();
                else:
                    esc_html_e('Archives', 'zoa');
                endif;
                ?>
            </h1>
            <?php
        }

    endif;

    add_filter('woocommerce_billing_fields', 'custom_woocommerce_billing_fields');

    function custom_woocommerce_billing_fields($fields) {

        $fields['billing_last_name_kana'] = array(
            'label' => __('Last Name Kana', 'zoa'),
            'required' => true,
            'class' => array('form-row-first')
        );
        $fields['billing_first_name_kana'] = array(
            'label' => __('First Name Kana', 'zoa'),
            'required' => true,
            'class' => array('form-row-last'),
            'clear' => true
        );


        $fields['billing_last_name']['class'] = array('form-row-first');
        $fields['billing_first_name']['class'] = array('form-row-last');

        $fields['billing_email']['class'] = array('form-row-first');
        $fields['billing_phone']['class'] = array('form-row-last');

        $fields['billing_postcode']['class'] = array('form-row-first');
        $fields['billing_postcode']['maxlength'] = 7;
        $fields['billing_state']['class'] = array('form-row-last');

        $fields['billing_city']['class'] = array('form-row-first');
        $fields['billing_address_1']['class'] = array('form-row-last');


        //change order
        $order = array(
            "billing_last_name",
            "billing_first_name",
            "billing_last_name_kana",
            "billing_first_name_kana",
            "billing_email",
            "billing_phone",
            "billing_country",
            "billing_postcode",
            "billing_state",
            "billing_city",
            "billing_address_1",
            "billing_address_2"
        );

        if (get_locale() == 'ja') {
            //unset($fields['billing_country']);
        }

        $ordered_fields = array();
        foreach ($order as $indexField => $field) {
            if (isset($fields[$field])) {
                $fields[$field]['priority'] = ($indexField + 1) * 10;
                $ordered_fields[$field] = $fields[$field];
            }
        }

        $fields = $ordered_fields;
        return $fields;
    }

    add_filter('woocommerce_shipping_fields', 'custom_woocommerce_shipping_fields');

    function custom_woocommerce_shipping_fields($fields) {

//     unset($fields['shipping_country']);

        $fields['shipping_last_name_kana'] = array(
            'label' => __('Last Name Kana', 'zoa'),
            'required' => true,
            'class' => array('form-row-first')
        );
        $fields['shipping_first_name_kana'] = array(
            'label' => __('First Name Kana', 'zoa'),
            'required' => true,
            'class' => array('form-row-last'),
            'clear' => true
        );




        if (get_locale() == 'ja') {
            $fields['shipping_phone'] = array(
                'label' => __('Phone', 'woocommerce'),
                'placeholder' => _x('', 'placeholder', 'woocommerce'),
                'required' => true,
                'class' => array('form-row-wide'),
                'clear' => true
            );

            $fields['shipping_last_name']['class'] = array('form-row-first');
            $fields['shipping_first_name']['class'] = array('form-row-last');
            $fields['shipping_postcode']['class'] = array('form-row-first', 'address-field');
            $fields['shipping_postcode']['maxlength'] = 7;
            $fields['shipping_state']['class'] = array('form-row-last', 'address-field');
            $fields['shipping_city']['class'] = array('form-row-first');
            $fields['shipping_address_1']['class'] = array('form-row-last');
            $fields['shipping_country']['class'] = array('form-row-last');

// 		unset($fields['shipping_country']);
            //change order
            $order = array(
                "shipping_last_name",
                "shipping_first_name",
                "shipping_last_name_kana",
                "shipping_first_name_kana",
                "shipping_country",
                "shipping_postcode",
                "shipping_state",
                "shipping_city",
                "shipping_address_1",
                "shipping_address_2",
                "shipping_phone"
            );
        } else {

            unset($fields['shipping_last_name_kana']);
            unset($fields['shipping_first_name_kana']);

            $fields['shipping_first_name']['class'] = array('form-row-first');
            $fields['shipping_last_name']['class'] = array('form-row-last');
            $fields['shipping_country']['class'] = array('form-row-wide');
            $fields['shipping_address_1']['class'] = array('form-row-first');
            $fields['shipping_address_2']['class'] = array('form-row-last');
            $fields['shipping_city']['class'] = array('form-row-first');
            $fields['shipping_state']['class'] = array('form-row-last', 'address-field');
            $fields['shipping_postcode']['class'] = array('form-row-first', 'address-field');
            $fields['shipping_phone']['class'] = array('form-row-last', 'address-field');

            //$fields['shipping_postcode']['maxlength'] = 8;
            //change order
            $order = array(
                "shipping_first_name",
                "shipping_last_name",
                "shipping_country",
                "shipping_address_1",
                "shipping_address_2",
                "shipping_city",
                "shipping_state",
                "shipping_postcode",
                "shipping_phone"
            );
        }

        $ordered_fields = array();
        foreach ($order as $indexField => $field) {
            if (isset($fields[$field])) {
                $fields[$field]['priority'] = ($indexField + 1) * 10;
                $ordered_fields[$field] = $fields[$field];
            }
        }

        $fields = $ordered_fields;

        return $fields;
    }

    /**
     * change woocommerce forms wrapper element
     *
     */
    if (!function_exists('woocommerce_form_field')) {

        /**
         * Outputs a checkout/address form field.
         *
         * @param string $key Key.
         * @param mixed  $args Arguments.
         * @param string $value (default: null).
         * @return string
         */
        function woocommerce_form_field($key, $args, $value = null) {
            $defaults = array(
                'type' => 'text',
                'label' => '',
                'description' => '',
                'placeholder' => '',
                'maxlength' => false,
                'required' => false,
                'autocomplete' => false,
                'id' => $key,
                'class' => array(),
                'label_class' => array(),
                'input_class' => array(),
                'return' => false,
                'options' => array(),
                'custom_attributes' => array(),
                'validate' => array(),
                'default' => '',
                'autofocus' => '',
                'priority' => '',
            );

            $args = wp_parse_args($args, $defaults);
            $args = apply_filters('woocommerce_form_field_args', $args, $key, $value);

            if ($args['required']) {
                $args['class'][] = 'validate-required';
                $required = '&nbsp;<abbr class="required" title="' . esc_attr__('required', 'woocommerce') . '">*</abbr>';
            } else {
                $required = '&nbsp;<span class="optional">(' . esc_html__('optional', 'woocommerce') . ')</span>';
            }

            if (is_string($args['label_class'])) {
                $args['label_class'] = array($args['label_class']);
            }

            if (is_null($value)) {
                $value = $args['default'];
            }

            // Custom attribute handling.
            $custom_attributes = array();
            $args['custom_attributes'] = array_filter((array) $args['custom_attributes'], 'strlen');

            if ($args['maxlength']) {
                $args['custom_attributes']['maxlength'] = absint($args['maxlength']);
            }

            if (!empty($args['autocomplete'])) {
                $args['custom_attributes']['autocomplete'] = $args['autocomplete'];
            }

            if (true === $args['autofocus']) {
                $args['custom_attributes']['autofocus'] = 'autofocus';
            }

            if ($args['description']) {
                $args['custom_attributes']['aria-describedby'] = $args['id'] . '-description';
            }

            if (!empty($args['custom_attributes']) && is_array($args['custom_attributes'])) {
                foreach ($args['custom_attributes'] as $attribute => $attribute_value) {
                    $custom_attributes[] = esc_attr($attribute) . '="' . esc_attr($attribute_value) . '"';
                }
            }

            if (!empty($args['validate'])) {
                foreach ($args['validate'] as $validate) {
                    $args['class'][] = 'validate-' . $validate;
                }
            }

            $field = '';
            $label_id = $args['id'];
            $sort = $args['priority'] ? $args['priority'] : '';
            $field_container = '<div class="form-row %1$s" id="%2$s" data-priority="' . esc_attr($sort) . '"><div class="field-wrapper">%3$s</div></div>';

            switch ($args['type']) {
                case 'country':
                    $countries = 'shipping_country' === $key ? WC()->countries->get_shipping_countries() : WC()->countries->get_allowed_countries();

                    if (1 === count($countries)) {

                        $field .= '<strong>' . current(array_values($countries)) . '</strong>';

                        $field .= '<input type="hidden" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" value="' . current(array_keys($countries)) . '" ' . implode(' ', $custom_attributes) . ' class="country_to_state" readonly="readonly" />';
                    } else {

                        $field = '<select name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" class="country_to_state country_select ' . esc_attr(implode(' ', $args['input_class'])) . '" ' . implode(' ', $custom_attributes) . '><option value="">' . esc_html__('Select a country&hellip;', 'woocommerce') . '</option>';

                        foreach ($countries as $ckey => $cvalue) {
                            $field .= '<option value="' . esc_attr($ckey) . '" ' . selected($value, $ckey, false) . '>' . $cvalue . '</option>';
                        }

                        $field .= '</select>';

                        $field .= '<noscript><button type="submit" name="woocommerce_checkout_update_totals" value="' . esc_attr__('Update country', 'woocommerce') . '">' . esc_html__('Update country', 'woocommerce') . '</button></noscript>';
                    }

                    break;
                case 'state':
                    /* Get country this state field is representing */
                    $for_country = isset($args['country']) ? $args['country'] : WC()->checkout->get_value('billing_state' === $key ? 'billing_country' : 'shipping_country');
                    $states = WC()->countries->get_states($for_country);

                    if (is_array($states) && empty($states)) {

                        $field_container = '<p class="form-row %1$s" id="%2$s" style="display: none">%3$s</p>';

                        $field .= '<input type="hidden" class="hidden" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" value="" ' . implode(' ', $custom_attributes) . ' placeholder="' . esc_attr($args['placeholder']) . '" readonly="readonly" />';
                    } elseif (!is_null($for_country) && is_array($states)) {

                        $field .= '<select name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" class="state_select ' . esc_attr(implode(' ', $args['input_class'])) . '" ' . implode(' ', $custom_attributes) . ' data-placeholder="' . esc_attr($args['placeholder']) . '">
						<option value="">' . esc_html__('Select a state&hellip;', 'woocommerce') . '</option>';

                        foreach ($states as $ckey => $cvalue) {
                            $field .= '<option value="' . esc_attr($ckey) . '" ' . selected($value, $ckey, false) . '>' . $cvalue . '</option>';
                        }

                        $field .= '</select>';
                    } else {

                        $field .= '<input type="text" class="input-text ' . esc_attr(implode(' ', $args['input_class'])) . '" value="' . esc_attr($value) . '"  placeholder="' . esc_attr($args['placeholder']) . '" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" ' . implode(' ', $custom_attributes) . ' />';
                    }

                    break;
                case 'textarea':
                    $field .= '<textarea name="' . esc_attr($key) . '" class="input-text ' . esc_attr(implode(' ', $args['input_class'])) . '" id="' . esc_attr($args['id']) . '" placeholder="' . esc_attr($args['placeholder']) . '" ' . ( empty($args['custom_attributes']['rows']) ? ' rows="2"' : '' ) . ( empty($args['custom_attributes']['cols']) ? ' cols="5"' : '' ) . implode(' ', $custom_attributes) . '>' . esc_textarea($value) . '</textarea>';

                    break;
                case 'checkbox':
                    $field = '<label class="checkbox ' . implode(' ', $args['label_class']) . '" ' . implode(' ', $custom_attributes) . '>
						<input type="' . esc_attr($args['type']) . '" class="input-checkbox ' . esc_attr(implode(' ', $args['input_class'])) . '" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" value="1" ' . checked($value, 1, false) . ' /> ' . $args['label'] . $required . '</label>';

                    break;
                case 'text':
                case 'password':
                case 'datetime':
                case 'datetime-local':
                case 'date':
                case 'month':
                case 'time':
                case 'week':
                case 'number':
                case 'email':
                case 'url':
                case 'tel':
                    $field .= '<input type="' . esc_attr($args['type']) . '" class="input-text ' . esc_attr(implode(' ', $args['input_class'])) . '" name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" placeholder="' . esc_attr($args['placeholder']) . '"  value="' . esc_attr($value) . '" ' . implode(' ', $custom_attributes) . ' />';

                    break;
                case 'select':
                    $field = '';
                    $options = '';

                    if (!empty($args['options'])) {
                        foreach ($args['options'] as $option_key => $option_text) {
                            if ('' === $option_key) {
                                // If we have a blank option, select2 needs a placeholder.
                                if (empty($args['placeholder'])) {
                                    $args['placeholder'] = $option_text ? $option_text : __('Choose an option', 'woocommerce');
                                }
                                $custom_attributes[] = 'data-allow_clear="true"';
                            }
                            $options .= '<option value="' . esc_attr($option_key) . '" ' . selected($value, $option_key, false) . '>' . esc_attr($option_text) . '</option>';
                        }

                        $field .= '<select name="' . esc_attr($key) . '" id="' . esc_attr($args['id']) . '" class="select ' . esc_attr(implode(' ', $args['input_class'])) . '" ' . implode(' ', $custom_attributes) . ' data-placeholder="' . esc_attr($args['placeholder']) . '">
							' . $options . '
						</select>';
                    }

                    break;
                case 'radio':
                    $label_id = current(array_keys($args['options']));

                    if (!empty($args['options'])) {
                        foreach ($args['options'] as $option_key => $option_text) {
                            $field .= '<input type="radio" class="input-radio ' . esc_attr(implode(' ', $args['input_class'])) . '" value="' . esc_attr($option_key) . '" name="' . esc_attr($key) . '" ' . implode(' ', $custom_attributes) . ' id="' . esc_attr($args['id']) . '_' . esc_attr($option_key) . '"' . checked($value, $option_key, false) . ' />';
                            $field .= '<label for="' . esc_attr($args['id']) . '_' . esc_attr($option_key) . '" class="radio ' . implode(' ', $args['label_class']) . '">' . $option_text . '</label>';
                        }
                    }

                    break;
            }

            if (!empty($field)) {
                $field_html = '';

                if ($args['label'] && 'checkbox' !== $args['type']) {
                    $field_html .= '<label for="' . esc_attr($label_id) . '" class="' . esc_attr(implode(' ', $args['label_class'])) . '">' . $args['label'] . $required . '</label>';
                }

                $field_html .= '<span class="woocommerce-input-wrapper">' . $field;

                if ($args['description']) {
                    $field_html .= '<span class="description" id="' . esc_attr($args['id']) . '-description" aria-hidden="true">' . wp_kses_post($args['description']) . '</span>';
                }

                $field_html .= '</span>';

                $container_class = esc_attr(implode(' ', $args['class']));
                $container_id = esc_attr($args['id']) . '_field';
                $field = sprintf($field_container, $container_class, $container_id, $field_html);
            }

            /**
             * Filter by type.
             */
            $field = apply_filters('woocommerce_form_field_' . $args['type'], $field, $key, $args, $value);

            /**
             * General filter on form fields.
             *
             * @since 3.4.0
             */
            $field = apply_filters('woocommerce_form_field', $field, $key, $args, $value);

            if ($args['return']) {
                return $field;
            } else {
                echo $field; // WPCS: XSS ok.
            }
        }

    }

    /**
     * Display field value on the order edit page
     */
    add_action('woocommerce_admin_order_data_after_shipping_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1);

    function my_custom_checkout_field_display_admin_order_meta($order) {
        echo '<p><strong>' . __('Phone From Checkout Form') . ':</strong> ' . get_post_meta($order->get_id(), '_shipping_phone', true) . '</p>';
    }

    define('BOOKING_FORM_ID', 2673);
    add_action('admin_enqueue_scripts', 'load_custom_wp_admin_custom_script');

    function load_custom_wp_admin_custom_script() {
        wp_enqueue_script('admin_js', get_stylesheet_directory_uri() . '/js/admin.js', array(
            'jquery'
        ));
        wp_enqueue_style('admin_css', get_stylesheet_directory_uri() . '/js/admin.css');

        if ($_REQUEST['page'] == 'birchschedule_settings') {
            wp_enqueue_script(
                    'field-date-js', 'Field_Date.js', array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'), time(), true
            );
        }

        wp_enqueue_style('jquery-ui-datepicker', get_stylesheet_directory_uri() . '/css/jquery-ui.min.css');
    }

    function pr($data) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    function hide_plugin_order_by_product() {
        global $wp_list_table;
        $hidearr = array(
            'birchschedule/birchschedule.php',
        );
        $active_plugins = get_option('active_plugins');

        $myplugins = $wp_list_table->items;
        foreach ($myplugins as $key => $val) {
            if (in_array($key, $hidearr) && in_array($key, $active_plugins)) {
                unset($wp_list_table->items[$key]);
            }
        }
    }

    add_action('pre_current_active_plugins', 'hide_plugin_order_by_product');

    function validate_client_info() {
        $field_group_fields = acf_get_fields(BOOKING_FORM_ID);
        $acf_post = $_POST['acf'];
        $save_fields = array();
        foreach ($field_group_fields as $field) {
            loop_to_get_sub_field($field, $save_fields);
        }
        $errors = array();
        foreach ($save_fields as $field) {
            $key_longs = explode('***', $field['key_long'] ? $field['key_long'] : $field['key']);
            foreach ($key_longs as $key_long) {
                $post_field = !isset($post_field) ? $acf_post[$key_long] : $post_field[$key_long];
            }
            if ($field['required']) {
                if (!$post_field) {
                    $errors[$field['key']] = $field['label'] . __(' is required', 'birchschedule');
                }
            }

            $_POST[$field['key']] = $post_field;
            assign_booking_post_data($field);
            unset($post_field);
        }

        if ($_POST['is_register']) {
            $_REQUEST['user_login'] = $_POST['user_login'] = $_REQUEST['user_email'] = $_POST['user_email'] = $_POST['birs_client_email'];
            // Check email is exist or not.
            $user = register_new_user($_POST['user_login'], $_POST['user_email']);
            if (!is_wp_error($user)) {
                //Success
                $result = array();
                $result['result'] = true;
                $result['user'] = get_user_by('id', $user);
                $result['user']->set_role('customer');

                $result['message'] = __('Registration complete. Please check your e-mail.', 'birchschedule');
            } else {
                //Something's wrong
                $errors['is_register'] = $user->get_error_messages();
                if (!empty($errors['is_register']) && count($errors['is_register']) == 2) {
                    $errors['is_register'] = $errors['is_register'][1];
                }
            }
        }
        return $errors;
    }

    add_action('register_new_user', 'autoLoginUser', 10, 1);

    function autoLoginUser($user_id) {
        $user = get_user_by('id', $user_id);
        if ($user && isset($_POST['is_register'])) {
            if ($_POST['user_password']) {
                wp_set_password($_POST['user_password'], $user_id);
            }
            wp_set_current_user($user_id, $user->user_login);
            wp_set_auth_cookie($user_id);
            do_action('wp_login', $user->user_login, $user);
        }
    }

    function assign_booking_post_data($field) {
        if ($field['name'] == 'last_name') {
            $_POST['birs_client_name_last'] = $_POST[$field['key']];
        } else if ($field['name'] == 'first_name') {
            $_POST['birs_client_name_first'] = $_POST[$field['key']];
        } else if ($field['name'] == 'tel') {
            $_POST['birs_client_phone'] = $_POST[$field['key']];
        } else if ($field['name'] == 'email') {
            $_POST['birs_client_email'] = $_POST[$field['key']];
        } else if (strpos($field['name'], 'address1')) {
            $_POST['birs_client_address1'] = $_POST[$field['key']];
        } else if (strpos($field['name'], 'address2')) {
            $_POST['birs_client_address2'] = $_POST[$field['key']];
        } else if (strpos($field['name'], 'address')) {
            $_POST['birs_client_address'] = $_POST[$field['key']];
        } else if (strpos($field['name'], 'city')) {
            $_POST['birs_client_city'] = $_POST[$field['key']];
        } else if (strpos($field['name'], 'state')) {
            $_POST['birs_client_state'] = $_POST[$field['key']];
        } else if (strpos($field['name'], 'zip')) {
            $_POST['birs_client_zip'] = $_POST[$field['key']];
        }
        $_POST['birs_client_country'] = 'JP';
    }

    function get_booking_form($step = '') {
        if (!session_id()) {
            session_start();
        }

        // Add this to call acf assets
        ob_start();
        get_client_info_html($_SESSION['appointment_id'], $step);
        $acf_form = ob_get_contents();
        ob_end_clean();

        $acf_form = str_replace('<form id="' . BOOKING_FORM_ID . '" class="acf-form" action="" method="post">', '', $acf_form);
        $acf_form = str_replace('</form>', '', $acf_form);
        $acf_form = str_replace('required="required"', 'required="required" class="validate[required]"', $acf_form);

        echo $acf_form;
    }

    function loop_to_get_sub_field($field, &$save_fields) {
        if (isset($field['sub_fields']) && !empty($field['sub_fields'])) {
            foreach ($field['sub_fields'] as $sub_field) {
                $sub_field['name_long'] = isset($field['name_long']) ? ($field['name_long'] . '_' . $sub_field['name']) : ($field['name'] . '_' . $sub_field['name']);
                $sub_field['key_long'] = isset($field['key_long']) ? ($field['key_long'] . '***' . $sub_field['key']) : ($field['key'] . '***' . $sub_field['key']);
                $sub_field['parent_id'] = $field['ID'];
                $sub_field['parent_name'] = $field['name'];
                $sub_field['sub_depth'] = isset($field['sub_depth']) ? $field['sub_depth'] + 1 : 1;

                $return_field = loop_to_get_sub_field($sub_field, $save_fields);
            }
        } else {
            $return_field = $field;
        }
        $save_fields[$return_field['ID']] = $return_field;
        return $return_field;
    }

    add_action('save_post', 'zoa_update_q_04_from_client', 10000);

    function zoa_update_q_04_from_client() {
        global $post;

        if ($_POST['birs_client_email'] && $post->post_type == 'birs_client') {
            $client_email = get_post_meta($post->ID, '_birs_client_email', true);
            $user = get_user_by('email', $client_email);
            if ($user) {
                save_birthday_account_details($user->ID);
            }
        }

        if ($_POST['post_type'] == 'birs_appointment') {
            $appointment_id = $_POST['post_ID'];
            after_save_client_booking($appointment_id);
        }
    }

    function get_client_info_html($client_id, $step = 0) {
        $field_group_fields = acf_get_fields(BOOKING_FORM_ID);
        $save_fields = array();
        foreach ($field_group_fields as $field) {
            loop_to_get_sub_field($field, $save_fields);
        }
        ob_start();
        acf_form(array(
            "id" => BOOKING_FORM_ID
        ));
        ob_end_clean();

        if (is_user_logged_in() && !is_admin()) {
            // Fill user info to acf if logged in
            $user_id = get_current_user_id();
            $user_info = array();
            $user_info['billing_last_name'] = get_user_meta($user_id, 'billing_last_name', true);
            $user_info['billing_first_name'] = get_user_meta($user_id, 'billing_first_name', true);
            $user_info['billing_last_name_kana'] = get_user_meta($user_id, 'billing_last_name_kana', true);
            $user_info['billing_first_name_kana'] = get_user_meta($user_id, 'billing_first_name_kana', true);
            $user_info['billing_email'] = get_user_meta($user_id, 'billing_email', true);
            $user_info['billing_phone'] = get_user_meta($user_id, 'billing_phone', true);

            $aDefaultFields = array(
                '2675' => 'billing_last_name', '2676' => 'billing_first_name',
                '2678' => 'billing_last_name_kana', '2679' => 'billing_first_name_kana',
                '2680' => 'billing_email', '2681' => 'billing_phone'
            );
            foreach ($field_group_fields as &$field_group_field) {
                if ($field_group_field['name'] == 'name' || $field_group_field['name'] == 'name_kana') {
                    $field_id1 = $field_group_field['sub_fields'][0]['ID'];
                    $field_id2 = $field_group_field['sub_fields'][1]['ID'];

                    $field_group_field['sub_fields'][0]['default_value'] = $user_info[$aDefaultFields[$field_id1]];
                    $field_group_field['sub_fields'][1]['default_value'] = $user_info[$aDefaultFields[$field_id2]];
                }

                if ($field_group_field['name'] == 'email' || $field_group_field['name'] == 'tel') {
                    $field_group_field['default_value'] = $user_info[$aDefaultFields[$field_group_field['ID']]];
                }
                if ($field_group_field['name'] == 'questions') {
                    $user_id = get_current_user_id();
                    if ($user_id && !isset($_SESSION['appointment_id'])) {
                        foreach ($field_group_field['sub_fields'] as $field_key => $question) {
                            if ($question['name'] == 'q_04') {
                                foreach ($question['sub_fields'] as $question_key => $question_item) {
                                    $field_default_value = get_user_meta($user_id, $question_item['name'], true);

                                    if ($field_default_value) {
                                        $field_group_field['sub_fields'][$field_key]['sub_fields'][$question_key]['value'] = (array) $field_default_value;
                                        $field_group_field['sub_fields'][$field_key]['sub_fields'][$question_key]['default_value'] = (array) $field_default_value;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $booking_id = isset($_GET['post']) ? $_GET['post'] : $client_id;
        $field_group_fields = getBookingStepFields($field_group_fields, $step);

        acf_render_fields($field_group_fields, $booking_id, 'div', 'label');
        global $birchschedule;
        $fields = $birchschedule->model->get_client_fields();
        foreach ($fields as $field) {
            echo '<input type="hidden" name="birs_client_fields[]" value="' . $field . '" />';
        }
    }

    function getBookingStepFields($field_group_fields, $step = 0) {
        if ($step == 0)
            return $field_group_fields;

        $field_group_fields_clone = $field_group_fields;
        if ($step == 3) {
            // Remove fields not in step 3
            foreach ($field_group_fields_clone as $field_index => $field_group_field_clone) {
                if (strpos($field_group_field_clone['wrapper']['class'], 'step3-fields') === false) {
                    unset($field_group_fields[$field_index]);
                }
            }
        } else {
            // Remove Step 3 fields
            foreach ($field_group_fields_clone as $field_index => $field_group_field_clone) {
                if (strpos($field_group_field_clone['wrapper']['class'], 'step3-fields') !== false) {
                    unset($field_group_fields[$field_index]);
                }
            }
        }
        return $field_group_fields;
    }

    add_action('after_save_booking', 'after_save_client_booking', 10, 3);

    function after_save_client_booking($appointment_id, $client_id = 0, $appointment1on1_id = 0) {
        if (!session_id()) {
            session_start();
        }

        $field_group_fields = acf_get_fields(BOOKING_FORM_ID);

        $save_fields = array();
        foreach ($field_group_fields as $field) {
            loop_to_get_sub_field($field, $save_fields);
        }

        foreach ($save_fields as $save_field) {
            $field_name = ($save_field['name_long'] ? $save_field['name_long'] : $save_field['name']);
            update_post_meta($appointment_id, '_' . $field_name, $save_field['key']);
            update_post_meta($appointment_id, $field_name, $_POST[$save_field['key']]);

            // Save q_04 value to custom info
            if (is_user_logged_in() && $save_field['parent_name'] == 'q_04' && !is_admin()) {
                $user_id = get_current_user_id();
                update_user_meta($user_id, $save_field['name'], $_POST[$save_field['key']]);
            }
        }

        if (!isset($_POST['booking_confirm']) && $_POST['action'] == 'birchschedule_view_bookingform_schedule') {
            $booking_post = array(
                'ID' => $appointment_id,
                'post_status' => 'trash',
            );

            // Update the post into the database
            wp_update_post($booking_post);
            $_SESSION['appointment_id'] = $appointment_id;
            $_SESSION['client_id'] = $client_id;
            $_SESSION['appointment1on1_id'] = $appointment1on1_id;

            wp_scheduled_delete();
        }

        if (is_admin() && $_POST['post_ID'] && $client_id) {
            update_meta_info_booking($appointment_id);
        }
    }

    function zoa_get_avaiable_booking_time() {
        global $birchschedule, $birchpress;

        $staff_id = $_POST['birs_appointment_staff'];
        $location_id = $_POST['birs_appointment_location'];
        $service_id = $_POST['birs_appointment_service'];
        $date_text = $_POST['birs_appointment_date'];
        $date = $birchpress->util->get_wp_datetime(
                array(
                    'date' => $date_text,
                    'time' => 0
                )
        );

        $time_options = $birchschedule->model->schedule->get_staff_avaliable_time($staff_id, $location_id, $service_id, $date);

        return $time_options;
    }

    function update_meta_info_booking($appointment_id = 0) {
        global $birchpress, $birchschedule;
        $appointment_id = $appointment_id ? $appointment_id : $_SESSION['appointment_id'];

        $appointment = $birchschedule->model->get($appointment_id, array(
            'base_keys' => array(),
            'meta_keys' => $birchschedule->model->get_appointment_fields()
        ));

        $timestamp = $birchpress->util->get_wp_datetime($appointment['_birs_appointment_timestamp']);
        $appointment_date = $timestamp->format('Y-m-d');
        $appointment_time = $timestamp->format('H:i');

        update_post_meta($appointment_id, '_birs_appointment_date', $appointment_date);
        update_post_meta($appointment_id, '_birs_appointment_time', $appointment_time);

        // Check and set date fulled room
        $_POST['birs_appointment_location'] = $appointment['_birs_appointment_location'];
        $_POST['birs_appointment_service'] = $appointment['_birs_appointment_service'];
        $_POST['birs_appointment_staff'] = $appointment['_birs_appointment_staff'];
        $_POST['birs_appointment_date'] = $timestamp->format('m/d/Y');
        $time_options = zoa_get_avaiable_booking_time();
        $booked_times = getTimeIsBookedFromDate();
        foreach ($booked_times as $booked_time) {
            foreach ($time_options as $time_key => $time_option) {
                if ($booked_time == date('H:i', strtotime($time_option['text']))) {
                    unset($time_options[$time_key]);
                    break;
                }
            }
        }
        if (empty($time_options)) {
            // Set this date is unavaiable
            $today = date('Y-m-d');
            $unavaiable_dates = get_option('booking_unavaiable_dates');
            $unavaiable_dates = $unavaiable_dates ? $unavaiable_dates : array();
            if (!empty($unavaiable_dates)) {
                $clone_unavaiable_dates = $unavaiable_dates;
                foreach ($clone_unavaiable_dates as $key_date => $clone_unavaiable_date) {
                    if ($clone_unavaiable_date < $today) {
                        unset($unavaiable_dates[$key_date]);
                    }
                }
            }
            $unavaiable_dates[$_POST['birs_appointment_staff']] = $appointment_date;
            update_option('booking_unavaiable_dates', $unavaiable_dates);
        }
    }

    add_action('wp_ajax_bookingform_schedule_confirmed', 'bookingform_schedule_confirmed');
    add_action('wp_ajax_nopriv_bookingform_schedule_confirmed', 'bookingform_schedule_confirmed');

    function bookingform_schedule_confirmed() {
        if (!session_id()) {
            session_start();
        }
        $booking_post = array(
            'ID' => $_SESSION['appointment_id'],
            'post_status' => 'publish',
        );

        wp_update_post($booking_post);

        update_meta_info_booking();

        // Send emails
        $success = send_booking_email();


        echo json_encode(array('success' => 1));
        die;
    }

    function getTimeIsBookedFromDate() {
// 	$location_id = $_REQUEST['birs_appointment_location'];
// 	$service_id = $_REQUEST['birs_appointment_service'];
        $staff_id = $_POST['birs_appointment_staff'];
        $selected_date = $_POST['birs_appointment_date'];
        $aDate = explode('/', $selected_date);

        $args = array(
            'post_type' => 'birs_appointment',
            'post_status' => 'publish',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => '_birs_appointment_staff',
                    'value' => $staff_id,
                    'compare' => '='
                ),
                // this array results in no return for both arrays
                array(
                    'key' => '_birs_appointment_date',
                    'value' => $aDate[2] . '-' . $aDate[0] . '-' . $aDate[1],
                    'compare' => '='
                )
            )
        );
        $posts = get_posts($args);
        $aBookedTimes = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $post_id = $post->ID;
                $booked_time = get_post_meta($post_id, '_birs_appointment_time', true);
                if ($booked_time) {
                    $aBookedTimes[] = $booked_time;
                }
            }
        }
        return $aBookedTimes;
    }

    function send_booking_email() {

        $user_email = get_post_meta($_SESSION['client_id'], '_birs_client_email', true);
        $options = get_option('birchschedule_options');
        $admin_email = $options['store_email'];

        $site_title = get_bloginfo('name');

        $headers = 'Content-type: text/html;charset=utf-8; reservation=true' . "\r\n";
        $headers .= 'From: ' . $site_title . ' <info@chiyonoanne.xsrv.jp>' . "\r\n";


        $attachments = array();
        if (!isset($_SESSION['image_id']) || !$_SESSION['image_id']) {
            $galllery = get_post_meta($_SESSION['pid'], 'gallery', true);
            if ($galllery != '') {
                foreach ($galllery as $gallery) {
                    $attachments[] = get_attached_file($gallery);
                }
            }
        } else {
            $attachments[] = get_attached_file($_SESSION['image_id']);
        }
        // Send to user
        $email_content = get_booking_email_template(false, false);
        $subject = __('Thank you for booking', 'zoa') . ' | ' . get_bloginfo('name') . "\r\n";
        $success = wp_mail($user_email, $subject, $email_content, $headers, $attachments);
        if ($success) {
            // Now send to admin
            $email_content = get_booking_email_template(true, false);
            $subject = __('We have a booking via form', 'zoa') . "\r\n";
            $success = wp_mail($admin_email, $subject, $email_content, $headers, $attachments);
        }

        return $success;
    }

    function get_booking_email_template($is_admin, $has_html = true) {
        ob_start();
        get_booking_confirm_html(true);
        $content_html = ob_get_contents();
        ob_end_clean();

        if ($is_admin) {
            $html .= __('', 'zoa'); //Header Email Admin
            $html .= $content_html;
            $html .= __('', 'zoa'); //Footer Email Admin
        } else {
            $html .= __('', 'zoa'); //Header Email User
            $html .= $content_html;
            $html .= __('', 'zoa'); //Footer Email User
        }
        return $html;
    }

    function get_booking_confirm_html($is_email = false) {
        if ($is_email) {
            get_template_part('template-parts/content', 'booking-email');
            return '';
        }

        $appointment_info = get_appointment_info();
        if (empty($appointment_info))
            return '';
        ?>
        <div class="row flex-justify-center pad_row">
            <fieldset class="confirm_info col-md-4 col-xs-12">
                <h3 class="appointment--confirm__form__title heading heading--small"><?php echo __('Appointment Info', 'zoa') ?></h3>
                <div class="form-row"><div class="field-wrapper">
                        <label class="form-row__label light-copy"><?php echo __('Date', 'zoa') ?></label>
                        <div class="text_output">
                            <span class="confirm-text-value"><?php echo $appointment_info['date4picker'] ?></span>
                        </div>
                    </div></div><!--/.form-row-->
                <div class="form-row"><div class="field-wrapper">
                        <label class="form-row__label light-copy"><?php echo __('Time', 'zoa') ?></label>
                        <div class="text_output">
                            <span class="confirm-text-value"><?php echo $appointment_info['time'] ?></span>
                        </div>
                    </div></div><!--/.form-row-->
                <?php /* ?>
                  <div class="form-row"><div class="field-wrapper">
                  <label class="form-row__label light-copy"><?php echo __('Provider', 'zoa')?></label>
                  <div class="text_output">
                  <span class="confirm-text-value"><?php echo $appointment_info['staff_name']?></span>
                  </div>
                  </div></div><!--/.form-row-->
                  <?php */ ?>
                <div class="form-row"><div class="field-wrapper">
                        <label class="form-row__label light-copy"><?php echo __('Service', 'zoa') ?></label>
                        <div class="text_output">
                            <span class="confirm-text-value"><?php echo $appointment_info['service_name'] ?></span>
                        </div>
                    </div></div><!--/.form-row-->
            </fieldset>

            <?php
            $field_group_fields = acf_get_fields(BOOKING_FORM_ID);
            $field_group_fields = getBookingStepFields($field_group_fields, 2);
            $save_fields = array();
            echo '<fieldset class="confirm_info col-md-4 col-xs-12">';
            echo '<h3 class="appointment--confirm__form__title heading heading--small">' . __('Your Info', 'zoa') . '</h3>';
            foreach ($field_group_fields as $field) {
                loop_to_get_sub_field($field, $save_fields);
            }

            $last_name_value = get_post_meta($_SESSION['appointment_id'], ($save_fields['2675']['name_long'] ? $save_fields['2675']['name_long'] : $save_fields['2675']['name']), true);
            $first_name_value = get_post_meta($_SESSION['appointment_id'], ($save_fields['2676']['name_long'] ? $save_fields['2676']['name_long'] : $save_fields['2676']['name']), true);
            $last_name_kana_value = get_post_meta($_SESSION['appointment_id'], ($save_fields['2678']['name_long'] ? $save_fields['2678']['name_long'] : $save_fields['2678']['name']), true);
            $first_name_kana_value = get_post_meta($_SESSION['appointment_id'], ($save_fields['2679']['name_long'] ? $save_fields['2679']['name_long'] : $save_fields['2679']['name']), true);

            $save_fields['2675']['label'] = __('Name', 'zoa');
            $save_fields['2675']['full_value'] = $last_name_value . $first_name_value;

            $save_fields['2678']['label'] = __('Name Kana', 'zoa');
            $save_fields['2678']['full_value'] = $last_name_kana_value . $first_name_kana_value;

            unset($save_fields[2676]);
            unset($save_fields[2679]);

            foreach ($save_fields as $save_field) {
                $field_value = $save_field['full_value'] ? $save_field['full_value'] : get_post_meta($_SESSION['appointment_id'], ($save_field['name_long'] ? $save_field['name_long'] : $save_field['name']), true);

                if (!$field_value)
                    continue;

                echo '<div class="form-row"><div class="field-wrapper">
				<label class="form-row__label light-copy">' . $save_field['label'] . '</label>
			    <div class="text_output">
			      <span class="confirm-text-value">' . (is_array($field_value) ? implode(', ', $field_value) : $field_value) . '</span>
			    </div>

			</div></div>';
            }
            echo '</fieldset>';

            echo '<div class="confirm_info col-md-4 col-xs-12">';
            echo '<fieldset>';

            $field_group_fields = acf_get_fields(BOOKING_FORM_ID);
            $field_group_fields = getBookingStepFields($field_group_fields, 3);
            $save_fields = array();
            foreach ($field_group_fields as $field) {
                loop_to_get_sub_field($field, $save_fields);
            }
            ?>
            <h3 class="appointment--confirm__form__title heading heading--small"><?php _e('Your Inquiry', 'zoa'); ?></h3>
            <?php
            foreach ($save_fields as $save_field) {
                $field_value = $save_field['full_value'] ? $save_field['full_value'] : get_post_meta($_SESSION['appointment_id'], ($save_field['name_long'] ? $save_field['name_long'] : $save_field['name']), true);
                if (!$field_value)
                    continue;
                ?>
                <div class="form-row">
                    <div class="field-wrapper">
                        <label class="form-row__label light-copy"><?php echo $save_field['label'] ?></label>
                        <div class="text_output">
                            <span class="confirm-text-value"><?php echo (is_array($field_value) ? implode(', ', $field_value) : $field_value) ?></span>
                        </div>
                    </div>
                </div>
            <?php } ?>
            </fieldset>

            <?php if (isset($_SESSION['p_image']) && $_SESSION['p_image']) { ?>
                <fieldset>
                    <h3 class="appointment--confirm__form__title heading heading--small"><?php echo __('Inspired Photo', 'zoa') ?></h3>
                    <div class="form-row">
                        <div class="field-wrapper">
                            <div class="col-12">
                                <img src="<?php echo $_SESSION['p_image']; ?>" alt="image">
                            </div>
                        </div>
                    </div>
                </fieldset>
            <?php } ?>
        </div>
    </div>
    <div class="cancel_term_text">
        <?php
        printf(__('cancel_appointment_confirm_text', 'zoa'), $appointment_info['cancel_before_timestamp']->format('H:i'), $appointment_info['cancel_before_timestamp']->format(get_option('date_format')));
        ?>
    </div>


    <?php
}

function get_appointment_info($appointment_id = 0) {
    if (!session_id()) {
        session_start();
    }

    global $birchpress, $birchschedule;
    $appointment_id = $appointment_id ? $appointment_id : $_SESSION['appointment_id'];
    //$_SESSION['pid'] = $_REQUEST['pid'];
    $appointment = $birchschedule->model->get($appointment_id, array(
        'base_keys' => array(),
        'meta_keys' => $birchschedule->model->get_appointment_fields()
    ));

    $appointment_info = array();
    if ($appointment) {
        $notes = get_post_meta($_SESSION['appointment1on1_id'], '_birs_appointment_notes', true);
        $appointment_info['notes'] = $notes;
        $location_id = $appointment['_birs_appointment_location'];
        $location = $birchschedule->model->get($location_id, array('keys' => array('post_title')));
        $appointment_info['location_name'] = $location ? $location['post_title'] : '';
        $appointment_info['location_id'] = $location_id;

        $service_id = $appointment['_birs_appointment_service'];
        $service = $birchschedule->model->get($service_id, array('keys' => array('post_title', '_birs_service_day_cancel_before', '_birs_service_time_cancel_before')));
        $appointment_info['service'] = $service;
        $appointment_info['service_name'] = $service ? $service['post_title'] : '';
        $appointment_info['service_id'] = $service_id;

        $staff_id = $appointment['_birs_appointment_staff'];
        $staff = $birchschedule->model->get($staff_id, array('keys' => array('post_title')));
        $appointment_info['staff_name'] = $staff ? $staff['post_title'] : '';
        $appointment_info['staff_id'] = $staff_id;

        $timestamp = $birchpress->util->get_wp_datetime($appointment['_birs_appointment_timestamp']);
        $appointment_info['timestamp'] = $timestamp;
        $appointment_info['date4picker'] = $timestamp->format(get_option('date_format'));
        $appointment_info['date'] = $timestamp->format('m/d/Y');
        $appointment_info['year'] = $timestamp->format('Y');
        $appointment_info['month'] = $timestamp->format('m');
        $appointment_info['day'] = $timestamp->format('d');
        $appointment_info['time'] = $timestamp->format(get_option('time_format'));
        $appointment_info['hour'] = $timestamp->format('H');
        $appointment_info['minute'] = $timestamp->format('i');

        $hour_cancel_sub = (float) $appointment_info['service']['_birs_service_day_cancel_before'] * 24 + (float) $appointment_info['service']['_birs_service_time_cancel_before'];
        $timestamp_cancel = clone $timestamp;
        $timestamp_cancel->sub(new DateInterval('PT' . $hour_cancel_sub . 'H'));
        $appointment_info['cancel_before_timestamp'] = $timestamp_cancel;
        $appointment_info['cancel_before'] = $timestamp_cancel->format(get_option('date_format')) . ' ' . $timestamp_cancel->format('H:i');
        $appointment_info['default'] = $birchschedule->model->mergefields->get_appointment_merge_values($appointment_id);
    }
    return $appointment_info;
}

function zoa_is_allow_cancel_appointment($appointment_id) {
    $now = new DateTime("now");
    $appointment_info = get_appointment_info($appointment_id);
    $is_allow_cancel = $now <= $appointment_info['cancel_before_timestamp'];
    return $is_allow_cancel;
}

function zoa_get_appointment_location_addrress($appointment_info) {
    $address = '';
    if (get_locale() == 'ja') {
        $address = $appointment_info['_birs_location_zip'] . '&nbsp' .
                $appointment_info['_birs_location_state'] .
                $appointment_info['_birs_location_city'] .
                $appointment_info['_birs_location_address1'] .
                $appointment_info['_birs_location_address2'];
    } else {
        '<!--if user lang is en, format is {Address2}{Address 1}, {City}, {State}, {Country}<br/>{postcode}-->';
        $address = $appointment_info['_birs_location_address2'] . ', ' .
                $appointment_info['_birs_location_address1'] . ', ' .
                $appointment_info['_birs_location_city'] . ', ' .
                $appointment_info['_birs_location_state'] . ', ' .
                WC()->countries->countries[$appointment_info['_birs_location_country']] . '<br /> ' .
                $appointment_info['_birs_location_zip'];
    }
    return $address;
}

add_filter('woocommerce_billing_fields', 'zoa_filter_address_by_locale', 100, 2);
add_filter('woocommerce_shipping_fields', 'zoa_filter_address_by_locale', 100, 2);
add_filter('woocommerce_address_to_edit', 'zoa_filter_address_by_locale', 100, 2);

function zoa_filter_address_by_locale($address, $load_address) {
    // Remove kana field if not japan language
    $locale = get_locale();
    if ($locale != 'ja') {
        $clone_address = $address;
        $aFieldRemove = array('billing_last_name_kana', 'billing_first_name_kana', 'shipping_last_name_kana', 'shipping_first_name_kana');
        foreach ($clone_address as $field_name => $new_address) {
            if (in_array($field_name, $aFieldRemove)) {
                $address[$field_name]['value'] = '....';
                $address[$field_name]['class'][] = 'hide hidden';
            }
        }
    }
    return $address;
}

add_filter('default_checkout_billing_first_name_kana', 'default_value_kana_outside_japan', 100, 2);
add_filter('default_checkout_billing_last_name_kana', 'default_value_kana_outside_japan', 100, 2);
add_filter('default_checkout_shipping_first_name_kana', 'default_value_kana_outside_japan', 100, 2);
add_filter('default_checkout_shipping_last_name_kana', 'default_value_kana_outside_japan', 100, 2);

function default_value_kana_outside_japan($value, $input) {
    $locale = get_locale();
    $aFieldRemove = array('billing_last_name_kana', 'billing_first_name_kana', 'shipping_last_name_kana', 'shipping_first_name_kana');
    if ($locale != 'ja' && in_array($input, $aFieldRemove)) {
        $value = '...';
    }
    return $value;
}

add_action('wp_ajax_get_portfolio', 'zoa_get_portfolio');
add_action('wp_ajax_nopriv_get_portfolio', 'zoa_get_portfolio');

function zoa_get_portfolio() {
    $pID = $_REQUEST['id'];
    $GLOBALS['post'] = $post = get_post($pID);
    setup_postdata($post);
    set_query_var('post', $post);
    get_template_part('single', 'portfolio');
    die;
}

/* * *
 *  removing parent theme js 
 */
add_action("wp_enqueue_scripts", "my_jquery_dequeue", 11);

function my_jquery_dequeue() {
    //wp_deregister_script('easyzoom');	
    //wp_deregister_script('zoa-custom');	
}

/* * *
 *  Woocommerce customizations
 */
include_once('woocommerce/custom.php');

add_filter('loop_shop_columns', 'zoa_four_shop_column', 1000, 1);

function zoa_four_shop_column($column) {
    return 3;
}

add_filter('woocommerce_general_settings', 'general_settings_shop_phone');

function general_settings_shop_phone($settings) {
    $key = 0;

    foreach ($settings as $values) {
        $new_settings[$key] = $values;
        $key++;

        // Inserting array just after the post code in "Store Address" section
        if ($values['id'] == 'woocommerce_store_postcode') {
            $new_settings[$key] = array(
                'title' => __('Phone Number'),
                'desc' => __('Optional phone number of your business office'),
                'id' => 'woocommerce_store_phone',
                'default' => '',
                'type' => 'text',
                'desc_tip' => true, // or false
            );
            $key++;
        }
    }
    return $new_settings;
}

/* * *
 * Appointment Cancel Email Text shortcode
 */

function booking_cancel_text() {

    $html = __('cancel_appointment_email_text', 'zoa');
    return $html;
}

add_shortcode('booking_cancel_text', 'booking_cancel_text');
/* * *
 * Store Phone no shortcode
 */

function store_phone_callback() {
    return WC_Admin_Settings::get_option('woocommerce_store_phone');
}

add_shortcode('store_phone', 'store_phone_callback');
/* * *
 * Term&Condition link shortcode
 */

function zoa_term_shortcode() {
    $term_page_id = PAGE_TERM_ID;
    $page = get_post($term_page_id);
    $html = '<a target="_blank" href="' . get_permalink($page) . '">' . $page->post_title . '</a>';
    return $html;
}

add_shortcode('term_conditions', 'zoa_term_shortcode');

function zoa_privacy_shortcode() {
    $term_page_id = PAGE_PRIVACY_ID;
    $page = get_post($term_page_id);
    $html = '<a target="_blank" href="' . get_permalink($page) . '">' . $page->post_title . '</a>';
    return $html;
}

add_shortcode('privacy_policy', 'zoa_privacy_shortcode');

add_filter('woocommerce_get_privacy_policy_text', 'zoa_woocommerce_get_privacy_policy_text', 10, 2);

function zoa_woocommerce_get_privacy_policy_text($text, $type) {
    $text = __($text, 'zoa');

    $term_page_id = PAGE_TERM_ID;
    $page_term = get_post($term_page_id);
    $html_term = '<a target="_blank" href="' . get_permalink($page_term) . '">' . $page_term->post_title . '</a>';

    $privacy_page_id = PAGE_PRIVACY_ID;
    $page_privacy = get_post($privacy_page_id);
    $html_privacy = '<a target="_blank" href="' . get_permalink($page_privacy) . '">' . $page_privacy->post_title . '</a>';

    $text = str_replace('[term_conditions]', $html_term, $text);
    $text = str_replace('[privacy_policy]', $html_privacy, $text);
    return $text;
}

/* * *
 * Store Address shortcode
 */

function store_address_callback() {
    $address = '';
    global $woocommerce;
    $CountryObj = new WC_Countries();
    $countries_array = $CountryObj->get_countries();
    $countryCode = $CountryObj->get_base_country();
    $stateCode = $CountryObj->get_base_state();
    $country_states_array = $CountryObj->get_states();
    $state = $country_states_array[$countryCode][$stateCode];
    $country = $countries_array[$countryCode];
    /* if ( function_exists('icl_object_id') ) {
      if( ICL_LANGUAGE_CODE == 'ja')
      } */
    if (get_locale() == 'ja') {
        $address .= '〒' . WC_Admin_Settings::get_option('woocommerce_store_postcode') . ' <br>
					' . $state . WC_Admin_Settings::get_option('woocommerce_store_city') . WC_Admin_Settings::get_option('woocommerce_store_address') . WC_Admin_Settings::get_option('woocommerce_store_address_2') . '';
    } else {
        $address .= WC_Admin_Settings::get_option('woocommerce_store_address_2') . ' <br>
					' . WC_Admin_Settings::get_option('woocommerce_store_address') . WC_Admin_Settings::get_option('woocommerce_store_city') . $state . $country . '<br> ' . WC_Admin_Settings::get_option('woocommerce_store_postcode') . '';
    }
    return $address;
}

add_shortcode('store_address', 'store_address_callback');


add_filter('woocommerce_account_menu_items', 'zoa_add_my_account_links');

function zoa_add_my_account_links($menu_links) {

    $logout_link = $menu_links['customer-logout'];
    unset($menu_links['customer-logout']);
    unset($menu_links['wishlist']);
    $menu_links['wishlist'] = __('My Wishlist', 'zoa');
    $menu_links['appointment'] = __('My Appointments', 'zoa');
    $menu_links['customer-logout'] = $logout_link;

    return $menu_links;
}

/**
 * Add endpoint
 */
function zoa_add_my_account_endpoint() {

    add_rewrite_endpoint('appointment', EP_ROOT | EP_PAGES);
    add_rewrite_endpoint('appointment-detail', EP_ROOT | EP_PAGES);
}

add_action('init', 'zoa_add_my_account_endpoint');

function zoa_appointment_endpoint_content() {
    get_template_part('template-parts/content', 'my-appointments');
}

add_action('woocommerce_account_appointment_endpoint', 'zoa_appointment_endpoint_content');

function zoa_appointment_detail_endpoint_content() {
    get_template_part('template-parts/content', 'appointment-detail');
}

add_action('woocommerce_account_appointment-detail_endpoint', 'zoa_appointment_detail_endpoint_content');

/**
 * Logged in user name
 * */
add_shortcode('loggedin_full_name', 'loggedin_full_name_callback');

function loggedin_full_name_callback() {
    $current_user = wp_get_current_user();
    if ($current_user) {
        return $current_user->user_firstname . ' ' . $current_user->user_lastname;
    } else {
        return '';
    }
}

add_action('wp_ajax_cancel_appointment', 'zoa_cancel_appointment');
add_action('wp_ajax_nopriv_cancel_appointment', 'zoa_cancel_appointment');

function zoa_cancel_appointment() {
    $appointment_id = $_REQUEST['appointment_id'];
    $is_allow_cancel = zoa_is_allow_cancel_appointment($appointment_id);
    if ($is_allow_cancel) {
        if (!$appointment_id) {
            $success = 0;
            $status = __('Active', 'zoa');
        } else {

            update_post_meta($appointment_id, '_birs_appointment_status', 'cancelled');
            $success = 1;
            $status = __('Cancelled', 'zoa');
        }
    } else {
        $success = 0;
        $status = __('Active', 'zoa');
    }
    $response = array('success' => $success, 'status' => $status);
    print_r(json_encode($response));
    die;
}

add_action('woocommerce_checkout_terms_and_conditions', 'zoa_wc_checkout_privacy_policy_text', 1);

function zoa_wc_checkout_privacy_policy_text() {
    remove_action('woocommerce_checkout_terms_and_conditions', 'wc_checkout_privacy_policy_text', 20);
    echo '<div class="woocommerce-privacy-policy-text form-row">';
    zoa_get_checkout_privacy_policy_text('checkout');
    echo '</div>';
}

add_action('woocommerce_register_form', 'zoa_wc_registration_privacy_policy_text', 1);

function zoa_wc_registration_privacy_policy_text() {
    remove_action('woocommerce_register_form', 'wc_registration_privacy_policy_text', 20);
    echo '<div class="woocommerce-privacy-policy-text form-row">';
    zoa_get_checkout_privacy_policy_text('registration');
    echo '</div>';
}

function zoa_get_checkout_privacy_policy_text($type) {
    ob_start();
    wc_privacy_policy_text($type);
    $policy_text = ob_get_contents();
    ob_end_clean();
    return strip_tags($policy_text);
}

function menuProduct_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'id' => null,
            ), $atts, 'menuProduct');
    $product = wc_get_product($atts['id']);

    $image = wp_get_attachment_image_src(get_post_thumbnail_id($atts['id']), 'single-post-thumbnail');
    $html = '<a href="' . get_permalink($atts['id']) . '"><div class="card" style="width: 18rem;">
					  <img class="card-img-top" src="' . $image[0] . '" alt="Card image cap">
					  <div class="card-body">
						<h3>' . $product->get_title() . '</h3>
					  </div>
					</div></a>';
    return $html;
}

add_shortcode('menuProduct', 'menuProduct_shortcode');

function get_banner_post_handler() {
    $post = get_post($_REQUEST['id']);
    include('template-parts/content-banner.php');
    wp_die();
}

add_action('wp_ajax_get_banner_post', 'get_banner_post_handler');
add_action('wp_ajax_nopriv_get_banner_post', 'get_banner_post_handler');

add_action('wp_ajax_remove_booking_photo', 'zoa_remove_booking_photo');
add_action('wp_ajax_nopriv_remove_booking_photo', 'zoa_remove_booking_photo');

function zoa_remove_booking_photo() {
    unset($_SESSION['pid']);
    unset($_SESSION['p_image']);
    echo json_encode(array('success' => 1));
    die;
}

add_shortcode('reservation-confirm', 'zoa_shortcode_reservation_confirm');

function zoa_shortcode_reservation_confirm($atts) {
    unset($_SESSION['appointment_id']);
    unset($_SESSION['appointment1on1_id']);
    unset($_SESSION['client_id']);

    if (!$_SESSION['appointment_id'])
        return '';

    ob_start();
    ?>
    <div id="reservationFormConfirm" class="form_entry">
        <div class="confirm-box">
            <?php get_booking_confirm_html(); ?>
        </div>
    </div>
    <?php
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

add_action('restrict_manage_posts', 'zoa_add_html_above_table', 50);
add_filter('parse_query', 'zoa_product_filter');

// Display dropdown
function zoa_add_html_above_table() {
    global $typenow;

    if ('product' != $typenow || !is_admin()) {
        return;
    }
    $series = get_terms(array(
        'taxonomy' => 'series',
        'hide_empty' => true,
    ));
    ?>
    <span id="series_type_filter_wrap">
        <select name="series_type_filter" id="series_type_filter">
            <option value=""><?php _e('All Series', 'zoa'); ?></option>
            <?php foreach ($series as $serie) { ?>
                <option value="<?php echo $serie->slug ?>" <?php echo isset($_REQUEST['series_type_filter']) && $_REQUEST['series_type_filter'] == $serie->slug ? 'selected' : '' ?>><?php echo $serie->name; ?></option>
            <?php } ?>
        </select>
    </span>
    <?php
}

function zoa_product_filter($query) {
    global $pagenow;
    $serie = $_GET['series_type_filter'];
    if ('product' === $_GET['post_type'] && $serie && is_admin() && $pagenow == 'edit.php') {
        $query->query_vars['tax_query'] = array(
            array(
                'taxonomy' => 'series',
                'field' => 'slug',
                'terms' => array($serie)
            )
        );
    }
}

function get_exclude_cat_footer_navigation_press($exclude_press = false) {
    $categoryTerms = get_terms(array(
        'taxonomy' => 'category',
        'hide_empty' => false,
    ));
    $excludeTerm = array();
    foreach ($categoryTerms as $categoryTerm) {
        if ($exclude_press) {
            if ($categoryTerm->slug == 'press') {
                $excludeTerm[] = $categoryTerm->term_id;
            }
        } else {
            if ($categoryTerm->slug != 'press') {
                $excludeTerm[] = $categoryTerm->term_id;
            }
        }
    }
    return $excludeTerm;
}

/**
 * rewrite news post
 * @param string $post_link
 * @param number $id
 * @return string $post_link
 */
function zoa_news_post_link($post_link, $id = 0) {
    $post = get_post($id);
    if (is_object($post) && $post->post_type == 'post') {
        $terms = wp_get_object_terms($post->ID, 'category');
        foreach ($terms as $term) {
            if (in_array($term->slug, array('info', 'events'))) {
                $post_link = str_replace('%category%', 'news/%category%', $post_link);
                return $post_link;
                break;
            }
        }
    }
    return $post_link;
}

add_filter('pre_post_link', 'zoa_news_post_link', 1, 3);


/* Override item meta data */
if (!function_exists('wc_display_item_meta')) {

    /**
     * Display item meta data.
     *
     * @since  3.0.0
     * @param  WC_Order_Item $item Order Item.
     * @param  array         $args Arguments.
     * @return string|void
     */
    function wc_display_item_meta($item, $args = array()) {
        $strings = array();
        $html = '';
        $args = wp_parse_args($args, array(
            'before' => '<div class="product__attribute-meta"><div class="mini-product__item mini-product__attribute">',
            'after' => '</div></div>',
            'separator' => '</div><div class="mini-product__item mini-product__attribute">',
            'echo' => true,
            'autop' => false,
        ));

        foreach ($item->get_formatted_meta_data() as $meta_id => $meta) {
            $value = isset($args['autop']) && $args['autop'] ? wp_kses_post($meta->display_value) : wp_kses_post(make_clickable(trim($meta->display_value)));
            $strings[] = '<span class="label">' . wp_kses_post($meta->display_key) . ': </span><span class="value">' . $value . '</span>';
        }

        if ($strings) {
            $html = $args['before'] . implode($args['separator'], $strings) . $args['after'];
        }

        $html = apply_filters('woocommerce_display_item_meta', $html, $item, $args);

        if ($args['echo']) {
            echo $html; // WPCS: XSS ok.
        } else {
            return $html;
        }
    }

}
/* show sku in wishlist */
add_filter("woocommerce_in_cartproduct_obj_title", "wdm_test", 10, 2);

function wdm_test($product_title, $product) {
    if (is_a($product, "WC_Product_Variation")) {
        $parent_id = $product->get_parent_id();
        $parent = get_product($parent_id);
        $product_test = get_product($product->variation_id);
        $product_title = $parent->name;
        $product_jatitle = get_the_subtitle($parent_id);
        $attributes = $product->get_attributes();

        $html = '<div class="mini-product__item mini-product__name-ja small-text"><a href="' . esc_url(get_permalink(apply_filters('woocommerce_in_cart_product', $parent->id))) . '">' . $product_jatitle . '</a></div>' .
                '<div class="mini-product__item mini-product__name p5">
			<a href="' . esc_url(get_permalink(apply_filters('woocommerce_in_cart_product', $parent->id))) . '">
				' . $product_title . '
			</a>
         </div>';

        foreach ($attributes as $attribute_key => $attribute_value) {
            $display_key = wc_attribute_label($attribute_key, $product);
            $display_value = $attribute_value;

            if (taxonomy_exists($attribute_key)) {
                $term = get_term_by('slug', $attribute_value, $attribute_key);
                if (!is_wp_error($term) && is_object($term) && $term->name) {
                    $display_value = $term->name;
                }
            }
            $html .= '<div class="mini-product__item mini-product__attribute">
						<span class="label variation-color">' . $display_key . ':</span>
						<span class="value variation-color">' . $display_value . '</span>
					</div>';
        }

        $html .= '<p class="mini-product__item mini-product__id light-copy">SKU #' . $product_test->get_sku() . '</p>';
        return $html;
    } elseif (is_a($product, "WC_Product")) {
        $product_test = new WC_Product($product->id);

        return '<div class="mini-product__item mini-product__name-ja small-text"><a href="' . esc_url(get_permalink(apply_filters('woocommerce_in_cart_product', $product->id))) . '">' . get_post_meta($product->id, '_custom_product_text_field', true) . '</a></div>' .
                '<div class="mini-product__item mini-product__name p5">
			<a href="' . esc_url(get_permalink(apply_filters('woocommerce_in_cart_product', $product->id))) . '">
				' . $product_title . '
			</a>
         </div>' .
                '<p class="mini-product__item mini-product__id light-copy">SKU #' . $product_test->get_sku() . '</p>';
    } else {
        return $product_title;
    }
}

function insertAtSpecificIndex($array = [], $item = [], $position = 0) {
    $previous_items = array_slice($array, 0, $position, true);
    $next_items = array_slice($array, $position, NULL, true);
    return $previous_items + $item + $next_items;
}

add_filter('manage_edit-product_columns', 'zoa_manage_product_columns', 1000, 1);

function zoa_manage_product_columns($columns) {
    $columns = (is_array($columns)) ? $columns : array();
    $columns['product_tag'] = __('Series', 'zoa');
    $product_tag_pos = array_search('product_tag', array_keys($columns));
    unset($columns['product_tag']);
    $columns = insertAtSpecificIndex($columns, array('product_series' => __('Series', 'zoa')), $product_tag_pos);
    return $columns;
}

function zoa_modify_product_column($column, $postid) {
    if ($column == 'product_series') {
        $terms = get_the_terms($postid, 'series');
        $series = array();
        if (!empty($terms)) {
            foreach ($terms as $term) {
                $series[] = '<a href="' . site_url('/wp-admin/edit.php?post_type=product&series_type_filter=arabella') . '">' . $term->name . '</a>';
            }
        }
        echo implode(',', $series);
    }
    return $column;
}

add_filter('manage_product_posts_custom_column', 'zoa_modify_product_column', 1000, 2);

function get_taxonomy_hierarchy($taxonomy, $args = array()) {
    $taxonomy = is_array($taxonomy) ? array_shift($taxonomy) : $taxonomy;
    $terms = get_terms($taxonomy, $args);
    $children = array();
    foreach ($terms as $term) {
        $args['parent'] = $term->term_id;
        $term->children = get_taxonomy_hierarchy($taxonomy, $args);
        $children[$term->term_id] = $term;
    }
    return $children;
}

function renderPortfolioCategories($is_mobile = false) {
    $args = array(
        'orderby' => 'Date',
        'order' => 'desc',
        'hide_empty' => true,
        'parent' => 0
    );

    $hierarchy = get_taxonomy_hierarchy('portfolio_category', $args);
    if (count($hierarchy) > 0) {
        foreach ($hierarchy as $portfolio_cat) {
            renderPortfolioCategory($portfolio_cat, 0, $is_mobile);
        }
    }
}

function renderPortfolioCategory($portfolio_cat, $depth = 0, $is_mobile = false, $portfolio_parent = null) {
    if (!empty($portfolio_cat->children)) {
        if (!$is_mobile) {
            echo '<li class="depth_' . $depth . '" data-id="p_' . $portfolio_cat->term_id . '">
				<span class="filter_link cta">' . $portfolio_cat->name . '</span>
				<span class="portfolio_cat_des" style="display:none">' . $portfolio_cat->description . '</span>';
            foreach ($portfolio_cat->children as $portfolio_child) {
                echo '<span class="portfolio_cat_child_title_hidden" style="display:none">' . $portfolio_child->name . '</span>
				<span class="portfolio_cat_child_des_hidden" style="display:none">' . $portfolio_child->description . '</span>';
            }

            echo '</li>';
        } else {
            echo '<li class="depth_' . $depth . '" data-id="p_' . $portfolio_cat->term_id . '" data-value="' . $portfolio_cat->name . '">
				<span class="filter_link cta">' . $portfolio_cat->name . '</span>
				<span class="portfolio_cat_des" style="display:none">' . $portfolio_cat->description . '</span>';
            foreach ($portfolio_cat->children as $portfolio_child) {
                echo '<span class="portfolio_cat_child_title_hidden" style="display:none">' . $portfolio_child->name . '</span>
				<span class="portfolio_cat_child_des_hidden" style="display:none">' . $portfolio_child->description . '</span>';
            }

            echo '</li>';
        }
        $depth++;
        foreach ($portfolio_cat->children as $portfolio_child) {
            renderPortfolioCategory($portfolio_child, $depth, $is_mobile, $portfolio_cat);
        }
    } else {
        if (!$is_mobile) {
            echo '<li class="depth_' . $depth . '" data-id="p_' . $portfolio_cat->term_id . '">
				<span class="filter_link cta">' . $portfolio_cat->name . '</span>
				<span class="portfolio_cat_des" style="display:none">' . $portfolio_cat->description . '</span>';
            if ($portfolio_parent) {
                echo '<span class="portfolio_cat_parent_title_hidden" style="display:none">' . $portfolio_parent->name . '</span>
				<span class="portfolio_cat_parent_des_hidden" style="display:none">' . $portfolio_parent->description . '</span>';
            }

            echo '</li>';
        } else {
            echo '<li class="depth_' . $depth . '" data-id="p_' . $portfolio_cat->term_id . '" data-value="' . $portfolio_cat->name . '">
				<span class="filter_link cta">' . $portfolio_cat->name . '</span>
				<span class="portfolio_cat_des" style="display:none">' . $portfolio_cat->description . '</span>';
            if ($portfolio_parent) {
                echo '<span class="portfolio_cat_parent_title_hidden" style="display:none">' . $portfolio_parent->name . '</span>
				<span class="portfolio_cat_parent_des_hidden" style="display:none">' . $portfolio_parent->description . '</span>';
            }

            echo '</li>';
        }
    }
}

add_filter('woocommerce_placeholder_img_src', 'zoa_woocommerce_placeholder_img_src', 100, 1);

function zoa_woocommerce_placeholder_img_src($image_url) {
    return get_stylesheet_directory_uri() . '/images/no_image.jpg';
}

add_filter('document_title_parts', 'zoa_wp_title', 10, 1);

function zoa_wp_title($title_parts) {
    global $wp;
    $request = explode('/', $wp->request);
    if ($request[0] == 'my-account') {
        if (isset($request[2]) && $request[2] == 'billing') {
            $title_parts['title'] = __("Edit Billing Address", 'zoa');
        } elseif (isset($request[2]) && $request[2] == 'shipping') {
            $title_parts['title'] = __("Edit Shipping Address", 'zoa');
        }
    }

    return $title_parts;
}

add_action('woocommerce_after_single_product_summary', 'zoa_woocommerce_before_shop_loop');
// add_action('woocommerce_before_shop_loop', 'zoa_woocommerce_before_shop_loop');
add_action('woocommerce_before_shop_loop_item', 'zoa_woocommerce_before_shop_loop');

function zoa_woocommerce_before_shop_loop() {
    # die(current_filter());
    // hide parent theme panel
    remove_action('woocommerce_before_shop_loop_item_title', 'zoa_wrap_product_image', 10);
    // add new panel
    add_action('woocommerce_before_shop_loop_item_title', 'zoa_wrap_product_image_override', 10);
    // hide parent theme product title
    remove_action('woocommerce_shop_loop_item_title', 'zoa_template_loop_product_title', 10);
    // add new panel
    add_action('woocommerce_shop_loop_item_title', 'woo_template_loop_product_title', 10);
    // remove add to cart button box
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
}

function woo_template_loop_product_title() {
    echo '<a href="' . get_permalink() . '" class="c-product-item_link"></a>';
    echo '<h2 class="woocommerce-loop-product__title"><span class="product_title">' . get_the_title() . '</span></h2>';
}

function zoa_wrap_product_image_override($size = 'woocommerce_thumbnail', $args = array()) {
    global $product;

    $image_size = apply_filters('single_product_archive_thumbnail_size', $size);

    $gallery = $product->get_gallery_image_ids();

    if ($product) {
        ?>
        <div class="product-image-wrapper">
            <?php
            /* PRODUCT IMAGE */
            // open tag <a>
            woocommerce_template_loop_product_link_open();
            echo zoo_get_product_thumbnail();

            /* HOVER IMAHE */
            if (!empty($gallery)) {
                $hover = wp_get_attachment_image_src($gallery[0], $image_size);
                ?>
                <span class="hover-product-image" style="background-image: url(<?php echo esc_url($hover[0]); ?>);"></span>
                <?php
            }
            // close tag </a>
            woocommerce_template_loop_product_link_close();
            ?>

            <?php
            /* LOOP ACTION */
            $loop_action_classes = 'loop-action';
            $quick_action = get_theme_mod('quick_action', 'false');
            if ($quick_action) {
                $loop_action_classes .= ' loop-action--visible-on-mobile';
            }
            ?>
            <div class="<?php echo esc_attr($loop_action_classes); ?>">
                <?php /* SHOW IN QUICK VIEW BTN */ ?>
                <span data-pid="<?php echo esc_attr($product->get_id()); ?>" class="product-quick-view-btn zoa-icon-quick-view"></span>
                <?php
                /* ADD TO WISHLIST BUTTON */
                echo do_shortcode("[ti_wishlists_addtowishlist loop=yes]");
                # echo class_exists( 'YITH_WCWL' ) ? do_shortcode( '[yith_wcwl_add_to_wishlist]' ) : '';

                /* ADD TO CART BUTTON */
                if ($product) {
                    $defaults = array(
                        'quantity' => 1,
                        'class' => implode(' ', array_filter(array(
                            'zoa-add-to-cart-btn',
                            'button',
                            'product_type_' . $product->get_type(),
                            $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                            $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '',
                        ))),
                        'attributes' => array(
                            'data-product_id' => $product->get_id(),
                            'data-product_sku' => $product->get_sku(),
                            'aria-label' => $product->add_to_cart_description()
                        ),
                    );

                    $args = apply_filters('woocommerce_loop_add_to_cart_args', wp_parse_args($args, $defaults), $product);

                    echo sprintf('<a href="%s" data-quantity="%s" class="%s" %s>%s</a>', esc_url($product->add_to_cart_url()), esc_attr(isset($args['quantity']) ? $args['quantity'] : 1 ), esc_attr(isset($args['class']) ? $args['class'] : 'button' ), isset($args['attributes']) ? wc_implode_html_attributes($args['attributes']) : '', esc_html($product->add_to_cart_text()
                    ));
                }
                ?>
            </div>

            <?php /* PRODUCT LABEL */ ?>
            <?php echo zoa_product_label($product); ?>
        </div>
        <?php
    }
}

//add_filter('woo_variation_gallery_image_inner_html' , 'woo_variation_gallery_image_inner_html_callback',10,2);

function woo_variation_gallery_image_inner_html_callback($inner_html, $attachment_id) {

    $dom = new DOMDocument;
    $dom->loadHTML($inner_html);
    $imgs = $dom->getElementsByTagName('img');
    foreach ($imgs as $img) {

        if (strpos($img->getAttribute('class'), 'attachment-woocommerce_single') !== false) {
            $img->setAttribute('class', $img->getAttribute('class') . ' woo-variation-gallery-trigger');
        } else if (!$img->hasAttribute('class')) {
            $img->addAttribute('class', 'woo-variation-gallery-trigger');
        }
    }

    $inner_html = $dom->saveHTML();

    return $inner_html;
}

if (!function_exists('get_field')) {

    function get_field() {
        
    }

}

function zoa_wpml_object_id($page_id, $type) {
    if ('page' == $type && $page_id == tinv_get_option('page', 'wishlist')) {
        if (!is_page($page_id)) {
            $page_id = wc_get_page_id('myaccount');
        }
    }

    return $page_id;
}

// replace wishlist page id with myaccount page id when necessary
add_filter('tinvwl_addtowishlist_return_ajax', 'zoa_addtowishlist_return_ajax', 20);
add_filter('tinvwl_addtowishlist_dialog_box', 'zoa_addtowishlist_return_ajax', 20);
#tinvwl_addtowishlist_dialog_box

function zoa_addtowishlist_return_ajax($data) {
    #$data['msg'] = str_replace($data['wishlist_url'], tinv_url_wishlist_default(), $data['msg']);
    $data['wishlist_url'] = tinv_url_wishlist_default();
    if (!empty($data['redirect'])) {
        $data['redirect'] = $data['wishlist_url'];
    }

    return $data;
}

// replace wishlist page id with myaccount page id when necessary
add_action('wp_loaded', 'zoa_filter_wpml_object_id', 10);

function zoa_filter_wpml_object_id() {
    add_filter('wpml_object_id', 'zoa_wpml_object_id', 10, 2);
}

// reverse the change of wishlist page id with myaccount page id
add_action('wp', 'zoa_unfilter_wpml_object_id', 10);

function zoa_unfilter_wpml_object_id() {
    remove_filter('wpml_object_id', 'zoa_wpml_object_id', 10, 2);
}

// add myaccount endpoint for wishlist
add_action('init', 'zoa_wishlist_endpoint');

function zoa_wishlist_endpoint() {
    add_rewrite_endpoint('wishlist', EP_ROOT | EP_PAGES);
}

// add wishlist to myaccount menu
add_filter('woocommerce_account_menu_items', 'zoa_woocommerce_wishlist_menu_items');

function zoa_woocommerce_wishlist_menu_items($items) {
    $items['wishlist'] = $items['my-wishlist'];
    unset($items['my-wishlist']);
    return $items;
}

// render wishlist page content
add_action('woocommerce_account_wishlist_endpoint', 'zoa_wishlist_endpoint_content');

function zoa_wishlist_endpoint_content() {
    echo do_shortcode('[ti_wishlistsview]');
}

add_filter('tinvwl_wishlist_item_add_to_cart', 'zoa_wishlist_item_add_to_cart', 10, 3);

function zoa_wishlist_item_add_to_cart($text, $wl_product, $_product) {
    global $product;
    // store global product data.
    $_product_tmp = $product;
    // override global product data.
    $product = $_product;
    if (apply_filters('tinvwl_product_add_to_cart_need_redirect', false, $product, $product->get_permalink(), $wl_product) && in_array(( version_compare(WC_VERSION, '3.0.0', '<') ? $product->product_type : $product->get_type()), array(
                'variable',
                'variable-subscription'
            ))) {

        $text = $product->add_to_cart_text();
        $text = 'Select Options';
    }

    return $text;
}

#add_filter( 'woocommerce_product_add_to_cart_text' , 'zoa_woocommerce_product_add_to_cart_text' );

function zoa_woocommerce_product_add_to_cart_text() {
    global $product;
    $product_type = $product->product_type;

    switch ($product_type) {
        case 'external':
            return __('View Item', 'woocommerce');
            break;
        case 'grouped':
            return __('View Group', 'woocommerce');
            break;
        case 'simple':
            return __('Add to Cart', 'woocommerce');
            break;
        case 'variable':
            return __('Select Options', 'woocommerce');
            break;
        default:
            return __('Read more', 'woocommerce');
    }
}

add_filter('wp_redirect', 'zoa_modify_specific_wp_redirect', 100, 2);

function zoa_modify_specific_wp_redirect($location, $status) {
    if ($_POST['action'] == 'save_account_details') {
        $location = site_url('my-account/edit-account/');
    }
    return $location;
}

function zoa_woocommerce_checkout($atts = array()) {
    $wraper = array(
        'class' => 'woocommerce woocommerce_thanks_wrapper row flex-justify-between',
        'before' => null,
        'after' => null,
    );
    return WC_Shortcodes::shortcode_wrapper(array('WC_Shortcode_Checkout', 'output'), $atts, $wraper);
}

add_action('wp', 'zoa_change_checkout_shortcode');

function zoa_change_checkout_shortcode() {
    global $wp;

    if (isset($wp->query_vars['order-received']) && $wp->query_vars['order-received']) {
        remove_shortcode('woocommerce_checkout');
        add_shortcode('woocommerce_checkout', 'zoa_woocommerce_checkout');
    }
}

add_filter('manage_edit-birs_appointment_columns', 'zoa_manage_birs_appointment_columns', 1000, 1);

function zoa_manage_birs_appointment_columns($columns) {
    unset($columns['title']);
    unset($columns['date']);
    $columns['id'] = __('Appointment Number', 'zoa');
    $columns['customer_name'] = __('Customer Name', 'zoa');
    $columns['customer_phone'] = __('Phone', 'zoa');
    $columns['customer_email'] = __('Email', 'zoa');
    $columns['booked_date'] = __('Booked Date', 'zoa');
    $columns['date'] = __('Date', 'zoa');
    return $columns;
}

function zoa_modify_birs_appointment_column($column, $postid) {
    global $birchschedule;
    $appointment = $birchschedule->model->mergefields->get_appointment_merge_values($postid);

    $args = array(
        'meta_key' => '_birs_appointment_id',
        'meta_value' => $postid,
        'post_status' => 'publish',
        'post_type' => 'birs_appointment1on1',
        'posts_per_page' => 1
    );
    $appointment1on1 = get_posts($args);
    $client_id = get_post_meta($appointment1on1[0]->ID, '_birs_client_id', true);

    $appointment = $birchschedule->model->get($postid, array(
        'base_keys' => array(),
        'meta_keys' => $birchschedule->model->get_appointment_fields()
    ));

    if ($column == 'id') {
        echo '<strong><a href="' . get_edit_post_link($postid) . '">' . $postid . '</a></strong>';
        echo '
			<div class="row-actions">
				<span class="edit">
					<a href="' . get_edit_post_link($postid) . '" >編集</a>
					|
				</span>
				<span class="trash">
					<a href="' . get_delete_post_link($postid, '', true) . '" class="submitdelete" aria-label="">ゴミ箱へ移動</a>
					|
				</span>
			</div>
		';
    } elseif ($column == 'customer_name') {
// 		echo '<a target="_blank" href="'. site_url('wp-admin/post.php?post='. $client_id .'&action=edit') .'">';
        echo get_post_meta($client_id, '_birs_client_name_last', true) . get_post_meta($client_id, '_birs_client_name_first', true);
        echo ' (' . get_post_meta($client_id, 'name_kana_last_name', true) . get_post_meta($client_id, 'name_kana_first_name', true) . ')';
// 		echo '</a>';
    } elseif ($column == 'customer_phone') {
        echo get_post_meta($client_id, '_birs_client_phone', true);
    } elseif ($column == 'customer_email') {
        echo get_post_meta($client_id, '_birs_client_email', true);
    } elseif ($column == 'booked_date') {
        echo $appointment['_birs_appointment_datetime'];
    }
    return $column;
}

add_filter('manage_birs_appointment_posts_custom_column', 'zoa_modify_birs_appointment_column', 1000, 2);


add_filter('woocommerce_localisation_address_formats', 'elsey_woocommerce_localisation_address_formats', 1000);

function elsey_woocommerce_localisation_address_formats($formats) {
    $format_string = "{last_name} {first_name}\n{kananame}\n{company}\n〒{postcode}\n{state}{city}{address_1}\n{address_2}";
    $formats['JP'] = $formats['default'] = $format_string;
    return $formats;
}

add_filter('woocommerce_formatted_address_replacements', 'look_woocommerce_formatted_address_replacements', 10000, 2);

function look_woocommerce_formatted_address_replacements($fields, $args) {
    $fields['{kananame}'] = $args['kananame'];
    return $fields;
}

add_filter('woocommerce_order_formatted_shipping_address', 'look_woocommerce_order_formatted_shipping_address', 10000, 2);

function look_woocommerce_order_formatted_shipping_address($args, $order) {
    $args['kananame'] = $order->shipping_last_name_kana . $order->shipping_first_name_kana;
    $args['country'] = $args['country'] ?: 'JP';
    return $args;
}

add_filter('woocommerce_order_formatted_billing_address', 'look_woocommerce_order_formatted_billing_address', 10000, 2);

function look_woocommerce_order_formatted_billing_address($args, $order) {
    $args['kananame'] = $order->billing_last_name_kana . $order->billing_first_name_kana;
    $args['country'] = $args['country'] ?: 'JP';
    return $args;
}

// change BACS fields
//original fields from plugins/woocommerce/includes/gateways/bacs/class-wc-gateway-bacs.php

add_filter('woocommerce_bacs_account_fields', 'custom_bacs_fields');

function custom_bacs_fields() {
    global $wpdb;
    $account_details = get_option('woocommerce_bacs_accounts', array(
        array(
            'account_name' => get_option('account_name'),
            'account_number' => get_option('account_number'),
            'sort_code' => get_option('sort_code'),
            'bank_name' => get_option('bank_name'),
            'iban' => get_option('iban'),
            'bic' => get_option('bic')
        )
            )
    );


    $account_fields = array(
        'bank_name' => array(
            'label' => __('Bank Name', 'zoa'),
            'value' => $account_details[0]['bank_name']
        ),
        'branch_name' => array(
            'label' => __('Branch name', 'zoa'),
            'value' => $account_details[0]['sort_code']
        ),
        'account_type' => array(
            'label' => __('Account Type', 'zoa'),
            'value' => $account_details[0]['iban']
        ),
        'account_number' => array(
            'label' => __('Account Number', 'zoa'),
            'value' => $account_details[0]['account_number']
        ),
        'account_name' => array(
            'label' => __('Account Name', 'zoa'),
            'value' => $account_details[0]['account_name']
        )
    );

    return $account_fields;
}

add_action('wp_ajax_customer_cancel_order', 'customer_cancel_order');
add_action('wp_ajax_nopriv_customer_cancel_order', 'customer_cancel_order');

function customer_cancel_order() {
    $response = array();
    $order_id = $_POST['order_id'];
    $order = wc_get_order($order_id);
    $statuses = wc_get_order_statuses();

    if (isOrderAllowCancel($order)) {
        // Cancel order
        $response['success'] = 1;
        $response['status'] = $statuses['wc-cancelled'];
        $order->update_status('cancelled');
    } else {
        $response['success'] = 0;
    }
    echo json_encode($response);
    die;
}

function isOrderAllowCancel($order) {
    if ($order->status == 'cancelled')
        return false;

    $today = date('Y-m-d', current_time('timestamp'));
    $hourNow = date('H', current_time('timestamp'));
    $date_created = $order->date_created->date('Y-m-d');

    $oToday = new DateTime($today);
    $oDateCreated = new DateTime($date_created);
    $dateDiff = $oToday->diff($oDateCreated);
    $nuber_date_diff = $dateDiff->d;

    if ($nuber_date_diff > 1) {
        $isAllow = false;
    } elseif ($nuber_date_diff == 1) {
        if ($hourNow <= 12) {
            // If <= 12 PM, allow
            $isAllow = true;
        } else {
            // If >= 12PM, not allow
            $isAllow = false;
        }
    } else {
        $isAllow = true;
    }
    return $isAllow;
}

add_action('woocommerce_before_shipping_calculator', 'zoa_woocommerce_before_shipping_calculator');
add_action('woocommerce_review_order_after_shipping', 'zoa_woocommerce_before_shipping_calculator');

function zoa_woocommerce_before_shipping_calculator() {
    if (calculateShippingFeeWithDeliveryDate()) {
        $num_cart_product = count(getShippingPackageByDeliverDate());
        $shipping_delivery_option = $_SESSION['shipping_delivery_option'];
        $selected_option_1 = !$shipping_delivery_option ? 'checked' : ($shipping_delivery_option == 1 ? 'checked' : '');
        $selected_option_2 = $shipping_delivery_option == 2 ? 'checked' : '';
        echo '<div class="choose_shipping_delivery_option_wraper order__summary__row">
			<div><input type="radio" id="shipping_delivery_option_1" name="shipping_delivery_option" value="1" ' . $selected_option_1 . '/><label for="shipping_delivery_option_1" class="label">' . __('Ship together', 'zoa') . '</label></div>
			<div><input id="shipping_delivery_option_2" type="radio" name="shipping_delivery_option" value="2" ' . $selected_option_2 . '/><label for="shipping_delivery_option_2" class="label">' . __('Ship according to completion date', 'zoa') . '(' . vsprintf(__('%s pkg.', 'zoa'), $num_cart_product) . ')' . '</label></div> 
		</div>';
    }
}

add_action('wp_ajax_select_shipping_delivery_option', 'ajax_select_shipping_delivery_option');
add_action('wp_ajax_nopriv_select_shipping_delivery_option', 'ajax_select_shipping_delivery_option');

function ajax_select_shipping_delivery_option() {
    $shipping_delivery_option = $_POST['shipping_delivery_option'];
    $_SESSION['shipping_delivery_option'] = $shipping_delivery_option;

    $packages = WC()->cart->get_shipping_packages();
    foreach ($packages as $package_key => $package) {
        $session_key = 'shipping_for_package_' . $package_key;
        WC()->session->destroy_session($session_key);
    }
    $response = array('success' => 1);
    echo json_encode($response);
    die;
}

add_filter('woocommerce_package_rates', 'zoa_calculate_shipping_costs_with_delivery_date', 10, 2);

function zoa_calculate_shipping_costs_with_delivery_date($rates, $package) {
    if (is_admin() && !defined('DOING_AJAX'))
        return $rates;

    // Calculate to get new shipping cost in rates
    calculateShippingFeeWithDeliveryDate(true, $rates);

    return $rates;
}

function getShippingPackageByDeliverDate($order = null) {
    $delivery_dates = array();
    if ($order) {
        $line_items = $order->get_items(apply_filters('woocommerce_admin_order_item_types', 'line_item'));
        foreach ($line_items as $item) {
            $product_id = $item->get_product_id();
            $delivery_date = trim(get_post_meta($product_id, 'deliver_date', true));
            $has_delivery_date = $delivery_date ? $delivery_date : 0;
            $delivery_dates[$has_delivery_date] = $has_delivery_date;
        }
    } else {
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
            $delivery_date = trim(get_post_meta($product_id, 'deliver_date', true));
            $has_delivery_date = $delivery_date ? 1 : 0;
            $delivery_dates[$has_delivery_date] = $has_delivery_date;
        }
    }
    return $delivery_dates;
}

function calculateShippingFeeWithDeliveryDate($is_calculate = false, &$rates = array(), $order = null) {
    $delivery_dates = getShippingPackageByDeliverDate($order);

    // If cart has both product with empty delivery date + not empty delivery date
    // Show options to choose one or separate package, then calculate price
    if (count($delivery_dates) >= 2) {
        if (!$is_calculate) {
            return true;
        } else {
            $shipping_delivery_option = $_SESSION['shipping_delivery_option'];
            if (!$shipping_delivery_option || $shipping_delivery_option == 1) {
                return $rates;
            }

            $num_cart_product = count($delivery_dates);
            // Make price multiple
            foreach ($rates as $rate_key => $rate) {
                $rates[$rate_key]->cost = $rates[$rate_key]->cost * $num_cart_product;
            }
            return $rates;
        }
    }
    $_SESSION['shipping_delivery_option'] = NULL;
    unset($_SESSION['shipping_delivery_option']);
    return false;
}

add_action('woocommerce_thankyou', 'zoa_thank_you', 10, 1);

function zoa_thank_you($order_id) {
    if (isset($_SESSION['shipping_delivery_option'])) {
        update_post_meta($order_id, 'shipping_delivery_option', $_SESSION['shipping_delivery_option']);
        unset($_SESSION['shipping_delivery_option']);
    }
}

add_action("add_meta_boxes", "zoa_add_custom_order_detail_meta_box");

function zoa_add_custom_order_detail_meta_box($postType) {
    if ($postType == 'shop_order') {
        add_meta_box("order-deliver-option-meta-box", __('Deliver Option', 'zoa'), "meta_box_deliver_option_markup", "shop_order", "side");
    }
}

function meta_box_deliver_option_markup($post) {
    $rates = array();
    $order = wc_get_order($post->ID);
    if (calculateShippingFeeWithDeliveryDate(false, $rates, $order))
        ; {
        $shipping_delivery_option = get_post_meta($order->get_id(), 'shipping_delivery_option', true);

        $selected_option_1 = !$shipping_delivery_option ? 'checked' : ($shipping_delivery_option == 1 ? 'checked' : '');
        $selected_option_2 = $shipping_delivery_option == 2 ? 'checked' : '';

        $num_cart_product = count(getShippingPackageByDeliverDate($order));
        wp_nonce_field(basename(__FILE__), "meta-box-nonce");
        echo '<div class="deliver_option_wraper">';
        echo '<div><input type="radio" id="shipping_delivery_option_1" name="shipping_delivery_option" value="1" ' . $selected_option_1 . '/><label for="shipping_delivery_option_1" class="label">' . __('Ship together', 'zoa') . '</label></div>
			  <div><input id="shipping_delivery_option_2" type="radio" name="shipping_delivery_option" value="2" ' . $selected_option_2 . '/><label for="shipping_delivery_option_2" class="label">' . __('Ship according to completion date', 'zoa') . '(' . vsprintf(__('%1s pkg.'), $num_cart_product) . ')</label></div>';
        echo '</div>';
    }
}

add_action("save_post", "zoa_save_custom_order_detail_meta_box", 10, 3);

function zoa_save_custom_order_detail_meta_box($post_id, $post, $update) {
    if (isset($_POST['shipping_delivery_option'])) {
        update_post_meta($post_id, 'shipping_delivery_option', $_POST['shipping_delivery_option']);
    }
}

add_filter('haet_mail_use_template', 'zoa_haet_mail_use_template', 10, 2);

function zoa_haet_mail_use_template($use_template, $mail_data) {
    if (strpos($mail_data['headers'], 'reservation=true') !== false) {
        $use_template = false;
    }
    return $use_template;
}

add_filter('body_class', 'zoa_body_class', 10, 1);

function zoa_body_class($classes) {
    $classes[] = get_locale();
    return $classes;
}

function getCartGiftCardData() {
    $giftCardData = array();
    foreach (WC()->cart->get_cart() as $cart) {
        if (isset($cart['tinvwl_formdata'])) {
            $giftCardData = $cart['tinvwl_formdata'];
        }
    }
    return $giftCardData;
}

function isHideShippingByMailGiftCard() {
    $giftCardData = array();
    foreach (WC()->cart->get_cart() as $cart) {
        if (isset($cart['tinvwl_formdata'])) {
            $giftCardData[$cart['tinvwl_formdata']['mwb_wgm_send_giftcard']] = $cart['tinvwl_formdata']['mwb_wgm_send_giftcard'];
        } else {
            return false;
        }
    }
    return count($giftCardData) == 1 && end($giftCardData) == 'Mail to recipient';
}

/* WOOF OVERWRITE FUNCTION */
/* Filter WOOF */
/* if (function_exists('woof_print_tax')) {
  //if (!function_exists('woof_print_tax')) {

  function woof_print_tax($taxonomies, $tax_slug, $terms, $exclude_tax_key, $taxonomies_info, $additional_taxes, $woof_settings, $args, $counter) {

  global $WOOF;

  if ($exclude_tax_key == $tax_slug) {
  //$terms = apply_filters('woof_exclude_tax_key', $terms);
  if (empty($terms)) {
  return;
  }
  }

  //***

  if (!woof_only($tax_slug, 'taxonomy')) {
  return;
  }

  //***


  $args['taxonomy_info'] = $taxonomies_info[$tax_slug];
  $args['tax_slug'] = $tax_slug;
  $args['terms'] = $terms;
  $args['all_terms_hierarchy'] = $taxonomies[$tax_slug];
  $args['additional_taxes'] = $additional_taxes;

  //***
  $woof_container_styles = "";
  if ($woof_settings['tax_type'][$tax_slug] == 'radio' OR $woof_settings['tax_type'][$tax_slug] == 'checkbox') {
  if ($WOOF->settings['tax_block_height'][$tax_slug] > 0) {
  $woof_container_styles = "max-height:{$WOOF->settings['tax_block_height'][$tax_slug]}px; overflow-y: auto;";
  }
  }
  //***
  //https://wordpress.org/support/topic/adding-classes-woof_container-div
  $primax_class = sanitize_key(WOOF_HELPER::wpml_translate($taxonomies_info[$tax_slug]));
  ?>
  <div data-css-class="woof_container_<?php echo $tax_slug ?>" class="woof_container woof_container_<?php echo $woof_settings['tax_type'][$tax_slug] ?> woof_container_<?php echo $tax_slug ?> woof_container_<?php echo $counter ?> woof_container_<?php echo $primax_class ?>">
  <div class="woof_container_overlay_item"></div>
  <div class="woof_container_inner woof_container_inner_<?php echo $primax_class ?> toggle-wrap">
  <?php
  $css_classes = "woof_block_html_items toggle__content";
  $show_toggle = 0;
  if (isset($WOOF->settings['show_toggle_button'][$tax_slug])) {
  $show_toggle = (int) $WOOF->settings['show_toggle_button'][$tax_slug];
  }
  //***
  $search_query = $WOOF->get_request_data();
  $block_is_closed = true;
  if (in_array($tax_slug, array_keys($search_query))) {
  $block_is_closed = false;
  }
  if ($show_toggle === 1 AND ! in_array($tax_slug, array_keys($search_query))) {
  $css_classes .= " woof_closed_block";
  }

  if ($show_toggle === 2 AND ! in_array($tax_slug, array_keys($search_query))) {
  $block_is_closed = false;
  }

  if (in_array($show_toggle, array(1, 2))) {
  $block_is_closed = apply_filters('woof_block_toggle_state', $block_is_closed);
  if($block_is_closed){
  $css_classes .= " woof_closed_block";
  }else{
  $css_classes = str_replace('woof_closed_block', '', $css_classes);
  }
  }
  //***
  switch ($woof_settings['tax_type'][$tax_slug]) {
  case 'checkbox':
  if ($WOOF->settings['show_title_label'][$tax_slug]) {
  ?>
  <div class="toggle__link flex-justify-between"><h4 class="toggle__name"><?php echo WOOF_HELPER::wpml_translate($taxonomies_info[$tax_slug]) ?><?php WOOF_HELPER::draw_title_toggle($show_toggle, $block_is_closed); ?></h4></div>
  <?php
  }

  if (!empty($woof_container_styles)) {
  $css_classes .= " woof_section_scrolled";
  }
  ?>
  <div class="<?php echo $css_classes ?>" <?php if (!empty($woof_container_styles)): ?>style="<?php echo $woof_container_styles ?>"<?php endif; ?>>
  <?php
  echo $WOOF->render_html(WOOF_PATH . 'views/html_types/checkbox.php', $args);
  ?>
  </div>
  <?php
  break;
  case 'select':
  if ($WOOF->settings['show_title_label'][$tax_slug]) {
  ?>
  <div class="toggle__link flex-justify-between"><h4 class="toggle__name"><?php echo WOOF_HELPER::wpml_translate($taxonomies_info[$tax_slug]) ?><?php WOOF_HELPER::draw_title_toggle($show_toggle, $block_is_closed); ?></h4></div>
  <?php
  }
  ?>
  <div class="<?php echo $css_classes ?>">
  <?php
  echo $WOOF->render_html(WOOF_PATH . 'views/html_types/select.php', $args);
  ?>
  </div>
  <?php
  break;
  case 'mselect':
  if ($WOOF->settings['show_title_label'][$tax_slug]) {
  ?>
  <div class="toggle__link flex-justify-between"><h4 class="toggle__name"><?php echo WOOF_HELPER::wpml_translate($taxonomies_info[$tax_slug]) ?><?php WOOF_HELPER::draw_title_toggle($show_toggle, $block_is_closed); ?></h4></div>
  <?php
  }
  ?>
  <div class="<?php echo $css_classes ?>">
  <?php
  echo $WOOF->render_html(WOOF_PATH . 'views/html_types/mselect.php', $args);
  ?>
  </div>
  <?php
  break;

  default:
  if ($WOOF->settings['show_title_label'][$tax_slug]) {
  $title = WOOF_HELPER::wpml_translate($taxonomies_info[$tax_slug]);
  $title = explode('^', $title); //for hierarchy drop-down and any future manipulations
  if (isset($title[1])) {
  $title = $title[1];
  } else {
  $title = $title[0];
  }
  ?>
  <div class="toggle__link flex-justify-between"><h4 class="toggle__name"><?php echo $title ?><?php WOOF_HELPER::draw_title_toggle($show_toggle, $block_is_closed); ?></h4></div>
  <?php
  }

  if (!empty($woof_container_styles)) {
  $css_classes .= " woof_section_scrolled";
  }
  ?>

  <div class="<?php echo $css_classes ?>" <?php if (!empty($woof_container_styles)): ?>style="<?php echo $woof_container_styles ?>"<?php endif; ?>>
  <?php
  if (!empty(WOOF_EXT::$includes['taxonomy_type_objects'])) {
  $is_custom = false;
  foreach (WOOF_EXT::$includes['taxonomy_type_objects'] as $obj) {
  if ($obj->html_type == $woof_settings['tax_type'][$tax_slug]) {
  $is_custom = true;
  $args['woof_settings'] = $woof_settings;
  $args['taxonomies_info'] = $taxonomies_info;
  echo $WOOF->render_html($obj->get_html_type_view(), $args);
  break;
  }
  }


  if (!$is_custom) {
  echo $WOOF->render_html(WOOF_PATH . 'views/html_types/radio.php', $args);
  }
  } else {
  echo $WOOF->render_html(WOOF_PATH . 'views/html_types/radio.php', $args);
  }
  ?>

  </div>
  <?php
  break;
  }
  ?>

  <input type="hidden" name="woof_t_<?php echo $tax_slug ?>" value="<?php echo $taxonomies_info[$tax_slug]->labels->name ?>" /><!-- for red button search nav panel -->

  </div>
  </div>
  <?php
  }

  }//function_exists('woof_print_tax') */
//WOOF filter logic AND
/* add_filter('woof_main_query_tax_relations', 'my_woof_main_query_tax_relations');

  function my_woof_main_query_tax_relations()
  {
  return array(
  //'product_cat' => 'AND'
  //'pa_color' => 'AND'
  );
  } */
if (!function_exists('woof_show_btn')) {

    function woof_show_btn($autosubmit = 1, $ajax_redraw = 0) {
        ?>
        <div class="woof_container woof_submit_search_form_container">
            <div class="toggle-wrap">
                <div class="toggle__link flex-justify-between toggle__link--no-indicator">
                    <h3 class="toggle__name"><?php esc_html_e('Filter by', 'zoa'); ?></h3>
                    <?php
                    global $WOOF;
                    if ($WOOF->is_isset_in_request_data($WOOF->get_swoof_search_slug())): global $woof_link;
                        ?>

                        <?php
                        $woof_reset_btn_txt = get_option('woof_reset_btn_txt', '');
                        if (empty($woof_reset_btn_txt)) {
                            $woof_reset_btn_txt = __('Reset', 'woocommerce-products-filter');
                        }
                        $woof_reset_btn_txt = WOOF_HELPER::wpml_translate(null, $woof_reset_btn_txt);
                        ?>

                        <?php if ($woof_reset_btn_txt != 'none'): ?>
                            <button class="woof_reset_search_form refinement__clear" data-link="<?php echo $woof_link ?>"><?php echo $woof_reset_btn_txt ?></button>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (!$autosubmit OR $ajax_redraw): ?>
                        <?php
                        $woof_filter_btn_txt = get_option('woof_filter_btn_txt', '');
                        if (empty($woof_filter_btn_txt)) {
                            $woof_filter_btn_txt = __('Filter', 'woocommerce-products-filter');
                        }

                        $woof_filter_btn_txt = WOOF_HELPER::wpml_translate(null, $woof_filter_btn_txt);
                        ?>
                        <button style="float: left;" class="button woof_submit_search_form"><?php echo $woof_filter_btn_txt ?></button>
                    <?php endif; ?>
                </div><!--/.toggle__link-->
            </div><!--/.togggle-wrap-->


        </div>
        <button id="closeRefinement" class="display--mid-only button"><?php esc_html_e('Close Filter', 'zoa'); ?></button>
        <?php
    }

}

add_filter('woocommerce_mail_content', 'zoa_woocommerce_mail_content', 1000, 1);

function zoa_woocommerce_mail_content($message) {
    $message = str_replace('<h2 class="wc-bacs-bank-details-heading">', '<h2 class="wc-bacs-bank-details-heading" style="text-align: center; font-style: normal;">', $message);
    $message = str_replace('<h3 class="wc-bacs-bank-details-account-name">', '<h3 class="wc-bacs-bank-details-account-name" style="text-align: center; font-style: normal; display: none;">', $message);
    $message = str_replace('<ul class="wc-bacs-bank-details order_details bacs_details">', '<ul class="wc-bacs-bank-details order_details bacs_details" style="text-align: center; list-style-type: none; padding-left: 0;">', $message);

    // check is order invoice email content
    if (strpos($message, 'checkout/order-pay') !== false) {
        $message .= '<div>order invoice page</div>';
        // Get order id by order pay url
        $matches = array();
        preg_match('/checkout\/order-pay\/([0-9]+)\//', $message, $matches);
        if (!empty($matches)) {
            $order_id = $matches[1];
            $order = new WC_Order($order_id);
            if ('bacs' == $order->get_payment_method()) {
                $message = str_replace('href="' . site_url() . '/checkout/order-pay', ' style="display: none;" href="' . site_url() . '/checkout/order-pay', $message);
            }
        }
    }
    if (strpos($message, 'order_tracking_template') !== false) {
        preg_match('/order_tracking_template_(\d+)/', $message, $matches);
        if (!empty($matches) && isset($matches[1])) {
            $order_id = $matches[1];
            $tracking_template = zoa_order_tracking_email_template(array(), $order_id);
            $message = str_replace('{' . $matches[0] . '}', $tracking_template, $message);
        }
    }
    return $message;
}

add_action('woocommerce_email_order_details', 'zoa_woocommerce_email_order_details', 1, 4);

function zoa_woocommerce_email_order_details($order, $sent_to_admin = false, $plain_text = false, $email = '') {
    // Add tracking in complete email
    if ('completed' == $order->status && $email->template_html == 'emails/customer-completed-order.php') {
        $tracking_number = get_post_meta($order->id, '_aftership_tracking_number', true);
        $tracking_provider = get_post_meta($order->id, '_aftership_tracking_provider_name', true);

        if ($tracking_provider) {
            echo '<div style="margin: 10px 0;">' . __('Your order was shipped via ', 'wc_aftership') . $tracking_provider . '</div>';
        }
        if ($tracking_number) {
            echo '<div style="margin: 10px 0;">' . __('Tracking number is ', 'wc_aftership') . $tracking_number . '</div>';
        }
    }
}

add_filter('woocommerce_customer_meta_fields', 'zoa_woocommerce_customer_meta_fields', 10000, 1);

function zoa_woocommerce_customer_meta_fields($show_fields) {
    $order_fields = array(
        "last_name",
        "first_name",
        "last_name_kana",
        "first_name_kana",
        "email",
        "phone",
        "country",
        "postcode",
        "state",
        "city",
        "address_1",
        "address_2"
    );
    $show_fields['billing']['fields']['billing_last_name_kana'] = $show_fields['shipping']['fields']['shipping_last_name_kana'] = array(
        'label' => __('Last Name Kana', 'zoa'),
        'required' => true,
        'class' => array('form-row-first')
    );
    $show_fields['billing']['fields']['billing_first_name_kana'] = $show_fields['shipping']['fields']['shipping_first_name_kana'] = array(
        'label' => __('First Name Kana', 'zoa'),
    );
    $billing_fields = $show_fields['billing']['fields'];
    $shipping_fields = $show_fields['shipping']['fields'];
    $show_fields['billing']['fields'] = $show_fields['shipping']['fields'] = array();
    foreach ($order_fields as $order_field) {
        if (isset($billing_fields['billing_' . $order_field])) {
            $show_fields['billing']['fields']['billing_' . $order_field] = $billing_fields['billing_' . $order_field];
        }

        if (isset($shipping_fields['shipping_' . $order_field])) {
            $show_fields['shipping']['fields']['shipping_' . $order_field] = $shipping_fields['shipping_' . $order_field];
        }
    }

    return $show_fields;
}

add_filter('woocommerce_validate_postcode', 'zoa_woocommerce_validate_postcode', 1000, 3);

function zoa_woocommerce_validate_postcode($valid, $postcode, $country) {
    switch ($country) {
        case 'JP':
            $valid = (bool) preg_match('/^([0-9]{7})$/', $postcode);
            break;
    }
    return $valid;
}

add_filter('woocommerce_format_postcode', 'zoa_woocommerce_format_postcode', 1000, 2);

function zoa_woocommerce_format_postcode($postcode, $country) {
    switch ($country) {
        case 'JP':
            $postcode = str_replace('-', '', $postcode);
            break;
    }
    return $postcode;
}

add_action('admin_init', 'post_limit_general_section');

function post_limit_general_section() {
    add_settings_section(
            'post_limit_settings_section', // Section ID
            __('Header Latest Banner Limit'), // Section Title
            'post_limit_section_options_callback', // Callback
            'general' // What Page?  This makes the section show up on the General Settings Page
    );

    add_settings_field(// Option 1
            'post_limit_banner_header', // Option ID
            __('Limit Banner Number', 'zoa'), 'post_limit_textbox_callback', // !important - This is where the args go!
            'general', // Page it will be displayed (General Settings)
            'post_limit_settings_section', // Name of our section
            array(// The $args
        'post_limit_banner_header' // Should match Option ID
            )
    );

    register_setting('general', 'post_limit_banner_header', 'esc_attr');
}

function post_limit_section_options_callback() { // Section Callback
}

function post_limit_textbox_callback($args) {  // Textbox Callback
    $option = get_option($args[0]);
    echo '<input type="text" id="' . $args[0] . '" name="' . $args[0] . '" value="' . $option . '" />';
}

function get_google_map_url_by_address() {
    $countries_obj = new WC_Countries();
    $country_state = get_option('woocommerce_default_country');
    $aCountry_state = explode(':', $country_state);
    $country_code = $aCountry_state[0];
    $country_name = WC()->countries->countries[$country_code];
    $states_list = $countries_obj->get_states($country_code);
    $state = $states_list[$aCountry_state[1]];
    $city = get_option('woocommerce_store_city');
    $postcode = get_option('woocommerce_store_postcode');
    $address1 = get_option('woocommerce_store_address');
    $address2 = get_option('woocommerce_store_address_2');

    $full_address = $country_name . '+' . $postcode . '+' . $state . '+' . $city . '+ ' . $address1 . $address2;
    $google_map_url = 'https://www.google.co.jp/maps/place/?hl=ja&q=' . $full_address;
    return $google_map_url;
}

function zoa_get_store_map($atts = array()) {
    return get_google_map_url_by_address();
}

add_shortcode('get_store_map', 'zoa_get_store_map');

//add new column for Appointments list in woocommerce-customers-manager plugin
function ch_manage_customers_columns($columns) {
    $columns['appointments_list'] = __('Appointments list', 'woocommerce-customers-manager');
    return $columns;
}

add_filter('manage_customers_columns', 'ch_manage_customers_columns');

function ch_manage_customers_custom_column($abs = null, $column_name, $item) {
    if ($column_name == 'appointments_list') {
        return '<a class="" target="_blank" href="' . admin_url('admin.php?page=appointments-list&user_id=' . $item) . '"><span class="wp-menu-image dashicons-before dashicons-calendar-alt"></span></a>';
    } else {
        return $item[$column_name];
    }
}

add_filter('manage_customers_custom_column', 'ch_manage_customers_custom_column', 10, 3);
//end

add_shortcode('order_tracking_template', 'zoa_order_tracking_email_template');

function zoa_order_tracking_email_template($atts, $order_id = null) {
    global $order;
    $atts = shortcode_atts(array(
        'order_id' => null,
            ), $atts);

// 	8414
    $order_id = $order_id ? $order_id : ($order ? $order->get_id() : $atts['order_id']);
    if ($order_id && class_exists('WCST_Tracking_info_displayer')) {
        ob_start();
        $order = wc_get_order($order_id);
        $tracking = new WCST_Tracking_info_displayer();
        $tracking->email_shipping_details($order);
        $tracking_html = ob_get_contents();
        ob_end_clean();
        return $tracking_html;
    }
}

function ch_rename_plugin_menus() {
    global $menu;

    // Define your changes here
    $updates = array(
        "YITH" => array(
            'name' => __('Other Tools', 'zoa')
        )
    );

    foreach ($menu as $k => $props) {

        // Check for new values
        $new_values = ( isset($updates[$props[0]]) ) ? $updates[$props[0]] : false;
        if (!$new_values)
            continue;

        // Change menu name
        $menu[$k][0] = $new_values['name'];
    }
}

add_action('admin_init', 'ch_rename_plugin_menus');

//process to save portfolio image for booked
function ch_portfolio_image_for_booked() {
    if (isset($_GET['pid'])) {
        if (is_page('reservation-form')) {
            $_SESSION['pid'] = $_GET['pid'];

            if (isset($_REQUEST['serie_index']) && $_REQUEST['serie_index']) {
                $images_series = get_field('images_series', $_GET['pid']);
                foreach ($images_series as $loop_series_index => $images_serie) {
                    if ($_REQUEST['serie_index'] == $loop_series_index + 1) {
                        $pt_categorized_images = $images_serie['images'];
                        foreach ($pt_categorized_images as $pt_categorized_image) {
                            $image = $pt_categorized_image['sizes']['woocommerce_thumbnail'];
                            $_SESSION['image_id'] = $pt_categorized_image['ID'];
                        }
                        break;
                    }
                }
            } else {
                $image = get_the_post_thumbnail_url($_GET['pid']);
            }
            if ($image == '') {
                $image = get_stylesheet_directory_uri() . '/images/pf_sample_thum.jpg';
            }
            $_SESSION['p_image'] = $image;
        } else {
            unset($_SESSION['pid']);
            unset($_SESSION['p_image']);
            unset($_SESSION['image_id']);
        }
    } else {
        unset($_SESSION['pid']);
        unset($_SESSION['p_image']);
        unset($_SESSION['image_id']);
    }
}

add_action('template_redirect', 'ch_portfolio_image_for_booked');

//show only 1st and 2nd sub menus booked for user is shop manager role
add_action('admin_menu', 'ch_booked_menu_user_shop_manager_role', 999);

function ch_booked_menu_user_shop_manager_role() {
    $user = wp_get_current_user();
    if (in_array('shop_manager', (array) $user->roles)) {
        remove_submenu_page('booked-appointments', 'booked-pending');
        remove_submenu_page('booked-appointments', 'edit-tags.php?taxonomy=booked_custom_calendars');
        remove_submenu_page('booked-appointments', 'booked-settings');
        remove_submenu_page('booked-appointments', 'booked-welcome');
        remove_submenu_page('booked-appointments', 'booked-feeds');
        remove_submenu_page('booked-appointments', 'booked_wc_payment_options');
        remove_submenu_page('booked-appointments', 'booked-install-addons');
    }
}

//remove rating sort
function ch_woocommerce_catalog_orderby($orderby) {
    unset($orderby["rating"]);
    return $orderby;
}

add_filter("woocommerce_catalog_orderby", "ch_woocommerce_catalog_orderby", 20);

function ch_ss_hidden_fields() {
    if (is_user_logged_in() && is_page('reservation-form')) {
        if (isset($_SESSION['ss_date'])) {
            echo '<input type="hidden" id="ss-date" value="' . $_SESSION['ss_date'] . '" /> ';
            unset($_SESSION['ss_date']);
        }
        if (isset($_SESSION['ss_title'])) {
            echo '<input type="hidden" id="ss-title" value="' . $_SESSION['ss_title'] . '" /> ';
            unset($_SESSION['ss_title']);
        }
        if (isset($_SESSION['ss_timeslot'])) {
            echo '<input type="hidden" id="ss-timeslot" value="' . $_SESSION['ss_timeslot'] . '" /> ';
            unset($_SESSION['ss_timeslot']);
        }
        if (isset($_SESSION['ss_calendar_id'])) {
            echo '<input type="hidden" id="ss-calendar-id" value="' . $_SESSION['ss_calendar_id'] . '" /> ';
            unset($_SESSION['ss_calendar_id']);
        }
    }
}

add_action('wp_footer', 'ch_ss_hidden_fields');

if (!function_exists('pll_current_language'))
{
	function pll_current_language(){
		return $_REQUEST['lang'];
	}
}

add_filter( 'wppb_register_pre_form_message', 'elsey_wppb_register_pre_form_message', 999, 1 );
function elsey_wppb_register_pre_form_message($message)
{
	return '';
}