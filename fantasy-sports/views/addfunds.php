<?php if(get_option('fanvictor_no_cash') == 0):?>
    <?php $paypal_type = get_option('fanvictor_paypal_type') != null ? get_option('fanvictor_paypal_type') : array();?>
    <div class="contentPlugin">
        <?php getMessage();?>
        <?php if($canplay):?>
            <?php if($paypal_type != null):?>
                <div id="msgAddCredits" class="public_message"></div>
                <form id="formAddCredits">
                    <div>
                        <?php echo __('Rate');?>: $1 <?php echo __('deposit equals', FV_DOMAIN);?> <?php echo get_option('fanvictor_cash_to_credit');?> <?php echo __('credits');?>
                    </div>
                    <?php if($fee_percentage > 0):?>
                    <div>
                        <?php echo __('Fee percentage');?>: <?php echo get_option('fanvictor_fee_percentage');?>%
                    </div>
                    <?php endif;?>
                    <p>
                        <?php echo __('How many credits do you want to add', FV_DOMAIN);?> (<?php echo sprintf(__('minimum $%s'), get_option('fanvictor_minimum_deposit'));?>):<br/>
                        <input type="text" name="credits" <?php if($fee_percentage > 0):?>onkeyup="jQuery.payment.addFundValue(this.value, '<?php echo $fee_percentage;?>')"<?php endif;?> /><br/>
                        <?php if($fee_percentage > 0):?>
                            <?php echo __('Real Value');?>: <span id="realCredits"></span>
                        <?php endif;?>
                    </p>
                    <?php if($isHasCoupon):?>
                    <p>
                        <?php echo __('Coupon code', FV_DOMAIN);?>:<br/>
                        <input type="text" name="coupon_code" />
                    </p>
                    <?php endif;?>
                    <?php if(in_array(FANVICTOR_PAYPAL_TYPE_NORMAL, $paypal_type) && in_array(FANVICTOR_PAYPAL_TYPE_PRO, $paypal_type)):?>
                        <select id="paypalType">
                            <option value="<?php echo FANVICTOR_PAYPAL_TYPE_PRO;?>">
                                <?php echo __("Credit card", FV_DOMAIN);?>
                            </option>
                            <option value="<?php echo FANVICTOR_PAYPAL_TYPE_NORMAL;?>">
                                <?php echo __("Paypal", FV_DOMAIN);?>
                            </option>
                        </select>
                        <div id="paypalPro">
                            <?php require_once(FANVICTOR__PLUGIN_DIR_VIEW.'Elements/paypal_pro.php');?>
                        </div>
                        <div id="paypalNormal" style="display: none">
                            <?php require_once(FANVICTOR__PLUGIN_DIR_VIEW.'Elements/paypal_normal.php');?>
                        </div>
                    <?php elseif(in_array(FANVICTOR_PAYPAL_TYPE_PRO, $paypal_type)):?>
                        <?php require_once(FANVICTOR__PLUGIN_DIR_VIEW.'Elements/paypal_pro.php');?>
                    <?php elseif(in_array(FANVICTOR_PAYPAL_TYPE_NORMAL, $paypal_type)):?>
                        <?php require_once(FANVICTOR__PLUGIN_DIR_VIEW.'Elements/paypal_normal.php');?>
                    <?php endif;?>
                    <input id="btnAddCredits" type="submit" class="button" value="<?php echo __('Add', FV_DOMAIN);?>" onclick="jQuery.payment.sendCredits()" />
                    <span class="waiting" style="display: none"><?php echo __('Please wait...', FV_DOMAIN);?></span>
                </form>
            <?php else:?> 
                <?php echo __("There are no available gateways", FV_DOMAIN);?>
            <?php endif;?>
        <?php else:?> 
            <?php echo __("Due to your location you cannot play in paid games so that they cannot add funds", FV_DOMAIN);?>
        <?php endif;?>
    </div>
    <script type="text/javascript">
        jQuery(window).load(function(){
            initData();
        });
        
        jQuery(document).on('change', '#paypalType', function(){
            initData();
        });
        
        function initData()
        {
            if(jQuery('#paypalType').val() == <?php echo FANVICTOR_PAYPAL_TYPE_PRO;?>)
            {
                jQuery('#paypalPro').show();
                jQuery('#paypalNormal').hide();
                jQuery('#paypalPro').find('input[type=text]').removeAttr('disabled');
                jQuery('#paypalPro').find('select').removeAttr('disabled');
                jQuery('#paypalNormal').find('input[type=text]').attr('disabled', 'disabled');
                jQuery('#paypalNormal').find('select').attr('disabled', 'disabled');
            }
            else
            {
                jQuery('#paypalPro').hide();
                jQuery('#paypalNormal').show();
                jQuery('#paypalNormal').find('input[type=text]').removeAttr('disabled');
                jQuery('#paypalNormal').find('select').removeAttr('disabled');
                jQuery('#paypalPro').find('input[type=text]').attr('disabled', 'disabled');
                jQuery('#paypalPro').find('select').attr('disabled', 'disabled');
            }
        }
    </script>
<?php else:?>
    <?php echo __('This function is currently unavailable.');?>
<?php endif; ?>
