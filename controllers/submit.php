<?php

include('auth.php');
include_once('config.php');

$no_of_var = 0;

if($_REQUEST['submit'] == 'true') {
	
	require(MYSQL);
	
	$csv = $_REQUEST['csv'];
	
	$file_handle = fopen($csv, 'r');
	
	while (!feof($file_handle) ) {

		$line_of_text = fgetcsv($file_handle, 1024); //open the CSV File and read it
		
		foreach($line_of_text as $var) {
			
			$no_of_var++;
			
			}

		if($no_of_var >= 2) {
			
			//getwhich one can be the phone number number
			foreach($line_of_text as $text) {
				
				str_replace("+", "", $text);
				str_replace('\"', '"', stripslashes($text));
				
				if(preg_match('/^\+?[0-9]{6,15}+$/', $text) ) {
					
					$recieversphone = $text;
				}
				
				//now acquire which one can be the message	
				$wording = count(explode(" ",$text) );
					
				if($wording > 3){
					
					$message = $text;
					
				}
				
			}
		
		}
		
		if($recieversphone != $_SESSION['num']) {
		
			if(preg_match('/^\+?[0-9]{6,15}+$/', $recieversphone) ) {
				
				str_replace('\"','"', $message);
			
				$q = "INSERT INTO sms(agent_phone, phone, msg, status, created_date) VALUES('$phone', '$recieversphone', '$message', '0', NOW())";
				
				$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br/>MySQL Error: " . mysqli_error($dbc));
				
				$_SESSION['msg'] = $message; $_SESSION['num'] =$recieversphone;
				
			}
		
		}
		
	}
	
	if (mysqli_affected_rows($dbc) >=1 ) {
			
			$_SESSION['databased'] = '<span class="noerr" style="font-weight:bold"> Your Message(s) have been queud!</span>';
			header('Location:'.BASE_URL.'views/quer.php?csvdb=submitted');
			
		}
	
}

?>