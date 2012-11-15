<html>
<head>
<title>birdrockResRun</title>
</head>
<body>
<b>birdrockResRun</b><br><br>
<?php

require("birdrockRes.inc.php");

$twp = $_GET['twp'];
echo("twp: $twp<br>");
echo("errorFilePath: $errorFilePath<br>");

if ($twp == "3")
{
$twoWeekPeriods = 3;
$filePath = "../../sites/www.visitsanjuans.com/files/cvbcron/zia/";
$fileName = "birdrockRes_".$twoWeekPeriods.".csv";
$filePath = "$filePath$fileName";

birdRockRes($filePath,$twoWeekPeriods);

}
elseif ($twp == "26")
{
$twoWeekPeriods = 26;
$filePath = "../../sites/www.visitsanjuans.com/files/cvbcron/zia/";
$fileName = "birdrockRes_".$twoWeekPeriods.".csv";
$filePath = "$filePath$fileName";

birdRockRes($filePath,$twoWeekPeriods);

}
else
{
echo("incorrect get");	
}

?>
</body>
