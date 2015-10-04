<?php
if(!@include 'approve.php') die( "approve.php was not found!");

$res = dbquery("select value from ". _DB_PREFIX_."configuration WHERE name='PS_STOCK_MANAGEMENT'");
$row = mysqli_fetch_array($res);
$stock_management = $row['value'];
/*
$showdate = 1;
$showcustomer = 1;
$showreference = 1;
*/
if (isset($_GET['id_order'])) $id_order = intval($_GET['id_order']);
else if (isset($_POST['id_order'])) $id_order = intval($_POST['id_order']);
else $id_order = "";
if (isset($_GET['id_lang'])) $id_lang = $_GET['id_lang'];
else if (isset($_POST['id_lang'])) $id_lang = $_POST['id_lang'];
else {
	$query="select value, l.name from ". _DB_PREFIX_."configuration f, ". _DB_PREFIX_."lang l";
	$query .= " WHERE f.name='PS_LANG_DEFAULT' AND f.value=l.id_lang";
	$res=dbquery($query);
	$row = mysqli_fetch_array($res);
	$id_lang = $row['value'];
}
$id_lang = strval(intval($id_lang));
if (!isset($_GET['attribute'])) $_GET['attribute'] = "";
else
$_GET['attribute'] = strval(intval($_GET['attribute']));

$query=" select cu.name, cu.id_currency,cu.conversion_rate from ". _DB_PREFIX_."configuration cf, ". _DB_PREFIX_."currency cu";
$query.=" WHERE cf.name='PS_CURRENCY_DEFAULT' AND cf.value=cu.id_currency";
$res=dbquery($query);
$row=mysqli_fetch_array($res);
$cur_name = $row['name'];
$cur_rate = $row['conversion_rate'];
$id_currency = $row['id_currency'];
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Prestashop Order Modify</title>
<link rel="stylesheet" href="style1.css" type="text/css" />
<style type="text/css">

body {font-family:arial; font-size:13px}

form {width:260px;}

label,span {height:20px; padding:5px 0; line-height:20px;}

label {width:130px; display:block; float:left; clear:left}
	label[for="costumer_id"] {float:left; clear:left}

	span {float:left; clear:right}

