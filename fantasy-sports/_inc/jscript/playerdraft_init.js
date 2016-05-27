jQuery(window).load(function(){
    
    if(jQuery("#type_league").val() == 'mixing'){
        jQuery.playerdraft.mixingLoadPlayers();
    }else{
        jQuery.playerdraft.loadPlayers();
        if(jQuery("#game_type").val() == 'GOLFSKIN'){
            jQuery(".f-fixture-picker-button-container .f-is-active").trigger('click');
        }
    }
    jQuery.playerdraft.calculateAvgPerPlayer();
    jQuery.playerdraft.editLineup();
});

jQuery(document).ready(function(){
    jQuery(".table-sorting").click(function() {
         jQuery.playerdraft.doSort(jQuery(this));
    });
});

jQuery(document).on('keyup', '#player-search', function(){
    jQuery.playerdraft.searchPlayers();
});
jQuery(document).on('keyup', '#mixing-player-search', function(){
    jQuery.playerdraft.searchMixingPlayers();
});