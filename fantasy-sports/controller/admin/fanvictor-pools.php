<?php
$Fanvictor_Pools = new Fanvictor_Pools();
class Fanvictor_Pools
{
    private static $pools;
    private static $fanvictor;
    private static $sports;
    private static $fighters;
    private static $playerposition;
    private static $url;
    private static $urladdnew;
    private static $urladd;
    public function __construct() 
    {
        self::$pools = new Pools();
        self::$fanvictor = new Fanvictor();
        self::$sports = new Sports();
        self::$fighters = new Fighters();
        self::$playerposition = new PlayerPosition();
        self::$url = admin_url().'admin.php?page=manage-pools';
        self::$urladdnew = admin_url().'admin.php?page=add-pools';
        self::$urladd = wp_get_referer();
    }
    public static function managePools()
    {
        //load css js
        wp_enqueue_style('admin.css', FANVICTOR__PLUGIN_URL_CSS.'admin.css');
        wp_enqueue_style('ui.css', FANVICTOR__PLUGIN_URL_CSS.'ui/ui.css');
        wp_enqueue_script('admin.js', FANVICTOR__PLUGIN_URL_JS.'admin/admin.js');
        wp_enqueue_script('fight.js', FANVICTOR__PLUGIN_URL_JS.'admin/fight.js');
        wp_enqueue_script('ui.js', FANVICTOR__PLUGIN_URL_JS.'ui.js');
        
        //task action delete
        if(isset($_POST["task"]) && $task = $_POST["task"])
        {
            switch($task)
            {
                case "delete":
                    self::delete();
                    break;
                case 'upload':
                    self::upload();
                    break;
            }
        }
        
        include FANVICTOR__PLUGIN_DIR_VIEW.'pools/class.table-pools.php';
        $myListTable = new TablePools();
        $myListTable->prepare_items(isset($_GET['s']) ? $_GET['s'] : null); 
        include FANVICTOR__PLUGIN_DIR_VIEW.'pools/index.php';
    }
    
    public static function addPools()
    {
        //must check that the user has the required capability 
        if (!current_user_can('manage_options'))
        {
            wp_die( __('You do not have sufficient permissions to access this page.') , FV_DOMAIN);
        }
        
        //load css js
        wp_enqueue_style('admin.css', FANVICTOR__PLUGIN_URL_CSS.'admin.css');
        wp_enqueue_style('ui.css', FANVICTOR__PLUGIN_URL_CSS.'ui/ui.css');
        wp_enqueue_script('admin.js', FANVICTOR__PLUGIN_URL_JS.'admin/admin.js');
        wp_enqueue_script('fight.js', FANVICTOR__PLUGIN_URL_JS.'admin/fight.js');
        wp_enqueue_script('ui.js', FANVICTOR__PLUGIN_URL_JS.'ui.js');
        wp_enqueue_script('init_add.js', FANVICTOR__PLUGIN_URL_JS.'admin/init_add.js');
        wp_enqueue_script('accounting.js', FANVICTOR__PLUGIN_URL_JS.'accounting.js');
        
        //edit data
        $bIsEdit = false;
		if (isset($_GET['id']) && $iEditId = $_GET['id'])
		{
            $bIsEdit = true;
            $aForms = self::$pools->getPools($iEditId);
            $aFights = self::$pools->getFights($iEditId);
            $aFights = $aFights == null ? array(array()) : $aFights;
		}
        else
        {
            $aForms = null;
            $aFights = array(null);
        }

        //create valid
		//$oValidator = $this->createValid();
		
        //add or update
		self::modify($bIsEdit);
        
        $aSports = self::$fanvictor->getListSports();
        $aPoolHours = self::$pools->getPoolHours();
        $aPoolMinutes = self::$pools->getPoolMinutes();
        $aPositions = self::$playerposition->getPlayerPosition();
        $aPositions = json_encode($aPositions);
        $aRounds = self::$fighters->getRounds();
        
        include FANVICTOR__PLUGIN_DIR_VIEW.'pools/add.php';
    }

