<style>
body{width:50%; margin:auto; font-family:"Trebuchet MS", Arial, Helvetica, sans-serif}
span{font-size:2em; color:#e00;}
.iploc{width:100%; margin:auto}
.iploc tr{background:#ff5;}
.iploc td{text-align: center; padding:1%}
.iploc td .c{width:40%;}
.iploc td .c2{width:30%;}
.iploc td .ip{width:30%;}
.iploc td p{padding:0; margin:0}
</style>

<?php

session_start();

include_once('../controllers/config.php');

ini_set('auto_detect_line_endings', true);

function displayTxt($fileName, $count) {
   
    if(file_exists($fileName)) {
        
		$file = fopen($fileName,'r');
		
		$dbc = mysqli_connect ('localhost', 'jhene', 'jhene75', 'freelay');
		
		if (!$dbc) {
		
		trigger_error ('Could not connect to MySQL: ' . mysqli_connect_error() );
		
		}
        
		while(!feof($file)) { 
		
		 if($count != 0) {
            
			$name = fgets($file);
			$string = explode('	', $name);
			
			$c_2 = $string[0];
			$country = $string[1];
			
			
            echo '<tr>
			<td class="c2">'.$count.'</td>
			<td class="c">'.$country.'</td>
			<td class="c2">'.$c_2.'</td>
			<td class="c2"><img src="../images/flags/'.$c_2.'.png" alt='.$c_2.' /></td>
			<tr>';
			
			/*
			$flag = '<img src="../images/flags/'.$c_2.'.png" alt='.$c_2.' />';
			
			$q = "insert into countries (c2, country, flag) values ('$c_2', '$country', '$flag')";
			$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br/>MySQL Error: " . mysqli_error($dbc));			
			*/
			
			}
			
			$count++;
			$_SESSION['results'] = $count;
			
        }
		
        fclose($file);
    
	} else {
        
		echo('<tr><td align="center">placeholder</td></tr>');
    
	}       

}
?>

<br>
<h3> I was able to generate :  <span><?php echo $_SESSION['results'] - 1; ?></span> results </h3>
<br>

<table class="iploc">

<?php displayTxt('../helpers/countries.txt', 0); ?>

</table>
