<?php
error_reporting(E_ALL|E_STRICT);
ini_set( 'display_errors', 1);
include("settings1.php");

if((sizeof($ipadresses) > 0) && (!checkIPs($ipadresses)))
{ echo "You may not use this script from IP Adress: ".$_SERVER['REMOTE_ADDR']; exit();}

$sname = $_SERVER['SERVER_NAME'];
$sname = str_replace(".","", $sname);

if(isset($usecookies) && $usecookies)
{  $session = false;
}
else
{ $session = true;
  session_id('t69'.$sname);
  ini_set('date.timezone', @date_default_timezone_get());  //alternative: if(!ini_get('date.timezone')) {date_default_timezone_set('GMT');} 
  if(!@is_writable(session_save_path()))
    $session = false;
  else
    session_start();
}

if(!isset($embedded))
{ 
$unsafeaccess = 0;
if($password == "opensecret")
   $unsafeaccess = 1; 
if(sizeof($ipadresses)==0)
   $unsafeaccess += 2; 
   
function check()
{ 	global $session;
	if($session)
	{   $file = @fopen("approve.php", "r"); 
		$bom = fread($file, 3); 
		if ($bom == b"\xEF\xBB\xBF") 
		{ echo '<script type="text/javascript">
				alert(\'BOM header found! Use another text editor!\');
			</script>';
		  exit;
		}
    } 
}

if(isset($_POST['username']) && isset($_POST['pswd']))
{ $pswd = $_POST['pswd'];
  if(($_POST['username'] == $username) && (($md5hashed && (md5($pswd) == $password )) || (!$md5hashed && ($pswd == $password))))
  { 
    if($session)
	{	check();
		$_SESSION['tripleedit'] = 'open';
		session_write_close();
	}
	else
	{  	$seed = @date("m Y").get_seed()."randomtext";	// for "strict standards" data() gives the warning "It is not safe to rely on the system's timezone settings."
		$encseed = stripslashes(stripslashes(stripslashes(convert_uuencode(base64_encode($seed)))));  // some servers escape cookies
		setcookie("tripleedit", $encseed);
		//setcookie("tripleedit", "magic");
	}
	$refererlength = strlen($_SERVER["SERVER_NAME"]);
    if(isset($_GET['url']))
      header('Location: '.urldecode($_GET['url'])); //Replace index.php with what page you want to go to after succesful login
    else
      header('Location: product-edit.php'); //Replace index.php with what page you want to go to after succesful login
	if(isset($_GET['url']))
	  echo "Redirection problem for ".$_GET['url'];
	else 
	  echo "Redirection to edit pages is impossible.";
    exit;
  } else {
    echo '<script type="text/javascript">
         alert(\'Wrong Username or Password, Please Try Again!\');
     </script>';
  }
}

echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title> Triple Edit Login </title>
</head>
<body>'; 
  $warnings = "";
  if ($unsafeaccess != 0)   {  // Do not change this line!!!
    if ($unsafeaccess & 1)
		$warnings .= "Change the password in the file \\'settings1.php\\'!\\n\\n";
    if ($unsafeaccess & 2)
		$warnings .=  "Set safe IP addresses in the file \\'settings1.php\\'!";
	if($warnings != "")
	echo "<script>alert('".$warnings."');</script>";
}
echo "<br/>";
if($session == true) echo "Session"; else echo "Cookie";
echo "<br/>IP address = ".$_SERVER['REMOTE_ADDR'];
if(!isset($_POST['username'])) $_POST['username'] = "";
if(!isset($_POST['pswd'])) $_POST['pswd'] = "";
echo '

<p/>&nbsp;<p/>&nbsp;<p/><p/><p/><p/>
<center>
<form method="post" action="">
<table>
<tr><td>User:</td><td><input type="input" name="username" value = "'.$_POST['username'].'"></td></tr>
<tr><td>Password:</td><td><input type="password" name="pswd" value = "'.$_POST['pswd'].'"></td></tr>
<tr><td>&nbsp;</td><td><input type="submit" name="login" value="Login"></td></tr>
</table>
</form>
</center>
</body>
</html>';
}

function checkIPs($ipadresses)
{ $myip = $_SERVER['REMOTE_ADDR'];
  $myparts = explode(".",$myip);
  foreach($ipadresses AS $ip)
  { if((strpos($myip,":") === false) && (strpos($ip,":") === false))
    { $parts = explode(".",$ip);
	  $allowed = true;
	  for($i=0; $i<4; $i++)
	    if(($myparts[$i]!= $parts[$i]) && ($parts[$i] != "*"))
		  $allowed = false;
	  if($allowed == true)
	    return true;
	}
	else if($myip == $ip)
	  return true;
  }
  return false;
}

function get_seed()
{ $seed = "";
  if(isset($_SERVER['SERVER_SIGNATURE']))   $seed.=$_SERVER['SERVER_SIGNATURE'];
  if(isset($_SERVER['GATEWAY_INTERFACE']))  $seed.=$_SERVER['GATEWAY_INTERFACE'];
  if(isset($_SERVER['SERVER_ADMIN']))       $seed.=$_SERVER['SERVER_ADMIN'];
  return $seed;
}
?>