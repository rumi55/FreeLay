<?

// This is a simple script to calculate the difference between two dates
// and express it in years, months and days
// 
// use as in: "my daughter is 4 years, 2 month and 17 days old" ... :-)
//
// Feel free to use this script for whatever you want
// 
// version 0.1 / 2002-10-3
//
// please send comments and feedback to webmaster@lotekk.net
//

// ****************************************************************************

// configure the base date here
$base_day		= 27;		// no leading "0"
$base_mon		= 10;		// no leading "0"
$base_yr		= 2009;		// use 4 digit years!

// get the current date (today) -- change this if you need a fixed date
$current_day		= date ("j");
$current_mon		= date ("n");
$current_yr		= date ("Y");

// and now .... calculate the difference! :-)

// overflow is always caused by max days of $base_mon
// so we need to know how many days $base_mon had
$base_mon_max		= date ("t",mktime (0,0,0,$base_mon,$base_day,$base_yr));

// days left till the end of that month
$base_day_diff 		= $base_mon_max - $base_day;

// month left till end of that year
// substract one to handle overflow correctly
$base_mon_diff 		= 12 - $base_mon - 1;

// start on jan 1st of the next year
$start_day		= 1;
$start_mon		= 1;
$start_yr		= $base_yr + 1;

// difference to that 1st of jan
$day_diff	= ($current_day - $start_day) + 1; 	// add today
$mon_diff	= ($current_mon - $start_mon) + 1;	// add current month
$yr_diff	= ($current_yr - $start_yr);

// and add the rest of $base_yr
$day_diff	= $day_diff + $base_day_diff;
$mon_diff	= $mon_diff + $base_mon_diff;

// handle overflow of days
if ($day_diff >= $base_mon_max)
{
	$day_diff = $day_diff - $base_mon_max;
	$mon_diff = $mon_diff + 1;
}

// handle overflow of years
if ($mon_diff >= 12)
{
	$mon_diff = $mon_diff - 12;
	$yr_diff = $yr_diff + 1;
}

// the results are here:

// $yr_diff  	--> the years between the two dates
// $mon_diff 	--> the month between the two dates
// $day_diff 	--> the days between the two dates

// ****************************************************************************

// simple output of the results 
print "The difference between <b>".$base_yr."-".$base_mon."-".$base_day."</b> ";
print "and <b>".$current_yr."-".$current_mon."-".$current_day."</b> is:";
print "<br><br>";

// this is just to make it look nicer
$years = "years";
$days = "days";
if ($yr_diff == "1") $years = "year";
if ($day_diff == "1") $days = "day";

// here we go
print $yr_diff." ".$years.", ";
print $mon_diff." month and ";
print $day_diff." ".$days;

?>

