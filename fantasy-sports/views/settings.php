<div class="wrap">
<h2><?php echo __('Fan Victor Settings', FV_DOMAIN);?></h2>
<form method="post" action="options.php">
    <?php settings_fields( 'fanvictor-settings-group' ); ?>
    <?php do_settings_sections( 'fanvictor-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
            <th scope="row"><?php echo __('Api Token', FV_DOMAIN);?></th>
            <td>
                <input type="text" name="fanvictor_api_token" class="regular-text ltr" value="<?php echo esc_attr(get_option('fanvictor_api_token'));?>" />
                <p class="description"><?php echo __('This is your unique license key for your plugin. You must get this by logging into FanVictor.com. Then copy and paste your key here.', FV_DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php echo __('Api Url', FV_DOMAIN);?></th>
            <td>
                <input type="text" name="fanvictor_api_url" class="regular-text ltr" value="<?php echo esc_attr(get_option('fanvictor_api_url'));?>" />
                <p class="description"><?php echo __("This is the URL that your plugin will connect to in order to set and get data. This should not be modified unless you are instructed to by Fan Victor's technical staff.", FV_DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php echo __('Api Admin Url', FV_DOMAIN);?></th>
            <td>
                <input type="text" name="fanvictor_api_url_admin" class="regular-text ltr" value="<?php echo esc_attr(get_option('fanvictor_api_url_admin'));?>" />
                <p class="description"><?php echo __("This is the URL that your plugin will connect to in order to set and get admin data. This should not be modified unless you are instructed to by Fan Victor's technical staff.", FV_DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php echo __('Entry Fee', FV_DOMAIN);?></th>
            <td class="array-holder">
                <?php $aEntrys = get_option('fanvictor_entry_fee');?>
                <?php 
                    if($aEntrys != null):
                    foreach($aEntrys as $aEntry):
                ?>
                <div class="array-item">
                    <input type="text" name="fanvictor_entry_fee[]" class="regular-text ltr entry_fee" value="<?php echo $aEntry;?>" />
                    <a href="#" onclick="return jQuery.option.removeArray(this)"><?php echo __('Remove', FV_DOMAIN);?></a>
                </div>
                <?php 
                    endforeach;
                    else:;
                ?>
                <div class="array-item">
                    <input type="text" name="fanvictor_entry_fee[]" class="regular-text ltr entry_fee"/>
                    <a href="#" onclick="return jQuery.option.removeArray(this)"><?php echo __('Remove', FV_DOMAIN);?></a>
                </div>
                <?php endif;?>
                <input type="button" data-name="fanvictor_entry_fee[]" value="<?php echo __('Add', FV_DOMAIN);?>" class="button button-primary" style="margin-top: 5px" onclick="return jQuery.option.addArray(this)" >
                <p class="description"><?php echo __('These are the values that will appear in the Entry Fee drop down menu when creating a new contest.', FV_DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php echo __('League Size', FV_DOMAIN);?></th>
            <td class="array-holder">
                <?php $aSizes = get_option('fanvictor_league_size');?>
                <?php 
                    if($aSizes != null):
                    foreach($aSizes as $aSize):
                ?>
                <div class="array-item">
                    <input type="text" name="fanvictor_league_size[]" class="regular-text ltr entry_fee" value="<?php echo $aSize;?>" />
                    <a href="#" onclick="return jQuery.option.removeArray(this)"><?php echo __('Remove', FV_DOMAIN);?></a>
                </div>
                <?php 
                    endforeach;
                    else:;
                ?>
                <div class="array-item">
                    <input type="text" name="fanvictor_league_size[]" class="regular-text ltr entry_fee"/>
                    <a href="#" onclick="return jQuery.option.removeArray(this)"><?php echo __('Remove', FV_DOMAIN);?></a>
                </div>
                <?php endif;?>
                <input type="button" data-name="fanvictor_league_size[]" value="<?php echo __('Add', FV_DOMAIN);?>" class="button button-primary" style="margin-top: 5px" onclick="return jQuery.option.addArray(this)" >
                <p class="description"><?php echo __('This are the values that will appear in the League Size drop down menu when creating a new contest.', FV_DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php echo __('Payout Percentage', FV_DOMAIN);?>(%)</th>
            <td>
                <input type="text" name="fanvictor_winner_percent" class="regular-text ltr" value="<?php echo esc_attr(get_option('fanvictor_winner_percent'));?>" />
                <p class="description"><?php echo __('This is the percentage that is paid out to winners. For example if the payout is 90%, then this means the site would have a rake of 10% per contest. 10% Would be the profit the site makes on a contest.', FV_DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php echo __('First Place Payout Percentage', FV_DOMAIN);?>(%)</th>
            <td>
                <input type="text" name="fanvictor_first_place_percent" class="regular-text ltr" value="<?php echo esc_attr(get_option('fanvictor_first_place_percent'));?>" />
                <p class="description"><?php echo __('This is the percentage that is paid out to the first place winner in a top 3 get paid contest.', FV_DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php echo __('Second Place Payout Percentage', FV_DOMAIN);?>(%)</th>
            <td>
                <input type="text" name="fanvictor_second_place_percent" class="regular-text ltr" value="<?php echo esc_attr(get_option('fanvictor_second_place_percent'));?>" />
                <p class="description"><?php echo __('This is the percentage that is paid out to the second place winner in a top 3 get paid contest.', FV_DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php echo __('Third Place Payout Percentage', FV_DOMAIN);?>(%)</th>
            <td>
                <input type="text" name="fanvictor_third_place_percent" class="regular-text ltr" value="<?php echo esc_attr(get_option('fanvictor_third_place_percent'));?>" />
                <p class="description"><?php echo __('This is the percentage that is paid out to the third place winner in a top 3 get paid contest.', FV_DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php echo __('Processing fee percentage', FV_DOMAIN);?></th>
            <td>
                <input type="text" name="fanvictor_fee_percentage" class="regular-text ltr" value="<?php echo esc_attr(get_option('fanvictor_fee_percentage'));?>" />%
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php echo __('Credit Exchange Rate (Deposit)', FV_DOMAIN);?></th>
            <td>
                <input type="text" name="fanvictor_cash_to_credit" class="regular-text ltr" value="<?php echo esc_attr(get_option('fanvictor_cash_to_credit'));?>" />
                <p class="description"><?php echo __('In addition to making money from a rake per contest. You can also set a rate for when user purchase credits. For example, $1 USD can buy 10 Credits. But to withdraw funds, it might cost 11 Credits to cash out $1 USD. In this field enter how many credits a user will receive for $1. It is not uncommon to say 1. So $1 buys 1 credit.', FV_DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php echo __('Credit Exchange Rate (Withdraw)', FV_DOMAIN);?></th>
            <td>
                <input type="text" name="fanvictor_credit_to_cash" class="regular-text ltr" value="<?php echo esc_attr(get_option('fanvictor_credit_to_cash'));?>" />
                <p class="description"><?php echo __('In this field enter how many credits a user needs to withdraw to receive $1.', FV_DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php echo __('Allow Create Contest', FV_DOMAIN);?></th>
            <td>
                <select class="postform" name="fanvictor_create_contest">
                    <option <?php echo get_option('fanvictor_create_contest') == 1 ? 'selected="true"' : '';?> value="1"><?php echo __('True', FV_DOMAIN);?></option>
                    <option <?php echo get_option('fanvictor_create_contest') == 0 ? 'selected="true"' : '';?> value="0"><?php echo __('False', FV_DOMAIN);?></option>
                </select>
                <p class="description"><?php echo __('Allow user create new contest at frontend.', FV_DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php echo __('No cash', FV_DOMAIN);?></th>
            <td>
                <select class="postform" name="fanvictor_no_cash">
                    <option <?php echo get_option('fanvictor_no_cash') == 0 ? 'selected="true"' : '';?> value="0"><?php echo __('False', FV_DOMAIN);?></option>
                    <option <?php echo get_option('fanvictor_no_cash') == 1 ? 'selected="true"' : '';?> value="1"><?php echo __('True', FV_DOMAIN);?></option>
                </select>
                <p class="description"><?php echo __('This will hide ALL references to Entry Fee and Prizes.  This is mainly used for site who are NOT offering CASH games.', FV_DOMAIN);?></p>
            </td>
        </tr>
		<tr valign="top">
            <th scope="row"><?php echo __('No invite user list', FV_DOMAIN);?></th>
            <td>
                <select class="postform" name="fanvictor_no_invite_user_list">
                    <option <?php echo get_option('fanvictor_no_invite_user_list') == 0 ? 'selected="true"' : '';?> value="0"><?php echo __('False', FV_DOMAIN);?></option>
                    <option <?php echo get_option('fanvictor_no_invite_user_list') == 1 ? 'selected="true"' : '';?> value="1"><?php echo __('True', FV_DOMAIN);?></option>
                </select>
                <p class="description"><?php echo __('This will hide user list when invite user to a contest.', FV_DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php echo __('Payout Method', FV_DOMAIN);?></th>
            <td>
                <select class="postform" name="fanvictor_payout_method">
                    <option <?php echo get_option('fanvictor_payout_method') == 'paypal' ? 'selected="true"' : '';?> value="paypal">Paypal</option>
                    <option <?php echo get_option('fanvictor_payout_method') == 'cheque' ? 'selected="true"' : '';?> value="cheque">Cheque</option>
                </select>
                <p class="description"><?php echo __('Select method to withdraw.', FV_DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php echo __('Paypal Type', FV_DOMAIN);?></th>
            <td>
                <?php $paypal_type = get_option('fanvictor_paypal_type') != null ? get_option('fanvictor_paypal_type') : array();?>
                <select class="postform" name="fanvictor_paypal_type[]" multiple="">
                    <option <?php echo in_array(FANVICTOR_PAYPAL_TYPE_NORMAL, $paypal_type) ? 'selected="true"' : '';?> value="<?php echo FANVICTOR_PAYPAL_TYPE_NORMAL;?>"><?php echo __('Normal', FV_DOMAIN);?></option>
                    <option <?php echo in_array(FANVICTOR_PAYPAL_TYPE_PRO, $paypal_type) ? 'selected="true"' : '';?> value="<?php echo FANVICTOR_PAYPAL_TYPE_PRO;?>"><?php echo __('Pro', FV_DOMAIN);?></option>
                </select>
                <p class="description"><?php echo __('If value is True, Paypal will change to testing mode.', FV_DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php echo __('Paypal Sandbox', FV_DOMAIN);?></th>
            <td>
                <select class="postform" name="paypal_test">
                    <option <?php echo get_option('paypal_test') == 1 ? 'selected="true"' : '';?> value="1"><?php echo __('True', FV_DOMAIN);?></option>
                    <option <?php echo get_option('paypal_test') == 0 ? 'selected="true"' : '';?> value="0"><?php echo __('False', FV_DOMAIN);?></option>
                </select>
                <p class="description"><?php echo __('If value is True, Paypal will change to testing mode.', FV_DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php echo __('Paypal Email', FV_DOMAIN);?></th>
            <td>
                <input type="text" name="paypal_email_account" class="regular-text ltr" value="<?php echo esc_attr(get_option('paypal_email_account'));?>" />
                <p class="description"><?php echo __('The email that represents your PayPal account.', FV_DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php echo __('Paypal Pro Username', FV_DOMAIN);?></th>
            <td>
                <input type="text" name="fanvictor_paypal_pro_username" class="regular-text ltr" value="<?php echo esc_attr(get_option('fanvictor_paypal_pro_username'));?>" />
                <p class="description"><?php echo __('The Username used for PayPal Pro account.', FV_DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php echo __('Paypal Pro Passowrd', FV_DOMAIN);?></th>
            <td>
                <input type="text" name="fanvictor_paypal_pro_password" class="regular-text ltr" value="<?php echo esc_attr(get_option('fanvictor_paypal_pro_password'));?>" />
                <p class="description"><?php echo __('The Password used for PayPal Pro account.', FV_DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php echo __('Paypal Pro Signature', FV_DOMAIN);?></th>
            <td>
                <input type="text" name="fanvictor_paypal_pro_signature" class="regular-text ltr" value="<?php echo esc_attr(get_option('fanvictor_paypal_pro_signature'));?>" />
                <p class="description"><?php echo __('The Signature used for PayPal Pro account.', FV_DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php echo __('Minimum Deposit', FV_DOMAIN);?></th>
            <td>
                <input type="text" name="fanvictor_minimum_deposit" class="regular-text ltr" value="<?php echo esc_attr(get_option('fanvictor_minimum_deposit'));?>" />
                <p class="description"><?php echo __('Minimum Deposit Value', FV_DOMAIN);?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php echo __('Choice account', FV_DOMAIN);?></th>
            <td>
                <input type="text" name="fanvictor_choice_account" class="regular-text ltr" placeholder="username/password" value="<?php echo esc_attr(get_option('fanvictor_choice_account'));?>"/>
                <p class="description"><?php echo __('Input as the following format: username/password', FV_DOMAIN);?></p>
                <p class="description"><?php echo  sprintf(__('Choice Merchant Solutions offers the widest selection of credit card processing solutions and products. Click %s to register', FV_DOMAIN), "<a href='http://fantasysportspayments.com/' target='_blank'>".__('here')."</a>");?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php echo __('Show import picks', FV_DOMAIN);?></th>
            <td>
                <input type="checkbox"  name="fanvictor_show_import_pick" <?php checked( 1, get_option( 'fanvictor_show_import_pick' ) ); ?>  value="1"/>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php echo __('Get email from bettor when join a contest', FV_DOMAIN);?></th>
            <td>
                <input type="checkbox"  name="fanvictor_get_email_from_better_join_contest" <?php checked( 1, get_option( 'fanvictor_get_email_from_better_join_contest' ) ); ?>  value="1"/>
            </td>
        </tr>
    </table>
    <?php submit_button(); ?>
</form>
</div>
