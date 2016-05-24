<div class="contentPlugin">    <h3 id="gametitle_current"><?php echo $league['name'];?></h3>    <div class="f-column-12 f-row">        <div class="f-clearfix" id="league-info">            <div class="f-column-4" id="league-info-size"><?php echo $league['size'];?> <?php echo __('Player League', FV_DOMAIN);?>                <span class="f-entries f-link" href="#" onclick="return jQuery.playerdraft.dlgEntries(<?php echo $league['leagueID'];?>, '<?php echo $league['name'];?>');">                    (<?php echo $league['entries'];?> <?php echo __('entries so far', FV_DOMAIN);?>)                </span>            </div>            <?php if(get_option('fanvictor_no_cash') == 0):?>            <div class="f-column-2" id="league-info-stake"><?php echo __('Entry fee:', FV_DOMAIN);?>                $<?php echo $league['entry_fee'];?>					            </div>                        <div class="f-column-6 f-text-align-right" id="league-info-prizes">                <?php echo __('Prize:');?>  <span class="f-showAllPrizes clickable f-link" href="#" onclick="return jQuery.playerdraft.dlgPrize(<?php echo $league['leagueID'];?>, '<?php echo $league['name'];?>', '<?php echo $league['entry_fee'];?>', '<?php echo $aPool['salary_remaining'];?>');">                    <?php echo __('Show All', FV_DOMAIN);?>                </span>            </div>            <?php endif;?>        </div>        <!--<div class="f-gamestatus f-gamestatus_open"><?php echo __('Game starts in', FV_DOMAIN);?>            <div id="countdown_1" class="f-countdown"></div>        </div>-->    </div>    <div class="f-rostertop row">        <div class="f-player">            <div class="f-opponent">                <div class="f-seatroster" id="thisroster">                    <table cellspacing="2" class="f-roster">                        <thead>                            <tr>                                <th style="font-size:12px" class="f-username" colspan="2">                                    <div class="f-button-container">                                        <a href="<?php echo FANVICTOR_URL_GAME.$leagueID."/?num=".$entry_number;?>" style="font-weight:normal;" class="f-button f-mini f-text-grey1"><?php echo __('Edit', FV_DOMAIN)?></a>                                     </div>                                    <a>                                        <div class="f-avatar f-small" style="background-image:url(<?php echo $user_avatar;?>)"></div>                                        <?php echo $current_user->display_name;?>                                    </a>                                </th>                                <th class="f-username"></th>                            </tr>                        </thead>                        <tbody>                            <?php if(!empty($aPlayers)):?>                            <?php foreach($aPlayers as $aPlayer):?>                            <tr class="f-pregame f-no-scoring">                                                                <td class="f-position">                                    <?php if($league['option_type'] != 'salary'):?>                                    <?php echo $aPlayer['position'];?>                                    <?php endif;?>                                </td>                                                                <td class="f-player" style="vertical-align: middle;">                                    <div class="f-name"><?php echo $aPlayer['name'];?></div>                                    <?php if(empty($aPool['only_playerdraft']) || !$aPool['only_playerdraft']):?>                                    <div class="f-fixture">                                        <?php if($aPlayer['teamID1'] == $aPlayer['team_id']):?>                                            <strong><?php echo $aPlayer['team1'];?></strong> @ <?php echo $aPlayer['team2'];?>                                        <?php else:?>                                            <?php echo $aPlayer['team1'];?> @ <strong><?php echo $aPlayer['team2'];?></strong>                                        <?php endif;?>                                    </div>                                    <?php endif;?>                                </td>                                <td class="f-score"></td>                            </tr>                            <?php endforeach;?>                            <?php endif;?>                        </tbody>                    </table>                </div>                <div class="f-column-4 f-text-align-center" id="f-whatnext">                    <h2><?php echo __('What next?', FV_DOMAIN);?></h2>                    <div id="invitePane">                        <input type="button" style="width:100%" value="<?php echo __('Challenge friends', FV_DOMAIN);?>" class="f-button f-primary f-fullwidth" onclick="jQuery.playerdraft.showDialog('#dlgFriends')">                    </div>                    <a class="f-showMultipleEntryLB f-button f-fullwidth" href="<?php echo FANVICTOR_URL_LOBBY;?>"><?php echo __('Enter other contests', FV_DOMAIN);?></a>		                </div>                <div class="clear"></div>            </div>        </div>    </div></div><?php require_once('dlg_info.php');?><?php require_once('dlg_info_friends.php');?><script type="text/javascript">    var showInviteFriends = '<?php echo $showInviteFriends;?>';    if(showInviteFriends)    {        jQuery.playerdraft.showDialog('#dlgFriends')    }    <?php if($allow_pick_email):?>    jQuery(window).load(function(){        jQuery.playerdraft.sendUserPickEmail('<?php echo $league['leagueID'];?>');    })    <?php endif;?></script>