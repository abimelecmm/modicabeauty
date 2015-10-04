<?php 
/* This script - part of Triple Edit - lists the revenues for each category within a certain period */
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;
if(!isset($input['id_shop'])) $input['id_shop']="0";
$id_shop = intval($input["id_shop"]);
if(!isset($input['startdate']) || (!check_mysql_date($input['startdate'])))
	$input['startdate']="";
if(!isset($input['enddate']) || (!check_mysql_date($input['enddate'])))
	$input['enddate']="";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Prestashop Ordered Products</title>
<link rel="stylesheet" href="style1.css" type="text/css" />
<script src="http://tinymce.cachefly.net/4.0/tinymce.min.js"></script> <!-- Prestashop settings can be found at /js/tinymce.inc.js -->
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<?php echo '<script type="text/javascript">
	function salesdetails(product)
	{ window.open("product-sales.php?product="+product+"&startdate='.$input["startdate"].'&enddate='.$input["enddate"].'&id_shop='.$id_shop.'","", "resizable,scrollbars,location,menubar,status,toolbar");
      return false;
    }
</script>
</head><body>';
print_menubar();
echo '<h1>Prestashop Category revenue</h1>';

echo '<form name="search_form" method="get">
Period (yyyy-mm-dd): <input size=5 name=startdate value='.$input['startdate'].'> till <input size=5 name=enddate value='.$input['enddate'].'> &nbsp; ';

/* making shop block */
	$query= "select id_shop,name from ". _DB_PREFIX_."shop ORDER BY id_shop";
	$res=dbquery($query);
	echo " &nbsp; Shop: <select name=id_shop><option value=0>All shops</option>";
	while ($shop=mysqli_fetch_array($res)) 
	{   $selected = "";
	    if($shop["id_shop"] == $id_shop) $selected = " selected";
        echo '<option  value="'.$shop['id_shop'].'" '.$selected.'>'.$shop['id_shop']."-".$shop['name'].'</option>';
	}	
    echo '</select><input type=submit></form>';

	$query="select value from ". _DB_PREFIX_."configuration WHERE name='PS_LANG_DEFAULT'";
	$res=dbquery($query);
	$row = mysqli_fetch_array($res);
	$id_lang = $row['value'];
	
	$order_states = array(2,3,4,5);
	echo "<p>Orders with the following states have been included: ";
	$comma = "";
	foreach($order_states AS $order_state)
	{ $osquery="select name from ". _DB_PREFIX_."order_state_lang WHERE id_order_state='".$order_state."'";
	  $osres=dbquery($osquery);
	  $osrow = mysqli_fetch_array($osres);
	  echo $comma.$osrow['name'];
	  $comma = ", ";
	}
	
	echo "<p>You may see one empty categoryname: this is from products that have been deleted.";
	echo "<p>The first set of values is for only the products for who it is the default category. The second is for all products inside a category.";	
	
$query = "SELECT name,id_category FROM ". _DB_PREFIX_."category_lang";
if($id_shop !=0)
	$query .= " AND o.id_shop=".$id_shop;
$query.= " ORDER BY id_category";
$res=dbquery($query);
$myresults = array();
while($datarow = mysqli_fetch_array($res))
{ $myresults[$datarow["id_category"]]["name"] = $datarow["name"];
}
mysqli_free_result($res);
	
$query="SELECT id_category_default AS id_category, SUM(total_price_tax_incl) AS pricetotal, product_price";
$query .= ", SUM(total_price_tax_excl) AS pricetotalex, SUM(product_quantity) AS quantitytotal, count(o.id_order) AS ordercount ";
$query .= ", COUNT(DISTINCT d.product_id) AS producttotal";
$query .= " FROM ". _DB_PREFIX_."order_detail d";
$query .= " LEFT JOIN ". _DB_PREFIX_."orders o ON o.id_order = d.id_order";
$query .= " LEFT JOIN ". _DB_PREFIX_."product_shop ps ON ps.id_product = d.product_id AND ps.id_shop=o.id_shop";

$query .= " WHERE o.current_state IN (".implode(",",$order_states).")";
if($id_shop !=0)
	$query .= " AND o.id_shop=".$id_shop;
if($input['startdate'] != "")
    $query .= " AND TO_DAYS(o.date_add) > TO_DAYS('".mysqli_real_escape_string($conn, $input['startdate'])."')";
if($input['enddate'] != "")
    $query .= " AND TO_DAYS(o.date_add) < TO_DAYS('".mysqli_real_escape_string($conn, $input['enddate'])."')";
$query .= " GROUP BY id_category";
$query .= " ORDER BY id_category";
//$verbose=true;
$res=dbquery($query);
while($datarow = mysqli_fetch_array($res))
{ $myresults[$datarow["id_category"]]["pricetotal"] = $datarow["pricetotal"];
  $myresults[$datarow["id_category"]]["pricetotalex"] = $datarow["pricetotalex"];
  $myresults[$datarow["id_category"]]["quantitytotal"] = $datarow["quantitytotal"];
  $myresults[$datarow["id_category"]]["ordercount"] = $datarow["ordercount"];
  $myresults[$datarow["id_category"]]["producttotal"] = $datarow["producttotal"];
}
mysqli_free_result($res);

