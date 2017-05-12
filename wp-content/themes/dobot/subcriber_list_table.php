<?php
/**
 * Defining Custom Table List
 */
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}


class Subscriber_List_Table extends WP_List_Table
{
    function __construct()
    {
        parent::__construct(array(
            'singular' => 'subscriber',
            'plural' => 'subscribers',
            'ajax'     => true,
        ));
    }

    public static function get_collects( $per_page = 10, $page_number = 1 ) {

        global $wpdb;
        $sql = "SELECT * FROM {$wpdb->prefix}subscriber";

        if ( ! empty( $_REQUEST['orderby'] ) ) {
            $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
            $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
        }
        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

        $result = $wpdb->get_results( $sql, 'ARRAY_A' );
        return $result;
    }


    /**
     * Delete a record.
     * @param int $id customer ID
     */
    public static function delete_collect( $id ) {
        global $wpdb;
        $wpdb->delete(
            "{$wpdb->prefix}subscriber",
            [ 'id' => $id ],
            [ '%d' ]
        );
    }

    /**
     * Returns the count of records in the database.
     * @return null|string
     */
    public static function record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}subscriber";

        return $wpdb->get_var( $sql );
    }

    public function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'create_date':
                $date =  date('M, d, Y \a\t H:i A',strtotime($item[ $column_name ]));
                return $date;
            default:
                return $item[ $column_name ];
        }
    }


    function column_email($item)
    {
        $actions = array(
            'delete' => sprintf(
                '<a href="?page=%s&action=delete&id=%s">%s</a>',
                $_REQUEST['page'], $item['id'], __('Delete', 'storefront')),
        );
        return sprintf('%s %s',
            $item['email'],
            $this->row_actions($actions)
        );
    }


    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }


    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            'email' => __('E-Mail', 'storefront'),
            'create_date' => __('Subscribe Date', 'storefront'),
            'subscrib_status' => __('Subscribe Status', 'storefront'),
        );
        return $columns;
    }


    function get_sortable_columns()
    {
        $sortable_columns = array(
            'email' => array('email', false),
            'create_date' => array('create_date', false),
        );
        return $sortable_columns;
    }


    function get_bulk_actions()
    {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }


    function process_bulk_action()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'subscriber';
        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);
            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        }
    }

    function prepare_items()
    {
        $paged = $_GET['paged'] ? $_GET['paged'] : 1;
        $per_page = 20;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->process_bulk_action();

        $total_items = self::record_count();

        $this->items = self::get_collects($per_page,$paged);

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));
    }
}