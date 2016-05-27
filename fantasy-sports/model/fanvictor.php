<?php
require_once("admin/RestClient.php");
class Fanvictor extends Model
{
    public function __construct() 
    {
        $this->pools = new Pools();
        $this->payment = new Payment();
        $this->scoringcategory = new ScoringCategory();
        if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']))
        {
            $this->postUserInfo();
        }
    }
    public function get_avatar($user_id,$size=96){
        if(!get_option('show_avatars')){
            return false;
        }
        global $wpdb;
        $table_user = $wpdb->prefix.'users';
        $sCond = " WHERE ID = ".$user_id;
        $sql = "Select user_email from $table_user $sCond";
        $data = $wpdb->get_row($sql, ARRAY_A);
        $email = $data['user_email'];
        $email_hash = '';
        $is_found_avatar = false;
        // email hash
        if ( strpos( $email, '@md5.gravatar.com' ) ) {
			// md5 hash
			list( $email_hash ) = explode( '@', $id_or_email );
	} 
        
        if ($email_hash) {
            $is_found_avatar = true;
            $gravatar_server = hexdec($email_hash[0]) % 3;
        } else {
            $gravatar_server = rand(0, 2);
        }
        if (!$email_hash) {
            if ($email) {
                $email_hash = md5(strtolower(trim($email)));
            }
        }
        if(is_ssl()){
            $url = 'https://secure.gravatar.com/avatar/' . $email_hash;
        }else{
            $url = sprintf( 'http://%d.gravatar.com/avatar/%s', $gravatar_server, $email_hash );
        }
        $a_default = get_option('avatar_default');
        switch ( $a_default ) {
        case 'mm' :
        case 'mystery' :
        case 'mysteryman' :
                $default = 'mm';
                break;
        case 'gravatar_default' :
                $default = false;
                break;
        }
        $a_rating = strtolower(get_option('avatar_rating'));
        $url.="?s=$size&#038;d=$a_default&#038;r=$a_rating";
        $avatar = sprintf(
		"<img alt='%s' src='%s' class='%s' height='%d' width='%d'/>",
		esc_attr( ''),
		esc_url( $url ),
		esc_attr( "" ),
		esc_attr(( 'avatar avatar-'.$size.' photo') ),
		(int)$size,
		(int)$size
	);
        return $avatar;
        
    }
    public function get_user_by( $field, $value ) {
        $userdata = WP_User::get_data_by( $field, $value );
        if ( !$userdata ){
            return false;
        }
	$user = new WP_User;
	$user->init( $userdata );
	return $user;
    }
    
    private function postUserInfo()
    {
        global $wpdb;
        $table_name = $wpdb->prefix."users";
        $sCond = "WHERE ID = ".(int)$_COOKIE['fanvictor_user_id'];
        $sql = "SELECT ID as user_id, user_login as user_name, user_nicename as full_name, user_email as email "
             . "FROM $table_name "
             . $sCond;
        $aUser = $wpdb->get_row($sql, ARRAY_A);
        $aUser = json_decode(json_encode($aUser), true);
        $aUser['ip'] = $_SERVER['REMOTE_ADDR'];
        $this->sendRequest("userInfo", $aUser, false);
    }

    function canPlay()
    {
        return $this->sendRequest("canPlay", null, false);;
    }
    
	public function getGamesummary($page = 1, $sort_by = '', $sort_type = '')
	{
        $aDatas = $this->sendRequest("gameSummary", array(
            'page' => $page,
            'sort_by' => $sort_by,
            'sort_type' => $sort_type
        ), false);
        if($aDatas["users"] != null)
        {
            foreach($aDatas["users"] as $k => $user)
            {
                $info = $this->get_user_by("id", $user["user_id"]);
                $aDatas["users"][$k]["user_login"] = $info->data->user_login;
            }
        }
        return $aDatas;
	}	

	public function getLeagueHeader($leagueID)
    {
        return $this->sendRequest("leagueHeader", array('leagueID' => $leagueID), false);
    }

    public function getFutureEvents()
	{
        return $this->sendRequest("futureEvents", null, false);
	}
    
    public function getNormalGameResult($leagueID, $entry_number, $page, $sort_by, $sort_value)
    {
        $aDatas = $this->sendRequest("getNormalGameResult", array(
            'leagueID' => $leagueID, 
            'entry_number' => $entry_number,
            'page' => $page,
            'sort_by' => $sort_by,
            'sort_value' => $sort_value), false);
        if($aDatas["users"] != null)
        {
            foreach($aDatas["users"] as $k => $user)
            {
                $info = $this->get_user_by("id", $user["userID"]);
                $aDatas["users"][$k]["user_login"] = $info->data->user_login;
            }
        }
        return $aDatas;
    }
    
