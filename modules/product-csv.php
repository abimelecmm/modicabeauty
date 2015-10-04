<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;
if(!isset($input['search_txta'])) $input['search_txta'] = "";
if(!isset($input['search_txtb'])) $input['search_txtb'] = "";
if(!isset($input['search_fld']) || ($input['search_fld'] == "")) $input['search_fld'] = "main fields";
if(!isset($input['startrec']) || (trim($input['startrec']) == '')) $input['startrec']="0";
if(!isset($input['numrecs'])) $input['numrecs']="100";
if(!isset($input['id_category'])) $input['id_category']="0";
if(!isset($input['id_shop'])) $input['id_shop']="1";
if(!isset($input['separator'])) $input['separator']="semicolumn";
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
    $input['fields'] = array("name","VAT","price", "active","category", "ean", "description", "shortdescription", "image");
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
   "supplier" => array("supplier","", "id_supplier", DISPLAY, 0, LEFT, NO_SORTER, 0, "p.id_supplier"),
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
   
   /* extras */
   "date_upd" => array("date_upd","", "date_upd", DISPLAY, 0, LEFT, NO_SORTER, NOT_EDITABLE, "p.date_upd"),
   "available" => array("available","", "available_for_order", DISPLAY, 0, LEFT, NO_SORTER, BINARY, "p.available_for_order"),
   "shipheight" => array("shipheight","", "height", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "p.height"),
   "shipwidth" => array("shipwidth","", "width", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "p.width"),
   "shipdepth" => array("shipdepth","", "depth", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "p.depth"), 
   "wholesaleprice" => array("wholesaleprice","", "wholesale_price", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "ps.wholesale_price"),
   "aShipCost" => array("aShipCost","", "additional_shipping_cost", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "ps.additional_shipping_cost"),
     
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
  while ($row=mysqli_fetch_array($res)) 
  { if(in_array($row['name'], $duplos))
	  $name = $row['name'].$row['id_category'];
	else
	  $name = $row['name'];
    $category_names[$row['id_category']] = $name;
  } 
  
  /* make supplier names list */
  $query = "select id_supplier,name from ". _DB_PREFIX_."supplier ORDER BY id_supplier";
  $res=dbquery($query);
  $supplier_names = array();
  while ($row=mysqli_fetch_array($res)) 
  { $supplier_names[$row['id_supplier']] = $row['name'];
  } 
  
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
		if ($shop['id_shop']==$input['id_shop']) {$selected=' selected="selected" ';} else $selected="";
	        $shopblock .= '<option  value="'.$shop['id_shop'].'" '.$selected.'>'.$shop['id_shop']."-".$shop['name'].'</option>';
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
  
$categories = array();
$products_done = array();
if(isset($input['subcats']))
  get_subcats($input['id_category']);
else 
  $categories = array($input['id_category']);
$cats = join(',',$categories);
  
$searchtext = "";
if ($input['search_txta'] != "")
{  if($input['search_fld'] == "main fields") 
     $searchtext .= " AND (p.reference like '%".$input['search_txta']."%' or p.supplier_reference like '%".$input['search_txta']."%' or pl.name like '%".$input['search_txta']."%' or pl.description like '%".$input['search_txta']."%'  or pl.description_short like '%".$input['search_txta']."%' or m.name like '%".$input['search_txta']."%' or p.id_product='".$input['search_txta']."') ";
   else if($input['search_fld'] == "p.id_category_default")
     $searchtext .= " AND p.id_category_default='".$input['search_txta']."'";
   else
     $searchtext .= " AND ".$input['search_fld']." like '%".$input['search_txta']."%' ";
}

