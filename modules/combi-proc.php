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
{ $refscript = str_replace("combi_proc","combi_edit",$_SERVER['REQUEST_URI']);
  if($refscript == "")
    $refscript = "combi_edit.php";
}

$quantity_changed = false;
  
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
// print_r($_POST)."<p>";
 
 if(isset($_POST['id_product_attribute']))
 { echo '<a href="#" title="Show the content of this frame in a New Window" onclick="newwin(); return false;">NW</a> ';
   change_rec("");
 }
 else
 { echo "<br>Go back to the <a href='".$refscript."'>Combi-edit page</a><p/>";
   echo $reccount." Records<br/>";
   for($i=0; $i<$reccount; $i++)
     change_rec($i);
   for($i=0; $i<$reccount; $i++) /* do this separate to reduce the chance that an error will leave us with more or less than one default_on */
     change_default_on($i);
 }
 
 /* get shop group and its shared_stock status */
$query="select s.id_shop_group, g.share_stock, g.name from ". _DB_PREFIX_."shop s, "._DB_PREFIX_."shop_group g";
$query .= " WHERE s.id_shop_group=g.id_shop_group and id_shop='".$id_shop."'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$id_shop_group = $row['id_shop_group'];
$share_stock = $row["share_stock"];
 
function change_rec($x)
{ global $id_lang, $id_shop, $id_shop_group, $errstring, $verbose, $id_product, $conn, $share_stock, $quantity_changed;
  echo "*";
  if((!isset($GLOBALS['id_product_attribute'.$x])) || (!is_numeric($GLOBALS['id_product_attribute'.$x]))) {if ($verbose=="true") echo "No changes"; return;}
  echo $x.": ";
    
  $id_product_attribute = $GLOBALS['id_product_attribute'.$x];
  $paupdates = "";
  
  if(isset($GLOBALS['ean'.$x]))  
  { $ean = $GLOBALS['ean'.$x];
    if(!is_numeric($ean)) colordie("invalid ean13 for ".$x);
    $paupdates .= " ean13='".mysqli_real_escape_string($conn, $ean)."',";
  }
  if(isset($GLOBALS['upc'.$x]))  
  { $upc = str_replace(",", ".", $GLOBALS['upc'.$x]);
    if(!is_numeric($upc)) colordie("invalid upc");
    $paupdates .= " upc='".mysqli_real_escape_string($conn, $upc)."',";
  }
  if(isset($GLOBALS['quantity'.$x]))  
  { $quantity = str_replace(",", ".", $GLOBALS['quantity'.$x]);
    if(!is_numeric($quantity)) colordie("invalid quantity");
    $paupdates .= " quantity='".mysqli_real_escape_string($conn, $quantity)."',";
  }
  if(isset($GLOBALS['supplier_reference'.$x]))
    $paupdates .= " supplier_reference='".mysqli_real_escape_string($conn, $GLOBALS['supplier_reference'.$x])."',";
  if(isset($GLOBALS['reference'.$x]))
    $paupdates .= " reference='".mysqli_real_escape_string($conn, $GLOBALS['reference'.$x])."',";
  if(isset($GLOBALS['location'.$x]))
    $paupdates .= " location='".mysqli_real_escape_string($conn, $GLOBALS['location'.$x])."',";
  if($id_shop == '1')
  { if(isset($GLOBALS['wholesale_price'.$x]))
    { $wholesale_price = str_replace(",", ".", $GLOBALS['wholesale_price'.$x]);
      if(!is_numeric($wholesale_price)) colordie("invalid wholesale_price");
        $paupdates .= " wholesale_price='".mysqli_real_escape_string($conn, $wholesale_price)."',";
    }
    if((isset($GLOBALS['priceVAT'.$x])) || (isset($GLOBALS['showprice'.$x])))
    { $price = str_replace(",", ".", $GLOBALS['price'.$x]);
      if(!is_numeric($price)) colordie("invalid price");
      $paupdates .= " price='".mysqli_real_escape_string($conn, $price)."',";
	}
	if(isset($GLOBALS['weight'.$x]))
    { $weight = str_replace(",", ".", $GLOBALS['weight'.$x]);
      if(!is_numeric($weight)) colordie("invalid weight");
      $paupdates .= " weight='".mysqli_real_escape_string($conn, $weight)."',";
	}
  }
  if($paupdates != "")
  { $query = "UPDATE ". _DB_PREFIX_."product_attribute SET".substr($paupdates,0,strlen($paupdates)-1)." WHERE id_product='".$id_product."' AND id_product_attribute='".$id_product_attribute."'";
    dbquery($query);
  }

  $pasupdates = "";
  if(isset($GLOBALS['minimal_quantity'.$x]))  
  { $minimalquantity = $GLOBALS['minimal_quantity'.$x];
    if(!is_numeric($minimalquantity)) colordie("invalid minimal_quantity for ".$x);
    $pasupdates .= " minimal_quantity='".mysqli_real_escape_string($conn, $minimalquantity)."',";
  }
  if((isset($GLOBALS['priceVAT'.$x])) || (isset($GLOBALS['showprice'.$x])))
  { $price = str_replace(",", ".", $GLOBALS['price'.$x]);
    if(!is_numeric($price)) colordie("invalid price");
    $pasupdates .= " price='".mysqli_real_escape_string($conn, $price)."',";
  }
  if(isset($GLOBALS['wholesale_price'.$x]))
  { $wholesale_price = str_replace(",", ".", $GLOBALS['wholesale_price'.$x]);
    if(!is_numeric($wholesale_price)) colordie("invalid wholesale_price");
    $pasupdates .= " wholesale_price='".mysqli_real_escape_string($conn, $wholesale_price)."',";
  }
  if(isset($GLOBALS['weight'.$x]))
  { $weight = str_replace(",", ".", $GLOBALS['weight'.$x]);
    if(!is_numeric($weight)) colordie("invalid weight");
    $pasupdates .= " weight='".mysqli_real_escape_string($conn, $weight)."',";
  }
  if(isset($GLOBALS['ecotax'.$x]))
  { $ecotax = str_replace(",", ".", $GLOBALS['ecotax'.$x]);
    if(!is_numeric($ecotax)) colordie("invalid ecotax");
    $pasupdates .= " ecotax='".mysqli_real_escape_string($conn, $ecotax)."',";
  }
  if(isset($GLOBALS['unit_price_impact'.$x]))
  { $unit_price_impact = str_replace(",", ".", $GLOBALS['unit_price_impact'.$x]);
    if(!is_numeric($unit_price_impact)) colordie("invalid unit_price_impact");
    $pasupdates .= " unit_price_impact='".mysqli_real_escape_string($conn, $unit_price_impact)."',";
  }
  if(isset($GLOBALS['minimal_quantity'.$x]))
  { $minimal_quantity = str_replace(",", ".", $GLOBALS['minimal_quantity'.$x]);
    if(!is_numeric($minimal_quantity)) colordie("invalid minimal_quantity");
    $pasupdates .= " minimal_quantity='".mysqli_real_escape_string($conn, $minimal_quantity)."',";
  }
  if(isset($GLOBALS['available_date'.$x]))
  { $available_date = $GLOBALS['available_date'.$x];
    $parts = explode("-", $available_date);
    if(!checkdate($parts[1],$parts[2],$parts[0]))
      colordie("invalid available_date");
    $pasupdates .= " available_date='".mysqli_real_escape_string($conn, $available_date)."',";
  }
  if($pasupdates != "")
  { if(!isset($id_shop)) die("<p><h2>Shop must be provided!</h2>");
    $query = "UPDATE ". _DB_PREFIX_."product_attribute_shop SET".substr($pasupdates,0,strlen($pasupdates)-1)." WHERE id_product_attribute='".$id_product_attribute."' AND id_shop='".$id_shop."'";
    dbquery($query);
  }

  if(isset($GLOBALS['image'.$x]))
  { $query = "SELECT * FROM ". _DB_PREFIX_."product_attribute_image WHERE id_product_attribute='".$id_product_attribute."'";
    $res = dbquery($query);
	$id_image = $GLOBALS['image'.$x];
	if(mysqli_num_rows($res) == 0)
	{ $query = "INSERT INTO ". _DB_PREFIX_."product_attribute_image SET id_product_attribute='".$id_product_attribute."', id_image='".$id_image."'";
      dbquery($query);
	}
	else 
	{ if($id_image == 0)
	  { $query = "DELETE FROM ". _DB_PREFIX_."product_attribute_image WHERE id_product_attribute='".$id_product_attribute."'";
        dbquery($query);
	  }
	  else
	  { $query = "UPDATE ". _DB_PREFIX_."product_attribute_image SET id_image='".$id_image."' WHERE id_product_attribute='".$id_product_attribute."'";
		dbquery($query);
	  }
	} 
  }
  
  if(isset($GLOBALS['quantity'.$x]))  
  { $quantity_changed = true;
    $quantity = str_replace(",", ".", $GLOBALS['quantity'.$x]);
    if(!is_numeric($quantity)) colordie("invalid quantity");
	
    if($share_stock)
	{ $query = "SELECT quantity FROM ". _DB_PREFIX_."stock_available WHERE id_shop_group = '".$id_shop_group."' AND id_product_attribute='".$id_product_attribute."'";
	  $res = dbquery($query);
	  /* the out_of_stock field determines whether ordering is allowed when stock too low: 2=not allowed 1=allowed 0=follow shop preferences */
	  if(mysqli_num_rows($res) == 0) /* no quantity entered yet  */
	  { $query = "INSERT INTO ". _DB_PREFIX_."stock_available SET quantity='".$quantity."', id_shop_group ='".$id_shop_group."', id_shop=0, id_product_attribute='".$id_product_attribute."', out_of_stock='2'";
	    $res = dbquery($query);
	  }
	  else 
	  { $query = "UPDATE ". _DB_PREFIX_."stock_available SET quantity='".$quantity."' WHERE id_shop_group ='".$id_shop_group."' AND id_product_attribute='".$id_product_attribute."'";
	    $res = dbquery($query);
	  }
	}
	else
	{ $query = "SELECT quantity FROM ". _DB_PREFIX_."stock_available WHERE id_shop = '".$id_shop."' AND id_product_attribute='".$id_product_attribute."'";
	  $res = dbquery($query);
	  /* the out_of_stock field determines whether ordering is allowed when stock too low: 2=not allowed 1=allowed 0=follow shop preferences */
	  if(mysqli_num_rows($res) == 0)  /* no quantity entered yet  */
	  { $query = "INSERT INTO ". _DB_PREFIX_."stock_available SET quantity='".$quantity."', id_shop ='".$id_shop."', id_product_attribute='".$id_product_attribute."', out_of_stock='2'";
	    $res = dbquery($query);
	  }
	  else 
	  { $query = "UPDATE ". _DB_PREFIX_."stock_available SET quantity='".$quantity."' WHERE id_shop ='".$id_shop."' AND id_product_attribute='".$id_product_attribute."'";
	    $res = dbquery($query);
	  }
	}
  }

  if(isset($GLOBALS['cimages'.$x]))
  { echo $x."--".$GLOBALS['cimages'.$x]."<br>";
  
    $query = "SELECT GROUP_CONCAT(CONCAT(id_image)) AS images FROM ". _DB_PREFIX_."product_attribute_image WHERE id_product_attribute='".$id_product_attribute."' GROUP BY id_product_attribute";
    $res = dbquery($query);
	$row=mysqli_fetch_array($res);
	$oldimages = explode(",",$row["images"]);
    if((sizeof($oldimages) == 1) && ($oldimages[0] == ""))
	   $oldimages = array();  /* empty array */
	$newimages = explode(",",preg_replace("/^,/","",$GLOBALS['cimages'.$x])); /* skip leading comma */
    if((sizeof($newimages) == 1) && ($newimages[0] == ""))
	   $newimages = array();  /* empty array */
	   
	$diff1 = array_diff($oldimages, $newimages); /* get images that are no longer there */
	foreach($diff1 AS $dif)
	{ $dquery = "DELETE FROM ". _DB_PREFIX_."product_attribute_image WHERE id_product_attribute='".$id_product_attribute."' AND id_image='".$dif."'";
	  $dres=dbquery($dquery);
	}
	  
	$diff2 = array_diff($newimages, $oldimages);
	foreach($diff2 AS $dif)
	{ $dquery = "INSERT INTO ". _DB_PREFIX_."product_attribute_image SET id_product_attribute='".$id_product_attribute."', id_image='".$dif."'";
      $dres=dbquery($dquery);
	}
  }
}

