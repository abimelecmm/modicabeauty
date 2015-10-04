<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;

$rewrite_settings = get_rewrite_settings();

/* get default language: we use this for the categories, manufacturers */
$query="select value, l.name from ". _DB_PREFIX_."configuration f, ". _DB_PREFIX_."lang l";
$query .= " WHERE f.name='PS_LANG_DEFAULT' AND f.value=l.id_lang";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$id_lang = $row['value'];
$def_langname = $row['name'];

/* Get default country for the VAT tables and calculations */
$query="select l.name, id_country from ". _DB_PREFIX_."configuration f, "._DB_PREFIX_."country_lang l";
$query .= " WHERE f.name='PS_COUNTRY_DEFAULT' AND f.value=l.id_country AND l.id_lang='1'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$countryname = $row['name'];
$id_country = $row["id_country"];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Prestashop Product Multiedit</title>
<style>
option.defcat {background-color: #ff2222;}
</style>
<link rel="stylesheet" href="style1.css" type="text/css" />
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<script>
function getElsByClassName(classname){
	var rv = []; 
	var elems  = document.getElementsByTagName('*')
	if (elems.length){
		for (var x in elems ){
			if (elems[x] && elems[x].className && elems[x].className == classname){
				rv.push(elems[x]);
			}
		}
	}
	return rv; 
}


</script>
</head>

<body>
<?php
print_menubar();
echo '<center><b><font size="+1">Discount Overview</font></b></center>';
echo '<table style="width:100%" ><tr><td>';
echo "The following settings were used:<br/>";
echo "Default language=".$def_langname." (used for productnames)";
echo "<br/>Country=".$countryname." (used for VAT grouping and calculations)";
echo "<br/>Date is ".date("d M Y");
echo "<p>This page provides an overview of the special prices in your shop. Those last inserted are shown first. The colored lines are either expired or not yet active. All prices have been rounded at two digits. In the dates the dates and the times are omitted when they are zero.";
echo "</td></tr></table>";


  // "*********************************************************************";

$query = "SELECT DISTINCT(s.id_specific_price), s.reduction,s.id_shop,s.id_currency,s.id_country,s.id_group,s.price AS fromprice,s.from_quantity,s.reduction, s.reduction_type,s.from,s.to, p.id_product,p.price, pl.name,t.rate,c.name AS country, g.name AS groupname,cu.firstname,cu.lastname FROM ". _DB_PREFIX_."specific_price s";
if(_PS_VERSION_ > "1.5")
  $query.=" left join ". _DB_PREFIX_."product_shop p ON p.id_product=s.id_product AND (p.id_shop=s.id_shop OR (s.id_shop='0' AND p.id_shop='1'))";
else
  $query.=" left join ". _DB_PREFIX_."product p ON p.id_product=s.id_product";
$query.=" left join ". _DB_PREFIX_."product_lang pl on p.id_product=pl.id_product AND pl.id_lang='".$id_lang."' AND (pl.id_shop=s.id_shop OR (s.id_shop='0' AND pl.id_shop='1'))";
$query.=" left join ". _DB_PREFIX_."image i on i.id_product=p.id_product and i.cover=1";
//$query.=" left join ". _DB_PREFIX_."category_lang cl on cl.id_category=p.id_category_default AND cl.id_lang='".$id_lang."'";
$query.=" left join ". _DB_PREFIX_."tax_rule tr on tr.id_tax_rules_group=p.id_tax_rules_group AND tr.id_country='".$id_country."'";
$query.=" left join ". _DB_PREFIX_."tax t on t.id_tax=tr.id_tax";
$query.=" left join ". _DB_PREFIX_."tax_lang tl on t.id_tax=tl.id_tax AND tl.id_lang='".$id_lang."'";
$query.=" left join ". _DB_PREFIX_."country_lang c on s.id_country=c.id_country AND c.id_lang='".$id_lang."'";
$query.=" left join ". _DB_PREFIX_."group_lang g on g.id_group=s.id_group AND g.id_lang='".$id_lang."'";
$query.=" left join ". _DB_PREFIX_."customer cu on cu.id_customer=s.id_customer";
$query.=" ORDER BY s.id_specific_price DESC";

  $res=dbquery($query);
  $numrecs2 = mysqli_num_rows($res);
  echo $numrecs2." displayed.";
//echo $query;

  $fields = array("id_product", "name", "VAT", "price", "from-price", "change","newprice","Min.Qu" ,"country","from", "to","group","shop","customer");
  echo '<div id="testdiv"><table id="Maintable" border=1><colgroup id=mycolgroup>';
  foreach($fields AS $field)
     echo '<col></col>'; /* needed for sort */
  echo '</colgroup><thead><tr>';
  $x=0;
  foreach($fields AS $field)
    echo '<th><a href="" onclick="this.blur(); return sortTable(\'offTblBdy\', '.$x++.', 0);" title="'.$field.'">'.$field.'</a></th
>';

  echo "</tr></thead><tbody id='offTblBdy'>"; /* end of header */
 
  $x=0;
  while ($datarow=mysqli_fetch_array($res)) {
    /* Note that trid (<tr> id) cannot be an attribute of the tr as it would get lost with sorting */
	$background = "";
	if(($datarow["from"] != "0000-00-00 00:00:00") && ($datarow["from"] > date("Y-m-d H:i:s")))
	  $background = " style='background-color:#cccc00;'";
	if(($datarow["to"] != "0000-00-00 00:00:00") && ($datarow["to"] < date("Y-m-d H:i:s")))
	  $background = " style='background-color:#00cccc;'";
    echo '<tr'.$background.'>';

    echo '<td>'.$datarow['id_product'].'</td>';
    echo '<td>'.$datarow['name'].'</td>';
    echo '<td>'.($datarow['rate']+0).'</td>';
	$priceVAT = (($datarow['rate']/100) +1) * $datarow['price'];
    echo '<td>'.number_format($datarow['price'],2, '.', '').' / '.number_format($priceVAT,2, '.', '').'</td>';
	if($datarow['fromprice'] > 0)
	{ $frompriceVAT = (($datarow['rate']/100) +1) * $datarow['fromprice'];
	  echo '<td>'.number_format($datarow['fromprice'],2, '.', '').' / '.number_format($frompriceVAT,2, '.', '').'</td>';
	}
	else 
	{ echo '<td></td>';
	  $frompriceVAT = $priceVAT;
	  $fromprice = $datarow['price'];
	}
	if($datarow['reduction_type'] == "amount")
		echo '<td>-'.number_format($datarow['reduction'],2,".","").'</td>';
	else
		echo '<td>-'.($datarow['reduction']*100).'%</td>';
	if ($datarow['reduction_type'] == "amount")
	  $newprice = $frompriceVAT - $datarow['reduction'];
	else 
	  $newprice = $frompriceVAT*(1-$datarow['reduction']);
	$newpriceEX = (1/(($datarow['rate']/100) +1)) * $newprice;
    $newprice = number_format($newprice,2, '.', '');
    $newpriceEX = number_format($newpriceEX,2, '.', '');
    echo '<td>'.$newpriceEX.' / '.$newprice.'</td>';
	echo '<td>'.$datarow['from_quantity'].'</td>';
	echo '<td>'.$datarow['country'].'</td>';
	if($datarow['from'] == "0000-00-00 00:00:00")
	  echo '<td></td>';
	else if(substr($datarow['from'], 11) == "00:00:00")
	  echo '<td>'.substr($datarow['from'],0,10).'</td>';
	else
	  echo '<td>'.$datarow['from'].'</td>';
	if($datarow['to'] == "0000-00-00 00:00:00")
	  echo '<td></td>';
	else if(substr($datarow['to'], 11) == "00:00:00")
	  echo '<td>'.substr($datarow['to'],0,10).'</td>';
	else
	  echo '<td>'.$datarow['to'].'</td>';
	echo '<td>'.$datarow['groupname'].'</td>';
	if($datarow['id_shop'] == '0')
	   echo '<td>All</td>';
	else
	   echo '<td>'.$datarow['id_shop'].'</td>';
	echo '<td>'.$datarow['firstname'].' '.$datarow['lastname'].'</td>';
//	echo '<td>'.$datarow['id_specific_price'].'</td>';
    $x++;
    echo '</tr>';
  }
  
  if(mysqli_num_rows($res) == 0)
	echo "<strong>products not found</strong>";
  echo '</table></form></div>';
  
  echo '<div style="display:block;"><form name=rowform action="product_proc.php" method=post target=tank><table id=subtable></table><input type=hidden name=id_lang value="'.$id_lang.'"></form></div>';

  include "footer.php";
  echo '</body></html>';


/* get subcategories: this function is recursively called */
function get_subcats($cat_id) 
{ global $categories;
  $categories[] = $cat_id;
  if($cat_id == 0) die("You cannot have category with value zero");
  $query="select id_category from ". _DB_PREFIX_."category WHERE id_parent='".mysqli_real_escape_string($conn, $cat_id)."'";
  $res = dbquery($query);
  while($row = mysqli_fetch_array($res))
    get_subcats($row['id_category']);
}

?>
