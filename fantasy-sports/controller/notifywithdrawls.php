<?php
class Notifywithdrawls
{
    private static $payment;
    private static $paypal;
    private static $fanvictor;
    public function __construct() 
    {
        self::$payment = new Payment();
        self::$paypal = new Paypal();
        self::$fanvictor = new Fanvictor();
    }
    
	public function process()
	{
        $status = self::$paypal->callback();
        
		if($status == 'completed' || $status == "pending")
		{
			$custom = explode('|', $_POST['custom']);
            $aWithdrawl = self::$payment->getWithdraw($custom[0]);
			$aVals = array('status' => 'APPROVED', 
						   'response_message' => $custom[1],
						   'processedDate' => date('Y-m-d H:i:s'),
						   'transactionID' => $_POST['txn_id']);
			self::$payment->updateWithdraw($custom[0], $aVals);
            self::$fanvictor->sendApplyWithdrawlEmail($custom[0], 'APPROVED');
            if($aWithdrawl != null &&$aWithdrawl['status'] == 'DECLINED')
            {
                self::$payment->updateUserBalance($aWithdrawl['amount'], true, 0, $aWithdrawl['userID']);
                $aUser = self::$payment->getUserData($aWithdrawl['userID']);
                self::$payment->addFundhistory($aWithdrawl['amount'], 0, $aUser['balance'], 'WITHDRAW', 'DEDUCT', $aWithdrawl['userID'], null, 'approved withdrawl');
            }
		}
		else
		{
			redirect(admin_url().'admin.php?page=withdrawls');
		}
	}
}
?>