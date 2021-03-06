<?php

function resErrorSet()
{
global $errorFilePath;
$errorFilePath = "../../sites/www.visitsanjuans.com/files/cvbcron/resGetErrors.txt";
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
if (strlen($day) == 1)
	{
	$day = "0$day";	
	}

if (strlen($mo) == 1)
	{
	$mo = "0$mo";	
	}
$checkDatePrint = "$yr" . "-$mo" . "-$day";
echo(" +0+ ");	
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