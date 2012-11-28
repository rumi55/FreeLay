<?php

session_start();

include('controllers/config.php');

if(isset($_COOKIE['phone'] ) ) {
	
	$_SESSION['phone'] = $_COOKIE['phone'];
	$_SESSION['password'] = $_COOKIE['pass'];
	$_SESSION['email'] = $_COOKIE['email'];
	$_SESSION['name'] = $_COOKIE['name'];

	header("Location: views/reports.php");

}else{
	
	header("Location: views/welcome.php");
	
}

?>