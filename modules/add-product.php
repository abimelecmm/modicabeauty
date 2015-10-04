<?php
if ((!isset($_GET['id_order'])) && (!isset($_GET['id_shop'])) && (!isset($_GET['id_lang'])) && (!isset($_POST['id_order'])))
{ echo "<script>setTimeout('location.href=\'order-edit.php\';',4000);</script>";
  die("add-product.php is not a standalone script. It is a script that is called from <a href='order-edit.php'>order-edit.php</a>");
}
if(!@include 'approve.php') die( "approve.php was not found!");
if (isset($_GET['id_order'])) $_POST['id_order']=$_GET['id_order'];
if (isset($_GET['id_shop'])) $_POST['id_shop']=$_GET['id_shop'];
if (isset($_POST['id_order'])) $_POST['id_order'] = strval(intval($_POST['id_order']));
if (isset($_GET['id_lang'])) $_POST['id_lang']=$_GET['id_lang'];
if(strlen($_POST['id_lang']) > 0) $_POST['id_lang'] = strval(intval($_POST['id_lang']));
if (!isset($_POST['id_order'])) die("This page can only work with an ordernumber as it needs to know the tax country of the customer!");
if (!isset($_POST['id_shop'])) die("No shop defined!");
if (!isset($_POST['search_txt'])) $_POST['search_txt']="";
if(!isset($_POST['offset'])) $_POST['offset'] = 0;
$_POST['offset'] = strval(intval($_POST['offset']));
if(!isset($_POST['id_category'])) $_POST['id_category'] = "";
if($_POST['id_category'] != "") $_POST['id_category'] = strval(intval($_POST['id_category']));
if(!isset($_POST['order'])) $_POST['order'] = "id_product";
$id_shop = intval($_POST['id_shop']);

$res = dbquery("select value from ". _DB_PREFIX_."configuration WHERE name='PS_STOCK_MANAGEMENT'");
$row = mysqli_fetch_array($res);
$stock_management = $row['value'];

$arrayB = array();
$arrayC = array();
$arrayD = array();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Add Product Order n.<?php echo $_POST['id_order'] ?></title>
</head>
<body>
<form name="search_form" method="post" action="add-product.php">
  <label>Search
  <input name="search_txt" type="text" value="<?php echo $_POST['search_txt'] ?>" size="60"  />
  </label>
    <input type="submit" name="search" value="search" />
    <input name="id_order" type="hidden" value="<?php echo $_POST['id_order'] ?>" /> &nbsp; &nbsp; &nbsp; &nbsp; 
	Shop nr: <?php echo $id_shop."<input name=id_shop value=".$id_shop.">"; ?>
  <p>
    <label>Filter Language
    <select name="id_lang">
      
      <?php 
	  if ($_POST['id_lang']=="") $selected=' selected="selected" ';
	  echo '<option selected="selected" value="">all</option>';
	  $query=" select * from ". _DB_PREFIX_."lang ";
	  $res=dbquery($query);
	  while ($language=mysqli_fetch_array($res)) {
		$selected='';
	  	if ($language['id_lang']==$_POST['id_lang']) $selected=' selected="selected" ';
	        echo '<option  value="'.$language['id_lang'].'" '.$selected.'>'.$language['name'].'</option>';
	  }
	?>
        </select>
    </label>
 &nbsp; &nbsp; &nbsp; &nbsp; order <select name="order">
      <?php 
	  if ($_POST['order']=="id_product") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>id_product</option>';
	  if ($_POST['order']=="name") {$selected=' selected="selected" ';} else $selected="";
	  echo '<option '.$selected.'>name</option>';
	?>
</select>
<?php 
  if ($_POST['id_lang']!="") { /* we don't want many times the same category - so only show this if a language is selected */
	echo ' &nbsp; &nbsp; &nbsp; &nbsp; category <select name="id_category">';
	echo '<option value="">All categories</option>';
	$query=" select * from ". _DB_PREFIX_."category_lang WHERE id_lang=".$_POST['id_lang']." ORDER BY name";
	$res=dbquery($query);
	while ($category=mysqli_fetch_array($res)) {
		if ($category['id_category']==$_POST['id_category']) {$selected=' selected="selected" ';} else $selected="";
	        echo '<option  value="'.$category['id_category'].'" '.$selected.'>'.$category['name'].'</option>';
	}
	echo "</select>";
  }
?>
</p>
  <p>
    <br />
    </p>

