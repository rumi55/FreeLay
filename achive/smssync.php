 <?php
 
 header('Content-Type: application/json');	
  
 require_once('dbconn.php');
    
//THIS IS TO HANDLE INCOMING MESSAGE FROM THE APP(PHONE'S MESSAGES) 
if($_REQUEST['message'] && $_REQUEST['from']) {
	
	$from = $_REQUEST['from'];
	$message = htmlspecialchars($_REQUEST['message'],  ENT_QUOTES, 'UTF-8', true);
	
	$gateway = $_REQUEST['phone'];
	$success = "true";
	
	
	//verify 1st that the user has a secret key
	/* not available in envaya */
	if($_REQUEST['secret'] != '') {
		$secret = trim($_REQUEST['secret']);
	}else{
		
		//$success = "false";
		//echo json_encode(array("payload"=>array("success"=>$success)));
		
		$reply[0] = array("to" => $gateway, "message" =>"The gateway wont work unless you have a password inputted.");
			
		echo json_encode(array("payload"=>array("success"=>$success,"task"=>"send","messages"=>array_values($reply))));	
		exit();
	}
	
	
	//see if the user exists [real] or [dummy]
	$i = "SELECT * FROM agent WHERE phone='$gateway'";
	$q = mysqli_query ($dbc, $i) or trigger_error("Query: $i \n<br/>MySQL Error: " . mysqli_error($dbc) );
	$r = mysqli_fetch_array($q, MYSQLI_ASSOC);
	
	if(mysqli_num_rows($q) == 1) { //if the user does exist

		if($r['phone'] != '') {
			$agentphone = $r['phone'];
		}else{
			$agentphone = $gateway;
		}
		
		$in = "INSERT INTO sms(agent_phone, phone, msg, status, created_date) VALUES ('$agentphone', '$from', '$message', '-1', NOW() )";
		$qin = mysqli_query ($dbc, $in) or trigger_error("Query: $in \n<br/>MySQL Error: " . mysqli_error($dbc) );
		
		if(mysqli_affected_rows($dbc) == 1) {
				
			//echo 'SUCCESS!!!!!!!!!!!';
			echo json_encode(array("payload"=>array("success"=>$success)));
			
			//$message  = "SMS from ".$from." has been successfully recieved";
			//echo json_encode(array("error"=>array("message"=>$message)));			
		
		}else{
			
			//$message  = "SMS from ".$from." failed to be stored in the database";
			//echo json_encode(array("error"=>array("message"=>$message)));
			
			echo json_encode(array("payload"=>array("success"=>"false")));
				
		}
	
	}else{//if the user doesnt exist
	
		$agentnumber = $gateway;
		$pswd = $secret; //rand(10000000,99999999);
		$c = $net = $name = $email = $org = 'dummy';
		
		$in = "INSERT INTO agent(phone, user_phone, password, country, operator, name, email, org_name, status) VALUES ('$agentnumber', '$agentnumber', '$pswd', '$c', '$net', '$name', '$email', '$org', 'inactive')";
		$qin = mysqli_query ($dbc, $in) or trigger_error("Query: $in \n<br/>MySQL Error: " . mysqli_error($dbc) );
		
		
		if(mysqli_affected_rows($dbc) == 1) {
			
			$reply[0] = array("to" => $gateway, "message" => "You have been temporarily registered in FreeLay. Please head to http://www.globalgivingcommunity.com/freelay/to finish registration to be able to manage your account such as getting reports and so forth.");
			
			echo json_encode(array("payload"=>array("success"=>"true","task"=>"send","messages"=>array_values($reply))));	
		
		}
		
	}
	
}



//THIS IS THE TASK HANDLER< CHECKING FOR OUTGOING MESSAGED FROM THE SERVER
if($_REQUEST['task']) {
	
	$success = "true";
	$gateway = $_REQUEST['phone'];
	$secret = $_REQUEST['secret'];
	
	//require_once('dbconn.php');
	
	$s = "SELECT * FROM sms WHERE agent_phone LIKE '$gateway%' AND status='0'";
	$q = mysqli_query ($dbc, $s) or trigger_error("Query: $s \n<br/>MySQL Error: " . mysqli_error($dbc));
	
	while($get = mysqli_fetch_array($q, MYSQLI_ASSOC) ) {
		
		//$id = rand(1, 99999);
		$id = $get['msg_id'];
		$sendto = $get['phone'];
		$message = $get['msg'].' - FreeLay';
		
		$reply[] =  array("to"=> $sendto,"message"=>$message);
		
		$u = "UPDATE sms SET status='1' WHERE msg_id='$id'";
		$uq = mysqli_query ($dbc, $u) or trigger_error("Query: $u \n<br/>MySQL Error: " . mysqli_error($dbc));
	
	}
		
	//$message = "This is a test message sent from the server to verify that IKO MESSAGES: ".$gateway." - Envaya";
	
 	echo json_encode(array("payload"=>array("success"=> $success,"task"=>"send", "secret"=> $secret, "messages"=>array_values($reply))));
	exit();
	
}

/*
if($_REQUEST['action'] == 'send_status') {
	
	$id = $_REQUEST['id'];
	$status = $_REQUEST['action'];
	
	$message = "Message ID - ".$id." was sent succesfully.";
	
	echo json_encode(array("events"=>$events = array(array("event"=>"log","message"=> $message)))); //LOG MESSASE
	exit();
	
}
*/
	
?>