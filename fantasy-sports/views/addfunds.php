<?php if(get_option('fanvictor_no_cash') == 0):?>
    <div class="contentPlugin">
        <?php getMessage();?>
        <?php if($canplay):?>
            <?php if($aGateways != null):?>
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
                <p>
                    <?php echo __('Gateway', FV_DOMAIN);?>:<br/>
                    <select name="gateway">
                        <?php foreach($aGateways as $aGateway):?>
                        <option value="<?php echo $aGateway;?>"><?php echo $aGateway;?></option>
                        <?php endforeach;?>
                    </select>
                </p>
                <br/>
                <div id="dp-data" style="display: none;border-top: 2px dotted #ddd">
                    <p>
                        <?php echo __('Firstname', FV_DOMAIN);?>
                        <br/>
                        <input name="dp[fname]" value=""/>
                    </p>
                    <p>
                        <?php echo __('Lastname', FV_DOMAIN);?>
                        <br/>
                        <input name="dp[lname]" value=""/>
                    </p>
                    <p>
                        <?php echo __('Company', FV_DOMAIN);?>
                        <br/>
                        <input name="dp[company]" value=""/>
                    </p>
                    <p>
                        <?php echo __('Address', FV_DOMAIN);?>
                        <br/>
                        <input name="dp[address]" value=""/>
                    </p>
                    <p>
                        <?php echo __('City', FV_DOMAIN);?>
                        <br/>
                        <input name="dp[city]" value=""/>
                    </p>
                    <p>
                        <?php echo __('State', FV_DOMAIN);?>
                        <br/>
                        <input name="dp[state]" value=""/>
                    </p>
                    <p>
                        <?php echo __('Zip', FV_DOMAIN);?>
                        <br/>
                        <input name="dp[zip]" value=""/>
                    </p>
                    <p>
                        <?php echo __('Country', FV_DOMAIN);?>
                        <br/>
                        <input name="dp[country]" value=""/>
                    </p>
                    <p>
                        <?php echo __('Phone', FV_DOMAIN);?>
                        <br/>
                        <input name="dp[phone]" value=""/>
                    </p>
                    <p>
                        <?php echo __('Fax', FV_DOMAIN);?>
                        <br/>
                        <input name="dp[fax]" value=""/>
                    </p>
                    <p>
                        <?php echo __('Email', FV_DOMAIN);?>
                        <br/>
                        <input name="dp[email]" value=""/>
                    </p>
                    <p>
                        <?php echo __('Credit card number', FV_DOMAIN);?>
                        <br/>
                        <input name="dp[cc]" value=""/>
                    </p>
                    <p>
                        <?php echo __('Credit card expiration date', FV_DOMAIN);?>
                        <br/>
                        <input name="dp[ccexp]" value="" maxlength="5"/>
                    </p>
                    <!--<p>
                        <?php echo __('Website', FV_DOMAIN);?>
                        <br/>
                        <input name="dp[website]"/>
                    </p>-->
                </div>
                <input id="btnAddCredits" type="submit" class="button" value="<?php echo __('Add', FV_DOMAIN);?>" onclick="jQuery.payment.sendCredits()" />
                <span class="waiting" style="display: none"><?php echo __('Please wait...', FV_DOMAIN);?></span>
            </form>
            <script type="text/javascript">
            (function($){
                $('select[name="gateway"]').change(function(){
                    if($(this).val()=="CHOICE")
                        $("#dp-data").show();
                    })
            })(jQuery);
            </script>
            <?php else:?> 
                <?php echo __("There are no available gateways", FV_DOMAIN);?>
            <?php endif;?>
        <?php else:?> 
            <?php echo __("Due to your location you cannot play in paid games so that they cannot add funds", FV_DOMAIN);?>
        <?php endif;?>
    </div>
<?php else:?>
    <?php echo __('This function is currently unavailable.');?>
<?php endif; ?>
