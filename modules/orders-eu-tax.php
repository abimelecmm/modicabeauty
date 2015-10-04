<?php 
/* This script - part of Triple Edit - gives a list of all the order completed within a certain period */
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;
if(!isset($input['id_shop'])) $input['id_shop']="0";
$id_shop = intval($input["id_shop"]);
if(!isset($input['startdate']) || (!check_mysql_date($input['startdate'])))
	$input['startdate']="";
if(!isset($input['enddate']) || (!check_mysql_date($input['enddate'])))
	$input['enddate']="";
if(!isset($input['startrec']) || (trim($input['startrec']) == '')) $input['startrec']="0"; else $input['startrec'] = intval(trim($input['startrec']));
if(!isset($input['numrecs']) || (intval(trim($input['numrecs']) == '0'))) $input['numrecs']="1000";
if(!isset($input['non-eu']) || (intval(trim($input['non-eu']) == '0'))) $input['non-eu']="0";
$eucountrynames = array("Belgium", "Bulgaria", "Croatia", "Cyprus (the Greek part)", "Denmark", "Germany", "Estonia", "Finland", "France", "Greece", "United Kingdom", "Hungary", "Ireland", "Italy", "Latvia", "Lithuania", "Luxembourg", "Malta", "The Netherlands", "Austria", "Poland", "Portugal", "Romania", "Slovenia", "Slovakia", "Spain", "Czech Republic", "Sweden");
/*							3			236			74			76						20			1			86			7		8			9			17				143			26		10		125			131				12			139				13			2		14			15			36			193			37			6		16				18		*/
$eucountries = array("3", "236", "74", "76", "", "20", "1", "86", "7", "8", "9", "17", "143", "26", "10", "125", "131", "	12", "139", "13", "2", "14", "15", "36", "193", "37", "6", "16", "18");

	$query="select value from ". _DB_PREFIX_."configuration WHERE name='PS_LANG_DEFAULT'";
	$res=dbquery($query);
	$row = mysqli_fetch_array($res);
	$id_lang = $row['value'];

