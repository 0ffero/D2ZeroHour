<?php
$start_date_void  = 20190507;
$start_date_arc   = 20190514;
$start_date_solar = 20190521;
$rotationArray = array('void', 'arc', 'solar');
$zhWeekArray = array($start_date_void, $start_date_arc, $start_date_solar);

// We have to figure out which week this is (0 = void, 1 = arc, 2 = solar)
$date = date("Ymd");
$currentDayNum = date("d");
$dayNum= date("w"); // 0 = Sunday
$dateOffset = $dayNum - 2; // We are looking for Tuesday (ie 2)
if ($dateOffset<0) { $dateOffset+=7; }
$previousResetDate = date('Ymd', strtotime('-' . $dateOffset . ' day'));
// Create an array for this week
$isoResetDate = substr($previousResetDate,0,4) . "-" . substr($previousResetDate,4,2) . "-" . substr($previousResetDate,6,2);
for ($i=0; $i<7; $i++) {
    $weekArray[] = date("d", strtotime($isoResetDate . ' +' . $i . 'day'));
}

$offsetSinceWeek1 = $previousResetDate - $start_date_void; // gives us the offset in days - ie 28 or 35 or 42 or 49 etc
$weekOffset = ($offsetSinceWeek1 / 7) %3; // gives us the week offset from 0-day - either 0, 1 or 2
//$weekOffset = 1; // USED FOR TESTING THE WEEK OFFSET   0 => [Void]    1 => [Arc]    2 => [Solar]
if ($included != "yes") {
    echo $rotationArray[$weekOffset]; exit;
} else {
    $weekType = ucfirst($rotationArray[$weekOffset]);
}
?>