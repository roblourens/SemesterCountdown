<html>
<head>
<title>Semester Countdown</title>
</head>
<body>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,800|Homenaje' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Glegoo|Ropa+Sans|Asap:400,700|Telex' rel='stylesheet' type='text/css'>
<style>

body {
    background: rgb(206,17,38); /* Old browsers */
    background: -moz-linear-gradient(top,  rgba(206,17,38,1) 0%, rgba(119,10,21,1) 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(206,17,38,1)), color-stop(100%,rgba(119,10,21,1))); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(top,  rgba(206,17,38,1) 0%,rgba(119,10,21,1) 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(top,  rgba(206,17,38,1) 0%,rgba(119,10,21,1) 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(top,  rgba(206,17,38,1) 0%,rgba(119,10,21,1) 100%); /* IE10+ */
    background: linear-gradient(top,  rgba(206,17,38,1) 0%,rgba(119,10,21,1) 100%); /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ce1126', endColorstr='#770a15',GradientType=0 ); /* IE6-9 */

    color: rgb(242,191,73);
    /* letter-spacing: .1em; */
    /* font-family: 'Open Sans', sans-serif; */
    /* font-family: 'Homenaje', sans-serif; */
    /* font-family: 'Glegoo', sans-serif; */
    /* font-family: 'Ropa Sans', sans-serif; */
    /* font-family: 'Asap', sans-serif; */
    font-family: 'Telex', sans-serif;
    text-shadow: 1px 1px 0px #733;
    margin: 0px;
}

#wrapper {
    width: 100%;
    height: 100%;
    background-image: url('grid_noise.png');
}

/*
    Homenaje: tight, robotic
    Glegoo:   wide, slightly serifed, thin
    Ropa:     tight, not crazy about it
    Asap:     rounded, 'normal'
    Telex:    just a little off-kilterness - nice
*/

h1 {
    margin-top: 50px;
    font-size: 70px;
    display: none;
}

table {
    font-size: 1.0em;
}

#topSpace {
    height: 7%;
}

#semEndRow {
    font-size: 2.7em;
}
#daysTilSemEnd {
    font-size: 2em;
}

#semTotal {
    font-size: .5em;
}

.percCompleteRow {
    font-size: 1.5em;
}
.percComplete {
    font-size: 1.7em;
}

.schoolDaysRow {
    font-size: 1.5em;
}
.schoolDays {
    font-size: 1.7em;
}


#sprBrkRow {
    font-size: 2.2em;
}
#daysTilSprBrk {
    font-size: 2em;
}

#veisheaRow {
    font-size: 2.2em;
}
#daysTilVeishea {
    font-size: 2em;
}
#riot {
    display: none;
}

table {
    margin-left: auto;
    margin-right: auto;
}
tr {
    vertical-align: text-bottom;
}
.l {
    text-align: right;
    white-space: nowrap;
}
.r {
    text-align: left;
}
.gapRow {
    height: 40px;
}
</style>
<?php
date_default_timezone_set("America/Chicago");

function dataToTime($data)
{
    return mktime(0, 0, 0, $data['month'], $data['day'], $data['year']);
}

function daysTilTime($future)
{
    return daysInInterval(time(), $future);
}

function daysInInterval($start, $end)
{
    $secs = $end-$start;
    if ($secs < 0)
        $days = 0;
    else
    {
        $secs += 60*60*24; # need to count today
        $days = floor($secs/60/60/24);
    }

    return $days;
}

function daysInDataInterval($dataStart, $dataEnd)
{
    return daysInInterval(dataToTime($dataStart), dataToTime($dataEnd));
}

function daysTil($data)
{
    $future = dataToTime($data);
    return daysTilTime($future);
}

function weekdaysTil($data)
{
    $future = dataToTime($data);
    $days = 0;
    $t = time();

    while ($t < $future)
    {
        // If the day is a weekday... (Sun: 0, Sat: 6)
        if (date('w', $t) % 6 != 0) {
            $days++;
        }

        $t = strtotime('+1 day', $t);
    }

    return $days;
}

function isAre($n)
{
    return $n == 1 ? 'is' : 'are';
}

function pluralDays($n)
{
    return $n == 1 ? 'day' : 'days';
}

$json = file_get_contents('iastate.json');
$data = json_decode($json, true);

$daysTilSemEnd = daysTil($data['semesterEnd']);
$daysTilSprBrk = daysTil($data['springBreak']);
$daysTilVeishea = daysTil($data['veishea']);

$semEndIsAre = isAre($daysTilSemEnd);
$semEndPlural = pluralDays($daysTilSemEnd);
$sprBrkIsAre = isAre($daysTilSprBrk);
$sprBrkPlural = pluralDays($daysTilSprBrk);
$veisheaIsAre = isAre($daysTilVeishea);
$veisheaPlural = pluralDays($daysTilVeishea);

$semEndSchoolDays = weekdaysTil($data['semesterEnd']);
$sprBrkSchoolDays = weekdaysTil($data['springBreak']);
$veisheaSchoolDays = weekdaysTil($data['veishea']);

$semesterTotal = daysInDataInterval($data['semesterStart'], $data['semesterEnd']);
$daysComplete = $semesterTotal-$daysTilSemEnd;
$percComplete = number_format($daysComplete/$semesterTotal*100, 2);

?>

<h1>Iowa State University</h1>

<div id="wrapper">
<div id="topSpace">&nbsp;</div>
<table>
<tr id="semEndRow"><td class="l">There <?php echo $semEndIsAre ?> <span id="daysTilSemEnd" ><?php echo $daysTilSemEnd?></span></td>
<td class="r"><span id="semTotal">/<?php echo $semesterTotal ?></span> <?php echo $semEndPlural?> left in the semester.</td></tr>
<tr class="percCompleteRow"><td class="percComplete l"><?php echo $percComplete ?></td>
<td class="r">% complete.</td></tr>

<tr class="schoolDaysRow"><td class="l"><span class="schoolDays"><?php echo $semEndSchoolDays ?></span></td>
<td class="r"> school days.</td></tr>

<tr class="gapRow" />

<tr id="sprBrkRow"><td class="l">There <?php echo $sprBrkIsAre ?> <span id="daysTilSprBrk"><?php echo $daysTilSprBrk?></td>
<td class="r"> <?php echo $sprBrkPlural?> until Spring break.</td></tr>

<tr class="schoolDaysRow"><td class="l"><span class="schoolDays"><?php echo $sprBrkSchoolDays ?></span></td>
<td class="r">school days.</td></tr>

<tr class="gapRow"/>

<tr id="veisheaRow"><td class="l">And there <?php echo $veisheaIsAre ?> <span id="daysTilVeishea"><?php echo $daysTilVeishea ?></td>
<td class="r"> <?php echo $sprBrkPlural ?> until <span id="riot">RIOT! </span>VEISHEA!</td></tr>
<tr class="schoolDaysRow"><td class="l"><span class="schoolDays"><?php echo $veisheaSchoolDays ?></span></td>
<td class="r">school days.</td></tr>
</div>

<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28941284-1']);
  _gaq.push(['_setDomainName', 'semestercountdown.com']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
</html>
