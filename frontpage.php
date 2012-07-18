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
<script type="text/javascript" src="jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="jquery-ui-1.8.21.custom.min.js"></script>
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

$(document).ready(function() {
    $('#schoolSelect').change(function() {
        window.location = "/"+$(this).attr('value');
    });
});
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
<select id="schoolSelect">
<option id="defaultOption">Schools</option>
<?php
function cmpByName($school1, $school2)
{
    $a = $school1['name'];
    $b = $school2['name'];

    $a = preg_replace("/(^| )the/i", " ", $a);
    $b = preg_replace("/(^| )the/i", " ", $b);
    $a = str_replace("University", "", $a);
    $b = str_replace("University", "", $b);
    $a = str_replace("of", "", $a);
    $b = str_replace("of", "", $b);
    $a = trim($a);
    $b = trim($b);

    if ($a > $b)
        return 1;
    else if ($a < $b)
        return -1;
    else
        return 0;
}

// collect all school names, and sort
$schools = array();
foreach ($index as $schoolIndex)
{
    $schoolFile = 'conf/'.$schoolIndex.'.json';
    $f = fopen($schoolFile, 'r');
    $schoolJson = fread($f, filesize($schoolFile));
    fclose($f);
    $school = json_decode($schoolJson, true);

    array_push($schools, $school);
}

usort($schools, "cmpByName");
foreach ($schools as $school)
    echo "<option value='".$school['id']."'>".$school['name']."</option>";
?>
</select>
<div id="request"><a href='request.php' id="requestLink">Request your school</a></div>
</div>
</div>
</div>
</div>
</body>
</html>
