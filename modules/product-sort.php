<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;
if(!isset($input['id_category']))
   $id_category="1"; /* 1=root */
else 
   $id_category = intval($input['id_category']);
if(!isset($input['id_lang'])) $input['id_lang']="";
if(!isset($input['fields'])) $input['fields']="";
if(!isset($input['startdate'])) $input['startdate']="";
if(!isset($input['enddate'])) $input['enddate']="";

  if(empty($input['fields']))
    $input['fields'] = array("name","VAT","price", "category", "ean", "active", "shortdescription", "image");
  $infofields = array();
  $if_index = 0;
   /* [0]title, [1]keyover, [2]source, [3]display(0=not;1=yes;2=edit;), [4]fieldwidth(0=not set), [5]align(0=default;1=right), [6]sortfield, [7]Editable, [8]table */
  define("HIDE", 0); define("DISPLAY", 1); define("EDIT", 2);  // display
  define("LEFT", 0); define("RIGHT", 1); // align
  define("NO_SORTER", 0); define("SORTER", 1); /* sortfield => 0=no escape removal; 1=escape removal; */
  define("NOT_EDITABLE", 0); define("INPUT", 1); define("TEXTAREA", 2); define("DROPDOWN", 3);   /* title, keyover, source, display(0=not;1=yes;2=edit), fieldwidth(0=not set), align(0=default;1=right), sortfield */
   /* sortfield => 0=no escape removal; 1=escape removal; 2 and higher= escape removal and n lines textarea */
  $infofields[$if_index++] = array("","", "", DISPLAY, 0, LEFT, 0,0,"");
  $infofields[$if_index++] = array("id","", "id_product", DISPLAY, 0, RIGHT, NO_SORTER,NOT_EDITABLE, "p.id_product");
  $infofields[$if_index++] = array("position","", "position", DISPLAY, 0, RIGHT, NO_SORTER,EDIT, "cp.position");
  
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
   "linkrewrite" => array("link_rewrite","", "link_rewrite", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "p.link_rewrite"),
   "metatitle" => array("meta_title","", "meta_title", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "pl.meta_title"),
   "metakeywords" => array("meta_keywords","", "meta_keywords", DISPLAY, 0, RIGHT, NO_SORTER, TEXTAREA, "pl.meta_keywords"),
   "metadescription" => array("meta_description","", "meta_description", DISPLAY, 0, LEFT, NO_SORTER, TEXTAREA, "pl.meta_description"),
   "shipweight" => array("shipweight","", "weight", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "p.weight"),
   "image" => array("image","", "id_image", HIDE, 0, LEFT, 0, 0, ""),
   
   "visits" => array("visits","", "visitcount", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "visitcount"),
   "visitz" => array("visitz","", "visitedpages", DISPLAY, 0, LEFT, NO_SORTER, NOT_EDITABLE, "visitedpages"),
   "salescnt" => array("salescnt","", "salescount", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "salescount"),
   "revenue" => array("revenue","", "revenue", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "revenue"),
   "orders" => array("orders","", "ordercount", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "ordercount"),
   "buyers" => array("buyers","", "buyercount", DISPLAY, 0, LEFT, NO_SORTER, INPUT, "buyercount")
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

