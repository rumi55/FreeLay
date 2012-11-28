<?php

include('../controllers/auth.php');

$title = "FreeLay - Generate CSV File";
include_once('config.php');
require_once(MYSQL);

if(isset($_GET['filter']) ) {
	
	$filter = $_GET['filter'];
	
}else{
	
	$filter = 'all';
}

if(isset($_GET['search']) &&  $_GET['search'] != NULL ) {
	
	$search = $_GET['search'];
	
}else{
	
	$search = NULL;
}


//acquire a temporary file
$filename = tempnam(sys_get_temp_dir(), "csv");

$file = fopen($filename,"w"); //open file
$info = 'msg_id, agent_phone, msg,phone, status,created_date'; //REQUIRED FIELDS

$csvfields  = array();


if(isset($_SESSION['symbol']) && isset($_GET['symbol']) ) {
	
$symbol = $_SESSION['symbol'];
$header = array('message ID', 'Phone No.', 'the message', 'recieved from', 'status', 'date created');

$q = "SELECT ".$info." FROM sms WHERE agent_phone='$phone' AND msg LIKE '%$symbol%'";
$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br/>MySQL Error: " . mysqli_error($dbc));

//GET ALL THE LENGTH OF THE ARRAYS
/*while($x = mysqli_fetch_array($r, MYSQLI_ASSOC) ) {
	$count = count(explode($symbol, $x['msg']));
	$allvalues[] = $count;
}*/

while($d = mysqli_fetch_array($r, MYSQLI_ASSOC) ) {
	
	$get = count(explode($symbol, $d['msg']));
	$allvalues[] = $get;
	
	if( ($get > 2) ) {
	
			//CHANGE STATUSES TO READABILITY
			foreach($status as $what => $value ) {
				if($d['status'] == $what ) {
					$stat = $value;
				}
			}
			
			$splits = explode($symbol, $d['msg']);
						
			$results = array($d['msg_id'], $d['agent_phone'], $d['msg'], $d['phone'], $stat.'('.$d['status'].')', $d['created_date']);
			
			$data[] = array_merge($results, $splits);
			
	}
	
}

$max = max($allvalues);

for($i=0; $i < $max; $i++) { $spaces[] = ' '; }

$csvfields[0] = array_merge($header, $spaces);

$name_of_file = 'Reports_parsed_messages_'.date('dmy_g-ia').'.csv';

}else{


// Add header of fields
// this step is optional (more for client view)
$csvfields[0]   = array('message ID', 'agent phone no', 'the message', 'recieved from', 'status', 'date created');


//SEE IF POST HAS BEEN SENT AND GET ALL THE INFORMATION REQUIRED
if(isset($filter) && $filter != 'all' ) {
	
	if($search != NULL) {
	$q = "SELECT ".$info." FROM sms WHERE agent_phone='$phone' AND status='$filter' AND msg LIKE '%$search%' ORDER BY created_date DESC LIMIT 300";
	}else{
		$q = "SELECT ".$info." FROM sms WHERE agent_phone='$phone' AND status='$filter' ORDER BY created_date DESC LIMIT 300";
	}
	
}else{
	
	if($search != NULL) {
	$q = "SELECT ".$info." FROM sms WHERE agent_phone='$phone' AND msg LIKE '%$search%' ORDER BY created_date DESC LIMIT  300";
	}else{
		$q = "SELECT ".$info." FROM sms WHERE agent_phone='$phone' ORDER BY created_date DESC LIMIT 300";
	}
}


//GET ALL THE DATA
$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br/>MySQL Error: " . mysqli_error($dbc));

//Create an array
while($d = mysqli_fetch_array($r, MYSQLI_ASSOC) ) {
	
	if($d['phone'] != 'Safaricom' && $d['phone']  != '+8988' ) { //REMOVE JUNK SMS
	
	//CHANGE STATUSES TO READABILITY
	foreach($status as $what => $value ) {
		
		if($d['status'] == $what ) {
			
			$stat = $value;
			
		}
		
	}
	
	$data[] = array( $d['msg_id'], $d['agent_phone'], $d['msg'], $d['phone'], $stat.'('.$d['status'].')', $d['created_date'] );
	
	//$info = 'msg_id, agent_phone , msg, phone, status, created_date'; //REQUIRED FIELDS
	
	}//remove junk smses
	
}


//JUST FOR FETCHING THE NAME OF THE FILE
if($filter == 'all') {
	
	$whichone = 'All - Unfiltered';
	
}else{

	foreach($status as $what => $value ) {
		
		if($_GET['filter'] == $what ) {
			
			$whichone = str_replace("'", "", $value);
			
		}
		
	}

}

$whichone = str_replace(' ', '', $value);
$whichone = str_replace('-', '', $value);

$name_of_file = 'report_'.$whichone.'_'.date('dmy_g-ia').'.csv';

}

$i = 1;
foreach($data as $row):
  $csvfields[$i] = $row;
$i++;
endforeach;

$delimiter = ',';
$enclose = '"';

foreach ($csvfields as $fields) {
	fputcsv($file, $fields, $delimiter, $enclose);
}
fclose($file);

header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment;Filename=".$name_of_file);

// send file to browser
readfile($filename);
unlink($filename);
?>