<?php
class wf_woocommerce_shipping_pro_method extends WC_Shipping_Method {

	/**
	 * Rules per page to be displayed.
	 */
	public static $rules_per_page = 25;

	function __construct() {
		$plugin_config = wf_plugin_configuration();
		$this->id					= $plugin_config['id']; 
		$this->method_title				= __( $plugin_config['method_title'], 'wf_woocommerce_shipping_pro' );
		$this->method_description			= __( $plugin_config['method_description'], 'wf_woocommerce_shipping_pro' );
				
		$this->init_settings();
		$this->init_form_fields();
		$this->title 					= isset($this->settings['title']) ? $this->settings['title'] : $this->method_title;
		$this->enabled 					= isset($this->settings['enabled']) ? $this->settings['enabled'] : 'no';

		$this->tax_status	   			= isset($this->settings['tax_status']) ? $this->settings['tax_status'] :  '';
		$this->rate_matrix	   			= isset($this->settings['rate_matrix']) ? $this->settings['rate_matrix'] : array();
		
		//get_option fill default if doesn't exist. other settings also can change to this
		$this->debug 					= $this->get_option('debug');				
		$this->displayed_columns			= ! empty($this->settings['displayed_columns']) ? $this->settings['displayed_columns'] : array();
		$calculation_mode	   			= $this->get_option('calculation_mode');
		$calc_min_max	   				= $this->get_option('calc_min_max');
		$calc_per		  			= $this->get_option('calc_per');
		
		if(!empty($calc_per) && !empty($calc_min_max)) {
			$calculation_mode = $calc_per."_".$calc_min_max;
		}
		$this->remove_free_text	   		= $this->get_option('remove_free_text');
		$this->and_logic 				= $this->get_option('and_logic')== 'yes' ? true : false;
		$this->strict_and_logic			= $this->get_option('strict_and_logic')== 'yes' ? true : false;
		
		$this->multiselect_act_class	=	'multiselect';
		$this->drop_down_style	=	'chosen_select ';			
		
		$this->drop_down_style.=	$this->multiselect_act_class;
		
		if ( ! class_exists( 'WF_Calc_Strategy' ) )
			include_once 'abstract-wf-calc-strategy.php' ;

		$this->calc_mode_strategy =  WF_Calc_Strategy::get_calc_mode($calculation_mode,$this->rate_matrix);
		$this->row_selection_choice = $this->calc_mode_strategy->wf_row_selection_choice();
		
		$this->col_count = count($this->displayed_columns)+1;
		
		
		$this->shipping_classes =WC()->shipping->get_shipping_classes();
		
		$this->product_category  = get_terms( 'product_cat', array('fields' => 'id=>name'));

		//variable to get decimal separator used.
		$separator = stripslashes( get_option( 'woocommerce_price_decimal_sep' ) );
		$this->decimal_separator = $separator ? $separator : '.';
				
		// Save settings in admin
		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
		
		if($this->remove_free_text == 'yes'){
			add_filter( 'woocommerce_cart_shipping_method_full_label', array( $this, 'wf_remove_local_pickup_free_label'), 10, 2 );
		}
	}

	function wf_debug($error_message){
		if($this->debug == 'yes')
			wc_add_notice( $error_message, 'notice' );
	}
	
	public function generate_activate_box_html() {
		ob_start();
		$plugin_name = 'shippingpro';
		include( dirname(__FILE__).'/../includes/wf_api_manager/html/html-wf-activation-window.php' ); //without diname() getting error due to some resone.
		return ob_get_clean();
	}
		
