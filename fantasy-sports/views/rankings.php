<table width="100%" border="0" class="table table-condensed table-responsive">
    <tr>
        <td valign="top">
            <h3><?php echo $aLeague['name'];?></h3>
        </td>
        <td valign="top" align="right"></td>
    </tr>
    <tr class="info">
        <td colspan="2">
            <table width="100%" border="0" class="table table-responsive">
                <tbody>
                    <tr class="info">
                        <td colspan="2">
                            <br>&nbsp;&nbsp;<b><?php echo __('Prize structure', FV_DOMAIN)?>:</b> <?php echo $aLeague['prize_structure'];?>
                            <br>&nbsp;&nbsp;<b><?php echo __('Sport', FV_DOMAIN)?>:</b> <?php echo $aPool['sport_name'];?>
							<br>&nbsp;&nbsp;<b><?php echo __('Event', FV_DOMAIN)?>:</b> <?php echo $aPool['poolName'];?>
                            <br>&nbsp;&nbsp;<b><?php echo __('Game Type', FV_DOMAIN)?>:</b> <?php echo $aLeague['gameType'];?>
                            <br>&nbsp;&nbsp;<b><?php echo __('Start', FV_DOMAIN)?>:</b> <?php echo $aLeague['startDate'];?>
                            <?php if($aLeague['multi_entry'] == 1):?>
                                - <?php echo __('Multi entry', FV_DOMAIN);?>
                            <?php endif;?>
                            <?php if(get_option('fanvictor_no_cash') == 0):?>
                            <br>&nbsp;&nbsp;<b><?php echo __('Ends', FV_DOMAIN)?>:</b> <?php echo __('Prizes paid next day', FV_DOMAIN);?>
                            <?php endif;?>
                            <br>&nbsp;&nbsp;<b><?php echo __('Creator', FV_DOMAIN)?>:</b> <?php echo $creator->data->user_login;?>
                            <br>&nbsp;&nbsp;<b><?php echo __('Players', FV_DOMAIN)?>:</b> <?php echo $aLeague['size'];?> player game, <?php echo $aLeague['entries'];?> entries
                        </td>
                        <?php if(get_option('fanvictor_no_cash') == 0):?>
                        <td width="170" align="center">
                            <br>
                            <div style="height:40px;-moz-border-radius: 5px;-webkit-border-radius: 5px;border: 1px solid #000;padding: 10px;background-color: #E6E6E6;">
                                <font size="4"><b>Entry</b> $<?php echo $aLeague['entry_fee'];?></font>
                            </div>
                        </td>
                        <td style="width:15px">&nbsp;</td>
                        <td width="170" align="center">
                            <br>
                            <div style=" height:40px;-moz-border-radius: 5px;-webkit-border-radius: 5px;border: 1px solid #000;padding: 10px;background-color: #E6E6E6;">
                                <font size="4"><b>Prizes</b> $<?php echo $aLeague['prizes'];?></font>
                            </div>
                        </td>
                        <td style="width:15px">&nbsp;</td>
                        <?php endif;?>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>
<input type="hidden" id="dataResult" />
<div id="league_history" >
    <input type="hidden" id="importleagueID" value="<?php echo $leagueID;?>" />
    <input type="hidden" id="sortBy"/>
    <input type="hidden" id="sortValue"/>
    <div class="leaguesHeader"></div>
    <br>
    <div id="listPlayers"></div>
    <div id="paging_ranking" class="pg-wrapper"></div>
    <br>
    <div class="results_caption"><?php echo __('Results for')?>:  <span class="competitor_name competitor_name_2"><?php echo __('Please select a user', FV_DOMAIN);?></span></div>
    <br>
    <div id="listFixtures"></div>
</div>
<br><br>

<!-- Modal -->
<div id="dlgInviteFriend" style="display: none;z-index: 99" title="<?php echo __('Invite your friends to play against you', FV_DOMAIN);?>">
    <form name="inviteForm" id="inviteForm">
        <input type="hidden" name="val[importleagueID]" value="<?php echo $leagueID;?>" />
        <div>
            <label><?php echo __('Attach a message', FV_DOMAIN);?></label>
            <br>
            <textarea rows='3' cols='58' style="width: 98%" name='val[message_boxinvite]'></textarea>
        </div>
        <br>
        <table class="table table-responsive">
            <tr>
                <td>
                    <div>
                        <label><?php echo __('Who would you like to invite?', FV_DOMAIN);?></label>
                        <div style="padding-bottom: 5px">
                            <input type="text" name="val[emails][]" placeholder="Enter email address" style="width:98%">
                        </div>
                        <div style="padding-bottom: 5px">
                            <input type="text" name="val[emails][]" placeholder="Enter email address" style="width:98%">
                        </div>
                        <div style="padding-bottom: 5px">
                            <input type="text" name="val[emails][]" placeholder="Enter email address" style="width:98%">
                        </div>
                        <div style="padding-bottom: 5px">
                            <input type="text" name="val[emails][]" placeholder="Enter email address" style="width:98%">
                        </div>
                        <div style="padding-bottom: 5px">
                            <input type="text" name="val[emails][]" placeholder="Enter email address" style="width:98%">
                        </div>
                    </div>
                    <br>
                    <div>
                        <input type='submit' class='button' value='<?php echo __('Send Invites', FV_DOMAIN);?>' onclick='jQuery.ranking.sendInvite(); return false;'>
                        <span class="inviting" style="display: none"><?php echo __('Sending...', FV_DOMAIN);?></span>
                    </div>
                </td>
            </tr>
        </table>
    </form>
</div>
<?php require_once('dlg_info_friends.php');?>

<script type="text/javascript">
    var isLive = <?php echo $aLeague['is_live'];?>;
    var status = '<?php echo $aPool['status'];?>';
    var showInviteFriends = '<?php echo $showInviteFriends;?>';
    if (isLive == 1 && status == 'NEW')
    {
        jQuery.ranking.liveEntriesResult('<?php echo $aLeague['poolID'];?>', '<?php echo $aLeague['leagueID'];?>', '<?php echo $entry_number;?>')
        setInterval(function(){ 
            jQuery.ranking.liveEntriesResult('<?php echo $aLeague['poolID'];?>', '<?php echo $aLeague['leagueID'];?>', '<?php echo $entry_number;?>')
        },60000);
    }
    else 
    {
        jQuery.ranking.enterLeagueHistory('<?php echo $entry_number;?>');
    }
    if(showInviteFriends)
    {
        //jQuery.ranking.inviteFriends();
        jQuery.playerdraft.showDialog('#dlgFriends');
    }
    
    <?php if($allow_pick_email):?>
    jQuery(window).load(function(){
        jQuery.playerdraft.sendUserPickEmail('<?php echo $aLeague['leagueID'];?>');
    })
    <?php endif;?>
</script>