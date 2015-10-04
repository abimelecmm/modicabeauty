<?php
if(!@include 'approve.php') die( "approve.php was not found!");
if(isset($_GET['id_product']))
  $id_product = intval($_GET['id_product']);
else 
  $id_product = "";
$product_name = "";

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
if($id_product != "")
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Prestashop Product Image Multiedit</title>
<link rel="stylesheet" href="style1.css" type="text/css" />
<style>
input.posita {width: 50px; text-align:right}
</style>
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<script type="text/javascript">
parts_stat = 0;
desc_stat = 0;
function switchDisplay(id, elt, fieldno, val)  // collapse(field)
{ var tmp, tmp2, val, checked;
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
    for(var i=0; i<tblEl.rows.length; i++)
    { tmp = tblEl.rows[i].cells[fieldno].innerHTML;
      tmp2 = tmp.replace("'","\'");
      row = tblEl.rows[i].cells[0].childNodes[0].name.substring(8); /* fieldname id_image7 => 7 */
	  if(field=="cover")
	  { if(tmp==1) checked="checked"; else checked="";
	    tblEl.rows[i].cells[fieldno].innerHTML = '<input type=hidden name="'+field+row+'" id="'+field+row+'" value="0" /><input type=checkbox name="'+field+row+'" id="'+field+row+'" onchange="set_cover('+row+');" value="1" '+checked+' />';
	  }
	  else if(field == "position")
		tblEl.rows[i].cells[fieldno].innerHTML = '<input name="'+field+row+'" value="'+tmp2+'" class="posita" />';
	  else			/* legend */
		tblEl.rows[i].cells[fieldno].innerHTML = '<input name="'+field+row+'" value="'+tmp2+'" size=40 />';
	
		
    }
    tmp = elt.parentElement.innerHTML;
    tmp = tmp.replace(/<br.*$/,'');
    elt.parentElement.innerHTML = tmp+"<br><br>Edit";
  }
  return;
}

function getColumn(name)
{ var tbl = document.getElementById("Maintable");
  var len = tbl.tHead.rows[1].cells.length;
  for(var i=0;i<len; i++)
  { if(tbl.tHead.rows[1].cells[i].firstChild.innerHTML == name)
      return i;
  }
}

function set_cover(row)
{ tblEl = document.getElementById('offTblBdy');
  for(var i=0; i<tblEl.rows.length; i++)
  { rownum = tblEl.rows[i].cells[0].childNodes[0].name.substring(8); 
    if(rownum == row)
	  tblEl.rows[i].cells[2].childNodes[1].checked = true;
	else
	  tblEl.rows[i].cells[2].childNodes[1].checked = false;	
  } 
}

function CatSort()
{ sortTable('offTblBdy', 1, 2);
  CatNumber();
}

function CatNumber()
{ rv = document.getElementsByClassName('posita');
  var length = rv.length;
  for(var i=0; i<length; i++)
  { rv[i].value = i+1;
  }
}

	function changeMAfield()
	{ var base = eval("document.massform.action");
	  var action = base.options[base.selectedIndex].text;
	  var muspan = document.getElementById("muval");
	  if (action == "replace") muspan.innerHTML = "old: <input name=\"oldval\"> new: <input name=\"myvalue\">";
	  else muspan.innerHTML = "Value: <input name=\"myvalue\">";
	}

	  function massUpdate()
	  { var i, tmp, base, changed;
		base = eval("document.massform.action");
		action = base.options[base.selectedIndex].text;
		if(action.substr(1,8) == "elect an") { alert("You must select an action!"); return;}
		myval = document.massform.myvalue.value;
		for(i=0; i < numrecs; i++) 
		{ 	changed = false;
			field = eval("document.Mainform.legend"+i);
			if(!field) continue;
			if(action == "insert before")
			{	myval2 = myval+field.value;
				changed = true;
			}
			else if(action == "insert after")
			{	myval2 = field.value+myval;
				changed = true;
			}
			else if(action == "replace")
			{	src = document.massform.oldval.value;
				evax = new RegExp(src,"g");
				oldvalue = field.value;
				myval2 = field.value.replace(evax, myval);
				if(oldvalue != myval2)
				  changed = true;
			}
			else myval2 = myval;

			oldvalue = field.value;
			field.value = myval2;
			if(oldvalue != myval2) changed = true;

		}
	  }


function SubmitForm()
{ reccount = ".$numrecs.";
  CatSort();
//  Mainform.verbose.value = ListForm.verbose.checked;
  Mainform.action = 'image-proc.php';
  Mainform.submit();
}

