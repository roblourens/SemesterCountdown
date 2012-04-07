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
<script type="text/javascript" src="jcanvas.min.js"></script>

<link href='http://fonts.googleapis.com/css?family=Telex' rel='stylesheet' type='text/css'></link>
<link href='sc.css' rel='stylesheet' type='text/css'></link>
<?php
date_default_timezone_set("America/Chicago");

if (isset($_GET['argyle_mode']))
{
    echo <<<EOL
<style>
#main {
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

$json = file_get_contents('iastate.json');
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

$(document).ready(function() {
    $('#moreDetailsButton').attr('href', 'javascript: U.moreDetails();');
    $('#fewerDetailsButton').attr('href', 'javascript: U.fewerDetails();');

});
</script>

<!-- try ?argyle_mode -->
<div id="main">
<a id="fewerDetailsButton" class="detailsButton" >-</a>

<a id="moreDetailsButton" class="detailsButton" >+</a>
<div id="wrapper">
</div>

<div class="tableDiv">
<div class="group">
<div class="otherRow">
<div class="l"></div>
<div class="r">
<div class="fb-like" data-href="http://iastate.semestercountdown.com" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div>
</div>
</div>

<div class="otherRow">
<div class="l"></div>
<div class="r">
<a href="https://twitter.com/share" class="twitter-share-button" data-text="How many days left in the semester?">Tweet</a>
</div>
</div>
</div>
</div>

<div id="timeline"><canvas id="timelineCanvas" width="800" height="150"></canvas></div>
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
