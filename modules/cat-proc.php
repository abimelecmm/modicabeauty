<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$mode = "background";

if(!isset($_POST['id_shop']))
{ echo "No shop";
  return;
}
$id_shop = strval(intval($_POST['id_shop']));
if(!isset($_POST['id_lang']))
{ echo "No language";
  return;
}
$id_lang = strval(intval($_POST['id_lang']));
$verbose = $_POST['verbose'];
$errstring = "";

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script>
function newwin()
{ nwin = window.open("","NewWindow", "scrollbars,menubar,toolbar, status,resizable,location");
  content = document.body.innerHTML;
  if(nwin != null)
  { nwin.document.write("<html><head><meta http-equiv=\'Content-Type\' content=\'text/html; charset=utf-8\' /></head><body>"+content+"</body></html>");
    nwin.document.close();
  }
}
</script></head><body>';

$refscript = $_SERVER['HTTP_REFERER'];
if($refscript == "")
{ $refscript = "cat_edit.php";
}

extract($_POST);

 if(isset($_POST['id_category']))
   echo '<a href="#" title="Show the content of this frame in a New Window" onclick="newwin(); return false;">NW</a> ';
 else
   echo "<br>Go back to <a href='".$refscript."'>cat-edit page</a><p/>".$reccount." Records - of which ".$_GET["d"]." submitted.<br/>";

 if(isset($_POST['id_category']))
   change_rec(""); 
 else
 { for($i=0; $i<$reccount; $i++)
     change_rec($i);
 }

function change_rec($x)
{ global $id_lang, $id_shop, $errstring, $verbose, $conn;
  echo "*";
  if((!isset($GLOBALS['id_category'.$x])) || (!is_numeric($GLOBALS['id_category'.$x]))) {if ($verbose=="true") echo "No changes"; return;}
  echo $x.": ";
  $id_category = $GLOBALS['id_category'.$x];

  $catupdates = "";
  if(isset($GLOBALS['active'.$x]))
  { $active = $GLOBALS['active'.$x];
    if(($active == "0") || ($active == "1"))
      $catupdates .= " active='".mysqli_real_escape_string($conn, $GLOBALS['active'.$x])."'";
}
  if($catupdates != "")
  { $query = "UPDATE ". _DB_PREFIX_."category SET".$catupdates." WHERE id_category='".$id_category."'";
    dbquery($query);
  }

  if(isset($GLOBALS['meta_keywords'.$x]))
    $meta_keywords = preg_replace("/ +/"," ",str_replace(",",", ",$GLOBALS['meta_keywords'.$x])); /* always a space behind comma; needed for break-off on page */

  $clupdates = "";
  if(isset($GLOBALS['name'.$x]))
    $clupdates .= " name='".mysqli_real_escape_string($conn, strip($GLOBALS['name'.$x]))."',";
  if(isset($GLOBALS['description'.$x]))
    $clupdates .= " description='".mysqli_real_escape_string($conn, strip($GLOBALS['description'.$x]))."',";
  if(isset($GLOBALS['link_rewrite'.$x]))
    $clupdates .= " link_rewrite='".mysqli_real_escape_string($conn, $GLOBALS['link_rewrite'.$x])."',";
  if(isset($GLOBALS['meta_title'.$x]))
    $clupdates .= " meta_title='".mysqli_real_escape_string($conn, strip($GLOBALS['meta_title'.$x]))."',";
  if(isset($GLOBALS['meta_keywords'.$x]))
    $clupdates .= " meta_keywords='".mysqli_real_escape_string($conn, strip($meta_keywords))."',";
  if(isset($GLOBALS['meta_description'.$x]))
    $clupdates .= " meta_description='".mysqli_real_escape_string($conn, strip($GLOBALS['meta_description'.$x]))."',";
  if($clupdates != "")
  { $query = "UPDATE ". _DB_PREFIX_."category_lang SET".substr($clupdates,0,strlen($clupdates)-1)." WHERE id_category='".$id_category."' AND id_lang='".$id_lang."' AND id_shop='".$id_shop."'";
    dbquery($query);
  }
}

echo "<br>Finished successfully!<p>Go back to <a href='".$refscript."'>Cat-edit page page</a>";

if(isset($_POST['id_row']))
{ $row = substr($_POST['id_row'], 4);
  echo "<script>if(parent) parent.reg_unchange(".$row.");</script>";
}
else if($verbose!="true")
{ echo "<script>location.href = '".$refscript."';</script>";
}

echo "</body></html>";
  

function strip($txt)
{ if (get_magic_quotes_gpc()) 
    $txt = stripslashes(stripslashes($txt)); // double seems necessary
  $txt2 = htmltrim($txt); /* empty fields contain an whitespace. Experience learns they often not removed */
  if($txt2 == "")
    return $txt;  // 
  else 
    return $txt2;
}

  function htmltrim($string)
   {
     $pattern = '(?:[ \t\n\r\x0B\x00\x{A0}\x{AD}\x{2000}-\x{200F}\x{201F}\x{202F}\x{3000}\x{FEFF}]|&nbsp;|<br\s*\/?>)+';
     return preg_replace('/^' . $pattern . '|' . $pattern . '$/u', '', $string);
   }
?>
