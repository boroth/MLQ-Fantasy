<?php getMessage();?>
<?php $aSportKeys = array_keys($aSports);?>
<input type="hidden" id="mixing_orginazation_id" value="<?php echo $aSportKeys[0];  ?>">
<input type="hidden" id="type_league" value="mixing">
<div class="f-contest-title-date">
    <h1 class="f-contest-title f-heading-styled"><?php echo $league['name'];?></h1>
    <div class="f-contest-date-container">
        <div class="f-contest-date-start-time">
            <?php echo __('Contest starts', FV_DOMAIN);?> <?php echo date('D M g H:i', strtotime($league['start_date']));?>
        </div>
    </div>
</div>
<ul class="f-contest-information-bar">
    <li class="f-contest-entries-league"><?php echo __('Entries:', FV_DOMAIN);?> 
        <b>
            <a class="f-lightboxLeagueEntries_show" href="#" onclick="return jQuery.playerdraft.ruleScoring('<?php echo $league['gameType'];?>', <?php echo $league['leagueID'];?>, '<?php echo htmlentities($league['name']);?>', '<?php echo $league['entry_fee'];?>', '<?php echo $aPool['salary_remaining'];?>', 2)"><?php echo $league['entries'];?></a>
        </b> / <?php echo $league['size'];?>
        <span class="f-entries-player-league"> <?php echo __('player league', FV_DOMAIN);?></span>
    </li>
    <?php if(get_option('fanvictor_no_cash') == 0):?>
    <li class="f-contest-entry-fee-container">
    <?php echo __('Entry fee:', FV_DOMAIN);?>
        <span class="f-entryFee-value amount">$<?php echo $league['entry_fee'];?></span>
    </li>
    <li class="f-contest-prize-container  f-gameEntry-inner-entryFeeSelected">
    <?php echo __('Prizes:', FV_DOMAIN);?>
        <span class="f-content-prize-amount">
            <a class="f-lightboxPrizeList_show" href="#"  onclick="return jQuery.playerdraft.ruleScoring('<?php echo $league['gameType'];?>', <?php echo $league['leagueID'];?>, '<?php echo htmlentities($league['name']);?>', '<?php echo $league['entry_fee'];?>', '<?php echo $aPool['salary_remaining'];?>', 3)">
                $<?php echo $league['prizes'];?>
            </a>
        </span>
    </li>
    <?php endif;?>
    <li class="f-contest-rules-link-container">
        <a class="f-lightboxRulesAndScoring_show" onclick="return jQuery.playerdraft.ruleScoring('<?php echo $league['gameType'];?>', <?php echo $league['leagueID'];?>, '<?php echo htmlentities($league['name']);?>', '<?php echo $league['entry_fee'];?>', '<?php echo $aPool['salary_remaining'];?>')" href="#">
            <?php echo __('Rules &amp; Scoring', FV_DOMAIN);?>
        </a>
    </li>
</ul>
<div class="clear"></div>
<?php if($otherLeagues != null):?>
    <p style="margin: 20px 0 10px;"><?php echo __('Below is a list of games you have already entered for this event. Simply click on \'Import Picks\' to import your picks from that game.', FV_DOMAIN)?></p>
    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="table table-striped table-bordered">
        <thead>
            <tr>
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
                        <input type="button" value="<?php echo __('Import Picks', FV_DOMAIN)?>"  class="btn btn-success" onclick="jQuery.playerdraft.addMultiPlayers('<?php echo $otherLeague['player_id']?>')">
                    </div>
                </td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
