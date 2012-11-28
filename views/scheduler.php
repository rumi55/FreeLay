<?php

include('../controllers/auth.php');
	
	setcookie('phone', $phone, time()+18000, '/'); //5 hrs time out
	setcookie('pass', $pass, time()+18000, '/');
	setcookie('country', $country, time()+18000, '/');
	setcookie('name', $name, time()+18000, '/');
	setcookie('email', $email, time()+18000, '/');


$title ="FreeLay - Scheduler";
include_once('../controllers/config.php');
include_once('../includes/header.php');

if(isset($_POST['submit']) ) {

	if(!empty($_POST['context']) && !empty($_POST['desc']) && !empty($_POST['milestone']) && !empty($_POST['time']) && !empty($_POST['msg']) ) {
		
		$context = $_POST['context'];
		$desc = $_POST['desc'];
		$milestone = $_POST['milestone'];
		$time = $_POST['time'];
		$msg = $_POST['msg'];
		
		require_once(MYSQL);
		
		$q = "INSERT INTO scheduler (agent_phone, context, msg, milestone, milestone_num, milestone_time, postdate)
		VALUES ({$_COOKIE['phone']},'$context','$msg', '$desc', '$milestone','$time', NOW())";
		
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br/>MySQL Error: " . mysqli_error($dbc));
		
		if (mysqli_affected_rows($dbc) ==1 ) {
			
			include_once('../includes/functions.php');
			
			#calculate the future date to send the message
			$day = date('z') + 1; //day in years today
			$year = date('Y'); //this year
			
			##FOR EXAMPLE ONE
			$rand  = rand_num();
			
			$num = random_numbers($milestone);
			
			$target = $milestone - $num;
			
			$days = get_total_days($time, $target);
			
			$d = $day + $days;
			
			$date = get_date($d, $year);
			
			## FOR EXAMPLE TWO
			$r = rand_num();
			
			$n = random_numbers($milestone);
			
			$t = $milestone - $n;
			
			$days = get_total_days($time, $t);
			
			$dd = $day + $days;
			
			$da = get_date($dd, $year);
			
			##FOR EXAMPLE THREE
			$r2 = rand_num();
			
			$n2 = bad_milestone($milestone);
			
			$t2 = $milestone - $n2;
			
			$days = get_total_days($time, $t2);
			
			$d2 = $day + $days;
			
			$dat = get_date($d2, $year);
			
			
            
			//THE DISPLAY OF THE EXAMPLES
			
			$response = '<span class="noerr">Your Scheduler has been set!<br />
			&nbsp;It will respond as described below!</span>';
			
			$noerror = '
            
            <div class="example">
			<p>If an incoming message looked like <span class="wht">072'.$rand.'#'.$num.'</span> </p>
            
			<div class="msg">
            
            <p>The Outgoing message would be: </p>

			<div class="theno"> '.$msg.' </div>
            
            </div>
            
            <p>This message would be sent '.$target.' '.$time.' from today on '.$date.'</p>
			
			<p>We calculated it as '.$milestone.' - '.$num.' = '.$target.' '.$time.'</p>
            
            </div>
            <br />
			
            <div class="example">
			<p>If an incoming message looked like <span class="wht">072'.$r.'#'.$n.'</span> </p>
            
			<div class="msg">
            
            <p>The Outgoing message would be: </p>

			<div class="theno"> '.$msg.' </div>
            
            </div>
            
            <p>This message would be sent '.$t.' '.$time.' from today on '.$da.'</p>
			
			<p>We calculated it as '.$milestone.' - '.$n.' = '.$t.' '.$time.'</p>
            
            </div>
            <br />
			
			 <div class="example">
			<p>If an incoming message looked like <span class="wht">072'.$r2.'#'.$n2.'</span> </p>
			
			<div class="msg">
            
            <p>No message would be sent beacause the milestone has passed.</p>
			<p>We calculated it as '.$milestone.' - '.$n2.' = '.$t2.' '.$time.'</p>
			
			</div>
			
			</div>
			<br />';
			
			}
		
	}else{
		
	 $_SESSION['scherror'] = '<span class="err">Please ensure that all the fields are filled!</span>';
		
	}

}

include('../includes/menu.php');

?>	

<div class="content">
<table class="rule">
<tr>

<td id="rule1"> <h2> Rule #2</h2>
<p class="telno"> 0723001575#X</p>
<span> X </span> is a number that will be used to calculate when the message is to be sent compared to a milestone

</td>

<td id="rule2"><h2> Rule#1</h2>
<p class="telno"> 0723001575#X</p>
<span> X </span> is the number of days/weeks/hours in the future for follow-up messages to be sent out
</td>

</tr>
</table>

<br />
<div style=" width:50%; margin:auto;"> <?php if(isset($noerror)) { echo $noerror; } ?></div>
<br />

<hr style="width:95%; margin:auto" />

<div class="require"<?php if(isset($_SESSION['scherror']) ) { unset($_SESSION['scherror']); /*do nothing*/ }else{ echo 'style=" display:none"'; } ?> >
<div style=" width:100%; margin:auto;"> <?php if(isset($error)) { echo $error; } ?></div>

<h4> Incoming messages must have this format</h4>

<p class="telno"> 0723001575#X</p>

<h4> Where X is some number that will be used to calculate when a follow-up message will be sent to 0723001575(for example)
</h4>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" name="rule" method="post">
<table>
<tr>

<td>
<h3> What will X mean to you</h3>

<input type="text" size="50" name="context" value="<?php if(isset($_POST['desc']) ) { echo $_POST['desc']; } ?>">

</td>

</tr>
<tr>

<td>
<h3>Milestone</h3>

<table>
<tr>
<td> Description</td>
<td> Number</td>
<td> Time</td>
</tr>

<tr>
<td style="width:60%">
<input type="text" size="50" name="desc" value="<?php if(isset($_POST['desc']) ) { echo $_POST['desc']; } ?>">
</td>
<td>
<input type="text" size="2" name="milestone" value="<?php if(isset($_POST['milestone']) ) { echo $_POST['milestone']; } ?>">
</td>
<td>
<select name="time">

<option value="hours">hours</option>
<option value="days">days</option>
<option value="weeks">weeks</option>
<option selected="selected" value="months">months</option>
<option value="years">years</option>

</select>
</td>
</tr>
</table>

</td>

</tr>

<tr>

<td>
<h3> What Message will everyone recieve when the milestone is reached: </h3>
<textarea name="msg"  rows="5" >
<?php if(isset($_POST['msg']) ) { echo $_POST['msg']; } ?>
</textarea>
</td>

</tr>

<tr>
<td>
<br />
<input class="submit" type="submit" name="submit" value="Submit Rule" />
<br />
<br />
</td>
</tr>

</table>
</form>

</div>

</table>

</div>

<script type="text/javascript">

function getRule() {
	
	var xmlhttp;
	
	xmlhttp = new XMLHttpRequest();
	
	xmlhttp.onreadystatechange = function() {
		
		if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			
			document.getElementById("reg").innerHTML = xmlhttp.responseText;
		
		}
	}
	
	xmlhttp.open("GET","../views/rule.php?get=2",true);
	xmlhttp.send();

}			
</script>




</body>