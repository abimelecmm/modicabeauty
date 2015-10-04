<?php
if(!@include 'approve.php') die( "approve.php was not found!");

//$verbose = true;

$rewrite_settings = get_rewrite_settings();

$query="select value from ". _DB_PREFIX_."configuration  WHERE name='PS_COUNTRY_DEFAULT'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$id_country = $row["value"];

if(!isset($_GET['id_lang']) || $_GET['id_lang'] == "") {
	$query="select value, l.name from ". _DB_PREFIX_."configuration f, ". _DB_PREFIX_."lang l";
	$query .= " WHERE f.name='PS_LANG_DEFAULT' AND f.value=l.id_lang";
	$res=dbquery($query);
	$row = mysqli_fetch_array($res);
	$id_lang = $row['value'];
}
else
  $id_lang = intval($_GET['id_lang']);
  
if(!isset($_GET['id_shop']) || $_GET['id_shop'] == "")
  $id_shop = 1;
else 
  $id_shop = intval($_GET['id_shop']);

$error = "";
if(isset($_GET['id_product']) && ($_GET['id_product'] != ""))
{ $id_product = intval($_GET['id_product']);
  $query="select * from ". _DB_PREFIX_."product";
  $query .= " WHERE id_product='".$id_product."'";
  $res=dbquery($query);
  if(mysqli_num_rows($res) == 0)
    $error = $id_product." is not a valid product id";
}
else 
{ $error = "Please provide a product id!";
  $id_product = "";
}
if($error == "")
{ $aquery="select * from ". _DB_PREFIX_."product_attribute";
  $aquery .= " WHERE id_product='".$id_product."'";
  $resa=dbquery($aquery);
  if(mysqli_num_rows($resa) == 0)
    $error = $id_product." has no attribute combinations";
}
if($error == "")
{ $squery="select price from ". _DB_PREFIX_."product_shop";
  $squery .= " WHERE id_product='".$id_product."' AND id_shop='".$id_shop."'";
  $ress=dbquery($squery);
  if(mysqli_num_rows($ress) == 0)
    $error = $id_product." is not in this shop";
  else
  { $row = mysqli_fetch_array($ress);
	$product_price = $row["price"];
  }
}
if($error == "")
{ $nquery="select name from ". _DB_PREFIX_."product_lang";
  $nquery .= " WHERE id_product='".$id_product."' AND id_shop='".$id_shop."' AND id_lang='".$id_lang."'";
  $resn=dbquery($nquery);
  if(mysqli_num_rows($resn) == 0)
    $error = $id_product." is not in this shop for this language";
  else
  { $row = mysqli_fetch_array($resn);
    $product_name = $row["name"];
  }
}
if($error == "")
{ $query = "SELECT rate,name,tr.id_tax_rule,g.id_tax_rules_group FROM "._DB_PREFIX_."tax_rule tr";
  $query .= " LEFT JOIN "._DB_PREFIX_."tax t ON (t.id_tax = tr.id_tax)";
  $query .= " LEFT JOIN "._DB_PREFIX_."tax_rules_group g ON (tr.id_tax_rules_group = g.id_tax_rules_group)";
  $query .= " LEFT JOIN "._DB_PREFIX_."product_shop ps on g.id_tax_rules_group=ps.id_tax_rules_group";
  $query .= " WHERE ps.id_product='".$id_product."' AND tr.id_country = '".$id_country."'";
  $res=dbquery($query);
  $row = mysqli_fetch_array($res);
  $VAT_rate = $row["rate"];
}

/* get shop group and its shared_stock status */
$query="select s.id_shop_group, g.share_stock, g.name from ". _DB_PREFIX_."shop s, "._DB_PREFIX_."shop_group g";
$query .= " WHERE s.id_shop_group=g.id_shop_group and id_shop='".$id_shop."'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$id_shop_group = $row['id_shop_group'];
$share_stock = $row["share_stock"];
$shop_group_name = $row["name"];
 
  define("LEFT", 0); define("RIGHT", 1); // align
  define("HIDE", 0); define("SHOW", 1); // hide by default?
  $combifields = array(
    array("id_product_attribute",RIGHT,SHOW),
	array("name", RIGHT,SHOW),
	array("wholesale_price",RIGHT,HIDE),
	array("price",RIGHT,SHOW),
	array("priceVAT",RIGHT,SHOW),
	array("ecotax",RIGHT,HIDE),
	array("weight", RIGHT,SHOW),
	array("unit_price_impact",RIGHT,HIDE),
	array("default_on", RIGHT,SHOW),
	array("minimal_quantity",RIGHT,HIDE),
	array("available_date",RIGHT,HIDE),
	
	array("reference",RIGHT,SHOW),
	array("supplier_reference",RIGHT,HIDE),
	array("location", RIGHT,HIDE),
	array("ean",RIGHT,HIDE),
	array("upc",RIGHT,HIDE),
	array("quantity", RIGHT,SHOW),
	array("image",RIGHT,SHOW));
  $numfields = sizeof($combifields); /* number of fields */
  
