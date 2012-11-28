<?php

include('../controllers/auth.php');

$title = "FreeLay - Queueing";
include_once('../controllers/config.php');
include_once('../includes/header.php');

include('../includes/menu.php');

$no_of_var = 0; //start of variable numbers in the csv form

?>

<div class="content" id="upload">


<div class="note">Note: Your CSV file must have a number and a message per line.
<br />
<br /><span class="blk">
Example:<br />
0723001575, Please bring the baby back for check up<br />
0732######, How are you doing after your visit to the hospital, Eugene<br />
0714######, Hope you have recovered fully from ur surgical operation, Beta Hospital, Sunday<br />
We will be having free check ups every Friday of the month feel free to come, 0708######<br />
</span>
</div>

<form action="../controllers/upload.php" name="frm" enctype="multipart/form-data" method="post">
<table style="padding:1%; width:70%; margin:auto" class="form">
<tr>

<td>Browse: <input style="background:#fff" name="doc" type="file" id="file" size="50"/><br /><br />

<table class="check">
<tr>
<td><input type="checkbox" name="header-xo" value="xxx" />Check this box if the CSV<br />
 file's first row is a header </td>
<td><input style="width:100%" class="submit" name="Submit" type="submit" id="submit" value="Upload" /></td>
</tr>
</table>

</td>

</tr>
</td>
 
</tr>
</table>
</form>

<div id="csv_results">
<?php

if(isset($_GET['jhene']) ) {
	
	$_SESSION['success'] =$_GET['jhene'];
	
}

if(isset($_SESSION['success']) ) {
	
if(isset($_GET['csv'])) {
	
$csv =$_GET['csv'];
	
$file_handle = fopen($csv, 'r');

echo '<h3> Does This Look Right: </h3>';

echo 

'<table class="results" style="background-color:#f99">
<tr>

<td> Phone No.</td><td> Message</td>

</tr>
</table>';

while (!feof($file_handle) ) {

$line_of_text = fgetcsv($file_handle, 1024); //open the CSV File and read it

foreach($line_of_text as $var) {
	
	$no_of_var++;
	
}

if($no_of_var >= 2) {
	
	//getwhich one can be the phone number number
	foreach($line_of_text as $text) {
		
		str_replace('+','', $text);
		str_replace('\"','"', $text);
		
		if(preg_match('/^\+?[0-9]{6,15}+$/', $text) ) {
			
			$recieversphone = $text;
		}
		
		//now acquire which one can be the message	
		$wording = count(explode(" ",$text) );
			
		if($wording >= 4){
			
			$message = $text;
			
		}
		
	}
	
	if($recieversphone != $_SESSION['num']) {
		
		if(preg_match('/^\+?[0-9]{6,15}+$/', $recieversphone) && $message) {
		
			echo '<table class="results"><tr>';
			echo '<tr><td>'.$recieversphone.'</td><td>'.ucfirst(str_replace('\"','"', $message)).'</td></tr>';
			echo '</table>';
			
			$_SESSION['msg'] = $message; $_SESSION['num'] =$recieversphone;
		
		}
	
	}

}
			


}

echo '<br />
<table class="subm">
<tr><td>
<a href="../controllers/submit.php?submit=true&csv='.$csv.'" style="margin-left:15%" class="submitit" onclick="dataBase()"> Submit</a></td>

<td><a  style="margin-left:15%" class="submitit" href="../controllers/discard.php?csv='.$csv.'">Discard</a></td>
</tr>
</table>';

fclose($file_handle);

}

$_SESSION['success'] = array();

}

?>
<br />
</div>
<br>
</div>

<script type="text/javascript">
<!--
function dataBase( ) {
	
	var xmlhttp;
	
	xmlhttp = new XMLHttpRequest();
	
	xmlhttp.onreadystatechange = function() {
		
		if((xmlhttp.readyState == 2) || (xmlhttp.readyState == 3)) {
			
			document.getElementById("csv_results").innerHTML = '<div class="loader"><p> Queuing...</p>
			<img src="../images/loadingAnimation.gif" /></div>';
			
		}
		
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			
			document.getElementById("csv_results").innerHTML = xmlhttp.responseText;
			document.getElementById("upload").innerHTML = '';
		
		}
			
	}
	
	xmlhttp.open("GET","../controllers/submit.php?submit=true&csv=<?php echo $csv; ?>", true);
	xmlhttp.send();

}

	
-->
</script>

<?php $_SESSION['msg'] = array(); $_SESSION['num'] = array(); //Clearing the session to give way to other csv uploads ?>
