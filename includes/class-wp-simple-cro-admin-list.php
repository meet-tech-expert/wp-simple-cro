<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Wp_Simple_CRO_Admin_List extends WP_List_Table {

    private $plugin_name;

    // Constructor
    public function __construct($plugin_name) {

        $this->plugin_name = $plugin_name;
        
        parent::__construct(array(
            'singular'  => 'CRO Block',
            'plural'    => 'CRO Blocks',
        ));
    }

    // Default column renderer
    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'title':
                return isset($item['title']) ? $item['title'] : '';
            case 'categories':
                return isset($item['cat']) ? $item['cat'] : '';
            case 'tags':
                return isset($item['tag']) ? $item['tag'] : '';
            case 'date':
                return isset($item['created_at']) ? "Published <br/>".date('Y/m/d h:i a', strtotime($item['created_at'])) : '';
            case 'winning':
                return isset($item['winning_block_title']) ? $item['winning_block_title'] : '';                               
            default:
                return isset($item[$column_name]) ? $item[$column_name] : '';
        }
    }

    // Rendering column with title and actions
    public function column_title($item) {
        $actions = array(
            'view'      => sprintf('<a href="?post_type=simple_cro&page=simple-cro-list&action=view&id=%s">%s</a>', $item['id'], __('View', $this->plugin_name)),
            'delete'    => sprintf('<a href="?post_type=simple_cro&page=simple-cro-list&action=delete&id=%s">%s</a>', $item['id'], __('Delete', $this->plugin_name)),
        );
        return sprintf('%1$s %2$s', $item['title'], $this->row_actions($actions));
    }

    // Checkbox column
    public function column_cb($item) {
        return sprintf('<input type="checkbox" name="id[]" value="%s" />', $item['id']);
    }

    // Define columns for the table
    public function get_columns() {
        return array(
            'cb'            => '<input type="checkbox" />',
            'title'         => __('Title', $this->plugin_name),
            'categories'    => __('Categories', $this->plugin_name),
            'tags'          => __('Tags', $this->plugin_name),
            'date'          => __('Date', $this->plugin_name),
            'winning'       => __('Winning', $this->plugin_name),
        );
    }

    // Define sortable columns
    public function get_sortable_columns() {
        return array(
            'title'         => array('title',   true),
            'date'          => array('created_at',    true),
            'winning'       => array('winning', false),
        );
    }

    // Return array of bulk actions
    public function get_bulk_actions() {
        $actions = array(
            'delete' => __('Delete permanently'),
        );
        return $actions;
    }

    // Process bulk actions
    public function process_bulk_action() {
        global $wpdb;
        $simple_cro_table       = $wpdb->prefix . SIMPLE_CRO_TABLE;
		$simple_cro_click_table = $wpdb->prefix . SIMPLE_CRO_CLICK_TABLE;

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $simple_cro_table WHERE id IN($ids)");
                $wpdb->query("DELETE FROM $simple_cro_click_table WHERE cro_id IN($ids)");
            }
        }
    }
    
    // Prepare items for the table
    public function prepare_items() {
        global $wpdb;

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $per_page = 20;
        $simple_cro_table = $wpdb->prefix . SIMPLE_CRO_TABLE;
		$simple_cro_click_table = $wpdb->prefix . SIMPLE_CRO_CLICK_TABLE;

        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->process_bulk_action();

        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $simple_cro_table");

        $search_query = isset($_REQUEST['s']) ? sanitize_text_field($_REQUEST['s']) : '';
  
        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged'] - 1) * $per_page) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'created_at';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';
        $query = "SELECT sct.id, sct.title, sct.cat, sct.tag, sct.created_at, 
        SUM(CASE WHEN scc.block_variation = 'a' THEN 1 ELSE 0 END) AS count_a,
        SUM(CASE WHEN scc.block_variation = 'b' THEN 1 ELSE 0 END) AS count_b,
        CASE WHEN SUM(CASE WHEN scc.block_variation = 'a' THEN 1 ELSE 0 END) > SUM(CASE WHEN scc.block_variation = 'b' THEN 1 ELSE 0 END) THEN sct.block1_title ELSE sct.block2_title END AS winning_block_title FROM $simple_cro_table AS sct INNER JOIN $simple_cro_click_table AS scc ON sct.id = scc.cro_id ";

        if (!empty($search_query)) {
            $query .= $wpdb->prepare("WHERE (title LIKE '%%%s%%' OR cat LIKE '%%%s%%' OR tag LIKE '%%%s%%')", $search_query, $search_query, $search_query);
        }

        $query .= " GROUP BY sct.id ORDER BY $orderby $order LIMIT $per_page OFFSET $paged";

        $this->items = $wpdb->get_results($query, ARRAY_A);
       
        $this->set_pagination_args(array(
            'total_items'   => $total_items,
            'per_page'      => $per_page,
            'total_pages'   => ceil($total_items / $per_page)
        ));        
    }
    
    // Function to display view form
    public function display_view_form() {
        if (isset($_GET['id']) && $_GET['id'] !== '') {
            $item_id = $_GET['id'];

            global $wpdb;
            $simple_cro_table = $wpdb->prefix . SIMPLE_CRO_TABLE;
            $query = $wpdb->prepare("SELECT * FROM $simple_cro_table WHERE id = %d", $item_id);
            $item_data = $wpdb->get_row($query, ARRAY_A);
            if ($item_data) {
                $block_a = '';
                $block_b = '';
                
                $args = array(
                    'post_type' => SIMPLE_CRO_CPT, 
                    'posts_per_page' => -1, 
                );
                $query = new WP_Query($args);
                if ($query->have_posts()) {
                    while ($query->have_posts()) {
                        $query->the_post();
                        $post_content = get_the_content();
                        if (strpos($post_content, $item_data['scro_id']) !== false) {
                            $dom = new DOMDocument();
                            @$dom->loadHTML($post_content); 
                            $xpath = new DOMXPath($dom);
                            $inner_blocks = $xpath->query('//div[contains(@class, "scro-inner-blocks")]');
    
                            if ($inner_blocks->length > 0) {
                                $inner_block = $inner_blocks->item(0);
                                $child_nodes = $inner_block->childNodes;
                                
                                foreach ($child_nodes as $node) {
                                    if ($node->nodeType === XML_ELEMENT_NODE) {
                                        $html_content = $dom->saveHTML($node);
                                        if (empty($block_a)) {
                                            $block_a = $html_content;
                                        } else {
                                            $block_b = $html_content;
                                        }
                                    }
                                }
                            } else {
                                echo 'Inner blocks not found.';
                            }
                            break; 
                        }
                    }
                    wp_reset_postdata();
                }
                ?>
                <div class="wrap">
                    <h1 class="wp-heading-inline"><?php _e('Edit Simple CRO Test', $this->plugin_name)?></h1>
                    <form method="get" action="">
                        <input type="text" name="item_title" value="<?php echo esc_attr($item_data['title']); ?>" style="width: 100%; background-color: #fff; font-size: 22px;">
                    </form>
                    
                    <div class="nav-tab-wrapper">
                        <a href="#live-results" class="nav-tab <?php if (!isset($_GET['tab']) || $_GET['tab'] === 'live-results') echo 'nav-tab-active'; ?>"><?php _e('Live Results', $this->plugin_name)?></a>
                        <a href="#settings" class="nav-tab <?php if (isset($_GET['tab']) && $_GET['tab'] === 'settings') echo 'nav-tab-active'; ?>"><?php _e('Settings', $this->plugin_name)?></a>
                    </div>
    
                    <div id="live-results" class="tab-content <?php if (!isset($_GET['tab']) || $_GET['tab'] === 'live-results') echo 'active'; ?>" style="background-color: #fff;">
                        <div class="live-result">
                            <table class="live-results-table">
                                <thead>
                                    <tr>
                                        <th>Block</th>
                                        <th>Block Title</th>
                                        <th>Block ID</th>
                                        <th>Displays</th>
                                        <th>Clicks</th>
                                        <th>Conversion Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Display data rows
                                    if ($item_data) {
                                        echo '<tr>';
                                        echo '<td>' . $item_data['block1_id'] . '</td>';
                                        echo '<td>' . $item_data['block1_title'] . '</td>';
                                        echo '<td>' . $item_data['block1_id'] . '</td>';

                                        // Add more rows as needed

                                        echo '</tr>';
                                    } else {
                                        echo '<tr><td colspan="6">No data found.</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
    
                    <div id="settings" class="tab-content <?php if (isset($_GET['tab']) && $_GET['tab'] === 'settings') echo 'active'; ?>">
                        <div class="setting-tab">
                            <div class="row">
                                <div class="flex-container">
                                    <label for="cro_test_id"><?php _e('CRO Test ID:', $this->plugin_name) ?></label>
                                    <input type="text" id="cro_test_id" value="<?php echo $item_data['scro_id']; ?>">
                                </div>
    
                                <div class="flex-container">
                                    <label for="cro_categories"><?php _e('CRO Categories:', $this->plugin_name) ?></label>
                                    <input type="text" id="cro_categories" value="<?php echo $item_data['cat']; ?>">
                                </div>
    
                                <div class="flex-container">
                                    <label for="cro_tags"><?php _e('CRO Tags:', $this->plugin_name) ?></label>
                                    <input type="text" id="cro_tags" value="<?php echo $item_data['tag']; ?>">
                                </div>
    
                                <div class="flex-container">
                                    <label for="cro_tags"><?php _e('Cro Block Distribution:', $this->plugin_name) ?></label>
                                    <div>
                                        <label for="block_a_percentage"><?php _e('Block A:', $this->plugin_name) ?></label>
                                        <input type="text" id="block_a_percentage" value="<?php echo $item_data['block1_perc']; ?>">
                                        <input type="range" id="block_percentage" min="<?php echo $item_data['block1_perc']; ?>" max="<?php echo $item_data['block2_perc']; ?>" value="<?php echo $item_data['block1_perc']; ?>">
                                        <label for="block_b_percentage"><?php _e('Block B:', $this->plugin_name) ?></label>
                                        <input type="text" id="block_b_percentage" value="<?php echo $item_data['block2_perc']; ?>">
                                    </div>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="flex-container">
                                    <div class="flex-content">
                                        <div class="flex">
                                            <label><?php _e('Block A', $this->plugin_name) ?></label>
                                            <div class="label">
                                                <label for="block_a_title"><?php _e('Block Title:', $this->plugin_name) ?></label>
                                                <input type="text" id="block_a_title" value="<?php echo $item_data['block1_title']; ?>">
                                            </div>
                                            <div  class="label">
                                                <label for="block_a_id"><?php _e('Block ID:', $this->plugin_name) ?></label>
                                                <input type="text" id="block_a_id" value="<?php echo $item_data['block1_id']; ?>">
                                            </div>
                                        </div>
                                        <div class="card-container">
                                            <div class="card">
                                                <div><?php echo $block_a ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="flex-container">
                                    <div class="flex-content">
                                        <div class="flex">
                                            <label><?php _e('Block B', $this->plugin_name) ?></label>
                                            <div class="label">
                                                <label><?php _e('Block Title:', $this->plugin_name) ?></label>
                                                <input type="text" id="block_b_title" value="<?php echo $item_data['block2_title']; ?>">
                                            </div>
                                            <div  class="label">
                                                <label><?php _e('Block ID:', $this->plugin_name) ?></label>
                                                <input type="text" id="block_b_id" value="<?php echo $item_data['block2_id']; ?>">
                                            </div>
                                        </div>
                                        <div class="card-container">
                                            <div class="card">
                                                <div><?php echo $block_b ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <style>
                    .label {
                        display: grid;
                    }

                    .flex-content {
                        display: flex;
                        justify-content: space-between;
                    }

                    .tab-content {
                        background-color: #fff;
                    }

                    .setting-tab {
                        padding: 1em;
                    }

                    .card-container {
                        width: 50%;
                    }

                    .card {
                        background-color: #f9f9f9;
                        border: 1px solid #ddd;
                        border-radius: 4px;
                        padding: 20px;
                        margin-bottom: 20px;
                    }

                    .row {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        margin-bottom: 20px;
                    }

                    .flex-container {
                        display: flex;
                        flex-direction: column;
                        margin-right: 20px;
                    }

                    label {
                        margin-bottom: 5px;
                    }

                    input[type="text"] {
                        width: 150px;
                        padding: 3px;
                        margin-bottom: 10px;
                    }

                </style>
                <script>
                    jQuery(document).ready(function() {
                        jQuery('.nav-tab').click(function(event) {
                            event.preventDefault();
                            var tabId = jQuery(this).attr('href'); // Get the href attribute value (e.g., #live-results or #settings)
                            jQuery('.nav-tab').removeClass('nav-tab-active');
                            jQuery(this).addClass('nav-tab-active');
                            jQuery('.tab-content').removeClass('active');
                            jQuery(tabId).addClass('active');
                        });
                    });
                </script>

                <?php
            } else {
                echo '<p>' . __('Invalid item ID.', $this->plugin_name) . '</p>';
            }
        }
    }    
}

