<?php // CONFIGURATION FILE

define('LIVE', true); // (if) FALSE: all errors are sent to the browser

//root folder of the domain
define('BASE_URL',' http://globalgivingcommunity.com/freelay/');

define('EMAIL', 'jhenetic@gmail.com'); //define admin email address here!!

//SITE NECESSITIES
define('SITENAME', 'freelay-'); //sitename image prefix

// LOCATION of mySQL connection script
define('MYSQL', '../helpers/dbconn.php'); //define path of mysql connection script

// FUNCTION TO TAKE CARE OF REDIRECTIONS
function twendeHapa($url)
{ 
echo '<script type="text/javascript">
<!--
window.location = "'.$url.'"
//–>
</script>';
}

//************ DEFAULT ALLOCATIONS *************//
date_default_timezone_set('Africa/Nairobi');


//******** CATEGORYS ************//
$status = array(			
			
			'-4' => 'Flash reply',
			'-3' => 'Received - Others',
			'-2' => 'Processed',
			'-1' => 'Received',
			'0' => 'Queued',
			'1' => 'Sent',
			'2' => 'Pending in Gateway',
			'3' => 'Others'
);

// --------------------------- PAGINATION ------------------------- //
$display = 100;
$no_blog = 5;
$range = 4;

//*****************  ERROR MANAGEMENT ********************//

// Create the error handler:

function my_error_handler ($e_number, $e_message, $e_file, $e_line, $e_vars) {
	
// Build the error message.
$message = "<br /><br /><p>An error occurred in script '$e_file' on line $e_line: $e_message \n <br />";

// Add the date and time:
$message .= "Date/Time: " . date('n-j-Y
H:i:s') . "\n<br />";

// Append $e_vars to the $message:
$message .= "<pre>" . print_r ($e_vars,1) . "</pre>\n</p>";

if (!LIVE) { 	// Development (print the error).

	 	echo '<div id="Error">'.$message.'</div><br />';

} /*else { 	// Don't show the error:

// Send an email to the admin:

mail(EMAIL, 'Site Error!', $message, 'From: jhenetic@gmail.com');

// Only print an error message if the error isn't a notice:

if ($e_number != E_NOTICE) {
	
	echo '<div id="Error">A system error occurred. We apologize for the inconvenience.</div><br />';
		
		}
	} // End of !LIVE IF.*/
	
} // End of my_error_handler() definition.

// Use my error handler.
set_error_handler ('my_error_handler');

// ************ END OF ERROR MANAGEMENT **************//

?>