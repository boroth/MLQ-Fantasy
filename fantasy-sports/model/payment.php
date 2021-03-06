<?php
include_once("paypal.php");
define('PAYPAL', 'PAYPAL');
define('CHOICE', 'CHOICE');
define('PAYPAL_PRO', 'PAYPAL_PRO');
class Payment
{
    function validEmail($email)
    {
        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
        if (preg_match($regex, $email)) {
            return true;
        } else { 
            return false;
        } 
    }
    
    function isGatewayExist($data)
    {
        $gateway = $this->viewGateway();
        $gateway[] = PAYPAL_PRO;
        if(in_array($data, $gateway))
        {
            return true;
        }
        return false;
    }
    
    function viewGateway()
    {
        $gateway = array();
        if(get_option('paypal_email_account') != null)
        {
            $gateway[] = PAYPAL;
        }
        if(get_option('fanvictor_choice_account') != null)
        {
            $gateway[] = CHOICE;
        }
        return $gateway;
    }
    
    function feePercentage($value)
    {
        $fee = get_option('fanvictor_fee_percentage');
        if($fee > 0)
        {
            $value = $value + round(($value * $fee / 100), 2);
        }
        return $value;
    }
    
    function changeCashToCredit($iCash)
    {
        $money = 1;
        $credit = get_option('fanvictor_credit_to_cash');
        return $iCash * $credit;
    }
    
    function changeCreditToCash($iCredit)
    {
        $money = 1;
        $credit = get_option('fanvictor_cash_to_credit');
        
        return $iCredit * $credit;
    }
    
    function onlineTransaction($gateway = PAYPAL, $aSettings, $fundshistoryID = null)
    {
        global $wpdb;
        switch($gateway)
        {
            case PAYPAL:
                $paypal = new Paypal();
                return $paypal->parseData($aSettings);
                break;
            case CHOICE:/*TODO, add new payment*/
            	include_once("choice.php");
                $pm = new gwapi();
                
                $dat=$_POST["dp"];
                $dat['ccexp']=str_replace("/","",$dat['ccexp']);
                $acc=explode("/", get_option('fanvictor_choice_account'));
                
				if($acc[0] AND $acc[1])
                	//$_SESSION['dp_acc']=$acc;
					$pm->setLogin($acc[0], $acc[1]);
                $pm->setBilling($dat['fname'],$dat['lname'],$dat['company'],$dat['address'],"", $dat['city'],
        $dat['state'],$dat['zip'],$dat['country'],$dat['phone'],$dat['fax'],$dat['email']);
				//$pm->setShipping("Mary","Smith","na","124 Shipping Main St","Suite Ship", "Beverly Hills", "CA","90210","US","support@example.com");
				$pm->setOrder("685".time(),$aSettings["item_name"],1, 2);

				$r = $pm->doSale($aSettings['amount'],$dat['cc'],$dat['ccexp']);
				//[response] => 1 [responsetext] => SUCCESS [authcode] => 123456 [transactionid] => 2619420359 [avsresponse] => N [cvvresponse] => [orderid] => 6851426813066 [type] => sale [response_code] => 100)
				if($pm->responses['response_code']==100){
					$custom = explode('|', $aSettings['custom']);
					$fundshistoryID = $custom[1];
					
					$this->updateUserBalance($custom[2], null, 0, $custom[0]);
					$x=$this->updateFundhistory($fundshistoryID, array('transactionID' => $pm->responses['transactionid'], 'is_checkout' => 1), $custom[0], "completed");
				}
                else
                {
                    $custom = explode('|', $aSettings['custom']);
					$fundshistoryID = $custom[1];
					
					$this->updateUserBalance($custom[2], null, 0, $custom[0]);
                    $this->updateFundhistory($fundshistoryID, array('transactionID' => $pm->responses['transactionid'], 'is_checkout' => 0), $custom[0], "failed");
                }
                if($pm->responses['responsetext'] == "SUCCESS")
                {
                    return __("Successfully transaction", FV_DOMAIN);
                }
                else 
                {
                    return __("Something went wrong. Please try again", FV_DOMAIN);
                }
                break;
            case PAYPAL_PRO:
                $paypal = new PaypalPro();
                $result = $paypal->checkout($aSettings);
                if($result['success'] == 1)
                {
                    $this->updateUserBalance($aSettings['AMT'], false, 0, $_COOKIE['fanvictor_user_id']);
                    $this->updateFundhistory($fundshistoryID, array(
                        'transactionID' => $result['transactionID'], 
                        'is_checkout' => 0), $_COOKIE['fanvictor_user_id'], "completed");
                    return true;
                }
                else if($result['success'] == 0 && !empty($result['message']))
                {
                    return $result['message'];
                }
                break;
            default :
                return false;
        }
        return false;
    }
    
