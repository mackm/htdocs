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
echo("AM Res");
echo("<hr>");

//$availHeadPos1 = strpos($availHeadRay[1],'</span>');
//$availHeadVal = substr($availHeadRay[1],0,$availHeadPos1);
//$resProp = $_GET['resProp'];
parseRes("H");
parseRes("S");
parseRes("T");

function parseRes($resPropArg)
{
global $resProp;	
$resProp = $resPropArg;
for ($i=1; $i<=13; $i++)
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

$resKey = "resText$iKey";
$resText = $_POST[$resKey];

//echo("<textarea cols=100 rows=10>$resText</textarea>");

$resRay = preg_split('/<tr/', $resText);
$resRayCnt = count($resRay);
//echo("<br><br>");
//echo("resRayCnt: $resRayCnt");
//echo("<br><br>");

global $monthNum, $yearNum;

$monthYearString = $resRay[0];
$myRay = preg_split('/<month>/', $monthYearString);
$myRay = preg_split('/<year>/', $myRay[1]);
$mString = $myRay[0];
$yString = $myRay[1];
$monthNum = parseInt($mString);
$yearNum = parseInt($yString);

//echo("<br>mString: <textarea>$mString</textarea>");
//echo("<br>yString: <textarea>$yString</textarea>");
//echo("<br>month: <textarea>$monthNum</textarea>");
//echo("<br>year: <textarea>$yearNum</textarea>");

global $resCheckRay;

for ($i=0;$i<$resRayCnt; $i++)
{
	$gDay = strpos($resRay[$i],'class="gDay">');

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

$csvPath = "../sites/www.visitsanjuans.com/files/cvbcron/am/availCheckAm.csv";
$fp = fopen($csvPath, 'w');
foreach ($resCheckRay as $ray) 
	{
   	$resDateX = $ray['resDate'];
	$availX = $ray['avail'];
	$csvLine = "$proName,$resDateX,$availX";
	echo("<br>$propName, $resDateX, $availX");
	fwrite($fp, $printLine);
    }
fclose($fp);
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