<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;
if(!isset($input['search_txta'])) $input['search_txta'] = "";
if(!isset($input['search_txtb'])) $input['search_txtb'] = "";
if(!isset($input['search_fld']) || ($input['search_fld'] == "")) $input['search_fld'] = "main fields";
if(!isset($input['startrec']) || (trim($input['startrec']) == '')) $input['startrec']="0";
if(!isset($input['numrecs'])) $input['numrecs']="100";
if(!isset($input['order'])) $input['order']="id_product"; /* sort by product */
if(!isset($input['id_category'])) $input['id_category']="0";
if(!isset($input['listcats'])) $input['listcats']="";
if(!isset($input['id_lang'])) $input['id_lang']="";
if(!isset($input['fields'])) $input['fields']="";

  $id_shop = $input['id_shop'];
  if(empty($input['fields']))
    $input['fields'] = array("name","VAT","price", "category", "ean", "description", "shortdescription", "image");
  $infofields = array();
  $if_index = 0;
   /* [0]title, [1]keyover, [2]source, [3]display(0=not;1=yes;2=edit;), [4]fieldwidth(0=not set), [5]align(0=default;1=right), [6]sortfield, [7]Editable, [8]table */
  define("HIDE", 0); define("DISPLAY", 1); define("EDIT", 2);  // display
  define("LEFT", 0); define("RIGHT", 1); // align
  define("NO_SORTER", 0); define("SORTER", 1); /* sortfield => 0=no escape removal; 1=escape removal; */
  define("NOT_EDITABLE", 0); define("INPUT", 1); define("TEXTAREA", 2); define("DROPDOWN", 3);   /* title, keyover, source, display(0=not;1=yes;2=edit), fieldwidth(0=not set), align(0=default;1=right), sortfield */
   /* sortfield => 0=no escape removal; 1=escape removal; 2 and higher= escape removal and n lines textarea */
  $infofields[$if_index++] = array("","", "", DISPLAY, 0, LEFT, 0,0);
  $infofields[$if_index++] = array("id","", "id_product", DISPLAY, 0, RIGHT, NO_SORTER,NOT_EDITABLE);
  
  $field_array = array(
   "name" => array("name","", "name", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "pl.name"),
   "active" => array("active","", "active", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "p.active"),
   "reference" => array("reference","", "reference", DISPLAY, 200, LEFT, NO_SORTER, INPUT, "pl.reference"),
   "ean" => array("ean","", "ean13", DISPLAY, 200, LEFT, NO_SORTER, INPUT, "p.ean"),
   "category" => array("category","", "id_category_default", DISPLAY, 0, LEFT, NO_SORTER, NOT_EDITABLE, "p.id_category_default"),
   "price" => array("price","", "price", DISPLAY, 200, LEFT, NO_SORTER, INPUT, "p.price"),
   "VAT" => array("VAT","", "rate", DISPLAY, 0, LEFT, NO_SORTER, DROPDOWN, ""),
   "priceVAT" => array("priceVAT","", "priceVAT", DISPLAY, 0, LEFT, NO_SORTER, INPUT, ""),
   "quantity" => array("qty","", "quantity", DISPLAY, 0, LEFT, NO_SORTER, TEXTAREA, "p.quantity"),
   "shortdescription" => array("description_short","", "description_short", DISPLAY, 0, LEFT, NO_SORTER, TEXTAREA, "pl.short_description"),
   "description" => array("description","", "description", DISPLAY, 0, LEFT, NO_SORTER, TEXTAREA, "pl.description"),
   "manufacturer" => array("manufacturer","", "manufacturer", DISPLAY, 0, LEFT, NO_SORTER, DROPDOWN, "m.name"),
   "link_rewrite" => array("link_rewrite","", "link_rewrite", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "p.link_rewrite"),
   "metatitle" => array("meta_title","", "meta_title", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "pl.meta_title"),
   "metakeywords" => array("meta_keywords","", "meta_keywords", DISPLAY, 0, RIGHT, NO_SORTER, TEXTAREA, "pl.meta_keywords"),
   "metadescription" => array("meta_description","", "meta_description", DISPLAY, 0, LEFT, NO_SORTER, TEXTAREA, "pl.meta_description"),
   "image" => array("image","", "id_image", HIDE, 0, LEFT, 0, 0, "")
   ); 
   
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

