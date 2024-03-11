<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Wp_Simple_Cro
 * @subpackage Wp_Simple_Cro/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Simple_Cro
 * @subpackage Wp_Simple_Cro/public
 * @author     Rinkesh Gupta <gupta.rinkesh1990@gmail.com>
 */
class Wp_Simple_Cro_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/simple-cro-front.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'simple-cro-gutenberg', plugin_dir_url( __FILE__ ) . 'js/simple-cro-front.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( 'simple-cro-gutenberg', 'scroFrontBlock', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce( 'handle_scro_data_nonce' ),
		));
	}	
	// store data in the database
	public function handle_scro_click() {
		global $wpdb;
	
		// Nonce verification
		if (!isset($_POST['scro_nonce']) || !wp_verify_nonce($_POST['scro_nonce'], 'handle_scro_data_nonce')) {
			wp_send_json_error('Nonce verification failed.');
		}
	
		// Check if all required fields are set
		$required_fields = array(
			'scro_id', 'scro_unique_id', 'scro_cat', 'scro_title', 'scro_tag', 'scro_block1_id',
			'scro_block1_percentage', 'scro_block1_title', 'scro_block2_id', 'scro_block2_percentage',
			'scro_block2_title', 'scro_device_type', 'scro_page_path', 'scro_block_variation', 'block_cta_row_column'
		);
	
		foreach ($required_fields as $field) {
			if (!isset($_POST[$field])) {
				wp_send_json_error('One or more required fields are missing.');
			}
		}
	
		// Insert data into the tables
		$simple_cro_table = $wpdb->prefix . SIMPLE_CRO_TABLE;
		$simple_cro_click_table = $wpdb->prefix . SIMPLE_CRO_CLICK_TABLE ;
	
		$inserted_into_simple_cro = $wpdb->insert(
			$simple_cro_table,
			array(
				'scro_id' => sanitize_text_field($_POST['scro_id']),
				'unique_id' => sanitize_text_field($_POST['scro_unique_id']),
				'title' => sanitize_text_field($_POST['scro_title']),
				'cat' => sanitize_text_field($_POST['scro_cat']),
				'tag' => sanitize_text_field($_POST['scro_tag']),
				'block1_id' => sanitize_text_field($_POST['scro_block1_id']),
				'block1_title' => sanitize_text_field($_POST['scro_block1_title']),
				'block1_perc' => absint($_POST['scro_block1_percentage']),
				'block2_id' => sanitize_text_field($_POST['scro_block2_id']),
				'block2_title' => sanitize_text_field($_POST['scro_block2_title']),
				'block2_perc' => absint($_POST['scro_block2_percentage'])
			)
		);
	
		$inserted_into_simple_cro_click = $wpdb->insert(
			$simple_cro_click_table,
			array(
				'device_type' => sanitize_text_field($_POST['scro_device_type']),
				'page_path' => sanitize_text_field($_POST['scro_page_path']),
				'block_variation' => sanitize_text_field($_POST['scro_block_variation']),
				'block_cta_row_column' => sanitize_text_field($_POST['block_cta_row_column']),
				'block_cta_unique_id' => sanitize_text_field($_POST['block_cta_unique_id']),
				'block_cta_order' => absint($_POST['block_cta_order'])
			)
		);
		if ($inserted_into_simple_cro && $inserted_into_simple_cro_click) {
			wp_send_json_success('Data inserted into both tables successfully.');
		} else {
			wp_send_json_error('Error inserting data into tables.');
		}
	}
}