function change_default_on($x)
{ global $id_lang, $id_shop, $id_shop_group, $errstring, $verbose, $id_product, $conn;
  echo "*";
  if((!isset($GLOBALS['id_product_attribute'.$x])) || (!is_numeric($GLOBALS['id_product_attribute'.$x]))) {if ($verbose=="true") echo "No changes"; return;}
  echo $x.": ";
    
  $id_product_attribute = $GLOBALS['id_product_attribute'.$x];
  $pasupdates = "";
  if(isset($GLOBALS['default_on'.$x]))
  { $default_on = $GLOBALS['default_on'.$x];
    if(($default_on != "0") && ($default_on != "1")) colordie("invalid default_on for ".$x);
    $pasupdates .= " default_on='".mysqli_real_escape_string($conn, $default_on)."',";
  }
  if($pasupdates != "")
  { if(!isset($id_shop)) die("<p><h2>Shop must be provided!</h2>");
    $query = "UPDATE ". _DB_PREFIX_."product_attribute_shop SET".substr($pasupdates,0,strlen($pasupdates)-1)." WHERE id_product_attribute='".$id_product_attribute."' AND id_shop='".$id_shop."'";
    dbquery($query);
  }
}

/* following section takes care that when individual stock quantities for the combinations are updated the total is updated too */
  if($quantity_changed)
  { if($share_stock)
	{ $query = "SELECT SUM(quantity) AS quantsum FROM ". _DB_PREFIX_."stock_available WHERE id_shop_group = '".$id_shop_group."' AND id_product='".$id_product."' AND id_product_attribute!='0'";
      $res = dbquery($query);
	  $row=mysqli_fetch_array($res);
	  $uquery = "UPDATE ". _DB_PREFIX_."stock_available SET quantity='".$row['quantsum']."' WHERE id_shop_group = '".$id_shop_group."' AND id_product='".$id_product."' AND id_product_attribute='0'";
      $res = dbquery($uquery);
	}
	else
	{ $query = "SELECT SUM(quantity) AS quantsum FROM ". _DB_PREFIX_."stock_available WHERE id_shop = '".$id_shop."' AND id_product='".$id_product."' AND id_product_attribute!='0'";
      $res = dbquery($query);
	  $row=mysqli_fetch_array($res);
	  $uquery = "UPDATE ". _DB_PREFIX_."stock_available SET quantity='".$row['quantsum']."' WHERE id_shop = '".$id_shop."' AND id_product='".$id_product."' AND id_product_attribute='0'";
      $res = dbquery($uquery);
	}
  }

