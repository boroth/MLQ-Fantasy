<div class="contentPlugin">
<form action="<?php echo FANVICTOR_URL_PICK_SQUARES;?>" method="POST" id="submitPickSquaresForm" name="submitPickSquaresForm">
    <h3 class="widget-title">Contest ID: <?php echo $aLeague['leagueID'];?> - <?php echo $aLeague['name'];?></h3>
    <input type="hidden" value="<?php echo $aLeague['poolID'];?>" name="poolID">
    <input type="hidden" value="<?php echo $aLeague['leagueID'];?>" name="leagueID">
    <input type="hidden" value="<?php echo $entry_number;?>" name="entry_number">
    <input type="hidden" value="<?php echo $aFights[0]['fightID'];?>" name="fightID">
    <input type="hidden" value="" name="winner<?php echo $aFights[0]['fightID']; ?>">
    <input type="hidden" value='<?php echo $picksquare; ?>' name="pick_squares" id="pick_squares">
    <input type="hidden" value='<?php echo $userSquares; ?>' name="user_squares" id="user_squares">
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
                <?php echo $aLeague['size'];?> player game, <?php echo $aLeague['entries'];?> entries
            </div>
            <?php if(!empty($payoutPickSquare)): ?>
             <div>
                <div class="label"><?php echo __('Payout', FV_DOMAIN);?>:</div>
                <div style="font-size: 17px;">
                <?php foreach($payoutPickSquare as $payouts): ?>
                    <?php echo $payouts['name'];?>&nbsp;&nbsp; <?php echo $payouts['price'];?><br>
                <?php endforeach; ?>
                  </div>
            </div>
            <?php endif; ?>
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
    

        <table border="0" class="table table-striped table-bordered table-responsive table-condensed">
            <tbody>
                <?php foreach($aFights as $aFight):?>
                <tr>
                    <td style="text-align:center;width:30%">
                        <?php echo $aFight['allow_spread'] ? $aFight['team1_spread_points'] : '';?>
                        <?php echo $aFight['allow_moneyline'] ? $aFight['team1_moneyline'] : '';?>
                        <br><?php echo $aFight['name1'];?>
                        <br>&nbsp;
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
	<?php if(strtolower($aLeague['gameType']) == 'picktie'):?> 
        <?php echo __("Predict point total");?>
        <input type="text" name="predict_point" value="<?php echo $aFights[0]['predict_point'];?>"/>
        (<?php echo __("If players tie for the first place prize, the player with the closest prediction to the game point total of the final Monday game will win.");?>)
    <?php endif;?>
        <div class="row" style="margin-top:10px;margin-bottom: 10px;">
            <div class="col-md-12">
                <!--        draw cell-->
                <table border="1" class="table-bordered picksquare_table">
                    <?php
                      $picksquare = json_decode($picksquare,true);
                      $userSquares = json_decode($userSquares,true);
                      $rows = $userSquares[0];
                      $cols = $userSquares[1];
                    foreach ($rows as $row) {
                        echo '<tr>';
                        foreach ($cols as $col) {
                            $content = $row.'_'.$col;
                            if(!empty($picksquare)){
                                 if(in_array($content, $picksquare)){
                                      echo '<td style="background:yellow">'.$content.'</td>';
                                 }else{
                                      echo '<td>'.$content.'</td>';
                                 }
                            }else{
                                echo '<td>'.$content.'</td>';
                            }
                        }
                        echo '</tr>';
                    }
                    ?>
                </table>
            </div>
        </div>
    <div class="row">
        <div class="col-md-12"><br><br>
            <div style="text-align:center">
                <input type="submit" class="btn btn-primary" value="<?php echo __('Save', FV_DOMAIN)?>" name="pickSquares" onclick="return checkFormPickSquare(771)">
            </div>
            <div style="text-align:center;margin-top:10px;">
                <?php echo __('The league will appear in the My Upcoming Entries table after you submit your picks.', FV_DOMAIN)?>
            </div>
        </div>
    </div>
</form>
</div>