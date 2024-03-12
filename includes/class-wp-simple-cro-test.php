<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Wp_Simple_CRO_Admin_List extends WP_List_Table {
    // Constructor
    public function __construct() {
        parent::__construct(array(
            'singular' => 'CRO Block',
            'plural' => 'CRO Blocks',
        ));
    }

    // Default column renderer
    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'title':
                return isset($item->post_title) ? $item->post_title : '';
            case 'categories':
                return isset($item->categories) ? $item->categories : '';
            case 'tags':
                return isset($item->tags) ? $item->tags : '';
            case 'date':
                return isset($item->post_date) ? $item->post_date : '';
            case 'winning':
                return isset($item->winning_block_title) ? $item->winning_block_title : '';
            default:
                return isset($item->$column_name) ? $item->$column_name : '';
        }
    }

    // Rendering column with title and actions
    public function column_name($item) {
        $actions = array(
            'edit' => sprintf('<a href="?page=edit_post&id=%s">%s</a>', $item->ID, __('Edit', 'cltd_example')),
            'view' => sprintf('<a href="?page=view_post&id=%s">%s</a>', $item->ID, __('View', 'cltd_example')),
            'trash' => sprintf('<a href="?page=%s&action=trash&id=%s">%s</a>', $_REQUEST['page'], $item->ID, __('Trash', 'cltd_example')),
        );
        return sprintf('%1$s %2$s', $item->post_title, $this->row_actions($actions));
    }

    // Checkbox column
    public function column_cb($item) {
        return sprintf('<input type="checkbox" name="id[]" value="%s" />', $item->ID);
    }

    // Define columns for the table
    public function get_columns() {
        return array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Title'),
            'categories' => __('Categories'),
            'tags' => __('Tags'),
            'date' => __('Date Published'),
            'winning' => __('Winning Block Title'),
        );
    }

    // Define sortable columns
    public function get_sortable_columns() {
        return array(
            'title' => array('title', false),
            'date' => array('date', false),
            'winning' => array('winning', false),
        );
    }

    // Return array of bulk actions
    public function get_bulk_actions() {
        $actions = array(
            'edit' => __('Edit'),
            'trash' => __('Move to Trash'),
        );
        return $actions;
    }

    // Process bulk actions
    public function process_bulk_action() {
        global $wpdb;

        if ('edit' === $this->current_action() && isset($_REQUEST['id'])) {
            // Handle bulk edit action
        } elseif ('trash' === $this->current_action() && isset($_REQUEST['id'])) {
            foreach ($_REQUEST['id'] as $post_id) {
                wp_trash_post($post_id); // Move post to trash
            }
        }
    }

    // Prepare items for the table
    public function prepare_items() {
        global $wpdb;

        // Define column headers, hidden columns, and sortable columns
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        // Prepare the SQL query to retrieve posts
        $query = "SELECT * FROM $wpdb->posts WHERE post_type = 'simple_cro' AND post_status = 'publish'";

        // Handle search query
        if (isset($_REQUEST['s'])) {
            $search = sanitize_text_field($_REQUEST['s']);
            $query .= " AND post_title LIKE '%$search%'";
        }

        // Apply date filtering
        if (isset($_REQUEST['start_date']) && isset($_REQUEST['end_date'])) {
            $start_date = sanitize_text_field($_REQUEST['start_date']);
            $end_date = sanitize_text_field($_REQUEST['end_date']);
            $query .= " AND post_date BETWEEN '$start_date' AND '$end_date'";
        }

        // Apply sorting
        if (isset($_REQUEST['orderby']) && isset($_REQUEST['order'])) {
            $orderby = sanitize_text_field($_REQUEST['orderby']);
            $order = sanitize_text_field($_REQUEST['order']);
            $query .= " ORDER BY $orderby $order";
        }

        // Get total number of items
        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM ($query) AS combined");

        // Pagination settings
        $per_page = 20;
        $current_page = $this->get_pagenum();
        $offset = ($current_page - 1) * $per_page;

        // Retrieve posts for the current page
        $query .= " LIMIT $per_page OFFSET $offset";
        $results = $wpdb->get_results($query);

        // Set items for the table
        $this->items = $results;

        // Register pagination settings
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
        ));

        // Set column headers
        $this->_column_headers = array($columns, $hidden, $sortable);
    }
}