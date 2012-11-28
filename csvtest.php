<?php

include_once('controllers/config.php');
 
$filename = tempnam(sys_get_temp_dir(), "csv");

$file = fopen($filename,"w"); //open file

// Your data should be like this
$database_returned_data = array( 

array ( 1 , 'Headphones' , 3 ), 
array ( 2 , 'CDPlayer', 4 ) , 
array ( 3 , 'iTouch', 1 ) 

);

$i = 1;
foreach($database_returned_data as $row):
  $csv_fields[$i][0] = $row[0];  
  $csv_fields[$i][1] = $row[1];
  $csv_fields[$i][2] = $row[2];  
$i++;
endforeach;


foreach ($csv_fields as $fields) {
	fputcsv($file, $fields);
}
fclose($file);


header("Content-Type: application/csv");
header("Content-Disposition: attachment;Filename=report(".$stat.") - ".date('d-m-Y g:i').".csv");

// send file to browser
readfile($filename);
unlink($filename);

?>