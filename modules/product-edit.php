<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;
if(!isset($input['search_txt1'])) $input['search_txt1'] = "";
if(!isset($input['search_txt2'])) $input['search_txt2'] = "";
if(!isset($input['search_fld1']) || ($input['search_fld1'] == "")) $input['search_fld1'] = "main fields";
if(!isset($input['search_fld2']) || ($input['search_fld2'] == "")) $input['search_fld2'] = "main fields";
if(!isset($input['startrec']) || (trim($input['startrec']) == '')) $input['startrec']="0";
if(!isset($input['numrecs'])) $input['numrecs']="100";
if(!isset($input['id_category'])) $input['id_category']="0";
if(!isset($input['id_shop'])) $input['id_shop']="1";
$id_shop = intval($input["id_shop"]);
if(!isset($input['startdate'])) $input['startdate']="";
if(!isset($input['enddate'])) $input['enddate']="";
if(!isset($input['rising'])) $input['rising'] = "ASC";
if(!isset($input['order']))
{ $input['order']="position";
  if($input['id_category']=="0")
    $input['order']="id_product"; /* sort by product */
}
if(!isset($input['id_lang'])) $input['id_lang']="";
if(!isset($input['fields'])) $input['fields']="";

  if(empty($input['fields'])) // if not set, set default set of active fields
    $input['fields'] = $default_product_fields; /* this is set in settings1.php */
  $infofields = array();
  $if_index = 0;
   /* [0]title, [1]keyover, [2]source, [3]display(0=not;1=yes;2=edit;), [4]fieldwidth(0=not set), [5]align(0=default;1=right), [6]sortfield, [7]Editable, [8]table */
  define("HIDE", 0); define("DISPLAY", 1); define("EDIT", 2);  // display
  define("LEFT", 0); define("RIGHT", 1); // align
  define("NO_SORTER", 0); define("SORTER", 1); /* sortfield => 0=no escape removal; 1=escape removal; */
  define("NOT_EDITABLE", 0); define("INPUT", 1); define("TEXTAREA", 2); define("DROPDOWN", 3); define("BINARY", 4); define("EDIT_BTN", 5);  /* title, keyover, source, display(0=not;1=yes;2=edit), fieldwidth(0=not set), align(0=default;1=right), sortfield */
   /* sortfield => 0=no escape removal; 1=escape removal; 2 and higher= escape removal and n lines textarea */
  $infofields[$if_index++] = array("","", "", DISPLAY, 0, LEFT, 0,0);
  $infofields[$if_index++] = array("id","", "id_product", DISPLAY, 0, RIGHT, NO_SORTER,NOT_EDITABLE);
  
  $field_array = array(
   "name" => array("name","", "name", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "pl.name"),
   "active" => array("active","", "active", DISPLAY, 0, LEFT, NO_SORTER, BINARY, "p.active"),
   "reference" => array("reference","", "reference", DISPLAY, 200, LEFT, NO_SORTER, INPUT, "p.reference"),
   "ean" => array("ean","", "ean13", DISPLAY, 200, LEFT, NO_SORTER, INPUT, "p.ean13"),
   "category" => array("category","", "id_category_default", DISPLAY, 0, LEFT, NO_SORTER, DROPDOWN, "p.id_category_default"),
   "price" => array("price","", "price", DISPLAY, 200, LEFT, NO_SORTER, INPUT, "p.price"),
   "VAT" => array("VAT","", "rate", DISPLAY, 0, LEFT, NO_SORTER, DROPDOWN, ""),
   "priceVAT" => array("priceVAT","", "priceVAT", DISPLAY, 0, LEFT, NO_SORTER, INPUT, ""),
   "quantity" => array("qty","", "quantity", DISPLAY, 0, LEFT, NO_SORTER, TEXTAREA, "s.quantity"),
   "shortdescription" => array("description_short","shortdescription", "description_short", DISPLAY, 0, LEFT, NO_SORTER, TEXTAREA, "pl.description_short"),
   "description" => array("description","", "description", DISPLAY, 0, LEFT, NO_SORTER, TEXTAREA, "pl.description"),
   "manufacturer" => array("manufacturer","", "manufacturer", DISPLAY, 0, LEFT, NO_SORTER, DROPDOWN, "m.name"),
   "stockflags" => array("stockflags","", "depends_on_stock", DISPLAY, 0, LEFT, NO_SORTER, BINARY, "sa.depends_on_stock"),
   "supplier" => array("supplier","", "supplier", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "su.name"),
   "linkrewrite" => array("link_rewrite","linkrewrite", "link_rewrite", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "pl.link_rewrite"),
   "metatitle" => array("meta_title","metatitle", "meta_title", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "pl.meta_title"),
   "metakeywords" => array("meta_keywords","metakeywords", "meta_keywords", DISPLAY, 0, RIGHT, NO_SORTER, TEXTAREA, "pl.meta_keywords"),
   "metadescription" => array("meta_description","metadescription", "meta_description", DISPLAY, 0, LEFT, NO_SORTER, TEXTAREA, "pl.meta_description"),
   "available" => array("available","", "available_for_order", DISPLAY, 0, LEFT, NO_SORTER, BINARY, "p.available_for_order"),   
   "onsale" => array("on_sale","onsale", "on_sale", DISPLAY, 0, LEFT, NO_SORTER, BINARY, "p.on_sale"),
   "onlineonly" => array("online_only","onlineonly", "online_only", DISPLAY, 0, LEFT, NO_SORTER, BINARY, "p.online_only"),
   "minimalquantity" => array("minimal_quantity","minimalquantity", "minimal_quantity", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "p.minimal_quantity"),
   "carrier" => array("carrier","", "carrier", DISPLAY, 0, LEFT, NO_SORTER, DROPDOWN, "cr.name"),
   "combinations" => array("combinations","", "combinations", DISPLAY, 0, LEFT, 0, 0, ""),
   "tags" => array("tags","", "tags", DISPLAY, 0, LEFT, NO_SORTER, TEXTAREA, "tg.name"),
   "shipweight" => array("shipweight","", "weight", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "p.weight"),
   "accessories" => array("accessories","", "accessories", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "accessories"),
   "image" => array("image","", "name", DISPLAY, 0, LEFT, 0, EDIT_BTN, ""), // name here is a dummy that is not used
   "discount" => array("discount","", "discount", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "discount"),
   
   /* fourth line */
   "date_upd" => array("date_upd","", "date_upd", DISPLAY, 0, LEFT, NO_SORTER, NOT_EDITABLE, "p.date_upd"),
   "warehousing" => array("warehousing","", "quantity", DISPLAY, 0, LEFT, NO_SORTER, NOT_EDITABLE, "s.quantity"),
   "shipheight" => array("shipheight","", "height", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "p.height"),
   "shipwidth" => array("shipwidth","", "width", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "p.width"),
   "shipdepth" => array("shipdepth","", "depth", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "p.depth"), 
   "wholesaleprice" => array("wholesaleprice","", "wholesale_price", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "ps.wholesale_price"),
   "aShipCost" => array("aShipCost","", "additional_shipping_cost", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "ps.additional_shipping_cost"),
   "attachmnts" => array("attachmnts","", "attachmnts", DISPLAY, 0, LEFT, NO_SORTER, INPUT, ""),  
	  
	/* statistics */
   "visits" => array("visits","", "visitcount", DISPLAY, 0, LEFT, NO_SORTER, NOT_EDITABLE, "visitcount"),
   "visitz" => array("visitz","", "visitedpages", DISPLAY, 0, LEFT, NO_SORTER, NOT_EDITABLE, "visitedpages"),
   "salescnt" => array("salescnt","", "salescount", DISPLAY, 0, LEFT, NO_SORTER, NOT_EDITABLE, "salescount"),
   "revenue" => array("revenue","", "revenue", DISPLAY, 0, LEFT, NO_SORTER, NOT_EDITABLE, "revenue"),
   "orders" => array("orders","", "ordercount", DISPLAY, 0, LEFT, NO_SORTER, NOT_EDITABLE, "ordercount"),
   "buyers" => array("buyers","", "buyercount", DISPLAY, 0, LEFT, NO_SORTER, NOT_EDITABLE, "buyercount")
   ); 

  if(in_array("priceVAT", $input["fields"])) /* if PriceVAT in array => replace it with price for simplification of the following foreach*/
  { $input["fields"] = array_diff($input["fields"], array("priceVAT"));
    if(!in_array("price", $input["fields"]))
	  array_push($input["fields"], "price");
  }
  foreach($field_array AS $key => $value)
  { if (in_array($key, $input["fields"]))
    { 	if (($key != "VAT") || (!in_array("price", $input["fields"]))) /* prevent showing "VAT" twice */
			$infofields[$if_index++] = $value;
		if($key == "price")
		{ 	$infofields[$if_index++] = $field_array["VAT"];
			$infofields[$if_index++] = $field_array["priceVAT"];
		}
	}
  }
  
//id_category 	id_parent 	level_depth 	nleft 	nright 	active 	date_add 	date_upd 	position
//id_category  id_lang 	name 	description 	link_rewrite 	meta_title 	meta_keywords 	meta_description

$rewrite_settings = get_rewrite_settings();

/* get default language: we use this for the categories, manufacturers */
$query="select value, l.name, l.iso_code from ". _DB_PREFIX_."configuration f, ". _DB_PREFIX_."lang l";
$query .= " WHERE f.name='PS_LANG_DEFAULT' AND f.value=l.id_lang";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$def_lang = $row['value'];
$def_langname = $row['name'];
$iso_code = $row['iso_code'];

/* Get default language if none provided */
if($input['id_lang'] == "") 
  $id_lang = $def_lang;
else
{ $query="select name, iso_code from ". _DB_PREFIX_."lang WHERE id_lang='".(int)$input['id_lang']."'";
  $res=dbquery($query);
  $row = mysqli_fetch_array($res);
  $languagename = $row['name'];
  $id_lang = $input['id_lang'];
  $iso_code = $row['iso_code'];
}

$and_code = "and"; /* this is the word that will be used instead of the ampersand when you regenerate link_rewrites */
if($iso_code == "fr") $and_code = "et";
if($iso_code == "es") $and_code = "y";
if($iso_code == "de") $and_code = "und";
if($iso_code == "it") $and_code = "e";
if($iso_code == "nl") $and_code = "en";

/* Get default country for the VAT tables and calculations */
$query="select l.name, id_country from ". _DB_PREFIX_."configuration f, "._DB_PREFIX_."country_lang l";
$query .= " WHERE f.name='PS_COUNTRY_DEFAULT' AND f.value=l.id_country ORDER BY id_lang IN('".$def_lang."','1') DESC"; /* the construction with the languages should select all languages with def_lang and '1' first */
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$countryname = $row['name'];
$id_country = $row["id_country"];

/* get shop group and its shared_stock status */
$query="select s.id_shop_group, g.share_stock, g.name from ". _DB_PREFIX_."shop s, "._DB_PREFIX_."shop_group g";
$query .= " WHERE s.id_shop_group=g.id_shop_group and id_shop='".$id_shop."'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$id_shop_group = $row['id_shop_group'];
$share_stock = $row["share_stock"];
$shop_group_name = $row["name"];

/* make tax block */
  $query = "SELECT rate,name,tr.id_tax_rule,g.id_tax_rules_group FROM "._DB_PREFIX_."tax_rule tr";
  $query .= " LEFT JOIN "._DB_PREFIX_."tax t ON (t.id_tax = tr.id_tax)";
  $query .= " LEFT JOIN "._DB_PREFIX_."tax_rules_group g ON (tr.id_tax_rules_group = g.id_tax_rules_group)";
  $query .= " WHERE tr.id_country = '".$id_country."'";
  $res=dbquery($query);
  $taxblock = '<option value="">Select VAT</option>';
  while($row = mysqli_fetch_array($res))
  { $taxblock .= '<option value="'.$row['id_tax_rules_group'].'" rate="'.$row['rate'].'">'.str_replace("'","\'",$row['name']).'</option>';
  }   
  $taxblock .= "</select>";

/* look for double category names */
  $duplos = array();
  $query = "select name,count(*) AS duplocount from ". _DB_PREFIX_."category_lang WHERE id_lang='".$def_lang."' AND id_shop='".$id_shop."' GROUP BY name HAVING duplocount > 1";
  $res=dbquery($query);
  while ($row=mysqli_fetch_array($res)) 
  {  $duplos[] = $row["name"];
  }
  
/* make category block */
  $query = "select id_category,name from ". _DB_PREFIX_."category_lang WHERE id_lang='".$def_lang."' AND id_shop='".$id_shop."' ORDER BY name";
  $res=dbquery($query);
  $category_names = array();
  $allcats = array();
  $x=0;
  $categoryblock0 = '<input type=hidden name="category_defaultCQX"><input type=hidden name="mycatsCQX">';
  $categoryblock0 .= '<table cellspacing=8><tr><td><select id="categorylistCQX" size=4 multiple>';
  $categoryblock1 = "";
  while ($row=mysqli_fetch_array($res)) 
  { if(in_array($row['name'], $duplos))
	  $name = $row['name'].$row['id_category'];
	else
	  $name = $row['name'];
    $categoryblock1 .= '<option value="'.$row['id_category'].'">'.str_replace("'","\'",$name).'</option>';
    $category_names[$row['id_category']] = $name;
  } 
  $categoryblock1 .= '</select>';
  $categoryblock2 = '</td><td><a href=# onClick=" Addcategory(\\\'CQX\\\'); reg_change(this); return false;"><img src=add.gif border=0></a><br><br>';
  $categoryblock2 .= '<a href=# onClick="Removecategory(\\\'CQX\\\'); reg_change(this); return false;"><img src=remove.gif border=0></a></td><td><select id=categoryselCQX size=3></select></td>';
  $categoryblock2 .= '<td><a href=# onClick="MakeCategoryDefault(\\\'CQX\\\'); reg_change(this); return false;"><img src="starr.jpg" border=0></a></td></td></tr></table>';
  
if(in_array('stockflags', $input["fields"])) 
{ $stockflagsarray = array("Manual","WHing and manual","Warehousing only");
  $stockflagsblock = '<option value="1">'.$stockflagsarray[0].'</option><option value="2">'.$stockflagsarray[1].'</option><option value="3">'.$stockflagsarray[2].'</option></select>';
}
  
/* make manufacturer block */
if(in_array('manufacturer', $input["fields"]))
{ $query = "SELECT id_manufacturer,name FROM "._DB_PREFIX_."manufacturer ORDER BY name";
  $res=dbquery($query);
  $manufacturerblock = '<option value="0">No manufacturer</option>';
  while($row = mysqli_fetch_array($res))
  { $manufacturerblock .= '<option value="'.$row['id_manufacturer'].'">'.str_replace("'","\'",$row['name']).'</option>';
  }   
  $manufacturerblock .= "</select>";
}
else 
  $manufacturerblock = "";
  
/* make carrier block */
if(in_array('carrier', $input["fields"]))
{ $query = "select id_reference,name from ". _DB_PREFIX_."carrier WHERE deleted='0' ORDER BY name";
  $res=dbquery($query);
  $carrierblock0 = '<input type=hidden name="carrier_defaultCQX"><input type=hidden name="mycarsCQX">';
  $carrierblock0 .= '<table cellspacing=8><tr><td><select id="carrierlistCQX" size=4 multiple>';
  $carrierblock1 = "";
  while ($row=mysqli_fetch_array($res)) 
  { $carrierblock1 .= '<option value="'.$row['id_reference'].'">'.str_replace("'","\'",$row['name']).'</option>';
  } 
  $carrierblock1 .= '</select>';
  $carrierblock2 = '</td><td><a href=# onClick=" Addcarrier(\\\'CQX\\\'); reg_change(this); return false;"><img src=add.gif border=0></a><br><br>';
  $carrierblock2 .= '<a href=# onClick="Removecarrier(\\\'CQX\\\'); reg_change(this); return false;"><img src=remove.gif border=0></a></td><td><select id=carrierselCQX size=3><option>none</option></select></td></tr></table>';
}  
else 
  $carrierblock0 = $carrierblock1 = $carrierblock2 = ""; 
  
  /* make supplier names list */
if(in_array('supplier', $input["fields"]))
{ $query = "select id_supplier,name from ". _DB_PREFIX_."supplier ORDER BY name";
  $res=dbquery($query);
  $supplier_names = array();
  $supplierblock0 = '<input type=hidden name="mysupsCQX">';
  $supplierblock0 .= '<table><tr><td><select id="supplierlistCQX">';
  $supplierblock1 = "";
  while ($row=mysqli_fetch_array($res)) 
  { $supplier_names[$row['id_supplier']] = $row['name'];
    $supplierblock1 .= '<option value="'.$row['id_supplier'].'">'.str_replace("'","\'",$row['name']).'</option>';
  }
  $supplierblock1 .= '</select>';
  $supplierblock2 = '</td><td><nobr><a href=# onClick=" Addsupplier(\\\'CQX\\\',1); reg_change(this); return false;"><img src=add.gif border=0></a> &nbsp; &nbsp; ';
  $supplierblock2 .= '<a href=# onClick="Removesupplier(\\\'CQX\\\'); reg_change(this); return false;"><img src=remove.gif border=0></a></nobr></td><td><select id="supplierselCQX"></select></td></tr></table>';
}
else 
  $supplierblock0 = $supplierblock1 = $supplierblock2 = "";
  
/* make attachment attachmnts block */
if(in_array('attachmnts', $input["fields"]))
{ $query = "SELECT a.file_name, l.name, a.id_attachment FROM ". _DB_PREFIX_."attachment a";
  $query .= " LEFT JOIN ". _DB_PREFIX_."attachment_lang l ON a.id_attachment=l.id_attachment AND l.id_lang='".$id_lang."'";
  $query .= " ORDER BY l.name";
  $res = dbquery($query);
  $attachmentblock0 = '<input type=hidden name="attachment_defaultCQX"><input type=hidden name="myattachmentsCQX">';
  $attachmentblock0 .= '<table cellspacing=8><tr><td><select id="attachmentlistCQX" size=4 multiple>';
  $attachmentblock1 = "";
  while ($row=mysqli_fetch_array($res)) 
  { $attachmentblock1 .= '<option value="'.$row['id_attachment'].'">'.str_replace("'","\'",$row['name']).'</option>';
  } 
  $attachmentblock1 .= '</select>';
  $attachmentblock2 = '</td><td><a href=# onClick=" Addattachment(\\\'CQX\\\'); reg_change(this); return false;"><img src=add.gif border=0></a><br><br>';
  $attachmentblock2 .= '<a href=# onClick="Removeattachment(\\\'CQX\\\'); reg_change(this); return false;"><img src=remove.gif border=0></a></td><td><select id=attachmentselCQX size=3><option>none</option></select></td></tr></table>';
  $currentDir = dirname(__FILE__);
  $download_dir = $currentDir."/".$triplepath."download/";
}
else 
  $attachmentblock0 = $attachmentblock1 = $attachmentblock2 = "";
  
/* Make blocks for features */
$query = "SELECT fl.id_feature, name FROM ". _DB_PREFIX_."feature_lang fl";
$query .= " LEFT JOIN ". _DB_PREFIX_."feature_shop fs ON fs.id_feature = fl.id_feature";
$query .= " WHERE id_lang='".$id_lang."' AND id_shop='".$id_shop."'";
$query .= " ORDER BY id_feature";
$res = dbquery($query);
$features = array();
$featureblocks = array();
$featurecount = 0;
$featurelist = array();
$featurekeys = array();
while($row = mysqli_fetch_array($res))
{ $features[$row['id_feature']] = $row['name'];
  if(in_array($row['name'], $input["fields"]))
  { $featurelist[$row['id_feature']] = $row['name'];
    $featurekeys[] = $row['id_feature'];
	$block = '<option value="">Select '.str_replace("'","\'",$row['name']).'</option>';
    $fquery = "SELECT v.id_feature_value, value FROM ". _DB_PREFIX_."feature_value v";
	$fquery .= " LEFT JOIN ". _DB_PREFIX_."feature_value_lang vl ON v.id_feature_value = vl.id_feature_value AND vl.id_lang='".$id_lang."'";
	$fquery .= " WHERE v.id_feature='".$row['id_feature']."' AND v.custom='0'";
	$fres = dbquery($fquery);
	if(mysqli_num_rows($fres) == 0)
		$featureblocks[$featurecount++] = "";
	else
	{ $fvalues = array();
	  while($frow = mysqli_fetch_array($fres))
	  {  $fvalues[$frow['id_feature_value']] = $frow['value'];
	  }
	  natsort($fvalues);
	  foreach($fvalues AS $key => $value)
	  { $block .= '<option value="'.$key.'">'.str_replace("'","\'",$value).'</option>';
	  }
	  $featureblocks[$featurecount++] = $block."</select>";
	}
  }
}

/* making shop block */
    $shopblock = "";
	$shops = array();
	$query=" select id_shop,name from ". _DB_PREFIX_."shop ORDER BY id_shop";
	$res=dbquery($query);
	while ($shop=mysqli_fetch_array($res)) {
        $shopblock .= '<option  value="'.$shop['id_shop'].'">'.$shop['id_shop']."-".$shop['name'].'</option>';
		$shops[] = $shop['name'];
	}	


/* Make the discount blocks */
/* 						0				1		2		3		  4			5			6		7				8			9	 		10	11*/
/* discount fields: shop, product_attribute, currency, country, group, id_customer, price, from_quantity, reduction, reduction_type, from, to */
  if(in_array("discount", $input["fields"]))
  { $currencyblock = "";
    $currencies = array();
	$query=" select id_currency,iso_code from ". _DB_PREFIX_."currency WHERE deleted='0' AND active='1' ORDER BY name";
	$res=dbquery($query);
	while ($currency=mysqli_fetch_array($res)) {
		$currencyblock .= '<option  value="'.$currency['id_currency'].'" >'.$currency['iso_code'].'</option>';
		$currencies[] = $currency['iso_code'];
	}
	
	$countryblock = "";
	$query=" select id_country,name from ". _DB_PREFIX_."country_lang WHERE id_lang='".$id_lang."' ORDER BY name";
	$res=dbquery($query);
	while ($country=mysqli_fetch_array($res)) {
		$countryblock .= '<option  value="'.$country['id_country'].'" >'.$country['id_country']."-".$country['name'].'</option>';
	}

	$groupblock = "";
	$query=" select id_group,name from ". _DB_PREFIX_."group_lang WHERE id_lang='".$id_lang."' ORDER BY id_group";
	$res=dbquery($query);
	while ($group=mysqli_fetch_array($res)) {
		$groupblock .= '<option  value="'.$group['id_group'].'" >'.$group['id_group']."-".$group['name'].'</option>';
	}
  }
  
  $carrier_notpaid = $tags_notpaid = $supplier_notpaid = $discount_notpaid = $features_notpaid = false;
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Prestashop Product Multiedit</title>
<link rel="stylesheet" href="style1.css" type="text/css" />
<script src="http://tinymce.cachefly.net/4.1/tinymce.min.js"></script> <!-- Prestashop settings can be found at /js/tinymce.inc.js -->
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<?php 
  if(in_array("discount", $input["fields"]))
    echo '<link rel="stylesheet" href="windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="windowfiles/dhtmlwindow.js"></script>