    public function getLeagueDetail($leagueID)
    {
        return $this->sendRequest("leagueDetail", array('leagueID' => $leagueID), false);
    }	

    public function getLeagueName($listLeagueID)
    {

        return $this->sendRequest('getLeagueName',array('leagueid'=>$listLeagueID),false);
    }
	public function postUserPicks($post="")
    {
        return $this->sendRequest("userpicks", $post, false);
    }
    
    public function getUserPicks($leagueID)
    {
        return $this->sendRequest("getuserpicks", array('leagueID' => $leagueID), false);
    }
    
	public function getFights($leagueID)
    {
        return $this->sendRequest("fights", array('leagueID' => $leagueID, 'mode' => 'html'), false, false);
	}

    public function inviteFriend($data)
    {
        if (!empty($data['message_boxinvite']))
			$message_boxinvite = mysql_real_escape_string($data['message_boxinvite']);	

        global $wpdb;
		$contacts=array();
		$trueContacts=array();
		$contacts = explode(",", $data["emails"]);
		$importleagueID = $data["importleagueID"];
        $inFriends = null;
        
		if (!empty($data['friend_ids']) )
		{
            $friendIds = trim($data['friend_ids']);
            $table_name = $wpdb->prefix."users";
            $sCond = "WHERE ID IN ($friendIds)";
            $sql = "SELECT user_email as email "
                 . "FROM $table_name "
                 . $sCond;
            $result = $wpdb->get_results($sql);
            $result = json_decode(json_encode($result), true);
            foreach($result as $item)
            {
                $contacts[] = $item['email'];
            }
		}
		
		// we can't send invite to ourselves, so let's get username and email
        $table_name = $wpdb->prefix."users";
        $sCond = "WHERE ID = ".(int)$_COOKIE['fanvictor_user_id'];
        $sql = "SELECT user_login, user_email as email "
             . "FROM $table_name "
             . $sCond;
        $result = $wpdb->get_row($sql);
        $result = json_decode(json_encode($result), true);
        $myUsername = $result['user_login'];
        $myEmail = $result['email'];
		
		// check if value is an email address. If not
		// then get mmavictor username

		foreach ( $contacts as $contact )
		{
			$contact = trim($contact);
			if ( $contact == $myUsername || $contact == $myEmail )
				continue;
			
			$pos = strpos($contact, '@');
			if ($pos === false)
			{
				// go get the email of the user
				if ( $mmavictorInfo = $this->getPlayerInfoByUsername($contact) )
					array_push($trueContacts,strtolower(trim($mmavictorInfo["email"])));
			}
			else
				array_push($trueContacts,strtolower(trim($contact)));
		}
		
		if ( count($trueContacts) == 0 )
			return json_encode(array("message" =>"You haven't selected any contacts to invite or you can not invite yourself !"));
        
		$trueContacts = array_unique($trueContacts);
		$playerInfo = $this->getPlayerInfo($_COOKIE['fanvictor_user_id']);
        
        //league
        $this->selectField(array('name', 'size', 'entry_fee', 'poolID'));
		$leagueInfo = $this->getLeagueDetail($importleagueID);
        $leagueInfo = $leagueInfo[0];
        $website = 'http://'.$_SERVER['SERVER_NAME'];
        $contest = 'http://'.$_SERVER['SERVER_NAME'].'/fantasy/submit-picks/'.$importleagueID;
        $siteTitle = get_option('blogname');

		require_once('admin/emailTemplates/invite.php');
		$message=array('subject'=>$message_subject,
			'body'=>$message_body,
			'attachment'=>"\n\rAttached message: \n\r".$message_boxinvite);

		$message_footer="\r\n\r\nThanks for playing and Good Luck!"
				."\r\n\r\nGet into the game here $contest";
		//$message_subject=$name.$message['subject'];
		$message_subject = $message['subject'];
		$message_body = $message['body'] . $message_footer;
		$headers = "From: ".get_option('blogname')." <".get_option('admin_email').">\r\n";
        $success = true;

        foreach ($trueContacts as $email)
		{
            try 
            {
                if(!mail($email,$message_subject,$message_body,$headers))
                {
                    $success = false;
                }
            } 
            catch (Exception $ex) 
            {
                
            }
		}
        $message= "Invites Sent!";
        if(!$success)
        {
            $message = 'Something went wrong! Please try again.';
            return json_encode(array("notice" => $message));
        }
		return json_encode(array("message" => $message));
    }
    
    private function getPlayerInfoByUsername($username = null)
	{
        if($username != null)
        {
            $result = $this->database()->select('*')
                ->from(Phpfox::getT('user'))
                ->where("username = '$username'")
                ->execute('getSlaveRow');
            return $result;
        }
        return null;
	}
    