/* make image block: legends for multi-image */
  $query = "SELECT i.id_image,legend FROM "._DB_PREFIX_."image i";
  $query .= " LEFT JOIN "._DB_PREFIX_."image_lang l on i.id_image=l.id_image AND l.id_lang='".$id_lang."'";
  $query .= " WHERE i.id_product='".$id_product."'";
  $res=dbquery($query);
  $allimgs = array();
  $x=0;
  $imageblock0 = '<input type=hidden name="cimagesCQX">';
  $imageblock0 .= '<table cellspacing=8><tr><td><select id="imagelistCQX" size=4 multiple>';
  $imageblock1 = "";
  while ($row=mysqli_fetch_array($res)) 
  { $imageblock1 .= '<option value="'.$row['id_image'].'">'.str_replace("'","\'",$row['legend']).'</option>';
  } 
  $imageblock1 .= '</select>';
  $imageblock2 = '</td><td><a href=# onClick=" Addimage(\'CQX\'); reg_change(this); return false;"><img src=add.gif border=0></a><br><br>';
  $imageblock2 .= '<a href=# onClick="Removeimage(\'CQX\'); reg_change(this); return false;"><img src=remove.gif border=0></a></td><td><select id=imageselCQX size=3><option>none</option></select></td></tr></table>';
 
  /* make legend block */
  $query = "SELECT i.id_image,legend FROM "._DB_PREFIX_."image i";
  $query .= " LEFT JOIN "._DB_PREFIX_."image_lang l on i.id_image=l.id_image AND l.id_lang='".$id_lang."'";
  $query .= " WHERE i.id_product='".$id_product."'";
  $res=dbquery($query);
  $legendblock = "";
  $emptylegendfound = false;
  while($row = mysqli_fetch_array($res))
  { $legendblock .= "<option value=".$row["id_image"].">".$row["legend"]."</option>";
    if($row["legend"]=="") $emptylegendfound = true;
  }
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Prestashop Combination Multiedit</title>
<link rel="stylesheet" href="style1.css" type="text/css" />
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<script type="text/javascript">
<?php
  $silencers = ""; /* take care of invisible fields */
  for($i=0; $i<sizeof($combifields); $i++)
  { if($combifields[$i][2] == HIDE)
      $silencers .= ',"'.($i+1).'"';
  }
  echo "silencers = new Array(".substr($silencers,1).");\r\n";
