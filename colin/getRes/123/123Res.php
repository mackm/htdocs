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


$csvPath = "../../sites/www.visitsanjuans.com/files/cvbcron/availCheck_123_$twp" . ".csv";
echo("csvPath: $csvPath<br>");

echo("<textarea id=debug name=debug>");
echo("$resData");
echo("</textarea>");

?>




<?php



$fp = fopen($csvPath, 'w');
fwrite($fp, $resData);


//fwrite($fp, "someting to file");
fclose($fp);
echo("<br>finished");

?>