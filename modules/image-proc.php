<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$mode = "background";
//print_r($_POST);

 /* Get the arguments */
if(!isset($_POST['id_lang']))
{ echo "No language";
  return;
}
$id_lang = strval(intval($_POST['id_lang']));

if(isset($_POST['id_shop']))
  $id_shop = strval(intval($_POST['id_shop']));
else	
  colordie("No shop provided");

if(isset($_POST['id_product']))
  $id_product = strval(intval($_POST['id_product']));
else	
  colordie("No product provided");
  
$refscript = $_SERVER['HTTP_REFERER'];
if($refscript == "")
{ $refscript = str_replace("image_proc","image_edit",$_SERVER['REQUEST_URI']);
  if($refscript == "")
    $refscript = "image_edit.php";
}
  
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script>
function newwin()
{ nwin = window.open("","_blank", "scrollbars,menubar,toolbar, status,resizable,location");
  content = document.body.innerHTML;
  if(nwin != null)
  { nwin.document.write("<html><head><meta http-equiv=\'Content-Type\' content=\'text/html; charset=utf-8\' /></head><body>"+content+"</body></html>");
    nwin.document.close();
  }
}
</script></head><body>';

extract($_POST);
 
/* get shop group and its shared_stock status */
$query="select s.id_shop_group, g.share_stock, g.name from ". _DB_PREFIX_."shop s, "._DB_PREFIX_."shop_group g";
$query .= " WHERE s.id_shop_group=g.id_shop_group and id_shop='".$id_shop."'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$id_shop_group = $row['id_shop_group'];
$share_stock = $row["share_stock"];
  
echo $reccount." Records<br/>";
for($i=0; $i<$reccount; $i++)
  change_rec($i);
$cover_found = false;
for($i=0; $i<$reccount; $i++) /* do this separate to reduce the change that an error will leave us with more or less than one default_on */
   change_cover($i);
  
function change_rec($x)
{ global $id_lang, $id_shop, $id_shop_group, $errstring, $verbose, $id_product, $conn;
  echo "*";
  if((!isset($GLOBALS['id_image'.$x])) || (!is_numeric($GLOBALS['id_image'.$x]))) {if ($verbose=="true") echo "No changes"; return;}
  echo $x.": ";
    
  $id_image = $GLOBALS['id_image'.$x];
  
  if(isset($GLOBALS['position'.$x]))  
  { $position = $GLOBALS['position'.$x];
    if(!is_numeric($position)) colordie("invalid position for ".$x);
    $query = "UPDATE ". _DB_PREFIX_."image SET position='".mysqli_real_escape_string($conn, $position)."' WHERE id_product='".$id_product."' AND id_image='".$id_image."'";
    dbquery($query);
  }
  
  if(isset($GLOBALS['legend'.$x]))  
  { $legend = $GLOBALS['legend'.$x];
    $legend = preg_replace('/[<>={}]+/', '', $legend);
    $query = "UPDATE ". _DB_PREFIX_."image_lang SET legend='".mysqli_real_escape_string($conn, $legend)."' WHERE id_image='".$id_image."' AND id_lang='".$id_lang."'";
    dbquery($query);
  }
}

function change_cover($x)
{ global $id_lang, $id_shop, $id_shop_group, $errstring, $verbose, $id_product, $conn, $cover_found;
  echo "*";
  if((!isset($GLOBALS['id_image'.$x])) || (!is_numeric($GLOBALS['id_image'.$x]))) {if ($verbose=="true") echo "No changes"; return;}
  echo $x.": ";
    
  $id_image = $GLOBALS['id_image'.$x];
  $pasupdates = "";
  if(isset($GLOBALS['cover'.$x]))
  { $cover = $GLOBALS['cover'.$x];
    if(($cover != "0") && ($cover != "1")) { colordie("invalid cover for ".$x."! "); }
	if($cover == "1")
	{ if(!$cover_found)
		$cover_found = true;
	  else
	  { $errstring .= "double cover found";
	    $cover = "0";
	  }
	}
    $query = "UPDATE ". _DB_PREFIX_."image SET cover=".$cover." WHERE id_product='".$id_product."' AND id_image='".$id_image."'";
    dbquery($query);
  }
}

if(isset($GLOBALS['id_image0']) && (isset($GLOBALS['cover0'])) && (!$cover_found) && is_numeric($GLOBALS['id_image0']))
{ $query = "UPDATE ". _DB_PREFIX_."image SET cover=1 WHERE id_product='".$id_product."' AND id_image='".$GLOBALS['id_image0']."'";
  dbquery($query);
  $errstring .= "No cover provided!";
}

if($errstring != "")
{ echo "<script>alert('There were errors: ".$errstring."');</script>!";
  echo str_replace("\n","<br>",$errstring);
}

echo "<br>Finished successfully!";
if(!isset($_POST['id_image'])) /* if submit all */
  echo "<p>Go back to <a href='".$refscript."'>Product Image Edit page</a></body></html>";
  
if($verbose!="true")
{ echo "<script>location.href = '".$refscript."';</script>";
}
  
function strip($txt)
{ if (get_magic_quotes_gpc()) 
   $txt = stripslashes($txt);
  return $txt;
}

function isValidName($name, $texttype)
{ global $errstring;
	if (empty($name) || !preg_match('/^[^<>={}]*$/u', $name))
	{ $errstring .= "\\n".htmlspecialchars($name)." is not a valid ".$texttype."!";
	  return false;
	}
	return true;
}
?>
