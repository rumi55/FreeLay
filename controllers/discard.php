<?php

include('../controllers/auth.php');
include_once('../controllers/config.php');

if(isset($_REQUEST['csv']) ) {
	
	require(MYSQL);
	
	$csv = $_REQUEST['csv'];
	
	unlink($csv);
	$_SESSION['discard'] = '<span class="noerr">the CSV previously uploaded has been discarded</span>';
	
	$url = BASE_URL.'views/quer.php';
	header("Location: $url");
	
}