$query="select c.value,l.name from ". _DB_PREFIX_."configuration c";
$query .= " LEFT JOIN "._DB_PREFIX_."country_lang l ON c.value=l.id_country AND l.id_lang='".$id_lang."'";
$query .= " WHERE c.name='PS_COUNTRY_DEFAULT'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$id_country_default = $row["value"];
$owncountry = $row["name"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Prestashop Order and tax list for EU tax</title>
<link rel="stylesheet" href="style1.css" type="text/css" />
<style>
td+td+td+td { text-align: right; }
td+td+td+td+td+td+td { text-align: left; }
</style>
<script src="http://tinymce.cachefly.net/4.0/tinymce.min.js"></script> <!-- Prestashop settings can be found at /js/tinymce.inc.js -->
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<?php echo '<script type="text/javascript">
	function salesdetails(product)
	{ window.open("product-sales.php?product="+product+"&startdate='.$input["startdate"].'&enddate='.$input["enddate"].'&id_shop='.$id_shop.'","", "resizable,scrollbars,location,menubar,status,toolbar");
      return false;
    }
	
	var rowsremoved = 0;
function RemoveRow(row)
{ var tblEl = document.getElementById("offTblBdy");
  var trow = document.getElementById("trid"+row).parentNode;
  trow.innerHTML = "<td></td>";
  rowsremoved++;
}
</script>
</head><body>';
print_menubar();
echo '<h1>Prestashop Orders in a period for EU Tax</h1>';

echo '<form name="search_form" method="get" >
Period (yyyy-mm-dd): <input size=5 name=startdate value='.$input['startdate'].'> till <input size=5 name=enddate value='.$input['enddate'].'> &nbsp;';

/* making shop block */
	$query= "select id_shop,name from ". _DB_PREFIX_."shop ORDER BY id_shop";
	$res=dbquery($query);
	echo " &nbsp; Shop: <select name=id_shop><option value=0>All shops</option>";
	while ($shop=mysqli_fetch_array($res)) 
	{   $selected = "";
	    if($shop["id_shop"] == $id_shop) $selected = " selected";
        echo '<option  value="'.$shop['id_shop'].'" '.$selected.'>'.$shop['id_shop']."-".$shop['name'].'</option>';
	}	
    echo '</select> &nbsp; <input type=submit><p>';
//	$checked = "";
//	 echo '<br/>Include non-eu countries: <input name="non-eu" value="1" '.$checked.' type="checkbox" />';
//	echo '<br/>Startrec: <input size=3 name=startrec value="'.$input['startrec'].'">';
//	echo ' &nbsp &nbsp; Number of recs: <input size=3 name=numrecs value="'.$input['numrecs'].'"> &nbsp; <input type=submit><p/>';

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
	echo "<br/>Orders for the follow period have been included: ".$input["startdate"]." - ".$input["enddate"]." for ";
	if($id_shop == 0)
		echo "all shops";
	else 
		echo "shop nr. ".$id_shop;
	echo "<br/>For EU countries orders with and without VAT number are mentioned seperately as those with VAT number don't have VAT.<br/>";
	
$query="SELECT a.id_country, name AS countryname, total_paid_tax_excl, total_paid_tax_incl, id_order, c.firstname, c.lastname, invoice_date";
$query .= ", b.vat_number, (LENGTH(b.vat_number) != 0) AS isCompany FROM ". _DB_PREFIX_."orders o";
 $query .= " LEFT JOIN ". _DB_PREFIX_."customer c ON o.id_customer = c.id_customer";
$query .= " LEFT JOIN ". _DB_PREFIX_."address a ON o.id_address_delivery = a.id_address";
$query .= " LEFT JOIN ". _DB_PREFIX_."country_lang cl ON cl.id_country = a.id_country AND cl.id_lang='".$id_lang."'";
$query .= " LEFT JOIN ". _DB_PREFIX_."address b ON o.id_address_invoice = b.id_address";
$query .= " WHERE o.current_state IN (".implode(",",$order_states).")";
if($id_shop !=0)
	$query .= " AND o.id_shop=".$id_shop;
if($input['startdate'] != "")
    $query .= " AND TO_DAYS(o.date_add) > TO_DAYS('".mysqli_real_escape_string($conn, $input['startdate'])."')";
if($input['enddate'] != "")
    $query .= " AND TO_DAYS(o.date_add) < TO_DAYS('".mysqli_real_escape_string($conn, $input['enddate'])."')";
$query .= " ORDER BY countryname, isCompany, id_order";
$res=dbquery($query);

$infofields = array("","id","Country","Sales/incl","Sales/excl","Tax","Pct", "Orders");
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
$x=0;
$incl = $excl = $taxes = $euincl = $euexcl  = $eutaxes = $exincl = $exexcl  = $extaxes = $ownincl = $ownexcl  = $owntaxes = 0;
$oldcountry = 0;
$oldisCompany = -1;
$total_incl = $total_excl = 0;
$myorders = "";
while($datarow = mysqli_fetch_array($res))
  { if(($datarow['id_country'] != $oldcountry) || ($datarow['isCompany'] != $oldisCompany))
	{ if($oldcountry != 0) 
      { print_line($row, $total_incl, $total_excl, $myorders);
	  }
	  $total_incl = $total_excl = 0;
	  $myorders = "";
	  $oldcountry = $datarow['id_country'];
	  $oldisCompany = $datarow['isCompany'];	  
	}
	$incl += $datarow["total_paid_tax_incl"];
	$excl += $datarow["total_paid_tax_excl"];
	$tax = $datarow["total_paid_tax_incl"] - $datarow["total_paid_tax_excl"];
    $taxes += $tax;
	$total_incl += $datarow["total_paid_tax_incl"];
	$total_excl += $datarow["total_paid_tax_excl"];	
    if(in_array($datarow["id_country"], $eucountries) && ($datarow["id_country"] != $id_country_default))
	{ 	$euincl += $datarow["total_paid_tax_incl"];
		$euexcl += $datarow["total_paid_tax_excl"];
		$eutaxes += $tax;
	}
	else if($datarow["id_country"] == $id_country_default)
	{ 	$ownincl += $datarow["total_paid_tax_incl"];
		$ownexcl += $datarow["total_paid_tax_excl"];
		$owntaxes += $tax;
		$owncountry = $datarow["countryname"];
	}
	else
	{ 	$exincl += $datarow["total_paid_tax_incl"];
		$exexcl += $datarow["total_paid_tax_excl"];
		$extaxes += $tax;
	}
	if(strlen($myorders) > 0) $myorders .= ",";
	$myorders .= '<a title="'.$datarow['firstname'].' '.$datarow['lastname'].' - '.$datarow['countryname'].' - '.$datarow['vat_number'].' : '.number_format($datarow['total_paid_tax_incl'],2).' / '.number_format($datarow['total_paid_tax_excl'],2).' - '.substr($datarow['invoice_date'],0,10).' '.'" href="#" onclick="return false;">'.$datarow["id_order"].'</a>';
	$row = $datarow;
  }
  print_line($row, $total_incl, $total_excl, $myorders);
  echo "</tbody></table></div>";
  
  echo "<table border=1;><tr><th colspan=4>Totals</th></tr>";
  echo "<tr><th></th><th>Sales/incl</th><th>Sales/excl</th><th>Tax</th></tr>";
  echo "<tr><td>".$owncountry."</td><td>".number_format($ownincl,2)."</td><td>".number_format($ownexcl,2)."</td><td>".number_format($owntaxes,2)."</td></tr>";
  echo "<tr><td>Within EU</td><td>".number_format($euincl,2)."</td><td>".number_format($euexcl,2)."</td><td>".number_format($eutaxes,2)."</td></tr>";
  echo "<tr><td>Outside EU</td><td>".number_format($exincl,2)."</td><td>".number_format($exexcl,2)."</td><td>".number_format($extaxes,2)."</td></tr>";
  echo "<tr><td>Total</td><td>".number_format($incl,2)."</td><td>".number_format($excl,2)."</td><td>".number_format($taxes,2)."</td></tr>";
  echo "</table>";
  
  function print_line($datarow, $total_incl, $total_excl, $myorders)
  { global $eucountries, $id_country_default, $x;
    $bgcolor = "";
    if(!in_array($datarow["id_country"], $eucountries))
      $bgcolor = 'style="background-color: yellow"';
	if($datarow["id_country"] == $id_country_default)
       $bgcolor = 'style="background-color: #EFCCEF"';	
    echo '<tr '.$bgcolor.'>';
	echo '<td id="trid'.$x.'"><input type="button" value="X" style="width:4px" onclick="RemoveRow('.$x.')" title="Hide line from display" /></td>';
	echo '<td>'.$datarow["id_country"].'</td>';
	echo '<td>'.$datarow["countryname"].'</td>';
	echo '<td>'.number_format($total_incl,2).'</td>';
    echo '<td>'.number_format($total_excl,2).'</td>';
	$tax = $total_incl- $total_excl;
    echo '<td>'.number_format($tax,2).'</td>';
	echo '<td>'.number_format(($tax*100)/$total_excl,2).'</td>';
	if($datarow["id_country"] == $id_country_default)
	  echo "<td></td>";
	else	
	  echo '<td>'.$myorders.'</td>';
	echo '<tr
	  >';
	$x++;
  }
 
?>
