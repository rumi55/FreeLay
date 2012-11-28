<?php

//get the phone number that sent the SMS.
if (isset($_POST['from']))
{
    $from = $_POST['from'];
}


// get the SMS aka message sent
if (isset($_POST['message']))
{
    $message = $_POST['message'];
}


//set success to true
$success = "true";



// in case a secret has been set at SMSSync side, 
//get it for match making
 
if (isset($_POST['secret']))
{
    $secret = $_POST['secret'];
}



//get the timestamp of the SMS

if(isset($_POST['sent_timestamp']))
{
    $sent_timestamp = $_POST['sent_timestamp'];
}


if (isset($_POST['sent_to'])) 
{
    $sent_to = $_POST['sent_to'];
}


if (isset($_POST['message_id']))
{
    $message_id = $_POST['message_id'];
}


/* now we have retrieved the data sent over by SMSSync 
 * via HTTP. next thing to do is to do something with 
 * the data. Either echo it or write it to a file or even 
 * store it in a database. This is entirely up to you. 
 * After, return a JSON string back to SMSSync to know 
 * if the web service received the message successfully. 
 
 * In this demo, we are just going to echo the data 
 * received.
 */
 
if ((strlen($from) > 0) AND (strlen($message) > 0) AND 
    (strlen($sent_timestamp) > 0 ) AND (strlen($sent_to) > 0) 
    AND (strlen($message_id) > 0))
{
    //in case secret is set on both SMSSync and the web 
    //service. Let's make sure they match.
    if ( ! ( $secret == '123456'))
    {
        $success = "false";
    }
    // now let's write the info sent by SMSSync
    //to a file called test.txt
    $string = "From: ".$from."n";
    $string .= "Message: ".$message."n";
    $string .= "Timestamp: ".$sent_timestamp."n";
    $string .= "Messages Id:" .$message_id."n";
    $string .= "Sent to: ".$sent_to."nnn";
    $myFile = "test.txt";
    $fh = fopen($myFile, 'a') or die("can't open file");
    @fwrite($fh, $string);
    @fclose($fh);


} 
else 
{
    $success = "false";  

}



/* now send a JSON formatted string to SMSSync to 
acknowledge that the web service received the message
*/

echo json_encode(array("payload"=>array("success"=>$success)));


 /*Comment the code below out if you want to send reply an instant 
 * reply as SMS to the user.
 
 * This feature requires the "Get reply from server" check on SMSSync.
 */



$msg = "Your message has been received"; 

$reply[0] = array("to" => $from, "message" => $msg);
 

 echo json_encode(array("payload"=>array("success"=>$success,"task"=>"send","messages"=>array_values($reply))));
 
?>