/* Get default language if none provided */
if($input['id_lang'] == "") {
	$query="select value, l.name from ". _DB_PREFIX_."configuration f, ". _DB_PREFIX_."lang l";
	$query .= " WHERE f.name='PS_LANG_DEFAULT' AND f.value=l.id_lang";
	$res=dbquery($query);
	$row = mysqli_fetch_array($res);
	$id_lang = $row['value'];
	$languagename = $row['name'];
}
else
  $id_lang = $input['id_lang'];

/* Get default country for the VAT tables and calculations */
$query="select l.name, id_country from ". _DB_PREFIX_."configuration f, "._DB_PREFIX_."country_lang l";
$query .= " WHERE f.name='PS_COUNTRY_DEFAULT' AND f.value=l.id_country AND l.id_lang='1'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$countryname = $row['name'];
$id_country = $row["id_country"];

/* Make category table for names */
$query="select name, c.id_category from ". _DB_PREFIX_."category c, ". _DB_PREFIX_."category_lang l WHERE c.id_category=l.id_category AND l.id_lang='".$id_lang."'";
$res=dbquery($query);
$category_names = array();
while($row = mysqli_fetch_array($res))
  $category_names[$row['id_category']] = $row['name'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Prestashop Product-List</title>
<style>
<?php
  if (isset($input['listdefault'])) 
  { echo "body{ font:normal 90%, Verdana, sans-serif; line-height: 100%; }
  td {vertical-align: top; padding:0;}
 table { border-spacing:0; border-collapse:collapse;}
table tr td { vertical-align: top}
table tr td table tr td table { width: 65px; overflow: hidden; } ";
  $input['listlines'] = 21;
  }
  else
  { echo "td {vertical-align: top; padding:0;}
table { border-spacing:0; border-collapse:collapse;} ";
  }
?>
</style>
</head>
<body>
<?php

/* get subcategories: this function is recursively called */
function get_subcats($cat_id) 
{ global $categories;
  $categories[] = $cat_id;
  if($cat_id == 0) die("You cannot have category with value zero");
  $query="select id_category from ". _DB_PREFIX_."category WHERE id_parent='".mysqli_real_escape_string($cat_id)."'";
  $res = dbquery($query);
  while($row = mysqli_fetch_array($res))
    get_subcats($row['id_category']);
}

if($input['listcats']!="")
{ $cl_parts = explode(",", $input['listcats']);
  foreach($cl_parts AS $clp)
  { if(stripos($clp,'s'))
      get_subcats(str_replace('s','',$clp));
	else
	  $categories[] = $clp;
	if($clp == 0) die("You cannot have category zero");
  }
  $input['startrec'] = 0;
  $input['numrecs'] = 99999;
}
else 
  $categories = array($input['id_category']);

$products_done = array();
$x=0; $col=1; $page=1;
echo '<table><tr><td style="width:33%">';
foreach($categories AS $selcat)
{ $head = "List: ";
  if ($selcat!="0") {
	$head .= $selcat."-".$category_names[$selcat];
  }
  else $head .= "All categories";

  if(($input['search_txta'] != '') || ($input['search_txtb'] != ''))
    $head .= ": (".$input['search_txta'].",".$input['search_txtb'].")";
  if($input['listcats']=="")
    $head .= " - ".$input['startrec'].",".$input['numrecs'];

  $searchtext = "";
  if ($input['search_txta'] != "") 
  {  if($input['search_fld'] == "main fields") 
       $searchtext .= " AND (p.reference like '%".$input['search_txta']."%' or p.supplier_reference like '%".$input['search_txta']."%' or pl.name like '%".$input['search_txta']."%') ";
     else
       $searchtext .= " AND ".$input['search_fld']." like '%".$input['search_txta']."%' ";
  }
  if ($input['search_txtb'] != "")
  {  if($input['search_fld'] == "main fields") 
       $searchtext .= " AND (p.reference like '%".$input['search_txtb']."%' or p.supplier_reference like '%".$input['search_txtb']."%' or pl.name like '%".$input['search_txtb']."%') ";
     else
       $searchtext .= " AND ".$input['search_fld']." like '%".$input['search_txtb']."%' ";
  }
  if ($id_lang!="") $langtext=' and pl.id_lang='.$id_lang.' and tl.id_lang='.$id_lang;
  if(($selcat == 0) && ($input['order']=="position")) /* no position when displaying all products */
    $input['order']="id_product";
  if ($input['order']=="id_product") $order="p.id_product";
  else if ($input['order']=="name") $order="pl.name";
  else if ($input['order']=="position") $order="cp.position";
  else if ($input['order']=="VAT") $order="t.rate";
  else if ($input['order']=="image") $order="i.cover";

  $catseg1=$catseg2="";
  if ($selcat != 0) {
	$catseg1=" left join ". _DB_PREFIX_."category_product cp on p.id_product=cp.id_product";
	$catseg2=" AND cp.id_category=".$selcat;
  }

  /* Note: we start with the query part after "from". First we count the total and then we take 100 from it */
  $query =" from ". _DB_PREFIX_."product_shop ps left join ". _DB_PREFIX_."product p on p.id_product=ps.id_product";
  $query.=" left join ". _DB_PREFIX_."product_lang pl on p.id_product=pl.id_product AND pl.id_lang='".$id_lang."'";
  $query.=" left join ". _DB_PREFIX_."image i on i.id_product=p.id_product and i.cover=1";
  $query.=" left join ". _DB_PREFIX_."manufacturer m on m.id_manufacturer=p.id_manufacturer";
  $query.=" left join ". _DB_PREFIX_."category_lang cl on cl.id_category=ps.id_category_default AND cl.id_lang='".$id_lang."' AND pl.id_shop=cl.id_shop";
  $query.=" left join ". _DB_PREFIX_."tax_rule tr on tr.id_tax_rules_group=ps.id_tax_rules_group AND tr.id_country='".$id_country."'";
  $query.=" left join ". _DB_PREFIX_."tax t on t.id_tax=tr.id_tax";
  $query.=" left join ". _DB_PREFIX_."tax_lang tl on t.id_tax=tl.id_tax AND tl.id_lang='".$id_lang."'";
  $query.=$catseg1;
  $query.=" where ps.id_shop='".$id_shop."'";
  if(($searchtext != "") || ($catseg2 != ""))
    $query.= $searchtext.$catseg2;
  $query .= " GROUP BY p.id_product";
  // echo $query;
  $res=dbquery("SELECT COUNT(*) AS rcount ".$query);
  $row = mysqli_fetch_array($res);
  $numrecs = $row['rcount'];
  //echo "Your search delivered ".$numrecs." records. ";

  $query .= " ORDER BY ".$order." LIMIT ".$input['startrec'].",".$input['numrecs'];
  $query = "select p.*,pl.*,t.id_tax,t.rate,i.id_image,m.name AS manufacturer, cl.name AS catname, cl.link_rewrite AS catrewrite ".$query; /* note: you cannot write here t.* as t.active will overwrite p.active without warning */
  $res=dbquery($query);
 // echo $query."<br/>".$numrecs." records. ";

  $numrecs2 = mysqli_num_rows($res);
  
  echo $head.' page '.$page.'<table>';
  $x++;
  while ($datarow=mysqli_fetch_array($res)) {
    if(in_array($datarow['id_product'],$products_done))  /* this is for the include subdirectories option that can include the same product more than once */
	  continue;
	$producst_done[] = $datarow['id_product'];
    echo '<tr>';
	if(isset($input['listdefault'])) 
	{ echo '<td><table><tr><td><nobr>'.substr($datarow['name'],0,26).'</nobr><br>';
	  echo '<nobr>';
	  $len = strlen(substr($datarow['name'],26));	  
	  if($len > 0)
	  { echo substr($datarow['name'],26)." ";
	    $len++;
	  }
	  echo substr(strip_tags($datarow['description_short']),0,(26-$len)).'</nobr><br>';
	  echo '<nobr>';
	  if ($rewrite_settings == '1')
        echo "<a href='../".$datarow['catrewrite']."/".$datarow['id_product']."-demo.html' target='_blank'>".$datarow['id_product']."</a>";
	  else
        echo "<a href='../product.php?id_product=".$datarow['id_product']."' target='_blank'>".$datarow['id_product']."</a>";
	  echo ' '.$datarow['active'].' '.number_format(((($datarow['rate']/100) +1) * $datarow['price']),2, '.', '').'</nobr></td>';
	  echo '<td>'.get_product_image($datarow['id_image'], "").'</td></tr>';

	  echo '</table></td>';
	  echo '</tr>';
	  $x++;
	  if($x>=$input['listlines'])
	  { if($col++ < $input['listcols'])
	      echo '</table></td><td style="width:33%"><table>';
	    else
	    { echo '</table></td></tr></table>';
	      for($i=0; $i< $input['listseps']; $i++)
		    echo '<br/>';
//		  echo chr(12); /* form feed doesn't work */
		  echo '<table><tr><td style="width:33%">'.$head.' Page '.++$page.'<table>';;
		  $col = 1;
	    }
	    $x=0;
	  }
	  continue;  
	} /* too lazy for an "else" */
	for($i=1; $i< sizeof($infofields); $i++)
    { $sorttxt = "";
      $color = "";
      if($infofields[$i][2] == "priceVAT")
		$myvalue =  number_format(((($datarow['rate']/100) +1) * $datarow['price']),2, '.', '');
      else
        $myvalue = $datarow[$infofields[$i][2]];
	  if($i == 1) /* id */
	  { $start = "";
	    $xdebug = ""; //"-".$x;
	    if($col > 0) $start= '&nbsp;';
	    if ($rewrite_settings == '1')
          echo "<td>".$start."<a href='../".$datarow['catrewrite']."/".$myvalue."-demo.html' target='_blank'>".addspaces($myvalue).$xdebug."</a></td>";
		else
          echo "<td>".$start."<a href='../product.php?id_product=".$myvalue."' target='_blank'>".addspaces($myvalue).$xdebug."</a></td>";
	  }
	  else if($infofields[$i][6] == 1)
      { $sorttxt = "srt='".str_replace("'", "\'",$myvalue)."'";
        echo "<td ".$sorttxt.">".$myvalue."</td>";
      }
      else if ($infofields[$i][0] == "category")
		echo "<td ".$sorttxt."><a title='".$datarow['catname']."' href='#' onclick='return false;'>".$myvalue."</a></td>";
      else if ($infofields[$i][0] == "VAT")
      { $sorttxt = "srt='".$datarow['id_tax_rules_group']."'";
		echo "<td>".$datarow['rate']."</td>";
      }
      else if ($infofields[$i][0] == "price")
      { echo "<td>".number_format($datarow['price'],2, '.', '')."</td>";
	  }
      else if ($infofields[$i][0] == "image")
      { echo "<td>".get_product_image($datarow['id_image'], "")."</td>";
      }
      else
         echo "<td>&nbsp;".strip_tags($myvalue)."</td>";
    }
    echo '</tr>';
	$x++;
	if($x>=$input['listlines'])
	{ if($col++ < $input['listcols'])
	    echo '</table></td><td><table>';
	  else
	  { echo '</table></td></tr></table>';
	    for($i=0; $i< $input['listseps']; $i++)
		  echo '<br/>';
		echo '<table><tr><td>'.$head.' Page '.++$page.'<table>';;
		$col = 1;
	  }
	  $x=0;
	}
  }
  if(mysqli_num_rows($res) == 0)
	echo "<strong>products not found</strong>";
  echo '</table>';
}
echo '</table></td></tr></table>';
echo '</body></html>';

?>
