<?php
class Game
{
    private static $orgs;
    private static $pools;
    private static $playerposition;
    private static $players;
    private static $teams;
    private static $fanvictor;
    private static $payment;
    public function __construct() 
    {
        self::$payment = new Payment();
        self::$orgs = new Organizations();
        self::$pools = new Pools();
        self::$playerposition = new PlayerPosition();
        self::$players = new Players();
        self::$teams = new Teams();
        self::$fanvictor = new Fanvictor();
    }
    
	public static function process()
	{    
          
        if(isset($_POST['submitPicks']))
        {
            if($_POST["session_id"] == session_id())
            {
                add_filter('the_content', array('Game', 'submitPicks'));
            }
            else 
            {
                redirect(FANVICTOR_URL_CREATE_CONTEST, __("Contest does not exist.", FV_DOMAIN), true);
            }
        }
        else 
        {
            add_action( 'wp_enqueue_scripts', array('Game', 'theme_name_scripts') );
            add_filter('the_content', array('Game', 'game'));
        }
	}
    
    public static function theme_name_scripts()
    {
        wp_enqueue_script('countdown.min.js', FANVICTOR__PLUGIN_URL_JS.'countdown.min.js', 5);
        wp_enqueue_script('playerdraft.js', FANVICTOR__PLUGIN_URL_JS.'playerdraft.js', 5);
        wp_enqueue_script('playerdraft_init.js', FANVICTOR__PLUGIN_URL_JS.'playerdraft_init.js', 5);
        wp_enqueue_script('tablesorter.js', FANVICTOR__PLUGIN_URL_JS.'tablesorter.js', 5);
        wp_enqueue_script('accounting.js', FANVICTOR__PLUGIN_URL_JS.'accounting.js', 5);
        wp_enqueue_script('ui.js', FANVICTOR__PLUGIN_URL_JS.'ui.js', 5);
        wp_enqueue_style('playerdraft.css', FANVICTOR__PLUGIN_URL_CSS.'playerdraft.css');
        wp_enqueue_style('font-awesome.css', FANVICTOR__PLUGIN_URL_CSS.'font-awesome/css/font-awesome.css');
    }

    public static function game()
    {
        
        if(!in_the_loop())
        {
            return;
        }
        $leagueId = pageSegment(3);
        $entry_number = isset($_GET['num']) ? $_GET['num'] : 0;
        //league
        $league = self::$fanvictor->getLeagueDetail($leagueId);
        //load game data
        $isMixing = $league[0]['is_mixing'];
        
        if($isMixing){
            // load game data with type mixing
            $data = self::$fanvictor->getEnterMixingGameData($leagueId, $entry_number);
        }else{
            // load game data with type single
             $data = self::$fanvictor->getEnterGameData($leagueId, $entry_number);
        }
        switch($data)
        {
            case 2:
                redirect(FANVICTOR_URL_CREATE_CONTEST, __('Contest does not exist', FV_DOMAIN), true);
                break;
            case 3:
                redirect(FANVICTOR_URL_CONTEST.$leagueId, null, true);
                break;
            case 4:
                redirect(FANVICTOR_URL_CREATE_CONTEST, __('Sorry! This contest was full', FV_DOMAIN), true);
                break;
        }
        if(!self::$payment->isUserEnoughMoneyToJoin($league[0]['entry_fee'], $leagueId, $entry_number))
        {
            redirect(FANVICTOR_URL_ADD_FUNDS, __('You do not have enough funds to enter. Please add funds', FV_DOMAIN), true);
        }
        else 
        {
            if(!$isMixing){
                $league = $data['league'];
                $league = self::$fanvictor->parseLeagueData(array($league));
                $league = $league[0];
                $aPool = $data['pool'];
                $aFights = $data['fights'];
                $aRounds = $data['rounds'];
                $aPositions = $data['positions'];
                $aLineups = $data['lineup'];
                $aTeams = $data['teams'];
                $aIndicators = $data['indicators'];
                $playerIdPicks = $data['playerIdPicks'];
                $otherLeagues = $data['otherLeagues'];
                $aPlayers = $data['players'];
                $aPlayers = self::$players->parsePlayersData($aPlayers);
                $aPlayerGolfSkin = $data['golfskinPlayers'];
            }else{
                $league = $data['league'];
                $league = self::$fanvictor->parseLeagueData(array($league));
                $league = $league[0];
                $aPool = $data['pool'];
                $aAllFights = $data['fights'];
                $aRounds = $data['rounds'];
                $aPositions = $data['positions'];
                $aLineups = $data['lineup'];
                $aTeams = $data['teams'];
                $aIndicators = $data['indicators'];
                $playerIdPicks = $data['playerIdPicks'];
                $otherLeagues = $data['otherLeagues'];
                $aPlayers = $data['players'];
                $aSports = $data['aSports'];
                $aPlayers = self::$players->parseMixingPlayersData($aPlayers);
                // count salary
                $salary_remaining = 0;
                foreach($aPool as $pool){
                    $salary_remaining+= $pool['salary_remaining'];
                }
            }
            if($league['gameType'] == 'GOLFSKIN'){
                $total_money = 0;
                $entry_fee = $league['entry_fee'];
                $is_entry_fee = 0;
                if($entry_fee > 0){
                    $is_entry_fee = 1;
                }
                if(!is_array($aPlayerGolfSkin)){
                    $tmp_array = json_decode($aPlayerGolfSkin,true);
                    foreach($tmp_array as $players){
                            $total_money+= count($players);
                    }
                }
                if(is_array($aPlayerGolfSkin)){
                $aPlayerGolfSkin = json_encode($aPlayerGolfSkin);
            }
            }
            include FANVICTOR__PLUGIN_DIR_VIEW.'game.php';
        }
    }
    
