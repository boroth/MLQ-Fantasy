<?php foreach($aFights as $aFight):?>
<div class="fight_container">
    <div class="title_area">
        <div class="fight_number_title">*<?php echo __("Fixture", FV_DOMAIN);?> <?php echo $aFight['count'];?></div>
        <a onclick="return jQuery.fight.removeFight(this);" class="fight_action fight_remove" href="#">
            <img src="<?php echo FANVICTOR__PLUGIN_URL_IMAGE.'delete.png';?>" alt="Delete" title="Delete" />
        </a>&nbsp;&nbsp;
        <a onclick="return jQuery.fight.addFight(this);" class="fight_action fight_add" href="#">
            <img src="<?php echo FANVICTOR__PLUGIN_URL_IMAGE.'add.png';?>" alt="Add" title="Add" />
        </a>
        <input type="hidden" name="val[fight][]" class="fight" value="" />
        <input type="hidden" data-name="fightID" value="<?php echo $aFight['fightID'];?>" />
    </div>
    <table>
        <tr>
            <th>
                <span class="for_fighter"><?php echo __("Fighter", FV_DOMAIN);?> 1</span>
                <span class="for_team"><?php echo __("Team", FV_DOMAIN);?> 1</span>
            </th>
            <th>
                <span class="for_fighter"><?php echo __("Fighter", FV_DOMAIN);?> 2</span>
                <span class="for_team"><?php echo __("Team", FV_DOMAIN);?> 2</span>
            </th>
        </tr>
        <tr>
            <td>
                <select data-name="fighterID1" data-sel="<?php echo $aFight['fighterID1'];?>" class="cbfighter for_fighter"></select>
                <select data-name="fighterID1" data-sel="<?php echo $aFight['fighterID1'];?>" style="display: none" class="cbteam for_team"></select>
            </td>
            <td>
                <select data-name="fighterID2" data-sel="<?php echo $aFight['fighterID2'];?>" class="cbfighter for_fighter"></select>
                <select data-name="fighterID2" data-sel="<?php echo $aFight['fighterID2'];?>" style="display: none" class="cbteam for_team"></select>
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <div class="table">
                    <div class="table_left">
                        <?php echo __("Fixture Name");?>  <span class="description">(<?php echo __("required", FV_DOMAIN);?>)</span>:
                    </div>
                    <div class="table_right">
                        <input type="text" data-name="fight_name" value="<?php echo $aFight['name'];?>" size="40"/>
                    </div>
                    <div class="clear"></div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <div class="table">
                    <div class="table_left">
                        <?php echo __("Start Date");?>  <span class="description">(<?php echo __("required", FV_DOMAIN);?>)</span>:
                    </div>
                    <div class="table_right">
                        <input type="text" class="fightDatePicker" data-name="fight_startDate" value="<?php echo $aFight['startDateOnly'];?>" size="40"/>
                        <?php echo __("Hour", FV_DOMAIN);?>:
                        <select data-name="fight_startHour">
                            <?php foreach($aPoolHours as $aPoolHour):?>
                            <option value="<?php echo $aPoolHour;?>" <?php echo $aFight['startHour'] == $aPoolHour ? 'selected="true"' : '';?>><?php echo $aPoolHour;?></option>
                            <?php endforeach;?>
                        </select>
                        <?php echo __("Minute", FV_DOMAIN);?>:
                        <select data-name="fight_startMinute">
                            <?php foreach($aPoolMinutes as $aPoolMinute):?>
                            <option value="<?php echo $aPoolMinute;?>" <?php echo $aFight['startMinute'] == $aPoolMinute ? 'selected="true"' : '';?>><?php echo $aPoolMinute;?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>
            </td>
        </tr>
        <tr class="for_fighter">
            <td colspan="6">
                <div class="table">
                    <div class="table_left">
                        <?php echo __("Championship Fight", FV_DOMAIN);?>:
                    </div>
                    <div class="table_right">
                        <input type="checkbox" data-name="champFight" <?php echo isset($aFight['champFight']) && $aFight['champFight'] == 'YES' ? 'checked="true"' : '';?> value="1" id="champFight" />
                    </div>
                    <div class="clear"></div>
                </div>
            </td>
        </tr>
        <tr class="for_fighter">
            <td colspan="6">
                <div class="table">
                    <div class="table_left">
                        <?php echo __("Amateur Fight", FV_DOMAIN);?>:
                    </div>
                    <div class="table_right">
                        <input type="checkbox" data-name="amateurFight" <?php echo isset($aFight['amateurFight']) && $aFight['amateurFight'] == 'YES' ? 'checked="true"' : '';?> value="1" id="amateurFight" />
                    </div>
                    <div class="clear"></div>
                </div>
            </td>
        </tr>
        <tr class="for_fighter">
            <td colspan="6">
                <div class="table">
                    <div class="table_left">
                        <?php echo __("Main Card Fight", FV_DOMAIN);?>:
                    </div>
                    <div class="table_right">
                        <input type="checkbox" data-name="mainFight" <?php echo isset($aFight['mainFight']) && $aFight['mainFight'] == 'YES' ? 'checked="true"' : '';?> value="1" id="mainFight" />
                    </div>
                    <div class="clear"></div>
                </div>
            </td>
        </tr>
        <tr class="for_fighter">
            <td colspan="6">
                <div class="table">
                    <div class="table_left">
                        <?php echo __("Preliminary Card Fight", FV_DOMAIN);?>:
                    </div>
                    <div class="table_right">
                        <input type="checkbox" data-name="prelimFight" <?php echo isset($aFight['prelimFight']) && $aFight['prelimFight'] == 'YES' ? 'checked="true"' : '';?> value="1" id="prelimFight" />
                    </div>
                    <div class="clear"></div>
                </div>
            </td>
        </tr>
        <tr class="for_fighter">
            <td colspan="6">
                <div class="table">
                    <div class="table_left">
                        <?php echo __("Round", FV_DOMAIN);?>:
                    </div>
                    <div class="table_right">
                        <select data-name="rounds">
                            <option value="">--</option>
                            <?php foreach($aRounds as $aRound):?>
                            <option value="<?php echo $aRound;?>" <?php echo isset($aFight['rounds']) && $aFight['rounds'] == $aRound ? 'selected="true"' : '';?>><?php echo $aRound;?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="clear"></div>
                </div>
            </td>
        </tr>
    </table>
</div>
<?php endforeach;?>