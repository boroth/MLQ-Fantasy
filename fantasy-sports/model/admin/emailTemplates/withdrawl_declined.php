<?php
$message = '
<p>Sorry! Your request withdrawal has been declined. Your request money was returned to your account.</p>

<p>Request Date: '.date('M d Y' , strtotime($withdrawl['requestDate'])).'</p>
<p>Response Date: '.date('M d Y' , strtotime($withdrawl['processedDate'])).'</p>
<p>Amount: '.$withdrawl['real_amount'].'</p>';
if(!empty($withdrawl['response_message']))
{
    $message .=
'<p>Message: '.$withdrawl['response_message'].'</p>';
}
$message .= '
<p>Please go to <a href="'.FANVICTOR_URL_TRANSACTIONS.'" target="_blank">this link</a> to check.</p>';
?>