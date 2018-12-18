<?php
new CSFramework_Quick_Edit( array(

	/* The below code works only when WooCommerce is installed */
	array(
		'id'        => '_wpsf_wc_metabox1',
		'post_type' => 'cspnc_news',
		'column'    => 'cspnc_news_category',
		'fields'    => array(

			array(
				'id'    => 'section_4_switcher',
				'type'  => 'switcher',
				'after' => '<br/><p>This switcher field is from products metabox.</p>',
				'title' => 'Upload A File ?',
				'label' => 'Yes, Please do it.',
			),
			array(
				'id'      => 'section4_multi_checkbox',
				'type'    => 'checkbox',
				'after'   => '<p>This checkbox field is from products metabox.</p>',
				'title'   => 'Checkbox',
				'options' => array(
					'option1' => 'Option 1',
					'option2' => 'Option 2',
					'option3' => 'Option 3',
				),
			),
		),
	),


) );