    private function getPlayerInfo($user_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix."users";
        $sCond = "WHERE ID = ".(int)$user_id;
        $sql = "SELECT *, user_email as email, display_name as full_name "
             . "FROM $table_name "
             . $sCond;
        $result = $wpdb->get_row($sql);
        $result = json_decode(json_encode($result), true);
        $result['pubKey'] = $result['firstName'] = $result['lastName'] = '';
        $result['username'] = $result['user_login'];
        return $result;
    }
    
    public function getAllPlayerInfo()
    {
        global $wpdb;
        $table_name = $wpdb->prefix."users";
        $sCond = "WHERE ID != ".$_COOKIE['fanvictor_user_id'];
        $sql = "SELECT *, user_email as email, display_name as full_name "
             . "FROM $table_name "
             . $sCond;
        $result = $wpdb->get_results($sql);
        $result = json_decode(json_encode($result), true);

        return $result;
    }
    
    ////////////////////////////////v2////////////////////////////////////
    public function isLeagueExist($leagueID)
    {
        if($this->sendRequest("isLeagueExist", array('leagueID' => $leagueID), false, false) == 1)
        {
            return true;
        }
        return false;
    }
    
    public function isNormalLeagueExist($leagueID)
    {
        if($this->sendRequest("isNormalLeagueExist", array('leagueID' => $leagueID), false, false) == 1)
        {
            return true;
        }
        return false;
    }
    
    public function isPlayerDraftLeagueExist($leagueID)
    {
        if($this->sendRequest("isPlayerDraftLeagueExist", array('leagueID' => $leagueID), false, false) == 1)
        {
            return true;
        }
        return false;
    }
    
    public function isPlayerDraftLeagueFull($leagueID, $entry_number)
    {
        if($this->sendRequest("isPlayerDraftLeagueFull", array('leagueID' => $leagueID, 'entry_number' => $entry_number), false, false) == 1)
        {
            return true;
        }
        return false;
    }
    
    public function getListSports()
    {
        return $this->sendRequest("getListSports", null, false);
    }
    
    public function getLeagueLobby()
    {
        return $this->sendRequest("getLeagueLobby", null, false);
    }
    
    public function getUpcomingEntries()
    {
        return $this->sendRequest("getUpcomingEntries", null, false);
    }
    
    public function getHistoryEntries()
    {
        return $this->sendRequest("getHistoryEntries", null, false);
    }
    
    public function getLiveEntries()
    {
        return $this->sendRequest("getLiveEntries", null, false);
    }
    
    public function liveEntriesResult($poolID, $leagueID)
    {
        echo $this->sendRequest("liveEntriesResult", array('poolID' => $poolID, 'leagueID' => $leagueID), false, false);exit;
    }

    public function parseLeagueData($aLeagues)
    {
        if($aLeagues != null)
        {
            foreach($aLeagues as $k => $aLeague)
            {
                $aLeagues[$k]['today'] = false;
                if(isset($aLeague['startDate']) && strtotime(date('Y-m-d')) == strtotime($aLeague['startDate']))
                {
                    $aLeagues[$k]['today'] = true;
                }
                
                //icon
                if(!empty($aLeague['sport_siteID']) && $aLeague['sport_siteID'] > 0 && !empty($aLeagues[$k]['icon']))
                {
                    $aLeagues[$k]['icon'] = FANVICTOR_IMAGE_URL.$aLeague['icon'];
                }
                
                //creator
                $user = $this->get_user_by("id",$aLeague['creator_userID']);
                $aLeagues[$k]['creator_name'] = $user != null ? $user->user_login : null;
                
                //total prize for winners
                $structure = '';
                if($aLeague['prize_structure'] == 'WINNER')
                {
                    $structure = 'winnertakeall';
                }
                else 
                {
                    $structure = 'top3';
                }
                $prizes = $this->pools->calculatePrizes('' , $structure, $aLeague['size'], $aLeague['entry_fee'], null, $aLeague['winner_percent'], $aLeague['first_percent'], $aLeague['second_percent'], $aLeague['third_percent']);
                $aLeagues[$k]['prizes'] = 0;
                foreach($prizes as $prize)
                {
                    $aLeagues[$k]['prizes'] += $prize;
                }
            }
        }
        return $aLeagues;
    }

    public function insertPlayerPicks($data)
    {
        $entry_number = $this->sendRequest("insertPlayerPicks", $data, false, false);
        if($entry_number > 0)
        {
            return $entry_number;
        }
        return false;
    }
    public function insertGolfSkinPlayerPicks($data)
    {
        $entry_number = $this->sendRequest("insertGolfSkinPlayerPicks", $data, false, false);
        if($entry_number > 0)
        {
            return $entry_number;
        }
        return false;
    }
    