input {border:1px solid #CCC}
input[type="text"] {width:120px; height:24px; margin:3px 0; float:left; clear:right; padding:0 0 0 2px; border-radius:3px; background:#F9F9F9}
	input[type="text"]:focus {background:#FFF}
	
select {width:120px; border:1px solid #CCC}
input[type="submit"] {clear:both; display:block; color:#FFF; background:#000; border:none; height:24px; padding:2px 4px; cursor:pointer; border-radius:3px}
input[type="submit"]:hover {background:#333}

</style>
<script type="text/javascript">
var product_fields = new Array();

function checkPrices()
{ 
rv = document.getElementsByClassName("price"); // also possible with document.querySelectorAll("price")

for(var i in rv) { 
    if(rv[i].value.indexOf(',') != -1) { 
      alert("Please use dots instead of comma's for the prices!");
      rv.focus();
      return false;
    }
  }
  return true;
}
</script>
<script type="text/javascript" src="utils8.js"></script>
</head>
<body>
<?php print_menubar(); ?>
<table style="border-bottom: 2px dotted #CCCCCC;"><tr><td width="300px">
<form name="order" method="post" action="order-edit.php">
	<label for="order_number">Order number:</label><input name="id_order" type="text" value="<?php echo $id_order ?>" size="10" maxlength="10" />

	<input name="send" type="submit" value="Find order" />
</form>
</td><td>
<?php
if ($id_order != "") {
  /* the following code should only work when specially enabled */
  if (isset($showdate) && isset($_GET['order_date']))
  {  if(strtotime($_GET['order_date']) && 1 === preg_match('~[0-9]~', $_GET['order_date']))
	 { $query = "UPDATE ". _DB_PREFIX_."orders SET date_add='".mysqli_real_escape_string($conn, $_GET['order_date'])."',date_upd='".mysqli_real_escape_string($conn, $_GET['order_date'])."' ";
	   $query.=" WHERE id_order ='".mysqli_real_escape_string($conn, $id_order)."'";
	   $res=dbquery($query);
	 }
	 else
	   echo "<p/><b>Invalid order date</b><p/>";
  }

  /* the following code should only work when specially enabled */
  if (isset($showcustomer) && isset($_GET['id_customer']))
  {  $query = "SELECT * FROM ". _DB_PREFIX_."customer WHERE id_customer ='".mysqli_real_escape_string($conn, $_GET['id_customer'])."'";
	 $res=dbquery($query);
	 if(mysqli_num_rows($res) == 0)
	 { echo "<p><u><b>You provided an invalid customer number. The order was not updated.</u></b><p>";
	 }
	 else
	 { $query = "select id_address from "._DB_PREFIX_."address WHERE id_customer='".mysqli_real_escape_string($conn, $_GET['id_customer'])."'";
	   $res=dbquery($query);
	   $row=mysqli_fetch_array($res);
	   $query = "UPDATE "._DB_PREFIX_."orders SET id_customer='".mysqli_real_escape_string($conn, $_GET['id_customer'])."', ";
	   $query .= " id_address_delivery='".$row['id_address']."', id_address_invoice='".$row['id_address']."'";
	   $query.=" WHERE id_order ='".mysqli_real_escape_string($conn, $id_order)."'";
	   $res=dbquery($query);	   
	   echo "<br>Customer id was updated.<br>";
	}
  }
  
  if (isset($showreference) && isset($_GET['reference']))
  {  $query = "UPDATE ". _DB_PREFIX_."orders SET reference='".mysqli_real_escape_string($conn, $_GET['reference'])."' ";
	 $query.=" WHERE id_order ='".mysqli_real_escape_string($conn, $id_order)."'";
	 $res=dbquery($query);
  }
  
  
$query="select o.id_shop, oi.id_order_invoice, a.id_country, a.id_state, s.name AS sname, c.name AS cname, cu.id_currency, cu.name AS currname, cu.conversion_rate AS currrate from ". _DB_PREFIX_."orders o";
$query .= " left join ". _DB_PREFIX_."order_invoice oi on o.id_order=oi.id_order";
$query .= " left join ". _DB_PREFIX_."address a on o.id_address_delivery=a.id_address";
$query .= " left join ". _DB_PREFIX_."country_lang c on a.id_country=c.id_country AND c.id_lang='".$id_lang."'";
$query .= " left join ". _DB_PREFIX_."state s on a.id_country=s.id_country  AND a.id_state=s.id_state";
$query .= " left join ". _DB_PREFIX_."currency cu on cu.id_currency=o.id_currency";
$query.=" WHERE o.id_order ='".mysqli_real_escape_string($conn, $id_order)."'";
$res=dbquery($query);
$row=mysqli_fetch_array($res);
$id_country = intval($row['id_country']);
$id_state = intval($row['id_state']);
$id_shop = intval($row['id_shop']);
$id_order_invoice = $row['id_order_invoice'];

$order_currency = $row['id_currency'];
$order_currname = $row['currname'];
$conversion_rate = $row['currrate'] / $cur_rate;

if ((isset($_GET['action'])) &&($_GET['action']=='add-product')) {
 $fields = " p.weight,p.ean13,p.upc,p.reference,p.supplier_reference,p.quantity, ps.*,pl.name,pl.id_lang,l.iso_code,t.rate as tax_rate,t.id_tax, tl.name as tax_name";
 $query="select ".$fields." from ". _DB_PREFIX_."product_shop ps";
 $query.=" left join ". _DB_PREFIX_."product p on p.id_product=ps.id_product";
 $query.=" left join ". _DB_PREFIX_."product_lang pl on p.id_product=pl.id_product AND pl.id_lang='".$id_lang."'";
 $query.=" left join ". _DB_PREFIX_."lang l on pl.id_lang=l.id_lang";
 $query.=" left join ". _DB_PREFIX_."tax_rule tr on tr.id_tax_rules_group=ps.id_tax_rules_group AND tr.id_country='".$id_country."'  AND tr.id_state='".$id_state."'";
 $query.=" left join ". _DB_PREFIX_."tax t on t.id_tax=tr.id_tax";
 $query.=" left join ". _DB_PREFIX_."tax_lang tl on t.id_tax=tl.id_tax AND tl.id_lang='".$id_lang."'";
 $query.=" WHERE ps.id_shop='".$id_shop."' AND p.id_product='".$_GET['id_product']."' ";
 $res=dbquery($query);
 $products=mysqli_fetch_array($res);
 
 if(($id_state!=0) && (!isset($row['id_tax']) || ($row['id_tax'] == 0))) /* Italy clause: if there is no tax for the state there is a national tax */
 { $query="select ".$fields." from ". _DB_PREFIX_."product_shop ps";
   $query.=" left join ". _DB_PREFIX_."product p on p.id_product=ps.id_product";
   $query.=" left join ". _DB_PREFIX_."product_lang pl on p.id_product=pl.id_product AND pl.id_lang='".$id_lang."'";
   $query.=" left join ". _DB_PREFIX_."lang l on pl.id_lang=l.id_lang";
   $query.=" left join ". _DB_PREFIX_."tax_rule tr on tr.id_tax_rules_group=ps.id_tax_rules_group AND tr.id_country='".$id_country."'  AND tr.id_state='0'";
   $query.=" left join ". _DB_PREFIX_."tax t on t.id_tax=tr.id_tax";
   $query.=" left join ". _DB_PREFIX_."tax_lang tl on t.id_tax=tl.id_tax AND tl.id_lang='".$id_lang."'";
   $query.=" WHERE ps.id_shop='".$id_shop."' AND ps.id_product='".$_GET['id_product']."' ";
   $res=dbquery($query);
   $products=mysqli_fetch_array($res);
 }

 //echo $query."<p>".mysqli_num_rows($res)." RESULTS";
 if(mysqli_num_rows($res) == 0) 		/* Debugging stuff */
 { echo "<h1>Product not found</h1>";
   echo "QUERY = ".$query."<br/>";
   $query2="select * from product WHERE id_product=".$_GET['id_product'];
   $res2=dbquery($query2);
   if(mysqli_num_rows($res2) == 0)
   { echo " product ".$_GET['id_product']." is not in database<br>".$query2;
     return;
   }  
   $prod=mysqli_fetch_array($res2);
   echo "product in category ".$prod['id_category_default']."<br>";
   $query3="select * from product p, product_lang pl WHERE p.id_product=".$_GET['id_product']." AND p.id_product=pl.id_product AND pl.id_lang='".$id_lang."'";
   $res3=dbquery($query3);
   if(mysqli_num_rows($res3) == 0)
   { echo " product ".$_GET['id_product']." is not in language database<br>".$query3;
     return;
   } 
 }

 echo "Tax group=".$products['id_tax_rules_group']."; perc=".(float)$products['tax_rate']."%<br>";
 $name = $products['name'];
 $price = $products['price'];
 $weight = $products['weight'];
 $quantity = $products['quantity'];
 $attribute = '0';

 if (is_null($products['tax_rate'])) $products['tax_rate']=0;

 if($_GET['attribute']!='')
 { $price = $price+$_GET['attprice'];
   $weight = $weight+$_GET['attweight'];
   $attribute = $_GET['attribute'];
   $gquery = "SELECT public_name,l.name,pa.quantity FROM ". _DB_PREFIX_."product_attribute_combination c LEFT JOIN "._DB_PREFIX_."attribute a on c.id_attribute=a.id_attribute";
   $gquery .= " LEFT JOIN ". _DB_PREFIX_."attribute_group_lang g on a.id_attribute_group=g.id_attribute_group AND g.id_lang='".$id_lang."'";
   $gquery .= " LEFT JOIN ". _DB_PREFIX_."attribute_lang l on a.id_attribute=l.id_attribute AND l.id_lang='".$id_lang."'";
   $gquery .= " LEFT JOIN ". _DB_PREFIX_."product_attribute pa on pa.id_product_attribute=c.id_product_attribute";
   $gquery .= " WHERE c.id_product_attribute='".$_GET['attribute']."'";
   $gres = dbquery($gquery);
   $grow=mysqli_fetch_array($gres);
   $atquantity = $grow["quantity"];
   $name .= " - ".$grow['public_name'].": ".$grow['name'];
   while ($grow=mysqli_fetch_array($gres))  /* products with multiple attributes */
      $name .= ", ".$grow['public_name'].": ".$grow['name'];
 }

 if($stock_management > 0)
 { dbquery("update ". _DB_PREFIX_."product set quantity=".($quantity-1)." where id_product=".$products['id_product']);
   if(($_GET['attribute']!=''))
     dbquery("update ". _DB_PREFIX_."product_attribute set quantity=".($atquantity-1)." where id_product_attribute=".$_GET['attribute']);
 }

 $query ="insert into ". _DB_PREFIX_."order_detail ";
 $query.=" SET id_order = '".$id_order."'";
 $query.=" ,id_order_invoice = '".$id_order_invoice."'";
 $query.=" ,id_shop = '".$id_shop."'";
 $query.=" ,product_id = '".$products['id_product']."'";
 $query.=" ,product_attribute_id = '".mysqli_real_escape_string($conn, $attribute)."'";
 $query.=" ,product_name = '".mysqli_real_escape_string($conn, $name)."'";
 $query.=" ,product_quantity = '1'";
 $query.=" ,product_quantity_in_stock = '1'"; //".$products['quantity']."'";
 $query.=" ,product_price = '".($price*$conversion_rate)."'";
 $query.=" ,product_ean13 = '".$products['ean13']."'";
 $query.=" ,product_upc = '".$products['upc']."'";
 $query.=" ,product_reference = '".$products['reference']."'";
 $query.=" ,product_supplier_reference = '".$products['supplier_reference']."'";
 $query.=" ,product_weight = '".$weight."'";
 $query.=" ,tax_name = '".$products['tax_name']."'";
 $query.=" ,tax_rate = '".$products['tax_rate']."'";
 $unit_price_excl = round($price,2);
 $unit_price_incl = round(($price*(100+$products['tax_rate'])/100),2);
 $query.=" ,total_price_tax_incl = '".$unit_price_incl."'";
 $query.=" ,total_price_tax_excl = '".$unit_price_excl."'";
 $query.=" ,unit_price_tax_incl = '".$unit_price_incl."'";
 $query.=" ,unit_price_tax_excl = '".$unit_price_excl."'";
 dbquery($query);
 
 $tquery ="insert into ". _DB_PREFIX_."order_detail_tax ";
 $tquery.=" SET id_order_detail = LAST_INSERT_ID()";
 $tquery.=" ,id_tax = '".$products['id_tax']."'";
 $tquery.=" ,unit_amount = '".($unit_price_incl-$unit_price_excl)."'";
 $tquery.=" ,total_amount = '".($unit_price_incl-$unit_price_excl)."'";
 dbquery($tquery);
 update_total($id_order);

} /* end if($_GET['action']=='add-product') */

$carrierseg = "";
if (isset($_POST['id_carrier'])) 
{ $carrierseg = " ,id_carrier=".$_POST['id_carrier'];

  $query="update  ". _DB_PREFIX_."order_carrier set ";
  $query.=" id_carrier=".$_POST['id_carrier'];
  $query.=" where id_order=".mysqli_real_escape_string($conn, $id_order);
  dbquery($query);
  
  
}

if (isset($_POST['order_total'])) { 
$query="select t.rate AS carriertax FROM "._DB_PREFIX_."carrier_tax_rules_group_shop ct ";
$query.=" left join ". _DB_PREFIX_."tax_rule tr on tr.id_tax_rules_group=ct.id_tax_rules_group AND tr.id_country='".$id_country."' AND tr.id_state='".$id_state."'";
$query.=" left join ". _DB_PREFIX_."tax t on t.id_tax=tr.id_tax";
$query .= " where ct.id_carrier=".mysqli_real_escape_string($conn, $_POST['id_carrier'])." AND ct.id_shop=".$id_shop;
$res = dbquery($query);
$trow=mysqli_fetch_array($res);
$carrier_tax_rate = $trow["carriertax"];

$total=price($_POST['total_products_wt'])+price($_POST['total_shipping'])+price($_POST['total_wrapping'])-price($_POST['total_discounts']);
$total_shipping_tax_excl = ($_POST['total_shipping']/(100+$carrier_tax_rate))*100;
$tax = price($_POST['total_products_wt']) - price($_POST['total_products']) + price($_POST['total_shipping']) - price($total_shipping_tax_excl);

$query="update ". _DB_PREFIX_."orders set ";
$query.=" total_discounts=".price($_POST['total_discounts']);
$query.=" ,total_wrapping=".price($_POST['total_wrapping']);
$query.=" ,total_shipping=".price($_POST['total_shipping']);
$query.=" ,total_shipping_tax_excl=".price($total_shipping_tax_excl);
$query.=" ,total_shipping_tax_incl=".price($_POST['total_shipping']);
$query.=" ,delivery_number=".price($_POST['delivery_number']);
$query.=$carrierseg;
$query.=" ,total_paid_tax_incl=".$total;
$query.=" ,total_paid_tax_excl='".($total - $tax)."'";
$query.=" ,total_paid=".$total;
$query.=" ,total_paid_real=".$total;
$query.=" where id_order=".mysqli_real_escape_string($conn, $id_order);
$query.=" limit 1";
dbquery($query);

$query="update ". _DB_PREFIX_."order_carrier set ";
$query.=" shipping_cost_tax_excl=".price($total_shipping_tax_excl);
$query.=" ,shipping_cost_tax_incl=".price($_POST['total_shipping']);
$query.=" where id_order=".mysqli_real_escape_string($conn, $id_order);
$query.=" limit 1";
dbquery($query);

$query="update ". _DB_PREFIX_."order_invoice set ";
$query.=" total_discount_tax_incl=".price($_POST['total_discounts']);
$query.=" ,total_discount_tax_excl=".price($_POST['total_discounts']);
$query.=" ,total_wrapping_tax_incl=".price($_POST['total_wrapping']);
$query.=" ,total_wrapping_tax_excl=".price($_POST['total_wrapping']);
$query.=" ,total_shipping_tax_excl='".price($total_shipping_tax_excl)."'";
$query.=" ,total_shipping_tax_incl='".price($_POST['total_shipping'])."'";
$query.=" ,total_paid_tax_excl='".($total - $tax)."'";
$query.=" ,total_paid_tax_incl='".$total."'";
$query.=" where id_order=".mysqli_real_escape_string($conn, $id_order);
$query.=" limit 1";
dbquery($query);

echo "<br/><b> Order Modified </b><br/>";

}

if (isset($_POST['Apply'])) {

//delete product
if (isset($_POST['product_delete'])) {
  foreach ($_POST['product_delete'] as $id_order_detail=>$value) {
    if($stock_management > 0) {
	   $res = dbquery("SELECT product_id,product_attribute_id,product_quantity from ". _DB_PREFIX_."order_detail where id_order_detail=".$id_order_detail);
	   $drow=mysqli_fetch_array($res);
	   if(mysqli_num_rows($res) > 0)
	   { dbquery("UPDATE ". _DB_PREFIX_."product SET quantity = quantity + '".$drow['product_quantity']."' where id_product=".$drow['product_id']);
	     if($drow['product_attribute_id']!='')
           dbquery("update ". _DB_PREFIX_."product_attribute set quantity = quantity + '".$drow['product_quantity']."' where id_product_attribute=".$drow['product_attribute_id']);
		}
	}
    dbquery("delete from ". _DB_PREFIX_."order_detail where id_order_detail=".$id_order_detail);
	dbquery("delete from ". _DB_PREFIX_."order_detail_tax where id_order_detail=".$id_order_detail);
  }
}

$total_products = 0;
if ($_POST['product_price']) {
foreach ($_POST['product_price'] as $id_order_detail=>$price_product) {
  $qty_difference=$_POST['product_quantity_old'][$id_order_detail]-$_POST['product_quantity'][$id_order_detail];
  $name=$_POST['product_name'][$id_order_detail];
  $attribute = $_POST['product_attribute'][$id_order_detail];
  
  $tquery  = "SELECT rate from ". _DB_PREFIX_."order_detail_tax ot";
  $tquery .= " LEFT JOIN ". _DB_PREFIX_."tax t ON t.id_tax=ot.id_tax";
  $tquery .= " WHERE id_order_detail = '".$id_order_detail."'";
  $tres = dbquery($tquery);
  $trow=mysqli_fetch_array($tres);
  
  $unit_price_excl = round($price_product,2);
  $unit_price_incl = round(($price_product*(100+$trow['rate'])/100),2);
  $quantity = intval($_POST['product_quantity'][$id_order_detail]);
  
  $query = "update ". _DB_PREFIX_."order_detail set product_price='".$price_product."'";
  $query .= ", product_quantity='".$quantity."'";
  $query .= ", product_quantity_in_stock='".intval($quantity)."'";
  $query .= ", product_name='".mysqli_real_escape_string($conn, $name)."'";
  $query .= ", total_price_tax_incl = '".($quantity*$unit_price_incl)."'";
  $query .= ", total_price_tax_excl = '".($quantity*$unit_price_excl)."'";
  $query .= ", unit_price_tax_incl = '".$unit_price_incl."'";
  $query .= ", unit_price_tax_excl = '".$unit_price_excl."'";
  $query .= "  where id_order_detail='".$id_order_detail."'";
  dbquery($query);

  //servirebbe ad aggiornare lo stock, ma si dovrebbe vincolare ad uno stato. Attualmete lo disabilito
  if($stock_management > 0)
  { dbquery("update  ". _DB_PREFIX_."product set quantity=quantity+'".$qty_difference."' where id_product=".$_POST['product_id'][$id_order_detail]);
    if($attribute != 0) 
      dbquery("update  ". _DB_PREFIX_."product_attribute set quantity=quantity+'".$qty_difference."' where id_product_attribute='".mysqli_real_escape_string($conn, $attribute)."'");
  }
  $total_products+=$_POST['product_quantity'][$id_order_detail]*price($price_product);
}
update_total($id_order);
}
}

if ($id_order) { 
$query="select distinct o.*,a.*,o.date_add AS order_date from ". _DB_PREFIX_."orders o";
$query .=" LEFT JOIN "._DB_PREFIX_."address a ON a.id_address=o.id_address_delivery";
$query .= " where o.id_order=".mysqli_real_escape_string($conn, $id_order);
$res=dbquery($query);
if (mysqli_num_rows($res)>0) {
$order=mysqli_fetch_array($res);
$id_customer=$order['id_customer'];
$reference = $order['reference'];
$id_lang=$order['id_lang'];
$id_cart=$order['id_cart'];
$payment=$order['payment'];
$module=$order['module'];
$invoice_number=$order['invoice_number'];
$delivery_number=$order['delivery_number'];
$total_paid_tax_excl=$order['total_paid_tax_excl'];
$total_paid_tax_incl=$order['total_paid_tax_incl'];
$total_products=$order['total_products'];
$total_products_wt=$order['total_products_wt'];
$total_discounts=$order['total_discounts'];
$total_shipping=$order['total_shipping'];
$total_wrapping=$order['total_wrapping'];
$firstname=$order['firstname'];
$lastname=$order['lastname'];
$company=$order['company'];
$carrier = $order['id_carrier'];
$order_date = $order['order_date'];
}
} 
?>

	<label for="costumer_id">Costumer ID:</label><span><?php echo $id_customer ?></span>
	<label for="costumer_name">Costumer Name:</label><span><?php echo $firstname." ".$lastname. " ".$company ?></span>
</td><td style="padding:6pt" valign="top">
<?php
echo "Tax country=".$row['cname'];
if ($id_state != 0)
  echo " AND state=".$row['sname'];
echo "<br>Shop id=".$id_shop;
?>
</td></tr></table>

<form name="order_total" method="post" action="order-edit.php" style="padding-top: 20px;width: 620px;">
<!-- hidden value --> <input type=hidden name=id_lang value="<?php echo $id_lang ?>">

	<label for="carrier">Carrier:</label>
	<select name="id_carrier">
		<?php	$query=" select * from ". _DB_PREFIX_."carrier WHERE deleted='0'";
			$res=dbquery($query);
			while ($carrierrow=mysqli_fetch_array($res)) {
			  $selected='';
			  if ($carrierrow['id_carrier']==$carrier) $selected=' selected="selected" ';
			  echo '<option  value="'.$carrierrow['id_carrier'].'" '.$selected.'>'.$carrierrow['name'].'</option>';
			}
		?>
	</select>
	
	<label for="total_shipping">Shipping:</label><input name="total_shipping" type="text" value="<?php echo $total_shipping ?>" />
	<label for="total_discounts">Discounts:</label><input name="total_discounts" type="text"  value="<?php echo $total_discounts ?>" />
	<label for="total_wrapping">Wrapping:</label><input name="total_wrapping" type="text" value="<?php echo $total_wrapping ?>" />
	<label for="delivery_number">Delivery no.:</label><input name="delivery_number" type="text"  value="<?php echo $delivery_number ?>" />
	<label for="subtotal">Subtotal (tax excl.):</label><span><?php echo $total_paid_tax_excl ?></span>
	<label for="total">Total (tax incl.):</label><span><?php echo $total_paid_tax_incl." &nbsp; ".$order_currname ?></span>

	<!-- hidden value -->  <input name="total_products" type="hidden"  value="<?php echo $total_products ?>" />
	<!-- hidden value -->  <input name="total_products_wt" type="hidden"  id="total_products_wt" value="<?php echo $total_products_wt ?>" />
	<!-- hidden value -->  <input name="id_order" type="hidden" value="<?php echo $id_order ?>" />
	
	<input type="submit" name="order_total"  value="Modify Order" />
</form>

<br style="clear:both; height:40px;display:block;" />

<?php

if ((isset($_GET['action'])) &&($_GET['action']=='add-product') && ($conversion_rate != 1))
 echo "Currency converted: Product price: ".$price." ".$cur_name." was converted into ".($price*$conversion_rate)." ".$order_currname;


?>

<form name="products" method="post" action="order-edit.php" onSubmit="return checkPrices();">
<table width="100%" ><tr><td width="100%" align=right>
<a style="height:20px; background:#000; color:#FFF; border-radius:3px; padding:5px 10px; text-decoration:none; margin:20px 0"href="add-product.php?id_order=<?php echo $id_order ?>&id_lang=<?php echo $id_lang ?>&id_shop=<?php echo $id_shop ?>" target="_self">Add new product</a>
</td></tr>
<tr><td>
<table width="100%" border="1" bgcolor="#FFCCCC" style="margin-top:10px;">
  <tr>
    <td >product id</td>
    <td>attrib</td>
    <td>Product Reference</td>
    <td>Product Name</td>
    <td>Price tax ex</td>
    <td>Tax</td>
    <td>Price with tax</td>
    <td>Qty</td>
    <td>Total no tax</td>
    <td>Total  tax inc.</td>
    <td>Weight</td>
    <td>Image</td>
    <td>Delete</td>
  </tr>

  <?php
$query="select o.*,p.quantity as stock, i.id_image, o.product_attribute_id,t.rate from ". _DB_PREFIX_."order_detail o";
$query .= " left join ". _DB_PREFIX_."product p  on  o.product_id=p.id_product";
$query .= " left join ". _DB_PREFIX_."image i on i.id_product=p.id_product and i.cover=1";
$query .= " LEFT JOIN ". _DB_PREFIX_."order_detail_tax ot ON o.id_order_detail=ot.id_order_detail";
$query .= " LEFT JOIN ". _DB_PREFIX_."tax t ON t.id_tax=ot.id_tax";
$query.=" where id_order=".mysqli_real_escape_string($conn, $id_order);
$query.=" order by id_order_detail asc";
  $res1=dbquery($query);

if (mysqli_num_rows($res1)>0) {

while ($products=mysqli_fetch_array($res1)) {
  echo '<tr>';
  if($products["rate"] == NULL)
  { $rquery = " SELECT rate from ". _DB_PREFIX_."product_shop ps ";
    $rquery.=" left join ". _DB_PREFIX_."tax_rule tr on tr.id_tax_rules_group=ps.id_tax_rules_group AND tr.id_country='".$id_country."' AND tr.id_state='".$id_state."'";
    $rquery.=" left join ". _DB_PREFIX_."tax t on t.id_tax=tr.id_tax";
	$rquery.=" WHERE ps.id_product='".$products["product_id"]."' AND ps.id_shop='".$id_shop."'";
	$rres=dbquery($rquery);
	$rrow=mysqli_fetch_array($rres);
	$products["rate"] = $rrow["rate"];
  }
  echo '  <td>'.$products['product_id'].'</td>';
  echo '  <td>'.$products['product_attribute_id'].'</td>';
  echo '  <td>'.$products['product_reference'].'</td>';
  echo '  <td><input name="product_name['.$products['id_order_detail'].']" value="'.htmlspecialchars($products['product_name']).'" size="35" /></td>';
  echo '  <td><input name="product_price['.$products['id_order_detail'].']" class="price" value="'.number_format($products['product_price'], 4, '.', '').'" size="9" /></td>';
  echo '  <td>'.(float)$products['rate'].'%</td>';
  echo '  <td>'.number_format($products['product_price']*(1+$products['rate']/100),2, '.', '').'</td>';  
  echo '  <td><input name="product_quantity['.$products['id_order_detail'].']" value="'.$products['product_quantity'].'" size="5" /></td>';
  echo '  <td>'.number_format($products['product_price']*$products['product_quantity'],2, '.', '').'</td>';  
  echo '  <td>'.number_format($products['product_price']*$products['product_quantity']*(1+$products['rate']/100),2, '.', '').'</td>';  
  echo '  <td>'.number_format($products['product_weight'],2, '.', '').'</td>';
  if($products['product_attribute_id']!=0) /* show attribute image when available */
  { $attriquery = "SELECT id_image from "._DB_PREFIX_."product_attribute_image WHERE id_product_attribute='".$products['product_attribute_id']."';";
    $attrires=dbquery($attriquery);
	$attrirow=mysqli_fetch_array($attrires);
	if($attrirow['id_image'] != 0)
	  echo "  <td>".get_product_image($attrirow['id_image'],'')."</td>";
	else
	  echo "  <td>".get_product_image($products['id_image'],'')."</td>";
  }
  else  
    echo "  <td>".get_product_image($products['id_image'],'')."</td>";
  echo '  <td><input name="product_delete['.$products['id_order_detail'].']" type="checkbox" />';
  echo '  <input name="product_quantity_old['.$products['id_order_detail'].']" type="hidden" value="'.$products['product_quantity'].'" />';
  echo '  <input name="product_id['.$products['id_order_detail'].']" type="hidden" value="'.$products['product_id'].'" />';
  echo '  <input name="product_attribute['.$products['id_order_detail'].']" type="hidden" value="'.$products['product_attribute_id'].'" />';
  echo '  <input name="product_stock['.$products['id_order_detail'].']" type="hidden" value="'.$products['stock'].'" />';
  echo '</td></tr> ';
    ?>
<?php
  }
  }
  ?>
</table>
</td></tr>
<tr><td width="100%" align=center>
  <input name="Apply" type="submit" value="Modify order" />
  <input name="id_order" type="hidden" value="<?php echo $id_order ?>" />
  <!--input name="tax_rate" type="hidden" value="<-?php echo $tax_rate ?->" /-->
  <input name="id_lang" type="hidden" value="<?php echo $id_lang ?>" />
</td></tr>
</table>
</form>
<p/>
<?php 
/* the following code will produce an editable order_date field. It should be normally disabled. */

echo '<table >';
if(isset($showdate))
{ echo '<form name="dateform" method="get" onsubmit="return checkdate();"><tr><td>';

  $oyear = substr($order_date, 0,4);
  $omonth = substr($order_date, 5,2);
  $oday = substr($order_date, 8,2);
  echo '
Order date (dd-mm-yyyy):</td><td><nobr><input id=oday size=2 value='.$oday.'>-<input id=omonth size=2 value='.$omonth.'>-<input id=oyear size=4 value='.$oyear.'></nobr></td><td>
<input name="id_order" type="hidden" value="'.$id_order.'" />
<input type=hidden name=order_date><input type=submit value="Change order date">
</form>
<script>
function checkdate()
{ error = 0;
  day = document.dateform["oday"].value;
  month = document.dateform["omonth"].value;
  year = document.dateform["oyear"].value;
  if((year < 500) || (year > 2100)) {field="oyear"; error=1;}
  if((month < 1 ) || (month > 12)) {field="omonth"; error=1;}
  if((day < 1) || (day > 31)) {field="oday"; error=1;}
  if((month==4 || month==6 || month==9 || month==11) && (day==31)) {field="oday"; error=1;}
  if((month==2) && (day > 29)) {field="day"; error=1;}
  if((month==2) && (day==29) && (!LeapYear(year))) {field="oday"; error=1;}
  if(error == 1)
  { alert("Invalid date!");
	document.dateform[field].focus();
	document.dateform[field].select();
	return false;
  }
  document.dateform["order_date"].value = year+"-"+month+"-"+day;
  return true;
}

function LeapYear(intYear) 
{ if (intYear % 100 == 0) 
  { if (intYear % 400 == 0) { return true; }
  }
  else
  { if ((intYear % 4) == 0) { return true; }
  }
  return false;
}
</script></td></tr>';
}

if(isset($showcustomer))
{ echo '<form name="customerform" method="get" ><tr><td>';
  echo '<nobr>Customer id: </td><td><input name="id_customer" value="'.$id_customer.'"></nobr></td><td>';
  echo '<input name="id_order" type="hidden" value="'.$id_order.'" />
  <input type=submit value="Change customer id"></form></td></tr>
';
}
if(isset($showreference))
{ echo '<form name="referenceform" method="get"><tr><td>';
  echo '<nobr>Customer id: </td><td><input name="reference" value="'.$reference.'"></nobr></td><td>';
  echo '<input name="id_order" type="hidden" value="'.$id_order.'" />
  <input type=submit value="Change order reference"></form></td></tr>
';
}

echo '</table>';
?>

Limitations:<br/>
 - this program will not recalculate your shipping costs if you change carrier or add or remove products. You should do that manually.<br/>
 - ecotax is ignored.<br/>
 - discounts on added products are not processed<br/>
 - stock management is supported. When a product is out of stock its quantity becomes negative but you get no warning.<br/>
 - split orders (more than one shipment) are not supported.<br/>

<?php
  include "footer.php";
  echo '</body></html>';

}

function price($price) {
$price=str_replace(",",".",$price);
return $price;
}

function update_total($id_order) {
global $conn;
$query="select sum(total_price_tax_incl) as total_products,sum(total_price_tax_excl) as total_products_notax  from  ". _DB_PREFIX_."order_detail where id_order=".$id_order;
$res2=dbquery($query);
$products=mysqli_fetch_array($res2);
if($products['total_products']=="")
  $products['total_products'] = $products['total_products_notax'] = 0; /* no products present */
$query="select * from  ". _DB_PREFIX_."orders where id_order=".mysqli_real_escape_string($conn, $id_order);
$res3=dbquery($query);
$order=mysqli_fetch_array($res3);
$total=price($products['total_products'])+price($order['total_shipping'])+price($order['total_wrapping'])-price($order['total_discounts']);
$query="update ". _DB_PREFIX_."orders set ";
$query.=" total_discounts=".$order['total_discounts'];
$query.=" ,total_wrapping=".$order['total_wrapping'];
$query.=" ,total_shipping=".$order['total_shipping'];
$query.=" ,total_products=".$products['total_products_notax'];
$query.=" ,total_products_wt=".$products['total_products'];
$query.=" ,total_paid_tax_excl='".($order['total_shipping_tax_excl'] + $products['total_products_notax'])."'";
$query.=" ,total_paid_tax_incl=".$total;
$query.=" ,total_paid_real=".$total;
$query.=" ,total_paid=".$total;
$query.=" where id_order=".mysqli_real_escape_string($conn, $id_order);
$query.=" limit 1";
dbquery($query);

$query="update ". _DB_PREFIX_."order_invoice set ";
$query.=" total_paid_tax_excl='".($order['total_shipping_tax_excl'] + $products['total_products_notax'])."'";
$query.=" ,total_paid_tax_incl=".$total;
$query.=" ,total_products=".$products['total_products_notax'];
$query.=" ,total_products_wt=".$products['total_products'];
$query.=" where id_order=".mysqli_real_escape_string($conn, $id_order);
$query.=" limit 1";
dbquery($query);
}

?>

</body>
</html>
