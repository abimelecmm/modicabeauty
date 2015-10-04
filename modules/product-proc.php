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
else	/* this happens only with sort. We set id_shop at 1 to prevent error message with the shared_stock query */
  $id_shop = '1'; 
$changed_categories = array();
$valid_products = array(); // used for accessories
$invalid_products = array(); // used for accessories
$deleted_tags = array();
$errstring = "";
$updateblock = array();  /* this will be sent back with rowsubmit */

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

$refscript = $_SERVER['HTTP_REFERER'];
if(strpos($refscript,"product-sort"))
  $srcscript = "product-sort";
else if((isset($_POST['methode'])) && ($_POST['methode'] == "vissort"))
  $srcscript = "product-vissort";
else
  $srcscript = "product-edit";
if($refscript == "")
{ $refscript = str_replace("product_proc","product_edit",$_SERVER['REQUEST_URI']);
  if($refscript == "")
    $refscript = "product_edit.php";
}

  extract($_POST);
  if(($srcscript == "product-vissort") || ($srcscript == "product-sort"))
    $id_category = intval($id_category); /* doing it here is optimization */

 if(isset($_POST['id_product']))
   echo '<a href="#" title="Show the content of this frame in a New Window" onclick="newwin(); return false;">NW</a> ';
 else
 { echo "<br>Go back to <a href='".$refscript."'>".$srcscript." page</a><p/>".$reccount." Records";
   if(isset($_GET['d']))
     echo " - of which ".$_GET["d"]." submitted.<br/>";
 }
 
/* get list of features */
$query = "SELECT id_feature, name FROM ". _DB_PREFIX_."feature_lang";
$query .= " WHERE id_lang='".$id_lang."'";
$query .= " ORDER BY id_feature";
$res = dbquery($query);
$features = array();
while($row = mysqli_fetch_array($res))
  $features[$row['id_feature']] = $row['name'];

/* get list of language id's */
$query = "SELECT id_lang FROM ". _DB_PREFIX_."lang";
$res = dbquery($query);
$languages = array();
while($row = mysqli_fetch_array($res))
  $languages[] = $row['id_lang'];
  
/* get shop group and its shared_stock status */
$query="select s.id_shop_group, g.share_stock, g.name from ". _DB_PREFIX_."shop s, "._DB_PREFIX_."shop_group g";
$query .= " WHERE s.id_shop_group=g.id_shop_group and id_shop='".$id_shop."'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$id_shop_group = $row['id_shop_group'];
$share_stock = $row["share_stock"];

 if(isset($_POST['id_product']))
   change_rec(""); 
 else
 { for($i=0; $i<$reccount; $i++)
     change_rec($i);
 }

