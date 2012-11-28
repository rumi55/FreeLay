<?php

$title = "FreeLay - Retrieve Password";
include_once('../controllers/config.php');
include('../includes/header.php');

require_once(MYSQL);

$error = array();

if(isset($_POST['submitted']) == "TRUE" ) {

if(isset($_POST['submit']) && !empty($_POST['email']) ) {
	
	$email = stripslashes(trim($_POST['email']));
	
	$e = "SELECT * FROM agent WHERE email = '$email'";
	$q = mysqli_query ($dbc, $e) or trigger_error("Query: $e \n<br/>MySQL Error: " . mysqli_error($dbc));
	
	if(mysqli_num_rows($q) >= 1) {
		
		$res =  mysqli_fetch_array($q, MYSQLI_ASSOC);
		
		$message  = 'Your FreeLay Password: '.$res['password'];
		
		mail($email, 'PASSWORD RETRIVAL - FREELAY', $message, 'From: curiosity@globalgiving.org');
		
		$error[] = '<div class="error"> Your Password has been sent to you.</div>';
		
		unset($_POST);
		
	}else{
		
		$error[] =  '<div class="error"> Oops! It seems we have no such email address registered in FreeLay. Please ensure you have the correct email address filled in.</div>';
		
	}
	
}else{
	
	$error[] = '<div class="error"> C\'mon! You have not filled in any email address.</div>';
	
}

}

?>

<div style="width:80%; margin:auto; margin-top:10px;">
<?php if(!empty($error) ) { foreach($error as $e) { echo $e; $error = NULL ; } } ?>
</div>

<div style="width:50%; margin:auto; margin-top:5%;">
<form name="contact" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
<table class="forget" id="forget"> 
<tr>

<td class="name"></td>
<td><p> Please enter your email address registered with us so as we can send you your  passsword</p></td>

</tr>
<tr>

<td class="name">Email Address:</td>
<td><input style="width:100%" type="text" name="email" id="email" value="<?php if(isset($_POST['email']) ) { echo $_POST['email']; } ?>" />
</td>

</tr>
<tr>

<td class="name"></td>
<td>
<input type="submit" name="submit" class="btn" id="submitbtn" value="Get Password" /> 
<input type="hidden" name="submitted" value="TRUE" />
</td>

</tr>
</table>
</form>

<p><a style="color:#fff" href="welcome.php">> Home</a></p>
</div>