$query="SELECT id_category, SUM(total_price_tax_incl) AS pricetotal, product_price";
$query .= ", SUM(total_price_tax_excl) AS pricetotalex, SUM(product_quantity) AS quantitytotal, count(o.id_order) AS ordercount ";
$query .= ", COUNT(DISTINCT d.product_id) AS producttotal";
$query .= " FROM ". _DB_PREFIX_."order_detail d";
$query .= " LEFT JOIN ". _DB_PREFIX_."orders o ON o.id_order = d.id_order";
$query .= " LEFT JOIN ". _DB_PREFIX_."category_product cp ON cp.id_product = d.product_id";

$query .= " WHERE o.current_state IN (".implode(",",$order_states).")";
if($id_shop !=0)
	$query .= " AND o.id_shop=".$id_shop;
if($input['startdate'] != "")
    $query .= " AND TO_DAYS(o.date_add) > TO_DAYS('".mysqli_real_escape_string($conn, $input['startdate'])."')";
if($input['enddate'] != "")
    $query .= " AND TO_DAYS(o.date_add) < TO_DAYS('".mysqli_real_escape_string($conn, $input['enddate'])."')";
$query .= " GROUP BY id_category";
$query .= " ORDER BY id_category";
//$verbose=true;
$res=dbquery($query);
while($datarow = mysqli_fetch_array($res))
{ $myresults[$datarow["id_category"]]["allpricetotal"] = $datarow["pricetotal"];
  $myresults[$datarow["id_category"]]["allpricetotalex"] = $datarow["pricetotalex"];
  $myresults[$datarow["id_category"]]["allquantitytotal"] = $datarow["quantitytotal"];
  $myresults[$datarow["id_category"]]["allordercount"] = $datarow["ordercount"];
  $myresults[$datarow["id_category"]]["allproducttotal"] = $datarow["producttotal"];
}

echo "<p>".mysqli_num_rows($res).' categories with sales for period: '.$input['startdate'].' - '.$input['enddate']." for ";
if($id_shop == 0)
  echo "all shops";
else 
  echo "shop nr. ".$id_shop;

$infofields = array("id","Category Name","Quant","p.price","Sales","Sales/tax","orders","products","","Sales","Sales/tax","orders","products");
echo '<div id="testdiv"><table id="Maintable" border=1><colgroup id="mycolgroup">';
for($i=0; $i<sizeof($infofields); $i++)
  echo "<col id='col".$i."'></col>";
echo '</colgroup><thead><tr>';
for($i=0; $i<sizeof($infofields); $i++)
{ $reverse = "false";
  if($i != 1) $reverse = "1";
  echo '<th><a href="" onclick="this.blur(); return sortTable(\'offTblBdy\', '.$i.', '.$reverse.');">'.$infofields[$i].'</a></th
>';
}
$total = 0;
$sumquantity = $sumtotal = $sumtotalex = 0;
echo "</tr></thead><tbody id='offTblBdy'>";
foreach ($myresults as $key => $datarow)
  { echo '<tr>';
	echo '<td>'.$key.'</td>';
	if(!isset($datarow["name"])) $datarow["name"] = "";
	echo '<td>'.$datarow["name"].'</td>';
	if(!isset($datarow["quantitytotal"])) $datarow["quantitytotal"] = "0";
    echo '<td>'.$datarow["quantitytotal"].'</td>';
	$sumquantity += intval($datarow["quantitytotal"]);
	if($datarow["quantitytotal"] != 0)
		echo '<td>'.number_format(($datarow["pricetotal"]/$datarow["quantitytotal"]),2,".","").'</td>';
	else 
		echo '<td>-</td>';
	if(!isset($datarow["pricetotal"])) $datarow["pricetotal"] = "0";
	$sumtotal += $datarow["pricetotal"];
	echo "<td>".number_format($datarow["pricetotal"],2,".","")."</a></td>";
	if(!isset($datarow["pricetotalex"])) $datarow["pricetotalex"] = "0";
	$sumtotalex += $datarow["pricetotalex"];
    echo '<td>'.number_format($datarow["pricetotalex"],2,".","").'</td>';
	if(!isset($datarow["ordercount"])) $datarow["ordercount"] = "0";
    echo '<td>'.$datarow["ordercount"].'</td>';
	if(!isset($datarow["producttotal"])) $datarow["producttotal"] = "0";
    echo '<td>'.$datarow["producttotal"].'</td>';
	echo '<td></td>';
	if(!isset($datarow["allpricetotal"])) $datarow["allpricetotal"] = "0";
	echo "<td>".number_format($datarow["allpricetotal"],2,".","")."</a></td>";
	if(!isset($datarow["allpricetotalex"])) $datarow["allpricetotalex"] = "0";	
    echo '<td>'.number_format($datarow["allpricetotalex"],2,".","").'</td>';
	if(!isset($datarow["allordercount"])) $datarow["allordercount"] = "0";
    echo '<td>'.$datarow["allordercount"].'</td>';
	if(!isset($datarow["allproducttotal"])) $datarow["allproducttotal"] = "0";
    echo '<td>'.$datarow["allproducttotal"].'</td>';	
	echo "</tr
>";

  }
  echo "</tbody></table></div>";
  echo $sumquantity." copies sold in ".mysqli_num_rows($res)." categories for in total ".number_format($sumtotal,2)." (".number_format($sumtotalex,2)." without VAT)";

?>
