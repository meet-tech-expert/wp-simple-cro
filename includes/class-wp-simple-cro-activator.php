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
     * Activate the plugin.
     *
     * This method is called when the plugin is activated.
     */
    public static function activate() {
        global $wpdb;
    
        // Check if custom post type simple_cro exists
        if (post_type_exists(SIMPLE_CRO_CPT)) {
            cro_admin_notice(SIMPLE_CRO_CPT . ' is already exists', 'error');
            deactivate_plugins(plugin_basename(__FILE__));
            return;
        }
    
        $table_name = $wpdb->prefix . 'simple_cro_block';
        $charset_collate = $wpdb->get_charset_collate();
    
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id INT AUTO_INCREMENT PRIMARY KEY,
            scro_id INT NOT NULL COMMENT 'ID of the SCRO',
            scro_unique_id VARCHAR(255) NOT NULL COMMENT 'Unique ID of the SCRO',
            scro_title VARCHAR(255) NOT NULL COMMENT 'Title of the SCRO',
            scro_cat VARCHAR(255) NOT NULL COMMENT 'Category of the SCRO',
            scro_tag VARCHAR(255) NOT NULL COMMENT 'Tag of the SCRO',
            scro_block1_id INT NOT NULL COMMENT 'ID of Block 1 in SCRO',
            scro_block1_title VARCHAR(255) NOT NULL COMMENT 'Title of Block 1 in SCRO',
            scro_block1_perc INT NOT NULL COMMENT 'Percentage of Block 1 in SCRO',
            scro_block2_id INT NOT NULL COMMENT 'ID of Block 2 in SCRO',
            scro_block2_title VARCHAR(255) NOT NULL COMMENT 'Title of Block 2 in SCRO',
            scro_block2_perc INT NOT NULL COMMENT 'Percentage of Block 2 in SCRO',
            scro_device_type VARCHAR(255) NOT NULL COMMENT 'Type of device used for SCRO',
            scro_page_path VARCHAR(255) NOT NULL COMMENT 'Page path for SCRO',
            scro_btn_url VARCHAR(255) NOT NULL COMMENT 'URL of the button in SCRO',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ) $charset_collate;";
    
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}