';
?>
<script type="text/javascript">
var product_fields = new Array();
var taxblock = '<?php echo $taxblock ?>';
var supplierblock0 = '<?php echo $supplierblock0 ?>';
var supplierblock1 = '<?php echo $supplierblock1 ?>';
var supplierblock2 = '<?php echo $supplierblock2 ?>';
var manufacturerblock = '<?php echo $manufacturerblock ?>';
var stockflagsblock = '<?php echo $stockflagsblock ?>';
var categoryblock0 = '<?php echo $categoryblock0 ?>';
var categoryblock1 = '<?php echo $categoryblock1 ?>';
var categoryblock2 = '<?php echo $categoryblock2 ?>';
var attachmentblock0 = '<?php echo $attachmentblock0 ?>';
var attachmentblock1 = '<?php echo $attachmentblock1 ?>';
var attachmentblock2 = '<?php echo $attachmentblock2 ?>';
var carrierblock0 = '<?php echo $carrierblock0 ?>';
var carrierblock1 = '<?php echo $carrierblock1 ?>';
var carrierblock2 = '<?php echo $carrierblock2 ?>';
var featurelist = ['<?php echo implode($featurelist, "','"); ?>'];
var featurekeys = ['<?php echo implode($featurekeys, "','"); ?>'];
var featureblocks = new Array();
<?php 
  for ($i=0; $i<$featurecount; $i++)
  { echo "featureblocks[".$i."]='".$featureblocks[$i]."';
";
  }
  if(in_array("discount", $input["fields"]))
  { echo "currencyblock='".$currencyblock."';
    countryblock='".$countryblock."';
	groupblock='".$groupblock."';
	shopblock='".$shopblock."';
";
    echo 'currencies=["';
	$currs = implode('","', $currencies);
	echo $currs.'"]; 
'; 

    echo 'shops=["';
	$shopz = implode('","', $shops);
	echo $shopz.'"]; 
'; 
  }  
?>
function checkPrices()
{ rv = document.getElementsByClassName("price");
  for(var i in rv) { 
    if(rv[i].value.indexOf(',') != -1) { 
      alert("Please use dots instead of comma's for the prices!");
      rv.focus();
      return false;
    }
  }
  return true;
}

var rowsremoved = 0;
function RemoveRow(row)
{ var tblEl = document.getElementById("offTblBdy");
  var trow = document.getElementById("trid"+row).parentNode;
  trow.innerHTML = "<td></td>";
  rowsremoved++;
}

function RowSubmit(elt)
{ var subtbl = document.getElementById("subtable");
  var row = elt.parentNode.parentNode;
  var rowno = row.childNodes[0].id.substr(4);
  if(!check_discounts(rowno)) return false;
  subtbl.innerHTML = '<tr>'+row.innerHTML+'</tr>';
  // field contents are not automatically copied
  var inputs = row.getElementsByTagName('input');
  for(var k=0;k<inputs.length;k++)
  { if(((inputs[k].name.substring(0,6) == "active") || (inputs[k].name.substring(0,7) == "on_sale") || (inputs[k].name.substring(0,11) == "online_only") || (inputs[k].name.substring(0,9) == "available")) && (inputs[k].type == "hidden"))
	{ elt = document.rowform[inputs[k].name][0]; /* the trick with the hidden field works not with the rowsubmit so we delete it */
	  elt.parentNode.removeChild(elt);
	  continue;
	}
    else if(inputs[k].type != "button")
    { if(((inputs[k].name.substring(0,6) == "active") || (inputs[k].name.substring(0,7) == "on_sale") || (inputs[k].name.substring(0,11) == "online_only") || (inputs[k].name.substring(0,9) == "available")) && (inputs[k].type == "checkbox"))
	  { document.rowform[inputs[k].name].type = "text";
	    if(!inputs[k].checked) document.rowform[inputs[k].name].value = "0"; /* value will initially always be "1" */
	  }
	  else	
	  {	document.rowform[inputs[k].name].value = inputs[k].value;
	  }
      var temp = document.rowform[inputs[k].name].name;
      temp = temp.replace(/[0-9]*$/, ""); /* chance "description1" into "description" */
      document.rowform[inputs[k].name].name = temp;
    }
  }
  var areas = row.getElementsByTagName('textarea');
  for(var k=0;k<areas.length;k++)  
  { if(((areas[k].name.substring(0,17) == "description_short") || (areas[k].name.substring(0,11) == "description")) && (areas[k].parentNode.childNodes[0].tagName == "DIV"))
    { //alert(tinyMCE.get(areas[k].name).getContent());
	  document.rowform[areas[k].name].value = tinyMCE.get(areas[k].name).getContent();
	}
	else if((areas[k].name.substring(0,17) == "description_short") || (areas[k].name.substring(0,11) == "description"))
	{ document.rowform[areas[k].name].value = tidy_html(areas[k].value);
	}
    else
	{ document.rowform[areas[k].name].value = areas[k].value;
	}
    var temp = document.rowform[areas[k].name].name;
    temp = temp.replace(/[0-9]*$/, ""); /* chance "description1" into "description" */
    document.rowform[areas[k].name].name = temp;
  }
  var selects = row.getElementsByTagName('select');
  for(var k=0;k<selects.length;k++)  
  { if(!selects[k].name) continue;
    document.rowform[selects[k].name].selectedIndex = selects[k].selectedIndex;
    var temp = document.rowform[selects[k].name].name;
    temp = temp.replace(/[0-9]*$/, ""); /* chance "description1" into "description" */
    document.rowform[selects[k].name].name = temp;
  }
  var tmp = document.getElementById('featureblock0');
  if(tmp.style == 'hidden')
    rowform.featuresset.value = 0;
  else
    rowform.featuresset.value = 1;
  if(rowform.carriersel0 && rowform.carriersel0.options[0].value == "none")
    rowform.carriersel0.options.length=0;
  rowform.verbose.value = ListForm.verbose.checked;
  document.rowform['id_row'].value = row.childNodes[0].id;
  document.rowform.submit();
}

function CopyToMassEdit(elt)
{
}

function price_change(elt)
{ var tblEl = document.getElementById("offTblBdy");
  var price = elt.value;
  var VATcol = getColumn("VAT");
  var VAT = elt.parentNode.parentNode.cells[VATcol].innerHTML;
  var pvcol = getColumn("priceVAT");
  var newprice = price * (1 + (VAT / 100));
  newprice = Math.round(newprice*1000000)/1000000; /* round to 6 decimals */
  elt.parentNode.parentNode.cells[pvcol].innerHTML = newprice;
  reg_change(elt);
}

function priceVAT_change(elt)
{ var tblEl = document.getElementById("offTblBdy");
  var priceVAT = elt.value;
  var VATcol = getColumn("VAT");
  var VAT = elt.parentNode.parentNode.cells[VATcol].innerHTML;
  var thisrow = elt.name.substring(8);
  var pcol = getColumn("price");
  var newprice = priceVAT / (1 + (VAT / 100));
  newprice = Math.round(newprice*1000000)/1000000; /* round to 6 decimals */
  elt.parentNode.parentNode.cells[pcol].innerHTML = newprice;
  pricefield = eval("Mainform.price"+thisrow);
  pricefield.value = newprice;
  reg_change(elt);
}

function VAT_change(elt)
{ var tblEl = document.getElementById("offTblBdy");
  var col1 = getColumn("price");
  var col2 = getColumn("priceVAT");
  var VAT = elt.options[elt.selectedIndex].getAttribute("rate");
  price = elt.parentNode.parentNode.cells[col1].innerHTML;
  var newpriceVAT = price * (1 + (VAT / 100));
  newpriceVAT = Math.round(newpriceVAT*1000000)/1000000; /* round to 6 decimals */
  elt.parentNode.parentNode.cells[col2].innerHTML = newpriceVAT;
  reg_change(elt);
}

function getColumn(name)
{ var tbl = document.getElementById("Maintable");
  var len = tbl.tHead.rows[0].cells.length;
  for(var i=0;i<len; i++)
  { if(tbl.tHead.rows[0].cells[i].firstChild.innerHTML == name)
      return i;
  }
}

function tidy_html(html) {
    var d = document.createElement('div');
    d.innerHTML = html;
    return d.innerHTML;
}

function check_string(myelt,taboos)
{ var patt = new RegExp( "[" + taboos + "]" );
  if(myelt.value.search(patt) == -1)
    return true;
  else
  { alert("The following characters are not allowed and have been removed: "+taboos);
    myelt.value = myelt.value.replace(patt,"");
    return false;
  }
}


/* take care that only one option is active at the same time */
function feature_change(elt)
{ var myform = elt;
  while (myform.nodeName != "FORM" && myform.parentNode) // find form (either massform or Mainform) 
  { myform = myform.parentNode;
  }
  if(!myform) alert("error finding form");
  if(elt.name.indexOf("_sel")>0)
  { var input = elt.name.replace("_sel","");
	myform[input].value="";
  }
  else
  { if(!check_string(elt,"<>;=#{}"))
      return;
    var patt1=/([0-9]*)$/;
    var sel = elt.name.replace(patt1, "_sel$1");
	if(myform[sel])
	  myform[sel].selectedIndex = 0;
  }
  if(myform.name == "Mainform")
    reg_change(elt);
}

var tabchanged = 0;
function reg_change(elt)	/* register changed row so that it will be colored and only changed rows will be submitted */
{ var elts = Array();
  elts[0] = elt;
  elts[1] = elts[0].parentNode;
  var i=1;
  while (elts[i] && (!elts[i].name || (elts[i].name != 'Mainform')))
  { elts[i+1] = elts[i].parentNode;
	i++;
  }
  elts[i-4].cells[0].setAttribute("changed", "1");
  elts[i-4].style.backgroundColor="#DDD";
  tabchanged = 1;
}

function reg_unchange(num, updateblock)	/* change status of row back to unchanged after it has been submitted */
{ var elt = document.getElementById('trid'+num);
  var row = elt.parentNode;
  row.cells[0].setAttribute("changed", "0");
  row.style.backgroundColor="#AAF";
  var elt = eval('Mainform.discount_count'+num);
  for (var field in updateblock)
  { if(field == "discounts")
    { for (var idx in updateblock[field]) 
	  { var elu = eval('Mainform.discount_id'+idx+'s'+num);
	    elu.value = updateblock[field][idx];
	    var elu = eval('Mainform.discount_status'+idx+'s'+num);
        elu.value = "update";
		var elv = eval('Mainform.discount_shop'+idx+'s'+num);
	    var txt = "";
	    if(elv.value==0)
	      txt = "all";
	    else
	      txt = elv.value;
	    elv.type = "hidden";
	    var elv = eval('Mainform.discount_attribute'+idx+'s'+num);
	    if(elv.value==0)
	      txt += " all";
	    else
	      txt += " "+elv.value;
	    elv.parentNode.insertBefore(document.createTextNode(txt) ,elv);
	    elv.type = "hidden";
	  }
	}
  }
}

parts_stat = 0;
desc_stat = 0;
trioflag = false; /* check that only one of price, priceVAT and VAT is editable at a time */
function switchDisplay(id, elt, fieldno, val)  // collapse(field)
{ var tmp, tmp2, val, checked;
  var advanced_stock = has_combinations = false;
  if(val == '0') /* hide */
  { var tbl= document.getElementById(id).parentNode;
    for (var i = 0; i < tbl.rows.length; i++)
	  if(tbl.rows[i].cells[fieldno])
	    tbl.rows[i].cells[fieldno].style.display='none';
  }
  if((val == '1') || (val=='2')) /* 1 = show */
  { var tbl= document.getElementById(id).parentNode;
    for (var i = 0; i < tbl.rows.length; i++) 
	  if(tbl.rows[i].cells[fieldno])
	    tbl.rows[i].cells[fieldno].style.display='table-cell';
  }
  if((val=='2') ||(val == '3')) /* 2 = edit */
  { tab = document.getElementById('Maintable');
    var tblEl = document.getElementById(id);
    field = tab.tHead.rows[0].cells[fieldno].children[0].innerHTML;
    if((trioflag == true) && ((field == "price") || (field == "VAT") || (field == "priceVAT")))
    { alert("You may edit only one of the following fields at a time: price, VAT, priceVAT");
      return;
    }
    if((field == "price") || (field == "VAT") || (field == "priceVAT"))
      trioflag = true;
	else if (field == "image")
	  var imgsuffix = '';
	  
<?php if(in_array("carrier", $input["fields"]) && !file_exists("TE_plugin_carriers.php"))
	  { echo 'else if(field=="carrier") 
		alert("Carriers is a plugin that needs to be bought seperately at www.Prestools.com.\nWithout the plugin you are in demo-mode: you can make changes but they can not be saved!");';
		$carrier_notpaid = true;
	  }
	  if(in_array("discount", $input["fields"]) && !file_exists("TE_plugin_discounts.php"))
	  { echo 'else if(field=="discount") 
		alert("Special Prices/Discounts is a plugin that needs to be bought seperately at www.Prestools.com.\nWithout the plugin you are in demo-mode: you can make changes but they can not be saved!");';
		$discount_notpaid = true;
	  }
	  if(in_array("features", $input["fields"]) && !file_exists("TE_plugin_features.php"))
	  { echo 'else if(field=="features") 
		alert("Features is a plugin that needs to be bought seperately at www.Prestools.com.\nWithout the plugin you are in demo-mode: you can make changes but they can not be saved!");';
		$features_notpaid = true;
	  }
 	  if(in_array("supplier", $input["fields"]) && !file_exists("TE_plugin_suppliers.php"))
	  { echo 'else if(field=="supplier") 
		alert("Suppliers is a plugin that needs to be bought seperately at www.Prestools.com.\nWithout the plugin you are in demo-mode: you can make changes but they can not be saved!");';
		$supplier_notpaid = true;
	  }
	  if(in_array("tags", $input["fields"]) && !file_exists("TE_plugin_tags.php"))
	  { echo 'else if(field=="tags") 
		alert("Tags is a plugin that needs to be bought seperately at www.Prestools.com.\nWithout the plugin you are in demo-mode: you can make changes but they can not be saved!");';
		$tags_notpaid = true;
	  }
?>
    for(var i=0; i<tblEl.rows.length; i++)
    { if(!tblEl.rows[i].cells[fieldno]) continue;
	  tmp = tblEl.rows[i].cells[fieldno].innerHTML;
      tmp2 = tmp.replace("'","\'");
      row = tblEl.rows[i].cells[0].childNodes[1].name.substring(10); /* fieldname id_product7 => 7 */
      if(field=="priceVAT") 
        tblEl.rows[i].cells[fieldno].innerHTML = '<input name="'+field+row+'" value="'+tmp2+'" onchange="priceVAT_change(this)" /><input type=hidden name="price'+row+'" value="'+tblEl.rows[i].cells[fieldno-2].innerHTML+'">';
      else if(field=="price")
        tblEl.rows[i].cells[fieldno].innerHTML = '<input name="'+field+row+'" value="'+tmp2+'" onchange="price_change(this)" />';
      else if(field=="VAT")
      { tmp = tblEl.rows[i].cells[fieldno].getAttribute("idx");
        tblEl.rows[i].cells[fieldno].innerHTML = '<select name="VAT'+row+'" onchange="VAT_change(this)">'+taxblock.replace('value="'+tmp+'"', 'value="'+tmp+'" selected');
      }
      else if(field=="manufacturer") 
      { tblEl.rows[i].cells[fieldno].innerHTML = '<select name="manufacturer'+row+'" onchange="reg_change(this);">'+manufacturerblock.replace('>'+tmp.replace('&amp;','&')+'<', ' selected>'+tmp+'<');
      }
      else if(field=="stockflags") 
      { tblEl.rows[i].cells[fieldno].innerHTML = '<select name="stockflags'+row+'" onchange="reg_change(this);">'+stockflagsblock.replace('>'+tmp.replace('&amp;','&')+'<', ' selected>'+tmp+'<');
      }	  
	  else if(field=="category") 
      { tblEl.rows[i].cells[fieldno].innerHTML = (categoryblock0.replace(/CQX/g, row))+categoryblock1+(categoryblock2.replace(/CQX/g, row));
	    fillCategories(row,tmp);
	  }
	  else if(field=="attachmnts") 
      { tmp = tblEl.rows[i].cells[fieldno].getElementsByTagName("a");
	    var atids = [];
		for(var j=0; j< tmp.length; j++)
		  atids[j] = tmp[j].title;
	    tblEl.rows[i].cells[fieldno].innerHTML = (attachmentblock0.replace(/CQX/g, row))+attachmentblock1+(attachmentblock2.replace(/CQX/g, row));
	    fillAttachments(row,atids);
	  }
	  else if(field=="carrier") 
      { var cars = new Array();
	    var tab = document.getElementById('carriers'+row);
	    if(tab)
		{ for(var y=0; y<tab.rows.length; y++)
		  {	cars[y] = tab.rows[y].cells[0].id;
		  }
		}
	    tblEl.rows[i].cells[fieldno].innerHTML = (carrierblock0.replace(/CQX/g, row))+carrierblock1+(carrierblock2.replace(/CQX/g, row));
	    fillCarriers(row,cars);
	  }
      else if(field=="supplier") 
      { var trow = document.getElementById("trid"+row).parentNode;
  	    var sups = trow.cells[fieldno].getAttribute("sups");
	    var attrs = trow.cells[fieldno].getAttribute("attrs");
	  
		var blob = '<input type=hidden name="supplier_attribs'+row+'" value="'+attrs+'">';
		blob += '<input type=hidden name="old_suppliers'+row+'" value="'+sups+'">';
	    blob += (supplierblock0.replace(/CQX/g, row))+supplierblock1+(supplierblock2.replace(/CQX/g, row));
	
	    var attributes = attrs.split(",");
		for(var a=0; a< attributes.length; a++)
		{ var tab = document.getElementById("suppliers"+attributes[a]+"s"+row);
		  blob += '<table id="suppliertable'+attributes[a]+'s'+row+'" class="suppliertable" title="'+tab.title+'">';
		  if(tab)
		  { var first = 0;
	        for(var y=0; y<tab.rows.length; y++)
		    { blob += '<tr><td>'+tab.rows[y].cells[0].innerHTML+'</td><td><input name="supplier_reference'+attributes[a]+'t'+tab.rows[y].title+'s'+row+'" value="'+tab.rows[y].cells[1].innerHTML.replace('"','\\"')+'" onchange="reg_change(this);"></td><td><input name="supplier_price'+attributes[a]+'t'+tab.rows[y].title+'s'+row+'" value="'+tab.rows[y].cells[2].innerHTML.replace('"','\\"')+'" onchange="reg_change(this);"></td>';
			  if(first++ == 0) 
				blob += '<td rowspan="'+tab.rows.length+'">'+tab.rows[y].cells[3].innerHTML+'</td>';
			  blob += '</tr>';
			}
		  }
		  blob += '</table>';
		}
		trow.cells[fieldno].innerHTML = blob;
		var list = document.getElementById('supplierlist'+row);
		var suppliers = sups.split(",");
		for (var x=0; x< suppliers.length; x++)
		{ for(var y=0; y< list.length; y++)
		  { if(list.options[y].value == suppliers[x])
			{ list.selectedIndex = y;
			  Addsupplier(row,0);
			}	
		  }
		}
	  }
	  else if(field=="discount") 
      { /* 								0			1		2		3		4			5			6		7				8			9	 10	*/
	    /* discount fields: product_attribute, currency, country, group, id_customer, price, from_quantity, reduction, reduction_type, from, to */
		var tab = document.getElementById('discount'+row);
	    if(tab)
		{ var blob = '<input type=hidden name="discount_count'+row+'" value="'+tab.rows.length+'">';
		  for(var y=0; y<tab.rows.length; y++)
		  { blob += "<div>";
		    blob += fill_discount(row,y,tab.rows[y].getAttribute("specid"),"update",tab.rows[y].cells[0].innerHTML,tab.rows[y].cells[1].innerHTML,tab.rows[y].cells[2].innerHTML,tab.rows[y].cells[3].innerHTML,tab.rows[y].cells[4].innerHTML,tab.rows[y].cells[5].innerHTML,tab.rows[y].cells[6].innerHTML,tab.rows[y].cells[7].innerHTML,tab.rows[y].cells[8].innerHTML,tab.rows[y].cells[9].innerHTML,tab.rows[y].cells[10].innerHTML,tab.rows[y].cells[11].innerHTML);
		    blob += "</div>";
		  }
		  blob += '<a href="#" onclick="return add_discount('+row+');" class="TinyLine" id="discount_adder'+row+'">Add discount rule</a>';
		}
		tblEl.rows[i].cells[fieldno].innerHTML = blob;
	  }
	  else if((field=="accessories") || (field == "name"))
      { tmp2 = tmp.replace(/<[^>]*>/g,'');
	    tblEl.rows[i].cells[fieldno].innerHTML = '<input name="'+field+row+'" value="'+tmp2+'" onchange="reg_change(this);" />';
	  }
      else if((field=="active") || (field=="on_sale") || (field=="online_only") || (field=="available"))
	  { if(tmp==1) checked="checked"; else checked="";
	    tblEl.rows[i].cells[fieldno].innerHTML = '<input type=hidden name="'+field+row+'" id="'+field+row+'" value="0" /><input type=checkbox name="'+field+row+'" id="'+field+row+'" onchange="reg_change(this);" value="1" '+checked+' />';
	  }
      else if((field=="description") || (field=="description_short"))
      { tblEl.rows[i].cells[fieldno].innerHTML = '<textarea name="'+field+row+'" id="'+field+row+'" rows="4" cols="35" onchange="reg_change(this);">'+tmp+'</textarea>';
		tblEl.rows[i].cells[fieldno].innerHTML += '<div class="TinyLine"><a href="#" onclick="useTinyMCE(this, \''+field+row+'\'); return false;">TinyMCE</a>&nbsp;<a href="#" onclick="useTinyMCE2(this, \''+field+row+'\'); return false;">TinyMCE-deluxe</a></div>';
      }
	  else if(field=="meta_description")
        tblEl.rows[i].cells[fieldno].innerHTML = '<textarea name="'+field+row+'" rows="4" cols="35" onchange="reg_change(this);">'+tmp+'</textarea>';
      else if(field=="tags")
      { tmp = tmp.replace(/<\/?nobr>/gi, "");
	    tmp = tmp.replace(/\<br>/gi, ",");
	    tblEl.rows[i].cells[fieldno].innerHTML = '<textarea name="'+field+row+'" rows="4" cols="25" onchange="reg_change(this);">'+tmp+'</textarea>';
	  }
      else if(field=="image")
      { if(tblEl.rows[i].cells[fieldno].innerHTML=='X') continue;
		var tmp = tblEl.rows[i].cells[fieldno].firstChild.title;
		if(imgsuffix == '')
		{ imgsuffix = tblEl.rows[i].cells[fieldno].firstChild.firstChild.src;
		  imgsuffix = imgsuffix.match(/-[^-]*$/);
		}
		var parts = tmp.split(';');
		var images = parts[1].split(',');
		var str = '<table><tr>';
		for (var j = 0; j < images.length; j++)  
		{ str += '<td><a href=\"<?php echo $triplepath; ?>img/p'+getpath(images[j])+'/'+images[j]+'.jpg\" target=\"_blank\" ><img';
		  if(images[j] == parts[0]) /* default image gets extra border */
			str += ' border=3'
		  str += ' src=\"<?php echo $triplepath; ?>img/p'+getpath(images[j])+'/'+images[j]+imgsuffix+'\" width=\"45px\" height=\"45px\" /></a></td>';
		}
		str += '</tr></table>';
		var id_product = eval('Mainform.id_product'+row+'.value');
		str += '<center><a href="image-edit.php?id_product='+id_product+'&id_shop=<?php echo $id_shop; ?>" title="Edit images in separate window" target="_blank" class="TinyLine" >edit</a></center>';
 		tblEl.rows[i].cells[fieldno].innerHTML = str;
	  //	    tblEl.rows[i].cells[fieldno].innerHTML = '<textarea name="'+field+row+'" rows="4" cols="25" onchange="reg_change(this);">'+tmp+'</textarea>';
	  }
	  else if(field=="qty")
	  { if(tblEl.rows[i].cells[fieldno].style.backgroundColor != "")
	    { if(tblEl.rows[i].cells[fieldno].style.backgroundColor == "yellow")
		    advanced_stock = true;
		  else /* combinations */
			has_combinations = true;
		  continue;
		}
	    tblEl.rows[i].cells[fieldno].innerHTML = '<input name="'+field+row+'" value="'+tmp2+'" onchange="reg_change(this);" />';
	  }
	  else if((pos = featurelist.indexOf(field)) != -1)
      { if(tmp.match("<b>")) 
		{ custom = 0; /* "custom" = in dropdown select */
		  tmp = tmp.replace(/<[^>]*>/g,"");
		  tmp3 = "";
		}
		else
		{ custom=1;
		  tmp3 = tmp2;
		}
		fieldname = "feature"+featurekeys[pos]+"field";
		if(val == 2)
		  inserta = '<input name="'+fieldname+row+'" value="'+tmp3+'" onkeyup="feature_change(this);" />';
		else
		  inserta = '<textarea name="'+fieldname+row+'" id="'+fieldname+row+'" rows="4" cols="35" onkeyup="feature_change(this);">'+tmp3+'</textarea>';
		if(featureblocks[pos] == "")
			tblEl.rows[i].cells[fieldno].innerHTML = inserta;
		else if (custom == 0)
		{  tblEl.rows[i].cells[fieldno].innerHTML = '<select name="'+fieldname+'_sel'+row+'" onchange="feature_change(this)">'+featureblocks[pos].replace('>'+tmp+'<', ' selected>'+tmp+'<')+inserta;
		}
		else if (custom == 1)
		{  tblEl.rows[i].cells[fieldno].innerHTML = '<select name="'+fieldname+'_sel'+row+'" onchange="feature_change(this)">'+featureblocks[pos]+inserta;
		}
		else // custom=-1 => field not set
		{  tblEl.rows[i].cells[fieldno].innerHTML = '<select name="'+fieldname+'_sel'+row+'" onchange="feature_change(this)">'+featureblocks[pos]+inserta;
		}
      }
      else
        tblEl.rows[i].cells[fieldno].innerHTML = '<input name="'+field+row+'" value="'+tmp2+'" onchange="reg_change(this);" />';
    }
	if(featurelist.indexOf(field) == -1) /* if not a feature */
		var cell = elt.parentElement; /* td cell */
	else
		var cell = elt.parentElement.parentElement;
	tmp = cell.innerHTML.replace(/<br.*$/,'');
	if((field == "description") || (field == "description_short") || (field == "meta_description"))
//	  elt.parentElement.innerHTML = '<table class="grower"><tr><td></td><td><img src="up.png" onclick="change_fieldsize(fieldno, 1,0);"></td><td></td></tr><tr><td><img src="min.png"></td><td>'+tmp+'<br>Edit'+'</td><td><img src="plus.png"></td></tr><tr><td></td><td><img src="down.png"></td><td></td></tr></table>';
	  cell.innerHTML = tmp+'<br>Edit<br><img src=minus.png title="make field less high" onclick="grow_textarea(\''+field+'\','+fieldno+', -1, 0);"><b>H</b><img src=plus.png title="make field higher" onclick="grow_textarea(\''+field+'\','+fieldno+', 1, 0);"><br><img src=minus.png title="make field less wide" onclick="grow_textarea(\''+field+'\','+fieldno+', 0, -7);"><b>W</b><img src=plus.png title="make field wider" onclick="grow_textarea(\''+field+'\','+fieldno+', 0, 7);">';	
	else if((field == "meta_keywords") || (field == "meta_title") || (field == "name"))
	  cell.innerHTML = tmp+'<br>Edit<br><nobr><img src="minus.png" title="make field less wide" onclick="grow_input(\''+field+'\','+fieldno+', -7);"><b>W</b><img src="plus.png" title="make field wider" onclick="grow_input(\''+field+'\','+fieldno+', 7);"></nobr>';
	else if ((typeof fieldname !== 'undefined') && (fieldname == "feature"+featurekeys[pos]+"field"))
	{ if(val == 2)
		cell.innerHTML = tmp+'<br>Edit<br><img src="minus.png" title="make field less wide" onclick="grow_input(\''+fieldname+'\','+fieldno+', -7);"><b>W</b><img src="plus.png" title="make field wider" onclick="grow_input(\''+fieldname+'\','+fieldno+', 7);">';	
	  else /* val==3 */
		cell.innerHTML = tmp+'<br>Edit<br><img src=minus.png title="make field less high" onclick="grow_textarea(\''+fieldname+'\','+fieldno+', -1, 0);"><b>H</b><img src=plus.png title="make field higher" onclick="grow_textarea(\''+fieldname+'\','+fieldno+', 1, 0);"><br><img src=minus.png title="make field less wide" onclick="grow_textarea(\''+fieldname+'\','+fieldno+', 0, -7);"><b>W</b><img src=plus.png title="make field wider" onclick="grow_textarea(\''+fieldname+'\','+fieldno+', 0, 7);">';	
	}
	else
	  cell.innerHTML = tmp+"<br><br>Edit";
  }
  var warning = "";
  if(advanced_stock)
    warning += "Quantity fields of products with advanced stock keeping - marked in yellow - cannot be changed.";
  if(has_combinations)
    warning += "Quantity fields for products with combinations - marked in red - cannot be changed here.";
  var tmp = document.getElementById("warning");
  tmp.innerHTML = warning;
  return;
}

