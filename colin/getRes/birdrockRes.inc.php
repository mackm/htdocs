<?php

require("earthboxRes.inc.php");
require_once("resPrintLine.inc");

function birdrockRes($filePath,$twoWeekPeriods)
{
global $errorFilePath;
resErrorSet();
echo("twoWeekPeriods: $twoWeekPeriods</b><br>");	
echo("filePath: $filePath</b><br>");	
global $propNamePrint;
$startDate = strtotime(date('Y-m-d'));
$propNamePrint = "Birdrock Inn";
birdrockRes1($startDate,$twoWeekPeriods,$filePath);
}


function birdrockRes1($startDate,$twoWeekPeriods,$filePath)
{
global $nextDate, $periodInt, $csvPath, $fp, $iPeriod;
global $numberNights;
global $firstDate;
global $errorFilePath;
//$errorFilePath = "rescurlEarthboxError.txt";
$firstDate = $startDate;
$numberNights = 14;
$nextDate = $startDate;
$periodInt = $twoWeekPeriods;
$csvPath = $filePath;
echo("csvPath: $csvPath");
$fp = fopen($csvPath, 'w');
for ($i=1; $i<=$periodInt; $i++)
	{
	$iPeriod = $i;
	if ($i > 1)
		{
		$nextDate = ($numberNights*86400) + $nextDate;
		}
	$curlGetResponse = rescurlBirdrockGet();
	$curlParseResponse = rescurlBirdrockParse($curlGetResponse);
	}
fclose($fp);
}


function rescurlBirdrockGet()
{
global $firstDate;	
global $nextDate, $periodInt, $csvPath, $fp, $iPeriod;
global $fromDate, $numberNights;
global $url;
echo("<hr>rescurlBirdrockGet - nextDate: $nextDate");

$fromDate = $nextDate + 86400;	

$toDate = $fromDate + ($numberNights*86400);
$nowDate =  strtotime(date('Y-m-d'));
$nowMonth = date('m', $nowDate);
$nowDay = date('d', $nowDate);
$nowYear = date('Y', $nowDate);

$fromMonth = date('m', $fromDate);
$fromDay = date('d', $fromDate);
$fromYear = date('Y', $fromDate);

$toMonth = date('m', $toDate);
$toDay = date('d', $toDate);
$toYear = date('Y', $toDate);

echo("<br> fromDate: $fromYear-$fromMonth-$fromDay");
echo(" toDate: $toYear-$toMonth-$toDay");

$resFields = array(
	'hotel_id'=>urlencode("590"),
	'user_click_id'=>urlencode("40105019"),
	'transaction_id'=>urlencode("-1"),
	'reservationcode_id'=>urlencode("1032"),
	'date_from_yearmonth'=>urlencode("$fromYear$fromMonth"),
	'date_from_day'=>$fromDay,
	'date_from_basedate'=>urlencode("$nowYear$nowMonth$nowDay"),
	'date_to_yearmonth'=>urlencode("$toYear$toMonth"),
	'date_to_day'=>urlencode("$toDay"),
	'date_to_basedate'=>urlencode("$nowYear$nowMonth$nowDay"),
	'num_nights'=>urlencode($numberNights),
	'num_adults'=>urlencode('2'),
	'num_children'=>urlencode('0'),
	'exchange_rates'=>urlencode('USD')
	);
$resPostFields = "";
foreach($resFields as $key=>$value) {
		$resPostFields .= $key.'='.$value.'&'; 
		//echo("$key : $value");
		//echo("<br>");
		}
//echo("<br>");
rtrim($resPostFields,'&');			
$ch = curl_init();
$url = "http://reservation.worldweb.com/Bookings-nr105/booking-list.html";
$urlReferer = "http://reservation.worldweb.com/Bookings-nr105/booking-list.html";
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $resPostFields);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_REFERER, $urlReferer);
curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$curlReturn = curl_exec($ch);
return $curlReturn;    
}

function rescurlBirdrockParse($curlGetResponse)
{
global $url;	
global $csvPath;
echo("<br>rescurlBirdrockParse");
$checkPageString = 'img src="https://secure.worldweb.com/Bookings-ClientTemplates/earthboxmotel/';
$checkPageString = 'https://secure.worldweb.com/Bookings-ClientTemplates/';

$checkPageStringPosition = strpos($curlGetResponse, $checkPageString);
if ($checkPageStringPosition === False)
	{
	echo("<textarea cols=100 rows=3>$curlGetResponse</textarea>");	
	$errorMessage = "Missing String in curl response -> url: $url -> checkPageString: $checkPageString -> unlinkCsv: $csvPath "; 
	errorLog($errorMessage,"unlinkCsv");
	return $curlReturn;    
	}
	
$availHeadRay = preg_split('/<span class="tableformheaderrow">/', $curlGetResponse);
$availHeadPos1 = strpos($availHeadRay[1],'</span>');
$availHeadVal = substr($availHeadRay[1],0,$availHeadPos1);
echo(" $availHeadVal");
$availPosition = strpos($curlGetResponse, '<span class="tableformheaderrow">Availability:');

if ($availPosition)
	{
	echo("<br>availPosition: $availPosition");
	rescurlBirdrockMarkAvail();
	}
else
	{
	echo("<br>availPosition: not exist");
	rescurlBirdrockCheckDays();
	}

echo("<br>");
}


