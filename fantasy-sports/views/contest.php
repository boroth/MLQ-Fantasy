<div class="contentPlugin">
    <div id="f-live-scoring-app">
        <input type="hidden" id="scoringCats" value='<?php echo   json_encode($scoringCats);?>' />
        <input type="hidden" id="multiEntry" value='<?php echo $league['multi_entry'];?>' />
        <input type="hidden" id="leagueOptionType" value='<?php echo $league['option_type'];?>' />
        <div class="f-column-12 f-clearfix">
            <div id="f-scoring-table-name">
                <h1>
                    <?php echo $league['name'];?>
                    <?php if($league['multi_entry'] == 1):?>
                        (<?php echo __("Multi Entry", FV_DOMAIN);?>)
                    <?php endif;?>
                </h1>
            </div>
            <div id="f-current-table-status">
                <span class="f-final">FINAL</span>
                <?php echo __("Start", FV_DOMAIN);?> <?php echo $league['startDate'];?>
            </div>
        </div>
        <div class="f-column-12" id="f-scoring-table-info">
            <div class="f-clearfix" id="f-table-meta-information">
                <ul class="f-column-8">
                    <li class="f-table-type"><?php echo __('Multiplayer league', FV_DOMAIN)?> (<?php echo $league['entries'];?> <?php echo ('entries')?>)</li>
                    <?php if(get_option('fanvictor_no_cash') == 0):?>
                    <li class="f-entry"><?php echo __('Entry', FV_DOMAIN)?>: $<?php echo $league['entry_fee'];?></li>
                    <?php endif;?>
                </ul>
                <ul class="f-prizes-breakdown">
                    <li class="f-prize-row">
                        <span class="f-pos-name">1st:</span> $0
                    </li>
                </ul>
            </div>
        </div>
        <?php if(!empty($aFights)):?>
        <div class="f-column-12" id="f-live-scoring-fixture-info">
            <section>
                <ul>
                    <?php foreach($aFights as $aFight):?>
                    <li class="f-fixture-card">
                        <ul class="f-fixture-card-live-status f-pending">
                            <li class="f-fixture-card-away"><?php echo $aFight['nickName1'];?> <?php echo $aFight['team1score'];?></li>
                            <li class="f-fixture-card-home"><?php echo $aFight['nickName2'];?> <?php echo $aFight['team2score'];?></li>
                            <li class="f-fixture-card-time">
                                <?php if($aFight['is_closed'] == 1):?>
                                    <?php echo __('FINAL', FV_DOMAIN);?>
                                <?php elseif($aFight['is_closed'] == 2):?>
                                    <?php echo __('POSTPONE', FV_DOMAIN);?>
                                <?php endif;?>&nbsp;
                            </li>
                        </ul>
                    </li>
                    <?php endforeach;?>
                </ul>
            </section>
        </div>
        <?php endif;?>
        <?php if(!empty($aRounds)):?>
        <div class="f-column-12" id="f-live-scoring-fixture-info">
            <section>
                <ul>
                    <?php foreach($aRounds as $aRound):?>
                    <li class="f-fixture-card">
                        <ul class="f-fixture-card-live-status f-pending">
                            <li class="f-fixture-card-away"><?php echo $aRound['name'];?></li>
                            <li class="f-fixture-card-time">
                                <?php if($aRound['is_closed'] == 1):?>
                                    <?php echo __('FINAL', FV_DOMAIN);?>
                                <?php elseif($aFight['is_closed'] == 2):?>
                                    <?php echo __('POSTPONE', FV_DOMAIN);?>
                                <?php endif;?>&nbsp;
                            </li>
                        </ul>
                    </li>
                    <?php endforeach;?>
                </ul>
            </section>
        </div>
        <?php endif;?>
        <div class="f-column-12 f-small-screen-pane" id="f-live-scoring-leaderboard">
            <div>
                <table class="f-condensed" id="tableScores">
                    <thead>
                        <tr>
                            <th style="width:40px;"></th>
                            <th><?php echo __('User', FV_DOMAIN)?></th>
                            <?php if($league['multi_entry'] == 1):?>
                            <th style="width:50px;"><?php echo __('Entry', FV_DOMAIN)?></th>
                            <?php endif;?>
                            <th class="f-text-align-right" style="width:54px;"><?php echo __('Score', FV_DOMAIN)?></th>
                            <?php if(get_option('fanvictor_no_cash') == 0):?>
                            <th class="f-text-align-right" style="width:106px;"><?php echo __('Prizes', FV_DOMAIN)?></th>
                            <?php endif;?>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <div class="fl-contest-right" >
            <div id="f-live-scoring-entry-details">
                <div class="f-slot f-column-6 f-entry-component f-small-screen-pane f-odd" id="f-seat-1"></div>
                <div class="f-slot f-column-6 f-entry-component f-small-screen-pane f-even" id="f-seat-2">
                    <div id="live-scoring-app" class="loading"></div>
                    <p class="f-entry-placeholder-text">
                        <?php echo __('Select a user from the list above to see their lineup.', FV_DOMAIN)?>
                    </p>
                </div>
                <div class="clear"></div>
                
            </div>
        </div>
		<div class="clear"></div>
        <?php if($scoringCats != null):?> 
            <div>
                <h3 style="margin-bottom: 0"><?php echo __('Scoring Categories', FV_DOMAIN);?></h3>
                <?php foreach($scoringCats as $item):?> 
                    <?php echo $item['name'];?> <?php echo (!empty($item['alias']) ? '('.$item['alias'].')' : '');?> = <?php echo $item['points'];?> <br/>
                <?php endforeach;?>
            </div>
        <?php endif;?> 
        <?php if($bonus != null):?>
        <div id="bonusPoints">
            <h3 style="margin-bottom: 0"><?php echo __('Bonus', FV_DOMAIN);?></h3>
            <?php echo $bonus;?>
        </div>
        <?php endif;?>
    </div>
</div>

<script type="text/javascript">
    jQuery(window).load(function(){
        var isLive = <?php echo $league['is_live'];?>;
        if(isLive)
        {
            jQuery.league.liveEntriesResult(<?php echo $league['poolID'];?>, <?php echo $league['leagueID'];?>, <?php echo $entry_number;?>);
            setInterval(function() { 
                jQuery.league.liveEntriesResult(<?php echo $league['poolID'];?>, <?php echo $league['leagueID'];?>, <?php echo $entry_number;?>)
            }, 60000);
        }
        else 
        {
            jQuery.playerdraft.loadContestScores(<?php echo $league['leagueID'];?>, <?php echo $entry_number;?>);
        }
    });
</script>