<input type="hidden" value='<?php echo json_encode($league);?>' id="leagueInfo" />
<?php if($users != null):?>
    <input type="hidden" value='<?php echo json_encode($users);?>' id="pickData" />
    <label><?php echo __("Game type", FV_DOMAIN);?></label>:<?php echo $league['gameType'];?>
    <br/>
    <label><?php echo __("User", FV_DOMAIN);?></label>
    <select id="cbUsers" onchange="jQuery.admin.showPicksDetail();">
        <?php foreach($users as $user):?> 
            <option value="<?php echo $user['userID'];?>"><?php echo $user['user_login'];?></option>
        <?php endforeach;?>
    </select>
    <?php if($league['multi_entry']):?>
        <br/>
        <label><?php echo __("Entry number", FV_DOMAIN);?></label>
        <?php foreach($users as $user):?> 
        <select id="cbEntry<?php echo $user['userID'];?>" class="cbEntry" style="display: none"  onchange="jQuery.admin.showPicksDetail();">
            <?php foreach($user['entries'] as $entry):?> 
                <option value="<?php echo $entry['entry_number'];?>"><?php echo $entry['entry_number'];?></option>
            <?php endforeach;?>
        </select>
        <?php endforeach;?>
    <?php endif;?>
    <table id="tbPickDetail" class="wp-list-table widefat books">
        <thead>
            <tr>
                <th style="width: 40px"><?php echo __("ID", FV_DOMAIN);?></th>
                <?php if($league['gameType'] == 'PLAYERDRAFT' && $league['is_team'] == 1):?>
                    <th style="width: 200px"><?php echo __("Team", FV_DOMAIN);?></th>
                <?php elseif($league['gameType'] != 'PLAYERDRAFT'):?> 
                    <th style="width: 200px"><?php echo __("Fixture", FV_DOMAIN);?></th>
                <?php endif;?>
                <th><?php echo __("Pick Name", FV_DOMAIN);?></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
<?php else:?> 
    <center><?php echo __('No picks', FV_DOMAIN);?></center>
<?php endif;?>