<?php if(get_option('fanvictor_no_cash') == 0):?>
    <div class="contentPlugin">
        <?php require_once('dlg_account_info.php');?>
        <?php require_once('dlg_request_payment.php');?>
        <?php require_once('dlg_coupon.php');?>
        <h1><?php echo __('Account information', FV_DOMAIN);?></h1>
        <div class="table">
            <div class="table_left"><?php echo __('Gateway', FV_DOMAIN);?></div>
            <div class="table_right"><?php if(isset($aUserPayment['gateway'])):?><?php echo $aUserPayment['gateway'];?><?php endif;?>&nbsp;</div>
        </div>
        <div class="table">
            <div class="table_left"><?php echo __('Email', FV_DOMAIN);?></div>
            <div class="table_right"><?php if(isset($aUserPayment['email'])):?><?php echo $aUserPayment['email'];?><?php endif;?>&nbsp;</div>
        </div>
        <div class="table">
            <div class="table_left"><?php echo __('Available balance', FV_DOMAIN);?></div>
            <div class="table_right"><?php echo $aUser['balance'];?></div>
        </div>
        <div class="table">
            <div class="table_left"><?php echo __('Pending request payment', FV_DOMAIN);?></div>
            <div class="table_right"><?php echo $withdrawPending;?>&nbsp;</div>
        </div>
        <a href="<?php echo FANVICTOR_URL_ADD_FUNDS;?>"><?php echo __('Add funds', FV_DOMAIN);?></a> | 
        <a href="#" onclick="return jQuery.payment.requestPayment('Request payment')"><?php echo __('Request payment', FV_DOMAIN);?></a>
        <?php if($isHasCoupon):?>
        |
        <a href="#" onclick="return jQuery.payment.showDlgCoupon('<?php echo __("Add money", FV_DOMAIN);?>')">
            <?php echo __('Add money by coupon code', FV_DOMAIN);?>
        </a>
        <?php endif;?>
    </div>
<?php else:?>
    <?php echo __('This function is currently unavailable.');?>
<?php endif; ?>
