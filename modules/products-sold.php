<?php 
/* This script - part of Triple Edit - gives a list of all the products bought within a certain period */
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;
if(!isset($input['id_shop'])) $input['id_shop']="0";
$id_shop = intval($input["id_shop"]);
if(!isset($input['startdate']) || (!check_mysql_date($input['startdate'])))
	$input['startdate']="";
if(!isset($input['enddate']) || (!check_mysql_date($input['enddate'])))
	$input['enddate']="";
$orderoptions = array("sales", "product_name","product_id");
if(!isset($input['order']) || (!in_array($input['order'], $orderoptions)))
  $input['order']="sales";
if(!isset($input['rising'])) 
{ if( $input['order']=="sales")
	$input['rising'] = "DESC";
  else
	$input['rising'] = "ASC";
}
if(!isset($input['startrec']) || (trim($input['startrec']) == '')) $input['startrec']="0"; else $input['startrec'] = intval(trim($input['startrec']));
if(!isset($input['numrecs']) || (intval(trim($input['numrecs']) == '0'))) $input['numrecs']="1000";
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
echo '<h1>Prestashop Ordered Products</h1>';

echo '<form name="search_form" method="get" >
Period (yyyy-mm-dd): <input size=5 name=startdate value='.$input['startdate'].'> till <input size=5 name=enddate value='.$input['enddate'].'> &nbsp;
sort by: <select name=order>';
foreach($orderoptions AS $option)
{ $selected = "";
  if($input['order'] == $option)
    $selected = "selected";
  echo '<option '.$selected.'>'.$option.'</option>';
}
echo '</select>';

	if((isset($input['rising'])) && ($input['rising'] == 'DESC'))
	  $checked = "selected";
    echo ' &nbsp; <SELECT name=rising><option>ASC</option><option '.$checked.'>DESC</option></select>';

/* making shop block */
	$query= "select id_shop,name from ". _DB_PREFIX_."shop ORDER BY id_shop";
	$res=dbquery($query);
	echo " &nbsp; Shop: <select name=id_shop><option value=0>All shops</option>";
	while ($shop=mysqli_fetch_array($res)) 
	{   $selected = "";
	    if($shop["id_shop"] == $id_shop) $selected = " selected";
        echo '<option  value="'.$shop['id_shop'].'" '.$selected.'>'.$shop['id_shop']."-".$shop['name'].'</option>';
	}	
    echo '</select>';
	echo '<br/>Startrec: <input size=3 name=startrec value="'.$input['startrec'].'">';
	echo ' &nbsp &nbsp; Number of recs: <input size=3 name=numrecs value="'.$input['numrecs'].'"> &nbsp; <input type=submit><p>';

	$query="select value from ". _DB_PREFIX_."configuration WHERE name='PS_LANG_DEFAULT'";
	$res=dbquery($query);
	$row = mysqli_fetch_array($res);
	$id_lang = $row['value'];
	
	$order_states = array(2,3,4,5);
	echo "Orders with the following states have been included: ";
	$comma = "";
	foreach($order_states AS $order_state)
	{ $osquery="select name from ". _DB_PREFIX_."order_state_lang WHERE id_order_state='".$order_state."'";
	  $osres=dbquery($osquery);
	  $osrow = mysqli_fetch_array($osres);
	  echo $comma.$osrow['name'];
	  $comma = ", ";
	}
	
	if($input['order'] == "sales")
		$order = "pricetotal";
	else if($input['order'] == "product_name")		
		$order = "product_name";
	else if($input['order'] == "product_id")			
		$order = "product_id";	
	
$query="SELECT SQL_CALC_FOUND_ROWS product_id, product_attribute_id, product_name, id_category_default, SUM(total_price_tax_incl) AS pricetotal, product_price";
$query .= ", SUM(total_price_tax_excl) AS pricetotalex, SUM(product_quantity) AS quantitytotal, count(o.id_order) AS ordercount ";
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
$query .= " GROUP BY product_id,product_attribute_id";
$query .= " ORDER BY ".$order." ".$input['rising'];
$query .= " LIMIT ".$input['startrec'].",".$input['numrecs'];
//$verbose=true;
$res=dbquery($query);
//echo "<p>".$query."<p>";

$query2="SELECT FOUND_ROWS() AS rowcount";
$res2=dbquery($query2);
$row2 = mysqli_fetch_array($res2);

echo '<p>'.mysqli_num_rows($res).' (of '.$row2["rowcount"].') ordered products shown for period: '.$input['startdate'].' - '.$input['enddate']." for ";
if($id_shop == 0)
  echo "all shops";
else 
  echo "shop nr. ".$id_shop;

$infofields = array("id","Attr","Name","category","Quant","p.price","Sales","Sales/tax","orders");
echo '<div id="testdiv"><table id="Maintable" border=1><colgroup id="mycolgroup">';
for($i=0; $i<sizeof($infofields); $i++)
  echo "<col id='col".$i."'></col>";
echo '</colgroup><thead><tr>';
for($i=0; $i<sizeof($infofields); $i++)
{ $reverse = "false";
  echo '<th><a href="" onclick="this.blur(); return sortTable(\'offTblBdy\', '.$i.', '.$reverse.');">'.$infofields[$i].'</a></th
>';
}
$total = 0;
$sumquantity = $sumtotal = $sumtotalex = 0;
echo "</tr></thead><tbody id='offTblBdy'>";
while($datarow = mysqli_fetch_array($res))
  { echo '<tr>';
	echo '<td>'.$datarow["product_id"].'</td>';
	echo '<td>'.$datarow["product_attribute_id"].'</td>';
	echo '<td>'.$datarow["product_name"].'</td>';
    echo '<td>'.$datarow["id_category_default"].'</td>';
    echo '<td>'.$datarow["quantitytotal"].'</td>';
	$sumquantity += intval($datarow["quantitytotal"]);
	echo '<td>'.number_format(($datarow["pricetotal"]/$datarow["quantitytotal"]),2,".","").'</td>';
	$sumtotal += $datarow["pricetotal"];
	echo "<td><a href onclick='return salesdetails(".$datarow['product_id'].")' title='show salesdetails'>".number_format($datarow["pricetotal"],2,".","")."</a></td>";
	$sumtotalex += $datarow["pricetotalex"];
    echo '<td>'.number_format($datarow["pricetotalex"],2,".","").'</td>';
    echo '<td>'.$datarow["ordercount"].'</td>';
	echo "</tr
>";

  }
  echo "</tbody></table></div>";
  echo $sumquantity." copies sold of ".mysqli_num_rows($res)." products for in total ".number_format($sumtotal,2)." (".number_format($sumtotalex,2)." without VAT)";

?>
