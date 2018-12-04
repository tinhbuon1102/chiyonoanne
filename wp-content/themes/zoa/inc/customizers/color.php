<?php

/* ADD COLOR SECTION
***************************************************/
zoa_Kirki::add_section(
	'color', array(
		'title'      => esc_attr__( 'Colors', 'zoa' ),
		'priority'   => 1,
	)
);



/* PRIMARY COLOR
***************************************************/
zoa_Kirki::add_field(
	'zoa', array(
		'type'      => 'color',
		'label'     => esc_attr__( 'Primary color', 'zoa' ),
		'settings'  => 'primary_color',
		'section'   => 'color',
		'default'   => '#dd2a2a',
		'transport' => 'auto',
		'output'    => array(
			/*! COLOR
			------------------------------------------------->*/
			array(
				'element' => array(
					'a:not(.woocommerce-loop-product__link):hover',
					'.menu-woo-action:hover .menu-woo-user',
					'.woocommerce-mini-cart__total .amount',
					'.read-more-link',
					'.wd-pro-flash-sale .price ins',
				),
				'property' => 'color',
			),

			/*! BACKGROUND
			------------------------------------------------->*/
			array(
				'element' => array(
					'#sidebar-menu-content .theme-primary-menu a:before',
					'#sidebar-menu-content .theme-primary-menu a:hover:before',
					'.menu-woo-cart span',
					'.shop-cart-count',
					'.sidebar-action-cart',
					'.loop-action .product-quick-view-btn:hover',
					'.loop-action a:hover',
					'.loop-action .yith-wcwl-add-to-wishlist a:hover',
					'.cart-sidebar-content .woocommerce-mini-cart__buttons .checkout',
					'#page-loader #nprogress .bar,
					.scroll-to-top',
				),
				'property' => 'background-color',
			),

			/*! BORDER
			------------------------------------------------->*/
			array(
				'element' => array(
					'.blog-read-more:hover',
					'.entry-categories a',
					'.swatch.selected:before',
					'.not-found .back-to-home',
				),
				'property' => 'border-color',
			),

			array(
				'element' => array(
					'.blog-read-more:hover',
					'.woocommerce-tabs .tabs li.active a',
				),
				'property' => 'border-bottom-color',
			),

			array(
				'element' => array(
					'.is-loading-effect:before',
				),
				'property' => 'border-top-color',
			),
		),
	)
);