function change_rec($x)
{ global $id_lang, $id_shop, $changed_categories, $features, $languages, $valid_products, $invalid_products, $share_stock, $id_shop_group;
  global $updateblock, $errstring, $verbose, $deleted_tags, $conn, $srcscript, $id_category;

  echo "*";
  if((!isset($GLOBALS['id_product'.$x])) || (!is_numeric($GLOBALS['id_product'.$x]))) {if ($verbose=="true") echo "No changes"; return;}
  echo $x.": ";
  $id_product = $GLOBALS['id_product'.$x];

  if(isset($GLOBALS['mycats'.$x]))  /* my categories */
  { $cquery = "select id_category, position from ". _DB_PREFIX_."category_product WHERE id_product='".$GLOBALS['id_product'.$x]."' ORDER BY id_category";
	$cres=dbquery($cquery);
	$carray = array();
	$parray = array();
	while ($crow=mysqli_fetch_array($cres)) 
	{  $carray[] = $crow['id_category'];
	   $parray[$crow['id_category']] = $crow['position']; /* can be used for optimizations */
	}
	$mycats = substr($GLOBALS['mycats'.$x], 1);
	$mycat_arr = explode(",", $mycats);

	$diff1 = array_diff($carray, $mycat_arr);
	foreach($diff1 AS $dif)
	{ $dquery = "DELETE from ". _DB_PREFIX_."category_product WHERE id_product='".$GLOBALS['id_product'.$x]."' AND id_category='".$dif."'";
	  $dres=dbquery($dquery);
	  $changed_categories[] = $dif; // prepare for later sorting of this category
	}
	  
	$diff2 = array_diff($mycat_arr, $carray);
	foreach($diff2 AS $dif)
	{ $dquery = "SELECT MAX(position) AS mposition FROM "._DB_PREFIX_."category_product WHERE id_category='".$dif."'";
	  $dres=dbquery($dquery);
	  $drow=mysqli_fetch_array($dres);
	  $dquery = "INSERT INTO "._DB_PREFIX_."category_product SET id_product='".$GLOBALS['id_product'.$x]."', id_category='".$dif."', position='".($drow['mposition']+1)."'";
	  $dres=dbquery($dquery);
	}
  }
  
    if(isset($GLOBALS['myattachments'.$x]))  /* my attachments */
  { $cquery = "select id_attachment from ". _DB_PREFIX_."product_attachment WHERE id_product='".$GLOBALS['id_product'.$x]."' ORDER BY id_attachment";
	$cres=dbquery($cquery);
	$carray = array();
	while ($crow=mysqli_fetch_array($cres)) 
	{  $carray[] = $crow['id_attachment'];
	}
	$myattas = substr($GLOBALS['myattachments'.$x], 1);
	$myattas_arr = explode(",", $myattas);

	$diff1 = array_diff($carray, $myattas_arr);
	foreach($diff1 AS $dif)
	{ $dquery = "DELETE from ". _DB_PREFIX_."product_attachment WHERE id_product='".$GLOBALS['id_product'.$x]."' AND id_attachment='".$dif."'";
	  $dres=dbquery($dquery);
	}
	  
	$diff2 = array_diff($myattas_arr, $carray);
	foreach($diff2 AS $dif)
	{ $dquery = "INSERT INTO "._DB_PREFIX_."product_attachment SET id_product='".$GLOBALS['id_product'.$x]."', id_attachment='".$dif."'";
	  $dres=dbquery($dquery);
	}
  }
  
  if(isset($GLOBALS['mycars'.$x]))  /* my carriers */
  { if(file_exists("TE_plugin_carriers.php"))
      include "TE_plugin_carriers.php";
  }

  $pupdates = "";
  if($srcscript == "product-edit")
    $pupdates = " date_upd='".date("Y-m-d H:i:s", time())."',";
  if(isset($GLOBALS['active'.$x]))  
  { $active = $GLOBALS['active'.$x];
    if(($active != "0") && ($active != "1")) colordie("invalid active for ".$x);
    $pupdates .= " active='".mysqli_real_escape_string($conn, $active)."',";
  }
  if(isset($GLOBALS['on_sale'.$x]))  
  { $onsale = $GLOBALS['on_sale'.$x];
    if(($onsale != "0") && ($onsale != "1")) colordie("Invalid onsale for line ".$x.". The onsale flag can only be 0 or 1.");
    $pupdates .= " on_sale='".mysqli_real_escape_string($conn, $onsale)."',";
  }
  if(isset($GLOBALS['online_only'.$x]))  
  { $onlineonly = $GLOBALS['online_only'.$x];
    if(($onlineonly != "0") && ($onlineonly != "1")) colordie("invalid online_only for ".$x);
    $pupdates .= " online_only='".mysqli_real_escape_string($conn, $onlineonly)."',";
  }
  if(isset($GLOBALS['available'.$x]))  
  { $available = $GLOBALS['available'.$x];
    if(($available != "0") && ($available != "1")) colordie("invalid available for ".$x);
    $pupdates .= " available_for_order='".mysqli_real_escape_string($conn, $available)."',";
  }
  	if(isset($GLOBALS['stockflags'.$x]))  
    { if($GLOBALS['stockflags'.$x] == 1) $advanced_stock_management = "0"; else $advanced_stock_management = "1";
      $pupdates .= " advanced_stock_management='".mysqli_real_escape_string($conn, $advanced_stock_management)."',";
    }
  if(isset($GLOBALS['minimal_quantity'.$x]))  
  { $minimalquantity = $GLOBALS['minimal_quantity'.$x];
    if(!is_numeric($minimalquantity)) colordie("invalid minimal_quantity for ".$x);
    $pupdates .= " minimal_quantity='".mysqli_real_escape_string($conn, $minimalquantity)."',";
  }
  if(isset($GLOBALS['shipweight'.$x]))  
  { $weight = str_replace(",", ".", $GLOBALS['shipweight'.$x]);
    if(!is_numeric($weight)) colordie("invalid weight");
    $pupdates .= " weight='".mysqli_real_escape_string($conn, $weight)."',";
  }
  if(isset($GLOBALS['shipheight'.$x]))  
  { $height = str_replace(",", ".", $GLOBALS['shipheight'.$x]);
    if(!is_numeric($height)) colordie("invalid height");
    $pupdates .= " height='".mysqli_real_escape_string($conn, $height)."',";
  }
  if(isset($GLOBALS['shipdepth'.$x]))  
  { $depth = str_replace(",", ".", $GLOBALS['shipdepth'.$x]);
    if(!is_numeric($depth)) colordie("invalid depth");
    $pupdates .= " depth='".mysqli_real_escape_string($conn, $depth)."',";
  }
  if(isset($GLOBALS['shipwidth'.$x]))  
  { $width = str_replace(",", ".", $GLOBALS['shipwidth'.$x]);
    if(!is_numeric($width)) colordie("invalid width");
    $pupdates .= " width='".mysqli_real_escape_string($conn, $width)."',";
  }
  if(isset($GLOBALS['ean'.$x]))
    $pupdates .= " ean13='".mysqli_real_escape_string($conn, $GLOBALS['ean'.$x])."',";
  if(isset($GLOBALS['aShipCost'.$x]))
    $pupdates .= " additional_shipping_cost='".mysqli_real_escape_string($conn, $GLOBALS['aShipCost'.$x])."',";
  if(isset($GLOBALS['price'.$x]))
  { $price = str_replace(",", ".", $GLOBALS['price'.$x]);
    if(!is_numeric($price)) colordie("invalid price");
    $pupdates .= " price='".mysqli_real_escape_string($conn, $price)."',";
  }
  if(isset($GLOBALS['manufacturer'.$x]))
    $pupdates .= " id_manufacturer='".mysqli_real_escape_string($conn, $GLOBALS['manufacturer'.$x])."',";
  if(isset($GLOBALS['category_default'.$x]) && ($GLOBALS['category_default'.$x] != '0'))
    $pupdates .= " id_category_default='".mysqli_real_escape_string($conn, $GLOBALS['category_default'.$x])."',";
  if(isset($GLOBALS['reference'.$x]))
    $pupdates .= " reference='".mysqli_real_escape_string($conn, $GLOBALS['reference'.$x])."',";
  if($pupdates != "")
  { $query = "UPDATE ". _DB_PREFIX_."product SET".substr($pupdates,0,strlen($pupdates)-1)." WHERE id_product='".$id_product."'";
    dbquery($query);
  }

  $psupdates = "";
  if($srcscript == "product-edit")
    $psupdates = " date_upd='".date("Y-m-d H:i:s", time())."',";
  if(isset($GLOBALS['active'.$x]))  
  { $active = $GLOBALS['active'.$x];
    if(($active != "0") && ($active != "1")) colordie("invalid active for ".$x);
    $psupdates .= " active='".mysqli_real_escape_string($conn, $active)."',";
  }
  if(isset($GLOBALS['on_sale'.$x]))  
  { $onsale = $GLOBALS['on_sale'.$x];
    if(($onsale != "0") && ($onsale != "1")) colordie("invalid onsale for ".$x);
    $psupdates .= " on_sale='".mysqli_real_escape_string($conn, $onsale)."',";
  }
  if(isset($GLOBALS['online_only'.$x]))  
  { $onlineonly = $GLOBALS['online_only'.$x];
    if(($onlineonly != "0") && ($onlineonly != "1")) colordie("invalid online_only for ".$x);
    $psupdates .= " online_only='".mysqli_real_escape_string($conn, $onlineonly)."',";
  }
  	if(isset($GLOBALS['stockflags'.$x]))  
    { if($GLOBALS['stockflags'.$x] == 1) $advanced_stock_management = "0"; else $advanced_stock_management = "1";
      $psupdates .= " advanced_stock_management='".mysqli_real_escape_string($conn, $advanced_stock_management)."',";
    }
  if(isset($GLOBALS['available'.$x]))  
  { $available = $GLOBALS['available'.$x];
    if(($available != "0") && ($available != "1")) colordie("invalid available for ".$x);
    $psupdates .= " available_for_order='".mysqli_real_escape_string($conn, $available)."',";
  }
  if(isset($GLOBALS['minimal_quantity'.$x]))  
  { $minimalquantity = $GLOBALS['minimal_quantity'.$x];
    if(!is_numeric($minimalquantity)) colordie("invalid minimal_quantity for ".$x);
    $psupdates .= " minimal_quantity='".mysqli_real_escape_string($conn, $minimalquantity)."',";
  }
  if(isset($GLOBALS['price'.$x]))
  { $price = str_replace(",", ".", $GLOBALS['price'.$x]);
    if(!is_numeric($price)) colordie("invalid price");
    $psupdates .= " price='".mysqli_real_escape_string($conn, $price)."',";
  }
  if(isset($GLOBALS['wholesaleprice'.$x]))
  { $wholesaleprice = str_replace(",", ".", $GLOBALS['wholesaleprice'.$x]);
    if(!is_numeric($wholesaleprice)) colordie("invalid wholesale price");
    $psupdates .= " wholesale_price='".mysqli_real_escape_string($conn, $wholesaleprice)."',";
  }
  if(isset($GLOBALS['aShipCost'.$x]))
    $psupdates .= " additional_shipping_cost='".mysqli_real_escape_string($conn, $GLOBALS['aShipCost'.$x])."',";
  if(isset($GLOBALS['VAT'.$x]))
    $psupdates .= " id_tax_rules_group='".mysqli_real_escape_string($conn, $GLOBALS['VAT'.$x])."',";
  if(isset($GLOBALS['category_default'.$x]) && ($GLOBALS['category_default'.$x] != '0'))
    $psupdates .= " id_category_default='".mysqli_real_escape_string($conn, $GLOBALS['category_default'.$x])."',";
  if($psupdates != "")
  { if(!isset($id_shop)) die("<p><h2>Shop must be provided!</h2>");
    $query = "UPDATE ". _DB_PREFIX_."product_shop SET".substr($psupdates,0,strlen($psupdates)-1)." WHERE id_product='".$id_product."' AND id_shop='".$id_shop."'";
    dbquery($query);
  }

  $plupdates = "";
  if(isset($GLOBALS['name'.$x]))
    $plupdates .= " name='".mysqli_real_escape_string($conn, strip($GLOBALS['name'.$x]))."',";
  if(isset($GLOBALS['description'.$x]))
    $plupdates .= " description='".mysqli_real_escape_string($conn, strip($GLOBALS['description'.$x]))."',";
  if(isset($GLOBALS['description_short'.$x]))
    $plupdates .= " description_short='".mysqli_real_escape_string($conn, strip($GLOBALS['description_short'.$x]))."',";
  if(isset($GLOBALS['link_rewrite'.$x]))
    $plupdates .= " link_rewrite='".mysqli_real_escape_string($conn, $GLOBALS['link_rewrite'.$x])."',";
  if(isset($GLOBALS['meta_title'.$x]))
    $plupdates .= " meta_title='".mysqli_real_escape_string($conn, strip($GLOBALS['meta_title'.$x]))."',";
  if(isset($GLOBALS['meta_keywords'.$x]))
  { $keywords = preg_replace("/,$/", "", strip($GLOBALS['meta_keywords'.$x])); /* comma at end gives problems in PS backoffice SEO page */
    $plupdates .= " meta_keywords='".mysqli_real_escape_string($conn, $keywords)."',";
  }
  if(isset($GLOBALS['meta_description'.$x]))
    $plupdates .= " meta_description='".mysqli_real_escape_string($conn, strip($GLOBALS['meta_description'.$x]))."',";
  if($plupdates != "")
  { if($pupdates != "") echo "<br> &nbsp; &nbsp; ";
    if(!isset($id_shop)) die("<p><h2>Shop must be provided!</h2>");
    $query = "UPDATE ". _DB_PREFIX_."product_lang SET".substr($plupdates,0,strlen($plupdates)-1)." WHERE id_product='".$id_product."' AND id_lang='".$id_lang."' AND id_shop='".$id_shop."'";
    dbquery($query);
  }
  
  $discupdates = "";
  if(isset($GLOBALS['discount_count'.$x]) && ($GLOBALS['discount_count'.$x] != ""))
  { if(file_exists("TE_plugin_discounts.php"))
      include "TE_plugin_discounts.php";
  }
  
  $supupdates = "";
  if(isset($GLOBALS['mysups'.$x]) && ($GLOBALS['mysups'.$x] != ""))
  { if(file_exists("TE_plugin_suppliers.php"))
      include "TE_plugin_suppliers.php";
  }
  
  $stockupdates = "";
  if(isset($GLOBALS['qty'.$x]) && ($GLOBALS['qty'.$x] != ""))
  { if(!is_numeric($GLOBALS['qty'.$x])) colordie("invalid quantity");
    $quantity = intval($GLOBALS['qty'.$x]);
    if($share_stock)
	{ $query = "SELECT quantity FROM ". _DB_PREFIX_."stock_available WHERE id_shop_group = '".$id_shop_group."' AND id_product='".$id_product."'";
	  $res = dbquery($query);
	  /* the out_of_stock field determines whether ordering is allowed when stock too low: 2=not allowed 1=allowed 0=follow shop preferences */
	  if(mysqli_num_rows($res) == 0) /* no quantity entered yet  */
	  { $query = "INSERT INTO ". _DB_PREFIX_."stock_available SET quantity='".$quantity."', id_shop_group ='".$id_shop_group."', id_shop=0, id_product='".$id_product."', out_of_stock='2'";
	    $res = dbquery($query);
	  }
	  else if(mysqli_num_rows($res) == 1)  /* ignore products with combinations!!! */
	  { $query = "UPDATE ". _DB_PREFIX_."stock_available SET quantity='".$quantity."' WHERE id_shop_group ='".$id_shop_group."' AND id_product='".$id_product."'";
	    $res = dbquery($query);
	  }
	  else echo "<br/><b>Your attempt to update the quantities of a product with combinations was ignored</b><br/>";
	}
	else
	{ $query = "SELECT quantity FROM ". _DB_PREFIX_."stock_available WHERE id_shop = '".$id_shop."' AND id_product='".$id_product."'";
	  $res = dbquery($query);
	  /* the out_of_stock field determines whether ordering is allowed when stock too low: 2=not allowed 1=allowed 0=follow shop preferences */
	  if(mysqli_num_rows($res) == 0)  /* no quantity entered yet  */
	  { $query = "INSERT INTO ". _DB_PREFIX_."stock_available SET quantity='".$quantity."', id_shop ='".$id_shop."', id_product='".$id_product."', out_of_stock='2'";
	    $res = dbquery($query);
	  }
	  else if(mysqli_num_rows($res) == 1)  /* ignore products with combinations!!! */
	  { $query = "UPDATE ". _DB_PREFIX_."stock_available SET quantity='".$quantity."' WHERE id_shop ='".$id_shop."' AND id_product='".$id_product."'";
	    $res = dbquery($query);
	  }
	  else echo "<br/><b>Your attempt to update the quantities of a product with combinations was ignored</b><br/>";
	}
  }

	if(isset($GLOBALS['stockflags'.$x]))  
    { if($GLOBALS['stockflags'.$x] == 3) $depends_on_stock = "1"; else $depends_on_stock = "0";
	  $query = "UPDATE ". _DB_PREFIX_."stock_available SET depends_on_stock='".mysqli_real_escape_string($conn, $depends_on_stock)."' WHERE id_shop ='".$id_shop."' AND id_product='".$id_product."'";
	  $res = dbquery($query);
    }
  
  if(($srcscript == "product-vissort") || ($srcscript == "product-sort"))
  { $query = "UPDATE ". _DB_PREFIX_."category_product SET position='".intval($x)."' WHERE id_product='".$id_product."' AND id_category='".$id_category."'";
    dbquery($query);

  }
  
  if(isset($GLOBALS['tags'.$x]))
  { if(file_exists("TE_plugin_tags.php"))
	  include "TE_plugin_tags.php";
  }
  
  if(isset($GLOBALS['accessories'.$x]))
  { $query ="SELECT GROUP_CONCAT(id_product_2) AS accessories FROM "._DB_PREFIX_."accessory";
	$query.=" WHERE id_product_1='".$id_product."' GROUP BY id_product_1";
	$res = dbquery($query);
	$row = mysqli_fetch_array($res);
	if($row["accessories"] != $GLOBALS['accessories'.$x])
	{ $oldaccs = explode(",", $row["accessories"]);
	  $newaccs = explode(",", $GLOBALS['accessories'.$x]);
	  foreach($oldaccs AS $oldacc)
	    if(!in_array($oldacc, $newaccs))
		{ $query = "DELETE FROM "._DB_PREFIX_."accessory WHERE id_product_1='".$id_product."' AND id_product_2='".$oldacc."'";
		  dbquery($query);
		}
	  foreach($newaccs AS $newacc)
	    if(!in_array($newacc, $oldaccs))
		{ if(!in_array($newacc, $valid_products))
		  { $query = "SELECT id_product FROM "._DB_PREFIX_."product WHERE id_product='".$newacc."'";
		    $res = dbquery($query);
			if(mysqli_num_rows($res) == 0)
			{ echo "<p><b>".$newacc." is not a valid article number!!!</b><br/>";
			  if(!in_array($newacc, $invalid_products))
			  { $invalid_products[] = $newacc;
				$errstring .= "\\n".$newacc." is not a valid product id number!";
			  }
			  continue;
			}
			$valid_products[] = $newacc;
		  }
		  $query = "INSERT INTO "._DB_PREFIX_."accessory SET id_product_1='".$id_product."', id_product_2='".$newacc."'";
		  dbquery($query);
		}
	}
  }

  if(isset($_POST["featuresset"]) && ($_POST["featuresset"] == 1))
  { if(file_exists("TE_plugin_features.php"))
	  include "TE_plugin_features.php";
  }
}

