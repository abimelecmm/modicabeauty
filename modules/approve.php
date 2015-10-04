<?php
$embedded = "1";
include("login1.php");
$mode = "front";
include("functions1.php");

$url = $_SERVER['REQUEST_URI'];
$validated = false;
if($session) 
{ if (isset($_SESSION['tripleedit']) && $_SESSION['tripleedit'] == 'open')
	$validated = true;
}
else
{ $seed = @date("m Y").get_seed()."randomtext"; // for "strict standards" data() gives the warning "It is not safe to rely on the system's timezone settings."
//  $encseed = preg_replace('/'.preg_quote("\\", '\\').'/',"", convert_uuencode(base64_encode($seed)));  // some servers escape cookies
  $encseed = stripslashes(stripslashes(stripslashes(convert_uuencode(base64_encode($seed)))));  // some servers escape cookies
  if(isset($_COOKIE["tripleedit"]) && (stripslashes(stripslashes(stripslashes($_COOKIE["tripleedit"]))) == $encseed))
//  if(isset($_COOKIE["tripleedit"]) && ($_COOKIE["tripleedit"] == "magic"))
// Note: cookie may be replaced with localStorage.setItem('key', 'value') and localStorage.getItem('key'));
    $validated = true;

 $debugger = "<html><body><code>: ".$seed."</code><p/><p/><code>: ".$encseed."</code><p/>";
 if(!isset($_COOKIE["tripleedit"]))
	$debugger .= "<p>No Cookie";
 else 
	$debugger .= "<p/><code>: ".$_COOKIE["tripleedit"]."</code>";
}

if (!$validated) {
header('Location: login1.php?url='.urlencode($url)); //Replace that if login1.php is somewhere else
Echo "Please login to view this page";
exit;
}
if(@include "config/settings.inc.php") 
  $triplepath = "./";
else if (@include "../config/settings.inc.php")
  $triplepath = "../";
else if (@include "../../config/settings.inc.php")
  $triplepath = "../../";
else if (include "../../../config/settings.inc.php")
  $triplepath = "../../../";
else
  die( "<p><b>Your files should be in a subdirectory of admin directory your shop!</b>");
   
$conn = mysqli_connect(_DB_SERVER_, _DB_USER_, _DB_PASSWD_) or die ("Error connecting to database server");
mysqli_select_db($conn, _DB_NAME_) or die ("Error selecting database");
$result = mysqli_query($conn, "SET NAMES 'utf8'");
?>