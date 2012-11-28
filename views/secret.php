<?php

$title = "FreeLay - Retrieve Password";
include_once('../controllers/config.php');
include('../includes/header.php');

require_once(MYSQL);

if(isset($_GET['secret']) ) {
	
	if(empty($_GET['secret'])) {
		$error[] = '<div class="error"> Please fill in the your secret in the space provided.</div>';
	}else{
	
	$secret = trim($_GET['secret']);
	
	//see if the user exists [real] or [dummy]
	$i = "SELECT * FROM agent WHERE phone='$secret'";
	$q = mysqli_query ($dbc, $i) or trigger_error("Query: $i \n<br/>MySQL Error: " . mysqli_error($dbc) );
	$r = mysqli_fetch_array($q, MYSQLI_ASSOC);
	
	if(mysqli_num_rows($q) == 1) { //if the user does exist
	
		if($r['status'] != 'active') {
			$secret = $r['id'];
			twendeHapa( BASE_URL.'views/register.php?siri='.$secret);	
		}else{
			$error[] = '<div class="error" style="background:#079">Oops! It seems we already have an account active with that Phone Number.</div>';
		}
	
	}else{
		$error[] = '<div class="error">No such Phone Number belongs to any temporary account in FreeLay</div>';
	}
	
	}
	
}	
	
?>
<style>
body{background:none;}
</style>

<div class="main">

<div style="width:80%; margin:auto; margin-top:5%;">
<?php if(!empty($error) ) { foreach($error as $e) { echo $e; $error = NULL ; } } ?>
</div>

<div style="width:100%; margin:auto; margin-top:5%;">
<form name="contact" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
<table class="forget" id="forget"> 
<tr>

<td class="name"></td>
<td><p> Please enter your phone no. as inputted in Envaya to be able to register</p></td>

</tr>
<tr>

<td class="name">Secret:</td>
<td><input style="width:100%" type="text" name="secret" id="email" value="<?php if(isset($_GET['secret'])) { echo $_GET['secret']; } ?>" />
</td>

</tr>
<tr>

<td class="name"></td>
<td>
<input type="submit" name="submit" class="btn" id="submitbtn" value="Go" /> 
<input type="hidden" name="submitted" value="TRUE" />
</td>

</tr>
</table>
</form>

<!--<p><a style="color:#fff; cursor:pointer" id="cboxClose">> Home</a></p>-->
</div>

</div>

</body>
</html>
