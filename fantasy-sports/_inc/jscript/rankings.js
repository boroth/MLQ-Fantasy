jQuery.ranking = {
    enterLeagueHistory:function(entry_number, page)
    {
        var leagueID = jQuery("#importleagueID").val();
        if(typeof page == 'undefined')
        {
            page = 1;
        }
        var sort_by = jQuery('#sortBy').val();
        var sort_value = jQuery('#sortValue').val();

        var data = {
            action: 'getNormalGameResult',
            leagueID : leagueID,
            entry_number: entry_number,
            page: page,
            sort_by: sort_by,
            sort_value: sort_value
        };
        jQuery.ajaxSetup({async:false});
        jQuery.post(ajaxurl, data, function(result) {
            jQuery("#dataResult").val(result);
            result = jQuery.parseJSON(result);
            var league = result.league;
            var pool = result.pool;
            var num_page = result.num_page;
            var current_page = result.current_page;
            var user_sort_class = rank_sort_class = '';
            switch(sort_by)
            {
                case 'user':
                    user_sort_class = sort_value;
                    break;
                case 'rank':
                    rank_sort_class = sort_value;
                    break;
            }
            
            //list players
            var users = result.users;
            var current_user = '';
            var htmlForMethod = '';
            var htmlPickultimate = '';
            var htmlMultiEntry = '';
            if(pool.allow_method == 1)
            {
                htmlForMethod = 
                    '<th>' + wpfs['h_Methods'] + '</th>\n\
                    <th>' + wpfs['h_Rounds'] + '</th>\n\
                    <th>' + wpfs['h_Minutes'] + '</th>\n\
                    <th>' + wpfs['h_Bonuses'] + '</th>';
            }
            if(league.gameType == 'PICKULTIMATE')
            {
                htmlPickultimate = 
                    '<th>Game Vs. Points</th>\n\
                    <th>Total Score</th>';
            }
            if(league.multi_entry == 1)
            {
                htmlMultiEntry = '<th style="width:13%">Entry number</th>';
            }
			
			var htmlCash = '';
            if(no_cash == 0)
            {
                htmlCash += 
                    '<th style="width:10%">' + wpfs['h_Winnings'] + '</th>';
            }
            var htmlPlayers = 
                '<table class="table table-bordered table-responsive table-condensed">\n\
                    <tr>\n\
                        <th class="sort '+ user_sort_class + '" onclick="jQuery.ranking.doSort(' + entry_number + ', ' + page + ', \'user\')">' + wpfs['h_User'] + '</th>\n\
                        ' + htmlMultiEntry + '\n\
                        <th style="width:10%;" class="sort ' + rank_sort_class + '" onclick="jQuery.ranking.doSort(' + entry_number + ', ' + page + ', \'rank\')">' + wpfs['h_Rank'] + '</th>\n\
                        <th style="width:10%">' + wpfs['h_Points'] + '</th>\n\
                        <th style="width:10%">' + wpfs['h_Winners'] + '</th>\n\
                        ' + htmlForMethod + '\n\
                        ' + htmlPickultimate + '\n\
                        ' + htmlCash + '\n\
                    </tr>';
            if(users != null)
            {
                for(var i=0; i<users.length;i++)
                {
                    user = users[i];
                    var htmlSelect = '';
                    var htmlPredict = '';
                    htmlPickultimate = '';
                    htmlMultiEntry = '';
                    if(league.gameType == 'PICKTIE')
                    {
                        htmlPredict = '<br/>Predict total point: ' + user.predict_point;
                    }
                    if(!user.current)
                    {
                        if(league.gameType == 'PICKSQUARES'){
                            htmlSelect = '<input type="radio" id="userinfo' + user.userID + user.entry_number +'" name="user" onclick="jQuery.ranking.selectPickSquaresUser(' + user.userID + ', ' + user.entry_number + ');">';

                        }else{
                            htmlSelect = '<input type="radio" id="userinfo' + user.userID + user.entry_number +'" name="user" onclick="jQuery.ranking.selectUser(' + user.userID + ', ' + user.entry_number + ');">';

                        }
                    }
                    if(pool.allow_method == 1)
                    {
                        htmlForMethod = 
                            '<td>' + user.methods + '</td>\n\
                            <td>' + user.rounds + '</td>\n\
                            <td>' + user.minutes + '</td>\n\
                            <td>' + user.bonuses + '</td>';
                    }
                    if(league.gameType == 'PICKULTIMATE')
                    {
                        htmlPickultimate = 
                            '<td>' + user.winner_spread + '</td>\n\
                            <td>' + user.winner_over_under + '</td>';
                    }
                    if(league.multi_entry == 1)
                    {
                        htmlMultiEntry = '<td>' + user.entry_number + '</td>';
                    }
					
                    htmlCash = '';
                    if(no_cash == 0)
                    {
                        htmlCash += 
                            '<td>' + user.winnings + '</td>';
                    }
                    htmlPlayers += 
                        '<tr>\n\
                            <td>\n\
                                ' + htmlSelect + '\n\
                                <label for="userinfo' + user.userID + user.entry_number + '">' + user.user_login + '</label>\n\
								' + htmlPredict + '\n\
                            </td>\n\
                            ' + htmlMultiEntry + '\n\
                            <td>' + user.rank + '</td>\n\
                            <td>' + user.points + '</td>\n\
                            <td>' + user.winners + '</td>\n\
                            ' + htmlForMethod + '\n\
                            ' + htmlPickultimate + '\n\
                            ' + htmlCash + '\n\
                        </tr>';
                    if(user.current == 1)
                    {
                        current_user = user;
                    }
                }
            }
            htmlPlayers += '</table>';
            jQuery("#listPlayers").empty().append(htmlPlayers);
            
            //fixtures
            //if(jQuery("#listFixtures").html() == '')
            //{
                var fights = result.fights;
                var htmlFixtures = 
                    '<table class="table table-bordered table-responsive table-condensed">\n\
                        <tr>\n\
                            <th>Fixture</th>\n\
                            <th style="width:25%" id="myResultHeader">My Pick (' + current_user.user_login + ')</th>\n\
                            <th style="width:25%" id="yourResultHeader">Competitor Pick</th>\n\
                            <th style="width:25%">Actual Result</th>\n\
                        </tr>';
                if(fights != null && league.gameType != 'PICKSQUARES')
                {
                    for(var i=0; i<fights.length; i++)
                    {
                        fight = fights[i];
                        var styleTeam1Win = styleTeam2Win = 'style="color:red"';
                        var htmlComplete = '';
                        if(fight.winnerID == fight.fighterID1)
                        {
                            styleTeam1Win = 'style="color:green"';
                        }
                        if(fight.winnerID == fight.fighterID2)
                        {
                            styleTeam2Win = 'style="color:green"';
                        }
                        if(fight.winnerID == 0){
                            styleTeamTie = 'style="color:green"';
                        }
                        if(league.is_complete)
                        {
                            htmlComplete = 
                                '<div ' + styleTeam1Win + '>' + fight.name1 + ' ' + fight.team1score + '</div>\n\
                                <div ' + styleTeam2Win + '>' + fight.name2 + ' ' + fight.team2score + '&nbsp;</div>';
                                if(fight.winnerID == 0){
                                    htmlComplete = 
                                '<div ' + styleTeamTie + '>' + fight.name1 + ' ' + fight.team1score + '</div>\n\
                                <div ' + styleTeamTie + '>' + fight.name2 + ' ' + fight.team2score + '&nbsp;</div>';
                                }
                            if(fight.method != '')
                            {
                                htmlComplete += '<div>Method: ' + fight.method + '</div>';
                            }
                            if(fight.round != '')
                            {
                                htmlComplete += '<div>Round: ' + fight.round + '</div>';
                            }
                            if(fight.minute != '')
                            {
                                htmlComplete += '<div>Minute: ' + fight.minute + '</div>';
                            }
                        }
                        var team1_spread_points = team2_spread_points = '';
                        if(league.gameType == "PICKSPREAD" || league.gameType == "PICKULTIMATE")
                        {
                            team1_spread_points = ' ' + fight.team1_spread_points;
                            team2_spread_points = ' ' + fight.team2_spread_points;
                        }
                        else if(league.gameType == "PICKMONEY")
                        {
                            team1_spread_points = ' ' + fight.team1_moneyline;
                            team2_spread_points = ' ' + fight.team2_moneyline;
                        }
                        htmlFixtures += 
                            '<tr>\n\
                                <td>' + fight.name1 + '<br/>' + team1_spread_points + '\n\
                                    <div>VS</div>' + fight.name2 + '<br/>' + team2_spread_points + '</td>\n\
                                <td id="myresult_' + fight.fightID + '">\n\
                                </td>\n\
                                <td id="yourresult_' + fight.fightID + '"></td>\n\
                                <td>\n\
                                    <div class="h_column actual_result">\n\
                                        ' + htmlComplete + '\n\
                                    </div>\n\
                                </td>\n\
                            </tr>';
                    }
                    htmlFixtures += 
                        '<tr>\n\
                            <td>&nbsp;</td>\n\
                            <td>\n\
                                <div id="myTotalPoints"></div>\n\
                            </td>\n\
                            <td>\n\
                                <div id="YourTotalPoints"></div>\n\
                            </td>\n\
                            <td>&nbsp;</td>\n\
                        </tr>';
                }else if(fights != null && league.gameType == 'PICKSQUARES'){
                    /* for type picksquares*/
                     fight = fights[0];
                     var picksquare = [];
                     if(users != null){
                         user = users[0];
                         picksquare = jQuery.parseJSON(user.picks);
                     }
                     
                    htmlComplete = 
                                '<div>'  +(Math.abs(fight.team1score) % 10)+'_'+(Math.abs(fight.team2score) % 10) +'</div>\n\
                                ';
                        htmlFixtures += 
                            '<tr>\n\
                                <td>' + fight.name1 + '<br/>\n\
                                    <div>VS</div>' + fight.name2 + '<br/></td>\n\
                                <td id="myresult_' + fight.fightID + '">'+picksquare.join()+'\n\
                                </td>\n\
                                <td id="yourresult_' + fight.fightID + '"></td>\n\
                                <td>\n\
                                    <div class="h_column actual_result">\n\
                                        ' + htmlComplete + '\n\
                                    </div>\n\
                                </td>\n\
                            </tr>';
                }
                
                
                htmlFixtures += '</table>';
                
                jQuery("#listFixtures").empty().append(htmlFixtures);
            //}
            
            //paging
            var htmlPaging = '';
            if(num_page > 0)
            {
                for(var i = 1; i <= num_page; i++)
                {
                    var class_active = '';
                    if(current_page == i)
                    {
                        class_active = 'active';
                    }
                    htmlPaging += '<div class="dib ' + class_active + '" onclick="jQuery.ranking.enterLeagueHistory(' + entry_number + ', ' + i + ')">' + i + '</div>';
                }
            }
            jQuery('#paging_ranking').empty().append(htmlPaging);
            jQuery.ranking.showUserResult(current_user, 1);
        });
        jQuery.ajaxSetup({async:true});
    },
    
    doSort: function(entry_number, page, sort_by)
    {
        var sort_value = jQuery('#sortValue').val();
        if(sort_value == 'asc' || sort_value == '')
        {
            sort_value = 'desc';
        }
        else if(sort_value == 'desc')
        {
            sort_value = 'asc';
        }
        jQuery('#sortBy').val(sort_by);
        jQuery('#sortValue').val(sort_value);
        this.enterLeagueHistory(entry_number, page);
    },
    
    selectUser: function(selID, entry_number)
    {
        var result = jQuery("#dataResult").val();
        result = jQuery.parseJSON(result);
        var league = result.league;
        var users = result.users;
        if(!league.can_view_user)
        {
            alert("You can see another users' picks after league start only.");
        }
        else 
        {
            if(users != null)
            {
                for(var i;i<users.length;i++)
                {
                    if(users[i].userID == selID && users[i].entry_number == entry_number)
                    {
                        jQuery.ranking.showUserResult(users[i], 0);
                    }
                }
            }
        }
    },
    selectPickSquaresUser: function(selID, entry_number){
        var result = jQuery("#dataResult").val();
        result = jQuery.parseJSON(result);
        var league = result.league;
        var users = result.users;
        if(!league.can_view_user)
        {
            alert("You can see another users' picks after league start only.");
        }
        else 
        {
            if(users != null)
            {
                for(var i in users)
                {
                    if(users[i].userID == selID && users[i].entry_number == entry_number)
                    {
                        picksquare = jQuery.parseJSON(user.picks);
                        jQuery("#yourresult_"+users[i].fightID).empty().append( picksquare.join());
                        
                    }
                }
            }
        }
    }
    ,
    showUserResult: function(user, mypick)
    {
        var result = jQuery("#dataResult").val();
        result = jQuery.parseJSON(result);
        var league = result.league;
        var header = headerName = body = totalPoints = '';
        if(mypick == 1)
        {
            header = jQuery("#myResultHeader");
            headerName = "My Pick";
            body = "myresult_";
            totalPoints = "myTotalPoints";
        }
        else 
        {
            header = jQuery("#yourResultHeader");
            headerName = "Competitor Pick";
            body = "yourresult_";
            totalPoints = "YourTotalPoints";
        }
        header.empty().append(headerName + ' (' + user.user_login + ')');
        if(user.picks != null)
        {
            var html = '';
            var fixture = '';
            for(var i;i<user.picks.length;i++)
            {
                fixture = user.picks[i];
                var styleWinner = styleMethod = styleMinute = styleRound = stylematchOverUnder = styleWinnerSpread = 'style="color:red"';
                var htmlPoint = "No points";
                if(league.is_complete)
                {
                    if(fixture.matchWinner)
                    {
                        styleWinner = 'style="color:green"';
                    }
                    if(fixture.matchWinnerSpread)
                    {
                        styleWinnerSpread = 'style="color:green"';
                    }
                    if(fixture.matchMethod)
                    {
                        styleMethod = 'style="color:green"';
                    }
                    if(fixture.matchMinute)
                    {
                        styleMinute = 'style="color:green"';
                    }
                    if(fixture.styleRound)
                    {
                        styleRound = 'style="color:green"';
                    }
                    if(fixture.matchOverUnder)
                    {
                        stylematchOverUnder = 'style="color:green"';
                    }
                    if(fixture.points != '')
                    {
                        htmlPoint = 'Points: ' + fixture.points;
                    }
                }
                if(league.gameType == 'PICKULTIMATE')
                {
                    html = 
                        '<div ' + styleWinner + '>' + fixture.name + ' (Winner)</div>\n\
                        <div ' + styleWinnerSpread + '>' + fixture.name_spread + ' (Spread)</div>\n\
                        <div ' + stylematchOverUnder + '>' + fixture.over_under_value + ' ' + fixture.over_under + '&nbsp;</div>';
                }
                else 
                {
                    html = 
                        '<div ' + styleWinner + '>' + fixture.name + '</div>';
                }
                if(fixture.method != '')
                {
                    html += '<div ' + styleMethod + '>Method: ' + fixture.method + '&nbsp;</div>';
                }
                if(fixture.round != '')
                {
                    html += '<div ' + styleRound + '>Round: ' + fixture.round + '&nbsp;</div>';
                }
                if(fixture.minute != '')
                {
                    html += '<div ' + styleMinute + '>Minute: ' + fixture.minute + '&nbsp;</div>';
                }
                html += '<div>' + htmlPoint + '</div>';
                jQuery("#" + body + fixture.fightID).empty().append(html);
                jQuery("#" + totalPoints).empty().append("Total points " + user.points);
            }
        }
    },
    
    inviteFriends: function()
    {
        var dialog = jQuery("#dlgInviteFriend").dialog({
            width:600,
            minWidth:600,
            modal:true,
            open: function() {
                jQuery('.ui-widget-overlay').bind('click',function(){
                    jQuery('#dlgInviteFriend').dialog('close');
                })
                jQuery('.ui-widget-overlay').addClass('custom-overlay');
            }
        });
    },
    
    checkAll: function()
    {
        jQuery("input[name='val[friend_ids][]']").attr('checked', true);
    },

    checkNone: function()
    {
        jQuery("input[name='val[friend_ids][]']").removeAttr('checked');
    },
    
    sendInvite: function()
    {
        jQuery('#inviteForm').find('.inviting').show();
        var dataSring = jQuery('#inviteForm').serialize();
        jQuery.post(ajaxurl, 'action=sendInviteFriend&' + dataSring, function(result) {
            var data = JSON.parse(result);
            if(data.notice)
            {
                alert(data.notice);
            }
            else
            {
                alert(data.message);
                jQuery("#dlgInviteFriend").dialog('close');
                jQuery('#inviteForm').find('.inviting').hide();
            }
        })
        return false;
    },
    
    liveEntriesResult: function(poolID, leagueID, entry_number)
    {
         var data = {
            action: 'liveEntriesResult',
            poolID: poolID,
            leagueID: leagueID
        };
        jQuery.post(ajaxurl, data, function(result) {
            jQuery.ranking.enterLeagueHistory(entry_number);
        });
    }
}