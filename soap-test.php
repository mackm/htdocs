<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
  // create a connection to the local host mono .NET pull back the wsdl to get the functions names
  // and also the parameters and return values
  
  $client = new SoapClient("http://irm.resortdata.com/RDPWinCentralSvc/IRMPublic.asmx?wsdl",
    array(
      "trace"      => 1,		// enable trace to view what is happening
      "exceptions" => 0,		// disable exceptions
      "cache_wsdl" => 0) 		// disable any caching on the wsdl, encase you alter the wsdl server
  );
  
  if (is_soap_fault($client)) {
    echo 'Constructor failed';
    trigger_error("SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})", E_USER_ERROR);
  }
  else{
    echo '<h1>SOAP object initialized</h1>';
    echo '<h2>WSDL = http://irm.resortdata.com/RDPWinCentralSvc/IRMPublic.asmx?wsdl</h2>';
  }
  
  echo '<h1>Enumerate methods</h1>';
  $array = $client->__getFunctions();
  
  foreach ($array as $row)
  {
    echo $row.'<br />';
  }
  echo '<h1>Enumerate data types</h1>';
  $array = $client->__getTypes();
  
  foreach ($array as $row)
  {
    echo $row.'<br />';
  }
  echo '<h1>Invoke actual methods</h1>';
  echo '<h2>Version()</h2>';
  $ver = $client->Version();
  
  echo '<strong>Version = </strong>'.$ver->VersionResult.'<br /><br />';
  
    // display what was sent to the server (the request)
  echo "<strong><p>Version Request :</strong>".htmlspecialchars($client->__getLastRequest()) ."</p>";
  // display the response from the server
  echo "<strong><p>Version Response:</strong>".htmlspecialchars($client->__getLastResponse())."</p>";
  
  echo '<h2>GDSLoginResponse GDSLogin(GDSLogin $parameters)</h2>';
  
  // struct irmWebSvcCredentials { string LogonID; string Password; string DataPath; string DatabaseID; }
  $irmWebSvcCredentials = array (
                                  'LogonID' => "userid",
                                  'Password' => "password",
                                  'DatabaseID' => "database"
                                  );
  
  // struct irmLoginInfo { string Email; string IDNum; string Password; string Type; boolean HideEMail; boolean ByZipCode; boolean AllowOLDOwner; }
  $irmLoginInfo = array (
                         'Email' => 'mack@swiftsuremarketing.com',
                         'IDNum' => '42',
                         'Password' =>'password',
                         'HideEMail' => true,
                         'ByZipCode' => false,
                         'IncludeNameAddress' => false,
                         'AllowOLDOwner' => false
                        );
                        
  //struct Login_irmRQ { irmWebSvcCredentials Credentials; irmLoginInfo LoginInfo; boolean IncludeNameAddress; boolean SkipPasswordValidation; }
  $Login_irmRQ = array (
                        'Credentials' => $irmWebSvcCredentials,
                        'LoginInfo' => $irmLoginInfo,
                        'IncludeNameAddress' => false,
                        'SkipPasswordValidation' => true
                       );

 $GDSLogin = array ('SysLoginRQ' => $Login_irmRQ);
                        
 $RC = $client->GDSLogin($GDSLogin);
 
 if ($RC->GDSLoginResult->Status == 'Success')
   echo '<h2>Login Sucessful</h2>';
 else
     echo '<h2>Login failed</h2>';
     
  echo "<strong><p>Login Request :</strong>".htmlspecialchars($client->__getLastRequest()) ."</p>";
  echo "<strong><p>Login Response:</strong>".htmlspecialchars($client->__getLastResponse())."</p>";  

?>

</body>
</html>