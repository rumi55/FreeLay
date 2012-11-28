<?php //INDEX PAGE - Redirects to real INDEX PAGE if user is not logged in but to HOME PAGE if
//  the User has Logged In (Works with cookie and session)

session_start ( ); //start the session, to be able to access it

include_once('config.php');

if( isset( $_SESSION['phone']) && isset($_SESSION['password']) ) { //check if the users variables are registered

		//if set then let the script continue....(edit nothing here!)
		$phone = $_SESSION['phone']; //username stored in the session
		$pass = $_SESSION['password']; //users password
		$country = $_SESSION['country'];
		$email = $_SESSION['email'];
		$name = $_SESSION['name']; //profile pic location
		
} else { // if the user is not logged in (no session variable of the user was found)
	
	$url = BASE_URL.'/views/welcome.php';
	header("Location: $url"); // then redirect the user to index/start page for the user to log in
	exit ( ); //exit the script
	
}

//PS - the user will be also verified at the start page to see if there was a mistake( of course not!!)
// but just a good security practice

?>