$sorted_categories = array(); // prevent that we sort the same category twice
foreach ($changed_categories AS $changedcat)
{ if(in_array($changedcat, $sorted_categories))
	continue;
  $sorted_categories[] = $changedcat;
  // the following is a copy of the function cleanPositions($id_category) in file product.php in the Classes directory
  // it readapts the positions after deletions
  echo "Now assign all products in category ".$changedcat." a new position to fill the space left by the deletion(s).<br/>";
  $xquery = 'SELECT `id_product` FROM `'._DB_PREFIX_.'category_product` WHERE `id_category` = '.(int)($changedcat).' ORDER BY `position`';
  $xres=dbquery($xquery);
  $i = 0;
  while ($xrow=mysqli_fetch_array($xres)) 
  { $yquery = 'UPDATE `'._DB_PREFIX_.'category_product` SET `position` = '.(int)($i++).' WHERE `id_category` = '.(int)($changedcat).' AND `id_product` = '.(int)($xrow['id_product']);
	$yres=dbquery($yquery);
  }
}

foreach($deleted_tags AS $deleted_tag)
{ $dquery = "SELECT id_product FROM "._DB_PREFIX_."product_tag WHERE id_tag='".$deleted_tag."' LIMIT 1";
  $dres=dbquery($dquery);
  if(mysqli_num_rows($dres) == 0)
  { $query ="DELETE FROM "._DB_PREFIX_."tag WHERE id_tag='".$deleted_tag."' AND id_lang='".$id_lang."'";
	$res = dbquery($query);
  }	
}