</script>
</head><body>
<?php print_menubar(); ?>
<table width="100%"><tr><td colspan=2>
<h1>Product Image Edit</h1></td>
<td width="50%" align=right rowspan=2><iframe name="tank" height="95" width="230"></iframe></td>
</tr><tr><td width="20%">
<?php
  echo "<b>".$error."</b>";
  echo "<form name=prodform action='image-edit.php' method=get><table><tr><td>Product id: </td><td><input name=id_product value='".$id_product."' size=3></td></tr>";
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
  
  define("LEFT", 0); define("RIGHT", 1); // align
  define("HIDE", 0); define("SHOW", 1); // hide by default?
  $imgfields = array(
    array("id_image",RIGHT,SHOW),
	array("position", RIGHT,SHOW),
	array("cover",RIGHT,SHOW),
	array("legend",RIGHT,SHOW),
	array("image",RIGHT,SHOW));
  $numfields = sizeof($imgfields); /* number of fields */
  
	echo '<hr/><div style="background-color:#CCCCCC">Mass update for Legend field<form name="massform" onsubmit="massUpdate(); return false;">';
	echo '<select name="action" onchange="changeMAfield()" style="width:120px"><option>Select an action</option>';
	echo '<option>set</option>';
	echo '<option>insert before</option>';
	echo '<option>insert after</option>';
	echo '<option>replace</option>';
	echo '</select>';
	echo '&nbsp; <span id="muval">value: <input name="myvalue"></span>';
	echo ' &nbsp; &nbsp; <input type="submit" value="update all editable records"></form>';
	echo 'NB: Prior to mass update you need to make the field editable. Afterwards you need to submit the records.';
	echo '</div><hr/>';
  
  
  echo "<hr>";

  echo '<form name=ListForm><table border=1 class="switchtab" style="empty-cells: show;"><tr><td><br>Hide<br>Show<br>Edit</td>';
  for($i=1; $i< sizeof($imgfields); $i++)
  { $checked0 = $checked1 = $checked2 = "";
    if($imgfields[$i][2] == 0) $checked0 = "checked"; 
    if($imgfields[$i][2] == 1) $checked1 = "checked"; 
	$j = $i;
    echo '<td>'.$imgfields[$i][0].'<br>';
    echo '<input type="radio" name="disp'.$j.'" id="disp'.$j.'_off" value="0" '.$checked0.' onClick="switchDisplay(\'offTblBdy\', this,'.$j.',0)" /><br>';
    echo '<input type="radio" name="disp'.$j.'" id="disp'.$j.'_on" value="1" '.$checked1.' onClick="switchDisplay(\'offTblBdy\', this,'.$j.',1)" /><br>';
    if(($imgfields[$i][0]!="image") && ($imgfields[$i][0]!="default_on"))
      echo '<input type="radio" name="disp'.$j.'" id="disp'.$j.'_edit" value="2" '.$checked2.' onClick="switchDisplay(\'offTblBdy\', this,'.$j.',2)" />';
    else
      echo "&nbsp;";
    echo "</td>";
  }
  echo "</tr></table></form>";
  
  
  $aquery = "select i.id_image, position, cover,legend from ". _DB_PREFIX_."image i";
  $aquery .= " left join ". _DB_PREFIX_."image_lang il ON i.id_image=il.id_image AND il.id_lang='".$id_lang."'";
  $aquery .= " WHERE i.id_product='".$id_product."'";
  $ares=dbquery($aquery);
  if(mysqli_num_rows($ares) == 0)
    $error = $id_product." has no images";

  $numrecs = mysqli_num_rows($ares);
  
  echo '<script>var numrecs='.$numrecs.';</script>'; 
  echo '<form name="Mainform" method=post ><input type=hidden name=reccount value="'.$numrecs.'"><input type=hidden name=id_lang value="'.$id_lang.'">';
  echo '<input type=hidden name=id_shop value='.$id_shop.'><input type=hidden name=id_product value="'.$id_product.'">';
  echo '<input type=hidden name=verbose>';

  echo "<table celpadding=0 cellspacing=0><tr><td colspan=5 align='right'><input type=checkbox name=verbose>verbose &nbsp; <input type=button value='Submit all' onClick='return SubmitForm();'></td></tr><tr><td>";
  echo '<div id="testdiv"><table id="Maintable" name="Maintable" border=1 style="empty-cells:show"><colgroup id="mycolgroup">';
  for($i=0; $i<$numfields; $i++)
  { $align = "";
    if($imgfields[$i][1]==RIGHT)
      $align = 'text-align:right;';
    echo "<col id='col".$i."' style='".$align."'></col>";
  }

  echo "</colgroup><thead><tr><th colspan='".($numfields-1)."' style='font-weight: normal;'>";
  echo mysqli_num_rows($ares)." images for ".$id_product." (".$product_name.")</th>";
  echo '<th><a href="" onclick="this.blur(); return upsideDown(\'offTblBdy\');" title="Upside down: reverse table order"><img src="upsidedown.jpg"></a></th>';
  echo '</tr><tr>';

  for($i=0; $i<$numfields; $i++)
  { if($i==0)
      $fieldname = "id";
	else 
	  $fieldname = $imgfields[$i][0];
	echo '<th><a href="" onclick="this.blur(); return sortTable(\'offTblBdy\', '.($i).', false);" title="'.$imgfields[$i][0].'">'.$fieldname.'</a></th
>';
  }

  echo "</tr></thead><tbody id='offTblBdy'>"; /* end of header */
  $x=0;
  $lastgroup = "";
  
  while ($row=mysqli_fetch_array($ares))
  { echo "<tr>";
    for($i=0; $i< sizeof($imgfields); $i++)
	{   if($imgfields[$i][0] == "id_image")
		  echo "<td><input type=hidden name='id_image".$x."' value='".$row['id_image']."'>".$row['id_image']."</td>";
		else if($imgfields[$i][0] == "position")
		  echo "<td>".$row['position']."</td>";
		else if($imgfields[$i][0] == "cover")
		  echo "<td>".$row['cover']."</td>";
		else if($imgfields[$i][0] == "legend")
		  echo "<td>".$row['legend']."</td>";
		else if($imgfields[$i][0] == "image")
		  echo "<td>".get_product_image($row['id_image'],$row['id_image'])."</td>";
		else 
		   echo "<td>".$row[$imgfields[$i][0]]."</td>";
	}
    $x++;
	echo "</tr>";
  }
  echo '</form></table></td></tr></table>';
  
  include "footer.php";
?>
</body>
</html>