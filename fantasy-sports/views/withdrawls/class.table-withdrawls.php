<?php
class TableWithdrawls extends WP_List_Table
{
    private static $user;
    function __construct()
    {
        self::$user = new User();
        global $status, $page;
        $aResults = null;
        parent::__construct( array(
            'singular'  => __( 'book', 'mylisttable' , FV_DOMAIN),     //singular name of the listed records
            'plural'    => __( 'books', 'mylisttable' , FV_DOMAIN),   //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
    }

    function column_default( $item, $column_name ) 
    {
        switch( $column_name ) 
        { 
            case 'ID':
                return $item['withdrawlID'];
            case 'uID':
                return $item['userID'];
            case 'user_login':
                return $item['user_login'];
            case 'amount':
                return $item['amount'];
            case 'real_amount':
                return $item['real_amount'];
            case 'new_balance':
                return $item['new_balance'];
            case 'requestDate':
                return $item['requestDate'];
            case 'status':
                return $item['status'];
            case 'action':
                return '<a onclick="return jQuery.admin.userWithdrawls(this, '.$item['ID'].', \'Action\')" href="#">Action</a>
                        <input class="withdrawlID" type="hidden" value="'.$item['withdrawlID'].'">
                        <input class="reason" type="hidden" value="'.$item['reason'].'">
                        <input class="response_message" type="hidden" value="'.$item['response_message'].'">';
            default:
                return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }

    function get_columns()
    {
        $columns = array(
            'ID' => __('ID', FV_DOMAIN),
            'uID' => __('uID', FV_DOMAIN),
            'name' => __('Name', FV_DOMAIN),
            'amount' => __('Amount', FV_DOMAIN),
            'real_amount' => __('Real Amount', FV_DOMAIN),
            'new_balance' => __('Balance', FV_DOMAIN),
            'requestDate' => __('Request Date', FV_DOMAIN),
            'status' => __('Status', FV_DOMAIN),
            'action' => __('Action', FV_DOMAIN),
        );		
        return $columns;
    }
    
    function get_sortable_columns() 
    {
        $sortable_columns = array(
			'ID'  => array('withdrawlID',false),
            'uID'  => array('userID',false),
            'name'  => array('user_login',false),
        );
        return $sortable_columns;
    }
    
    function usort_reorder( $a, $b ) 
    {
        // If no sort, default to title
        $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'ID';
        // If no order, default to asc
        $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'ASC';
		
        if(is_numeric($a[$orderby]))
        {
            $result = $a[$orderby] - $b[$orderby];
        }
        else 
        {
            $result = strcmp( $a[$orderby], $b[$orderby] );
        }
        return ( $order === 'asc' ) ? $result : -$result;
    }
    
    function column_name($item) 
    {
        $actions = array(
        );

        return sprintf('%1$s %2$s', $item['user_login'], $this->row_actions($actions) );
    }
    
    function prepare_items($keyword = null) 
    {
        $user_id = $_COOKIE['fanvictor_user_id'];
        $screen = get_current_screen();
        
        // retrieve the "per_page" option
        $screen_option = $screen->get_option('per_page', 'option');
        
        //add page number to table usermeta
        if(isset($_POST['wp_screen_options']))
        {
            $screen_value = $_POST['wp_screen_options']['value'];
            $meta = get_user_meta($user_id, $screen_option);
            if($meta == null)
            {
                add_user_meta($user_id, $screen_option, $screen_value);
            }
            else 
            {
                update_user_meta($user_id, $screen_option, $screen_value);
            }
            header('Location:'.$_SERVER['REQUEST_URI']);
        }
        
        // retrieve the value of the option stored for the current user
        $item_per_page = get_user_meta($_COOKIE['fanvictor_user_id'], $screen_option, true);
        
        if ( empty ( $item_per_page) || $item_per_page < 1 ) {
            // get the default value if none is set
            $item_per_page = $screen->get_option( 'per_page', 'default' );
        }
        
        //search
        $aCond = null;
        if($keyword != null)
        {
            $keyword = trim($keyword);
            $aCond = array("user_login LIKE '%%%$keyword%%'");
        }
        
        //get data
        list($total_items, $aResults) = self::$user->getUsersWithdrawls($aCond, 'w.withdrawlID DESC', ($this->get_pagenum() - 1) * $item_per_page, $item_per_page);
        $columns  = $this->get_columns();
        $hidden   = array();
        
        //sort data
        $sortable = $this->get_sortable_columns();
        if($aResults != null)
        {
            usort( $aResults, array( &$this, 'usort_reorder' ) );
        }
        $this->_column_headers = array( $columns, $hidden, $sortable );
        
        //pagination
        $this->set_pagination_args( array(
            'total_items' => $total_items,                 
            'per_page'    => $item_per_page       
        ) );
        $this->items = $aResults;
    }
}
?>