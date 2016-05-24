<div id="dlgAccountInfo" style="display: none">
    <div id="msgAccountInfo" class="public_message"></div>
    <form id="formAccountInfo">
        <?php if(get_option('fanvictor_payout_method') == 'paypal'):?>
            <p>
                <?php echo __('Gateway', FV_DOMAIN);?>:<br/>
                <select name="val[gateway]">
                    <?php foreach($aGateways as $aGateway):?>
                    <option value="<?php echo $aGateway;?>" <?php if(isset($aUserPayment['gateway']) && $aUserPayment['gateway'] == $aGateway):?>selected=true"<?php endif;?>><?php echo $aGateway;?></option>
                    <?php endforeach;?>
                </select>
            </p>
            <p>
                <?php echo __('Email', FV_DOMAIN);?>:<br/>
                <input type="text" name="val[email]" size="60" value="<?php if(isset($aUserPayment['email'])):?><?php echo $aUserPayment['email'];?><?php endif;?>" />
            </p>
        <?php else:?>
            <p>
                <?php echo __('Name');?> (<?php echo __('required', FV_DOMAIN);?>):<br/>
                <input type="text" name="val[name]" size="60" value="<?php if(isset($aUserPayment['name'])):?><?php echo $aUserPayment['name'];?><?php endif;?>" />
            </p>
            <p>
                <?php echo __('House/Deparment');?>:<br/>
                <input type="text" name="val[house]" size="60" value="<?php if(isset($aUserPayment['house'])):?><?php echo $aUserPayment['house'];?><?php endif;?>" />
            </p>
            <p>
                <?php echo __('Street');?> (<?php echo __('required', FV_DOMAIN);?>):<br/>
                <input type="text" name="val[street]" size="60" value="<?php if(isset($aUserPayment['street'])):?><?php echo $aUserPayment['street'];?><?php endif;?>" />
            </p>
            <p>
                <?php echo __('Unit number', FV_DOMAIN);?>:<br/>
                <input type="text" name="val[unit_number]" size="60" value="<?php if(isset($aUserPayment['unit_number'])):?><?php echo $aUserPayment['unit_number'];?><?php endif;?>" />
            </p>
            <p>
                <?php echo __('City');?> (<?php echo __('required', FV_DOMAIN);?>):<br/>
                <input type="text" name="val[city]" size="60" value="<?php if(isset($aUserPayment['city'])):?><?php echo $aUserPayment['city'];?><?php endif;?>" />
            </p>
            <p>
                <?php echo __('State/Provine');?> (<?php echo __('required', FV_DOMAIN);?>):<br/>
                <input type="text" name="val[state]" size="60" value="<?php if(isset($aUserPayment['state'])):?><?php echo $aUserPayment['state'];?><?php endif;?>" />
            </p>
            <p>
                <?php echo __('Country');?> (<?php echo __('required', FV_DOMAIN);?>):<br/>
                <input type="text" name="val[country]" size="60" value="<?php if(isset($aUserPayment['country'])):?><?php echo $aUserPayment['country'];?><?php endif;?>" />
            </p>
        <?php endif;?>
    </form>
</div>