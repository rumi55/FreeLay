<?php

include_once('../controllers/config.php');
require_once(MYSQL);


$extra = $_GET['filter'];
unset($_GET);

$q = "SELECT * FROM sms WHERE agent_phone='$phone' AND status='$extra' ORDER BY created_date DESC LIMIT 100";
$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br/>MySQL Error: " . mysqli_error($dbc));

while($res = mysqli_fetch_array($r, MYSQLI_ASSOC) ) {
	
	
	if($res['phone'] != 'Safaricom' && $res['phone']  != '+8988' ) { //REMOVE JUNK SMS
	
		if(preg_match('/^(\+)?([0-9]+){10,15}/', $res['phone'] ) ) {
			
			if(strlen($res['phone']) <= 10 ) { //shorten according to the length of the phone no
				$no  = substr($res['phone'], 0, 7).'xxx';
			}else{
				$no = substr($res['phone'],0, 10).'xxx';
			}
			
		}else{
			$no = $res['phone'];
			//do nothing
		}
	
	//CHANGE STATUSES TO READABILITY
	foreach($status as $what => $value ) {
		
		if($res['status'] == $what ) {
			
			$stat = $value;
			
		}
		
	}
	
	//CLEAN MESSAGES
	rtrim(stripslashes($res['msg']));
	
	//DISPLAY THE FINAL RESULTS AFTER TABLING
	echo '<table class="report"><tr>';
	echo '<td>'.$res['msg_id'].'</td><td class="msg">'.$res['msg'].'</td><td>'.$no.'<td>'.$res['created_date'].'</td><td>'.$stat.'</td>';
	echo '</tr></table>';
	
	}//display only valid messages
	
}