<?php
if($_POST['id_lang']=="") {
  echo "No language was set! Please note that if you duplicated products the duplicats will have the name of the original in all the languages that you didn't change!<p />";
}
$query="select id_country,id_state from ". _DB_PREFIX_."orders o left join ". _DB_PREFIX_."address a on o.id_address_delivery=a.id_address";
$query.=" WHERE o.id_order ='".$_POST['id_order']."'";
$res=dbquery($query);
$row=mysqli_fetch_array($res);
$id_country = $row['id_country'];
$id_state = $row['id_state'];

$searchtext = "";
if (isset($_POST['search'])) 
  $searchtext = " AND (p.reference like '%".mysqli_real_escape_string($conn, $_POST['search_txt'])."%' or p.supplier_reference like '%".$_POST['search_txt']."%' or pl.name like '%".$_POST['search_txt']."%') ";
if ($_POST['id_lang']!="")
{ $lang1text=" and pl.id_lang='".$_POST['id_lang']."'";
  $lang2text=" and tl.id_lang='".$_POST['id_lang']."'";
  $langtext = "";
}
else
{ $lang1text=$lang2text = "";
  $langtext=' and pl.id_lang=tl.id_lang';
}
if ($_POST['order']=="id_product") $order="p.id_product";
else  $order="pl.name";
$catseg1=$catseg2="";
if ($_POST['id_category']!="") {
	$catseg1=" left join ". _DB_PREFIX_."category_product cp on p.id_product=cp.id_product";
	$catseg2=" AND cp.id_category=".$_POST['id_category'];
}
/* Note: we start with the query part after "from". First we count the total and then we take 100 from it */
$query = " from ". _DB_PREFIX_."product_shop ps left join ". _DB_PREFIX_."product_lang pl on ps.id_product=pl.id_product".$lang1text." AND pl.id_shop='".$id_shop."'";
$query.=" left join ". _DB_PREFIX_."product p on p.id_product=ps.id_product";
$query.=" left join ". _DB_PREFIX_."lang l on pl.id_lang=l.id_lang";
$query.=" left join ". _DB_PREFIX_."image i on i.id_product=p.id_product and i.cover=1";
$query.=" left join ". _DB_PREFIX_."tax_rule tr on tr.id_tax_rules_group=ps.id_tax_rules_group AND tr.id_country='".$id_country."' AND tr.id_state='".$id_state."'";
$query.=" left join ". _DB_PREFIX_."tax t on t.id_tax=tr.id_tax";
$query.=" left join ". _DB_PREFIX_."tax_lang tl on t.id_tax=tl.id_tax".$lang2text;

$query.=$catseg1;
$query.=" where ps.id_shop='".$id_shop."' ".$searchtext.$langtext.$catseg2; // the "true" serves to catch optional " AND " follow-up texts
// echo $query;
if($stock_management >0)
  echo "You have stock management enabled: quantities are shown for products and attributes.<br/>";
$res=dbquery("SELECT COUNT(*) AS rcount ".$query);
$row = mysqli_fetch_array($res);
$numrecs = $numshown = $row['rcount'];
echo "<table width='100%'><tr><td>Your search delivered ".$numrecs." records";
$numlimit = 100;
if($numrecs > $numlimit) { 
  $numshown = $numrecs - $_POST['offset'];
  if($numshown > $numlimit) $numshown = $numlimit;
  echo ": $numshown are shown<br>";
  echo 'Select the record number offset: <select name="offset">';
  if ($_POST['offset']==0) {$selected=' selected="selected" ';} else $selected=""; 
  echo '<option '.$selected.'>0</option>';
  $i=$numlimit;
  while ($i < $numrecs) { 
    if ($i==$_POST['offset']) {$selected=' selected="selected" ';} else $selected="";
    echo '<option '.$selected.'>'.$i.'</option>';
    $i=$i+$numlimit;
  }
  echo "</select> and click 'Search'";
}
  echo "<input type=hidden name=numshown value=".$numshown.">";
  echo "</td><td align=right>Show images <input type=checkbox checked name=imagehider onchange='hide_images(this)'>";
  echo '</form></td></tr></table>';
  echo '<form name=attriForm>
  <table width="100%" border="1" id="maintable">
  <tr>
    <td width="5%">ID</td>
    <td width="10%">Reference</td>
    <td width="5%">Lang</td>
    <td width="50%">Name</td>'; 
  if($stock_management > 0)
  { echo '<td width="2%">Quant</td>'; 
  }
  echo '<td width="15%">attributes</td>
    <td width="10%">Price</td>
    <td width="5%">Tax Value</td>
	<td width="5%">PriceInc</td>
    <td width="10%">Action</td>
    <td width="5%">Image</td>
  </tr>';

  $query .= " ORDER BY ".$order." LIMIT ".$_POST['offset'].",$numlimit";
