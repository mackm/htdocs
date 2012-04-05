<html>
<head>
<title>123 Res Error</title>
<style>

body
{
font-family: arial;	
background-color: white;
font-size: 12px;
}


#debug
{
width: 333px;
height: 444px;	
}


</style>
</head>

<body>
123 Res Error
<br>
<?php
$resDataError = $_POST['resDataError'];
echo("resDataError: $resDataError");
$errorFilePath = "../../sites/www.visitsanjuans.com/files/cvbcron/resGetErrors.txt";
$date = date('Y-m-d H:i:s');
$errorResponse = error_log("$date: $resDataError\n",3,$errorFilePath);
echo("<br>errorResponse: $errorResponse");
?>
</body>