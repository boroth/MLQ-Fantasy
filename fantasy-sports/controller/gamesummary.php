<?php
class GameSummary
{
    private static $fanvictor;
    public function __construct() 
    {
        self::$fanvictor = new Fanvictor();
    }
    
	public static function process()
	{       
        add_action('wp_enqueue_scripts', array('GameSummary', 'theme_name_scripts'));
        add_filter('the_content', array('GameSummary', 'addContent'));
	}
    
    public static function theme_name_scripts()
    {
        wp_enqueue_style('style.css', FANVICTOR__PLUGIN_URL_CSS.'style.css');
        wp_enqueue_script('summary.js', FANVICTOR__PLUGIN_URL_JS.'summary.js');
        wp_enqueue_style('font-awesome.css', FANVICTOR__PLUGIN_URL_CSS.'font-awesome/css/font-awesome.css');
    }
    
    public static function addContent()
    {
        if(!in_the_loop())
        {
            return;
        }
        $aUsers = self::$fanvictor->getGamesummary();
        include FANVICTOR__PLUGIN_DIR_VIEW.'gamesummary.php';
    }
}
?>