if ($input['search_txtb'] != "") 
{  if($input['search_fld'] == "main fields") 
     $searchtext .= " AND (p.reference like '%".$input['search_txtb']."%' or p.supplier_reference like '%".$input['search_txtb']."%' or pl.name like '%".$input['search_txtb']."%' or pl.description like '%".$input['search_txtb']."%'  or pl.description_short like '%".$input['search_txtb']."%' or m.name like '%".$input['search_txtb']."%' or p.id_product='".$input['search_txtb']."') ";
   else
   { $frags = explode(",",$input['search_txtb']);
     if(sizeof($frags) == 1)
       $searchtext .= " AND ".$input['search_fld']." like '%".$input['search_txtb']."%' ";
	 else
	 { $searchtext .= " AND (";
	   $first = true;
	   foreach($frags AS $frag)
	   { if($first)
	       $first= false;
		 else
		   $searchtext .= " OR ";
		 if(strpos($frag, "%") === false)
	       $searchtext .=  $input['search_fld']."='".trim($frag)."' ";
		 else 
		   $searchtext .=  $input['search_fld']." LIKE '".trim($frag)."' "; 
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
else $order = $input['order'];
$catseg1=$catseg2="";
if ($input['id_category']!="0") {
	$catseg1=" left join ". _DB_PREFIX_."category_product cp on p.id_product=cp.id_product";
	$catseg2=" AND cp.id_category IN ($cats)";
}

/* Note: we start with the query part after "from". First we count the total and then we take 100 from it */
$queryterms = "p.*,ps.*,pl.*,t.id_tax,t.rate,m.name AS manufacturer, cl.name AS catname, cl.link_rewrite AS catrewrite, pld.name AS originalname, s.quantity";

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

if($input["search_fld"]=="cr.name")
{ $queryterms .= ",cr.name";
  $query .= " LEFT JOIN ". _DB_PREFIX_."product_carrier pc ON pc.id_product = p.id_product";
  $query .= " LEFT JOIN ". _DB_PREFIX_."carrier cr ON cr.id_reference=pc.id_carrier_reference AND cr.deleted=0";
}

if($input["search_fld"]=="tg.name")
{ $queryterms .= ",tg.name";
  $query .= " LEFT JOIN ". _DB_PREFIX_."product_tag pt ON pt.id_product = p.id_product";
  $query .= " LEFT JOIN ". _DB_PREFIX_."tag tg ON pt.id_tag=tg.id_tag AND tg.id_lang='".$id_lang."'";
}

if(in_array("visits", $input["fields"]) OR ($order=="visits") OR ($input["search_fld"]=="visitcount"))
{ $query .= " LEFT JOIN ( SELECT pg.id_object, count(*) AS visitcount FROM ". _DB_PREFIX_."connections c LEFT JOIN ". _DB_PREFIX_."page pg ON pg.id_page_type='1' AND pg.id_page = c.id_page AND c.id_shop='".$id_shop."'";
  if($input['startdate'] != "")
    $query .= " AND TO_DAYS(c.date_add) >= TO_DAYS('".$input['startdate']."')";
  if($input['enddate'] != "")
    $query .= " AND TO_DAYS(c.date_add) <= TO_DAYS('".$input['enddate']."')";
  $queryterms .= ", visitcount ";
  $query .= " GROUP BY pg.id_object ) v ON p.id_product=v.id_object";
}
if(in_array("visitz", $input["fields"]) OR ($order=="visitz") OR ($input["search_fld"]=="visitedpages"))
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
{ $query .= " LEFT JOIN ( SELECT product_id, SUM(product_quantity)-SUM(product_quantity_return) AS quantity, ROUND(SUM((product_quantity-product_quantity_return)*(product_price-reduction_amount)*(100-reduction_percent)*(100+tax_rate)/10000),2) AS revenue, count(DISTINCT d.id_order) AS ordercount, count(DISTINCT o.id_customer) AS buyercount FROM ". _DB_PREFIX_."order_detail d";
  $query .= " LEFT JOIN ". _DB_PREFIX_."orders o ON o.id_order = d.id_order AND o.id_shop=d.id_shop";
  if($input['startdate'] != "")
    $query .= " AND TO_DAYS(o.date_add) >= TO_DAYS('".$input['startdate']."')";
  if($input['enddate'] != "")
    $query .= " AND TO_DAYS(o.date_add) <= TO_DAYS('".$input['enddate']."')";
  $query .= " AND o.valid=1";
  $query .= " WHERE d.id_shop='".$id_shop."'";
  $query .= " GROUP BY d.product_id ) r ON p.id_product=r.product_id";
  $queryterms .= ", revenue, r.quantity AS salescount, ordercount, buyercount ";
}

$query.=" where ps.id_shop='".$id_shop."' ".$searchtext.$catseg2;

$res=dbquery("SELECT COUNT(*) AS rcount ".$query);
$row = mysqli_fetch_array($res);
$numrecs = $row['rcount'];

  $statfields = array("salescnt", "revenue","orders","buyers","visits","visitz");
  $stattotals = array("salescnt" => 0, "revenue"=>0,"orders"=>0,"buyers"=>0,"visits"=>0,"visitz"=>0); /* store here totals for stats */
//  $statz = array("salescount", "revenue","ordercount","buyercount","visitcount","visitedpages"); /* here pro memori: moved up to search_fld definition */
  if(in_array($order, $statfields))
  { $ordertxt = $statz[array_search($order, $statfields)];
  }
  else
    $ordertxt = $order;
  $query .= " ORDER BY ".$ordertxt." ".$input['rising']." LIMIT ".$input['startrec'].",".$input['numrecs'];
  
  $query= "select ".$queryterms.$query; /* note: you cannot write here t.* as t.active will overwrite p.active without warning */
  $res=dbquery($query);
  $numrecs2 = mysqli_num_rows($res);
  
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=product-'.date('Y-m-d-Gis').'.csv');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');

// According to a comment on php.net the following can be added here to solve Chinese language problems
// fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));

  // "*********************************************************************";
  if($input['separator'] == "comma")
  { $separator = ",";
	$subseparator = ";";
  }
  else 
  { $separator = ";";
	$subseparator = ",";
  }
  $csvline = array();  // array for the fputcsv function
  for($i=1; $i<sizeof($infofields); $i++)
  { if($infofields[$i][0] == "supplier")
    { $csvline[] = "supplier";
	  $csvline[] = "supplier reference";
	}
	else if($infofields[$i][0] == "discount")
    { $csvline[] = "discount amount";
	  $csvline[] = "discount pct";
	  $csvline[] = "discount from";
	  $csvline[] = "discount to";
	}
	else
      $csvline[] = $infofields[$i][0];
  }
  foreach($features AS $key => $feature)
  { if (in_array($feature, $input["fields"]))
      $csvline[] = $feature;
  }	
  $out = fopen('php://output', 'w');
  publish_csv_line($out, $csvline, $separator);

  $x=0;
  $ress = dbquery("SELECT domain,physical_uri FROM ". _DB_PREFIX_."shop_url WHERE id_shop='".$id_shop."'");
  $rows = mysqli_fetch_array($ress);
  $imagebase = "http://".$rows["domain"].$rows["physical_uri"];
  while ($datarow=mysqli_fetch_array($res))
  { $csvline = array();
    for($i=1; $i< sizeof($infofields); $i++)
    { $sorttxt = "";
      $color = "";
      if($infofields[$i][2] == "priceVAT")
		$myvalue =  number_format(((($datarow['rate']/100) +1) * $datarow['price']),2, '.', '');
      else if (($infofields[$i][2] != "carrier") && ($infofields[$i][2] != "tags") && ($infofields[$i][2] != "discount") && ($infofields[$i][2] != "combinations"))
        $myvalue = $datarow[$infofields[$i][2]];
      if($i == 1) /* id */
	  { $csvline[] = $myvalue;
	  }
	  else if($infofields[$i][6] == 1)
      { $csvline[] = $myvalue;
      }
      else if ($infofields[$i][0] == "category")
	  { $cquery = "select cp.id_category from ". _DB_PREFIX_."category_product cp";
		$cquery .= " LEFT JOIN ". _DB_PREFIX_."category_lang cl on cp.id_category=cl.id_category AND id_lang='".$id_lang."'";
		$cquery .= " WHERE cp.id_product='".$datarow['id_product']."' ORDER BY id_category";
		$cres=dbquery($cquery);
		$z=0;
		$tmp = $category_names[$myvalue];
		while ($crow=mysqli_fetch_array($cres)) 
		{	if ($crow['id_category'] == $myvalue)
				continue;
			$tmp .= $subseparator.$category_names[$crow['id_category']];
		}
	    $csvline[] = $tmp;
		mysqli_free_result($cres);
	  }
	  else if ($infofields[$i][0] == "accessories")
	  { $csvline[] =  $accs;
	  }
      else if ($infofields[$i][0] == "VAT")
      { $csvline[] = (float)$myvalue;
      }
	  else if ($infofields[$i][0] == "supplier")
      { $squery = "SELECT id_supplier,product_supplier_reference AS reference FROM ". _DB_PREFIX_."product_supplier WHERE id_product=".$datarow['id_product']." AND id_product_attribute='0' LIMIT 1";
		$sres=dbquery($squery);
		if(mysqli_num_rows($sres) > 0)
		{ $srow=mysqli_fetch_array($sres); 
		  $csvline[] = $supplier_names[$srow['id_supplier']];
		  $csvline[] = $srow['reference'];
		}
		else
		{ $csvline[] = "";
		  $csvline[] = "";
		}
		mysqli_free_result($sres);
      }
	  else if ($infofields[$i][0] == "carrier")		/* niet beschikbaar in CSV */
      { $tmp = ""; 
	    $cquery = "SELECT id_carrier_reference FROM ". _DB_PREFIX_."product_carrier WHERE id_product=".$datarow['id_product']." AND id_shop='".$id_shop."' LIMIT 1";
		$cres=dbquery($cquery);
		if(mysqli_num_rows($cres) != 0)
		{ $cquery = "SELECT id_reference, cr.name FROM ". _DB_PREFIX_."product_carrier pc";
		  $cquery .= " LEFT JOIN ". _DB_PREFIX_."carrier cr ON cr.id_reference=pc.id_carrier_reference AND cr.deleted=0";
		  $cquery .= " WHERE id_product='".$datarow['id_product']."' AND id_shop='".$id_shop."' ORDER BY cr.name";
		  $cres=dbquery($cquery);
		  $idx = 0;
		  while ($crow=mysqli_fetch_array($cres)) 
		  { if($idx++ > 0) $tmp .= $subseparator;
		    $tmp .= $crow["name"];
		    $idx++;
		  }
		}
		$csvline[] = $tmp;
		mysqli_free_result($cres);
	  }
	  else if ($infofields[$i][0] == "tags")
      { $tquery = "SELECT pt.id_tag,name FROM ". _DB_PREFIX_."product_tag pt";
		$tquery .= " LEFT JOIN ". _DB_PREFIX_."tag t ON pt.id_tag=t.id_tag AND t.id_lang='".$id_lang."'";
	    $tquery .= " WHERE pt.id_product='".$datarow['id_product']."'";
		$tres=dbquery($tquery);
		$idx = 0;
		$tmp = "";
		while ($trow=mysqli_fetch_array($tres)) 
		{ if($idx++ > 0) $tmp .= $subseparator;
		  $tmp .= $trow["name"];
		  $idx++;
		}
		$csvline[] = $tmp;
		mysqli_free_result($tres);
      }
	  else if ($infofields[$i][0] == "combinations")
      { $csvline[] = "";
	    continue;
	    $cquery = "SELECT count(*) AS counter FROM ". _DB_PREFIX_."product_attribute";
	    $cquery .= " WHERE id_product='".$datarow['id_product']."'";
		$cres=dbquery($cquery);
		$crow=mysqli_fetch_array($cres);
		echo "<td>";
		if($crow["counter"] != 0)
			echo '<a href="combi-edit.php?id_product='.$datarow['id_product'].'&id_shop='.$id_shop.'" title="Click here to edit combinations in separate window" target="_blank" style="background-color:#99aaee; text-decoration:none">&nbsp; '.$crow["counter"].' &nbsp;</a>';
		echo "</td>";
		mysqli_free_result($cres);
      }
	  else if ($infofields[$i][0] == "discount")
      { $dquery = "SELECT sp.reduction,sp.reduction_type,sp.from,sp.to";
		$dquery .= " FROM ". _DB_PREFIX_."specific_price sp";
	    $dquery .= " WHERE sp.id_product='".$datarow['id_product']."' AND (sp.id_shop='".$id_shop."' OR sp.id_shop='0') AND (sp.to >= NOW() OR sp.to = '0000-00-00 00:00:00' ) AND sp.id_product_attribute='0'";
		$dquery .= " ORDER BY sp.id_country, sp.id_group, sp.id_customer,sp.id_currency LIMIT 1"; /* order by should put zero's (=all) first */
		$dres=dbquery($dquery);
		if(mysqli_num_rows($dres) > 0)
		{ $drow=mysqli_fetch_array($dres);
		  if($drow["reduction_type"] == "pct")
		  { $csvline[] = "0";
		    $csvline[] = $drow['reduction'];
		  }
		  else
		  { $csvline[] = $drow['reduction'];
			$csvline[] = "0";
		  }
		  $csvline[] = $drow['from'];
		  $csvline[] = $drow['to'];
		}
		else
		{ $csvline[] = "ddd";
		  $csvline[] = "";
		  $csvline[] = "";
		  $csvline[] = "";
		}
		mysqli_free_result($dres);
      }
	  else if ($infofields[$i][0] == "revenue")
      { $csvline[] = $datarow['revenue'].";";
      }
      else if ($infofields[$i][0] == "image")
      { $iquery = "SELECT id_image,cover FROM ". _DB_PREFIX_."image WHERE id_product='".$datarow['id_product']."' ORDER BY cover DESC, position";
		$ires=dbquery($iquery);
		$id_image = 0;
		$xx = 0;
		$imsize = mysqli_num_rows($ires);
		$tmp = "";
		while ($irow=mysqli_fetch_array($ires)) 
		{ $tmp .= $imagebase.'img/p'.getpath($irow['id_image']).'/'.$irow['id_image'].'.jpg';
		  $xx++;
		  if($xx < $imsize)
		    $tmp .= $subseparator;
		}
		$csvline[] = $tmp;
		mysqli_free_result($ires);
      }
      else
         $csvline[] = $myvalue;
	  if(in_array($infofields[$i][0], $statfields))
	    $stattotals[$infofields[$i][0]] += $myvalue;
    }

	foreach($features AS $key => $feature)
	{ if (in_array($feature, $input["fields"]))
	  { if($datarow['value'.$key] == "")
			$csvline[] = "";
		else if($datarow['custom'.$key] == "0")
	  	    $csvline[] = $datarow['value'.$key];
		else // custom = 1
	  	    $csvline[] = $datarow['value'.$key];
	  }
	}
    $x++;
    publish_csv_line($out, $csvline, $separator);
  }
  fclose($out);
  
function publish_csv_line($out, $csvline, $separator)
{ fputcsv($out, $csvline, $separator);
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

/* if fputcsv doesn't work this can be used as alternative. It is one of the options mentioned in the comment section of php.net for fputcsv() */
function fputcsv2 ($fh, array $fields, $delimiter = ',', $enclosure = '"', $mysql_null = false) { 
    $delimiter_esc = preg_quote($delimiter, '/'); 
    $enclosure_esc = preg_quote($enclosure, '/'); 

    $output = array(); 
    foreach ($fields as $field) { 
        if ($field === null && $mysql_null) { 
            $output[] = 'NULL'; 
            continue; 
        } 

        $output[] = preg_match("/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field) ? ( 
            $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure 
        ) : $field; 
    } 

    fwrite($fh, join($delimiter, $output) . "\n"); 
} 

?>
