<?php

session_start();

$error = array();

$title = "FreeLay - Welcome";
include_once('../controllers/config.php');
include('../includes/header.php');

?>
<link media="screen" rel="stylesheet" href="../css/colorbox.css" />

<script src="../js/jquery-1.6.2.min.js"></script>
<script src="../js/jquery.colorbox.js"></script>
<script type="text/javascript">
<!--
$(document).ready(function(){
			
			$(".linkbox").colorbox({width:"80%", height: "60%"});
			$(".box").colorbox({width:"70%"});
			$(".view").colorbox({width:"100%", height:"100%", iframe:true});
		
		});
		-->
</script>

<?php

require_once(MYSQL);

if(isset($_POST['submitted']) == "TRUE" ) {
	
	 if (!empty($_POST['phone']) &&  !empty($_POST['pass']) ) {
		 
		 if(preg_match('/[0-9]/', $_POST['phone']) ) {
	
			$mn = trim($_POST['phone']);
			
				} else {
					
					$error[] =  '<div class="error"> Curious!!! Is That Really Your Username!</div>';
			
			}
			
			if (isset($_POST['pass'])) {
	
				$pm = trim($_POST['pass']);
				
				} else {
						
						$error[] = '<div class="error"> Curious!!! Is That Really Your Password.</div>';
				
			}
			
		
		if ($mn && $pm) {
			
			$log = "SELECT * FROM agent WHERE phone='$mn' AND password='$pm'";
			
			$rlog = mysqli_query ($dbc, $log) or trigger_error("Query: $log \n<br/>MySQL Error: " . mysqli_error($dbc));
			
			if (mysqli_num_rows($rlog) == 1) {
				
				$_SESSION = mysqli_fetch_array($rlog, MYSQLI_ASSOC);
				
				$url = BASE_URL.'views/reports.php'; // redirect the user to home page where he willl be authenticated
				twendeHapa("$url"); //by session created here....if existent, display page if not, redirect to index page
				
				mysqli_close($dbc);
				
				exit ( );
			
			}else { // if no match was made
	
			$error[] = '<div class="error">Oh no! Wrong Phone No. and Password Combination</div>';
			
			}
			
		}else{ $error[] = '<div class="error">An error occured, please try again.</div>'; }

	 }else{
	
		$error[] =  '<div class="error">Please Ensure That You Have Filled All The Field, Thank You! </div>';
		
	 }

}

$q = "SELECT phone FROM agent WHERE status='active'";

$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br/>MySQL Error: " . mysqli_error($dbc));

?>

<div style="width:80%; margin:auto; margin-top:10px;">
<?php if(isset($error) ) { foreach($error as $e) { echo $e; } } ?>
</div>

<div style="width:80%; margin:auto; margin-top:10%">
<table class="welcome">
<tr>

<td class="image">
<img src="../images/logo.png" />
</td>

<td class="form">

<form name="contact" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
<table class="formfield">
<tr>

<td>
<p>Agent Phone No.:</p>
<select name="phone">
<?php

while($no = mysqli_fetch_array($r, MYSQLI_ASSOC) ) { 
	
	echo '<option value="'.$no['phone'].'">'.$no['phone'].'</option>';

}

?>
</select>
</td>

</tr>
<tr>

<td>
<p>Password:</p><input type="password" name="pass" id="pswd" />
</td>

</tr>
<tr>

<td class="input">
<br />
<input type="submit" name="submit" class="btn" id="submitbtn" value="Log In" /> 
<input type="hidden" name="submitted" value="TRUE" />

</td>
</tr>


<tr>
<td>
<b style="font-size:14px;"><a style="padding:0; margin:0; height:10px;" href="../views/didforget.php">Did you forget your password?</a></b>
</td>
</tr>

</table>
</form>

</td>

</tr>
</table>
<br />

<div class="smsreg">
<a class="view" href="secret.php"> If you are using SMSSync or EnvayaSMS, Register Here!</a>
</div>

</div>

