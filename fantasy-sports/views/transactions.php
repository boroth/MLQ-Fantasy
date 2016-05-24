<?php if(get_option('fanvictor_no_cash') == 0):?> 
    <div class="contentPlugin">
        <h1><?php echo __('Transaction History', FV_DOMAIN);?></h1>
        <?php getMessage();?>
        <?php if($aFundHistorys != null):?>
            <div>
                <div class="tableLiveEntries ">
                    <div class="tableTitle">
                        <div><?php echo __('Date', FV_DOMAIN);?></div>
                        <div><?php echo __('Operation', FV_DOMAIN);?></div>
                        <div><?php echo __('Type', FV_DOMAIN);?></div>
                        <div><?php echo __('Contest', FV_DOMAIN);?></div>
                        <div><?php echo __('Gateway', FV_DOMAIN);?></div>
                        <div><?php echo __('Status', FV_DOMAIN);?></div>
                        <div><?php echo __('Transactionid', FV_DOMAIN);?></div>
                        <div><?php echo __('Amount', FV_DOMAIN);?></div>
                        <div><?php echo __('New balance', FV_DOMAIN);?></div>

                    </div>
                </div>
                <div class="tableLiveEntries tableLiveEntriesContent">
                    <?php foreach($aFundHistorys as $aFundHistory):?>
                    <div>
                        <div><span><?php echo __('Date', FV_DOMAIN);?></span><?php echo $aFundHistory['date'];?></div>
                        <div><span><?php echo __('Operation', FV_DOMAIN);?></span><?php echo $aFundHistory['operation'];?></div>
                        <div><span><?php echo __('Type', FV_DOMAIN);?></span><?php echo $aFundHistory['type'];?></div>
                        <div>
                            <span><?php echo __('Contest', FV_DOMAIN);?></span>
                            <?php echo $aFundHistory['name_contest'];?>
                            <?php if($aFundHistory['leagueID'] > 0):?> (<?php echo $aFundHistory['leagueID'];?>)<?php endif;?>
                        </div>
                        <div><span><?php echo __('Gateway', FV_DOMAIN);?></span><?php echo !empty($aFundHistory['gateway']) ? $aFundHistory['gateway'] : '&nbsp;';?></div>
                        <div><span><?php echo __('Status', FV_DOMAIN);?></span><?php echo !empty($aFundHistory['status']) ? $aFundHistory['status'] : '&nbsp;';?></div>
                        <div><span><?php echo __('Transactionid', FV_DOMAIN);?></span><?php echo !empty($aFundHistory['transactionID']) ? $aFundHistory['transactionID'] : '&nbsp;';?></div>
                        <div><span><?php echo __('Amount', FV_DOMAIN);?></span><?php echo $aFundHistory['amount'];?></div>
                        <div><span><?php echo __('New balance', FV_DOMAIN);?></span><?php echo $aFundHistory['new_balance'];?></div>
                    </div>
                    <?php endforeach;?>
                </div>
            </div>
        <?php else:?>
            <?php echo __("There are no transaction histories", FV_DOMAIN);?>
        <?php endif; ?>
    </div>
<?php else:?>
    <?php echo __('This function is currently unavailable.');?>
<?php endif; ?>