<?php

session_start();

require_once('config.php');
require_once('upload_config.php');

$title="FreeLay - CSV Upload";
include_once('../includes/header.php');

if(!empty($file) && isset($file) ) { //if any file has been submitted

require_once('name_gen.php');

if(in_array($filetype, $allowedExtension) ) { //check to see if the FILE IS AN IMAGE FILE
	
	//if the file is a valid image file let the script continue
	
}else{
	
	$_SESSION['notuploaded'] = '<span class="err">'.$the_error[9].' ('.$file.') its probably a(n) '.$filetype.'</span><br />'; 
	twendeHapa('../views/quer.php');
	exit;
	
	}
	
if(move_uploaded_file($filetmp,$path)) { //copy the file to the upload folder then assess it...

$_SESSION['success'] = '<span class="noerr">File ('.$file.') was Uploaded successfully</span><br />';
twendeHapa('../views/quer.php?csv='.$uploaddir);

}

}//main clause
else{

	  $_SESSION['notuploaded'] = '<span class="err">'.$the_error[8].'</span>';
	  twendeHapa('../views/quer.php');

}

?>
	