$time_start = microtime(true);
  $res=dbquery("select ps.*,p.*, pl.name,pl.id_lang,l.iso_code,t.*,i.id_image ".$query);
$time_end = microtime(true);
$arrayB[] = ($time_end - $time_start);

// Begin loop
  if (mysqli_num_rows($res)>0) {
		while ($products=mysqli_fetch_array($res)) {
  $hasfunc = 0;
?>
  <tr>
    <td width="5%"><?php echo $products['id_product'] ?></td>
    <td width="10%"><?php echo $products['reference'] ?></td>
    <td width="10%"><?php echo $products['iso_code'] ?></td>
    <td width="50%"><?php echo $products['name'] ?></td>
<?php	
  if($stock_management > 0)
  { echo '<td width="2%">'.$products['quantity'].'</td>'; 
  }
  echo '<td>';
  $aquery = "SELECT id_product_attribute FROM ". _DB_PREFIX_."product_attribute WHERE id_product=".$products['id_product']." LIMIT 1";
  $ares=dbquery($aquery);
  if(mysqli_num_rows($ares) != 0) {
	$hasfunc = 1;
	$aquery = "SELECT pa.id_product_attribute,pa.price,pa.weight, l.name,a.id_attribute_group,pa.quantity from ". _DB_PREFIX_."product_attribute pa";
	$aquery .= " LEFT JOIN ". _DB_PREFIX_."product_attribute_combination c on pa.id_product_attribute=c.id_product_attribute";
	$aquery .= " LEFT JOIN ". _DB_PREFIX_."attribute a on a.id_attribute=c.id_attribute";
	$aquery .= " LEFT JOIN ". _DB_PREFIX_."attribute_lang l on l.id_attribute=c.id_attribute AND l.id_lang='".$_POST['id_lang']."'";
	$aquery .= " WHERE id_product='".$products['id_product']."' ORDER BY pa.id_product_attribute,a.id_attribute_group";
	$time_start = microtime(true);
	$ares=dbquery($aquery);
	$time_end = microtime(true);
	$arrayC[] = ($time_end - $time_start);
	echo "<select name='attribs".$products['id_product']."'>";
	$lastgroup = "";
	while ($row=mysqli_fetch_array($ares)) {
		if($lastgroup != $row['id_product_attribute']) {
			if($lastgroup != "")
				echo "</option>";
			echo "<option value='".$row['id_product_attribute']."' price='".$row['price']."' weight='".$row['weight']."'>".$row['name'];
			if($stock_management >0)
			  echo " (q:".$row['quantity'].")";
			$lastgroup = $row['id_product_attribute'];
		}
		else
			echo " - ".$row['name'];
	}
	echo "</option></select>";	
  }
?>	
    </td>
    <td><?php echo round($products['price'],3) ?></td>
    <td><?php echo (float)$products['rate'] ?>%</td>
	<td><?php echo number_format($products['price']*(1+($products['rate']/100)),2,'.',''); ?></td>
    <td><div align="center"><a href="order-edit.php?action=add-product&id_lang=<?php echo $products['id_lang'] ?>&id_order=<?php echo $_POST['id_order'] ?>&id_product=<?php echo $products['id_product'] ?>" <?php if($hasfunc > 0) echo "onclick=\"add_attribute(this, '".$products['id_product']."');\""; ?> ><nobr>add product</nobr></a></div></td>
    <td><?php echo get_product_image($products['id_image'],''); ?></td>
  </tr>

    <?php
	}
	} else {
	echo "<strong>products not found</strong>";
	}

?>
</form>
</table>
  <script>
   function add_attribute(obj, id_product) {
	var sel = eval("document.attriForm.attribs"+id_product);
	var attrib = sel.options[sel.selectedIndex].value;
	var name = sel.options[sel.selectedIndex].text;
	var price = sel.options[sel.selectedIndex].getAttribute('price');
	var weight = sel.options[sel.selectedIndex].getAttribute('weight');
	obj.href = obj.href+"&attribute="+attrib;
	obj.href = obj.href+"&attname="+name;
	obj.href = obj.href+"&attprice="+price;
	obj.href = obj.href+"&attweight="+weight;
   }
   
   function hide_images(elt)
   { var num = document.search_form.numshown.value;
     var tabl = document.getElementById("maintable");
	 for(i=0; i<=num; i++)
	 {	if(elt.checked)
			tabl.tBodies[0].rows[i].cells[9].style.display="table-cell";
		else
			tabl.tBodies[0].rows[i].cells[9].style.display="none";
	 }
   }
  </script>
</body>
</html>