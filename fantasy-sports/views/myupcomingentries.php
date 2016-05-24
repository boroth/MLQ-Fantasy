<div class="contentPlugin">
    <h3 class="widget-title">
        <?php echo __("My Upcoming Entries", FV_DOMAIN);?>
    </h3>
    <?php if($aLeagues != null):?>
        <div class="content">
            <div class="wrap_content">
                 <div >
                    <div class="tableLiveEntries">
                        <div class="tableTitle">
                            <div style="width: 5%"><?php echo __('ID', FV_DOMAIN)?></div>
                            <div style="width: 15%"><?php echo __('Date', FV_DOMAIN)?></div>
                            <div style="width: <?php echo get_option('fanvictor_no_cash') == 0 ? '23%' : '40%';?>"><?php echo __('Name', FV_DOMAIN)?></div>
                            <div style="width: 12%"><?php echo __('Type', FV_DOMAIN)?></div>
                            <div style="width: 7%"><?php echo __('Entries', FV_DOMAIN)?></div>
                            <div style="width: 13%"><?php echo __('Entry number', FV_DOMAIN)?></div>
                            <?php if(get_option('fanvictor_no_cash') == 0):?>
                            <div style="width: 10%"><?php echo __('Entry Fee', FV_DOMAIN)?></div>
                            <div style="width: 7%"><?php echo __('Prizes', FV_DOMAIN)?></div>
                            <?php endif;?>
                            <div style="width: 8%">&nbsp;</div>
                        </div>
                    </div>
                    <?php if($aLeagues != null):?>
                    <div class="tableLiveEntries tableLiveEntriesContent">
                        <?php foreach($aLeagues as $aLeague):?>
                        <div>
                            <div style="width: 5%"><span><?php echo __('ID', FV_DOMAIN)?></span><?php echo $aLeague['leagueID'];?></div>
                            <div style="width: 15%"><span><?php echo __('Date', FV_DOMAIN)?></span><?php echo $aLeague['startDate'];?></div>
                            <div style="width: <?php echo get_option('fanvictor_no_cash') == 0 ? '23%' : '40%';?>">
                                <span><?php echo __('Name', FV_DOMAIN)?></span><?php echo $aLeague['name'];?>
                            </div>
                            <div style="width: 12%"><span><?php echo __('Type', FV_DOMAIN)?></span><?php echo $aLeague['gameType'];?></div>
                            <div style="width: 7%"><span><?php echo __('Entries', FV_DOMAIN)?></span><?php echo $aLeague['entries'];?> / <?php echo $aLeague['size'];?></div>
                            <div style="width: 13%"><span><?php echo __('Entry number', FV_DOMAIN)?></span><?php echo $aLeague['entry_number'];?></div>
                            <?php if(get_option('fanvictor_no_cash') == 0):?>
                            <div style="width: 10%"><span><?php echo __('Entry Fee', FV_DOMAIN)?></span>$<?php echo $aLeague['entry_fee'];?></div>
                            <div style="width: 7%"><span><?php echo __('Prizes', FV_DOMAIN)?></span>$<?php echo $aLeague['prizes'];?></div>
                            <?php endif;?>
                            <div style="text-align: center;width: 8%">
                                <input type="button" class="btn btn-success btn-xs" value="<?php echo __('Edit', FV_DOMAIN)?>" onclick="window.location = '<?php echo FANVICTOR_URL_SUBMIT_PICKS.$aLeague['leagueID']."/?num=".$aLeague['entry_number'];?>'">
                            </div>
                        </div>
                        <?php endforeach;?>
                    </div>
                    <?php endif;?>
                </div>
            </div>
        </div>
    <?php else:?>
        <?php echo __("There are no upcoming entries", FV_DOMAIN);?>
    <?php endif; ?>
</div>