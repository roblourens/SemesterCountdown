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
#semEndDiv {
    margin-top: 100px;
    font-size: 50px;
}
#daysTilSemEnd {
    font-size: 100px;
}

#sprBrkDiv {
    font-size: 40px;
}
#daysTilSprBrk {
    font-size: 80px;
}
</style>
<?php
date_default_timezone_set("America/Chicago");

function daysTil($data)
{
    $today = time();
    $future = mktime(0, 0, 0, $data['month'], $data['day'], $data['year']);

    $secsTil = $future-$today;
    if ($secsTil < 0)
        $daysTil = 0;
    else
    {
        $secsTil += 60*60*24; # need to count today
        $daysTil = floor($secsTil/60/60/24);
    }

    return $daysTil;
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

echo <<<EOL
<div id="semEndDiv">
There $semEndIsAre <span id="daysTilSemEnd" >$daysTilSemEnd</span> $semEndPlural left in the semester.
</div>
<br />
<div id="sprBrkDiv">
And there $sprBrkIsAre <span id="daysTilSprBrk">$daysTilSprBrk</span> $sprBrkPlural until spring break!
</div>
EOL;

?>

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
