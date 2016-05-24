<div class="wrap">

    <h2>
        <?php echo __("Organization Settings", FV_DOMAIN);?>
        <a class="add-new-h2" href="<?php echo self::$urladdnew;?>"><?php echo __("Add New", FV_DOMAIN);?></a>
    </h2>
    
    <?php echo settings_errors();?>

    <form name="adminForm" action="<?php echo self::$url;?>" method="post">

        <input id="submitTask" type="hidden" name="task">

        <?php $myListTable->display();?>

        <input type="button" value="<?php echo __("Delete Selected");?>" class="button button-primary"  onclick="return jQuery.admin.action('', 'delete');">

    </form>

</div>

<div id="resultDialog" title="" style="display: none"></div>