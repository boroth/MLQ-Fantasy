<?php$message_subject = "Enter league '" . $league['name'] . "'";$message_body = 'Thank you for entering league \'' . $league['name'] . '\'. Please verify the list that you picked below:<br/>';if($picks != null){    foreach($picks as $k => $pick)    {        if($league['gameType'] == 'PICKSQUARES'){            $message_body.= ($k + 1).'. '.$pick."<br/>";        }else{            $message_body .= ($k + 1).'. '.$pick['name']."<br/>";        }    }}?>