?>
var legendblock = <?php echo json_encode($legendblock); ?>;
var imageblock0 = <?php echo json_encode($imageblock0); ?>;
var imageblock1 = <?php echo json_encode($imageblock1); ?>;
var imageblock2 = <?php echo json_encode($imageblock2); ?>;
parts_stat = 0;
desc_stat = 0;
trioflag = false; /* check that only one of price, priceVAT and VAT is editable at a time */
function switchDisplay(id, elt, fieldno, val)  // collapse(field)
{ var tmp, tmp2, val, checked;
  var advanced_stock = false;
  if(val == '0') /* hide */
  { var tbl= document.getElementById(id).parentNode;
    for (var i = 0; i < tbl.rows.length; i++)
	  if(tbl.rows[i].cells[fieldno])
	    tbl.rows[i].cells[fieldno].style.display='none';
  }
  if((val == '1') || (val=='2')) /* 1 = show */
  { var tbl= document.getElementById(id).parentNode;
    for (var i = 0; i < tbl.rows.length; i++) 
	  if(tbl.rows[i].cells[fieldno])
	    tbl.rows[i].cells[fieldno].style.display='table-cell';
  }
  if(val=='2') /* 2 = edit */
  { tab = document.getElementById('Maintable');
    var tblEl = document.getElementById(id);
    field = tab.tHead.rows[1].cells[fieldno].children[0].innerHTML;
    if((trioflag == true) && ((field == "price") || (field == "priceVAT")))
    { alert("You may edit only one of the two fields at a time: price and priceVAT");
      return;
    }
    if((field == "price") || (field == "priceVAT"))
      trioflag = true;
	if(field == "default_on")
	{ var fieldnr = tbl.rows[1].cells.length - 1;
	  for (var i = 0; i < tbl.rows.length; i++)
		if(tbl.rows[i].cells[fieldnr])
			tbl.rows[i].cells[fieldnr].style.display='none';
	  alert("Please use the Submit All button to submit changes to the default field!");
	}
    for(var i=0; i<tblEl.rows.length; i++)
    { if(!tblEl.rows[i].cells[fieldno]) continue; 
	  tmp = tblEl.rows[i].cells[fieldno].innerHTML;
      tmp2 = tmp.replace("'","\'");
      row = tblEl.rows[i].cells[1].childNodes[0].name.substring(20); /* fieldname id_product_attribute7 => 7 */
      if(field=="priceVAT") 
      { tblEl.rows[i].cells[fieldno].innerHTML = '<input name="'+field+row+'" value="'+tmp2+'" onchange="priceVAT_change(this)" />';
		priceVAT_editable = true;
	  }
      else if(field=="price") 
      { tblEl.rows[i].cells[fieldno].innerHTML = '<input name="showprice'+row+'" value="'+tmp2+'" onchange="price_change(this)" />';
		price_editable = true;
	  }
      else if(field=="default_on") 	  
	  { if(tmp==1) checked="checked"; else checked="";
	    tblEl.rows[i].cells[fieldno].innerHTML = '<input type=hidden name="'+field+row+'" id="'+field+row+'" value="0" /><input type=checkbox name="'+field+row+'" id="'+field+row+'" onchange="default_change(this);" value="1" '+checked+' />';
	  }
      else if(field=="image") 	  
	  { <?php if($emptylegendfound)
				echo "alert('Please make sure that all images of this product have legends!'); return;";
		?>
		var res = tmp.match(/(\d+)\.jpg/);
		if(res)
			var id_image = res[1];
		else 
			var id_image = "XEARQ"; /* atches with nothing */
		var tagger =  new RegExp("="+id_image+">", "g");
		var legendblock2 = legendblock.replace(tagger,'='+id_image+' selected>');
		tblEl.rows[i].cells[fieldno].innerHTML = '<select name="image'+row+'"><option value=0>Select an image</option>'+legendblock2+'</select>';
	  }
	  else if(field=="quantity")
	  { if(tblEl.rows[i].cells[fieldno].style.backgroundColor == "yellow")
		{ advanced_stock = true;
		  continue;
		}
	  }
      else
        tblEl.rows[i].cells[fieldno].innerHTML = '<input name="'+field+row+'" value="'+tmp2+'" onchange="reg_change(this);" />';
    }
    tmp = elt.parentElement.innerHTML;
    tmp = tmp.replace(/<br.*$/,'');
    elt.parentElement.innerHTML = tmp+"<br><br>Edit";
  }
  if(val=='3') /* 3 = multi-edit of image */
  { var tblEl = document.getElementById(id);
    for(var i=0; i<tblEl.rows.length; i++)
    { if(!tblEl.rows[i].cells[fieldno]) continue;
	  tmp = tblEl.rows[i].cells[fieldno].innerHTML;		
 	  image_arr = [];
	  if(res = tmp.match(/(\d+)\.jpg/g))
	  { for(var j=0; j<res.length;j++)
	    { image_arr[image_arr.length] = res[j].substring(0,res[j].indexOf("."));
		}
	  }
	  images = image_arr.join();
      row = tblEl.rows[i].cells[1].childNodes[0].name.substring(20); /* fieldname id_product_attribute7 => 7 */
	  tblEl.rows[i].cells[fieldno].innerHTML = (imageblock0.replace(/CQX/g, row))+imageblock1+(imageblock2.replace(/CQX/g, row));
	  fillImages(row,images);
	}
	tmp2 = elt.parentElement.innerHTML;
    tmp2 = tmp2.replace(/<br.*$/,'');
    elt.parentElement.innerHTML = tmp2+"<br>Multi<br>Edit";
  }
  var warning = "";
  if(advanced_stock)
    warning += "Quantity fields of combinations with advanced stock keeping - marked in yellow - cannot be changed.";
  var tmp = document.getElementById("warning");
  tmp.innerHTML = warning;
  return;
}