/* the following section checks that only one default attribute is selected for a product. If not it repairs the situation */
  $query = "SELECT pa.id_product_attribute, pas.default_on from ". _DB_PREFIX_."product_attribute_shop pas";
  $query .= " left join ". _DB_PREFIX_."product_attribute pa ON pa.id_product_attribute=pas.id_product_attribute";
  $query .= " WHERE pa.id_product='".$id_product."' AND pas.id_shop='".$id_shop."'";
  $res = dbquery($query);
  $allatts = array();
  $defatts = array();
  while ($row=mysqli_fetch_array($res))
  { $selatts[] = $row["id_product_attribute"];
    if($row["default_on"] == '1')
	  $defatts[] = $row["id_product_attribute"];
  }
  echo "DD".sizeof($defatts)."<br>";
  if(sizeof($defatts) == 0)
  { $query = "UPDATE ". _DB_PREFIX_."product_attribute_shop SET default_on='1' WHERE id_product_attribute='".$allatts[0]."' AND id_shop='".$id_shop."' LIMIT 1";
    $res = dbquery($query);
	if($id_shop == '1')
	{ $query = "UPDATE ". _DB_PREFIX_."product_attribute SET default_on='1' WHERE id_product_attribute='".$allatts[0]."' AND id_product='".$id_product."' LIMIT 1";
      $res = dbquery($query);
	}
  }
  else if (sizeof($defatts) > 1)
  { for($i=1; $i<sizeof($defatts); $i++)
    { $query = "UPDATE ". _DB_PREFIX_."product_attribute_shop SET default_on='0' WHERE id_product_attribute='".$defatts[$i]."' AND id_shop='".$id_shop."'";
      $res = dbquery($query);
	  if($id_shop == '1')
	  { $query = "UPDATE ". _DB_PREFIX_."product_attribute SET default_on='0' WHERE id_product_attribute='".$defatts[$i]."' AND id_product='".$id_product."' LIMIT 1";
        $res = dbquery($query);
	  }
	}
  }

if($errstring != "")
{ echo "<script>alert('There were errors: ".$errstring."');</script>!";
  echo str_replace("\n","<br>",$errstring);
}

echo "<br>Finished successfully!";
if(!isset($_POST['id_product_attribute'])) /* if submit all */
{ echo "<p>Go back to <a href='".$refscript."'>Combination Edit page</a></body></html>";
  if($verbose!="true")
    echo "<script>location.href = '".$refscript."';</script>";
}

if(isset($_POST['id_row']))
{ $row = substr($_POST['id_row'], 4);
  echo "<script>if(parent) parent.reg_unchange(".$row.");</script>";
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

