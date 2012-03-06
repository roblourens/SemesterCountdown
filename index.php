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
<script type="text/javascript" src="moment.min.js"></script>
<script type="text/javascript" src="jquery-1.7.1.min.js"></script>

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

$json = file_get_contents('new2.json');
$data = json_decode($json, true);
?>

<script type='text/javascript' src='DateSpan.js'></script>
<script type='text/javascript' src='U.js'></script>
<script type='text/javascript'>
<?php echo "U.init($json);"; ?>

function doUpdate()
{
    U.update();
    setTimeout(doUpdate, 1000);
}

doUpdate();
</script>

<!-- try ?argyle_mode -->
<div id="wrapper">
<div id="topSpace">&nbsp;</div>
<table>

<?php
foreach ($data['events'] as $eventName => $event)
{
    // There are # days ...
    printf('<tr id="%1$sRow"><td class="l">There <span id="%1$s_isAre"></span> <span id="%1$s_daysTil" ></span></td>', $eventName);
    printf("<td class='r'>");

    // /total
    if (isset($event['refPoint']))
        printf('<span id="semTotal">/<span id="%1$s_total"></span></span> ', $eventName);

    // # days phrase
    printf('<span id="%1$s_days"></span> %2$s</td></tr>', $eventName, $event['phrase']);

    // % complete
    if (isset($event['refPoint']))
    {
        printf('<tr class="%1$s_percRow"><td class="percComplete l"><span id="%1$s_perc"></span></td>', $eventName)
        print('<td class="r">% complete.</td></tr>');
    }

    // # school days
    printf('<tr class="schoolDaysRow"><td class="l"><span id="%1$s_schoolDays" class="schoolDays"></span></td>');
    printf('<td class="r"> school days.</td></tr>');

    printf('<tr class="gapRow" />');
}
?>


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