    function confirmPaypal()
    {
        //Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID
        if(isset($_GET["token"]) && isset($_GET["PayerID"]))
        {
            //we will be using these two variables to execute the "DoExpressCheckoutPayment"
            //Note: we haven't received any payment yet.

            $token = $_GET["token"];
            $payer_id = $_GET["PayerID"];

            //get session variables
            $ItemName 			= $_SESSION['ItemName']; //Item Name
            $ItemPrice 			= $_SESSION['ItemPrice'] ; //Item Price
            $ItemNumber 		= $_SESSION['ItemNumber']; //Item Number
            $ItemDesc 			= $_SESSION['ItemDesc']; //Item Number
            $ItemQty 			= $_SESSION['ItemQty']; // Item Quantity
            $ItemTotalPrice 	= $_SESSION['ItemTotalPrice']; //(Item Price x Quantity = Total) Get total amount of product; 

            $padata = 	'&TOKEN='.urlencode($token).
                        '&PAYERID='.urlencode($payer_id).
                        '&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").

                        //set item info here, otherwise we won't see product details later	
                        '&L_PAYMENTREQUEST_0_NAME0='.urlencode($ItemName).
                        '&L_PAYMENTREQUEST_0_NUMBER0='.urlencode($ItemNumber).
                        '&L_PAYMENTREQUEST_0_DESC0='.urlencode($ItemDesc).
                        '&L_PAYMENTREQUEST_0_AMT0='.urlencode($ItemPrice).
                        '&L_PAYMENTREQUEST_0_QTY0='. urlencode($ItemQty).
                        '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ItemTotalPrice).
                        '&PAYMENTREQUEST_0_AMT='.urlencode($ItemTotalPrice).
                        '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode('USD');
            
            $paypal = new MyPayPal(Phpfox::getParam('fanvictor.fanvictor_paypal_username'),
                                   Phpfox::getParam('fanvictor.fanvictor_paypal_password'),
                                   Phpfox::getParam('fanvictor.fanvictor_paypal_signature'),
                                   Phpfox::getParam('fanvictor.paypal_test'));
            return $paypal->confirm($padata);
        }
        return false;
    }
    
    ###########################
	#
	#       USER
	#
	###########################
    function getUserData($userID = null)
    {
        $user_id = (int)$_COOKIE['fanvictor_user_id'];
        if((int)$userID > 0)
        {
            $user_id = $userID;
        }
        
        global $wpdb;
        $table_user = $wpdb->prefix.'users';
        $table_user_extended = $wpdb->prefix.'user_extended';
        $sCond = "WHERE u.ID = ".$user_id;
        $sql = "SELECT u.*, u.display_name as full_name, u.user_email as email, u.user_login as user_name, IFNULL(ue.balance, 0.00) as balance "
             . "FROM $table_user u "
             . "LEFT JOIN $table_user_extended ue ON ue.user_id = u.ID "
             . $sCond;
        $data = $wpdb->get_row($sql, ARRAY_A);
        return $data;
    }
    
    public function isUserExtendedExist($user_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix.'user_extended';
        $sCond = "WHERE user_id = ".(int)$user_id;
        $sql = "SELECT COUNT(*) "
             . "FROM $table_name "
             . $sCond;
        $data = $wpdb->get_var($sql);
        if($data == 1)
        {
            return true;
        }
        return false;
    }
    
