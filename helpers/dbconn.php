<?php //DATABASE CONNECTION SCRIPT

// Connection to the database

define('DB_HOST', 'localhost'); //host

define('DB_USER', 'globamh1'); //database username

define('DB_PASSWORD', 'ission~1X'); //password

define('DB_NAME', 'globamh1_gg'); //database name

// Make the connection:

$dbc = mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (!$dbc) {

trigger_error ('Could not connect to MySQL: ' . mysqli_connect_error() );

}

?>