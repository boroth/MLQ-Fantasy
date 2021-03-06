<?php
class Entry
{
    private static $orgs;
    private static $pools;
    private static $playerposition;
    private static $players;
    private static $teams;
    private static $fanvictor;
    public function __construct() 
    {
        self::$orgs = new Organizations();
        self::$pools = new Pools();
        self::$playerposition = new PlayerPosition();
        self::$players = new Players();
        self::$teams = new Teams();
        self::$fanvictor = new Fanvictor();
    }
    
	public static function process()
	{    
        add_action( 'wp_enqueue_scripts', array('Entry', 'theme_name_scripts') );
        add_filter('the_content', array('Entry', 'entry'));
	}
    
    public static function theme_name_scripts()
    {
        wp_enqueue_script('countdown.min.js', FANVICTOR__PLUGIN_URL_JS.'countdown.min.js', 5);
        wp_enqueue_script('playerdraft.js', FANVICTOR__PLUGIN_URL_JS.'playerdraft.js', 5);
        wp_enqueue_script('accounting.js', FANVICTOR__PLUGIN_URL_JS.'accounting.js', 5);
        wp_enqueue_style('playerdraft.css', FANVICTOR__PLUGIN_URL_CSS.'playerdraft.css');
        wp_enqueue_style('font-awesome.css', FANVICTOR__PLUGIN_URL_CSS.'font-awesome/css/font-awesome.css');
    }

    public static function entry()
    {
        if(!in_the_loop())
        {
            return;
        }
        $leagueID = pageSegment(3);
        $entry_number = $_GET['num'];
        
        //league
        $league = self::$fanvictor->getLeagueDetail($leagueID);
        $isMixing = $league[0]['is_mixing'];
        //pool
        
        self::$pools->selectField(array('status'));
        if($isMixing){
            $listPools = json_decode($league[0]['mixing_pools'],true);
            $aPool = self::$pools->getMixingPools($listPools, null, false, true);

        }else{
            $aPool = self::$pools->getPools($league[0]['poolID'], null, false, true);

        }
        if(!self::$fanvictor->isPlayerDraftLeagueExist($leagueID))
        {
            redirect(FANVICTOR_URL_CREATE_CONTEST, __('Contest does not exist', FV_DOMAIN), true);
        }
        else//check league completed
        {
            if($isMixing){
                if($league[0]['is_mixing_complete']){
                    redirect(FANVICTOR_URL_CONTEST.$leagueID, null, true);
                }
                
            }else{
                if($aPool['status'] != 'NEW'){
                    redirect(FANVICTOR_URL_CONTEST.$leagueID, null, true);
                }    
            }
            
        }
        
        //player picks
        $playerPicks = self::$fanvictor->getPlayerPicks($leagueID, $entry_number);

        if($playerPicks == null && $league[0]['gameType'] != 'GOLFSKIN')
        {
            redirect(FANVICTOR_URL_CREATE_CONTEST, __('You did not select any players', FV_DOMAIN));
        }
        else
        {
            if($isMixing){
               
                  $data = self::$fanvictor->getMixingGameEntryData($leagueID, $entry_number);
            }else{
                  $data = self::$fanvictor->getGameEntryData($leagueID, $entry_number);
            }
            $league = $data['league'];
            $aPool = $data['pool'];
            $aFights = $data['fights'];
            $aPlayers = $data['players'];
            $allow_pick_email = $data['allow_pick_email'];
            
            //cur user
            $current_user = wp_get_current_user();
            $user_avatar = self::$fanvictor->get_avatar_url(self::$fanvictor->get_avatar($_COOKIE['fanvictor_user_id'], 32 ));
            //list friend
            $aFriends = self::$fanvictor->getAllPlayerInfo();
            $iTotalFriends = count($aFriends);
            sort($aFriends, SORT_ASC);
            usort($aFriends, function($a, $b){
                $a = strtolower($a['full_name'] ? $a['full_name'] : $a['user_name']);
                $b = strtolower($b['full_name'] ? $b['full_name'] : $b['user_name']);
                return strcmp($a, $b);
            });
            
            //allow show popup
            $showInviteFriends = false;
            $link_contest = FANVICTOR_URL_ENTRY;
            if($league['gameType'] == 'GOLFSKIN'){
                $link_contest = FANVICTOR_URL_GAME;
            }
            if(isset($_SESSION['showInviteFriends'.$leagueID]) && $aPool['status'] == "NEW")
            {
                unset($_SESSION['showInviteFriends'.$leagueID]);
                $showInviteFriends = true;
            }
            include FANVICTOR__PLUGIN_DIR_VIEW.'entry.php';
        }
    }
}
?>