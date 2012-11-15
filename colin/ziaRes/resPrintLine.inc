<?php

function resErrorLog($errorMessage, $unlinkCsv = "no")
{
global $csvPath;
global $errorFilePath;
echo("<br>error logged to: $errorFilePath - $errorMessage<br>");
$date = date('Y-m-d H:i:s');
$errorResponse = error_log("$date: $errorMessage\n",3,$errorFilePath);
if ($unlinkCsv == "unlinkCsv")
	{
	unlink($csvPath);	
	echo("<br>Exiting All Processes<br>");
	exit();
	}
}

function resPrintLine($propNamePrint,$checkDatePrint,$dayAvailPrint)
{
global $fp;	
$printLine = '"'. $propNamePrint . '"' . ",$checkDatePrint,$dayAvailPrint" . "\n";
echo(" resPrintLine() resPrintLine: --> $printLine");
fwrite($fp, $printLine);	
}

?>