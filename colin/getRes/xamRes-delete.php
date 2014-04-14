<html>
<head>
<title>AM Res Check</title>
<style>

body
{
background-color: white;
}

.res
{
float:left;
margin-left: 10px;
font-size: 12px;
color: black;	
}

</style>
</head>

<body>

<?php

require_once('resPrintLine.inc');
resErrorSet();
echo("AM Res twp: $twp");
echo("<hr>");
$twp = $_GET['twp'];
echo("twp: $twp<br>");

//$availHeadPos1 = strpos($availHeadRay[1],'</span>');
//$availHeadVal = substr($availHeadRay[1],0,$availHeadPos1);
//$resProp = $_GET['resProp'];
//require("resPrintLine.inc");

if ($twp == "3")
{
amRes3();
}
elseif ($twp == "26")
{
amRes26();	
}

function amRes3()
{
$filePath = "../../sites/www.visitsanjuans.com/files/cvbcron/availCheck_am_3.csv";
amRes($filePath,1);
}

function amRes26()
{
$filePath = "../../sites/www.visitsanjuans.com/files/cvbcron/availCheck_am_26.csv";
amRes($filePath,13);
}

function amRes($filePath,$twoWeekPeriods)
{
global $csvPath, $iTwp, $fp;	
$csvPath = $filePath;
echo("csvPath: $csvPath<br>");

$iTwp = $twoWeekPeriods;
$fp = fopen($csvPath, 'w');

// code for debugging fix on 4/13/2014
//global $fpDebug;
//$csvPathDebug = $csvPath . ".debug.php";
//$fpDebug = fopen($csvPathDebug, 'w');


parseRes("H");
parseRes("S");
parseRes("T");
fclose($fp);
}


function parseRes($resPropArg)
{
global $iTwp;	
global $resProp;	
$resProp = $resPropArg;
for ($i=1; $i<=$iTwp; $i++)
{
echo("<div class=res>");
parseRes1($i);	
echo("</div>");
}
}

?>
</body>
</html>

<?php

function parseRes1($iKey)
{
global $resCheckRay;	
global $resProp;	
global $fp;

$resKey = "resText$iKey";
$resText = $_POST[$resKey];



// code for debugging fix on 4/13/2014
//echo("<br><br><textarea style='width: 1200px; height: 555px;'>$resText</textarea><br><br>");
//global $csvPath, $fpDebug;
//fwrite($fpDebug, $resText);



$resRay = preg_split('/<tr/', $resText);
$resRayCnt = count($resRay);

global $monthNum, $yearNum;

$monthYearString = $resRay[0];
$myRay = preg_split('/<month>/', $monthYearString);
$myRay = preg_split('/<year>/', $myRay[1]);
$mString = $myRay[0];
$yString = $myRay[1];
$monthNum = parseInt($mString);
$yearNum = parseInt($yString);

//checkdate ( int $month , int $day , int $year)
//echo("<br>mString: <textarea>$mString</textarea>");
//echo("<br>yString: <textarea>$yString</textarea>");
//echo("<br>month: <textarea>$monthNum</textarea>");
//echo("<br>year: <textarea>$yearNum</textarea>");

global $resCheckRay;

for ($i=0;$i<$resRayCnt; $i++)
{
	$gDay = strpos($resRay[$i],'class="gDay">');



//old data scraping 
if (1==2)
{

	$H0 =	strpos($resRay[$i],'class="r0"><td align="left">H');
	$H1 =	strpos($resRay[$i],'class="r1"><td align="left">H');
	$H7 =	strpos($resRay[$i],'"left">H7');
	
	$S0 =	strpos($resRay[$i],'class="r0"><td align="left">S');
	$S1 =	strpos($resRay[$i],'class="r1"><td align="left">S');

	$TL0 =	strpos($resRay[$i],'class="r0"><td align="left">TL');
	$TL1 =	strpos($resRay[$i],'class="r1"><td align="left">TL');

	$TU0 =	strpos($resRay[$i],'class="r0"><td align="left">TU');
	$TU1 =	strpos($resRay[$i],'class="r1"><td align="left">TU');

	$TU99  = strpos($resRay[$i],'"left">TU99');
}
// new data scraping 4/13/2014
	$H0 =	strpos($resRay[$i],'class="r0"><td class="name">H');
	$H1 =	strpos($resRay[$i],'class="r1"><td class="name">H');
	$H7 =	strpos($resRay[$i],'class="name">H7');
	
	$S0 =	strpos($resRay[$i],'class="r0"><td class="name">S');
	$S1 =	strpos($resRay[$i],'class="r1"><td class="name">S');

	$TL0 =	strpos($resRay[$i],'class="r0"><td class="name">TL');
	$TL1 =	strpos($resRay[$i],'class="r1"><td class="name">TL');

	$TU0 =	strpos($resRay[$i],'class="r0"><td class="name">TU');
	$TU1 =	strpos($resRay[$i],'class="r1"><td class="name">TU');

	$TU99  = strpos($resRay[$i],'class="name">TU99');
	
	
	

	if ($gDay > 0 )
	{
		$gDayNot = strpos($resRay[$i],'<td colspan="2">');
//		echo("<br>gDAY | gDayNot: $gDayNot");
		if ($gDayNot === false)
			{
			$resCheckRay = makeDayRay($resRay[$i]);	
			}
	}
	
	if (($H0 > 0 || $H1 > 0) && $H7 === false && $resProp == "H")
	{
		//echo("<br>Harrison");
		checkAvail($resRay[$i]);
		$propName = "Harrison House";
	}

	if ($H7 > 0 && 1==2)
	{
		echo("<br>Harrison Wait List");	
	}

	if (($S0 > 0 || $S1 > 0) && $resProp == "S")
	{
		//echo("<br>Sunshine Suite");
		checkAvail($resRay[$i]);
		$propName = "Sunshine Suite";
	}
	
	if (($TU0 > 0 || $TU1 > 0) && $TU99 === false && $resProp == "T")
	{
		//echo("<br>Tucker House");
		checkAvail($resRay[$i]);
		$propName = "Tucker House";
	}
	
	if (($TL0 > 0 || $TL1 > 0) && $TU99 === false && $resProp == "T")
	{
		//echo("<br>Tucker Lodge");
		checkAvail($resRay[$i]);
		$propName = "Tucker House";
	}

	if ($TU99 > 0 && 1==2)
	{
		echo("<br>Tucker Wait List");	
	}
	//echo("<br>$i $gDay | $H0 | $H1 | $H7 <br><textarea cols=100 rows=7>$resRay[$i]</textarea>");
}

$theCnt = count($resCheckRay);
//echo("<font color=red>theCnt:$theCnt</font>");

foreach ($resCheckRay as $ray) 
	{
	$propNamePrint = $propName;	
   	$availDatePrint = $ray['resDate'];
	$dayAvailPrint = $ray['avail'];
	$dayAvailPrint = parseInt($dayAvailPrint);
	if ($dayAvailPrint == 0)
	{
	$dayAvailPrint = "0";	
	}
	else
	{
	$dayAvailPrint = "1";	
	}
	
//	$dayAvailPrint = parseInt($dayAvailPrint);
//	echo("<br>$propNamePrint, $availDatePrint, $dayAvailPrint");
	$checkDatePrint = $availDatePrint;
	echo("<br> . ");

//  $errorMessage = "Missing String in curl response -> url: $url -> checkPageString: $checkPageString -> unlinkCsv: $csvPath "; 
//	errorLog($errorMessage,"unlinkCsv");
	
	resPrintLine($propNamePrint,$checkDatePrint,$dayAvailPrint);
//	$printLine = '"'. $propNamePrint . '"' . "," . $availDatePrint . "," . $dayAvailPrint . "\n";
//	$printLine = "$propNamePrint,$availDatePrint,$dayAvailPrint" . "\n";
//	fwrite($fp, $printLine);
	
    }

}

