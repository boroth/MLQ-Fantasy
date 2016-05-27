<?php getMessage();?>

<?php if($aSports != null && $aPools != null):?>
    <h3><?php echo __('Pick your sport', FV_DOMAIN);?></h3>

    <form role="form" class="f-form-inline" id="formCreateContest">
        <input type="hidden" id="sportData" value='<?php echo htmlentities(json_encode($aSports), ENT_QUOTES);?>' />
        
        <input type="hidden" id="poolData" value='<?php echo $aPools;?>' />

        <input type="hidden" id="fightData" value='<?php echo $aFights;?>' />
        
        <input type="hidden" id="roundData" value='<?php echo $aRounds;?>' />

        <input type="hidden" id="winnerPercent" value='<?php echo get_option('fanvictor_winner_percent');?>' />

        <input type="hidden" id="firstPercent" value='<?php echo get_option('fanvictor_first_place_percent');?>' />

        <input type="hidden" id="secondPercent" value='<?php echo get_option('fanvictor_second_place_percent');?>' />

        <input type="hidden" id="thirdPercent" value='<?php echo get_option('fanvictor_third_place_percent');?>' />
        
        <input type="hidden" id="plugin_url_image" value='<?php echo FANVICTOR__PLUGIN_URL_IMAGE?>' />

        <?php if($aSports != null):?>
       
            <select id="sports" name="organizationID" onchange="jQuery.createcontest.loadPools()">
            <?php foreach($aSports as $aSport):?>
                
                <?php if(!empty($aSport['child']) && is_array($aSport['child'])):?>

                <option disabled="true"><?php echo $aSport['name'];?></option>

                <?php foreach($aSport['child'] as $aOrg):?>

                    <?php if($aOrg['is_active'] == 1):?>

                    <option value="<?php echo $aOrg['id'];?>" playerdraft="<?php echo $aOrg['is_playerdraft'];?>" only_playerdraft="<?php echo $aOrg['only_playerdraft'];?>" is_round="<?php echo $aOrg['is_round'];?>" is_picktie="<?php echo $aOrg['is_picktie'];?>" style="padding-left: 20px">

                        <?php echo $aOrg['name'];?>

                    </option>

                    <?php endif;?>

                <?php endforeach;?>

                <?php endif;?>

            <?php endforeach;?>

            </select>

        <?php endif;?>



        <h3 class="widget-title"><?php echo __('Events', FV_DOMAIN);?></h3>

        <div id="poolDates"></div>


        <div id="wrapFixtures">
            <h3 class="widget-title"><?php echo __('Fixture Selection', FV_DOMAIN);?></h3>

            <div id="fixtureDiv"></div>
        </div>
        
        <div id="wrapRounds">
            <h3 class="widget-title"><?php echo __('Rounds', FV_DOMAIN);?></h3>

            <div id="roundDiv"></div>
        </div>


        <h3 class="minutes widget-title"><?php echo __('Minutes', FV_DOMAIN);?></h3>

        <select class="minutes" name="allow_minutes" id="allow_minutes">

            <option value="off"><?php echo __('No', FV_DOMAIN);?></option>

            <option value="on"><?php echo __('Yes', FV_DOMAIN);?></option>

        </select>



        <h3 class="widget-title"><?php echo __("Game Type", FV_DOMAIN);?></h3>

        <select class="form-control" name="game_type" id="game_type"></select>

        <h3 class="widget-title"><?php echo __('Opponent', FV_DOMAIN);?></h3>

        <div class="radio">

          <label>

            <input type="radio" name="opponent" id="oppoRadio1" value="public" checked>

                <?php echo __('Anyone', FV_DOMAIN);?>

            </label>

        </div>

        <div class="radio">

          <label>

            <input type="radio" name="opponent" id="oppoRadio1" value="private">

                <?php echo __('Friends Only', FV_DOMAIN);?>

            </label>

        </div>



        <h3 class="widget-title"><?php echo __('Contest Type', FV_DOMAIN);?></h3>

        <div class="radio">

          <label>

            <input type="radio" name="type" id="typeRadios7" value="head2head" checked>

                <?php echo __('Head to head', FV_DOMAIN);?> 	

            </label>

        </div>

        <div class="radio">

          <label>

            <input type="radio" name="type" id="typeRadios8" value="league">

                <?php echo __('League', FV_DOMAIN);?>	

            </label>

        </div>
        
        <div class="leagueDiv" name="leagueDiv" style="display: none">
            
            <label>

                <input type="checkbox" name="multi_entry" value="1" />

                <?php echo __('Multi Entry', FV_DOMAIN);?>	

            </label>

            <h3><?php echo __('League Size', FV_DOMAIN);?></h3>

            <select class="form-control" name="leagueSize" id="leagueSize" onchange="jQuery.createcontest.calculatePrizes()">

                <?php foreach($aLeagueSizes as $aLeagueSize):?>

                    <option value="<?php echo $aLeagueSize;?>"><?php echo $aLeagueSize;?></option>

                <?php endforeach;?>

            </select>
            <?php if(get_option('fanvictor_no_cash') == 0):?>
                <h3 class="widget-title"><?php echo __('Prize Structure', FV_DOMAIN);?> </h3>

                <div class="radio">

                    <label>

                        <input type="radio" name="structure" id="typeRadios9" value="winnertakeall" checked>

                        <?php echo __('Winner takes all', FV_DOMAIN);?> 

                    </label>

                </div>

                <div class="radio">

                    <label>

                        <input type="radio" name="structure" id="typeRadios10" value="top3">

                        <?php echo __('Top 3 get prizes', FV_DOMAIN);?> 

                    </label>
                </div>

                            <div class="radio">

                    <label>

                        <input type="radio" name="structure" id="typeRadios10" value="multi_payout">

                        <?php echo __('Multi payout', FV_DOMAIN);?> 

                    </label>
                    <a id="addPayouts" onclick="return jQuery.createcontest.addPayouts();" href="#" style="display: none">
                        <img title="<?php echo __("Add", FV_DOMAIN);?>" alt="<?php echo __("Add", FV_DOMAIN);?>" src="<?php echo FANVICTOR__PLUGIN_URL_IMAGE.'add.png';?>">
                    </a>
                    <div id="payoutExample" style="display: none;margin-left: 50px;">
                        <?php echo __('Example', FV_DOMAIN);?>: <br/>
                        1st: <?php echo __('From', FV_DOMAIN);?>  1 <?php echo __('to', FV_DOMAIN);?> 1: 40%<br/>
                        2nd: <?php echo __('From', FV_DOMAIN);?>  2 <?php echo __('to', FV_DOMAIN);?> 2: 30%<br/>
                        3rd: <?php echo __('From', FV_DOMAIN);?>  3 <?php echo __('to', FV_DOMAIN);?> 3: 20%<br/>
                        4th - 5th: <?php echo __('From', FV_DOMAIN);?> 4 <?php echo __('to', FV_DOMAIN);?> 6: 10%<br/>
                        <?php echo __('Total percent must be 100%', FV_DOMAIN);?>
                    </div>
                </div>

                <div id="payouts" style="margin-left: 50px;"></div>
            <?php else:?> 
                <input type="hidden" name="structure" value="winnertakeall">
            <?php endif;?>
        </div>


        <?php if(get_option('fanvictor_no_cash') == 0):?>
            <h3 class="widget-title"><?php echo __('Entry Fee', FV_DOMAIN);?></h3>

            <select class="form-control" id="entry_fee" name="entry_fee" onchange="jQuery.createcontest.calculatePrizes()">

                <option value="0"><?php echo __('Free Practice', FV_DOMAIN);?></option> 

                <?php foreach($aEntryFees as $aEntryFee):?>

                    <option value="<?php echo $aEntryFee;?>"><?php echo $aEntryFee;?></option>

                <?php endforeach;?>

            </select>
        <?php else:?>
            <input type="hidden" name="entry_fee" value="0" />
        <?php endif;?>


        <h3 class="widget-title"><?php echo __('Name your league', FV_DOMAIN);?></h3>

            <input type="text" class="form-control" id="leaguename" name="leaguename" placeholder="<?php echo __('Name your league', FV_DOMAIN);?>">

        <br/>

        <?php if(get_option('fanvictor_no_cash') == 0):?>
        <h3 class="widget-title"><?php echo __('Prizes Structure', FV_DOMAIN);?></h3>

        <div name="prizesum" id="prizesum"></div>
        <?php endif;?>


        <br/>

        <br/>


        <div class="public_message" style="display: none;"></div>
        <button id="btn_create_contest" type="submit" class="f-create-contest f-button f-primary f-right" name="submitContest"><?php echo __('Create Contest', FV_DOMAIN);?></button>

    </form>

<?php else:?>

    <?php echo __("There are no events.", FV_DOMAIN);?>

<?php endif;?>