function fillImages(idx,tmp)
{ var list = document.getElementById('imagelist'+idx);
  var sel = document.getElementById('imagesel'+idx);
  var imgs = tmp.split(','); 
  for(var i=0; i< imgs.length; i++)
  { for(var j=0; j< list.length; j++)
	{ if(list.options[j].value == imgs[i])
	  { list.selectedIndex = j;
		Addimage(idx);
	  }
	}
  }
}

function Addimage(idx)
{ var list = document.getElementById('imagelist'+idx);
  var sel = document.getElementById('imagesel'+idx);
  var listindex = list.selectedIndex;
  if(listindex==-1) return; /* none selected */
  var i, max = sel.options.length;
  img = list.options[listindex].text;
  img_id = list.options[listindex].value;
  list.options[listindex]=null;
  i=0;
  var base = sel.options;
  if(base[0].text=='none')
    base[0] = new Option(img);
  else
  { while((i<max) && (img > base[i].text)) i++;
    if(i==max)
      base[max] = new Option(img);
    else
    { newOption = new Option(img);
      if (document.createElement && (newOption = document.createElement('option'))) 
      { newOption.appendChild(document.createTextNode(img));
	  }
      sel.insertBefore(newOption, base[i]);
    }
  }
  base[i].value = img_id;
  var myimgs = eval("document.Mainform.cimages"+idx);
  myimgs.value = myimgs.value+','+img_id;
}

function Removeimage(idx)
{ var list = document.getElementById('imagelist'+idx);
  var sel = document.getElementById('imagesel'+idx);
  var selindex = sel.selectedIndex;
  if(selindex==-1) return; /* none selected */
  var i, max = list.options.length;
  img = sel.options[selindex].text;
  img_id = sel.options[selindex].value;
  classname = sel.options[selindex].className;
  if(img=='none') return;
  if(sel.options.length == 1)
    sel.options[0] = new Option('none');
  else
    sel.options[selindex]=null;
  i=0;
  while((i<max) && (img > list.options[i].text)) i++;
  if(i==max)
    list.options[max] = new Option(img);
  else
  { newOption = new Option(img);
    if (document.createElement && (newOption = document.createElement('option'))) 
      newOption.appendChild(document.createTextNode(img));
    list.insertBefore(newOption, list.options[i]);
  }
  list.options[i].value = img_id;
  
  var myimgs = eval("document.Mainform.cimages"+idx);
  myimgs.value = myimgs.value.replace(','+img_id, '');
}

function getColumn(name)
{ var tbl = document.getElementById("Maintable");
  var len = tbl.tHead.rows[1].cells.length;
  for(var i=0;i<len; i++)
  { if(tbl.tHead.rows[1].cells[i].firstChild.innerHTML == name)
      return i;
  }
}

var price_editable = false;
var priceVAT_editable = false;

function price_change(elt)
{ var tblEl = document.getElementById("offTblBdy");
  var price = elt.value;
  var thisrow = elt.name.substring(9);
  var VAT = document.Mainform.VAT_rate.value;
  var pvcol = getColumn("priceVAT");
  var newprice = price * (1 + (VAT / 100));
  newprice = newprice.toFixed(6);; /* round to 6 decimals */
  elt.parentNode.parentNode.cells[pvcol].innerHTML = newprice;
  if(document.Mainform.base_included.checked)
  { base_price = parseFloat(document.Mainform.base_price.value);
    price = price - base_price;
  }
  var pricefield = eval("document.Mainform.price"+thisrow);
  pricefield.value = price;
  reg_change(elt);
}

function priceVAT_change(elt)
{ var tblEl = document.getElementById("offTblBdy");
  var priceVAT = elt.value;
  var VAT = document.Mainform.VAT_rate.value;
  var thisrow = elt.name.substring(8);
  var pcol = getColumn("price");
  var newprice = priceVAT / (1 + (VAT / 100));
  if(document.Mainform.base_included.checked)
  { base_price = parseFloat(document.Mainform.base_price.value);
    newprice = newprice - base_price;
  }
  newprice = newprice.toFixed(6); /* round to 6 decimals */
  elt.parentNode.parentNode.cells[pcol].innerHTML = newprice;
  var pricefield = eval("document.Mainform.price"+thisrow);
  pricefield.value = newprice;
  reg_change(elt);
}