    public function deletePlayerPicks($leagueID)
    {
        if($this->sendRequest("deletePlayerPicks", array('leagueID' => $leagueID), false, false))
        {
            return true;
        }
        return false;
    }
    
    public function getPlayerPicks($leagueID, $entry_number)
    {
        $data = $this->sendRequest("getPlayerPicks", array('leagueID' => $leagueID, 'entry_number' => $entry_number), false);
        return $data;
    }
    
    public function getPlayerPickEntries($leagueID)
    {
        $aDatas = $this->sendRequest("getPlayerPickEntries", array('leagueID' => $leagueID), false);
        return $this->parseUserData($aDatas);
    }
    
    public function getEntries($leagueID)
    {
        $aDatas = $this->sendRequest("getEntries", array('leagueID' => $leagueID), false);
        return $this->parseUserData($aDatas);
    }
    
    public function getScores($leagueID)
    {
        $aDatas = $this->sendRequest("getScores", array('leagueID' => $leagueID), false);
        return $this->parseUserData($aDatas);
    }
    
    private function parseUserData($aDatas = null)
    {
        if($aDatas != null)
        {
            foreach($aDatas as $k => $aData)
            {
                $user = $this->get_user_by("id",$aData['userID']);
                if($user != null)
                {
                    $aDatas[$k]['username'] = $user->user_login;
                    $aDatas[$k]['avatar'] = $this->get_avatar_url($this->get_avatar($aData['userID']));
                }
            }
        }
        return $aDatas;
    }
    
    public function get_avatar_url($get_avatar)
    {
        preg_match("/src=['\"](.*?)['\"]/i", $get_avatar, $matches);
		return $matches[1];
    }
    
    public function getPlayerPicksResult($leagueID, $userID, $entry_number,$round_id)
    {
        return $this->sendRequest("getPlayerPicksResult", array('leagueID' => $leagueID, 'userID' => $userID, 'entry_number' => $entry_number,'roundID'=>$round_id), false);
    }
    
    public function getPlayerStatistics($orgID, $playerID, $poolID)
    {
        return $this->sendRequest("getPlayerStatistics", array("orgID" => $orgID, "playerID" => $playerID, "poolID" => $poolID), false);
    }

    public function getPoolInfo($leagueID)
    {
        $aDatas = $this->sendRequest("getPoolInfo", array('leagueID' => $leagueID), false);
        $aDatas['entries'] = $this->parseUserData($aDatas['entries']);
        return $aDatas;
    }

    public function getNewPools()
    {
        return $this->sendRequest("getNewPools", null, false);
    }
    
    public function validCreateLeague($orgID, $poolID, $game_type, $name, $fightID, $roundID, $payouts_from = null, $payouts_to = null, $percentage = null, $mixingPools = null, $sport_type = null, $mixing_game_type = null,$payout_name = null, $payout_price = null)
    {
        return $this->sendRequest("validCreateLeague", array(
            "orgID" => $orgID, 
            "poolID" => $poolID, 
            "game_type" => $game_type,
            "name" => $name,
            "fightID" => $fightID,
            "roundID" => $roundID,     
            "payouts_from" => $payouts_from,
            "payouts_to" => $payouts_to,
            "percentage" => $percentage,
            "mixingPools" => $mixingPools,
            "sport_type" => $sport_type,
            "mixing_game_type" => $mixing_game_type,
            "payouts_name"=>$payout_name,
            "payouts_price"=>$payout_price), false, false);
    }
    
    public function createLeague($data)
    {
        /*$data['winner_percent'] = get_option('fanvictor_winner_percent');
        $data['first_percent'] = get_option('fanvictor_first_place_percent');
        $data['second_percent'] = get_option('fanvictor_second_place_percent');
        $data['third_percent'] = get_option('fanvictor_third_place_percent');*/
        if(!isset($data['is_refund']))
        {
            $data['is_refund'] = 0;
        }
        if(!isset($data['is_payouts']))
        {
            $data['is_payouts'] = 0;
        }
        return $this->sendRequest("createLeague", $data, false, false);
    }

	public function loadCreateLeagueForm($leagueID = null)
    {
        return $this->sendRequest("loadCreateLeagueForm", array("leagueID" => $leagueID), false);
    }
    
    public function getEnterGameData($leagueID, $entry_number)
    {
        return $this->sendRequest("getEnterGameData", array("leagueID" => $leagueID, "entry_number" => $entry_number), false);
    }
    public function getEnterMixingGameData($leagueID, $entry_number)
    {
        return $this->sendRequest("getEnterMixingGameData", array("leagueID" => $leagueID, "entry_number" => $entry_number), false);
    }
    
    public function getEnterNormalGameData($leagueID, $entry_number)
    {
        return $this->sendRequest("getEnterNormalGameData", array("leagueID" => $leagueID, "entry_number" => $entry_number), false);
    }

