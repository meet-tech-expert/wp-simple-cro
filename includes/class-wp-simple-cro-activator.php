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

 class Wp_Simple_Cro_Activator {

    /**
     * Activate the plugin.
     *
     * This method is called when the plugin is activated.
     */
    public static function activate() {
        ob_start(); // Start output buffering to capture any output
    
        global $wpdb;
    
        // Check if custom post type simple_cro exists
        if (post_type_exists(SIMPLE_CRO_CPT)) {
            cro_admin_notice(SIMPLE_CRO_CPT . ' already exists', 'error');
            deactivate_plugins(plugin_basename(__FILE__));
            return;
        }
    
        $simple_cro_table = $wpdb->prefix . SIMPLE_CRO_TABLE;
        $simple_cro_click_table = $wpdb->prefix . SIMPLE_CRO_CLICK_TABLE;
        $charset_collate = $wpdb->get_charset_collate();
    
        // SQL query to create the first table
        $sql = "CREATE TABLE IF NOT EXISTS $simple_cro_table (
            id INT AUTO_INCREMENT PRIMARY KEY,
            scro_id VARCHAR(50) NOT NULL UNIQUE COMMENT 'ID of the SCRO',
            unique_id VARCHAR(50) NOT NULL COMMENT 'Unique ID of the SCRO',
            title VARCHAR(255) NOT NULL COMMENT 'Title of the SCRO',
            cat VARCHAR(255) NOT NULL COMMENT 'Category of the SCRO',
            tag VARCHAR(255) NOT NULL COMMENT 'Tag of the SCRO',
            block1_id VARCHAR(50) NOT NULL COMMENT 'ID of Block 1 in SCRO',
            block1_title VARCHAR(255) NOT NULL COMMENT 'Title of Block 1 in SCRO',
            block1_perc INT NOT NULL COMMENT 'Percentage of Block 1 in SCRO',
            block2_id VARCHAR(50) NOT NULL COMMENT 'ID of Block 2 in SCRO',
            block2_title VARCHAR(255) NOT NULL COMMENT 'Title of Block 2 in SCRO',
            block2_perc INT NOT NULL COMMENT 'Percentage of Block 2 in SCRO',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ) $charset_collate;";
    
        // SQL query to create the second table
        $sql1 = "CREATE TABLE IF NOT EXISTS $simple_cro_click_table (
            id INT AUTO_INCREMENT PRIMARY KEY,
            cro_id INT NOT NULL COMMENT 'ID of the SCRO',
            block_variation VARCHAR(5) NOT NULL COMMENT 'Variation of the block in SCRO',
            block_cta_row_column VARCHAR(50) NOT NULL COMMENT 'CTA of Block in SCRO',
            block_cta_order INT NOT NULL COMMENT 'CTA order of block in SCRO',
            block_cta_unique_id VARCHAR(50) NOT NULL COMMENT 'Unique ID of CTA in SCRO',
            device_type VARCHAR(255) NOT NULL COMMENT 'Type of device used for SCRO',
            page_path VARCHAR(255) NOT NULL COMMENT 'Page path for SCRO',
            add_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";
    
        // Execute SQL queries
        $wpdb->query($sql);
        $wpdb->query($sql1);
    
        ob_end_clean(); // End output buffering and discard any captured output
    }    
}