    public static function submitPicks()
    {
        //check valid data
        if(isset($_POST['game_type']) && $_POST['game_type'] == 'GOLFSKIN'){
            return self::golfskinSubmitPicks();
        }
        self::validData();
        $entry_number = $_POST['entry_number'];
        $data = array('leagueID' => $_POST['leagueID'],
                      'player_id' => $_POST['player_id'],
                      'entry_number' => $entry_number);
        $entry_number = self::$fanvictor->insertPlayerPicks($data);
        if($entry_number > 0)
        {
            $league = self::$fanvictor->getLeagueDetail($_POST['leagueID']);
            $league = $league [0];
            $makeBet = self::$payment->isMakeBetForLeague($league['leagueID'], $entry_number);
                    

            if($makeBet == false)
            {
                //decrease user money
                self::$payment->updateUserBalance($league['entry_fee'], true, $league['leagueID']);

                //add to history
                $aUser = self::$payment->getUserData();
                self::$payment->addFundhistory($league['entry_fee'], $league['leagueID'], $aUser['balance'], 'MAKE_BET', 'DEDUCT', null, null, null, null, null, $entry_number);
            }
            $_SESSION['showInviteFriends'.$league['leagueID']] = true;
			$_SESSION['userPicksInfo'] = array($_POST['leagueID'], $_COOKIE['fanvictor_user_id'], $entry_number);
            redirect(FANVICTOR_URL_ENTRY.$_POST['leagueID']."/?num=".$entry_number, null, true);
        }
        redirect(FANVICTOR_URL_GAME.$_POST['leagueID'], __('Something went wrong! Please try again.', FV_DOMAIN), true);
    }
    