    public function isUserEnoughMoneyToJoin($prize = 0, $leagueID = null, $entry_number = 1)
    {
        
        if($this->isMakeBetForLeague($leagueID, $entry_number))
        {
            return true;
        }
        $user = $this->getUserData();
        if((int)$user['balance'] < $prize)
        {
            return false;
        }
        return true;
    }
    
    public function updateUserBalance($prize = 0, $decrease = false, $leagueID = 0, $user_id = null)
    {
        global $wpdb;
        
        $user = $this->getUserData($user_id);
        $deposit = $user['balance'] + $prize;
        if($decrease)
        {
            $deposit = $user['balance'] - $prize;
        }
        
        $values = array('user_id' => $user['ID'], 'balance' => $deposit);
        $table_name = $wpdb->prefix.'user_extended';
        if($this->isUserExtendedExist($user['ID']))
        {
            return $wpdb->update($table_name, $values, array('user_id' => $user['ID']));
        }
        else 
        {
            $result = $wpdb->insert($table_name, $values);
            if($result === false)
            {
                return false;
            }
            return true;
        }
    }
    
    public function isUserPaymentInfoExist($aVals)
    {
        global $wpdb;
        $sCond = "WHERE user_id = ".$_COOKIE['fanvictor_user_id'];
        $table_name = $wpdb->prefix."user_payment";
        $sql = "SELECT user_id "
             . "FROM $table_name "
             . $sCond;
        $aData = $wpdb->get_row($sql);
        if(count($aData) == 1)
        {
            return true;
        }
        return false;
    }
    
    function getUserPaymentInfo($gateway = PAYPAL, $user_id = null)
    {
        global $wpdb;
        $sCond = "WHERE up.user_id = ".(int)$_COOKIE['fanvictor_user_id']." AND up.gateway = '$gateway'";
        if((int)$user_id > 0)
        {
            $sCond = "WHERE up.user_id = ".(int)$user_id;
        }
        $table_userpayment = $wpdb->prefix."user_payment";
        $table_user = $wpdb->prefix."users";
        $table_userextended = $wpdb->prefix."user_extended";
        $sql = "SELECT up.*, u.display_name as full_name, IFNULL(ue.balance, 0.00) as balance "
             . "FROM $table_userpayment up "
             . "INNER JOIN $table_user u ON up.user_id = u.ID "
             . "LEFT JOIN $table_userextended ue ON ue.user_id = u.ID "
             . $sCond;
        $data = $wpdb->get_row($sql);
        $data = json_decode(json_encode($data), true);
        return $data;
    }
    
    public function addUserPaymentInfo($aVals)
    {
        global $wpdb;
        $aVals['user_id'] = $_COOKIE['fanvictor_user_id'];
        $aVals['time_stamp'] = current_time('timestamp');
        $aVals['time_update'] = current_time('timestamp');
        return $wpdb->insert($wpdb->prefix."user_payment", $aVals);
    }
    
    public function updateUserPaymentInfo($aVals)
    {
        global $wpdb;
        $aVals['time_update'] = current_time('timestamp');
        return $wpdb->update($wpdb->prefix."user_payment", $aVals, array('user_id' => $_COOKIE['fanvictor_user_id']));
    }
    
    ###########################
	#
	#       FUNDHISTORY
	#
	###########################
    public function isMakeBetForLeague($leagueID, $entry_number = 1)
    {
        global $wpdb;
        $sCons = "WHERE userID = ".$_COOKIE['fanvictor_user_id']." AND leagueID = ".(int)$leagueID." AND entry_number = ".$entry_number;
        $table_name = $wpdb->prefix.'fundhistory';
        $sql = "SELECT count(*) "
             . "FROM $table_name "
             . $sCons;
        $data = $wpdb->get_var($sql);
        if($data > 0)
        {
            return $data;
        }
        return false;
    }
    
    public function isPaypalCompleted($fundshistoryID)
    {
        global $wpdb;
        $sCons = "WHERE userID = ".$_COOKIE['fanvictor_user_id']." AND transactionID != '' AND fundshistoryID = ".(int)$fundshistoryID;
        $table_name = $wpdb->prefix.'fundhistory';
        $sql = "SELECT count(*) "
             . "FROM $table_name "
             . $sCons;
        $data = $wpdb->get_var($sql);
        if($data == 1)
        {
            return true;
        }
        return false;
    }
	
