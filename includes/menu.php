<ul class="userinfo">
<li>Phone: <?php echo $phone; ?></li>
<li>Country: <?php echo $country; ?></li>
</ul>

<a id="logout" href="../controllers/logout.php"> Log Out</a>


<ul class="menu">
<li id="reply"><a href="../views/reply.php"></a></li>
<li id="queu"><a href="../views/quer.php"></a></li>
<li id="scheduler"><a href="../views/scheduler.php"></a></li>
<li id="reports"><a href="../views/reports.php"></a></li>
<li id="gap"></li>
<li id="infospace"><div class="infospace">
<p><?php echo $title; ?></p>
<p><?php 

if(isset($_SESSION['notuploaded'])) { echo $_SESSION['notuploaded']; $_SESSION['notuploaded'] = array(); } 

if(isset($_GET['csvdb'])) { echo $_SESSION['databased']; $_SESSION['databased'] = array(); }

if(isset($_SESSION['scherror'])) { echo $_SESSION['scherror']; $_SESSION['scherror'] = array(); }

if(isset($_SESSION['discard'])) { echo $_SESSION['discard']; $_SESSION['discard'] = array(); }

if(!empty($response) ){ echo $response; unset($response);}

if($title == "FreeLay - Reports") {
	
if(isset($_GET['symbol']) &&  $_GET['symbol'] != NULL ) {

$count = 0;
$parsed = array();

$symbol = $_GET['symbol'];	
$n = "SELECT  COUNT(*) FROM sms WHERE agent_phone='$phone' AND msg LIKE '%$symbol%'";
$qn =  mysqli_query ($dbc, $n) or trigger_error("Query: $q\n<br/>MySQL Error: " . mysqli_error($dbc));
$num = mysqli_fetch_row($qn); //no of rows returned

while($r = mysqli_fetch_array($qn, MYSQLI_ASSOC)) {
	$parsed[] = $r['msg'];
}

foreach($parsed as $p) {
	
	$done  = count(explode($symbol, $p));
	
	if($done > 1) {
		$count++;
	}

}

echo	 '<span class="noerr" style="font-weight:bold">Parsed Messages: '.$count.'</span><br />';

}else{

	
//START OF PAGINATION
if(isset($filter) && $filter != 'all' ) {
	
	if($search != NULL) {
		$sql = "SELECT COUNT(*) FROM sms WHERE agent_phone='$phone' AND status='$filter' AND msg LIKE '%$search%'";
	  }else{
		$sql = "SELECT COUNT(*) FROM sms WHERE agent_phone='$phone' AND status='$filter'";
	}
			
	}else{
		
		if($search != NULL) {
		$sql = "SELECT COUNT(*) FROM sms WHERE agent_phone='$phone' AND msg LIKE '%$search%'";
	  }else{
		$sql = "SELECT COUNT(*) FROM sms WHERE agent_phone='$phone'";
	}
	
	}

//GET THE TOTAL NUMBER OF ITEMS AT HAND
$p =  mysqli_query ($dbc, $sql) or trigger_error("Query: $q\n<br/>MySQL Error: " . mysqli_error($dbc));
$pn = mysqli_fetch_row($p); //no of rows returned
$num = $pn[0];

$totalpages = ceil($num/$display); //total pages generated $display = 32 images

if(isset($_GET['filter']) && $_GET['filter'] != 'all' ) {
		foreach($status as $stat => $value) {
			if($_GET['filter'] == $stat) {
				echo '<span class="noerr" style="font-weight:bold">Filtered by: \''.$value.'\' - '.$num.' SMSes, '.$totalpages.' page(s)</span>';
			}
		}
	}else{
		echo '<span class="noerr" style="font-weight:bold">Unfiltered Report - '.$num.' SMSes, '.$totalpages.' page(s)</span>';
	}
}

}
	
?>
</p>
</div>
<p style="margin-left:3%">
<?php if($title == "FreeLay - Reports") {
	if(!empty($symbol) ) {
		echo '<a class="submitit" style="padding:0; margin-left: 10%; margin:0; width: 150px; height: 25px" href="../controllers/csvget.php?symbol='.$symbol.'">Download CSV</a>';
	}else{
	
	if(isset($_GET['filter']) ) { $get = $_GET['filter']; }else{ $get='all'; }
	echo '<a class="submitit" style="padding:0; margin-left: 10%; margin:0; width: 150px; height: 25px" href="../controllers/csvget.php?filter='.$get.'&search='.$search.'">Download CSV</a>'; }

}
?>
</p>
</div></li>
</ul>

<!--
<table class="topmenu">
<tr>

<td><a href="../views/autoreply.php">AUTO REPLIES</a></td>
<td><a href="../views/quer.php">QUEING</a></td>
<td><a href="../views/scheduler.php">SCHEDULER</a></td>
<td><a href="../views/reports.php">REPORTS</a></td>

</tr>
</table>
-->