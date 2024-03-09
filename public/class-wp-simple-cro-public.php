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
			'scro_block2_title', 'scro_device_type', 'scro_page_path', 'scro_button_url'
		);
	
		foreach ($required_fields as $field) {
			if (!isset($_POST[$field])) {
				wp_send_json_error('One or more required fields are missing.');
			}
		}
	
		// Sanitize and validate input data
		$scro_id = sanitize_text_field($_POST['scro_id']);
		$scro_uid = sanitize_text_field($_POST['scro_unique_id']);
		$scro_title = sanitize_text_field($_POST['scro_title']);
		$scro_cat = sanitize_text_field($_POST['scro_cat']);
		$scro_tag = sanitize_text_field($_POST['scro_tag']);
		$scro_block1_id = sanitize_text_field($_POST['scro_block1_id']);
		$scro_block1_title = sanitize_text_field($_POST['scro_block1_title']);
		$scro_block1_perc = absint($_POST['scro_block1_percentage']);
		$scro_block2_id = sanitize_text_field($_POST['scro_block2_id']);
		$scro_block2_title = sanitize_text_field($_POST['scro_block2_title']);
		$scro_block2_perc = absint($_POST['scro_block2_percentage']);
		$scro_device_type = sanitize_text_field($_POST['scro_device_type']);
		$scro_page_path = esc_url_raw($_POST['scro_page_path']);
		$scro_btn_url = esc_url_raw($_POST['scro_button_url']);
	
		// Insert data into the table
		$table_name = $wpdb->prefix . 'simple_cro_block';
	
		$wpdb->insert(
			$table_name,
			array(
				'scro_id' => $scro_id,
				'scro_unique_id' => $scro_uid,
				'scro_title' => $scro_title,
				'scro_cat' => $scro_cat,
				'scro_tag' => $scro_tag,
				'scro_block1_id' => $scro_block1_id,
				'scro_block1_title' => $scro_block1_title,
				'scro_block1_perc' => $scro_block1_perc,
				'scro_block2_id' => $scro_block2_id,
				'scro_block2_title' => $scro_block2_title,
				'scro_block2_perc' => $scro_block2_perc,
				'scro_device_type' => $scro_device_type,
				'scro_page_path' => $scro_page_path,
				'scro_btn_url' => $scro_btn_url,
			)
		);
	
		// Check if data is inserted successfully
		if ($wpdb->last_error) {
			wp_send_json_error('Error storing data: ' . $wpdb->last_error);
		} else {
			wp_send_json_success('Data stored successfully.');
		}
		wp_die();
	}
	
}