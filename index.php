<?php
date_default_timezone_set("America/Chicago");

function schoolExists($school)
{
    $indexFile = 'data/index.json';
    $f = fopen($indexFile, 'r');
    $indexJson = fread($f, filesize($indexFile));
    fclose($f);
    $index = json_decode($indexJson, true);
    return in_array($school, $index);
}

$server_name = $_SERVER['SERVER_NAME'];
$name_parts = explode('.', $server_name);
$subdomain = $name_parts[0];
if ($subdomain == 'lolhost')
    $resBase = "/~rob/SemesterCountdown/";
else if ($subdomain == 'semestercountdown')
    $resBase = "/";

if (isset($_GET['s']) && schoolExists($_GET['s']))
    $subdomain = $_GET['s'];
else
    $subdomain = 'frontpage';

if ($subdomain != 'frontpage')
{
    $abbrevFile = 'conf/'.$subdomain.'.json';
    $f = fopen($abbrevFile, 'r');
    $abbrevJson = fread($f, filesize($abbrevFile));
    fclose($f);
    $conf = json_decode($abbrevJson, true);
    $abbrev = $conf['abbrev'];
}
else
{
    include('frontpage.php');
    exit(0);
}
?>

<html>
<head>
<title><?php echo $abbrev." Semester Countdown";?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta property="og:title" content="<?php echo $abbrev. ' Semester Countdown'?>" />
<meta property="og:type" content="website" />
<meta property="og:url" content="http://semestercountdown.com/<?php echo $subdomain ?>" />
<meta property="og:image" content="http://semestercountdown.com/fbimgs/<?php echo $subdomain ?>.png" />
<meta property="fb:admins" content="500029869" />
<script type="text/javascript">
 var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28941284-1']);
  _gaq.push(['_setDomainName', '.semestercountdown.com']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
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
<script type="text/javascript" src="<?php echo $resBase.'moment.min.js'?>"></script>
<script type="text/javascript" src="<?php echo $resBase.'jquery-1.7.1.min.js'?>"></script>
<script type="text/javascript" src="<?php echo $resBase.'jcanvas.min.js'?>"></script>

<link href='http://fonts.googleapis.com/css?family=Telex' rel='stylesheet' type='text/css'></link>
<link href="<?php echo $resBase.'sc.css'?>" rel='stylesheet' type='text/css'></link>

<?php
$dataPath = 'data/'.$subdomain.'.json';
$confPath = 'conf/'.$subdomain.'.json';
$dataJson = file_get_contents($dataPath);
$confJson = file_get_contents($confPath);
?>

<script type='text/javascript' src="<?php echo $resBase.'DateSpan.js'?>"></script>
<script type='text/javascript' src="<?php echo $resBase.'U.js'?>"></script>
<script type='text/javascript' src="<?php echo $resBase.'Conf.js'?>"></script>
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
    // 1 pixel is added somewhere
    var socialH = Math.max(0, $(window).height()-$('.detailsButton').height()-$('#wrapper').height()-$('#timeline').height()-1);
    $('#socialContainer').height(socialH);
}

doUpdate();

$(document).ready(function() {
    $('#moreDetailsButton').attr('href', 'javascript: U.moreDetails();');
    $('#fewerDetailsButton').attr('href', 'javascript: U.fewerDetails();');
    $('#argyleButton').attr('href', 'javascript: U.argyle();');
    <?php echo "Conf.init($confJson);"; ?>
});
</script>

<!-- try ?argyle_mode -->
<div id="main">
<a id="fewerDetailsButton" class="detailsButton" >-</a>
<a id="moreDetailsButton" class="detailsButton" >+</a>
<a id="argyleButton" class="detailsButton" style="font-size: 1em">♦</a>
<span id="title" style="margin-left: 50px; font-weight: bold"></span>
<span id="name"></span>
<div id="wrapper">
</div>

<div id="timeline"><canvas id="timelineCanvas" width="800" height="75"></canvas></div>

<div id="socialContainer">
<div id="socialSet">
<div class="fb-like" data-href="http://semestercountdown.com/<?php echo $subdomain ?>" data-send="false" data-layout="box_count" data-width="50" data-show-faces="false"></div>
<a href="https://twitter.com/share" class="twitter-share-button" data-text="How many days left in the semester?" data-count="vertical">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
<?php if ($subdomain == 'uiowa')
echo <<<EOL
<script type="text/javascript"><!--
google_ad_client = "ca-pub-3515112588362314";
/* uiowa */
google_ad_slot = "3116831064";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
EOL;
?>
</div>
<a id='frontpageLink' href='/'>Other schools?</a>
</div>
</div>

</html>
