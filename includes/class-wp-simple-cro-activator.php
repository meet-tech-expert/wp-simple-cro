<?php

/**
 * Fired during plugin activation
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Wp_Simple_Cro
 * @subpackage Wp_Simple_Cro/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Simple_Cro
 * @subpackage Wp_Simple_Cro/includes
 * @author     Rinkesh Gupta <gupta.rinkesh1990@gmail.com>
 */
class Wp_Simple_Cro_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		// Check if custom post type simple_cro exists
		if (post_type_exists(SIMPLE_CRO_CPT)) {
			cro_admin_notice(SIMPLE_CRO_CPT . 'is already exists', 'error');
			deactivate_plugins(plugin_basename(__FILE__));
		}		
	}	

}