<?php endif;?>
    <div class="f-pick-your-team">
    <?php foreach($aAllFights as $index=>$aFights): ?>
    <section data-role="fixture-picker" class="f-fixture-picker" style="margin-bottom: 5px;">
        <?php if(!empty($aFights)):?>
		<h1><?php echo __('Players available from (click to filter):', FV_DOMAIN);?></h1>
		<div class="f-fixture-picker-button-container">
               <a class="f-button f-mini fixture-item <?php echo ($index == $aSportKeys[0])?'s-sport-is-active':'' ?>" onclick="jQuery.playerdraft.setActiveFixture(this);jQuery.playerdraft.mixingSelectTypeSport(this,<?php echo $index; ?>, '<?php echo __('Unlimited', FV_DOMAIN);?>','<?php echo __('Add player', FV_DOMAIN);?>')"><?php echo $aSports[$index]; ?></a>           
	
            <?php foreach($aFights as $aFight):?>
            <a data-sport-id="<?php echo $index; ?>" data-team-id1="<?php echo $aFight['fighterID1'];?>" data-team-id2="<?php echo $aFight['fighterID2'];?>" <?php if($aFight['started'] == 0):?>onclick="jQuery.playerdraft.setActiveFixture(this);return jQuery.playerdraft.mixingLoadPlayers(
                        );"<?php endif;?> class="f-button f-mini fixture-item <?php if($aFight['started'] == 1):?>f-is-disabled<?php endif;?>">
                <span class="f-fixture-team-home"><?php echo $aFight['nickName1'];?></span>
                @
                <span class="f-fixture-team-away"><?php echo $aFight['nickName2'];?></span>
                <span class="f-fixture-start-time"><?php echo $aFight['startDate'];?></span>
            </a>
            <?php endforeach;?>
        </div>
        <?php endif;?>
                
        <?php if(!empty($aRounds)):?>
		<h1><?php echo __('Players available from (click to filter):', FV_DOMAIN);?></h1>
		<div class="f-fixture-picker-button-container">
			<a class="f-button f-mini f-is-active fixture-item" onclick="jQuery.playerdraft.setActiveFixture(this);return jQuery.playerdraft.mixingLoadPlayers();">All</a>
            <?php foreach($aRounds as $aRound):?>
            <a class="f-button f-mini fixture-item">
                <span class="f-fixture-team-home"><?php echo $aRound['name'];?></span>
                <span class="f-fixture-start-time"><?php echo $aRound['startDate'];?></span>
            </a>
			<?php endforeach;?>
        </div>
        <?php endif;?>
	</section>
    
    <?php endforeach; ?>
