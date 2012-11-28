<?php

include_once('controllers/config.php');

// Connection to the database

define('DB_HOST', 'localhost'); //host

define('DB_USER', 'globamh1'); //database username

define('DB_PASSWORD', 'ission~1X'); //password

define('DB_NAME', 'globamh1_gg'); //database name

// Make the connection:

$dbc = mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (!$dbc) {

trigger_error ('Could not connect to MySQL: ' . mysqli_connect_error() );

}


//THIS IS TO HANDLE INCOMING MESSAGE FROM THE APP(PHONE'S MESSAGES) 
if(isset($_REQUEST['from']) && !empty($_REQUEST['message']) ) {
	
	$userno = $_REQUEST['sent_to']; //THE MOBILE PHONE NUMBER
	$from = $_REQUEST['from'];
	
	//verify 1st that the user has a secret key
	
	if($_REQUEST['secret'] != '') {
		$secret = $_REQUEST['secret'];
	}else{
		header('Content-type: application/json');
		$reply[] = array("to" => $userno, "message" => "You have not set a secret key in SyncSMS, please ensure that you enter one. - Freelay");
		echo json_encode(array("payload"=>array("success"=>"true","task"=>"send","messages"=>array_values($reply))));
		exit();
	}
		
	
	//see if the user exists [real] or [dummy]
	$i = "SELECT * FROM agent WHERE password='$secret'";
	$q = mysqli_query ($dbc, $i) or trigger_error("Query: $i \n<br/>MySQL Error: " . mysqli_error($dbc) );
	$r = mysqli_fetch_array($q, MYSQLI_ASSOC);
	
	if(mysqli_num_rows($q) == 1) { //if the user does exist
	
	header('Content-type: application/json');
	
			if($r['phone'] != NULL) {
				$agentphone = $r['phone'];
			}else{
				$reply[] = array("to" => $userno, "message" => "No admin phone number existent, head to  http://www.globalgivingcommunity.com/freelay/ to complete registration - Freelay");
				echo json_encode(array("payload"=>array("success"=>"true","task"=>"send","messages"=>array_values($reply))));
				exit();
			}
			
			$msg = htmlspecialchars($_REQUEST['message'],  ENT_QUOTES, 'UTF-8', true);

			$in = "INSERT INTO sms(agent_phone, phone, msg, status, created_date) VALUES ('$agentphone', '$from', '$msg', '-1', NOW() )";
			$qin = mysqli_query ($dbc, $in) or trigger_error("Query: $in \n<br/>MySQL Error: " . mysqli_error($dbc) );
			
			if(mysqli_affected_rows($dbc) == 1) {
				
				//echo 'SUCCESS!!!!!!!!!!!';
				echo json_encode(array("payload"=>array("success"=>"true")));
				
			}else{
				
				//$reply[] = array("to" => '+254723001575', "message" => "Failed to send one of the incoming messages");
				//echo json_encode(array("payload"=>array("success"=>"true","task"=>"send","messages"=>array_values($reply))));
				
				echo json_encode(array("payload"=>array("success"=>"false"))); //if not databased
				
			}
	
	}else{//if the user doesnt exist
		
		$pswd = $secret;
		$c = $net = $name = $email = $org =  'dummy';
		
		$in = "INSERT INTO agent(phone, user_phone, password, country, operator, name, email, org_name, status) VALUES ('$userno', '$userno', '$pswd', '$c', '$net', '$name', '$email', '$org', 'inactive')";
		$qin = mysqli_query ($dbc, $in) or trigger_error("Query: $in \n<br/>MySQL Error: " . mysqli_error($dbc) );
		
		
		if(mysqli_affected_rows($dbc) == 1) {
			
			header('Content-type: application/json');
			
			$reply[] = array("to" => $userno, "message" => "You have been temporarily registered in FreeLay. Please head to http://www.globalgivingcommunity.com/freelay/ to be able to manage your account such as getting reports and so forth.");
			
			echo json_encode(array("payload"=>array("success"=>"true","task"=>"send","messages"=>array_values($reply))));
		
		}
		
	}
	
	exit();
	
}


//THIS IS THE TASK HANDLER< CHECKING FOR OUTGOING MESSAGED FROM THE SERVER
if(isset($_REQUEST['task']) ) {
	
	$success = "true";
	$secret = $_REQUEST['secret'];
	$userno = $_REQUEST['sent_to']; //THE MOBILE PHONE NUMBER
	
	$i = "SELECT * FROM agent WHERE password = '$secret'";
	$q = mysqli_query ($dbc, $i) or trigger_error("Query: $i \n<br/>MySQL Error: " . mysqli_error($dbc) );
	$r = mysqli_fetch_array($q, MYSQLI_ASSOC);
	
	if($r['phone'] != NULL) {
				$agentphone = $r['phone'];
			}else{
				$reply[] = array("to" => $userno, "message" => "No admin phone number existent, head to  http://www.globalgivingcommunity.com/freelay/ to complete registration - Freelay");
				echo json_encode(array("payload"=>array("success"=>"true","task"=>"send","messages"=>array_values($reply))));
				exit();
			}
		
	$o = "SELECT * FROM sms WHERE agent_phone = '$agentphone' AND status = '0' AND delivery_date < NOW() LIMIT 50";
	$qout = mysqli_query ($dbc, $o) or trigger_error("Query: $o \n<br/>MySQL Error: " . mysqli_error($dbc));
	
	if(mysqli_num_rows($qout) >= 1) {
		
		/*while($out = mysqli_fetch_array($qout , MYSQLI_ASSOC) ) {
			
			$id = $out['msg_id'];
			$sendto = $out['phone']; 
			$inspiration = htmlspecialchars_decode($out['msg'],  ENT_QUOTES,'UTF-8', true);
						
			$reply[] = array("to" => $sendto, "message" => $inspiration);
				
			$u = "UPDATE sms SET status='1' WHERE id='$id'";
			$uq = mysqli_query ($dbc, $u) or trigger_error("Query: $u \n<br/>MySQL Error: " . mysqli_error($dbc));
	
		}*/
		
		$failure = 'Oops! Task Checking Actually is working I wonder what\'s the Problem '.$ego;
		$reply[0] = array("to" =>"+254723001575", "message" => $failure);
		
		header('Content-Type: application/json');
			
		echo json_encode(array("payload"=>array("task"=>"send", "secret"=> $secret, "messages"=>array_values($reply))));
		
	}
	
}