		function get_inner_sections()
		{
			if(empty($_REQUEST['inner_section'])) $_REQUEST['inner_section']='default';
			$current_tab=$_REQUEST['inner_section'];
			?>
		 <ul class="nav-tab-wrapper">
			 <li></li>
			 <li>
				 <a href="admin.php?page=wc-settings&tab=shipping&section=wf_woocommerce_shipping_pro" class="nav-tab <?php echo $current_tab=="default"?"nav-tab-active":''; ?>">Shipping Rules</a>
			 </li>
			 <li>
				 <a href="admin.php?page=wc-settings&tab=shipping&section=wf_woocommerce_shipping_pro&inner_section=settings" class="nav-tab <?php echo $current_tab=="settings"?"nav-tab-active":''; ?>">Settings</a>
			 </li>
			 <li>
				 <a href="admin.php?page=wc-settings&tab=shipping&section=wf_woocommerce_shipping_pro&inner_section=import_export" class="nav-tab <?php echo $current_tab=="import_export"?"nav-tab-active":''; ?>">Import/Export</a>
			 </li>
			 <li>
				 <a href="admin.php?page=wc-settings&tab=shipping&section=wf_woocommerce_shipping_pro&inner_section=license" class="nav-tab <?php echo $current_tab=="license"?"nav-tab-active":''; ?>">License</a>
			 </li>
			 
		 </ul>
			<?php
		}