</div>
<div class="f-row">
    <section class="f-contest-player-list-container" data-role="player-list">
        <div class="f-row">
            <h1><?php echo __('Available Players', FV_DOMAIN);?></h1>
            <ul class="f-player-list-position-tabs f-tabs f-row">
		<li>
                    <a href="" data-id="" class="f-is-active" onclick="jQuery.playerdraft.setActivePosition(this);return jQuery.playerdraft.mixingLoadPlayers();"><?php echo __('All', FV_DOMAIN)?></a>
                </li>
                <?php if($aPositions != null):?>
                <?php foreach($aPositions[$aSportKeys[0]] as $aPosition):?>
                <li>
                    <a href="" data-id="<?php echo $aPosition['id'];?>" onclick="jQuery.playerdraft.setActivePosition(this);return jQuery.playerdraft.mixingLoadPlayers();">
                        <?php echo $aPosition['name'];?>
                    </a>
                </li>
                <?php endforeach;?>
                <?php endif;?>
                <li class="f-player-search">
					<label class="f-is-hidden" for="mixing-player-search"><?php echo __('Find a Player', FV_DOMAIN);?></label>
					<input type="search" id="mixing-player-search" placeholder="<?php echo __('Find a player...', FV_DOMAIN);?>" incremental="" autosave="fd-player-search" results="10">
		</li>
			</ul>
            <div data-role="scrollable-header">
				<table class="f-condensed f-player-list-table-header f-header-fields">
					<thead>
						<tr>
                            <th colspan="2" class="f-player-name table-sorting">
								<?php echo __('Name', FV_DOMAIN);?>
								<i class="f-icon f-sorted-asc">▴</i>
								<i class="f-icon f-sorted-desc">▾</i>
							</th>
							<th class="f-player-played table-sorting">
								<i class="f-icon f-sorted-asc">▴</i>
								<i class="f-icon f-sorted-desc">▾</i>
								<?php echo __('Team', FV_DOMAIN);?>
							</th>
							<th class="f-player-fixture table-sorting">
								<?php echo __('Game', FV_DOMAIN);?>
								<i class="f-icon f-sorted-asc">▴</i>
								<i class="f-icon f-sorted-desc">▾</i>
							</th>
							<th class="f-player-salary table-sorting">
								<i class="f-icon f-sorted-asc">▴</i>
								<i class="f-icon f-sorted-desc">▾</i>
								<?php echo __('Salary', FV_DOMAIN);?>
							</th>
							<th class="f-player-add"></th>
						</tr>
					</thead>
				</table>
			</div>
            <div class="f-errorMessage"></div>
            <div data-role="scrollable-body" id="listPlayers">
                <div class="f-player-list-empty"><?php echo __('No matching players. Try changing your filter settings.', FV_DOMAIN);?></div>
                <table class="f-condensed f-player-list-table">
                    <thead class="f-is-hidden">
                        <tr>
                            <th class="f-player-name">
                                <?php echo __('Pos', FV_DOMAIN);?>
                            </th>
                            <th class="f-player-name">
                                <?php echo __('Name', FV_DOMAIN);?>
                            </th>
                            <th class="f-player-fppg">
                                <?php echo __('FPPG', FV_DOMAIN);?>
                            </th>
                            <th class="f-player-played">
                                <?php echo __('Team', FV_DOMAIN);?>
                            </th>
                            <th class="f-player-fixture">
                                <?php echo __('Game', FV_DOMAIN);?>
                            </th>
                            <th class="f-player-salary">
                                <?php echo __('Salary', FV_DOMAIN);?>
                            </th>
                            <th class="f-player-add"></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="f-row f-legend">
                <div class="f-draft-legend-key-title" data-role="expandable-heading" onclick="return jQuery.playerdraft.showIndicatorLegend()"><?php echo __('Indicator legend', FV_DOMAIN);?></div>
                <div class="f-clear"></div>
                <div class="f-draft-legend-key-content">
					<ul>
                        <?php foreach($aIndicators[$aSportKeys[0]] as $aIndicator):?>
                        <?php 
                            $indicatorClass = '';
                            switch($aIndicator['alias'])
                            {
                                case 'IR':
                                    $indicatorClass = 'f-player-badge f-player-badge-injured-out';
                                    break;
                                case 'O':
                                    $indicatorClass = 'f-player-badge f-player-badge-injured-out';
                                    break;
                                case 'D':
                                    $indicatorClass = 'f-player-badge f-player-badge-injured-possible';
                                    break;
                                case 'Q':
                                    $indicatorClass = 'f-player-badge f-player-badge-injured-possible';
                                    break;
                                case 'P':
                                    $indicatorClass = 'f-player-badge f-player-badge-injured-probable';
                                    break;
                                case 'NA':
                                    $indicatorClass = 'f-player-badge f-player-badge-injured-out';
                                    break;
								case 'S':
                                    $indicatorClass = 'f-player-badge f-player-badge-injured-possible';
                                    break;
                            }
                        ?>
                        <li>
                            <span class="<?php echo $indicatorClass;?>">
                                <?php echo $aIndicator['alias'];?>
                            </span> 
                            <?php echo $aIndicator['name'];?>
                        </li>
                        <?php endforeach;?>
					</ul>
                    <div class="f-clear"></div>
				</div>
			</div>
        </div>
    </section>
    <section class="f-roster-container" data-role="team">
        <header>
            <div class="f-lineup-text-container">
                <h1><?php echo __('Your lineup', FV_DOMAIN);?></h1>
                <p class="f-lineup-lock-message">
                    <i class="fa fa-lock"></i> <?php echo __('Locks @', FV_DOMAIN);?> <?php echo date('D M g H:i', strtotime($league['start_date']));?>
                    <span class="f-game_status_open"></span>
                </p>
            </div>
            <div class="f-salary-remaining">
                <div class="f-salary-remaining-container">
                    <span class="f-salary-remaining-amount" id="salaryRemaining">
                        <?php if($aPool[0]['salary_remaining'] > 0):?> 
                            $<?php echo number_format($salary_remaining);?>
                        <?php else:?>
                            <?php echo __('Unlimited', FV_DOMAIN);?>
                        <?php endif;?>
                    </span><?php echo __('Salary Remaining', FV_DOMAIN);?>
                </div>
                <div class="f-player-average-container">
                    <span class="f-player-average-amount" id="AvgPlayer"></span><?php echo __('Avg/Player', FV_DOMAIN);?>
                </div>
            </div>
        </header>
        <section class="f-roster">
            <ul>
                <?php if($aLineups != null && is_array($aLineups)):?>
                    <?php foreach($aLineups as $aLineup):?>
                        <?php foreach($aLineup as $item): ?>
                            <?php for($i = 0; $i < $item['total']; $i++):?>
                            <li class="f-roster-position f-count-0 player-position-<?php echo $item['id'];?>" <?php if(isset($aPool['is_round']) && $aPool['is_round'] == 1):?>style="padding-left: 0;background: none;"<?php endif;?>>
                                <div class="f-player-image" <?php if(isset($aPool['is_round']) && $aPool['is_round'] == 1):?>style="display: none;"<?php endif;?>></div>
                                <div class="f-position"><?php echo $item['name'];?>
                                  <span class="f-empty-roster-slot-instruction"><?php echo __('Add player', FV_DOMAIN);?></span>
                                </div>
                                <div class="f-player"></div>
                                <div class="f-salary">$0</div>
                                <div class="f-fixture"></div>
                                <a class="f-button f-tiny f-text">
                                    <i class="fa fa-minus-circle"></i>
                                </a>
                            </li>
                            <?php endfor;?>
                     <?php endforeach;?>
                    <?php endforeach;?>
                <?php else:?>  
                    <?php for($i = 0; $i < $aLineups; $i++):?> 
                        <li class="f-roster-position f-count-0 player-position-0" <?php if($aPool[0]['is_round'] == 1):?>style="padding-left: 0;background: none;"<?php endif;?>>
                            <div class="f-player-image" <?php if($aPool[0]['is_round'] == 1):?>style="display: none;"<?php endif;?>></div>
                            <div class="f-position">
                                <span class="f-empty-roster-slot-instruction"><?php echo __('Add player', FV_DOMAIN);?></span>
                            </div>
                            <div class="f-player"></div>
                            <div class="f-salary">$0</div>
                            <div class="f-fixture"></div>
                            <a class="f-button f-tiny f-text">
                                <i class="fa fa-minus-circle"></i>
                            </a>
                        </li>
                    <?php endfor;?>
                <?php endif;?>
            </ul>
            <div class="f-row import-clear-button-container">
                <button class="f-button f-mini f-text f-right" id="playerPickerClearAllButton" onclick="jQuery.playerdraft.clearAllPlayer()" type="button">
                    <small><i class="fa fa-minus-circle"></i> <?php echo __('Clear all', FV_DOMAIN);?></small>
                </button>
            </div>
        </section>
        <footer class="f-">
            <div class="f-contest-entry-fee-container">
                <form id="formLineup" enctype="multipart/form-data" method="POST" action="<?php echo FANVICTOR_URL_GAME;?>">
                    <div id="enterForm.game_id.e" class="f-form_error"></div>
                    <input type="hidden" value="1" name="submitPicks">
                    <input type="hidden" value="<?php echo $league['leagueID'];?>" name="leagueID">
                    <input type="hidden" value="<?php echo $entry_number;?>" name="entry_number">
                    <input type="hidden" value="<?php echo session_id();?>" name="session_id">
                    <input type="hidden" value="1" name="submitPicks">
                </form>
            </div>
            <div class="f-contest-enter-button-container">
                <input type="submit" data-nav-warning="off" id="btnSubmit" value="<?php echo __('Enter', FV_DOMAIN);?>" class="f-button f-jumbo f-primary" onclick="jQuery.playerdraft.submitData()">
            </div>
        </footer>
    </section>
</div>
<?php require_once(FANVICTOR__PLUGIN_DIR_VIEW.'dlg_info.php');?>
<script type="text/javascript">
    jQuery.playerdraft.mixSetData('<?php echo json_encode($aPlayers);?>', 
                               '<?php echo $salary_remaining;?>', 
                               '<?php echo json_encode($playerIdPicks);?>', 
                               '<?php echo json_encode($league);?>', 
                               '<?php echo json_encode($aAllFights);?>',
                               '<?php echo json_encode($aPool);?>',
                               '<?php echo json_encode($aIndicators);?>',
                               '<?php echo json_encode($aLineups); ?>',
                               '<?php echo json_encode($aPositions); ?>');
</script>