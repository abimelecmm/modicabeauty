<?php
 //   print_r($GLOBALS);
	$mysups = $GLOBALS['mysups'.$x];
	$old_suppliers = $GLOBALS['old_suppliers'.$x];
	if((strlen($mysups) > 0) || (strlen($old_suppliers) > 0))
	{ $suppliers = explode(",", substr($mysups, 1));
	  $oldsuppliers = explode(",", $old_suppliers);
	  $diff1 = array_diff($oldsuppliers, $suppliers);
	  foreach($diff1 AS $dif)
	  { $dquery = "DELETE from ". _DB_PREFIX_."product_supplier WHERE id_product='".$id_product."' AND id_supplier='".$dif."'";
	    $dres=dbquery($dquery);
	  }
	  $query="select value from ". _DB_PREFIX_."configuration";
	  $query .= " WHERE name='PS_CURRENCY_DEFAULT'";
	  $res=dbquery($query);
	  $row = mysqli_fetch_array($res);
      $id_currency = $row['value'];

	  /* the product can have got both new suppliers and new attributes */
	  $supplier_attribs = $GLOBALS['supplier_attribs'.$x];
	  $attributes = explode(",", $supplier_attribs);
	  foreach($suppliers AS $supplier)
	  { $zerofound=0;
	    foreach($attributes AS $attribute)
		{ if($attribute == 0) $zerofound = 1;
		  $price = $GLOBALS['supplier_price'.$attribute.'t'.$supplier.'s'.$x];
		  $reference = $GLOBALS['supplier_reference'.$attribute.'t'.$supplier.'s'.$x];
		  $uquery = 'INSERT into '. _DB_PREFIX_.'product_supplier SET product_supplier_price_te="'.mysqli_real_escape_string($conn, $price).'",  product_supplier_reference="'.mysqli_real_escape_string($conn, $reference).'",';
		  $uquery .= ' id_product="'.$id_product.'", id_product_attribute="'.$attribute.'", id_supplier="'.$supplier.'", id_currency='.$id_currency;
		  $uquery .= ' ON DUPLICATE KEY UPDATE  product_supplier_price_te="'.mysqli_real_escape_string($conn, $price).'",  product_supplier_reference="'.mysqli_real_escape_string($conn, $reference).'"';
		  $ures=dbquery($uquery);
		}
		if($zerofound == 0)
		{ $uquery = 'INSERT into '. _DB_PREFIX_.'product_supplier SET product_supplier_price_te="0.000000",  product_supplier_reference="",';
		  $uquery .= ' id_product="'.$id_product.'", id_product_attribute="0", id_supplier="'.$supplier.'", id_currency='.$id_currency;
		  $uquery .= ' ON DUPLICATE KEY UPDATE product_supplier_price_te="0.000000",  product_supplier_reference=""';
		  $ures=dbquery($uquery);
		}
	  }
	}
?>