if($errstring != "")
{ echo "<script>alert('There were errors: ".$errstring."');</script>!";
  echo str_replace("\n","<br>",$errstring);
}

echo "<br>Finished successfully!<p>Go back to <a href='".$refscript."'>".$srcscript." page</a>";

if(isset($_POST['id_row']))
{ $row = substr($_POST['id_row'], 4);
  echo "\n<script>updateblock = [];";
  foreach($updateblock AS $field => $updateline)
  { echo "\nupdateblock['".$field."'] = [];";
    foreach($updateline AS $subrow => $subid)
    { echo "\nupdateblock['".$field."']['".$subrow."'] = '".$subid."'; ";
	}
  }
  echo "\nif(parent) parent.reg_unchange(".$row.", updateblock);</script>";
}
else if($verbose!="true")
{ echo "<script>location.href = '".$refscript."';</script>";
}

echo "</body></html>";

function check_customer($customer)
{ $dquery = "SELECT id_customer FROM "._DB_PREFIX_."customer WHERE id_customer='".$customer."' LIMIT 1";
  $dres=dbquery($dquery);
  if(mysqli_num_rows($dres) == 0)
    colordie("Customer No ".$customer." is not a valid customer number");
}
function check_country($country)
{ global $countries;
  if(!isset($countries))
  { $cquery = "SELECT id_country FROM "._DB_PREFIX_."country";
    $cres=dbquery($cquery);
	$countries = array();
	while ($crow=mysqli_fetch_array($cres)) 
	  $countries[] = $crow["id_country"];
  }
  if(!in_array($country, $countries))
    colordie("Country No ".$country." is not a valid country number");
}
function check_group($group)
{ global $groups;
  if(!isset($groups))
  { $gquery = "SELECT id_group FROM "._DB_PREFIX_."group";
    $gres=dbquery($gquery);
	$groups = array();
	while ($grow=mysqli_fetch_array($gres)) 
	  $groups[] = $grow["id_group"];
  }
  if(!in_array($group, $groups))
    colordie("Group No ".$group." is not a valid group number");
}
function check_currency($currency)
{ global $currencies;
  if(!isset($currencies))
  { $cquery = "SELECT id_currency FROM "._DB_PREFIX_."currency";
    $cres=dbquery($cquery);
	$currencies = array();
	while ($crow=mysqli_fetch_array($cres)) 
	  $currencies[] = $crow["id_currency"];
  }
  if(!in_array($currency, $currencies))
    colordie("Currency No ".$currency." is not a valid currency number");
}

function check_shop($shop)
{ global $shops;
  if(!isset($shops))
  { $squery = "SELECT id_shop FROM "._DB_PREFIX_."shop";
    $sres=dbquery($squery);
	$shops = array();
	while ($srow=mysqli_fetch_array($sres)) 
	  $shops[] = $srow["id_shop"];
  }
  if(!in_array($shop, $shops))
    colordie("Shop No ".$shop." is not a valid shop number");
}

/* strip makes sure that the requirements of isValidName() - that is used by PS on many fields - are respected */
/* first to check which fields */
function strip($txt)
{ // $txt = preg_replace('/[<>={}]+/', '', $txt);
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

