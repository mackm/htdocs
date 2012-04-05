<html>
<head>
</head>
<body>
<b>Rescurl</b><br>
WebRes Availability Grabber<br>
<?php

errorLog();

//runCron();



function errorLog()
{
   error_log("Big trouble, we're all out of FOOs!", 3," errorLog.txt");
}


function runCron()
{
global $fp;
$filePath = 'csv/'; 
$date = date('Y-m-d');
$islands = array ('OI', 'SJ', 'LI');
$periods = 3;
foreach ($islands as $island)
	{
	$fileName = $island."_load_webrev_".$periods.".csv";
	$fileNamePath = $filePath . $fileName;
	parse_web_res_html($date,$island,$periods,$fileNamePath);
   	}

$periods = 26;
foreach ($islands as $island)
	{
	$fileName = $island."_load_webrev_".$periods.".csv";
	$fileNamePath = $filePath . $fileName;
	parse_web_res_html($date,$island,$periods,$fileNamePath);
  }
}

function parse_web_res_html($startDate,$island,$period,$csv)
{
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


function rescurlGetCurl()
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


function rescurlParseCurl($curlString)
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

function rescurlDays($prop)
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