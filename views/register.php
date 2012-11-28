<?php

$title = "FreeLay - Retrieve Password";
include_once('../controllers/config.php');
include('../includes/header.php');

require_once(MYSQL);

if(!empty($_GET['pass'])&& !empty($_GET['name']) && !empty($_GET['email']) && !empty($_GET['org']) && !empty($_GET['op']) ) {
	
		$secret = ($_GET['secret']);
			
		if ($_GET['pass'] == $_GET['pass2']) {
			$pswd = trim( $_GET['pass']);
		}else{
			echo '<div class="error">Your Password Does Not Match The Confirmation Password!</div>';
		}
		
	 /*if(preg_match('/[0-9]{5}/', $_GET['phone']) ) {
		 $phone = $_GET['phone']; 
	 }else{
		 $error[] = '<div class="error"> Invalid Phone Number.</div>';
	 }*///not in use NOW
	 
	 if (preg_match ('/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/', $_GET['email'])) {
		$email =strtolower(trim($_GET['email']));
		}else{
			$error[] = '<div class="error"> Invalid Email address.</div>';
	 }
			
	
	if(count(explode(' ', $_GET['name'])) >= 2) {
		$name = $_GET['name'];
	}else{
		$error[] = '<div class="error">Please fill in your two most preferred names</div>';
	 }
	
	$org  = $_GET['org'];
	
	/*$i = "SELECT * FROM agent WHERE phone='$phone'";
	$q = mysqli_query ($dbc, $i) or trigger_error("Query: $i \n<br/>MySQL Error: " . mysqli_error($dbc) );
	$r = mysqli_fetch_array($q, MYSQLI_ASSOC);
	
	if(mysqli_num_rows($q) == 0) { //if the user does not exist*/
	
	if(!empty($name) && !empty($pswd) && !empty($email)) {
	
		$in = "UPDATE agent  SET country = 'Unknown', operator ='$op', name ='$name', email ='$email', org_name =  '$org', status = 'active', password='$pswd' WHERE id='$secret'";
		$qin = mysqli_query ($dbc, $in) or trigger_error("Query: $in \n<br/>MySQL Error: " . mysqli_error($dbc) );
		
		if(mysqli_affected_rows($dbc) == 1) {
			
			$noerror = '<div style="width:45%; margin:auto; padding: 3%; margin-top:15%; background: #079; border-radius: 10px">
			Welcome '.$name.' to FreeLay. <a style="height:auto; margin:0; padding:0; color: #fff" target="_parent" href="welcome.php" > Click here to Reload</a> the Page so as you can be able to log in.
			</div>';
		}
		
	}
		
	/*}else{ //if there is somealredy registered with that no
	
		$error[] = '<div class="error">Oops! It Seems that number is already registered in Freelay</div>';
	}*/
	
}else{
	
	$error[] = '<div class="error"> Please ensure that you have filled in all of the fields</div>';
}

?>

<style>
body{background:none;}
</style>

<?php if(!empty($noerror)) { echo $noerror; exit(); } ?>

<div style="width:45%; margin:auto; margin-top:20px;">
<?php if(!empty($error) ) { foreach($error as $e) { echo $e; $error = NULL ; } } ?>
</div>

<div style="width:50%; margin:auto; margin-top:5%;">
<form name="contact" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
<table class="forget" id="forget"> 
<tr>

<td class="name"></td>
<td><p> REGISTARTION FORM. **All fields are required.</p></td>

</tr>
<tr>

<td class="name">Full Name:</td>
<td><input style="width:100%" type="text" name="name" id="email" value="<?php if(isset($_GET['name']) ) { echo $_GET['name']; } ?>" />
</td>

</tr>
<tr>

<td class="name"> Password:</td>
<td><input style="width:100%" type="text" name="pass" id="email" value="<?php if(isset($_GET['phone']) ) { echo $_GET['phone']; } ?>" />
</td>

</tr>
<tr>

<td class="name">Re-Enter Password</td>
<td><input style="width:100%" type="text" name="pass2" id="email" value="<?php if(isset($_GET['phone']) ) { echo $_GET['phone']; } ?>" />
</td>

</tr>
<tr>

<td class="name"> Email:</td>
<td><input style="width:100%" type="text" name="email" id="email" value="<?php if(isset($_GET['email']) ) { echo $_GET['email']; } ?>" />
</td>

</tr>
<tr>

<td class="name">Organisation:</td>
<td><input style="width:100%" type="text" name="org" id="email" value="<?php if(isset($_GET['org']) ) { echo $_GET['org']; } ?>" />
</td>

</tr>
<tr>

<td class="name"> Your Mobile Operator:</td>
<td><input style="width:100%" type="text" name="op" id="email" value="<?php if(isset($_GET['op']) ) { echo $_GET['op']; } ?>" />
</td>

</tr>
<tr>

<td class="name"></td>
<td>
<input type="submit" name="submit" class="btn" id="submitbtn" value="Submit" /> 
<input type="hidden" name="secret" value="<?php if(isset($_GET['secret']) ){ echo $_GET['secret']; }else{ echo $_GET['siri']; }?>" />
</td>

</tr>
</table>
</form>

<!--<p><a style="color:#fff; cursor:pointer" id="cboxClose">> Home</a></p>-->
</div>
