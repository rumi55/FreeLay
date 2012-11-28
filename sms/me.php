<?php

include_once('../controllers/config.php');
require_once(MYSQL);

if(isset($_GET['set']) ) {
/*
$userno = '+254723001575';
$pswd = 'strawberry';
$c = 'Kenya';
$net = 'Safaricom';
$name = 'Eugene Mutai';
$email = 'jhenetic@gmail.com';
$org =  'FreeLay';

$in = "INSERT INTO agent(phone, user_phone, password, country, operator, name, email, org_name) VALUES ('$userno', '$userno', '$pswd', '$c', '$net', '$name', '$email', '$org')";
$qin = mysqli_query ($dbc, $in) or trigger_error("Query: $in \n<br/>MySQL Error: " . mysqli_error($dbc) );
*/

$agentphone = '+254723001575';
$from = $rand(1,10);
$message = "THis are messages used entirely on Testing the SMS Gateway by Jhene";
	
for ($i=1; $i<=50; $i++) {
		
		$sms = "INSERT INTO sms(agent_phone, phone, msg, status, created_date) VALUES ('$agentphone', '$from', '$message', '0', NOW() )";
		$q = mysqli_query ($dbc, $sms) or trigger_error("Query: $sms \n<br/>MySQL Error: " . mysqli_error($dbc) );
		
}

if(mysqli_affected_rows($dbc) >= 1) {
		
		echo 'SUCCESS!!!!!!!!!!!';
		
}

}



if(isset($_GET['del']) ) {
	
$userno = '+254723001575';
$pswd = 'strawberry';
	
$in = "DELETE FROM agent WHERE phone = '$userno' AND password='$pswd'";

$qin = mysqli_query ($dbc, $in) or trigger_error("Query: $in \n<br/>MySQL Error: " . mysqli_error($dbc) );

if(mysqli_affected_rows($dbc) == 1) {
		
		echo 'DELETED!!!!!!!!!!!';
		
}

}



?>