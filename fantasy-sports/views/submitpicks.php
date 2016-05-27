<div class="contentPlugin">
<form action="<?php echo FANVICTOR_URL_SUBMIT_PICKS;?>" method="POST" id="submitPicksForm" name="submitPicksForm">
    <h3 class="widget-title">Contest ID: <?php echo $aLeague['leagueID'];?> - <?php echo $aLeague['name'];?></h3>
    <input type="hidden" value="<?php echo $aLeague['poolID'];?>" name="poolID">
    <input type="hidden" value="<?php echo $aLeague['leagueID'];?>" name="leagueID">
    <input type="hidden" value="<?php echo $entry_number;?>" name="entry_number">
    <div class="contestStructure">
        <div class="left">
            <div>
                <div class="label"><?php echo __('Prize structure', FV_DOMAIN);?>:</div>
                <?php echo $aLeague['prize_structure'];?>
            </div>
            <div>
                <div class="label"><?php echo __('Sport', FV_DOMAIN);?>:</div>
                <?php echo $aPool['sport_name'];?>
            </div>
            <div>
                <div class="label"><?php echo __('Event', FV_DOMAIN);?>:</div>
                <?php echo $aPool['poolName'];?>
            </div>
            <div>
                <div class="label"><?php echo __('Game Type', FV_DOMAIN);?>:</div>
                <?php echo $aLeague['gameType'];?>
                <?php if($aLeague['multi_entry'] == 1):?>
                    - <?php echo __('Multi entry', FV_DOMAIN);?>
                <?php endif;?>
            </div>
            <div>
                <div class="label"><?php echo __('Start', FV_DOMAIN);?>:</div>
                <?php echo $aLeague['startDate'];?>
            </div>
            <div>
                <div class="label"><?php echo __('Ends', FV_DOMAIN);?>:</div>
                Prizes paid next day
            </div>
            <div>
                <div class="label"><?php echo __('Creator', FV_DOMAIN);?>:</div>
                <?php echo $creator->data->user_login;?>
            </div>
            <div>
                <div class="label"><?php echo __('Players', FV_DOMAIN);?>:</div>
                <?php echo $aLeague['size'];?> player game, <?php echo $aLeague['entries'];?> entries</td>
            </div>
        </div>
        <div class="right">
            <div class="boxEntry">
                <span><?php echo __('Entry', FV_DOMAIN);?></span> $<?php echo $aLeague['entry_fee'];?>
            </div>
            <div class="boxPrizes">
                <span><?php echo __('Prizes', FV_DOMAIN);?></span> $<?php echo $aLeague['prizes'];?>
            </div>
        </div>
    </div>
    
    <?php if($otherLeagues != null && get_option('fanvictor_show_import_pick') == 1):?>
    <p><?php echo __('Below is a list of games you have already entered for this event. Simply click on \'Import Picks\' to import your picks from that game.', FV_DOMAIN)?></p>
    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th><?php echo __('Contest ID', FV_DOMAIN)?></th>
                <th><?php echo __('Name', FV_DOMAIN)?></th>
                <th><?php echo __('Opponent', FV_DOMAIN)?></th>
                <th><?php echo __('Type', FV_DOMAIN)?></th>
                <th><?php echo __('Entry Fee', FV_DOMAIN)?></th>
                <th><?php echo __('Size', FV_DOMAIN)?></th>
                <th><?php echo __('Structure', FV_DOMAIN)?></th>
                <th colspan="2">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($otherLeagues as $otherLeague):?>
            <tr>
                <td>
                    <div><?php echo $otherLeague['leagueID'];?></div>
                </td>
                <td>
                    <div><?php echo $otherLeague['name'];?></div>
                </td>
                <td>
                    <div><?php echo $otherLeague['opponent'];?></div>
                </td>
                <td>
                    <div><?php echo $otherLeague['gameType'];?></div>
                </td>
                <td>
                    <div><?php echo $otherLeague['entry_fee'];?></div>
                </td>
                <td>
                    <div><?php echo $otherLeague['size'];?></div>
                </td>
                <td>
                    <div><?php echo $otherLeague['prize_structure'];?></div>
                </td>
                <td colspan="2">
                    <div>
                        <input type="button" value="<?php echo __('Import Picks', FV_DOMAIN)?>" onclick="importPicks('<?php echo $otherLeague["winnerID"];?>', '<?php echo $otherLeague["methodID"];?>', '<?php echo $otherLeague["roundID"];?>', '<?php echo $otherLeague["minuteID"];?>', '<?php echo $otherLeague["winner_spreadID"];?>', '<?php echo $otherLeague["over_under_value"];?>')" class="btn btn-success">
                    </div>
                </td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    <?php endif;?>
    <?php if($aLeague['gameType'] == 'PICKULTIMATE'):?>
        <table border="0" class="table table-striped table-bordered table-responsive table-condensed tb-submit">
            <thead> 
                <tr> 
                    <th><?php echo __("Time & Date");?></th>
                    <th><?php echo __("Game Vs. Points");?></th>
                    <th><?php echo __("Winner");?></th>
                    <th><?php echo __("Total Score");?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($aFights as $aFight):?>
                <tr>
                    <td style="width:20%">
                        <?php echo $aFight['startDate'];?>
                    </td>
                    <td style="width:30%">
                        <span>
                            <?php echo $aFight['name1'];?> (<?php echo $aFight['team1_spread_points'];?>)
                        </span>
                        <input type="radio" value="<?php echo $aFight['fighterID1'];?>" name="spread<?php echo $aFight['fightID'];?>" data-fightid="<?php echo $aFight['fightID'];?>" <?php if($aFight['winner_spreadID'] == $aFight['fighterID1']):?>checked="checked"<?php endif;?> <?php if($aFight['started'] == 1):?>disabled="true"<?php endif;?>>
                        <span>@</span>
                        <span>
                            <?php echo $aFight['name2'];?> (<?php echo $aFight['team2_spread_points'];?>)
                        </span>
                        <input type="radio" value="<?php echo $aFight['fighterID2'];?>" name="spread<?php echo $aFight['fightID'];?>" data-fightid="<?php echo $aFight['fightID'];?>" <?php if($aFight['winner_spreadID'] == $aFight['fighterID2']):?>checked="checked"<?php endif;?> <?php if($aFight['started'] == 1):?>disabled="true"<?php endif;?>>
                    </td>
                    <td style="width:30%">
                        <span>
                            <?php echo $aFight['name1'];?>
                        </span>
                        <input type="radio" class="fightID" value="<?php echo $aFight['fighterID1'];?>" name="winner<?php echo $aFight['fightID'];?>" data-fightid="<?php echo $aFight['fightID'];?>" <?php if($aFight['winnerID'] == $aFight['fighterID1']):?>checked="checked"<?php endif;?> <?php if($aFight['started'] == 1):?>disabled="true"<?php endif;?>>
                        <span>@</span>
                        <span>
                            <?php echo $aFight['name2'];?>
                        </span>
                        <input type="radio" class="fightID" value="<?php echo $aFight['fighterID2'];?>" name="winner<?php echo $aFight['fightID'];?>" data-fightid="<?php echo $aFight['fightID'];?>" <?php if($aFight['winnerID'] == $aFight['fighterID2']):?>checked="checked"<?php endif;?> <?php if($aFight['started'] == 1):?>disabled="true"<?php endif;?>>
                    </td>
                    <?php if($aMethods != null || $aFight['allow_ultimate']):?>
                    <td>
                        <span>
                            <?php echo __('Over', FV_DOMAIN);?> <?php echo $aFight['over_under'];?>
                        </span>
                        <input type="radio" value="over" name="over_under_value<?php echo $aFight['fightID'];?>" <?php if($aFight['over_under_value'] == 'over'):?>checked="checked"<?php endif;?> <?php if($aFight['started'] == 1):?>disabled="true"<?php endif;?>>
                        <br/><br/>
                        <span>
                            <?php echo __('Under', FV_DOMAIN);?> <?php echo $aFight['over_under'];?>
                        </span>
                        <input type="radio" value="under" name="over_under_value<?php echo $aFight['fightID'];?>" <?php if($aFight['over_under_value'] == 'under'):?>checked="checked"<?php endif;?> <?php if($aFight['started'] == 1):?>disabled="true"<?php endif;?>>
                    </td>
                    <?php endif;?>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    <?php elseif($aLeague['gameType'] != 'PICKEM'):?>
        <table border="0" class="table table-striped table-bordered table-responsive table-condensed">
            <tbody>
                <?php foreach($aFights as $aFight):?>
                <tr>
                    <td style="text-align:center;width:30%">
                        <?php echo $aFight['allow_spread'] ? $aFight['team1_spread_points'] : '';?>
                        <?php echo $aFight['allow_moneyline'] ? $aFight['team1_moneyline'] : '';?>
                        <br><?php echo $aFight['name1'];?>
                        <br>&nbsp;
                        <input type="radio" class="fightID" value="<?php echo $aFight['fighterID1'];?>" name="winner<?php echo $aFight['fightID'];?>" data-fightid="<?php echo $aFight['fightID'];?>" <?php if($aFight['winnerID'] == $aFight['fighterID1']):?>checked="checked"<?php endif;?> <?php if($aFight['started'] == 1):?>disabled="true"<?php endif;?>>
                    </td>
                    <td style="text-align:center;vertical-align: middle">
                        <?php echo $aFight['allow_spread'] ? __('Spread').'<br><br>' : '';?>
                        <?php echo $aFight['allow_moneyline'] ? __('Money Line').'<br><br>' : '';?>
                        VS
                        <br><?php echo $aFight['startDate'];?>
                    </td>
                    <td style="text-align:center;width:30%">
                        <?php echo $aFight['allow_spread'] ? $aFight['team2_spread_points'] : '';?>
                        <?php echo $aFight['allow_moneyline'] ? $aFight['team2_moneyline'] : '';?>
                        <br><?php echo $aFight['name2'];?>
                        <br>&nbsp;
                        <input type="radio" class="fightID" value="<?php echo $aFight['fighterID2'];?>" name="winner<?php echo $aFight['fightID'];?>" data-fightid="<?php echo $aFight['fightID'];?>" <?php if($aFight['winnerID'] == $aFight['fighterID2']):?>checked="checked"<?php endif;?> <?php if($aFight['started'] == 1):?>disabled="true"<?php endif;?>>
                    </td>
                    <?php if($aMethods != null):?>
                    <td style="text-align:center;vertical-align: middle">
                        <?php if($aMethods != null):?>
                            <select onchange="checkMethod(this.value,<?php echo $aFight['fightID'];?>)" class="method" data-id="<?php echo $aFight['fightID'];?>" id="method<?php echo $aFight['fightID'];?>" name="method<?php echo $aFight['fightID'];?>" style="width:205px" <?php if($aFight['started'] == 1):?>disabled="true"<?php endif;?>>
                                <option value="-1">-- Select Method --</option>
                                <?php foreach($aMethods as $aMethod):?>
                                <option value="<?php echo $aMethod["methodID"];?>" <?php if($aFight['methodID'] == $aMethod["methodID"]):?>selected="true"<?php endif;?>>
                                    <?php echo $aMethod["description"];?>
                                </option>
                                <?php endforeach;?>
                            </select>
                        <?php endif;?>
                        <?php if($aRounds != null):?>
                            <br>
                            <br>
                            <select id="round<?php echo $aFight['fightID'];?>" name="round<?php echo $aFight['fightID'];?>" style="width:205px" <?php if($aFight['started'] == 1):?>disabled="true"<?php endif;?>>
                                <option value="-1">-- Select Round --</option>
                                <?php foreach($aRounds as $aRound):?>
                                <option value="<?php echo $aRound;?>" <?php if($aFight['roundID'] == $aRound):?>selected="true"<?php endif;?>>
                                    <?php echo $aRound;?>
                                </option>
                                <?php endforeach;?>
                            </select>
                        <?php endif;?>
                        <?php if($aMinutes != null):?>
                            <br>
                            <br>
                            <select onchange="checkMinute(this.value,<?php echo $aFight['fightID'];?>)" class="minute" data-id="<?php echo $aFight['fightID'];?>" id="minute<?php echo $aFight['fightID'];?>" name="minute<?php echo $aFight['fightID'];?>" style="width:205px" <?php if($aFight['started'] == 1):?>disabled="true"<?php endif;?>>
                                <option value="-1">-- Select Minute --</option>
                                <?php foreach($aMinutes as $aMinute):?>
                                <option value="<?php echo $aMinute["minuteID"];?>" <?php if($aFight['minuteID'] == $aMinute["minuteID"]):?>selected="true"<?php endif;?>>
                                    <?php echo $aMinute["description"];?>
                                </option>
                                <?php endforeach;?>
                            </select>
                        <?php endif;?>
                    </td>
                    <?php endif;?>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    <?php elseif($aLeague['gameType'] == 'PICKEM'): ?>
    <table border="0" class="table table-striped table-bordered table-responsive table-condensed">
            <tbody>
                <?php foreach($aFights as $aFight):?>
                <?php if($aLeague['allow_tie']): ?>
                <tr>
                    <td style="border: none !important;"></td>
                    <td style="text-align:center;vertical-align: middle;border: none;">   <br><?php echo $aFight['startDate'];?></td>
                    <td style="border: none"></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td style="text-align:center;width:30%">
                        <?php echo $aFight['allow_spread'] ? $aFight['team1_spread_points'] : '';?>
                        <?php echo $aFight['allow_moneyline'] ? $aFight['team1_moneyline'] : '';?>
                        <br><?php echo $aFight['name1'];?>
                        <br>&nbsp;
                        <input type="radio" class="fightID" value="<?php echo $aFight['fighterID1'];?>" name="winner<?php echo $aFight['fightID'];?>" data-fightid="<?php echo $aFight['fightID'];?>" <?php if($aFight['winnerID'] == $aFight['fighterID1']):?>checked="checked"<?php endif;?> <?php if($aFight['started'] == 1):?>disabled="true"<?php endif;?>>
                    </td>
                    <td style="text-align:center;vertical-align: middle">
                        <?php echo $aFight['allow_spread'] ? __('Spread').'<br><br>' : '';?>
                        <?php echo $aFight['allow_moneyline'] ? __('Money Line').'<br><br>' : '';?>
                        <?php if($aLeague['allow_tie']): ?>
                        <br><?php echo __('Draw') ?>
                        <br><input type="radio" class="fightID" value="0" name="winner<?php echo $aFight['fightID'];?>" data-fightid="<?php echo $aFight['fightID'];?>" <?php if($aFight['winnerID'] ===0):?>checked="checked"<?php endif;?> <?php if($aFight['started'] == 1):?>disabled="true"<?php endif;?>>
                        <?php else: ?>
                        <br> VS
                         <br><?php echo $aFight['startDate'];?>
                        <?php endif; ?>
                    </td>
                    <td style="text-align:center;width:30%">
                        <?php echo $aFight['allow_spread'] ? $aFight['team2_spread_points'] : '';?>
                        <?php echo $aFight['allow_moneyline'] ? $aFight['team2_moneyline'] : '';?>
                        <br><?php echo $aFight['name2'];?>
                        <br>&nbsp;
                        <input type="radio" class="fightID" value="<?php echo $aFight['fighterID2'];?>" name="winner<?php echo $aFight['fightID'];?>" data-fightid="<?php echo $aFight['fightID'];?>" <?php if($aFight['winnerID'] == $aFight['fighterID2']):?>checked="checked"<?php endif;?> <?php if($aFight['started'] == 1):?>disabled="true"<?php endif;?>>
                    </td>
                    <?php if($aMethods != null):?>
                    <td style="text-align:center;vertical-align: middle">
                        <?php if($aMethods != null):?>
                            <select onchange="checkMethod(this.value,<?php echo $aFight['fightID'];?>)" class="method" data-id="<?php echo $aFight['fightID'];?>" id="method<?php echo $aFight['fightID'];?>" name="method<?php echo $aFight['fightID'];?>" style="width:205px" <?php if($aFight['started'] == 1):?>disabled="true"<?php endif;?>>
                                <option value="-1">-- Select Method --</option>
                                <?php foreach($aMethods as $aMethod):?>
                                <option value="<?php echo $aMethod["methodID"];?>" <?php if($aFight['methodID'] == $aMethod["methodID"]):?>selected="true"<?php endif;?>>
                                    <?php echo $aMethod["description"];?>
                                </option>
                                <?php endforeach;?>
                            </select>
                        <?php endif;?>
                        <?php if($aRounds != null):?>
                            <br>
                            <br>
                            <select id="round<?php echo $aFight['fightID'];?>" name="round<?php echo $aFight['fightID'];?>" style="width:205px" <?php if($aFight['started'] == 1):?>disabled="true"<?php endif;?>>
                                <option value="-1">-- Select Round --</option>
                                <?php foreach($aRounds as $aRound):?>
                                <option value="<?php echo $aRound;?>" <?php if($aFight['roundID'] == $aRound):?>selected="true"<?php endif;?>>
                                    <?php echo $aRound;?>
                                </option>
                                <?php endforeach;?>
                            </select>
                        <?php endif;?>
                        <?php if($aMinutes != null):?>
                            <br>
                            <br>
                            <select onchange="checkMinute(this.value,<?php echo $aFight['fightID'];?>)" class="minute" data-id="<?php echo $aFight['fightID'];?>" id="minute<?php echo $aFight['fightID'];?>" name="minute<?php echo $aFight['fightID'];?>" style="width:205px" <?php if($aFight['started'] == 1):?>disabled="true"<?php endif;?>>
                                <option value="-1">-- Select Minute --</option>
                                <?php foreach($aMinutes as $aMinute):?>
                                <option value="<?php echo $aMinute["minuteID"];?>" <?php if($aFight['minuteID'] == $aMinute["minuteID"]):?>selected="true"<?php endif;?>>
                                    <?php echo $aMinute["description"];?>
                                </option>
                                <?php endforeach;?>
                            </select>
                        <?php endif;?>
                    </td>
                    <?php endif;?>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    
            <?php endif;?>
	<?php if(strtolower($aLeague['gameType']) == 'picktie'):?> 
        <?php echo __("Predict point total");?>
        <input type="text" name="predict_point" value="<?php echo $aFights[0]['predict_point'];?>"/>
        (<?php echo __("If players tie for the first place prize, the player with the closest prediction to the game point total of the final Monday game will win.");?>)
    <?php endif;?>
    <div class="row">
        <div class="col-md-12"><br><br>
            <div style="text-align:center">
                <input type="submit" class="btn btn-primary" value="<?php echo __('Save', FV_DOMAIN)?>" name="SubmitPicks" onclick="return pickSelected(771)">
            </div>
            <div style="text-align:center;margin-top:10px;">
                <?php echo __('The league will appear in the My Upcoming Entries table after you submit your picks.', FV_DOMAIN)?>
            </div>
        </div>
    </div>
</form>
</div>