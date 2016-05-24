<div class="wrap">
    <h2><?php echo __("Event Statistics", FV_DOMAIN);?></h2>
    <?php echo settings_errors();?>
    <form method="get">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'];?>" />
        <?php $myListTable->search_box('search', 'search_id'); ?>
    </form>
    <?php $myListTable->display();$data = $myListTable->getData()?>
    <p>
        <b><?php echo __("Total Cash Processed", FV_DOMAIN);?>:</b>
        <?php echo $data['accumCash'];?>
    </p>
    <p>
        <b>Total Pay Out:</b>
        <?php echo $data['accumPayOut'];?>
    </p>
    <p>
        <b>Total Profit:</b>
        <?php echo $data['accumProfit'];?>
    </p>
</div>
<div id="dlgStatistic" style="display: none"><center><?php echo __("Loading...Please wait!", FV_DOMAIN);?></center></div>