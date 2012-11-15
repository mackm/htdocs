<html>
<head>
<title>earthboxResRun</title>
</head>
<body>
<b>earthboxResRun</b><br><br>

<?php

require('earthboxRes.inc.php');


$twp = $_GET['twp'];
echo("twp: $twp<br>");
echo("errorFilePath: $errorFilePath<br>");

if ($twp == "3")
{
$twoWeekPeriods = 3;
$filePath = "../../sites/www.visitsanjuans.com/files/cvbcron/zia/";
$fileName = "earthboxRes_".$twoWeekPeriods.".csv";
$filePath = "$filePath$fileName";
earthboxRes($filePath,$twoWeekPeriods);
}
elseif ($twp == "26")
{
$twoWeekPeriods = 26;
$filePath = "../../sites/www.visitsanjuans.com/files/cvbcron/zia/";
$fileName = "earthboxRes_".$twoWeekPeriods.".csv";
$filePath = "$filePath$fileName";
earthboxRes($filePath,$twoWeekPeriods);
}
else
{
echo("incorrect get");	
}

?>
</body>