if(!isset($input['id_category']))
{ $query="select id_category from ". _DB_PREFIX_."category_lang";
  $query .= " WHERE name='Home'";
  $res=dbquery($query);
  if(mysqli_num_rows($res) > 0)
  { $row = mysqli_fetch_array($res);
    $id_category=$row['id_category'];
  }
}

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
$query .= " WHERE f.name='PS_COUNTRY_DEFAULT' AND f.value=l.id_country ORDER BY id_lang IN('".$id_lang."','1') DESC"; /* the construction with the languages should select all languages with def_lang and '1' first */
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$countryname = $row['name'];
$id_country = $row["id_country"];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Prestashop Product Sort</title>
<link rel="stylesheet" href="style1.css" type="text/css" />
<style>
option.defcat {background-color: #ff2222;}
input.posita {width: 50px; text-align:right}
</style>
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
</head>

<body>
<?php
print_menubar();
echo '<table style="width:100%" ><tr><td width="70%" valign="top">';
echo '<center><b><font size="+1">Product Sort</font></b></center>';
echo "The following setting(s) were derived from your configuration:<br/>";
if($input['id_lang'] == "")
  echo " - language: ".$languagename." (used for productnames)<br/>";
echo " - country: ".$countryname." (used for VAT grouping and calculations)<br/>";
echo "Prestashop stores only one sorting order for a category. In multishop not all informative data may come from the same shop.<br/>";
echo "By default the product will move immediately after you entered the number. You can switch that off with the 'autosort' checkbox. Don't forget to submit to implement your changes!<br>";
echo 'If you have more than one product on a row - like on the standard homepage - you may also try <a href="product-vissort.php">Visual Sort</a></td>';
echo '<td style="text-align:right; width:30%"><iframe name=tank width="230" height="95"></iframe></td></tr></table>';
?>

<table class="triplesearch"><tr><td>
<form name="search_form" method="get" action="product-sort.php">
Select the category in which you want to sort the products: <select name="id_category">
<?php 
    $category_names = array();
	$query=" select * from ". _DB_PREFIX_."category_lang WHERE id_lang=".$id_lang." GROUP BY id_category ORDER BY name";
	$res=dbquery($query);
	while ($category=mysqli_fetch_array($res)) {
		if ($category['id_category']==$id_category) {$selected=' selected="selected" ';} else $selected="";
	        echo '<option  value="'.$category['id_category'].'" '.$selected.'>'.$category['name'].'</option>';
		 $category_names[$category['id_category']] = $category['name'];	
	}
	echo '</select>';
	
	$res=dbquery("SELECT COUNT(*) AS rcount from ". _DB_PREFIX_."product");
	$row = mysqli_fetch_array($res);
	$totcount = $row["rcount"];
	echo '<br/>Total '.$totcount.' products.';
	echo ' &nbsp &nbsp; Language: <select name="id_lang" style="margin-top:5px">';
	  $query=" select * from ". _DB_PREFIX_."lang ";
	  $res=dbquery($query);
	  while ($language=mysqli_fetch_array($res)) {
		$selected='';
	  	if ($language['id_lang']==$id_lang) $selected=' selected="selected" ';
	        echo '<option  value="'.$language['id_lang'].'" '.$selected.'>'.$language['name'].'</option>';
	  }
	echo '</select><hr/>';

	echo '<table><tr>';
	$checked = in_array("name", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="name" '.$checked.' /> Name</td>';
	$checked = in_array("VAT", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="VAT" '.$checked.' /> VAT</td>';
	$checked = in_array("priceVAT", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="priceVAT" '.$checked.' /> priceVAT</td>';
	$checked = in_array("reference", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="reference" '.$checked.' /> Reference</td>';
	$checked = in_array("category", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="category" '.$checked.' /> Category</td>';
	$checked = in_array("description", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="description" '.$checked.' /> Description</td>';
	$checked = in_array("shortdescription", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="shortdescription" '.$checked.' /> Short Desc</td>';
	$checked = in_array("active", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="active" '.$checked.' /> Active</td>';
	$checked = in_array("onsale", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="onsale" '.$checked.' /> On Sale</td>';
	$checked = in_array("image", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="image" '.$checked.' /> Image</td>';	
	echo '</tr><tr>';
	$checked = in_array("quantity", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="quantity" '.$checked.' /> Qty</td>';	
	$checked = in_array("price", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="price" '.$checked.' /> Price</td>';
	$checked = in_array("linkrewrite", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="linkrewrite" '.$checked.' /> Link-rewrite</td>';
	$checked = in_array("shipweight", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="shipweight" '.$checked.' />Sh. Weight</td>';
	$checked = in_array("ean", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="ean" '.$checked.' /> ean</td>';
	$checked = in_array("manufacturer", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="manufacturer" '.$checked.' /> Manufacturer</td>';
	$checked = in_array("metatitle", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="metatitle" '.$checked.' /> Metatitle</td>';
	$checked = in_array("metakeywords", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="metakeywords" '.$checked.' /> Metakeys</td>';
	$checked = in_array("metadescription", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="metadescription" '.$checked.' /> Meta Desc</td>';
	$checked = in_array("features", $input["fields"]) ? "checked" : "";
	echo '<!--td><input type="checkbox" name="fields[]" value="features" '.$checked.' onchange="swapFeatures()" /> Features</td-->';
	echo '</tr><tr>';
	
	echo '<td colspan=4>Statistics: Period (yyyy-mm-dd): <input size=5 name=startdate value='.$input['startdate'].'> till <input size=5 name=enddate value='.$input['enddate'].'></td>';
	$checked = in_array("visits", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="visits" '.$checked.' /> Visits</td>';
	$checked = in_array("visitz", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="visitz" '.$checked.' /> Visitz</td>';
	$checked = in_array("salescnt", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="salescnt" '.$checked.' /> Sold</td>';
	$checked = in_array("revenue", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="revenue" '.$checked.' /> Revenue</td>';
	$checked = in_array("orders", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="orders" '.$checked.' /> Orders</td>';	
	$checked = in_array("buyers", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="buyers" '.$checked.' /> Buyers</td>';
	echo '</tr></table></td><td><input type="submit" value="search" /></form></td>';
	echo '</tr></table></form>';
	
  // "*********************************************************************";
  echo '<form name=ListForm><table class="tripleswitch"><tr><td>';
  echo "<input type=button value='Sort' onClick='return CatSort();' title='Sort on number in position field'>";
  echo " &nbsp; &nbsp; <input type=button value='Number' onClick='return CatNumber();' title='Give new position numbers'>";
  echo " &nbsp; &nbsp; <input type=button value='Randomize' onClick='return Randomize();' title='Give random position numbers'>";
  $checked = "";
  if(isset($autosort) && $autosort)
    $checked = "checked";
  echo ' &nbsp; &nbsp; autosort <input type=checkbox name=autosort '.$checked.' onchange="switch_autosort()"></td>';
  echo "<td width='40%' align=center><input type=checkbox name=verbose>verbose &nbsp; &nbsp; <input type=button value='Submit all' onClick='return SubmitForm();'></td></tr></table></form>";
  // "*********************************************************************";
  
/* Note: we start with the query part after "from". */
$statterms = "";
$querysel = "select p.*,ps.*,pl.*,t.id_tax,t.rate,i.id_image,m.name AS manufacturer, cl.name AS catname, cl.link_rewrite AS catrewrite, cp.position"; /* note: you cannot write here t.* as t.active will overwrite p.active without warning */
$query = " from ". _DB_PREFIX_."product p left join ". _DB_PREFIX_."product_lang pl on p.id_product=pl.id_product AND pl.id_lang='".(int)$id_lang."'";
$query.=" inner join ". _DB_PREFIX_."product_shop ps on ps.id_product=p.id_product";
$query.=" left join ". _DB_PREFIX_."image i on i.id_product=p.id_product and i.cover=1";
$query.=" left join ". _DB_PREFIX_."manufacturer m on m.id_manufacturer=p.id_manufacturer";
$query.=" left join ". _DB_PREFIX_."category_lang cl on cl.id_category=ps.id_category_default AND cl.id_lang='".(int)$id_lang."'";
$query.=" left join ". _DB_PREFIX_."tax_rule tr on tr.id_tax_rules_group=ps.id_tax_rules_group AND tr.id_country='".(int)$id_country."'";
$query.=" left join ". _DB_PREFIX_."tax t on t.id_tax=tr.id_tax";
$query.=" left join ". _DB_PREFIX_."tax_lang tl on t.id_tax=tl.id_tax AND tl.id_lang='".(int)$id_lang."'";
$query.=" left join ". _DB_PREFIX_."category_product cp on p.id_product=cp.id_product";
if(in_array("visits", $input["fields"]))
{ $query .= " LEFT JOIN ( SELECT pg.id_object, count(*) AS visitcount FROM ". _DB_PREFIX_."connections c LEFT JOIN ". _DB_PREFIX_."page pg ON pg.id_page_type='1' AND pg.id_page = c.id_page";
  if($input['startdate'] != "")
    $query .= " AND TO_DAYS(c.date_add) > TO_DAYS('".$input['startdate']."')";
  if($input['enddate'] != "")
    $query .= " AND TO_DAYS(c.date_add) < TO_DAYS('".$input['enddate']."')";
  $statterms .= ", visitcount ";
  $query .= " GROUP BY pg.id_object ) v ON p.id_product=v.id_object";
}
if(in_array("visitz", $input["fields"]))
{ $query .= " LEFT JOIN ( SELECT pg.id_object, sum(counter) AS visitedpages FROM ". _DB_PREFIX_."page_viewed v LEFT JOIN ". _DB_PREFIX_."page pg ON pg.id_page_type='1' AND pg.id_page = v.id_page";
  $query .= " LEFT JOIN ". _DB_PREFIX_."date_range d ON d.id_date_range = v.id_date_range";
  if($input['startdate'] != "")
    $query .= " AND TO_DAYS(d.time_start) >= TO_DAYS('".$input['startdate']."')";
  if($input['enddate'] != "")
    $query .= " AND TO_DAYS(d.time_end) <= TO_DAYS('".$input['enddate']."')";
  $statterms .= ", visitedpages ";
  $query .= " GROUP BY v.id_page ) w ON p.id_product=w.id_object";
}
if(in_array("revenue", $input["fields"]) OR in_array("salescnt", $input["fields"]) OR in_array("orders", $input["fields"]))
{ $query .= " LEFT JOIN ( SELECT product_id, SUM(product_quantity)-SUM(product_quantity_return) AS quantity, ";
  $query .= " ROUND(SUM(total_price_tax_incl),2) AS revenue";
  $query .= ", count(DISTINCT d.id_order) AS ordercount, count(DISTINCT o.id_customer) AS buyercount FROM ". _DB_PREFIX_."order_detail d";
  $query .= " LEFT JOIN ". _DB_PREFIX_."orders o ON o.id_order = d.id_order";
  $query .= " WHERE true";
  if($input['startdate'] != "")
    $query .= " AND TO_DAYS(o.date_add) > TO_DAYS('".$input['startdate']."')";
  if($input['enddate'] != "")
    $query .= " AND TO_DAYS(o.date_add) < TO_DAYS('".$input['enddate']."')";
  $query .= " AND o.valid=1";
  $query .= " GROUP BY d.product_id ) r ON p.id_product=r.product_id";
  $statterms .= ", revenue, r.quantity AS salescount, ordercount, buyercount ";
}
  $query .= " WHERE cp.id_category=".$id_category;
  $query.= " GROUP BY ps.id_product";
  $query .= " ORDER BY position";
  $query = $querysel.$statterms.$query;
  $res=dbquery($query);
  $numrecs2 = mysqli_num_rows($res);
  echo "This category contains ".$numrecs2." products.<br/>";
  $previous_order = array();
  $highestposition = 0;
// echo $query;

echo "<script>
function SubmitForm()
{ var reccount = ".$numrecs2.";
  formSubmitting = true; /* prevent error message for leaving page with unsaved changes */
  CatSort();
  var tabbody = document.getElementById('offTblBdy');
  for(var i=0; i<reccount; i++)
  { if(previous_order[i] == tabbody.childNodes[i].childNodes[0].childNodes[0].value) /* remove unchanged row positions */
	{ tabbody.childNodes[i].innerHTML = '';
	  continue;
	}
    tabbody.childNodes[i].childNodes[0].childNodes[0].name = 'id_product'+i; /* change hidden input field names */
    tabbody.childNodes[i].childNodes[2].innerHTML = ''; /* remove position field: we get position from fieldnames like id_product15 */
  }							/* reducing the number of fields doubles the number of records that can be updated until nearly 1000 */
  Mainform.verbose.value = ListForm.verbose.checked;
  Mainform.action = 'product-proc.php';
  Mainform.submit();
}

function CatNumber()
{ rv = document.getElementsByClassName('posita');
  var length = rv.length;
  for(var i=0; i<length; i++)
  { rv[i].value = i;
  }
}

function Randomize()
{ rv = document.getElementsByClassName('posita');
  var length = rv.length;
  var arr = new Array;
  for(var i=0; i<length; i++)
    arr[i] = i;
  shuffle(arr);
  for(var i=0; i<length; i++)
  { rv[i].value = arr[i];
  }
  sortTable('offTblBdy', 2, 2);
}

function CatSort()
{ sortTable('offTblBdy', 2, 2);
  CatNumber();
}

function ChangePosition(elt)
{ if(ListForm.autosort.checked)
  { var tmp = elt.parentNode.parentNode.innerHTML;
  	var mytable = document.getElementById('Maintable');
	if(isNaN(elt.value))
	  var newrow = 1;
	else
	  var newrow = parseInt(elt.value) + 1;
	if(newrow >= mytable.rows.length)
	  newrow = mytable.rows.length-1;
    var row = elt.parentNode.parentNode.rowIndex;
	mytable.deleteRow(row);
	var myrow = mytable.insertRow(newrow);
	myrow.innerHTML = tmp;
	CatNumber();
  }
}

function moveup(elt)
{ var pos = elt.parentNode.parentNode.rowIndex;
  if(pos > 1)
  { var tmp = elt.parentNode.parentNode.innerHTML;
  	var mytable = document.getElementById('Maintable');
	mytable.rows[pos].innerHTML = mytable.rows[pos-1].innerHTML;
	mytable.rows[pos-1].innerHTML = tmp;
	CatNumber();
  }
}

function movedown(elt)
{ var pos = elt.parentNode.parentNode.rowIndex;
  var mytable = document.getElementById('Maintable');
  if(pos <= (mytable.rows.length - 1))
  { var tmp = elt.parentNode.parentNode.innerHTML;
	mytable.rows[pos].innerHTML = mytable.rows[pos+1].innerHTML;
	mytable.rows[pos+1].innerHTML = tmp;
	CatNumber();
  }
}

function switch_autosort()
{
}

function salesdetails(product)
{ window.open('product-sales.php?product='+product+'&startdate=".$input['startdate']."&enddate=".$input['enddate']."','', 'resizable,scrollbars,location,menubar,status,toolbar');
  return false;
}

/* the following functions shows a warning when you leave the page while there are unsaved changes */
var formSubmitting = false;
window.onload = function() {
    window.addEventListener('beforeunload', function (e) {
		var reccount = ".$numrecs2.";
        var confirmationMessage = 'You did not submit your changes. ';
        confirmationMessage += 'If you leave before saving, your changes will be lost.';
		
		if (formSubmitting) {
            return undefined;
        }

		var tabbody = document.getElementById('offTblBdy');
		var changed = 0;
		for(var i=0; i<reccount; i++)
		{ if(previous_order[i] != tabbody.childNodes[i].childNodes[0].childNodes[0].value) /* anything changed? */
			changed = 1;
		}
		if(changed == 0)
			return;

        (e || window.event).returnValue = confirmationMessage; //Gecko + IE
        return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
    });
};

</script>";
  // "*********************************************************************";

  echo '<form name="Mainform" method=post><input type=hidden name=reccount value="'.$numrecs2.'"><input type=hidden name=id_lang value="'.$id_lang.'">';
  echo '<input type="hidden" name="id_category" value="'.$id_category.'">';
  echo '<input type=hidden name=verbose>';
  echo '<div id="testdiv"><table id="Maintable" class="triplemain"><colgroup id="mycolgroup">';
  for($i=0; $i<sizeof($infofields); $i++)
  { $align = $visibility = $classname = "";
    if($infofields[$i][5] == 1)
      $align = ' style="text-align:right"';
	if($infofields[$i][0] == "position")
	  $classname = ' class="numeric"';
	if($infofields[$i][0] == "name")
      $classname = ' class="namecol"';
    echo "<col id='col".$i."'".$align.$visibility.$classname."></col>";
  }

  echo "</colgroup><thead><tr>";

  $statsfound = false; /* flag whether we should create an extra stats totals line */
  $statfields = array("salescnt", "revenue","orders","buyers","visits","visitz");
  $stattotals = array();
  for($i=0; $i<sizeof($infofields); $i++)
  { $reverse = "false";
    if($infofields[$i][0] == "active")
	  $reverse = 1;
    else if (in_array($infofields[$i][0], $statfields))
	{ $reverse = 1;
      $statsfound = true;
	  $stattotals[$infofields[$i][0]] = floatval(0);
	}
    echo '<th><a href="" onclick="this.blur(); sortTable(\'offTblBdy\', '.$i.', '.$reverse.'); CatNumber(); return false;" title="'.$infofields[$i][1].'">'.$infofields[$i][0].'</a></th
>';
  }


echo '<th><a href="" onclick="this.blur(); return upsideDown(\'offTblBdy\');" title="Upside down: reverse table order"><img src="upsidedown.jpg"></a></th>';
  echo "</tr></thead><tbody id='offTblBdy'>"; /* end of header */

 
  $x=0;
  while ($datarow=mysqli_fetch_array($res)) { 
    /* Note that trid (<tr> id) cannot be an attribute of the tr as it would get lost with sorting */
    echo '<tr><td id="trid'.$x.'" changed="0"><input type=hidden name="id_product'.$x.'" value="'.$datarow['id_product'].'"></td>';
	$previous_order[$datarow["position"]] = $datarow['id_product'];
	$infofieldsize = sizeof($infofields);
    for($i=1; $i< $infofieldsize; $i++)
    { $sorttxt = "";
      $color = "";
      if($infofields[$i][2] == "priceVAT")
		$myvalue =  number_format(((($datarow['rate']/100) +1) * $datarow['price']),2, '.', '');
      else
        $myvalue = $datarow[$infofields[$i][2]];
      if($i==0)
		echo "<td></td>";
      else if($i == 1) /* id */
	  { if ($rewrite_settings == '1')
          echo "<td><a href='../".$datarow['catrewrite']."/".$myvalue."-demo.html' target='_blank'>".addspaces($myvalue)."</a></td>";
		else
          echo "<td><a href='../product.php?id_product=".$myvalue."' target='_blank'>".addspaces($myvalue)."</a></td>";
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
	  }
      else if ($infofields[$i][0] == "VAT")
      { $sorttxt = "idx='".$datarow['id_tax_rules_group']."'";
		echo "<td ".$sorttxt.">".(float)$myvalue."</td>";
      }
      else if ($infofields[$i][0] == "image")
      { echo "<td>".get_product_image($datarow['id_image'],'')."</td>";
      }
	  else if ($infofields[$i][0] == "position")
      { echo "<td><input name='position".$x."' value='".$myvalue."' class='posita' onchange='ChangePosition(this)'></td>";
	    $highestposition = $myvalue;
      }
	  else if ($infofields[$i][0] == "revenue")
      { echo "<td><a href onclick='return salesdetails(".$datarow['id_product'].")' title='show salesdetails'>".$datarow['revenue']."</a></td>";
      }
      else
         echo "<td>".$myvalue."</td>";
		 
	  if ( (in_array($infofields[$i][0], $statfields)))
		$stattotals[$infofields[$i][0]] += floatval($myvalue);
    }

    echo '<td><img src=up.png onclick="moveup(this);"><br><img src=mid.png><br><img src=down.png onclick="movedown(this);"></td>';
    $x++;
    echo '</tr>';
  }
  
  if(mysqli_num_rows($res) == 0)
	echo "<strong>products not found</strong>";
  echo '</table></form></div>
  <script>var previous_order = [';
  for($i=0; $i<=$highestposition; $i++) /* note that PS positions are not always continguous numbers. Note also that Javascript doesn't allow associative arrays */
  { if(isset($previous_order[$i]))
	  echo $previous_order[$i];
	else
	  echo "0";
	if($i != $highestposition) 
	  echo ",";
  }
  echo "];</script> ";
  
  if($statsfound)
  { echo '<table class=triplemain><td colspan=2 style="text-align:center">Totals</td>';
    for($i=0; $i< sizeof($infofields); $i++)
	{ if (in_array($infofields[$i][0], $statfields))
	    echo '<tr><td>'.$infofields[$i][0].'</td><td>'.$stattotals[$infofields[$i][0]].'</td></tr>';
	}
	echo '</table>';
  }
 
  include "footer.php";
  echo '</body></html>';

?>
