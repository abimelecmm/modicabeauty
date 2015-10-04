<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;
if(!isset($input['id_lang'])) $input['id_lang']="";
$id_lang = intval($input["id_lang"]);
if(!isset($input['id_shop'])) $input['id_shop']="";
$id_shop = intval($input["id_shop"]);
if(!isset($input['id_product'])) $input['id_product']="";
$id_product = intval($input["id_product"]);
$startdate = $enddate = "0000-00-00";

   /* [0]title, [1]keyover, [2]source, [3]display(0=not;1=yes;2=edit;), [4]fieldwidth(0=not set), [5]align(0=default;1=right), [6]sortfield, [7]Editable, [8]table */
  define("HIDE", 0); define("DISPLAY", 1); define("EDIT", 2);  // display
  define("LEFT", 0); define("RIGHT", 1); // align
  define("NO_SORTER", 0); define("SORTER", 1); /* sortfield => 0=no escape removal; 1=escape removal; */
  define("NOT_EDITABLE", 0); define("INPUT", 1); define("TEXTAREA", 2); define("DROPDOWN", 3); define("BINARY", 4); define("EDIT_BTN", 5);  /* title, keyover, source, display(0=not;1=yes;2=edit), fieldwidth(0=not set), align(0=default;1=right), sortfield */

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
   "supplier" => array("supplier","", "supplier", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "su.name"),
   "linkrewrite" => array("link_rewrite","linkrewrite", "link_rewrite", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "pl.link_rewrite"),
   "metatitle" => array("meta_title","metatitle", "meta_title", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "pl.meta_title"),
   "metakeywords" => array("meta_keywords","metakeywords", "meta_keywords", DISPLAY, 0, RIGHT, NO_SORTER, TEXTAREA, "pl.meta_keywords"),
   "metadescription" => array("meta_description","metadescription", "meta_description", DISPLAY, 0, LEFT, NO_SORTER, TEXTAREA, "pl.meta_description"),
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
   "available" => array("available","", "available_for_order", DISPLAY, 0, LEFT, NO_SORTER, BINARY, "p.available_for_order"),
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

$rewrite_settings = get_rewrite_settings();
$input["fields"] = $fields = array("id_product","name","VAT","price","priceVAT", "quantity", "active","category", "ean", "description", "shortdescription", "image",
"reference","linkrewrite","metatitle","metakeywords","metadescription","wholesaleprice","manufacturer",
"onsale","onlineonly","date_upd","minimalquantity","shipweight","shipheight","shipwidth","shipdepth","aShipCost","attachmnts","tags",
"carrier","available","accessories","combinations","discount","supplier");

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
  $res = dbquery($query);
  $attachmentblock0 = '<input type=hidden name="attachment_defaultCQX"><input type=hidden name="myattachmentsCQX">';
  $attachmentblock0 .= '<table cellspacing=8><tr><td><select id="attachmentlistCQX" size=4 multiple>';
  $attachmentblock1 = "";
  while ($row=mysqli_fetch_array($res)) 
  { $attachmentblock1 .= '<option value="'.$row['id_attachment'].'">'.str_replace("'","\'",$row['name']).'</option>';
  } 
  $attachmentblock1 .= '</select>';
  $attachmentblock2 = '</td><td><a href=# onClick=" Addattachment(\\\'CQX\\\'); reg_change(this); return false;"><img src=add.gif border=0></a><br><br>';
  $attachmentblock2 .= '<a href=# onClick="Removeattachment(\\\'CQX\\\'); reg_change(this); return false;"><img src=remove.gif border=0></a></td><td><select id=attachmentselCQX size=3></select></td></tr></table>';
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
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Prestashop Product Solo Edit</title>
<link rel="stylesheet" href="style1.css" type="text/css" />
<script src="http://tinymce.cachefly.net/4.1/tinymce.min.js"></script> <!-- Prestashop settings can be found at /js/tinymce.inc.js -->
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<link rel="stylesheet" href="windowfiles/dhtmlwindow.css" type="text/css" />
<script type="text/javascript" src="windowfiles/dhtmlwindow.js"></script>
<script type="text/javascript">
var product_fields = new Array();
var taxblock = '<?php echo $taxblock ?>';
var supplierblock0 = '<?php echo $supplierblock0 ?>';
var supplierblock1 = '<?php echo $supplierblock1 ?>';
var supplierblock2 = '<?php echo $supplierblock2 ?>';
var manufacturerblock = '<?php echo $manufacturerblock ?>';
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

