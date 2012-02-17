<html>
<head>
<title>Semester Countdown</title>
<meta property="og:title" content="ISU Semester Countdown" />
<meta property="og:type" content="website" />
<meta property="og:url" content="http://iastate.semestercountdown.com" />
<meta property="og:image" content="http://iastate.semestercountdown.com/fbimg.png" />
<meta property="og:site_name" content="ISU Semester Countdown" />
<meta property="fb:admins" content="500029869" />
</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=298537000208033";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<link href='http://fonts.googleapis.com/css?family=Telex' rel='stylesheet' type='text/css'>
<link href='sc.css' rel='stylesheet' type='text/css'>
<?php
date_default_timezone_set("America/Chicago");

if (isset($_GET['argyle_mode']))
{
    echo <<<EOL
<style>
#wrapper {
    background-image: url('argyle.png');
}
</style>
EOL;
}

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
    # normalize out of DST
    if (date("I", $start)=="1")
        $start += 60*60;

    if (date("I", $end)=="1")
        $end += 60*60;

    $secs = $end-$start;
    if ($secs <= 0)
        $days = 0;
    else
    {
        $days = ceil($secs/60/60/24);
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

// TODO: make better
$veisheaSchoolDays -= 5;

$semesterTotal = daysInDataInterval($data['semesterStart'], $data['semesterEnd']);
$daysComplete = $semesterTotal-$daysTilSemEnd;
$percComplete = number_format($daysComplete/$semesterTotal*100, 2);

?>

<!-- try ?argyle_mode -->
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

<tr class="gapRow"/>

<tr id="fbRow"><td class="l">&nbsp;</td>
<td class="r"> <div class="fb-like" data-href="http://iastate.semestercountdown.com" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div></td></tr>

<tr id="twitterRow"><td class="l">&nbsp;</td>
<td class="r"><a href="https://twitter.com/share" class="twitter-share-button" data-text="<?php echo $daysTilSemEnd?> days left in the semester!">Tweet</a></td></tr>

</table>
</div>

<script type="text/javascript">
!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");

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
