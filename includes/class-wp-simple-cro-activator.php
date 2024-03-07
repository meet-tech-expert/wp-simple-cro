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

        global $wpdb;
		// Check if custom post type simple_cro exists
		if (post_type_exists(SIMPLE_CRO_CPT)) {
			cro_admin_notice(SIMPLE_CRO_CPT . 'is already exists', 'error');
			deactivate_plugins(plugin_basename(__FILE__));
		}		

		$table_name = $wpdb->prefix . 'simple_cro_blocks';
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_name (
			id INT AUTO_INCREMENT PRIMARY KEY,
			cro_id INT NOT NULL,
			cro_unique_id INT NOT NULL,
			cro_title VARCHAR(255) NOT NULL,
			cro_category VARCHAR(255) NOT NULL,
			cro_tag VARCHAR(255) NOT NULL,
			block1_percentage INT NOT NULL,
			block2_percentage INT NOT NULL,
			block1_id INT NOT NULL,
			block1_title VARCHAR(255) NOT NULL,
			block2_id INT NOT NULL,
			block2_title VARCHAR(255) NOT NULL,
			device_type VARCHAR(255) NOT NULL,
			page_path VARCHAR(255) NOT NULL,
			click_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
		) $charset_collate;";


		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);    
	}
}