function RemoveRow(row)
{ var tblEl = document.getElementById("offTblBdy");
  var trow = document.getElementById("trid"+row).parentNode;
  trow.innerHTML = "<td></td>";
}

function price_change(elt)
{ var val, rate;
  if(elt.name == "price")
  { val = parseFloat(elt.value);
    rate = Mainform.VAT.options[Mainform.VAT.selectedIndex].getAttribute("rate");
    rate = parseFloat(rate);
	newprice = val*(1+ (rate/100));
	Mainform.priceVAT.value = Math.round(newprice*100)/100; /* round to 2 decimals */
  }
  else if(elt.name == "priceVAT")
  { val = parseFloat(elt.value);
    rate = Mainform.VAT.options[Mainform.VAT.selectedIndex].getAttribute("rate");
    rate = parseFloat(rate);
	newprice = val/(1+ (rate/100));
	Mainform.price.value = Math.round(newprice*100)/100; /* round to 2 decimals */
  }
  else if(elt.name == "VAT")
  { rate = Mainform.VAT.options[Mainform.VAT.selectedIndex].getAttribute("rate");
    rate = parseFloat(rate);
	val = Mainform.price.value;
	newprice = val*(1+ (rate/100));
	Mainform.priceVAT.value = Math.round(newprice*100)/100; /* round to 2 decimals */
  }  
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
<?php if(!file_exists("TE_plugin_carriers.php"))
		echo 'else if(field=="carrier") 
		alert("Carriers is a plugin that needs to be bought seperately at www.Prestools.com.\nWithout the plugin you are in demo-mode: you can make changes but they will not be saved!");';
	  if(!file_exists("TE_plugin_discounts.php"))
		echo 'else if(field=="discount") 
		alert("Special Prices/Discounts is a plugin that needs to be bought seperately at www.Prestools.com.\nWithout the plugin you are in demo-mode: you can make changes but they will not be saved!");';
	  if(!file_exists("TE_plugin_features.php"))
		echo 'else if(field=="features") 
		alert("Suppliers is a plugin that needs to be bought seperately at www.Prestools.com.\nWithout the plugin you are in demo-mode: you can make changes but they will not be saved!");';
		if(!file_exists("TE_plugin_suppliers.php"))
		echo 'else if(field=="supplier") 
		alert("Suppliers is a plugin that needs to be bought seperately at www.Prestools.com.\nWithout the plugin you are in demo-mode: you can make changes but they will not be saved!");';
	  if(!file_exists("TE_plugin_tags.php"))
		echo 'else if(field=="tags") 
		alert("Tags is a plugin that needs to be bought seperately at www.Prestools.com.\nWithout the plugin you are in demo-mode: you can make changes but they will not be saved!");';
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
	  else if(field=="accessories") 
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

function Addattachment(plIndex)
{ var list = document.getElementById('attachmentlist'+plIndex);
  var sel = document.getElementById('attachmentsel'+plIndex);
  var listindex = list.selectedIndex;
  if(listindex==-1) return; /* none selected */
  var i, max = sel.options.length;
  attachment = list.options[listindex].text;
  attach_id = list.options[listindex].value;
  list.options[listindex]=null;
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

</script>
</head>

<body>
<?php
print_menubar();
echo '<h1>Solo Product Edit</h1>
<form name="search_form" method="get" action="product-solo.php">
<table class="triplehome" cellpadding=0 cellspacing=0>
<tr><td>Product id: </td><td><input size=2 name=id_product value="'.$id_product.'"></td><td><input type=submit value="Search"></td>
<td style="text-align:right; width:30%" rowspan=4><iframe name=tank width="230" height="95"></iframe></td></tr>
<tr><td>Language: </td><td><select name="id_lang" style="margin-top:5px">';
	  $query=" select * from ". _DB_PREFIX_."lang ";
	  $res=dbquery($query);
	  while ($language=mysqli_fetch_array($res)) {
		$selected='';
	  	if ($language['id_lang']==$id_lang) $selected=' selected="selected" ';
	        echo '<option  value="'.$language['id_lang'].'" '.$selected.'>'.$language['name'].'</option>';
	  }
echo '</select></td><td>Your default language is '.$def_lang.'.</td></tr>
<tr><td>Shop: </td><td><select name="id_shop">'.$shopblock.'</select></td><td></td></tr>
</table></form>';

echo "You are editing product data for shop number ".$id_shop;
if($share_stock == 1) echo " - stock group ".$shop_group_name;
echo "<br/>Country=".$countryname." (used for VAT grouping and calculations)<hr>";

//echo '</tr><tr><td class="notpaid">90% of this software is free. Only for a few fields that required major development time (carrier, supplier, features, tags, discounts (=specific prices)) do you need to buy a plugin at <a href="http://www.prestools.com/">www.Prestools.com</a>. You can try out those plugins in the free version: all functionality - except for saving - is there.</td></tr></table>';

echo "<script>";

if(!file_exists("TE_plugin_features.php"))
	echo 'alert("Features is a plugin that needs to be bought seperately at www.Prestools.com.\nWithout the plugin you are in demo-mode: you can make changes but they will not be saved!");';
echo '
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
	  
	function salesdetails(product)
	{ window.open("product-sales.php?product="+product+"&startdate='.$startdate.'&enddate='.$enddate.'&id_shop='.$id_shop.'","", "resizable,scrollbars,location,menubar,status,toolbar");
      return false;
    }
	</script>';
 // "*********************************************************************";

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

if(in_array("accessories", $input["fields"]))
{ $query.=" LEFT JOIN ( SELECT GROUP_CONCAT(id_product_2) AS accessories, id_product_1 FROM "._DB_PREFIX_."accessory GROUP BY id_product_1 ) a ON a.id_product_1=p.id_product";
  $queryterms .= ", accessories";
}


if(in_array("visits", $input["fields"]))
{ $query .= " LEFT JOIN ( SELECT pg.id_object, count(*) AS visitcount FROM ". _DB_PREFIX_."connections c LEFT JOIN ". _DB_PREFIX_."page pg ON pg.id_page_type='1' AND pg.id_page = c.id_page AND c.id_shop='".$id_shop."'";
  if($input['startdate'] != "")
    $query .= " AND TO_DAYS(c.date_add) >= TO_DAYS('".$input['startdate']."')";
  if($input['enddate'] != "")
    $query .= " AND TO_DAYS(c.date_add) <= TO_DAYS('".$input['enddate']."')";
  $queryterms .= ", visitcount ";
  $query .= " GROUP BY pg.id_object ) v ON p.id_product=v.id_object";
}
if(in_array("visitz", $input["fields"]))
{ $query .= " LEFT JOIN ( SELECT pg.id_object, sum(counter) AS visitedpages FROM ". _DB_PREFIX_."page_viewed v LEFT JOIN ". _DB_PREFIX_."page pg ON pg.id_page_type='1' AND pg.id_page = v.id_page AND v.id_shop='".$id_shop."'";
  $query .= " LEFT JOIN ". _DB_PREFIX_."date_range d ON d.id_date_range = v.id_date_range";
  if($input['startdate'] != "")
    $query .= " AND TO_DAYS(d.time_start) >= TO_DAYS('".$input['startdate']."')";
  if($input['enddate'] != "")
    $query .= " AND TO_DAYS(d.time_end) <= TO_DAYS('".$input['enddate']."')";
  $queryterms .= ", visitedpages ";
  $query .= " GROUP BY v.id_page ) w ON p.id_product=w.id_object";
}
if(in_array("revenue", $input["fields"]) OR in_array("salescnt", $input["fields"]) OR in_array("orders", $input["fields"]))
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

$query.=" WHERE ps.id_shop='".$id_shop."' AND p.id_product='".$id_product."'";

  $statfields = array("salescnt", "revenue","orders","buyers","visits","visitz");
//  $statz = array("salescount", "revenue","ordercount","buyercount","visitcount","visitedpages"); /* here pro memori: moved up to search_fld definition */
 
  $query= "select SQL_CALC_FOUND_ROWS ".$queryterms.$query; /* note: you cannot write here t.* as t.active will overwrite p.active without warning */
  $res=dbquery($query);


  if(in_array("accessories", $input["fields"]))
    echo "<br/>For accessories fill in comma separated article numbers like '233,467'. Non-existent articles numbers will be ignored!";
  echo '<br/><span id="warning" style="background-color: #FFAAAA"></span>';
// echo $query;

echo "<script>
function SubmitForm()
{ reccount = 1;
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
/*  var tmp = document.getElementById('featureblock0');
  if(tmp.style == 'hidden')
    Mainform.featuresset.value = 0;
  else
    Mainform.featuresset.value = 1;  
*/
  if(Mainform.verbose.value == 'on') Mainform.verbose.value = 'true'; /* make same as product_edit */
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
  echo '<form name="Mainform" method=post><input type=hidden name=reccount value="1"><input type=hidden name=id_lang value="'.$id_lang.'">';
  echo '<input type=hidden name=id_shop value='.$id_shop.'>';
  echo '<input type=hidden name=featuresset>';
  echo '<input type=checkbox name=verbose>verbose &nbsp; &nbsp; &nbsp; <input type=button value="Submit all" onClick="return SubmitForm();" style="display:inline-block">';
  echo '<div id="testdiv"><table id="Maintable" class="triplemain">';

  $statsfound = false; /* flag whether we should create an extra stats totals line */
  for($i=0; $i<sizeof($fields); $i++)
  { $reverse = "false";
    $id="";
    if (in_array($fields[$i], $statfields))
	{ $reverse = 1;
	  $id = 'id="stat_'.$fields[$i].'"'; /* assign id for filling in totals */
      $statsfound = true;
	}
  }

	if(mysqli_num_rows($res) == 0)
	{ echo "This is an unknown product number!";
	  return;
	}
    $datarow=mysqli_fetch_array($res);
	$x = 0;
    for($i=0; $i< sizeof($fields); $i++)
    { $sorttxt = "";
      $color = "";
	  echo "<tr><td>".$fields[$i]."</td>";
	  
      if($fields[$i] == "priceVAT")
		$myvalue =  number_format(((($datarow['rate']/100) +1) * $datarow['price']),2, '.', '');
	  else if ($i == 0)
	    $myvalue  = $id_product;
      else if (($fields[$i] != "carrier") && ($fields[$i] != "tags") && ($fields[$i] != "discount") && ($fields[$i] != "combinations") && ($fields[$i] != "supplier") && ($fields[$i] != "attachmnts"))
        $myvalue = $datarow[$field_array[$fields[$i]][2]];
      if($i == 0) /* id */
      {   echo "<td><a href='".get_base_uri()."product.php?id_product=".$myvalue."' title='".$datarow['originalname']."' target='_blank'>".addspaces($id_product)."</a>";
		  echo '<input type=hidden name="id_product" value="'.$id_product.'"></td>';
	  } 
	  else if($fields[$i] == 1)
      { $sorttxt = "srt='".str_replace("'", "\'",$myvalue)."'";
        echo "<td ".$sorttxt.">".$myvalue."</td>";
      }
      else if ($fields[$i] == "category")
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
	  else if ($fields[$i] == "quantity")
	  { if($datarow["depends_on_stock"] == "1")
          echo '<td style="background-color:yellow">'.$myvalue.'</td>';	  
		else 
		{ $aquery = "SELECT id_product_attribute FROM ". _DB_PREFIX_."product_attribute WHERE id_product=".$datarow['id_product'];
		  $ares=dbquery($aquery);
		  $attrs = array();	
		  if(mysqli_num_rows($ares) != 0)
            echo '<td style="background-color:#FF8888">'.$myvalue.'</td>';	 
		  else
            echo '<td><input name="'.$field_array[$fields[$i]][0].'" value="'.$myvalue.'"></td>';
		}
	  }
	  else if ($fields[$i] == "accessories")
	  { echo "<td srt='".$myvalue."'>";
	    $accs = explode(",",$myvalue);
		$z=0;
	    foreach($accs AS $acc)
		{ if($z++ > 0) echo ",";
		  echo "<a title='".get_product_name($acc)."' href='#' onclick='return false;' style='text-decoration: none;'>".$acc."</a>";
		}
	    echo "</td>";
	  }
      else if ($fields[$i] == "VAT")
      { $sorttxt = "idx='".$datarow['id_tax_rules_group']."'";
		echo "<td ".$sorttxt.">";
		echo '<select name="VAT" onchange="price_change(this)">'.str_replace('value="'.$datarow['id_tax_rules_group'].'"','value="'.$datarow['id_tax_rules_group'].'" selected',$taxblock);
		echo "</td>";
      }
      else if (($fields[$i] == "price") || ($fields[$i] == "priceVAT"))
	  {  echo '<td><input name="'.$field_array[$fields[$i]][0].'" onkeyup=price_change(this) value="'.$myvalue.'"></td>';
	  }
	  else if ($fields[$i] == "supplier")
      { $squery = "SELECT id_product_supplier,ps.id_supplier,id_product_attribute FROM ". _DB_PREFIX_."product_supplier ps";
	    $squery .= " LEFT JOIN ". _DB_PREFIX_."supplier s on s.id_supplier=ps.id_supplier";
		$squery .= " WHERE id_product=".$datarow['id_product']." AND id_product_attribute=0 ORDER BY s.name";
		$sres=dbquery($squery);
	    $sups = array();
		while ($srow=mysqli_fetch_array($sres))
		    $sups[] = $srow["id_supplier"];

	    $aquery = "SELECT id_product_attribute FROM ". _DB_PREFIX_."product_attribute WHERE id_product=".$datarow['id_product'];
		$ares=dbquery($aquery);
		$attrs = array();	
		if(mysqli_num_rows($ares) == 0)
		   $attrs[] = 0;
		else
		{ while ($arow=mysqli_fetch_array($ares))
		    $attrs[] = $arow["id_product_attribute"];
		}

		echo '<td sups="'.implode(",",$sups).'" attrs="'.implode(",",$attrs).'">';
			
		if($attrs[0] == 0)
		{ $has_combinations = false;
		  echo '<table border=1 class="supplier" id="suppliers0s'.$x.'" title="">';
		  $squery = "SELECT ps.id_product_supplier,s.id_supplier,ps.id_product_attribute,product_supplier_reference AS reference,product_supplier_price_te AS supprice FROM ". _DB_PREFIX_."product_supplier ps";
		  $squery .= " LEFT JOIN ". _DB_PREFIX_."supplier s on s.id_supplier=ps.id_supplier";
		  $squery .= " WHERE id_product=".$datarow['id_product']." AND (ps.id_supplier != 0) ORDER BY s.name";
		  $sres=dbquery($squery); /* in 1.6 product_supplier has */
		  $rowcount = mysqli_num_rows($sres);
		  $xx=0;
		  while ($srow=mysqli_fetch_array($sres)) 
		  { echo "<td >".$supplier_names[$srow['id_supplier']]."</td><td>".$srow['reference']."</td><td>".$srow['supprice']."</td>";
			if($xx++ == 0) echo '<td rowspan="'.$rowcount.'">';
			echo "</tr>";
		  }
		  echo "</table>";
		  mysqli_free_result($sres);
		}
		else /* note that a product with attributes can have a row for the product (id_product_attribute=0) but not for the attributes */
			 /* So we create the $sups array that contains all the fields and set them to zero/empty when there are no values for them */
		{ $has_combinations = true;
	
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
			$squery .= " WHERE ps.id_product_attribute=".$parow['id_product_attribute']." ORDER BY suppliername";
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
	  else if ($fields[$i] == "carrier")
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
	  else if ($fields[$i] == "tags")
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
	  else if ($fields[$i] == "combinations")
      { $cquery = "SELECT count(*) AS counter FROM ". _DB_PREFIX_."product_attribute";
	    $cquery .= " WHERE id_product='".$datarow['id_product']."'";
		$cres=dbquery($cquery);
		$crow=mysqli_fetch_array($cres);
		echo "<td>";
		if($crow["counter"] != 0)
			echo '<a href="combi-edit.php?id_product='.$datarow['id_product'].'&id_shop='.$id_shop.'" title="Click here to edit combinations in separate window" target="_blank" style="background-color:#99aaee; text-decoration:none">&nbsp; '.$crow["counter"].' &nbsp;</a>';
		echo "</td>";
		mysqli_free_result($cres);
      }
	  else if ($fields[$i] == "attachmnts")
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
	  else if ($fields[$i] == "discount")
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
	  else if ($fields[$i] == "revenue")
      { echo "<td><a href onclick='return salesdetails(".$datarow['id_product'].")' title='show salesdetails'>".$datarow['revenue']."</a></td>";
      }
      else if ($fields[$i] == "image")
      { $iquery = "SELECT id_image,cover FROM ". _DB_PREFIX_."image WHERE id_product='".$datarow['id_product']."' ORDER BY position";
		$ires=dbquery($iquery);
		$id_image = 0;
		$imagelist = "";
		$first=0;
		echo "<td>";
		while ($irow=mysqli_fetch_array($ires)) 
		{	$border = '';
		    if($irow['cover'] == 1)
		      $border = ' style="border:1px"';
			echo get_product_image($irow['id_image'],"jpg")." ";
		}
		echo "</td>";
		mysqli_free_result($ires);
      }
	  else if (($fields[$i] == "description")||($fields[$i] == "shortdescription")||($fields[$i] == "metadescription"))
	     echo '<td><textarea rows=4 cols=40 name="'.$field_array[$fields[$i]][0].'">'.$myvalue.'</textarea></td>';
      else
         echo '<td><input name="'.$field_array[$fields[$i]][0].'" value="'.$myvalue.'"></td>';
	  echo "</tr>";
	}
	
	foreach($features AS $key => $feature)
	{ $xquery = "SELECT fv.custom AS custom,fl.value AS value FROM ". _DB_PREFIX_."feature_product fp";
	  $xquery.=" left join ". _DB_PREFIX_."feature_value fv on fp.id_feature_value=fv.id_feature_value";
	  $xquery.=" left join ". _DB_PREFIX_."feature_value_lang fl on fp.id_feature_value=fl.id_feature_value AND fl.id_lang='".$id_lang."'";
	  $xquery .= " WHERE fp.id_product = '".$id_product."' AND fp.id_feature='".$key."'";
	  $xres=dbquery($xquery);
	  while ($xrow=mysqli_fetch_array($xres)) /* mag maar één keer gebeuren */
	  { echo "<tr><td>".$key."-".$feature."</td><td>";
	    if($xrow["custom"] == "1") echo $xrow["value"];
		else echo "<b>".$xrow["value"]."</b>";
		echo "</td></tr>";
	  }
	}
  echo '</table></form></div>';
  
  if($statsfound)
  { echo '<table class=triplemain><td colspan=2 style="text-align:center">Totals</td>';
    for($i=0; $i< sizeof($fields); $i++)
	{ if (in_array($fields[$i], $statfields))
	    echo '<tr><td>'.$fields[$i].'</td><td>'.$stattotals[$fields[$i]].'</td></tr>';
	}
	echo '</table>';
  }

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

?>
