<html>
<head>
<title>wvationsResRun</title>
</head>
<body>
<b>wvationsResRun</b><br><br>
<?php

require("wvationsRes.inc.php");

$twp = $_GET['twp'];
echo("twp: $twp<br>");

wvationsResRun($twp);

function wvationsResRun($twp)
{
echo("wvationsResRun()<br>");	
global $twp;
$date = date('Y-m-d');
$islands = array('OI', 'SJ', 'LI');
$periods = $twp;
$filePath = '../../sites/www.visitsanjuans.com/files/cvbcron/wvations/'; 
foreach ($islands as $island)
	{
	$fileName = $island."_wvationsRes_".$periods.".csv";
	$fileNamePath = $filePath . $fileName;
	echo("date:$date | island:$island | periods:$periods | fileNamePath:$fileNamePath <br>");
	$errorMessage = wvationsRes($date,$island,$periods,$fileNamePath);
   	}
}



?>
</body>
