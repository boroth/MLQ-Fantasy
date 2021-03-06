jQuery.admin =
{
    action: function(id, sAction, sUrl)
    {
        var task = jQuery("#submitTask");
        switch (sAction)
        {
            case 'delete':
                jQuery("#js_id_row" + id).attr('checked', true);
                if(!this.checkSelectedItem())
                {
                    alert('Please select an item');
                }
                else if(confirm(wpfs['a_sure']))
                {
                    task.val("delete");
                    this.doSubmit();
                }
                else 
                {
                    jQuery("#js_id_row" + id).removeAttr('checked');
                    task.val('submitTask');
                }
                break;
            case 'upload':
                task.val("upload");
                this.doSubmit();
            break;
            default:
                break;	
        }

        return false;
    },
    
    doSubmit : function(task)
    {
        var frm = document.adminForm;
        /*if(task != '')
        {
            frm.action = frm.action + task
        }*/
        frm.submit();
    },

    checkSelectedItem : function()
    {
        if(jQuery('input[name$="id[]"]:checked').length > 0)
        {
            return true;
        }
        return false;
    },
    
    newImage : function()
    {
        jQuery("#js_submit_upload_image").show();
        jQuery("#js_slide_current_image").remove();
    },
    
    userCredits : function(item, userID, task, sTitle)
    {
        var payment = this;
        var dialog = jQuery("#dlgUserCredits").dialog({
            height: 'auto',
            width:'400',
            modal:true,
            title:sTitle,
            open:function(){
                jQuery('#msgUserCredits').empty().hide(); 
                jQuery('#formUserCredits')[0].reset();
                var user = jQuery(item).parents('tr');
                jQuery("#formUserCredits").find('.user_id').val(userID);
                jQuery("#formUserCredits").find('.full_name').empty().append(user.find('.column-name').text());
                jQuery("#formUserCredits").find('.total_balance').empty().append(user.find('.column-balance').text());
                jQuery("#formUserCredits").find('.payment_request_pending').empty().append(user.find('.column-payment_request_pending').text());
            },
            buttons: {
                "Send": function() {
                    payment.sendUserCredits(task, jQuery(item).parents('tr'), userID);
                },
                Cancel: function() {
                    dialog.dialog( "close" );
                }
            },
        });
        return false;
    },
    
    sendUserCredits: function(task, obj, userID)
    {
        var dataString = jQuery('#formUserCredits').serialize();
        jQuery.post(ajaxurl, 'action=sendUserCredits&task=' + task + "&" + dataString, function(result) {
            var data = JSON.parse(result);
            if(data.notice)
            {
                jQuery('#msgUserCredits').empty().append(data.notice).show();
            }
            if(data.result)
            {
                alert(data.result);
                jQuery.admin.loadUser(obj, userID);
                jQuery("#dlgUserCredits").dialog('close');
            }
	});
    },
    
    activeOrgsSetting: function(id, active)
    {
        var data = {
            action: 'activeOrgs',
            id : id,
            active: active
        };
        jQuery.post(ajaxurl, data, function(result) {
            result = JSON.parse(result);
            if(result.notice)
            {
                alert(result.notice);
            }
            else
            {
                var item = jQuery('#setting' + id);
                if(item.find('.active').is(':visible'))
                {
                    item.find('.unactive').show();
                    item.find('.active').hide();
                }
                else
                {
                    item.find('.active').show();
                    item.find('.unactive').hide();
                }
            }
	});
    },
    
    reversePointOrgsSetting: function(id, active)
    {
        var data = {
            action: 'reversePointOrgs',
            id : id,
            active: active
        };
        jQuery.post(ajaxurl, data, function(result) {
            result = JSON.parse(result);
            if(result.notice)
            {
                alert(result.notice);
            }
            else
            {
                var item = jQuery('#rv_setting' + id);
                if(item.find('.active').is(':visible'))
                {
                    item.find('.unactive').show();
                    item.find('.active').hide();
                }
                else
                {
                    item.find('.active').show();
                    item.find('.unactive').hide();
                }
            }
	});
    },
    
    activeScoringCategorySetting: function(id, active)
    {
        var data = {
            action: 'activeScoringCategory',
            id : id,
            active: active
        };
        jQuery.post(ajaxurl, data, function(result) {
            result = JSON.parse(result);
            if(result.notice)
            {
                alert(result.notice);
            }
            else
            {
                var itemActive = jQuery('#active' + id);
                var itemUnActive = jQuery('#unactive' + id);
                if(itemActive.is(':visible'))
                {
                    itemUnActive.show();
                    itemActive.hide();
                }
                else
                {
                    itemActive.show();
                    itemUnActive.hide();
                }
            }
	});
    },
    
    loadUser: function(obj, userID)
    {
        var data = {
            action: 'loadUser',
            user_id : userID
        };
        jQuery.post(ajaxurl, data, function(result) {
            var result = JSON.parse(result);
            obj.find('.column-ID').empty().append(result.ID);
            obj.find('.column-name').empty().append(result.user_login);
            obj.find('.column-balance').empty().append(result.balance);
            obj.find('.column-payment_request_pending').empty().append(result.payment_request_pending);
	});
    },
    
    userWithdrawls : function(obj, userID, sTitle)
    {
        var payment = this;
        var dialog = jQuery("#dlgUserWithdrawls").dialog({
            height: 'auto',
            width:'auto',
            modal:true,
            title:sTitle,
            open:function(){
                jQuery('#msgUserWithdrawls').empty().hide(); 
                jQuery('#formUserWithdrawls')[0].reset();
                var user = jQuery(obj).parents('tr');
                jQuery("#formUserWithdrawls").find('.withdrawlID').val(user.find('.withdrawlID').val());
                jQuery("#formUserWithdrawls").find('.full_name').empty().append(user.find('.column-name').text());
                jQuery("#formUserWithdrawls").find('.amount').empty().append(user.find('.column-amount').text());
                jQuery("#formUserWithdrawls").find('.real_amount').empty().append(user.find('.column-real_amount').text());
                jQuery("#formUserWithdrawls").find('.request_date').empty().append(user.find('.column-requestDate').text());
                jQuery("#formUserWithdrawls").find('.reason').empty().append(user.find('.reason').val());
                jQuery("#formUserWithdrawls").find('.status').val(user.find('.column-status').val());
                jQuery("#formUserWithdrawls").find('.response_message').val(user.find('.response_message').val());
            },
            buttons: {
                "Send": function() {
                    payment.sendUserWithdrawls(jQuery(obj).parents('tr'), userID);
                },
                Cancel: function() {
                    dialog.dialog( "close" );
                }
            },
        });
        return false;
    },
    
    sendUserWithdrawls: function(obj, userID)
    {
        var dataString = jQuery('#formUserWithdrawls').serialize();
        jQuery.post(ajaxurl, 'action=sendUserWithdrawls&' + dataString, function(result) {
            var data = JSON.parse(result);
            if(data.notice)
            {
                jQuery('#msgUserWithdrawls').empty().append(data.notice).show();
            }
            else if(data.redirect)
            {
                window.location = data.redirect;
            }
            else if(data.result)
            {
                alert(data.result);
                jQuery(obj).find('.status').empty().append(jQuery("#formUserWithdrawls").find('.status').val());
                jQuery(obj).find('.response_message').val(jQuery("#formUserWithdrawls").find('.response_message').val());
                jQuery("#dlgUserWithdrawls").dialog('close');
            }
        })
    },
    
    showPoolStatisticDetail : function(poolID, sTitle)
    {
        var dialog = jQuery("#dlgStatistic").dialog({
            height: 'auto',
            width:'800',
            modal:true,
            title:sTitle,
            buttons: {
                'Close': function() {
                    dialog.dialog( "close" );
                }
            }
        });
        var data = {
            action: 'showPoolStatisticDetail',
            poolID : poolID
        };
        jQuery.post(ajaxurl, data, function(result) {
            var data = JSON.parse(result);
            jQuery("#dlgStatistic").empty().append(data.result);
        })
        return false;
    },
    
    showUserPicks: function(leagueID)
    {
        jQuery('#dlgPicks').empty().append('<center>' + wpfs['pleasewait'] + '</center>');
        var dialog = jQuery("#dlgPicks").dialog({
            width:'800',
            modal:true,
			title:wpfs['pleasewait'],
            buttons: {
                'Close': function() {
                    dialog.dialog( "close" );
                }
            }
        });
        var data = {
            action: 'showUserPicks',
            leagueID : leagueID
        };
        jQuery.post(ajaxurl, data, function(data) {
            jQuery("#dlgPicks").empty().append(data);
            jQuery.admin.showPicksDetail();
            var league = jQuery.parseJSON(jQuery("#leagueInfo").val()); 
			var sTitle = ' ';
			if(league)
			{
				sTitle = league.name;
			}
            jQuery("#dlgPicks").dialog({
                height: 'auto',
                width:'800',
                modal:true,
                title:sTitle,
                buttons: {
                    'Close': function() {
                        dialog.dialog( "close" );
                    }
                }
            });
        })
        return false;
    },
    
    showPicksDetail: function()
    {
        var user_id = jQuery("#cbUsers").val();
        jQuery('.cbEntry').hide();
        jQuery('#cbEntry' + user_id).show();
        var entry_number = jQuery('#cbEntry' + user_id + ':visible').val();
        if(typeof entry_number == 'undefined')
        {
            entry_number = 1;
        }
        var pick_items = ''; 
        var picks = jQuery.parseJSON(jQuery("#pickData").val()); 
        var league = jQuery.parseJSON(jQuery("#leagueInfo").val()); 
        var entries = '';
        
        //find players list
        for(var i in picks)
        {
            if(picks[i].userID == user_id)
            {
                for(var j in picks[i].entries)
                {
                    var entries = picks[i].entries;
                    if(entries[j].entry_number == entry_number)
                    {
                        pick_items = entries[j].pick_items;
                        break;
                    }
                }
                break;
            }
        }

        //show players
        var html = '';
        for(var i in pick_items)
        {
            var html_fight_name = '';
            if(league.gameType == 'PLAYERDRAFT' && league.is_team == 1)
            {
                html_fight_name = '<td>' + pick_items[i].team_name + '</td>';
            }
            else if(league.gameType != 'PLAYERDRAFT') 
            {
                html_fight_name = '<td>' + pick_items[i].fight_name + '</td>';
            }
            html += 
                '<tr>\n\
                    <td>' + pick_items[i].id + '</td>\n\
                    ' + html_fight_name + '\n\
                    <td>' + pick_items[i].name + '</td>\n\
                </tr>';
        }
        jQuery('#tbPickDetail tbody').empty().append(html);
    },
    
    showInviteFriendDialog: function(sTitle, leagueID){
        jQuery('#formInviteFriend')[0].reset();
        var dialog = jQuery("#dlgFriends").dialog({
            maxHeight: 500,
            width:800,
            minWidth:600,
            modal:true,
            title:sTitle,
            open: function() {
                jQuery('.ui-widget-overlay').addClass('custom-overlay');
            }
        });
        jQuery('#formInviteFriend #leagueIdValue').val(leagueID);
    },
    
    sendPrivateInvitationEmail: function()
    {
        jQuery('#formInviteFriend .loading').show();
        jQuery('#formInviteFriend #btnSendInvite').attr('disabled', 'disabled');
        jQuery.post(ajaxurl, jQuery('#formInviteFriend').serialize() + '&action=sendPrivateInvitationEmail', function(data) {
            var json = jQuery.parseJSON(data);
            if(json.message)
            {
                alert(json.message);
            }
            else if(json.notice)
            {
                alert(json.notice);
            }
            jQuery('#formInviteFriend .loading').hide();
            jQuery('#formInviteFriend #btnSendInvite').removeAttr('disabled');
        })
    },
    
    checkAllFriends: function()
    {
        jQuery("input[name='friend_ids[]']").attr('checked', true);
    },

    checkNoneFriends: function()
    {
        jQuery("input[name='friend_ids[]']").removeAttr('checked');
    },
    showInfoManageSport: function(){
            var dialog = jQuery("#readmeDialog").dialog({
            height: 'auto',
            width:'400',
            modal:true,
            title:"Read me",
            open:function(){
                
            },
            buttons: {
                Cancel: function() {
                    dialog.dialog( "close" );
                }
            },
        });
    }
}

