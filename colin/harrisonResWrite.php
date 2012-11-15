HarrisonRes Write...
<br />
<?php

$csvPath = "../../sites/www.visitsanjuans.com/files/cvbcron/am/availCheckAm.csv";
$fp = fopen($csvPath, 'w');
$csvLine = "propertyName, 2011/03/02, 1";
echo("<br>$csvLine");
fwrite($fp, $csvLine);
fclose($fp);

?>
<br />
Write finished