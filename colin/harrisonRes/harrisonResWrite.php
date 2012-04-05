HarrisonRes Write...
<br />
<?php

$dir = "../../sites/www.visitsanjuans.com/files/cvbcron/weberv/";
// Open a known directory, and proceed to read its contents
echo("<br><br>check directory contents open dir: $dir");


if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            echo "<br>filename: $file : filetype: " . filetype($dir . $file) . "\n";
        }
        closedir($dh);
    }
}


$csvPath = "../../sites/www.visitsanjuans.com/files/cvbcron/am/availCheckAm.csv";
echo("<br><br>attempt open csvPath: $csvPath");

$fp = fopen($csvPath, 'w');
$csvLine = "propertyName, 2011/03/02, 1";
echo("<br>$csvLine");
$x = fwrite($fp, $csvLine);
echo("<br>x: $x");
echo("<br>");
fclose($fp);

$dir = "../../sites/www.visitsanjuans.com/files/cvbcron/am/";
echo("<br><br>check directory contents open dir: $dir");


// Open a known directory, and proceed to read its contents
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            echo "<br>filename: $file : filetype: " . filetype($dir . $file) . "\n";
        }
        closedir($dh);
    }
}


?>
<br />
Write Test finished