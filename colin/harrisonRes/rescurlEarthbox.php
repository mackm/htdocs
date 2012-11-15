<html>
<head>
</head>
<body>
<b>Rescurl</b><br>
Earthbox Availability Grabber<br>

<?php


$nextDate = strtotime(date('Y-m-d'));
$x = rescurlSingleGet();
echo($x);
//runCron();
function runCron()
{
$filePath = 'csv/'; 
$startDate = strtotime(date('Y-m-d'));
$twoWeekPeriods = 3;
rescurlSingle($startDate,$twoWeekPeriods,$filePath);
}

function rescurlSingle($startDate,$island,$period,$csv)
{
global $nextDate, $islandAbr, $periodInt, $csvPath, $fp, $iPeriod;
for ($i=1; $i<=$periodInt; $i++)
	{
	$iPeriod = $i;
	if ($i > 1)
		{
		$nextDate = (14*86400) + $nextDate;
		}
	$curlReturn = rescurlGetCurl();
	$x = rescurlParseCurl($curlReturn);
	}
fclose($fp);
}


function rescurlSingleGet()
{
global $nextDate, $periodInt, $csvPath, $fp, $iPeriod;

echo("<br>nextDate: $nextDate");

$numberNights = 14;
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

$resFields = array(
	'hotel_id'=>urlencode("584"),
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
	//'zmonth'=>urlencode($zmonth),
	//'zday'=>urlencode($zday),
	//'zyear'=>urlencode($zyear),
	//'zmode'=>urlencode("display"),
	//'region'=>urlencode($islandAbr),
	//'wsearch'=>urlencode("Look It Up")
	);
$resPostFields = "";
foreach($resFields as $key=>$value) {
		$resPostFields .= $key.'='.$value.'&'; 
		echo("$key : $value");
		echo("<br>");
		}
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


function rescurlSingleParse($curlString)
{
global $nextDate, $islandAbr, $periodInt, $csvPath, $fp, $iPeriod, $propNamePrint;
//echo("curlString: $curlString");
$tables = preg_split('/Previous Two Weeks/', $curlString);
$tableCount = count($tables);
echo("tableCount: $tableCount");
$resTable = $tables[1];
$props = preg_split('/1x1.gif/', $resTable);
$propCount = count($props);

for ($i=1;$i<$propCount;$i++)
	{
	$prop = $props[$i];
	$propName = preg_split('/target="new">/', $prop);
	$propNameStrpos = strpos($propName[1], "</a>");
	$strpos = strpos($propName[1], "</a>");
	$propNameString = substr($propName[1],0,$propNameStrpos);
	$propNamePrint = '"' . $propNameString . '"';
	echo("<br><br>");
	echo("propNamePrint: |$propNamePrint|<br>");
	$nextDatePrint = date('Y-m-d', $nextDate);
	echo("nextDatePrint: $nextDatePrint<br>");
	echo("csvPath: $csvPath<br>");
	$availStrpos = strpos($propName[1], "x.gif");
	$setAvail = 1;
	if(strlen($availStrpos)==0)
		{
		$setAvail = 0;
		}
	rescurlDays($prop);
	echo("<br><br><br>");
	}
}


function runCronx()
{
// simulation of existing cron code
global $fp;
$filePath = 'csv/'; 
$date = date('Y-m-d');
$islands = array ('OI', 'SJ', 'LI');


// loop through islands array to produce unique .csv files for each island 
// each file shows availablity for 6 weeks from the date run
$periods = 3;
foreach ($islands as $island)
	{
	$fileName = $island."_load_webrev_".$periods.".csv";
	$fileNamePath = $filePath . $fileName;
	parse_web_res_html($date,$island,$periods,$fileNamePath);
   	}


// loop through islands array to produce unique .csv files for each island 
// each file shows availablity for 52 weeks from the date run
$periods = 26;
foreach ($islands as $island)
	{
	$fileName = $island."_load_webrev_".$periods.".csv";
	$fileNamePath = $filePath . $fileName;
	parse_web_res_html($date,$island,$periods,$fileNamePath);
  }
}

function parse_web_res_htmlx($startDate,$island,$period,$csv)
{
// loop through periods moving start date "$nextDate" forward
// run rescurlGetCurl for each start date to get back raw html "$curlReturn";
// pass $curlReturn to rescurlParseCurl() to parse raw hmtl and find individual properties
// rescurlParseCurl() uses rescurlDays() as a helper function to check availability on individual days

global $nextDate, $islandAbr, $periodInt, $csvPath, $fp, $iPeriod;
$nextDate = strtotime($startDate);
$islandAbr = $island;
$periodInt = $period;
$csvPath = $csv;
$fp = fopen($csvPath, 'w');
$nextDatePrint = date('Y-m-d', $nextDate);
echo("nextDatePrint: $nextDatePrint<br>");
echo("islandAbr: $islandAbr<br>");
echo("periodInt: $periodInt<br>");
echo("csvPath: $csvPath<br><br>");
for ($i=1; $i<=$periodInt; $i++)
	{
	$iPeriod = $i;
	if ($i > 1)
		{
		$nextDate = (14*86400) + $nextDate;
		}
	$curlReturn = rescurlGetCurl();
	$x = rescurlParseCurl($curlReturn);
	}
fclose($fp);
}


function rescurlGetCurlx()
{
global $nextDate, $islandAbr, $periodInt, $csvPath, $fp, $iPeriod;
$zmonth = date('n', $nextDate);
$zday = date('j', $nextDate);
$zyear = date('Y', $nextDate);
$resFields = array(
			'zmonth'=>urlencode($zmonth),
			'zday'=>urlencode($zday),
			'zyear'=>urlencode($zyear),
			'zmode'=>urlencode("display"),
			'region'=>urlencode($islandAbr),
			'wsearch'=>urlencode("Look It Up")
			);
$resPostFields = "";
foreach($resFields as $key=>$value) {
		$resPostFields .= $key.'='.$value.'&'; 
		}
rtrim($resPostFields,'&');			
$ch = curl_init();
$url = "http://www.webervations.com/magic-scripts/associations/sanjuan.asp";
$urlReferer = "http://www.webervations.com/magic-scripts/associations/sanjuan.asp";
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


function rescurlParseCurlx($curlString)
{
global $nextDate, $islandAbr, $periodInt, $csvPath, $fp, $iPeriod, $propNamePrint;
//echo("curlString: $curlString");
$tables = preg_split('/Previous Two Weeks/', $curlString);
$tableCount = count($tables);
echo("tableCount: $tableCount");
$resTable = $tables[1];
$props = preg_split('/1x1.gif/', $resTable);
$propCount = count($props);

for ($i=1;$i<$propCount;$i++)
	{
	$prop = $props[$i];
	$propName = preg_split('/target="new">/', $prop);
	$propNameStrpos = strpos($propName[1], "</a>");
	$strpos = strpos($propName[1], "</a>");
	$propNameString = substr($propName[1],0,$propNameStrpos);
	$propNamePrint = '"' . $propNameString . '"';
	echo("<br><br>");
	echo("propNamePrint: |$propNamePrint|<br>");
	$nextDatePrint = date('Y-m-d', $nextDate);
	echo("nextDatePrint: $nextDatePrint<br>");
	echo("csvPath: $csvPath<br>");
	$availStrpos = strpos($propName[1], "x.gif");
	$setAvail = 1;
	if(strlen($availStrpos)==0)
		{
		$setAvail = 0;
		}
	rescurlDays($prop);
	echo("<br><br><br>");
	}
}

function rescurlDaysx($prop)
{
global $nextDate, $islandAbr, $periodInt, $csvPath, $fp, $iPeriod, $propNamePrint;
echo("<hr>");
$propRes = preg_split('<td align="center" valign="center">', $prop);
$propResCnt = count($propRes);
$aDate = $nextDate;
$thisDate = $nextDate;
for ($j=1; $j<$propResCnt; $j++)
	{
	if ($j>1)
		{
		$thisDate = 86400 + $thisDate;
		}
	$thisDatePrint = date('Y-m-d', $thisDate);
	$dayAvailPos = strpos($propRes[$j], "x.gif");
	$dayAvail = 1;
	if(strlen($dayAvailPos)==0)
		{
		$dayAvail = 0;
		}
	$printLine = "$propNamePrint,$thisDatePrint,$dayAvail" . "\n";
	echo("$iPeriod x $j printLine: $printLine<br>");
	fwrite($fp, $printLine);
	}
}
?>
<br>
</body>