function RowSubmit(elt)
{ var subtbl = document.getElementById("subtable");
  var row = elt.parentNode.parentNode;
  subtbl.innerHTML = '<tr>'+row.innerHTML+'</tr>';
  // field contents are not automatically copied
  var inputs = row.getElementsByTagName('input');
  for(var k=0;k<inputs.length;k++)
  { if((inputs[k].name.substring(0,6) == "active") || (inputs[k].name.substring(0,7) == "default_on"))
	{ elt = document.rowform[inputs[k].name][0]; /* the trick with the hidden field works not with the rowsubmit so we delete it */
	  elt.parentNode.removeChild(elt);
	  continue;
	}
    else if(inputs[k].type != "button")
    { if(((inputs[k].name.substring(0,6) == "default_on")))
	  { document.rowform[inputs[k].name].type = "text";
	    if(!inputs[k].checked) document.rowform[inputs[k].name].value = "0"; /* value will initially always be "1" */
	  }
	  else	
	  {	document.rowform[inputs[k].name].value = inputs[k].value;
	  }
      var temp = document.rowform[inputs[k].name].name;
      temp = temp.replace(/[0-9]*$/, ""); /* chance "description1" into "description" */
      document.rowform[inputs[k].name].name = temp;
    }
  }
  rowform.verbose.value = subForm.verbose.checked;
  document.rowform['id_row'].value = row.childNodes[0].id;
  document.rowform.submit();
}

function default_change(elt)
{ var dfield;
  var eltnum = elt.name.substring(10);
  reccount = Mainform.reccount.value;
  if(!elt.checked)
    return;
  for(var i=0; i< reccount; i++)
  { if(i == eltnum)
	  continue;
    dfield = eval("document.Mainform.default_on"+i);
	if(!dfield) continue; 
//	alert("CCC"+document.Mainform.default_on2.checked);
	dfield = dfield[1];
	dfield.checked = false;

  }
  reg_change(elt);
}

var tabchanged = 0;
function reg_change(elt)	/* register changed row so that it will be colored and only changed rows will be submitted */
{ var elts = Array();
  elts[0] = elt;
  elts[1] = elts[0].parentNode;
  var i=1;
  while (elts[i] && (!elts[i].name || (elts[i].name != 'Mainform')))
  { elts[i+1] = elts[i].parentNode;
	i++;
  }
  elts[i-4].cells[0].setAttribute("changed", "1");
  elts[i-4].style.backgroundColor="#DDD";
  tabchanged = 1;
}

function reg_unchange(num)	/* change status of row back to unchanged after it has been submitted */
{ var elt = document.getElementById('trid'+num);
  var row = elt.parentNode;
  row.cells[0].setAttribute("changed", "0");
  row.style.backgroundColor="#AAF";
}

function switch_pricebase(elt)
{ var tbl = document.getElementById("Maintable");
  var len = tbl.tBodies[0].rows.length;
  var VAT = document.Mainform.VAT_rate.value;
  var base_price = parseFloat(document.Mainform.base_price.value);
  var pvcol = getColumn("priceVAT");
  var pcol = getColumn("price");
  var tbl = document.getElementById("Maintable"); 
  if(elt.checked == false) base_price = 0;
  for(var i=0;i<len; i++)
  { if(tbl.tBodies[0].rows[i].innerHTML == "") continue;
	var netprice = base_price + parseFloat(tbl.tBodies[0].rows[i].cells[1].childNodes[2].value);
	netprice = netprice.toFixed(6); 
	if(price_editable)
	   tbl.tBodies[0].rows[i].cells[pcol].childNodes[0].value = netprice;
	else
		tbl.tBodies[0].rows[i].cells[pcol].innerHTML = netprice;
	var VATprice = (netprice * (1 + VAT/100)).toFixed(2);
	if(priceVAT_editable)
	   tbl.tBodies[0].rows[i].cells[pvcol].childNodes[0].value = VATprice;
	else
		tbl.tBodies[0].rows[i].cells[pvcol].innerHTML = VATprice;
  } 
}

var rowsremoved = 0;
function RemoveRow(row)
{ var tblEl = document.getElementById("offTblBdy");
  var trow = document.getElementById("trid"+row).parentNode;
  trow.innerHTML = "<td></td>";
  rowsremoved++;
}

function SubmitForm()
{ if(subForm.verbose.checked)
    Mainform.verbose.value = "true";
  Mainform.action = 'combi-proc.php';
  Mainform.submit();
}

function init()
{ for(i=0; i<silencers.length; i++)
    switchDisplay('offTblBdy',null,silencers[i], 0);
}

