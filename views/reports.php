<?php

include('../controllers/auth.php');

$title = "FreeLay - Reports";
include_once('../controllers/config.php');
include_once('../includes/header.php');

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

include('../includes/menu.php');

?>

<script type="text/javascript" src="../js/datepicker.js"></script>

<div class="content">

<?php

if(isset($_GET['symbol']) &&  $_GET['symbol'] != NULL ) {

echo '<br/>';

$symbol = $_GET['symbol'];
$_SESSION['symbol'] = $symbol;

$q = "SELECT * FROM sms WHERE agent_phone='$phone' AND msg LIKE '%$symbol%'";
$p =  mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br/>MySQL Error: " . mysqli_error($dbc));
$n = mysqli_fetch_row($p); //no of rows returned

while($r = mysqli_fetch_array($p, MYSQLI_ASSOC)) {
	
	$messages[] = $r['msg'];
	
}


//CALL FORTH THE HEADER
echo '
<table class="parsing" style="font-size:16px; font-weight: bold; background:#f88;">
<tr>
<td> Parsed Messages using "'.$symbol.'"</td>';
echo '</tr></table>';


foreach($messages as $msg) {

$c = count(explode($symbol, $msg));

$elements = explode($symbol, $msg);

if($c > 2) {

echo '
<table class="parsing">
<tr>
<td>'.$msg.'</td>';

for ($i = 0; $i < $c; $i++) {
	
	echo '<td>'.$elements[$i].'</td>';
	
}

echo '</tr></table>';

$count++;

}

}

if($count == 0) {
	echo '<table class="parsing"><tr>
	<td> No messages were found that could be parsed using such a syntax</td>
	</tr>
	</table>';
	
echo '<br/>';
exit();

}else{

echo '<br/>';
$messages = array();
exit();

}

}
?>


<!--<form name="frm" onubmit="return(false)" action="<?php $_SERVER['PHP_SELF']; ?>"  method="get">
<table class="options" style="width:95%; margin:auto;">
<tr>
<td>
<td style="width:25%">Parse by Symbol:
<input style="width:15%;" type="text" name="symbol" value="#" />
</td>
<td style="width:5%">
<input style="width:50px; margin:auto" type="submit" name="submit" class="btn" id="submitbtn" value="Go" /> 
</td>
<td style="width:70%">
</td>
</table>
</form>

<!-- FILTER AND SEARCH PARAMETRE -->
<form name="frm" onubmit="return(false)" action="<?php $_SERVER['PHP_SELF']; ?>"  method="get">
<table class="options" style="width:95%; margin:auto">
<tr>

<td style="width:22%">Parse by:
<input style="width:50%;" type="text" name="symbol" value="" />
</td>

<td style="width:45%"> Filter by status: 
<select style="width:62%" name="filter" onchange="sortReport(document.frm.select.value)">
<?php

if($filter == 'all') {
	
	echo '<option selected="selected" value="all"> All - Unfiltered</option>';
	
	foreach($status as $stat => $value) {
		echo '<option value="'.$stat.'">'.$value.'</option>';
		}

}else{
	
echo '<option selected="selected" value="all"> All - Unfiltered</option>';

foreach($status as $stat => $value) {
	
	if($filter == $stat ) {
		echo '<option selected="selected" value="'.$stat.'">'.$value.'</option>';
	}else{
	echo '<option value="'.$stat.'">'.$value.'</option>';
	}

}

}

?>
</select>

</td>
<td style="width:5%">
<input style="width:50px; margin:auto" type="submit" name="submit" class="btn" id="submitbtn" value="Go" /> 
</td>

<td style="width:20%">
<input type="text" style="background: url(../images/search.jpg) no-repeat left center; padding-left:32px;" name="search" value="<?php if(isset($_GET['search']) ) { echo $_GET['search']; } ?>"  />
</td>
<td style="width:8%">
<?php
if(!empty($search)) {
echo '<a class="submitit" style="padding:0; margin:0; width:30px; float:right; height:auto" href="reports.php" >X</a>';
}
?>
</td>
<!--
<td>
<input class="inputbox" id="mstartdate" name="mstartdate" value="dd/mm/yyyy" size="12"   />
</td>

<td>
<input type="button" value="..." onclick="displayDatePicker('mstartdate')" />
</td>
 -->     
</tr> 
</table>
</form>
<br />

<?php

