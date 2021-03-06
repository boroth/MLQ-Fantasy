jQuery.playerdraft =
{
    setData : function()
    {
        this.aPlayers = jQuery('#dataPlayers').html();
        this.salaryRemaining = jQuery('#dataSalaryRemaining').html();
        this.salaryCap = jQuery('#dataSalaryRemaining').html();
        this.playerIdPicks = jQuery('#dataPlayerIdPicks').html();
        this.league = jQuery('#dataLeague').html();
        this.aFights = jQuery('#dataFights').html();
        this.aPool = jQuery('#dataPool').html();
        this.aIndicators = jQuery('#dataIndicators').html();
        this.scoringCat = '';
    },
    golfSkinSetData : function()
    {
        this.aPlayers = jQuery('#dataPlayers').html();
        this.salaryRemaining = jQuery('#dataSalaryRemaining').html();
        this.salaryCap = jQuery('#dataSalaryRemaining').html();
        this.playerIdPicks = jQuery('#dataPlayerIdPicks').html();
        this.league = jQuery('#dataLeague').html();
        this.aFights = jQuery('#dataFights').html();
        this.aPool = jQuery('#dataPool').html();
        this.aIndicators = jQuery('#dataIndicators').html();
        this.scoringCat = '';
        this.totalMoney = jQuery('#dataTotalMoney').html();
        this.balance = jQuery('#dataBalance').html();
        this.entry_fee = jQuery('#dataentryFee').html();
        this.is_entry_fee = jQuery('#dataIsEntryFee').html();
        aGolfSkinPlayers = JSON.parse(jQuery('#dataPlayerGolfSkin').html());
        if(jQuery.isArray(aGolfSkinPlayers)){
            this.aGolfSkinPlayers = {};
        }else{
            this.aGolfSkinPlayers  = aGolfSkinPlayers;
        }
    },
    mixSetData : function()
    {
        this.aPlayers = jQuery('#dataPlayers').html();
        this.salaryRemaining = jQuery('#dataSalaryRemaining').html();
        this.salaryCap = jQuery('#dataSalaryRemaining').html();
        this.playerIdPicks = jQuery('#dataPlayerIdPicks').html();
        this.league = jQuery('#dataLeague').html();
        this.aFights = jQuery('#dataFights').html();
        this.aPool = jQuery('#dataPool').html();
        this.aIndicators = jQuery('#dataIndicators').html();
        this.scoringCat = ''; 
        this.aLineUps = jQuery('#dataLineups').html();
        this.aPostiions = jQuery('#dataPositions').html();
    },
    loadPlayers: function()
    {
        var position_id = jQuery('.f-tabs li a.f-is-active').attr('data-id');
        var teamId1 = jQuery('.fixture-item.f-is-active').attr('data-team-id1');
        var teamId2 = jQuery('.fixture-item.f-is-active').attr('data-team-id2');
        var aPool = jQuery.parseJSON(this.aPool);
        var aPlayers = jQuery.parseJSON(this.aPlayers);
        var aIndicators = jQuery.parseJSON(this.aIndicators);
        var keyword = jQuery('#player-search').val().toString();
        var league = jQuery.parseJSON(this.league);
        if(aPlayers.length > 0)
        {
            var html = '';
            for(var i = 0; i < aPlayers.length; i++)
            {
                var aPlayer = aPlayers[i];
                if(keyword == '' || aPlayer.name.toString().search(new RegExp(keyword,'i')) > -1)
                {
                    if((typeof teamId1 == 'undefined' && typeof teamId2 == 'undefined') || 
                        (aPlayer.team_id == teamId1 || aPlayer.team_id == teamId2)
                       )
                    {
                        if((aPlayer.position_id == position_id) || 
                            position_id == '')
                        {
                            var match = '';
                            if(aPlayer.teamID1 == aPlayer.team_id)
                            {
                                match = '<b>' + aPlayer.team1 + '</b>@' + aPlayer.team2;
                            }
                            else 
                            {
                                match = aPlayer.team1 + '@<b>' + aPlayer.team2 + '</b>';
                            }
                            
                            //indicator
                            var htmlIndicator = '';
                            switch(aPlayer.indicator_alias)
                            {
                                case 'IR':
                                    htmlIndicator = '<span class="f-player-badge f-player-badge-injured-out">IR</span>';
                                    break;
                                case 'O':
                                    htmlIndicator = '<span class="f-player-badge f-player-badge-injured-out">O</span>';
                                    break;
                                case 'D':
                                    htmlIndicator = '<span class="f-player-badge f-player-badge-injured-possible">D</span>';
                                    break;
                                case 'Q':
                                    htmlIndicator = '<span class="f-player-badge f-player-badge-injured-possible">Q</span>';
                                    break;
                                case 'P':
                                    htmlIndicator = '<span class="f-player-badge f-player-badge-injured-probable">P</span>';
                                    break;
                                case 'NA':
                                    htmlIndicator = '<span class="f-player-badge f-player-badge-injured-out">NA</span>';
                                    break;
                            }
                            var positionName = aPlayer.position;
                            if(aPool.no_position == 1)
                            {
                                positionName = '&nbsp;';
                            }
                            
                            //pitcher for mlb
                            var htmlPitcher = '';
                            if(aPlayer.is_pitcher == 1)
                            {
                                htmlPitcher = ' <span class="f-player-badge f-player-badge-injured-possible">S</span> ';
                            }
                            if(league.gameType == 'GOLFSKIN'){
                                html += '<tr class="f-pR" data-role="player">\n\
                                        <td class="f-player-name">\n\
                                            <div onclick="jQuery.playerdraft.playerInfo(' + aPlayer.id + ')">' + aPlayer.name + htmlPitcher + htmlIndicator + '</div>\n\
                                        </td>';
                            }else{
                                html += '<tr class="f-pR" data-role="player">\n\
                                        <td class="f-player-position">' + positionName + '</td>\n\
                                        <td class="f-player-name">\n\
                                            <div onclick="jQuery.playerdraft.playerInfo(' + aPlayer.id + ')">' + aPlayer.name + htmlPitcher + htmlIndicator + '</div>\n\
                                        </td>';
                            }
                            
                            if(aPool.only_playerdraft == 0)
                            {
                                html += 
                                        '<td class="f-player-played">' + aPlayer.myteam + '</td>\n\
                                        <td class="f-player-fixture">' + match + '</td>';
                            }
                            if(league.gameType=='GOLFSKIN'){
                                html += '<td class="f-player-add">';
                            }else{
                                html +=
                                        '<td class="f-player-salary">$' + accounting.formatNumber(aPlayer.salary) + '</td>\n\
                                        <td class="f-player-add">'; 
                            }
                           
                            if(aPlayer.disable == 0)
                            {
                                html += 
                                    '<a class="f-button f-tiny f-text f-player-add-button" id="buttonAdd' + aPlayer.id + '" onclick="jQuery.playerdraft.addPlayer(' + aPlayer.id + ')">\n\
                                        <i class="fa fa-plus-circle"></i>\n\
                                    </a>\n\
                                    <a class="f-button f-tiny f-text f-player-remove-button" id="buttonRemove' + aPlayer.id + '" onclick="jQuery.playerdraft.clearPlayer(' + aPlayer.id + ')">\n\
                                        <i class="fa fa-minus-circle"></i>\n\
                                    </a>';
                            }
                            html += '</td>\n\
                                    </tr>';
                        }
                    }
                }
            }
            if(html != '')
            {
                jQuery('#listPlayers tbody').empty().append(html);
                jQuery('#listPlayers .f-player-list-empty').hide();
                jQuery('th.f-player-salary').trigger('click');
                jQuery('th.f-player-salary').trigger('click');

                //check player in line
                jQuery('.f-roster-position').each(function(){
                    var id = jQuery(this).attr('data-id');
                    jQuery('#buttonAdd' + id).hide();
                    jQuery('#buttonAdd' + id).parents('tr').addClass('f-player-in-lineup');
                    jQuery('#buttonRemove' + id).css('display', 'block');
                });
            }
            else
            {
                jQuery('#listPlayers tbody').empty();
                jQuery('#listPlayers .f-player-list-empty').show();
            }
        }
        return false;
    },
    mixingGetPoolByOrgID: function(org_id)
    {
         var aPool = jQuery.parseJSON(this.aPool);
         for(var i in aPool){
             if(aPool[i].organization == org_id){
                 return aPool[i];
             }
         }
         return false;
    },

    mixingLoadPlayers: function()
    {
        var org_id = jQuery("#mixing_orginazation_id").val();
        var position_id = jQuery('.f-tabs li a.f-is-active').attr('data-id');
        var teamId1 = jQuery('.fixture-item.f-is-active').attr('data-team-id1');
        var teamId2 = jQuery('.fixture-item.f-is-active').attr('data-team-id2');
        var aPool = jQuery.playerdraft.mixingGetPoolByOrgID(org_id);
        var aPlayers = jQuery.parseJSON(this.aPlayers);
        var aIndicators = jQuery.parseJSON(this.aIndicators);
        var keyword = jQuery('#mixing-player-search').val().toString();
        var teamOrgID = jQuery('.fixture-item.f-is-active').attr('data-sport-id');
        
     
        if( typeof teamOrgID != 'undefined' && teamOrgID != org_id){
            
//            jQuery('.f-pick-your-team').find('*').removeClass('s-sport-is-active');
//            var current_sport = jQuery('.fixture-item.f-is-active').parent('.f-fixture-picker-button-container').find('a:first');
//            jQuery.playerdraft.mixingSelectTypeSport(current_sport,teamOrgID,'Unlimited','Add PLAYERS');
//            return;
        }
        aPlayers = aPlayers[org_id];
        aIndicators = aIndicators[org_id];
        
        if(aPlayers.length > 0)
        {
            var html = '';
            for(var i = 0; i < aPlayers.length; i++)
            {
                var aPlayer = aPlayers[i];
                if(keyword == '' || aPlayer.name.toString().search(new RegExp(keyword,'i')) > -1)
                {
                    if((typeof teamId1 == 'undefined' && typeof teamId2 == 'undefined') || 
                        (aPlayer.team_id == teamId1 || aPlayer.team_id == teamId2)
                       )
                    {
                        if((aPlayer.position_id == position_id) || 
                            position_id == '')
                        {
                            var match = '';
                            if(aPlayer.teamID1 == aPlayer.team_id)
                            {
                                match = '<b>' + aPlayer.team1 + '</b>@' + aPlayer.team2;
                            }
                            else 
                            {
                                match = aPlayer.team1 + '@<b>' + aPlayer.team2 + '</b>';
                            }
                            
                            //indicator
                            var htmlIndicator = '';
                            switch(aPlayer.indicator_alias)
                            {
                                case 'IR':
                                    htmlIndicator = '<span class="f-player-badge f-player-badge-injured-out">IR</span>';
                                    break;
                                case 'O':
                                    htmlIndicator = '<span class="f-player-badge f-player-badge-injured-out">O</span>';
                                    break;
                                case 'D':
                                    htmlIndicator = '<span class="f-player-badge f-player-badge-injured-possible">D</span>';
                                    break;
                                case 'Q':
                                    htmlIndicator = '<span class="f-player-badge f-player-badge-injured-possible">Q</span>';
                                    break;
                                case 'P':
                                    htmlIndicator = '<span class="f-player-badge f-player-badge-injured-probable">P</span>';
                                    break;
                                case 'NA':
                                    htmlIndicator = '<span class="f-player-badge f-player-badge-injured-out">NA</span>';
                                    break;
                            }
                            var positionName = aPlayer.position;
                            if(aPool.no_position == 1)
                            {
                                positionName = '&nbsp;';
                            }
                            
                            //pitcher for mlb
                            var htmlPitcher = '';
                            if(aPlayer.is_pitcher == 1)
                            {
                                htmlPitcher = ' <span class="f-player-badge f-player-badge-injured-possible">S</span> ';
                            }
                            html += '<tr class="f-pR" data-role="player">\n\
                                        <td class="f-player-position">' + positionName + '</td>\n\
                                        <td class="f-player-name">\n\
                                            <div onclick="jQuery.playerdraft.playerInfo(' + aPlayer.id + ')">' + aPlayer.name + htmlPitcher + htmlIndicator + '</div>\n\
                                        </td>';
                            if(aPool.only_playerdraft == 0)
                            {
                                html += 
                                        '<td class="f-player-played">' + aPlayer.myteam + '</td>\n\
                                        <td class="f-player-fixture">' + match + '</td>';
                            }
                            html +=
                                        '<td class="f-player-salary">$' + accounting.formatNumber(aPlayer.salary) + '</td>\n\
                                        <td class="f-player-add">';
                            if(aPlayer.disable == 0)
                            {
                                html += 
                                    '<a class="f-button f-tiny f-text f-player-add-button" id="buttonAdd' + aPlayer.id + '" onclick="jQuery.playerdraft.addPlayer(' + aPlayer.id + ')">\n\
                                        <i class="fa fa-plus-circle"></i>\n\
                                    </a>\n\
                                    <a class="f-button f-tiny f-text f-player-remove-button" id="buttonRemove' + aPlayer.id + '" onclick="jQuery.playerdraft.clearPlayer(' + aPlayer.id + ')">\n\
                                        <i class="fa fa-minus-circle"></i>\n\
                                    </a>';
                            }
                            html += '</td>\n\
                                    </tr>';
                        }
                    }
                }
            }
            if(html != '')
            {
                jQuery('#listPlayers tbody').empty().append(html);
                jQuery('#listPlayers .f-player-list-empty').hide();
                jQuery('th.f-player-salary').trigger('click');
                jQuery('th.f-player-salary').trigger('click');

                //check player in line
                jQuery('.f-roster-position').each(function(){
                    var id = jQuery(this).attr('data-id');
                    jQuery('#buttonAdd' + id).hide();
                    jQuery('#buttonAdd' + id).parents('tr').addClass('f-player-in-lineup');
                    jQuery('#buttonRemove' + id).css('display', 'block');
                })
            }
            else
            {
                jQuery('#listPlayers tbody').empty();
                jQuery('#listPlayers .f-player-list-empty').show();
            }
        }
        return false;
    },
    mixingSelectTypeSport: function(item,org_id,salary_ullimited,sAddPlayer){

        jQuery('.f-pick-your-team').find('*').removeClass('s-sport-is-active');
        jQuery(item).addClass('s-sport-is-active');
        jQuery("#mixing_orginazation_id").val(org_id);
        // clear all players from selected
//       jQuery('.f-roster .f-roster-position').each(function(){
//                if(typeof jQuery(this).attr('data-id') != typeof undefined)
//                {
//                    jQuery.playerdraft.clearPlayer(jQuery(this).attr('data-id'));
//                }
//            }); 
        jQuery.playerdraft.mixingLoadPlayers();
        // handle salary
//        var htmlSalary = '';
//        var aPool = jQuery.playerdraft.mixingGetPoolByOrgID(org_id);
//        var salary = aPool.salary_remaining;
//        if(salary > 0){
//            htmlSalary = '$'+salary;
//        }else{
//            htmlSalary = salary_ullimited;
//        }
//        jQuery('#salaryRemaining').html(htmlSalary);
//        this.salaryRemaining = salary;
       
        // handle available players
        var aPositions = jQuery.parseJSON(this.aPostiions);
        aPositions = aPositions[org_id];
        var htmlPositions = '';
        for(var i in aPositions){
            htmlPositions += '<li>';
            htmlPositions += '<a href="" data-id="'+aPositions[i].id+'" onclick="jQuery.playerdraft.setActivePosition(this);return jQuery.playerdraft.mixingLoadPlayers();">';
            htmlPositions += aPositions[i].name +'</a>';
            htmlPositions += '</li>';
        }
        jQuery('ul.f-player-list-position-tabs>li').not('li:first,li:last').remove();
        jQuery('ul.f-player-list-position-tabs>li:first').after(htmlPositions);
        jQuery('ul.f-player-list-position-tabs>li:first a').click();
        
        
      
        // handle lineup
//        var aLineups = jQuery.parseJSON(this.aLineUps);
//        var htmlLineups = '';
//        aLineups = aLineups[org_id];
//        if(aLineups.constructor === Array){
//            for(var i in aLineups){
//                var lineUp = aLineups[i];;
//                for(var j = 0; j < lineUp.total; j++){
//                    htmlLineups+='<li class="f-roster-position f-count-0 player-position-'+aLineups[i].id+'">';
//                    htmlLineups+='<div class="f-player-image"></div>';
//                    htmlLineups+= '<div class="f-position">'+aLineups[i].name;
//                    htmlLineups+= '<span class="f-empty-roster-slot-instruction">'+sAddPlayer+'</span></div>';
//                    htmlLineups+= '<div class="f-player"></div>';
//                    htmlLineups+= '<div class="f-salary">$0</div>';
//                    htmlLineups+= '<div class="f-fixture"></div>';
//                    htmlLineups+= '<a class="f-button f-tiny f-text">';
//                    htmlLineups+= '<i class="fa fa-minus-circle"></i>';
//                    htmlLineups+= '</a></li>';
//                }
//            }
//           jQuery('section.f-roster ul').empty().html(htmlLineups);
//        }
        
          // handle avg salary
        //jQuery.playerdraft.calculateAvgPerPlayer();
    },
    
    setNoImage: function(item)
    {
        item.parent().addClass('f-no-image').css('background-image', '');
        item.remove();
    },
        setActiveFixture: function(item)
    {
        jQuery('.fixture-item').removeClass('f-is-active');
        jQuery(item).addClass('f-is-active');
        jQuery(item).blur();
        return false;
    },
    
    mixingLoadPlayersixture: function(item)
    {
        jQuery('.fixture-item').removeClass('f-is-active');
        jQuery(item).addClass('f-is-active');
        jQuery(item).blur();
        return false;
    },
    
    setActivePosition: function(item)
    {
        jQuery('.f-tabs li a').removeClass('f-is-active');
        jQuery(item).addClass('f-is-active');
        jQuery(item).blur();
        return false;
    },
    
    doSort: function(item)
    {
        jQuery("#listPlayers table").tablesorter(); 
        jQuery("#listPlayers table").trigger("updateAll");
        var index = item.index() + 1;
        jQuery("#listPlayers table").trigger("sorton",[ [[index,"n"]] ]);
        if(this.sortIndex != index)
        {
            this.sortType = '';
            this.sortIndex = index;
        }
        item.parent().find('.f-icon').hide();
        if(this.sortType == 'asc')
        {
            item.find('.f-sorted-desc').show();
            this.sortType = 'desc';
        }
        else if(this.sortType == 'desc')
        {
            item.find('.f-sorted-asc').show();
            this.sortType = 'asc';
        }
        else 
        {
            this.sortType = 'asc';
            item.find('.f-sorted-asc').show();
        }
        return false;
    },
    
    editLineup: function()
    {
        if(this.playerIdPicks != '')
        {
            var playerIdPicks = jQuery.parseJSON(this.playerIdPicks);

            for(var i = 0; i < playerIdPicks.length; i++)
            {
                this.addPlayer(playerIdPicks[i]);
            }
        }
    },
    mixingGetOrgIDByPlayerID : function(player_id)
    {
        var aPlayers = jQuery.parseJSON(this.aPlayers);
        for(var org in aPlayers){
            for(var i in aPlayers[org]){
                if(aPlayers[org][i].id == player_id){
                    return org;
                }
            }
        }
    },
    mixingGetDetailPlayerByPlayerID : function(player_id)
    {
        var aPlayers = jQuery.parseJSON(this.aPlayers);
        for(var org in aPlayers){
            for(var i in aPlayers[org]){
                if(aPlayers[org][i].id == player_id){
                    return aPlayers[org][i];
                }
            }
        }
    },
    addPlayer: function(id)
    {
        var aPool = jQuery.parseJSON(this.aPool);

        var player = this.findPlayer(id);
        // for mixing sport game   
        if(jQuery("#type_league").val() == 'mixing'){

            if(this.playerIdPicks != ''){
                player = jQuery.playerdraft.mixingGetDetailPlayerByPlayerID(id);
                org_id = jQuery.playerdraft.mixingGetOrgIDByPlayerID(player.id);
                aPool = jQuery.playerdraft.mixingGetPoolByOrgID(org_id);
            }else{
                 var org_id = jQuery('#mixing_orginazation_id').val();
                 aPool = jQuery.playerdraft.mixingGetPoolByOrgID(org_id);
            }
        }
        
        if(typeof player != 'undefined')
        {
            var position_id = player.position_id;
            if(aPool.no_position == 1)
            {
                position_id = 0;
            }
            if(jQuery("#game_type").val() == 'GOLFSKIN'){
                var item = jQuery('.player-position' + ':not(.f-has-player)').first();
            }else{
                var item = jQuery('.player-position-' + position_id + ':not(.f-has-player)').first();
            }
            if(item.length == 1)
            {
                
                if(jQuery("#game_type").val() == 'GOLFSKIN'){
                    var round_id = jQuery(".f-fixture-picker-button-container .f-is-active").attr('data-id');      
                    if(!jQuery.isArray(this.aGolfSkinPlayers[round_id])){
                        this.aGolfSkinPlayers[round_id] = [];
                    }
                    if(this.aGolfSkinPlayers[round_id].indexOf(id) === -1){
                        this.aGolfSkinPlayers[round_id].push(id);
                        this.golfSkinCaculateMoney('plus');
                    }  
                    
                }
                
                jQuery('#buttonAdd' + id).hide();
                jQuery('#buttonAdd' + id).parents('tr').addClass('f-player-in-lineup');
                jQuery('#buttonRemove' + id).css('display', 'block');
                var match = '';
                if(aPool.only_playerdraft == 0)
                {
                    if(player.teamID1 == player.team_id)
                    {
                        match = '<b>' + player.team1 + '</b>@' + player.team2;
                    }
                    else 
                    {
                        match = player.team1 + '@<b>' + player.team2 + '</b>';
                    }
                }
                item.addClass('f-has-player');
                item.attr('id', 'f-has-player' + id);
                item.attr('data-id', id);
                item.find('.f-empty-roster-slot-instruction').hide();
                item.find('.f-player-image').empty().append('<img src="' + player.full_image_path + '" onerror="jQuery.playerdraft.setNoImage(jQuery(this))" />');
                item.find('.f-player').empty().append(player.name).css('visibility', 'visible').attr("onclick" , "jQuery.playerdraft.playerInfo(" + player.id + ")");
                item.find('.f-salary').empty().append('$' + accounting.formatNumber(player.salary)).css('visibility', 'visible');
                item.find('.f-fixture').empty().append(match);
                item.find('.f-button').css('visibility', 'visible');
                if(player.disable == 1)
                {
                    item.find('.f-button').remove();
                }
                else 
                {
                    item.find('.f-button').attr('onclick', 'jQuery.playerdraft.clearPlayer(' + id + ')');
                }
                this.calculateSalary(id, 'add');
                this.calculateAvgPerPlayer();
            }
            else 
            {
                if(!jQuery('.f-errorMessage').is(':visible'))
                {
                    var positionName = "'" + player.position + "'";
                    if(aPool.no_position == 1)
                    {
                        positionName = '';
                    }
                      if(jQuery("#game_type").val() == 'GOLFSKIN'){
                            jQuery('.f-errorMessage').empty().append(wpfs['fullpositions1'] + " " + wpfs['fullpositions2']).slideToggle().delay(4000).fadeOut();

                      }else{
                            jQuery('.f-errorMessage').empty().append(wpfs['fullpositions1'] + positionName + " " + wpfs['fullpositions2']).slideToggle().delay(4000).fadeOut();

                      }
                }
            }
        }
        
    },
	
	addMultiPlayers:function(playersID)
    {
        playersID = playersID.split(',');
        // first: clear all players
        jQuery('.f-roster .f-roster-position').each(function(){
                if(typeof jQuery(this).attr('data-id') != typeof undefined)
                {
                    jQuery.playerdraft.clearPlayer(jQuery(this).attr('data-id'));
                }
            });
        //second: add new players
        for(var i = 0; i < playersID.length; i++)
        {
            this.addPlayer(playersID[i]);
        }
    },
    
    clearPlayer: function(id)
    {
        
        jQuery('#buttonAdd' + id).css('display', 'block');
        jQuery('#buttonAdd' + id).parents('tr').removeClass('f-player-in-lineup');
        jQuery('#buttonRemove' + id).hide();
        this.resetLineup(id);
        if(jQuery("#game_type").val() == 'GOLFSKIN'){
            this.golfSkinClearPlayer(id);
        }else{
            this.calculateSalary(id, 'remove');
            this.calculateAvgPerPlayer(); 
        }

    },
    golfSkinClearPlayer: function(id)
    {
        id = parseInt(id);
         var round_id = jQuery(".f-fixture-picker-button-container .f-is-active").attr('data-id');  
                    if(jQuery.isArray(this.aGolfSkinPlayers[round_id])){
                        var index = this.aGolfSkinPlayers[round_id].indexOf(id);
                        if(index !== -1){
                            this.aGolfSkinPlayers[round_id].splice(index,1);
                            this.golfSkinCaculateMoney('minus');
                        }
                    }
    }
    ,
    clearAllPlayer: function()
    {
        if(confirm(wpfs['players_out_team']))
        {
            jQuery('.f-roster .f-roster-position').each(function(){
                if(typeof jQuery(this).attr('data-id') != typeof undefined)
                {
                    jQuery.playerdraft.clearPlayer(jQuery(this).attr('data-id'));
                }
            });
        }
    },
    
    resetLineup: function(id)
    {
        var item = jQuery('#f-has-player' + id);
        item.removeClass('f-has-player');
        item.removeAttr('id');
        item.removeAttr('data-id');
        item.find('.f-empty-roster-slot-instruction').show();
        item.find('.f-player-image').empty();
        item.find('.f-player').empty().css('visibility', 'hidden').removeAttr("onclick");
        item.find('.f-salary').empty().css('visibility', 'hidden');
        item.find('.f-button').css('visibility', 'hidden');
    },
    
    findPlayer: function(id)
    {
        var aPlayers = jQuery.parseJSON(this.aPlayers);
        // for mixing sport
        if(jQuery('#type_league').val() == 'mixing'){
            var org_id = jQuery('#mixing_orginazation_id').val();
            aPlayers = aPlayers[org_id];
        }
       
        //
        for(var i = 0; i < aPlayers.length; i++)
        {
            if(aPlayers[i].id == id)
            {
                return aPlayers[i];
            }
        }
    },
    mixFindPlayerToClear: function(id)
    {
        var aPlayers = jQuery.parseJSON(this.aPlayers);
        for(var i in aPlayers){
            for(var j in aPlayers[i]){
                if(aPlayers[i][j].id == id){
                    return aPlayers[i][j];
                }
            }
        }
    },
    
    calculateSalary: function(player_id, task)
    {
        if(this.salaryCap > 0)
        {
            if(jQuery('#type_league').val() == 'mixing'){
                 var player = this.mixFindPlayerToClear(player_id); // mixing league
            }else{
                 var player = this.findPlayer(player_id);  // single mixing
            }
           
            switch (task)
            {
                case 'add':
                    this.salaryRemaining -= parseInt(player.salary);
                    if(this.salaryRemaining < 0)
                    {
                        jQuery('#salaryRemaining').addClass('f-error');
                    }
                    break;
                case 'remove':
                    this.salaryRemaining += parseInt(player.salary);
                    if(this.salaryRemaining > 0)
                    {
                        jQuery('#salaryRemaining').removeClass('f-error');
                    }
                    break;
            }
            jQuery('#salaryRemaining').empty().append('$' + accounting.formatNumber(this.salaryRemaining));
        }
    },
    
    calculateAvgPerPlayer: function()
    {
        var total = jQuery('.f-roster-position:not(.f-has-player)').length;
        if(total > 0)
        {
            total = this.salaryRemaining / total;
        }
        else 
        {
            total = 0;
        }
        jQuery('#AvgPlayer').empty().append('$' + accounting.formatNumber(Math.round(total)));
    },
    
    submitData: function()
    {
        if(jQuery('.f-roster-position:not(.f-has-player)').length > 0)
        {
            alert(wpfs['player_each_position']);
        }
        else if(this.salaryCap > 0 && this.salaryRemaining < 0)
        {
            alert(wpfs['team_exceed_salary_cap']);
        }
        else 
        {
            jQuery('#btnSubmit').attr("disabled", "true");
            jQuery('.f-roster .f-roster-position').each(function(){
                if(typeof jQuery(this).attr('data-id') != typeof undefined)
                {
                    jQuery('#formLineup').append('<input type="hidden" value="' + jQuery(this).attr('data-id') + '" name="player_id[]">');
                }
            });
            jQuery('#formLineup').submit();
        }
    },
    golfSkinSubmitData: function(){
        var aLeague = jQuery.parseJSON(this.league);
        var rounds = aLeague.rounds;
        var isSuccess = false
        rounds = rounds.split(",");
        for(var i in rounds){
            if(jQuery.isArray(this.aGolfSkinPlayers[rounds[i]]) && this.aGolfSkinPlayers[rounds[i]].length > 1){
                isSuccess = true;
                break;
            }
        }

        if(isSuccess == false){
            alert(wpfs['golfskin_player_position']);
            return;
        }
        var obj = this;
        jQuery('#btnSubmit').attr('disabled', 'true').text(wpfs['working'] + '...');
        if(this.is_entry_fee){
            jQuery.post(ajaxurl, 'action=getUserbalance', function(result) {
            result = jQuery.parseJSON(result);
            jQuery('#btnSubmit').removeAttr('disabled').text(wpfs['enter']);
            if(parseInt(result.balance) < obj.totalMoney){
              alert(wpfs['golfskin_add_balance']);
            }else{
                jQuery("#total_money").val(obj.totalMoney);
                jQuery("#players").val(JSON.stringify(obj.aGolfSkinPlayers));
                jQuery('#formLineup').submit();
               
            }
         }); 
        }else{
                jQuery("#total_money").val(obj.totalMoney);
                jQuery("#players").val(JSON.stringify(obj.aGolfSkinPlayers));
                jQuery('#formLineup').submit();
        }

    }
    ,
    userResult: function(leagueID, is_curent, userID, username, avatar, rank, totalScore, entry_number)
    {
        var name = 'Score';
        if(jQuery("#gameType").val() == 'GOLFSKIN'){
            name = 'Skin';
        }
        //load user info
        var html = 
            '<div class="f-user-score-summary f-clearfix">\n\
                <div>\n\
                    <div class="f-rank">\n\
                        <header>\n\
                            <h6>' + wpfs['position'] + '</h6>\n\
                        </header>\n\
                        <h1>' + rank + '</h1>\n\
                    </div>\n\
                    <div class="f-user-info">\n\
                        <div style="background-image: url(\'' + avatar + '\')" class="f-avatar f-left">' + username + '</div>\n\
                        <h2 class="f-truncate">\n\
                            ' + username + '\n\
                        </h2>\n\
                    </div>\n\
                    <div class="f-score right">\n\
                        <header>\n\
                            <h6>'+name+'</h6>\n\
                        </header>\n\
                        <h1 class="f-user-score f-positive  ">' + totalScore + '</h1>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
            <div class="f-roster">\n\
                <div class="f-loading">\n\
                </div>\n\
            </div>';
        if(is_curent == 1)
        {
            jQuery('#f-seat-1').empty().append(html);
        }
        else 
        {
            jQuery('#f-seat-2').empty().append(html);
        }
        
        //load result
        var leagueOptionType = jQuery('#leagueOptionType').val();
        var data = 'leagueID=' + leagueID + '&userID=' + userID + '&entry_number=' + entry_number;
        var round_id = 0;
         if(jQuery("#gameType").val() == 'GOLFSKIN'){
             round_id = jQuery('#list_round').val();
         }
         data+='&roundID='+round_id;
        jQuery.post(ajaxurl, "action=loadUserResult&" + data, function(data) {
            data = jQuery.parseJSON(data);
            html = '';
            if(data)
            {
                for(var i = 0; i < data.length; i++)
                {
                    var aResult = data[i];
                    var resultPlayerDraft = '';
                    if(aResult.playerdraft != null)
                    {
                        for(var j in aResult.playerdraft)
                        {
                            var playerdraft = aResult.playerdraft[j];
                            //var scroring_points = jQuery.playerdraft.getScoringPointById(playerdraft.scoring_category_id);
                            //var score = scroring_points * playerdraft.points;
                            resultPlayerDraft += 
                                '<li class="f-player-card-item">' + playerdraft.points + '\n\
                                    <span title="' + playerdraft.scoring_name + '">\n\
                                        ' + playerdraft.scoring_alias + '\n\
                                         (' + playerdraft.score + ')\n\
                                    </span>\n\
                                </li>';
                        }
                    }
                    var resultFight = fights = '';
                    if(aResult.fights != null && typeof aResult.fights[0] != 'undefined')
                    {
                        fights = aResult.fights;
                        var classAway = classHome = '';
                        if(aResult.teamID == fights[0]['teamID'])
                        {
                            classAway = 'f-player-team-highlight';
                        }
                        else if(aResult.teamID == fights[1]['teamID'])
                        {
                            classHome = 'f-player-team-highlight';
                        }
                        resultFight += '<div class="f-fixture-info">\n\
                                            <div> \n\
                                                <span class="f-away ' + classAway + '">\n\
                                                    ' + fights[0].nickName + '\n\
                                                </span> ' + fights[0].team1score + ' @\n\
                                                <span class="f-home ' + classHome + '">\n\
                                                    ' + fights[1].nickName + '\n\
                                                </span> ' + fights[1].team2score + '\n\
                                            </div>\n\
                                        </div>';
                    }
                    
                    var htmlPosition = '';
                    if((typeof aResult.player_position != 'undefined') && (leagueOptionType != 'salary') && jQuery("#gameType").val() != 'GOLFSKIN')
                    {
                        htmlPosition = 
                            '<div class="f-pos">\n\
                                <span title="Point Guard">' + aResult.player_position + '</span>\n\
                            </div>';
                    }
                    var styleLeagueOptionType = '';
                    if(leagueOptionType == 'salary')
                    {
                        styleLeagueOptionType = 'style="padding-left:10px;"';
                    }
                    html += 
                        '<div class="f-roster-row f-finished" ' + styleLeagueOptionType + '>\n\
                            <div class="f-roster-row-summary">\n\
                                ' + htmlPosition + '\n\
                                <div class="f-name">' + aResult.player_name + '</div>\n\
                                ' + resultFight + '\n\
                                <div class="f-player-secondary-information">\n\
                                    <div class="f-player-salary">$' + accounting.formatNumber(aResult.player_salary) + '\n\
                                    </div>\n\
                                </div>\n\
                                <div class="f-player-score-breakdown">\n\
                                    <ul class="f-player-card">\n\
                                        ' + resultPlayerDraft + '\n\
                                    </ul>\n\
                                </div>\n\
                                <div class="f-score">\n\
                                    <div>\n\
                                        <div class="f-fixture-status f-positive f-finished">\n\
                                            ' + aResult.points + '\n\
                                        </div>\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                        </div>';
                }
            }
            if(is_curent == 1)
            {
                jQuery('#f-seat-1 .f-loading').removeClass('f-loading').empty().append(html);
            }
            else 
            {
                jQuery('#f-seat-2 .f-loading').removeClass('f-loading').empty().append(html);
            }
        })
        return false;
    },
    
    getScoringPointById: function(id)
    {
        var scoringCats = jQuery("#scoringCats").val();
        if(scoringCats != '')
        {
            scoringCats = jQuery.parseJSON(scoringCats);
            for(var i in scoringCats)
            {
                if(scoringCats[i].id == id)
                {
                    return scoringCats[i].points;
                }
            }
        }
        return 0;
    },
    
    searchPlayers: function()
    {
        
        jQuery.playerdraft.loadPlayers();
    },
     searchMixingPlayers: function()
    {
        
        jQuery.playerdraft.mixingLoadPlayers();
    },
    
    isPlayerInline: function(player_id)
    {
        var existed = false;
        jQuery('.f-roster .f-roster-position').each(function(){
            if(jQuery(this).attr('data-id') == player_id)
            {
                existed = true;
            }
        });
        if(existed)
        {
            return true;
        }
        return false;
    },
    
    sendInviteFriendEmail: function()
    {
        var warning = jQuery('.f-manual-email-form-button .f-warning');
        var dataSring = jQuery('#formInviteFriend').serialize();
        jQuery.post(ajaxurl, 'action=sendInviteFriend&' + dataSring, function(result) {
            var data = JSON.parse(result);
            if(data.notice)
            {
                warning.empty().append(data.notice).css('display','inline-block').delay(4000).fadeOut();
            }
            else
            {
                warning.empty().append(data.message).css('display','inline-block').delay(4000).fadeOut();
            }
        })
        return false;
    },
    
    loadContestScores: function(leagueID, entry_number)
    {
        var html = '';
        var aScore = '';
        var htmlCurrent = '';
        var htmlNoCurrent = '';
        var currentScores = '';
        var data = 'leagueID=' + leagueID + '&entry_number=' + entry_number;
        jQuery.post(ajaxurl, "action=loadContestScores&" + data, function(result) {
            var aScores = jQuery.parseJSON(result);
            if(aScores != null)
            {
                for(var i = 0; i < aScores.length; i++)
                {
                    aScore = aScores[i];
                    htmlCurrent = htmlNoCurrent = '';
                    if(aScore.current)
                    {
                        htmlCurrent = 'class="f-user-highlight"';
                        currentScores = aScore;
                    }
                    //else
                    //{
                        htmlNoCurrent = 'href="#" onclick="return jQuery.playerdraft.userResult(' + leagueID + ', 0, ' + aScore.userID + ', \'' + aScore.username + '\', \'' + aScore.avatar + '\', ' + aScore.rank + ', \'' + aScore.points + '\', ' + aScore.entry_number + ')"';
                    //}
                    var htmlMultiEntry = '';
                    if(jQuery('#multiEntry').val() == 1)
                    {
                        htmlMultiEntry = '<td style="text-align:center">\n\
                            ' + aScore.entry_number + '\n\
                        </td>';
                    }
					
					var htmlCash = '';
                    if(no_cash == 0)
                    {
                        htmlCash += 
                            '<td class="f-num">\n\
                                ' + aScore.amount + '\n\
                            </td>';
                    }
                    html +=
                        '<tr ' + htmlCurrent + ' ' + htmlNoCurrent + ' >\n\
                            <td style="text-align:center">\n\
                                ' + aScore.rank + '\n\
                            </td>\n\
                            <td>\n\
                                <div style="background-image: url(\'' + aScore.avatar + '\')" class="f-avatar">\n\
                                </div>\n\
                                <a class="f-truncate">\n\
                                    ' + aScore.username + '\n\
                                </a>\n\
                            </td>\n\
                            ' + htmlMultiEntry + '\n\
                            <td class="f-num">\n\
                                ' + aScore.points + '\n\
                            </td>\n\
                            ' + htmlCash + '\n\
                        </tr>';
                }
            }
            jQuery('#tableScores tbody').empty().append(html);
            if(currentScores != '')
            {
                jQuery.playerdraft.userResult(leagueID, 0, currentScores.userID, currentScores.username, currentScores.avatar, currentScores.rank, currentScores.points, currentScores.entry_number);
            }
        });
    },
    loadUserResultByRound: function(){
        jQuery("#tableScores .f-user-highlight").trigger('click');
    },
    loadFixtureScores: function(leagueID)
    {
        var data = 'leagueID=' + leagueID;
        jQuery.post(ajaxurl, "action=loadFixtureScores&" + data, function(result) {
            jQuery("#f-live-scoring-fixture-info").after(result).remove();
        });
    },
    
    showIndicatorLegend: function()
    {
        var item = jQuery('.f-draft-legend-key-content');
        if(!item.is(':visible'))
        {
            item.slideDown();
        }
        else 
        {
            item.slideUp();
        }
    },
    
    ////////////////////////tab////////////////////////
    playerInfo: function(player_id)
    {
        var pool = jQuery.parseJSON(this.aPool);
        var player = this.findPlayer(player_id);
        
        //add player or remove player button
        var button = '';
        if(!this.isPlayerInline(player_id))
        {
            button =    '<div class="f-add-button">\n\
                            <input type="button" value="Add Player" class="f-button f-primary f-mini f-plbARB" onclick="jQuery.playerdraft.addPlayer(' + player_id + '); jQuery.playerdraft.closeDialog(\'#dlgInfo\');">\n\
                        </div>';
        }
        else 
        {
            button =    '<div class="f-add-button">\n\
                            <input type="button" value="' + wpfs['remove_player'] + '" class="f-button f-primary f-mini f-plbARB" onclick="jQuery.playerdraft.clearPlayer(' + player_id + '); jQuery.playerdraft.closeDialog(\'#dlgInfo\');">\n\
                        </div>';
        }
        
        //html
        var positionName = player.position;
        if(pool.no_position == 1)
        {
            positionName = '';
        }
        var html = '<div>\n\
                        <div class="f-player-stats-lightbox">\n\
                            <div class="f-player-chunk">\n\
                                <div class="f-player-image" style="background-image: none;">\n\
                                    <img alt="' + player.name + '" src="' + player.full_image_path_org + '" onerror="jQuery.playerdraft.setNoImage(jQuery(this))">\n\
                                </div>\n\
                                <div class="f-player-container">\n\
                                    <div class="f-player-info">\n\
                                        <span class="f-player-pos">' + positionName + '</span>\n\
                                        <h1 class="f-player-name">' + player.name + '</h1>';
        if(pool.only_playerdraft == 0)
        {
            html += 
                                        '<span class="f-player-team">' + player.myteam + '</span>';
        }
        html +=
                                    '</div>\n\
                                    <div class="f-player-stats f-brief">\n\
                                                   <div class="f-stat">\n\
                                            <b>' + player.played + '</b> ' + wpfs['played'] + ' </div>\n\
                                        <div class="f-stat">\n\
                                            <b>$' + accounting.formatNumber( player.salary) + '</b> ' + wpfs['salary'] + ' </div>\n\
                                    </div>\n\
                                </div>\n\
                                ' + button + '\n\
                                <ul class="f-tabs">\n\
                                    <li>\n\
                                        <a data-tabname="tab1" href="#tab1">' + wpfs['summary'] + '</a>\n\
                                    </li>\n\
                                    <li>\n\
                                        <a data-tabname="tab2" href="#tab2">' + wpfs['game_log'] + '</a>\n\
                                    </li>\n\
                                    <li>\n\
                                        <a data-tabname="tab3" href="#tab3">' + wpfs['player_news'] + '</a>\n\
                                    </li>\n\
                                </ul>\n\
                            </div>\n\
                            <div class="f-player-stats-lb-tab tab1" id="tab1">\n\
                                <div class="f-player-stats f-season">\n\
                                    <h1>' + wpfs['season_statistics'] + '</h1>\n\
                                    <div class="f-well f-clearfix" id="playerStatistic"></div>\n\
                                </div>\n\
                                <div class="f-player-news f-latest">\n\
                                    <div class="f-row">\n\
                                        <h1 class="f-left">' + wpfs['latest_player_news'] + '</h1>\n\
                                    </div>\n\
                                    <div data-role="scrollable-body" class="f-clear f-news-item" id="playerBrief"></div>\n\
                                </div>';
        if(pool.only_playerdraft == 0)
        {
            html +=             
                                '<div class="f-next-game">\n\
                                    <h1>' + wpfs['next_game'] + '</h1>\n\
                                    <div class="f-game">' + player.teamName1 + ' vs ' + player.teamName2 + '</div>\n\
                                </div>';
        }
        html += 
                            '</div>\n\
                            <div class="f-player-stats-lb-tab f-tab2" id="tab2">\n\
                                <div class="f-game-log">\n\
                                    <h1>' + wpfs['game_log'] + '</h1>\n\
                                    <div class="f-table-container" id="gameLog"></div>\n\
                                </div>\n\
                            </div>\n\
                            <div id="tab3" class="f-player-stats-lb-tab f-tab3">\n\
                                <div class="f-player-news">\n\
                                    <div class="f-row">\n\
                                        <h1 class="f-left">' + wpfs['player_news'] + '</h1>\n\
                                    </div>\n\
                                    <div class="f-clear f-news-item" data-role="scrollable-body" id="playerNews"></div>\n\
                                </div>\n\
                            </div>\n\
                        </div>\n\
                    </div>';
        this.showDialog('#dlgInfo', html)
        jQuery(".f-player-stats-lightbox").tabs({active : 0});
        
        //statistic
        var htmlStatistic = htmlOpponentStatistic = totalPlayed = '';
        var orgID = jQuery.parseJSON(this.aPool).organization;
        jQuery.post(ajaxurl, "action=loadPlayerStatistics&orgID=" + orgID + '&playerID=' + player_id + '&poolID=' + pool.poolID, function(result) {
            result = jQuery.parseJSON(result);
            var player_news = result.news;
            var aStatistics = result.scoring_category;
            var aOpponentStatistics = result.opponent_scoring_category;
            totalPlayed = result.played;
            var aStatistic = '';
            htmlStatistic += '<div class="f-stat">\n\
                              <b>' + result.played + '</b>Games</div>';
            if(aStatistics != null)
            {
                for(var i =0; i < aStatistics.length; i++)
                {
                    aStatistic = aStatistics[i];
                    htmlStatistic += '<div class="f-stat">\n\
                            <b>' + aStatistic.points + '</b> ' + aStatistic.name + ' </div>';
                }
            }
            jQuery('#playerStatistic').empty().append(htmlStatistic);
            
            //opponent statistic
            if(aOpponentStatistics != '')
            {
                htmlOpponentStatistic += 
                    '<h1>' + wpfs['opposing_pitcher'] + ' - ' + result.opponent_name +'</h1>\n\
                    <div id="playerStatistic" class="f-well f-clearfix">\n\
                        <div class="f-stat">\n\
                        <b>' + result.opponent_played + '</b>Games</div>';
                for(var i in aOpponentStatistics)
                {
                    aOpponentStatistic = aOpponentStatistics[i];
                    htmlOpponentStatistic += '<div class="f-stat">\n\
                            <b>' + aOpponentStatistic.points + '</b> ' + aOpponentStatistic.name + ' </div>';
                }
            }
            htmlOpponentStatistic += '</div>';
            jQuery('#playerStatistic').after(htmlOpponentStatistic);
            
            //full statistic
            var aStats = result.stats;
            var htmlPlayerStatistic = wpfs['player_no_match'];
            if(aStats.scoring != null)
            {
                htmlPlayerStatistic =   '<table class="f-game-log f-condensed f-text-align-right">\n\
                                                <thead>\n\
                                                <tr>';
                for(var i in aStats.cats)
                {
                    htmlPlayerStatistic += '<th>' + aStats.cats[i] + '</th>';
                }
                htmlPlayerStatistic += '</tr></thead><tbody class="f-text-align-right">';
                for(var i in aStats.scoring)
                {
                    htmlPlayerStatistic += '<tr>';
                    for(var j in aStats.scoring[i])
                    {
                        htmlPlayerStatistic += '<td>' + aStats.scoring[i][j] + '</td>';
                    }
                    htmlPlayerStatistic += '</tr>';
                }
                htmlPlayerStatistic += '</tbody></table>';
            }
            jQuery('#gameLog').empty().append(htmlPlayerStatistic);
            
            //player news
            var htmlNewsBrief = '';
            var htmlNews = '';
            if(player_news != null)
            {
                var style = 'style="padding-bottom:5px;margin-bottom:5px;border-bottom:solid 1px #8b8b8b"';
                for(var i in player_news)
                {
                    if(player_news.length == (parseInt(i)+1))
                    {
                        style = '';
                    }
                    htmlNews += '<div ' + style + '>' + player_news[i].updated + '<br/>' + player_news[i].title + '<br/>' + player_news[i].content + '</div>';
                    if(i == 0)
                    {
                        htmlNewsBrief = player_news[i].title + '<br/>' + player_news[i].content;
                    }
                }
            }
            if(htmlNewsBrief == '')
            {
                htmlNewsBrief = wpfs['no_news']; 
            }
            if(htmlNews == '')
            {
                htmlNews = wpfs['no_news']; 
            }
            jQuery('#playerBrief').empty().append(htmlNewsBrief);
            jQuery('#playerNews').empty().append(htmlNews);
        })
    },
    
    ruleScoring: function(gameType, leagueID, name, entry_fee, salary_remaining, tab, contest_url)
    {
        var htmlSalaryCap = '';
        if(gameType == 'PLAYERDRAFT')
        {
            htmlSalaryCap = '<li class="f-game-info-salary-cap">' + wpfs['salary_cap'] + ': $' + accounting.formatNumber(salary_remaining) + '</li>';
        }
		var htmlCash = '';
        var htmlEntryFee = '';
        if(no_cash == 0)
        {
            htmlCash += 
                '<li onclick="jQuery.playerdraft.loadTabLeaguePrizes(jQuery(this), ' + leagueID + ')">\n\
                    <a data-tabname="tab3" href="#tab3">' + wpfs['Prizes'] + '</a>\n\
                </li>';
            htmlEntryFee = '<li class="f-game-info-entry-fee">' + wpfs['Entry Fee'] + ': $' + entry_fee + '</li>';
        }
        var html = '<div>\n\
                        <header>\n\
                            <h1 class="f-game-title">' + name + '</h1>\n\
                            <ul class="f-game-info">\n\
                                ' + htmlEntryFee + '\n\
                                ' + htmlSalaryCap + '\n\
                            </ul>\n\
                            <div id="tabRuleScoring">\n\
                                <ul class="f-tabs">\n\
                                    <li onclick="jQuery.playerdraft.loadTabScoringCategory(jQuery(this), ' + leagueID + ', \'' + contest_url + '\')">\n\
                                        <a data-tabname="tab-info" href="#tab1">' + wpfs['contest'] + '</a>\n\
                                    </li>\n\
                                    <li onclick="jQuery.playerdraft.loadTabLeagueEntries(jQuery(this), ' + leagueID + ')">\n\
                                        <a data-tabname="tab2" href="#tab2">' + wpfs['a_entries'] + '</a>\n\
                                    </li>\n\
                                    ' + htmlCash + '\n\
                                </ul>\n\
                            </div>\n\
                        </header>\n\
                        <div id="f-contest-lightbox-content">\n\
                            <div class="f-quickfire-tab" id="tab-info">\n\
                                <div class="f-tab-game-info"></div>\n\
                            </div>\n\
                        </div>\n\
                        <div class="f-quickfire-footer f-no-content"></div>\n\
                    </div>';
        jQuery('#dlgInfo').addClass('f-quickfire-lightbox');
        this.showDialog('#dlgInfo', html)
        
        switch(tab)
        {
            case 2:
                jQuery('#tabRuleScoring li:first').next().trigger('click');
                break;
            case 3:
                jQuery('#tabRuleScoring li:last').trigger('click');
                break;
            default :
                jQuery('#tabRuleScoring li:first').trigger('click');
        }
        return false;
    },
    
    dlgEntries: function(leagueID, name)
    {
        var html = '<div>\n\
                        <div class="f-lightbox-entries f-entries">\n\
                            <header>\n\
                                <h4>' + name + '</h4>\n\
                            </header>\n\
                            <div id="f-contest-lightbox-content">\n\
                                <div class="f-quickfire-tab" id="tab-info">\n\
                                    <div class="f-tab-game-info"></div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="f-quickfire-footer f-no-content"></div>\n\
                        </div>\n\
                    </div>';
        this.showDialog('#dlgInfo', html)
        jQuery.playerdraft.loadTabLeagueEntries(jQuery(this), leagueID);
    },
    
    dlgPrize: function(leagueID, name)
    {
        var html = '<div>\n\
                        <div class="f-lightbox-prizes f-entries">\n\
                            <header>\n\
                                <h4>' + name + '</h4>\n\
                            </header>\n\
                            <div id="f-contest-lightbox-content">\n\
                                <div class="f-quickfire-tab" id="tab-info">\n\
                                    <div class="f-tab-game-info"></div>\n\
                                </div>\n\
                            </div>\n\
                            <div class="f-quickfire-footer f-no-content"></div>\n\
                        </div>\n\
                    </div>';
        this.showDialog('#dlgInfo', html)
        jQuery.playerdraft.loadTabLeaguePrizes(jQuery(this), leagueID);
    },
    
    loadTabScoringCategory: function(item, leagueID, contest_url)
    {
        if(!item.find('a').hasClass('f-is-active'))
        {
            jQuery('#tabRuleScoring li a').removeClass('f-is-active');
            item.find('a').addClass('f-is-active');

            var data = 'leagueID=' + leagueID;
            jQuery('.f-lightbox .f-tab-game-info').empty().append(this.loading());
            jQuery.post(ajaxurl, "action=loadPoolInfo&" + data, function(result) {
                result = jQuery.parseJSON(result);
                var aLeague = result.league;
                var aPlayerDraftScorings = result.scorings.playerdraft;
                var aNormalScorings = result.scorings.normal;
                var aFights = result.fights;
                var aRounds = result.rounds;

                //result fight
                var resultFight = '';
                if(aFights != null)
                {
                    for(var i = 0; i < aFights.length; i++)
                    {
                        resultFight += '<li><b>' + aFights[i].nickName1 + ' @ ' + aFights[i].nickName2 + '</b> ' + aFights[i].startDate + '</li>';
                    }
                }
                
                //result round
                var htmlRound = '';
                if(aRounds != null)
                {
                    for(var i = 0; i < aRounds.length; i++)
                    {
                        htmlRound += '<li><b>' + aRounds[i].name + '</b> ' + aRounds[i].startDate + '</li>';
                    }
                }
                

                //result scoring
                var resultScoring = scorings = bonusHtml = '';
                if(aLeague.bonus != null)
                {
                    bonusHtml = 
                        '<h5 class="f-game-info-scoring-title">Bonus</h5>\n\
                        <div class="f-game-info-scoring-categories">\n\
                            ' + aLeague.bonus + '\n\
                        </div>';
                }
                resultScoring = 
                        '<hr class="f-divider">\n\
                        <div class="f-row">\n\
                            <div class="f-column-12 game-info-scoring">\n\
                                <h5 class="f-game-info-scoring-title">' + wpfs['scoring_categories'] + '</h5>\n\
                                <div class="f-game-info-scoring-categories">';
                if(aNormalScorings != null)
                {
                    for(var i = 0; i < aNormalScorings.length; i++)
                    {
                        aScoring = aNormalScorings[i];
                        resultScoring += '<div style="margin-left:20px;">' + aScoring + '</div>';
                    }
                }
                if(aPlayerDraftScorings != null)
                {
                    if(aPlayerDraftScorings.length > 1)
                    {
                        resultScoring += '<div style="margin-left:20px;">';
                        for(var i = 0; i < aPlayerDraftScorings.length; i++)
                        {
                            var aScoring = aPlayerDraftScorings[i];
							var alias = '';
                            if(aScoring.alias)
                            {
                                alias = ' (' + aScoring.alias + ')';
                            }
                            resultScoring += aScoring.name + alias + ' = ' + aScoring.points + '<br/> '; 
                        }
                        resultScoring += '</div>';
                    }
                    resultScoring +=
                                    '</div>\n\
                                    ' + bonusHtml + '\n\
                                </div>\n\
                            </div>';
                }

                var htmlPickPlayer = '';
                if(aLeague.is_playerdraft && aLeague.only_playerdraft == 0 && (contest_url != 'undefined'))
                {
                    htmlPickPlayer = '<p>' + wpfs['pick_a_team'] + ' <a href="' + contest_url + '">' + wpfs['here'] + '</a></p>';
                }
                else if(aLeague.is_playerdraft && aLeague.only_playerdraft == 1 && (contest_url != 'undefined'))
                {
                    htmlPickPlayer = '<p>' + wpfs['pick_player_from_list'] + ' <a href="' + contest_url + '">' + wpfs['here'] + '</a></p>';
                }
                var html = '<div class="f-row">\n\
                                <div class="f-game-info-fixtures">\n\
                                    ' + htmlPickPlayer + '\n\
                                    <ul class="f-game-info-fixture-list">\n\
                                        ' + resultFight + '\n\
                                        ' + htmlRound + '\n\
                                    </ul>\n\
                                </div>\n\
                                <div class="f-game-info-start-time">\n\
                                    <div class="f-stat">\n\
                                        <b> ' + result.startDate + '</b> ' + wpfs['Start'] + '\n\
                                    </div>\n\
                                </div>\n\
                            </div>\n\
                            ' + resultScoring;
                jQuery('.f-lightbox .f-tab-game-info').empty().append(html);
            })
        }
    },
    
    loadTabLeagueEntries: function(item, leagueID)
    {
        if(!item.find('a').hasClass('f-is-active'))
        {
            jQuery('#tabRuleScoring li a').removeClass('f-is-active');
            item.find('a').addClass('f-is-active');

            var data = 'leagueID=' + leagueID;
            jQuery('.f-lightbox .f-tab-game-info').empty().append(this.loading());
            jQuery.post(ajaxurl, "action=loadLeagueEntries&" + data, function(result) {
                var aUsers = jQuery.parseJSON(result);
                var html = '<center>' + wpfs['no_contest_entry'] + '</center>';
                if(aUsers != null)
                {
                    html = '<ul class="f-contest-entrants-list">';
                    var user = '';
                    for(var i = 0; i < aUsers.length; i++)
                    {
                        user = aUsers[i];
                        html += 
                            '<li class="f-contest-entrant">\n\
                                <b class="f-number">' + (i + 1) + '.</b>\n\
                                ' + user.username + '\n\
                            </li>';
                    }
                    html += '</ul>';
                }
                jQuery('.f-tab-game-info').empty().append(html);
            })
        }
    },
    
    loadTabLeaguePrizes: function(item, leagueID)
    {
        if(!item.find('a').hasClass('f-is-active'))
        {
            jQuery('#tabRuleScoring li a').removeClass('f-is-active');
            item.find('a').addClass('f-is-active');

            var data = 'leagueID=' + leagueID;
            jQuery('.f-lightbox .f-tab-game-info').empty().append(this.loading());
            jQuery.post(ajaxurl, "action=loadLeaguePrizes&" + data, function(result) {
                var json = jQuery.parseJSON(result);
                var aPrizes = json.prize;
                var note = json.note;
                var html = '<ul class="f-contest-prizes-list">';
                var aPrize = '';
                for(var i = 0; i < aPrizes.length; i++)
                {
                    aPrize = aPrizes[i];
                    html += 
                        '<li>\n\
                            <span class="f-number">' + aPrize.place + ': </span>\n\
                            $' + aPrize.prize + '\n\
                        </li>';
                }
                html += '</ul><div class="clear"></div>';
                if(note != null)
                {
                    html += '<div>' + note + '</div>';
                }
                jQuery('.f-lightbox .f-tab-game-info').empty().append(html);
            })
        }
    },
	
	sendUserPickEmail : function(leagueID)
    {
        var data = {
            action: 'sendUserPickEmail',
            leagueID : leagueID
        };
        jQuery.post(ajaxurl, data, function(result) {})
    },
    
    sendUserJoincontestEmail: function(league_id,entry_number){
        var data = {
            action: 'sendUserJoincontestEmail',
            league_id: league_id,
            entry_number: entry_number
        };
        jQuery.post(ajaxurl, data, function(result) {});
    },
    
    loading: function()
    {
        return '<div class="f-loading-indicator">\n\
                    <div class="f-loading-circle f-loading-circle-1"></div>\n\
                    <div class="f-loading-circle f-loading-circle-2"></div>\n\
                    <div class="f-loading-circle f-loading-circle-3"></div>\n\
                </div>';
    },
    
    showDialog: function(dlg, data)
    {
        dlg = jQuery(dlg);
        if(typeof data !== 'undefined' && data != '')
        {
            dlg.find('.f-body').empty().append(data).show();
        }
        dlg.find('.f-body').show();
        dlg.fadeIn();
    },
    
    closeDialog: function(dlg)
    {
        dlg = jQuery(dlg);
        dlg.find('.f-body').hide();
        dlg.removeClass("f-quickfire-lightbox");
        dlg.fadeOut();
        return false;
    },
    
    copyLink: function(url)
    {
        Copied = jQuery('.f-refer-link input').createTextRange();
        Copied.execCommand("RemoveFormat");
        Copied.execCommand(url);
    },
    selectGolfSkinRounds: function(e){
        this.golfSkinResetPlayers();
        var round_id = jQuery(e).attr('data-id');
        for(var i in this.aGolfSkinPlayers){
            if(round_id == i){
                for(var j in this.aGolfSkinPlayers[round_id]){
                    this.addPlayer(this.aGolfSkinPlayers[round_id][j]);
                }
            }
        }
    },
    golfSkinResetPlayers: function(){
        
        var obj = this;
            jQuery('.f-roster .f-roster-position').each(function(){
                if(typeof jQuery(this).attr('data-id') != typeof undefined)
                {
                    
                    var id = jQuery(this).attr('data-id');
                    jQuery('#buttonAdd' + id).css('display', 'block');
                    jQuery('#buttonAdd' + id).parents('tr').removeClass('f-player-in-lineup');
                    jQuery('#buttonRemove' + id).hide();
                    obj.resetLineup(id);
                    
                }
            });
        //========
    },
    golfSkinCaculateMoney: function(type){
        if(this.is_entry_fee == 0){
            return;
        }
       switch(type){
            case 'plus':
                this.totalMoney+=this.entry_fee;
               break;
            case 'minus':
                this.totalMoney-=this.entry_fee;
                break;
       }
       jQuery(".f-salary-remaining .f-salary-remaining-container span").html(this.totalMoney + ' $');
    }
};

jQuery(document).on('click', '.f-refer-prompt-tab-buttons a', function(){
    jQuery('.f-refer-prompt-tab-buttons a').removeClass('f-is-active');
    jQuery(this).blur().addClass('f-is-active');
    var tabName = jQuery(this).attr('data-tab-name');
    
    jQuery('.f-refer-prompt-tabs div').removeClass('f-is-active');
    jQuery('.f-refer-prompt-tabs div').each(function(){
        if(jQuery(this).attr('data-tab-name') == tabName)
        {
            jQuery(this).addClass('f-is-active').show();
        }
    });
});

jQuery('#formInviteFriend').submit(function(e){
    e.preventDefault();
    var dataSring = jQuery('#formInviteFriend').serialize();
    jQuery.post(ajaxurl, '=sendInviteFriend&' + dataSring, function(result) {
        var data = JSON.parse(result);
        if(data.notice)
        {
            alert(data.notice);
        }
        else
        {
            alert(data.message);
        }
    });
    return false;
});

function checkAll()
{
    jQuery("input[name='val[friend_ids][]']").attr('checked', true);
}

function checkNone()
{
    jQuery("input[name='val[friend_ids][]']").removeAttr('checked');
}