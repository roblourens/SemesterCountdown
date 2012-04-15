<?php
$indexFile = 'data/index.json';
$f = fopen($indexFile, 'r');
$indexJson = fread($f, filesize($indexFile));
fclose($f);
$index = json_decode($indexJson, true);
?>

<html>
<head>
<title>Semester Countdown</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
<link href='http://fonts.googleapis.com/css?family=Telex' rel='stylesheet' type='text/css'></link>
<link href='frontpage.css' rel='stylesheet' type='text/css'></link>
</head>
<body>
<div id="topContainer">
<div id="midContainer">
<div id="wrapper">
<h2>Semester Countdown</h2>
<h3>Is it over yet?</h3>
<div id="content">
<?php
foreach ($index as $schoolIndex)
{
    $schoolFile = 'conf/'.$schoolIndex.'.json';
    $f = fopen($schoolFile, 'r');
    $schoolJson = fread($f, filesize($schoolFile));
    fclose($f);
    $school = json_decode($schoolJson, true);

    $schoolName = $school['name'];
    echo "<div class='schoolRow'><a href='/$schoolIndex'>$schoolName</a></div>";
}
?>
</div>
</div>
</div>
</div>
</body>
</html>