    private static function validData()
    {
        //league
        $league = self::$fanvictor->getLeagueDetail($_POST['leagueID']);
        //valid
        $isMixing = $league[0]['is_mixing'];
        
        if($isMixing){ // mixing game
            $valid = self::$fanvictor->validEnterMixingPlayerdraft($_POST['leagueID'], $_POST['player_id']);

        }else{ // single game
            $valid = self::$fanvictor->validEnterPlayerdraft($_POST['leagueID'], $_POST['player_id']);
        }
        switch($valid)
        {
            case 2:
                redirect(FANVICTOR_URL_CREATE_CONTEST, __("This contest had completed", FV_DOMAIN), true);
                break;
            case 3:
                redirect(FANVICTOR_URL_CREATE_CONTEST, __('Contest does not exist', FV_DOMAIN), true);
                break;
            case 4:
                redirect(FANVICTOR_URL_CREATE_CONTEST, __('Sorry! This contest is full', FV_DOMAIN), true);
                break;
            case 5:
                redirect(FANVICTOR_URL_GAME.$league[0]['leagueID'], __("Your team has exceeded this game's salary cap. Please change your team so it fits under the salary cap before entering"), true, FV_DOMAIN);
                break;
            case 6:
                redirect(FANVICTOR_URL_GAME.$league[0]['leagueID'], __("Please select a player for each position", FV_DOMAIN), true);
                break;
            case 7:
                redirect(FANVICTOR_URL_GAME.$league[0]['leagueID'], __("You can not pick players of started game", FV_DOMAIN), true);
                break;
        }
        if(!self::$payment->isUserEnoughMoneyToJoin($league[0]['entry_fee'], $_POST['leagueID']))
        {
            redirect(FANVICTOR_URL_ADD_FUNDS, __('You do not have enough funds to enter. Please add funds', FV_DOMAIN));
        }
    }
    
    private static function golfskinSubmitPicks(){
        self::golfskinValidata();
        $entry_number = $_POST['entry_number'];
        $data = array('leagueID' => $_POST['leagueID'],
                      'players' => $_POST['players'],
                      'poolID' => $_POST['poolID'],
                      'entry_number' => $entry_number);
        $entry_number = self::$fanvictor->insertGolfSkinPlayerPicks($data);
        if($entry_number > 0)
        {
            $league = self::$fanvictor->getLeagueDetail($_POST['leagueID']);
            $league = $league [0];
            $makeBet = self::$payment->isMakeBetForLeague($league['leagueID'], $entry_number);
            $total_money = $_POST['total_money'];
            if($makeBet == false)
            {
                //decrease user money
                self::$payment->updateUserBalance($total_money, true, $league['leagueID']);
                //add to history
                $aUser = self::$payment->getUserData();
                self::$payment->addFundhistory($total_money, $league['leagueID'], $aUser['balance'], 'MAKE_BET', 'DEDUCT', null, null, null, null, null, $entry_number);
            }
            else{
                // update
                $diff_balance = $makeBet['amount'] - $total_money;
                if($diff_balance != 0){
                    //update balance
                    $status = ($diff_balance > 0)?false:true;
                    self::$payment->updateUserBalance($diff_balance, $status, $league['leagueID']);
                    
                    
                    $payout_percentage = get_option('fanvictor_winner_percent');
                    $site_profit = $total_money * (100 - $payout_percentage) / 100;
                    $aValues['site_profit'] = $site_profit;
                    self::$payment->updateFundhistory($makeBet['fundshistoryID'],$aValues,'DEDUCT');
                }
                
            }
            $_SESSION['showInviteFriends'.$league['leagueID']] = true;
		$_SESSION['userPicksInfo'] = array($_POST['leagueID'], get_current_user_id(), $entry_number);
            redirect(FANVICTOR_URL_ENTRY.$_POST['leagueID']."/?num=".$entry_number, null, true);
        }
        redirect(FANVICTOR_URL_GAME.$_POST['leagueID'], __('Something went wrong! Please try again.', FV_DOMAIN), true);
    }
    private static function golfskinValidata(){
        //league
        $league = self::$fanvictor->getLeagueDetail($_POST['leagueID']);
        //valid
        $valid = self::$fanvictor->validEnterGolfSkin($_POST['leagueID'], $_POST['player_id']);
        switch($valid)
        {
            case 2:
                redirect(FANVICTOR_URL_CREATE_CONTEST, __("This contest had completed", FV_DOMAIN), true);
                break;
            case 3:
                redirect(FANVICTOR_URL_CREATE_CONTEST, __('Contest does not exist', FV_DOMAIN), true);
                break;
            case 4:
                redirect(FANVICTOR_URL_CREATE_CONTEST, __('Sorry! This contest is full', FV_DOMAIN), true);
                break;
            case 5:
                redirect(FANVICTOR_URL_GAME.$league[0]['leagueID'], __("Please select at least one player for each round"), true, FV_DOMAIN);
                break;
        }
    }
}
?>