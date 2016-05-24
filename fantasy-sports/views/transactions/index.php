<div class="wrap">

    <h2><?php echo __("Manage Transactions", FV_DOMAIN);?></h2>

    <?php echo settings_errors();?>

    <form method="get">

        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'];?>" />

        <?php $myListTable->search_box('search', 'search_id'); ?>

    </form>

    <?php $myListTable->display();?>

</div>

<div id="dlgStatistic" style="display: none"><center><?php echo __("Loading...Please wait!", FV_DOMAIN);?></center></div>