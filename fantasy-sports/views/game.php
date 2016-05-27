<?php
    if($isMixing){
        include FANVICTOR__PLUGIN_DIR_VIEW.'type_game/mixing_game.php';
    }else{
        if($league['gameType'] == 'GOLFSKIN'){
            include FANVICTOR__PLUGIN_DIR_VIEW.'type_game/golf_skin.php';
        }else{
            include FANVICTOR__PLUGIN_DIR_VIEW.'type_game/single_game.php';
        }
    }

