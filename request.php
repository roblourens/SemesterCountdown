<html>
<head>
<title>Semester Countdown</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="jquery-1.7.1.min.js"></script>
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
<script type='text/javascript'>
function sendRequest()
{
    var url = $('#scheduleUrl').val().replace(/ /g, '');
    var fromEmail = $('#fromEmail').val().replace(/ /g, '');

    if (url == '')
        alert('Enter the URL for your school\'s schedule page');
    else
    {
        var data = {
            'url': url, 
            'fromEmail': fromEmail
        };
        $.post('handleRequest.php', data, function(data) {
                alert(data);
        });
    }
    return false;
}
</script>
</head>
<body>
<div id="topContainer">
<div id="midContainer">
<div id="wrapper">
<a href='/' style="text-decoration: none; color: black"><h2>Semester Countdown</h2></a>
<h3>Request a countdown for your school</h3>
<div id="content">
<form action="javascript: sendRequest();">
URL of school schedule:<br />
<input id="scheduleUrl"></input><br />
(Optional) Your email address, if you want an email when your school's countdown is up<br />
<input id="fromEmail"></input><br />
<input id="submitBtn" type="submit"></input>
</form>
</div>
</div>
</div>
</div>
</body>
</html>
