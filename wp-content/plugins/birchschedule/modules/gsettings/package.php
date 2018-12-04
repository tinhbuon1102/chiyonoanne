<?php

birch_ns( 'birchschedule.gsettings', function( $ns ) {

        $update_info = array();

        $ns->init = function() use( $ns ) {
            add_action( 'init', array( $ns, 'wp_init' ) );

            add_action( 'admin_init', array( $ns, 'wp_admin_init' ) );

        };

        $ns->is_module_gsettings = function( $module ) {
            return $module['module'] === 'gsettings';
        };

        $ns->wp_init = function() use ( $ns ) {
            //add_action( 'birchschedule_view_show_notice', array( $ns, 'show_update_notice' ) );

            add_filter( 'site_transient_update_plugins', array( $ns, 'get_update_info' ), 20 );

            add_filter( 'birchschedule_view_settings_get_tabs', array( $ns, 'add_tab' ) );

            add_filter( 'birchschedule_model_get_currency_code', array( $ns, 'get_option_currency' ) );

            add_filter( 'birchschedule_view_calendar_get_default_view',
                array( $ns, 'get_option_default_calendar_view' ) );
            
        };

        $ns->wp_admin_init = function() use ( $ns ) {
            register_setting( 'birchschedule_options', 'birchschedule_options', array( $ns, 'sanitize_input' ) );
            $ns->add_settings_sections();
        };

        $ns->add_tab = function( $tabs ) use ( $ns ) {
            $tabs['general'] = array(
                'title' => __( 'General', 'birchschedule' ),
                'action' => array( $ns, 'render_page' ),
                'order' => 0
            );

            return $tabs;
        };

        $ns->add_settings_sections = function() use ( $ns ) {
            add_settings_section( 'birchschedule_general', __( 'General Options', 'birchschedule' ),
                array( $ns, 'render_section_general' ), 'birchschedule_settings' );
            $ns->add_settings_fields();
        };

        $ns->add_settings_fields = function() use ( $ns ) {
            add_settings_field( 'birchschedule_timezone', __( 'Timezone' ),
                array( $ns, 'render_timezone' ), 'birchschedule_settings', 'birchschedule_general' );

            add_settings_field( 'birchschedule_date_time_format', __( 'Date Format, Time Format', 'birchschedule' ),
                array( $ns, 'render_date_time_format' ), 'birchschedule_settings', 'birchschedule_general' );

            add_settings_field( 'birchschedule_start_of_week', __( 'Week Starts On', 'birchschedule' ),
                array( $ns, 'render_start_of_week' ), 'birchschedule_settings', 'birchschedule_general' );

            add_settings_field( 'birchschedule_currency', __( 'Currency', 'birchschedule' ),
                array( $ns, 'render_currency' ), 'birchschedule_settings', 'birchschedule_general' );

            add_settings_field( 'birchschedule_default_calendar_view', __( 'Default Calendar View', 'birchschedule' ),
                array( $ns, 'render_default_calendar_view' ), 'birchschedule_settings', 'birchschedule_general' );
            
            add_settings_field( 'birchschedule_cancel_policy', __( 'Cancel Policy', 'birchschedule' ),
                array( $ns, 'render_cancel_policy' ), 'birchschedule_settings', 'birchschedule_general' );
            
            add_settings_field( 'birchschedule_store_email', __( 'Store Email', 'birchschedule' ),
                array( $ns, 'render_store_email' ), 'birchschedule_settings', 'birchschedule_general' );
            
            add_settings_field( 'birchschedule_offduty_dates', __( 'Offduty dates', 'birchschedule' ),
            		array( $ns, 'render_offduty_dates' ), 'birchschedule_settings', 'birchschedule_general' );

        };

        $ns->get_option_currency = function() use ( $ns ) {
            $options = $ns->get_options();
            return $options['currency'];
        };

        $ns->get_option_default_calendar_view = function() use ( $ns ) {
            $options = $ns->get_options();
            return $options['default_calendar_view'];
        };
        
        $ns->get_cancel_policy = function() use ( $ns ) {
            $options = $ns->get_options();
            return $options['cancel_policy'];
        };
        
        $ns->get_store_email = function() use ( $ns ) {
        	$options = $ns->get_options();
        	return $options['store_email'];
        };
        
        $ns->get_offduty_dates = function() use ( $ns ) {
        	$options = $ns->get_options();
        	return $options['offduty_dates'];
        };

        $ns->render_section_general = function() {
            echo '';
        };

        $ns->get_options = function() use ( $ns ) {
            $options = get_option( 'birchschedule_options' );
            return $options;
        };

        $ns->render_timezone = function() {
            $timezone_url = admin_url( 'options-general.php' );
            echo sprintf(
                __( "<label>Timezone settings are located <a href='%s'>here</a>.</label>", 'birchschedule' ),
                $timezone_url );
        };

        $ns->render_date_time_format = function() {
            $timezone_url = admin_url( 'options-general.php' );
            echo sprintf(
                __( "<label>Date format, time format settings are located <a href='%s'>here</a>.</label>", 'birchschedule' ),
                $timezone_url );
        };

        $ns->render_start_of_week = function() {
            $timezone_url = admin_url( 'options-general.php' );
            echo sprintf(
                __( "<label>First day of week setting is located <a href='%s'>here</a>.</label>", 'birchschedule' ),
                $timezone_url );
        };

        $ns->map_currencies = function( $currency ) {
            if ( $currency['symbol_right'] != '' ) {
                return $currency['title'] . ' (' . $currency['symbol_right'] . ')';
            } else {
                return $currency['title'] . ' (' . $currency['symbol_left'] . ')';
            }
        };

        $ns->render_currency = function() use ( $ns ) {
            global $birchpress;

            $currencies = $birchpress->util->get_currencies();
            $currencies = array_map( array( $ns, 'map_currencies' ), $currencies );
            $currency = $ns->get_option_currency();
            echo '<select id="birchschedule_currency" name="birchschedule_options[currency]">';
            $birchpress->util->render_html_options( $currencies, $currency );
            echo '</select>';
        };

        $ns->render_default_calendar_view = function() use ( $ns ) {
            global $birchpress;

            $views = $birchpress->util->get_calendar_views();
            $default_view = $ns->get_option_default_calendar_view();
            echo '<select id="birchschedule_default_calenar_view" name="birchschedule_options[default_calendar_view]">';
            $birchpress->util->render_html_options( $views, $default_view );
            echo '</select>';
        };
        
        $ns->render_cancel_policy = function() use ( $ns ) {
            $cancel_policy = $ns->get_cancel_policy();
            echo '<textarea id="birchschedule_cancel_policy" name="birchschedule_options[cancel_policy]" rows="10">'. $cancel_policy .'</textarea>';
        };
        
        $ns->render_store_email = function() use ( $ns ) {
        	$store_email = $ns->get_store_email();
        	echo '<input type="text" id="birchschedule_store_email" name="birchschedule_options[store_email]" rows="10" value="'.$store_email.'" style="width: 300px;"/>';
        };
        
        $ns->render_offduty_dates = function() use ( $ns ) {
        	$offduty_dates = get_option('offduty_dates');
        	echo '<input type="text" id="birchschedule_offduty_dates" readonly name="birchschedule_options[offduty_dates]" rows="10" style="width: 300px;"/> ';
        	?>
        	<?php if (!empty($offduty_dates)) {?>
        	<br />
        	<table class="table_offduty_dates">
				<thead>
					<tr>
						<th><?php echo __('Date', 'zoa')?></th>
						<th><?php echo __('Remove', 'zoa')?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($offduty_dates as $offduty_date) { if (!$offduty_date) continue;?>
					<tr>
						<td><?php echo $offduty_date?></td>
						<td><input type="checkbox" name="remove_offduty_date[]" value="<?php echo $offduty_date?>"/></td>
					</tr>
					<?php }?>
				</tbody>
			</table>
			<?php }?>
			<script>
		   	 setTimeout(function(){
		   		var dateToday = new Date();
			   	 jQuery('#birchschedule_offduty_dates').datepicker({
		   			minDate: dateToday,
			   	 });}, 1000) 
			</script>
			<style>
				.table_offduty_dates{background: white; margin-top: 20px; border-collapse: collapse;}
				.table_offduty_dates td, .table_offduty_dates th{text-align: center;}
				.table_offduty_dates tr{border-bottom: 1px dashed #d2d2d2;}
			</style>
			<?php
        };

        $ns->render_page = function() use ( $ns ) {
            $options = $ns->get_options();
            $version = $options['version'];
            settings_errors();
?>
                <form action="options.php" method="post">
                    <input type='hidden' name='birchschedule_options[version]' value='<?php echo $version; ?>'>
                    <?php settings_fields( 'birchschedule_options' ); ?>
                    <?php do_settings_sections( 'birchschedule_settings' ); ?>
                    <p class="submit">
                        <input name="Submit" type="submit" class="button-primary"
                               value="<?php _e( 'Save changes', 'birchschedule' ); ?>" />
                    </p>
                </form>
<?php
        };

        $ns->sanitize_input = function( $input ) {
            return $input;
        };

        $ns->get_update_info = function( $checked_data ) use ( &$update_info ) {
            $plugin_slug = "birchschedule";
            $slug_str = $plugin_slug . '/' . $plugin_slug . '.php';
            if ( isset( $checked_data->response[$slug_str] ) ) {
                $update_info = $checked_data->response[$slug_str];
                $update_info = array(
                    'version' => $update_info->new_version
                );
            }
            
            // Save offduty dates
            $offduty_dates = get_option('offduty_dates');
            $offduty_dates = $offduty_dates ? $offduty_dates : array();
            
            //Remove if exist
            if($_POST['remove_offduty_date'])
            {
	            $remove_offduty_dates = $_POST['remove_offduty_date'];
	            if (!empty($remove_offduty_dates))
	            {
	            	foreach ($remove_offduty_dates as $remove_offduty_date)
	            	{
	            		unset($offduty_dates[$remove_offduty_date]);
	            	}
	            }
            }
            
            if($_POST['birchschedule_options']['offduty_dates'])
            {
            	$offduty_date_add = $_POST['birchschedule_options']['offduty_dates'];
            	$offduty_date_add = str_replace(array('年','月'), '-', $offduty_date_add);
            	$offduty_date_add = str_replace('日', '', $offduty_date_add);
            	
            	
            	$offduty_dates[$offduty_date_add] = $offduty_date_add;
            }
            
            if ($offduty_dates && !empty($offduty_dates))
            {
	            $offduty_dates = array_unique($offduty_dates);
	            asort($offduty_dates);
	            update_option('offduty_dates', $offduty_dates);
            }
            
            return $checked_data;
        };

        $ns->show_update_notice = function() use ( &$update_info ) {
            global $birchschedule;

            $product_name = $birchschedule->get_product_name();
            $update_url = admin_url( 'update-core.php' );
            $update_text = "%s %s is available! <a href='$update_url'>Please update now</a>.";
            if ( $update_info ):
?>
                <div class="updated inline">
                    <p><?php echo sprintf( $update_text, $product_name, $update_info['version'] ); ?></p>
                </div>
<?php
            endif;
        };

    } );
