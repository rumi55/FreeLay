<?php //LOG OUT AUTHENTICATION

session_start();

require_once('config.php');

setcookie('phone', $_COOKIE['phone'], time()-3600, '/'); //1 hrs time out
setcookie('pass', $_COOKIE['pass'], time()-3600, '/');
setcookie('name', $_COOKIE['name'], time()-3600, '/');
setcookie('email', $_COOKIE['email'], time()-3600, '/');

	$_SESSION = array( ); // destroy all the SESSIONs that had been registered in the COOKIE array
			
	$url = BASE_URL.'views/welcome.php'; // if all the COOKIE is destroyed then redirect the user to the home page
			
	header("Location: $url");
			
	exit ( ) ;
						

?>>