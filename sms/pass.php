<?php

header('Content-Type: application/json');

//THIS IS TO HANDLE INCOMING MESSAGE FROM THE APP(PHONE'S MESSAGES) 
if($_REQUEST['action'] == 'outgoing') {
	
	$message = "Your Password is: ".$_SERVER['HTTP_X_REQUEST_SIGNATURE'];
	
	echo json_encode(array("events"=>$events = array(array("event"=>"log","message"=> $message)))); //LOG MESSASE
	exit();
	
}

?>