</script>
</head><body onload="init()">
<?php print_menubar(); ?>
<table width="100%"><tr><td colspan=2>
<h1>Product combination edit</h1></td>
<td width="50%" align=right rowspan=2><iframe name="tank" height="95" width="230"></iframe></td>
</tr><tr><td width="20%">
<?php
  echo "<b>".$error."</b>";
  echo "<form name=prodform action='combi-edit.php' method=get><table><tr><td>Product id: </td><td><input name=id_product value='".$id_product."' size=3></td></tr>";
  echo '<tr><td>Language: </td><td><select name="id_lang">';
	  $query="select * from ". _DB_PREFIX_."lang ";
	  $res=dbquery($query);
	  while ($language=mysqli_fetch_array($res)) {
		$selected='';
	  	if ($language['id_lang']==$id_lang) $selected=' selected="selected" ';
	        echo '<option  value="'.$language['id_lang'].'" '.$selected.'>'.$language['name'].'</option>';
	  }
  echo '</select></td></tr><tr><td>';
  
	echo 'shop: </td><td><select name="id_shop">';
	$query=" select id_shop,name from ". _DB_PREFIX_."shop ORDER BY id_shop";
	$res=dbquery($query);
	while ($shop=mysqli_fetch_array($res)) {
		if ($shop['id_shop']==$id_shop) {$selected=' selected="selected" ';} else $selected="";
	        echo '<option  value="'.$shop['id_shop'].'" '.$selected.'>'.$shop['id_shop']."-".$shop['name'].'</option>';
	}	
	echo '</select></td></tr></table></td><td><p><input type=submit></td></tr></table>';
  echo "</form>";
  
  if($error != "")
  { echo "</body></html>";
    return;
  }
  
  $squery = "SELECT depends_on_stock FROM ". _DB_PREFIX_."stock_available WHERE id_product='".$id_product."' AND id_product_attribute=0";
  $sres=dbquery($squery);
  $srow=mysqli_fetch_array($sres);
    
  $aquery = "SELECT ps.*, pa.reference, pa.supplier_reference,pa.location,pa.ean13,pa.upc,s.quantity, s.depends_on_stock, pi.id_image, il.legend, GROUP_CONCAT(CONCAT(pi.id_image)) AS images";
  $aquery .= " from ". _DB_PREFIX_."product_attribute pa";
  $aquery .= " LEFT JOIN ". _DB_PREFIX_."product_attribute_shop ps on pa.id_product_attribute=ps.id_product_attribute AND ps.id_shop='".$id_shop."'";
  $aquery .= " LEFT JOIN ". _DB_PREFIX_."product_attribute_image pi on pa.id_product_attribute=pi.id_product_attribute";
  $aquery .= " LEFT JOIN ". _DB_PREFIX_."image_lang il on il.id_image=pi.id_image AND il.id_lang='".$id_lang."'";
if($share_stock == 0)
  $aquery .=" left join ". _DB_PREFIX_."stock_available s on s.id_product_attribute=pa.id_product_attribute AND s.id_shop = '".$id_shop."'";
