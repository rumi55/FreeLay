<?
$number = "120";
$this_year = date('Y');

function get_date($day, $year)    {
    $number_date =  date("m-d-y", mktime(0, 0, 0, 0, $day, $year));
    return $number_date;
}

$your_date = get_date($number, $this_year);

echo $your_date;

?>
	
Reply With Quote