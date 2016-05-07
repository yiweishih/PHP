<?php
//This gets today’s date
$date =time () ;
//This puts the day, month, and year in seperate variables
$day = date(‘d’, $date) ;
$month = date(‘m’, $date) ;
$year = date(‘Y’, $date) ;
//Here we generate the first day of the month
$first_day = mktime(0,0,0,$month, 1, $year) ;
//This gets us the month name
$title = date(‘F’, $first_day) ;
//Here we find out what day of the week the first day of the month falls on
$day_of_week = date(‘D’, $first_day) ;
//Once we know what day of the week it falls on, we know how many blank days occure before it. If the first day of the week is a Sunday then it would be zero
switch($day_of_week){
case "Sun": $blank = 0; break;
case "Mon": $blank = 1; break;
case "Tue": $blank = 2; break;
case "Wed": $blank = 3; break;
case "Thu": $blank = 4; break;
case "Fri": $blank = 5; break;
case "Sat": $blank = 6; break;
}
//We then determine how many days are in the current month
$days_in_month = cal_days_in_month(0, $month, $year) ;
//Here we start building the table heads
echo "<table id=wp-calendar summary=Calendar>";
echo "<CAPTION>$title $year </CAPTION>";
echo "<tr>
<TH title=Monday scope=col abbr=Monday>M</TH>
<TH title=Tuesday scope=col abbr=Tuesday>T</TH>
<TH title=Wednesday scope=col abbr=Wednesday>W</TH>
<TH title=Thursday scope=col abbr=Thursday>T</TH>
<TH title=Friday scope=col abbr=Friday>F</TH>
<TH title=Saturday scope=col abbr=Saturday>S</TH>
<TH title=Sunday scope=col abbr=Sunday>S</TH>";
//This counts the days in the week, up to 7
$day_count = 1;
echo "<tr>";
//first we take care of those blank days
while ( $blank > 0 )
{
echo "<td></td>";
$blank = $blank-1;
$day_count++;
}
//sets the first day of the month to 1
$day_num = 1;
//count up the days, untill we’ve done all of them in the month
while ( $day_num <= $days_in_month )
{
if($day_num == $day) {
echo "<td id=today> $day_num </td>";
} else {
echo "<td > $day_num </td>";
}
$day_num++;
$day_count++;
//Make sure we start a new row every week
if ($day_count > 7)
{
echo "</tr><tr>";
$day_count = 1;
}
}
//Finaly we finish out the table with some blank details if needed
while ( $day_count >1 && $day_count <=7 )
{
echo "<td> </td>";
$day_count++;
}
echo "</tr></table>";
?>