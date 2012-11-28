<?php

header('Content-Type: application/json');

//THIS IS TO HANDLE INCOMING MESSAGE FROM THE APP(PHONE'S MESSAGES) 
if($_REQUEST['action'] == 'incoming') {

	$from = $_REQUEST['from'];
	$message = htmlspecialchars($_REQUEST['message'],  ENT_QUOTES, 'UTF-8', true);
	
	$gateway = $_REQUEST['phone_number'];
	
	require_once('dbconn.php');
	
	$in = "SELECT * FROM agent WHERE phone='$gateway'";
	$qin = mysqli_query ($dbc, $in) or trigger_error("Query: $in \n<br/>MySQL Error: " . mysqli_error($dbc));
	
	if(mysqli_num_rows($qin) == 1) {
		
		$p = "INSERT INTO sms(agent_phone, phone, msg, status, created_date) VALUES ('$gateway', '$from', '$message', '-1', NOW() )";
		$qp = mysqli_query ($dbc, $p) or trigger_error("Query: $p \n<br/>MySQL Error: " . mysqli_error($dbc) );
		
		$message = 'Message successfully inserted into the DB:SMS';
		echo json_encode(array("events"=>$events = array(array("event"=>"log","message"=> $message))));
		
	}else{
		
		$pswd = rand(10000000,99999999);
		$c = $net = $name = $email = $org = 'dummy';
		
		$u = "INSERT INTO agent(phone, user_phone, password, country, operator, name, email, org_name, status) VALUES ('$gateway', '$gateway', '$pswd', '$c', '$net', '$name', '$email', '$org', 'inactive')";
		$qu = mysqli_query ($dbc, $u) or trigger_error("Query: $u \n<br/>MySQL Error: " . mysqli_error($dbc) );
		
		if(mysqli_affected_rows($dbc) == 1) {
			
			$reply[0] = array("to" => $gateway, "message" => "You have been temporarily registered in FreeLay. Please head to http://www.globalgivingcommunity.com/freelay/ to finish registration. Once you are full registered you'll be able to manage your account such as getting reports of your SMS activities, queuing messages and so forth.");
		
		echo json_encode(array("events"=>$events = array(array("event"=>"send","messages"=>array_values($reply)))));
		exit();
	
 		//echo json_encode(array("events"=>$events = array(array("event"=>"log","message"=>"Recieved SMS from:".$from))));
		//LOG MESSASE
		//exit();
		}
		
	}
	
}



//DEALS WITH OUTGOING MESSAGED - QUED MESSAGES
if($_REQUEST['action'] == 'outgoing') {

	$gateway = $_REQUEST['phone_number'];
	
	require_once('dbconn.php');
	
	$s = "SELECT * FROM sms WHERE agent_phone LIKE '$gateway%' AND status='0'";
	$q = mysqli_query ($dbc, $s) or trigger_error("Query: $s \n<br/>MySQL Error: " . mysqli_error($dbc));
	
	while($get = mysqli_fetch_array($q, MYSQLI_ASSOC) ) {
		
		$id = rand(1, 99999);
		//$id = $get['msg_id'];
		$message = $get['msg'].' - Sent from FreeLay';
		$sendto = $get['phone'];
		
		$reply[] =  array("id"=>"$id","to"=> $sendto,"message"=>$message);
	
	}
		
	//$message = "This is a test message sent from the server to verify that IKO MESSAGES: ".$gateway." - Envaya";
	
 	echo json_encode(array("events"=>$events = array(array("event"=>"send","messages"=>array_values($reply)))));
	exit();
	
}


if($_REQUEST['action'] == 'send_status') {
	
	$id = $_REQUEST['id'];
	$status = $_REQUEST['action'];
	
	$message = "Message ID - ".$id." was sent succesfully.";
	
	echo json_encode(array("events"=>$events = array(array("event"=>"log","message"=> $message)))); //LOG MESSASE
	exit();
	
}
	
	