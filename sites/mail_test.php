<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
<p>&lt;?php</p>
<p>error_reporting(E_ALL);<br />
  ini_set('display_errors', TRUE);<br />
  ini_set('display_startup_errors', TRUE);</p>
<p><br />
  $to = &quot;mackm@rockisland.com&quot;;<br />
  $subject = &quot;Test mail&quot;;         </p>
<p>$message = &quot;Hello! This is a simple email message.&quot;;</p>
<p>$from = &quot;noreply@eample.com&quot;;</p>
<p>$headers = &quot;From: &quot;. $from;</p>
<p> mail($to,$subject,$message,$headers);</p>
<p>echo &quot;Mail Sent.&quot;; </p>
<p>?&gt;</p>
</body>
</html>