	function checkMoneyWonAdded($user_id, $amount, $operation, $type, $leagueID, $entry_number = 1)
    {
        global $wpdb;
        $sCons = "WHERE userID = $user_id AND amount = '".$amount."' AND operation = '".$operation."' AND type = '".$type."' AND leagueID = ".$leagueID." AND entry_number = $entry_number ";
        $table_name = $wpdb->prefix.'fundhistory';
        $sql = "SELECT count(*) "
             . "FROM $table_name "
             . $sCons;
        $data = $wpdb->get_var($sql);
        if($data == 1)
        {
            return true;
        }
        return false;
    }
    
    public function getFundhistory($aConds, $sSort = 'fundshistoryID DESC', $iPage = '', $iLimit = '')
	{	
        global $wpdb;
        $table_fundhistory = $wpdb->prefix."fundhistory";
        $aConds .= 'userID = '.(int)$_COOKIE['fanvictor_user_id'];
        $sCond = $aConds != null ? "WHERE ".$aConds : '';
        $sql = "SELECT COUNT(*) "
             . "FROM $table_fundhistory "
             . $sCond;
        $iCnt = $wpdb->get_var($sql);

        $sCond = $aConds != null ? "WHERE ".$aConds : '';
        $sql = "SELECT *, DATE_FORMAT(date, '%Y-%m-%d') as date "
             . "FROM $table_fundhistory "
             . $sCond." "
             . "ORDER BY $sSort "
             . "limit $iPage, $iLimit ";
        $aRows = $wpdb->get_results($sql);
        $aRows = json_decode(json_encode($aRows), true);
        
        return array($iCnt, $aRows);
	}
    
    public function parseFunhistoryData($aDatas = null)
    {
		// get list league
        $listLeagueID = array();
        foreach($aDatas as $item)
        {
            $listLeagueID[] = $item['leagueID'];

        }
        if($listLeagueID)
        {
            require_once'fanvictor.php';
            $fanvictor = new Fanvictor();
            $aListNameLeague = $fanvictor->getLeagueName($listLeagueID);
        }
		
        if($aDatas != null)
        {
            foreach($aDatas as $k => $aData)
            {
				$nameContest = '--';
                if(isset($aListNameLeague[$aData['leagueID']]))
				{
                    $nameContest = $aListNameLeague[$aData['leagueID']];
				}
                $aDatas[$k]['name_contest'] = $nameContest;
                if($aData['operation'] == 'ADD')
                {
                    $aDatas[$k]['amount'] = "+".$aData['amount'];
                }
                else if($aData['operation'] == 'DEDUCT')
                {
                    $aDatas[$k]['amount'] = "-".$aData['amount'];
                }
                if($aData['type'] == 'MAKE_BET')
                {
                    $aDatas[$k]['type'] = "ENTRY FEE";
                }
            }
        }
        return $aDatas;
    }
    
    public function addFundhistory($prize = 0, $leagueID = 0, $newBalance = 0, $type, $operation, $user_id = null, $gateway = null, $reason = null, $changeRate = null, $status = null, $entry_number = 1)
    {
        global $wpdb;
        if($prize > 0)
        {
            $userID = (int)$_COOKIE['fanvictor_user_id'];
            if((int)$user_id > 0)
            {
                $userID = $user_id;
            }
            
            //site profit
            $payout_percentage = get_option('fanvictor_winner_percent');
            $site_profit = $prize * (100 - $payout_percentage) / 100;
            
            $values = array('userID' => $userID, 
                            'amount' => $prize,
                            'operation' => $operation,
                            'type' => $type,
                            'new_balance' => $newBalance,
                            'gateway' => $gateway,
                            'reason' => $reason,
                            'cash_to_credit' => $changeRate,
                            'leagueID' => $leagueID,
							'entry_number' => $entry_number,
                            'date' => date('Y-m-d H:i:s'),
                            'site_profit' => $site_profit);
            if($status != null)
            {
                $values['status'] = $status;
            }
            $table_name = $wpdb->prefix.'fundhistory';
            $wpdb->insert($table_name, $values);
            return $wpdb->insert_id;
        }
        return 0;
    }
    
