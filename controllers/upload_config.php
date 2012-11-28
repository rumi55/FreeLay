<?php

//allowed fileand type of images
$allowedExtension = array(
			'text/csv',
			'text/anytext',
			'text/comma-seperated-values',
			'application/csv',
			'application/vnd.ms-excel',
			'application/vnd.msexcel',
			'application/excel',
			'application/octet-stream'
			);

$extention = '.csv';

$the_error = array(  //to be used as definer for the errors eg. $the_error[$error]
								0=>'The image exceeds the MAXIMUM FILE SIZE directive', //image must be under 2 MB in size
								1=>'The image exceeds the MAXIMUM FILE SIZE directive',
								2=>'The image was only partially uploaded.',
								3=>'No file was uploaded.',
								4=>'Missing a temporary folder.',
								6=>'Failed to write file to disk.',
								7=>'A PHP extension stopped the file upload.',
								8=>'No file was selected for upload',
								9=>'The file chosen is not VALID.',
							  10=>'The image file is smaller than the minimum dimensions directive (height: 800px and width: 600px).'
								);


if(!empty($_FILES['doc']['name']) && isset($_FILES['doc']['name']) ) {
	
	$file = $_FILES['doc']['name'];
	$filetmp = $_FILES['doc']['tmp_name'];
	$filesize = $_FILES['doc']['size'];
	$filetype = $_FILES['doc']['type'];
	
	$error = $_FILES['doc']['error'];	
	
}