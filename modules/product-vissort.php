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
<title>Prestashop Product Visual Sort</title>
<link rel="stylesheet" href="style1.css" type="text/css" />
<style>
option.defcat {background-color: #ff2222;}
input.posita {width: 50px; text-align:right}
p.prodname { margin-top:0px; margin-bottom:0px; text-align:center; color:#888888; font-size:3pt; }
p.stats { margin-top:0px; margin-bottom:-2px; text-align:center; color:#888888; font-size:8pt; }
</style>
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
</head>

<body>
<?php
print_menubar();
echo '<table style="width:100%" ><tr><td width="70%" valign="top">';
echo '<h3 style="margin-bottom:0;">Prestashop Visual Product Sort</h3>';
echo 'This script does not display inactive products<br>';
echo 'When you submit all inactive products will be transfered to the bottom of the page.</td>';
echo '<td><div id=tmp></td>';
echo '<td style="text-align:right; width:30%"><iframe name=tank width="230" height="95"></iframe></td></tr></table>';
?>

<table class="triplesearch"><tr><td width="67%">
<form name="search_form" method="get">
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
	echo '</select><br>';
	
	if(isset($_GET["colcount"]))
	  $colcount = $_GET["colcount"];
	else 
	  $colcount = 3;
	echo 'Number of items on one row <select name=colcount>';
    for($i=2; $i<=7; $i++)
	{ $selected = "";
	  if ($i == $colcount)
	    $selected = " selected";
	  echo "<option".$selected.">".$i."</option>";
	}
	echo "</select>";
	
	if(isset($_GET["rowcount"]))
	  $rowcount = $_GET["rowcount"];
	else 
	  $rowcount = 4;
	echo ' &nbsp; &nbsp; No of rows on page: <input size=1 name=rowcount value="'.$rowcount.'">';
	
/* Get shop number */
	if(isset($_GET["id_shop"]))
	  $id_shop = $_GET["id_shop"];
	else 
	  $id_shop = 1;
	$query="select id_shop,name from ". _DB_PREFIX_."shop ORDER BY id_shop";
	$res=dbquery($query);
	echo '<br>Shop <select name="id_shop">';
	while ($shop=mysqli_fetch_array($res)) 
	{	if ($shop['id_shop']==$id_shop) {$selected=' selected="selected" ';} else $selected="";
	    echo '<option  value="'.$shop['id_shop'].'" '.$selected.'>'.$shop['id_shop']."-".$shop['name'].'</option>';
	}	
	echo "</select> A product being active can be set by shop.";
	
	echo '<br>Language: <select name="id_lang" style="margin-top:5px">';
	  $query=" select * from ". _DB_PREFIX_."lang ";
	  $res=dbquery($query);
	  while ($language=mysqli_fetch_array($res)) {
		$selected='';
	  	if ($language['id_lang']==$id_lang) $selected=' selected="selected" ';
	        echo '<option  value="'.$language['id_lang'].'" '.$selected.'>'.$language['name'].'</option>';
	  }
	echo '</select><br>';
	
	/* Get image type (=extension */
	if(isset($_GET["imgtype"]))
	  $imgtype = $_GET["imgtype"];
	else 
	  $imgtype = "home_default";
	$query = "SELECT name,width,height from ". _DB_PREFIX_."image_type WHERE products=1";
	$res=dbquery($query);
	echo 'Select an image type: <select name="imgtype">';
	while($row = mysqli_fetch_array($res))
	{ $selected='';
	  if ($row['name']==$imgtype) $selected=' selected="selected" ';
	    echo '<option '.$selected.'>'.$row['name'].'</option>';
	}		
	echo '</select>';
	echo '</td><td width="50%" align=center rowspan="2"><input type=submit value=search></td></tr>';
	echo '<tr><td>Statistics: Period (yyyy-mm-dd): <input size=5 name=startdate value='.$input['startdate'].'> till <input size=5 name=enddate value='.$input['enddate'].'>';
	echo '<br>The figures show: nr - total sales - nr of orders - nr of buyers - price</td>';
	echo '</tr></table></form>';	
	echo '<hr/>';
	
/* First we look how many active products there are */
	$query = "select count(*) AS activecount"; /* note: you cannot write here t.* as t.active will overwrite p.active without warning */
	$query .= " from ". _DB_PREFIX_."product p left join ". _DB_PREFIX_."product_lang pl on p.id_product=pl.id_product AND pl.id_lang='".(int)$id_lang."'";
	$query.=" left join ". _DB_PREFIX_."product_shop ps on p.id_product=ps.id_product and ps.id_shop='".$id_shop."'";
	$query.=" left join ". _DB_PREFIX_."image i on i.id_product=p.id_product and i.cover=1";
	$query.=" left join ". _DB_PREFIX_."category_product cp on p.id_product=cp.id_product";
  $query .= " WHERE cp.id_category=".$id_category." AND ps.active=1";
  $res=dbquery($query);
  $row = mysqli_fetch_array($res);
  $activerecs = $row["activecount"];

	$query = "select p.*,pl.*,i.id_image,cp.position,ps.active AS psactive, t.rate, revenue, r.quantity AS salescount, ordercount, buyercount "; /* note: you cannot write here t.* as t.active will overwrite p.active without warning */
	$query .= " from ". _DB_PREFIX_."product p left join ". _DB_PREFIX_."product_lang pl on p.id_product=pl.id_product AND pl.id_lang='".(int)$id_lang."'";
	$query.=" left join ". _DB_PREFIX_."product_shop ps on p.id_product=ps.id_product and ps.id_shop='".$id_shop."'";
	$query.=" left join ". _DB_PREFIX_."image i on i.id_product=p.id_product and i.cover=1";
	$query.=" left join ". _DB_PREFIX_."category_product cp on p.id_product=cp.id_product";
	$query.=" left join ". _DB_PREFIX_."tax_rule tr on tr.id_tax_rules_group=ps.id_tax_rules_group AND tr.id_country='".(int)$id_country."'";
	$query.=" left join ". _DB_PREFIX_."tax t on t.id_tax=tr.id_tax";
if(1)
{ $query .= " LEFT JOIN ( SELECT product_id, SUM(product_quantity)-SUM(product_quantity_return) AS quantity, ROUND(SUM((product_quantity-product_quantity_return)*(product_price-reduction_amount)*(100-reduction_percent)*(100+tax_rate)/10000),2) AS revenue, count(DISTINCT d.id_order) AS ordercount, count(DISTINCT o.id_customer) AS buyercount FROM ". _DB_PREFIX_."order_detail d";
  $query .= " LEFT JOIN ". _DB_PREFIX_."orders o ON o.id_order = d.id_order";
  $query .= " WHERE true";
  if($input['startdate'] != "")
    $query .= " AND TO_DAYS(o.date_add) > TO_DAYS('".$input['startdate']."')";
  if($input['enddate'] != "")
    $query .= " AND TO_DAYS(o.date_add) < TO_DAYS('".$input['enddate']."')";
  $query .= " AND o.valid=1";
  $query .= " GROUP BY d.product_id ) r ON p.id_product=r.product_id";
}
  $query .= " WHERE cp.id_category=".$id_category;
  $query .= " ORDER BY ps.active DESC, position";
  $res=dbquery($query);
  $numrecs = mysqli_num_rows($res);
  $previous_order = array();
  $highestposition = 0;
  
  echo '<form name=ListForm><table border=1 class="tripleswitch" width:100%;"><tr><td>';
  echo "This category contains ".$numrecs." products of which ".$activerecs." are active.";
  echo "</td><td><input type=checkbox name=verbose>verbose &nbsp; &nbsp; <input type=button value='Submit all' onClick='return SubmitForm();'></td></tr></table></form>";
  echo "<hr>";
//echo $query;

echo "<script>
var colcount=".$colcount.";
var reccount=".$numrecs.";
var activecount=".$activerecs.";

function SubmitForm()
{ for(var i=0; i<reccount; i++)
  { field = eval('Mainform.id_product'+i);
    if(field.value == previous_order[i]) /* remove unchanged row positions */
	{ field.remove();
	}
  }
  Mainform.verbose.value = ListForm.verbose.checked;
  Mainform.action = 'product-proc.php';
  Mainform.submit();
}

function exchange(elt)
{ var from = +elt.parentNode.id.substring(4);
  var distance = +eval('Mainform.distance'+from+'.value');
  var to = from+distance;
  if(to < 0) to = 0;
  if(to >= activecount) to = activecount-1;
  if(from == to) return;
  var from_obj = document.getElementById('tdid'+from);
  var to_obj = document.getElementById('tdid'+to);
  var tmpdiv = from_obj.childNodes[0].innerHTML;
  from_obj.childNodes[0].innerHTML = to_obj.childNodes[0].innerHTML;
  from_obj.childNodes[0].childNodes[1].name = 'id_product'+from; /* id_product field */
  to_obj.childNodes[0].innerHTML = tmpdiv;
  to_obj.childNodes[0].childNodes[1].name = 'id_product'+to; /* id_product field */
}

function move_left(elt)
{ var from = +elt.parentNode.id.substring(4);
  var distfield = eval('Mainform.distance'+from);
  var distance = +distfield.value;
  if(distance <= 0)
  { alert('distance must be a positive value');
    return;
  }
  var to = from-distance;
  if(to < 0) to = 0;
  if(from == to) return;
  var from_obj = document.getElementById('tdid'+from);

  var tmpdiv = from_obj.childNodes[0].innerHTML;
  for(i=from-1; i>=to; i--)
  { var to_obj = document.getElementById('tdid'+i);
    from_obj.childNodes[0].innerHTML = to_obj.childNodes[0].innerHTML;
	from_obj.childNodes[0].childNodes[1].name = 'id_product'+(i+1); /* id_product field */
	from_obj = to_obj;
  }
  to_obj.childNodes[0].innerHTML = tmpdiv;
  to_obj.childNodes[0].childNodes[1].name = 'id_product'+(i+1); /* id_product field */
  distfield.value = 1;
}

function move_up(elt)
{ var from = +elt.parentNode.id.substring(4);
  var distfield = eval('Mainform.distance'+from);
  var distance = +distfield.value;
  if(distance <= 0)
  { alert('distance must be a positive value');
    return;
  }
  if(from < colcount) return;
  var from_obj = document.getElementById('tdid'+from);
  var tmpdiv = from_obj.childNodes[0].innerHTML;
  
  for(i=1; i<=distance; i++)
  { x = from - (i * colcount);
    if(x < 0) break;
    var to_obj = document.getElementById('tdid'+x);
    from_obj.childNodes[0].innerHTML = to_obj.childNodes[0].innerHTML;
	from_obj.childNodes[0].childNodes[1].name = 'id_product'+(x + colcount); /* id_product field */
	from_obj = to_obj;
  }
  if(i>distance) x = from - (i * colcount);	/* when the loop ran out we missed this statement */
  to_obj.childNodes[0].innerHTML = tmpdiv;
  to_obj.childNodes[0].childNodes[1].name = 'id_product'+(x+colcount); /* id_product field */
  distfield.value = 1;
}

function move_down(elt)
{ var from = +elt.parentNode.id.substring(4);
  var distfield = eval('Mainform.distance'+from);
  var distance = +distfield.value;
  if(distance <= 0)
  { alert('distance must be a positive value');
    return;
  }
  if(from > (activecount - colcount -1)) return;
  var from_obj = document.getElementById('tdid'+from);
  var tmpdiv = from_obj.childNodes[0].innerHTML;
  for(i=1; i<=distance; i++)
  { x = from + (i * colcount);
    if(x >= activecount) break;
    var to_obj = document.getElementById('tdid'+x);
    from_obj.childNodes[0].innerHTML = to_obj.childNodes[0].innerHTML;
	from_obj.childNodes[0].childNodes[1].name = 'id_product'+(x - colcount); /* id_product field */
	from_obj = to_obj;
  }
  if(i>distance) x = from + (i * colcount);	/* when the loop ran out we missed this statement */
  to_obj.childNodes[0].innerHTML = tmpdiv;
  to_obj.childNodes[0].childNodes[1].name = 'id_product'+(x - colcount); /* id_product field */
  distfield.value = 1;
}

function move_right_old(elt)
{ var from = 2;
  var to = 3;
  var from_obj = document.getElementById('tdid2');
  var to_obj = document.getElementById('tdid3');
  tmpdiv.appendChild(from_obj.childNodes[0].childNodes[0]);
  from_obj.childNodes[0].appendChild(to_obj.childNodes[0].childNodes[0]);
  to_obj.childNodes[0].appendChild(tmpdiv.childNodes[0]);
}

function move_right(elt)
{ var from = +elt.parentNode.id.substring(4);
  var distfield = eval('Mainform.distance'+from);
  var distance = +distfield.value;
  if(distance <= 0)
  { alert('distance must be a positive value');
    return;
  }
  var to = from+distance;
  if(to >= activecount) to = activecount-1;
  if(from == to) return;
  var from_obj = document.getElementById('tdid'+from);
  var tmpdiv = from_obj.childNodes[0].innerHTML;
  for(i=from+1; i<=to; i++)
  { var to_obj = document.getElementById('tdid'+i);
    from_obj.childNodes[0].innerHTML = to_obj.childNodes[0].innerHTML;
	from_obj.childNodes[0].childNodes[1].name = 'id_product'+(i-1); /* id_product field */
	from_obj = to_obj;
  }
  to_obj.childNodes[0].innerHTML = tmpdiv;
  to_obj.childNodes[0].childNodes[1].name = 'id_product'+(i-1); /* id_product field */  
  distfield.value = 1;
}

</script>";
  // "*********************************************************************";

  echo '<form name="Mainform" method=post>
	<input type=hidden name=id_lang value="'.$id_lang.'">
	<input type="hidden" name="id_category" value="'.$id_category.'">
	<input type="hidden" name="methode" value="vissort">
	<input type="hidden" name="reccount" value="'.$numrecs.'"><input type=hidden name=verbose>
	<table id="Maintable" class="triplemain">';

  $base_uri = get_base_uri();
  $x=0;
  $rownumber = 0;
  while ($datarow=mysqli_fetch_array($res)) 
  { $previous_order[$datarow["position"]] = $datarow['id_product'];
    if($datarow["position"] > $highestposition)
	  $highestposition = $datarow["position"];
    if($datarow["psactive"] == "1")
    { if(!($x % $colcount))
	  { $rownumber++;
	    $accent = "";
	    if($rownumber > $rowcount) $accent = ' style="background-color:#FFFF55"';
	    echo "<tr ".$accent.">";
	  }
	  $id_image = $datarow['id_image'];
	  echo '
	  <td id="tdid'.$x.'">';
	  echo '<div>';
/* Note: the next line is broken to make relevant data easily visible in source code. Note that if there is a space between the link and the input the input will become childNodes[2] when the id is assigned */
	  echo '<a href="'.$base_uri.'img/p'.getpath($id_image).'/'.$id_image.'.jpg" target="_blank" title="'.$datarow['name'].'"><img src="'.$base_uri.'img/p'.getpath($id_image).'/'.$id_image.'-'.$imgtype.'.jpg"  /></a
	  ><input type=hidden name="id_product'.$x.'" value="'.$datarow['id_product'].'">';
	  echo '<p class="prodname">'.$datarow['name'].'</p>';
	  $price = number_format(((($datarow['rate']/100) +1) * $datarow['price']),2, '.', '');
	  echo '<p class="stats">'.$x.' - '.$datarow['revenue'].' - '.$datarow['ordercount'].'-'.$datarow['buyercount'].' - '.$price.'</p>';
	  echo '</div>';
	  echo '<a href="" onclick="move_left(this); return false;" border=0><img src="left.gif"></a> ';
	  echo '<a href="" onclick="move_up(this); return false;" border=0><img src="up.gif"></a> ';
	  echo '<input name=distance'.$x.' size=1 value="1"> ';
	  echo '<a href="" onclick="exchange(this); return false;" border=0><img src="xchange.gif"></a>';
	  echo '<a href="" onclick="move_down(this); return false;" border=0><img src="down.gif"></a> ';
	  echo '<a href="" onclick="move_right(this); return false;" border=0><img src="right.gif"></a>';
	  echo "</td>";
	  $x++;
	  if(!($x % $colcount))
	    echo "</tr>";
	}
	else
	{ echo '
	<input type=hidden name="id_product'.$x.'" value="'.$datarow['id_product'].'">';
	  $x++;
	}
  }  
  
  echo '</table>
	</table></form>
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
  include "footer.php";
  echo '</body></html>';

?>
