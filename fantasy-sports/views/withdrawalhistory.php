<?php if(get_option('fanvictor_no_cash') == 0):?>
    <div class="contentPlugin">
        <div class="contentPlugin">
            <h1><?php echo __('Withdrawal History', FV_DOMAIN);?></h1>
            <?php if($aWithdraws != null):?>
                <div>
                    <div class="tableLiveEntries table6">
                        <div class="tableTitle">
                            <div style="width:90px"><?php echo __('Request date', FV_DOMAIN);?></div>
                            <div style="width:80px"><?php echo __('Credits', FV_DOMAIN);?></div>
                            <div style="width:100px"><?php echo __('Rate', FV_DOMAIN);?></div>
                            <div style="width:80px"><?php echo __('Real money', FV_DOMAIN);?></div>
                            <div style="width:200px"><?php echo __('Reason', FV_DOMAIN);?></div>
                            <div style="width:80px"><?php echo __('Status', FV_DOMAIN);?></div>
                            <div style="width:100px"><?php echo __('Response date', FV_DOMAIN);?></div>
                            <div><?php echo __('Response message', FV_DOMAIN);?></div>
                        </div>
                    </div>
                    <div class="tableLiveEntries tableLiveEntriesContent">
                    <?php foreach($aWithdraws as $aWithdraw):?>
                        <div>
                            <div style="width:90px"><?php echo $aWithdraw['requestDate'];?></div>
                            <div style="width:80px"><?php echo $aWithdraw['amount'];?></div>
                            <div style="width:100px"><?php echo get_option('fanvictor_credit_to_cash');?> <?php echo __('credits equals', FV_DOMAIN);?> $1</div>
                            <div style="width:80px"><?php echo $aWithdraw['real_amount'];?></div>
                            <div style="width:200px"><?php echo $aWithdraw['reason'];?></div>
                            <div style="width:80px"><?php echo $aWithdraw['status'];?></div>
                            <div style="width:100px"><?php echo $aWithdraw['processedDate'];?></div>
                            <div><?php echo $aWithdraw['response_message'];?></div>
                        </div>
                    <?php endforeach;?>
                    </div>
                </div>
            <?php else:?>
                <?php echo __("There are no withdrawal histories", FV_DOMAIN);?>
            <?php endif; ?>
        </div>
    </div>
<?php else:?>
    <?php echo __('This function is currently unavailable.');?>
<?php endif; ?>