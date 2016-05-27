<?php getMessage();?>
<input type="hidden" id="type_league" value="single">
<div class="f-contest-title-date">
    <h1 class="f-contest-title f-heading-styled"><?php echo $league['name'];?></h1>
    <div class="f-contest-date-container">
        <div class="f-contest-date-start-time">
            <?php echo __('Contest starts', FV_DOMAIN);?> <?php echo $aPool['startDate'];?>
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

    <li class="f-contest-rules-link-container">
        <a class="f-lightboxRulesAndScoring_show" onclick="return jQuery.playerdraft.ruleScoring('<?php echo $league['gameType'];?>', <?php echo $league['leagueID'];?>, '<?php echo htmlentities($league['name']);?>', '<?php echo $league['entry_fee'];?>', '<?php echo $aPool['salary_remaining'];?>')" href="#">
            <?php echo __('Rules &amp; Scoring', FV_DOMAIN);?>
        </a>
    </li>
</ul>
<div class="clear"></div>
<div class="f-pick-your-team">
    <section data-role="fixture-picker" class="f-fixture-picker">
        <?php if(!empty($aRounds)):?>
		<h1><?php echo __('Players available from (click to filter):', FV_DOMAIN);?></h1>
		<div class="f-fixture-picker-button-container">
			<a class="f-button f-mini fixture-item" onclick="return jQuery.playerdraft.loadPlayers();">All</a>
            <?php foreach($aRounds as $key=>$aRound):?>
                        <a data-id="<?php echo $aRound['id']; ?>" class="f-button f-mini fixture-item <?php if($key == 0){echo 'f-is-active';} ?>" onclick="jQuery.playerdraft.setActiveFixture(this);return jQuery.playerdraft.selectGolfSkinRounds(this);">
                <span class="f-fixture-team-home"><?php echo $aRound['name'];?></span>
                <span class="f-fixture-start-time"><?php echo $aRound['startDate'];?></span>
            </a>
			<?php endforeach;?>
        </div>
        <?php endif;?>
	</section>
</div>
<div class="f-row">
    <section class="f-contest-player-list-container" data-role="player-list">
        <div class="f-row">
            <h1><?php echo __('Available Players', FV_DOMAIN);?></h1>
            <ul class="f-player-list-position-tabs f-tabs f-row">
		<li>
                    <a href="" data-id="" class="f-is-active" onclick="jQuery.playerdraft.setActivePosition(this);return jQuery.playerdraft.loadPlayers();"><?php echo __('All', FV_DOMAIN)?></a>
                </li>
                <li class="f-player-search">
					<label class="f-is-hidden" for="player-search"><?php echo __('Find a Player', FV_DOMAIN);?></label>
					<input type="search" id="player-search" placeholder="<?php echo __('Find a player...', FV_DOMAIN);?>" incremental="" autosave="fd-player-search" results="10">
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
                            <?php if(!$aPool['only_playerdraft']):?>
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
                            <?php endif;?>
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
                            <?php if(!$aPool['only_playerdraft']):?>
                            <th class="f-player-played">
                                <?php echo __('Team', FV_DOMAIN);?>
                            </th>
                            <th class="f-player-fixture">
                                <?php echo __('Game', FV_DOMAIN);?>
                            </th>
                            <?php endif;?>
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
                        <?php foreach($aIndicators as $aIndicator):?>
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
                    <i class="fa fa-lock"></i> <?php echo __('Locks @', FV_DOMAIN);?> <?php echo $aPool['startDate'];?>
                    <span class="f-game_status_open"></span>
                </p>
            </div>
            <?php if($is_entry_fee): ?>
            <div class="f-salary-remaining">
                <div class="f-salary-remaining-container">
                    <span id="f-salary"><?php echo $total_money; ?> $</span>
                </div>
            </div>
            <?php endif; ?>
        </header>
        <section class="f-roster">
            <ul>
                <?php if($aLineups != null && is_array($aLineups)):?>
                    <?php foreach($aLineups as $aLineup):?>
                        <?php for($i = 0; $i < $aLineup['total']; $i++):?>
                        <li class="f-roster-position f-count-0 player-position" <?php if($aPool['is_round'] == 1):?>style="padding-left: 0;background: none;"<?php endif;?>>
                            <div class="f-player-image" <?php if($aPool['is_round'] == 1):?>style="display: none;"<?php endif;?>></div>
                            <div class="f-position"><?php echo '';?>
                                <span class="f-empty-roster-slot-instruction"><?php echo __('Add player', FV_DOMAIN);?></span>
                            </div>
                            <div class="f-player"></div>
                            <a class="f-button f-tiny f-text">
                                <i class="fa fa-minus-circle"></i>
                            </a>
                        </li>
                        <?php endfor;?>
                    <?php endforeach;?>
                <?php else:?>  
                    <?php for($i = 0; $i < $aLineups; $i++):?> 
                        <li class="f-roster-position f-count-0 player-position-0" <?php if($aPool['is_round'] == 1):?>style="padding-left: 0;background: none;"<?php endif;?>>
                            <div class="f-player-image" <?php if($aPool['is_round'] == 1):?>style="display: none;"<?php endif;?>></div>
                            <div class="f-position">
                                <span class="f-empty-roster-slot-instruction"><?php echo __('Add player', FV_DOMAIN);?></span>
                            </div>
                            <div class="f-player"></div>
                            <div></div>
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
                    <input type="hidden" value="<?php echo $league['gameType']; ?>" name="game_type" id="game_type" >
                    <input type="hidden" value="0" name="total_money" id="total_money">
                    <input type="hidden" value='' name="players" id="players">
                    <input type="hidden" value='<?php echo $league['poolID'] ?>' name="poolID">
                </form>
            </div>
            <div class="f-contest-enter-button-container">
                <input type="submit" data-nav-warning="off" id="btnSubmit" value="<?php echo __('Enter', FV_DOMAIN);?>" class="f-button f-jumbo f-primary" onclick="jQuery.playerdraft.golfSkinSubmitData()">
            </div>
        </footer>
    </section>
</div>
<?php require_once(FANVICTOR__PLUGIN_DIR_VIEW.'dlg_info.php');?>
<script type="text/template" id="dataPlayers">
    <?php echo json_encode($aPlayers);?>
</script>
<script type="text/template" id="dataSalaryRemaining">
    <?php echo $aPool['salary_remaining'];?>
</script>
<script type="text/template" id="dataPlayerIdPicks">
    <?php echo json_encode($playerIdPicks);?>
</script>
<script type="text/template" id="dataLeague">
    <?php echo json_encode($league);?>
</script>
<script type="text/template" id="dataFights">
    <?php echo json_encode($aFights);?>
</script>
<script type="text/template" id="dataPool">
    <?php echo json_encode($aPool);?>
</script>
<script type="text/template" id="dataIndicators">
    <?php echo json_encode($aIndicators);?>
</script>
<script type="text/template" id="dataBalance">
    <?php echo $balance;?>
</script>
<script type="text/template" id="dataPlayerGolfSkin">
    <?php echo $aPlayerGolfSkin;?>
</script>
<script type="text/template" id="dataTotalMoney">
    <?php echo $total_money;?>
</script>
<script type="text/template" id="dataentryFee">
    <?php echo $entry_fee;?>
</script>
<script type="text/template" id="dataIsEntryFee">
    <?php echo $is_entry_fee;?>
</script>
<script type="text/javascript">
    jQuery.playerdraft.golfSkinSetData();
</script>