function makeDayRay($dayString)
{
global $monthNum, $yearNum;

//echo("makeDayRay....");
//echo("<br><textarea cols=100 rows=7>$dayString</textarea><br>");
$dayRay = preg_split('/<td/', $dayString);
$dayRayCnt = count($dayRay);
//echo("dayRayCnt: $dayRayCnt");


$firstDay = "yes";
for ($i=0; $i<=$dayRayCnt; $i++)
{
$dayNum = parseInt($dayRay[$i]);	
	if($dayNum === false)
	{
//	echo(".... not number.... <br>");	
	}
	else
	{
//	echo("dayNum: $dayNum firstDay: $firstDay<br>");
	
	if ($firstDay == "no" && $dayNum == 1)
		{
		$monthNum = $monthNum + 1;
		if ($monthNum == 13)
			{
			$monthNum = 1;
			$yearNum = $yearNum + 1;	
			}
		}

	$resDate = "$monthNum/$dayNum/$yearNum";
//	$resDate = "$yearNum" . "-$monthNum" . "-$dayNum";
	
	$monthCheck = $monthNum;
	$dayCheck = $dayNum;
	$yearCheck = $yearNum;
	
	if ($i == 12)
		{
		//$yearCheck = "blahh...";	
		}
	
	//echo("resDate: $resDate<br>");
	$isDate = checkdate($monthCheck,$dayCheck,$yearCheck);
	if ($isDate === false)
		{
	//echo("date is false<br>");	
		$errorMessage = "amRes.php invalid date numbers M:".$monthCheck.' D:'.$dayCheck.'Y: '.$yearCheck;
		errorLog($errorMessage,"unlinkCsv");		
		}
	else
	  	{
		//echo("isDate<br>");	
		}

//	echo("resDate: $resDate<br><br>");
		
	$dateCheckRay[$resDate]['resDate'] = $resDate;
	$dateCheckRay[$resDate]['avail'] = 1;

	if ($firstDay == "yes")	
		{
		$firstDay = "no";
		}

	}
}

return $dateCheckRay;
//echo("start for each check");
}
  

function checkAvail($resCheckStr)
{
global $resCheckRay;	

foreach ($resCheckRay as $ray) 
	{
   	$resDateX = $ray['resDate'];
	$availX = $ray['avail'];
		
	$resDateCheck = "|$resDateX";
	$availCheck = strpos($resCheckStr,$resDateCheck);
	
	if ($availCheck === False)
		{
		$availNew = $availX;	
		}
	else
		{
		$availNew = 0;	
		}
//	echo("<br> $resDateX | $availX -> $availCheck | $availNew");
	$resCheckRay[$resDateX]['avail'] = $availNew;
	}

}


function parseInt($string) {
//	return intval($string);
	if(preg_match('/(\d+)/', $string, $array)) {
		return $array[1];
	} else {
		return false;
	}
}

?>