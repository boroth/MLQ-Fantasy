<script type="text/javascript">
    jQuery.league.loadLiveEntries('<?php echo FANVICTOR_URL_RANKINGS;?>', '<?php echo FANVICTOR_URL_SUBMIT_PICKS;?>');
    setInterval(function() { jQuery.league.loadLiveEntries('<?php echo FANVICTOR_URL_RANKINGS;?>', '<?php echo FANVICTOR_URL_SUBMIT_PICKS;?>') }, 60000);
</script>

<div class="contentPlugin">
    <h3 class="widget-title">
        <?php echo __("My Live Entries", FV_DOMAIN);?>
    </h3>
    <div class="content">
        <div class="wrap_content">
            <div id="tableLiveEntries">
                <div class="tableLiveEntries">
                    <div class="tableTitle">
                        <div style="width: 6%"><?php echo __('ID', FV_DOMAIN)?></div>
                        <div style="width: 15%"><?php echo __('Date', FV_DOMAIN)?></div>
                        <div style="width: <?php echo get_option('fanvictor_no_cash') == 0 ? '34%' : '50%';?>"><?php echo __('Name', FV_DOMAIN)?></div>
                        <div style="width: 10%"><?php echo __('Entries', FV_DOMAIN)?></div>
                        <?php if(get_option('fanvictor_no_cash') == 0):?>
                        <div style="width: 10%"><?php echo __('Entry Fee', FV_DOMAIN)?></div>
                        <div style="width: 7%"><?php echo __('Prizes', FV_DOMAIN)?></div>
                        <?php endif;?>
                        <div style="width: 6%"><?php echo __('Rank', FV_DOMAIN)?></div>
                        <div style="width: 12%">&nbsp;</div>
                    </div>
                </div>
                <div class="tableLiveEntries" id="tableLiveEntriesContent"></div>
            </div>
        </div>
    </div>
</div>