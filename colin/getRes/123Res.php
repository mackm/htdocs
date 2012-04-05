<html>
<head>
<title>123 Res Check</title>
<style>

body
{
font-family: arial;	
background-color: white;
font-size: 12px;
}


#debug
{
width: 333px;
height: 444px;	
}


</style>
</head>

<body>

<?php

//resErrorSet();
$twp = $_POST['twp'];
echo("123Res twp: $twp");
echo("<hr>");
$resData = $_POST['resData'];

echo("twp: $twp<br>");
$resDataRay = explode("\n Finished",$resData);
$count = count($resDataRay);
echo("count: $count");
echo("<br>resData[1]:" . $resDataRay[1]);

if ($count == 2)
{
$resData = trim($resDataRay[0]);
$csvPath = "../../sites/www.visitsanjuans.com/files/cvbcron/availCheck_123_$twp" . ".csv";
echo("<br>csvPath: $csvPath<br>");

echo("<textarea id=debug name=debug>");
echo("$resData");
echo("</textarea>");
$fp = fopen($csvPath, 'w');
fwrite($fp, $resData);
//fwrite($fp, "someting to file");
fclose($fp);
}
else
{
echo("Error Posted Data is Faulty/Not Finished");	
}

echo("<br>finished");

?>