    private static function validData($aVals)
    {
        $sport = self::$sports->getSportById($aVals['organization']);
        if(empty($aVals['poolName']))
        {
            redirect(self::$urladd, 'Provide a name');
        }
        else if($sport == null)
        {
            redirect(self::$urladd, 'Please select organization');
        }
        else if(empty($aVals['startDate']))
        {
            redirect(self::$urladd, 'Provide start date');
        }
        else if(empty($aVals['cutDate']))
        {
            redirect(self::$urladd, 'Provide cut date');
        }
        if($sport[0]['only_playerdraft'] == 0)
        {
            //valid fight
            foreach($aVals['fighterID1'] as $item)
            {
                if(empty($item))
                {
                    redirect(self::$urladd, 'Please select fighter 1');
                }
            }
            foreach($aVals['fighterID2'] as $item)
            {
                if(empty($item))
                {
                    redirect(self::$urladd, 'Please select fighter 2');
                }
            }
            foreach($aVals['fight_name'] as $item)
            {
                if(empty($item))
                {
                    redirect(self::$urladd, 'Provide fixture name');
                }
            }
        }
        return true;
    }
    
    private static function modify()
    {
        if (isset($_POST['val']) && $aVals = $_POST['val'])
		{
			if (self::validData($aVals))
			{
                if(self::$pools->isPoolExist($aVals['poolID'])) //update
                {
                    if (self::$pools->update($aVals))
                    {
                        redirect(self::$urladd, 'Succesfully updated');
                    }
                }
                else //add
                {
                    if (self::$pools->add($aVals))
                    {
                        redirect(self::$url, 'Succesfully added');
                    }
                }
                redirect(self::$urladd, 'Something went wrong! Please try again.');
			}
		}
    }
    
    private static function delete()
	{
        if ($aIds = $_POST['id'])
		{
			$iDeleted = 0;
			foreach ($aIds as $iId)
			{
				if (self::$pools->delete($iId))
				{
					$iDeleted++;
				}
			}
			
			if ($iDeleted > 0)
			{
                redirect(self::$url, 'Succesfully deleted');
			}
		}
        redirect(self::$url);
	}
    private static function upload(){
        if(empty($_FILES)){
            return;
        }
        $aFiles = $_FILES;
        $info = $_POST['info'];
        foreach($aFiles as $key=>$file){
            if($file['error'] >0){
                continue;
            }
            $poolID = explode('_',$key);
            $poolID = $poolID[2];
            if(!isset($info[$poolID])){
                continue;
            }
            // check extension
            $extension = end(explode('.', $file['name']));
            if(strtolower($extension) != 'csv'){
                continue;
            }
            $detailInfo = $info[$poolID];
            $detailInfo = json_decode(stripslashes($detailInfo),true);
            $data['file_name'] = $file['name'];
            $data['poolID'] = $poolID;
            $data['org_id'] = $detailInfo['org_id'];
            $data['startDate'] = $detailInfo['startDate'];
            $tool_url = self::$fanvictor->createFolderCustomSport($data);

            $upload['filename'] = $file['name'];
            $upload['upload_file'] = '@'.$file['tmp_name'];
            $upload['dir_path'] = $tool_url[0];
            $upload['filesize'] = $file['size'];
            self::sendFile($upload);

            
        }
    }
    
    public static function sendFile($file){
    $url = get_option('fanvictor_api_url_admin').'/upload_file.php';
        $headers = array("Content-Type:multipart/form-data"); // cURL headers for file uploading
        $postfields = array("filedata" => $file['upload_file'], "filename" => $file['filename'],'dir_path'=>$file['dir_path']);
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => true,
            CURLOPT_POST => 1,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_INFILESIZE => $file['filesize'],
            CURLOPT_RETURNTRANSFER => true
        ); // cURL options
        curl_setopt_array($ch, $options);
        curl_exec($ch);
    }    
}
?>