function grow_input(field, fieldno, width)
{ var tblEl = document.getElementById("offTblBdy");
  var size = -1;
  for(var i=0; i<tblEl.rows.length; i++)
  { if(!tblEl.rows[i].cells[fieldno]) continue; 
	row = tblEl.rows[i].cells[0].childNodes[1].name.substring(10);  /* id_product is 10 chars long */
	myfield = eval("Mainform."+field+row);
    if(size == -1)
	{ size = myfield.size;
	  size += width;
	  if(size < 10) size = 10;
	}
	myfield.size = size;
  }
}

function grow_feature(field, fieldno, width)
{ var tblEl = document.getElementById("offTblBdy");
  var size = -1;
  for(var i=0; i<tblEl.rows.length; i++)
  { if(!tblEl.rows[i].cells[fieldno]) continue; 
	row = tblEl.rows[i].cells[0].childNodes[1].name.substring(10);  /* id_product is 10 chars long */
	myfield = eval("Mainform."+field+row);
    if(size == -1)
	{ size = myfield.size;
	  size += width;
	  if(size < 10) size = 10;
	}
	myfield.size = size;
  }
}

function grow_textarea(field, fieldno, height, width)
{ var tblEl = document.getElementById("offTblBdy");
  var rows = -1, cols;
  for(var i=0; i<tblEl.rows.length; i++)
  { if(!tblEl.rows[i].cells[fieldno]) continue; 
	row = tblEl.rows[i].cells[0].childNodes[1].name.substring(10);  /* id_product is 10 chars long */
	myfield = eval("Mainform."+field+row);
    if(rows == -1)
	{ rows = myfield.rows;
	  cols = myfield.cols;
	  rows += height;
	  cols += width;
	  if(cols < 10) cols = 10;
	  if(rows < 2) rows = 2;	  
	}
	myfield.cols = cols;
	myfield.rows = rows;	
  }
}

function add_discount(row)
{ var count_root = eval('Mainform.discount_count'+row);
  var dcount = parseInt(count_root.value);
  var blob = fill_discount(row,dcount,"","new","","","","0","0","0","","1","","","","");
  var new_div = document.createElement('div');
  new_div.innerHTML = blob;
  var adder = document.getElementById("discount_adder"+row);
  adder.parentNode.insertBefore(new_div,adder);
  count_root.value = dcount+1;
  return false;
}

function edit_discount(row, entry)
{ var changed = 0;
  var status = eval('Mainform.discount_status'+entry+'s'+row+'.value');
  var shop = eval('Mainform.discount_shop'+entry+'s'+row+'.value');
  var currency = eval('Mainform.discount_currency'+entry+'s'+row+'.value');
  var group = eval('Mainform.discount_group'+entry+'s'+row+'.value');
  var country = eval('Mainform.discount_country'+entry+'s'+row+'.value');
  
  var blob = '<form name="dhform"><input type=hidden name=row value="'+row+'"><input type=hidden name=entry value="'+entry+'">';
  	blob += '<input type=hidden name="discount_status" value="'+status+'">';	
  	blob += '<input type=hidden name="discount_id" value="'+eval('Mainform.discount_id'+entry+'s'+row+'.value')+'">';			
	blob += '<table id="discount_table" cellpadding="2"';
	blob += '<tr><td><b>Shop id</b></td>';
	if(status == "update")
	{	blob += '<td><input type=hidden name="discount_shop" value="'+eval('Mainform.discount_shop'+entry+'s'+row+'.value')+'">';
		if(shop == "") blob += 'all</td></tr>';
		else blob+=''+shop+'</td></tr>';
		blob += '<tr><td><b>Attribute</b></td><td><input type=hidden name="discount_attribute" value="'+eval('Mainform.discount_attribute'+entry+'s'+row+'.value')+'">';
	}
	else /* insert */
	{	blob += '<td><select name="discount_shop" onchange="changed = 1;">';
		blob += '<option value="0">All</option>'+(((shop == "") || (shop == 0))? shopblock : shopblock.replace(">"+shop+"-", " selected>"+shop+"-"))+'</select></td></tr>';
		blob += '<tr><td><b>Attribute</b></td><td><input name="discount_attribute" value="'+eval('Mainform.discount_attribute'+entry+'s'+row+'.value')+'" onchange="changed = 1;"></td></tr>';
	}
	
	blob += '<tr><td><b>Currency</b></td>';
	blob += '<td><select name="discount_currency" onchange="changed = 1;">';
	blob += '<option value="0">All</option>'+((currency == "")? currencyblock : currencyblock.replace(">"+currency+"<", " selected>"+currency+"<"))+'</select></td></tr>';

	blob += '<tr><td><b>Country</b></td>';
	blob += '<td><select name="discount_country" onchange="changed = 1;">';
	blob += '<option value="0">All</option>'+((country == "")? countryblock : countryblock.replace(">"+country+"-", " selected>"+country+"-"))+'</select></td></tr>';
	
	blob += '<tr><td><b>Group</b></td>';
	blob += '<td><select name="discount_group" onchange="changed = 1;">';
	blob += '<option value="0">All</option>'+((group == "")? groupblock : groupblock.replace(">"+group+"-", " selected>"+group+"-"))+'</select></td></tr>';

	blob += '<tr><td><b>Customer id</b></td><td><input name="discount_customer" value="'+eval('Mainform.discount_customer'+entry+'s'+row+'.value')+'" onchange="changed = 1;"> &nbsp; 0=all customers</td></tr>';
	
	blob += '<tr><td><b>Price</b></td><td><input name="discount_price" value="'+eval('Mainform.discount_price'+entry+'s'+row+'.value')+'" class="prijs" onchange="changed = 1;"> &nbsp; From price. Leave empty when equal to normal price.</td></tr>';
	blob += '<tr><td><b>Quantity</b></td><td><input name="discount_quantity" value="'+eval('Mainform.discount_quantity'+entry+'s'+row+'.value')+'" onchange="changed = 1;"> &nbsp; Threshold for reduction.</td></tr>';
	blob += '<tr><td><b>Reduction</b></td><td><input name="discount_reduction" value="'+eval('Mainform.discount_reduction'+entry+'s'+row+'.value')+'" onchange="changed = 1;"></td></tr>';

	blob += '<tr><td><b>Red. type</b></td><td><select name="discount_reductiontype" onchange="changed = 1;">';
    if(eval('Mainform.discount_reductiontype'+entry+'s'+row+'.selectedIndex') == 1)
	   blob += '<option>amt</option><option selected>pct</option>';
	else
	   blob += '<option selected>amt</option><option>pct</option>';
	blob += '</select></td></tr>';
	blob += '<tr><td><nobr><b>From date</b></nobr></td><td><input name="discount_from" value="'+eval('Mainform.discount_from'+entry+'s'+row+'.value')+'" class="datum" onchange="changed = 1;"></td></tr>';
	blob += '<tr><td><b>To date</b></td><td><input name="discount_to" value="'+eval('Mainform.discount_to'+entry+'s'+row+'.value')+'" class="datum" onchange="changed = 1;"></td></tr>';
	blob += '<tr><td></td><td align="right"><input type=button value="submit" onclick="submit_dh_discount()"></td></tr></table></form>'; 
    googlewin=dhtmlwindow.open("Edit_discount", "inline", blob, "Edit discount", "width=550px,height=425px,resize=1,scrolling=1,center=1", "recal");
  return false;
}

function submit_dh_discount()
{ /*					row				entry				id					status					shop			attribute			*/
  var currency = dhform.discount_currency.options[dhform.discount_currency.selectedIndex].text;
  var country = dhform.discount_country.options[dhform.discount_country.selectedIndex].text;
  country = country.substring(0,country.indexOf('-'));
  var group = dhform.discount_group.options[dhform.discount_group.selectedIndex].text;
  group = group.substring(0,group.indexOf('-'));
  var reductiontype = dhform.discount_reductiontype.options[dhform.discount_reductiontype.selectedIndex].text;
  
  var blob = fill_discount(dhform.row.value,dhform.entry.value,dhform.discount_id.value,dhform.discount_status.value,dhform.discount_shop.value,dhform.discount_attribute.value,currency,country,group,dhform.discount_customer.value,dhform.discount_price.value,dhform.discount_quantity.value,dhform.discount_reduction.value,reductiontype,dhform.discount_from.value,dhform.discount_to.value);
  var eltname = 'discount_table'+dhform.entry.value+'s'+dhform.row.value;
  var target = document.getElementById(eltname);
  target = target.parentNode;
  target.innerHTML = blob;
  
//function fill_discount(row,entry,id,status, shop,attribute,currency,country,group,customer,price,quantity,reduction,reductiontype,from,to)
  googlewin.close();
}

function del_discount(row, entry)
{ var tab = document.getElementById("discount_table"+entry+"s"+row);
  tab.innerHTML = "";
  var statusfield = eval('Mainform.discount_status'+entry+'s'+row);
  statusfield.value = "deleted";
  reg_change(tab);
  return false;
}

/* the ps_specific_prices table has two unique keys that forbid that two too similar reductions are inserted.
 * This function - called before submit - checks for them. 
 * Without this check you get errors like: 
 *   Duplicate entry '113-0-0-0-0-0-0-0-15-0000-00-00 00:00:00-0000-00-00 00:00:00' for key 'id_product_2'
 * This key contains the following fields: id_product, id_shop,id_shop_group,id_currency,id_country,id_group,id_customer,id_product_attribute,from_quantity,from,to */
function check_discounts(rowno)
{ var field = eval("Mainform.discount_count"+rowno);
  if (!field || (field.value == 0))
    return true;
  var keys2 = new Array();
  for(var i=0; i< field.value; i++)
  { if(eval("Mainform.discount_status"+i+"s"+rowno+".value") == "deleted")
      continue;
    var key = eval("Mainform.id_product"+rowno+".value")+"-"+eval("Mainform.discount_shop"+i+"s"+rowno+".value")+"-0-"+eval("Mainform.discount_currency"+i+"s"+rowno+".value")+"-"+eval("Mainform.discount_country"+i+"s"+rowno+".value")+"-"+eval("Mainform.discount_group"+i+"s"+rowno+".value")+"-"+eval("Mainform.discount_customer"+i+"s"+rowno+".value")+"-"+eval("Mainform.discount_attribute"+i+"s"+rowno+".value")+"-"+eval("Mainform.discount_quantity"+i+"s"+rowno+".value")+"-"+eval("Mainform.discount_from"+i+"s"+rowno+".value")+"-"+eval("Mainform.discount_to"+i+"s"+rowno+".value");
    for(var j = 0; j < keys2.length; j++) {
        if(keys2[j] == key) 
		{ var tbl= document.getElementById("offTblBdy");
		  var productno = tbl.rows[rowno].cells[1].childNodes[0].text;
		  alert("You have two or more price rules for a product that are too similar for product "+productno+" on row "+rowno+"! Please correct this!");
		  return false;
		}
    }
	keys2[j] = key;
  }
  return true;
}

/* 					0			1				2		3		4		5			6		7				8			9	 		 10  	11	*/
/* discount fields: shop, product_attribute, currency, country, group, id_customer, price, from_quantity, reduction, reduction_type, from, to */
function fill_discount(row,entry,id,status, shop,attribute,currency,country,group,customer,price,quantity,reduction,reductiontype,from,to)
{ 	var blob = '<input type=hidden name="discount_id'+entry+'s'+row+'" value="'+id+'">';
	blob += '<input type=hidden name="discount_status'+entry+'s'+row+'" value="'+status+'">';		
	blob += '<table id="discount_table'+entry+'s'+row+'"><tr><td rowspan=3><a href="#" onclick="return edit_discount('+row+','+entry+')"><img src="pen.png"></a></td>';
	
	if(customer == "") customer = 0;
	if(country == "") country = 0;
	if(group == "") group = 0;
	if(attribute == "") attribute = 0;
	if(quantity == "") quantity = 1;
	if(shop == "") shop = 0;
	
	if(status == "update")
	{	blob += '<td><input type=hidden name="discount_shop'+entry+'s'+row+'" value="'+shop+'">';
		if(shop == "") blob += "all";
		else blob+=shop;
		blob += '-<input type=hidden name="discount_attribute'+entry+'s'+row+'" value="'+attribute+'">';
		if(attribute == "") blob += "all";
		else blob+=attribute;
	}
	else /* insert */
	{	blob += '<td><input name="discount_shop'+entry+'s'+row+'" value="'+shop+'" title="shop id" onchange="reg_change(this);"> &nbsp;';
		blob += '<input name="discount_attribute'+entry+'s'+row+'" value="'+attribute+'" title="product_attribute id" onchange="reg_change(this);"> &nbsp;';
	}
	
	blob += '<select name="discount_currency'+entry+'s'+row+'" value="'+currency+'" title="currency" onchange="reg_change(this);">';
	blob += '<option value="0">All</option>'+((currency == "")? currencyblock : currencyblock.replace(">"+currency+"<", " selected>"+currency+"<"))+'</select> &nbsp;';

	blob += '<input name="discount_country'+entry+'s'+row+'" value="'+country+'" title="country id" onchange="reg_change(this);"> &nbsp;';
	blob += '<input name="discount_group'+entry+'s'+row+'" value="'+group+'" title="group id" onchange="reg_change(this);"></td>';
	
	blob += '<td rowspan=3><a href="#" onclick="return del_discount('+row+','+entry+')"><img src="del.png"></a></td></tr><tr>';
	blob += '<td><input name="discount_customer'+entry+'s'+row+'" value="'+customer+'" title="customer id" onchange="reg_change(this);"> &nbsp; ';

	blob += '<input name="discount_price'+entry+'s'+row+'" value="'+price+'" title="From Price" class="prijs" onchange="reg_change(this);"> &nbsp; ';
	blob += '<input name="discount_quantity'+entry+'s'+row+'" value="'+quantity+'" title="From Quantity" onchange="reg_change(this);"> &nbsp;';
	blob += '<input name="discount_reduction'+entry+'s'+row+'" value="'+reduction+'" title="Reduction" onchange="reg_change(this);">';
	blob += '</tr><tr>';
	blob += '<td><select name="discount_reductiontype'+entry+'s'+row+'" title="Reduction Type" onchange="reg_change(this);">';
	if(reductiontype == "pct")
	   blob += '<option>amt</option><option selected>pct</option>';
	else
	   blob += '<option selected>amt</option><option>pct</option>';
	blob += '</select> &nbsp;';
	blob += '<input name="discount_from'+entry+'s'+row+'" value="'+from+'" title="From Date" class="datum" onchange="reg_change(this);"> &nbsp; ';
	blob += '<input name="discount_to'+entry+'s'+row+'" value="'+to+'" title="To Date" class="datum" onchange="reg_change(this);"></td>';	
	blob += "</tr></table><hr/>";
	return blob;
}

/* check_stockflags() makes sure that you cannot set depends_on_stock to true while advanced_stock_keeping is disabled */
function check_stockflags()
{
}

function useTinyMCE(elt, field)
{ while (elt.nodeName != "TD")
  {  elt = elt.parentNode;
  }
  elt.childNodes[0].cols="125";
  elt.childNodes[1].style.display = "none";  /* hide the links */
  tinymce.init({
//	content_css: "http://localhost/css/my_tiny_styles.css",
//    fontsize_formats: "8pt 9pt 10pt 11pt 12pt 26pt 36pt",	
	selector: "#"+field, 
//	width:500
//	setup: function (ed) {
//  	ed.on("change", function () {
//        })
//	}
  });		// Note: onchange_callback was for TinyMCE 3.x and doesn't work in 4.x
}

