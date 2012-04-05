<?php

function resErrorSet()
{
global $errorFilePath;
$errorFilePath = "../../sites/www.visitsanjuans.com/files/cvbcron/123/resGetErrors.txt";
}

function errorLog($errorMessage, $unlinkCsv = "no")
{
global $csvPath;
global $errorFilePath;
echo("<br>error logged to: $errorFilePath - $errorMessage<br>");
$date = date('Y-m-d H:i:s');
$errorResponse = error_log("$date: $errorMessage\n",3,$errorFilePath);
if ($unlinkCsv == "unlinkCsv")
	{
	unlink($csvPath);	
	echo("unlinked: $csvPath");
	echo("<br>Exiting All Processes<br>");
	exit();
	}
}

function resPrintLine($propNamePrint,$checkDatePrint,$dayAvailPrint)
{
global $fp;	

echo(" checkDatePrint:$checkDatePrint ");
$checkDateRay = explode('/',$checkDatePrint);
$cnt = count($checkDateRay);
if ($cnt > 1)
{
//echo("checkDatePrint wrong format: $checkDatePrint");
$mo = $checkDateRay[0];
$day = $checkDateRay[1];
$yr = $checkDateRay[2];

$checkDatePrint = "$yr" . "-$mo" . "-$day";
echo(" + ");	

}
else
{
echo(" . ");	
}

$printLine = '"'. $propNamePrint . '"' . ",$checkDatePrint,$dayAvailPrint" . "\n";


echo("$printLine");
fwrite($fp, $printLine);	
}

?>