else
  $aquery .=" left join ". _DB_PREFIX_."stock_available s on s.id_product_attribute=pa.id_product_attribute AND s.id_shop_group = '".$id_shop_group."'";
  $aquery .= " WHERE pa.id_product='".$id_product."' GROUP BY ps.id_product_attribute ORDER BY pa.id_product_attribute";
  $ares=dbquery($aquery);
  $numrecs = mysqli_num_rows($ares);
  
  $doublepresent = false;
  while ($row=mysqli_fetch_array($ares))
  { if(strpos($row["images"], ",") > 0)
    { $doublepresent = true;
	  break;
	}
  }
  mysqli_data_seek($ares, 0);
  
  echo '<hr>Note that if you want to edit the images you should first <a href="image-edit.php?id_product='.$id_product.'&id_shop='.$id_shop.'">assign legends</a> to your images!';

  echo '<form name=ListForm><table border=1 class="switchtab" style="empty-cells: show;"><tr><td>&nbsp;<br>Hide<br>Show<br>Edit<br><font size=-2>multi-<br>image</font></td>';
  for($i=2; $i< sizeof($combifields); $i++)
  { $checked0 = $checked1 = $checked2 = "";
    if($combifields[$i][2] == 0) $checked0 = "checked"; 
    if($combifields[$i][2] == 1) $checked1 = "checked"; 
	$j = $i+1;
    echo '<td>'.$combifields[$i][0].'<br>';
    echo '<input type="radio" name="disp'.$j.'" id="disp'.$j.'_off" value="0" '.$checked0.' onClick="switchDisplay(\'offTblBdy\', this,'.$j.',0)" /><br>';
    echo '<input type="radio" name="disp'.$j.'" id="disp'.$j.'_on" value="1" '.$checked1.' onClick="switchDisplay(\'offTblBdy\', this,'.$j.',1)" /><br>';
	if(($combifields[$i][0] != "image") || (!$doublepresent))
      echo '<input type="radio" name="disp'.$j.'" id="disp'.$j.'_edit" value="2" '.$checked2.' onClick="switchDisplay(\'offTblBdy\', this,'.$j.',2)" /><br>';
	else
	  echo '&nbsp;<br>';
	if($combifields[$i][0] == "image")
		echo '<input type="radio" name="disp'.$j.'" id="disp'.$j.'_multi" value="3" '.$checked2.' onClick="switchDisplay(\'offTblBdy\', this,'.$j.',3)" />';
    else
	   echo '&nbsp;';
	echo "</td>";
  }
  echo "</tr></table></form>";
  
  echo '<table><tr><td colspan="5" align="right"><form name=subForm><input type="checkbox" name="verbose">verbose &nbsp; <input type="button" value="Submit all" onclick="SubmitForm(); return false;"></form></td></tr><tr><td>';
  
  echo '<form name="Mainform" method=post><input type=hidden name=reccount value="'.$numrecs.'"><input type=hidden name=id_lang value="'.$id_lang.'">';
  echo '<input type=hidden name=id_shop value='.$id_shop.'><input type=hidden name=id_product value="'.$id_product.'">';
  echo '<input type=hidden name=VAT_rate value="'.$VAT_rate.'">';
  echo '<input type=hidden name=base_price value="'.$product_price.'">';
  echo '<input type=hidden name=verbose>';
  echo '<div id="testdiv"><table id="Maintable" name="Maintable" border=1 style="empty-cells:show"><colgroup id="mycolgroup">';
  for($i=0; $i<$numfields; $i++)
  { $align = "";
    if($combifields[$i][1]==RIGHT)
      $align = 'text-align:right;';
    echo "<col id='col".$i."' style='".$align."'></col>";
  }

  echo "</colgroup><thead><tr><th colspan='".($numfields+1)."' style='font-weight: normal;'>";
  echo mysqli_num_rows($ares)." combinations for ".$id_product." (<b>".$product_name."</b>) - ".round($product_price,2)."(+".($VAT_rate+0)."%) ".round(($product_price*(100+$VAT_rate)/100),2)." &nbsp; &nbsp; <input type=checkbox name='base_included' onclick='switch_pricebase(this)'> include baseprice<br/><span id='warning' style='background-color: #FFAAAA'></span></th></tr><tr><th><b></b></th>";

  for($i=0; $i<$numfields; $i++)
  { if($i==0)
      $fieldname = "id";
	else 
	  $fieldname = $combifields[$i][0];
    echo '<th><a href="" onclick="this.blur(); return sortTable(\'offTblBdy\', '.($i+1).', false);" title="'.$combifields[$i][0].'">'.$fieldname.'</a></th
>';
  }

  echo '<th><a href="" onclick="this.blur(); return upsideDown(\'offTblBdy\');" title="Upside down: reverse table order"><img src="upsidedown.jpg"></a></th>';
  echo "</tr></thead><tbody id='offTblBdy'>"; /* end of header */
  $x=0;
  $lastgroup = "";
  
  while ($row=mysqli_fetch_array($ares))
  { echo "<tr
  >";
    for($i=0; $i< sizeof($combifields); $i++)
	{   if($combifields[$i][0] == "id_product_attribute")
		{ echo '<td id="trid'.$x.'" changed="0"><input type="button" value="X" style="width:4px" onclick="RemoveRow('.$x.')" title="Hide line from display" /></td>';
		  echo "<td><input type=hidden name=id_product_attribute".$x." value='".$row['id_product_attribute']."'>".$row['id_product_attribute'];
		  echo "<input type=hidden name=price".$x." value='".$row['price']."'></td>";
		}
		else if($combifields[$i][0] == "name")
		{ $paquery = "SELECT GROUP_CONCAT(CONCAT(gl.name,': ',l.name)) AS nameblock from ". _DB_PREFIX_."product_attribute pa";
		  $paquery .= " LEFT JOIN ". _DB_PREFIX_."product_attribute_combination c on pa.id_product_attribute=c.id_product_attribute";
		  $paquery .= " LEFT JOIN ". _DB_PREFIX_."attribute a on a.id_attribute=c.id_attribute";
		  $paquery .= " LEFT JOIN ". _DB_PREFIX_."attribute_lang l on l.id_attribute=c.id_attribute AND l.id_lang='".$id_lang."'";
		  $paquery .= " LEFT JOIN ". _DB_PREFIX_."attribute_group_lang gl on gl.id_attribute_group=a.id_attribute_group AND gl.id_lang='".$id_lang."'";
		  $paquery .= " WHERE pa.id_product_attribute='".$row['id_product_attribute']."' GROUP BY pa.id_product_attribute";
		  $pares=dbquery($paquery);
		  $parow = mysqli_fetch_array($pares);
		  $labels = explode(",", $parow['nameblock']);
		  sort($labels);
		  echo "<td>";
		  foreach($labels AS $label)
		    echo $label."<br>";
		  echo "</td>";
		}
		else if($combifields[$i][0] == "price")
		  echo "<td>".$row['price']."</td>";
		else if($combifields[$i][0] == "priceVAT")
		  echo "<td>".round(($row['price']*(100+$VAT_rate)/100),2)."</td>";
		else if($combifields[$i][0] == "weight")
		  echo "<td>".$row['weight']."</td>";
		else if($combifields[$i][0] == "wholesale_price")
		  echo "<td>".$row['wholesale_price']."</td>";
		else if($combifields[$i][0] == "ecotax")
		  echo "<td>".$row['ecotax']."</td>";
		else if($combifields[$i][0] == "unit_price_impact")
		  echo "<td>".$row['unit_price_impact']."</td>";
		else if($combifields[$i][0] == "default_on")
		  echo "<td>".$row['default_on']."</td>";
		else if($combifields[$i][0] == "minimal_quantity")
		  echo "<td>".$row['minimal_quantity']."</td>";
		else if($combifields[$i][0] == "available_date")
		  echo "<td>".$row['available_date']."</td>";
		else if($combifields[$i][0] == "quantity")
		{ if($srow["depends_on_stock"] == "1")
            echo '<td style="background-color:yellow">'.$row['quantity'].'</td>';	
		  else
		    echo "<td>".$row['quantity']."</td>";
		}
		/* below the ps_product_attribute fields */
		else if($combifields[$i][0] == "reference")
		  echo "<td>".$row['reference']."</td>";
		else if($combifields[$i][0] == "supplier_reference")
		  echo "<td>".$row['supplier_reference']."</td>";
		else if($combifields[$i][0] == "location")
		  echo "<td>".$row['location']."</td>";
		else if($combifields[$i][0] == "ean")
		  echo "<td>".$row['ean13']."</td>";
		else if($combifields[$i][0] == "upc")
		  echo "<td>".$row['upc']."</td>";
		else if($combifields[$i][0] == "quantity")
		  echo "<td>".$row['quantity']."</td>";
		/* image */
		else if($combifields[$i][0] == "image")
		{ if(($row["id_image"] == "") || ($row["id_image"] == "0"))
		    echo "<td>X</td>";
		  else
		  { $images = explode(",",$row["images"]);
		    echo "<td>";
		    foreach($images AS $id_image)
			{ get_image_extension($id_image, "product");
			  $imglink = $triplepath.'img/p'.getpath($id_image).'/'.$id_image;
			  echo "<a href='".$imglink.".jpg' title='".$row['legend']."'><img src='".$imglink.$selected_img_extension."'></a>";
			}
			echo "</td>";
		  }
		}
		else 
		   echo "<td>".$row[$combifields[$i][0]]."</td>";
	}
    echo '<td><img src="enter.png" title="submit row '.$x.'" onclick="RowSubmit(this)"></td>';
    $x++;
	echo "</tr>";
  }
  echo '</form></table></td></tr></table>
	<div style="display:block;"><form name=rowform action="combi-proc.php" method=post target=tank><table id=subtable></table>
	<input type=hidden name=id_row><input type=hidden name=id_lang value="'.$id_lang.'"><input type=hidden name=id_product value="'.$id_product.'">
	<input type=hidden name=id_shop value="'.$id_shop.'"><input type=hidden name=verbose></form></div>';
  
  include "footer.php";
?>
</body>
</html>