//START OF PAGINATION
if(isset($filter) && $filter != 'all') {
	
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

//get current page or set default page

if(isset($_GET['page']) && is_numeric($_GET['page'])) {
	
	//cast var as int
	$thispage = (int)$_GET['page'];

}else{
	//default page number
	$thispage = 1;
}

//if the current page is greater than total pages
if($thispage > $totalpages) {
	
	$thispage = $totalpages;
	
}

//if the current page is less than the 1st page
if($thispage < 1) {
	
	$thispage = 1;
}

$offset =($thispage - 1) * $display;

?>

<div class="page">
<table class="pages">
<tr>

<?php

if($thispage != 1) {
	
	echo '<td><a href="';
	echo "{$_SERVER['PHP_SELF']}";
	echo '?filter='.$filter.'&page=1">first</a>
	</td>';
	
}

for($x = ($thispage - $range); $x < (($thispage + $range) + 1); $x ++) {
	
	//if the page is a valid page
	if(($x > 0) && ($x <= $totalpages)) {
		
		//if we are in the current page
		if($x == $thispage){
			
			//echo '<b>page : '.$x.' (out of '.$totalpages.') , <span class="me_name">'.$num.'</span> SMSes</b>';
			
		echo '<td  style="background:#05576b; color:#fff; border-radius: 5px"><a href="';
		echo "{$_SERVER['PHP_SELF']}";
		echo '?filter='.$filter.'&page='.$x.'">'.$x.'</a></td>';
		
		}else {//make it a link
		
		echo '<td><a href="';
		echo "{$_SERVER['PHP_SELF']}";
		echo '?filter='.$filter.'&page='.$x.'">'.$x.'</a></td>';
		
		}
	}
}

if($thispage != $totalpages) {
	
	echo '<td><a href="';
	echo "{$_SERVER['PHP_SELF']}";
	echo '?filter='.$filter.'&page='.$totalpages.'"> last </a>
	</td>';
	
}
?>

</tr>
</table>
</div>

<?php 

if($filter) {

echo ''; }

?>

<table class="report" style="background:#f88; font-size:14px; font-weight:bold">
<tr>
<td> msgid</td><td class="msg">message</td><td>phone no.</td><td>date</td><td>status</td>
</tr>
</table>

<?

/*elseif(!empty($symbol)) {

echo '
<table class="parsing" style="background:#eee; font-size:14px; font-weight:bold">
<tr>
<td> message</td>
</tr>
</table>';

}*/
?>

<div id="reporting">

<?php

//SEE IF POST HAS BEEN SENT AND GET ALL THE INFORMATION REQUIRED
if(isset($filter) && $filter != 'all' ) {
	
	if($search != NULL) {
	$q = "SELECT * FROM sms WHERE agent_phone='$phone' AND status='$filter' AND msg LIKE '%$search%' ORDER BY created_date DESC LIMIT $offset, $display";
	}else{
		$q = "SELECT * FROM sms WHERE agent_phone='$phone' AND status='$filter' ORDER BY created_date DESC LIMIT $offset, $display";
	}
	
}else{
	
	if($search != NULL) {
	$q = "SELECT * FROM sms WHERE agent_phone='$phone' AND msg LIKE '%$search%' ORDER BY created_date DESC LIMIT $offset, $display";
	}else{
		$q = "SELECT * FROM sms WHERE agent_phone='$phone' ORDER BY created_date DESC LIMIT $offset, $display";
	}
}

$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br/>MySQL Error: " . mysqli_error($dbc));


//IF IT RETURNS RESULTS
if(mysqli_num_rows($r) >= 1) {

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

}else{ //IF NO RESULTS WERE RETURNED

foreach($status as $what => $value ) {
		
		if($filter == $what ) {
			
			$stat = $value;
			
		}
}

echo '<table class="report"><tr>';
echo '<td></td><td class="msg"> No Results have been found associated with such a status</td><td><td></td><td>'.$stat.'</td>';
echo '</tr></table>';

}

?>

</div>
<br />

<div class="page">
<table class="pages">
<tr>

<?php

if($thispage != 1) {
	
	echo '<td><a href="';
	echo "{$_SERVER['PHP_SELF']}";
	echo '?filter='.$filter.'&page=1"> first </a>
	</td>';
	
}

for($x = ($thispage - $range); $x < (($thispage + $range) + 1); $x ++) {
	
	//if the page is a valid page
	if(($x > 0) && ($x <= $totalpages)) {
		
		//if we are in the current page
		if($x == $thispage){
			
			//echo '<b> page : <span class="me_name">'.$x.'</span>/'.$totalpages.'</b>';
		echo '<td style="background:#05576b; color:#fff; border-radius: 5px"><a href="';
		echo "{$_SERVER['PHP_SELF']}";
		echo '?filter='.$filter.'&page='.$x.'">'.$x.'</a></td>';
		
		}else {//make it a link
		
		echo '<td><a href="';
		echo "{$_SERVER['PHP_SELF']}";
		echo '?filter='.$filter.'&page='.$x.'"> '.$x.' </a></td>';
		
		}
	}
}

if($thispage != $totalpages) {
	
	$nextpage = $thispage + 1;
	
	echo '<td><a href="';
	echo "{$_SERVER['PHP_SELF']}";
	echo '?filter='.$filter.'&page='.$totalpages.'"> last </a>
	</td>';
	
}
	
?>

</tr>
</table>
</div>

<br />

</div>


</body>