/* the arguments for this version were derived from source code of the "classic" example on the TinyMCE website */
/* some buttons were removed bu all plugins were maintained */
function useTinyMCE2(elt, field)
{ while (elt.nodeName != "TD")
  {  elt = elt.parentNode;
  }
  elt.childNodes[0].cols="125";
  elt.childNodes[1].style.display = "none";  /* hide the links */
  tinymce.init({
  	selector: "#"+field, 
	plugins: [
		"advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
		"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
		"table contextmenu directionality emoticons template textcolor paste fullpage textcolor colorpicker textpattern"
	],
	toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
	toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview",
	toolbar3: "forecolor backcolor | table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking",
	menubar: false,
	toolbar_items_size: 'small',
	style_formats: [
		{title: 'Bold text', inline: 'b'},
		{title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
		{title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
		{title: 'Example 1', inline: 'span', classes: 'example1'},
		{title: 'Example 2', inline: 'span', classes: 'example2'},
		{title: 'Table styles'},
		{title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
	],
	width: 640,
	autosave_ask_before_unload: false
  });
}  


function Addcarrier(plIndex)
{ var list = document.getElementById('carrierlist'+plIndex); /* available carriers */
  var sel = document.getElementById('carriersel'+plIndex);	/* selected carriers */
  var listindex = list.selectedIndex;
  if(listindex==-1) return; /* none selected */
  var i, max = sel.options.length;
  carrier = list.options[listindex].text;
  car_id = list.options[listindex].value;
  list.options[listindex]=null;		/* remove from available carriers list */
  if(sel.options[0].value == "none")
  { sel.options.length = 0;
    max = 0;
  }
  i=0;
  var base = sel.options;
  while((i<max) && (carrier > base[i].text)) i++;
  if(i==max)
    base[max] = new Option(carrier);
  else
  { newOption = new Option(carrier);
    if (document.createElement && (newOption = document.createElement('option'))) 
    { newOption.appendChild(document.createTextNode(carrier));
	}
    sel.insertBefore(newOption, base[i]);
  }
  base[i].value = car_id;
  var mycars = eval("document.Mainform.mycars"+plIndex);
  mycars.value = mycars.value+','+car_id;
}

function Removecarrier(plIndex)
{ var list = document.getElementById('carrierlist'+plIndex);
  var sel = document.getElementById('carriersel'+plIndex);
  var selindex = sel.selectedIndex;
  if(selindex==-1) return; /* none selected */
  var i, max = list.options.length;
  carrier = sel.options[selindex].text;
  if(carrier == "none") return; /* none selected */
  car_id = sel.options[selindex].value;
  classname = sel.options[selindex].className;
  sel.options[selindex]=null;
  i=0;
  while((i<max) && (carrier > list.options[i].text)) i++;
  if(i==max)
    list.options[max] = new Option(carrier);
  else
  { newOption = new Option(carrier);
    if (document.createElement && (newOption = document.createElement('option'))) 
      newOption.appendChild(document.createTextNode(carrier));
    list.insertBefore(newOption, list.options[i]);
  }
  if(sel.options.length == 0)
    sel.options[0] = new Option("none");
  list.options[i].value = car_id;
  var mycars = eval("document.Mainform.mycars"+plIndex);
  mycars.value = mycars.value.replace(','+car_id, '');
}

function fillCarriers(idx,cars)
{ var list = document.getElementById('carrierlist'+idx);
  var sel = document.getElementById('carriersel'+idx);
  for(var i=0; i< cars.length; i++)
  { for(var j=0; j< list.length; j++)
	{ if(list.options[j].value == cars[i])
	  { list.selectedIndex = j;
		Addcarrier(idx);
	  }
	}
  }
}

function Addsupplier(plIndex, init)
{ var list = document.getElementById('supplierlist'+plIndex);
  var sel = document.getElementById('suppliersel'+plIndex);
  var listindex = list.selectedIndex;
  if(listindex==-1) return; /* none selected */
  var i, max = sel.options.length;
  supplier = list.options[listindex].text;
  sup_id = list.options[listindex].value;
  list.options[listindex]=null;
  i=0;
  var base = sel.options;
  while((i<max) && (supplier > base[i].text)) i++;
  if(i==max)
    base[max] = new Option(supplier);
  else
  { newOption = new Option(supplier);
    if (document.createElement && (newOption = document.createElement('option'))) 
    { newOption.appendChild(document.createTextNode(supplier));
	}
    sel.insertBefore(newOption, base[i]);
  }
  base[i].value = sup_id;
  if(init == 1)
  { var attributes = eval("document.Mainform.supplier_attribs"+plIndex+".value");
    var myattribs = attributes.split(",");
    for(i=0; i < myattribs.length; i++)
    { var tab = document.getElementById("suppliertable"+myattribs[i]+"s"+plIndex);
	  if(tab.rows[0])
		tab.rows[0].deleteCell(3);
      for (j=0; j<= tab.rows.length; j++)
	  { if(!tab.rows[j] || tab.rows[j].cells[0].innerHTML > supplier)
	    { var newRow = tab.insertRow(j);
		  newRow.innerHTML='<td>'+supplier+'</td><td><input name="supplier_reference'+myattribs[i]+'t'+sup_id+'s'+plIndex+'" value="" onchange="reg_change(this);" /></td><td><input name="supplier_price'+myattribs[i]+'t'+sup_id+'s'+plIndex+'" value="0.000000" onchange="reg_change(this);" /></td>';
		  break;
		}
	  }
	  tab.rows[0].innerHTML += '<td rowspan="'+tab.rows.length+'">'+tab.title+'</td>';
	}
  }  
  var mysups = eval("document.Mainform.mysups"+plIndex);
  mysups.value = mysups.value+','+sup_id;
}

function Removesupplier(plIndex)
{ var list = document.getElementById('supplierlist'+plIndex);
  var sel = document.getElementById('suppliersel'+plIndex);
  var selindex = sel.selectedIndex;
  if(selindex==-1) return; /* none selected */
  var i, j, max = list.options.length;
  var supplier = sel.options[selindex].text;
  sup_id = sel.options[selindex].value;
  classname = sel.options[selindex].className;
  sel.options[selindex]=null;
  i=0;
  while((i<max) && (supplier > list.options[i].text)) i++;
  if(i==max)
    list.options[max] = new Option(supplier);
  else
  { newOption = new Option(supplier);
    if (document.createElement && (newOption = document.createElement('option'))) 
      newOption.appendChild(document.createTextNode(supplier));
    list.insertBefore(newOption, list.options[i]);
  }
  list.options[i].value = sup_id;
  var attributes = eval("document.Mainform.supplier_attribs"+plIndex+".value");
  var myattribs = attributes.split(",");
  for(i=0; i < myattribs.length; i++)
  { var tab = document.getElementById("suppliertable"+myattribs[i]+"s"+plIndex);
    tab.rows[0].deleteCell(3);
    for (j=0; j< tab.rows.length; j++)
	{ if(tab.rows[j].cells[0].innerHTML == supplier)
	  { tab.deleteRow(j);
	  }
	}
	if(tab.rows.length > 0)
		tab.rows[0].innerHTML += '<td rowspan="'+tab.rows.length+'">'+tab.title+'</td>';	
  }
  var mysups = eval("document.Mainform.mysups"+plIndex);
  mysups.value = mysups.value.replace(','+sup_id, '');
}

function Addcategory(plIndex)
{ var list = document.getElementById('categorylist'+plIndex);
  var sel = document.getElementById('categorysel'+plIndex);
  var listindex = list.selectedIndex;
  if(listindex==-1) return; /* none selected */
  var i, max = sel.options.length;
  category = list.options[listindex].text;
  cat_id = list.options[listindex].value;
  list.options[listindex]=null;
  i=0;
  var base = sel.options;
  while((i<max) && (category > base[i].text)) i++;
  if(i==max)
    base[max] = new Option(category);
  else
  { newOption = new Option(category);
    if (document.createElement && (newOption = document.createElement('option'))) 
    { newOption.appendChild(document.createTextNode(category));
	}
    sel.insertBefore(newOption, base[i]);
  }
  base[i].value = cat_id;
  var mycats = eval("document.Mainform.mycats"+plIndex);
  mycats.value = mycats.value+','+cat_id;
}

function Removecategory(plIndex)
{ var list = document.getElementById('categorylist'+plIndex);
  var sel = document.getElementById('categorysel'+plIndex);
  var selindex = sel.selectedIndex;
  if(selindex==-1) return; /* none selected */
  var i, max = list.options.length;
  category = sel.options[selindex].text;
  cat_id = sel.options[selindex].value;
  classname = sel.options[selindex].className;
  if(sel.options.length == 1)
  { alert('There must always be at least one selected category!');
    return; /* leave selection not empty */
  }
  sel.options[selindex]=null;
  i=0;
  while((i<max) && (category > list.options[i].text)) i++;
  if(i==max)
    list.options[max] = new Option(category);
  else
  { newOption = new Option(category);
    if (document.createElement && (newOption = document.createElement('option'))) 
      newOption.appendChild(document.createTextNode(category));
    list.insertBefore(newOption, list.options[i]);
  }
  list.options[i].value = cat_id;
  if(classname == 'defcat')
  { sel.options[0].className = 'defcat';
    var default_cat = eval("document.Mainform.category_default"+plIndex);
	default_cat.value = sel.options[0].value;
  }
  var mycats = eval("document.Mainform.mycats"+plIndex);
  mycats.value = mycats.value.replace(','+cat_id, '');
}

function fillCategories(idx,tmp)
{ var cats = tmp.split(','); 
  var list = document.getElementById('categorylist'+idx);
  var sel = document.getElementById('categorysel'+idx);
  var defcatvalue = -1;
  for(var i=0; i< cats.length; i++)
  { if(!cats[i].match("text-decoration"))
	  defcatvalue = cats[i]= striptags(cats[i]);
	else 
	  cats[i]= striptags(cats[i]);
    for(var j=0; j< list.length; j++)
	{ if(list.options[j].value == cats[i])
	  { list.selectedIndex = j;
		Addcategory(idx);
	  }
	}
  }
  for(var k=0; k< sel.length; k++)
  { if(sel.options[k].value == defcatvalue)
    { defcat = k; break; 
	}
  }
  if(defcatvalue >= 0)
  { sel.options[defcat].className = 'defcat';
    var default_cat = eval("document.Mainform.category_default"+idx);
    default_cat.value = '0'; // zero indicates that is was not changed
  }
  else
    alert("No default found for "+idx);
}

function striptags(mystr) /* remove html tags from text */
{ var regex = /(<([^>]+)>)/ig;
  return mystr.replace(regex, "");
}

function MakeCategoryDefault(idx)
{ var sel = document.getElementById('categorysel'+idx);
  for(var j=0; j< sel.length; j++)
	sel.options[j].className = '';
  sel.options[sel.selectedIndex].className = 'defcat';
  var default_cat = eval("document.Mainform.category_default"+idx);
  default_cat.value = sel.options[sel.selectedIndex].value;
}

function change_categories()
{ var tmp = document.getElementById('cat_order');
  var tmp2 = document.getElementById('catid_show');
  if(document.search_form.id_category.selectedIndex ==0)
  {	tmp.style.display = 'none';
	document.search_form.order.selectedIndex = 1;
	tmp2.innerHTML = '';
	document.search_form.subcats.checked = false;
  }
  else
  { if (tmp.style.display == 'none') /* if not visible: show and select order option "positions" */
    { tmp.style.display = 'inline';
      document.search_form.order.selectedIndex = 0;
	}
	tmp3 = document.search_form.id_category;
	tmp2.innerHTML = tmp3.options[tmp3.selectedIndex].value;
  }
}

function change_subcats()
{ if(document.search_form.id_category.selectedIndex ==0) 
	document.search_form.subcats.checked = false;
  var tmp = document.getElementById('cat_order');
  if(document.search_form.subcats.checked)
  { tmp.style.display = 'none';
	document.search_form.order.selectedIndex = 1;
  }
  else if (tmp.style.display == 'none') /* if not visible: show and select order option "positions" */
  { tmp.style.display = 'inline';
    document.search_form.order.selectedIndex = 0;
  }
}

function Addattachment(plIndex)
{ var list = document.getElementById('attachmentlist'+plIndex);
  var sel = document.getElementById('attachmentsel'+plIndex);
  var listindex = list.selectedIndex;
  if(listindex==-1) return; /* none selected */
  var i, max = sel.options.length;
  attachment = list.options[listindex].text;
  attach_id = list.options[listindex].value;
  list.options[listindex]=null;
  if(sel.options[0].value == "none")
  { sel.options.length = 0;
    max = 0;
  }
  i=0;
  var base = sel.options;
  while((i<max) && (attachment > base[i].text)) i++;
  if(i==max)
    base[max] = new Option(attachment);
  else
  { newOption = new Option(attachment);
    if (document.createElement && (newOption = document.createElement('option'))) 
    { newOption.appendChild(document.createTextNode(attachment));
	}
    sel.insertBefore(newOption, base[i]);
  }
  base[i].value = attach_id;
  var myattachments = eval("document.Mainform.myattachments"+plIndex);
  myattachments.value = myattachments.value+','+attach_id;
}

function Removeattachment(plIndex)
{ var list = document.getElementById('attachmentlist'+plIndex);
  var sel = document.getElementById('attachmentsel'+plIndex);
  var selindex = sel.selectedIndex;
  if(selindex==-1) return; /* none selected */
  var i, max = list.options.length;
  attachment = sel.options[selindex].text;
  if(attachment == "none") return; /* none selected */
  attach_id = sel.options[selindex].value;
  classname = sel.options[selindex].className;
  sel.options[selindex]=null;
  i=0;
  while((i<max) && (attachment > list.options[i].text)) i++;
  if(i==max)
    list.options[max] = new Option(attachment);
  else
  { newOption = new Option(attachment);
    if (document.createElement && (newOption = document.createElement('option'))) 
      newOption.appendChild(document.createTextNode(attachment));
    list.insertBefore(newOption, list.options[i]);
  }
  if(sel.options.length == 0)
    sel.options[0] = new Option("none");
  list.options[i].value = attach_id;
  var myattachments = eval("document.Mainform.myattachments"+plIndex);
  myattachments.value = myattachments.value.replace(','+attach_id, '');
}

function fillAttachments(idx,attas)
{ var list = document.getElementById('attachmentlist'+idx);
  var sel = document.getElementById('attachmentsel'+idx);
//  alert("PPP "+attas[0]);
  for(var i=0; i< attas.length; i++)
  { for(var j=0; j< list.length; j++)
	{ if(list.options[j].value == attas[i])
	  { list.selectedIndex = j;
		Addattachment(idx);
	  }
	}
  }
}

function change_order()
{ if(document.search_form.order.selectedIndex < 6) 
	document.search_form.rising.selectedIndex = 0;
  else
  	document.search_form.rising.selectedIndex = 1;
}
</script>
</head>

<body onload=showtotals()>
<?php
print_menubar();
echo '<table class="triplehome" cellpadding=0 cellspacing=0><tr><td width=8% rowspan=2><a href="product-edit.php"><input type="button" value="Home" style="height:52px;"></a></td>';
echo '<td width="62%" >';
echo "The following setting(s) were derived from your configuration:<br/>";
echo "Default language=".$def_langname." (used for productnames)";
if($input['id_lang'] != "")
  echo " - Active language=".$languagename;

echo "<br/>Country=".$countryname." (used for VAT grouping and calculations)</td>";

echo '<td style="text-align:right; width:30%" rowspan=2><iframe name=tank width="230" height="95"></iframe></td></tr>';
echo '</tr><tr><td class="notpaid">90% of this software is free. Only for a few fields that required major development time (carrier, supplier, features, tags, discounts (=specific prices)) do you need to buy a plugin at <a href="http://www.prestools.com/">www.Prestools.com</a>. You can try out those plugins in the free version: all functionality - except for saving - is there.</td></tr></table>';
?>

<table class="triplesearch"><tr><td>
<form name="search_form" method="get" action="product-edit.php" onsubmit="newwin_check()">
<input type="hidden" name="separator">
<table class="tripleminimal"><tr><td>Find <input name="search_txt1" type="text" value="<?php echo $input['search_txt1'] ?>" size="6"  />
in<br><select name="search_fld1" style="width:10em"><option>main fields</option>
<?php
  $selected = "";
  if($input['search_fld1'] == "p.id_product") $selected = "selected";
  echo "<option value='p.id_product' ".$selected.">id_product</option>";

  $statz = array("salescount", "revenue","ordercount","buyercount","visitcount","visitedpages");
  foreach($field_array AS $key => $value)
    if(($value[8] != "") && (!in_array($value[8], $statz)))
	{ $selected = "";
	  if($input['search_fld1'] == $value[8]) $selected = "selected";
	  echo "<option value='".$value[8]."' ".$selected.">".$value[2]."</option>";
	  if($value[2] == "id_category_default")
	  {	$selected = "";
	    if($input['search_fld1'] == "cl.name") $selected = "selected"; 
		echo "<option value='cl.name' ".$selected.">category</option>";
	  }
	}
?>
</select></td><td>And <input name="search_txt2" type="text" value="<?php echo $input['search_txt2'] ?>" size="6"  />
in<br><select name="search_fld2" style="width:10em"><option>main fields</option>
<?php
  $selected = "";
  if($input['search_fld2'] == "p.id_product") $selected = "selected";
  echo "<option value='p.id_product' ".$selected.">id_product</option>";

  $statz = array("salescount", "revenue","ordercount","buyercount","visitcount","visitedpages");
  foreach($field_array AS $key => $value)
    if(($value[8] != "") && (!in_array($value[8], $statz)))
	{ $selected = "";
	  if($input['search_fld2'] == $value[8]) $selected = "selected";
	  echo "<option value='".$value[8]."' ".$selected.">".$value[2]."</option>";
	  if($value[2] == "id_category_default")
	  {	$selected = "";
	    if($input['search_fld2'] == "cl.name") $selected = "selected"; 
		echo "<option value='cl.name' ".$selected.">category</option>";
	  }
	}
	
	$res=dbquery("SELECT COUNT(*) AS rcount from ". _DB_PREFIX_."product");
	$row = mysqli_fetch_array($res);
	$totcount = $row["rcount"];
    echo '</select></td><td>Total '.$totcount.' prods. Sort by <select name="order" onchange="change_order()">';

	  if ($input['order']=="position") {$selected=' selected="selected" ';} else $selected="";
	  if (($input['id_category']=="0") ||(isset($input['subcats']))) {$catdisp=' style="display:none"';} else $catdisp="";
	  echo '<option '.$selected.$catdisp.' id="cat_order">position</option>';
	  if ($input['order']=="id_product") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>id_product</option>';
	  if ($input['order']=="name") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>name</option>';
	  if ($input['order']=="price") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>price</option>';
	  if ($input['order']=="VAT") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>VAT</option>';
	  if ($input['order']=="shipweight") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>shipweight</option>';
	  if ($input['order']=="image") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>image</option>';
	  if ($input['order']=="active") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>active</option>';
	  if ($input['order']=="visits") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>visits</option>';
	  if ($input['order']=="visitz") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>visitz</option>';	  
	  if ($input['order']=="revenue") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>revenue</option>';
	  if ($input['order']=="orders") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>orders</option>';
	  if ($input['order']=="buyers") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>buyers</option>';
	  if ($input['order']=="date_upd") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>date_upd</option>';
	  echo '</select>';

	$checked = "";
	if((isset($input['rising'])) && ($input['rising'] == 'DESC'))
	  $checked = "selected";
    echo ' &nbsp; <SELECT name=rising><option>ASC</option><option '.$checked.'>DESC</option></select>';
	
	echo '&nbsp; category <select name="id_category" onchange="change_categories();" style="width:12em">
	<option value="0">All categories</option>';

	$query=" select DISTINCT id_category,name from ". _DB_PREFIX_."category_lang WHERE id_lang=".$def_lang." ORDER BY name";
	$res=dbquery($query);
	while ($category=mysqli_fetch_array($res)) {
		if ($category['id_category']==$input['id_category']) {$selected=' selected="selected" ';} else $selected="";
	        echo '<option  value="'.$category['id_category'].'" '.$selected.'>'.$category['name'].'</option>';
	}
	echo '</select><span id="catid_show" style="color: #999999">'.$input['id_category'].'</span>';
	
	$checked = "";
	if(isset($_GET["subcats"]) && $_GET["subcats"] == "on") $checked = "checked";
    echo ' &nbsp; With subcats <input type="checkbox" name="subcats" '.$checked.' onchange="change_subcats()">';
 	
	echo '<br/>Startrec: <input size=3 name=startrec value="'.$input['startrec'].'">';
	echo ' &nbsp &nbsp; Number of recs: <input size=3 name=numrecs value="'.$input['numrecs'].'">';
	echo ' &nbsp &nbsp; Language: <select name="id_lang" style="margin-top:5px">';
	  $query=" select * from ". _DB_PREFIX_."lang ";
	  $res=dbquery($query);
	  while ($language=mysqli_fetch_array($res)) {
		$selected='';
	  	if ($language['id_lang']==$id_lang) $selected=' selected="selected" ';
	        echo '<option  value="'.$language['id_lang'].'" '.$selected.'>'.$language['name'].'</option>';
	  }
	echo '</select>';

	echo ' &nbsp; shop <select name="id_shop">'.$shopblock.'</select>';
	
	echo '</td></tr></table>';
	echo '<hr/>';
	echo '<table ><tr>';
	$checked = in_array("name", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="name" '.$checked.' />Name</td>';
	$checked = in_array("VAT", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="VAT" '.$checked.' />VAT</td>';
	$checked = in_array("priceVAT", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="priceVAT" '.$checked.' />priceVAT</td>';
	$checked = in_array("reference", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="reference" '.$checked.' />Reference</td>';
	$checked = in_array("linkrewrite", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="linkrewrite" '.$checked.' />Link-rewrite</td>';
	$checked = in_array("description", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="description" '.$checked.' />Description</td>';
	$checked = in_array("shortdescription", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="shortdescription" '.$checked.' />ShortDesc</td>';
	$checked = in_array("metatitle", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="metatitle" '.$checked.' />MetaTitle</td>';
	$checked = in_array("metakeywords", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="metakeywords" '.$checked.' />MetaKeys</td>';
	$checked = in_array("metadescription", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="metadescription" '.$checked.' />MetaDesc</td>';
	echo '</tr><tr>';
	$checked = in_array("quantity", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="quantity" '.$checked.' />Qty</td>';	
	$checked = in_array("price", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="price" '.$checked.' />Price</td>';
	$checked = in_array("category", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="category" '.$checked.' />Category</td>';
	$checked = in_array("wholesaleprice", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="wholesaleprice" '.$checked.' />Wholesale</td>';
	$checked = in_array("stockflags", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="stockflags" '.$checked.' />StockFlags</td>';	
	$checked = in_array("manufacturer", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="manufacturer" '.$checked.' />Manufacturer</td>';
	echo '<td></td>';
	$checked = in_array("available", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="available" '.$checked.' />Available</td>';
	$checked = in_array("onsale", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="onsale" '.$checked.' />On Sale</td>';
	$checked = in_array("onlineonly", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="onlineonly" '.$checked.' />OnlineOnly</td>';	
	echo '</tr><tr>';
	$checked = in_array("ean", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="ean" '.$checked.' />ean</td>';
	$checked = in_array("image", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="image" '.$checked.' />Image</td>';	
	$checked = in_array("date_upd", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="date_upd" '.$checked.' />Date_upd</td>';
	$checked = in_array("minimalquantity", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="minimalquantity" '.$checked.' />MinQuant</td>';
	$checked = in_array("shipweight", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="shipweight" '.$checked.' />Shipweight</td>';
	$checked = in_array("shipheight", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="shipheight" '.$checked.' />Shipheight</td>';
	$checked = in_array("shipwidth", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="shipwidth" '.$checked.' />Shipwidth</td>';
	$checked = in_array("shipdepth", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="shipdepth" '.$checked.' />Shipdepth</td>';
	$checked = in_array("aShipCost", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="aShipCost" '.$checked.' />aShipCost</td>';
	$checked = in_array("attachmnts", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="attachmnts" '.$checked.' />Attachmnts</td>';	
	echo '</tr><tr>';
	$checked = in_array("tags", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="tags" '.$checked.' />Tags</td>';
	$checked = in_array("active", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="active" '.$checked.' />Active</td>';
	$checked = in_array("carrier", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="carrier" '.$checked.' />Carrier</td>';
	$checked = in_array("warehousing", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="warehousing" '.$checked.' />warehouse</td>';	
	$checked = in_array("accessories", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="accessories" '.$checked.' />Accessories</td>';
	$checked = in_array("combinations", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="combinations" '.$checked.' />Combinations</td>';
	$checked = in_array("discount", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="discount" '.$checked.' />Discounts</td>';
	$checked = in_array("supplier", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="supplier" '.$checked.' />Supplier</td>';
	$checked = in_array("features", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="features" '.$checked.' onchange="swapFeatures(this)" />Features</td>';
	$checked = in_array("stats", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="stats" '.$checked.' onchange="swapStats(this)" />Statistics</td>';
	echo '</tr>';
	
	$disped = in_array("features", $input["fields"]) ? "" : "style='display:none'";
	$colorclass = "";
	if($features_notpaid)
	  $colorclass = "notpaid";
	echo '<tr id=featureblock0 '.$disped.'" class="'.$colorclass.'">';
	$fcount = 0;
	$fblock = 0;
	foreach($features AS $key => $feature)
	{	if($fcount++ == 10){ echo '</tr><tr id=featureblock'.++$fblock.' '.$disped.'>'; }
		$checked = in_array($feature, $input["fields"]) ? "checked" : "";
		echo '<td><input type="checkbox" name="fields[]" value="'.$feature.'" '.$checked.' /> '.$feature.'</td>';
	}	
	
	$disped = in_array("stats", $input["fields"]) ? "" : "style='display:none'";
	echo '</tr><tr id=statsblock '.$disped.'">';
	echo '<td colspan=4>Statistics: Period (yyyy-mm-dd): <input size=5 name=startdate value='.$input['startdate'].'> till <input size=5 name=enddate value='.$input['enddate'].'> &nbsp; &nbsp; &nbsp <img src="ea.gif" title="Statistics here are per shop. For statistics for a product over all shops use product-sort."></td>';
	$checked = in_array("visits", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="visits" '.$checked.' />Visits</td>';
	$checked = in_array("visitz", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="visitz" '.$checked.' />Visitz</td>';
	$checked = in_array("salescnt", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="salescnt" '.$checked.' />Sold</td>';
	$checked = in_array("revenue", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="revenue" '.$checked.' />Revenue</td>';
	$checked = in_array("orders", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="orders" '.$checked.' />Orders</td>';	
	$checked = in_array("buyers", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="buyers" '.$checked.' />Buyers</td>';
	
	echo '</tr></table></td><td><input type=checkbox name=newwin>new<br/>window<p/><input type="submit" value="search" /></td>';
	echo '</tr></table></form>';
	
	echo '<script type="text/javascript">
	  function isNumber(n) {
		return !isNaN(parseFloat(n)) && isFinite(n);
	  }
	  
	  function newwin_check()
	  { if(search_form.newwin.checked)
		{	search_form.target = "_blank";
		}
		else
		{	search_form.target = "";
		}
	  }
	  
	  function swapFeatures(elt)
	  { var myrow, i;
	    if (elt.checked)
		{ ';
	    
if($features_notpaid)
	echo 'alert("Features is a plugin that needs to be bought seperately at www.Prestools.com.\nWithout the plugin you are in demo-mode: you can make changes but they will not be saved!");';
echo '  }
	    for(i=0; i<5; i++)
	    { if(myrow = document.getElementById("featureblock"+i))
		  { if(elt.checked)
			  myrow.style.display = "table-row";
		    else
		    { myrow.style.display = "none";
			  var elts = myrow.getElementsByTagName("input");
			  for(j=0; j<elts.length; j++)
			    elts[j].checked = false;
			}
		  }
		}
	  }
	  
	  function swapStats(elt)
	  { var myrow = document.getElementById("statsblock");
	    if(elt.checked)
		  myrow.style.display = "table-row";
		else
		{ myrow.style.display = "none";
		  var elts = myrow.getElementsByTagName("input");
		  for(j=0; j<elts.length; j++)
		    elts[j].checked = false;
		}
	  }
	  
	  function ListProducts()
	  { var argument = "";
	    argument += "startrec="+document.search_form.startrec.value;
		argument += "&id_category="+document.search_form.id_category.value;
		argument += "&listcats="+document.ListForm.listcats.value;
	    argument += "&numrecs="+document.search_form.numrecs.value;
		argument += "&id_lang="+document.search_form.id_lang.value;
	    argument += "&search_txt1="+document.search_form.search_txt1.value;
		argument += "&search_txt2="+document.search_form.search_txt2.value;
		argument += "&search_fld1="+document.search_form.search_fld1.value;
		argument += "&search_fld2="+document.search_form.search_fld2.value;
		argument += "&order="+document.search_form.order.value;
		argument += "&id_shop="+document.search_form.id_shop.value;
		argument += "&listcols="+document.ListForm.listcols.value;
		argument += "&listseps="+document.ListForm.listseps.value;
		argument += "&listlines="+document.ListForm.listlines.value;
		argument += "&listdefault="+document.ListForm.listdefault.checked;
		chk_arr = document.getElementsByName("fields[]");
		for(k=0;k< chk_arr.length;k++)
		{   if(	chk_arr[k].checked == true)
				argument += "&fields%5B%5D="+chk_arr[k].value;
		}
		window.open("product-list.php?"+argument,"", "resizable,scrollbars,location,menubar,status,toolbar");
	  }
	  
	  function submitCSV()
	  { search_form.action = "product-csv.php";
	    search_form.separator.value = csvform.separator.value;
	    search_form.target = "_blank";
	    search_form.submit();
	    search_form.action = "product-edit.php";
	    search_form.target = "_self";
	  }
	  
	  function prepare_update()
	  { records = new Array();
	  	for(i=0; i < '.$input['numrecs'].'; i++) 
		{ prod_base = eval("document.Mainform.id_product"+i);
		  if(!prod_base) continue;
		  id_product = prod_base.value;
		  records[id_product] = i;
		}
	  }
	  
	  function update_field(product, field, value)
	  { var base = eval("document.Mainform."+field+records[product]);
	    if(base.value != value)
			reg_change(base);
	    base.value = value;
	  }
	  
	 //&id_category=&fields%5B%5D=name&fields%5B%5D=VAT&fields%5B%5D=category&fields%5B%5D=description&fields%5B%5D=price&fields%5B%5D=ean&fields%5B%5D=image&fields%5B%5D=shortdescription
	  
	  function massUpdate()
	  { var i, j, k, x, tmp, base, changed;
	    base = eval("document.massform.field");
		fieldtext = base.options[base.selectedIndex].text;
		fieldname = base.options[base.selectedIndex].value;
		if(fieldname.substr(1,8) == "elect a "){ alert("You must select a fieldname!"); return;}
		base = eval("document.massform.action");
		action = base.options[base.selectedIndex].text;
		if(action.substr(1,8) == "elect an") { alert("You must select an action!"); return;}
		if(action == "copy from default lang")
		{ var potentials = new Array("name","description_short","description","meta_title","meta_keywords","link_rewrite","meta_description");
		  var products = new Array();
		  var fields = new Array();
		  var fields_checked = false;
		  j=0; k=0;
		  for(i=0; i < '.$input['numrecs'].'; i++) 
		  { prod_base = eval("document.Mainform.id_product"+i);
		    if(!prod_base) continue;
			id_product = prod_base.value;
		    if(!fields_checked)
			{ for(x=0; x<potentials.length; x++)
			  { field = eval("document.Mainform."+potentials[x]+i);
			    if(field) fields[j++] = potentials[x];
			  }
			  if(fields.length == 0) return;
			  fields_checked = true;
			}
			products[k++] = id_product;
		  }
		  document.copyForm.products.value = products.join(",");
		  document.copyForm.fields.value = fields.join(",");
		  document.copyForm.submit(); /* copyForm comes back with the prepare_update() function */
		  return;
		}
		if((action != "copy from field") && (action != "replace from field") && (fieldtext != "discount") && (action != "TinyMCE") && (action != "TinyMCE-deluxe"))
		   myval = document.massform.myvalue.value;
		if(((fieldname == "price") || (fieldname == "priceVAT")) && !isNumber(myval)) { alert("Only numeric prices are allowed!\nUse decimal points!"); return;}
		if(((fieldname == "description") || (fieldtext == "shortdescription")) && (action == "set") && (myval.length != 0) &&(myval.substring(0,2)!="<p"))
				myval = "<p>"+myval+"</p>";
		if((fieldname == "VAT") || (fieldtext == "manufacturer"))
			myval = document.massform.myvalue.selectedIndex;
		if(fieldname == "stockflags") 
			myval = document.massform.myvalue.value;	
		if((action == "add") && (fieldtext == "discount"))
		{	shop = massform.shop.options[massform.shop.selectedIndex].value;
			currency = massform.currency.options[massform.currency.selectedIndex].value;
			country = massform.country.options[massform.country.selectedIndex].value;
			group = massform.group.options[massform.group.selectedIndex].value;			
			price = massform.price.value;
			quantity = massform.quantity.value;
			reduction = massform.reduction.value;
			reductiontype = massform.reductiontype.options[massform.reductiontype.selectedIndex].text;
			datefrom = massform.datefrom.value;
			dateto = massform.dateto.value;
		}
		if((action == "remove") && (fieldtext == "discount"))
		{	var subfieldname = massform.fieldname.options[massform.fieldname.selectedIndex].text;
		    if((subfieldname == "shop") || (subfieldname == "currency") ||(subfieldname == "country") ||(subfieldname == "group") ||(subfieldname == "reductiontype"))
				var subfield = massform.subfield.options[massform.subfield.selectedIndex].text;
			else 
				var subfield = massform.subfield.value;
		}
		if((fieldtext == "active") || (fieldtext == "onsale") || (fieldtext == "onlineonly") || (fieldtext == "available"))
		{	myval = document.massform.myvalue.checked;
		}
		if(fieldtext == "category")
		{	myval = document.massform.myvalue.value;
			fieldname = "categorysel"; /* needed because we check the field for existence/edibility at the beginning of the loop */
		}
		if(fieldtext == "supplier")
		{	myval = document.massform.myvalue.value;
			fieldname = "suppliersel"; /* needed because we check the field for existence/edibility at the beginning of the loop */
		}
		if(fieldtext == "attachmnts")
		{	myval = document.massform.myvalue.value;
			fieldname = "attachmentsel"; /* needed because we check the field for existence/edibility at the beginning of the loop */
		}
		if(fieldtext == "carrier")
		{	myval = document.massform.myvalue.value;
			fieldname = "carriersel"; /* needed because we check the field for existence/edibility at the beginning of the loop */
		}
		if((action == "copy from field") || (action == "replace from field"))
		{	copyfield = document.massform.copyfield.options[document.massform.copyfield.selectedIndex].text;
			cellindex = cell_array.indexOf(copyfield);
			if(action == "replace from field")
				oldval = document.massform.oldval.value;
			tmp = eval("ListForm.disp"+cellindex);
			if(!tmp) 
			{ alert("The field which you copy or replace from should not be in editable mode!");
			  return;
			}
		}

		if((pos = featurelist.indexOf(fieldtext)) != -1)
		{	custom = 1;
			if((document.massform.myvalue_sel) && (document.massform.myvalue_sel.selectedIndex!=0))
			{ custom=0;
			  myval = document.massform.myvalue_sel.selectedIndex;
			}
			if(action == "replace")
			{ oldcustom = 1;
			  oldval = document.massform.oldval.value;
			  if((document.massform.oldval_sel) && (document.massform.oldval_sel.selectedIndex!=0))
			  { oldcustom=0;
				oldval = document.massform.oldval_sel.selectedIndex;
			  }
			  if(custom != oldcustom)
			  { alert("The old and the new values should be of the same type: either select or freetext!");
			    return;
			  }
			}
		}
		for(i=0; i < '.$input['numrecs'].'; i++) 
		{ 	changed = false;
			if(fieldname == "discount")
			   fieldname = "discount_count";
			field = eval("document.Mainform."+fieldname+i);
			if(!field) continue;
			if(((fieldname == "description") || (fieldtext == "shortdescription")) && (field.parentNode.childNodes[0].tagName == "DIV"))
				field.value = tinyMCE.get(fieldname+i).getContent();	

			if(action == "insert before")
			{	if((fieldname == "description") || (fieldtext == "shortdescription"))
				{   if(myval.substring(0,3) == "<p>")
					{ myval2 = myval+field.value;
					}
				    else
					{ orig = field.value.replace(/^<p>/, "");
					  myval2 = "<p>"+myval+orig;
					}
				}
				else
					myval2 = myval+field.value;
				changed = true;
			}
			else if(action == "increase%")
			{ tmp = field.value * (100 + parseInt(myval));
			  myval2 = tmp / 100;
			  if(myval2 != 0)
				changed = true;
			}
			else if(action == "insert after")
			{	if((fieldname == "description") || (fieldtext == "shortdescription"))
				{	if( myval.charAt(0) == "<") /* new alinea */
					{	myval2 = field.value+myval;
					}
					else	/* insert in last alinea */
					{	orig = field.value.replace(/<\/p>$/, "");
						myval2 = orig+myval+"</p>";
					}
				}
				else
					myval2 = field.value+myval;
				changed = true;
			}
			else if(action == "TinyMCE")
			{	field = eval("document.Mainform."+fieldname+i);
				useTinyMCE(field, fieldname+i);
			}
			else if(action == "TinyMCE-deluxe")
			{	field = eval("document.Mainform."+fieldname+i);
				useTinyMCE2(field, fieldname+i);
			}
			else if(action == "regenerate")
			{	field = eval("document.Mainform."+fieldname+i);
				oldvalue = field.value;
				myval2 = str2url(field.parentNode.parentNode.childNodes[2].innerHTML);
				if(oldvalue != myval2)
				  changed = true;
			}
			else if(action == "replace")
			{	if((pos = featurelist.indexOf(fieldtext)) != -1)
				{ if(custom == 1)
				  { if(!document.massform.myregexp.checked)
					{ src2 = oldval.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
					  oldvalue = field.value;
					  myval2 = field.value.replace(src2, myval);
					}
				    else
				    { evax = new RegExp(oldval,"g");
					  oldvalue = field.value;
				      myval2 = field.value.replace(evax, myval);
				    }
				  }
				}
				else
				{ src = document.massform.oldval.value;
			      if(!document.massform.myregexp.checked)
				  { src2 = src.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&");
					oldvalue = field.value;
					myval2 = field.value.replace(src2, myval);
				  }
				  else
				  { evax = new RegExp(src,"g");
					oldvalue = field.value;
				    myval2 = field.value.replace(evax, myval);
				  }
				if(oldvalue != myval2)
				    changed = true;		
				}
			}
			else if(action == "set as default")  /* set category as default */
			{ var list = document.getElementById("categorysel"+i);
			  len = list.length;
			  for(x=0; x<len; x++)
			  { if(list[x].value == myval)
				{ list.selectedIndex = x;
				  MakeCategoryDefault(i); 
				  changed = true;
				  break;
				}
			  }
			}
			else if(action == "add")  /* add category or discount to product */
			{ 	if(fieldtext == "category")
				{ var list = document.getElementById("categorylist"+i);
				  len = list.length;
				  for(x=0; x<len; x++)
				  { if(list[x].value == myval)
					{ list.selectedIndex = x;
					  Addcategory(i);
					  changed = true;
					  break;
					}
				  }
				}
				else if(fieldtext == "carrier")
				{ var list = document.getElementById("carrierlist"+i);
				  len = list.length;
				  for(x=0; x<len; x++)
				  { if(list[x].value == myval)
					{ list.selectedIndex = x;
					  Addcarrier(i);
					  changed = true;
					  break;
					}
				  }
				}
				else if(fieldtext == "supplier")
				{ var list = document.getElementById("supplierlist"+i);
				  len = list.length;
				  for(x=0; x<len; x++)
				  { if(list[x].value == myval)
					{ list.selectedIndex = x;
					  Addsupplier(i, 1);
					  changed = true;
					  break;
					}
				  }
				}
				else if(fieldtext == "attachmnts")
				{ var list = document.getElementById("attachmentlist"+i);
				  len = list.length;
				  for(x=0; x<len; x++)
				  { if(list[x].value == myval)
					{ list.selectedIndex = x;
					  Addattachment(i, 1);
					  changed = true;
					  break;
					}
				  }
				}
				else if (fieldtext == "tags")
				{ myval = myval.substring(0,32);
				  if(field.value != "")
				  { myval2 = field.value.split(",");
					var tagfound = false;
				    for (index = 0; index < myval2.length; index++) 
				    { if(myval2[index] == myval)
				        tagfound = true;
				    }
				    if(tagfound) continue;
					myval2[index] = myval;
				    field.value = myval2.join(",");
				  }
				  else
				    field.value = myval;
				  changed = true;
				}
			    else  /* discount */
				{  	var count_root = eval("Mainform.discount_count"+i);
					var dcount = parseInt(count_root.value);
					var blob = fill_discount(i,dcount,"","new", shop,"",currency,country,group,"",price,quantity,reduction,reductiontype,datefrom,dateto);
					var new_div = document.createElement("div");
					new_div.innerHTML = blob;
					var adder = document.getElementById("discount_adder"+i);
					adder.parentNode.insertBefore(new_div,adder);
					count_root.value = dcount+1;
					changed = true;
				}
			}
			else if(action == "remove") 
			{ if(fieldtext == "category")			/* remove category from product */
			  {	var list = document.getElementById("categorysel"+i);
				len = list.length;
				for(x=0; x<len; x++)
				{ if(list[x].value == myval)
				  { list.selectedIndex = x;
					Removecategory(i);
				    changed = true;
				    break;
				  }
				}
			  }
			  else if(fieldtext == "carrier")			/* remove carrier from product */
			  {	var list = document.getElementById("carriersel"+i);
				len = list.length;
				for(x=0; x<len; x++)
				{ if(list[x].value == myval)
				  { list.selectedIndex = x;
					Removecarrier(i);
				    changed = true;
				    break;
				  }
				}
			  }
			  else if(fieldtext == "supplier")			/* remove supplier from product */
			  {	var list = document.getElementById("suppliersel"+i);
				len = list.length;
				for(x=0; x<len; x++)
				{ if(list[x].value == myval)
				  { list.selectedIndex = x;
					Removesupplier(i);
				    changed = true;
				    break;
				  }
				}
			  }
			  else if(fieldtext == "attachmnts") /* attachment remove */
			  { var list = document.getElementById("attachmentsel"+i);
				len = list.length;
				for(x=0; x<len; x++)
				{ if(list[x].value == myval)
				  { list.selectedIndex = x;
					Removeattachment(i);
					changed = true;
					break;
				  }
				}
			  }
			  else if (fieldtext == "tags")	/* tags remove */
			  { myval2 = field.value.split(",");
				var tagfound = false;
				for (index = 0; index < myval2.length; index++) 
				{ if(myval2[index] == myval)
				  { myval2.splice(index,1);
				    tagfound=true;
					break;
				  }
				}
				if(!tagfound) continue;
				field.value = myval2.join(",");
				changed = true;
			  }
			  else /* discount remove */
			  { var count_root = eval("Mainform.discount_count"+i);
				var dcount = parseInt(count_root.value);
				for(x=0; x<dcount; x++)
				{ if((subfieldname == "shop") || (subfieldname == "currency") ||(subfieldname == "country") ||(subfieldname == "group") ||(subfieldname == "reductiontype"))
				  {	var subroot = eval("Mainform.discount_"+subfieldname+x+"s"+i);
				    var subvalue = subroot.options[subroot.selectedIndex].text;
				  }
				  else
				    var subvalue = eval("Mainform.discount_"+subfieldname+x+"s"+i+".value");
				  if(subvalue == subfield)
				  { del_discount(i,x);
				  }
				}
			  }
			}
			else if(action == "replace from field") 
			{ oldvalue = field.value;
			  myval2 = field.parentNode.parentNode.cells[cellindex].innerHTML;
			  if(fieldname == "meta_description")
			    myval2 = myval2.replace(/<(?:.|\n)*?>/gm, "");
			  evax = new RegExp(oldval,"g");
			  myval2 = field.value.replace(evax, myval2);
			  if(oldvalue != myval2) changed = true;
			}
			else if(action == "copy from field") 
			{ oldvalue = field.value;
			  myval2 = field.parentNode.parentNode.cells[cellindex].innerHTML;
			  if(fieldname == "meta_description")
			    myval2 = myval2.replace(/<(?:.|\n)*?>/gm, "");
			  else if((pos = featurelist.indexOf(fieldtext)) != -1)
			    myval2 = myval2.replace(/<\/?b>/gm, "");			  
			  if(oldvalue != myval2) changed = true;
			}
			else if (fieldtext == "tags")
			  myval2 = myval.substring(0,32);
			else myval2 = myval;
			if((fieldname == "VAT") || (fieldtext == "manufacturer"))
			{	oldvalue = field.selectedIndex;
				field.selectedIndex = myval2;
				if(oldvalue != myval2) changed = true;
			}
			else if((fieldtext == "active") || (fieldtext == "onsale") || (fieldtext == "onlineonly") || (fieldtext == "available"))
			{   field = field[1];
				oldvalue = field.checked;
			    field.checked = myval;
				if(oldvalue != myval) changed = true;
			}
			else if((pos = featurelist.indexOf(fieldtext)) != -1)
			{	field2 = eval("document.Mainform."+fieldname+"_sel"+i);
				oldvalue = field.value;
				if(field2)
					oldvalue2 = field2.selectedIndex;
				if(custom == 0)
				{	if((action != "replace") || (field2.selectedIndex == oldval))
					{ field2.selectedIndex = myval;
					  field.value = "";
					}
				}
				else
				{	if(field2) // if there is select box
				      field2.selectedIndex = 0;
					field.value = myval2;
				}
				if((oldvalue != field.value) || (field2 && (oldvalue2 != field2.selectedIndex)))
					changed = true;
			}
			else if((action != "add") && (action != "remove") && (action != "set as default") && (action != "TinyMCE") && (action != "TinyMCE-deluxe"))
			{	oldvalue = field.value;
				field.value = myval2;
				if(oldvalue != myval2) changed = true;
			}
			if(fieldname == "price")
				price_change(field);
			if(fieldname == "VAT")
				VAT_change(field);
			if(fieldname == "priceVAT")
				priceVAT_change(field);
				
			if(((fieldname == "description") || (fieldtext == "shortdescription")) && (field.parentNode.childNodes[0].tagName == "DIV"))
				base.value = tinyMCE.get(fieldname+i).setContent(field.value);	

			if(changed) /* we flag only those really changed */
				reg_change(field);
		}
	  }
	  
	  var myarray = [];'; /* define which actions to show for which fields */
	  /* indices: 0=Set; 1=insert before 2=insert after 3=replace 4=increase% 5=regenerate 6=add 7=remove 8=copy from default lang 
	  9=copy from field 10=set as default 11=TinyMCE 12=TinyMCE-deluxe 13=replace from field */
  
    if($id_lang == $def_lang)	/* prepare for "copy from default lang" */
	{ echo '
	  myarray["name"] = 			[1,1,1,1,0,0,0,0,0,0,0,0,0,0];
	  myarray["shortdescription"] = [1,1,1,1,0,0,0,0,0,1,0,1,1,1];
	  myarray["description"] = 		[1,1,1,1,0,0,0,0,0,1,0,1,1,1];	  
	  myarray["metatitle"] = 		[1,1,1,1,0,0,0,0,0,0,0,0,0,0];
	  myarray["metakeys"] = 		[1,1,1,1,0,0,0,0,0,0,0,0,0,0];
	  myarray["linkrewrite"] = 		[1,1,1,1,0,1,0,0,0,0,0,0,0,0];
	  myarray["metadescription"] = 	[1,1,1,1,0,0,0,0,0,1,0,0,0,1];';
	}
	else
	{ echo '
	  myarray["name"] = 			[1,1,1,1,0,0,0,0,1,0,0,0,0,0];
	  myarray["shortdescription"] = [1,1,1,1,0,0,0,0,1,1,0,1,1,0];
	  myarray["description"] = 		[1,1,1,1,0,0,0,0,1,1,0,1,1,0];	  
	  myarray["metatitle"] = 		[1,1,1,1,0,0,0,0,1,0,0,0,0,0];
	  myarray["metakeys"] = 		[1,1,1,1,0,0,0,0,1,0,0,0,0,0];
	  myarray["linkrewrite"] = 		[1,1,1,1,0,1,0,0,1,0,0,0,0,0];
	  myarray["metadescription"] = 	[1,1,1,1,0,0,0,0,1,1,0,0,0,0];';
	}
	echo '
	  myarray["ean"] = 			[1,1,1,1,0,0,0,0,0,0,0,0,0,0];
	  myarray["price"] = 		[1,0,0,1,1,0,0,0,0,0,0,0,0,0];
	  myarray["VAT"] = 			[1,0,0,0,0,0,0,0,0,0,0,0,0,0];
	  myarray["priceVAT"] = 	[1,0,0,1,1,0,0,0,0,0,0,0,0,0];
	  myarray["reference"] = 	[1,1,1,1,0,0,0,0,0,0,0,0,0,0];
	  myarray["manufacturer"] = [1,0,0,0,0,0,0,0,0,0,0,0,0,0];
	  myarray["stockflags"] = 	[1,0,0,0,0,0,0,0,0,0,0,0,0,0];	  
	  myarray["linkrewrite"] = 	[1,1,1,1,0,1,0,0,0,0,0,0,0,0];
	  myarray["shipweight"] = 	[1,0,0,1,0,0,0,0,0,0,0,0,0,0];
	  myarray["accessories"] = 	[1,1,1,1,0,0,0,0,0,0,0,0,0,0];
	  myarray["active"] = 		[1,0,0,0,0,0,0,0,0,0,0,0,0,0];
	  myarray["onsale"] = 		[1,0,0,0,0,0,0,0,0,0,0,0,0,0];
	  myarray["onlineonly"] = 	[1,0,0,0,0,0,0,0,0,0,0,0,0,0];
	  myarray["available"] = 	[1,0,0,0,0,0,0,0,0,0,0,0,0,0];	  
	  myarray["discount"] = 	[0,0,0,0,0,0,1,1,0,0,0,0,0,0];
	  myarray["tags"] = 		[1,0,0,0,0,0,1,1,0,0,0,0,0,0];
	  myarray["carrier"] = 		[0,0,0,0,0,0,1,1,0,0,0,0,0,0];	  
	  myarray["default"] = 		[1,0,0,0,0,0,0,0,0,0,0,0,0,0];
	  myarray["supplier"] = 	[0,0,0,0,0,0,1,1,0,0,0,0,0,0];	
	  myarray["attachmnts"] = 	[0,0,0,0,0,0,1,1,0,0,0,0,0,0];		  
	  myarray["image"] = 		[0,0,0,0,0,0,0,0,0,0,0,0,0,0];	  
	  myarray["Select a field"] = 	[0,0,0,0,0,0,0,0,0,0,0,0,0,0];	  
	  myarray["category"] = 	[0,0,0,0,0,0,1,1,0,0,1,0,0,0];';
   	foreach($featurelist AS $key => $feature)
	  	echo 'myarray["'.$feature.'"] = [1,0,0,1,0,0,0,0,0,1,0,0,0,0];';
		
	echo '
	  function changeMfield()  /* change input fields for mass update when field is selected */
	  { base = eval("document.massform.field");
		fieldtext = base.options[base.selectedIndex].text;
		if(myarray[fieldtext])
		  myarr = myarray[fieldtext];
		else
		  myarr = myarray["default"];
		var muspan = document.getElementById("muval");
		for(i=0; i<myarray["name"].length; i++) /* use here .length to prepare for extra elements */
		{	if(myarr[i] == 0)
			{	document.massform.action.options[i+1].style.display = "none";
				document.massform.action.options[i+1].disabled = true;
			}
			else
			{	document.massform.action.options[i+1].style.display = "block";
				document.massform.action.options[i+1].disabled = false;
			}
		}
		document.massform.action.selectedIndex = 0;

		if(fieldtext == "VAT")  muspan.innerHTML = "<select name=\"myvalue\">"+taxblock; 
		else if(fieldtext == "category") muspan.innerHTML = "<select name=\"myvalue\">"+categoryblock1;	
		else if(fieldtext == "carrier") muspan.innerHTML = "<select name=\"myvalue\">"+carrierblock1;		
		else if(fieldtext == "supplier") muspan.innerHTML = "<select name=\"myvalue\">"+supplierblock1;	
		else if(fieldtext == "attachmnts") muspan.innerHTML = "<select name=\"myvalue\">"+attachmentblock1;	
		else if(fieldtext == "manufacturer") muspan.innerHTML = "<select name=\"myvalue\">"+manufacturerblock;
		else if(fieldtext == "stockflags") muspan.innerHTML = "<select name=\"myvalue\">"+stockflagsblock;		
		else muspan.innerHTML = "value: <textarea name=\"myvalue\" class=\"masstarea\"></textarea>";
	  }
	  
/* this function comes from admin.js in PS 1.4.9 */
function str2url(str)
{
	str = str.toUpperCase();
	str = str.toLowerCase();

	/* Lowercase */
	str = str.replace(/[\u00E0\u00E1\u00E2\u00E3\u00E4\u00E5\u0101\u0103\u0105]/g, "a");
	str = str.replace(/[\u00E7\u0107\u0109\u010D]/g, "c");
	str = str.replace(/[\u010F\u0111]/g, "d");
	str = str.replace(/[\u00E8\u00E9\u00EA\u00EB\u0113\u0115\u0117\u0119\u011B]/g, "e");
	str = str.replace(/[\u011F\u0121\u0123]/g, "g");
	str = str.replace(/[\u0125\u0127]/g, "h");
	str = str.replace(/[\u00EC\u00ED\u00EE\u00EF\u0129\u012B\u012D\u012F\u0131]/g, "i");
	str = str.replace(/[\u0135]/g, "j");
	str = str.replace(/[\u0137\u0138]/g, "k");
	str = str.replace(/[\u013A\u013C\u013E\u0140\u0142]/g, "l");
	str = str.replace(/[\u00F1\u0144\u0146\u0148\u0149\u014B]/g, "n");
	str = str.replace(/[\u00F2\u00F3\u00F4\u00F5\u00F6\u00F8\u014D\u014F\u0151]/g, "o");
	str = str.replace(/[\u0155\u0157\u0159]/g, "r");
	str = str.replace(/[\u015B\u015D\u015F\u0161]/g, "s");
	str = str.replace(/[\u00DF]/g, "ss");
	str = str.replace(/[\u0163\u0165\u0167]/g, "t");
	str = str.replace(/[\u00F9\u00FA\u00FB\u00FC\u0169\u016B\u016D\u016F\u0171\u0173]/g, "u");
	str = str.replace(/[\u0175]/g, "w");
	str = str.replace(/[\u00FF\u0177\u00FD]/g, "y");
	str = str.replace(/[\u017A\u017C\u017E]/g, "z");
	str = str.replace(/[\u00E6]/g, "ae");
	str = str.replace(/[\u0153]/g, "oe");

	/* Uppercase */
	str = str.replace(/[\u0100\u0102\u0104\u00C0\u00C1\u00C2\u00C3\u00C4\u00C5]/g, "A");
	str = str.replace(/[\u00C7\u0106\u0108\u010A\u010C]/g, "C");
	str = str.replace(/[\u010E\u0110]/g, "D");
	str = str.replace(/[\u00C8\u00C9\u00CA\u00CB\u0112\u0114\u0116\u0118\u011A]/g, "E");
	str = str.replace(/[\u011C\u011E\u0120\u0122]/g, "G");
	str = str.replace(/[\u0124\u0126]/g, "H");
	str = str.replace(/[\u0128\u012A\u012C\u012E\u0130]/g, "I");
	str = str.replace(/[\u0134]/g, "J");
	str = str.replace(/[\u0136]/g, "K");
	str = str.replace(/[\u0139\u013B\u013D\u0139\u0141]/g, "L");
	str = str.replace(/[\u00D1\u0143\u0145\u0147\u014A]/g, "N");
	str = str.replace(/[\u00D3\u014C\u014E\u0150]/g, "O");
	str = str.replace(/[\u0154\u0156\u0158]/g, "R");
	str = str.replace(/[\u015A\u015C\u015E\u0160]/g, "S");
	str = str.replace(/[\u0162\u0164\u0166]/g, "T");
	str = str.replace(/[\u00D9\u00DA\u00DB\u00DC\u0168\u016A\u016C\u016E\u0170\u0172]/g, "U");
	str = str.replace(/[\u0174]/g, "W");
	str = str.replace(/[\u0176]/g, "Y");
	str = str.replace(/[\u0179\u017B\u017D]/g, "Z");
	str = str.replace(/[\u00C6]/g, "AE");
	str = str.replace(/[\u0152]/g, "OE");
	str = str.toLowerCase();

	str = str.replace(/\&amp\;/," '.$and_code.' "); /* added */
	str = str.replace(/[^a-z0-9\s\/\'\:\[\]-]/g,"");
	str = str.replace(/[\u0028\u0029\u0021\u003F\u002E\u0026\u005E\u007E\u002B\u002A\u003A\u003B\u003C\u003D\u003E]/g, "");
	str = str.replace(/[\s\'\:\/\[\]-]+/g, " ");

	// Add special char not used for url rewrite
	str = str.replace(/[ ]/g, "-");
//	str = str.replace(/[\/\'\"|,;]*/g, "");

	str = str.replace(/-$/,""); /* added */

	return str;
}
	  
	function changeMAfield()
	{ var base = eval("document.massform.action");
	  var action = base.options[base.selectedIndex].text;
	  base = eval("document.massform.field");
	  var fieldname = base.options[base.selectedIndex].text;
	  var muspan = document.getElementById("muval");
	  if(((fieldname=="active") || (fieldname=="onsale") || (fieldname=="onlineonly") || (fieldname=="available")) &&(action=="set"))
		muspan.innerHTML = "value: <input type=\"checkbox\" name=\"myvalue\">";
	  else if ((action == "copy from field") || (action == "replace from field"))
	  { tmp = document.massform.field.innerHTML;
	    tmp = tmp.replace("Select a field","Select field to copy from");
		tmp = tmp.replace("<option value=\""+fieldname+"\">"+fieldname+"</option>","");
		tmp = tmp.replace("<option value=\"active\">active</option>","");
		tmp = tmp.replace("<option value=\"category\">category</option>","");
		tmp = tmp.replace("<option value=\"image\">image</option>","");
		tmp = tmp.replace("<option value=\"accessories\">accessories</option>","");
		tmp = tmp.replace("<option value=\"combinations\">combinations</option>","");
		tmp = tmp.replace("<option value=\"discount\">discount</option>","");
		tmp = tmp.replace("<option value=\"carrier\">carrier</option>","");
		if (action == "copy from field")
	       muspan.innerHTML = "<select name=copyfield>"+tmp+"</select>";
		else /* replace from field */
			muspan.innerHTML = "text to replace <textarea name=\"oldval\" class=\"masstarea\"></textarea> <select name=copyfield>"+tmp+"</select>";
	  }
	  ';
		$i=0;
		foreach($featurelist AS $key => $feature)
		{ 	if($featureblocks[$i] == "")
				echo 'else if(fieldname == "'.$feature.'")
				{ if (action == "set")
				    muspan.innerHTML = "value: <textarea name=\"myvalue\" class=\"masstarea\" onkeyup=\"feature_change(this)\"></textarea>"; 
				  else /* replace */
				    muspan.innerHTML = "old: <textarea name=\"oldval\" class=\"masstarea\"></textarea> new: <textarea name=\"myvalue\" class=\"masstarea\"></textarea> regexp <input type=checkbox name=myregexp>";
				}';
			else
				echo 'else if(fieldname == "'.$feature.'") 
				{ if (action == "set")
				    muspan.innerHTML = "<select name=\"myvalue_sel\" onchange=\"feature_change(this)\">"+featureblocks['.$i.']+"<textarea name=\"myvalue\" class=\masstarea\" onkeyup=\"feature_change(this)\"></textarea>"; 
				  else /* replace */
				    muspan.innerHTML = "old: <select name=\"oldval_sel\" onchange=\"feature_change(this)\">"+featureblocks['.$i.']+"<textarea name=\"oldval\" class=\"masstarea\" onkeyup=\"feature_change(this)\"></textarea>"
					+"new: <select name=\"myvalue_sel\" onchange=\"feature_change(this)\">"+featureblocks['.$i.']+"<textarea name=\"myvalue\" class=\"masstarea\" onkeyup=\"feature_change(this)\"></textarea> regexp <input type=checkbox name=myregexp>";
				} ';
			$i++;
		}
		echo '
	  else if (action == "replace") muspan.innerHTML = "old: <textarea name=\"oldval\" class=\"masstarea\"></textarea> new: <textarea name=\"myvalue\" class=\"masstarea\"></textarea> regexp <input type=checkbox name=myregexp>";
	  else if (action == "increase%") muspan.innerHTML = "Percentage (can be negative): <input name=\"myvalue\">";
	  else if (action == "copy from default lang") muspan.innerHTML = "This affects name, description and meta fields";
	  else if((action=="TinyMCE") || (action=="TinyMCE-deluxe"))
	    muspan.innerHTML = "";
	  else if ((fieldname=="discount") &&(action=="add"))
/* 						0				1		2		3		  4			5			6		7				8			9	 		10	11*/
/* discount fields: shop, product_attribute, currency, country, group, id_customer, price, from_quantity, reduction, reduction_type, from, to */
	  { tmp = "<br/>";
	    tmp += "<select name=shop style=\"width:100px\"><option value=0>All shops</option>"+shopblock.replace(" selected","")+"</select>";
		tmp += " &nbsp; ";
	    tmp += "<select name=currency><option value=0>All cs</option>"+currencyblock+"</select>";
	    tmp += " &nbsp; <select name=country style=\"width:100px\"><option value=0>All countries</option>"+countryblock+"</select>";
		tmp += " &nbsp; <select name=group style=\"width:90px\"><option value=0>All groups</option>"+groupblock+"</select>";
		tmp += " &nbsp; Cust.id<input name=customer style=\"width:30px\">";
		tmp += " &nbsp; FromPrice<input name=price style=\"width:50px\">";
		tmp += " &nbsp; Min.Qu.<input name=quantity style=\"width:20px\" value=\"1\">";
		tmp += " &nbsp; discount<input name=reduction style=\"width:50px\">";
		tmp += " &nbsp; <select name=reductiontype><option>amt</option><option>pct</option></select>";
		tmp += " &nbsp;period:<input name=datefrom style=\"width:70px\">";
		tmp += "-<input name=dateto style=\"width:70px\"> (yyyy-mm-dd)";
		tmp += "<br/>";
	    muspan.innerHTML = tmp;
	  }
	  else if ((fieldname=="discount") &&(action=="remove"))
	  { tmp = " &nbsp; where &nbsp; ";
	    tmp += "<select name=fieldname style=\"width:150px\" onchange=\"dc_field_optioner()\"><option>Select subfield</option><option>shop</option><option>currency</option><option>country</option><option>group</option>";
	    tmp += "<option>price</option><option>quantity</option><option>reduction</option><option>reductiontype</option><option>date_from</option><option>date_to</option></select>";
		tmp += "<span id=\"dc_options\">";
	    muspan.innerHTML = tmp;
	  }
	  else if (document.massform.action.options[3].style.display == "block")
		muspan.innerHTML = "value: <textarea name=\"myvalue\" class=\"masstarea\"></textarea>";
	}
	
	/* for massedit discount remove: gives subfield options */
	function dc_field_optioner()
	{ var base = eval("document.massform.fieldname");
	  var fieldname = base.options[base.selectedIndex].text;
	  var tmp = "";
	  if (fieldname == "shop") 
	    tmp = "<select name=subfield style=\"width:100px\"><option>All shops</option>"+shopblock+"</select>";
	  else if (fieldname == "currency") 
	    tmp = "<select name=subfield style=\"width:100px\"><option>All currencies</option>"+currencyblock+"</select>";	
	  else if (fieldname == "country") 
	    tmp = "<select name=subfield style=\"width:100px\"><option>All countries</option>"+countryblock+"</select>";
	  else if (fieldname == "group") 
	    tmp = "<select name=subfield style=\"width:100px\"><option>All groups</option>"+groupblock+"</select>";	
	  else if (fieldname == "reductiontype") 
	    tmp = "<select name=subfield style=\"width:100px\"><option>amt</option><option>pct</option></select>";		
	  else 
	    tmp = "<input name=subfield size=40>";
	  var fld = document.getElementById("dc_options");
	  fld.innerHTML = " = "+tmp;
	}
	  
	function salesdetails(product)
	{ window.open("product-sales.php?product="+product+"&startdate='.$input["startdate"].'&enddate='.$input["enddate"].'&id_shop='.$id_shop.'","", "resizable,scrollbars,location,menubar,status,toolbar");
      return false;
    }
	</script>';
	echo '<hr/><div style="background-color:#CCCCCC; position: relative;">Mass update<form name="massform" onsubmit="massUpdate(); return false;">
	<select name="field" onchange="changeMfield()"><option>Select a field</option>';
	foreach($field_array AS $key => $field)
	{	if((in_array($key, $input['fields'])) && ($key != 'VAT'))
		{	echo '<option value="'.$field[0].'">'.$key.'</option>';
			if($key == "price")
			{	echo '<option value="VAT">VAT</option>';
				echo '<option value="priceVAT">priceVAT</option>';
			}
		}
		if(($key == "VAT") && (!in_array("price", $input['fields'])) && (in_array($key, $input['fields'])))
			echo '<option value="VAT">VAT</option>';
	}
	foreach($features AS $key => $feature)
    { if (in_array($feature, $input["fields"]))
	  {	echo '<option value="feature'.$key.'field">'.$feature.'</option>';
	  }
	}
	echo '</select>';
	echo '<select name="action" onchange="changeMAfield()" style="width:120px"><option>Select an action</option>';
	echo '<option>set</option>';
	echo '<option>insert before</option>';
	echo '<option>insert after</option>';
	echo '<option>replace</option>';
	echo '<option>increase%</option>';
	echo '<option>regenerate</option>';
	echo '<option>add</option>';
	echo '<option>remove</option>';
	echo '<option>copy from default lang</option>';
	echo '<option>copy from field</option>';
	echo '<option>set as default</option>';	
	echo '<option>TinyMCE</option>';
	echo '<option>TinyMCE-deluxe</option>';
	echo '<option>replace from field</option>';	
	echo '</select>';
	echo '&nbsp; <span id="muval">value: <textarea name="myvalue" class="masstarea"></textarea></span>';
	echo ' &nbsp; &nbsp; <input type="submit" value="update all editable records"></form>';
	echo 'NB: Prior to mass update you need to make the field editable. Afterwards you need to submit the records.';
	echo '<form name=csvform><div style="position: absolute; right: 5px; top:0px">Separator<br>&nbsp;<input type="radio" name="separator" value="semicolon" checked>; <input type="radio" name="separator" value="comma">, <br>';
	echo '</form> &nbsp; <button onclick="submitCSV(); return false;">CSV</button></div>';
	echo '</div><hr/>';

  // "*********************************************************************";
  echo '<form name=ListForm><table class="tripleswitch" style="empty-cells: show;"><tr><td><br>Hide<br>Show<br>Edit</td>';
  $cellindexer = "cell_array=new Array(); "; /* this becomes the javascript array that links mass update fields to cell numbers */
  
  for($i=2; $i< sizeof($infofields); $i++)
  { $checked0 = $checked1 = $checked2 = "";
    if($infofields[$i][3] == 0) $checked0 = "checked"; 
    if($infofields[$i][3] == 1) $checked1 = "checked"; 
    if($infofields[$i][3] == 2) $checked2 = "checked"; 

	if($infofields[$i][1] != "")
	  $cellindexer .= 'cell_array['.$i.']="'.$infofields[$i][1].'"; ';
	else 
	  $cellindexer .= 'cell_array['.$i.']="'.$infofields[$i][0].'"; ';
	$colorclass = "";
    if(($infofields[$i][0] == "carrier") && $carrier_notpaid)
	  $colorclass = "notpaid";
    if(($infofields[$i][0] == "discount") && $discount_notpaid)
	  $colorclass = "notpaid";
    if(($infofields[$i][0] == "features") && $features_notpaid)
	  $colorclass = "notpaid";
    if(($infofields[$i][0] == "supplier") && $supplier_notpaid)
	  $colorclass = "notpaid";	
    if(($infofields[$i][0] == "tags") && $tags_notpaid)
	  $colorclass = "notpaid";	  
    echo '<td class="'.$colorclass.'">'.$infofields[$i][0].'<br>';
    echo '<input type="radio" name="disp'.$i.'" id="disp'.$i.'_off" value="0" '.$checked0.' onClick="switchDisplay(\'offTblBdy\', this,'.$i.',0)" /><br>';
    echo '<input type="radio" name="disp'.$i.'" id="disp'.$i.'_on" value="1" '.$checked1.' onClick="switchDisplay(\'offTblBdy\', this,'.$i.',1)" /><br>';
    if ($infofields[$i][0] == "stockflags")
	{ $query="select value from ". _DB_PREFIX_."configuration WHERE name='PS_STOCK_MANAGEMENT'";
	  $res = dbquery($query);
	  $row = mysqli_fetch_array($res);
	  $query="select value from ". _DB_PREFIX_."configuration WHERE name='PS_ADVANCED_STOCK_MANAGEMENT'";
	  $res2 = dbquery($query);
	  $row2 = mysqli_fetch_array($res2);
	  $stock_allowed = false;
	  if(($row["value"] == "1") && ($row2["value"] == "1")) $stock_allowed = true;
	}
	if(($infofields[$i][0]!="parent") && ($infofields[$i][0]!="depth") && ($infofields[$i][7]!=NOT_EDITABLE) && (($infofields[$i][0]!="stockflags") || $stock_allowed))
      echo '<input type="radio" name="disp'.$i.'" id="disp'.$i.'_edit" value="2" '.$checked2.' onClick="switchDisplay(\'offTblBdy\', this,'.$i.',2)" />';
    else
      echo "&nbsp;";
    echo "</td>";
  }

  foreach($features AS $key => $feature)
  { if (in_array($feature, $input["fields"]))
	{    echo '<td><nobr>'.$feature.'</nobr><br>';
		echo '<input type="radio" name="disp'.$i.'" id="disp'.$i.'_off" value="0" '.$checked0.' onClick="switchDisplay(\'offTblBdy\', this,'.$i.',0)" /><br>';
		echo '<input type="radio" name="disp'.$i.'" id="disp'.$i.'_on" value="1" '.$checked1.' onClick="switchDisplay(\'offTblBdy\', this,'.$i.',1)" /><br>';
		echo '<nobr><input type="radio" name="disp'.$i.'" id="disp'.$i.'_edit" value="2" '.$checked2.' onClick="switchDisplay(\'offTblBdy\', this,'.$i.',2)" />';
		echo '<input type="radio" name="disp'.$i.'" id="disp'.$i.'_edit" value="3"  onClick="switchDisplay(\'offTblBdy\', this,'.$i.',3)" /></nobr>';
		echo '</td>';
	    $cellindexer .= 'cell_array['.$i.']="'.$feature.'"; ';
		$i++;
	}
  }
  echo "<td width='35%' align=center><input type=checkbox name=verbose>verbose &nbsp; &nbsp; <input type=button value='Submit all' onClick='return SubmitForm();'></td>";
  if (in_array("image", $input["fields"]))
    $listsize = 20;
  else 
    $listsize = 40;
  echo '<td width="20%"><nobr>Categories: <input name="listcats" value="" size="15"><img src="ea.gif" title="The default selection is what you select in the search block. Here you can enter comma separated cat numbers. For including subcategories add an \'s\' to the number (each product will only be listed once). Note that when you enter values here the quantities of the search block will be ignored. Search text, sorting and field selection are still respected."></nobr><br/>
    Cols: <select name="listcols"><option>1</option><option>2</option><option selected>3</option></select>
    Lines/page: <input name="listlines" value="'.$listsize.'" size="2"><br/>
	<nobr>Separationlines: <input name="listseps" value="1" size="1"></nobr><br/>
	<input type=checkbox name=listdefault checked> default &nbsp; &nbsp;
	<input type="submit" value="List products" onclick="ListProducts(); return false;" title="Make a printable productlist of the selected products and fields." /></td></tr></table></form>
	';
	
  echo "<script>".$cellindexer."</script>";

  echo '<form name="copyForm" action="copy_product_language.php" target="tank" method="post"><input type="hidden" name="products"><input type="hidden" name="id_shop" value="'.$id_shop.'"><input type="hidden" name="id_lang" value="'.$def_lang.'"><input type=hidden name=fields></form>';
 // "*********************************************************************";
$categories = array();
$products_done = array();
if(isset($input['subcats']))
  get_subcats($input['id_category']);
else 
  $categories = array($input['id_category']);
$cats = join(',',$categories);
  
$searchtext = "";
if ($input['search_txt1'] != "")
{  if($input['search_fld1'] == "main fields") 
     $searchtext .= " AND (p.reference like '%".$input['search_txt1']."%' or p.supplier_reference like '%".$input['search_txt1']."%' or pl.name like '%".$input['search_txt1']."%' or pl.description like '%".$input['search_txt1']."%'  or pl.description_short like '%".$input['search_txt1']."%' or m.name like '%".$input['search_txt1']."%' or p.id_product='".$input['search_txt1']."') ";
   else if(($input['search_fld1'] == "p.id_category_default") || ($input['search_fld1'] == "p.id_product"))
     $searchtext .= " AND ".$input['search_fld1']."='".$input['search_txt1']."'";
   else if ($input['search_fld1'] == "cr.name")
	 $searchtext .= " AND EXISTS(SELECT NULL FROM ". _DB_PREFIX_."product_carrier pc LEFT JOIN ". _DB_PREFIX_."carrier cr ON cr.id_reference=pc.id_carrier_reference AND cr.deleted=0 WHERE pc.id_product = p.id_product AND cr.name LIKE '%".$input['search_txt1']."%')";
   else if($input['search_fld1'] == "su.name")
	 $searchtext .= " AND EXISTS(SELECT NULL FROM ". _DB_PREFIX_."product_supplier psu LEFT JOIN ". _DB_PREFIX_."supplier su ON psu.id_supplier=su.id_supplier WHERE psu.id_product = p.id_product AND su.name LIKE '%".$input['search_txt1']."%')";
   else 
     $searchtext .= " AND ".$input['search_fld1']." like '%".$input['search_txt1']."%' ";
}

if ($input['search_txt2'] != "") 
{  if($input['search_fld2'] == "main fields") 
     $searchtext .= " AND (p.reference like '%".$input['search_txt2']."%' or p.supplier_reference like '%".$input['search_txt2']."%' or pl.name like '%".$input['search_txt2']."%' or pl.description like '%".$input['search_txt2']."%'  or pl.description_short like '%".$input['search_txt2']."%' or m.name like '%".$input['search_txt2']."%' or p.id_product='".$input['search_txt2']."') ";
   else if ($input['search_fld2'] == "cr.name")
	 $searchtext .= " AND EXISTS(SELECT NULL FROM ". _DB_PREFIX_."product_carrier pc LEFT JOIN ". _DB_PREFIX_."carrier cr ON cr.id_reference=pc.id_carrier_reference AND cr.deleted=0 WHERE pc.id_product = p.id_product AND cr.name LIKE '%".$input['search_txt2']."%')";
   else if($input['search_fld2'] == "su.name")
	 $searchtext .= " AND EXISTS(SELECT NULL FROM ". _DB_PREFIX_."product_supplier psu LEFT JOIN ". _DB_PREFIX_."supplier su ON psu.id_supplier=su.id_supplier WHERE psu.id_product = p.id_product AND su.name LIKE '%".$input['search_txt2']."%')";

	 else
   { $frags = explode(",",$input['search_txt2']);
     if(sizeof($frags) == 1)
       $searchtext .= " AND ".$input['search_fld2']." like '%".$input['search_txt2']."%' ";
	 else
	 { $searchtext .= " AND (";
	   $first = true;
	   foreach($frags AS $frag)
	   { if($first)
	       $first= false;
		 else
		   $searchtext .= " OR ";
		 if(strpos($frag, "%") === false)
	       $searchtext .=  $input['search_fld2']."='".trim($frag)."' ";
		 else 
		   $searchtext .=  $input['search_fld2']." LIKE '".trim($frag)."' "; 
	   }
	   $searchtext .= ")";
	 }
   }
}
$langtext=' and pl.id_lang='.$id_lang.' and tl.id_lang='.$id_lang;
if ($input['order']=="id_product") $order="p.id_product";
else if ($input['order']=="name") $order="pl.name";
else if ($input['order']=="position") $order="cp.position";
else if ($input['order']=="VAT") $order="t.rate";
else if ($input['order']=="price") $order="ps.price";
else if ($input['order']=="active") $order="ps.active";
else if ($input['order']=="shipweight") $order="p.weight";
else if ($input['order']=="image") $order="i.cover";
else if ($input['order']=="date_upd") $order="p.date_upd";
else $order = $input['order'];
$catseg1=$catseg2="";
if ($input['id_category']!="0") {
	$catseg1=" left join ". _DB_PREFIX_."category_product cp on p.id_product=cp.id_product";
	$catseg2=" AND cp.id_category IN ($cats)";
}

/* Note: we start with the query part after "from". First we count the total and then we take 100 from it */
$queryterms = "p.*,ps.*,pl.*,t.id_tax,t.rate,m.name AS manufacturer, cl.name AS catname, cl.link_rewrite AS catrewrite, pld.name AS originalname, s.quantity, s.depends_on_stock";

$query = " from ". _DB_PREFIX_."product_shop ps left join ". _DB_PREFIX_."product p on p.id_product=ps.id_product";
$query.=" left join ". _DB_PREFIX_."product_lang pl on pl.id_product=p.id_product and pl.id_lang='".$id_lang."' AND pl.id_shop='".$id_shop."'";
$query.=" left join ". _DB_PREFIX_."product_lang pld on pld.id_product=p.id_product and pld.id_lang='".$def_lang."' AND pld.id_shop='".$id_shop."'"; /* This gives the name in the shop language instead of the selected language */
$query.=" left join ". _DB_PREFIX_."manufacturer m on m.id_manufacturer=p.id_manufacturer";
$query.=" left join ". _DB_PREFIX_."category_lang cl on cl.id_category=ps.id_category_default AND cl.id_lang='".$id_lang."' AND cl.id_shop = '".$id_shop."'";
if($share_stock == 0)
  $query.=" left join ". _DB_PREFIX_."stock_available s on s.id_product=p.id_product AND s.id_shop = '".$id_shop."' AND id_product_attribute='0'";
else
  $query.=" left join ". _DB_PREFIX_."stock_available s on s.id_product=p.id_product AND s.id_shop_group = '".$id_shop_group."' AND id_product_attribute='0'";
$query.=" left join ". _DB_PREFIX_."tax_rule tr on tr.id_tax_rules_group=ps.id_tax_rules_group AND tr.id_country='".$id_country."'";
$query.=" left join ". _DB_PREFIX_."tax t on t.id_tax=tr.id_tax";
$query.=" left join ". _DB_PREFIX_."tax_lang tl on t.id_tax=tl.id_tax AND tl.id_lang='".$def_lang."'";
if($order == "i.cover")  /* sorting on image makes only sense to get the products without an image */
{  $queryterms .= ",i.id_image, i.cover";
   $query.=" left join ". _DB_PREFIX_."image i on i.id_product=p.id_product and i.cover=1";
}
$query.=$catseg1;

if(in_array("accessories", $input["fields"]))
{ $query.=" LEFT JOIN ( SELECT GROUP_CONCAT(id_product_2) AS accessories, id_product_1 FROM "._DB_PREFIX_."accessory GROUP BY id_product_1 ) a ON a.id_product_1=p.id_product";
  $queryterms .= ", accessories";
}
foreach($features AS $key => $feature)
{ if (in_array($feature, $input["fields"]))
  { $query.=" left join ". _DB_PREFIX_."feature_product fp".$key." on fp".$key.".id_product=p.id_product AND fp".$key.".id_feature='".$key."'";
	$query.=" left join ". _DB_PREFIX_."feature_value fv".$key." on fp".$key.".id_feature_value=fv".$key.".id_feature_value";
	$query.=" left join ". _DB_PREFIX_."feature_value_lang fl".$key." on fp".$key.".id_feature_value=fl".$key.".id_feature_value AND fl".$key.".id_lang='".$id_lang."'";
	$queryterms .= ",fv".$key.".custom AS custom".$key.",fl".$key.".value AS value".$key;
  }
}

if((($input["search_fld1"]=="tg.name") && ($input["search_txt1"]!="")) || (($input["search_fld2"]=="tg.name") && ($input["search_txt2"]!="")))
{ $queryterms .= ",tg.name AS tag";
  $query .= " LEFT JOIN ". _DB_PREFIX_."product_tag pt ON pt.id_product = p.id_product";
  $query .= " LEFT JOIN ". _DB_PREFIX_."tag tg ON pt.id_tag=tg.id_tag AND tg.id_lang='".$id_lang."'";
}

if(in_array("visits", $input["fields"]) OR ($order=="visits") OR ($input["search_fld1"]=="visitcount") OR ($input["search_fld2"]=="visitcount"))
{ $query .= " LEFT JOIN ( SELECT pg.id_object, count(*) AS visitcount FROM ". _DB_PREFIX_."connections c LEFT JOIN ". _DB_PREFIX_."page pg ON pg.id_page_type='1' AND pg.id_page = c.id_page AND c.id_shop='".$id_shop."'";
  if($input['startdate'] != "")
    $query .= " AND TO_DAYS(c.date_add) >= TO_DAYS('".$input['startdate']."')";
  if($input['enddate'] != "")
    $query .= " AND TO_DAYS(c.date_add) <= TO_DAYS('".$input['enddate']."')";
  $queryterms .= ", visitcount ";
  $query .= " GROUP BY pg.id_object ) v ON p.id_product=v.id_object";
}
if(in_array("visitz", $input["fields"]) OR ($order=="visitz") OR ($input["search_fld1"]=="visitedpages") OR ($input["search_fld2"]=="visitedpages"))
{ $query .= " LEFT JOIN ( SELECT pg.id_object, sum(counter) AS visitedpages FROM ". _DB_PREFIX_."page_viewed v LEFT JOIN ". _DB_PREFIX_."page pg ON pg.id_page_type='1' AND pg.id_page = v.id_page AND v.id_shop='".$id_shop."'";
  $query .= " LEFT JOIN ". _DB_PREFIX_."date_range d ON d.id_date_range = v.id_date_range";
  if($input['startdate'] != "")
    $query .= " AND TO_DAYS(d.time_start) >= TO_DAYS('".$input['startdate']."')";
  if($input['enddate'] != "")
    $query .= " AND TO_DAYS(d.time_end) <= TO_DAYS('".$input['enddate']."')";
  $queryterms .= ", visitedpages ";
  $query .= " GROUP BY v.id_page ) w ON p.id_product=w.id_object";
}
if(in_array("revenue", $input["fields"]) OR in_array("salescnt", $input["fields"]) OR in_array("orders", $input["fields"]) OR ($order=="revenue")OR ($order=="orders")OR ($order=="buyers"))
{ $query .= " LEFT JOIN ( SELECT product_id, SUM(product_quantity)-SUM(product_quantity_return) AS quantity, ";
  $query .= " ROUND(SUM(total_price_tax_incl),2) AS revenue, ";
  $query .= " count(DISTINCT d.id_order) AS ordercount, count(DISTINCT o.id_customer) AS buyercount FROM ". _DB_PREFIX_."order_detail d";
  $query .= " LEFT JOIN ". _DB_PREFIX_."orders o ON o.id_order = d.id_order AND o.id_shop=d.id_shop";
  $query .= " WHERE d.id_shop='".$id_shop."'";
  if($input['startdate'] != "")
    $query .= " AND TO_DAYS(o.date_add) >= TO_DAYS('".$input['startdate']."')";
  if($input['enddate'] != "")
    $query .= " AND TO_DAYS(o.date_add) <= TO_DAYS('".$input['enddate']."')";
  $query .= " AND o.valid=1";
  $query .= " GROUP BY d.product_id ) r ON p.id_product=r.product_id";
  $queryterms .= ", revenue, r.quantity AS salescount, ordercount, buyercount ";
}

$res=dbquery("SELECT COUNT(*) AS rcount ".$query." WHERE ps.active='1' AND ps.id_shop='".$id_shop."' ".$searchtext.$catseg2);
$row = mysqli_fetch_array($res);
$numrecs = $row['rcount'];

$query.=" WHERE ps.id_shop='".$id_shop."' ".$searchtext.$catseg2;

  $statfields = array("salescnt", "revenue","orders","buyers","visits","visitz");
  $stattotals = array("salescnt" => 0, "revenue"=>0,"orders"=>0,"buyers"=>0,"visits"=>0,"visitz"=>0); /* store here totals for stats */
//  $statz = array("salescount", "revenue","ordercount","buyercount","visitcount","visitedpages"); /* here pro memori: moved up to search_fld definition */
  if(in_array($order, $statfields))
  { $ordertxt = $statz[array_search($order, $statfields)];
  }
  else
    $ordertxt = $order;
  $query .= " ORDER BY ".$ordertxt." ".$input['rising']." LIMIT ".$input['startrec'].",".$input['numrecs'];
  
  $query= "select SQL_CALC_FOUND_ROWS ".$queryterms.$query; /* note: you cannot write here t.* as t.active will overwrite p.active without warning */
  $res=dbquery($query);
  $numrecs3 = mysqli_num_rows($res);
  $res2=dbquery("SELECT FOUND_ROWS() AS foundrows");
  $row2 = mysqli_fetch_array($res2);
  $numrecs2 = $row2['foundrows'];
  
  echo "Your search delivered ".$numrecs2." records of which ".$numrecs." are active. ";
  echo $numrecs3." displayed. <b>You are editing product data for shop number ".$id_shop."</b>";
  if($share_stock == 1) echo " - stock group ".$shop_group_name;
  if(in_array("accessories", $input["fields"]))
    echo "<br/>For accessories fill in comma separated article numbers like '233,467'. Non-existent articles numbers will be ignored!";
  echo '<br/><span id="warning" style="background-color: #FFAAAA"></span>';
// echo $query;

echo "<script>
function SubmitForm()
{ reccount = ".$numrecs3.";
  submitted=0;
  for(i=0; i<reccount; i++)
  { divje = document.getElementById('trid'+i);
    if(!divje)
      continue;
	var chg = divje.getAttribute('changed');
";
	if(in_array("description", $input["fields"]))
	  echo 'docfield = eval("document.Mainform.description"+i);
	if(docfield)
	{ if (docfield.parentNode.childNodes[0].tagName == "DIV")
	  { var tmp = tinyMCE.get(docfield.name).getContent();
	    if(tmp != docfield.value)
	    { docfield.value = tmp;
	      chg = 1;
		}
	  }
	  else
	    docfield.value = tidy_html(docfield.value)
	}';
	
	if(in_array("shortdescription", $input["fields"]))
	  echo 'docfield = eval("document.Mainform.description_short"+i);
	if(docfield)
	{ if (docfield.parentNode.childNodes[0].tagName == "DIV")
	  { var tmp = tinyMCE.get(docfield.name).getContent();
	    if(tmp != docfield.value)
	    { docfield.value = tmp;
	      chg = 1;
		}
	  }
	  else
	    docfield.value = tidy_html(docfield.value)
	}';
	
	if(in_array("carrier", $input["fields"]))
	  echo 'carrier = eval("document.Mainform.carriersel"+i);
	if(carrier)			/* note that this  will cause the carrier to be empty rather than none when there is an error message */
	{ if (carrier.options[0].value == "none")
	  { carrier.options.length=0;
	  }
	}';
	
	echo "
    if(chg == 0)
    { divje.parentNode.innerHTML='';
    }
	else
	{ submitted++;
";
	if(in_array("discount", $input["fields"]))
	  echo "if(!check_discounts(i)) return false;";
  
	echo "
	}
  }
  var tmp = document.getElementById('featureblock0');
  if(tmp.style == 'hidden')
    Mainform.featuresset.value = 0;
  else
    Mainform.featuresset.value = 1;  
  Mainform.verbose.value = ListForm.verbose.checked;
  Mainform.action = 'product-proc.php?c='+reccount+'&d='+submitted;
  Mainform.submit();
}

function sortTheTable(tab,col,flag) 
{ if(tabchanged != 0)
  { alert('You can only sort the table if it hasn\'t been changed yet!');
    /* copying fields will not copy changed contents!!! */
    return flag;
  }
  return sortTable(tab, col, flag);
}

/* getpath() takes a string like '189' and returns something like '/1/8/9' */
function getpath(name)
{ str = '';
  for (var i=0; i<name.length; i++)
  { str += '/'+name[i];
  }
  return str;
}

</script>";
  // "*********************************************************************";
  echo '<div id="dhwindow" style="display:none"></div>';
  echo '<form name="Mainform" method=post><input type=hidden name=reccount value="'.$numrecs3.'"><input type=hidden name=id_lang value="'.$id_lang.'">';
  echo '<input type=hidden name=id_shop value='.$id_shop.'>';
  echo '<input type=hidden name=verbose><input type=hidden name=featuresset>';
  echo '<div id="testdiv"><table id="Maintable" class="triplemain"><colgroup id="mycolgroup">';
  for($i=0; $i<sizeof($infofields); $i++)
  { $align = $namecol = "";
    if($infofields[$i][5] == 1)
      $align = ' style="text-align:right"';
	if($infofields[$i][0] == "name")
      $namecol = ' class="namecol"';
    echo "<col id='col".$i."'".$align.$namecol."></col>";
  }
  for(;$i<sizeof($infofields)+$featurecount; $i++)
	echo "<col id='col".$i."'></col>";
  echo "</colgroup><thead><tr>";

  $statsfound = false; /* flag whether we should create an extra stats totals line */
  $stattotals = array();
  for($i=0; $i<sizeof($infofields); $i++)
  { $reverse = "false";
    $id="";
    if (in_array($infofields[$i][0], $statfields))
	{ $reverse = 1;
	  $id = 'id="stat_'.$infofields[$i][0].'"'; /* assign id for filling in totals */
      $statsfound = true;
	  $stattotals[$infofields[$i][0]] = floatval(0);
	}
    echo '<th><a href="" onclick="this.blur(); return sortTable(\'offTblBdy\', '.$i.', '.$reverse.');" '.$id.' title="'.$infofields[$i][1].'">'.$infofields[$i][0].'</a></th
>';
  }
  foreach($features AS $key => $feature)
  { if (in_array($feature, $input["fields"]))
		echo '<th><a href="" onclick="this.blur(); return sortTheTable(\'offTblBdy\', '.$i++.', false);" title="'.$feature.'">'.$feature.'</a></th>';
  }	

  echo '<th><a href="" onclick="this.blur(); return upsideDown(\'offTblBdy\');" title="Upside down: reverse table order"><img src="upsidedown.jpg"></a></th>';
  echo "</tr></thead
  ><tbody id='offTblBdy'>"; /* end of header */
 
  $x=0;
  while ($datarow=mysqli_fetch_array($res)) {
    /* Note that trid (<tr> id) cannot be an attribute of the tr as it would get lost with sorting */
    echo '<tr><td id="trid'.$x.'" changed="0"><input type="button" value="X" style="width:4px" onclick="RemoveRow('.$x.')" title="Hide line from display" /><input type=hidden name="id_product'.$x.'" value="'.$datarow['id_product'].'"></td>';
    for($i=1; $i< sizeof($infofields); $i++)
    { $sorttxt = "";
      $color = "";
	  if (($infofields[$i][0] == "qty") || ($infofields[$i][0] == "stockflags") || ($infofields[$i][0] == "warehousing") || ($infofields[$i][0] == "supplier"))
	  { $aquery = "SELECT count(*) FROM ". _DB_PREFIX_."product_attribute WHERE id_product=".$datarow['id_product'];
		$ares=dbquery($aquery);
		list($combination_count) = mysqli_fetch_row($ares); 
	  }
	  
      if($infofields[$i][2] == "priceVAT")
		$myvalue =  number_format(((($datarow['rate']/100) +1) * $datarow['price']),2, '.', '');
      else if (($infofields[$i][2] != "carrier") && ($infofields[$i][2] != "tags") && ($infofields[$i][2] != "discount") && ($infofields[$i][2] != "combinations") && ($infofields[$i][2] != "supplier") && ($infofields[$i][2] != "attachmnts"))
        $myvalue = $datarow[$infofields[$i][2]];
      if($i == 1) /* id */
	  { echo "<td><a href='product-solo.php?id_product=".$myvalue."&id_lang=".$id_lang."&id_shop=".$id_shop."' title='".$datarow['originalname']."' target='_blank'>".addspaces($myvalue)."</a></td>";
	  }
	  else if ($infofields[$i][0] == "name")
	  { if ($rewrite_settings == '1')
          echo "<td><a href='".get_base_uri().$datarow['catrewrite']."/".$datarow['id_product']."-".$datarow['link_rewrite'].".html' title='".$datarow['originalname']."' target='_blank' class='redname'>".$myvalue."</a></td>";
		else
          echo "<td><a href='".get_base_uri()."product.php?id_product=".$datarow['id_product']."' title='".$datarow['originalname']."' target='_blank' class='redname'>".$myvalue."</a></td>";
	  }
	  else if($infofields[$i][6] == 1)
      { $sorttxt = "srt='".str_replace("'", "\'",$myvalue)."'";
        echo "<td ".$sorttxt.">".$myvalue."</td>";
      }
      else if ($infofields[$i][0] == "category")
	  { echo "<td ".$sorttxt.">";
	    $cquery = "select id_category from ". _DB_PREFIX_."category_product WHERE id_product='".$datarow['id_product']."' ORDER BY id_category";
		$cres=dbquery($cquery);
		$z=0;
		while ($crow=mysqli_fetch_array($cres)) 
		{	if($z++ > 0) echo ",";
			if ($crow['id_category'] == $myvalue)
				echo "<a title='".$category_names[$myvalue]."' href='#' onclick='return false;'>".$myvalue."</a>";
			else 
				echo "<a title='".$category_names[$crow['id_category']]."' href='#' onclick='return false;' style='text-decoration: none;'>".$crow['id_category']."</a>";
		}
	    echo "</td>";
		mysqli_free_result($cres);
	  }
	  else if ($infofields[$i][0] == "qty")
	  { if($datarow["depends_on_stock"] == "1")
          echo '<td style="background-color:yellow">'.$myvalue.'</td>';	  
		else if($combination_count != 0)
            echo '<td style="background-color:#FF8888">'.$myvalue.'</td>';	 
		else
            echo "<td>".$myvalue."</td>";
	  }
	  else if ($infofields[$i][0] == "warehousing")
	  { echo "<td>";
	    if($combination_count == 0)
		{ echo "<table>";
	      $wquery = "SELECT w.id_warehouse, w.name AS whname, physical_quantity, usable_quantity, price_te FROM ". _DB_PREFIX_."stock st";
	      $wquery .= " LEFT JOIN ". _DB_PREFIX_."warehouse w on w.id_warehouse=st.id_warehouse";
		  $wquery .= " WHERE id_product=".$datarow['id_product']." ORDER BY w.name";
		  $wres=dbquery($wquery);
		  while ($wrow=mysqli_fetch_array($wres))
		    echo "<tr><td>".$wrow["whname"]."</td><td>".$wrow["physical_quantity"]."</td><td>".$wrow["usable_quantity"]."</td><td>".number_format($wrow["price_te"],2)."</td></tr>";
		  echo "</table>";
		}
		else
		{ $aquery = "SELECT id_product_attribute FROM ". _DB_PREFIX_."product_attribute WHERE id_product=".$datarow['id_product'];
		  $ares=dbquery($aquery);
		  while ($arow=mysqli_fetch_array($ares))
		    $attrs[] = $arow["id_product_attribute"];
		  
		  $paquery = "SELECT pa.id_product_attribute, GROUP_CONCAT(CONCAT(gl.name,': ',l.name)) AS nameblock from ". _DB_PREFIX_."product_attribute pa";
		  $paquery .= " LEFT JOIN ". _DB_PREFIX_."product_attribute_combination c on pa.id_product_attribute=c.id_product_attribute";
		  $paquery .= " LEFT JOIN ". _DB_PREFIX_."attribute a on a.id_attribute=c.id_attribute";
		  $paquery .= " LEFT JOIN ". _DB_PREFIX_."attribute_lang l on l.id_attribute=c.id_attribute AND l.id_lang='".$id_lang."'";
		  $paquery .= " LEFT JOIN ". _DB_PREFIX_."attribute_group_lang gl on gl.id_attribute_group=a.id_attribute_group AND gl.id_lang='".$id_lang."'";
		  $paquery .= " WHERE pa.id_product='".$datarow['id_product']."' GROUP BY pa.id_product_attribute ORDER BY pa.id_product_attribute";
		  $pares=dbquery($paquery);
		  
		  while ($parow=mysqli_fetch_array($pares))
		  { echo '<table border=1  title="'.$parow["nameblock"].'">';
			$wquery = "SELECT w.id_warehouse, w.name AS whname, physical_quantity, usable_quantity, price_te FROM ". _DB_PREFIX_."stock st";
			$wquery .= " LEFT JOIN ". _DB_PREFIX_."warehouse w on w.id_warehouse=st.id_warehouse";
			$wquery .= " WHERE id_product=".$datarow['id_product']." AND st.id_product_attribute=".$parow['id_product_attribute']." ORDER BY w.name";
			$wres=dbquery($wquery);
			$size = mysqli_num_rows($wres);
			
			$xx = 0;
			while ($wrow=mysqli_fetch_array($wres))
			{ echo "<tr><td>".$wrow["whname"]."</td><td>".$wrow["physical_quantity"]."</td><td>".$wrow["usable_quantity"]."</td><td>".number_format($wrow["price_te"],2)."</td>";
			  if($xx++ == 0)
			    echo '<td rowspan="'.$size.'">'.$parow["nameblock"].'</td>';
			  echo "</tr>";
			}
		    echo "</table>";
		  }
		  mysqli_free_result($wres);
		  mysqli_free_result($pares);
		}
		echo "</td>";
	  }
	  else if ($infofields[$i][0] == "accessories")
	  { echo "<td srt='".$myvalue."'>";
	    $accs = explode(",",$myvalue);
		$z=0;
	    foreach($accs AS $acc)
		{ if($z++ > 0) echo ",";
		  echo "<a title='".get_product_name($acc)."' href='#' onclick='return false;' style='text-decoration: none;'>".$acc."</a>";
		}
	    echo "</td>";
	  }
	  else if ($infofields[$i][0] == "stockflags")
	  { echo "<td srt='".$myvalue."'>";
	    $depends_on_stock = $datarow['depends_on_stock'];
		$advanced_stock_management = $datarow['advanced_stock_management'];
		if($advanced_stock_management == 0)
		  echo $stockflagsarray[0];
		else if($depends_on_stock == 0)
		  echo $stockflagsarray[1];
		else
		  echo $stockflagsarray[2];
	    echo "</td>";
	  }	  
      else if ($infofields[$i][0] == "VAT")
      { $sorttxt = "idx='".$datarow['id_tax_rules_group']."'";
		echo "<td ".$sorttxt.">".(float)$myvalue."</td>";
      }
	  else if ($infofields[$i][0] == "supplier")
      { $squery = "SELECT id_product_supplier,ps.id_supplier,id_product_attribute FROM ". _DB_PREFIX_."product_supplier ps";
	    $squery .= " LEFT JOIN ". _DB_PREFIX_."supplier s on s.id_supplier=ps.id_supplier";
		$squery .= " WHERE id_product=".$datarow['id_product']." AND id_product_attribute=0 ORDER BY s.name";
		$sres=dbquery($squery);
	    $sups = array();
		while ($srow=mysqli_fetch_array($sres))
		    $sups[] = $srow["id_supplier"];

		$attrs = array();	
		if($combination_count == 0)
		{ echo '<td sups="'.implode(",",$sups).'" attrs="0">';
		  echo '<table border=1 class="supplier" id="suppliers0s'.$x.'" title="">';
		  $squery = "SELECT ps.id_product_supplier,s.id_supplier,ps.id_product_attribute,product_supplier_reference AS reference,product_supplier_price_te AS supprice FROM ". _DB_PREFIX_."product_supplier ps";
		  $squery .= " LEFT JOIN ". _DB_PREFIX_."supplier s on s.id_supplier=ps.id_supplier";
		  $squery .= " WHERE id_product=".$datarow['id_product']." AND (ps.id_supplier != 0) ORDER BY s.name";
		  $sres=dbquery($squery);
		  $rowcount = mysqli_num_rows($sres);
		  $xx=0;
		  while ($srow=mysqli_fetch_array($sres)) 
		  { echo "<tr title='".$srow["id_supplier"]."'>";
		  	echo "<td >".$supplier_names[$srow['id_supplier']]."</td><td>".$srow['reference']."</td><td>".$srow['supprice']."</td>";
			if($xx++ == 0) echo '<td rowspan="'.$rowcount.'">';
			echo "</tr>";
		  }
		  echo "</table>";
		  mysqli_free_result($sres);
		}
		else
		{ $aquery = "SELECT id_product_attribute FROM ". _DB_PREFIX_."product_attribute WHERE id_product=".$datarow['id_product'];
		  $ares=dbquery($aquery);
		  while ($arow=mysqli_fetch_array($ares))
		    $attrs[] = $arow["id_product_attribute"];
		  echo '<td sups="'.implode(",",$sups).'" attrs="'.implode(",",$attrs).'">';
		  
		  $paquery = "SELECT pa.id_product_attribute, GROUP_CONCAT(CONCAT(gl.name,': ',l.name)) AS nameblock from ". _DB_PREFIX_."product_attribute pa";
		  $paquery .= " LEFT JOIN ". _DB_PREFIX_."product_attribute_combination c on pa.id_product_attribute=c.id_product_attribute";
		  $paquery .= " LEFT JOIN ". _DB_PREFIX_."attribute a on a.id_attribute=c.id_attribute";
		  $paquery .= " LEFT JOIN ". _DB_PREFIX_."attribute_lang l on l.id_attribute=c.id_attribute AND l.id_lang='".$id_lang."'";
		  $paquery .= " LEFT JOIN ". _DB_PREFIX_."attribute_group_lang gl on gl.id_attribute_group=a.id_attribute_group AND gl.id_lang='".$id_lang."'";
		  $paquery .= " WHERE pa.id_product='".$datarow['id_product']."' GROUP BY pa.id_product_attribute ORDER BY pa.id_product_attribute";
		  $pares=dbquery($paquery);
		  
		  while ($parow=mysqli_fetch_array($pares))
		  { echo '<table border=1 class="supplier" id="suppliers'.$parow['id_product_attribute'].'s'.$x.'" title="'.$parow["nameblock"].'">';
			$suppls = array();
			$squery = "SELECT ps.id_product_supplier,ps.id_supplier,s.name as suppliername, ps.id_product_attribute,product_supplier_reference AS reference,product_supplier_price_te AS supprice FROM ". _DB_PREFIX_."product_supplier ps";
			$squery .= " LEFT JOIN ". _DB_PREFIX_."supplier s on s.id_supplier=ps.id_supplier";
			$squery .= " WHERE ps.id_product_attribute=".$parow['id_product_attribute']." AND (ps.id_supplier != 0) ORDER BY suppliername";
		    $sres=dbquery($squery);
			while ($srow=mysqli_fetch_array($sres))
			{ $suppls[$srow["id_supplier"]] = array($srow["id_product_supplier"],$srow['reference'], $srow['supprice']);
			}
			$xx = 0;
			foreach($sups AS $sup)
			{ if(isset($suppls[$sup]))
			  { echo "<tr title='".$sup."'>";
			    echo "<td >".$supplier_names[$sup]."</td><td>".$suppls[$sup][1]."</td><td>".$suppls[$sup][2]."</td>";
			  }
			  else 		/* this is the situation initially: when the supplier has just been added for the product */
			  { echo "<tr title='0'>"; 
			    echo "<td>".$supplier_names[$sup]."</td><td></td><td>0.000000</td>";
			  }
			  if($xx++ == 0)
			    echo '<td rowspan="'.sizeof($sups).'">'.$parow["nameblock"].'</td>';
			  echo "</tr>";
			}
		    echo "</table>";
		  }
		  mysqli_free_result($sres);
		  mysqli_free_result($pares);
		}
		echo "</td>";
      }
	  else if ($infofields[$i][0] == "carrier")
      { $cquery = "SELECT id_carrier_reference FROM ". _DB_PREFIX_."product_carrier WHERE id_product=".$datarow['id_product']." AND id_shop='".$id_shop."' LIMIT 1";
		$cres=dbquery($cquery);
		if(mysqli_num_rows($cres) != 0)
		{ $cquery = "SELECT id_reference, cr.name FROM ". _DB_PREFIX_."product_carrier pc";
		  $cquery .= " LEFT JOIN ". _DB_PREFIX_."carrier cr ON cr.id_reference=pc.id_carrier_reference AND cr.deleted=0";
		  $cquery .= " WHERE id_product='".$datarow['id_product']."' AND id_shop='".$id_shop."' ORDER BY cr.name";
		  $cres=dbquery($cquery);
		  echo "<td><table border=1 id='carriers".$x."'>";
		  while ($crow=mysqli_fetch_array($cres)) 
		  { echo "<tr><td id='".$crow['id_reference']."'>".$crow['name']."</td></tr>";
		  }
		  echo "</table></td>";
		}
		else
		  echo "<td></td>";
		mysqli_free_result($cres);
	  }
	  else if ($infofields[$i][0] == "tags")
      { $tquery = "SELECT pt.id_tag,name FROM ". _DB_PREFIX_."product_tag pt";
		$tquery .= " LEFT JOIN ". _DB_PREFIX_."tag t ON pt.id_tag=t.id_tag AND t.id_lang='".$id_lang."'";
	    $tquery .= " WHERE pt.id_product='".$datarow['id_product']."'";
		$tres=dbquery($tquery);
		$idx = 0;
		echo "<td>";
		while ($trow=mysqli_fetch_array($tres)) 
		{ if($idx++ > 0) echo "<br/>";
		  echo "<nobr>".$trow["name"]."</nobr>";
		}
		echo "</td>";
		mysqli_free_result($tres);
      }
	  else if ($infofields[$i][0] == "combinations")
      { echo "<td>";
		if($combination_count != 0)
			echo '<a href="combi-edit.php?id_product='.$datarow['id_product'].'&id_shop='.$id_shop.'" title="Click here to edit combinations in separate window" target="_blank" style="background-color:#99aaee; text-decoration:none">&nbsp; '.$combination_count.' &nbsp;</a>';
		echo "</td>";
      }
	  else if ($infofields[$i][0] == "attachmnts")
      { $cquery = "SELECT a.file_name, a.file, a.mime, l.name, p.id_attachment FROM ". _DB_PREFIX_."product_attachment p";
		$cquery .= " LEFT JOIN ". _DB_PREFIX_."attachment a ON a.id_attachment=p.id_attachment";
	    $cquery .= " LEFT JOIN ". _DB_PREFIX_."attachment_lang l ON a.id_attachment=l.id_attachment AND l.id_lang='".$id_lang."'";
	    $cquery .= " WHERE id_product='".$datarow['id_product']."'";
		echo "<td>";
		$cres=dbquery($cquery);
		$z=0;
		while ($crow=mysqli_fetch_array($cres)) 
		{	if($z++ > 0) echo "<br>";
			echo "<a class='attachlink' title='".$crow['id_attachment']."' href='downfile.php?filename=".$crow["file_name"]."&filecode=".$crow["file"]."&download_dir=".$download_dir."&mime=".$crow["mime"]."' target=_blank'>".$crow['name']."</a>";
		}
	    echo "</td>";
		mysqli_free_result($cres);
      }
	  else if ($infofields[$i][0] == "discount")
      { $dquery = "SELECT id_specific_price, id_product_attribute, sp.id_currency,sp.id_country, sp.id_group, sp.id_customer, sp.price, sp.from_quantity,sp.reduction,sp.reduction_type,sp.from,sp.to, id_shop, cu.iso_code AS currency";
//	    $dquery .= ", c.name AS country,g.name AS groupname, cu.name AS currency";
		$dquery .= " FROM ". _DB_PREFIX_."specific_price sp";
//		$dquery.=" left join ". _DB_PREFIX_."group_lang g on g.id_group=sp.id_group AND g.id_lang='".$id_lang."'";
//		$dquery.=" left join ". _DB_PREFIX_."country_lang c on sp.id_country=c.id_country AND c.id_lang='".$id_lang."'";
		$dquery.=" left join ". _DB_PREFIX_."currency cu on sp.id_currency=cu.id_currency";		
	    $dquery .= " WHERE sp.id_product='".$datarow['id_product']."'";
//		$dquery .= " AND (sp.id_shop='".$id_shop."' OR sp.id_shop='0')";
		$dres=dbquery($dquery);
		echo "<td><table border=1 id='discount".$x."'>";
		while ($drow=mysqli_fetch_array($dres)) 
		{ echo '<tr specid='.$drow["id_specific_price"].'>';
/* 						0				1		2		3		  4			5			6		7				8			9	 		10	11*/
 /* discount fields: shop, product_attribute, currency, country, group, id_customer, price, from_quantity, reduction, reduction_type, from, to */
		  if($drow["id_shop"] == "0") $drow["id_shop"] = "";
		  echo "<td>".$drow["id_shop"]."</td>";
		  if($drow["id_product_attribute"] == "0") $drow["id_product_attribute"] = "";
		  echo "<td>".$drow["id_product_attribute"]."</td>";
		  echo "<td>".$drow["currency"]."</td>";
		  echo "<td>".$drow["id_country"]."</td>";
		  echo "<td>".$drow["id_group"]."</td>";

		  if($drow["id_customer"] == "0") $drow["id_customer"] = "";
		  echo "<td>".$drow["id_customer"]."</td>";
		  if($drow["price"] == -1) $drow["price"] = "";
		  else $drow["price"] = $drow["price"] * 1; /* remove trailing zeroes */
		  echo "<td>".$drow["price"]."</td>";
		  echo "<td style='background-color:#FFFFAA'>".$drow["from_quantity"]."</td>";
		  if($drow["reduction_type"] == "percentage")
			$drow["reduction"] = $drow["reduction"] * 100;
		  else 
		    $drow["reduction"] = $drow["reduction"] * 1;
		  echo "<td>".$drow["reduction"]."</td>";
		  if($drow["reduction_type"] == "amount") $drow["reduction_type"] = "amt"; else $drow["reduction_type"] = "pct";
		  echo "<td>".$drow["reduction_type"]."</td>"; 
		  if($drow["from"] == "0000-00-00 00:00:00") $drow["from"] = "";
		  else if(substr($drow["from"],11) == "00:00:00") $drow["from"] = substr($drow["from"],0,10);
		  echo "<td>".$drow["from"]."</td>";
		  if($drow["to"] == "0000-00-00 00:00:00") $drow["to"] = ""; 
		  else if(substr($drow["to"],11) == "00:00:00") $drow["to"] = substr($drow["to"],0,10);
		  echo "<td>".$drow["to"]."</td>";
		  echo "</tr>";
		}
		echo "</table></td>";
		mysqli_free_result($dres);
      }
	  else if ($infofields[$i][0] == "revenue")
      { echo "<td><a href onclick='return salesdetails(".$datarow['id_product'].")' title='show salesdetails'>".$datarow['revenue']."</a></td>";
      }
      else if ($infofields[$i][0] == "image")
      { $iquery = "SELECT id_image,cover FROM ". _DB_PREFIX_."image WHERE id_product='".$datarow['id_product']."' ORDER BY position";
		$ires=dbquery($iquery);
		$id_image = 0;
		$imagelist = "";
		$first=0;
		while ($irow=mysqli_fetch_array($ires)) 
		{	if($irow['cover'] == 1)
			{ $id_image=$irow['id_image'];
			}
			if($first++ != 0)
			  $imagelist .= ",";
			$imagelist .= $irow['id_image'];
		}
		echo "<td>".get_product_image($id_image,$imagelist)."</td>";
		mysqli_free_result($ires);
      }
      else
         echo "<td>".$myvalue."</td>";
	  if(in_array($infofields[$i][0], $statfields))
	    $stattotals[$infofields[$i][0]] += $myvalue;
    }

	foreach($features AS $key => $feature)
	{ if (in_array($feature, $input["fields"]))
	  { if($datarow['value'.$key] == "")
			echo "<td></td>";
		else if($datarow['custom'.$key] == "0")
	  	    echo "<td><b>".$datarow['value'.$key]."</b></td>";
		else // custom = 1
	  	    echo "<td>".$datarow['value'.$key]."</td>";
	  
/*	  if($datarow['value'.$key] != "")
	  	    echo "<td idx='".$datarow['custom'.$key]."'>".$datarow['value'.$key]."</td>";
		else
	  	    echo "<td idx='-1'></td>";
*/	  }
	}

//	echo '<td style="height: 10px;"><table style="height:100%; border-spacing:0; border:0;"><tr style="min-height:50%"><td ><img src="masscopy.png" title="copy field to mass edit" onclick="CopyToMassEdit(this)" ></td></tr><tr style="min-height:50%"><td><img src="enter.png" title="submit row '.$x.'" onclick="RowSubmit(this)"></td></tr></table></td>';
//    echo '<td style="margin:0; padding:0; height: 100%; border:0"><img src="masscopy.png" title="copy field to mass edit" onclick="CopyToMassEdit(this)" vertical-" ></td></tr><tr style="min-height:50%"><td><img src="enter.png" title="submit row '.$x.'" onclick="RowSubmit(this)"></td></tr></table></td>';
//	echo '<td style="position:relative; display:block; height:100%"><div style="position: absolute; top: 0; bottom: 0; right: 0; left:0; height:100%; background-color:yellow">xx<br><img src="masscopy.png" title="copy field to mass edit" onclick="CopyToMassEdit(this)" ><br><img src="enter.png" title="submit row '.$x.'" onclick="RowSubmit(this)"></div></td>';
//	echo '<td style="overflow:hidden"><div style="margin:-10em; padding:10em; background-color:yellow; display:flex; align-content:space-around; flex-direction:column;"><img src="masscopy.png" title="copy field to mass edit" onclick="CopyToMassEdit(this)" > <img src="enter.png" title="submit row '.$x.'" onclick="RowSubmit(this)"></div></td>';
//	echo '<td style="display:flex; align-content:space-around; flex-flow: row wrap; flex-direction:column; "><img src="enter.png" title="submit row '.$x.'" onclick="RowSubmit(this)" style="margin:auto; flex:none"> <img src="enter.png" title="submit row '.$x.'" onclick="RowSubmit(this)" style="margin:auto; flex:none"></td>'; 
//	echo '<td><img src="masscopy.png" title="copy field to mass edit" onclick="CopyToMassEdit(this)" style="margin-bottom:9px" > <img src="enter.png" title="submit row '.$x.'" onclick="RowSubmit(this)"></td>';
	echo '<td><img src="enter.png" title="submit row '.$x.'" onclick="RowSubmit(this)"></td>';
	$x++;
    echo '</tr
>'; 
  }
  
  if(mysqli_num_rows($res) == 0)
	echo "<strong>products not found</strong>";
  echo '</table></form></div>';
  
  echo "<script>function showtotals() {";
  foreach($statfields AS $statfield)
	{ if(in_array($statfield, $input["fields"]))
	   echo "var id = document.getElementById('stat_".$statfield."');
	   id.title = 'Page total=".$stattotals[$statfield]."'; ";
	}
  echo "}</script>";
  
  if($statsfound)
  { echo '<table class=triplemain><td colspan=2 style="text-align:center">Totals</td>';
    for($i=0; $i< sizeof($infofields); $i++)
	{ if (in_array($infofields[$i][0], $statfields))
	    echo '<tr><td>'.$infofields[$i][0].'</td><td>'.$stattotals[$infofields[$i][0]].'</td></tr>';
	}
	echo '</table>';
  }
  
  echo '<div style="display:block;"><form name=rowform action="product-proc.php" method=post target=tank><table id=subtable></table>';
  echo '<input type=hidden name=id_row><input type=hidden name=id_lang value="'.$id_lang.'">';
  echo '<input type=hidden name=id_shop value="'.$id_shop.'"><input type=hidden name=verbose><input type=hidden name=featuresset></form></div>';

  include "footer.php";
  echo '</body></html>';


$product_list = array();
function get_product_name($id)
{ global $product_list,$id_lang;
  if(isset($product_list[$id]))
    return $product_list[$id];
  $query = "select name from ". _DB_PREFIX_."product_lang WHERE id_product='".$id."' AND id_lang='".$id_lang."'";
  $res = dbquery($query);
  $row=mysqli_fetch_array($res);
  $product_list[$id] = $row["name"];
  return $row["name"];
}

/* get subcategories: this function is recursively called */
function get_subcats($cat_id) 
{ global $categories, $conn;
  $categories[] = $cat_id;
  if($cat_id == 0) die("You cannot have category with value zero");
  $query="select id_category from ". _DB_PREFIX_."category WHERE id_parent='".mysqli_real_escape_string($conn, $cat_id)."'";
  $res = dbquery($query);
  while($row = mysqli_fetch_array($res))
    get_subcats($row['id_category']);
}

?>
