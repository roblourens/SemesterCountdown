<html>
<head>
<title>Semester Countdown</title>
<meta property="og:title" content="ISU Semester Countdown" />
<meta property="og:type" content="website" />
<meta property="og:url" content="http://iastate.semestercountdown.com" />
<meta property="og:image" content="http://iastate.semestercountdown.com/fbimg.png" />
<meta property="og:site_name" content="ISU Semester Countdown" />
<meta property="fb:admins" content="500029869" />
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "92b1d824-e709-4b9a-a588-3fec47e291d8"}); </script>
</head>
<body>
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

$server_name = $_SERVER['SERVER_NAME'];
$name_parts = split('\.', $server_name);
$subdomain = $name_parts[0];

$dataPath = 'data/'.$subdomain.'.json';
$confPath = 'conf/'.$subdomain.'.json';
if (!file_exists($dataPath))
{
    echo "THERE'S NO WORDS THERE: " . $dataPath;
    exit;
}

if (!file_exists($confPath))
{
    echo "I DON'T KNOW YOU: ". $confPath;
    exit;
}

$dataJson = file_get_contents($dataPath);
$confJson = file_get_contents($confPath);
?>

<script type='text/javascript' src='DateSpan.js'></script>
<script type='text/javascript' src='U.js'></script>
<script type='text/javascript' src='Conf.js'></script>
<script type='text/javascript'>
<?php echo "U.init($dataJson); "; ?>

function doUpdate()
{
    U.update();
    Conf.doCssUpdate();
    setSocialH();

    setTimeout(doUpdate, 500);
}

// set social container height
// should call this on window resize, but adding a resize handler breaks this 
// version of jcanvas for some reason
function setSocialH()
{
    var socialH = Math.max(0, $(window).height()-$('.detailsButton').height()-$('#wrapper').height()-$('#timeline').height());
    $('#socialContainer').height(socialH);
}

doUpdate();

$(document).ready(function() {
    $('#moreDetailsButton').attr('href', 'javascript: U.moreDetails();');
    $('#fewerDetailsButton').attr('href', 'javascript: U.fewerDetails();');
    <?php echo "Conf.init($confJson);"; ?>
});
</script>

<!-- try ?argyle_mode -->
<div id="main">
<a id="fewerDetailsButton" class="detailsButton" >-</a>

<a id="moreDetailsButton" class="detailsButton" >+</a>
<div id="wrapper">
</div>

<div id="timeline"><canvas id="timelineCanvas" width="800" height="75"></canvas></div>

<div id="socialContainer">
<div id="socialSet">
<span class='st_fblike_vcount' displayText='Facebook Like'></span>
<span class='st_twitter_vcount' displayText='Tweet'></span>
</div>
</div>
</div>

</html>
