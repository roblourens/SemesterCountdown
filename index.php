<html>
<head>
<title>Semester Countdown</title>
</head>
<body>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,800' rel='stylesheet' type='text/css'>
<style>
body {
    background: rgb(206,17,38); /* Old browsers */
    background: -moz-linear-gradient(top,  rgba(206,17,38,1) 0%, rgba(140,12,25,1) 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(206,17,38,1)), color-stop(100%,rgba(140,12,25,1))); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(top,  rgba(206,17,38,1) 0%,rgba(140,12,25,1) 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(top,  rgba(206,17,38,1) 0%,rgba(140,12,25,1) 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(top,  rgba(206,17,38,1) 0%,rgba(140,12,25,1) 100%); /* IE10+ */
    background: linear-gradient(top,  rgba(206,17,38,1) 0%,rgba(140,12,25,1) 100%); /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ce1126', endColorstr='#8c0c19',GradientType=0 ); /* IE6-9 */

    color: rgb(242,191,73);
    font-family: 'Open Sans', sans-serif;
    text-align: center;
    text-shadow: 1px 1px 0px #733;
}

h1 {
    margin-top: 50px;
    font-size: 70px;
    display: none;
}

#semEndDiv {
    margin-top: 70px;
    font-size: 50px;
}
#daysTilSemEnd {
    font-size: 100px;
}
#semTotal {
    font-size: 20px;
}

#sprBrkDiv {
    font-size: 40px;
}
#daysTilSprBrk {
    font-size: 80px;
}

.schoolDaysSect {
    font-size: 30px;
}
.schoolDays {
    font-size: 50px;
}

.percCompleteSect {
    font-size: 30px;
}
.percComplete{
    font-size: 50px;
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

$semEndIsAre = isAre($daysTilSemEnd);
$semEndPlural = pluralDays($daysTilSemEnd);
$sprBrkIsAre = isAre($daysTilSprBrk);
$sprBrkPlural = pluralDays($daysTilSprBrk);

$semEndSchoolDays = weekdaysTil($data['semesterEnd']);
$sprBrkSchoolDays = weekdaysTil($data['springBreak']);

$semesterTotal = daysInDataInterval($data['semesterStart'], $data['semesterEnd']);
$daysComplete = $semesterTotal-$daysTilSemEnd;
$percComplete = number_format($daysComplete/$semesterTotal*100, 2);

?>

<h1>Iowa State University</h1>

<div id="semEndDiv">
There <?php echo $semEndIsAre ?> <span id="daysTilSemEnd" ><?php echo $daysTilSemEnd?></span><span id="semTotal">/<?php echo $semesterTotal ?></span> <?php echo $semEndPlural?> left in the semester.
</div>

<span class="percCompleteSect">
<span class="percComplete"><?php echo $percComplete ?>%</span> complete.
</span>

<span id="semEndSchoolDaysSect" class="schoolDaysSect">
<span id="semEndSchoolDays" class="schoolDays"><?php echo $semEndSchoolDays ?></span> school days.
</span>

<br />
<br />
<br />

<div id="sprBrkDiv">
And there <?php echo $sprBrkIsAre?> <span id="daysTilSprBrk"><?php echo $daysTilSprBrk?></span> <?php echo $sprBrkPlural?> until spring break!
</div>

<span id="sprBrkSchoolDaysSect" class="schoolDaysSect">
<span id="sprBrkSchoolDays" class="schoolDays"><?php echo $sprBrkSchoolDays ?></span> school days.
</span>

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
