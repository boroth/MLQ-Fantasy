<?php
class TablePools extends WP_List_Table
{
    private static $pools;
    function __construct()
    {
        self::$pools = new Pools();
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
            case 'image':
                return '<img width="35px" height="35px" alt="" src="'.FANVICTOR_IMAGE_URL.Pools::replaceSuffix($item['image']).'">';
            case 'poolName':
                return $item[ $column_name ];
            case 'playerdraft_result':
                if($item['playerdraft'] == true)
                {
                    $info = '';
                    if(is_null($item['uploaded_file'])){
                        $item['uploaded_file'] = '';
                    }
                        $arr = array(
                            'org_id' => $item['organization'],
                            'uploaded_file'=>$item['uploaded_file']
                        );
                        //$info = '<input type="hidden" id="info_upload_'.$item['poolID'].'" value='. json_encode($arr) . '>';
                        $info = "<input type='hidden' id='info_upload_$item[poolID]' value='".  json_encode($arr)."'>";
                    $hide = 'style="display:none"';
                    if($item['status'] == 'NEW')
                    {
                        $hide = '';
                    }
                    return '<a onclick="return jQuery.fight.viewPlayerDraftResult('.$item['poolID'].', \'Player Draft Results\');" href="#" '.$hide.'>Result</a>'.$info;
                }
                return '';
            case 'result':
                if(!$item['only_playerdraft'])
                {
                    $hide = 'style="display:none"';
                    if($item['status'] == 'NEW')
                    {
                        $hide = '';
                    }
                    return '<a onclick="return jQuery.fight.viewResult('.$item['poolID'].', \'Results\');" href="#" '.$hide.'>Result</a>';
                }
                return '';
            case 'status':
                $disable = $reverse = '';
                if($item['status'] != 'NEW')
                {
                    $disable = 'disabled="true"';
                }
                else 
                {
                    $reverse = 'style="display:none"';
                }
                return '<select '.$disable.' onchange="jQuery.fight.updatePoolStatus('.$item['poolID'].', this, \''.$item['status'].'\');" name="status">
                            <option '.($item['status'] == 'NEW' ? 'selected="true"' : "").' value="NEW">New</option>
                            <option '.($item['status'] == 'COMPLETE' ? 'selected="true"' : "").' value="COMPLETE">Complete</option>
                        </select>
                        <input type="button" class="button button-primary btn-reverse" onclick="jQuery.fight.reverseResult('.$item['poolID'].', this)" '.$reverse.' value="Reverse" />';
            case 'edit':
                if($item['status'] == 'NEW')
                {
                    return sprintf('<a href="?page=%s&action=%s&id=%s">Edit</a>', 'add-pools','edit',$item['poolID']);
                }
                return '';
            default:
                return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }

    function get_columns()
    {
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'image' => __('Image', FV_DOMAIN),
            'poolName' => __('Name', FV_DOMAIN),
            'playerdraft_result'    =>  __('Player Draft', FV_DOMAIN),
            'result'    =>  __('Fight', FV_DOMAIN),
            'status'    =>  __('Status', FV_DOMAIN),
            'edit'    => '',
        );
        return $columns;
    }
    
    function get_sortable_columns() 
    {
        $sortable_columns = array(
            'poolName'  => array('poolName',false),
        );
        return $sortable_columns;
    }
    
    function usort_reorder( $a, $b ) 
    {
        // If no sort, default to title
        $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'poolID';
        // If no order, default to asc
        $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'DESC';
        // Determine sort order
        $result = strcmp( $a[$orderby], $b[$orderby] );
        // Send final sort direction to usort
        return ( $order === 'asc' ) ? $result : -$result;
    }
    
    function column_cb($item) 
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />', $item['poolID']
        );    
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
            $aCond = array("poolName LIKE '%%%$keyword%%'");
        }
        
        //get data
        list($total_items, $aPools) = self::$pools->getPoolsByFilter($aCond, 'poolID DESC', ($this->get_pagenum() - 1) * $item_per_page, $item_per_page);
        $aResults = self::$pools->parsePoolsData($aPools);
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