		function admin_options() {
		 ?>
		 <h2><?php _e($this->method_title,'woocommerce'); ?></h2>
		 <?php echo $this->method_description; ?>
		 </br></br> 
		 <?php  $this->get_inner_sections(); ?>
		 <div class="clear"></div>
		 </br>
		 <table class="form-table">
		 <?php $this->generate_settings_html(); ?>
		 </table> <?php
		 }
		/**
		 * Initialise Settings Form Fields
		 */
		 function init_form_fields() {
			 if(empty($_REQUEST['inner_section'])) $_REQUEST['inner_section']='default';
			 switch ($_REQUEST['inner_section']) {
				 case 'default':
								$this->form_fields  = array(
															'rate_matrix' => array('type' => 'rate_matrix'),									
															);					
					 break;
				 case 'settings':
					 echo "<style>select {	padding: 0px !important;}</style>";
					$this->form_fields  = $this->get_settings_page_fields();					
					 break;
				 case 'import_export':
								
								$this->form_fields  = array(
									'impexpbtn' => array('type' => 'import_export_btn'),   
							   );					
					 break;
				 case 'license':
								$this->form_fields  = array(
															'licence'  => array(
																		 'type'			=> 'activate_box'
																 ),									
							   );					
					 break;
				 
			 }

		} // End init_form_fields()
		function generate_import_export_btn_html()
		{
			?>
			<style> .woocommerce-save-button{display:none !important;} </style>
			<a href="<?php echo admin_url( 'admin.php?import=shippingpro_rate_matrix_csv' ); ?>" class="button"><?php _e( 'Import CSV', 'wf_woocommerce_shipping_pro' ); ?></a>
			<span style=" text-align: center; vertical-align: middle; ">	  <?php _e( 'From here you can import rules from a csv file.', 'wf_woocommerce_shipping_pro' ); ?></span></br></br>
			<a href="<?php echo admin_url( 'admin.php?wf_export_shippingpro_rate_matrix_csv=true' ); ?>" class="button"><?php _e( 'Export CSV', 'wf_woocommerce_shipping_pro' ); ?></a>
			<span style=" text-align: center; vertical-align: middle; ">	  <?php _e( 'Download all rules in csv format', 'wf_woocommerce_shipping_pro' ); ?></span></br></br>

		 <?php
		}
	function get_settings_page_fields(){
		///////code to support old fields value after Updated UI////////
		$mode=$this->get_option('calculation_mode');
		$tmp=explode('_',$mode);
		$min_max=array_pop($tmp);
		$min_max=array_pop($tmp);
		if(empty($min_max)) 
			$min_max='max';
		$calc_min_max=$min_max.'_cost';
		$tmp=implode('_',$tmp);
		$calc_per=$tmp;
		$calc_min_max=$this->get_option('calc_min_max',$calc_min_max);
		$calc_per=$this->get_option('calc_per',$calc_per);
		///////////////////////////////////////////////////////////////////////
		return array(
			'enabled'	=> array(
				'title'   => __( 'Enable/Disable', 'wf_woocommerce_shipping_pro' ),
				'type'	=> 'checkbox',
				'label'   => __( 'Enable this shipping method', 'wf_woocommerce_shipping_pro' ),
				'default' => 'no',
			),						
			'title'	  => array(
				'title'	   => __( 'Method Title', 'wf_woocommerce_shipping_pro' ),
				'type'		=> 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'wf_woocommerce_shipping_pro' ),
				'default'	 => __( $this->method_title, 'wf_woocommerce_shipping_pro' ),
			),
			'and_logic'	=> array(
				'title'   	  => __( 'Calculation Logic (AND)', 'wf_woocommerce_shipping_pro' ),
				'type'		=> 'checkbox',
				'default' 	  => 'no',
				'description' => __( 'On enabling this, the calculation logic for "Shipping Class" and "Product Category" fields will follow AND logic. By default the plugin follows OR logic', 'wf_woocommerce_shipping_pro' ),
				'label'	  =>  __( 'Enable', 'wf_woocommerce_shipping_pro' ),
			),
			'strict_and_logic'	=>	array(
				'title'			=>	__( 'Strict Logic (AND)', 'wf_woocommerce_shipping_pro' ),
				'type'			=>	'checkbox',
				'default'		=>	'no',
				'description'	=>	__( 'On enabling this, the plugin will calculate rates only for the Shiping Classes and Product Categories mentioned in the rule.', 'wf_woocommerce_shipping_pro'),
				'label'			=>	__( 'Enable', 'wf_woocommerce_shipping_pro' ),
			),
			'displayed_columns' => array(
				'title'	   => __( 'Display/Hide matrix columns', 'wf_woocommerce_shipping_pro' ),
				'type'		=> 'multiselect',
				'description' => __( 'Select the columns which are used in the matrix. Please Save changes to reflect the modifications.', 'wf_woocommerce_shipping_pro' ),
				'class'	   => 'chosen_select',
				'css'		 => 'width: 450px;',
				'default'	 => array(
					'shipping_name',
					'zone_list'   ,
					'weight' , 
					'fee'   ,
					'cost' ,
					'weigh_rounding'   
				), //if change the default value here change 'settings/html-rate-marix.php'
				'options'	 => array(
					'shipping_name' => __( 'Method title', 'wf_woocommerce_shipping_pro' ),
					'method_group' => __( 'Method group', 'wf_woocommerce_shipping_pro' ),					
					'zone_list' => __( 'Zone list', 'wf_woocommerce_shipping_pro' ),					
					'country_list'	=> __( 'Country list', 'wf_woocommerce_shipping_pro' ),
					'state_list'	=> __( 'State list', 'wf_woocommerce_shipping_pro' ),
					'city'	=> __( 'Cities', 'wf_woocommerce_shipping_pro' ),
					'postal_code'	=> __( 'Postal code', 'wf_woocommerce_shipping_pro' ),
					'shipping_class'	=> __( 'Shipping class', 'wf_woocommerce_shipping_pro' ),
					'product_category'	=> __( 'Product category', 'wf_woocommerce_shipping_pro' ),
					'weight'	=> __( 'Weight', 'wf_woocommerce_shipping_pro' ),
					'item'	=> __( 'Item', 'wf_woocommerce_shipping_pro' ),
					'price'	=> __( 'Price', 'wf_woocommerce_shipping_pro' ),					
					'cost_based_on'	=> __( 'Rate Based on', 'wf_woocommerce_shipping_pro' ),
					'fee'	=> __( 'Base cost', 'wf_woocommerce_shipping_pro' ),
					'cost'	=> __( 'Cost/unit', 'wf_woocommerce_shipping_pro' ),
					'weigh_rounding'	=> __( 'Round', 'wf_woocommerce_shipping_pro' )					
				),
				'custom_attributes' => array(
						'data-placeholder' => __( 'Choose matrix columns', 'wf_woocommerce_shipping_pro' )
				)
			),
			'calc_min_max' => array(
				'title'	   => __( 'Minimum cost or Maximum cost', 'wf_woocommerce_shipping_pro' ),
				'type'		=> 'select',
				'description' => __( 'Choose maximum/minimum rate in case multiple rule.' , 'wf_woocommerce_shipping_pro' ),
				'default'	 => !empty($calc_min_max)?$calc_min_max:'max_cost',
				'options'	 => array(
					'max_cost' => __( 'Choose maximum rate in case multiple rule.', 'wf_woocommerce_shipping_pro' ),
					'min_cost' => __( 'Choose minimum rate in case multiple rule.', 'wf_woocommerce_shipping_pro' ),
				),
			),			
			'calc_per' => array(
				'title'	   => __( 'Calculation Mode', 'wf_woocommerce_shipping_pro' ),
				'type'		=> 'select',
				'description' => __( 'Per Order: Shipping calculation will be done on the entire cart together. Total weight of the cart will be used with the selected rule to calculate the shipping.  Rule will be satisfied if one of the items in the cart meets the criteria. Per Item: Shipping calculation will happen on item wise, item weight multiply with the quantity will be used with the selected rule to calculate the shipping cost for the item, all the item cost will be summed to find the final cost.' , 'wf_woocommerce_shipping_pro' ),
				'default'	 => !empty($calc_per)?$calc_per:'per_order',
				'options'	 => array(
					'per_line_item' => __( 'Calculate shipping cost per Line item.', 'wf_woocommerce_shipping_pro' ),
					'per_item' => __( 'Calculate shipping cost per item.', 'wf_woocommerce_shipping_pro' ),
					'per_order'=> __( 'Calculate shipping cost per order.', 'wf_woocommerce_shipping_pro' ),
					'per_category'=> __( 'Calculate shipping cost per category.', 'wf_woocommerce_shipping_pro' ),
					'per_shipping_class'=> __( 'Calculate shipping cost per shipping class.', 'wf_woocommerce_shipping_pro' ),
				),
			),			
			'tax_status' => array(
				'title'	   => __( 'Tax Status', 'wf_woocommerce_shipping_pro' ),
				'type'		=> 'select',
				'description' => '',
				'default'	 => 'none',
				'options'	 => array(
						'taxable' => __( 'Taxable', 'wf_woocommerce_shipping_pro' ),
						'none'	=> __( 'None', 'wf_woocommerce_shipping_pro' ),
				),
			),
			'remove_free_text'	=> array(
				'title'   => __( 'Remove Free Text', 'wf_woocommerce_shipping_pro' ),
				'type'	=> 'checkbox',
				'label'   => __( 'Remove default (Free) text from shipping label', 'wf_woocommerce_shipping_pro' ),
				'default' => 'no',
			),
			'debug'	=> array(
				'title'   => __( 'Debug', 'wf_woocommerce_shipping_pro' ),
				'type'	=> 'checkbox',
				'label'   => __( 'Debug this shipping method', 'wf_woocommerce_shipping_pro' ),
				'default' => 'no',
			),										
		);
	}
	 
	public function wf_remove_local_pickup_free_label($full_label, $method){
		if( strpos($method->id, $this->id) !== false) $full_label = str_replace(' (Free)','',$full_label);
		return $full_label;
	}
	
	function wf_hidden_matrix_column($column_name){
		return in_array($column_name,$this->displayed_columns) ? '' : 'hidecolumn';	
	}
	
	/**
	 * Validate rate_matrix fields.
	 */
	public function validate_rate_matrix_field( $key ) {
		
		$rules_per_page 			= self::$rules_per_page;
		$rate_matrix 				= ! empty($this->rate_matrix) ? $this->rate_matrix : array();		// All rate matrix before saving
		$current_page_rate_matrix	= isset( $_POST['rate_matrix'] ) ? $_POST['rate_matrix'] : array();	// Current page rate matrix being saved
		$current_page 				= ! empty($_GET['paged']) ? $_GET['paged'] : 1;						// Page number of pagination
		$new_rate_matrix 			= array();
		
		// Rate matrix of previous pages
		if( $current_page > 1 ) {
			$new_rate_matrix = array_slice( $rate_matrix, 0, ( ( $current_page - 1 ) * $rules_per_page) ) ;
		}

		$new_rate_matrix = array_merge( $new_rate_matrix, $current_page_rate_matrix);

		// Rate matrix of all the next pages
		$rate_matrix_after_current_page = array_slice( $rate_matrix, ( $current_page ) * $rules_per_page ) ;

		if( ! empty($rate_matrix_after_current_page) ) {
			$new_rate_matrix = array_merge( $new_rate_matrix, $rate_matrix_after_current_page );
		}
		
		//Register shipping method name for WPML translation.
		foreach ($new_rate_matrix as $key => $rule) {
			do_action( 'wpml_register_single_string', 'wf_woocommerce_shipping_pro', 'shipping-method-title_'.$rule['shipping_name'], $rule['shipping_name'] );
		}

		return $new_rate_matrix;
	}

	public function generate_rate_matrix_html() {
		include_once('settings/html-rate-marix.php');
	}

	function calculate_shipping( $package = array() ) {

		do_action( 'ph_woocommerce_shipping_pro_before_shipping_calculation');

		$rules = $this->wf_filter_rules( $this->wf_find_zone($package), $package['destination']['country'], $package['destination']['state'], $package['destination']['city'], $package['destination']['postcode'], $this->calc_mode_strategy->wf_find_shipping_classes($package), $this->calc_mode_strategy->wf_find_product_category($package), $package );
		$costs = $this->wf_calc_cost($rules, $package);	
		$this->wf_add_rate(apply_filters( 'wf_woocommerce_shipping_pro_shipping_costs', $costs),$package);

		do_action( 'ph_woocommerce_shipping_pro_after_shipping_calculation');
		
	}

	public function wf_find_zone($package){
		$matching_zones=array();		
		if( class_exists('WC_Shipping_Zones') ){
			$zones_obj = new WC_Shipping_Zones;
			$matches = $zones_obj::get_zone_matching_package($package);
			if( method_exists ( $matches, 'get_id' ) ){ //if WC 3.0+
				$zone_id = $matches->get_id();
			}else{
				$zone_id =  $matches->get_zone_id();
			}
			array_push( $matching_zones, $zone_id );
		}
		return $matching_zones;
	}
	
	function wf_get_weight($package_items){
		$total_weight = 0;
		foreach($package_items as $package_item){		
			$_product = $package_item['data'];
			$total_weight += apply_filters( 'wf_shipping_pro_item_weight', (float) $_product->get_weight() * $package_item['quantity'], $package_item, $package_items );
		}
		return apply_filters('ph_shipping_pro_total_weight',$total_weight, $package_items);
	}
	
	
	function wf_get_item_count($package_items){
		$total_count = 0;
		foreach($package_items as $package_item){		
			$_product = $package_item['data'];
			$total_count += apply_filters( 'wf_shipping_pro_item_quantity', $package_item['quantity'],$_product->id);			
		}
		return $total_count;
	}
	
	function wf_filter_rules( $zone, $country, $state, $city, $post_code, $shipping_classes,$product_category,$package ) {
		$selected_rules = array();
		if(sizeof($this->rate_matrix) > 0) {
			foreach($this->rate_matrix as $key => $rule ) {
				$satified_general=false;
				if( $this->wf_compare_array_rule_field($rule,'zone_list',$zone,'','')
					&& $this->wf_compare_array_rule_field($rule,'country_list',$country,'rest_world','any_country')
					&& $this->wf_compare_array_rule_field($rule,'state_list',$country.':'.strtoupper($state),'rest_country','any_state')
					&& $this->wf_compare_string_rule_field($rule,'city',$city,'','*')
					&& $this->wf_compare_post_code($rule,'postal_code',$post_code,'','*')){
						$satified_general=true;	
				}
				
				if($satified_general){
					foreach ( $this->calc_mode_strategy->wf_get_grouped_package($package) as $item_id => $values ) {
						if(	$this->wf_compare_array_rule_field($rule,'shipping_class',$shipping_classes,'rest_shipping_class','any_shipping_class',$item_id)
							&& $this->wf_compare_array_rule_field($rule,'product_category',$product_category,'rest_product_category','any_product_category',$item_id)
							&& $this->wf_compare_range_field($rule,'weight',$this->wf_get_weight($values))
							&& $this->wf_compare_range_field($rule,'item',$this->wf_get_item_count($values))
							&& $this->wf_compare_range_field($rule,'price',$this->calc_mode_strategy->wf_get_price($values)) ){
								if(!isset($rule['item_ids'])) $rule['item_ids'] = array(); 
								$rule['item_ids'][] = $item_id;
						}												
					}
					if(isset($rule['item_ids'])) $selected_rules[] = $rule;						
				}					
			}					
		}
		return $selected_rules;	 
	}
	
	function wf_compare_string_rule_field($rule, $field_name, $input_value, $const_rest, $const_any ){
		//if rule_value is null then shipping rule will be acceptable for all
		if (!empty($rule[$field_name]) && in_array($field_name,$this->displayed_columns) ){
			$rule_value = $rule[$field_name];
			$this->wf_debug("rule_value : $rule_value");
		}
		else	
			return true;
		
		if($rule_value == $const_any)
				return true;
			
		if ( ! empty( $rule_value ) ) {
			
			$cities = explode( ';', $rule_value );
			$cities = array_map( 'strtoupper', array_map( 'wc_clean', $cities ) );
			$input_value = strtoupper($input_value);

			foreach( $cities as $city ) {
				if ( $city == $input_value)
					return true;
			}
		}
		return false;
	}

	function wf_compare_post_code($rule, $field_name, $input_value, $const_rest, $const_any ){
		//if rule_value is null then shipping rule will be acceptable for all
		if (!empty($rule[$field_name]) && in_array($field_name,$this->displayed_columns) ){
			$rule_value = $rule[$field_name];
			$this->wf_debug("rule_value : $rule_value");
		}
		else	
			return true;
		
		if($rule_value == $const_any)
				return true;
			
		if ( ! empty( $rule_value ) ) {
			
			$postcodes = explode( ';', $rule_value );
			$postcodes = array_map( 'strtoupper', array_map( 'wc_clean', $postcodes ) );
			$input_value = strtoupper($input_value);

			foreach( $postcodes as $postcode ) {
				if ( strstr( $postcode, '-' ) ) {
					$this->wf_debug("$postcode - $input_value ");
					$postcode_parts = explode( '-', $postcode );
					if ( is_numeric( $postcode_parts[0] ) && is_numeric( $postcode_parts[1] ) && $postcode_parts[1] > $postcode_parts[0] ) {
						for ( $i = $postcode_parts[0]; $i <= $postcode_parts[1]; $i ++ ) {
							if ( ! $i )
								continue;

							if ( strlen( $i ) < strlen( $postcode_parts[0] ) )
								$i = str_pad( $i, strlen( $postcode_parts[0] ), "0", STR_PAD_LEFT );

							if($input_value == $i)
							{
								$this->wf_debug("$i matched $input_value ");							
								return true;
							}								
						}
					}
				}
				elseif ( strstr( $postcode, '*' ) )
				{
					$this->wf_debug("$postcode * $input_value ");
					if(preg_match('/\A'.str_replace('*', '.', $postcode).'/',$input_value))
						return true;
				}
				else {
					$this->wf_debug("$postcode == $input_value ");
					if ( $postcode == $input_value)
						return true;
				}
			}
		}
		return false;
	}

	function wf_compare_array_rule_field( $rule, $field_name, $input_value, $const_rest, $const_any, $item_id=false ){
		//if rule_value is null then shipping rule will be acceptable for all
		global $rule_value;
		if (!empty($rule[$field_name]) && in_array($field_name,$this->displayed_columns) ){
			$rule_value = $rule[$field_name];
			$this->wf_debug("rule_value : $rule_value[0]");
		}
		else	
			return true;
		
		if (is_array($rule_value) && count($rule_value) == 1){
			if($rule_value[0] == $const_rest)	
				return $this->wf_partof_rest_of_the($input_value,$field_name,$item_id,$rule);
			elseif($rule_value[0] == $const_any)
				return true;	
		}
		
		if(!is_array($input_value)){
			return in_array($input_value,$rule_value);
		}
		else{			
			if( $item_id ){
				if( isset($input_value[$item_id]) && is_array($input_value[$item_id]) ){
					if( $this->and_logic && ($field_name == 'product_category' || $field_name == 'shipping_class' ) ){
						// return $input_value[$item_id] == $rule_value; //If both arrays are equal, for strict AND logic.
						$matched_shipping_class_or_prod_cat_count 	= count(array_intersect($rule_value, $input_value[$item_id]));
						$rule_val_count								= count($rule_value);
						$input_value_count							= count($input_value[$item_id]);
						return $this->strict_and_logic ? ( $matched_shipping_class_or_prod_cat_count == $rule_val_count && $rule_val_count == $input_value_count ) : ( $matched_shipping_class_or_prod_cat_count == $rule_val_count );
					}else{
						return count( array_intersect($input_value[$item_id],$rule_value) ) > 0;
					}
				}
				else
					return false;
			}else{ //case of zone.
				return count(array_intersect($input_value,$rule_value)) > 0;
			}
		}
	}
	
	function wf_compare_range_field( $rule,$field_name, $totalweight) {
		$weight = $totalweight;
		if (!empty($rule['min_'.$field_name]) && $weight <= $rule['min_'.$field_name]) 
			return false;
		if (!empty($rule['max_'.$field_name]) && $weight > $rule['max_'.$field_name]) 
			return false;					
		return true;	
	}	

	function wf_partof_rest_of_the( $input_value,$field_name,$item_id=false ,$current_rule) {
		global $combined_rule_value;
		$combined_rule_value = array();
		if ( sizeof( $this->rate_matrix ) > 0) {
			foreach ( $this->rate_matrix as $key => $rule ) {
				if(!empty($rule[$field_name]) && ($rule['method_group'] == $current_rule['method_group']))
					$combined_rule_value = array_merge($rule[$field_name],$combined_rule_value);
				
			}					
		}
		
		if(!is_array($input_value)){
			//county not defined as part of any other rule 
			if(!in_array($input_value,$combined_rule_value))
				return true;
			return false;
		}
		else{
			//returns true if at least one product category doesn't exist combined list.
			if($item_id !== false && isset($input_value[$item_id]) && is_array($input_value[$item_id])){
				//This is a case where product with NO shipping class in the cart. 
				//This will not handle the case where multiple product in the group and some products are not 'NO Shipping Class' 
				//So finally if its NO Shipping Class case we will consider it as matching with Rest of the shipping class. 
				if(empty($input_value[$item_id]))
					return true;
				return count(array_diff($input_value[$item_id],$combined_rule_value)) > 0;
			}
				
			return false;				
		}						
	}
	
	function wf_calc_cost( $rules ,$package) {
		$cost = array();
		if ( sizeof($rules) > 0) {
			$grouped_package = $this->calc_mode_strategy->wf_get_grouped_package($package);
			foreach ( $rules as $key => $rule) {
				$method_group = isset($rule['method_group']) ? $rule['method_group'] : null;	
				$item_ids = isset($rule['item_ids']) ? $rule['item_ids'] : null;
				if(!empty($item_ids)){
					foreach($item_ids as $item_key => $item_id){
						if(empty($grouped_package[$item_id])) continue;
						$shipping_cost = $this->wf_get_rule_cost($rule,$grouped_package[$item_id]);
						if($shipping_cost !== false){
							if(isset($cost[$method_group]['cost'][$item_id])){
								if($cost[$method_group]['cost'][$item_id] > $shipping_cost && $this->row_selection_choice == 'min_cost'
								|| $cost[$method_group]['cost'][$item_id] < $shipping_cost && $this->row_selection_choice == 'max_cost'){
									 $cost[$method_group]['cost'][$item_id] = $shipping_cost;
									 $cost[$method_group]['shipping_name'] = !empty($rule['shipping_name']) ? $rule['shipping_name'] : $this->title;
								}							   
							}
							else{
								if(!isset($cost[$method_group])) {
									$cost[$method_group] = array();
									$cost[$method_group]['cost'] = array();
								}
								
								$cost[$method_group]['shipping_name'] = !empty($rule['shipping_name']) ? $rule['shipping_name'] : $this->title;
								$cost[$method_group]['cost'][$item_id] = $shipping_cost;																								
							}
						}		   
					}
				}						   
			}
		}	   
		return 	$cost;
	}	

	function wf_get_rule_cost( $rate,$grouped_package) {
		$based_on = 'weight';
		if(!empty($rate['cost_based_on'])) $based_on = $rate['cost_based_on'];
		
		if($based_on == 'price'){
			$totalweight = $this->calc_mode_strategy->wf_get_price($grouped_package);
		}
		elseif($based_on == 'item'){
			$totalweight = $this->wf_get_item_count($grouped_package);
		}
		else{
			$totalweight = $this->wf_get_weight($grouped_package);		
		}
		
		
		$weight = floatval($totalweight);
		
		if( isset($rate['min_'.$based_on]) ){
			$weight = max(0, $weight - floatval($rate['min_'.$based_on]) );
		}

		$weightStep = isset($rate['weigh_rounding']) ? floatval($rate['weigh_rounding']) : 1;

		if (trim($weightStep)) 
			$weight = floatval(ceil($weight / $weightStep) * $weightStep);

		$rate_fee   = isset($rate['fee']) ? floatval(str_replace($this->decimal_separator, '.', $rate['fee'])) : 0;
		$rate_cost  = isset($rate['cost']) ? floatval(str_replace($this->decimal_separator, '.', $rate['cost'])) : 0;
		$price = $rate_fee + $weight * $rate_cost;
		
		if ( $price !== false) return $price;
		
		return false;		
	}	
	
	function wf_check_all_item_exists($costs,$package_content){
		return count(array_intersect_key($costs,$package_content)) == count($package_content);
	}
	
	function wf_add_rate($costs,$package) {
		if ( sizeof($costs) > 0) {
			$grouped_package = $this->calc_mode_strategy->wf_get_grouped_package($package);
			foreach ($costs as $method_group => $method_cost) {
				if($this->wf_check_all_item_exists($method_cost['cost'],$grouped_package)){
					if(isset($method_cost['shipping_name']) && isset($method_cost['cost'])){
		
						$method_id = sanitize_title( $method_group . $method_cost['shipping_name'] );
						$method_id = preg_replace( '/[^A-Za-z0-9\-]/', '', $method_id ); //Omit unsupported charectors
						$this->add_rate( array(
										'id'		=> $this->id . ':' . $method_id,
										'label'		=> apply_filters( 'ph_wc_shipping_pro_rate_label', $method_cost['shipping_name'] ),
										'cost'		=> $method_cost['cost'],
										'taxes'		=> '',
										'calc_tax'	=> $this->calc_mode_strategy->wf_calc_tax()));
					}
				}								
			}
		}
	}
	
}