    public function getMixingGameEntryData($leagueID, $entry_number)
    {
        return $this->sendRequest("getMixingGameEntryData", array("leagueID" => $leagueID, "entry_number" => $entry_number), false);
    }
    
    public function getGameEntryData($leagueID, $entry_number)
    {
        return $this->sendRequest("getGameEntryData", array("leagueID" => $leagueID, "entry_number" => $entry_number), false);
    }

    public function validEnterPlayerdraft($leagueID, $playerIDs)
    {
        return $this->sendRequest("validEnterPlayerdraft", array("leagueID" => $leagueID, "playerIDs" => $playerIDs), false, false);
    }
    
    public function validEnterGolfSkin($leagueID, $playerIDs)
    {
        return $this->sendRequest("validEnterGolfSkin", array("leagueID" => $leagueID, "playerIDs" => $playerIDs), false, false);
    }
    public function validEnterMixingPlayerdraft($leagueID, $playerIDs)
    {
        return $this->sendRequest("validEnterMixingPlayerdraft", array("leagueID" => $leagueID, "playerIDs" => $playerIDs), false, false);
    }
    
    public function getContestResult($leagueID)
    {
        return $this->sendRequest("getContestResult", array("leagueID" => $leagueID), false);
    }

    public function loadFixtureScores($leagueID)
    {
        return $this->sendRequest("loadFixtureScores", array("leagueID" => $leagueID), false);
    }
	
	public function getStatData()
    {
        return $this->sendRequest("getStatData", null, false);//, false);die;
    }


	public function getStatJS($a, $b, $c, $d, $sort_name, $sort_value, $team_id, $position_id)
	{
		return $this->sendRequest("getStatJS", array(
            "sid"=> $a, 
            "pid"=> $b, 
            "filters" => $c, 
            "lim"=> $d, 
            "sort_name" => $sort_name, 
            "sort_value" => $sort_value,
            "team_id" => $team_id,
            "position_id" => $position_id), false);
	}
	
	public function showUserPicks($leagueID)
    {
        $data = $this->sendRequest("showUserPicks", array('leagueID' => $leagueID), false);
        if(!empty($data['picks']))
        {
            foreach($data['picks'] as $k1 => $pick)
            {
                $user = $this->get_user_by("id",$pick['userID']);
                if($user != null)
                {
                    $data['picks'][$k1]['user_login'] = $user->user_login;
                }
            }
        }
        return $data;
    }
    
    public function sendUserPickEmail($leagueID, $user_id, $entry_number)
    {
        $data = $this->sendRequest("showUserPicks", array(
            'leagueID' => $leagueID, 
            'userID' => $user_id,
            'entry_number' => $entry_number), false);
       $aUser = $this->getPlayerInfo($_COOKIE['fanvictor_user_id']);
        if($data != null && $aUser != null && !empty($aUser))
        {
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= 'To: ' . $aUser['user_email']. "\r\n";
            $headers .= "From: ".get_option('blogname')." <".get_option('admin_email').">\r\n";
            $league = $data['league'];
            if($league['gameType'] == 'PICKSQUARES'){
                $picks = $data['picks'];
                if($picks){
                    $picks = json_decode($picks,true);
                }
            }else{
                $picks = $data['picks'][0]['entries'][0]['pick_items'];
            }
            include 'admin/emailTemplates/picks.php';
            try 
            {
                mail($aUser['user_email'], $message_subject, $message_body, $headers);
            } 
            catch (Exception $ex) 
            {

            }
        }
    }
    
    public function sendUserJoincontestEmail($leagueID, $entry_number) {
        $data = $this->sendRequest("showUserPicks", array(
            'leagueID' => $leagueID, 
            'userID' => $_COOKIE['fanvictor_user_id'],
            'entry_number' => $entry_number), false);
       $aUser = $this->getPlayerInfo($_COOKIE['fanvictor_user_id']);
        if($data != null && $aUser != null && !empty($aUser))
        {
            $admin_email = get_option('admin_email');
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= 'To: ' . $admin_email. "\r\n";
            $headers .= "From: ".get_option('blogname')." <".get_option('admin_email').">\r\n";
            $league = $data['league'];
            $user_name = $aUser['user_login'];
            if($league['gameType'] == 'PICKSQUARES'){
                $picks = $data['picks'];
                if($picks){
                    $picks = json_decode($picks,true);
                }
            }else{
                $picks = $data['picks'][0]['entries'][0]['pick_items'];
            }
            include 'admin/emailTemplates/picks_admin.php';
            try 
            {
                mail($admin_email, $message_subject, $message_body, $headers);
            } 
            catch (Exception $ex) 
            {

            }
        }
    }