    public function updateFundhistory($iId, $aValues, $user_id = null, $status = '')
    {
        global $wpdb;
        $iUserId = $_COOKIE['fanvictor_user_id'];
        if((int)$user_id > 0)
        {
            $iUserId = $user_id;
        }
        $user = $this->getUserData($iUserId);
        $aValues['new_balance'] = $user['balance'];
        $aValues['status'] = $status;
        return $wpdb->update($wpdb->prefix.'fundhistory', $aValues, array('fundshistoryID' => (int)$iId));
    }
    
    public function deleteFundhistory($iId)
    {
        return $this->database()->delete(Phpfox::getT('fundhistory'), 'fundshistoryID = '.(int)$iId);
    }
    
    ###########################
	#
	#       WITHDRAWLS
	#
	###########################
    public function isAllowWithdraw($amount = 0, $user_id = null)
    {
        $aUser = $this->getUserData($user_id);
        if($aUser['balance'] >= $amount)
        {
            return true;
        }
        return false;
    }
    
    public function getWithdraw($iId = null)
    {
        global $wpdb;
        $table_name = $wpdb->prefix.'withdrawls';
        $sCond = "WHERE userID = ".$_COOKIE['fanvictor_user_id'];
        if((int)$iId > 0)
        {
            $sCond = "WHERE withdrawlID = ".$iId;
        }
        $sql = "SELECT *, DATE_FORMAT(requestDate, '%Y-%m-%d') as requestDate, DATE_FORMAT(processedDate, '%Y-%m-%d') as processedDate "
             . "FROM $table_name "
             . $sCond." "
             . "ORDER BY withdrawlID DESC ";
        if((int)$iId > 0)
        {
            $data = $wpdb->get_row($sql);
        }
        else
        {
            $data = $wpdb->get_results($sql);
        }
        $data = json_decode(json_encode($data), true);
        return $data;
    }
    
    public function getListWithdraw($aConds, $sSort = 'withdrawlID DESC', $iPage = '', $iLimit = '')
	{	
        global $wpdb;
        $table_name = $wpdb->prefix."withdrawls";
        $aConds .= 'userID = '.(int)$_COOKIE['fanvictor_user_id'];
        $sCond = $aConds != null ? "WHERE ".$aConds : '';
        $sql = "SELECT COUNT(*) "
             . "FROM $table_name "
             . $sCond;
        $iCnt = $wpdb->get_var($sql);

        $sCond = $aConds != null ? "WHERE ".$aConds : '';
        $sql = "SELECT *, DATE_FORMAT(requestDate, '%Y-%m-%d') as requestDate, DATE_FORMAT(processedDate, '%Y-%m-%d') as processedDate "
             . "FROM $table_name "
             . $sCond." "
             . "ORDER BY $sSort "
             . "limit $iPage, $iLimit ";
        $aRows = $wpdb->get_results($sql);
        $aRows = json_decode(json_encode($aRows), true);
        
        return array($iCnt, $aRows);
	}
    
    public function addWithdraw($amount = 0, $reson = null, $user_id = null, $new_balance = 0)
    {
        global $wpdb;
        $userID = $_COOKIE['fanvictor_user_id'];
        if((int)$user_id > 0)
        {
            $userID = $user_id;
        }
        $values = array('userID' => $userID, 
                        'amount' => $amount,
                        'real_amount' => $this->changeCashToCredit($amount),
                        'credit_to_cash' => get_option('fanvictor_credit_to_cash'), 
                        'new_balance' => $new_balance,
                        'reason' => $reson,
                        'requestDate' => date('Y-m-d H:i:s'));
        $wpdb->insert($wpdb->prefix.'withdrawls', $values);
        return $wpdb->insert_id;
    }
    
    public function updateWithdraw($iId, $aValues)
    {
        global $wpdb;
        return $wpdb->update($wpdb->prefix."withdrawls", $aValues, array('withdrawlID' => (int)$iId));
    }
}
?>