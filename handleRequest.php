<?php
date_default_timezone_set("America/Chicago");

$fromEmail = ''; 
if (isset($_POST['fromEmail']))
    $fromEmail = $_POST['fromEmail'];

$requestUrl = $_POST['url'];

$requestsFile = 'requests.json';
$f = fopen($requestsFile, 'r+');
$requestsJson = fread($f, filesize($requestsFile));
$requests = json_decode($requestsJson);

$newRequest = Array();
$newRequest['fromEmail'] = $fromEmail;
$newRequest['url'] = $requestUrl;
$newRequest['date'] = date('M j, Y: H:i:s');
array_push($requests, $newRequest);

ftruncate($f, 0);
fseek($f, 0);
fwrite($f, json_encode($requests));
fclose($f);

echo "Received your request - I'll get right on it!";
?>