    public function sendRequestPaymentEmail($id, $credits)
    {
        $current_user = $this->getPlayerInfo($_COOKIE['fanvictor_user_id']);
        $emailAdmin      = get_option('admin_email');
        $subject = 'Request Payment';
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'To: ' . $emailAdmin . "\r\n";
        $headers .= "From: ".get_option('blogname')." <".$emailAdmin.">\r\n";
        $username = $current_user['user_login'];
        $mount = $credits;
        
        require_once(FANVICTOR__PLUGIN_DIR_MODEL.'admin/emailTemplates/withdrawl.php');
        try 
        {
            mail($emailAdmin,$subject,$message,$headers);
        } catch (Exception $ex) {
            return false;
        }
    }
    
    public function sendApplyWithdrawlEmail($id, $status)
    {
        $withdrawl = $this->payment->getWithdraw($id);
        if($withdrawl != null)
        {
            $user = $this->payment->getUserData($withdrawl['userID']);
            $emailAdmin      = get_option('admin_email');
            $subject = 'Request Payment';
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= 'To: ' . $user['user_email'] . "\r\n";
            $headers .= "From: ".get_option('blogname')." <".$emailAdmin.">\r\n";
            
            if($status == 'APPROVED')
            {
                require_once(FANVICTOR__PLUGIN_DIR_MODEL.'admin/emailTemplates/withdrawl_approved.php');
            }
            else if($status == 'DECLINED')
            {
                require_once(FANVICTOR__PLUGIN_DIR_MODEL.'admin/emailTemplates/withdrawl_declined.php');
            }

            try 
            {
                mail($emailAdmin,$subject,$message,$headers);
            } 
            catch (Exception $ex) 
            {
                return false;
            }
        }
    }
	
	public function sendUserCreditEmail($user_id, $credits, $operation, $reason = null)
    {
        $user = $this->payment->getUserData($user_id);
        $emailAdmin      = get_option('admin_email');
        $subject = 'Credits';
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'To: ' . $user['user_email'] . "\r\n";
        $headers .= "From: ".get_option('blogname')." <".$emailAdmin.">\r\n";

        if($operation == 'ADD')
        {
            require_once(FANVICTOR__PLUGIN_DIR_MODEL.'admin/emailTemplates/credit_add.php');
        }
        else if($operation == 'DEDUCT')
        {
            require_once(FANVICTOR__PLUGIN_DIR_MODEL.'admin/emailTemplates/credit_deduct.php');
        }

        try 
        {
            mail($user['user_email'],$subject,$message,$headers);
        } 
        catch (Exception $ex) 
        {
            return false;
        }
    }
    public function createFolderCustomSport($data){
       return $this->sendRequest("createfolderCustomSport", $data, true);
    }
    
    public function loadStatsUploadedFile($data){
         return $this->sendRequest("loadStatsUploadedFile", $data, true);
    }
    
