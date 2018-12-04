<?php
	
	defined( 'ABSPATH' ) or die( 'Keep Quit' );
	
	// Kalium Theme Support
	add_action( 'init', function () {
		if ( function_exists( 'kalium_woocommerce_init' ) ) {
			remove_action( 'kalium_woocommerce_single_product_images', 'kalium_woocommerce_show_product_images_custom_layout', 20 );
			remove_filter( 'woocommerce_available_variation', 'kalium_woocommerce_variation_image_handler', 10 );
		}
		
		// woo_variation_gallery_default_width
		
	}, 20 );
	
	// Re Attach Hooks
	add_action( 'wp_loaded', function () {
		
		$position = 22;
		
		// Avada Theme
		if ( class_exists( 'Avada' ) ) {
			$position = 50;
		}
		
		// Enfold Theme
		if ( defined( 'AV_FRAMEWORK_VERSION' ) ) {
			$position = 5;
		}
		
		// Attach Product Image
		if ( apply_filters( 'wvg_re_attach_template', true ) ) {
			
			add_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', $position );
			
			if ( function_exists( 'Customify' ) ) {
				remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 22 );
			}
		}
		
	}, 9 );
	
	add_filter( 'woo_variation_product_gallery_inline_style', function ( $styles ) {
		
		// eCommerce Lite Theme
		if ( class_exists( 'eCommerce_Lite_Functions' ) ) {
			$styles[ 'width' ] = '450px';
		}
		
		// Mia Theme
		if ( function_exists( 'mia_setup' ) ) {
			$styles[ 'width' ] = '500px';
		}
		
		return $styles;
	}, 8 );
	
	// Theme Wise Default Width
	add_filter( 'woo_variation_gallery_default_width', function ( $width ) {
		
		// Avada Theme
		if ( class_exists( 'Avada' ) ) {
			$width = 45;
		}
		
		// OceanWP Theme
		if ( class_exists( 'OCEANWP_Theme_Class' ) ) {
			$width = 40;
		}
		
		// Astra Theme
		if ( defined( 'ASTRA_THEME_DIR' ) ) {
			$width = 50;
		}
		
		// Be Theme
		if ( function_exists( 'mfn_opts_get' ) ) {
			$width = 100;
		}
		
		// Divi Theme
		if ( function_exists( 'et_setup_theme' ) ) {
			$width = 50;
		}
		
		// Enfold Theme
		if ( defined( 'AV_FRAMEWORK_VERSION' ) ) {
			$width = 100;
		}
		
		// Salient Theme
		if ( defined( 'NECTAR_FRAMEWORK_DIRECTORY' ) ) {
			$width = 100;
		}
		
		// Flatsome Theme
		if ( class_exists( 'Flatsome_Default' ) ) {
			$width = 100;
		}
		
		// Porto Theme
		if ( defined( 'porto_lib' ) ) {
			$width = 90;
		}
		
		// Shopisle Theme
		if ( function_exists( 'shopisle_load_sdk' ) ) {
			$width = 45;
		}
		
		// Zerif Lite Theme
		if ( function_exists( 'zerif_load_sdk' ) ) {
			$width = 50;
		}
		
		// Hestia Theme
		if ( function_exists( 'hestia_load_sdk' ) ) {
			$width = 45;
		}
		
		// Storefront Theme
		if ( function_exists( 'storefront_is_woocommerce_activated' ) ) {
			$width = 40;
		}
		
		// Shopkeeper Theme and The Hanger Theme
		if ( function_exists( 'getbowtied_theme_name' ) ) {
			$width = 100;
		}
		
		// Shophistic Lite Theme
		if ( class_exists( 'shophistic_lite_Theme' ) ) {
			$width = 100;
		}
		
		// WR Nitro Theme
		if ( class_exists( 'WR_Nitro' ) ) {
			$width = 100;
		}
		
		// Sydney Theme
		if ( function_exists( 'sydney_setup' ) ) {
			$width = 50;
		}
		
		// ColorMag Theme
		if ( function_exists( 'colormag_setup' ) ) {
			$width = 50;
		}
		
		// GeneratePress Theme
		if ( function_exists( 'generate_setup' ) ) {
			$width = 50;
		}
		
		// Kalium Theme
		if ( class_exists( 'Kalium' ) ) {
			$width = 100;
		}
		
		// Kuteshop Theme
		if ( class_exists( 'Kuteshop_Functions' ) ) {
			$width = 40;
		}
		
		// TwentySixteen Theme
		if ( function_exists( 'twentysixteen_setup' ) ) {
			$width = 45;
		}
		
		// TwentySeventeen Theme
		if ( function_exists( 'twentyseventeen_setup' ) ) {
			$width = 50;
		}
		
		// Sober Theme
		if ( function_exists( 'sober_setup' ) ) {
			$width = 40;
		}
		
		// Stockholm Theme
		if ( defined( 'QODE_FRAMEWORK_ROOT' ) ) {
			$width = 50;
		}
		
		// X Theme
		if ( function_exists( 'x_boot' ) ) {
			$width = 50;
		}
		
		// Saha Theme
		if ( function_exists( 'saha_theme_setup' ) ) {
			$width = 100;
		}
		
		// Customify Theme
		if ( function_exists( 'Customify' ) ) {
			$width = 95;
		}
		
		// Suave Theme
		if ( function_exists( 'cg_setup' ) ) {
			$width = 100;
		}
		
		return $width;
	}, 8 );
	
	add_filter( 'wvg_remove_default_template', function ( $default ) {
		
		if ( function_exists( 'saha_theme_setup' ) ) {
			return false;
		}
		
		return $default;
	} );