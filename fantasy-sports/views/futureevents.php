<div class="contentPlugin">
    <?php if($futureEvents != null):?>
        <form action="{url link='fanvictor.submitpicks'}" medivod="POST">
            <input type="hidden" class="leagueID" name="leagueID" />
            <input type="hidden" class="poolID" name="poolID" />
            <div id="leagues_future_events">

                <div class="tableLiveEntries table6">
                    <div class="tableTitle">
                        <div style="width: 6%"><?php echo __('ID', FV_DOMAIN);?></div>
                        <div style="width: 39%"><?php echo __('Name', FV_DOMAIN);?></div>
                        <div style="width: 15%"><?php echo __('Sport', FV_DOMAIN);?></div>
                        <div style="width: 15%"><?php echo __('Start Date', FV_DOMAIN);?></div>
                        <div style="width: 15%"><?php echo __('Cut Date', FV_DOMAIN);?></div>
                        <div style="width: 10%"><?php echo __('Fixture', FV_DOMAIN);?></div>
                    </div>
                </div>
                <div class="tableLiveEntries tableLiveEntriesContent  table6">
                    <?php foreach($futureEvents as $item):?>
                    <div >
                        <div style="width: 6%"><span><?php echo __('ID', FV_DOMAIN);?></span><?php echo $item['poolID']?></div>
                        <div style="width: 39%"><span><?php echo __('Name', FV_DOMAIN);?></span><?php echo $item['poolName']?></div>
                        <div style="width: 15%"><span><?php echo __('Sport', FV_DOMAIN);?></span><?php echo $item['organization']?></div>
                        <div style="width: 15%"><span><?php echo __('Start Date', FV_DOMAIN);?></span><?php echo $item['startDate']?></div>
                        <div style="width: 15%"><span><?php echo __('Cut Date', FV_DOMAIN);?></span><?php echo $item['cutDate']?></div>
                        <div style="width: 10%">
                            <?php if($item['only_playerdraft'] == 0):?>
                            <a href="#" onclick="return viewPoolFixture(<?php echo $item['poolID'];?>, '<?php echo __("fixtures", FV_DOMAIN);?>')">
                                <?php echo __("View fixtures", FV_DOMAIN);?>
                            </a>
                            <?php endif;?>
                        </div>
                    </div>
                    <?php endforeach;?>
                </div>
            </div>
        </form>
        <div id="dlgFixture" style="display: none"></div>
    <?php else:?>
        <?php echo __("There are no future events", FV_DOMAIN);?>
    <?php endif;?>
</div>