    public function getCountryList()
    {
        return array(
            'AF' => 'AFGHANISTAN',
            'AX' => 'ÅLAND ISLANDS',
            'AL' => 'ALBANIA',
            'DZ' => 'ALGERIA',
            'AS' => 'AMERICAN SAMOA',
            'AD' => 'ANDORRA',
            'AO' => 'ANGOLA',
            'AI' => 'ANGUILLA',
            'AQ' => 'ANTARCTICA',
            'AG' => 'ANTIGUA AND BAR­BUDA',
            'AR' => 'ARGENTINA',
            'AM' => 'ARMENIA',
            'AW' => 'ARUBA',
            'AU' => 'AUSTRALIA',
            'AT' => 'AUSTRIA',
            'AZ' => 'AZERBAIJAN',
            'BS' => 'BAHAMAS',
            'BH' => 'BAHRAIN',
            'BD' => 'BANGLADESH',
            'BB' => 'BARBADOS',
            'BY' => 'BELARUS',
            'BE' => 'BELGIUM',
            'BZ' => 'BELIZE',
            'BJ' => 'BENIN',
            'BM' => 'BERMUDA',
            'BT' => 'BHUTAN',
            'BO' => 'BOLIVIA',
            'BA' => 'BOSNIA AND HERZE­GOVINA',
            'BW' => 'BOTSWANA',
            'BV' => 'BOUVET ISLAND',
            'BR' => 'BRAZIL',
            'IO' => 'BRITISH INDIAN OCEAN TERRITORY',
            'BN' => 'BRUNEI DARUSSALAM',
            'BG' => 'BULGARIA',
            'BF' => 'BURKINA FASO',
            'BI' => 'BURUNDI',
            'KH' => 'CAMBODIA',
            'CM' => 'CAMEROON',
            'CA' => 'CANADA',
            'CV' => 'CAPE VERDE',
            'KY' => 'CAYMAN ISLANDS',
            'CF' => 'CENTRAL AFRICAN REPUBLIC',
            'TD' => 'CHAD',
            'CL' => 'CHILE',
            'CN' => 'CHINA',
            'CX' => 'CHRISTMAS ISLAND',
            'CC' => 'COCOS (KEELING) ISLANDS',
            'CO' => 'COLOMBIA',
            'KM' => 'COMOROS',
            'CG' => 'CONGO',
            'CD' => 'CONGO, THE DEMO­CRATIC REPUBLIC OF THE',
            'CK' => 'COOK ISLANDS',
            'CR' => 'COSTA RICA',
            'CI' => 'COTE D IVOIRE',
            'HR' => 'CROATIA',
            'CU' => 'CUBA',
            'CY' => 'CYPRUS',
            'CZ' => 'CZECH REPUBLIC',
            'DK' => 'DENMARK',
            'DJ' => 'DJIBOUTI',
            'DM' => 'DOMINICA',
            'DO' => 'DOMINICAN REPUBLIC',
            'EC' => 'ECUADOR',
            'EG' => 'EGYPT',
            'SV' => 'EL SALVADOR',
            'GQ' => 'EQUATORIAL GUINEA',
            'ER' => 'ERITREA',
            'EE' => 'ESTONIA',
            'ET' => 'ETHIOPIA',
            'FK' => 'FALKLAND ISLANDS (MALVINAS)',
            'FO' => 'FAROE ISLANDS',
            'FJ' => 'FIJI',
            'FI' => 'FINLAND',
            'FR' => 'FRANCE',
            'GF' => 'FRENCH GUIANA',
            'PF' => 'FRENCH POLYNESIA',
            'TF' => 'FRENCH SOUTHERN TERRITORIES',
            'GA' => 'GABON',
            'GM' => 'GAMBIA',
            'GE' => 'GEORGIA',
            'DE' => 'GERMANY',
            'GH' => 'GHANA',
            'GI' => 'GIBRALTAR',
            'GR' => 'GREECE',
            'GL' => 'GREENLAND',
            'GD' => 'GRENADA',
            'GP' => 'GUADELOUPE',
            'GU' => 'GUAM',
            'GT' => 'GUATEMALA',
            'GG' => 'GUERNSEY',
            'GN' => 'GUINEA',
            'GW' => 'GUINEA-BISSAU',
            'GY' => 'GUYANA',
            'HT' => 'HAITI',
            'HM' => 'HEARD ISLAND AND MCDONALD ISLANDS',
            'VA' => 'HOLY SEE (VATICAN CITY STATE)',
            'HN' => 'HONDURAS',
            'HK' => 'HONG KONG',
            'HU' => 'HUNGARY',
            'IS' => 'ICELAND',
            'IN' => 'INDIA',
            'ID' => 'INDONESIA',
            'IR' => 'IRAN, ISLAMIC REPUB­LIC OF',
            'IQ' => 'IRAQ',
            'IE' => 'IRELAND',
            'IM' => 'ISLE OF MAN',
            'IL' => 'ISRAEL',
            'IT' => 'ITALY',
            'JM' => 'JAMAICA',
            'JP' => 'JAPAN',
            'JE' => 'JERSEY',
            'JO' => 'JORDAN',
            'KZ' => 'KAZAKHSTAN',
            'KE' => 'KENYA',
            'KI' => 'KIRIBATI',
            'KP' => 'KOREA, DEMOCRATIC PEOPLES REPUBLIC OF',
            'KR' => 'KOREA, REPUBLIC OF',
            'KW' => 'KUWAIT',
            'KG' => 'KYRGYZSTAN',
            'LA' => 'LAO PEOPLES DEMO­CRATIC REPUBLIC',
            'LV' => 'LATVIA',
            'LB' => 'LEBANON',
            'LS' => 'LESOTHO',
            'LR' => 'LIBERIA',
            'LY' => 'LIBYAN ARAB JAMA­HIRIYA',
            'LI' => 'LIECHTENSTEIN',
            'LT' => 'LITHUANIA',
            'LU' => 'LUXEMBOURG',
            'MO' => 'MACAO',
            'MK' => 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF',
            'MG' => 'MADAGASCAR',
            'MW' => 'MALAWI',
            'MY' => 'MALAYSIA',
            'MV' => 'MALDIVES',
            'ML' => 'MALI',
            'MT' => 'MALTA',
            'MH' => 'MARSHALL ISLANDS',
            'MQ' => 'MARTINIQUE',
            'MR' => 'MAURITANIA',
            'MU' => 'MAURITIUS',
            'YT' => 'MAYOTTE',
            'MX' => 'MEXICO',
            'FM' => 'MICRONESIA, FEDER­ATED STATES OF',
            'MD' => 'MOLDOVA, REPUBLIC OF',
            'MC' => 'MONACO',
            'MN' => 'MONGOLIA',
            'MS' => 'MONTSERRAT',
            'MA' => 'MOROCCO',
            'MZ' => 'MOZAMBIQUE',
            'MM' => 'MYANMAR',
            'NA' => 'NAMIBIA',
            'NR' => 'NAURU',
            'NP' => 'NEPAL',
            'NL' => 'NETHERLANDS',
            'AN' => 'NETHERLANDS ANTI­LLES',
            'NC' => 'NEW CALEDONIA',
            'NZ' => 'NEW ZEALAND',
            'NI' => 'NICARAGUA',
            'NE' => 'NIGER',
            'NG' => 'NIGERIA',
            'NU' => 'NIUE',
            'NF' => 'NORFOLK ISLAND',
            'MP' => 'NORTHERN MARIANA ISLANDS',
            'NO' => 'NORWAY',
            'OM' => 'OMAN',
            'PK' => 'PAKISTAN',
            'PW' => 'PALAU',
            'PS' => 'PALESTINIAN TERRI­TORY, OCCUPIED',
            'PA' => 'PANAMA',
            'PG' => 'PAPUA NEW GUINEA',
            'PY' => 'PARAGUAY',
            'PE' => 'PERU',
            'PH' => 'PHILIPPINES',
            'PN' => 'PITCAIRN',
            'PL' => 'POLAND',
            'PT' => 'PORTUGAL',
            'PR' => 'PUERTO RICO',
            'QA' => 'QATAR',
            'RE' => 'REUNION',
            'RO' => 'ROMANIA',
            'RU' => 'RUSSIAN FEDERATION',
            'RW' => 'RWANDA',
            'SH' => 'SAINT HELENA',
            'KN' => 'SAINT KITTS AND NEVIS',
            'LC' => 'SAINT LUCIA',
            'PM' => 'SAINT PIERRE AND MIQUELON',
            'VC' => 'SAINT VINCENT AND THE GRENADINES',
            'WS' => 'SAMOA',
            'SM' => 'SAN MARINO',
            'ST' => 'SAO TOME AND PRINC­IPE',
            'SA' => 'SAUDI ARABIA',
            'SN' => 'SENEGAL',
            'CS' => 'SERBIA AND MON­TENEGRO',
            'SC' => 'SEYCHELLES',
            'SL' => 'SIERRA LEONE',
            'SG' => 'SINGAPORE',
            'SK' => 'SLOVAKIA',
            'SI' => 'SLOVENIA',
            'SB' => 'SOLOMON ISLANDS',
            'SO' => 'SOMALIA',
            'ZA' => 'SOUTH AFRICA',
            'GS' => 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS',
            'ES' => 'SPAIN',
            'LK' => 'SRI LANKA',
            'SD' => 'SUDAN',
            'SR' => 'SURINAME',
            'SJ' => 'SVALBARD AND JAN MAYEN',
            'SZ' => 'SWAZILAND',
            'SE' => 'SWEDEN',
            'CH' => 'SWITZERLAND',
            'SY' => 'SYRIAN ARAB REPUB­LIC',
            'TW' => 'TAIWAN, PROVINCE OF CHINA',
            'TJ' => 'TAJIKISTAN',
            'TZ' => 'TANZANIA, UNITED REPUBLIC OF',
            'TH' => 'THAILAND',
            'TL' => 'TIMOR-LESTE',
            'TG' => 'TOGO',
            'TK' => 'TOKELAU',
            'TO' => 'TONGA',
            'TT' => 'TRINIDAD AND TOBAGO',
            'TN' => 'TUNISIA',
            'TR' => 'TURKEY',
            'TM' => 'TURKMENISTAN',
            'TC' => 'TURKS AND CAICOS ISLANDS',
            'TV' => 'TUVALU',
            'UG' => 'UGANDA',
            'UA' => 'UKRAINE',
            'AE' => 'UNITED ARAB EMIR­ATES',
            'GB' => 'UNITED KINGDOM',
            'US' => 'UNITED STATES',
            'UM' => 'UNITED STATES MINOR OUTLYING ISLANDS',
            'UY' => 'URUGUAY',
            'UZ' => 'UZBEKISTAN',
            'VU' => 'VANUATU',
            'VE' => 'VENEZUELA',
            'VN' => 'VIET NAM',
            'VG' => 'VIRGIN ISLANDS, BRIT­ISH',
            'VI' => 'VIRGIN ISLANDS, U.S.',
            'WF' => 'WALLIS AND FUTUNA',
            'EH' => 'WESTERN SAHARA',
            'YE' => 'YEMEN',
            'ZM' => 'ZAMBIA',
            'ZW' => 'ZIMBABWE',
        );
    }
}
?>