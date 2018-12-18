<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://www.castorstudio.com
 * @since      1.0.0
 *
 * @package    Ipido_admin
 * @subpackage Ipido_admin/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Ipido_admin
 * @subpackage Ipido_admin/includes
 * @author     Castorstudio <support@castorstudio.com>
 */
class Ipido_admin_Deactivator {

	/**
	 * Remove all plugin generated settings
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		if (cs_get_settings('resetsettings_status')){
			delete_option('cs_ipidoadmin_menuorder');
			delete_option('cs_ipidoadmin_submenuorder');
			delete_option('cs_ipidoadmin_menurename');
			delete_option('cs_ipidoadmin_submenurename');
			delete_option('cs_ipidoadmin_menudisable');
			delete_option('cs_ipidoadmin_submenudisable');
			delete_option('cs_ipidoadmin_settings');
		}
	}

}
