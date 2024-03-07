<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Wp_Simple_CRO_CPT {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */

    public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;       
        // Hook the methods properly
        add_action( 'init', array( $this, 'register_simple_cro_cpt' ) );
        add_action( 'admin_menu', array($this, 'add_cpt_submenu' ) );

	}

    public function register_simple_cro_cpt() {

        $labels = array(
            'name'                  => esc_html__( 'Simple CRO', $this->plugin_name ),
            'singular_name'         => esc_html__( 'Simple CRO', $this->plugin_name ),
            'menu_name'             => esc_html__( 'Simple CRO', $this->plugin_name ),
            'name_admin_bar'        => esc_html__( 'Simple CRO', $this->plugin_name ),
            'archives'              => esc_html__( 'Simple CRO Archives', $this->plugin_name ),
            'attributes'            => esc_html__( 'Simple CRO Attributes', $this->plugin_name ),
            'parent_item_colon'     => esc_html__( 'Parent Simple CRO:', $this->plugin_name ),
            'all_items'             => esc_html__( 'All Simple CRO ', $this->plugin_name ),
            'add_new_item'          => esc_html__( 'Add New Simple CRO ', $this->plugin_name ),
            'add_new'               => esc_html__( 'Add New', $this->plugin_name ),
            'new_item'              => esc_html__( 'New Simple CRO ', $this->plugin_name ),
            'edit_item'             => esc_html__( 'Edit Simple CRO ', $this->plugin_name ),
            'update_item'           => esc_html__( 'Update Simple CRO ', $this->plugin_name ),
            'view_item'             => esc_html__( 'View Simple CRO ', $this->plugin_name ),
            'view_items'            => esc_html__( 'View Simple CRO ', $this->plugin_name ),
            'search_items'          => esc_html__( 'Search Simple CRO ', $this->plugin_name ),
            'not_found'             => esc_html__( 'Not found', $this->plugin_name ),
            'not_found_in_trash'    => esc_html__( 'Not found in Trash', $this->plugin_name ),
            'featured_image'        => esc_html__( 'Featured Image', $this->plugin_name ),
            'set_featured_image'    => esc_html__( 'Set featured image', $this->plugin_name ),
            'remove_featured_image' => esc_html__( 'Remove featured image', $this->plugin_name ),
            'use_featured_image'    => esc_html__( 'Use as featured image', $this->plugin_name ),
            'insert_into_item'      => esc_html__( 'Insert into Simple CRO ', $this->plugin_name ),
            'uploaded_to_this_item' => esc_html__( 'Uploaded to this Simple CRO ', $this->plugin_name ),
            'items_list'            => esc_html__( 'Simple CRO list', $this->plugin_name ),
            'items_list_navigation' => esc_html__( 'Simple CRO list navigation', $this->plugin_name ),
            'filter_items_list'     => esc_html__( 'Filter Simple CRO list', $this->plugin_name ),
            'item_published'        => esc_html__( 'Simple CRO published', $this->plugin_name ),
            'item_published_privately' => esc_html__( 'Simple CRO published privately', $this->plugin_name ),
            'item_reverted_to_draft' => esc_html__( 'Simple CRO reverted to draft', $this->plugin_name ),
            'item_scheduled'        => esc_html__( 'Simple CRO scheduled', $this->plugin_name ),
            'item_updated'          => esc_html__( 'Simple CRO updated', $this->plugin_name ),
        );        
        $args = array(
            'label'                 => esc_html__( 'Simple CRO', $this->plugin_name ),
            'description'           => esc_html__( 'Simple CRO', $this->plugin_name ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'custom-fields' ),
            'show_in_rest'          => true,
            'public'                => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-layout',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'rewrite'               => array( 'slug' => 'simple-cro', 'with_front' => true ),
            'show_ui'               => true,
        );  
        $cro_cpt = register_post_type( SIMPLE_CRO_CPT, $args );
        
        if ( is_wp_error( $cro_cpt ) ) {
            cro_admin_notice('Failed to register '.SIMPLE_CRO_CPT. ' post type '. $cro_cpt->get_error_message() , 'error');
        }      
    }
    public function add_cpt_submenu() {
        add_submenu_page(
            'edit.php?post_type=simple_cro', 
            'CRO test',               
            'CRO test',                
            'manage_options',                 
            'simple-cro-test',                
            array( $this, 'display_simple_cro_test_page' ) 
        );
    }
    public function display_simple_cro_test_page() {
        $args = array(
            'post_type'      => 'simple_cro',
            'posts_per_page' => -1, // Retrieve all posts
            'post_status'    => 'publish', // Retrieve only published posts
        );
    
        $cro_query = new WP_Query( $args );
    
        // Check if there are any Simple CRO posts
        if ( $cro_query->have_posts() ) {
            // Start outputting the list
            echo '<div class="wrap">';
            echo '<h1 class="wp-heading-inline">CRO Tests</h1>';
    
            // Start table
            echo '<table class="wp-list-table widefat striped">';
            echo '<thead>';
            echo '<tr>';
            echo '<th scope="col" class="manage-column column-title">Title</th>';
            echo '<th scope="col" class="manage-column column-categories">Categories</th>';
            echo '<th scope="col" class="manage-column column-tags">Tags</th>';
            echo '<th scope="col" class="manage-column column-date">Date</th>';
            echo '<th scope="col" class="manage-column column-winning">Winning Block</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody id="the-list">';
    
            // Loop through each Simple CRO post
            while ( $cro_query->have_posts() ) {
                $cro_query->the_post();
                $post_id = get_the_ID();
                $cro_title = get_the_title();
                $cro_categories = get_the_category();
                $cro_tags = get_the_tags();
                $cro_date = get_the_date();
    
                // Calculate the winning block (you need to implement this logic)
                $winning_block = "Block A"; // Replace with your actual logic to determine the winning block
    
                // Output list item for each Simple CRO post
                echo '<tr id="post-' . $post_id . '" class="iedit author-self level-0 post-' . $post_id . ' type-post status-publish format-standard hentry">';
                echo '<td class="title column-title has-row-actions column-primary page-title" data-colname="Title">' . $cro_title;
                echo '<div class="row-actions">';
                echo '<span class="edit">';
                echo '<a href="' . get_edit_post_link( $post_id ) . '" aria-label="Edit “' . $cro_title . '”">Edit</a> | ';
                echo '</span>';
                echo '<span class="trash">';
                echo '<a href="' . get_delete_post_link( $post_id ) . '" class="submitdelete" aria-label="Move “' . $cro_title . '” to the Trash">Trash</a> | ';
                echo '</span>';
                echo '<span class="view">';
                echo '<a href="' . get_permalink( $post_id ) . '" aria-label="View “' . $cro_title . '”">View</a>';
                echo '</span>';
                echo '</div>';
                echo '</td>';
                echo '<td class="categories column-categories" data-colname="Categories">';
                if ( ! empty( $cro_categories ) ) {
                    foreach ( $cro_categories as $category ) {
                        echo $category->name . ', ';
                    }
                }
                echo '</td>';
                echo '<td class="tags column-tags" data-colname="Tags">';
                if ( ! empty( $cro_tags ) ) {
                    foreach ( $cro_tags as $tag ) {
                        echo $tag->name . ', ';
                    }
                }
                echo '</td>';
                echo '<td class="date column-date" data-colname="Date">' . $cro_date . '</td>';
                echo '<td class="winning column-winning" data-colname="Winning Block">' . $winning_block . '</td>';
                echo '</tr>';
            }
    
            // End of the list
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
    
            wp_reset_postdata(); // Restore original post data
        } else {
            echo '<div class="wrap">';
            echo '<h1 class="wp-heading-inline">CRO Tests</h1>';
            echo '<p>No Simple CRO posts found.</p>';
            echo '</div>';
        }
    }
    
    
}