function rescurlBirdrockMarkAvail()
{
global $fromDate, $numberNights, $fp;
global $propDatePrint;
global $propNamePrint;
$availDate = $fromDate;
for ($i=1;$i<=$numberNights;$i++)
	{
	if ($i > 1)
		{	
		$availDate = 86400 + $availDate;
		}
	$availDatePrint = date('Y-m-d', $availDate);
	echo("<br>$i");
	echo(" - $availDate");
	echo(" - $availDatePrint");
//	$propNamePrint = "Earthbox Inn";
	$dayAvail = 0;
	if (1==1) 
		{
		$propNameArg = $propNamePrint;
		$checkDateArg = $availDatePrint;
		$dayAvailArg = $dayAvail;
		resPrintLine($propNameArg,$checkDateArg,$dayAvailArg);
		}
	else
	 	{	
		$printLine = '"'. $propNamePrint . '"' . ",$availDatePrint,$dayAvail" . "\n";
		echo("<br>printLine: $printLine");
		fwrite($fp, $printLine);
		}
	}
}

function rescurlBirdrockCheckDays()
{
global $fromDate, $numberNights, $fp;
global $propNamePrint;
$checkDate = $fromDate;
for ($i=1;$i<=$numberNights;$i++)
	{
	if ($i > 1)
		{	
		$checkDate = 86400 + $checkDate;
		}
	$checkDatePrint = date('Y-m-d', $checkDate);
	$curlGetResponse = rescurlBirdrockGetSingle($checkDate);
	echo("<br>$i");
	echo(" - $checkDate");
	echo(" - $checkDatePrint");
	$checkPageString = 'https://secure.worldweb.com/Bookings-ClientTemplates/';
	$checkPageStringPosition = strpos($curlGetResponse, $checkPageString);
	if ($checkPageStringPosition === False)
		{
		echo("<textarea cols=100 rows=50>$curlGetResponse</textarea>");	
		$errorMessage = "Missing String in curl response -> url: $url -> checkPageString: $checkPageString -> unlinkCsv: $csvPath "; 
		errorLog($errorMessage,"unlinkCsv");
		}
	
	$availPosition = strpos($curlGetResponse, '<span class="tableformheaderrow">Availability:');
	if ($availPosition === False)
		{
		echo(" Not Available - ");
		$dayAvail = 1;
		}
	else
		{
		echo(" availPosition: $availPosition - ");
		$dayAvail = 0;
		}
	
	if (1==1) 
		{
		$propNameArg = $propNamePrint;
		$checkDateArg = $checkDatePrint;
		$dayAvailArg = $dayAvail;
		resPrintLine($propNameArg,$checkDateArg,$dayAvailArg);
		}
	else
	 	{	
		$printLine = '"'. $propNamePrint . '"' . ",$checkDatePrint,$dayAvail" . "\n";
		echo(" printLine: $printLine");
		fwrite($fp, $printLine);
		echo("<br>");
		}
	
	}
}


function rescurlBirdrockGetSingle($checkDate)
{
echo("<br>rescurlBirdrockGetSingle - checkDate: $checkDate");
$fromDate = $checkDate;
$toDate = $fromDate + 86400;
$nowDate =  strtotime(date('Y-m-d'));
$nowMonth = date('m', $nowDate);
$nowDay = date('d', $nowDate);
$nowYear = date('Y', $nowDate);

$fromMonth = date('m', $fromDate);
$fromDay = date('d', $fromDate);
$fromYear = date('Y', $fromDate);

$toMonth = date('m', $toDate);
$toDay = date('d', $toDate);
$toYear = date('Y', $toDate);

$resFields = array(
	'hotel_id'=>urlencode("590"),
	'user_click_idX'=>urlencode("40105019"),
	'transaction_idX'=>urlencode("-1"),
	'reservationcode_idX'=>urlencode("1032"),
	'date_from_yearmonth'=>urlencode("$fromYear$fromMonth"),
	'date_from_day'=>$fromDay,
	'date_from_basedate'=>urlencode("$nowYear$nowMonth$nowDay"),
	'date_to_yearmonth'=>urlencode("$toYear$toMonth"),
	'date_to_day'=>urlencode("$toDay"),
	'date_to_basedate'=>urlencode("$nowYear$nowMonth$nowDay"),
	'num_nights'=>urlencode($numberNights),
	'num_adults'=>urlencode('2'),
	'num_children'=>urlencode('0'),
	'exchange_rates'=>urlencode('USD')
	);
$resPostFields = "";
foreach($resFields as $key=>$value) {
		$resPostFields .= $key.'='.$value.'&'; 
		//echo("$key : $value");
		}
//echo("<br>");
rtrim($resPostFields,'&');			
$ch = curl_init();
$url = "http://reservation.worldweb.com/Bookings-nr105/booking-list.html";
$urlReferer = "http://reservation.worldweb.com/Bookings-nr105/booking-list.html";
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $resPostFields);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_REFERER, $urlReferer);
curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$curlReturn = curl_exec($ch);
return $curlReturn;    
}
?>
<br>
</body>